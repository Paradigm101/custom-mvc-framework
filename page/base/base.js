
// At each ajax start
$(document).ajaxStart(function () {
    $('#ajaxModal').modal('show');
});

// At each ajax finished
$(document).ajaxStop(function () {
    $('#ajaxModal').modal('hide');
});

// Make an element unselectable
var make_unselectable = function( $target ) {
    $target
        .addClass( 'unselectable' ) // All these attributes are inheritable
        .attr( 'unselectable', 'on' ) // For IE9 - This property is not inherited, needs to be placed onto everything
        .attr( 'draggable', 'false' ) // For moz and webkit, although Firefox 16 ignores this when -moz-user-select: none; is set, it's like these properties are mutually exclusive, seems to be a bug.
        .on( 'dragstart', function() { return false; } );  // Needed since Firefox 16 seems to ingore the 'draggable' attribute we just applied above when '-moz-user-select: none' is applied to the CSS 

    $target // Apply non-inheritable properties to the child elements
        .find( '*' )
        .attr( 'draggable', 'false' )
        .attr( 'unselectable', 'on' ); 
};

// to change label on specific dropdown menu
$(".dropdown-menu-change-label li a").click(function()
{
    $(this).parents(".dropdown").find('.btn').html($(this).text() + '&nbsp;<span class="caret"></span>');
    $(this).parents(".dropdown").find('.btn').val($(this).data('value'));
});
