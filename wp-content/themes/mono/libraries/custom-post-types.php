<?php
	
	
	
	
	// Portfolio Layouts
	$portfolio_layout_array = array(		 
		array('Attachment List','attachment_list'),		
		array('Attachment Slider','attachment_slider'),
		array('Standard', 'standard')
	);
	
	
	
	
	// Register Portfolio post type
	add_action('init', 'portfolio_register');
	function portfolio_register() {
		$labels = array(
			'name' => __('Portfolio', 'cudazi'),
			'singular_name' => __('Portfolio Item', 'cudazi'),
			'add_new' => __('Add New', 'cudazi'),
			'add_new_item' => __('Add New Portfolio Item','cudazi'),
			'edit_item' => __('Edit Portfolio Item','cudazi'),
			'new_item' => __('New Portfolio Item','cudazi'),
			'view_item' => __('View Portfolio Item','cudazi'),
			'search_items' => __('Search Portfolio','cudazi'),
			'not_found' =>  __('Nothing found','cudazi'),
			'not_found_in_trash' => __('Nothing found in Trash','cudazi'),
			'parent_item_colon' => ''
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'menu_icon' => null,
			'rewrite' => true,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array('title','editor','thumbnail','excerpt','comments')
		  ); 		
		register_post_type( 'portfolio' , $args );		
	}
	
	
	
	
	// Register Slider post type
	add_action('init', 'slider_register');
	function slider_register() {
		$labels = array(
			'name' => __('Slides', 'cudazi'),
			'singular_name' => __('Slide', 'cudazi'),
			'add_new' => __('Add New', 'cudazi'),
			'add_new_item' => __('Add New Slide','cudazi'),
			'edit_item' => __('Edit Slide','cudazi'),
			'new_item' => __('New Slide','cudazi'),
			'view_item' => __('View Slide','cudazi'),
			'search_items' => __('Search Slides','cudazi'),
			'not_found' =>  __('Nothing found','cudazi'),
			'not_found_in_trash' => __('Nothing found in Trash','cudazi'),
			'parent_item_colon' => ''
		);
		$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'menu_icon' => null,
			'rewrite' => false,
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => array('title','editor', 'page-attributes' )
		  ); 		
		register_post_type( 'slider' , $args );		
	}
	
	
	
	
	// Register "Skills" taxonomy
	register_taxonomy(
		"skills", 
		array("portfolio"), 
		array(
			"hierarchical" => true, 
			"label" => __('Portfolio Tags','cudazi'), 
			"singular_label" => __('Portfolio Tag','cudazi'), 
			"rewrite" => true
			)
	);




	// Register Slider Categories taxonomy
	register_taxonomy(
		"slide-categories", 
		array("slider"), 
		array(
			"hierarchical" => true, 
			"label" => __('Slider Categories','cudazi'), 
			"singular_label" => __('Slider Category','cudazi'), 
			"rewrite" => false
			)
	);


