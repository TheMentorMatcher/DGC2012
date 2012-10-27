<div class="entry-meta clearfix">
	<div class="left">
			<span class="post-date"><?php the_time( get_option( 'date_format' ) ); ?></span> &bull; <span class="post-categories"><?php the_category(', '); ?></span> &bull; <?php _e('by', 'cudazi'); ?> <?php the_author_posts_link(); ?> <?php edit_post_link( __('edit','cudazi'), ' &bull; '); ?>
	</div><!--//left-->
	<div class="right">
			<?php if ( get_the_tags() && is_single() ) { ?>
				<span class="post-categories"><?php the_tags(' '); ?></span>
			<?php } ?>	
			<?php if( comments_open() && ! is_single() ) { ?><span class="post-comment-link"><?php comments_popup_link( __( '0 Comments', 'cudazi' ), __( '1 Comment', 'cudazi' ), __( '% Comments', 'cudazi' ), null, '' ); ?></span><?php } ?>			
	</div><!--//right-->
</div><!--//entry-meta -->