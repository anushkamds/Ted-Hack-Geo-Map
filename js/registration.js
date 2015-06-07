var locationHolder = {};
function loadPlaces(place, marker) {
    console.log(place);
    var id = place.id;
    if (locationHolder[id]) {
        return;
    }
    locationHolder[id] = {'place': place, 'marker': marker};


    var location = place.name;
    $("#selected-location-place-holder").append('<li class="collection-item location-holder-li"><div>'
            + location + '<a href="#!" class="secondary-content location-holder" id="remove-location-"' + id + ' location ="' + id + '">\n\
                            <i class="mdi-content-clear"></i>\n\
                          </a></div>\n\
             </li>');
    $('.location-holder').on('click', function() {
        var me = $(this);
        var id = me.attr('location');
        locationHolder[id].marker.setVisible(false);
        delete  locationHolder[id];
        me.closest('.location-holder-li').remove();
    })
}

$(document).ready(function() {
    $('#save-btn').click(function() {
        if ($("#option-courier").prop("checked") || $("#option-driver").prop("checked")) {
            submitRegistrationForm();
        }
    });


});

function loadCourierServiceProviders() {

}

function submitRegistrationForm() {
    var extraData= getextraData();
    console.log(extraData);
    $('#registration-form').submit(extraData);
}

function getextraData(){
    var locations = [];
    
    $.each(locationHolder, function(index, value){
        var place = value.place;
        locations.push({
            'name': place.formatted_address,
            'lat': place.geometry.location.A,
            'log': place.geometry.location.F
        });
    });
    return locations;
}