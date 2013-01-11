<script type="text/javascript">
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
            zoom: 16,
            mapTypeId: 'roadmap'
        });

        google.maps.event.addListener(map, 'dragend', function(){
            window.setTimeout(function() {
                
                c = map.getCenter();

                loadData(c.lat(),c.lng());
                ///todo clear all markers
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
                    var username = markers[i].getAttribute("username");
                    var type = markers[i].getAttribute("type");
                    var point = new google.maps.LatLng(
                    parseFloat(markers[i].getAttribute("lat")),
                    parseFloat(markers[i].getAttribute("lng")));
                    var html = "<b>" + name + "</b> <br/>" 
                        + address + "<br/><font color=gray style='float:right'>-"+username+"</font>";
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
            load();
            $.get("http://www.motojunkyard.com/scriptor/gpsthoughts/pull.php?latitude="+lat+"&longitude="+lng, function(data){
                if(data){
                    value = data.split("|");
                    if(value[1]){
                        value[1]= "There are "+value[1]+" thoughts in your area!";
                    }
                    $("#error").html("Current Location: <b>" + value[0] + "</b> " + value[1] + "<br/>");
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
<div id="map"></div>
