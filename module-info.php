<?php
//Mongo Connection
$connection = new MongoClient();
$db = $connection->drupal_data;
$collection = $db->module_data;
$issue_collection = $db->issue_data;

//TODO move these into functions
$module_filter = array('field_project_machine_name' => trim($_GET['machine_name']));
$cursor = $collection->find($module_filter);

foreach ($cursor as $key => $module_info) {
	//just take the latest value, need to have versions in place while inserting through python
	$module_data = $module_info;
}

//TODO move this piece
//get maintainers info
$count = 0;
foreach ($module_data['maintainers'] as $maintainer_id => $maintainer_info) {
	$maintainers_data[$count]['uid'] = $maintainer_id;
	$maintainers_data[$count]['name'] = $maintainer_info['name'];	
	$count++;
}

//TODO move this
//maintainers activity
$maintainer_act_filter = array('project_id'=>$module_data['nid']);
$maintainer_cursor = $issue_collection->find($maintainer_act_filter);
foreach ($maintainer_cursor as $key => $issue_info) {
	foreach ($maintainers_data as $key => $maintainer_data) {
		if($maintainer_data['uid'] == $issue_info['author_id']) {
			$maintainers_data[$key]['timestamps'][] = $issue_info['created'];
		}
		foreach ($issue_info['comments_data'] as $key => $comment_data) {
			foreach ($maintainers_data as $key => $new_value) {
				if($new_value['uid'] == $comment_data['user_id']) {
					$maintainers_data[$key]['timestamps'][] = $comment_data['created_created'];		
				}
			}
			//if($maintainer_data['uid'] == $comment_data['user_id']) {
				//$maintainers_data[$key]['timestamps'][] = $comment_data['created_created'];		
			//}
		}
	}
}
//general activity




//TODO move this
//re-order maintainer activity for last year
foreach ($maintainers_data as $key => $maintainer_info) {
	$maintainers_data[$key]['activity']['feb-apr'] = $maintainers_data[$key]['activity']['may-jul'] = $maintainers_data[$key]['activity']['aug-oct'] = $maintainers_data[$key]['activity']['nov-jan'] = 0;
	foreach ($maintainer_info['timestamps'] as $time_value) {
		if($time_value > '1422748800' && $time_value < '1430438400') {
			$maintainers_data[$key]['activity']['feb-apr']++;
		}
		elseif($time_value > '1430438400' && $time_value < '1438387200') {
			$maintainers_data[$key]['activity']['may-jul']++;
		}
		elseif($time_value > '1438387200' && $time_value < '1446336000') {
			$maintainers_data[$key]['activity']['aug-oct']++;
		}
		elseif($time_value > '1446336000' && $time_value < '1454284800') {
			$maintainers_data[$key]['activity']['nov-jan']++;
		}
	}
}

//TODO move these into functions
//get average bug resolution time
$delta_filter = array('project_id'=>$module_data['nid'],'field_issue_status'=>array('$ne'=>'1'), 'field_issue_category'=>'1');

$delta_cursor = $issue_collection->find($delta_filter);
$issue_count = $delta_values = 0;

foreach ($delta_cursor as $key => $issue_info) {
	$delta_values += $issue_info['time_delta']; 
	$issue_count++;
}
if($issue_count != 0) {
	$average_res_time = round($delta_values/$issue_count);
}
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
</head>
<body>
<script src="js/highcharts.js"></script>
<script>
$(function () {
    $('#container').highcharts({
        title: {
            text: 'Maintainer quarterly activity in Issue Queue(<em>past year</em>)',
            x: -20 //center
        },
        credits: {
            enabled: false
        },
        subtitle: {
            text: 'Source: drupal.org API',
            x: -20
        },
        xAxis: {
            categories: ['Feb-Apr', 'May-July', 'Aug-Oct', 'Nov-Jan']
        },
        yAxis: {
            title: {
                text: 'Posts (count)'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: ' posts'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [
        	<?php
        	foreach ($maintainers_data as $key => $maint_activity) {
        		echo '{name: '."'".$maint_activity['name']."',";
        		echo 'data: '.'[';
        		foreach ($maint_activity['activity'] as $key => $post_value) {
        			echo $post_value.',';
        		}
        		echo ']},';
        	}
        	?>
        ]
    });
});
</script>
<?php include_once("analyticstracking.php") ?>
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
    <div class="two-third column" style="margin-top: 0">
    	<center><h2>Module Insights</h2></center>
			<table style="width: 100%">
				<tr style="border:none;">
					<td style="width: 10%;max-width:80px;text-align: left;vertical-align: top;">
					<?php
					//TODO: move this into a functiona as well
					if (count($module_data['field_project_images'])>0) {
						//get image details using FILE API
						header('Accept: application/json');
						$image_call_url = $module_data['field_project_images'][0]['file']['uri'].".json";
					  //  Initiate curl
					  $ch = curl_init();
					  // Disable SSL verification
					  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
					  // Will return the response, if false it print the response
					  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					  // Set the url
					  curl_setopt($ch, CURLOPT_URL,$image_call_url);
					  // Execute
					  $base_result=curl_exec($ch);
					  // Closing
					  curl_close($ch);
					  $base_result = json_decode($base_result);
						echo '<img src="'.$base_result->url.'" width="60px"/>';
					}
					else {
						//display default image
						echo '<img src="images/drupal.png" width="60px" />';
					}
					?>
					</td>
					<td>
					<h3><?php echo ucfirst($module_data['title']);?></h3>
					<a href="#" onclick="window.history.back();return false;">Go Back</a><br />
					<b>Download Count:</b> <?php echo $module_data['field_download_count'].'; ';?>
					<b>D7 Releases:</b> <?php if($module_data['field_project_has_releases']==1){echo 'Yes';}else{echo 'No';}?>
					<br /><br />
					<b>Average Bug Resolution time: </b><span class="partial-core"><?php echo $average_res_time. ' days';?></span><br /><br />
					<b>Maintainers: </b><em style="font-size: 80%;"><?php foreach ($maintainers_data as $key => $maint_data) {
						print $maint_data['name'].'; ';
					} ?></em>
					</td>
				</tr>
			</table>
			<div class="mod-desc">
				<b>Module Description: </b> <?php echo $module_data['body']['value'];?>
			</div>
			<table style="width: 100%" class="module-charts">
				<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
			</table>
		</div>
		</div>
	</div>
</body>
</html>
<?php
//echo '<pre>';
//print_r($module_data);
//echo '</pre>';
?>