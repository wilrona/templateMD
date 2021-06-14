<?php

use Timber\Timber;
use Timber\Twig_Filter;

include('admin-ui/admin-ui.php');

include('typerocket/init.php');

include('app/init.php');

session_start();
//unset($_SESSION['panier']);

$timber = new Timber();

// Check for Timber
if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});

	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});

	return;
}

// Define paths to Twig templates

Timber::$dirname = array(
	'views',
	'templates'
);

// Define TimeberSite Child Class
class BootsmoothSite extends TimberSite {

	function __construct() {

		// Enable theme support for core WP functionality
//		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
        /* suppression de la barre d'administration sur le template */
        add_filter('show_admin_bar', '__return_false');

        // action a faire pour activer une page d'option de theme
        add_filter('tr_theme_options_page', array($this, 'theme_option_page'));
        add_filter('tr_theme_options_name', array($this, 'theme_option_name'));

        // Limiter l'access a l'espace admin pour les utilisateurs abonnes
        add_action('admin_init', array($this, 'no_admin_access'), 100);

        // Faire des modifications dans l'espace d'admin
        add_action('admin_menu', array($this, 'remove_menus_to_admin'));

		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
        add_action( 'init', array($this, 'allow_origin') );
//		add_action( 'init', array( $this, 'register_post_types' ) );
//		add_action( 'init', array( $this, 'register_taxonomies' ) );

        add_action( 'init', array( $this, 'register_widget_areas' ) );
        add_action( 'init', array( $this, 'custom_login_page' ) );
		add_action('widgets_init', array($this, 'register_widget_TR'));
		add_action( 'init', array( $this, 'register_navigation_menus') );

		// Faire les elements assets
        add_action('wp_enqueue_scripts', array($this, 'wp_asset_frontend'));
        add_action('admin_enqueue_scripts', array($this, 'wp_asset_backend'));

        add_action('admin_head', array($this, 'wp_asset_head_backend'));
        add_action('admin_footer', array($this, 'wp_asset_head_backend'));

        parent::__construct();
	}

	function theme_option_page(){
        return get_template_directory() . '/app/options/init.php';
    }

    function theme_option_name(){
	    return 'options';
    }

    function no_admin_access()
    {
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : home_url('/');
        if (!defined('DOING_AJAX') && current_user_can('subscriber'))
            exit(wp_redirect($redirect));

    }

    function custom_login_page(){

        $login_page  = get_permalink(tr_options_field('options.login_url'));
        $profile_editor = get_permalink(tr_options_field('options.member_url'));

        if(tr_options_field('options.active_login_url')){
            $page_viewed = basename($_SERVER['REQUEST_URI']);

            if(str_contains($page_viewed, 'wp-login.php') && $_SERVER['REQUEST_METHOD'] == 'GET' && !is_user_logged_in()) {
                wp_redirect($login_page);
                exit;
            }
            if(basename($_SERVER['REQUEST_URI']) === basename($login_page) && is_user_logged_in()){
                $user = get_current_user_id();
                if(!user_can($user, 'subscriber')){
                    exit(wp_redirect(home_url('/wp-admin')));
                }else{
                    exit(wp_redirect($profile_editor));
                }
            }
        }
    }

    function remove_menus_to_admin(){
        //	remove_menu_page( 'index.php' );                  //Dashboard
        //	remove_menu_page( 'jetpack' );                    //Jetpack*
//        	remove_menu_page( 'edit.php' );                   //Posts
        //	remove_menu_page('upload.php');                 //Media
        //	remove_menu_page( 'edit.php?post_type=page' );    //Pages
//          remove_menu_page('edit-comments.php');          //Comments
        //	remove_menu_page( 'themes.php' );                 //Appearance
        //	remove_menu_page( 'plugins.php' );                //Plugins
        //	remove_menu_page( 'users.php' );                  //Users
        //	remove_menu_page( 'tools.php' );                  //Tools
        //	remove_menu_page( 'options-general.php' );        //Settings
    }

    function allow_origin(){
        header("Access-Control-Allow-Origin: *");
    }

	function register_post_types() {
		//this is where you can register custom post types
	}

	function register_taxonomies() {
		//this is where you can register custom taxonomies
	}

	function register_navigation_menus() {
		// Register navigation menus
		register_nav_menus(
			array(
				'headerWeb' => 'Menu pour la version web',
				'footer' => 'Menu Footer lien rapide',
                'headerMobileWeb' => 'Menu pour la version web mobile',
                'headerTop' => 'Menu plus haut a gauche',
                'menuTopLogin' => 'Menu plus haut a droite',
                'menuMember' => 'Menu des membres connectés',
                'menuTabMobileApp' => 'Menu pour les tabs de la page d\'accueil de l\'application mobile',
			)
		);
	}

	// register custom context variables
	function add_to_context( $context ) {
		$context['menu_web'] = new TimberMenu('headerWeb');
		$context['footer'] = new TimberMenu('footer');
		$context['menu_mobile_web'] = new TimberMenu('headerMobileWeb');
		$context['menu_top'] = new TimberMenu('headerTop');
		$context['menu_top_login'] = new TimberMenu('menuTopLogin');
		$context['menu_member'] = new TimberMenu('menuMember');
		$context['site'] = $this;
		$context['options'] = tr_options_field('options');
		$context['admin_url'] = admin_url('admin-ajax.php');
		$context['get'] = $_GET;
		$context['post'] = $_POST;

		$context['home_header_widgets'] = Timber::get_widgets( 'Home header' );
		$context['home_une_widgets'] = Timber::get_widgets( 'Home A la une' );
		$context['home_center_widgets'] = Timber::get_widgets( 'Home center 1' );
		$context['home_sidebar_widgets'] = Timber::get_widgets( 'Home sidebar' );
		$context['home_section_area_widgets'] = Timber::get_widgets( 'Home area center' );
		$context['home_section_area_footer_left_widgets'] = Timber::get_widgets( 'Home area footer left' );
		$context['home_section_area_footer_right_widgets'] = Timber::get_widgets( 'Home area footer right' );

		$context['sidebar_widgets'] = Timber::get_widgets( 'Sidebar' );
		$context['footer_right'] = Timber::get_widgets( 'Footer right' );

        $context['top_cat_widget'] = Timber::get_widgets( 'Top category' );
        $context['bottom_cat_widget'] = Timber::get_widgets( 'Bottom category' );

//		$context['breadcrumbs'] = wpd_nav_menu_breadcrumbs(new TimberMenu('headerWeb'));
		$context['breadcrumbs'] = [];
        if(function_exists('yoast_breadcrumb') && !(is_home() || is_front_page())) {
            $context['breadcrumbs'] = get_crumb_array();
        }
		return $context;
	}

	function add_to_twig( $twig ) {
		/* this is where you can add your own functions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter(new Twig_Filter('is_array', function($data){
		    return is_array($data);
        }));
        $twig->addFilter(new Twig_Filter('trim_word', function($data, $num_words = 30){

            $content = $data;
            $content = preg_replace("/<img[^>]+\>/i", " ", $content);
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]>', $content);

            return wp_trim_words($content, $num_words, '...') ;
        }));

        $twig->addFilter(new Twig_Filter('simple_trim_word', function($data, $num_words = 30){
            $content = $data;
            $content = preg_replace("/<img[^>]+\>/i", " ", $content);
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]>', $content);
            return substr($content, 0, $num_words) ;
        }));

		return $twig;
	}

    function register_widget_areas() {
        // Register widget areas
        if ( function_exists('register_sidebar') ) {
            register_sidebar(array(
                    'name' => 'Home header',
                    'id' => 'home_header',
                    'before_widget' => '<div class="home-header-widget">',
                    'after_widget' => '</div>',
                    'before_title' => '<div class="uk-text-large">',
                    'after_title' => '</div>',
                )
            );

            register_sidebar(array(
                    'name' => 'Home A la une',
                    'id' => 'home_a_la_une',
                    'before_widget' => '<div class="home-une-widget col-lg-12">',
                    'after_widget' => '</div>',
                    'before_title' => '<div class="uk-text-large">',
                    'after_title' => '</div>',
                )
            );


            register_sidebar(array(
                    'name' => 'Home center 1',
                    'id' => 'home_center_1',
                    'before_widget' => '<div class="home-center-one-widget">',
                    'after_widget' => '</div>',
                    'before_title' => '<div class="uk-text-large">',
                    'after_title' => '</div>',
                )
            );

            register_sidebar(array(
                    'name' => 'Home sidebar',
                    'id' => 'home_sidebar',
                    'before_widget' => '<div class="home-sidebar-widget" style="margin-bottom: 30px;">',
                    'after_widget' => '</div>',
                    'before_title' => '<div class="uk-text-large">',
                    'after_title' => '</div>',
                )
            );

            register_sidebar(array(
                    'name' => 'Home area center',
                    'id' => 'home_area_center',
                    'before_widget' => '<div class="home-section-center-widget">',
                    'after_widget' => '</div>',
                    'before_title' => '<div class="uk-text-large">',
                    'after_title' => '</div>',
                )
            );

            register_sidebar(array(
                    'name' => 'Home area footer left',
                    'id' => 'home_area_footer_left',
                    'before_widget' => '<div class="home-area-footer-left-widget">',
                    'after_widget' => '</div>',
                    'before_title' => '<div class="uk-text-large">',
                    'after_title' => '</div>'
                )
            );

            register_sidebar(array(
                    'name' => 'Home area footer right',
                    'id' => 'home_area_footer_right',
                    'before_widget' => '<div class="home-area-footer-right-widget">',
                    'after_widget' => '</div>',
                    'before_title' => '<div class="uk-text-large">',
                    'after_title' => '</div>'
                )
            );

            register_sidebar(array(
                    'name' => 'Footer right',
                    'id' => 'footer_right',
                    'before_widget' => '<div class="footer-right-widget">',
                    'after_widget' => '</div>',
                    'before_title' => '<div class="uk-text-large">',
                    'after_title' => '</div>'
                )
            );

            register_sidebar(array(
                    'name' => 'Sidebar',
                    'id' => 'sidebar',
                    'before_widget' => '<div class="sidebar-widget">',
                    'after_widget' => '</div>',
                    'before_title' => '<div class="uk-text-large">',
                    'after_title' => '</div>'
                )
            );

            register_sidebar(array(
                    'name' => 'Top category',
                    'id' => 'top_category',
                    'before_widget' => '<div class="top-category-widget">',
                    'after_widget' => '</div>',
                    'before_title' => '<div class="uk-text-large">',
                    'after_title' => '</div>'
                )
            );

            register_sidebar(array(
                    'name' => 'Bottom category',
                    'id' => 'bottom_category',
                    'before_widget' => '<div class="bottom-category-widget">',
                    'after_widget' => '</div>',
                    'before_title' => '<div class="uk-text-large">',
                    'after_title' => '</div>'
                )
            );
        }
    }

    function register_widget_TR(){
        register_widget('FlashInfo_Widget');
        register_widget('LastNew_Widget');
        register_widget('BestNews_Widget');
        register_widget('Magazine_Widget');
        register_widget('Carousel_Widget');
        register_widget('MultiCategory_Widget');
        register_widget('Recommended_Widget');
        register_widget('FromCategory_Widget');
        register_widget('PostPopular_Widget');
    }

    function wp_asset_frontend(){

	    // Ajout des elements en JavaScript
        wp_register_script('lazysizes', get_stylesheet_directory_uri() . '/js/lazysizes.min.js', '', '1', false);
        wp_register_script('jquery', get_stylesheet_directory_uri() . '/js/jquery.min.js', '', '1', true);
        wp_register_script('bootstrap', get_stylesheet_directory_uri() . '/js/bootstrap.min.js', '', '1', true);
        wp_register_script('easing', get_stylesheet_directory_uri() . '/js/easing.min.js', '', '1', true);
        wp_register_script('owl-carousel', get_stylesheet_directory_uri() . '/js/owl-carousel.min.js', '', '1', true);
        wp_register_script('flickity', get_stylesheet_directory_uri() . '/js/flickity.pkgd.min.js', '', '1', true);
        wp_register_script('jquery.newsTicker', get_stylesheet_directory_uri() . '/js/jquery.newsTicker.min.js', '', '1', true);
        wp_register_script('jquery.sticky-kit', get_stylesheet_directory_uri() . '/js/jquery.sticky-kit.min.js', '', '1', true);
        wp_register_script('modernizr', get_stylesheet_directory_uri() . '/js/modernizr.min.js', '', '1', true);
        wp_register_script('scripts', get_stylesheet_directory_uri() . '/js/scripts.js', '', '1', true);




        wp_enqueue_script('lazysizes');
        wp_enqueue_script('jquery');
        wp_enqueue_script('bootstrap');
        wp_enqueue_script('easing');
        wp_enqueue_script('owl-carousel');
        wp_enqueue_script('flickity');
        wp_enqueue_script('jquery.sticky-kit');
        wp_enqueue_script('jquery.newsTicker');
        wp_enqueue_script('modernizr');
        wp_enqueue_script('scripts');



        // Ajout des elements en CSS
        wp_register_style('bootstrap', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', false, '1.0', 'all');
        wp_register_style('fontIcon', get_stylesheet_directory_uri() . '/css/font-icons.css', false, '1.0', 'all');
        wp_register_style('style', get_stylesheet_directory_uri() . '/css/style.css', false, '1.0', 'all');
        wp_register_style('color_red', get_stylesheet_directory_uri() . '/css/colors/red.css', false, '1.0', 'all');

        wp_enqueue_style('bootstrap');
        wp_enqueue_style('fontIcon');
        wp_enqueue_style('style');
        wp_enqueue_style('color_red');

    }

    function wp_asset_backend(){

	    // Ajout des elements css
        wp_register_style('datepickercss', get_stylesheet_directory_uri() . '/admin_css/datepicker.css', false, '1.0', 'all');
        wp_register_style('select2css', get_stylesheet_directory_uri() . '/admin_css/select2.css', false, '1.0', 'all');
        wp_register_style('uikit', get_stylesheet_directory_uri() . '/admin_css/uikit.css', '', '', 'all');
        wp_register_style('admin', get_stylesheet_directory_uri() . '/admin_css/admin.css', '', '', 'all');

        wp_enqueue_style('uikit');
        wp_enqueue_style('select2css');
        wp_enqueue_style('datepickercss');
        wp_enqueue_style('admin');

        // AJout des elements js
        wp_register_script('select2', get_stylesheet_directory_uri() . '/admin_js/select2.min.js', '', '1.0', true);
        wp_register_script('uikit', get_stylesheet_directory_uri() . '/admin_js/uikit.js', '', '1', true);
        wp_register_script('uikit-icon', get_stylesheet_directory_uri() . '/admin_js/uikit-icons.js', '', '1', true);
        wp_register_script('datepicker', get_stylesheet_directory_uri() . '/admin_js/datepicker.js', '', '1.0', true);
        wp_register_script('datepicker.fr', get_stylesheet_directory_uri() . '/admin_js/datepicker.fr-FR.js', '', '1.0', true);
        wp_register_script('repeater', get_stylesheet_directory_uri() . '/admin_js/jquery.repeater.js', array('jquery'), '1.0', true);


        wp_enqueue_script('uikit');
        wp_enqueue_script('uikit-icon');
        wp_enqueue_script('select2');
        wp_enqueue_script('datepicker');
        wp_enqueue_script('datepicker.fr');
        wp_enqueue_script('repeater');

    }

    function wp_asset_head_backend(){
        wp_register_script('head-backend', get_stylesheet_directory_uri() . '/admin_js/head_backend.js', '', '1', true);
        wp_enqueue_script('head-backend');
    }

    function wp_asset_footer_backend(){
        wp_register_script('footer-backend', get_stylesheet_directory_uri() . '/admin_js/footer_backend.js', '', '1', true);
        wp_enqueue_script('footer-backend');
    }

}

new BootsmoothSite();
