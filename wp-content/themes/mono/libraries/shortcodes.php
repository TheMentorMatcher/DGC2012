<?php
/**************************************************************************************************************************************************/	

// Contact Form Shortcode
	// [cudazi_contact to='you@email.com' subject='' button='' sent='' error='']	
	function cudazi_contact_sc($atts) {	
		global $custom_settings;
		extract(shortcode_atts(array(
			"to" => '',
			"subject" => __('Contact Form Message','cudazi'),
			"button" => __('Send Message','cudazi'),
			"sent" => __('Message Sent!','cudazi'),
			"error" => __('Error, please be sure you filled out all fields properly.','cudazi')
		), $atts));

			ob_start();
			if($to != "")
			{
				if(isset($_POST["cf_submit"]))
				{	
					$from = $_POST["cf_email"];
					$hasErrors = false;
					if(empty($_POST["cf_name"]) || empty($_POST["cf_email"]) || empty($_POST["cf_message"]))
					{
						$hasErrors = true;
					}else{
						if(preg_match("/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/", $_POST["cf_email"]) == 1) {
							$hasErrors = false;
						}else{
							$hasErrors = true;
						}
					}
					if($hasErrors == false)
					{
						$headers = "From: <".$from . ">";
						$body = "\nContact Form Message:\n\n";
						$body .= "From: " . $_POST["cf_name"] . " (".$_POST['cf_email'].")\n";						
						$body .= "\nMessage:\n" . $_POST["cf_message"] . "\n\n";
						mail($to,$subject,$body,$headers);
					}
					if($hasErrors == false)
					{
						echo "<p class='message success'>".$sent."</p>";
					}else{
						echo "<p class='message error'>".$error."</p>";
					}
				
				}
				
				?>
                <form class="standard" method="post" action="">
                    <div class="field clearfix">
                        <label><strong><?php _e( 'Name*', 'cudazi'); ?></strong></label>                        
                        <input type="text" size="45" name="cf_name" id="cf_name" class="textbox" />
                    </div>
                    <div class="field clearfix">
                        <label><strong><?php _e( 'Email*', 'cudazi'); ?></strong></label>                        
                        <input type="text" size="45" name="cf_email" id="cf_email" class="textbox" />
                    </div>
                    <div class="field clearfix">
                        <label><strong><?php _e( 'Message*', 'cudazi'); ?></strong></label>
                        <textarea name="cf_message" id="cf_message" cols="50" rows="6" class="textarea"></textarea>
                    </div>
                    <div class="field clearfix">
                        <input type="submit" style="font-weight: bold;" class="button" value="<?php echo $button; ?>" name="cf_submit" id="cf_submit" />
                    </div>                    
                </form>
				<?php
			}else{
				echo __( "You need to specify a to email address, see the help files.", 'cudazi' );
			}
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
	add_shortcode('cudazi_contact', 'cudazi_contact_sc');
	
/**************************************************************************************************************************************************/		











/**************************************************************************************************************************************************/	

	// [cudazi_scroll]Scroll to the Top![/cudazi_scroll]
	function cudazi_scroll_sc($atts, $content = null) {
		extract(shortcode_atts(array(
			//no params at this time
		), $atts));
		return "<a href='#' class='scroll-top'>" . $content . "</a>";
	}
	add_shortcode('cudazi_scroll', 'cudazi_scroll_sc');
	
/**************************************************************************************************************************************************/		










/**************************************************************************************************************************************************/	

	// [cudazi_message type='warning' close='Hide This']Warning, you are about to catch on fire.[/cudazi_message]
	function cudazi_message_sc($atts, $content = null) {
		extract(shortcode_atts(array(
			'type' => 'success',
			'close' => __( 'Hide', 'cudazi' )
		), $atts));
		
		$output = "";
		
		$output .= "<p class='". $type . " message rounded clearfix'>";
		
		if( $close) { 
			$output .= "<a class='hideparent right' href='#'>" . $close . "</a>";
		}
		$output .= $content . "</p>";
		
		return $output;
	}
	add_shortcode('cudazi_message', 'cudazi_message_sc');
	
/**************************************************************************************************************************************************/		








/**************************************************************************************************************************************************/	

	// [social_icon src="" title="Add a hover title!" link="#custom-link"]
	function social_icon_sc($atts, $content = null) {
		extract(shortcode_atts(array(
			'src' => 'picons33.png',
			'title' => __('Get in touch','cudazi'),
			'link' => '#'
		), $atts));
		
		$output = "";
		
		$output .= "<a href='".$link."' class='social-icon' title='".$title."'>";
		$output .= "<img src='" . get_template_directory_uri() . "/images/social_icons/".$src."' alt='".$title."' />";
		$output .= "</a>";
		
		return $output;
	}
	add_shortcode('social_icon', 'social_icon_sc');
	
/**************************************************************************************************************************************************/		









/**************************************************************************************************************************************************/	

	// [cudazi_promotext]Large Promo Text![/cudazi_promotext]
	function cudazi_promotext_sc($atts, $content = null) {
		extract(shortcode_atts(array(
			//no params at this time
		), $atts));
		return "<p class='promo_text'>" . $content . "</p>";
	}
	add_shortcode('cudazi_promotext', 'cudazi_promotext_sc');
	
/**************************************************************************************************************************************************/		









/**************************************************************************************************************************************************/	

	/*
	[cudazi_expander position='beginning']
		Summary content here...
	[cudazi_expander position='middle']
		Full content here, initally hidden...
	[cudazi_expander position='end' more='Show More...' less='Show Less...']
	*/
	function cudazi_expander_sc($atts) {
		
		extract(shortcode_atts(array(
			'position' => '',
			'more' => __( 'Show More...', 'cudazi' ),
			'less' => __( 'Show Less...', 'cudazi' )
		), $atts));
		
		if ( $position == 'beginning' ) {
			$output = "<div class='toggle-container'>";
		} else if ( $position == 'middle' ) {
			$output = "<div class='hidden toggle-fulltext'>";
		} else if ( $position == 'end' ) {
		
			$output = "</div>";	// end hidden
			$output .= "<p><a class='toggle' href='#'>" . $more . "</a></p>";
			$output .= "</div>"; // end toggle container
			$output .= "<script type='text/javascript'> jQuery(function($){ $('a.toggle').toggle( function() { $(this).parents('.toggle-container').find('.toggle-fulltext').slideDown(); $(this).html('" . $less . "'); return false; }, function() { $(this).parents('.toggle-container').find('.toggle-fulltext').slideUp(); $(this).html('" . $more . "'); return false; }); }); </script>";
		}
		return $output;
	}
	add_shortcode('cudazi_expander', 'cudazi_expander_sc');
	
/**************************************************************************************************************************************************/		


	
	
	
	
	
	
	
	
/**************************************************************************************************************************************************/		

	/*
	[cudazi_column width='4' class='alpha' ] <div class='grid_4 alpha'>
		Column One
	[cudazi_column_end] </div>
	
	[cudazi_column width='4' ] <div class='grid_4'>
		Column Two
	[cudazi_column_end] </div>
	
	[cudazi_column width='4' class='omega' ] <div class='grid_4 omega'>
		Column Three
	[cudazi_column_end] </div>
	
	[cudazi_clear] <div class='clear'></div>
	*/
	function cudazi_column_sc( $atts ) {
		$output = $addclass = "";
		extract(shortcode_atts(array(
			'width' => 4,
			'class' => ''
		), $atts));
		
		if( ! is_numeric( $width ) )
			$width = 3;			

		if ( $class == 'alpha' ) {
			$output = "<div class='grid_" . $width . " " . $class . "'>";
		} else if ( $class == 'omega' ) {
			$output = "<div class='grid_" . $width ." " . $class . "'>";
		} else {
			$output = "<div class='grid_" . $width ."'>";
		}
		
		return $output;
	}
	add_shortcode('cudazi_column', 'cudazi_column_sc');
	
/**************************************************************************************************************************************************/		









	
/**************************************************************************************************************************************************/		

	// [cudazi_column_end]
	// Simply adds the closing div tag on multi column layouts
	function cudazi_column_end_sc( $atts ) {
		extract(shortcode_atts(array(
			// none
		), $atts));
		return '</div>';
	}
	add_shortcode('cudazi_column_end', 'cudazi_column_end_sc');
	
/**************************************************************************************************************************************************/		





	
	
	
	
	
/**************************************************************************************************************************************************/		

	// [cudazi_clear]
	// Used to clear the 960 grid after unique layouts
	function cudazi_clear_sc( $atts ) {
		extract(shortcode_atts(array(
			// none
		), $atts));
		return "<div class='clear'></div>";
	}
	add_shortcode('cudazi_clear', 'cudazi_clear_sc');
	
/**************************************************************************************************************************************************/		










/**************************************************************************************************************************************************/	

	// Remove the autop and wptexturize (in filters library) on all content
	// THEN add it all back in, except when using [raw] yourcontent [/raw]
	if ( ! function_exists( 'cudazi_strip_formatting' ) ) {
		function cudazi_strip_formatting($content) {
			$formatted_content = '';
			$pattern = "{(\[raw\].*?\[/raw\])}is";
			$contents = "{\[raw\](.*?)\[/raw\]}is";
			$arr_pieces = preg_split($pattern, $content, -1, PREG_SPLIT_DELIM_CAPTURE);
			
			foreach ($arr_pieces as $piece) 
			{
				if (preg_match($contents, $piece, $matches)) 
				{
					$formatted_content .= $matches[1];
				} else {
					$formatted_content .= wptexturize(wpautop($piece));
				}
			}
			return $formatted_content;
		}
		// First, remove wpautop and wptexturize from the content
	// Then, add it in using cudazi_strip_formatting except on specific shortcodes	
	remove_filter('the_content', 'wpautop');
	remove_filter('the_content', 'wptexturize');
	add_filter('the_content', 'cudazi_strip_formatting', 1);
	add_filter('widget_text', 'cudazi_strip_formatting', 2);
	}
	
		
/**************************************************************************************************************************************************/		








/**************************************************************************************************************************************************/	

	// Simple function to display an icon list
	// [cudazi_iconlist] add icon schortcodes [/cudazi_iconlist]
	function cudazi_iconlist_sc( $atts, $content = null ) {
		extract(shortcode_atts(array(
			// no atts
		), $atts));
		return "<ul class='iconlist'>" . do_shortcode( $content ) . "</ul>";
	}
	add_shortcode('cudazi_iconlist', 'cudazi_iconlist_sc');
	
	//
	
	// Simple function to display an icon list
	// [cudazi_iconlist icon='check32.png' icon_full='' heading='' text='' link='']
	function cudazi_iconlist_icon_sc( $atts ) {
		extract(shortcode_atts(array(
			'icon' => 'check32.png',
			'icon_full' => '',
			'heading' => 'Icon Heading',
			'text' => '',
			'link' => ''
		), $atts));
		
		if ( $icon_full ) {
			$icon = $icon_full;
		} else {
			$icon = get_template_directory_uri() . '/images/icons/' . $icon;
		}
		
		$output = "<li class='clearfix'>";
		$output .= "<img class='left' alt='" . $heading . "' src='" . $icon . "' />";
		$output .= "<h5>";
		if ( $link ) { $output .= "<a href='" . $link . "'>"; }
		$output .=  $heading;
		if ( $link ) { $output .= "</a>"; }
		$output .= "</h5>";
		if ( $text ) { $output .= "<p>" . $text . "</p>"; }
		$output .= "</li>";
		
		return $output;
	}
	add_shortcode('cudazi_iconlist_icon', 'cudazi_iconlist_icon_sc');
	
/**************************************************************************************************************************************************/		








/**************************************************************************************************************************************************/	

	// Simple function to display an icon list
	// [cudazi_slider]
	function cudazi_slider_sc( $atts, $content = null ) {
		
		extract(shortcode_atts(array(
			'max' => '-1',			
			'categories' => '',
			'slide_effect' => 'fade',
			'slide_delay' => '0',
			'slide_disable_nav' => '',
			'slide_disable_nextprev' => '',
			'slide_crossfade' => '',
			'slide_hoverpause' => ''
		), $atts));
		
		global $post, $wp_query;
		$output = "";
		$slide_counter = 0;
		$original_wp_query = $wp_query;
		$wp_query = null;
		
		$args = array(
			'post_type' => 'slider',			
			'posts_per_page' => $max,
			'slide-categories' => $categories
		);

		// Run the new query
		$wp_query = new WP_Query( $args );
		
		$slider_id = "slider_" . rand(100,10000); // need to generate ID for multiple slider usage
		
		// Slider wrap
		$output .= "<div class='slides_container' id='".$slider_id."'>";		
			$output .= "<ul class='stripped thumbnail-list-slider clearfix'>";	
			//print_r_pre($wp_query);
			if ( have_posts() ) {				
				while ( have_posts() ) {			
					the_post();				
					
					$add_style = $width = $height = "";
					
					$width = get_post_meta( $post->ID, 'slide_width', true);
					$height = get_post_meta( $post->ID, 'slide_height', true);
					
					if ( $width ) { $add_style .= ' width:' . $width . ';'; }else{ $width = ''; }
					if ( $height ) { $add_style .= ' height:' . $height . ';'; }else{ $height = ''; }
					if ( $width || $height ) { $add_style = " style='" . $add_style . "'"; }else{ $add_style = ''; }
					
					if ( $slide_counter > 0 ) { $output .= "<li class='hidden'>"; }else{ $output .= "<li>"; }							
						$output .= "<div class='clearfix content' " . $add_style . ">";
						$output .= apply_filters('the_content', $post->post_content);
						$output .= "</div>";					
					$output .= "</li>";	
					
					$slide_counter++;
					
				} // end while				
				$output .= "</ul>";				
		// Slider wrap end						
		$output .= "</div><!--//slides_container-->"; 				

		$output .= "<script type='text/javascript'> /* <![CDATA[ */ jQuery(function($){ ";
			$output .= "$('#".$slider_id."').slides({";		
				$output .= "container: 'thumbnail-list-slider', ";
				$output .= "preload: true, ";
				$output .= "paginationClass: 'slides_pagination', ";				
				$output .= "effect: '".$slide_effect."', ";				
				if ( ! $slide_disable_nav ) { $output .= "generatePagination: true, "; }else{ $output .= "generatePagination: false, "; } 
				if ( ! $slide_disable_nextprev ) { $output .= "generateNextPrev: true, "; }	
				if ( $slide_delay ) { $output .= "play: ". $slide_delay .", "; }
				if ( $slide_crossfade ) { $output .= "crossfade: true, "; }
				if ( $slide_hoverpause ) { $output .= "hoverPause: true,"; }
				$output .= "autoHeight: true";						
			$output .= "});";
		$output .= "}); </script><!--//end slides js-->";				
	
		}else{		
			$output = "<!-- no posts/attachments found -->";		
		} // end post if
		
		// restore query
		
		$wp_query = $original_wp_query;
		wp_reset_query();
		return $output;		
	}
	add_shortcode('cudazi_slider', 'cudazi_slider_sc');
	
/**************************************************************************************************************************************************/		



	

?>