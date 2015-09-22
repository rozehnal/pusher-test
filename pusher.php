<?php
session_start();
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

?>

<!DOCTYPE html>
<head>
    <title>Pusher Test</title>
    <script src="https://js.pusher.com/3.0/pusher.min.js"></script>
    <script>

        var enable = true;
        var me, userId, userInfo;

        // Enable pusher logging - don't include this in production
        Pusher.log = function(message) {
            if (window.console && window.console.log) {
            }
        };

        var pusher = new Pusher('31a1c5ee118d567a3eda', {
            encrypted: true,
            authEndpoint: '/auth.php'
        });
        var channel = pusher.subscribe('presence-test_channel');

        channel.bind('pusher:subscription_succeeded', function() {
            console.log(channel.members);
            me = channel.members.me;
            userId = me.id;
            userInfo = me.info;
        });

        channel.bind('client-my_event', function(data) {
            console.log(data, $('#' + data.user));
            if ($('#' + data.user).length > 0) {
                var div = $('#' + data.user);
            }else{
                jQuery('<div/>', {
                    id: data.user,
                    href: 'http://google.com',
                    title: '',
                    rel: 'external',
                    text: data.user
                }).appendTo('body');

            }

            $('#' + data.user).offset({ top: data.y, left: data.x})

        });

        function send(x, y) {
            if (!enable) return;
            console.log(channel);
            channel.trigger('client-my_event', {
                name: 'IP: <?php echo $ip?>',
                x: x,
                y: y,
                user: userId
            });
            enable = false;
            setTimeout('enable=true;', 150);

        }

    </script>
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>
<body>
    <input type="button" onclick="send()" value="send mess">
    <textarea id="log" style="width: 100%;"></textarea>
    <script>
        $( document ).on( "mousemove", function( event ) {
            $( "#log" ).text( "pageX: " + event.pageX + ", pageY: " + event.pageY );
            send(event.pageX, event.pageY);
        });
    </script>
</body>
</html>