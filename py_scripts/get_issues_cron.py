

"""
    get_issue_cron
    ~~~~~~~~~~~~~
    This is supposed to run after load_modules. This script will recursively build issue,comment data for modules
    in the data store
    Schedule this every week
    :copyright: (c) 2016 by Charan P <charan.puvvala@gmail.com>, Harshitha P
    :license: TODO:.
"""

import requests
from bs4 import BeautifulSoup
import re
import sys
from pymongo import MongoClient
import json
import pprint
import time
import xml.etree.ElementTree
import urlparse
from datetime import date
import datetime as dt

#Mongo connection
client = MongoClient('localhost', 27017)
db = client['drupal_data']
module_collection = db['module_data']
issue_collection = db['issue_data']

#Drupal Endpoints
NODE_URL = 'https://www.drupal.org/api-d7/node.json'

def parse_comment_json(comment_json):
	comment_timestamps = []
	for comment_info in comment_json:
		comment_data = {}
		comment_data['created_created'] = comment_info['created']
 		comment_data['cid'] = comment_info['cid']
 		try:
 			comment_data['user_id'] = comment_info['author']['id']
 		except Exception, e:
 			print "no author for this comment"
 		comment_data['name'] = comment_info['name']
 		comment_timestamps.append(comment_data)
 	return comment_timestamps

def parse_issue_json(issue_json):
	issue_data = []
	for issue_info in issue_json:
		current_issue = {}
		current_issue['created'] = issue_info['created']
		current_issue['project_id'] = issue_info['field_project']['id']
		current_issue['nid'] = issue_info['nid']
		current_issue['field_issue_status'] = issue_info['field_issue_status']
		current_issue['field_issue_category'] = issue_info['field_issue_category']
		current_issue['field_issue_priority'] = issue_info['field_issue_priority']
		current_issue['field_issue_component'] = issue_info['field_issue_component']
		current_issue['field_issue_version'] = issue_info['field_issue_version']
		current_issue['author_id'] = issue_info['author']['id']
		current_issue['comment_count'] = issue_info['comment_count']
		current_issue['last_comment_timestamp'] = issue_info['last_comment_timestamp']
		last_date = float(current_issue['last_comment_timestamp']) 
		create_date = float(current_issue['created'])
		time_delta = int((last_date - create_date)/86400)
		current_issue['time_delta'] = time_delta
		#print issue_info['nid']
		
		#loop through the comments data for each issue and store
	 	#get comment for a nid
	 	comment_timestamps = []
	 	comment_pager = 0
	 	get_comment_url = 'https://www.drupal.org/api-d7/comment.json?node=' + str(issue_info['nid']) + '&page=' + str(comment_pager)
	 	comment_list = json.loads(requests.get(get_comment_url).content)
	 	#get number of pages, some big issues can have comments running into 5-6 pages
	 	c_response_par = urlparse.parse_qs(urlparse.urlparse(comment_list['last']).query)
		c_page_count = int(c_response_par['page'][0])
		try:
			comment_timestamps = parse_comment_json(comment_list['list'])
		except Exception, e:
			print "issue parsing comments"
		if c_page_count !=0:
			for x in xrange(1,c_page_count):
				if x <= 10:
					get_comment_url = 'https://www.drupal.org/api-d7/comment.json?node=' + str(issue_info['nid']) + '&page=' + str(x)
					comment_list = json.loads(requests.get(get_comment_url).content)
					try:
						comment_timestamps = parse_comment_json(comment_list['list'])
					except Exception, e:
						print "issue parsing comments"
					comment_timestamps.append(curr_comment_timestamps)
				else:
					pass
		current_issue['comments_data'] = comment_timestamps

		#pprint.pprint(current_issue)
		nid_key = {'nid':current_issue['nid']}
		issue_collection.update(nid_key, current_issue, upsert=True)

#loop all modules
for module_data in module_collection.find({},{'nid':1}).sort([('nid',-1)]).limit(34000):
	current_pager = 0
	try:
		print "Module Id is: " + module_data['nid']
		issue_start_url = NODE_URL + '?type=project_issue&field_project=' + str(module_data['nid']) + '&page=' + str(current_pager) + '&sort=created&direction=DESC'
		issue_reponse = json.loads(requests.get(issue_start_url).content)
		response_par = urlparse.parse_qs(urlparse.urlparse(issue_reponse['last']).query)
		page_count = int(response_par['page'][0])
		parse_issue_json(issue_reponse['list'])
		if page_count != 0:
			for x in xrange(1,page_count):
				if x <= 5:
					issue_get_url = NODE_URL + '?type=project_issue&field_project=' + str(module_data['nid']) + '&page=' + str(x) + '&sort=created&direction=DESC'
					issue_reponse = json.loads(requests.get(issue_start_url).content)
					try:
						parse_issue_json(issue_reponse['list'])
					except Exception, e:
						print "some error in saving issue"
						print e
				else:
					pass
	except Exception, e:
		print "some error in saving issue"
		print e
	