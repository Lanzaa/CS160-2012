<html>
<head>
<title>CS160 Team 1</title>
	<link href="style.css" rel="stylesheet">
  <script>
    var map, geocoder;
    // initialize the map and geocoder objects, assign autocomplete to location text field.
    function initialize() {
      var mapOptions = { zoom: 13, center: new google.maps.LatLng(0, 0), mapTypeId: google.maps.MapTypeId.ROADMAP };
      map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
      geocoder = new google.maps.Geocoder();
      google.maps.event.addListener(map, 'center_changed', function() { updateLocation(); });
      var input = document.getElementsByName('location')[0];
      autocomplete = new google.maps.places.Autocomplete(input);
      google.maps.event.addListener(autocomplete, 'place_changed', function() {
        map.setCenter(autocomplete.getPlace().geometry.location);
      });
    }

    // load the google maps api for use, run via window.onload after website is done loading.
    function loadScript() {
      var script = document.createElement('script');
      script.type = 'text/javascript';
      script.src = 'https://maps.googleapis.com/maps/api/js?&sensor=false&libraries=places&callback=initialize';
      document.body.appendChild(script);
    }
    window.onload = loadScript;

    // update the map's displayed area based on where the user typed in.
    function updateMap(location) {
      if (location != "") {
        geocoder.geocode({ 'address': location }, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK)
            map.setCenter(results[0].geometry.location);
        });
      }
    }

    // update the location test field based on where the map has been moved to.
    function updateLocation() {
      var input = document.getElementsByName("location")[0];
      geocoder.geocode({ 'latLng': map.getCenter() }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK && results[1])
            input.value = results[1].formatted_address;
      });
    }
  </script>
</head>
        <body>
	<div class="search">
                <form onsubmit="updateMap(document.getElementsByName('location')[0].value);return false" method="get">
                        <div class="label">Location</div>
                        <input type="text" name="location" value="<?php echo $_GET["location"] ?>"><br>
                        <div class="label">Keyword</div>
                        <input type="text" name="keyword" value="<?php echo $_GET["keyword"] ?>"><br>
                        <div class="label">Salary</div>
                        <input type="text" name="salary" value="<?php echo $_GET["salary"] ?>"><br>
                        <div class="label">Education</div>
                        <input type="text" name="education" value="<?php echo $_GET["education"] ?>"><br>
                        <input type="submit" value="Redo"><br>
                </form>
	</div>
		<div class="results">
                <div id="map_canvas" style="width: 600px; height: 500px"></div>
		</div>
        </body>
</html>
