<?php
/**
 * Apricot functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Apricot
 */

/**
 * Include tgm class to required plugins
 */
require_once get_template_directory() . '/inc/php/class-tgm-plugin-activation.php';

/**
 * declare Apricot main class
 * 
 * @package Apricot
 * @author kika <kika.khaled@gmail.com>
 *
 */
if (!class_exists( 'apricot' )) {
	class apricot {
		/**
		 * constract function to add all hocks
		 */
		function __construct() {
			add_action( 'tgmpa_register', array($this, 'apricot_req_plugins' )); //required plugins
			add_action( 'after_setup_theme', array($this, 'apricot_theme_setup' )); //general support
			add_action( 'after_setup_theme', array($this, 'apricot_content_width' ), 0); //content width
			add_action( 'widgets_init', array($this, 'apricot_widgets_init' )); //Sidebar or widget area
			add_action( 'wp_enqueue_scripts', array($this, 'apricot_fronted' )); // enqueue all css & js we'll use
			add_filter( 'mce_buttons_3', array($this, 'apricot_enable_more_buttons' )); // Add more buttons to editor
			$this->apricot_includes();
		}
		/**
		 * all reqiured plugins
		 */
		function apricot_req_plugins(){
			$plugins = array(
                array(
                    'name' => 'Meta Box',
                    'slug' => 'meta-box',
                    'required' => true,
                ),
			);
			$config = array(
                'id' => 'apricot',
                'default_path' => '',
                'menu' => 'tgmpa-install-plugins',
                'parent_slug' => 'themes.php',
                'capability' => 'edit_theme_options',
                'has_notices' => true,
                'dismissable' => false,
                'dismiss_msg' => '',
                'is_automatic' => false,
                'message' => '',
            );
            tgmpa($plugins, $config);
		}
		/**
		 * Sets up theme defaults and registers support for various WordPress features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 */
		function apricot_theme_setup(){
			register_nav_menus(array(
                'primary' => esc_html__( 'Primary', 'Apricot' ),
                'top' => esc_html__( 'Top menu', 'Apricot' ),
            ));

			/*
			 * Switch default core markup for search form, comment form, and comments
			 * to output valid HTML5.
			 */
	        add_theme_support( 'html5', array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
            ));
			/*
			 * Enable support for Post Formats.
			 * See https://developer.wordpress.org/themes/functionality/post-formats/
			 */
	        add_theme_support( 'post-formats', array(
                'aside',
                'image',
                'video',
                'gallery',
                'link',
                'status',
                'audio',
            ));
			/*
			 * Enable support for Post Thumbnails on posts and pages.
			 *
			 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
			 */
			add_theme_support( 'post-thumbnails' );
			/*
			 * Let WordPress manage the document title.
			 * By adding theme support, we declare that this theme does not use a
			 * hard-coded <title> tag in the document head, and expect WordPress to
			 * provide it for us.
			 */
			add_theme_support( 'title-tag' );
				// Add default posts and comments RSS feed links to head.
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'woocommerce' );
			add_theme_support( 'customize-selective-refresh-widgets' );
			add_theme_support( 'custom-logo' );
			add_theme_support( 'custom-header' );
			add_theme_support( 'custom-background' );
			/*
			 * Make theme available for translation.
			 * Translations can be filed in the /languages/ directory.
			 * If you're building a theme based on Apricot, use a find and replace
			 * to change 'Apricot' to the name of your theme in all the template files.
			 */
			load_theme_textdomain( 'Apricot', get_template_directory() . '/languages' );
		}
		/**
		 * Set the content width in pixels, based on the theme's design and stylesheet.
		 *
		 * Priority 0 to make it available to lower priority callbacks.
		 *
		 * @global int $content_width
		 */
 		function apricot_content_width(){
 			$GLOBALS['content_width'] = apply_filters( 'Apricot_content_width', 640 );
		}
		/**
		 * Register widget area.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
		 */
 		function apricot_widgets_init(){
			register_sidebar( array(
				'name'          => esc_html__( 'Sidebar', 'Apricot' ),
				'id'            => 'sidebar-1',
				'description'   => '',
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h2 class="widget-title">',
				'after_title'   => '</h2>',
			) );
		}
		/**
		 * Enqueue scripts and styles.
		 */
		function apricot_fronted(){
            global $is_IE;
            /* ============ Styles ============ */
            wp_register_style('bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', false, '3.3.7');
            wp_enqueue_style('bootstrap');
            wp_register_style('fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css', false, '4.6.3');
            wp_enqueue_style('fontawesome');
            wp_register_style('animate', get_template_directory_uri() . '/inc/css/animate.min.css', false, '3.5.1');
            wp_enqueue_style('animate');
            wp_register_style('apricotmain', get_stylesheet_directory_uri() . '/inc/css/main.min.css', false, '1.0'); //main
            wp_enqueue_style('apricotmain');
            /* ============ Javascripts ============ */
            if ($is_IE) {
                wp_register_script('html5', get_template_directory_uri() . '/inc/js/html5shiv.min.js', false, '3.7.3', false);
                wp_script_add_data('html5', 'conditional', 'lt IE 9');
                wp_enqueue_script('html5');
                wp_register_script('respond', get_template_directory_uri() . '/inc/js/respond.min.js', false, '1.4.2', false);
                wp_script_add_data('respond', 'conditional', 'lt IE 9');
                wp_enqueue_script('respond');
            }
            wp_register_script('bootstrapjs', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array('jquery'), '3.3.7', true); //bootstrap
            wp_enqueue_script('bootstrapjs');
            wp_enqueue_script('apricot-navigation', get_template_directory_uri() . '/inc/js/navigation.min.js', array(), '20151215', true);
            wp_enqueue_script('apricot-skip-link-focus-fix', get_template_directory_uri() . '/inc/js/skip-link-focus-fix.min.js', array(), '20151215', true);
            if (is_singular() && comments_open() && get_option('thread_comments')) {
                wp_enqueue_script('comment-reply');
            }
            wp_register_script('mainjs', get_stylesheet_directory_uri() . '/inc/js/main.min.js', array('jquery'), '1.0', true); //main
            wp_localize_script('mainjs', 'ajax_object', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce('return_ajax')));
            wp_enqueue_script('main_js');
        }
        /**
         * Enable more buttons in wp_editor
         */
        function apricot_enable_more_buttons(){
        	$buttons[] = 'fontselect';
            $buttons[] = 'fontsizeselect';
            $buttons[] = 'styleselect';
            $buttons[] = 'backcolor';
            $buttons[] = 'newdocument';
            $buttons[] = 'cut';
            $buttons[] = 'copy';
            $buttons[] = 'charmap';
            $buttons[] = 'hr';
            $buttons[] = 'visualaid';
            return $buttons;
        }
        /**
         * Include required files
         */
        function apricot_includes(){
			/*
			 * Add Redux Framework
			 */
            require get_template_directory() . '/admin/admin-init.php';
			/*
			 * Add bootstrap wp-nav()
			 */            
            if (!is_admin()) {
                require get_template_directory() . '/inc/php/bootstrap-walker.php';
            }
			/*
			 * Add files for underscore theme
			 */
            require get_template_directory() . '/inc/php/custom-header.php';
            require get_template_directory() . '/inc/php/template-tags.php';
            require get_template_directory() . '/inc/php/extras.php';
            require get_template_directory() . '/inc/php/customizer.php';
            require get_template_directory() . '/inc/php/jetpack.php';
			/*
			 * Add metabox fields
			 */
            require get_template_directory() . '/inc/php/meta.php';
        }
        /**
         * custom functions NOT hocked
         */
        /* ============ Count view of posts ============ */
        /**
         * get count views of post
         * @author kika <kika.khaled@gmail.com>
         * @global object/string $postID
         * @return string count of views
         */
        function apricot_getPostViews($postID) {
            $count_key = 'post_views_count';
            $count = get_post_meta($postID, $count_key, true);
            if ($count == '') {
                delete_post_meta($postID, $count_key);
                add_post_meta($postID, $count_key, '0');
                $count = 0;
                return $count;
            }
            return $count;
        }
        /**
         * set/update count views of post
         * @author kika <kika.khaled@gmail.com>
         * @global object/int $postID
         */
        function apricot_setPostViews($postID) {
            $count_key = 'post_views_count';
            $count = get_post_meta($postID, $count_key, true);
            if ($count == '') {
                $count = 0;
                delete_post_meta($postID, $count_key);
                add_post_meta($postID, $count_key, '0');
            } else {
                $count++;
                update_post_meta($postID, $count_key, $count);
            }
        }
		/**
		 * custom breadcrumbs
		 * @author kika <kika.khaled@gmail.com>
		 * @global object/int $postID
		 * @global object $wp_query
		 */
		function apricot_custom_breadcrumbs() {
		    //settings
		    $separator = '/';
		    $breadcrums_id = 'breadcrumbs';
		    $breadcrums_class = 'breadcrumbs';
		    $home_title = __('Homepage', 'Apricot');
		    $position = 1;
		    // Get the query & post information
		    global $post, $wp_query;
		    // Do not display on the homepage
		    if (!is_front_page()) {
		        // Build the breadcrums
		        echo '<ul id="' . $breadcrums_id . '" class="' . $breadcrums_class . '"  itemscope itemtype="http://schema.org/BreadcrumbList">';
		        // Home page
		        echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="item-home">';
		        echo '<a itemprop="item" class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '"><span itemprop="name">' . $home_title . '</span></a>';
		        echo '<meta itemprop="position" content="' . $position . '" /></li>';
		        echo '<li class="separator separator-home"> ' . $separator . ' </li>';
		        $position++;
		        if (is_single()) {
		            // If post is a custom post type
		            $post_type = get_post_type();
		            if ($post_type != 'post') {
		                $post_type_object = get_post_type_object($post_type);
		                $post_type_archive = get_post_type_archive_link($post_type);
		                echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="item-cat item-custom-post-type-' . $post_type . '">';
		                echo '<a itemprop="item" class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '"><span itemprop="name">' . $post_type_object->labels->name . '</span></a>';
		                echo '<meta itemprop="position" content="' . $position . '" /></li>';
		                echo '<li class="separator"> ' . $separator . ' </li>';
		                $position++;
		                //check if posthave parent
		                $ancestors = get_post_ancestors($post->ID);
		                if (!empty($ancestors)) {
		                    $ancestors = array_reverse($ancestors);
		                    // Parent post loop
		                    foreach ($ancestors as $ancestor) {
		                        $parents .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="item-parent item-parent-' . $ancestor . '">';
		                        $parents .= '<a itemprop="item" class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '"><span itemprop="name">' . get_the_title($ancestor) . '</span></a>';
		                        $parents .= '<meta itemprop="position" content="' . $position . '" /></li>';
		                        $parents .= '<li class="separator separator-' . $ancestor . '"> ' . $separator . ' </li>';
		                        $position++;
		                    }
		                    echo $parents;
		                }
		            }
		            echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="item-current item-' . $post->ID . '">';
		            echo '<strong itemprop="item" class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '"><span itemprop="name">' . get_the_title() . '</span></strong>';
		            echo '<meta itemprop="position" content="' . $position . '" /></li>';
		        } else if (is_page()) {
		            // Standard page
		            if ($post->post_parent) {
		                // If child page, get parents 
		                $anc = get_post_ancestors($post->ID);
		                // Get parents in the right order
		                $anc = array_reverse($anc);
		                // Parent page loop
		                foreach ($anc as $ancestor) {
		                    $parents .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="item-parent item-parent-' . $ancestor . '">';
		                    $parents .= '<a itemprop="item" class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '"><span itemprop="name">' . get_the_title($ancestor) . '</span></a>';
		                    $parents .= '<meta itemprop="position" content="' . $position . '" /></li>';
		                    $parents .= '<li class="separator separator-' . $ancestor . '"> ' . $separator . ' </li>';
		                    $position++;
		                }
		                // Display parent pages
		                echo $parents;
		            }
		            // Just display current page if not parents
		            echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="item-current item-' . $post->ID . '">';
		            echo '<strong itemprop="item" class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '"><span itemprop="name">' . get_the_title() . '</span></strong>';
		            echo '<meta itemprop="position" content="' . $position . '" /></li>';
		        } elseif (is_post_type_archive()) {
		            echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="item-current">';
		            echo '<strong itemprop="item" class="bread-current"><span itemprop="name">';
		            post_type_archive_title();
		            echo '</span></strong><meta itemprop="position" content="' . $position . '" /></li>';
		        } elseif (is_404()) {
		            // 404 page
		            echo '<li><strong>';
		            _e( 'Error 404', 'Apricot' );
		            echo '</strong></li>';
		        } elseif (is_tax()) {
		            // Display the tax name
		            echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="item-current">';
		            echo '<strong itemprop="item" class="bread-current"><span itemprop="name">' . single_term_title('', false) . '</span></strong>';
		            echo '<meta itemprop="position" content="' . $position . '" /></li>';
		        } else if (is_category()) {
		            // Get parent any categories and create array
		            $get_cat_parents = rtrim(get_category_parents(get_query_var('cat'), true, ','), ',');
		            $cat_parents = explode(',', $get_cat_parents);
		            // Loop through parent categories and store in variable $cat_display
		            array_pop($cat_parents);
		            if (!empty($cat_parents)) {
		                foreach ($cat_parents as $parents) {
		                    $cat_display .= '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="item-cat">' . $parents . '<meta itemprop="position" content="' . $position . '" /></li>';
		                    $cat_display .= '<li class="separator"> ' . $separator . ' </li>';
		                    $position++;
		                }
		                echo $cat_display;
		            }
		            // Category page
		            echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="item-current item-cat">';
		            echo '<strong itemprop="item" class="bread-current bread-cat"><span itemprop="name">' . single_cat_title('', false) . '</span></strong>';
		            echo '<meta itemprop="position" content="' . $position . '" /></li>';
		        } else if (is_tag()) {
		            $term_id = get_query_var('tag_id');
		            // Display the tag name
		            echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="item-current item-tag-' . $term_id . '">';
		            echo '<strong itemprop="item" class="bread-current bread-tag-' . $term_id . '"><span itemprop="name">' . single_tag_title('', false) . '</span></strong>';
		            echo '<meta itemprop="position" content="' . $position . '" /></li>';
		        } elseif (is_day()) {
		            // Year link
		            echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="item-year item-year-' . get_the_time('Y') . '">';
		            echo '<a itemprop="item" class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link(get_the_time('Y')) . '" title="' . get_the_time('Y') . '"><span itemprop="name">' . get_the_time('Y') . ' Archives</span></a>';
		            echo '<meta itemprop="position" content="' . $position . '" /></li>';
		            echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';
		            $position++;
		            // Month link
		            echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="item-month item-month-' . get_the_time('m') . '">';
		            echo '<a itemprop="item" class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '" title="' . get_the_time('M') . '"><span itemprop="name">' . get_the_time('M') . ' Archives</span></a>';
		            echo '<meta itemprop="position" content="' . $position . '" /></li>';
		            echo '<li class="separator separator-' . get_the_time('m') . '"> ' . $separator . ' </li>';
		            $position++;
		            // Day display
		            echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="item-current item-' . get_the_time('j') . '">';
		            echo '<strong itemprop="item" class="bread-current bread-' . get_the_time('j') . '"><span itemprop="name">' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</span></strong>';
		            echo '<meta itemprop="position" content="' . $position . '" /></li>';
		        } else if (is_month()) {
		            // Year link
		            echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="item-year item-year-' . get_the_time('Y') . '">';
		            echo '<a itemprop="item" class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link(get_the_time('Y')) . '" title="' . get_the_time('Y') . '"><span itemprop="name">' . get_the_time('Y') . ' Archives</span></a>';
		            echo '<meta itemprop="position" content="' . $position . '" /></li>';
		            echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';
		            $position++;
		            // Month display
		            echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="item-month item-month-' . get_the_time('m') . '">';
		            echo '<strong itemprop="item" class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '"><span itemprop="name">' . get_the_time('M') . ' Archives</span></strong>';
		            echo '<meta itemprop="position" content="' . $position . '" /></li>';
		        } else if (is_year()) {
		            // Display year archive
		            echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="item-current item-current-' . get_the_time('Y') . '">';
		            echo '<strong itemprop="item" class="bread-current bread-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '"><span itemprop="name">' . get_the_time('Y') . ' Archives</span></strong>';
		            echo '<meta itemprop="position" content="' . $position . '" /></li>';
		        } else if (is_author()) {
		            // Get the author information
		            global $author;
		            $userdata = get_userdata($author);
		            // Display author name
		            echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="item-current item-current-' . $userdata->user_nicename . '">';
		            echo '<strong itemprop="item" class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '"><span itemprop="name">' . 'Author: ' . $userdata->display_name . '</span></strong>';
		            echo '<meta itemprop="position" content="' . $position . '" /></li>';
		        } else if (get_query_var('paged')) {
		            // Paginated archives
		            echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="item-current item-current-' . get_query_var('paged') . '">';
		            echo '<strong itemprop="item" class="bread-current bread-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '"><span itemprop="name">' . __('Page') . ' ' . get_query_var('paged') . '</span></strong>';
		            echo '<meta itemprop="position" content="' . $position . '" /></li>';
		        } else if (is_search()) {
		            // Search results page
		            echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="item-current item-current-' . get_search_query() . '">';
		            echo '<strong itemprop="item" class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '"><span itemprop="name">Search results for: ' . get_search_query() . '</span></strong>';
		            echo '<meta itemprop="position" content="' . $position . '" /></li>';
		        }
		        echo '</ul>';
		    }
		}
	}
	new apricot;
}
