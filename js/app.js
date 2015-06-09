$(document).ready(function() {
    var directionsService = new google.maps.DirectionsService();
    var firstTime = true;
//    $('select').material_select();
    $('#courier-details-place-holder').hide();
    $('#driver-details-place-holder').hide();


    $('.service-provider-options').click(function() {
        if ($("#option-courier").prop("checked")) {
            $('#courier-details-place-holder').show();
        } else {
            $('#courier-details-place-holder').hide();
        }

        if ($("#option-driver").prop("checked")) {
            $('#driver-details-place-holder').show();
            if (firstTime) {
                initialize();
                firstTime = false;
            }
        } else {
            $('#driver-details-place-holder').hide();
        }

    });
    $('.modal-trigger').leanModal({
        dismissible: true, // Modal can be dismissed by clicking outside of the modal
        opacity: .5, // Opacity of modal background
        in_duration: 300, // Transition in duration
        out_duration: 200, // Transition out duration       
    }
    );

    $('.btn').click(function() {
        if ($("#option-courier").prop("checked")) {
            $('#dirver_form').submit();
        } else {
            var locations = getExtraData();
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
                    return waypoints
                }(),
                provideRouteAlternatives: false,
                travelMode: google.maps.TravelMode.DRIVING

            }
            directionsService.route(request, function(result, status) {

                if (status == google.maps.DirectionsStatus.OK) {
                    $('#waypoint-holder').val(JSON.stringify(result['routes'][0]['overview_path']));

                }
                $('#dirver_form').submit();
            });
        }
    });

    loadCourierServicesList();

});


function loadCourierServicesList() {
    $.ajax({
        type: "GET",
        url: 'ctrls/getCourierServiceList.php',
        data: {
        },
        dataType: "html",
        success: function(data) {
            $('#courrier_service').html(data);
            $('#courrier_service').material_select();
        }
    });
}