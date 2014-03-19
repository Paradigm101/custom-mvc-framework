
$(function () {

    // Adding Shortcuts to any page with this header
    $(document).keypress(function(e) {

        // Ctrl+a : goto page About
        if( e.ctrlKey && e.which == 97 ) {
            $(location).attr('href', 'http://localhost/custom-mvc-framework/?page=about');
            return false;
        }
        
        // Ctrl+h : goto page Home
        if( e.ctrlKey && e.which == 104 ) {
            $(location).attr('href', 'http://localhost/custom-mvc-framework/?page=main');
            return false;
        }

        // Ctrl+b : goto page Bootstrap demo
        if( e.ctrlKey && e.which == 98 ) {
            $(location).attr('href', 'http://localhost/custom-mvc-framework/?page=bootstrapdemo');
            return false;
        }
    });

    // TBD: Generic method to display alt content in iframe with missing url
    $('iframe').each(function () {

    });
});