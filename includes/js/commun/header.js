
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
            url: "?page=ajax&action=signup",
            data: {
                email: $('#inputEmail').val(),
                username: $('#inputUsername').val(),
                password: $('#inputPassword').val(),
                password2: $('#inputPassword2').val()
            },
            success: function(data) {
                if ( data.userId == 0 )
                    $('#signupFeedback').html(data.error);
                else
                    $('#signupFeedback').html('Signed in!');
            },
            error: function(xhr, desc, err) {
                console.log(xhr);
                console.log("Details: " + desc + "\nError:" + err);
            }
        });
    });
});
