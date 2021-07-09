  let placeSearch;
  let autocomplete;
  let locatePressAdminMap;
  const componentForm = {
        // street_number: "short_name",
        // route: "long_name",
        // locality: "long_name",
        // administrative_area_level_1: "short_name",
        country: "long_name",
        //postal_code: "short_name",

  };
  var infowindow , marker;


  function load_map(){
      var mapDivs= document.getElementById('lp-meta-map-canvas');
      var dSets  =mapDivs.dataset.latlong;
      var dSetsArr = dSets.split('/');
      var lats = parseFloat(dSetsArr[0]);
      var longs = parseFloat(dSetsArr[1]);
       locatePressAdminMap = new google.maps.Map(mapDivs, {
        center: {lat: -33.8688, lng: 151.2195},
        zoom: 13
      });
      var bounds = new google.maps.LatLngBounds();
      var input = document.getElementById('lp-search-input');
      locatePressAdminMap.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
      autocomplete = new google.maps.places.Autocomplete(input);
      autocomplete.bindTo('bounds', locatePressAdminMap);
      autocomplete.addListener("place_changed", fillInAddress);
      infowindow = new google.maps.InfoWindow();
      marker = new google.maps.Marker({
        position : {lat: lats , lng : longs},
          map: locatePressAdminMap,
          anchorPoint: new google.maps.Point(0, -29),
          
      });

    if(dSets !== ''){
      locatePressAdminMap.setCenter(marker.position);

    }else {

      if (navigator.geolocation) {
         navigator.geolocation.getCurrentPosition(function (position) {
             initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
             locatePressAdminMap.setCenter(initialLocation);
         });
     }

     }
  }

  function fillInAddress(){
      const place = autocomplete.getPlace();
      infowindow.close();
      marker.setVisible(false);
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
       document.getElementById('lp_location_lat_long').value=place.geometry.location.lat()+'/'+place.geometry.location.lng();
       document.getElementById('country').value=place.formatted_address;
          marker.setIcon(({
              url :place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(35, 35)
          }));
        marker.setPosition(place.geometry.location);
        marker.setVisible(true);
        // Get each component of the address from the place details,
        // and then fill-in the corresponding field on the form.

        // for (const component in componentForm) {

         
        //   document.getElementById(component).value = "";

        //   // document.getElementById(component).disabled = false;
        // }

        // for (const component of place.address_components) {
        //   const addressType = component.types[0];

        //   if (componentForm[addressType]) {
        //     const val = component[componentForm[addressType]];
        //     document.getElementById(addressType).value = val;
        //   }
        // }
  }

  google.maps.event.addDomListener(window, 'load', load_map);
