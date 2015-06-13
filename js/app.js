$(document).ready(function() {
  
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
		$('#dirver_form').submit();
       
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