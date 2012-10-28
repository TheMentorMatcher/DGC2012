<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
		<?php 
			if ( ! get_post_meta( $post->ID, 'hide_title', true ) ) {
				the_title( '<h2>', '</h2>', true );
			}
		?>
		
		<div class="entry-content clearfix">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '' . __( '<p>Pages:', 'cudazi' ), 'after' => '</p>' ) ); ?>
			<?php edit_post_link( __( 'Edit', 'cudazi' ), '<p>', '</p>' ); ?>
		</div><!-- //entry-content -->
	</div><!-- //post -->
	<?php comments_template( '', true ); ?>
	
<?php endwhile; ?>