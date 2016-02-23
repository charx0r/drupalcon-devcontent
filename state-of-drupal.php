<!DOCTYPE html>
<html lang="en">
<head>

  <!-- Basic Page Needs
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta charset="utf-8">
  <title>Drupal 8 Project Estimation</title>
  <meta name="description" content="Estimate your Drupal 7 to 8 project migration based on contrib module status">
  <meta name="author" content="Charan Puvvala, Harshitha Venna">

  <!-- Mobile Specific Metas
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- FONT
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">

  <!-- CSS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/skeleton.css">
  <link rel="stylesheet" type="text/css" href="css/estimations.css">
  <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
  <script type="text/javascript" src="js/jquery-countTo.js"></script>
  <script type="text/javascript" src="js/jquery.appear.js"></script>
  <!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="icon" type="image/png" href="images/favicon.png">

</head>
<body>
<?php include_once("analyticstracking.php") ?>
  <!-- Primary Page Layout
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
<nav id="navigation" class="navbar replaceme">
  <div class="container">
  <div class="left-corner"></div>
  <div class="right-corner"></div>
  <div class="region region-main-menu">
  <div id="block-system-main-menu" class="block block-system block-menu">
  <div class="content">
  <ul id="menu" class="navbar-list"><li class="first leaf navbar-item"><a href="/drupal_data" class="navbar-link a-class ">Home</a></li>
  <li class="leaf navbar-item"><a href="state-of-drupal" class="navbar-link a-class active">State of Drupal 8 Contrib</a></li>
  <li class="leaf navbar-item"><a href="https://github.com/charx0r/drupalcon-devcontent/blob/master/README.md" class="navbar-link a-class" target="_blank">Documentation</a></li>
  <li class="leaf navbar-item"><a href="https://github.com/charx0r/drupalcon-devcontent/" class="navbar-link a-class" target="_blank">Github</a></li>
  </ul> </div>
  </div>
  </div>
  </div>
</nav>
  <div class="container">
    <div class="row">
      <div class="full column" style="margin-top: 2%">
        <center><h2>State of Drupal 8 Contrib</h2></center>
        <div class="row">
        <div class="one-third column bg-blue number-counters">
        <h5>Total Drupal 8 Modules:</h5>
        <div class="mod-count counters-item"><strong data-to="1374">1374</strong></div>
        </div>
        <div class="one-third column  bg-blue number-counters">
        <h5>D8 Contrib Maintainers:</h5>
        <div class="mod-count counters-item"><strong data-to="3915">3915</strong></div>
        </div>
        <div class="one-third column bg-blue number-counters"><h5>Modules in dev rel only:</h5><div class="mod-count counters-item"><strong data-to="380">380</strong></div></div>
        </div>
        <h4>Drupal 8 Contrib Release Frequency</h4>
        <p>The y-axis plots the days of week and the x-axis plots the time of day. The z-axis plots the number of releases within that hour.</p>
        <blockquote style="font-size: 120%;"><b>Most of the module releases happen during the first half of the week and the second half of the day.</b></blockquote>
        <iframe src="http://52.36.81.128/drupal_data/bubble-matrix.html" height="605px" width="100%" frameborder="0"></iframe>
        <h4><span>Contrib Module commit, 2011-2016</span></h4>
        <div class="hint">mousewheel to zoom, drag to pan</div>
        <iframe src="http://52.36.81.128/drupal_data/area_chart.html" height="677px" width="108%" frameborder="0"></iframe>
        <!-- Note: The class .u-full-width is just a utility class shorthand for width: 100% -->
      </div>
    </div>
  </div>
<script type="text/javascript">
jQuery(document).ready(function(){
  jQuery(".number-counters").appear(function () {
        jQuery(".number-counters [data-to]").each(function () {
            var e = jQuery(this).attr("data-to");
            jQuery(this).delay(6e3).countTo({
                from: 50,
                to: e,
                speed: 3e3,
                refreshInterval: 50
            })
        })
    });
});
</script>
<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
</body>
</html>
