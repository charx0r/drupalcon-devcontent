
"""
    load_modules
    ~~~~~~~~~~~~~
    Recursively call the node.json endpoint and store the module info in mongo.
    run this every month to get new modules into the datastore.
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

#Drupal Endpoints
NODE_URL = 'https://www.drupal.org/api-d7/node.json'
PROJECT_BASE_URL = 'https://www.drupal.org/project/'
RELEASE_URL = 'https://updates.drupal.org/release-history/'
VERSION_NUMBER = '/8.x'
PROJECT_URL_RES = '/maintainers.json'
ENTITY_PROJECT = 'project_module'
ENTITY_ISSUE = 'project_issue'
D8_TAXONOMY = '20540'

#Mongo connection
client = MongoClient('localhost', 27017)
db = client['drupal_data']
module_collection = db['module_data']

#returns if there is a stable d8 version available for a module
#returns 0 - no release available
#returns 1 - stable release available
#returns 2 - dev release available
def get_stable_version(update_history):
	try:
		release_info = update_history.project.releases.findAll('release')
	except Exception, e:
		return 0
	for release_data in release_info:
		if 'alpha' in release_data.version.contents[0] or 'dev' in release_data.version.contents[0] or 'beta' in release_data.version.contents[0] or 'rc' in release_data.version.contents[0]:
			pass
		else:
			return 1
	return 2

#just loop through the 331 pages and fetch module info
#store the 33100 module info in mongo for futher processing
for x in xrange(0,331):
	print "Loop number:" + str(x)
	url = NODE_URL + '?type=' + ENTITY_PROJECT + '&page=' + str(x)
	req = requests.get(url)
	module_list = req.content
	#parse json
	module_list = json.loads(module_list)

	#load each module as seperate document so we can add more details from other endpoints
	for basic_module_data in module_list['list']:
		try:
			#get maintainers data and attach to document
			maintainers_url = PROJECT_BASE_URL + basic_module_data['nid'] + PROJECT_URL_RES
			maintainers_json = json.loads(requests.get(maintainers_url).content)
			basic_module_data['maintainers'] = maintainers_json

			#print module nid just for the log
			print "Current Project Id is: " + str(basic_module_data['nid'])
			current_nid = basic_module_data['nid']

			#Get D8 issue data
			#Run some analysis and then store the info
			#Store issue related details
			#Store comment related details on each issue
			d8_issue_url = NODE_URL + '?type=' + ENTITY_ISSUE + '&field_project=' + basic_module_data['nid'] + '&taxonomy_vocabulary_9=' + D8_TAXONOMY
						
			d8_issue_data = json.loads(requests.get(d8_issue_url).content)
			#pprint.pprint(d8_issue_data)
			issue_history = []

			'''loop through the issue list and store basic data for analysis
					TODO: move this to a new function
			'''
			try:
				for d8_issue_info in d8_issue_data['list']:
					#pprint.pprint(d8_issue_info)
					print d8_issue_info['nid']
					issue_data = {}
				 	issue_data['issue_created'] = d8_issue_info['created']
				 	issue_data['comment_count'] = d8_issue_info['comment_count']
				 	issue_data['last_comment_timestamp'] = d8_issue_info['last_comment_timestamp']
				 	issue_data['issue_nid'] = d8_issue_info['nid']
				 	issue_data['field_issue_status'] = d8_issue_info['field_issue_status']
				 	issue_data['field_issue_priority'] = d8_issue_info['field_issue_priority']
				 	issue_data['field_issue_category'] = d8_issue_info['field_issue_category']
				 	issue_data['issue_url'] = d8_issue_info['url']
				 	
				 	comment_timestamps = []

				 	#loop through the comments data for each issue and store
				 	#get comment for a nid
				 	get_comment_url = 'https://www.drupal.org/api-d7/comment.json?node=' + str(d8_issue_info['nid'])
				 	comment_list = json.loads(requests.get(get_comment_url).content)
				 	print comment_list
				 	for comment_info in comment_list['list']:
				 		comment_data = {}
				 		comment_data['created_created'] = comment_info['created']
				 		comment_data['cid'] = comment_info['cid']
				 		comment_data['user_id'] = comment_info['author']['id']
				 		comment_data['name'] = comment_info['name']
				 		comment_timestamps.append(comment_data)
				 	issue_data['comments_data'] = comment_timestamps
					issue_history.append(issue_data)
			except Exception, e:
				print "error at issue_data"
				print e
			#print issue_history
			basic_module_data['issue_data'] = issue_history

			#store release data
			release_get_url = RELEASE_URL + basic_module_data['field_project_machine_name'] + VERSION_NUMBER
			release_data = requests.get(release_get_url).content
			update_history = BeautifulSoup(release_data, 'lxml')
			basic_module_data['release_exists'] = get_stable_version(update_history)

			#get maintainers last activity
			#ok, Lets do this analysis somewhere else, just store data for now

			#store basic module data along with maintainers, issue details, comment details in mongo		
			#module_collection.insert(basic_module_data)
			nid_key = {'nid':current_nid}
			module_collection.update(nid_key, basic_module_data, upsert=True)
		except Exception, e:
			print "no json found for the current nid"
			print e
		#sys.exit()
	#give 2 sec delay for each page call, so we don't choke the API
	#time.sleep(2)
