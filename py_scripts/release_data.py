
"""
    release_data
    ~~~~~~~~~~~~~
    Recursively call the releases xml endpoint and store the release info, timestamps in mongo.
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
import xml

#Drupal Endpoints
RELEASE_URL = 'https://updates.drupal.org/release-history/'
VERSION_NUMBER = '/8.x'

#Mongo connection
client = MongoClient('localhost', 27017)
db = client['drupal_data']
module_collection = db['module_data']
update_collection = db['update_data']

#returns if there is a stable d8 version available for a module
#returns 0 - no release available
#returns 1 - stable release available
#returns 2 - dev release available
def get_version_history(update_history):
	update_data = []
	try:
		release_info = update_history.project.releases.findAll('release')
	except Exception, e:
		print e
		return update_data
	for release_data in release_info:
		update_item = {}
		update_item['name'] = release_data.find('name').contents[0]
		update_item['version'] = release_data.find('version').contents[0]
		try:
			update_item['date'] = release_data.find('date').contents[0]
		except Exception, e:
			update_item['date'] = ""
		update_data.append(update_item)
	return update_data

for module_data in module_collection.find():
	print module_data['nid']
	#store release data
	basic_module_data = {}
	release_get_url = RELEASE_URL + module_data['field_project_machine_name'] + VERSION_NUMBER
	release_data = requests.get(release_get_url).content
	update_history = BeautifulSoup(release_data, "lxml")
	basic_module_data['update_history'] = get_version_history(update_history)
	basic_module_data['field_project_machine_name'] = module_data['field_project_machine_name']
	basic_module_data['nid'] = module_data['nid']
	basic_module_data['project_name'] = module_data['title']
	basic_module_data['maintainers'] = module_data['maintainers']

	nid_key = {'nid':basic_module_data['nid']}
	update_collection.update(nid_key, basic_module_data, upsert=True)