<!DOCTYPE html>
<html class="production" lang="it">
<head>
<title>{{seoTitle}}</title>
<meta content="{{seoDescription}}" name="description" />
<meta content="{{seoKeywords}}" name="keywords" />
<meta charset="UTF-8" />
<meta content="{{ogTitle}}" property="og:title" />
<meta content="{{ogDescription}}" property="og:description" />
<meta content="{{ogUrl}}" property="og:url" />
<meta content="{{ogImage}}" property="og:image" />
<meta content="{{ogImageUrl}}" property="og:image:url" />
<meta content="{{ogSiteName}}" property="og:site_name" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:site" content="{{twitterSite}}" />
<meta name="twitter:title" content="{{twitterTitle}}" />
<meta name="twitter:description" content="{{twitterDescription}}" />
<meta name="twitter:image" content="{{twitterImage}}" />

<meta content="width=device-width, initial-scale=1.0" name="viewport">

<!-- foundation -->
<link rel="stylesheet" href="{{templatePath}}vendor/css/normalize.css">
<link rel="stylesheet" href="{{templatePath}}vendor/css/foundation.css">
<!-- foundation-datepicker -->
<link rel="stylesheet" href="{{templatePath}}vendor/css/foundation-datepicker.min.css">
<!-- cropper -->
<link rel="stylesheet" href="{{templatePath}}vendor/css/jquery.jCrop.min.css">
<!-- googlefonts -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet">
<!-- font awesome -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- chosen -->
<link rel="stylesheet" href="{{templatePath}}vendor/css/chosen.min.css">
<!-- custom style -->
<link rel="stylesheet" href="{{templatePath}}css/foundation_override.css">
<link rel="stylesheet" href="{{templatePath}}css/front_custom.css">
<link rel="stylesheet" href="{{templatePath}}css/backoffice_custom.css">

<link rel="alternate" type="application/rss+xml" title="News ed Eventi" href="/rss/prossimi-eventi.xml" />
<link rel="shortcut icon" type="image/x-icon" href="{{templatePath}}images/front/favicon.ico" />
</head>
<body class="front tenant-bs <?php echo $bodyclass; ?>">
<div class="off-canvas-wrap" data-offcanvas>
  <div class="inner-wrap" data-equalizer>
    <div class="tab-bar show-for-small small_logo" data-equalizer>
      <a title="Discoteche Brescia" href="{{Link|Get|/}}"><img alt="Discoteche Brescia" src="{{templatePath}}images/front/small/logo-discoteche-italia.png" /></a>
      <section class="right-small">
        <a class="right-off-canvas-toggle menu-icon" href="#">
          <span></span>
        </a>
      </section>
    </div>
    <?php include("sidebar.php"); ?>
    <div data-equalizer-watch id="main">
      