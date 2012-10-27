<?php
/**
* Template Name: Full Width Page
*/
get_header(); ?>
				
    <div class="grid_12" id="primary">
    	<?php // Run the loop to output the posts. ?>
		<?php get_template_part( 'loop', 'page' ); ?>		
    </div><!--//grid_X-->
	
<?php get_footer(); ?>