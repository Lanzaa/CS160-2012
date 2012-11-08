#!/usr/bin/python
import httplib
import urllib
import urllib2
import re
import sys

print "Content-Type: text/html"
print 

def searchMonsterForSalary(min_salary):

	conn = httplib.HTTPConnection('jobsearch.monster.com')
		
	params = {'salmin' : min_salary, 'saltyp' : '1', 'nosal' : 'false'}
		
	conn.request('GET','/search/?' + urllib.urlencode(params))

	response = conn.getresponse().read()
	response = urllib.unquote_plus( response )
	lines = response.split('\n')
	result = ""
	results = []
	for line in lines:
		line = line.strip()
		if line.startswith('<label id='):
			match = re.search('>(.*?)<', line, re.IGNORECASE)
			title = match.group(1)
			result = title
		elif line.startswith('<div class="companyConfidential">'):
			match = re.search('>(.*?)<', line, re.IGNORECASE)
			company = match.group(1)
			result += " - " + company
		elif line.startswith("<div class='bulletSeparator'>&bull;</div>"):
			match = re.search("'fnt13'>(.*?)<", line, re.IGNORECASE)
			salary = match.group(1)
			result += " (" + salary + ")"
			results.append(result)
			
	return results
	

if(len(sys.argv) >= 2):
	results = searchMonsterForSalary(sys.argv[1])
	for result in results:
		print result

print "Hello"


#print "Hello World"

