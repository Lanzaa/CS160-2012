<!DOCTYPE html>
<html>
<!--Load the Google Maps API with the places library.-->
<script src="https://maps.googleapis.com/maps/api/js?&sensor=true&libraries=places"></script>
<!-- Load jquery to read the JSON from a file. -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<!-- Load the map interface stuff. -->
<script src="map.js"></script>

<head>
	<link rel = "stylesheet" type = "text/css" href = "style.css"/>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
</head>
<body id="div-my-tabl">

<tr><td></td></tr>
</table>
        <div class = "subHeadline"> Didn't find what you were looking for? </div>
        <div class = "search">
	
         <form name="user_input" action="scrap.php" method="get">
            <p>
             <div class="label"> Location:
                  <input type="text" name="location" value="<?php echo $_GET['location']?>">
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
			<option value="Bachelor's-Degree">Bachelor's Degree</option>
			<option value="Advanced-Degree">Advanced Degree</option>
		</select>
               </div>
            </p>
          <input type="submit" value="Search">
         </form>
	
     </div>
      <div class="results">
         <div class="title"></div>
      </div>
 
<h2 id="DATE">	
<script language="javascript">
<!--
today = new Date();
document.write("<BR> Searching jobs on the date of: ", today.getMonth()+1,"/",today.getDate(),"/",today.getYear());
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
	        $("#div-my-table").text("<table>");
	        $.each(data, function(i, item) {
			
				$("#div-my-tabl").append("<tr>");
				$("#div-my-tabl").append("<td>");
				
				$("#div-my-tabl").append("<div>");
				$("#div-my-tabl").append('<a href="' + $(item).attr("link") + '">' + $(item).attr("title") + '</a>');
				$("#div-my-tabl").append("</div>");
				
				$("#div-my-tabl").append("<div>");
				$("#div-my-tabl").append("</div>");
				$("#div-my-tabl").append(item.company);
				$("#div-my-tabl").append("<div>");
				$("#div-my-tabl").append("</div>");	
			        

				$("#div-my-tabl").append(item.city);
                                $("#div-my-tabl").append("<div>");
                                $("#div-my-tabl").append("</div>");

				$("#div-my-tabl").append(item.salary);
                                $("#div-my-tabl").append("<div>");
                                $("#div-my-tabl").append("</div>");
				
				$("#div-my-tabl").append(item.requirements);
				$("#div-my-tabl").append("</td>");
	            		$("#div-my-tabl").append("</tr>");
				$("#div-my-tabl").append("<br />");
				$("#div-my-tabl").append("<br />");
				// $("#div-my-tabl").append("<br />");
			
	        });
		
	        $("#div-my-table").append("</table>");

	    });
	});
	</script>
<div id="map_cont"><div id="map_canvas" class="map_canvas" style="width:450px; height:450px"></div></div>
	
	<br><br>
</body>
</html>
