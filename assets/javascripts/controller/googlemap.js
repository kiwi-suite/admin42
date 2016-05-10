angular.module('admin42')
    .controller('GoogleMapController',['$scope', 'uiGmapGoogleMapApi', function($scope, uiGmapGoogleMapApi){
        var location = {
            latitude: false,
            longitude: false,
            zoom: 1
        };

        $scope.hiddenField = "";

        $scope.info = {
            address: "",
            latitude: "",
            longitude: ""
        };

        $scope.map = {
            show: false,
            center: {
                latitude: location.latitude || 48.19813003544663,
                longitude: location.longitude || 16.349973678588867
            },
            zoom: location.zoom,
            control: {},
            options: {
                streetViewControl: false,
                panControl: false,
                maxZoom: 20,
                minZoom: 1
            },
            events: {
                click: function (mapModel, eventName, originalEventArgs) {
                    var e = originalEventArgs[0];
                    var lat = e.latLng.lat(),
                        lon = e.latLng.lng();

                    setMarkerCoords (lat, lon);
                    $scope.$evalAsync();
                }
            }
        };

        $scope.searchbox = {
            template:'searchbox.tpl.html',
                events:{
                places_changed: function (searchBox) {
                    console.log(searchBox);
                }
            }
        };

        $scope.marker = {
            id: 0,
            options:{}
        };
        
        uiGmapGoogleMapApi.then(function(maps) {
            maps.visualRefresh = true;

            if (location.latitude) {
                setMarkerCoords(location.latitude, location.longitude);
            }

            $scope.map.show = true;
        });

        function setMarkerCoords(lat, lon) {
            $scope.marker = {
                id: 0,
                options: { draggable: true },
                latitude: lat,
                longitude: lon,
                events: {
                    dragend: function (marker, eventName, args) {

                    }
                }
            };

            $scope.hiddenField = lat + "|" + lon;

            $scope.info = {
                latitude: latitude,
                longitude: longitude
            };

            var geocoder = new google.maps.Geocoder;
            geocoder.geocode({'location': {lat: parseFloat(lat), lng: parseFloat(lon)}}, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    $scope.info.address = results[0]['formatted_address'];
                }
            });

        }
}]);
