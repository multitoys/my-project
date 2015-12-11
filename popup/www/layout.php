<!--
http://plutov.by/post/html5_history_api
-->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
</head>
<body>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="contacts.php">Contacts</a></li>
        <li><a href="about.php">About</a></li>
    </ul>
    <div id="content"><?php echo $content; ?></div>

    <script>
        $(document).ready(function() {
            $('a').click(function() {
                var url = $(this).attr('href');

                $.ajax({
                    url:     url + '?ajax=1',
                    success: function(data){
                        $('#content').html(data);
                    }
                });

                if(url != window.location){
                    window.history.pushState(null, null, url);
                }

                return false;
            });

            $(window).bind('popstate', function() {
                $.ajax({
                    url:     location.pathname + '?ajax=1',
                    success: function(data) {
                        $('#content').html(data);
                    }
                });
            });
        });
    </script>
</body>
</html>