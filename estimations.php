<?php

//Build on this list
$core_list = array('breakpoint','cache_tags','entity_translation','email','entity','entityreference','entity_view_mode','file_entity','link','phone','picture','quickedit','transliteration','uuid','views','restws','ckeditor','strongarm','menu_block','boxes','jquery_update');
$partial_list = array('ctools','date','ds','migrate','views_bulk_operations');
$removed_list = array('blog','dashboard','openid_connect','overlay','poll');


$project_modules = explode(",", $_POST['module_list']);

$complete_percentage = 0;
foreach ($project_modules as $mod_count => $module_key) {
	$project_info[$mod_count]['name'] = trim($module_key);
	//check if module exists in core
	if(in_array(trim($module_key), $core_list)) {
		//print $module_key. ": is part of core".'</br >';
		$complete_percentage += 1;
		$project_info[$mod_count]['score'] = 1;
		$project_info[$mod_count]['d8_stable'] = 'core';
		$project_info[$mod_count]['d8_dev'] = 'Yes';
	}
	//check if module exists in partial list
	elseif (in_array(trim($module_key), $partial_list)) {
		//print $module_key. ": partially in core".'</br >';
		$complete_percentage += 0.8;
		$project_info[$mod_count]['score'] = 0.8;
		$project_info[$mod_count]['d8_stable'] = 'partial-core';
		$project_info[$mod_count]['d8_dev'] = 'Yes';
	}

	//check if module has d8 release
	else {
		//Mongo Connection
		$connection = new MongoClient();

		$db = $connection->drupal_data;

		$collection = $db->module_data;
		$filter = array('field_project_machine_name' => trim($module_key));

		$cursor = $collection->find($filter);
		foreach ($cursor as $key => $module_info) {
			//print $module_info['title'];
			//print "Release status of ".$module_key." is : " . $module_info['release_exists'].'</br >';
			if($module_info['release_exists'] == 2) {
				//alpha / beta version / rc version available
				$complete_percentage += 0.6;
				$project_info[$mod_count]['d8_dev'] = 'Yes';
				$project_info[$mod_count]['d8_stable'] = 'none';
				$project_info[$mod_count]['score'] = 0.6;
				//look at response rate of D8 issue queue and add to the score
				//print count($module_info['issue_data']). '<br />';
			}
			elseif($module_info['release_exists'] == 1) {
				$complete_percentage += 1;
				$project_info[$mod_count]['d8_stable'] = 'stable';
				$project_info[$mod_count]['d8_dev'] = 'Yes';
				$project_info[$mod_count]['score'] = 1;
				//look at response rate of D8 issue queue and add to the score
				//print count($module_info['issue_data']). '<br />';
			}
			elseif ($module_info['release_exists'] == 0) {
				//do nothing
				//check for any discussions
				$project_info[$mod_count]['d8_dev'] = 'No';
				$project_info[$mod_count]['d8_stable'] = 'none';
				$project_info[$mod_count]['score'] = 0;
				//look at response rate of D8 issue queue and add to the score
				if(count($module_info['issue_data']) > 0) {

				}
				//print count($module_info['issue_data']). '<br />';
			}
		}
	}

	//check if module exists

	//module doesn't exist
}
//print "Readiness percentage: " . (($complete_percentage/sizeof($project_modules))*100).'<br />';
$project_readiness = (($complete_percentage/sizeof($project_modules))*100);
?>
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
  <link rel="stylesheet" type="text/css" href="css/estimations.css">

  <!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="icon" type="image/png" href="images/favicon.png">

  <script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function () {
		DrawPie();
	});
      //$(function () {
      function DrawPie(){
          $('#container').highcharts({
              chart: {
                  plotBackgroundColor: null,
                  plotBorderWidth: null,
                  plotShadow: false,
                  type: 'pie'
              },
              title: {
      			verticalAlign: 'middle',
                  floating: true,
                  text: <?php print "'<span style=".'"font-weight:bold;">'.round($project_readiness)."%</span>'";?>
              },
              credits: {
                       enabled: false
               },
              tooltip: {
                  pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
              },
              plotOptions: {
                  pie: {
      				innerSize: '50%',
                      allowPointSelect: true,
                      cursor: 'pointer',
                      dataLabels: {
                          enabled: false,
                          format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                          style: {
                              color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                          }
                      }
                  }
              },
              series: [{
                  name: "Percentage",
                  colorByPoint: true,
                  data: [{
                      name: "Readiness",
                      y: <?php print $project_readiness;?>,
                      color: '#62072E'
                  },
                  {
                      name: "ToDo",
                      y: <?php print (100-$project_readiness);?>,
                      color: "#fff"
                  }
                  ]
              }]
          });
      }
	</script>
</head>
<body>
<script src="js/highcharts.js"></script>
<?php include_once("analyticstracking.php") ?>
	<div class="container">
    <div class="row">
    <div class="two-third column" style="margin-top: 1%">
    	<center><h2><a href="/drupal_data">Estimations</a></h2></center>
			<table style="width: 100%">
				<tr style="border:none;">
					<td style="width: 20%;text-align: left;">
					<div id="container" style="min-width: 120px; height: 160px; max-width: 300px; margin: 0 auto"></div>
					<center><b>Readiness Score</b></center>
					</td>
					<td><h3><?php echo "Project: ".ucfirst($_POST['project_name']);?></h3></td>
				</tr>
			</table>
			<p>
				<b>Readiness Score: </b> Readiness score defines the current Drupal contrib status available for your project. You will have to put in the remaining work effort.<br />
				Readiness score is a factor of
				<ul>
					<li>Part of D8 core</li>
					<li>Partially part of D8 core</li>
					<li>D8 Stable release</li>
					<li>D8 Dev release</li>
					<li>D8 issues count</li>
					<li>D8 issue activity (tagged D8, D8dx)</li>
					<li>Overall maintainers acitivity (<em style="font-size: 70%;">Todo:add the factor to the overall score</em>)</li>
					<li>Average bug resolution time. <em style="font-size: 85%;font-weight: bold;">Click on each module for detailed stats. Only works for top 100 modules, so as to not overwhelm the drupal.org with api calls for the last 2 days.</em></li>
				</ul>
				<b>Cost Escalation: </b> Your project cost could escalate by <b><?php print round(106-$project_readiness);?>%</b>, including the Drupal 8 learning curve invloved.
			</p>
			<table style="width: 100%" class="module-status">
				<thead>
					<tr>
						<th>Module Name</th>
						<th>D8 Stable</th>
						<th>D8 Dev Branch</th>
						<th>D8 Score</th>
					</tr>
					<?php
						foreach ($project_info as $key => $project_data) {
							if($project_data['name'] != '') {
								echo "<tr>";
								echo '<td><a href="module-info?machine_name='.$project_data['name'].'">'.ucwords(str_replace('_', ' ', $project_data['name'])).'</a></td>';
								echo '<td>';
								if($project_data['d8_stable'] == 'core') {
									echo '<span class="in-core">Core</span>';
								}
								elseif($project_data['d8_stable'] == 'partial-core') {
									echo '<span class="partial-core">Partial Core</span>';
								}
								elseif($project_data['d8_stable'] == 'stable') {
									echo '<span class="stable-rel">Yes</span>';
								}
								else {
									echo '<span class="no-rel">No</span>';
								}
								echo '</td>';
								echo '<td>';
								if ($project_data['d8_dev'] == 'Yes') {
									echo '<span class="dev-avail">'.$project_data['d8_dev'].'</span>';
								}
								else {
									echo '<span class="dev-not-avail">'.$project_data['d8_dev'].'</span>';
								}
								echo '</td>';
								echo '<td>'.$project_data['score'].'</td>';
								echo '</tr>';
							}
						}
					?>
				</thead>
				<tr>
					
				</tr>
			</table>
		</div>
		</div>
	</div>
</body>
</html>