<?php
/**
 * The template for displaying all map_listing posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage Twenty_Nineteen
 * @since Twenty Nineteen 1.0
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


get_header();
global $post;

echo do_action('before_single_page_starts');
$tax = get_the_terms($post->ID,'location_type');
$icon_meta = get_term_meta ( $tax[0]->term_id, 'location_type-icon', true );
?>

<div class="container">
    <div class="row ">
        <?php echo apply_filters('locatepress_before_single_title','<div class="col-md-8">') ?>
            <div class="plugin-main-title">
                <h2><?php echo $post->post_title;?></h2>
            </div>
        <?php echo apply_filters('locatepress_after_single_title','</div>') ?>
    </div>

    <div class="row">
        <div class="col-md-7">
            <?php do_action('before_single_page_featured_image'); ?>
        	 <img src="<?php echo get_the_post_thumbnail_url($post->ID,'full'); ?>">
              <?php do_action('after_single_page_featured_image'); ?>
        	<?php echo apply_filters('single_page_content',get_the_content())?>
        </div>
        <div class="col-md-5">

        	<?php echo apply_filters('locatepress_single_address_title','<span class="addres-span"><b>'.  __('Address :','locatepress').'</b></span>'); ?>
            <?php echo apply_filters('locatepress_before_single_address','<p class="lp-address-meta">'); ?>
        	<?php echo apply_filters('locatpress_single_address',get_post_meta($post->ID,'lp_location_country',true)); ?>
            <?php echo apply_filters('locatepress_after_single_address','</p>'); ?>
             <?php do_action('before_single_page_map'); ?>
        	<div class="locatepress-single-map" data-latlong="<?php echo get_post_meta($post->ID,'lp_location_lat_long',true); ?>" data-info="<?php echo  get_post_meta($post->ID,'lp_location_country',true);  ?>"  id="single-map" data-marker='<?php echo esc_url(wp_get_attachment_url($icon_meta));?>' style="width:100%;height: 300px;"></div>
             <?php do_action('after_single_page_map'); ?>
        </div>
    </div>
</div>
<?php
echo do_action('after_single_page_ends');
get_footer();
 