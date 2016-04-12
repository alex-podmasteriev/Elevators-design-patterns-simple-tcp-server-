var host = 'http://localhost:8088';

$(document).on('ready', function () {

    $.ajax({
        type: 'Post',
        url: host + '/get/config',
        success: function(data){

            $('#building').html( buildHouse(data) );

            $('.presser').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var url = host + $(this).attr('href');
                $.ajax({
                    type: 'Post',
                    url: url,
                    success: function(msg) {
                    }
                })
            });
        }
    })

});


function buildHouse( config ) {

    var html = '';

    for ( var i = config.floors; i > 0; i-- ){
        if ( i == 1 ) {
            html = html + '<li>';
            html = html + '<div class="btn-group">';
            html = html + '<a class="btn btn-default presser" href="/press/button/floor/' + i + '/direction/up">' + i +' ↑</a>';
            html = html + '</div>';
            html = html + '</li>';
        } else if ( i == config.floors ) {
            html = html + '<li>';
            html = html + '<div class="btn-group">';
            html = html + '<a class="btn btn-default presser" href="/press/button/floor/' + i + '/direction/down"> ' + i +' ↓</a>';
            html = html + '</div>';
            html = html + '</li>';
        } else {
            html = html + '<li>';
            html = html + '<div class="btn-group">';
            html = html + '<a class="btn btn-default presser" href="/press/button/floor/' + i + '/direction/down">' + i +' ↓</a>';
            html = html + '<a class="btn btn-default presser" href="/press/button/floor/' + i + '/direction/up">' + i +' ↑</a>';
            html = html + '</div>';
            html = html + '</li>';
        }

    }

    return html;

}

function getElevators(){

    $.ajax({
        type: 'Post',
        url: host + '/get/elevators',
        success: function( elevators ) {
            $('#elevators').html('');
            buildElevators ( elevators );
        }
    })
}

function buildElevators ( elevators ) {

    var divisor = elevators.length;

    var clss = 'col-sm-' + parseInt(12/divisor);

    $(elevators).each( function(i,e,a){

        var dest_f = '';

        if ($.isArray(e.destination_floors) ) {

            if (e.direction === 'up'){

                e.destination_floors.sort(function(a,b){return a-b;});

            } else {

                e.destination_floors.sort(function(a,b){return b-a;});

            }

            dest_f = e.destination_floors.join('|');
        }


        var name = $('<p>').html('<b>Name:</b> ' + e.name);
        var state = $('<p>').html('<b>Sate:</b> ' + e.state);
        var ds = $('<p>').html('<b>Doors:</b> ' + e.door_status);
        var cf = $('<p>').html('<b>Current Floor:</b> ' + e.current_floor);
        var dir = $('<p>').html('<b>Direction:</b> ' + e.direction);
        var stops = $('<p>').html('<b>Stops:</b> ' + dest_f);

        $( '<div>', {class: clss} )
            .append( name, state, ds, cf,dir, stops)
            .appendTo('#elevators');

    });
}

window.setInterval(getElevators, 1000);
