<!DOCTYPE html>
<html>
	<head>
		<!--Load the Google Maps API with the places library.-->
		<script src="https://maps.googleapis.com/maps/api/js?&sensor=true&libraries=places"></script>
		<!-- Load jquery to read the JSON from a file. -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<!-- Load the map interface stuff. -->
		<script src="map.js"></script>
		<!-- load the stylesheet for formatting. -->
		<link rel = "stylesheet" type = "text/css" href = "style.css"/>
<!-- javascript stuff for this page to populate various fields and display results. -->
<script>
	// populate the Date attribute.
	var today = new Date();
	$("#DATE").text("Searching jobs on the date of: " + (today.getMonth() + 1) +
		"/" + today.getDate() + "/" + today.getFullYear());

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

	// repopulate a drop down box to the given value.
	function repopulate(selection, value) {
		for (var i = 0; i < selection.options.length; i++)
			if (selection.options[i].value == value)
				selection.selectedIndex = i;
	}


	// repopulate the salary and education fields.
    $("document").ready(function(){
        repopulate(document.getElementsByName('salary')[0], getUrlVars()['salary']);
        repopulate(document.getElementsByName('education')[0], getUrlVars()['education']);
    });

    var params = window.location.href.slice(window.location.href.indexOf('?') + 1);
    var jsonurl = "./scrape.php?"+params;

	// anonymous function to put the results on the page formatted correctly.
	$("document").ready(function() {
		$.getJSON(jsonurl, function(data) {
            $("#loader").hide();
			$.each(data, function(i, item) {
                addMarker(item);
				var toappend = "<div>";
				toappend += '<a href="' + item.link + '">' + item.title + '</a>';
				toappend += "</div>";
				toappend += '<div class="company">' + (item.company || '') + '</div>';
				toappend += '<div class="city">' + (item.city || '') + '</div>';
				toappend += '<div class="salary">' + (item.salary || '') + '</div>';
				toappend += '<div class="requirements">' + (item.requirements || '') + '</div>';
				toappend += "\n<br />\n";
				$(".results").append(toappend);
			});
		});
	});
</script>
	</head>
	<body id="div-my-tabl">
		<div class = "subHeadline"> Didn't find what you were looking for? </div>

		<div class = "search">
			<form name="user_input" action="results.php" method="get">
				<p>
				<ul>
				  <li class="label"> Location:
					<input type="text" name="location" value="<?php echo $_GET['location']?>">
				  </li>
				  <li class="label">Keyword:
					 <input type="text" name="keyword" value="<?php echo $_GET['keyword']?>">
				  </li>
				  <li class="label">Salary:
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
				  </li>
				  <li class="label">Education:
					 <select name="education">
						<option value=""></option>
						<option value="High-School">High School</option>
						<option value="Associate-Degree">Associate Degree</option>
						<option value="Bachelors-Degree">Bachelors Degree</option>
						<option value="Advanced-Degree">Advanced Degree</option>
					 </select>
				  </li>
			   </ul>
			</p>
			<input type="submit" value="Search">
		 </form>
      </div>

		<div class="results">
            <div id="loader"><img src="loading.gif"></div>
			<div class="title"></div>
		</div>

		<h2 id="DATE"></h2>

		<div id="map_cont">
    	<div id="d_cb">
				<label for="cb">Redo Search When Map Moved</label>
				<input id="cb" type="checkbox" />
			</div>
    	<div id="map_canvas" class="map_canvas" style="width:450px; height:450px" />
		</div>
	<br><br></body>
</html>
