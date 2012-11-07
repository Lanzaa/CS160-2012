#!/usr/bin/python
import httplib
import urllib
import urllib2
import re
import sys

print "Content-Type: text/html"
print 

def searchMonsterForSalary(location):
	conn = httplib.HTTPConnection('jobsearch.monster.com')
		
	params = {'where' : location}
		
	conn.request('GET','/search/?' + urllib.urlencode(params))
	urlStr = 'jobsearch.monster.com' + '/search/?' + urllib.urlencode(params) + '\n\n'
        
	response = conn.getresponse().read()
	response = urllib.unquote_plus( response )
	lines = response.split('\n')
	result = ""
	results = []
	results.append(urlStr)
	for line in lines:
		line = line.strip()
		if line.startswith('<label id='):
			match = re.search('>(.*?)<', line, re.IGNORECASE)
			title = match.group(1)
			result = "Title: " + title + '\n'
		elif line.startswith('<div class="companyConfidential">'):
			match = re.search('>(.*?)<', line, re.IGNORECASE)
			company = match.group(1)
			result += "Company: " + company + '\n'
		elif line.startswith('<div class="jobLocationSingleLine"> <a'):
			match = re.search('>.*?>(.*?)</a', line, re.IGNORECASE)
			location = match.group(1)
			result += "Location: " + location + "\n\n"
			results.append(result)

	return results
	

if(len(sys.argv) >= 2):
	results = searchMonsterForSalary(sys.argv[1])
	for result in results:
		print result

print "Hello"