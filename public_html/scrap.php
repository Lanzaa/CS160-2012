
<?php
function getMonsterJobPostings($page) {	
	global $monsterJobPostings;
	$html = file_get_html($page);
	$items = $html->find('tr.even, tr.odd'); 
	foreach ($items as $element) {
		$title = $element->children(1)->children(0)->children(0)->first_child()->innertext;
		$city = $element->children(2)->children(0)->children(0)->first_child()->innertext;
		$company = $element->children(1)->children(0)->children(1)->children(0)->children(0)->innertext;
		$salary = $element->children(1)->children(0)->children(1)->children(0)->children(2)->innertext;		
		$link = $element->children(1)->children(0)->children(0)->first_child()->href;
	
		$monsterJobPostings[] = array(
			'title' => $title,
			'city' => $city,
			'company' => $company,
			'salary' => $salary,
			'requirements' => "TODO",
			'link' => $link
		);
	}
	// clear and unset to prevent memory leaks
	$html->clear();
	unset($html);
}

function getDiceJobPostings($page) {	
	global $diceJobPostings;
	$html = file_get_html($page);
	
	$items = $html->find('tr.gold, tr.STDsrRes');
	foreach ($items as $element) {
		$title = $element->children(0)->children(0)->first_child()->innertext;
		$city = $element->children(2)->innertext;
		$company = $element->children(1)->children(0)->innertext;		
		$link = "http://seeker.dice.com/" . $element->children(0)->children(0)->first_child()->href;
	
		$diceJobPostings[] = array(
			'title' => $title,
			'city' => $city,
			'company' => $company,
			'salary' => "TODO",
			'requirements' => "TODO",
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
	$data = array();
	$url = "http://jobsearch.monster.com/search/?";
	
	if (strlen($inKeyword) > 0) {
		$data['q'] = $inKeyword;
	}
	
	if (strlen($inLocation) > 0) {
		$data["where"] = $inLocation;
	}
	
	$url .= convertToMonsterEncoding(http_build_query($data));
	// echo $url . "<br><br>";
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

// TODO: write a better merge function, it should check for potential duplicates
function mergeJobPostings($monsterJP, $diceJP) {
	return array_merge($monsterJP, $diceJP);
}

// echo "Hello" . "<br><br>";
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
echo "before scrapping" . "<br><br>";
// scrap websites
// getMonsterJobPostings('monster.htm');
// getDiceJobPostings('dice.htm');
getDiceJobPostings(createDiceURL());
getMonsterJobPostings(createMonsterURL());
echo "after scrapping" . "<br><br>";
// merge job postings
$jobPostings = mergeJobPostings($monsterJobPostings, $diceJobPostings);

// output to html
outputDicDataToHTML($jobPostings);

// write to json file
writeToJsonFile($jobPostings);

// redirect after script completion
echo "<script>window.location = './results.html'</script>";
?>









