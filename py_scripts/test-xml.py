import requests
import xml.etree.ElementTree
from bs4 import BeautifulSoup

url = 'https://updates.drupal.org/release-history/seven/8.x'
req = requests.get(url)
update_history = BeautifulSoup(req.content, 'lxml')
#print update_history

def get_stable_version(update_history):
	try:
		release_info = update_history.project.releases.findAll('release')
	except Exception, e:
		return False
	for release_data in release_info:
		if 'alpha' in release_data.version.contents[0] or 'dev' in release_data.version.contents[0] or 'beta' in release_data.version.contents[0] or 'rc' in release_data.version.contents[0]:
			pass
		else:
			return True
	return False

print get_stable_version(update_history)
#print e