<?php

/**
 * This class is loaded on the front-end since its main job is 
 * to display the WhatsApp box.
 */

class GMFCF_Frontend {
	
	public function __construct () {
		add_action( 'wp_enqueue_scripts',  array( $this, 'gmfcf_insta_scritps' ) );

		add_action( 'wp_footer', array($this,'GMFCF_cf7_gpa_plugin_script'), 21, 1 );
	}
	public function gmfcf_insta_scritps () {
		wp_enqueue_style('gmfcf-stylee', GMFCF_PLUGIN_URL . '/assents/css/style.css', array(), '1.0.0', 'all');

	}
	public function GMFCF_cf7_gpa_plugin_script() 
	{
		$gpa_page = get_option( 'gmfcf_cf7_geo_gpa_page' );
		$gmfcf_country_code = get_option( 'gmfcf_country_code','' );
		
	?>
<script>
window.onload = function initialize_gpa() {
	

	var optionsc = {
		<?php
		if($gmfcf_country_code!=''){
		  	echo "componentRestrictions: {country: ".json_encode(explode(",",$gmfcf_country_code))."},";
		  }

		?>
		
	};
    var acInputs = document.getElementsByClassName("wpcf7-googlemap");
	for (var i = 0; i < acInputs.length; i++) {
		ApplyAutoComplete(acInputs[i],optionsc)
	}

}
function ApplyAutoComplete(input,optionsc) {
		

		var autocomplete = new google.maps.places.Autocomplete(input,optionsc);
		autocomplete.inputId = input.id;
		autocomplete.inputName = input.name;
		const lat_c = 6.423750;
		const lng_c = -66.589729;
		const zoomlevel_c = 7;
		const map = new google.maps.Map(document.getElementById(autocomplete.inputName+"map"), {
					zoom: zoomlevel_c,
					center: {lat:lat_c, lng: lng_c},
				});
		const geocoder = new google.maps.Geocoder();
		const marker = new google.maps.Marker({
				    position: {lat:lat_c, lng: lng_c},
				    map,
				    draggable: true 
				  });
		
		var address2Field = document.querySelector("#"+autocomplete.inputName+"_address2");
		var postalField = document.querySelector("#"+autocomplete.inputName+"_postcode");
		
		
		google.maps.event.addListener(autocomplete, 'place_changed', function () {
			
			const place = autocomplete.getPlace();
			console.log(place);
			
			console.log(autocomplete.inputName);
			document.getElementById(autocomplete.inputName+"map").style.display = "block";
			
			/*const marker = new google.maps.Marker({
				
				map,
				draggable: true 
			});
			marker.setVisible(false);
			handleMarkerDragEnd(marker, autocomplete, autocomplete.inputName);
			google.maps.event.addListener(marker, 'dragend', function() {
				geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						if (results[0]) {
							var countryMarker = gmaddresComponent('country', results[0], true);
							console.log(countryMarker);
							setinpusal(autocomplete.inputName,results,'dragend');
						}
					}
				});
			});*/
			if (place.geometry.viewport) {
				map.fitBounds(place.geometry.viewport);
			} else { 
				map.setCenter(place.geometry.location);
				map.setZoom(zoomlevel_c);
			}
			marker.setPosition(place.geometry.location);
			marker.setVisible(true);
			
			
			setinpusal(autocomplete.inputName,place.address_components,'changed');
		});

		google.maps.event.addListener(marker, 'dragend', function() {
			console.log("gg")
			geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if (results[0]) {
						//console.log(results);
						//console.log(results[0].formatted_address);
						var countryMarker = gmaddresComponent('country', results[0], true);
						console.log(countryMarker);
						setinpusal(autocomplete.inputName,results,'dragend');
					}
				}
			});
		});

		function setinpusal(input,componentinput,type){
			//console.log(componentinput);
			let address1 = "";
			let postcode = "";
			if(type=='dragend'){
				if(document.getElementsByName(input)){
		        	document.querySelector("input[name="+input+"]").value = componentinput[0].formatted_address;
		    	}
			}
			for (const component of componentinput) {
			    const componentType = component.types[0];
			    //console.log(componentType);
			    if(type=='changed'){
			    	var mycalvailw = component;
			    }else{
			    	var mycalvailw = component.address_components[0];
			    }
			   // console.log(mycalvailw);
			    switch (componentType) {
			      case "street_number": {
			        address1 = `${mycalvailw.long_name} ${address1}`;
			        break;
			      }

			      case "route": {
			        address1 += mycalvailw.short_name;
			        break;
			      }

			      case "postal_code": {
			        postcode = `${mycalvailw.long_name}${postcode}`;
			        break;
			      }

			      case "postal_code_suffix": {
			        postcode = `${postcode}-${mycalvailw.long_name}`;
			        break;
			      }
			      case "locality":
			      	if(document.getElementById(input+"_locality")){
			      		document.querySelector("#"+input+"_locality").value = mycalvailw.long_name;
			      	}
			        
			        break;
			      case "administrative_area_level_1": {
			      	if(document.getElementById(input+"_state")){
				        document.querySelector("#"+input+"_state").value = mycalvailw.long_name;
				    }
			        break;
			      }
			      case "country":
			      	console.log(component);
			      	if(document.getElementById(input+"_country")){
			        	document.querySelector("#"+input+"_country").value = mycalvailw.long_name;
			    	}
			        break;
			    }
			}
			if(document.getElementById(input+"_address2")){
				address2Field.value = address1;
			}
			console.log(autocomplete.inputName);
			if(document.getElementById(input+"_postcode")){
				postalField.value = postcode;
			}
		}
		function gmaddresComponent(type, geocodeResponse, shortName) {
		  for(var i=0; i < geocodeResponse.address_components.length; i++) {
		    for (var j=0; j < geocodeResponse.address_components[i].types.length; j++) {
		      if (geocodeResponse.address_components[i].types[j] == type) {
		        if (shortName) {
		          return geocodeResponse.address_components[i].short_name;
		        }
		        else {
		          return geocodeResponse.address_components[i].long_name;
		        }
		      }
		    }
		  }
		  return '';
		}
}
</script>
	<?php 
				
	}
}
