<?php
/**
 * Plugin Name: Boginya Featured Products Slider
 * Description: Elementor widget that shows up to five featured WooCommerce products in a minimalist, auto-playing slider.
 * Version: 1.0.0
 * Author: OpenAI
 * Text Domain: boginya-featured-products
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class Boginya_Featured_Products_Plugin {
    /**
     * Singleton instance.
     *
     * @var Boginya_Featured_Products_Plugin|null
     */
    private static $instance = null;

    /**
     * Plugin version.
     */
    const VERSION = '1.0.0';

    /**
     * Plugin slug for handles.
     */
    const SLUG = 'boginya-featured-products';

    /**
     * Get instance.
     *
     * @return Boginya_Featured_Products_Plugin
     */
    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct() {
        add_action( 'elementor/init', array( $this, 'register_category' ) );
        add_action( 'elementor/widgets/register', array( $this, 'register_widget' ) );
        add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_frontend_scripts' ) );
        add_action( 'elementor/frontend/after_register_styles', array( $this, 'register_frontend_styles' ) );
    }

    /**
     * Register Elementor category for the plugin.
     */
    public function register_category() {
        \Elementor\Plugin::instance()->elements_manager->add_category(
            'boginya-elements',
            array(
                'title' => __( 'Boginya Elements', 'boginya-featured-products' ),
                'icon'  => 'fa fa-plug',
            )
        );
    }

    /**
     * Register Elementor widget if dependencies are ready.
     */
    public function register_widget( $widgets_manager ) {
        if ( ! class_exists( '\\WooCommerce' ) ) {
            return;
        }

        require_once __DIR__ . '/includes/class-boginya-featured-products-widget.php';
        $widgets_manager->register( new \Boginya_Featured_Products_Widget() );
    }

    /**
     * Register scripts used by the widget.
     */
    public function register_frontend_scripts() {
        $base = plugin_dir_url( __FILE__ );

        wp_register_script(
            self::SLUG . '-siema',
            $base . 'assets/js/siema.min.js',
            array(),
            self::VERSION,
            true
        );

        wp_register_script(
            self::SLUG . '-frontend',
            $base . 'assets/js/featured-slider.js',
            array( 'jquery', self::SLUG . '-siema' ),
            self::VERSION,
            true
        );
    }

    /**
     * Register styles used by the widget.
     */
    public function register_frontend_styles() {
        $base = plugin_dir_url( __FILE__ );

        wp_register_style(
            self::SLUG . '-frontend',
            $base . 'assets/css/featured-slider.css',
            array(),
            self::VERSION
        );

        wp_register_style(
            self::SLUG . '-custom',
            $base . 'assets/css/custom.css',
            array( self::SLUG . '-frontend' ),
            self::VERSION
        );
    }
}

Boginya_Featured_Products_Plugin::instance();
