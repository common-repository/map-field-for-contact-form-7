<?php
/**
* This class is loaded on the front-end since its main job is
* to display the WhatsApp box.
*/
class GMFCF_Backend {
	
	public function __construct () {
		add_action('admin_menu', array($this,'GMFCF_cf7_address_autocomplete_menu_item'));
		add_action('admin_init', array($this,'GMFCF_cf7_address_autocomplete_display_gpa_fields'));
		add_action( 'wp_enqueue_scripts', array($this,'GMFCF_cf7_gpa_load_user_api' ));
	}
	public function GMFCF_cf7_address_autocomplete_menu_item()
	{
		add_submenu_page(
											'wpcf7',
											__('Google Place API','google-map-place-api'),
											__('Google Place API','google-map-place-api'), 
											'manage_options',
											'google-map-place-api',
											array($this, 'GMFCF_cf7_google_place_admin' ),
										);
	}
	public function GMFCF_cf7_google_place_admin()
	{
		$gmfcf_country_code = get_option('gmfcf_country_code','');
		$gmfcf_address_option = get_option('gmfcf_address_option',array());
		if(empty($gmfcf_address_option)){
        	$gmfcf_address_option = array();
        }

        
?>
		<div class="wrap">
			<h1>Google Places API Info.</h1>
			<form method="post" action="options.php">
				<?php settings_fields( 'gmfcf_section' ); ?>
				<table class="form-table" role="presentation">
				   <tbody>
				      <tr>
				         <th scope="row">Google Places API Key</th>
				         <td>
				            <input type="text" class="regular-text" required="" name="gmfcf_cf7_geo_api_key" id="api_key" value="<?php echo esc_attr(get_option('gmfcf_cf7_geo_api_key'));?>">
				            <p class="description">Google requires an API key to retrieve Auto Complete Address for job listings. Acquire an API key from the <a target="_blank" href="https://developers.google.com/maps/documentation/javascript/places-autocomplete">Google Maps API developer site</a>.</p>
				         </td>
				      </tr>
				      <tr valign="top">
			            <th scope="row">
			               <label>Specific Country Address Show</label>
			            </th>
			            <td>
			               <input class="regular-text" type="text" placeholder="US,AU" name="gmfcf_country_code" value="<?php echo esc_attr($gmfcf_country_code); ?>"  />
			               <p class="description"><strong>Default is blank</strong> it will be show all Country address if you want to particular country address than add two digit code <strong>Example: France for add fr</strong> <a href="https://codesmade.com/demo/country-code-list/">Get Country Code list</a></p>
			            </td>
			         </tr>
			         <tr valign="top">
			            <th scope="row">
			               <label>Enable Address Field Option</label>
			            </th>
			            <td>
			            	 <input  type="checkbox"  name="gmfcf_address_option[]" value="street_number" <?php echo (in_array("street_number", $gmfcf_address_option))?'checked':''?> />Street Number<br/>
			            	 <input  type="checkbox"  name="gmfcf_address_option[]" value="postcode" <?php echo (in_array("postcode", $gmfcf_address_option))?'checked':''?>  />Postcode<br/>
			            	 <input  type="checkbox"  name="gmfcf_address_option[]" value="locality" <?php echo (in_array("locality", $gmfcf_address_option))?'checked':''?>  />Locality<br/>
			            	 <input  type="checkbox"  name="gmfcf_address_option[]" value="administrative_area_level_1" <?php echo (in_array("administrative_area_level_1", $gmfcf_address_option))?'checked':''?> />State<br/>
			            	 <input  type="checkbox"  name="gmfcf_address_option[]" value="country" <?php echo (in_array("country", $gmfcf_address_option))?'checked':''?> />Country<br/>
			            </td>
			         </tr>
			          
				   </tbody>
				</table>
				<?php
					     
					submit_button(); 
				?>
			
			</form>
		</div>
<?php
	}
	
	public function GMFCF_cf7_address_autocomplete_display_gpa_fields()
	{
		
			
			register_setting('gmfcf_section', 'gmfcf_cf7_geo_api_key');
			register_setting('gmfcf_section', 'gmfcf_country_code');
			register_setting('gmfcf_section', 'gmfcf_address_option');
	  
	}

	public function GMFCF_cf7_gpa_load_user_api()
	{
		$api_script ='';
	  $gpa_page = get_option( 'gmfcf_cf7_geo_gpa_page' );
	  $api_key = get_option( 'gmfcf_cf7_geo_api_key' );
	  if(is_ssl())
	  {
			$securee = 'https';
	  }
	  else
	  {
			$securee = 'http';
	  }
	  $api_script .= $securee.'://maps.googleapis.com/maps/api/js?key=' . $api_key . '&libraries=places';
		wp_enqueue_script( 'gpa-google-places-api', $api_script, array(), 'null', true );
		
	}
	
	
}
?>