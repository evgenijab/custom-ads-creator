<?php
 /*Template Name: List and filter all ADs
 */
 
get_header(); ?>
<div id="primary" class="content-area">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
		<header class="entry-header">
		 	<h1><?php the_title(); ?> </h1>
		 	
<!-- 		 	Add from with dropdown options for both Locations and Prices Taxonomies -->
					</header>
		<div class="entry-content">
			
			<?php the_post(); the_content(); ?>
			<form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter">
				<?php
				if( $terms = get_terms( array( 'taxonomy' => 'locations', 'orderby' => 'name', 'hide_empty'    => false, ) ) ) : 
					echo '<select name="locationfilter"><option value="">Select location</option>';
					foreach ( $terms as $term ) :
						echo '<option value="' . $term->term_id . '">' . $term->name . '</option>'; // ID of the category as the value of an option
					endforeach;
					echo '</select>';
				endif;?>
				<?php
					if( $terms = get_terms( array( 'taxonomy' => 'prices', 'orderby' => 'name', 'hide_empty'    => false, ) ) ) : 
						echo '<select name="pricefilter"><option value="">Select Price</option>';
						foreach ( $terms as $term ) :
							echo '<option value="' . $term->term_id . '">' . $term->name . '</option>'; // ID of the category as the value of an option
						endforeach;
						echo '</select>';
					endif;
				?>
				<button style="padding: 10px;">Submit</button>
				<input type="hidden" name="action" value="myfilter">
			</form>

			<div id="response">
			    <?php
				    
			    $mypost = array( 'post_type' => 'custom_ads', );
			    $loop = new WP_Query( $mypost );
			    
    			while ( $loop->have_posts() ) : $loop->the_post();
    				if ( '' === locate_template( 'template-parts/custom-ads-content.php', true, false ) )
    					include( 'template-parts/custom-ads-content.php' ); 
    			endwhile; ?>
   			 </div>
		</div>
		<?php wp_reset_query(); ?>
	</article>
</div>
<?php get_footer(); ?>