let placeSearch;
let autocomplete;
let locatePressAdminMap;
const componentForm = {
  country: "long_name",
};
var infowindow, marker;


function load_map() {
  var mapDivs = document.getElementById('lp-meta-map-canvas');
  var dSets = mapDivs.dataset.latlong;
  var dSetsArr = dSets.split('/');
  var lats = parseFloat(dSetsArr[0]);
  var longs = parseFloat(dSetsArr[1]);

  //init map
  locatePressAdminMap = new google.maps.Map(mapDivs, {
    center: { lat: -33.8688, lng: 151.2195 },
    zoom: 3
  });

  // marker = new google.maps.Marker({
  //   position: { lat: -33.8688, lng: 151.2195 },
  //   map: locatePressAdminMap,
  //   anchorPoint: new google.maps.Point(0, -29),
  //   draggable: true,
  // });


  //init geocoder
  var geocoder = new google.maps.Geocoder();

  var bounds = new google.maps.LatLngBounds();
  var input = document.getElementById('lp-search-input');
  locatePressAdminMap.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

  autocomplete = new google.maps.places.Autocomplete(input);
  autocomplete.bindTo('bounds', locatePressAdminMap);
  autocomplete.addListener("place_changed", fillInAddress);
  infowindow = new google.maps.InfoWindow();


  //if no location is set initally use add a marker on clicked position for once then drag the marker
  function placeMarker() {
    google.maps.event.addListener(locatePressAdminMap, 'click', function (event) {
      if (marker){
        marker.setMap(null);
      }
      marker = new google.maps.Marker({
        position: event.latLng,
        map: locatePressAdminMap,
        anchorPoint: new google.maps.Point(0, -29),
        draggable: true,
      });

      geocoder.geocode({ 'latLng': marker.getPosition() }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          if (results[0]) {

            document.getElementById('country').value = results[0].formatted_address;
            document.getElementById('lp_location_lat_long').value = marker.getPosition().lat() + '/' + marker.getPosition().lng();
            document.getElementById('lp_location_latitude').value = marker.getPosition().lat();
            document.getElementById('lp_location_longitude').value = marker.getPosition().lng();

            infowindow.setContent(results[0].formatted_address);
            infowindow.open(locatePressAdminMap, marker);
          }
        }
      });

      dragableMarker();

    });

  };

  //if location is set already and set marker to that given position
  function defaultMarker(latitude, longitude) {

    locatePressAdminMap.setCenter({ lat: latitude, lng: longitude });
    locatePressAdminMap.setZoom(15);

    marker = new google.maps.Marker({
      position: { lat: latitude, lng: longitude },
      map: locatePressAdminMap,
      anchorPoint: new google.maps.Point(0, -29),
      draggable: true,
    });

    dragableMarker();
  }

  //make marker dragabale and get location detail and fill in respective fields
  function dragableMarker() {

    google.maps.event.addListener(marker, 'dragend', function () {
      geocoder.geocode({ 'latLng': marker.getPosition() }, function (results, status) {
        if (status == google.maps.GeocoderStatus.OK) {

          // for(var i=0; i<results[1].address_components.length; i++){

          var address = results[0].address_components;
          var array = {};

					jQuery.each(address, function(k,v1) {jQuery.each(v1.types, function(k2, v2){array[v2]=v1.long_name});});
					//console.log(address);

					//console.log(array);
            // if(results[1].address_components[i].types[0] == "administrative_area_level_1"){

              
            //   }
          // }
          if (results[0]) {

            document.getElementById('country').value = results[0].formatted_address;     
            document.getElementById('lp_location_lat_long').value = marker.getPosition().lat() + '/' + marker.getPosition().lng();
            document.getElementById('lp_location_latitude').value = marker.getPosition().lat();
            document.getElementById('lp_location_longitude').value = marker.getPosition().lng();

            infowindow.setContent(results[0].formatted_address);
            infowindow.open(locatePressAdminMap, marker);
          }
        }
      });
    });

  }


  if (isNaN(lats) && isNaN(longs)) {
    placeMarker();
  } else {
    defaultMarker(lats, longs);
    placeMarker();

  }

  if (dSets !== '') {
    locatePressAdminMap.setCenter(marker.position);

  } else {

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (position) {
        initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        locatePressAdminMap.setCenter(initialLocation);
      });
    }

  }
}



function fillInAddress() {
  const place = autocomplete.getPlace();
  infowindow.close();
  //marker.setVisible(false);
  if (!place.geometry) {
    window.alert("Autocomplete's returned place contains no geometry");
    return;
  }
  // If the place has a geometry, then present it on a map.
  if (place.geometry.viewport) {
    locatePressAdminMap.fitBounds(place.geometry.viewport);
  } else {
    locatePressAdminMap.setCenter(place.geometry.location);
    locatePressAdminMap.setZoom(17);
  }
  document.getElementById('lp_location_lat_long').value = place.geometry.location.lat() + '/' + place.geometry.location.lng();
  document.getElementById('country').value = place.formatted_address;
  document.getElementById('lp_location_latitude').value = place.geometry.location.lat();
  document.getElementById('lp_location_longitude').value = place.geometry.location.lng();
  // marker.setIcon(({
  //   url: place.icon,
  //   size: new google.maps.Size(71, 71),
  //   origin: new google.maps.Point(0, 0),
  //   anchor: new google.maps.Point(17, 34),
  //   scaledSize: new google.maps.Size(35, 35)
  // }));
}

google.maps.event.addDomListener(window, 'load', load_map);


