var directionsDisplay;
var directionsService;
var map;
function initialize() {
	directionsDisplay = new google.maps.DirectionsRenderer();
	 directionsService = new google.maps.DirectionsService();
    var mapOptions = {
        center: new google.maps.LatLng(7.26801, 79.861573),
        zoom: 7
    };
    var markersArray = [];
    map = new google.maps.Map(document.getElementById('map-canvas'),
            mapOptions);
	directionsDisplay.setMap(map);
    var input = /** @type {HTMLInputElement} */ (
            document.getElementById('pac-input'));

    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);
    autocomplete.setTypes([]);

    var infowindow = new google.maps.InfoWindow();
    var marker;

    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        // infowindow.close();
        // marker.setVisible(false);
        marker = new google.maps.Marker({
            map: map,
            anchorPoint: new google.maps.Point(0, -29)
        });
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            $('#autocomplete-modal-wrapper').click();            
            return;
        }

        // If the place has a geometry, then present it on a map.
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(7); // Why 17? Because it looks good.
        }
        marker.setIcon(/** @type {google.maps.Icon} */ ({
            url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(35, 35)
        }));
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);

        infowindow.setContent('<div><strong>' + place.name + '</strong></div>');
        infowindow.open(map, marker);
        console.log(place.geometry.location);
        markersArray.push(place.name);
//        markersArray.toString();
        loadPlaces(place, marker);
		processWaypoints();
//        document.getElementById("added-list").innerHTML = markersArray;
    });

}

function processWaypoints() {
	var locations = getExtraData();
	if(locations.length>1) {
		
            var request = {
                origin: locations[0] ? new google.maps.LatLng(locations[0].lat, locations[0].log) : {},
                destination: locations[0] ? new google.maps.LatLng(locations[locations.length - 1].lat, locations[locations.length - 1].log) : {},
                waypoints: function() {
                    var waypoints = Array();
                    for (var i = 1; i < (locations.length - 1); i++) {
                        waypoints.push({
                            location: new google.maps.LatLng(locations[i].lat, locations[i].log),
                            stopover: false
                        });
                    }
                    return waypoints;
                }(),
                provideRouteAlternatives: false,
                travelMode: google.maps.TravelMode.DRIVING
			};
            directionsService.route(request, function(result, status) {

                if (status == google.maps.DirectionsStatus.OK) {
                    $('#waypoint-holder').val(JSON.stringify(result['routes'][0]['overview_path']));
					directionsDisplay.setDirections(result);
                }
                
            });
	}
}

