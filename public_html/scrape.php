
<?php
$salaryArray = array(
	"$50,000 - $60,000",
	"$55,000 - $65,000",
	"$60,000 - $70,000",
	"$65,000 - $75,000",
	"$75,000 - $85,000",
	"$85,000 - $95,000",
	"$95,000 - $105,000",
	"$105,000 - $115,000",
	"$115,000 - $125,000",
	"$125,000 - $135,000",
);

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

function getMonsterJobPostings($page) {	
	global $monsterJobPostings, $requirementsArray, $salaryArray;
	$html = file_get_html($page);
	$items = $html->find('tr.even, tr.odd'); 
	foreach ($items as $element) {
		$title = $element->children(1)->children(0)->children(0)->first_child()->title;
		$city = $element->children(2)->children(0)->children(0)->first_child()->innertext;
		$company = $element->children(1)->children(0)->children(1)->children(0)->children(0)->innertext;
		$salary = $element->children(1)->children(0)->children(1)->children(0)->children(2)->innertext;		
		$link = $element->children(1)->children(0)->children(0)->first_child()->href;
		$requirements = $requirementsArray[rand(0,sizeof($requirementsArray) - 1)];
		
		/*if (sizeof($salary) == 0) {
			$salary = $salaryArray[rand(0,sizeof($salaryArray) - 1)];
		}*/
	
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

function getDiceJobPostings($page) {	
	global $diceJobPostings, $requirementsArray, $salaryArray;;
	$html = file_get_html($page);
	
	$items = $html->find('tr.gold, tr.STDsrRes');
	foreach ($items as $element) {
		$title = $element->children(0)->children(0)->first_child()->innertext;
		$city = $element->children(2)->innertext;
		$company = $element->children(1)->children(0)->innertext;		
		$link = "http://seeker.dice.com/" . $element->children(0)->children(0)->first_child()->href;
		$requirements = $requirementsArray[rand(0,sizeof($requirementsArray) - 1)];
		//$salary = $salaryArray[rand(0,sizeof($salaryArray) - 1)];

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

// TODO
function getMonsterRequirements($page) {

}

// TODO 
function getDiceRequirements($page) {
	
}

// Ouput array of dictionary items to HTML
function outputDicDataToHTML($items) {
	echo "<div>";
	foreach ($items as $item) {
		foreach ($item as $key => $value) {
			echo $key . ": " . $value . "<br>";
		}
		echo "<br>";
	}
	echo "</div>";
}

function writeToJsonFile($inputData) {
	$fp = fopen('./results/results.json', 'w');
    fwrite($fp, json_encode($inputData));
    fclose($fp);
}

function createMonsterURL() {
	global $inLocation, $inKeyword;
	global $inSalary, $inEducation;
	$data = array();
	$url = "http://jobsearch.monster.com/search/?";
	
	if (strlen($inKeyword) > 0) {
		$data['q'] = $inKeyword;
	}
	
	if (strlen($inLocation) > 0) {
		$data["where"] = $inLocation;
	}

	if(isset($inSalary) && $inSalary != ""){
		$data["salmin"] = $inSalary;
		$data["saltyp"] = "1";
		$data["nosal"] = "false";
	}

	if(isset($inEducation) && $inEducation != ""){
		$data["eid"] = $inEducation;
	}
	
	$url .= convertToMonsterEncoding(http_build_query($data));
	 echo $url . "<br><br>";
	return $url;
}

function createDiceURL() {
	global $inLocation, $inKeyword;
	$data = array(
		'FREE_TEXT' => $inKeyword,
		'WHERE' => $inLocation
	);
	$url = "http://seeker.dice.com/jobsearch/servlet/JobSearch?op=300&N=0&Hf=0&NUM_PER_PAGE=30&Ntk=JobSearchRanking&Ntx=mode+matchall&AREA_CODES=&AC_COUNTRY=1525&QUICK=1&ZIPCODE=&RADIUS=64.37376&ZC_COUNTRY=0&COUNTRY=1525&STAT_PROV=0&METRO_AREA=33.78715899%2C-84.39164034&TRAVEL=0&TAXTERM=0&SORTSPEC=0&FRMT=0&DAYSBACK=30&LOCATION_OPTION=2&";
	
	$url .= http_build_query($data);
	// echo $url . "<br><br>";
	return $url;
}

// TODO: finish converting special characters to be compatible with monster.com
function convertToMonsterEncoding($url) {
	$url = str_replace("+","-",$url);
	return $url;
}

// Remove potential duplicates and merge
function mergeJobPostings($monsterJP, $diceJP) {
	// remove duplicate job posts from $diceJP
	for ($i = 0; $i < sizeof($monsterJP); $i++) {
		for ($j = 0; $j < sizeof($diceJP); $j++) {
			$val = compareJobPosts($monsterJP[$i], $diceJP[$j]);
			if ($val == 0) {
				// TODO: delete the post with less information
				unset($diceJP[$j]);
				break;
			}
		}
	}
	$mergedJP = array_merge($monsterJP, $diceJP);
	shuffle($mergedJP);
	return $mergedJP;
}

function compareJobPosts($jp1, $jp2) {
	return ( abs(strcmp($jp1['title'], $jp2['title'])) +
			 abs(strcmp($jp1['city'], $jp2['city'])) +
			 abs(strcmp($jp1['company'], $jp2['company'])) );
}

// ******************Start Scraping******************
// import library(s)
include('simple_html_dom.php');

// input variables
$inLocation = $_GET['location'];
$inKeyword = $_GET['keyword'];
$inSalary = $_GET['salary'];
$inEducation = $_GET['education'];

// array of dictionary items holding job postings
$monsterJobPostings = array();
$diceJobPostings = array();
$jobPostings = array();

// scrape websites
// getMonsterJobPostings('monster.htm');
// getDiceJobPostings('dice.htm');
if((!(isset($inSalary)) || $inSalary == "") && (!isset($inEducation) || $inEducation == ""))
	getDiceJobPostings(createDiceURL());
getMonsterJobPostings(createMonsterURL());

// merge job postings
$jobPostings = mergeJobPostings($monsterJobPostings, $diceJobPostings);

// output to html
outputDicDataToHTML($jobPostings);

// write to json file
writeToJsonFile($jobPostings);

// redirect after script completion
echo "<script>window.location = './results.php?location=".urlencode($_GET['location']);
echo "&keyword=".urlencode($_GET['keyword']);
echo "&salary=".urlencode($_GET['salary'])."&education=".urlencode($_GET['education'])."'</script>";
?>

