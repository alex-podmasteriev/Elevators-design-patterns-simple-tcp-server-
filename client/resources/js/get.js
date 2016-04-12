setInterval(

function ( ) {

    $.ajax({
        url: '/elevators',
        type: 'post',
        success: function (data){
            data = JSON.parse(data);
            buildElevators(data);
        }
    });

    function buildElevators( data ) {

        $('#elevators').html('');

        $(data).map(function( index, elevator ){

            $('#elevators').append( buildElevator( elevator ) );

        });

    }

    function buildElevator ( obj ) {

        if (
               obj.hasOwnProperty('state')
            && obj.hasOwnProperty('name')
            && obj.hasOwnProperty('direction')
            && obj.hasOwnProperty('door_status')
            && obj.hasOwnProperty('current_floor')
            && obj.hasOwnProperty('destination_floors')
        ) {

            var stops = '';

            if( obj.destination_floors.length > 0 ) {

                stops = obj.destination_floors.join(' | ');
            }

            var elevator = $('<div>', {class: 'col-lg-2'})
                .append(
                    $('<h3>').html('<b>' + obj.name + '</b>')
                )
                .append(
                    $('<p>').html('<b>Current state:</b> ' + obj.state)
                )
                .append(
                    $('<p>').html('<b>Doors:</b> ' + obj.door_status)
                )
                .append(
                    $('<p>').html('<b>Current floor:</b> ' + obj.current_floor)
                )
                .append(
                    $('<p>').html('<b>Direction:</b> ' + obj.direction)
                )
                .append(
                    $('<p>').html('<b>Stops at floors:</b> ' + stops)
                );
            return elevator;
        }

    }

}, 1000 );
