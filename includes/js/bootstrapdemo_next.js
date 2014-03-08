
// When page is loaded
$(function(){

    // How to toggle my success alert
    var toggleAlert = function(event) {

        event.preventDefault();

        $('#successAlert').slideToggle();        
    };

    // Opening my success alert
    $('#openAlert').click( toggleAlert );
    
    // Closing my success alert
    $('#closeAlert').click( toggleAlert );
    
    // Popover
    $('a.pop').click(function(event){
        event.preventDefault();
    });
    
    $('a.pop').popover();
    
    $('[rel="tooltip"]').tooltip();
});

