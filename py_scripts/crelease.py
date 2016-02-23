import sys
from pymongo import MongoClient
import json
import pprint
import time
import datetime

#Mongo connection
client = MongoClient('localhost', 27017)
db = client['drupal_data']
module_collection = db['module_data']
update_collection = db['update_data']
crelease_collection = db['crelease_collection']

release_data = dict()
bubble_date = dict()
for module_release in update_collection.find():
	#lloop  releases
	for release_info in module_release['update_history']:
		#convert timestamp to date
		current_timestamp = release_info['date']
		if len(release_info['date']) == 10:
			current_date = datetime.datetime.fromtimestamp(int(current_timestamp)).strftime('%Y-%m-%d')
			current_time = datetime.datetime.fromtimestamp(int(current_timestamp)).strftime('%H')
			current_day = datetime.datetime.fromtimestamp(int(current_timestamp)).weekday()
			composite_key = str(current_day) + str(current_time)
			if current_date in release_data:
				release_data[current_date] += 1
			else:
				release_data[current_date] = 1
			if composite_key in bubble_date:
				bubble_date[composite_key] += 1
			else:
				bubble_date[composite_key] = 1
#pprint.pprint(release_data)
#pprint.pprint(bubble_date)
import csv
with open('contrib_release_growth.csv', 'w') as outfile:
  #json.dump(release_data, outfile)
  cr = csv.writer(outfile, delimiter=',')
  csv_data= []
  for key in release_data:
  	temp_rl_data = [key, release_data[key]]
  	csv_data.append(temp_rl_data)
  cr.writerows(csv_data)

with open('bubble_release.csv', 'w') as fp:
    a = csv.writer(fp, delimiter=',')
    data = []
    for key in bubble_date:
    	temp_data = []
    	if str(key)[:1] == '0':
    		temp_data = ['Sunday', str(key)[-2:],bubble_date[key], 'Drupal']
    		data.append(temp_data)
    	elif str(key)[:1] == '1':
    		temp_data = ['Monday', str(key)[-2:],bubble_date[key], 'Drupal']
    		data.append(temp_data)
    	elif str(key)[:1] == '2':
    		temp_data = ['Tuesday', str(key)[-2:],bubble_date[key], 'Drupal']
    		data.append(temp_data)
    	elif str(key)[:1] == '3':
    		temp_data = ['Wednesday', str(key)[-2:],bubble_date[key], 'Drupal']
    		data.append(temp_data)
    	elif str(key)[:1] == '4':
    		temp_data = ['Thursday', str(key)[-2:],bubble_date[key], 'Drupal']
    		data.append(temp_data)
    	elif str(key)[:1] == '5':
    		temp_data = ['Friday', str(key)[-2:],bubble_date[key], 'Drupal']
    		data.append(temp_data)
    	elif str(key)[:1] == '6':
    		temp_data = ['Saturday', str(key)[-2:],bubble_date[key], 'Drupal']
    		data.append(temp_data)
    print data
    a.writerows(data)
