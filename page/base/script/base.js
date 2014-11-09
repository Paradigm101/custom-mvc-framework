
// used in every page
$(function () {

    // Page shortcuts
    $(document).keypress(function(e) {

        // Ctrl+h : Home page
        if( e.ctrlKey && e.which == 104 /* h */ ) {

            e.preventDefault();
            $(location).attr('href', SITE_ROOT + 'rt=' + REQUEST_TYPE_PAGE + '&' + 'rn=main' );
        }

        // Ctrl+b : Bootstrap demo page
        if( e.ctrlKey && e.which == 98 /* b */ ) {

            e.preventDefault();
            $(location).attr('href', SITE_ROOT + 'rt=' + REQUEST_TYPE_PAGE + '&' + 'rn=bootstrapdemo' );
        }

        // Ctrl+c : Session page
        if( e.ctrlKey && e.which == 99 /* c */ ) {

            e.preventDefault();
            $(location).attr('href', SITE_ROOT + 'rt=' + REQUEST_TYPE_PAGE + '&' + 'rn=session' );
        }

        // Ctrl+t : Table page
        if( e.ctrlKey && e.which == 116 /* t */ ) {

            e.preventDefault();
            $(location).attr('href', SITE_ROOT + 'rt=' + REQUEST_TYPE_PAGE + '&' + 'rn=table' );
        }

        // Ctrl+a : About page
        if( e.ctrlKey && e.which == 97 /* a */ ) {

            e.preventDefault();
            $(location).attr('href', SITE_ROOT + 'rt=' + REQUEST_TYPE_PAGE + '&' + 'rn=about' );
        }
    });

    // TBD: Generic method to display alt content in iframe with missing url (wtf did I mean?!?)
//    $('iframe').each(function () {
//        console.log('in iframe fonction');
//    });
});
