
<!DOCTYPE html>
<html>
    <head>
        <title>GPS Thoughts</title>
        <link href="css/style.css" rel="stylesheet" />
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="http://www.motojunkyard.com/scriptor/gpsthoughts/css/jquery.autocomplete.js"></script>
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
        <script type="text/javascript">
            //<![CDATA[

            var customIcons = {
                restaurant: {
                    icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png',
                    shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
                },
                bar: {
                    icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png',
                    shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
                }
            };

            function load() {
                var map = new google.maps.Map(document.getElementById("map"), {
                    center: new google.maps.LatLng($("#latitude").val(),$("#longitude").val()),
                    zoom: 12,
                    mapTypeId: 'roadmap'
                });

                google.maps.event.addListener(map, 'dragend', function(){
                    window.setTimeout(function() {
                        c = map.getCenter();
                        //data = mapCenter.split(",");
                        loadData(c.lat(),c.lng());
                        
                        ///todo clear markers
                    }, 500);
                });
                
                var infoWindow = new google.maps.InfoWindow;
                function loadData(latitude,longitude){
                    downloadUrl("pull.php?type=populateMap&latitude="+latitude+"&longitude="+longitude, function(data) {
                        var xml = data.responseXML;
                        var markers = xml.documentElement.getElementsByTagName("marker");
                        for (var i = 0; i < markers.length; i++) {
                            var name = markers[i].getAttribute("name");
                            var address = markers[i].getAttribute("address");
                            var type = markers[i].getAttribute("type");
                            var point = new google.maps.LatLng(
                            parseFloat(markers[i].getAttribute("lat")),
                            parseFloat(markers[i].getAttribute("lng")));
                            var html = "<b>" + name + "</b> <br/>" + address;
                            var icon = customIcons[type] || {};
                            var marker = new google.maps.Marker({
                                map: map,
                                position: point,
                                icon: icon.icon,
                                shadow: icon.shadow
                            });
                            bindInfoWindow(marker, map, infoWindow, html);
                        }
                    });
                }
                loadData($("#latitude").val(),$("#longitude").val());
            }

            function bindInfoWindow(marker, map, infoWindow, html) {
                google.maps.event.addListener(marker, 'click', function() {
                    infoWindow.setContent(html);
                    infoWindow.open(map, marker);
                });
            }

            function downloadUrl(url, callback) {
                var request = window.ActiveXObject ?
                    new ActiveXObject('Microsoft.XMLHTTP') :
                    new XMLHttpRequest;

                request.onreadystatechange = function() {
                    if (request.readyState == 4) {
                        request.onreadystatechange = doNothing;
                        callback(request, request.status);
                    }
                };

                request.open('GET', url, true);
                request.send(null);
            }

            function doNothing() {}

            //]]>
            


            $(document).ready(function() {
                $("#category").autocomplete({
                    url : 'http://www.motojunkyard.com/scriptor/gpsthoughts/post.php',
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
                        }
                    );
                    } else {
                        fail();
                    }
                }

                function success(lat, lng){
                    $("#latitude").val(lat);
                    $("#longitude").val(lng);
                    load();
                    $.get("http://www.motojunkyard.com/scriptor/gpsthoughts/pull.php?latitude="+lat+"&longitude="+lng, function(data){
                        if(data){
                            value = data.split("|");
                            if(value[1]){
                                value[1]= "There are a total of "+value[1]+" thoughts in your area!";
                            }
                            $("#error").html("You are located in <b>" + value[0] + "</b> " + value[1]);
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
        <style>
            #map {
                position:absolute; width:100%; height:100%;
            }
        </style>
    </head>

    <body>
        <form>
            <input type="hidden" name="latitude" value="39.9522" id="latitude" />
            <input type="hidden" name="longitude" value="75.1642" id="longitude" />
        </form>

        <div id="map"></div>
    </body>
</html>

</html>