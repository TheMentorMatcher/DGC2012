<?php if (  $wp_query->max_num_pages > 1 ) : ?>        
	<div class="post-pagination clearfix">
		<?php if ( function_exists( 'wp_pagenavi' ) ) { ?>
				<?php 
					// Use pagenavi plugin if installed
					wp_pagenavi(); 
				?>
		<?php }else{ ?>
			<?php previous_posts_link( __( 'Previous Posts', 'cudazi' ) ); ?>
			<span class="right"><?php next_posts_link( __( 'Next Posts', 'cudazi' ) ); ?></span>                
		<?php } ?>
	</div><!--//post-navigaiton-->
<?php endif; ?>