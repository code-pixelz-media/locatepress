var LocatePress = {};

LocatePress.App = (function ($) {
	var LpForm = $('.lp-search-filter-form'),
		LpKeyword = $('.lp-input-keyword'),
		LpLocSearch = $('.lp-loc-search'),
		LpLoctype = $('.lp-search-filter-loc'),
		LpCat = $('.lp-search-filter-cat'),
		LpLat = $('.lp_location_lat'),
		LpLng = $('.lp_location_lng'),
		LpListing = $('.lp-display-listing'),
		LpMap = $('#lp-display-map'),
		LpReset = $('#lp-resetbutton'),
		LocatePressMap, icons, marker, mapDiv, popup,
		formDataObj = {}, markerArrList = [],
		//zoom = parseInt(lp_settings.map.lp_map_zoom),
		autocompleteSearch, bounds, infos;




	// Ajax Request For Search Filters
	function make_ajax_request(datas, reinit = true) {
		var data = { 'action': 'locatepress_ajax_search_filter', 'data': datas, }
		jQuery.post(lp_settings.ajaxUrl, data, function (results) {
			if (reinit) {
				if (checkel(LpMap)) {
					locatePressMapInit();
					pan_map_according_to_url();
					markerArrList = [];
					locatePressSetMarkers(results.marker_data);
					get_and_display_visible_markers();

				}
				if (checkel(LpListing)) {
					LpListing.empty();
					LpListing.prepend(results.listings);
				}

			} else {

			}
		}, 'JSON');
	}


	//Autocomplete Function


	//initialize map for single page
	function locatePressSingleMap(id = 'single-map') {

		var singleMarker, singleInfoWindow, singleIcon;
		singleMapDiv = document.getElementById(id);
		if (singleMapDiv != null) {
			var latiLong = singleMapDiv.dataset.latlong.split('/');
			var marker_icon_url = singleMapDiv.dataset.marker;

			if (latiLong.length > 1) {
				if (marker_icon_url) {
					singleIcon = marker_icon_url;

				} else {
					singleIcon = lp_settings.map.lp_default_marker;
				}
				var mi = {
					url: singleIcon,
					scaledSize: new google.maps.Size(40, 40),

				};
				var lati = parseFloat(latiLong[0]);
				var longi = parseFloat(latiLong[1]);
				var singlePageMap = new google.maps.Map(singleMapDiv, {
					zoom: 15,
					center: new google.maps.LatLng(lati, longi),
					mapTypeId: google.maps.MapTypeId.ROADMAP
				});

				singleMarker = new google.maps.Marker({
					position: { lat: lati, lng: longi },
					singlePageMap,
					icon: mi,
				});
				singleMarker.setMap(singlePageMap);
				singleInfoWindow = new google.maps.InfoWindow();
				google.maps.event.addListener(singleMarker, 'click', (function (singleMarker) {

					singleInfoWindow.setContent(singleMapDiv.dataset.info);
					singleInfoWindow.open(singlePageMap, singleMarker);

				})(singleMarker));

			}
		}
	}
	//initialiaze map
	function locatePressMapInit() {

		var LocatePressMapOptions = {
			zoom: 2,
			center: new google.maps.LatLng(43.4130, 34.2993),

		}
		if (lp_settings.map.lp_zoom_control === 'off') {
			LocatePressMapOptions.zoomControl = false;
		}

		if (lp_settings.map.lp_full_screen_control === 'off') {
			LocatePressMapOptions.fullscreenControl = false;
		}
		if (lp_settings.map.lp_maptype_control === 'off') {
			LocatePressMapOptions.mapTypeControl = false;
		}
		if (lp_settings.map.lp_streetview_control === 'off') {
			LocatePressMapOptions.streetViewControl = false;
		}

		if (lp_settings.map.lp_map_type !== '') {
			LocatePressMapOptions.mapTypeId = lp_settings.map.lp_map_type;
		} else {
			LocatePressMapOptions.mapTypeId = 'roadmap';
		}
		var el = document.getElementById('lp-display-map');
		LocatePressMap = new google.maps.Map(LpMap.get(0), LocatePressMapOptions);


	}


	//set markers for map variables
	//icon data must be in format of array()
	function locatePressSetMarkers(mark) {

		infos = new google.maps.InfoWindow();
		bounds = new google.maps.LatLngBounds();

		if (mark.length != 0) {
			for (var k = 0; k < mark.length; k++) {

				var iconUrl;

				if (mark[k].marker_icon) {
					iconUrl = mark[k].marker_icon;

				} else {
					iconUrl = lp_settings.map.lp_default_marker;
				}

				var mlist = new google.maps.Marker({
					position: new google.maps.LatLng(mark[k].latitude, mark[k].longitude),
					title: mark[k].title,
					custom: mark[k].p_id

				});

				if (iconUrl) {
					icons = {
						url: iconUrl,
						scaledSize: new google.maps.Size(40, 40),
					};

					//add custom icon if available
					mlist.setIcon(icons);
				} else {

					mlist.setIcon(null);

				}



				mlist.setMap(LocatePressMap);
				markerArrList.push(mlist);

				bounds.extend(mlist.position);

				google.maps.event.addListener(mlist, 'click', (function (mlist, k) {
					return function () {
						var contInfo = `<div class="marker-container">
								  <img src="${mark[k].featured_image}" class='info-marker'>
								  <p class="info-title">${mark[k].title}</p>
								  <p class = "info-location">${mark[k].location}</p>
								  <a href="${mark[k].permalink}"><button class="load-link">View Location</button></a>
								  </div>`;
						infos.setContent(contInfo);
						infos.open(LocatePressMap, mlist);
						LocatePressMap.panTo(this.getPosition());

					}

				})(mlist, k));

			}
			//add marker cluster
			new MarkerClusterer(LocatePressMap, markerArrList, {
				imagePath:
					"https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m",
			});

			LocatePressMap.fitBounds(bounds);
		}else{
			return;
		}

	}

	//Form data in objeect
	function get_form_data() {

		var formData = $('.lp-search-filter-form').serializeArray();

		$.each(formData, function (_, kv) {

			formDataObj[kv.name] = kv.value;

		});

		return formDataObj;

	}




	function checkel(el) {
		if (typeof el.get(0) !== 'undefined') {
			return true;
		} else {
			return false;
		}
	}

	function on_change_handlers() {

		var fData = get_form_data();
		if (checkel(LpKeyword)) {
			LpKeyword.keyup(function () {
				fData.lp_search_keyword = $(this).val();
				make_ajax_request(fData);

			});
		}

		if (checkel(LpLocSearch)) {

			autocompleteSearch = new google.maps.places.Autocomplete(LpLocSearch.get(0));

			if (checkel(LpMap)) {

				google.maps.event.addListener(autocompleteSearch, 'place_changed', function (e) {
					var place = autocompleteSearch.getPlace();
					var components =place.address_components;
					var array = {};

					$.each(components, function(k,v1) {$.each(v1.types, function(k2, v2){array[v2]=v1.long_name});});
					//console.log(place.address_components);

					//console.log(array);
					if (!place.geometry) {
						window.alert("Autocomplete's returned place contains no geometry");
						return;
					}

					if (place.geometry.viewport) {
						var lat = place.geometry.location.lat();
						var lng = place.geometry.location.lng();

						fData.lp_location_latitude = lat;
						fData.lp_location_longitude = lng;
						
						make_ajax_request(fData);
						LocatePressMap.fitBounds(place.geometry.viewport);

					} else {
						LocatePressMap.setCenter(place.geometry.location);

					}

					LpLocSearch.keyup(function () {
						if (this.value.length === 0) {
							fData.lp_location_latitude = "";
							fData.lp_location_longitude = "";
							make_ajax_request(fData);

						}

					});

				});
			} else {
				//console.log('okay');
			}
		}

		if (checkel(LpLoctype)) {

			LpLoctype.change(function () {

				fData.lp_search_filter_loctype = $(this).val();
				make_ajax_request(fData);

			});
		}

		if (checkel(LpCat)) {
			LpCat.change(function () {
				fData.lp_search_filter_cat = $(this).val();
				make_ajax_request(fData);
			});
		}

		if (checkel(LpReset)) {
			LpReset.click(function () {
				var queryString = window.location.search;
				var urlParams = new URLSearchParams(queryString);
				urlParams.delete('lp_search_filter_loc');
				//console.log(urlParams);
				LpForm.get(0).reset();
				LpKeyword.val('');
				LpLocSearch.val('');
				LpLoctype.val('');
				LpCat.val('');
				LpLat.val('');
				LpLng.val('');

				fData.lp_search_filter_loc = '';
				fData.lp_search_filter_loctype = '';
				fData.lp_search_keyword = '';
				fData.lp_search_filter_cat = '';
				fData.lp_location_latitude = '';
				fData.lp_location_longitude = '';
				make_ajax_request(fData);
			});
		}
	}


	function pan_map_according_to_url() {
		var queryString = window.location.search;
		var urlParams = new URLSearchParams(queryString);
		var locationq = urlParams.get('lp_search_filter_loc');
		if (locationq !== '') {
			if (LpLocSearch.val() !== '') {
				geocodeAddress(locationq);
			}
			// 
		} else {
			return;
		}

	}
	//show visible markers listings

	function showVisibleMarkers() {

		var bnds = LocatePressMap.getBounds();

		var popList = [];
		for (var i = 0; i < markerArrList.length; i++) {
			var marker = markerArrList[i];

			if (bnds.contains(marker.getPosition()) === true) {

				popList.push(marker.custom);

			}
			else {

				LpListing.empty();
			}
		}
		//console.log (popList);
		return popList;
	}

	function locatepressAutoCompleteListings(id) {

		var dataPost = { 'action': 'locatepress_listings_visible_markers', 'data': id, }
		jQuery.post(lp_settings.ajaxUrl, dataPost, function (responseList) {
			LpListing.empty();
			LpListing.prepend(responseList.html);

		}, 'JSON');

	}




	function get_and_display_visible_markers() {
		if (checkel(LpListing)) {
			google.maps.event.addListener(LocatePressMap, 'idle', function () {

				var visibleItems = showVisibleMarkers();
				if (visibleItems.length > 0) {
					//console.log(visibleItems);
					locatepressAutoCompleteListings(visibleItems);
				}

			});
		} else {
			return;
		}
	}

	function geocodeAddress(address) {

		var geocoder = new google.maps.Geocoder();

		geocoder.geocode({ 'address': address }, function (results, status) {
			if (status == 'OK') {


				LocatePressMap.fitBounds(results[0].geometry.viewport);

				get_and_display_visible_markers();
			} else {

				return;
			}
		});
	}


	return {
		init: function () {

			if (checkel(LpForm)) {
				make_ajax_request(get_form_data());

			}
			locatePressSingleMap();
			on_change_handlers();

		}

	}
})(jQuery);

jQuery(document).ready(function () {

	LocatePress.App.init();

});

jQuery(document).ready(function() {

    var sync1 = jQuery("#sync1");
    var sync2 = jQuery("#sync2");
    var slidesPerPage = 4; //globaly define number of elements per page
    var syncedSecondary = true;

    sync1.owlCarousel({
        items: 1,
        slideSpeed: 2000,
        nav: true,
        autoplay: false, 
        dots: true,
        loop: true,
        responsiveRefreshRate: 200,
        navText: ['<svg width="100%" height="100%" viewBox="0 0 11 20"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M9.554,1.001l-8.607,8.607l8.607,8.606"/></svg>', '<svg width="100%" height="100%" viewBox="0 0 11 20" version="1.1"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M1.054,18.214l8.606,-8.606l-8.606,-8.607"/></svg>'],
    }).on('changed.owl.carousel', syncPosition);

    sync2
        .on('initialized.owl.carousel', function() {
            sync2.find(".owl-item").eq(0).addClass("current");
        })
        .owlCarousel({
            items: slidesPerPage,
            dots: true,
            nav: true,
            smartSpeed: 200,
            slideSpeed: 500,
            slideBy: slidesPerPage, //alternatively you can slide by 1, this way the active slide will stick to the first item in the second carousel
            responsiveRefreshRate: 100
        }).on('changed.owl.carousel', syncPosition2);

    function syncPosition(el) {
        //if you set loop to false, you have to restore this next line
        //var current = el.item.index;

        //if you disable loop you have to comment this block
        var count = el.item.count - 1;
        var current = Math.round(el.item.index - (el.item.count / 2) - .5);

        if (current < 0) {
            current = count;
        }
        if (current > count) {
            current = 0;
        }

        //end block

        sync2
            .find(".owl-item")
            .removeClass("current")
            .eq(current)
            .addClass("current");
        var onscreen = sync2.find('.owl-item.active').length - 1;
        var start = sync2.find('.owl-item.active').first().index();
        var end = sync2.find('.owl-item.active').last().index();

        if (current > end) {
            sync2.data('owl.carousel').to(current, 100, true);
        }
        if (current < start) {
            sync2.data('owl.carousel').to(current - onscreen, 100, true);
        }
    }

    function syncPosition2(el) {
        if (syncedSecondary) {
            var number = el.item.index;
            sync1.data('owl.carousel').to(number, 100, true);
        }
    }

    sync2.on("click", ".owl-item", function(e) {
        e.preventDefault();
        var number = jQuery(this).index();
        sync1.data('owl.carousel').to(number, 300, true);
    });
});