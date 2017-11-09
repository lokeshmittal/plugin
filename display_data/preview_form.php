<div class="section_right">
  <h3>FILTER</h3>
  <div class="preview_cat">
  <link rel="stylesheet" href="./range_slider/jquery-ui.css">
  <link rel="stylesheet" href="./range_slider/style.css">
  <script src="./range_slider/jquery-1.12.4.js.download"></script>
  <script src="./range_slider/jquery-ui.js.download"></script>
    
<div class="filter_saprate_block price_slider_one" style="display:none;"><h4 class="filter_back_end_h4">Price 
	 <span class="filter_backend_toggle"></span>
	 </h4><div class="open_close_filter" style="display: none;">
	 <div id="slider-range" class="ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"><div class="ui-slider-range ui-corner-all ui-widget-header" style="left: 0%; width: 97.8%;"></div><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default ui-state-focus" style="left: 0%;"></span><span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default" style="left: 97.8%;"></span></div>
	 <p>
	 <label for="amount">Price:</label>
	 <input type="text" id="amount" readonly="" style="border:0; color:#f6931f; font-weight:bold;">
	 </p>
	 </div>
</div>

<?php 
$args = array(
  'public'   => true,
  '_builtin' => false
  
); 
$output = 'names'; // or objects
$operator = 'and'; // 'and' or 'or'
$taxonomies = get_taxonomies( $args, $output, $operator ); 
if ( $taxonomies ) {
  foreach ( $taxonomies  as $taxonomy ) {
	 echo '<div class="filter_saprate_block hide_show_'.$taxonomy.'">';
     echo '<h4 class="filter_back_end_h4">' . $taxonomy . ' 
	 <span class="filter_backend_toggle"></span>
	 </h4>';
	$terms = get_terms( array(
    'taxonomy' => $taxonomy,
    'hide_empty' => false,
	) );


	  //echo '<pre>'; 
	 //print_r($terms);
     echo '<div class="open_close_filter" style="display:none;">';	 
	 if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
     echo '<ul class="check_box_div type_radio_'.$taxonomy.'" style="display:none;">';
     foreach ( $terms as $term ) {
		echo '<li>'; 
        echo '<span class="label_type"><input type="radio" class="divi_checkbox_term divi_checkbox_term_17" value=""></span>';
		echo '<span class="label_mname">'.$term->name.'</span>';
		echo '</li>';
     }
     echo '</ul>';
    }
	
	
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
     echo '<ul class="check_box_div type_checkbox_'.$taxonomy.'" style="display:none;">';
     foreach ( $terms as $term ) {
		echo '<li>'; 
        echo '<span class="label_type"><input type="checkbox" class="divi_checkbox_term divi_checkbox_term_17" value=""></span>';
		echo '<span class="label_mname">'. $term->name.'</span>';
		echo '</li>';
     }
     echo '</ul>';
    }
    echo '</div>';

	
	echo '</div>'; 
	
  }
}

	?>
  
  
  </div>
  <div class="preview_apply_reset">
	 <div class="preview_apply_reset_left"><button class="apply_text">Apply</button><button class="reset_text">Reset</button></div>
	<?php 
	$divi_mainbutton_font_icon = $divi_settings['divi_mainbutton_font_icon'];
	if($divi_mainbutton_font_icon == ''){
	$divi_mainbutton_font_icon1 = '<i class="fa fa-sliders" aria-hidden="true"></i>';
	}
	else{
	$divi_mainbutton_font_icon1 = $divi_mainbutton_font_icon;
	}
	?>
	 <button class="preview_apply_reset_right"><?php echo $divi_mainbutton_font_icon1; ?></button>
  </div>
</div>


<script>
	 jQuery(document).ready(function(){
		jQuery(".filter_backend_toggle").click(function(){
			 jQuery(this).toggleClass('active');
			 jQuery(this).parent().next('.open_close_filter').toggle();
		});
	 });
</script>
	<script>
	jQuery( function() {
		jQuery( "#slider-range" ).slider({
			range: true,
			min: 0,
			max: 500,
			values: [ 75, 300 ],
			slide: function( event, ui ) {
				jQuery( "#amount" ).val( "€" + ui.values[ 0 ] + " - €" + ui.values[ 1 ] );
			}
		});
		jQuery( "#amount" ).val( "€" + jQuery( "#slider-range" ).slider( "values", 0 ) +
			" - €" + jQuery( "#slider-range" ).slider( "values", 1 ) );
	} );
	</script>