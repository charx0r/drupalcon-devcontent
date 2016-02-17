# DrupalCon Mumbai - devcontent
Drupal 8 Project estimator

Motivation - Data, visualizations are only as important as the knowledge we extract from it to make better descisions. Help developers make better judgments while working on Feasibility, Risk and Cost analysis of a Drupal 8 project. See if your project is Drupal 8 ready.

##Demo URL
http://52.36.81.128/drupal_data/

##Mongo Connection
52.36.81.128 - default mongo port. - So you dont have to do the import stuff

##Installation
- Python > 2.6
- Mongodb
- Apache / Nginx
- PHP
- php5-curl
- python-pip
- pip install BeautifulSoup4 pymongo 
- Create database in mongo called drupal_data, then create two empty collection module_data, issue_data
- Run the first script load_modules.py (Use nohup as this can take sometime, 5-6 hrs)
- Run the second script get_issues_cron.py
- Move the php code into Apache / Nginx

##Environment
- Using AWS t2.large instance with Ubuntu 14.04 server edition

##Estimator
- <b>Readiness Score: </b> Readiness score defines the current Drupal contrib status available for your project. You will have to put in the remaining work effort.<br />
- <b>Maintainer activity chart: </b> Display line chart from the data of last year's issues, comments. Need to add commits to this activity metric.
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
- <b>Average bug resolution time: </b> Only considers issues which have been marked as "Bug Report" and closed with status 3,7,9. Need to extend this Average Support resolution time as well.
