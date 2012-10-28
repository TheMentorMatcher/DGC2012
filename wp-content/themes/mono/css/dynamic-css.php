<?php 
	
	// This file includes inline styles/css based on theme settings
	
	$dynamic_css_output = "";
	
	$css_theme = cudazi_get_option( get_option_tree('css_theme', $cudazi_theme_options, false, false ), '', 'css_theme' );
	$color_link_primary = cudazi_get_option( get_option_tree('color_link_primary', $cudazi_theme_options, false, false ), '', false );
	$color_link_primary_hover = cudazi_get_option( get_option_tree('color_link_primary_hover', $cudazi_theme_options, false, false ), '', false );
	
	if (
		$css_theme || 
		$color_link_primary ||
		$color_link_primary_hover
	){
		
		ob_start();
	
		if ( $css_theme ) { ?> 
		/* Skin add-on from theme options */
			@import url(<?php echo get_template_directory_uri() . '/css/themes/' . $css_theme . '.css'; ?>) screen;
		/* End skin */
		<?php } ?>
	
		<?php if ( $color_link_primary ) { ?>
		/* Style add-on from theme options */
			a, 
			.entry-title a:hover, 
			.sf-menu a:hover,
			.sf-menu li.current-menu-item a:hover,
			.sf-menu li.sfHover ul a:hover, 
			.sf-menu li.sfHover a { 
				color: <?php echo $color_link_primary; ?>;
			}
			.sf-menu li.current-menu-item a:hover, 
			.sf-menu a:hover, 
			.sf-menu li.sfHover A { 
				border-bottom-color: <?php echo $color_link_primary; ?>;
			}
		<?php } ?>
		
		<?php if ( $color_link_primary_hover ) { ?>
		/* Style add-on from theme options */
			a:hover, 
			.cudazi-latest-posts li a:hover { 
				color: <?php echo $color_link_primary_hover; ?>;
			}				
		<?php } ?>
			
		<?php $buffer = ob_get_contents();
		ob_end_clean(); ?>
		
		<!-- dynamic-css from theme settings override -->
		<style type='text/css'><?php echo $buffer; ?></style>
		<!-- // end dynamic-css from theme settings override -->
		
<?php } // end if css override	?>