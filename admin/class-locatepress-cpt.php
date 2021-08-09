<?php 
/**
 * Register custom post type
 *
 * @since      0.1.0
 *
 * @package    locatepress
 * @subpackage locatepress/includes
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Locatepress_Register_Cpt {

    /**
     * Register custom post type
     */
    private function locatepress_register_single_post_type( $fields ) {

        $labels = array(
            'name'                  => $fields['plural'],
            'singular_name'         => $fields['singular'],
            'menu_name'             => $fields['menu_name'],
            'new_item'              => sprintf( __( 'New %s', 'locatepress' ), $fields['singular'] ),
            'add_new'               => sprintf( __( 'Add new %s' , 'locatepress' ), $fields['singular'] ),
            'add_new_item'          => sprintf( __( 'Add new %s', 'locatepress' ), $fields['singular'] ),
            'edit_item'             => sprintf( __( 'Edit %s', 'locatepress' ), $fields['singular'] ),
            'view_item'             => sprintf( __( 'View %s', 'locatepress' ), $fields['singular'] ),
            'view_items'            => sprintf( __( 'View %s', 'locatepress' ), $fields['plural'] ),
            'search_items'          => sprintf( __( 'Search %s', 'locatepress' ), $fields['plural'] ),
            'not_found'             => sprintf( __( 'No %s found', 'locatepress' ), strtolower( $fields['plural'] ) ),
            'not_found_in_trash'    => sprintf( __( 'No %s found in trash', 'locatepress' ), strtolower( $fields['plural'] ) ),
            'all_items'             => sprintf( __( 'All %s', 'locatepress' ), $fields['plural'] ),
            'archives'              => sprintf( __( '%s Archives', 'locatepress' ), $fields['singular'] ),
            'attributes'            => sprintf( __( '%s Attributes', 'locatepress' ), $fields['singular'] ),
            'insert_into_item'      => sprintf( __( 'Insert into %s', 'locatepress' ), strtolower( $fields['singular'] ) ),
            'uploaded_to_this_item' => sprintf( __( 'Uploaded to this %s', 'locatepress' ), strtolower( $fields['singular'] ) ),

           
            'parent_item'           => sprintf( __( 'Parent %s', 'locatepress' ), $fields['singular'] ),
            'parent_item_colon'     => sprintf( __( 'Parent %s:', 'locatepress' ), $fields['singular'] ),

            
			'archive_title'        => $fields['plural'],
        );

        $args = array(
            'labels'             => $labels,
            'description'        => ( isset( $fields['description'] ) ) ? $fields['description'] : '',
            'public'             => ( isset( $fields['public'] ) ) ? $fields['public'] : true,
            'publicly_queryable' => ( isset( $fields['publicly_queryable'] ) ) ? $fields['publicly_queryable'] : true,
            'exclude_from_search'=> ( isset( $fields['exclude_from_search'] ) ) ? $fields['exclude_from_search'] : false,
            'show_ui'            => ( isset( $fields['show_ui'] ) ) ? $fields['show_ui'] : true,
            'show_in_menu'       => ( isset( $fields['show_in_menu'] ) ) ? $fields['show_in_menu'] : true,
            'query_var'          => ( isset( $fields['query_var'] ) ) ? $fields['query_var'] : true,
            'show_in_admin_bar'  => ( isset( $fields['show_in_admin_bar'] ) ) ? $fields['show_in_admin_bar'] : true,
            'capability_type'    => ( isset( $fields['capability_type'] ) ) ? $fields['capability_type'] : 'post',
            'has_archive'        => ( isset( $fields['has_archive'] ) ) ? $fields['has_archive'] : true,
            'hierarchical'       => ( isset( $fields['hierarchical'] ) ) ? $fields['hierarchical'] : true,
            'show_in_rest'       => ( isset( $fields['show_in_rest'] ) ) ? $fields['show_in_rest'] : true,
            'supports'           => ( isset( $fields['supports'] ) ) ? $fields['supports'] : array(
                    'title',
                    'editor',
                    'excerpt',
                    'author',
                    'thumbnail',
                    'comments',
                    'trackbacks',
                    'custom-fields',
                    'revisions',
                    'page-attributes',
                    'post-formats',
            ),
            'menu_position'      => ( isset( $fields['menu_position'] ) ) ? $fields['menu_position'] : 21,
            'menu_icon'          => ( isset( $fields['menu_icon'] ) ) ? $fields['menu_icon']: 'dashicons-admin-generic',
            'show_in_nav_menus'  => ( isset( $fields['show_in_nav_menus'] ) ) ? $fields['show_in_nav_menus'] : true,
        );

        if ( isset( $fields['rewrite'] ) ) {

        
            $args['rewrite'] = $fields['rewrite'];
        }

        if ( $fields['custom_caps'] ) {

            $args['capabilities'] = array(

                // Meta capabilities
                'edit_post'                 => 'edit_' . strtolower( $fields['singular'] ),
                'read_post'                 => 'read_' . strtolower( $fields['singular'] ),
                'delete_post'               => 'delete_' . strtolower( $fields['singular'] ),

                // Primitive capabilities used outside of map_meta_cap():
                'edit_posts'                => 'edit_' . strtolower( $fields['plural'] ),
                'edit_others_posts'         => 'edit_others_' . strtolower( $fields['plural'] ),
                'publish_posts'             => 'publish_' . strtolower( $fields['plural'] ),
                'read_private_posts'        => 'read_private_' . strtolower( $fields['plural'] ),

                // Primitive capabilities used within map_meta_cap():
                'delete_posts'              => 'delete_' . strtolower( $fields['plural'] ),
                'delete_private_posts'      => 'delete_private_' . strtolower( $fields['plural'] ),
                'delete_published_posts'    => 'delete_published_' . strtolower( $fields['plural'] ),
                'delete_others_posts'       => 'delete_others_' . strtolower( $fields['plural'] ),
                'edit_private_posts'        => 'edit_private_' . strtolower( $fields['plural'] ),
                'edit_published_posts'      => 'edit_published_' . strtolower( $fields['plural'] ),
                'create_posts'              => 'edit_' . strtolower( $fields['plural'] )

            );

            $args['map_meta_cap'] = true;

        
            $this->locatepress_assign_capabilities( $args['capabilities'], $fields['custom_caps_users'] );
        }

        register_post_type( $fields['slug'], $args );


        if ( isset( $fields['taxonomies'] ) && is_array( $fields['taxonomies'] ) ) {

            foreach ( $fields['taxonomies'] as $taxonomy ) {

                $this->locatepress_register_single_post_type_taxnonomy( $taxonomy );

            }

        }

    }

    private function locatepress_register_single_post_type_taxnonomy( $tax_fields ) {

        $labels = array(
            'name'                       => $tax_fields['plural'],
            'singular_name'              => $tax_fields['single'],
            'menu_name'                  => $tax_fields['plural'],
            'all_items'                  => sprintf( __( 'All %s' , 'locatepress' ), $tax_fields['plural'] ),
            'edit_item'                  => sprintf( __( 'Edit %s' , 'locatepress' ), $tax_fields['single'] ),
            'view_item'                  => sprintf( __( 'View %s' , 'locatepress' ), $tax_fields['single'] ),
            'update_item'                => sprintf( __( 'Update %s' , 'locatepress' ), $tax_fields['single'] ),
            'add_new_item'               => sprintf( __( 'Add New %s' , 'locatepress' ), $tax_fields['single'] ),
            'new_item_name'              => sprintf( __( 'New %s Name' , 'locatepress' ), $tax_fields['single'] ),
            'parent_item'                => sprintf( __( 'Parent %s' , 'locatepress' ), $tax_fields['single'] ),
            'parent_item_colon'          => sprintf( __( 'Parent %s:' , 'locatepress' ), $tax_fields['single'] ),
            'search_items'               => sprintf( __( 'Search %s' , 'locatepress' ), $tax_fields['plural'] ),
            'popular_items'              => sprintf( __( 'Popular %s' , 'locatepress' ), $tax_fields['plural'] ),
            'separate_items_with_commas' => sprintf( __( 'Separate %s with commas' , 'locatepress' ), $tax_fields['plural'] ),
            'add_or_remove_items'        => sprintf( __( 'Add or remove %s' , 'locatepress' ), $tax_fields['plural'] ),
            'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s' , 'locatepress' ), $tax_fields['plural'] ),
            'not_found'                  => sprintf( __( 'No %s found' , 'locatepress' ), $tax_fields['plural'] ),
        );

        $args = array(
        	'label'                 => $tax_fields['plural'],
        	'labels'                => $labels,
        	'hierarchical'          => ( isset( $tax_fields['hierarchical'] ) )          ? $tax_fields['hierarchical']          : true,
        	'public'                => ( isset( $tax_fields['public'] ) )                ? $tax_fields['public']                : true,
        	'show_ui'               => ( isset( $tax_fields['show_ui'] ) )               ? $tax_fields['show_ui']               : true,
        	'show_in_nav_menus'     => ( isset( $tax_fields['show_in_nav_menus'] ) )     ? $tax_fields['show_in_nav_menus']     : true,
        	'show_tagcloud'         => ( isset( $tax_fields['show_tagcloud'] ) )         ? $tax_fields['show_tagcloud']         : true,
        	'meta_box_cb'           => ( isset( $tax_fields['meta_box_cb'] ) )           ? $tax_fields['meta_box_cb']           : null,
        	'show_admin_column'     => ( isset( $tax_fields['show_admin_column'] ) )     ? $tax_fields['show_admin_column']     : true,
        	'show_in_quick_edit'    => ( isset( $tax_fields['show_in_quick_edit'] ) )    ? $tax_fields['show_in_quick_edit']    : true,
        	'update_count_callback' => ( isset( $tax_fields['update_count_callback'] ) ) ? $tax_fields['update_count_callback'] : '',
        	'show_in_rest'          => ( isset( $tax_fields['show_in_rest'] ) )          ? $tax_fields['show_in_rest']          : true,
        	'rest_base'             => $tax_fields['taxonomy'],
        	'rest_controller_class' => ( isset( $tax_fields['rest_controller_class'] ) ) ? $tax_fields['rest_controller_class'] : 'WP_REST_Terms_Controller',
        	'query_var'             => $tax_fields['taxonomy'],
        	'rewrite'               => ( isset( $tax_fields['rewrite'] ) )               ? $tax_fields['rewrite']               : true,
        	'sort'                  => ( isset( $tax_fields['sort'] ) )                  ? $tax_fields['sort']                  : '',
        );

        $args = apply_filters( $tax_fields['taxonomy'] . '_args', $args );

        register_taxonomy( $tax_fields['taxonomy'], $tax_fields['post_types'], $args );

    }

    /**
     * Assign capabilities to users
     *
     */
    public function locatepress_assign_capabilities( $caps_map, $users  ) {

        foreach ( $users as $user ) {

            $user_role = get_role( $user );

            foreach ( $caps_map as $cap_map_key => $capability ) {

                $user_role->add_cap( $capability );

            }

        }

    }

    

    /**
     * Create post types
     */
    public function locatepress_create_custom_post_type() {

        $post_types_fields = array(
            array(
                'slug'                  => 'map_listing',
                'singular'              => 'Listing',
                'plural'                => 'Listings',
                'menu_name'             => 'Listings',
                'description'           => 'Tests',
                'has_archive'           => true,
                'hierarchical'          => false,
                'menu_icon'             => 'dashicons-tag',
                'rewrite' => array(
                    'slug'                  => 'map_listing',
                    'with_front'            => true,
                    'pages'                 => true,
                    'feeds'                 => true,
                    'ep_mask'               => EP_PERMALINK,
                ),
                'menu_position'         => 21,
                'public'                => true,
                'publicly_queryable'    => true,
                'exclude_from_search'   => true,
                'show_ui'               => true,
                'show_in_menu'          => true,
                'show_in_rest'          => true,
                'query_var'             => true,
                'show_in_admin_bar'     => true,
                'show_in_nav_menus'     => true,
                'supports'              => array(
                    'title',
                    'editor',
                    'excerpt',
                    'author',
                    'thumbnail',
                    'comments',
                    'trackbacks',
                    'custom-fields',
                    'revisions',
                    'page-attributes',
                    'post-formats',
                ),
                'custom_caps'           => true,
                'custom_caps_users'     => array(
                    'administrator',
                ),
                'taxonomies'            => array(

                    array(
                        'taxonomy'          => 'listing_type',
                        'plural'            => 'Listing Types',
                        'single'            => 'Listing Type',
                        'post_types'        => array( 'map_listing' ),
                    ),
                    array(
                        'taxonomy'          => 'listing_category',
                        'plural'            => 'Categories',
                        'single'            => 'Category',
                        'post_types'        => array( 'map_listing' ),
                    ),
                    array(
                        'taxonomy'          => 'listing_tags',
                        'plural'            => 'Listing Tags',
                        'single'            => 'Listing Tag',
                        'post_types'        => array( 'map_listing'),
                        'hierarchical'     => false,
                        'rewrite' => array( 'slug' => 'tag' ),   //added tags
                    ),

                ),

            ),
        );

        foreach ( $post_types_fields as $fields ) {

            $this->locatepress_register_single_post_type( $fields );

        }

      

    }

   
}

