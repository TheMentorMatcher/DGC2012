<!-- Source: loop.php -->
<?php // The loop that displays posts. ?>
<?php
	// Grab some theme settings 
	global $cudazi_theme_options;
	$nth_post_widget = cudazi_get_option( get_option_tree('nth_post_widget', $cudazi_theme_options, false, false ), 1, false ); 
	$nth_post_widget = explode( ',', $nth_post_widget );
?>
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('posts-loop-above') ) : endif; ?>
    
	<?php /* If there are no posts to display, such as an empty archive page */ ?>
	<?php if ( ! have_posts() ) : ?>
		<div id="post-0" class="post error404 not-found clearfix">
			<h2 class="entry-title"><?php _e( 'Not Found', 'cudazi' ); ?></h2>
			<div class="entry-content">
				<p><?php _e( "We are sorry, the item you requested cannot be found.", 'cudazi' ); ?></p>
				<div class="grid_3 alpha"><?php get_search_form(); ?></div><div class="clear"></div>
			</div><!-- //entry-content -->
		</div><!-- //post-0 -->
	<?php endif; ?>
	
	<?php /* Start the Loop. */ ?>
	<?php $postcount = 0; ?>
	<?php while ( have_posts() ) : the_post(); ?>
	
		<?php $postcount++; ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>			

			<?php echo cudazi_get_featured_image( 'grid_8', '' ); ?>		

			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'cudazi' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<?php get_template_part( 'meta', 'post' ); ?>
			<div class="entry-content">
				<?php the_content( __( 'Read More...', 'cudazi' ) ); ?>
			</div><!--//entry-content -->
		</div><!--//post-->
		
		<?php 
			if ( in_array($postcount, $nth_post_widget ) ) {
				if ( is_active_sidebar( 'nth-post' ) ) { 
					dynamic_sidebar( 'nth-post' );
				} 
			} 
		?>

	<?php endwhile; // End the loop. ?>
	<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('posts-loop-below') ) : endif; ?>	
    <?php get_template_part( 'nav', 'post-loop' ); ?>
