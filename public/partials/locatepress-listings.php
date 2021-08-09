<?php

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

$featured_img_url 	= get_the_post_thumbnail_url(get_the_ID(), 'medium');
$terms 				= get_the_terms(get_the_ID(), 'listing_type');
$term_name 			= $terms[0]->name;
?>
<?php apply_filters('locatepress_listing_loop_start', get_the_id());?>
<div class="lp-card-col">
	<div class="lp-card ">
		<?php apply_filters('locatepress_before_listing_image', get_the_id());?>

		<img class="card-featured-image" src="<?php echo esc_url($featured_img_url)  ?>" class="aligncenter">

		<?php apply_filters('locatepress_after_listing_image', get_the_id())?>

		<div class="lp-card-body">

			<?php apply_filters('locatepress_before_listing_title', get_the_id());?>

			<h3 class="card-body-title"><?php echo esc_html (get_the_title() ); ?></h3>

			<?php apply_filters('locatepress_after_listing_title', get_the_id());?>

			<?php apply_filters('locatepress_before_listing_term', get_the_id());?>

			<span class="card-body-cat"><?php echo esc_html( $term_name ) ; ?></span>

			<?php apply_filters('locatepress_after_listing_term', get_the_id());?>

			<?php apply_filters('locatepress_before_listing_viewlocation', get_the_id());?>

			<p class="card-body-location">
				<a href="<?php echo esc_url (get_the_permalink()); ?>"><?php _e('View Location', 'locatepress');?></a>
			</p>
			
			<?php apply_filters('locatepress_after_listing_viewlocation', get_the_id());?>
		</div>
	</div>
</div>
<?php apply_filters('locatepress_listing_loop_ends', get_the_id());?>
<?php
