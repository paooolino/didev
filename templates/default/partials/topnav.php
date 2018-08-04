      <div class="show-for-medium-up" id="header">
        <ul class="navTop inline-list">
          <?php foreach ($navtopitems as $navtopitem) { ?>
            <li>
              <a href="<?php echo $navtopitem["url"]; ?>" title="<?php echo $navtopitem["title"]; ?>">
                <?php echo $navtopitem["label"]; ?>
              </a>
            </li>
          <?php } ?>
          <?php foreach ($linktopitems as $linktopitem) { ?>
            <li>
              <a href="<?php echo $linktopitem["url"]; ?>" class="<?php echo $linktopitem["class"]; ?>" title="<?php echo $linktopitem["title"]; ?>">
                <i class="fa <?php echo $linktopitem["classicon"]; ?>"></i>
              </a>
            </li>
          <?php } ?>
        </ul>
        <aside class="row text-center">
          <div class="show-for-medium-up large-9 columns">
            <div class="banner">
              <a title="{{topBannerTitle}}" href="{{topBannerUrl}}">
                <img alt="{{topBannerTitle}}" title="{{topBannerTitle}}" data-gavalue="{{topBannerTitle}}" data-galabel="Banner_leader_board" src="{{imageUrl}}" />
              </a>
            </div>
          </div>
          <div class="show-for-large-up large-3 columns">
          </div>
        </aside>
      </div>