<?php


	// Allow shortcodes in text widgets
	if ( ! is_admin() ) {
		add_filter('widget_text', 'do_shortcode');
	}
	
	
	// Nested Shortcodes
	add_filter('the_content', 'do_shortcode');
	
	
	// Filter the page title wp_title() in header.php
	function cudazi_page_title( $title ) { 
		$the_page_title = $title;
		if( ! $the_page_title ){
			$the_page_title = get_bloginfo("name");
		}else{
			$the_page_title = $the_page_title . " - " . get_bloginfo("name");
		}
		return $the_page_title .= " - ". get_bloginfo("description");
	} 
	add_filter('wp_title', 'cudazi_page_title');


	// Remove inline styles printed when the gallery shortcode is used.
	function cudazi_remove_gallery_css( $css ) {
		return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
	}
	add_filter( 'gallery_style', 'cudazi_remove_gallery_css' );
	
	
	// Remove the jump that happens when a read more link is clicked
	function cudazi_remove_readmore_jump($link) {
		$offset = strpos($link, '#more-');
		if ($offset) {
			$end = strpos($link, '"',$offset);
		}
		if ($end) {
			$link = substr_replace($link, '', $offset, $end-$offset);
		}
		return $link;
	}
	add_filter('the_content_more_link', 'cudazi_remove_readmore_jump');
	
?>