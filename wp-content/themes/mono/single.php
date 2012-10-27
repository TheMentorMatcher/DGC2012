<?php
/**
* The Template for displaying single posts, customized to pull in a unique post template on a per-post basis.
*/
	get_header(); 
	$cudazi_post_settings = get_post_meta($post->ID, 'cudazi_post_settings', true);
	$primary_grid_size = 'grid_8';
?>
	
	<?php if( $cudazi_post_settings == 'default' || !$cudazi_post_settings) { ?>
		<div class="grid_8" id="primary">
	<?php } else { 
		$primary_grid_size = 'grid_12'; ?>
		<div class="grid_12" id="primary">
	<?php } ?>
	
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>    
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			
				
				<?php echo cudazi_get_featured_image( $primary_grid_size, '' ); ?>
				
				<?php 
					if ( ! get_post_meta( $post->ID, 'hide_title', true ) ) { 
						the_title( "<h2 class='entry-title'>", '</h2>', true ); 
					}				
				?>
				
				<div class="entry-content">
					<?php the_content(); ?>
					<?php wp_link_pages( array( 'before' => '' . __( '<p>Pages:', 'cudazi' ), 'after' => '</p>' ) ); ?>
				</div><!--//entry-content -->     
				
				<?php
					if ( ! get_post_meta( $post->ID, 'hide_meta', true ) ) { 
						get_template_part( 'meta', 'post' ); 
					}
				?>
				
			</div><!-- //post-->
			<?php comments_template( '', true ); ?>				
		<?php endwhile; // end of the loop. ?>
		
	</div><!--//grid_X-->
	
	<?php if( $cudazi_post_settings == 'default' || !$cudazi_post_settings ) { ?>
		<div class="grid_4" id="aside">
			<?php get_sidebar(); ?>
		</div><!--//grid_X-->
	<?php } ?>
	
<?php get_footer(); ?>