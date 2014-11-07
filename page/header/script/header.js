
// When page is loaded
$(function(){

    // Adding Shortcuts to any page with this header
    $(document).keypress(function(e) {

        // Ctrl+s : open Signup modal
        if( e.ctrlKey && e.which == 115 ) {
            $('#signupModal').modal('show');
            return false;
        }

        // Ctrl+l : open login modal
        if( e.ctrlKey && e.which == 108 ) {
            $('#loginModal').modal('show');
            return false;
        }
    });

    // ***************
    // *** Sign-up ***
    // ***************
    // Sign-up OK modal: user can hit enter to leave
    $('#signupOkModal').keypress(function(e) {
        if (e.which == '13') {
            e.preventDefault();
            $(this).modal('hide');
        }
    });

    // Sign-up Modal: Give focus to first element
    $('#signupModal').on('shown.bs.modal', function() {
        $('#inputEmailSU').focus();
    });

    // Sign-up Modal: user can hit enter to submit
    $('#signupModal').keypress(function(e) {
        if (e.which == '13') {
            e.preventDefault();
            $('#signupForm').submit();
        }
    });

    // Sign-up Button: Click to submit
    $('#signupButton').click( function (e) {

        e.preventDefault();
        $('#signupForm').submit();
    });

    // Sign-up form is being submitted
    $('#signupForm').submit(function(e){

        e.preventDefault();
        
        $.ajax({
            type: "POST",
            url: "",
            data: {
                request_type:   'ajax',
                request_name:   'signup',
                email:          $('#inputEmailSU').val(),
                username:       $('#inputUsernameSU').val(),
                password:       $('#inputPasswordSU').val(),
                password2:      $('#inputPassword2SU').val()
            },
            success: function(data) {
                var message = '';

                // Problem managed by system
                if ( data.error ) {
                    message = data.error;
                }
                // User added
                else if ( data.userId ) {
                    $('#signupModal').modal('hide');
                    $('#signupOkModal').modal('show');
                    setTimeout(function(){
                        $('#signupOkModal').modal('hide');
                    }, 3000);
                }
                // Exception, unknown problem
                else {
                    message = 'Something wrong happened, try again later.';
                }

                $('#signupFeedback').html('<strong>' + message + '</strong>');
            }
        });
    });

    // ***************
    // *** Log-out ***
    // ***************
    // Log-out Button
    $('#logoutButton').click( function (e) {

        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "",
            data: {
                request_type:   'ajax',
                request_name:   'logout'
            },
            success: function() {

                $('#logoutOkModal').modal('show');

                // After 3 seconds, hide modal and refresh page
                setTimeout(function(){

                    $('#logoutOkModal').modal('hide');
                    location.reload();
                }, 3000);
            }
        });
    });

    // **************
    // *** Log-in ***
    // **************
    // Log-in OK modal: user can hit enter to leave
    $('#loginOkModal').keypress(function(e) {
        if (e.which == '13') {
            e.preventDefault();
            $(this).modal('hide');
        }
    });

    // Log-in Modal: Give focus to first element
    $('#loginModal').on('shown.bs.modal', function() {
        $('#inputEmailLI').focus();
    });

    // Log-in Modal: user can hit enter to submit
    $('#loginModal').keypress(function(e) {
        if (e.which == '13') {
            e.preventDefault();
            $('#loginForm').submit();
        }
    });

    // Log-in Button: Click to submit
    $('#loginButton').click( function (e) {

        e.preventDefault();
        $('#loginForm').submit();
    });

    // Log-in form is being submitted
    $('#loginForm').submit(function(e){
        
        e.preventDefault();
        
        $.ajax({
            type: "POST",
            url: "",
            data: {
                request_type:   'ajax',
                request_name:   'login',
                email:          $('#inputEmailLI').val(),
                password:       $('#inputPasswordLI').val()
            },
            success: function(data) {

                // Problem managed by system
                if ( data.error ) {
                    $('#loginFeedback').html('<strong>' + data.error + '</strong>');
                }
                // User logged in
                else {
                    $('#loginModal').modal('hide');
                    $('#loginOkModal').modal('show');

                    // After 3 seconds
                    setTimeout(function(){
                        
                        // Hide modal
                        $('#loginOkModal').modal('hide');
                        
                        // Then reload page
                        location.reload();
                    }, 3000);
                }
            }
        });
    });
});
