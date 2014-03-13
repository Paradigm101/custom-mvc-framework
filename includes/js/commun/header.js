
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

    
    // Sign-up OK modal: user can hit enter to leave
    $('#signupOkModal').keypress(function(e) {
        if (e.which == '13') {
            e.preventDefault();
            $(this).modal('hide');
        }
    });

    // Sign-up Modal: Give focus to first element
    $('#signupModal').on('shown.bs.modal', function() {
        $('#inputEmail').focus();
    });

    // Log-in Modal: Give focus to first element
    $('#loginModal').on('shown.bs.modal', function() {
        $('#inputEmail2').focus();
    });

    // Sign-up Modal: user can hit enter to submit
    $('#signupModal').keypress(function(e) {
        if (e.which == '13') {
            e.preventDefault();
            $('#signupForm').submit();
        }
    });

    // Log-in Modal: user can hit enter to submit
    $('#loginModal').keypress(function(e) {
        if (e.which == '13') {
            e.preventDefault();
            $('#loginForm').submit();
        }
    });

    // Sign-up Button: Click to submit
    $('#signupButton').click( function (e) {

        e.preventDefault();
        $('#signupForm').submit();
    });

    // Log-in Button: Click to submit
    $('#loginpButton').click( function (e) {

        e.preventDefault();
        $('#loginForm').submit();
    });

    // Sign-up form is being submitted
    $('#signupForm').submit(function(e){

        e.preventDefault();
        
        $.ajax({
            type: "POST",
            url: "",
            data: {
                page:      'ajax',
                action:    'signup',
                email:     $('#inputEmail').val(),
                username:  $('#inputUsername').val(),
                password:  $('#inputPassword').val(),
                password2: $('#inputPassword2').val()
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
                }
                // Exception, unknown problem
                else {
                    message = 'Something wrong happened, try again later.';
                }

                $('#signupFeedback').html('<strong>' + message + '</strong>');
            }
        });
    });

    // Log-in form is being submitted
    $('#loginForm').submit(function(e){

        e.preventDefault();
        
        $.ajax({
            type: "POST",
            url: "",
            data: {
                page:      'ajax',
                action:    'login',
                email:     $('#inputEmail2').val(),
                password:  $('#inputPassword2').val()
            },
            success: function(data) {

                // Problem managed by system
                if ( data.error ) {
                    $('#loginFeedback').html('<strong>' + data.error + '</strong>');
                }
                // User loged in
                else {
                    $('#loginModal').modal('hide');
                }
            }
        });
    });
});
