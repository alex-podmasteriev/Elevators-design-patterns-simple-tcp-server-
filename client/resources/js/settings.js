$(document).on('ready', function(){

    $('#settings').on('submit', function( e ){

        e.stopPropagation();
        e.preventDefault();

        var data = {
            elevators: $('input[name="elevators"]').val(),
            floors: $('input[name="floors"]').val()
        };

        var url = $(this).attr('action');

        $.post( url, data );

    });

});