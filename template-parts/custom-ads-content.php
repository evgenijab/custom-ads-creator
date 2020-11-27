<!-- Custom ADs content for Filter and List View -->
    <div class="custom-ad-post">
        <div style="float: right; margin: 0 10px">
            <?php the_post_thumbnail( array( 400, 400 ) ); ?>
         </div>
		<div class="entry-title">
            <a href="<?php echo get_permalink($the_post->ID);?>"><?php the_title(); ?></a>
        </div>
        <?php the_excerpt(); ?>
	</div>
