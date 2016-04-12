$(document).on('ready', function(){

    $('a.presser').on('click', function(e){

        e.stopPropagation();
        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            type: 'get',
            url: url
        });

    });

});