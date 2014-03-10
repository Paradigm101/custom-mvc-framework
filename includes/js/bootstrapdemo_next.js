
// When page is loaded
$(function(){

    // How to toggle my success alert
    var toggleAlert = function(e) {

        e.preventDefault();

        $('#successAlert').slideToggle();
    };

    // Opening my success alert
    $('#openAlert').click( toggleAlert );
    
    // Closing my success alert
    $('#closeAlert').click( toggleAlert );
    
    // Popover
    $('a.pop').click(function(e){
        e.preventDefault();
    });
    
    // Popover management
    $('a.pop').popover();
    
    // Tooltip management
    $('[rel="tooltip"]').tooltip();
    
    // Avoid fake submit button to break anything
    $('#bsDemoSaveBtn').click(function(e){
            e.preventDefault();
    });
});
