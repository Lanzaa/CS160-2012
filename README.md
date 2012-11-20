CS160-2012
==========

CS 160 project reposity for group 2



## Unified Data Format

We are going to use a json format for our job listings. The data will contain the following data:

* title: string, the title of the job
* company: string, the name of the company the job posting is for
* salary: int, the salary for the job, $USD/year
* requirements: string, the requirements for the job as a string
* link: string, the url of the job posting

```javascript
{
	"title": "Software Monkey",
	"company": "International Banana Monkeys",
	"city": "Drawbridge, California",
	"salary": 65000,
	"requirements": "Potty Training, 3 years experience",
	"link": "http://www.petsorfood.com/wordpress/products-page/mammals/spider-monkey/",
}
```

