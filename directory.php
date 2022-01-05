<?php

/**
 *
 *
 * @wordpress-plugin
 * Plugin Name:       Directory
 * Plugin URI:        https://wordpress.org
 * Description:       This plugin enables you to add your directory posts.
 * Version:           1.0
 * Author:            Rashmi Singh
 * Author URI:        https://profiles.wordpress.org
 * Text Domain:       directory
 */
 
namespace Directory;

$plugin_name = 'directory';
$plugin_text_domain = 'directory';
$plugin_version = '1.0';

/*** Define Constants*/
define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

class Directory {

    /**
	 * The instance of the plugin.
	 *
	 * @since    1.0
	 * @var      Init $init Instance of the plugin.
	 */	

    public function init()
    {
        add_action('init', array($this, 'directoryPosts'));
		add_shortcode( 'directory-posts', array( $this,'wp_directory_posts' )); 
		add_filter('manage_edit-directory_columns' , array( $this,'custom_set_directory_columns'));
		add_action( 'manage_directory_posts_custom_column' , array( $this,'custom_set_posts_columns_value'), 10, 2 );
    }
     
    public function directoryPosts(){
        register_post_type(
            'directory',
            array(
                'labels' => array(
                    'name' => 'Directories',
                    'singular_name' => 'Directories',
                    'add_new' => 'Add New Directory',
                    'add_new_item' => 'Add New Directory',
                    'edit' => 'Edit',
                    'edit_item' => 'Edit Directory',
                    'new_item' => 'New Directory',
                    'view' => 'View',
                    'view_item' => 'View Directory',
                    'search_items' => 'Search Directory',
                    'not_found' => 'No Directory',
                    'not_found_in_trash' => 'No Directory found in Trash',
                    'parent' => 'Parent Directory'
                ),
     
                'public' => true,
                'publicly_queryable' => true,  // you should be able to query it
                'show_ui' => true,  // you should be able to edit it in wp-admin
                'exclude_from_search' => true,  // you should exclude it from search results
                'show_in_menu' => true,
                'show_in_nav_menus' => true,  // you shouldn't be able to add it to menus
                'rewrite' => true,  // it shouldn't have rewrite rules
                'menu_position' => 20,
                'hierarchical' => true,
                'menu_icon' => 'dashicons-format-aside',
                'can_export' => true,
                'query_var' => true,
                'supports' => array( 'title', 'editor','thumbnail','categories' ),
                'has_archive' => false,
                'capability_type' => 'post'
            )
        );
    }
	public function be_mcq_explode( $string = '' ) {
		$string = str_replace( ', ', ',', $string );
		return explode( ',', $string );
	}
	
	public function wp_directory_posts($atts){
		
        extract(shortcode_atts(array(
            'post_type' => 'directory',
            'id' => '',
            'posts_per_page' => -1,
            'order' => 'ASC'
            ), $atts, 'directory-posts')
        );  
		
		// If Post IDs.
        if ( $id ) {
            $posts_in = explode(',', $id);
            $args['post__in'] = $posts_in;
        }
		ob_start();
        $query = new \WP_Query($args);

        if ( $query->have_posts() ) { ?>
            <ul class="clothes-listing">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                <li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </li>
                <?php endwhile;
                wp_reset_postdata(); ?>
            </ul>
        <?php $myvariable = ob_get_clean();
        return $myvariable;
        }
    }
	
	public function custom_set_directory_columns($columns) {
		$columns['id'] = __('ID');
		$columns['shortcodes'] = __('Shortcodes');
		return $columns;
	}

	public function custom_set_posts_columns_value( $column, $post_id ) {
		if ($column == 'id'){
			echo $post_id;
		}
		if ($column == 'shortcodes'){
			echo '[directory-posts id = '.$post_id.']';
		}
	}
   

}

// Initialize
( new Directory() )->init();