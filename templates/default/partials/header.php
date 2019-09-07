<!DOCTYPE html>
<html class="production" lang="it">
<head>
<title>{{seoTitle}}</title>
<meta content="{{seoDescription}}" name="description" />
<meta content="{{seoKeywords}}" name="keywords" />
<meta charset="UTF-8" />
<meta content="{{ogTitle}}" property="og:title" />
<meta content="{{ogDescription}}" property="og:description" />
<meta content="<?php echo $App->getCurrentUrl(); ?>" property="og:url" />
<meta content="<?php echo isset($ogImage) ? $ogImage : $App->defaultOgImage; ?>" property="og:image" />
<meta content="<?php echo isset($ogImage) ? $ogImage : $App->defaultOgImage; ?>" property="og:image:url" />
<meta content="{{ogSiteName}}" property="og:site_name" />
<?php if (isset($twitterTitle)) { ?>
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="@Discoteche<?php echo $DB->getDiscoCode(); ?>" />
<meta name="twitter:title" content="{{twitterTitle}}" />
<meta name="twitter:description" content="{{twitterDescription}}" />
<meta name="twitter:image" content="<?php echo isset($ogImage) ? $ogImage : $App->defaultOgImage; ?>" />
<?php } ?>
<?php if (isset($canonical)) { ?>
<link href="<?php echo $canonical; ?>" rel="canonical" />
<?php } ?>
<?php if (isset($next)) { ?>
<link href="<?php echo $next; ?>" rel="next">
<?php } ?>
<meta content="width=device-width, initial-scale=1.0" name="viewport">

<!-- foundation -->
<link rel="stylesheet" href="{{templatePath}}vendor/css/normalize.css">
<link rel="stylesheet" href="{{templatePath}}vendor/css/foundation.css">
<!-- foundation-datepicker -->
<link rel="stylesheet" href="{{templatePath}}vendor/css/foundation-datepicker.min.css">
<!-- slick carousel -->
<link rel="stylesheet" href="{{templatePath}}vendor/css/slick.css">
<link rel="stylesheet" href="{{templatePath}}vendor/css/slick-theme.css">
<!-- googlefonts -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet">
<!-- font awesome -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- custom style -->
<link rel="stylesheet" href="{{templatePath}}css/foundation_override.css">
<link rel="stylesheet" href="{{templatePath}}css/front_custom.css">
<link rel="stylesheet" href="{{templatePath}}css/backoffice_custom.css">
<!-- fancybox -->
<link rel="stylesheet" href="{{templatePath}}vendor/fancybox/css/jquery.fancybox.css" type="text/css" media="screen" />

<link rel="alternate" type="application/rss+xml" title="News ed Eventi" href="/rss/prossimi-eventi.xml" />

<link rel="shortcut icon" type="image/x-icon" href="{{templatePath}}images/front/favicon.ico" />
<?php echo $codeCustomHeader; ?>
</head>
<body class="front tenant-bs <?php echo $bodyclass; ?>">
<!-- adsense -->
<script src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" async="async" defer="defer"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-6371727345571989",
    enable_page_level_ads: true
  });
</script>

<div class="off-canvas-wrap" data-offcanvas>
  <div class="inner-wrap" data-equalizer>
    <div class="tab-bar show-for-small small_logo" data-equalizer>
      <a title="Discoteche Brescia" href="{{Link|Get|HOME}}"><img alt="Discoteche Brescia" src="{{templatePath}}images/front/small/logo-discoteche-italia.png" /></a>
      <section class="right-small">
        <a class="right-off-canvas-toggle menu-icon" href="#">
          <span></span>
        </a>
      </section>
    </div>
    <?php include("right_off_canvas_menu.php"); ?>
    <?php include("sidebar.php"); ?>
    <div data-equalizer-watch id="main">
      <?php include("topnav.php"); ?>
      