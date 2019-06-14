<?php
namespace WebEngine\Plugin;

class Backoffice {
  private $_machine;
  private $_config;
  private $App;
  private $DB;
  
  private $_table_template = '
    <table data-table="{{TABLE}}" data-field_id="{{FIELD_ID}}">
      <thead>
        <tr>
          {{HEADER_CELLS}}
          <th>Azioni</th>
        </tr>
      </thead>
      <tbody>
        {{ROWS}}
      </tbody>
    </table>
  ';
  
  private $_row_header_template = '
    <th>
      {{COLUMN_NAME}}
    </th>
  ';
  
  private $_row_template = '
    <tr data-id="{{ID_VALUE}}" class="active_{{ACTIVE}}">
      {{CELLS}}
      <td style="white-space: nowrap;"><a href="{{EDIT_RECORD_LINK}}">modifica</a> | <a onclick="return confirm(\'Confermi la cancellazione del record?\');" href="{{DELETE_RECORD_LINK}}">elimina</a></td>
    </tr>
  ';
  
  private $_row_template_noactions = '
    <tr data-id="{{ID_VALUE}}" class="active_{{ACTIVE}}">
      {{CELLS}}
      <td>&nbsp;</td>
    </tr>
  ';
  
  private $_cell_template = '
    <td data-field="{{FIELD_NAME}}">
      {{FIELD_VALUE}}
    </td>
  ';
  
  public function __construct($machine) 
  {
    $this->_machine = $machine;
    $this->_config = [];
    $this->App = $machine->plugin("App");
    $this->DB = $machine->plugin("DB");
  }
  
  public function loadConfig($path)
  {
    if (file_exists($path)) {
      $this->_config = json_decode(file_get_contents($path), true);
    } else {
      die("unable to load backoffice config file.");
    }
  }
  
  public function upload($FILE, $table, $id) {
    $Upload = $this->_machine->plugin("UploadS3");
    $Upload->add_date_to_uploadpath = false;
    
    // build the upload path and upload
    /*
    $itemtype = "images";
    $idstr = str_pad($id, 9, "0", STR_PAD_LEFT);
    $dir1 = substr($idstr, 0, 3);
    $dir2 = substr($idstr, 3, 3);
    $dir3 = substr($idstr, 6, 3);
    $uploadpath = "uploads/$table/$itemtype/$dir1/$dir2/$dir3/original/";
    */
    $uploadpath = "uploads/$table/original/";
    $Upload->setUploadPath($uploadpath);
    $result = $Upload->upload($FILE);  

    return $result;
  }
  
  /**
   *  return the fields to show in the backoffice list for the table,
   *  as defined in config.
   */
  public function getFeaturedFields($table)
  {
    return isset($this->_config[$table]["featured"]) 
      ? $this->_config[$table]["featured"] 
      : ["*"];
  }
  
  public function getFieldId($table)
  {
    return $this->_config[$table]["field_id"];
  }
  
  public function getOrderByField($table)
  {
    return isset($this->_config[$table]["orderBy"])
      ? $this->_config[$table]["orderBy"]
      : $this->getFieldId($table);
  }
  
  public function getSiteField($table) {
    return isset($this->_config[$table]["sitefield"])
      ? $this->_config[$table]["sitefield"]
      : "";
  }
  
  public function getJoinPart($table) {
    return isset($this->_config[$table]["joinpart"]) ? $this->_config[$table]["joinpart"] : "";
  }
  
  public function getForeignKey($table) {
    return isset($this->_config[$table]["foreignkey"]) ? $this->_config[$table]["foreignkey"] : "";
  }

  public function getForeignTable($table) {
    return isset($this->_config[$table]["foreigntable"]) ? $this->_config[$table]["foreigntable"] : "";
  }
  
  public function getExternOrphans($table) {
    $result = [];
    
    $foreignkey = $this->_config[$table]["foreignkey"];
    $foreigntable = $this->_config[$table]["foreigntable"];
    
    $query = "
      SELECT 
        id, logic_title, $foreignkey from $foreigntable
      LEFT JOIN
        (
          select $foreignkey from $table 
          where site_id = ?
        ) AS B
      ON $foreigntable.id = B.$foreignkey
      WHERE B.$foreignkey IS NULL
      order by logic_title
    ";

    $result = $this->DB->select($query, [$this->DB->getSite()]);
    return $result;
  }
  
  // ==========================================================================
  //  funzioni per la lista dei record
  // ==========================================================================
  
  /**
   *  Ritorna l'HTML completo per la tabella che lista tutti i record.
   *
   *  @param Array $opts
   *    "table" String The table name
   *    "field_id" String The field id name
   *    "orderby" String The orderBy condition as defined in backoffice.json
   *    "fields_list" Array An array of field names
   *    "edit_page" String The edit page url
   */
  public function getTableHtml($opts)
  {
    $html = "";
    
    $table = $opts["table"];
    $data = $this->getTableListData(
      $table, 
      $opts["fields_list"],
      $opts["orderby"]
    );
    
    $fields_names = array_keys($data[0]);
    
    // get labels from config
    $fields_names = array_map(function($fieldname) use ($table) {
      return $this->_getFieldLabel($table, $fieldname);
    }, $fields_names);
    
    $html = $this->_machine->populateTemplate(
      $this->_table_template,
      [
        "TABLE" => $opts["table"],
        "FIELD_ID" => $this->getFieldId($opts["table"]),
        "HEADER_CELLS" => $this->_getHeaderCellsHtml($fields_names),
        "ROWS" => $this->_getRowsHtml($data, $opts)
      ]
    );
    
    return $html;
  }
  
  private function _getFieldLabel($table, $field)
  {
    if (
      isset($this->_config[$table])
      && isset($this->_config[$table]["fields"])
    ) {
      foreach ($this->_config[$table]["fields"] as $f) {
        if (isset($f["name"]) && $f["name"] == $field) {
          if (isset($f["label"])) {
            return $f["label"];
          }
        }
      }
    }
    return $field;
  }
  
  /**
   *  Ritorna l'HTML di un singolo record
   *
   *  Funzione utile per l'update dopo la chiamata ajax
   */
  public function getRecordHtml($table, $id, $edit_page) {
    if ($table == "location_visibilities") {
      return $this->getRecordLocationVisibilitiesHtml($table, $id);
    }
    $html = "";
    
    $data = $this->getTableListData(
      $table,
      $this->_config[$table]["featured"],
      $this->_config[$table]["orderBy"],
      $id
    );
    
    $opts = [
      "table" => $table,
      "field_id" => $this->_config[$table]["field_id"],
      "edit_page" => $edit_page
    ];
    $html = $this->_getRowsHtml($data, $opts);
    
    return $html;
  }
  
  public function getRecordLocationVisibilitiesHtml($table, $id) {
    $html = '';
    
    $result = $this->getRecordData([
      "table" => $table,
      "id" => $id,
      "field_id" => "id"
    ]);

    $result_loc = $this->getrecordData([
      "table" => "locations",
      "id" => $result[0]["location_id"],
      "field_id" => "id"
    ]);
    
    $levels = [
      "3" => "Platinum",
      "2" => "Gold",
      "1" => "Silver",
      "0" => "Free"
    ];        
    $level_select = $this->_getHtmlSelect($levels, $result[0]["level"]);
    
    $expire_select_value = $this->expireSelectValue($result[0]["expire_at"]);
    $expire_select = $this->_getHtmlSelect([
      date("Y-m-d") => "scade il",
      "2100-01-01" => "illimitato",
      "2000-01-01" => "disabilitato"
    ], $expire_select_value);

    $expire_datefield = "";
    if ($expire_select_value != "2100-01-01" && $expire_select_value != "2000-01-01") {
      $expire_datefield = '<input class="backoffice-datepicker" value="' . $result[0]["expire_at"] . '">';
    }
    
    $html .= '<td>' . $result_loc[0]["title"] . '</td>';
    $html .= '<td data-field="level">' . $level_select . '</td>';
    $html .= '<td style="white-space:nowrap;" data-field="expire_at">' . $expire_select . $expire_datefield . '</td>';
    return '<tr data-id="' . $result[0]["id"] . '">' . $html . '</tr>';
    
    return $html;
  }
  
  /**
   *  Ritorna il set di dati da visualizzare
   */
  public function getTableListData($table, $fields=[], $orderby="", $id=NULL, $offset=NULL)
  {
    if ($table == "location_visibilities") {
      $query = "
        SELECT 
          location_visibilities.id as id, type, locations.title as title, level, typos.logic_title as typotitle, zones.title as zonetitle, expire_at
        FROM
          location_visibilities
        LEFT JOIN locations
          ON location_visibilities.location_id = locations.id
        LEFT JOIN typos
          ON location_visibilities.typo_id = typos.id
        LEFT JOIN zones
          ON location_visibilities.zone_id = zones.id
        WHERE 
          location_visibilities.site_id = ?
          AND expire_at <> '2100-01-01'
          AND expire_at BETWEEN 
            DATE_ADD(NOW(), INTERVAL -2 MONTH)
            AND DATE_ADD(NOW(), INTERVAL 2 MONTH)    
      ";
      $data[] = $this->DB->getSite(); 
      $result = $this->DB->select($query, $data);
      return $result;
    }
    
    $data = [];
    
    $fields_str = count($fields) == 0
      ? "*"
      : $fields_str = implode(",", $fields);

    $wherePart = "";
    if (isset($this->_config[$table]["sitefield"])) {
      $wherePart = "WHERE " . $this->_config[$table]["sitefield"] . " = ?";
      $data[] = $this->DB->getSite();   
    }
    
    if (isset($this->_config[$table]["wherepart"])) {
      $wherePart .= " " . $this->_config[$table]["wherepart"];
    }
    
    if (!is_null($id)) {
      if ($wherePart != "") {
        $wherePart .= " AND ";
      } else {
        $wherePart .= " WHERE ";
      }
      $wherePart .= $table . "." . $this->_config[$table]["field_id"] . " = ?";
      $data[] = $id;
    }
    
    if ($table == "events" && !isset($_GET["all"])) {
      if ($wherePart != "") {
        $wherePart .= " AND ";
      } else {
        $wherePart .= " WHERE ";
      }
      $wherePart .= "events.time_to > DATE_ADD(NOW(), INTERVAL -6 MONTH)";
    }
    
    $activeCondition = isset($this->_config[$table]["activeCondition"])
      ? ", " . $this->_config[$table]["activeCondition"] . " AS activeview"
      : "";

    if (isset($this->_config[$table]["joinpart"])) {
      $fields_str = $table . "." . $fields_str;
      $table = $table . " " . $this->_config[$table]["joinpart"];
    }
    $offset = $offset == null ? 0 : $offset;
    $query = "SELECT $fields_str $activeCondition FROM $table $wherePart ORDER BY $orderby LIMIT $offset, 1000";

    $result = $this->DB->select($query, $data);

    return $result;
  }
  
  /**
   *  Ritorna l'HTML della sezione <thead>
   */
  private function _getHeaderCellsHtml($arr_fields_names)
  {
    $html = '';
    foreach ($arr_fields_names as $fieldname) {
      if ($fieldname != "activeview") {
        $html .= $this->_machine->populateTemplate(
          $this->_row_header_template,
          [
            "COLUMN_NAME" => $fieldname
          ]
        );
      }
    }
    return $html;
  }

  /**
   *  Ritorna l'HTML delle righe della tabella
   */  
  private function _getRowsHtml($rows, $opts)
  {
    $html = '';
    foreach ($rows as $row) {
      $html .= $this->_machine->populateTemplate(
        $opts["table"] == "location_visibilities"
          ? $this->_row_template_noactions
          : $this->_row_template,
        [
          "ACTIVE" => isset($row["activeview"]) ? $row["activeview"]
            : (isset($row["active"]) ? $row["active"] : false),
          "CELLS" => $this->_getCellsHtml($row, $opts),
          "ID_VALUE" => $row[$opts["field_id"]],
          "EDIT_RECORD_LINK" => $this->_machine->populateTemplate(
            $opts["edit_page"],
            [
              "id" => $row[$opts["field_id"]]
            ]
          ),
          "DELETE_RECORD_LINK" => $this->_machine->plugin("Link")->Get(["ADMIN_DELETE", $opts["table"], $row[$opts["field_id"]]])
        ]
      );
    }
    return $html;
  }
  
  /**
   *  Ritorna l'HTML delle celle di una riga
   */
  private function _getCellsHtml($row, $opts) {
    $html = '';
    foreach ($row as $fieldname => $fieldvalue) {
      if ($fieldname != "activeview") {
        $fieldType = $this->_getFormFieldType($opts["table"], $fieldname);
        if ($fieldType == "checkbox") {
          $fieldvalue = $this->_getHtmlFlag($fieldvalue);
        }
        if ($fieldType == "select") {
          $select_options = $this->_getSelectOptions($opts["table"], $fieldname);
          $fieldvalue = $this->_getHtmlSelect($select_options, $fieldvalue);
        }
        if ($fieldType == "image") {
          $fieldvalue = $this->_getHtmlImage(
            $opts["table"], 
            $row[$opts["field_id"]],
            $fieldname,
            $fieldvalue
          );
        }
        if ($fieldType == "date") {
          $fieldvalue = '<input class="backoffice-datepicker" value="' . $fieldvalue . '">';
        }
        if ($fieldType == "date_readonly") {
          $class = "";
          if (strtotime($fieldvalue) < time()) {
            $class = "red";
          }            
          $fieldvalue = '<span class="' . $class . '">' . date("d/m/Y", strtotime($fieldvalue)) . '</span>';
        }
        if ($fieldType == "time") {
          $fieldvalue = $this->getTimeFieldsHtml($fieldvalue);
        }
        if ($fieldType == "subscription") {
          $levels = [
            "3" => "Platinum",
            "2" => "Gold",
            "1" => "Silver",
            "0" => "Free"
          ];
          $fieldvalue = $levels[$fieldvalue];
        }
        if ($fieldType == "relation") {
          $relations = [
            "LocationVisibilityTypo" => "location tra tipologia",
            "LocationVisibilityZone" => "location tra zona"
          ];
          $fieldvalue = $relations[$fieldvalue];
        }
        if ($fieldType == "datetime") {
          $t = strtotime($fieldvalue);
          $minusdate = date("Y-m-d", strtotime("-7 days", $t));
          $plusdate = date("Y-m-d", strtotime("+7 days", $t));
          $fieldvalue = '<table><tr><td style="white-space:nowrap;">';
          $fieldvalue .= '<button class="newdatebutton minibutton" data-new="' . $minusdate . '">-7</button>';
          $fieldvalue .= '<input class="backoffice-datepicker" value="' . date("Y-m-d", $t) . '">';
          $fieldvalue .= '<button class="newdatebutton minibutton" data-new="' . $plusdate . '">+7</button>';
          $fieldvalue .= '</td><td>' . date("H:i", $t) . '</td></tr></table>';
        }
        $html .= $this->_machine->populateTemplate(
          $this->_cell_template,
          [
            "FIELD_NAME" => $fieldname,
            "FIELD_VALUE" => $fieldvalue == null ? "" : $fieldvalue
          ]
        );
      }
    }
    return $html;
  }
  
  /**
   *  Ritorna l'html del flag da visualizzare in lista
   */
  private function _getHtmlFlag($fieldvalue) {
    $datavalue = 0;
    if ($fieldvalue == 1 || $fieldvalue == true || $fieldvalue == "true") {
      $datavalue = 1;
    }
    return '<button type="button" class="flag" data-value="' . $datavalue . '"></button>';
  }
  
  public function getFieldOptions($table, $fieldname) {
    foreach ($this->_config[$table]["fields"] as $f) {
      if (isset($f["name"]) && $f["name"] == $fieldname) {
        return $f;
      }
    }
  }
  
  private function _getSelectOptions($table, $fieldname) {
    $fo = $this->getFieldOptions($table, $fieldname);
    
    if (isset($fo["options"])) {
      return $fo["options"];
    }    
    if (
      isset($fo["query"])
      && isset($fo["select_key"])
      && isset($fo["select_value"])
    ) {
      
      $options = [];
      $query = $fo["query"];
      $select_key = $fo["select_key"];
      $select_value = $fo["select_value"];
      $params = [$this->_machine->plugin("DB")->getSite()];
      $results = $this->_machine->plugin("DB")->select($query, $params);
      foreach ($results as $r) {
        $options[$r[$select_key]] = $r[$select_value];
      }
      return $options;
    }
  }
  
  private function _getHtmlSelect($options, $value) {
    $opts = '';
    foreach ($options as $k => $v) {
      if ($k == $value) {
        $opts .= '<option selected value="' . $k . '">' . $v . '</option>';
      } else {
        $opts .= '<option value="' . $k . '">' . $v . '</option>';
      }
    }
    $html = '<select class="select">' . $opts . '</select>';
    return $html;
  }
  
  private function _getHtmlImage($table, $id, $fieldname, $fieldvalue) { 
    $itemtype = "images";
    $thumbsize = "admin";
    //$src = $this->_machine->plugin("App")->getImgCdn($table, $id, $itemtype, $thumbsize, $fieldvalue);
    $src = $this->_machine->plugin("App")->getImg($table, $id, $itemtype, $fieldvalue, "W", 40);
    $img = '<a href="' . $this->_machine->plugin("Link")->Get(["ADMIN_RITAGLIO", $table, $id, $fieldname]) . '"><img src="' . $src . '" /></a>';
    
    // add mini-form for on-the-fly update
    $Form = $this->_machine->plugin("Form");
    $opts_form = [
      "action" => $this->_machine->plugin("Link")->Get(["UPLOAD", $table, $id, $fieldname]),
      "fields" => [
        [
          "", "image", [
          "name" => $fieldname . "_" . $id,
          "data-table" => $table,
          "data-id" => $id,
          "data-fieldname" => $fieldname,
          "data-fieldvalue" => $fieldvalue
        ]]
      ]
    ];
    $Form->addForm("imgform", $opts_form);
    
    return $img . $Form->Render(["imgform"]);
  }
  
  // ==========================================================================
  //  funzioni per il form di modifica dei dati
  // ==========================================================================
  
  public function getHtmlOrderList($endpoint, $extern_table, $field_id, $list) {
    $Backoffice = $this->_machine->plugin("Backoffice");
    return '
      <table 
          data-order_endpoint="' . $endpoint . '"
          class="sortable"
          data-table="' . $extern_table . '"
          data-field_id="' . $field_id . '"
        >
        <thead>
          <th>Location</th>
          <th>Livello</th>
          <th>Scadenza</th>
        </thead>
        <tbody>
          ' . implode("", array_map(function($item) use($Backoffice) {
            $levels = [
              "3" => "Platinum",
              "2" => "Gold",
              "1" => "Silver",
              "0" => "Free"
            ];
            $level_select = $Backoffice->_getHtmlSelect($levels, $item["level"]);
            $expire_select_value = $Backoffice->expireSelectValue($item["expire_at"]);
            $expire_select = $Backoffice->_getHtmlSelect([
              date("Y-m-d") => "scade il",
              "2100-01-01" => "illimitato",
              "2000-01-01" => "disabilitato"
            ], $expire_select_value);
            
            $expire_datefield = "";
            if ($expire_select_value != "2100-01-01" && $expire_select_value != "2000-01-01") {
              $expire_datefield = '<input class="backoffice-datepicker" value="' . $item["expire_at"] . '" />';
            }
            
            $tds = '';
            $tds .= '<td>' . $item["title"] . '</td>';
            $tds .= '<td style="width:160px;" data-field="level">' . $level_select . '</td>';
            $tds .= '<td style="white-space:nowrap;" data-field="expire_at">' . $expire_select . "<br>" . $expire_datefield . '</td>';
            return '<tr data-id="' . $item["main_id"] . '">' . $tds . '</tr>';
          }, $list)) . '
        </tbody>
      </table>
    ';
  }
  
  /**
   *  @param $opts [table, field_id, id]
   */
  public function getUpdateFormHtml($opts) {
    $App = $this->_machine->plugin("App");
    $Link = $this->_machine->plugin("Link");
    $DB = $this->_machine->plugin("DB");
    $Backoffice = $this;
    
    // parametri da url
    $table = $opts["table"];
    $id = $opts["id"];
    $field_id = $this->getFieldId($table);
    
    // record database
    $result = $this->getRecordData($opts);
    $row = $result[0];
    
    // lista di campi
    $fo = $this->_config[$table]["fields"];
    
    $html = '';
    $html .= '<form action="' . $Link->Get(['/admin/{table}/{id}', $table, $id]) . '" method="post" enctype="multipart/form-data" class="form_inner">';
    $html .= $this->getHtmlFromConfigFields($fo, $row, $opts);
    $html .= '  <div class="form_footer">';
    $html .= '    <button type="submit">Invia</button>';
    $html .= '  </div>';
    $html .= '</form>';
    
    // aggiunge eventualmente la lista delle scadenze dei locali
    $f = $this->_config[$table];
    if (isset($f["listalocali"]) && isset($f["listalocali"]["slugfield"])) {
      $item_id = $row[$f["listalocali"]["field_id"]];
      $slug = $row[$f["listalocali"]["slugfield"]];
      if ($table == "typo_btw_sites") {
        $list = $DB->getListCategoriaLocaliByTypoId($item_id, true, 1, true);
        $endpoint = $this->_machine->plugin("Link")->Get(["CATEGORIA_LOCALI_ORDERACTION", $item_id]);
      }
      /*if ($table == "zones") {
        $list = $DB->getListLocaliZona($item_id);
        $endpoint = $this->_machine->plugin("Link")->Get(["ZONA_ORDERACTION", $item_id]);
      }*/
      
      // indice zone
      list($z1, $z2) = $DB->getZonesListForCategoriaLocali($slug);
      $html .= '
        <div>
        Vai alle liste per zona: ' . implode(" - ", array_map(function($item) use($App, $Link, $item_id){
            $link = $Link->Get(['ADMIN_CAT_ZONA_ORDINAMENTO', $item_id, $item["id"]]);
            return '<a href="' . $link . '">' . $App->_zName($item) . '</a>'; 
          }, array_merge($z1, $z2)
        )) . ' 
        <br><br></div>
      ';
      
      $html .= $this->getHtmlOrderList($endpoint, $f["listalocali"]["extern_table"], $f["listalocali"]["field_id"], $list);
    }
    
    return $html;
  }
  
  
  public function getNewFormHtml($opts) {
    $table = $opts["table"];
    
    $row = [];
    
    // lista di campi
    $fo = $this->_config[$table]["fields"];
    
    // valori di default
    foreach ($fo as $f) {
      if (isset($f["name"])) {
        if ($f["name"] == "expire") {
          $row[$f["name"]] = date("Y-m-d", strtotime("+60 days"));
        }
        if ($f["name"] == "position") {
          $row[$f["name"]] = 0;
        }
        if ($f["name"] == "time_from") {
          $row[$f["name"]] = date("Y-m-d");
        }
        if ($f["name"] == "time_to") {
          $row[$f["name"]] = date("Y-m-d", strtotime("+2 month"));
        }
      }
    }
    
    $html = '';
    $html .= '<form action="' . $this->_machine->plugin("Link")->Get(['/admin/new/{table}', $table]) . '" method="post" enctype="multipart/form-data" class="form_inner">';
    if (isset($_GET["ext_id"]) && $_GET["ext_id"] != "0") {
      $html .= '<input type="hidden" name="ext_id" value="' . $_GET["ext_id"] . '">';
    }
    $html .= $this->getHtmlFromConfigFields($fo, $row, $opts);
    $html .= '  <div class="form_footer">';
    $html .= '    <button type="submit">Invia</button>';
    $html .= '  </div>';
    $html .= '</form>';
    return $html;
  }
  
  public function mysqlDateToShort($mysqldate) {
    $parts = explode("-", $mysqldate);
    return $parts[2] . "/" . $parts[1] . "/" . $parts[0];
  }
  
  public function expireSelectValue($mysqldate) {
    if ($mysqldate == "2100-01-01")
      return "2100-01-01";

    if ($mysqldate == "2000-01-01")
      return "2000-01-01";
    
    return "";
  }
  
  private function getHtmlForFormField($label, $fieldHtml) {
    $html = '';
    $html .= '<div class="formrow">';
    $html .= '  <div class="formlabel">' . $label . '</div>';
    $html .= '  <div class="formfield">';
    $html .= '    ' . $fieldHtml;
    $html .= '  </div>';
    $html .= '</div>';    
    return $html;
  }
  
  // transforms {0=> value0, 1=> value1} to [[0, value0],[1, value1]] 
  private function objectToArray($obj) {
    $result = [];
    foreach ($obj as $k => $v) {
      $result[] = [$k, $v];
    }
    return $result;
  }
  
  // $opt è necessario perchè contiene le informazioni sulla tabella necessarie per
  //  visualizzare l'anteprima del campo "image"
  private function getHtmlFromConfigFields($fields_arr, $row_values, $opts) {
    $Form = $this->_machine->plugin("Form");
    
    $html = "";
    foreach ($fields_arr as $f) {
      $type = isset($f["type"]) ? $f["type"] : "text";
      switch ($type) {
        case "openbox":
          $html .= '<div class="openbox">';
          $html .= '  <div class="openbox_inner">';
          $html .= '    <b>' . $f["label"] . '</b>';
          break;
        case "closebox":
          $html .= '  </div>';
          $html .= '</div>';
          break;
        case "password":
          $fieldHtml = $Form->input($f["name"], "");
          $html .= $this->getHtmlForFormField($f["label"], $fieldHtml);
          break;
        case "text":
          $value = $this->getValueForUpdateField($f, $opts, $row_values);
          $fieldHtml = $Form->input($f["name"], $value);
          $html .= $this->getHtmlForFormField($f["label"], $fieldHtml);
          break;
        case "textarea":
          $value = $this->getValueForUpdateField($f, $opts, $row_values);
          $fieldHtml = $Form->textarea($f["name"], $value, ["class" => "ckeditor"]);
          $html .= $this->getHtmlForFormField($f["label"], $fieldHtml);
          break;
        case "simpletextarea":
          $value = $this->getValueForUpdateField($f, $opts, $row_values);
          $fieldHtml = $Form->textarea($f["name"], $value);
          $html .= $this->getHtmlForFormField($f["label"], $fieldHtml);
          break;
        case "date":
          $value = $this->getValueForUpdateField($f, $opts, $row_values);
          $fieldHtml = '<input class="backoffice-datepicker" id="' . $f["name"] . '" type="text" value="' . $value . '" name="' . $f["name"] . '">';
          $html .= $this->getHtmlForFormField($f["label"], $fieldHtml);
          break;
        case "time":
          $value = $this->getValueForUpdateField($f, $opts, $row_values);
          $fieldHtml = $this->getTimeFieldsHtml($f["name"], $value);
          $html .= $this->getHtmlForFormField($f["label"], $fieldHtml);
          break;
        case "datetime":
          $value = $this->getValueForUpdateField($f, $opts, $row_values);
          $fieldHtml = $this->getDatetimeFieldsHtml($f["name"], $value);
          $html .= $this->getHtmlForFormField($f["label"], $fieldHtml);
          break;
        case "select":
          $value = $this->getValueForUpdateField($f, $opts, $row_values);
          $options = $this->_getSelectOptions($opts["table"], $f["name"]);
          $koptions = [];
          foreach ($options as $k => $v) {
            $koptions[] = [$k, $v];
          }
          $fieldHtml = $Form->select($f["name"], $koptions, $value);
          $html .= $this->getHtmlForFormField($f["label"], $fieldHtml);
          break;
        case "manytomany":
          $query = $f["queryvalues"];
          $value = [];
          if (isset($row_values[$f["select_key"]])) {
            $id_value = $row_values[$f["select_key"]];
            $result = $this->_machine->plugin("DB")->select($query, [$id_value]);
            foreach ($result as $row) {
              $value[] = array_values($row)[0];
            }
          }
          $options = $this->_getSelectOptions($opts["table"], $f["name"]);
          $koptions = [];
          foreach ($options as $k => $v) {
            $koptions[] = [$k, $v];
          }
          $fieldHtml = $Form->select($f["name"], $koptions, $value, true);
          $html .= '<input type="hidden" name="' . htmlentities($f["name"]) . '" value="" />';
          $html .= $this->getHtmlForFormField($f["label"], $fieldHtml);
          break;
        case "multiselect":
          $arr_value = explode(",", $this->getValueForUpdateField($f, $opts, $row_values));
          $select_html = "";
          $extern_table = $f["extern_table"];
          $extern_field = $f["extern_field"];
          $extern_id = $f["extern_id"];
          $query = "SELECT $extern_id, $extern_field FROM $extern_table WHERE site_id = ? ORDER BY $extern_field";
          $result = $this->_machine->plugin("DB")->select($query, [$this->DB->getSite()]);
          foreach ($result as $row) {
            $selected = in_array($row[$extern_id], $arr_value) ? 'selected="selected"' : "";
            $select_html .= '<option ' . $selected . ' value="' . $row[$extern_id] . '">' . $row[$extern_field] . '</option>';
          }          
          $html .= '
            <select multiple name="' . $f["name"] . '[]">
              ' . $select_html . '
            </select>
          ';
          break;
        case "checkbox":
          $value = $this->getValueForUpdateField($f, $opts, $row_values);
          $html .= '<input type="hidden" name="' . $f["name"] . '" value="0" />';
          $html .= $Form->checkbox($f["name"], $f["label"], 1, $value);
          break;
        case "image":
          $value = $this->getValueForUpdateField($f, $opts, $row_values);
          $fieldHtml = '';
          if ($value != "") {
            $src = $this->getImageSrcForUpdateField($f, $opts, $row_values, $value);
            $id_value = $row_values[$opts["field_id"]];
            $fieldHtml .= '<a href="' . $this->_machine->plugin("Link")->Get(["ADMIN_RITAGLIO", $opts["table"], $id_value, $f["name"]]) . '">';
            $fieldHtml .= ' <img src="' . $src . '" />';
            $fieldHtml .= '</a>';
          }
          $fieldHtml .= $Form->image($f["name"], $value);
          $html .= $this->getHtmlForFormField($f["label"], $fieldHtml);
          break;
        case "file":
          $value = $this->getValueForUpdateField($f, $opts, $row_values);
          $fieldHtml = '';
          /*
          if ($value != "") {
            $src = $this->getFileSrcForUpdateField($f, $opts, $row_values, $value);
            $fieldHtml .= '<a href="' . $src . '">' . $value . '</a>';
          }
          */
          $fieldHtml .= $Form->image($f["name"], $value);
          $html .= $this->getHtmlForFormField($f["label"], $fieldHtml);
          break;
      }
    }
    return $html;
  }
  
  public function leadZero($n) {
    if ($n < 10) {
      return "0" . $n;
    }
    return "" . $n;
  }
  
  private function getDatetimeFieldsHtml($name, $value) {
    $html = "";
    
    $datepicker = '<input class="backoffice-datepicker" style="margin-bottom:0;" id="' . $name . '" type="text" value="' . date("Y-m-d", strtotime($value)) . '" name="' . $name . '">';
    
    $opts_h = "";
    for ($i = 0; $i < 24; $i++) {
      $vi = $this->leadZero($i);
      $selected = date("H", strtotime($value)) == $vi ? 'selected="selected"' : "";
      $opts_h .= '<option ' . $selected . ' value="' . $vi . '">' . $vi . '</option>';
    }

    $opts_m = "";
    for ($i = 0; $i < 60; $i++) {
      $vi = $this->leadZero($i);
      $selected = date("i", strtotime($value)) == $vi ? 'selected="selected"' : "";
      $opts_m .= '<option ' . $selected . ' value="' . $vi . '">' . $vi . '</option>';
    } 
    
    $html .= '
      <table>
        <tr>
          <td>
            ' . $datepicker. '
          </td>
          <td width="80">
            <select name="' . $name . '_h">
              ' . $opts_h . '
            </select>
          </td>
          <td>:</td>
          <td width="80">
            <select name="' . $name . '_m">
              ' . $opts_m . '
            </select>
          </td>
        </tr>
      </table>
    ';
    return $html;
  }
  
  private function getTimeFieldsHtml($name, $value) {
    $html = "";
    
    $opts_h = "";
    for ($i = 0; $i < 24; $i++) {
      $vi = $this->leadZero($i);
      $selected = date("H", strtotime($value)) == $vi ? 'selected="selected"' : "";
      $opts_h .= '<option ' . $selected . ' value="' . $vi . '">' . $vi . '</option>';
    }

    $opts_m = "";
    for ($i = 0; $i < 59; $i++) {
      $vi = $this->leadZero($i);
      $selected = date("i", strtotime($value)) == $vi ? 'selected="selected"' : "";
      $opts_m .= '<option ' . $selected . ' value="' . $vi . '">' . $vi . '</option>';
    } 
    
    $html .= '
      <table>
        <tr>
          <td width="80">
            <select name="' . $name . '_h">
              ' . $opts_h . '
            </select>
          </td>
          <td>:</td>
          <td width="80">
            <select name="' . $name . '_m">
              ' . $opts_m . '
            </select>
          </td>
        </tr>
      </table>
    ';
    return $html;
  }
  
  // $opts
  //  - table
  //  - field_id
  //  - id
  private function getValueForUpdateField($f, $opts, $row) {
    if (isset($f["join"])) {
      $field_name = $f["name"];
      $extern_table = $f["join"]["extern_table"];
      $field_id = $f["join"]["extern_id"];
      if (!isset($row[$f["join"]["foreign_key"]]) && !(isset($_GET["ext_id"]) && $_GET["ext_id"] != "0")) {
        return "";
      }
      $id_value = (isset($_GET["ext_id"]) && $_GET["ext_id"] != "0") ? $_GET["ext_id"] : $row[$f["join"]["foreign_key"]];
      $query = "SELECT $field_name FROM $extern_table WHERE $field_id = ?";
      $result = $this->_machine->plugin("DB")->select($query, [$id_value]);
      return $result[0][$f["name"]];
    } else {
      return isset($row[$f["name"]]) ? $row[$f["name"]] : "";
    }
  }
  
  private function getImageSrcForUpdateField($f, $opts, $row, $value) {
    if (isset($f["join"])) {
      $extern_table = $f["join"]["extern_table"];
      if (!isset($row[$f["join"]["foreign_key"]])) {
        return "";
      }
      $id_value = $row[$f["join"]["foreign_key"]];
      return $this->_machine->plugin("App")->getImg($extern_table, $id_value, "images", $value, "W", 40);
    } else {
      return $this->_machine->plugin("App")->getImg($opts["table"], $opts["id"], "images", $value, "W", 40);
    }
  }
  
  private function _getFormFieldType($table, $fieldname)
  {
    $fo = $this->getFieldOptions($table, $fieldname);
    if (isset($fo["type"])) {
      return $fo["type"];
    }
    if (isset($fo["type"])) {
      return $fo["type"];
    }
    
    $fo = $this->getFieldOptions($table, $fieldname . "[]");
    if (isset($fo["type"])) {
      return $fo["type"];
    }
    if (isset($fo["type"])) {
      return $fo["type"];
    }
    
    return "text";
  }
  
  public function getFormFieldType($table, $fieldname) {
    return $this->_getFormFieldType($table, $fieldname);
  }
  
  public function getFormFieldJoin($table, $fieldname)
  {
    $fo = $this->getFieldOptions($table, $fieldname);
    if (isset($fo["join"])) {
      return $fo["join"];
    }
    return "";
  }  
  
  public function updateRecurrents($id_recurrent) {
    // recurrence_from
    // recurrence_to
    // repeat_day_1 ... repeat_day_7 (lunedì ... domenica)
    // repeat_week_1 ... repeat_week_4, repeat_week_last
    $result = $this->getRecordData([
      "table" => "recurrents",
      "field_id" => "id",
      "id" => $id_recurrent
    ]); 
    $row = $result[0];
    
    // build the repeat_days array
    $repeat_days = [];
    $t = [7,1,2,3,4,5,6];
    for ($i = 0; $i < 7; $i++) {
      // $i è il valore "w" di php (0=domenica, 6=sabato)
      // $t è il valore del giorno in base alla convenzione di discos
      //  (1=lunedì, 7=domenica)
      if ($row["repeat_day_" . ($t[$i])] == 1) {
        $repeat_days[] = $i;
      }
    }
    
    // tell if repeat_week is every week
    $filter_by_week = 0;
    $weekslabel = [1,2,3,4,"last"];
    for ($i = 0; $i < count($weekslabel); $i++) {
      $filter_by_week += $row["repeat_week_" . $weekslabel[$i]];
    }
    
    // get the event dates
    $event_dates = [];
    $ordinals = ["first", "second", "third", "fourth", "fifth"];
    $from = new \DateTimeImmutable($row["recurrence_from"]);
    $to = new \DateTimeImmutable($row["recurrence_to"]);
    $date = $from;
    while ($date <= $to) {
      $w = $date->format("w");
      // if repeat_day is not satisfied, increment and continue
      if (!in_array($w, $repeat_days)) {
        $date = $date->modify("+1 day");
        continue;
      }
      
      // if repeat_week is not satisfied, increment and continue
      $dayname = $date->format("l");
      $monthinfo = $date->format("Y-m");
      if ($filter_by_week) {
        $found = false;
        for ($i = 0; $i < count($ordinals); $i++) {
          if ($row["repeat_week_" . $weekslabel[$i]] == 1) {
            $relativelabel = $ordinals[$i] . " " . $dayname . " " . $monthinfo; 
            $checkdate = new \DateTime($relativelabel);
            if ($checkdate == $date) {
              $found = true;
              break;
            }
          }
        }
        if (!$found) {
          $date = $date->modify("+1 day");
          continue;
        }
      }
      
      // if tests passed, add the date
      $event_dates[] = $date;
      
      // then insert the event
      $date = $date->modify("+1 day");
    }
    
    // elimina le date non presenti
    $this->cleanEventDates($id_recurrent, $event_dates);
    
    // inserire/aggiornare le date ricavate
    foreach ($event_dates as $evdate) {
      $this->updateEvents($id_recurrent, $evdate, $row);
    }
  }

  public function cleanEventDates($id_recurrent, $event_dates) {
    // cerca tutte le date presenti
    $query = "SELECT id, date(time_from) AS d FROM events WHERE recurrent_id = ?";
    $result = $this->DB->select($query, [
      $id_recurrent
    ]);
    
    // trasformo le date nuove in stringhe
    $sdates = array_map(function($item) {
      return $item->format("Y-m-d");
    }, $event_dates);
    
    // per ogni data presente, verifico se è presente nell'array nuovo
    //  altrimenti la cancello
    foreach ($result as $row) {
      if (!in_array($row["d"], $sdates)) {
        $res = $this->DB->delete("DELETE FROM events WHERE id = ?", [$row["id"]]);
      }
    }
  }
  
  // aggiorna un singolo evento dato id_recurrent e evdate
  public function updateEvents($id_recurrent, $evdate, $rowdata) {
    $time_from = $evdate->format("Y-m-d") . " " . date("H:i:s", strtotime($rowdata["time_from"]));
    
    // cerca un evento con id_recurrent e evdate
    $query = "SELECT * FROM events WHERE recurrent_id = ? AND date(time_from) = ?";
    $result = $this->DB->select($query, [
      $id_recurrent, $evdate->format("Y-m-d")
    ]);
    
    // se non esiste lo inserisce
    $time_from_dt = new \DateTime($time_from);
    if (count($result) == 0) {
      $query = "
        INSERT INTO events
        (
          site_id,
          recurrent_id,
          created_at,
          updated_at,
          time_from,
          time_to,
          description
        )
        VALUES
        (?, ?, ?, ?, ?, ?, ?)
      ";
      $data = [
        $this->DB->getSite(),
        $id_recurrent,
        date("Y-m-d H:i:s"),
        date("Y-m-d H:i:s"),
        $time_from,
        $time_from_dt->add(new \DateInterval("PT" . $rowdata["time_duration"] . "H"))->format("Y-m-d H:i:s"),
        $rowdata["description"]
      ];
      $event_id = $this->DB->insert($query, $data);
    } else {
      $event_id = $result[0]["id"];
    }
    
    // aggiorna tutti gli altri campi
    $fieldvalues = [
      "seo_title" => $rowdata["seo_title"],
      "seo_description" => $rowdata["seo_description"],
      "seo_keyword" => $rowdata["seo_keyword"],
      "seo_url" => $rowdata["seo_url"],
      "seo_footer" => $rowdata["seo_footer"],
      "active" => $rowdata["active"],
      "title" => $rowdata["title"],
      "title_date" => $rowdata["title_date"],
      "on_home" => $rowdata["on_home"],
      "special" => $rowdata["special"],
      "address_way" => $rowdata["address_way"],
      "address_number" => $rowdata["address_number"],
      "address_city" => $rowdata["address_city"],
      "address_zip" => $rowdata["address_zip"],
      "address_province" => $rowdata["address_province"],
      "nation_id" => $rowdata["nation_id"],
      "phone" => $rowdata["phone"],
      "mobile" => $rowdata["mobile"],
      "fax" => $rowdata["fax"],
      "email" => $rowdata["email"],
      "url" => $rowdata["url"],
      "image_file_name" => $rowdata["image_file_name"],
      "document_file_name" => $rowdata["document_file_name"],
      "time_from" => $time_from,
      "time_to" => $time_from_dt->add(new \DateInterval("PT" . $rowdata["time_duration"] . "H"))->format("Y-m-d H:i:s"),
      "description" => $rowdata["description"]
    ];

    $result = $this->DB->save(
      "events", 
      array_keys($fieldvalues),
      array_values($fieldvalues),
      "id",
      $event_id
    );
  }
  
  private function _isHiddenByConfig($table, $fieldname) {
    if (isset($this->_config[$table]["hidden"])) {
      if (in_array($fieldname, $this->_config[$table]["hidden"])) {
        return true;
      }
    }
    return false;
  }
  
  public function getTableForField($table, $field) {
    $f = $this->getFieldOptions($table, $field);
    if (isset($f["join"]) && isset($f["join"]["extern_table"])) {
      return $f["join"]["extern_table"];
    }
    return $table;
  }
  
  public function getIdValueForField($table, $id, $field) {
    $f = $this->getFieldOptions($table, $field);
    if (isset($f["join"]) && isset($f["join"]["extern_table"])) {
      $result = $this->getRecordData([
        "table" => $table,
        "field_id" => $this->getFieldId($table),
        "id" => $id
      ]);
      return $result[0][$f["join"]["foreign_key"]];
    }
    return $id;
  }
  
  public function getRecordData($opts) {
    $joinpart = $this->getJoinPart($opts["table"]);
    if ($joinpart != "") {
      $query = "SELECT * FROM " . $opts["table"] . " " . $joinpart . " WHERE " . $opts["table"] . "." . $opts["field_id"] . " = ?";
    } else {
      $query = "SELECT * FROM " . $opts["table"] . " WHERE " . $opts["field_id"] . " = ?";
    }
    $result = $this->DB->select($query, [$opts["id"]]);
    return $result;
  }
}