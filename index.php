<?
require("conn.php");

if (!isset($_SESSION["login"])) {
    if (isset($_COOKIE["login"])) {
        $cookie = mysqli_real_escape_string($conn, $_COOKIE["login"]);

        $query = mysqli_query($conn, "select username, userid from users where md5(concat(`userid`,`username`,'gpsthoughts')) = '$cookie'");
        $row = mysqli_fetch_assoc($query);
        if ($row["username"]) {
            $_SESSION["login"] = "true";
            $_SESSION["userid"] = $row["userid"];
            $loginID = md5($row["userid"] . $row["username"] . "gpsthoughts");
            setcookie("loggedin", $loginID, time() + 2592000);
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>GPS Thoughts</title>
        <link href="css/style.css" rel="stylesheet" />
        <link rel="apple-touch-icon-precomposed" href="img/icon.png"/>
        <link rel="apple-touch-startup-image" href="img/splash.png" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="default" />
        <meta name="viewport" content = "width = device-width, initial-scale = 1, user-scalable = no" />  
        <meta name="viewport" content = "width = device-width, initial-scale = 1, minimum-scale = 1, maximum-scale = 1" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
    </head>

    <body>
        <header>
            <h1><a href="./">GPS Thoughts</a></h1>
        </header>

        <div id="mainContent">
            <section id="alert">
                <div id="error" style="display:none">Could not locate your coordinates!</div>
            </section>

            <section id="main">
                <form id="thought-form" autocomplete="off">
                    <textarea name="thought" id="thought"onblur="if(this.value == '') { this.value='Enter Thought..'}" onfocus="if (this.value == 'Enter Thought..') {this.value=''}">Enter Thought..</textarea>
                    <input name="category" id="category" class="category" type="text" value="Enter Category.." onblur="if(this.value == '') { this.value='Enter Category..'}" onfocus="if (this.value == 'Enter Category..') {this.value=''}">Private&nbsp;&nbsp;<input type="checkbox" value="1" name="visibility" id="visibility">
                    <input type="hidden" name="latitude" value="39.9522" id="latitude" />
                    <input type="hidden" name="longitude" value="75.1642" id="longitude" />
                    <button id="submitThought">Pin Thought</button>
                </form>
            </section>
            <section id="login">
                <form id="login-form" autocomplete="off">
                    <textarea name="username" id="username" onblur="if(this.value == '') { this.value='username'}" onfocus="if (this.value == 'username') {this.value=''}">username</textarea>
                    <input name="password" id="password" type="password" onblur="if(this.value == '') { this.value='username'}" onfocus="if (this.value == 'username') {this.value=''}" value="password">
                    <button id="loginButton">Login</button>
                </form>
            </section>
            <?
            if (!isset($_SESSION["login"])) {
                echo "<style>section#register { display: none; }</style>";
                echo "<style>section#main { display: none; }</style>";
                echo "<style>section#login { display: block; }</style>";
            }
            if (isset($_SESSION["login"])) {
                echo "<style>section#register { display: none; }</style>";
                echo "<style>section#main { display: block; }</style>";
                echo "<style>section#login { display: none; }</style>";
            }
            ?>
        </section>
        <section id="register">
            <form id="register-form" autocomplete="off">
                <textarea name="username" id="usernameregister" onblur="if(this.value == '') { this.value='username'}" onfocus="if (this.value == 'username') {this.value=''}">username</textarea>
                <textarea name="email" id="emailregister" onblur="if(this.value == '') { this.value='email'}" onfocus="if (this.value == 'email') {this.value=''}">email</textarea>
                <input name="password" id="passwordregister" value="password" onblur="if(this.value == '') { this.value='password'}" onfocus="if (this.value == 'password') {this.value=''}" type="password" value="">
                <button id="registerButton">Register</button>
            </form>
        </section>
    </div>

    <div id="googlemap">

    </div>

    <p style="text-align:center;font-weight:bold;color:#fff;">Created by Bogdan, Jon, and Kevin.</p>

    <footer>
        <nav>
            <ul>
                <li><a href="#" class="loadMap">Map</a></li>
                <?
                if (isset($_SESSION["login"])) {
                    ?>
                    <li><a href="index.php" id="addThoughtButton">Add Thought</a></li>
                <? } else { ?>
                    <li><a href="index.php" id="registerShow">Register</a></li>
                <? } ?>
            </ul>
        </nav>
    </footer>

    <script src="http://www.motojunkyard.com/scriptor/gpsthoughts/css/jquery.autocomplete.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#loginButton").live("click",function(e){
                e.preventDefault();
                username=$("#username").val(); 
                password=$("#password").val(); 
                if(username&&password){
                    $.get("http://www.motojunkyard.com/scriptor/gpsthoughts/login.php?username="+username+"&password="+password, function(data){
                        if(data=="error"){
                            $("#error").html("Wrong Username/Password");
                        }
                        if(data=="loggedin"){
                            $("#main").fadeIn("slow");
                            $("#login").fadeOut("slow");
                        }                      
                    });
                }
            });

            //footer buttons
            $("#addThoughtButton").live("click",function(e){
                e.preventDefault();
                $("#googlemap").hide();
                $("#mainContent").show();
                $("#mainContent").fadeIn("slow");
                    
            });
            
            $("#registerShow").live("click",function(e){
                e.preventDefault();
                $("#googlemap").hide();
                $("#mainContent").show();
                $("#main").hide();
                $("#login").hide();
                $("#register").fadeIn("slow");
            });
                                
                                
            $(".loadMap").live("click",function(e){
                e.preventDefault();
                $.ajax({
                    url:"map.php",
                    data:"",
                    success: function(data){
                        //                            alert(data);
                        $("#googlemap").html('');
                        $("#googlemap").html(data);
                        $("#mainContent").hide();
                        $("#googlemap").show();
                        $("#googlemap").fadeIn("slow");
                    }
                });
            });
            //register functions
            $("#registerButton").live("click",function(e){
                e.preventDefault();
                username=$("#usernameregister").val(); 
                password=$("#passwordregister").val(); 
                email=$("#emailregister").val(); 
                if(username&&password&&email){
                    $.get("http://www.motojunkyard.com/scriptor/gpsthoughts/register.php?email="+email+"&username="+username+"&password="+password, function(data){
                        if(data){
                            if(data!="emailsent"){
                                if(data=="short"){
                                    $("#error").html("User name too short.");
                                }
                                if(data=="long"){
                                    $("#error").html("User name too long.");
                                }
                                if(data=="shortpassword"){
                                    $("#error").html("Password too short!");
                                }
                                if(data=="bademail"){
                                    $("#error").html("Invalid Email Address!");
                                }
                                if(data=="userexists"){
                                    $("#error").html("Username taken!");
                                }
                                if(data=="emailexists"){
                                    $("#error").html("User already associated with email!");
                                }
                            }else{
                                $("#error").html("Registered! Check your email for confirmation key.");
                                $("#register").fadeOut("slow");
                                $("#login").fadeIn("slow");
                            }
                        
                        }
                        if(data=="loggedin"){
                            $("#main").fadeOut("slow");
                            $("#register").fadeIn("slow");
                        }                      
                    });
                }
                return false;
            });
            //thought submit form
            $("#thought-form").submit(function(){
                var thought = encodeURIComponent($("#thought").val());
                var category = encodeURIComponent($("#category").val());
                var visibility = $("#visibility").val();
                var latitude = $("#latitude").val();
                var longitude = $("#longitude").val();
                $.ajax({
                    url:"post.php",
                    data:"&thought="+thought+"&category="+category+"&visibility="+visibility+"&latitude="+latitude+"&longitude="+longitude,
                    success: function(data){
                        if(data=="error"){
                            $("#error").html(data);
                        }
                        if(data=="posted"){
                            $("#error").html("Thought posted! <a href='#' class=loadMap>Click Here to View Map!</a>");                            
                        }
                    }
                });
                return false;
            });
            
            //autocomplete functions
            $("#category").autocomplete({
                url : 'http://www.motojunkyard.com/scriptor/gpsthoughts/post.php?type=autocomplete',
                maxItemsToShow: 10,
                useCache: false
            });

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
                    },
                    {maximumAge:0}
                );
                } else {
                    fail();
                }
            }

            function success(lat, lng){
                $("#latitude").val(lat);
                $("#longitude").val(lng);
                $.get("http://www.motojunkyard.com/scriptor/gpsthoughts/pull.php?type=getCity&latitude="+lat+"&longitude="+lng, function(data){
                    if(data){
                        value = data.split("|");
                        if(value[1]){
                            value[1]= "<br/>There are "+value[1]+" thoughts nearby!";
                        }
                        $("#error").html("Current Location: <strong><a href='#' class=loadMap>" + value[0] + "</a></strong> " + value[1]);
                        $("#error").show();                    
                    }
                });
            }
            function fail(){
                $("#error").show();
            }
            geoloc(success, fail);
            $("textarea,input").live("click", function(){
                geoloc(success, fail);
            });

        });
    </script>
</body>
</html>