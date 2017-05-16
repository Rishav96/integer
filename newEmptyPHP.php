<!doctype html>
<html lang="en">
   <head>
        <title>jQuery mobile with Google maps</title>
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
        <script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3&sensor=false&language=en"></script>
        <script type="text/javascript">

            var map,
                currentPosition,
                directionsDisplay, 
                directionsService,
                destinationLatitude = 59.3426606750,
                destinationLongitude = 18.0736160278;

            function initializeMapAndCalculateRoute(lat, lon)
            {
                directionsDisplay = new google.maps.DirectionsRenderer(); 
                directionsService = new google.maps.DirectionsService();

                currentPosition = new google.maps.LatLng(lat, lon);

                map = new google.maps.Map(document.getElementById('map_canvas'), {
                   zoom: 15,
                   center: currentPosition,
                   mapTypeId: google.maps.MapTypeId.ROADMAP
                 });

                directionsDisplay.setMap(map);

                 var currentPositionMarker = new google.maps.Marker({
                    position: currentPosition,
                    map: map,
                    title: "Current position"
                });

                // current position marker info
                /*
                var infowindow = new google.maps.InfoWindow();
                google.maps.event.addListener(currentPositionMarker, 'click', function() {
                    infowindow.setContent("Current position: latitude: " + lat +" longitude: " + lon);
                    infowindow.open(map, currentPositionMarker);
                });
                */

                // calculate Route
                calculateRoute();
            }

            function locError(error) {
               // the current position could not be located
            }

            function locSuccess(position) {
                // initialize map with current position and calculate the route
                initializeMapAndCalculateRoute(position.coords.latitude, position.coords.longitude);
            }

            function calculateRoute() {

                var targetDestination =  new google.maps.LatLng(destinationLatitude, destinationLongitude);
                if (currentPosition != '' && targetDestination != '') {

                    var request = {
                        origin: currentPosition, 
                        destination: targetDestination,
                        travelMode: google.maps.DirectionsTravelMode["DRIVING"]
                    };

                    directionsService.route(request, function(response, status) {
                        if (status == google.maps.DirectionsStatus.OK) {
                            directionsDisplay.setPanel(document.getElementById("directions"));
                            directionsDisplay.setDirections(response); 

                            /*
                                var myRoute = response.routes[0].legs[0];
                                for (var i = 0; i < myRoute.steps.length; i++) {
                                    alert(myRoute.steps[i].instructions);
                                }
                            */
                            $("#results").show();
                        }
                        else {
                            $("#results").hide();
                        }
                    });
                }
                else {
                    $("#results").hide();
                }
            }

            $(document).live("pagebeforeshow", "#map_page", function() {
                // find current position and on success initialize map and calculate the route
                navigator.geolocation.getCurrentPosition(locSuccess, locError);
            });

        </script>
    </head>
    <body>
        <div id="basic-map" data-role="page">
            <div data-role="header">
                <h1><a data-ajax="false" href="/">jQuery mobile with Google maps v3</a></h1>
            </div>
            <div data-role="content">   
                <div class="ui-bar-c ui-corner-all ui-shadow" style="padding:1em;">
                    <div id="map_canvas" style="height:350px;"></div>
                </div>
                <div id="results" style="display:none;">
                    <div id="directions"></div>
                </div>
            </div>
        </div>      
    </body>
</html>