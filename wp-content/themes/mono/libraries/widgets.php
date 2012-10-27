<?php
	/*
		Setup and init widget areas.
	*/
	
	
	function cudazi_widgets_init() {
		
		$default_before_widget = '<div id="%1$s" class="widget %2$s">';
		$default_after_widget = '</div>';
		$default_before_title = '<h3 class="widgettitle">';
		$default_after_title = '</h3>';
	
	
		register_sidebar( array(
			'name' => __( 'Column Two: All', 'cudazi' ),
			'id' => 'column-two-all',
			'description' => __( 'Appears in any template with a sidebar column except the 404 and default blog-list front page.', 'cudazi' ),
			'before_widget' => $default_before_widget,
			'after_widget' => $default_after_widget,
			'before_title' => $default_before_title,
			'after_title' => $default_after_title
		) );
		register_sidebar( array(
			'name' => __( 'Column Two: Home', 'cudazi' ),
			'id' => 'column-two-home',
			'description' => __( 'Appears sidebar column of the page set in Settings > Reading > Static > Front Page. (or default posts page if nothing is set)', 'cudazi' ),
			'before_widget' => $default_before_widget,
			'after_widget' => $default_after_widget,
			'before_title' => $default_before_title,
			'after_title' => $default_after_title
		) );
		register_sidebar( array(
			'name' => __( 'Column Two: Blog', 'cudazi' ),
			'id' => 'column-two-blog',
			'description' => __( 'Appears sidebar column of the page set in Settings > Reading > Static > Posts Page.', 'cudazi' ),
			'before_widget' => $default_before_widget,
			'after_widget' => $default_after_widget,
			'before_title' => $default_before_title,
			'after_title' => $default_after_title
		) );
		register_sidebar( array(
			'name' => __( 'Column Two: Single Post', 'cudazi' ),
			'id' => 'column-two-single-post',
			'description' => __( 'Appears on a single post page', 'cudazi' ),
			'before_widget' => $default_before_widget,
			'after_widget' => $default_after_widget,
			'before_title' => $default_before_title,
			'after_title' => $default_after_title
		) );
		register_sidebar( array(
			'name' => __( 'Column Two: Single Page', 'cudazi' ),
			'id' => 'column-two-page',
			'description' => __( 'Appears on a single page template, except the home page.', 'cudazi' ),
			'before_widget' => $default_before_widget,
			'after_widget' => $default_after_widget,
			'before_title' => $default_before_title,
			'after_title' => $default_after_title
		) );
		
		
		register_sidebar( array(
			'name' => __( 'Column Two: Any Archive Page', 'cudazi' ),
			'id' => 'column-two-archive',
			'description' => __( 'When any type of Archive page is being displayed. Category, Tag, Author and Date based pages are all types of Archives.', 'cudazi' ),
			'before_widget' => $default_before_widget,
			'after_widget' => $default_after_widget,
			'before_title' => $default_before_title,
			'after_title' => $default_after_title
		) );
		register_sidebar( array(
			'name' => __( 'Column Two: 404 Page', 'cudazi' ),
			'id' => 'column-two-404',
			'description' => __( 'When a page displays after an "HTTP 404: Not Found" error occurs.', 'cudazi' ),
			'before_widget' => $default_before_widget,
			'after_widget' => $default_after_widget,
			'before_title' => $default_before_title,
			'after_title' => $default_after_title
		) );
		register_sidebar( array(
			'name' => __( 'Column Two: Logged in User', 'cudazi' ),
			'id' => 'column-two-logged-in',
			'description' => __( 'Appears in the sidebar when a user is logged in.', 'cudazi' ),
			'before_widget' => $default_before_widget,
			'after_widget' => $default_after_widget,
			'before_title' => $default_before_title,
			'after_title' => $default_after_title
		) );
		
		
		register_sidebar( array(
			'name' => __( 'Footer: Full-Width Above', 'cudazi' ),
			'id' => 'footer-fullwidth-above',
			'description' => __( 'Main footer, full width above Footer Columns', 'cudazi' ),
			'before_widget' => $default_before_widget,
			'after_widget' => $default_after_widget,
			'before_title' => $default_before_title,
			'after_title' => $default_after_title
		) );
		register_sidebar( array(
			'name' => __( 'Footer: Full-Width Below', 'cudazi' ),
			'id' => 'footer-fullwidth-below',
			'description' => __( 'Main footer, full width below Footer Columns', 'cudazi' ),
			'before_widget' => $default_before_widget,
			'after_widget' => $default_after_widget,
			'before_title' => $default_before_title,
			'after_title' => $default_after_title
		) );
		register_sidebar( array(
			'name' => __( 'Footer Column: A', 'cudazi' ),
			'id' => 'footer-a',
			'description' => __( 'Main footer, position in column A. Control more layout options in the Theme Settings page.', 'cudazi' ),
			'before_widget' => $default_before_widget,
			'after_widget' => $default_after_widget,
			'before_title' => $default_before_title,
			'after_title' => $default_after_title
		) );
		register_sidebar( array(
			'name' => __( 'Footer Column: B', 'cudazi' ),
			'id' => 'footer-b',
			'description' => __( 'Main footer, position in column B. Control more layout options in the Theme Settings page.', 'cudazi' ),
			'before_widget' => $default_before_widget,
			'after_widget' => $default_after_widget,
			'before_title' => $default_before_title,
			'after_title' => $default_after_title
		) );
		register_sidebar( array(
			'name' => __( 'Footer Column: C', 'cudazi' ),
			'id' => 'footer-c',
			'description' => __( 'Main footer, position in column C. Control more layout options in the Theme Settings page.', 'cudazi' ),
			'before_widget' => $default_before_widget,
			'after_widget' => $default_after_widget,
			'before_title' => $default_before_title,
			'after_title' => $default_after_title
		) );
		register_sidebar( array(
			'name' => __( 'Footer Column: D', 'cudazi' ),
			'id' => 'footer-d',
			'description' => __( 'Main footer, position in column D. Control more layout options in the Theme Settings page.', 'cudazi' ),
			'before_widget' => $default_before_widget,
			'after_widget' => $default_after_widget,
			'before_title' => $default_before_title,
			'after_title' => $default_after_title
		) );
		register_sidebar( array(
			'name' => __( 'Footer bottom: Left', 'cudazi' ),
			'id' => 'footer-bottom-left',
			'description' => __( 'Footer bottom bar, left side. Designed for a text widget. Do not enter a widget title. Check the box to add a paragraph tag.', 'cudazi' ),
			'before_widget' => $default_before_widget,
			'after_widget' => $default_after_widget,
			'before_title' => '',
			'after_title' => ''
		) );
		
				
		register_sidebar( array(
			'name' => __( 'Posts loop: Above', 'cudazi' ),
			'id' => 'posts-loop-above',
			'description' => __( 'Appears above the loop of posts.', 'cudazi' ),
			'before_widget' => $default_before_widget,
			'after_widget' => $default_after_widget,
			'before_title' => $default_before_title,
			'after_title' => $default_after_title
		) );
		register_sidebar( array(
			'name' => __( 'Posts loop: Below', 'cudazi' ),
			'id' => 'posts-loop-below',
			'description' => __( 'Appears below the loop of posts', 'cudazi' ),
			'before_widget' => $default_before_widget,
			'after_widget' => $default_after_widget,
			'before_title' => $default_before_title,
			'after_title' => $default_after_title
		) );
		register_sidebar( array(
			'name' => __( 'Posts loop: Appear After Nth Post', 'cudazi' ),
			'id' => 'nth-post',
			'description' => __( 'By default, widget area appears in post loop after the first post, adjust this in theme settings. Perfect for ads, sponsors, etc.', 'cudazi' ),
			'before_widget' => $default_before_widget,
			'after_widget' => $default_after_widget,
			'before_title' => $default_before_title,
			'after_title' => $default_after_title
		) );
		register_sidebar( array(
			'name' => __( 'Body: Top', 'cudazi' ),
			'id' => 'body-top',
			'description' => __( 'Appears at the top of the main body.', 'cudazi' ),
			'before_widget' => "<div class='grid_12'>" . $default_before_widget,
			'after_widget' => $default_after_widget . "</div><div class='clear'></div>",
			'before_title' => $default_before_title,
			'after_title' => $default_after_title
		) );
		register_sidebar( array(
			'name' => __( 'Body: Bottom', 'cudazi' ),
			'id' => 'body-bottom',
			'description' => __( 'Appears at the end of the main body, above the footers.', 'cudazi' ),
			'before_widget' => "<div class='grid_12'>" . $default_before_widget,
			'after_widget' => $default_after_widget . "</div><div class='clear'></div>",
			'before_title' => $default_before_title,
			'after_title' => $default_after_title
		) );
		
		
		
		
	}
	
	/** Register sidebars by running cudazi_widgets_init() on the widgets_init hook. */
	add_action( 'widgets_init', 'cudazi_widgets_init' );
?>