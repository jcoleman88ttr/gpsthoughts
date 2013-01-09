<!DOCTYPE html>
<html>
    <head>
        <title>GPS Thoughts</title>
        <link href="css/style.css" rel="stylesheet" />
    </head>

    <body>
        <header>
            <h1>GPS Thoughts</h1>
        </header>

        <section id="main">
            <form id="thought-form" action="post.php">
                <label for="thought">Thought</label>
                <textarea name="thought" id="thought"></textarea>
                <label for="category">Category</label>
                <input type="text" name="category" id="category" />
                <input type="hidden" name="latitude" value="39.9522" id="latitude" />
                <input type="hidden" name="longitude" value="75.1642" id="longitude" />
                <button id="submitThought">Pin Thought</button>
            </form>
        </section>

        <footer>
            <p style="text-align:center;">Copyright &copy; 2013.</p>
        </footer>

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                if (navigator.geolocation) 
                {
                    navigator.geolocation.getCurrentPosition( 
				 
                    function (position) { 
                        mapServiceProvider(position.coords.latitude,position.coords.longitude);
                    }, 
                    // next function is the error callback
                    function (error)
                    {
                        switch(error.code) 
                        {
                            case error.TIMEOUT:
                                alert ('Timeout');
                                break;
                            case error.POSITION_UNAVAILABLE:
                                alert ('Position unavailable');
                                break;
                            case error.PERMISSION_DENIED:
                                alert ('Permission denied');
                                break;
                            case error.UNKNOWN_ERROR:
                                alert ('Unknown error');
                                break;
                        }
                    }
                );
                }
				
            });
        </script>
    </body>
</html>