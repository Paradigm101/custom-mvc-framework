
// used in every page
$(function () {

    // Page shortcuts
    $(document).keypress(function(e) {

        // Ctrl+h : Home page
        if( e.ctrlKey && e.which == 104 /* h */ ) {

            e.preventDefault();
            $(location).attr('href', getURL('main') );
        }

        // Ctrl+b : Bootstrap demo page
        if( e.ctrlKey && e.which == 98 /* b */ ) {

            e.preventDefault();
            $(location).attr('href', getURL('bootstrapdemo') );
        }

        // Ctrl+c : Session page
        if( e.ctrlKey && e.which == 101 /* e */ ) {

            e.preventDefault();
            $(location).attr('href', getURL('session') );
        }

        // Ctrl+t : Table page
        if( e.ctrlKey && e.which == 116 /* t */ ) {

            e.preventDefault();
            $(location).attr('href', getURL('table') );
        }

        // Ctrl+p : API page
        if( e.ctrlKey && e.which == 112 /* p */ ) {

            e.preventDefault();
            $(location).attr('href', getURL('api') );
        }

        // Ctrl+a : About page
        if( e.ctrlKey && e.which == 97 /* a */ ) {

            e.preventDefault();
            $(location).attr('href', getURL('about') );
        }
    });

    // TBD: Generic method to display alt content in iframe with missing url (wtf did I mean?!?)
//    $('iframe').each(function () {
//        console.log('in iframe fonction');
//    });
});
