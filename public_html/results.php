<!DOCTYPE html>
<html>
<head>
    <!--Load the Google Maps API with the places library.-->
    <script src="https://maps.googleapis.com/maps/api/js?&sensor=true&libraries=places"></script>
    <!-- Load jquery to read the JSON from a file. -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <!-- Load the map interface stuff. -->
    <script src="map.js"></script>

    <link rel = "stylesheet" type = "text/css" href = "style.css"/>
</head>
<body id="div-my-tabl">
        <div class = "subHeadline"> Didn't find what you were looking for? </div>
        <div class = "search">
	
		<form name="user_input" action="scrape.php" method="get">
			<p>
			<div class="label"> Location:
				<input type="text" name="location" value="<?php echo $_GET['location']?>">
			</div>
			<div class="label">Keyword:
				<input type="text" name="keyword" value="<?php echo $_GET['keyword']?>">
			</div>
			<div class="label">Salary:
				<select name="salary">
				<option value=""></option>
				<option value="30000">$30,000.00</option>
				<option value="40000">$40,000.00</option>
				<option value="50000">$50,000.00</option>
				<option value="60000">$60,000.00</option>
				<option value="70000">$70,000.00</option>
				<option value="80000">$80,000.00</option>
				<option value="90000">$90,000.00</option>
				<option value="100000">$100,000.00</option>
				<option value="125000">$125,000.00</option>
				<option value="150000">$150,000.00</option>
				</select>
			</div>
			<div class="label">Education:
				<select name="education">
					<option value=""></option>
					<option value="High-School">High School</option>
					<option value="Associate-Degree">Associate Degree</option>
					<option value="Bachelors-Degree">Bachelors Degree</option>
					<option value="Advanced-Degree">Advanced Degree</option>
				</select>
			</div>
			</p>
			<input type="submit" value="Search">
		</form>
</div>

<!-- repopulate the salary and education fields. -->
<script>
	repopulate(document.getElementsByName('salary')[0], getUrlVars()['salary']);
	repopulate(document.getElementsByName('education')[0], getUrlVars()['education']);

	// taken from http://snipplr.com/view/799/get-url-variables/
	// Read a page's GET URL variables and return them as an associative array.
	function getUrlVars() {
		var vars = [], hash;
		var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
		for(var i = 0; i < hashes.length; i++) {
			hash = hashes[i].split('=');
			vars.push(hash[0]);
			vars[hash[0]] = hash[1];
		}
		return vars;
	}

	// repopulate a select drop down box with the given value.
	function repopulate(selection, value) {
		for (var i = 0; i < selection.options.length; i++)
			if (selection.options[i].value == value)
				selection.selectedIndex = i;
	}
</script>

<div class="results">
	<div class="title"></div>
</div>
 
<h2 id="DATE">	
<script language="javascript">
<!--
today = new Date();
$("#DATE").text("Searching jobs on the date of: "+(today.getMonth()+1)+"/"+today.getDate()+"/"+today.getFullYear());
//-->
</script> </h2>


	<style type="text/css">
	body {
	
	font-family: Georgia, "Times New Roman", Times, serif;
        font-size:16px;
	margin-top: 5px; margin-bottom: 0px;
        font-weight: normal;
        color: #222;
	} </style>

	<script>
	$("document").ready(function() {
	    $.getJSON("./results/results.json", function(data) {
	        $.each(data, function(i, item) {
				var toappend = "<div>";
				toappend += '<a href="' + item.link + '">' + item.title + '</a>';
				toappend += "</div>";
				toappend += '<div class="company">'+(item.company||'')+'</div>';
				toappend += '<div class="city">'+(item.city||'')+'</div>';
				toappend += '<div class="salary">'+(item.salary||'')+'</div>';
				toappend += '<div class="requirements">'+(item.requirements||'')+'</div>';
				$("#div-my-tabl").append(toappend+"\n");
				$("#div-my-tabl").append("<br />\n");
				// $("#div-my-tabl").append("<br />");
	        });
	    });
	});
	</script>
<div id="map_cont"><div id="map_canvas" class="map_canvas" style="width:450px; height:450px"></div></div>
	
	<br><br>
</body>
</html>
