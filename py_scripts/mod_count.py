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

mod_count = 0
dev_count = 0
stable_count = 0
committers_count = 0
for update_info in update_collection.find():
	if len(update_info['update_history']) != 0:
		mod_count += 1
		for mod_info in module_collection.find({'nid':update_info['nid']}):
			committers_count += len(mod_info['maintainers'])
		for update_info_det in update_info['update_history']:
			if "beta" in update_info_det['version']:
				dev_count += 1
print mod_count
print dev_count
print committers_count