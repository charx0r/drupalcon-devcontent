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
  <ul id="menu" class="navbar-list"><li class="first leaf navbar-item"><a href="/drupal_data" class="navbar-link a-class active">Home</a></li>
  <li class="leaf navbar-item"><a href="state-of-drupal" class="navbar-link a-class">State of Drupal 8 Contrib</a></li>
  <li class="leaf navbar-item"><a href="https://github.com/charx0r/drupalcon-devcontent/blob/master/README.md" class="navbar-link a-class" target="_blank">Documentation</a></li>
  <li class="leaf navbar-item"><a href="https://github.com/charx0r/drupalcon-devcontent/" class="navbar-link a-class" target="_blank">Github</a></li>
  </ul> </div>
  </div>
  </div>
  </div>
</nav>
  <div class="container">
    <div class="row">
      <div class="two-third column" style="margin-top: 5%">
        <center><h2>Estimate your Drupal 8 project migration</h2></center>
        <p>The Drupal project calculator is in its infancy stage. The current set up requires you to look at your current Drupal 7 website and key in the modules used. The the calculator uses a set of rules and common formulas to project the cost escalation based on the current Drupal 8 Contrib module state.<br />
        <b>Motivation</b> - Data, visualizations are only as important as the knowledge, insights we extract from it to make better descisions. Help developers make better judgments while working on Feasibility, Risk and Cost analysis of a Drupal 8 project. See if your project is Drupal 8 ready<br />
        <center><span style="font-size: 120%;"><a href="state-of-drupal" target="_blank">Also checkout Status of Drupal 8 Contrib</a></span></center>
        <h4>Readiness Score:</h4> Readiness score defines the current Drupal contrib status available for your project. You will have to put in the remaining work effort. Readiness score is a factor of
        <ul>
          <li>Part of D8 core</li>
          <li>Partially part of D8 core</li>
          <li>D8 Stable release</li>
          <li>D8 Dev release</li>
          <li>D8 issues count</li>
          <li>D8 issue activity (tagged D8, D8dx)</li>
          <li>Overall maintainers acitivity (Todo:add the factor to the overall score)</li>
          <li>Average bug resolution time. <em>Click on each module for detailed stats. Only works for top 100 modules, so as to not overwhelm the drupal.org with api calls for the last 2 days.</em></li>
        </ul>
        </p>
        <p>
          Peep in and have a look at the code. <a href="https://github.com/charx0r/drupalcon-devcontent/" target="_blank">Github</a><br />
          Tech:
          <ul>
            <li>Python</li>
            <li>Mongo</li>
            <li>Scrapy</li>
            <li>D3.js</li>
            <li>Highcharts</li>
            <li>PHP</li>
          </ul>
        </p>
        <p>
          You can use any of the below module set for testing or build one of your own quickly. Validations not in place so sometimes things might not work as expected.
          <h6><b>Example module sets</b></h6>
          <ol>
            <li>rules, webform, token, views, admin_menu, ctools, date, pathauto, libraries, jquery_update, imce, ckeditor, google_analytics, link, module_filter, metatag, backup_migrate, xmlsitemap, views_slideshow, captcha, entityreference, menu_block, ds</li>
            <li>rules, webform, entity, views, admin_menu, ctools, token, pathauto, libraries, jquery_update, date, views_slideshow, colorbox, media, field_group, views_bulk_operations, panels, devel</li>
          </ol>
          
        </p>
        <!-- The above form looks like this -->
        <center><h2>Estimate Now!!</h2></center>
        <form action="estimations" method="POST" accept-charset="UTF-8">
          <div class="row">
            <div class="six columns">
              <label for="exampleEmailInput">Your Name</label>
              <input class="u-full-width" type="text" placeholder="John Doe" id="exampleEmailInput" name="user_name" value="John Doe">
            </div>
            <div class="six columns">
              <label for="exampleRecipientInput">Project name</label>
              <input class="u-full-width" type="text" placeholder="intranet" id="exampleEmailInput" name="project_name" value="Foobar">
            </div>
          </div>
          <label for="exampleMessage">Module List</label>
          <textarea class="u-full-width" placeholder="views,ctools …" id="exampleMessage" name="module_list"></textarea>
          <input class="button-primary" type="submit" value="Submit">
        </form>

        <!-- Always wrap checkbox and radio inputs in a label and use a <span class="label-body"> inside of it -->

        <!-- Note: The class .u-full-width is just a utility class shorthand for width: 100% -->
      </div>
    </div>
  </div>

<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
</body>
</html>
