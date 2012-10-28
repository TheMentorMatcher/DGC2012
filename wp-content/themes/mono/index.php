<?php
/**
* The main template file.
*/
get_header(); ?>
    <div class="grid_8" id="primary">    
	 	<?php 
	 		// Run the loop to output the posts.
			get_template_part( 'loop', 'index' ); 
		?>
    </div><!--//grid_X-->
	
	<div class="grid_4" id="aside">
		<?php get_sidebar(); ?>
	</div><!--//grid_X-->
                
<?php get_footer(); ?>