    <div class="right-off-canvas-menu">
      <nav class="mainMenu">
        <div class="row">
          <div class="large-12 columns">
            <form class="simple_form row collapse postfix-round formSearch" novalidate="novalidate" action="{{Link|Get|SEARCH}}" accept-charset="UTF-8" method="get"><input name="utf8" type="hidden" value="&#x2713;" />
              <div class="small-9 columns">
                <div class="row string optional form_search_phrase">
                  <input placeholder="Cerca nel sito" class="string optional" type="text" name="form_search[phrase]" />
                </div>
              </div>
              <div class="small-3 columns">
                <button class="button postfix">
                  <i class="fa fa-search"></i>
                </button>
              </div>
            </form>
            <div class="select-tenant">
              <button aria-controls="choiceTenant-small" aria-expanded="false" class="button-tenant radius" data-dropdown="choiceTenant-small" href="#">
                <span class="choice">
                  cambia città
                </span>
                <span class="actual">
                  <i class="fa fa-map-marker"></i>
                    {{currentCity}}
                  <i class="fa fa-angle-right"></i>
                </span>
              </button>
              <ul aria-hidden class="f-dropdown choiceTenants" data-dropdown-content id="choiceTenant-small">
                <?php foreach ($cities as $city) { ?>
                  <?php
                  $url = "//" . $city["url"];
                  $admin = (isset($sidebar_admin) && $sidebar_admin == true) ? "1" : "0";
                  if (!$App->is_online) { //($admin) {
                    $url = $Link->Get("SET_SITE_COOKIE") . "?admin=" . $admin . "&n_site=" . $city["id"];
                  }
                  ?>
                  <li><a title="Discoteche, locali ed eventi a <?php echo $city["name"]; ?>" data-gavalue="<?php echo $city["name"]; ?>" data-galabel="Cambio_provincia" href="<?php echo $url; ?>"><?php echo $city["name"]; ?></a></li>
                <?php } ?>
              </ul>
            </div>
          </div>
        </div>
        <ul class="navMain" role="navigation">
          <?php foreach ($menuitems as $menuitem) { ?>
            <?php if (!isset($menuitem["children"])) { 
              $className = "";
              if ($menuitem["url"] != "") {
                //if (stristr($Link->Get($this->getCurrentPath()), $menuitem["url"]) !== false) {
                if ($Link->Get($this->getCurrentPath()) == $menuitem["url"]) {
                  $className = "active";
                }
              }
              ?>
              <li data-path="<?php echo $Link->Get($this->getCurrentPath()); ?>" class="<?php echo $className; ?>">
                <a href="<?php echo str_replace(
                    "http://www." . $DB->getCurrentCity()[0]["url"], 
                    "//" . $_SERVER["HTTP_HOST"] . $this->basepath, 
                    $menuitem["url"]
                  ); ?>" title="<?php echo $menuitem["title"]; ?>">
                  <i class="fa <?php echo $menuitem["iconclass"]; ?>"></i>
                  <?php echo $menuitem["label"]; ?>
                </a>
              </li>
            <?php } else { ?>
              <li class="dropdown <?php echo isset($cat) ? "active" : ""; ?>">
                <a href="#">
                  <i class="fa <?php echo $menuitem["iconclass"]; ?>"></i>
                  <?php echo $menuitem["label"]; ?>
                  <span><i class="fa fa-angle-down"></i></span>
                </a>
                <ul>
                  <?php foreach($menuitem["children"] as $submenuitem) { ?>
                    <?php 
                    $class = "";
                    if (isset($cat) && $cat["typo_id"] == $submenuitem["id"]) {
                      $class = "active";
                    }
                    ?>
                    <li class="<?php echo $class; ?>"><a title="<?php echo $submenuitem["title"]; ?>" href="{{Link|Get|CATEGORIA_LOCALI|<?php echo $submenuitem["url"]; ?>}}"><?php echo $submenuitem["label"]; ?></a></li>
                  <?php } ?>
                </ul>
              </li>
            <?php } ?>
          <?php } ?>
        </ul>
      </nav>
      <div class="adv">
        <?php foreach ($navbanners as $navbanner) { ?>
          <?php $imageurl = $App->img("banners", $navbanner["id"], 205, "H", $navbanner["image_file_name"]); ?>
          <div class="banner">
            <a title="<?php echo $navbanner["title"]; ?>" href="<?php echo $navbanner["url"]; ?>">
              <img alt="<?php echo $navbanner["title"]; ?>" title="<?php echo $navbanner["title"]; ?>" data-gavalue="<?php echo $navbanner["title"]; ?>" data-galabel="Banner_SX" src="<?php echo $imageurl; ?>" />
            </a>
          </div>
        <?php } ?>
      </div>
    </div>