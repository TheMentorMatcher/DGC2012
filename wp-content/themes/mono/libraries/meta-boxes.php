<?php




	//
	// Add Meta Boxes on Admin Init
	//	
	add_action("admin_init", "cudazi_admin_init");
	function cudazi_admin_init(){
		add_meta_box("cudazi_post_settings-meta", __("Custom Post Settings","cudazi"), "cudazi_post_settings_box", "post", "side", "core");		
		
		add_meta_box("cudazi_thumb_linksto_meta", __("Featured Image Thumbnail Link","cudazi"), "cudazi_thumb_linksto", "post", "normal", "default");
		add_meta_box("cudazi_thumb_linksto_meta", __("Featured Image Thumbnail Link","cudazi"), "cudazi_thumb_linksto", "portfolio", "normal", "default");
		add_meta_box("cudazi_slider_settings_box_meta", __("Slide Settings","cudazi"), "cudazi_slider_settings_box", "slider", "normal", "default");
		
		add_meta_box("cudazi_portfolio_settings_box_meta", __("Custom Portfolio Settings","cudazi"), "cudazi_portfolio_settings_box", "portfolio", "normal", "core");
		
		add_meta_box("cudazi_portfolio_template_box_meta", __("Custom Portfolio Page Template Settings","cudazi"), "cudazi_portfolio_template_box", "page", "normal", "core");
	}
	
	
	
	
	
	
	
	
	//
	//	Save
	//
	add_action('save_post', 'cudazi_save_meta_box_content');
	function cudazi_save_meta_box_content(){		
		global $post;
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post->ID;
		} else {
			if ( $post->post_type == 'post' ) {
				update_post_meta($post->ID, "cudazi_post_settings", $_POST["cudazi_post_settings"]);
				update_post_meta($post->ID, "cudazi_post_hide_featured_single", $_POST["cudazi_post_hide_featured_single"]);
			}
			if ( $post->post_type == 'page' ) {
				update_post_meta($post->ID, "portfolio_layout", $_POST["portfolio_layout"]);
				update_post_meta($post->ID, "portfolio_posts_per_page", $_POST["portfolio_posts_per_page"]);
				update_post_meta($post->ID, "portfolio_skills", $_POST["portfolio_skills"]);
			}  
			if ( $post->post_type == 'portfolio' ) {			
				update_post_meta($post->ID, "portfolio_layout", $_POST["portfolio_layout"]);
				update_post_meta($post->ID, "portfolio_video_src_embed", $_POST["portfolio_video_src_embed"]);				
			}
			if ( $post->post_type == 'post' || $post->post_type == 'portfolio') {
				update_post_meta($post->ID, "featured_image_link_to", $_POST["featured_image_link_to"]);
				update_post_meta($post->ID, "featured_image_link_to_url", $_POST["featured_image_link_to_url"]);
			}
			if ( $post->post_type == 'slider' ) {
				update_post_meta($post->ID, "slide_width", $_POST["slide_width"]);
				update_post_meta($post->ID, "slide_height", $_POST["slide_height"]);
			}
		}	
	}
	
	
	
	
	
	
	
	
	
	//
	//	Meta Boxes
	//
	
	
	// Meta Box
	function cudazi_thumb_linksto(){
	  global $post;
	  $custom = get_post_custom($post->ID);
	  $featured_image_link_to = $custom["featured_image_link_to"][0];
	  $featured_image_link_to_url = $custom["featured_image_link_to_url"][0];
	  ?>
	  <p><?php _e('Choose on a post by post basis where the featured image thumbnails to link to.','cudazi'); ?></p>
	  <p>
	  <select name="featured_image_link_to">
	  	<option value=''><?php _e('Default','cudazi'); ?></option>
	  	<?php
	  		$featured_image_link_to_options = array(
	  			'post' => __('Full Post','cudazi'),
	  			'image' => __('Image','cudazi')	  			
	  		);	
			foreach ( $featured_image_link_to_options as $k => $v ) {
				if ( $k == $featured_image_link_to ) { $sel = " selected='selected'"; }else{ $sel = ""; }
			  	echo "<option " . $sel . " value='". $k ."'>" . $v . "</option>"; 
			}
	  	?>
		</select>
		<em><?php _e('or','cudazi'); ?></em> <?php _e('Custom URL:','cudazi'); ?> <input type="text" style='width:300px; border-style:solid; border-width:1px;' name="featured_image_link_to_url" value="<?php echo $featured_image_link_to_url; ?>" /> <?php _e('(Full URL - Video, External Page, etc...)','cudazi'); ?></p>		
	  <?php
	}
	
	
	// Meta Box
	function cudazi_post_settings_box(){
	  global $post;
	  $custom = get_post_custom($post->ID);
	  $cudazi_post_settings = $custom["cudazi_post_settings"][0];
	  $cudazi_post_hide_featured_single = $custom["cudazi_post_hide_featured_single"][0];
	  ?>
	  <p><?php _e('Single post page layout:', 'cudazi' ); ?> <select name="cudazi_post_settings">
	  	<?php
	  		$custom_post_settings = array(
	  			'default' => __('Default','cudazi'),
	  			'fullwidth' => __('Full Width','cudazi')
	  		);	
			foreach ( $custom_post_settings as $custom_settings_key => $custom_settings_value ) {
				if ( $custom_settings_key == $cudazi_post_settings ) { $sel = " selected='selected'"; }else{ $sel = ""; }
			  	echo "<option " . $sel . " value='". $custom_settings_key ."'>" . $custom_settings_value . "</option>"; 
			}
	  	?>
		</select></p>
		<p><label><input type="checkbox" name="cudazi_post_hide_featured_single" value="1" <?php if ( $cudazi_post_hide_featured_single ) { echo " checked='yes' "; } ?> /> <?php _e('Disable featured image on single post page', 'cudazi' ); ?></label></p>
	  <?php
	}
	
	
	// Meta Box
	function cudazi_portfolio_settings_box(){
	  global $post;
	  $custom = get_post_custom($post->ID);
	  $portfolio_layout = $custom["portfolio_layout"][0];
	  $portfolio_video_src_embed = $custom["portfolio_video_src_embed"][0];
	  ?>
	  <p><?php _e('Portfolio single post display style:','cudazi'); ?> <select name="portfolio_layout">
	  	<?php
	  		$portfolio_layouts = array(
	  			'attachment_list' => __('Attachment List','cudazi'),
	  			'attachment_slider' => __('Attachment Slider','cudazi'),
	  			'video' => __('Video / HTML Embed','cudazi'),
	  			'standard-fullwidth' => __('Standard - Full Width','cudazi'),
	  			'standard' => __('Standard','cudazi')
	  		);	
			foreach ( $portfolio_layouts as $k => $v ) {
				if ( $k == $portfolio_layout ) { $sel = " selected='selected'"; }else{ $sel = ""; }
			  	echo "<option " . $sel . " value='". $k ."'>" . $v . "</option>"; 
			}
	  	?>
		</select></p>
		<p><?php _e('Attachment list displays all images in the media section. Attachment slider displays in a slider. Standard displays like a standard post.','cudazi'); ?><br /><br /></p>	
		<p><strong><?php _e('Video / HTML Embed', 'cudazi'); ?></strong><br /><?php _e('Directly embed basic HTML, shortcodes or video embed code. Will replace the attached images on single portfolio pages.','cudazi'); ?></p>
		<p><textarea style='font-family:monospace;width:75%;height:120px;border-style:solid; border-width:1px;' cols="50" rows="5" name="portfolio_video_src_embed"><?php echo $portfolio_video_src_embed; ?></textarea></p>		
		<?php
	}
	
	
	// Meta Box
	function cudazi_portfolio_template_box(){
	  global $post;
	  $custom = get_post_custom($post->ID);
	  $portfolio_layout = $custom["portfolio_layout"][0];
	  $portfolio_posts_per_page = $custom["portfolio_posts_per_page"][0];
	  $portfolio_skills = $custom["portfolio_skills"][0];
	  ?>
	  <p><?php _e('If you chose the portfolio grid page template, use the settings below to control it further:','cudazi'); ?></p>
	  <p><?php _e('Portfolio Layout:','cudazi'); ?> <select name="portfolio_layout">
	  	<?php
	  		$portfolio_layouts = array(
	  			'4-columns' => __('4 Columns','cudazi'),
	  			'3-columns' => __('3 Columns','cudazi'),
	  			'2-columns' => __('2 Columns','cudazi')
	  		);	
			foreach ( $portfolio_layouts as $k => $v ) {
				if ( $k == $portfolio_layout ) { $sel = " selected='selected'"; }else{ $sel = ""; }
			  	echo "<option " . $sel . " value='". $k ."'>" . $v . "</option>"; 
			}
	  	?>
		</select></p>

		<p><label><?php _e('Posts Per Page','cudazi'); ?> <input type="text" style='width:40px; border-style:solid; border-width:1px;' name="portfolio_posts_per_page" value="<?php echo $portfolio_posts_per_page; ?>" /></label></p>
		<p><label><?php _e('Skills','cudazi'); ?> <input type="text" style='width:300px; border-style:solid; border-width:1px;' name="portfolio_skills" value="<?php echo $portfolio_skills; ?>" /></label>
		<?php 
			$skills_array = get_terms('skills','array');
			if ( count($skills_array) > 0 ) {
				echo '<br />';
				_e('Enter comma separated list of skill slugs or leave blank to show all.','cudazi');
				$skill_sample_list = '&nbsp&nbsp;';
				foreach ( $skills_array as $skill ) {									
					$skill_sample_list .= $skill->slug . ','; 
				}
				echo substr( $skill_sample_list,0,-1 );
			}			
		?>
		</p>		
		<?php
	}
	
	// Meta Box
	function cudazi_slider_settings_box(){
	  global $post;
	  $custom = get_post_custom($post->ID);
	  $slide_width = $custom["slide_width"][0];
	  $slide_height = $custom["slide_height"][0];
	  
	  //$slide_width = $custom["slide_"][0];
	  //$slide_width = $custom["slide_width"][0];
	  
	  ?>
		<p><?php _e('Enter a width and height on a per-slide basis. Insert content in the main editor above.','cudazi'); ?></p>
		<p><label><?php _e('Slide Width','cudazi'); ?></label> <input type="text" style='width:70px; border-style:solid; border-width:1px;' name="slide_width" value="<?php echo $slide_width; ?>" /> <?php _e('(e.g. 400px)','cudazi'); ?></p>
		<p><label><?php _e('Slide Height','cudazi'); ?></label> <input type="text" style='width:70px; border-style:solid; border-width:1px;' name="slide_height" value="<?php echo $slide_height; ?>" /> <?php _e('(e.g. 300px)','cudazi'); ?></p>
				
	  <?php
	}
	
	
	
	
?>