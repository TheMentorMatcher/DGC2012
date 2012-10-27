<?php
	
	
	// Load custom libraries used in theme
	$cudazi_libraries = 
		array( 
			'themesetup',
			'theme-options',			
			'debug',
			'filters',
			'shortcodes',
			'widgets',
			'featuredimages',
			'meta-boxes',
			'custom-post-types',
			'comments',
			'attachment-gallery',			
			'plugins/cudazi-latest-posts',
			'plugins/cudazi-twitter-widget'
		);
	foreach( $cudazi_libraries as $library ) {
		include_once( 'libraries/' . $library . '.php' );
	}
	

	
	// Theme and Version Information
	$cudazi_theme_data = get_theme_data(TEMPLATEPATH . '/style.css');
	define('CUDAZI_THEME_NAME', $cudazi_theme_data['Title']);
	define('CUDAZI_THEME_AUTHOR', $cudazi_theme_data['Author']);
	define('CUDAZI_THEME_URI', $cudazi_theme_data['URI']);
	define('CUDAZI_THEME_VERSION', $cudazi_theme_data['Version']);
	define('CUDAZI_THEME_INFOLINE', CUDAZI_THEME_NAME . ' by ' . CUDAZI_THEME_AUTHOR . ' (' . CUDAZI_THEME_URI . ') v' . CUDAZI_THEME_VERSION);
	
	add_action('wp_footer','cudazi_display_themeinfo');
	function cudazi_display_themeinfo() {
		echo '<!-- ' . CUDAZI_THEME_INFOLINE . ' -->'; // Display for easier debugging remotely
	}


	// Fallback (Pre 3.0) menu system
	function cudazi_menu_fallback()
	{
		$menu = "<ul class='sf-menu'>";
		//$menu .= wp_list_pages('echo=0&title_li=');
		$menu .= "<li><a href='#'>" . __( 'Add a menu in Apperance, Menus', 'cudazi' ) . "</a></li>";
		$menu .= "</ul>";
		echo $menu;
	}
	
	
	// post->ID returns first post ID, need to correct on the blog page only
	function cudazi_corrected_post_id() {
		global $post;
		$post_id = null;
		if ( get_option('show_on_front') == 'page' && get_option('page_for_posts') && is_home() ) {
			$post_id = get_option('page_for_posts');
		} else {
			if ($post != null) {
				$post_id = $post->ID;
			}
		}
		return $post_id;
	}


	// return page number
	function cudazi_get_page_number() {
		global $post;
		if(get_query_var('paged')) {
			 $paged = get_query_var('paged');
		} elseif(get_query_var('page')) {
			 $paged = get_query_var('page');
		} else {
			 $paged = 1;
		}
		return $paged;
	}
	
	
	// Get Featured Image + Link
	function cudazi_get_featured_image( $img_size, $fallback ) {
		global $post;		
		
		$cudazi_post_hide_featured_single = get_post_meta($post->ID, 'cudazi_post_hide_featured_single', true);
		if ( is_single() && $cudazi_post_hide_featured_single ) {
			return false;
		}
		
		if ( has_post_thumbnail() ) {								
			$featured_image_link_to = get_post_meta($post->ID, 'featured_image_link_to', true);
			$featured_image_link_to_url = get_post_meta($post->ID, 'featured_image_link_to_url', true);			
			if ( $featured_image_link_to_url ) {
				$featured_image_link = $featured_image_link_to_url;
			}else{
				if ( $featured_image_link_to == 'post' ) {
					$featured_image_link = get_permalink();
				}else if ( $featured_image_link_to == 'image' || !$featured_image_link_to ) {
					$featured_image_link = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
					$featured_image_link = $featured_image_link[0];					
				}
			} // end if url set			
			$featured_image = get_the_post_thumbnail($post->ID, $img_size, array( 'class' => '' ));
		}else{ // no thumbnail set
			$featured_image_link = get_permalink();
			$featured_image = $fallback;
		} // end if has featured image				
		
		if ( has_post_thumbnail() ) {		
			return "<div class='post-thumbnail'><a href='" . $featured_image_link . "'>" . $featured_image . "</a></div>";		
		}
	}

?>