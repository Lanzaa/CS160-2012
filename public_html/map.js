// when the page is loaded, call init function.
google.maps.event.addDomListener(window, 'load', init);

// Variables for use internally.
var map, markers = [];

// Initialize the map, assign autocomplete to location text field.
function init() {
	// required options for the map.
	mapOptions = { zoom:10, center:new google.maps.LatLng(0, 0), mapTypeId:google.maps.MapTypeId.ROADMAP };
	// the field object where location is typed in.
	input = document.getElementsByName('location')[0]; // TODO hard coded field.
	//enable autocomplete on input field, and update map when autocomplete option clicked.
	autocomplete = new google.maps.places.Autocomplete(input);
	google.maps.event.addListener(autocomplete, 'place_changed', function() {
		map.setCenter(autocomplete.getPlace().geometry.location);
	});
	// create a map, listener for map location changed
	map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
	google.maps.event.addListener(map, 'dragend', function() { updateLocation(input); });
	// initially place the map where the user has given us a location.
	updateMap(input.value);
	// parse the json file.  TODO hard coded directory, keep up to date!
	parseJSON("./results/results.json");
}

// Update the map's displayed area based on location supplied by user.
function updateMap(location) {
	// if the location is not empty, update the map.
	if (location != "") {
		new google.maps.Geocoder().geocode({ 'address': location }, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK)
				map.setCenter(results[0].geometry.location);
		});
	}
}

// Update the location text field based on where the map has been moved to.
function updateLocation(field) {
	cur = field.value;
	new google.maps.Geocoder().geocode({ 'latLng': map.getCenter() }, function(r, s) {
		if (s == google.maps.GeocoderStatus.OK) {
			for (i = 0; i < r.length; i++) {
				for (j = 0; j < r[i].types.length; j++) {
					if (r[i].types[j] == "locality") {
						console.log(r[i].formatted_address);
						field.value = r[i].formatted_address;
					}
				}
			}
		}
		// Perform a new search
		if (cur != field.value)
			document.forms["user_input"].submit();
	});
}

// Add a marker to the map given a business name and city.
function addMarker(json) {
	if (json.name == "" || json.city == "")
		return;
	new google.maps.Geocoder().geocode({ 'address':json.city }, function(r, s) {
		if (s == google.maps.GeocoderStatus.OK) {
			new google.maps.places.PlacesService(map).
				nearbySearch({ location:r[0].geometry.location, radius:50000, name:json.name }, createMarker(json));
				} else { // sleep 1/4 of a second and try again.
				console.log(s);
			setTimeout(function() { addMarker(json); }, 300);
		}
	});
}

// Create a marker, place on the map, store in markers array.
function createMarker(json) {
	out = function(results, status) {
		if (status == google.maps.places.PlacesServiceStatus.OK) {
			marker = new google.maps.Marker({ map:map, position:results[0].geometry.location });
			markers.push(marker);
			infowindow = new google.maps.InfoWindow({ disableAutoPan:true });
			google.maps.event.addListener(marker, 'mouseover', function() {
					infowindow.setContent(getContent(json));
					infowindow.open(map, this);
			});
			google.maps.event.addListener(marker, 'mouseout', function() {
				infowindow.close();
			});
			resizeMap();
		}
	}
	return out;
}

// Parse a set of JSON objects into markers.
function parseJSON(path) {
	$.getJSON(path, function(data) {
		$.each(data, function(i, item) {
			addMarker(item);
		});
	});
}

// create a content string for a given json entry.
function getContent(r) {
	return r.company + " in " + r.city + " are hiring a(n) " + r.title + " for " + r.salary + " annually";
}

// Resize the map to fit all the markers.
function resizeMap() {
	// north, east, south, west, current lattitude, current longitude
	var n = -90, e = -180, s = 90, w = 180;
	for (i = 0; i < markers.length; i++) {
		curLat = markers[i].position.lat();
		curLng = markers[i].position.lng();
		n = n < curLat ? curLat : n;
		e = e < curLng ? curLng : e;
		s = s > curLat ? curLat : s;
		w = w > curLng ? curLng : w;
	}
	// update the map's display based on north-east and south-west corners
	map.fitBounds(new google.maps.LatLngBounds(new google.maps.LatLng(s, w), new google.maps.LatLng(n, e)));
}
