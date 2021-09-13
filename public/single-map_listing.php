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
// If this file is called direcgit ptly, abort.
if (!defined('WPINC')) {
    die;
}

get_header();
global $post;

echo do_action('before_single_page_starts');
$tax = get_the_terms($post->ID, 'listing_type');
if (!empty($tax)) {
    $icon_meta = get_term_meta($tax[0]->term_id, 'listing_type-icon', true);
} else {
    $icon_meta = '';
}

?>
<div class= "locatepress-single-listing-wrap">
<div class="container">
    <div class="row ">

        <?php echo apply_filters('locatepress_before_single_title', '<div class="col-md-8">') ?>
            <div class="plugin-main-title">
                <?php echo Locatepress_Public::locatepress_single_listing_logo($post->ID) ?>
                <h1><?php echo esc_html($post->post_title); ?></h1>
            </div>

        <?php do_action('before_sinlge_listing_address');?>

        <?php echo Locatepress_Public::locatepress_single_address($post->ID) ?>

        <?php do_action('after_sinlge_listing_address');?>

        <?php do_action('before_sinlge_listing_contact_no');?>

        <?php echo Locatepress_Public::locatepress_single_contact_no($post->ID) ?>

        <?php do_action('after_sinlge_listing_contact_no');?>

        <?php echo apply_filters('locatepress_after_single_title', '</div>') ?>


    </div>

    <div class="row">
        <div class="col-md-8">
            <?php do_action('before_single_page_featured_image');?>

            <?php echo Locatepress_Public::locatepress_single_listing_gallery($post->ID) ?>

            <?php do_action('after_single_page_featured_image');?>

            <?php do_action('before_single_page_content');?>

        	<?php echo apply_filters('single_page_content', get_the_content()) ?>

            <?php do_action('after_single_page_content');?>

            <?php do_action('before_single_page_video');?>

            <?php echo Locatepress_Public::locatepress_single_video($post->ID) ?>

            <?php do_action('after_single_page_video');?>

            <?php do_action('before_single_page_map');?>

            <?php echo apply_filters('locatepress_single_address_title', '<span class="address-span"><h3>' . __('Location ', 'locatepress') . '</h3></span>'); ?>

            <div class="locatepress-single-map" data-latlong="<?php echo esc_html(get_post_meta($post->ID, 'lp_location_lat_long', true)); ?>" data-info="<?php echo esc_html(get_post_meta($post->ID, 'lp_location_country', true)); ?>"  id="single-map" data-marker='<?php echo esc_url(wp_get_attachment_url($icon_meta)); ?>' style="width:100%;height: 300px;"></div>

            <?php do_action('after_single_page_map');?>

        </div>

        <div class="col-md-4">

            <?php do_action('before_sinlge_listing_business_hour');?>

            <?php echo Locatepress_Public::locatepress_single_business_hour($post->ID) ?>

            <?php do_action('after_sinlge_listing_business_hour');?>

            <?php do_action('before_sinlge_listing_social_profile');?>

            <?php echo Locatepress_Public::locatepress_single_social_profile($post->ID) ?>

            <?php do_action('after_sinlge_listing_social_profile');?>

            <?php dynamic_sidebar('locatepress-sidebar');?>

        </div>
    </div>
</div>
</div>
<?php
echo do_action('after_single_page_ends');
get_footer();
