<?
require("conn.php");
?>
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
            <div id="error" style="display:none">Could not locate your coordinates!</div>
            <form id="thought-form" action="post.php">
                <label for="thought">Thought</label>
                <textarea name="thought" id="thought"></textarea>
                <label for="category">Category</label>
                <select name="category" id="category">
                    <?
                    foreach($thoughtCategories as $value){
                        echo "<option value=\"$value\">$value</option>";
                    }
                    ?>
                </select>
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
                function geoloc(success, fail){
                    var is_echo = false;
                    if(navigator && navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                        function(pos) {
                            if (is_echo){ return; }
                            is_echo = true;
                            success(pos.coords.latitude,pos.coords.longitude);
                        }, 
                        function() {
                            if (is_echo){ return; }
                            is_echo = true;
                            fail();
                        }
                    );
                    } else {
                        fail();
                    }
                }

                function success(lat, lng){
                    $("#latitude").val(lat);
                    $("#longitude").val(lng);
                    $.get("http://www.motojunkyard.com/scriptor/gpsthoughts/pull.php?latitude="+lat+"&longitude="+lng, function(data){
                        if(data){
                            $("#error").html("You are located in <b>" + data + "</b>");
                            $("#error").show();                    
                        }
                    });
                }
                function fail(){
                    $("#error").show();
                }
                geoloc(success, fail);
            });
        </script>
    </body>
</html>