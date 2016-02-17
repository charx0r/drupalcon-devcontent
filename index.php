<!DOCTYPE html>
<html lang="en">
<head>

  <!-- Basic Page Needs
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta charset="utf-8">
  <title>Drupal 8 Project Estimation</title>
  <meta name="description" content="Estimate your Drupal 7 to 8 project migration based on contrib module status">
  <meta name="author" content="Charan Puvvala">

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

  <!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="icon" type="image/png" href="images/favicon.png">

</head>
<body>
<?php include_once("analyticstracking.php") ?>
  <!-- Primary Page Layout
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <div class="container">
    <div class="row">
      <div class="two-third column" style="margin-top: 5%">
        <h2>Estimate your Drupal 8 project migration</h2>
        <p>The Drupal project calculator is in its infancy stage. The current set up requires you to look at your current Drupal 7 website and key in the modules used. The the calculator uses a set of rules and common formulas to project the cost escalation based on the current Drupal 8 Contrib module state.
        <h4>Few metrics used</h4>
        <ul>
          <li>Release status</li>
          <li>Release Version (alpha, beta, dev, rc, stable?)</li>
          <li>Current discussion in issue queue on D8 port</li>
          <li>Average respond rate on current issue queue relative to overall issue queue</li>
          <li>Overall maintainers activity</li>
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
