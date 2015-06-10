<?php
error_reporting(-1);
ini_set('display_errors', 'On');
require_once './lib/Driver.php';
require_once './lib/CourierService.php';
require './lib/Location.php';
require_once './lib/Route.php';
require_once './lib/WayPoints.php';
require_once './lib/DbManager.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['provider'] == 'driver') {
        $driver = new Driver();
        $driver->setFirstName($_POST['driver-first-name']);
        $driver->setLastName($_POST['driver-last-name']);
        $driver->setNic($_POST['driver-nic']);
        $courierId = (!empty($_POST['driver-courier-service'])) ? $_POST['driver-courier-service'] : NULL;
        $driver->setCourierServiceProviderId($courierId);
        $driver->setMobileNumber($_POST['driver-mobile-number']);
        $driver->setOtherNumber($_POST['driver-other-number']);
        $driver->setAddress($_POST['driver-address']);
        $driver->save();

        $route = new Route();
        $route->setDriverId($driver->getId());
        $route->save();
        //TODO: create route, a driver can have multiple routes
        $waypoints = json_decode($_POST['waypoints'], FALSE);
        foreach ($waypoints as $waypoint) {
            $latitude = $waypoint->A;
            $longitude = $waypoint->F;
            $location = new Location();
            $location->setLat($latitude);
            $location->setLog($longitude);
            $location->save();

            $newWayPoint = new WayPoints();
            $newWayPoint->setRouteId($route->getId());
            $newWayPoint->setLocationId($location->getId());
            $newWayPoint->save();
        }
    } else {
        $courierService = new CourierService();
        $courierService->setAddress($_POST['courier-address']);
        $courierService->setName($_POST['courier-name']);
        $courierService->setTelephone($_POST['courier-telephone']);
        $courierService->setOtherTelephone($_POST['courier-other-number']);
        $courierService->save();
    }
}
?>
<html>

    <head>
        <title>
            eCarrier
        </title>
        <link rel="icon" type="image/png" href="favicon.ico">
        <!--Import materialize.css-->
        <link type="text/css" rel="stylesheet" href="materialize/css/materialize.min.css" media="screen,projection" />
        <link type="text/css" rel="stylesheet" href="css/style.css" media="screen,projection" />

        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    </head>

    <body>
        <nav>
            <div class="nav-wrapper green accent-4">
                <a href="#!" class="brand-logo">Geo Tracking - Registration</a>
                <ul class="right hide-on-med-and-down">
                    <li><a href="#"><i class="mdi-action-search"></i></a></li>
                    <li><a href="#"><i class="mdi-action-view-module"></i></a></li>
                    <li><a href="#"><i class="mdi-navigation-refresh"></i></a></li>
                    <li><a href="#"><i class="mdi-navigation-more-vert"></i></a></li>
                </ul>
            </div>
        </nav>
        <div class="row">
            <form class="col s12" method="POST" action="" name="dirver_form" id="dirver_form">
                <input name="waypoints" type="hidden" id="waypoint-holder"/>
                <div class="col s12">
                    <p>
                        <input class="with-gap service-provider-options" name="provider" type="radio" id="option-driver" value="driver"/>
                        <label for="option-driver">Driver</label>
                    </p>
                    <p>
                        <input class="with-gap service-provider-options" name="provider" type="radio" id="option-courier" value="courier" />
                        <label for="option-courier">Courier Service</label>
                    </p>
                </div>
                <div id="courier-details-place-holder">
                    <div class="row">
                        <div class="input-field col s6">
                            <input id="courier_service_name" type="text" class="validate" name="courier-name">
                            <label for="courier_service_name">Courier Service Name</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <textarea id="courier_service_address" class="materialize-textarea" length="255" name="courier-address"></textarea>
                            <label for="courier_service_address">Courier Service Address</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <input id="telephone-number" type="text" class="validate" name="courier-telephone">
                            <label for="telephone-number">Telephone Number</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="telephone-number-other" type="text" name="courier-other-number">
                            <label for="telephone-number-other">Other Number</label>
                        </div>
                    </div>
                </div>
                <div id="driver-details-place-holder">
                    <div class="row">
                        <div class="input-field col s6">
                            <input id="first_name" type="text" class="validate" name="driver-first-name">
                            <label for="first_name">First Name</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="last_name" type="text" class="validate" name="driver-last-name">
                            <label for="last_name">Last Name</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <input id="driver-nic" type="text" class="validate" name="driver-nic">
                            <label for="driver-nic">NIC</label>
                        </div>
                        <div class="input-field col s6">
                            <select id="courrier_service" name="driver-courier-service">
                                <option value="" selected>None</option>
                                <option value="1">Option 1</option>
                                <option value="2">Option 2</option>
                                <option value="3">Option 3</option>
                            </select>
                            <label for="courrier_service">Courier Service</label>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <textarea id="courier_service_address" class="materialize-textarea" length="255" name="driver-address"></textarea>
                            <label for="courier_service_address">Driver Address</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <input id="first_name" type="text" class="validate" name="driver-mobile-number">
                            <label for="first_name">Mobile Number</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="last_name" type="text" class="validate" name="driver-other-number">
                            <label for="last_name">Other Number</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <p id="added-list">Places</p>
                            <input id="pac-input" class="controls" type="text" placeholder="Enter a location">
                            <div id="map-canvas" class="s6"></div>
                        </div>
                    </div>
                    <div class="row">
                        <ul class="collection with-header" id="selected-location-place-holder">
                            <li class="collection-header"><h6>Selected Locations</h6></li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <input type="button" class="btn picker__header" value="Save"/>
                </div>

            </form>
        </div>

        <button data-target="autocomplete-modal" class="btn modal-trigger hide" id="autocomplete-modal-wrapper">Modal</button>
        <div id="autocomplete-modal" class="modal">
            <div class="modal-content">
                <h4>Error - Auto Complete</h4>
                <p>Auto-complete is returned place contains no geometry</p>
            </div>
            <div class="modal-footer">
                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat btn">Close</a>
            </div>
        </div>

        <!--Import jQuery before materialize.js-->
        <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
        <script type="text/javascript" src="materialize/js/materialize.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>
        <script type="text/javascript" src="js/map.js"></script>
        <script type="text/javascript" src="js/app.js"></script>
        <script type="text/javascript" src="js/registration.js"></script>
    </body>

</html>


