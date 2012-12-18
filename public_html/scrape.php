<?php
// Scrape monster.com for job postings.
function getMonsterJobPostings($page) {
	global $monsterJobPostings;
	$html = file_get_html($page);
	$items = $html->find('tr.even, tr.odd');
	foreach ($items as $element) {
		$title = $element->children(1)->children(0)->children(0)->first_child()->title;
		$city = $element->children(2)->children(0)->children(0)->first_child()->innertext;
		$company = $element->children(1)->children(0)->children(1)->children(0)->children(0)->innertext;
		$salary = $element->children(1)->children(0)->children(1)->children(0)->children(2)->innertext;
		$link = $element->children(1)->children(0)->children(0)->first_child()->href;
		$requirements = getMonsterRequirements($page);

		$monsterJobPostings[] = array(
			'title' => $title,
			'city' => $city,
			'company' => $company,
			'salary' => $salary,
			'requirements' => $requirements,
			'link' => $link
		);
	}
	// clear and unset to prevent memory leaks
	$html->clear();
	unset($html);
}

// Scrape dice.com for job postings.
function getDiceJobPostings($page) {
	global $diceJobPostings;
	$html = file_get_html($page);
	$items = $html->find('tr.gold, tr.STDsrRes');
	foreach ($items as $element) {
		$title = $element->children(0)->children(0)->first_child()->innertext;
		$city = $element->children(2)->innertext;
		$company = $element->children(1)->children(0)->innertext;		
		$link = "http://seeker.dice.com/" . $element->children(0)->children(0)->first_child()->href;
		$requirements = getDiceRequirements($page);

		$diceJobPostings[] = array(
			'title' => $title,
			'city' => $city,
			'company' => $company,
			'salary' => $salary,
			'requirements' => $requirements,
			'link' => $link
		);
	}
	// clear and unset to prevent memory leaks
	$html->clear();
	unset($html);
}

// possible education requirements
$requirementsArray = array(
	"0-1 years experience",
	"1-2 years experience",
	"2-3 years experience",
	"3-4 years experience",
	"4-5 years experience",
	"5-6 years experience",
	"6-7 years experience",
	"7-8 years experience",
	"8+ years experience",
	"10+ years experience"
);

// TODO Parse the requirements for a monster.com job listing.
function getMonsterRequirements($page) {
	global $requrementsArray;
	return $requirementsArray[rand(0, sizeof($requirementsArray) - 1)];
}

// TODO Parse the requirements for a dice.com job listing.
function getDiceRequirements($page) {
	global $requrementsArray;
	return $requirementsArray[rand(0,sizeof($requirementsArray) - 1)];
}

// Write the results to a JSON file.
function outputJSON($inputData) {
    echo json_encode($inputData);
}

// Create the url to scrape monster.com
function createMonsterURL() {
	global $inLocation, $inKeyword;
	global $inSalary, $inEducation;
	$data = array();
	$url = "http://jobsearch.monster.com/search/?";
	// if keyword is not null, add to the url
	if (strlen($inKeyword) > 0)
		$data['q'] = $inKeyword;
	// if location is not null, add to the url
	if (strlen($inLocation) > 0)
		$data["where"] = $inLocation;
	// if salary is not null, add to the url
	if(isset($inSalary) && $inSalary != "") {
		$data["salmin"] = $inSalary;
		$data["saltyp"] = "1";
		$data["nosal"] = "false";
	}
	// if education is not null, add to the url
	if(isset($inEducation) && $inEducation != "")
		$data["eid"] = $inEducation;
	// convert special characters
	$url .= convertToMonsterEncoding(http_build_query($data));
	return $url;
}

// Convert special characters to be compatible with monster.com.
function convertToMonsterEncoding($url) {
	// TODO possibly more special characters to consider
	$url = str_replace("+","-",$url);
	return $url;
}

// Create the url to scrape dice.com
function createDiceURL() {
	global $inLocation, $inKeyword;
	$data = array(
		'FREE_TEXT' => $inKeyword,
		'WHERE' => $inLocation
	);
	$url = "http://seeker.dice.com/jobsearch/servlet/JobSearch?op=300&N=0&Hf=0&NUM_PER_PAGE=30&Ntk=JobSearchRanking&Ntx=mode+matchall&AREA_CODES=&AC_COUNTRY=1525&QUICK=1&ZIPCODE=&RADIUS=64.37376&ZC_COUNTRY=0&COUNTRY=1525&STAT_PROV=0&METRO_AREA=33.78715899%2C-84.39164034&TRAVEL=0&TAXTERM=0&SORTSPEC=0&FRMT=0&DAYSBACK=30&LOCATION_OPTION=2&";
	$url .= http_build_query($data);
	return $url;
}

// Remove potential duplicates and merge results from both pages
function mergeJobPostings($monsterJP, $diceJP) {
	// remove duplicate job posts from $diceJP
	for ($i = 0; $i < sizeof($monsterJP); $i++) {
		for ($j = 0; $j < sizeof($diceJP); $j++) {
			$val = compareJobPosts($monsterJP[$i], $diceJP[$j]);
			// TODO just deletes dice's posting, change to delete the post with less information
			if ($val == 0) {
				unset($diceJP[$j]);
				break;
			}
		}
	}
	// combine the results from monster and dice
	$mergedJP = array_merge($monsterJP, $diceJP);
	//shuffle the results to avoid preferential treatment of any one site
	shuffle($mergedJP);
	return $mergedJP;
}

// Compare two job posts to see if they are for the same job.
function compareJobPosts($jp1, $jp2) {
	return ( abs(strcmp($jp1['title'], $jp2['title'])) +
			 abs(strcmp($jp1['city'], $jp2['city'])) +
			 abs(strcmp($jp1['company'], $jp2['company'])) );
}

// ******************Start Scraping******************
// import simplehtmldom library for parsing html
include('simple_html_dom.php');

// input variables, things to search by
$inLocation = $_GET['location'];
$inKeyword = $_GET['keyword'];
$inSalary = $_GET['salary'];
$inEducation = $_GET['education'];

// array of dictionary items holding found job postings
$monsterJobPostings = array();
$diceJobPostings = array();
$jobPostings = array();

// scrape websites
// if salary and education are not set, scrape dice.com
if ((!(isset($inSalary)) || $inSalary == "") && 
		(!isset($inEducation) || $inEducation == ""))
	getDiceJobPostings(createDiceURL());
getMonsterJobPostings(createMonsterURL());

// merge job postings
$jobPostings = mergeJobPostings($monsterJobPostings, $diceJobPostings);

// output json
outputJSON($jobPostings);

?>
