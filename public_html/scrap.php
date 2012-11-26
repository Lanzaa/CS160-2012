<?php
function getArticles($page) {
	global $articles;
	$html = new simple_html_dom();
	$html->load_file($page);
	
	$items = $html->find('tr.even, tr.odd'); 
	foreach ($items as $element) {
		$title = $element->children(1)->children(0)->children(0)->first_child()->innertext;
		$city = $element->children(2)->children(0)->children(0)->first_child()->innertext;
		$company = $element->children(1)->children(0)->children(1)->children(0)->children(0)->innertext;
		$salary = $element->children(1)->children(0)->children(1)->children(0)->children(2)->innertext;		
		$link = $element->children(1)->children(0)->children(0)->first_child()->href;

		
		$articles[] = array(
			'title' => $title,
			'city' => $city,
			'company' => $company,
			'salary' => $salary,
			'requirements' => "TODO",
			'link' => $link
		);
	}
}

function writeToJsonFile($inputData) {
	$fp = fopen('results.json', 'w');
    fwrite($fp, json_encode($inputData));
    fclose($fp);
}

include('simple_html_dom.php');
$articles = array();
// getArticles('monster.htm');
getArticles("http://jobsearch.monster.com/search/?where=san-jose__2C-ca");

foreach ($articles as $item) {
	echo "<div>";
    echo 'title: ' . $item['title'] . "<br>";
	echo 'company: ' . $item['company'] . "<br>";
	echo 'city: ' . $item['city'] . "<br>";
	echo 'salary: ' . $item['salary'] . "<br>";
	echo 'link: ' . $item['link'] . "<br>";
 	echo "<br>";
    echo "</div>";
}

// writeToJsonFile($articles);



$html->clear();
unset($html);
?>