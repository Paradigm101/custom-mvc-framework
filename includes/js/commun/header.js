
// When page is loaded
$(function(){

    $('#signupButton').click( function (e) {
        e.preventDefault();
        $('#signupForm').submit();
    });

    $('#signupForm').submit(function(e){
        e.preventDefault();
        alert('Sign in form submited!');
    });
});
