<?php
 /*Template Name: Single Template for Custom ADs
 */
 
get_header(); ?>
<div id="primary">
    <div id="content" role="main">
<!--     <?php global $post;?> -->
    <?php
    $mypost = array( 'post_type' => 'custom_ads', );
    $loop = new WP_Query( $mypost );
    
	while ( have_posts() ) : the_post();?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header"><h1 class="entry-title"><?php the_title(); ?></h1><div class="published-meta"> <p>Published on <?php the_time('F jS, Y'); ?> </p> </div></header>
            <div class="entry-content">
                <div class="section group">
                    <div class="col span_1_of_2">
                        <?php  the_content(); ?>
                    </div>
                    <div class="col span_1_of_2 tax-col">
                    <div style="text-align: center; ;">
                    <?php the_post_thumbnail( array( 400, 400 ) ); ?>
                    <?php
                $terms = wp_get_post_terms( $post->ID, 'locations');
                foreach ( $terms as $term ) { 
                   ?><div class="tax-terms"><strong>Location: </strong>
                   <a href="<?php echo get_term_link($term);?>"><?php echo $term->name; ?></a> </div><?php
                 }
                 $terms = wp_get_post_terms( $post->ID, 'prices');
                foreach ( $terms as $term ) { 
                     ?><div class="tax-terms"><strong>Price: </strong>
	                   <a href="<?php echo get_term_link($term);?>"> <?php echo $term->name; ?> </a></div><?php
                 }
                 ?>
                </div>
                    </div>
                </div>
                <!-- Display featured image in right-aligned floating div -->
                
                
                <!-- Display Title and Author Name -->

                
            </div>
 
            <!-- Display movie review contents -->
            <!-- <div class="entry-content"><?php the_content(); ?> -->

           

                
                

            </div>
        </article>
 
   <?php endwhile; // End of the loop.
			?>
    </div>
</div>
<?php wp_reset_query(); ?>
<?php get_footer(); ?>