<?php
/**
 * WebEngine
 *
 * PHP version 5
 *
 * @category  Plugin
 * @package   WebEngine
 * @author    Paolo Savoldi <paooolino@gmail.com>
 * @copyright 2017 Paolo Savoldi
 * @license   https://github.com/paooolino/WebEngine/blob/master/LICENSE 
 *            (Apache License 2.0)
 * @link      https://github.com/paooolino/WebEngine
 */
namespace WebEngine\Plugin;

/**
 * Form class
 *
 * A Form manager for the WebEngine.
 *
 * @category Plugin
 * @package  WebEngine
 * @author   Paolo Savoldi <paooolino@gmail.com>
 * @license  https://github.com/paooolino/WebEngine/blob/master/LICENSE 
 *           (Apache License 2.0)
 * @link     https://github.com/paooolino/WebEngine
 */
class Form
{
  private $_engine;
  private $forms;
  
  private $formrow_template = '
    <div class="formRow type{{CLASS_TYPE}}">
      <div class="formLabel">
        {{LABEL}}
      </div>
      <div class="formField">
        {{FIELD}}
      </div>
      <div class="closing"></div>
    </div>
  ';
  private $form_template = '
    <div class="formContainer form_{{FORMNAME}}">
      <form method="post" action="{{FORMACTION}}" enctype="multipart/form-data">
        {{FORMROWS}}
        <button type="submit">{{SUBMITLABEL}}</button>
      </form>
    </div>
  ';
  
  private $field_templates = [
    "text" => '<input id="{{UNIQUE_ID}}" type="text" value="{{VALUE}}" {{ATTRIBUTES}} />',
    "image" => '<input id="{{UNIQUE_ID}}" type="file" data-value="{{VALUE}}" {{ATTRIBUTES}} />',
    "content" => '',
    "email" => '<input id="{{UNIQUE_ID}}" type="email" value="{{VALUE}}" {{ATTRIBUTES}} />',  
    "select" => '<select id="{{UNIQUE_ID}}" {{ATTRIBUTES}}>{{OPTS}}</select>',  
    "password" => '<input id="{{UNIQUE_ID}}" type="password" {{ATTRIBUTES}} />',  
    "checkbox" => '<label><input id="{{UNIQUE_ID}}" type="checkbox" {{ATTRIBUTES}} /> {{LABEL}}</label>',     
    "hidden" => '<input type="hidden" value="{{VALUE}}" {{ATTRIBUTES}} />',
    "textarea" => '<textarea id="{{UNIQUE_ID}}" {{ATTRIBUTES}}>{{VALUE}}</textarea>',
    // for radio buttons, value is an attribute!
    "radio" => '<label><input id="{{UNIQUE_ID}}" name="{{NAME}}" type="radio" {{ATTRIBUTES}}" checked="{{CHECKED}}"> {{LABEL}}</label>'
  ];
  
  private $_values;
  
  private $_currentForm;
  
  /**
   * Form plugin constructor.
   *
   * The user should not use it directly, as this is called by the WebEngine.
   *
   * @param WebEngine $engine the WebEngine instance.
   */
  function __construct($engine) 
  {
    $this->_engine = $engine;
    $this->_values = [];
    $this->_currentForm = "";
  }
  
  /**
   * Add a form, given a name and some options.
   *
   * An example
   * <code>
	 *	$Form->addForm("myForm", [
   *    "action" => "/register/",
   *    "submitlabel" => "Invia",
   *    "fields" => [
   *      ["email", "text", ["name" => "email"]],
   *      ["password", "password", ["name" => "password"]]
   *    ]
   *  ]);
   * </code>
   *
   * @param string $name
   * @param array  $opts
   *
   * @return void
   */
  public function addForm($name, $opts) 
  {
    $this->forms[$name] = $opts;
  }
  
  public function setFieldTemplate($fieldtype, $newtemplate)
  {
    $this->field_templates[$fieldtype] = $newtemplate;
  }
  
  /**
   *  Set the values for a form.
   */
  public function setValues($formname, $values)
  {
    $this->_values[$formname] = $values;
  }
  
  /**
   *  render single input
   */
  public function input($name, $value) {
    return $this->_engine->populateTemplate(
      $this->field_templates["text"],
      [
        "VALUE" => $value,
        "UNIQUE_ID" => $name,
        "ATTRIBUTES" => $this->_buildFieldAttributesString([
          "name" => $name
        ])
      ]
    );
  }
  
  /**
   *  render single textarea
   */
  public function textarea($name, $value, $opts=[]) {
    return $this->_engine->populateTemplate(
      $this->field_templates["textarea"],
      [
        "VALUE" => $value,
        "UNIQUE_ID" => $name,
        "ATTRIBUTES" => $this->_buildFieldAttributesString(
          array_merge($opts, [
            "name" => $name
          ])
        )
      ]
    );    
  }
  
  /**
   *  render single file input
   */
  public function image($name, $value) {
    return $this->_engine->populateTemplate(
      $this->field_templates["image"],
      [
        "VALUE" => $value,
        "UNIQUE_ID" => $name,
        "ATTRIBUTES" => $this->_buildFieldAttributesString([
          "name" => $name
        ])
      ]
    );
  }
  
  /**
   *  render single select input
   *
   *  opts: [[value, label],[..., ...], ...]
   */
  public function select($name, $opts, $value, $multiple=false) {
    $attributes = [
      "name" => $name
    ];
    if ($multiple)
      $attributes["multiple"] = "multiple";
    
    return $this->_engine->populateTemplate(
      $this->field_templates["select"],
      [
        "UNIQUE_ID" => $name,
        "ATTRIBUTES" => $this->_buildFieldAttributesString($attributes),
        "OPTS" => $this->_getHtmlForOptions($opts, $value)
      ]
    );
  }
  
  /**
   *  render single checkbox input
   *
   */
  public function checkbox($name, $label, $value_attr=1, $value=false) {
    $arr_attributes = [
      "name" => $name,
      "value" => $value_attr
    ];
    if ($value == 1 || $value == true || $value == "true") {
      $arr_attributes["checked"] = "checked";
    } 
    return $this->_engine->populateTemplate(
      $this->field_templates["checkbox"],
      [
        "UNIQUE_ID" => $name,
        "LABEL" => $label,
        "ATTRIBUTES" => $this->_buildFieldAttributesString($arr_attributes)
      ]
    );
  }
  
  /**
   * Renders the form, given the name.
   *
   * @param string $params
   *
   * @return string The html code to display the form.
   */
  public function Render($params) 
  {
    $formName = $params[0];
    $this->_currentForm = $formName;
    
    $opts = $this->forms[$formName];
    
    $html_rows = "";
    foreach ($opts["fields"] as $formField) {
      $value = "";
      if (isset($formField[2]) && isset($formField[2]["name"])) {
        $fieldname = $formField[2]["name"];
        if (isset($this->_values[$formName][$fieldname])) {
          $value = $this->_values[$formName][$fieldname];
        }
      }
      $html_rows .= $this->_engine->populateTemplate(
        $this->formrow_template, [
          "LABEL" => $this->getFormLabel($formField),
          "FIELD" => $this->getFormField($formField, $value),
          "CLASS_TYPE" => $formField[1]
        ]
      );
    }
    
    $html = $this->_engine->populateTemplate(
      $this->form_template, [
        "FORMNAME" => $formName,
        "FORMACTION" => $opts["action"],
        "FORMROWS" => $html_rows,
        "SUBMITLABEL" => isset($opts["submitlabel"]) ? $opts["submitlabel"] : "submit"
      ]
    );
    
    return $html;
  }
  
  // $value may be an array of values in case of multiselect
  private function _getHtmlForOptions($opts, $value) {
    $html = '';
    
    foreach ($opts as $opt) {
      if (gettype($opt) == "string") {
        if (
          (gettype($value) == "string" && $value == $opt)
          || (gettype($value) == "array" && in_array($opt, $value))
        ) {
          $html .= '<option selected>' . $opt . '</option>';
        } else {
          $html .= '<option>' . $opt . '</option>';
        }
      } else {
        if (
          (gettype($value) == "string" && $value == $opt[0])
          || (gettype($value) == "array" && in_array($opt[0], $value))        
        ) {
          $html .= '<option selected value="' . $opt[0] . '">' . $opt[1] . '</option>';
        } else {
          $html .= '<option value="' . $opt[0] . '">' . $opt[1] . '</option>';
        }
      }
    }
    
    return $html;
  }
  
  /**
   *  Get an unique id for the field.
   *  
   *  This is used to assign an id="" attribute to the DOM input element, thus 
   *  able to be referenced by a possible <label for="".
   *  The id should be deterministic (not trandomly generated). It is build
   *  joining the form name and the field name, plus the field "value" attribute
   *  in case of radio buttons.
   */
  private function _getUniqueId($formField)
  {
    $id = $this->_currentForm . $formField[2]["name"];
    $id .= isset($formField[2]["value"]) ? $formField[2]["value"] : "";
    return $id;
  }
  
  private function getFormLabel($formField) 
  {
    $field_type = $formField[1];
    switch ($field_type) {
      case "hidden":
        return "";
        break;
      case "checkbox";
      case "radio":
        return "";
        break;
      case "content":
        return $formField[0];
      default:
        return '<label for="' . $this->_getUniqueId($formField) . '">' . $formField[0] . '</label>';
    } 
  }
  
  private function getFormField($formField, $value) 
  {
    $field_type = $formField[1];
    $additional_macros = isset($formField[3]) ? $formField[3] : [];
    switch ($field_type) {
      case "text";
      case "image";
      case "email";
      case "hidden";
      case "textarea";
      case "password":
        return $this->_engine->populateTemplate(
          $this->field_templates[$field_type],
          array_merge($additional_macros, [
            "VALUE" => $value,
            "UNIQUE_ID" => $this->_getUniqueId($formField),
            "ATTRIBUTES" => $this->_buildFieldAttributesString($formField[2])
          ])
        );
        break;
      case "content":
        return '';
      case "select":
        return $this->_engine->populateTemplate(
          $this->field_templates[$field_type],
          array_merge($additional_macros, [
            "UNIQUE_ID" => $this->_getUniqueId($formField),
            "ATTRIBUTES" => $this->_buildFieldAttributesString($formField[2]),
            "OPTS" =>  $this->_getHtmlForOptions($formField[2]["options"], $value)
          ])
        );
        break;;
      case "checkbox":
        $arr_attributes = $formField[2];
        if ($value == 1 || $value == true || $value == "true") {
          $arr_attributes["checked"] = "checked";
        }    
        return $this->_engine->populateTemplate(
          $this->field_templates[$field_type],
          array_merge($additional_macros, [
            "UNIQUE_ID" => $this->_getUniqueId($formField),
            "LABEL" => $formField[0],
            "ATTRIBUTES" => $this->_buildFieldAttributesString($arr_attributes)
          ])
        );
        break;
      case "radio":
        return $this->_engine->populateTemplate(
          $this->field_templates[$field_type],
          array_merge($additional_macros, [
            "VALUE" => $value,
            "UNIQUE_ID" => $this->_getUniqueId($formField),
            "LABEL" => $formField[0],
            "ATTRIBUTES" => $this->_buildFieldAttributesString($formField[2])
          ])
        );
        break;
    }
  }
  
  private function _buildFieldAttributesString($arr_attributes)
  {
    $allowed_attributes = ["class", "name", "disabled", "checked", "value", "multiple"];
    $atts = [];
    foreach ($arr_attributes as $k => $v) {
      if (in_array($k, $allowed_attributes)) {
        $atts[] = $k . '="' . htmlentities($v) . '"';
      }
    }
    return implode(" ", $atts);
  }
}
