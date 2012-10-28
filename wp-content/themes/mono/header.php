<?php	
	// Grab custom settings
	global $cudazi_theme_options;	
	$logo_upload = cudazi_get_option( get_option_tree('logo_upload', $cudazi_theme_options, false, false ), get_template_directory_uri() . '/images/logo.png', false ); 
	$logo_url = cudazi_get_option( get_option_tree('logo_url', $cudazi_theme_options, false, false ), '', false ); 
	$logo_text_based = cudazi_get_option( get_option_tree('logo_text_based', $cudazi_theme_options, false, false ), '', false );
	
	// $disable_header_search = cudazi_get_option( get_option_tree('disable_header_search', $cudazi_theme_options, false, false ), '', false );
?><!DOCTYPE html>
<!--[if lt IE 7 ]>	<html lang="en" class="ie6 ie">	<![endif]-->
<!--[if IE 7 ]>	<html lang="en" class="ie7 ie">	<![endif]-->
<!--[if IE 8 ]>	<html lang="en" class="ie8 ie">	<![endif]-->
<!--[if IE 9 ]>	<html lang="en" class="ie9 ie">	<![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>> <!--<![endif]-->
	<head>

	<!-- General meta information -->
	<title><?php wp_title( ' ', true, 'right' ); /* filtered in libraries/filers.php */ ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<!-- // General meta information -->
	<!-- Google Font -->
	<link href='http://fonts.googleapis.com/css?family=Archivo+Narrow:400,700' rel='stylesheet' type='text/css'>

	<!-- Load stylesheets -->	
	<link type="text/css" rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/screen.css" media="screen" />	
	<?php /* Dynamic CSS from Theme Settings */ include_once(TEMPLATEPATH . "/css/dynamic-css.php"); ?>
	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
	<!-- // Load stylesheets -->

	<?php
		// Load Javascript
		if ( is_singular() && get_option( 'thread_comments' ) )	{ wp_enqueue_script( 'comment-reply' ); }
		wp_enqueue_script("jquery");
		wp_enqueue_script("slides", get_template_directory_uri() . "/js/libs/slides.min.jquery.js");
		wp_enqueue_script("superfish", get_template_directory_uri() . "/js/libs/superfish-combined.js");
		wp_enqueue_script("cudazi_general", get_template_directory_uri() . "/js/script.js");
		wp_head(); // do not remove this
	?>	
	<!--[if lt IE 9]><script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script><![endif]-->
	
	<!-- site icons -->
	<link type="text/css" rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.gif" />
	<link type="text/css" rel="apple-touch-icon" href="<?php echo get_template_directory_uri(); ?>/apple-touch-icon.png" />
	<!-- // site icons -->
	
</head>

<body <?php body_class(); ?>>
	<div id="outer">			
		<div id="header" class="clearfix">				
			<div class="container_12">
				<?php 
					$logo_area_size = cudazi_get_option( get_option_tree('logo_area_size', $cudazi_theme_options, false, false ), '', false ); 					
					if( $logo_area_size == 'tiny' ) {
						$menu_container_left = 'grid_1';
						$menu_container_right = 'grid_11';
					}else if( $logo_area_size == 'small' ){
						$menu_container_left = 'grid_2';
						$menu_container_right = 'grid_10';
					}else if( $logo_area_size == 'medium' ){
						$menu_container_left = 'grid_3';
						$menu_container_right = 'grid_9';
					}else if( $logo_area_size == 'large' ){
						$menu_container_left = 'grid_4';
						$menu_container_right = 'grid_8';
					}else{
						$menu_container_left = 'grid_2';
						$menu_container_right = 'grid_10';
					}
				?>
				<div class="<?php echo $menu_container_left; ?>">					
					<h1 id="logo">
						<?php /* Assign logo url if set 			*/	if ( $logo_url ) { $logo_upload = $logo_url; } ?>
						<?php /* If text logo not set, use image 	*/	if ( !$logo_text_based){ ?><a href="<?php echo home_url(); ?>"><img src="<?php echo $logo_upload; ?>" alt="<?php bloginfo('name'); ?>" /></a>
						<?php /* If text logo set, use the text		*/	}else{ ?><a href="<?php echo home_url(); ?>" class="textlogo"><?php echo $logo_text_based; ?></a><?php } ?>
					</h1>
				</div><!--//grid_x-->
				
				<div class="top_menu">
						<div class="tmenu-item students">

							<a href="/students/">Students</a>
							
							</div>
							<div class="tmenu-item mentors">
							
							<a href="/mentors/">Mentors</a>
							
							</div>
							<div class="tmenu-item parents"><a href="/parents/">Parents</a></div>
							<div class="tmenu-item donate"><a href="/donate/">Donate</a></div>
							<div class="social-icons">
							<a href="https://twitter.com/MentorMatcher" target="_blank"><img class="alignnone size-full wp-image-644" title="twitter" src="http://thementormatcher.org/wp-content/uploads/2012/10/twitter.png" alt="" width="24" height="24" /></a><a href="http://www.linkedin.com/groups/Mentor-Matcher-TMM-3954287" target="_blank"><img class="alignnone size-full wp-image-643" title="linkedin" src="http://thementormatcher.org/wp-content/uploads/2012/10/linkedin.png" alt="" width="24" height="24" /></a><a href="https://www.facebook.com/TheMentorMatcher" rel="attachment wp-att-642" target="_blank"><img class="alignnone size-full wp-image-642" title="facebook" src="http://thementormatcher.org/wp-content/uploads/2012/10/facebook.png" alt="" width="24" height="24" /></a>
							</div>
						
						
					</div>
				<div class="<?php echo $menu_container_right; ?> menu_container">					
					<?php wp_nav_menu( array( 'menu_class' => 'sf-menu clearfix', 'theme_location' => 'primary', 'fallback_cb' => 'cudazi_menu_fallback' ) ); ?>
					<?php
						$menu_email_link = cudazi_get_option( get_option_tree('menu_email_link', $cudazi_theme_options, false, false ), '', false );
						if( $menu_email_link ) {
							echo "<a href='mailto:" . $menu_email_link . "' class='menu-contact'>" . $menu_email_link . "</a>";
						}
					?>					
				</div><!--//grid_x-->					
				
			</div><!--//container_12-->			
		</div><!--//header-->
		
		<!-- *** -->
		
		<div id="main" class="clearfix">
			<div class="container_12">				
			<?php if ( is_active_sidebar( 'body-top' ) ) { dynamic_sidebar( 'body-top' ); } ?>
			