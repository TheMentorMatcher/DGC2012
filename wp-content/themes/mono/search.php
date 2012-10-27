<?php
/**
* The search results template
*/
get_header(); ?>
				
	<div class="grid_8" id="primary">
			<p><strong><?php 
				echo sprintf( 
					__( 'Your search for %s returned %s %s.', 'cudazi' ), 
						"<span class='highlight'>".get_search_query()."</span>", 
						$wp_query->found_posts,
						_n( 'result', 'results', $wp_query->found_posts, 'cudazi' )						
				 ); ?></strong></p>
            <?php
			
            /* Run the loop for the search to output the results.
             * If you want to overload this in a child theme then include a file
             * called loop-search.php and that will be used instead.
             */
             query_posts($query_string . '&posts_per_page=100');
             get_template_part( 'loop', 'search' );
            ?>
    </div><!--//grid_X-->
	<div class="grid_4" id="aside">
		<?php get_sidebar(); ?>
	</div><!--//grid_4-->
<?php get_footer(); ?>