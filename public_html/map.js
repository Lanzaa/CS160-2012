// When the page is finished loading, call init function.
google.maps.event.addDomListener(window, 'load', init);

// Variables for use internally.
var map, geo, markers = [];

// Initialize the map, assign autocomplete to location text field.
function init() {
	// the field object where location is typed in
	input = document.getElementsByName('location')[0]; // TODO hard coded field
	// enable autocomplete on input field
	new google.maps.places.Autocomplete(input);
	// initialize the geocoder
	geo = new google.maps.Geocoder();
	// set map center via user input location and other required options for the map
	var center = new google.maps.LatLng(0, 0);
	geo.geocode({ 'address':input.value }, function(r, s) {
		if (s == google.maps.GeocoderStatus.OK)
			center = r[0].geometry.location;
	});
	mapOptions = { zoom:10, center:center, mapTypeId:google.maps.MapTypeId.ROADMAP };
	// create a map, listener for map location changed
	map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
	google.maps.event.addListener(map, 'dragend', function() { updateLocation(input); });

    // Note the json data is parsed and markers added in the main file
}

// Update the location text field based on where the map has been moved to.
function updateLocation(field) {
	// if the check box is not checked, don't update location
	if (!document.getElementById('cb').checked) // TODO hard coded field
		return;
	// otherwise, store the current location and find the new one
	cur = field.value;
	geo.geocode({ 'latLng':map.getCenter() }, function(r, s) {
		if (s == google.maps.GeocoderStatus.OK)
			for (i = 0; i < r.length; i++)
				for (j = 0; j < r[i].types.length; j++)
					if (r[i].types[j] == "locality")
						field.value = r[i].formatted_address;
		// if the current location is different than the new one, do a search
		if (cur != field.value)
			document.forms["user_input"].submit();
	});
}

// Add a marker to the map given a business name and city.
function addMarker(json) {
	if (json.name != "" && json.city != "")
		geo.geocode({ 'address':json.city }, function(r, s) {
			if (s == google.maps.GeocoderStatus.OK) {
				new google.maps.places.PlacesService(map).nearbySearch(
						{ location:r[0].geometry.location, radius:50000, name:json.name }, 
						createMarker(json));
			} else if (s == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
				// sleep a second and try again.
				console.log(s);
				setTimeout(function() { addMarker(json); }, 1000);
			}
		});
}

// Create a marker, place on the map, store in markers array.
function createMarker(json) {
	return function(r, s) {
		if (s == google.maps.places.PlacesServiceStatus.OK) {
			m = new google.maps.Marker({ map:map, position:r[0].geometry.location });
			markers.push(m);
			infowindow = new google.maps.InfoWindow({ content:getContent(json) });
			google.maps.event.addListener(m, 'click', function() {
				infowindow.open(map, this);
			});
			resizeMap();
		}
	}
}

// create a content string for a given json entry.
function getContent(r) {
	return "<a href='" + r.link + "'>" + r.company + "</a>" + " in " + r.city + 
		" are hiring a(n) " + r.title + (r.salary != null ? " for " + r.salary : "");
}

// Resize the map to fit all the markers.
function resizeMap() {
	// north, east, south, west, current lattitude, current longitude
	var n = -90, e = -180, s = 90, w = 180, curLat, curLng;
	// find the furthest points north, east, south, and west
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
