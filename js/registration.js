var locationHolder = {};
function loadPlaces(place, marker) {
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