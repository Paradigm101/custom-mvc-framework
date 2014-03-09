
// When page is loaded
$(function(){

    // Click sign-up button : submit form
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
                    message = 'You are now signed in, welcome!';
                }
                // Exception, unknown problem
                else {
                    message = 'Something wrong happened, try again later.';
                }

                $('#signupFeedback').html('<strong>' + message + '</strong>');
            }
        });
    });
});
