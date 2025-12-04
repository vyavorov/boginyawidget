<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Boginya_Featured_Products_Widget extends Widget_Base {
    public function get_name() {
        return 'boginya-featured-products';
    }

    public function get_title() {
        return __( 'Featured Products Slider', 'boginya-featured-products' );
    }

    public function get_icon() {
        return 'eicon-slider-album';
    }

    public function get_categories() {
        return array( 'boginya-elements' );
    }

    public function get_style_depends() {
        return array( Boginya_Featured_Products_Plugin::SLUG . '-frontend', Boginya_Featured_Products_Plugin::SLUG . '-custom' );
    }

    public function get_script_depends() {
        return array( Boginya_Featured_Products_Plugin::SLUG . '-frontend' );
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            array(
                'label' => __( 'Content', 'boginya-featured-products' ),
            )
        );

        $this->add_control(
            'product_count',
            array(
                'label'   => __( 'Products to show', 'boginya-featured-products' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 5,
                'min'     => 1,
                'max'     => 5,
            )
        );

        $this->add_control(
            'slides_to_show',
            array(
                'label'   => __( 'Slides in view', 'boginya-featured-products' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 3,
                'min'     => 1,
                'max'     => 5,
                'description' => __( 'Maximum visible items on wide screens. Will adapt on smaller screens.', 'boginya-featured-products' ),
            )
        );

        $this->add_control(
            'autoplay',
            array(
                'label'        => __( 'Autoplay', 'boginya-featured-products' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'autoplay_speed',
            array(
                'label'     => __( 'Autoplay speed (ms)', 'boginya-featured-products' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 4500,
                'min'       => 1500,
                'step'      => 500,
                'condition' => array( 'autoplay' => 'yes' ),
            )
        );

        $this->add_control(
            'transition_speed',
            array(
                'label'   => __( 'Transition speed (ms)', 'boginya-featured-products' ),
                'type'    => Controls_Manager::NUMBER,
                'default' => 450,
                'min'     => 150,
                'step'    => 50,
            )
        );

        $this->add_control(
            'show_price',
            array(
                'label'        => __( 'Show price', 'boginya-featured-products' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'show_add_to_cart',
            array(
                'label'        => __( 'Show add to cart', 'boginya-featured-products' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'show_dots',
            array(
                'label'        => __( 'Show dots', 'boginya-featured-products' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'show_arrows',
            array(
                'label'        => __( 'Show arrows', 'boginya-featured-products' ),
                'type'         => Controls_Manager::SWITCHER,
                'default'      => 'yes',
                'return_value' => 'yes',
            )
        );

        $this->add_control(
            'custom_css_class',
            array(
                'label'       => __( 'Custom CSS class', 'boginya-featured-products' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __( 'Optional wrapper class', 'boginya-featured-products' ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            array(
                'label' => __( 'Product Card', 'boginya-featured-products' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'title_typography',
                'label'    => __( 'Title Typography', 'boginya-featured-products' ),
                'selector' => '{{WRAPPER}} .boginya-card__title',
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'     => 'card_border',
                'selector' => '{{WRAPPER}} .boginya-card',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'card_shadow',
                'selector' => '{{WRAPPER}} .boginya-card',
            )
        );

        $this->add_control(
            'card_padding',
            array(
                'label'      => __( 'Card Padding', 'boginya-featured-products' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .boginya-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'dot_color',
            array(
                'label'     => __( 'Dot Color', 'boginya-featured-products' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .boginya-dots button' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'arrow_color',
            array(
                'label'     => __( 'Arrow Color', 'boginya-featured-products' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .boginya-arrow' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $limit    = ! empty( $settings['product_count'] ) ? absint( $settings['product_count'] ) : 5;
        $limit    = min( 5, max( 1, $limit ) );

        $slides_to_show = ! empty( $settings['slides_to_show'] ) ? absint( $settings['slides_to_show'] ) : 3;
        $slides_to_show = min( 5, max( 1, $slides_to_show ) );

        $products = wc_get_products(
            array(
                'limit'    => $limit,
                'featured' => true,
                'status'   => 'publish',
            )
        );

        if ( empty( $products ) ) {
            echo '<div class="boginya-widget__empty">' . esc_html__( 'No featured products found.', 'boginya-featured-products' ) . '</div>';
            return;
        }

        $wrapper_classes = array( 'boginya-featured-products' );
        if ( ! empty( $settings['custom_css_class'] ) ) {
            $wrapper_classes[] = sanitize_html_class( $settings['custom_css_class'] );
        }

        $data = array(
            'autoplay'        => ( ! empty( $settings['autoplay'] ) && 'yes' === $settings['autoplay'] ) ? 'true' : 'false',
            'autoplaySpeed'   => ! empty( $settings['autoplay_speed'] ) ? absint( $settings['autoplay_speed'] ) : 4500,
            'transitionSpeed' => ! empty( $settings['transition_speed'] ) ? absint( $settings['transition_speed'] ) : 450,
            'showDots'        => ( ! empty( $settings['show_dots'] ) && 'yes' === $settings['show_dots'] ) ? 'true' : 'false',
            'showArrows'      => ( ! empty( $settings['show_arrows'] ) && 'yes' === $settings['show_arrows'] ) ? 'true' : 'false',
            'slidesToShow'    => $slides_to_show,
        );

        echo '<div class="' . esc_attr( implode( ' ', $wrapper_classes ) ) . '" data-settings="' . esc_attr( wp_json_encode( $data ) ) . '">';

        if ( ! empty( $data['showArrows'] ) && 'true' === $data['showArrows'] ) {
            echo '<button class="boginya-arrow boginya-arrow--prev" type="button" aria-label="' . esc_attr__( 'Previous', 'boginya-featured-products' ) . '"><span>&larr;</span></button>';
        }

        echo '<div class="boginya-slider-shell">';
        echo '<div class="boginya-slider">';
        echo '<div class="boginya-slider__track">';

        foreach ( $products as $product ) {
            $product_id = $product->get_id();
            $permalink  = get_permalink( $product_id );
            $image      = $product->get_image( 'medium', array( 'loading' => 'lazy' ) );
            $price_html = $product->get_price_html();

            echo '<div class="boginya-slide">';
            echo '<div class="boginya-card">';

            if ( $image ) {
                echo '<a href="' . esc_url( $permalink ) . '" class="boginya-card__thumb">' . wp_kses_post( $image ) . '</a>';
            }

            echo '<h3 class="boginya-card__title"><a href="' . esc_url( $permalink ) . '">' . esc_html( $product->get_name() ) . '</a></h3>';

            if ( ! empty( $settings['show_price'] ) && 'yes' === $settings['show_price'] && $price_html ) {
                echo '<div class="boginya-card__price">' . wp_kses_post( $price_html ) . '</div>';
            }

            if ( ! empty( $settings['show_add_to_cart'] ) && 'yes' === $settings['show_add_to_cart'] ) {
                echo '<div class="boginya-card__cta">' . do_shortcode( '[add_to_cart id="' . $product_id . '" show_price="false" style=""]' ) . '</div>';
            }

            echo '</div>';
            echo '</div>';
        }

        echo '</div>';
        echo '</div>';

        if ( ! empty( $data['showDots'] ) && 'true' === $data['showDots'] ) {
            echo '<div class="boginya-dots" aria-hidden="true"></div>';
        }

        echo '</div>';

        if ( ! empty( $data['showArrows'] ) && 'true' === $data['showArrows'] ) {
            echo '<button class="boginya-arrow boginya-arrow--next" type="button" aria-label="' . esc_attr__( 'Next', 'boginya-featured-products' ) . '"><span>&rarr;</span></button>';
        }

        echo '</div>';
    }
}
