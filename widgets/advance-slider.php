<?php

if( !defined( 'ABSPATH' ) )  {
	exit; // Exit if accessed directly.
}

require_once( QUANTUM_DIR . "functions.php" );

define( "VESRION", time() );

class Advance_slider extends \Elementor\Widget_Base  {
   public $slides_templates = [], $slides_template_names = [];
   ///not initializing here because it giving me some error
   public $template_paths = [];

   public function __construct( $data = [], $args = null )  {
      parent::__construct( $data, $args );
      wp_register_script( 'advance-slider-script', plugins_url( 'assets/js/advance-slider.js', __DIR__ ), ['jquery', 'elementor-frontend'], VESRION, true );
      wp_register_style( 'advance-slider-styles', plugins_url( 'assets/css/advance-slider.css', __DIR__ ), null, VESRION );

      $this->template_paths = [QUANTUM_DIR . "/templates/advance-slider", get_stylesheet_directory() . "/quantum-addons/advance-slider"];
      $this->init_templates();
   }

   public function init_templates()  {
      $this->slides_templates = quantum_addons_get_templates( $this->template_paths, "html" );

      foreach( $this->slides_templates as $template_key_name => $_ )  {
         $template_name = ucfirst( preg_replace( '/[-]/', " " ,$template_key_name ) );
         $this->slides_template_names[$template_key_name] = $template_name;
      }
   }

   public function get_name()  {
      return 'Advance_slider';
   }

   public function get_title()  {
      return esc_html__( 'Advance Slider', 'quantum-addons' );
   }

   public function get_icon()  {
      return 'eicon-post-slider';
   }

   public function get_custom_help_url() {
      return 'https://github.com/abhy12/quantum-addons';
   }

   public function get_categories()  {
      return ['basic'];
   }

   public function get_keywords()  {
      return ['slider, carousel'];
   }

   public function get_script_depends()  {
      return ['advance-slider-script'];
   }

   public function get_style_depends()  {
      return ['advance-slider-styles'];
   }

   protected function register_controls()  {
      //start with tab section
      $this->start_controls_section(
         'section_container',
         [
            'label' => esc_html__( 'Content', 'quantum-addons' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
         ]
      );

      $this->add_control(
         'select_template',
         [
            'label'   => esc_html__( 'Select Template', 'quantum-addons' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => $this->slides_template_names,
            'default' => 'default'
         ],
      );

      $repeater = new \Elementor\Repeater();

      $repeater->add_control(
         'image',
         [
            'label'   => esc_html__( 'Image', 'quantum-addons' ),
            'type'    => \Elementor\Controls_Manager::MEDIA,
            'default' => [
               'url' => \Elementor\Utils::get_placeholder_image_src(),
            ],
         ]
      );

      $repeater->add_control(
         'title',
         [
            'label'       => esc_html__( 'Heading', 'quantum-addons' ),
            'type'        => \Elementor\Controls_Manager::TEXT,
            'placeholder' =>  esc_html__( 'Write someting...', 'quantum-addons' ),
         ]
      );

      $repeater->add_control(
         'content',
         [
            'label'       => esc_html__( 'Paragraph', 'quantum-addons' ),
            'type'        => \Elementor\Controls_Manager::TEXTAREA,
            'placeholder' => esc_html__( 'Write someting...', 'quantum-addons' ),
         ]
      );

      $repeater->add_control(
         'additional_text',
         [
            'label'   => esc_html__( 'Additonal Content', 'quantum-addons' ),
            'type'    => \Elementor\Controls_Manager::WYSIWYG,
            'default' => esc_html__( 'additional content...', 'quantum-addons' ),
         ]
      );

      $this->add_control(
         'slides',
         [
            'label'   => esc_html__( 'Slides', 'quantum-addons' ),
            'type'    => \Elementor\Controls_Manager::REPEATER,
            'fields'  => $repeater->get_controls(),
            'default' => [
               [
                  'title'   => 'Slide #',
                  'content' => "Lorem ipsum, dolor sit amet consectetur adipisicing elit. Delectus earum fuga nam.",
               ],
            ]
         ]
      );

      $this->end_controls_section();

      $this->start_controls_section(
         'slider-options',
         [
            'label' => esc_html__( 'Slider Options', 'quantum-addons' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
         ]
      );

      $this->add_responsive_control(
         'slide_per_view',
         [
            'label'              => esc_html__( 'Show Slides', 'quantum-addons' ),
            'type'               => \Elementor\Controls_Manager::NUMBER,
            'frontend_available' => true,
            'desktop_default'    => 3,
            'tablet_default'     => 2,
            'mobile_default'     => 1,
         ]
      );

      $this->add_responsive_control(
         'slide_per_group',
         [
            'label'   => esc_html__( 'Slides per Swipe', 'quantum-addons' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => [
               '1' => '1',
               '2' => '2',
               '3' => '3',
               '4' => '4',
               '5' => '5',
               '6' => '6',
               '7' => '7',
               '8' => '8',
               '9' => '9',
            ],
            'desktop_default'    => '1',
            'tablet_default'     => '1',
            'mobile_default'     => '1',
            'frontend_available' => true,
         ]
      );

      $this->add_responsive_control(
         'center_slide',
         [
            'label'   => esc_html__( 'Slides Centered', 'quantum-addons' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => [
               '1' => 'Yes',
               '0' => 'No',
            ],
            'desktop_default'    => '1',
            'tablet_default'     => '1',
            'mobile_default'     => '1',
            'default'            => '1',
            'frontend_available' => true,
         ]
      );

      $this->add_responsive_control(
         'space_between',
         [
            'label'              => esc_html__( 'Space Between Slides', 'quantum-addons' ),
            'type'               => \Elementor\Controls_Manager::NUMBER,
            'desktop_default'    => 30,
            'tablet_default'     => 20,
            'mobile_default'     => 10,
            'frontend_available' => true,
         ]
      );

      $this->add_control(
         'loop',
         [
            'label'              => esc_html__( 'Infinite Slides', 'quantum-addons' ),
            'type'               => \Elementor\Controls_Manager::SWITCHER,
            'label_on'           => esc_html__( 'Yes', 'quantum-addons' ),
            'label_off'          => esc_html__( 'No', 'quantum-addons' ),
            'return_value'       => '1',
            'default'            => '1',
            'frontend_available' => true,
         ]
      );

      $this->end_controls_section();

      $this->start_controls_section(
         'navigation_controls_options',
         [
            'label' => esc_html__( 'Navigation Controls', 'quantum-addons' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
         ]
      );

      $this->add_responsive_control(
         'show_navigation_next_prev_controls',
         [
            'label'           => esc_html__( 'Next & Previous Controls', 'quantum-addons' ),
            'type'            => \ELEMENTOR\Controls_Manager::SELECT,
            'default'         => '',
            'options'         => [
               ''     => esc_html__( 'Show', 'quantum-addons' ),
               'none' => esc_html__( 'Hide', 'quantum-addons' ),
            ],
            'selectors'       => [
               '{{WRAPPER}} .quantum-swiper-container .quantum-slider-btn' => 'display: {{VALUE}};',
            ],
            'condition'       => [
               'is_custom_navigation_buttons' => ''
            ],
         ]
      );

      $this->add_responsive_control(
         'navigation_buttons_icon_size',
         [
            'label'      => 'Icon Size',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'rem', 'em'],
            'default'    => [
               'unit' => 'rem',
               'size' => '1.2'
            ],
            'selectors'  => [
               '{{WRAPPER}} .quantum-slider-btn' => 'font-size: {{SIZE}}{{UNIT}};'
            ],
            'condition' => [
               'is_custom_navigation_buttons' => ''
            ]
         ]
      );

      $this->add_control(
         'navigation_control_prev_icon',
         [
            'label'     => esc_html__( 'Previous Control Icon', 'quantum-addons' ),
            'type'      => \ELEMENTOR\Controls_Manager::ICONS,
            'skin'      => 'media',
            'default'   => [
               'value'   => 'fas fa-chevron-left',
               'library' => 'fa-solid',
            ],
            'condition' => [
               'is_custom_navigation_buttons' => ''
            ]
         ]
      );

      $this->add_control(
         'navigation_control_next_icon',
         [
            'label'     => esc_html__( 'Next Control Icon', 'quantum-addons' ),
            'type'      => \ELEMENTOR\Controls_Manager::ICONS,
            'skin'      => 'media',
            'default'   => [
               'value'   => 'fas fa-chevron-right',
               'library' => 'fa-solid',
            ],
            'condition' => [
               'is_custom_navigation_buttons' => ''
            ]
         ]
      );

      $this->add_control(
         'is_custom_navigation_buttons',
         [
            'label'     => esc_html__( 'Custom Controls', 'quantum-addons' ),
            'type'      => \ELEMENTOR\Controls_Manager::SWITCHER,
            'label_on'  => 'Custom',
            'label_off' => 'Default',
            'default'   => '',
         ]
      );

      $this->add_control(
         'custom_navigation_prev_button_selector',
         [
            'label'              => esc_html__( 'Previous Control Selector', 'quantum-addons' ),
            'type'               => \ELEMENTOR\Controls_Manager::TEXT,
            'description'        => esc_html__( 'Input CSS selector eg: .custom-prev-btn or #custom-prev-btn', 'quantum-addons' ),
            'placeholder'        => esc_html__( '.custom-prev-btn', 'quantum-addons' ),
            'label_block'        => true,
            'condition'          => [
               'is_custom_navigation_buttons' => 'yes'
            ],
            'frontend_available' => true,
         ]
      );

      $this->add_control(
         'custom_navigation_next_button_selector',
         [
            'label'              => esc_html__( 'Next Control Selector', 'quantum-addons' ),
            'type'               => \ELEMENTOR\Controls_Manager::TEXT,
            'description'        => esc_html__( 'Input CSS selector eg: .custom-next-btn or #custom-next-btn', 'quantum-addons' ),
            'placeholder'        => esc_html__( '.custom-next-btn', 'quantum-addons' ),
            'label_block'        => true,
            'condition'          => [
               'is_custom_navigation_buttons' => 'yes'
            ],
            'frontend_available' => true,
         ]
      );

      $this->end_controls_section();

      $this->start_controls_section(
         'pagination_options',
         [
            'label' => esc_html__( 'Pagination', 'quantum-addons' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
         ]
      );

      $this->add_responsive_control(
         'show_pagination',
         [
            'label'           => esc_html__( 'Pagination', 'quantum-addons' ),
            'type'            => \ELEMENTOR\Controls_Manager::SELECT,
            'default'         => '',
            'options'         => [
               ''     => esc_html__( 'Show', 'quantum-addons' ),
               'none' => esc_html__( 'Hide', 'quantum-addons' ),
            ],
            'selectors'       => [
               '{{WRAPPER}} .quantum-swiper-container .quantum-slider-pagination' => 'display: {{VALUE}};',
            ],
            'condition'       => [
               'is_custom_pagination' => ''
            ]
         ]
      );

      $this->add_control(
         'is_custom_pagination',
         [
            'label'     => esc_html__( 'Custom Pagination', 'quantum-addons' ),
            'type'      => \ELEMENTOR\Controls_Manager::SWITCHER,
            'label_on'  => 'Custom',
            'label_off' => 'Default',
            'default'   => '',
         ]
      );

      $this->add_control(
         'custom_pagination_selector',
         [
            'label'              => esc_html__( 'Custom Pagination Selector', 'quantum-addons' ),
            'type'               => \ELEMENTOR\Controls_Manager::TEXT,
            'description'        => esc_html__( 'Input CSS selector eg: .custom-pagination or #custom-pagination', 'quantum-addons' ),
            'placeholder'        => esc_html__( '.custom-pagination', 'quantum-addons' ),
            'label_block'        => true,
            'condition'          => [
               'is_custom_pagination' => 'yes'
            ],
            'frontend_available' => true,
         ]
      );

      $this->add_control(
         'is_pagination_clickable',
         [
            'label'              => esc_html__( 'Clickable', 'quantum-addons' ),
            'type'               => \ELEMENTOR\Controls_Manager::SWITCHER,
            'separator'          => 'before',
            'label_on'           => 'Yes',
            'label_off'          => 'No',
            'default'            => 'Yes',
            'frontend_available' => true,
         ]
      );

      $this->add_control(
         'pagination_type',
         [
            'label'              => esc_html__( 'Pagination type', 'quantum-addons' ),
            'type'               => \ELEMENTOR\Controls_Manager::SELECT,
            'default'            => 'bullets',
            'options'            => [
               'bullets'     => esc_html__( 'Dots', 'quantum-addons' ),
               'fraction'    => esc_html__( 'Numbers', 'quantum-addons' ),
               'progressbar' => esc_html__( 'Progressbar', 'quantum-addons' ),
            ],
            'frontend_available' => true,
         ]
      );

      $this->end_controls_section();

      $this->start_controls_section(
         'pagination_scrollbar_options',
         [
            'label' => esc_html__( 'Scrollbar', 'quantum-addons' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
         ]
      );

      $this->add_responsive_control(
         'show_pagination_scrollbar',
         [
            'label'           => esc_html__( 'Pagination Scrollbar', 'quantum-addons' ),
            'type'            => \ELEMENTOR\Controls_Manager::SELECT,
            'default'         => '',
            'options'         => [
               ''     => esc_html__( 'Show', 'quantum-addons' ),
               'none' => esc_html__( 'Hide', 'quantum-addons' ),
            ],
            'selectors'       => [
               '{{WRAPPER}} .quantum-swiper-container .quantum-slider-scrollbar' => 'display: {{VALUE}};',
            ],
            'condition'       => [
               'is_custom_scrollbar' => ''
            ]
         ]
      );

      $this->add_control(
         'is_custom_scrollbar',
         [
            'label'     => esc_html__( 'Custom Scrollbar', 'quantum-addons' ),
            'type'      => \ELEMENTOR\Controls_Manager::SWITCHER,
            'label_on'  => 'Custom',
            'label_off' => 'Default',
            'default'   => '',
         ]
      );

      $this->add_control(
         'custom_scrollbar_selector',
         [
            'label'              => esc_html__( 'Custom Scrollbar Selector', 'quantum-addons' ),
            'type'               => \ELEMENTOR\Controls_Manager::TEXT,
            'description'        => esc_html__( 'Input CSS selector eg: .custom-scrollbar or #custom-scrollbar', 'quantum-addons' ),
            'placeholder'        => esc_html__( '.custom-scrollbar', 'quantum-addons' ),
            'label_block'        => true,
            'condition'          => [
               'is_custom_scrollbar' => 'yes'
            ],
            'frontend_available' => true,
         ]
      );

      $this->add_control(
         'is_scrollbar_draggable',
         [
            'label'              => esc_html__( 'Draggable', 'quantum-addons' ),
            'type'               => \ELEMENTOR\Controls_Manager::SWITCHER,
            'separator'          => 'before',
            'label_on'           => 'Yes',
            'label_off'          => 'No',
            'default'            => 'Yes',
            'frontend_available' => true,
         ]
      );

      $this->end_controls_section();

      $this->start_controls_section(
         'container_style',
         [
            'label' => esc_html__( 'Slides Container', 'quantum-addons' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
         ]
      );

      $this->add_control(
         'container_overflow',
         [
            'label'     => esc_html__( 'Overflow', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::SELECT,
            'default'   => 'hidden',
            'options'   => [
               'hidden'  => 'Hidden',
               'visible' => 'Visible',
            ],
            'selectors' => [
               '{{WRAPPER}} .quantum-swiper-container' => 'overflow: {{VALUE}}'
            ],
         ]
      );

      $this->add_responsive_control(
         'container_padding',
         [
            'label'       => esc_html__( 'Padding', 'quantum-addons' ),
            'type'        => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units'  => ['px', 'rem' ,'em', '%'],
            'description' => esc_html__( 'Note: if you change padding you may want to reload the browser (if slider not working properly)', 'quantum-addons' ),
            'selectors'   => [
               '{{WRAPPER}} .quantum-swiper-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
         ]
      );

      $this->add_responsive_control(
         'container_margin',
         [
            'label'      => esc_html__( 'Margin', 'quantum-addons' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'rem' ,'em', '%'],
            'selectors'  => [
               '{{WRAPPER}} .quantum-swiper-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
         ]
      );

      $this->end_controls_section();

      $this->start_controls_section(
         'slide_style',
         [
            'label' => esc_html__( 'Slide', 'quantum-addons' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
         ]
      );

      $this->add_group_control(
         \Elementor\Group_Control_Border::get_type(),
         [
            'name'     => 'border',
            'label'    => esc_html__( 'Border', 'quantum-addons' ),
            'selector' => '{{WRAPPER}} .quantum-slide',
         ]
      );

      $this->add_group_control(
         \Elementor\Group_Control_Box_Shadow::get_type(),
         [
            'name'     => 'box_shadow',
            'label'    => esc_html__( 'Box Shadow', 'quantum-addons' ),
            'selector' => '{{WRAPPER}} .quantum-slide',
         ]
      );

      $this->add_group_control(
         \Elementor\Group_Control_Background::get_type(),
         [
            'name'     => 'background',
            'label'    => esc_html__( 'Background', 'quantum-addons' ),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .quantum-slide',
         ]
      );

      $this->add_responsive_control(
         'slide_padding',
         [
            'label'      => esc_html__( 'Padding', 'quantum-addons' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'rem' ,'em', '%'],
            'selectors'  => [
               '{{WRAPPER}} .quantum-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
         ]
      );

      $this->end_controls_section();

      $this->start_controls_section(
         'image_style',
         [
            'label' => 'Image',
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
         ]
      );

      $this->add_control(
         'image_width',
         [
            'label'      => 'Image Width',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'rem', 'em'],
            'default'    => [
               'unit' => '%',
               'size' => '100'
            ],
            'selectors'  => [
               '{{WRAPPER}} .slider-image' => 'width: {{SIZE}}{{UNIT}};'
            ]
         ]
      );

      $this->add_control(
         'image_height',
         [
            'label'     => esc_html__( 'Image Height', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', '%', 'rem', 'em'],
            'default'    => [
               'unit' => 'px',
               'size' => '250'
            ],
            'selectors' => [
               '{{WRAPPER}} .slider-image' => 'height: {{SIZE}}{{UNIT}};'
            ],
         ]
      );

      $this->add_control(
         'object-fit',
         [
            'label'     => esc_html__( 'Object Fit', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::SELECT,
            'options'   => [
               'cover'      => 'Cover',
               'contain'    => 'Contain',
               'fill'       => 'Fill',
               'none'       => 'None',
               'scale-down' => 'Scale down'
            ],
            'default'   => 'cover',
            'selectors' => [
               '{{WRAPPER}} .slider-image'  => 'object-fit: {{VALUE}}'
            ],
            'condition' => [
               'image_height!' => '',
            ],
         ]
      );

      $this->end_controls_section();

      $this->start_controls_section(
         'content_section',
         [
            'label' => esc_html__( 'Slide Content Container', 'quantum-addons' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
         ]
      );

      $this->add_group_control(
         \Elementor\Group_Control_Background::get_type(),
         [
            'name'     => 'content_background',
            'label'    => esc_html__( 'Background', 'quantum-addons' ),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .el-quantum-content-container',
         ]
      );

      $this->add_responsive_control(
         'content_padding',
         [
            'label'      => esc_html__( 'Padding', 'quantum-addons' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'rem' ,'em', '%'],
            'selectors'  => [
               '{{WRAPPER}} .el-quantum-content-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
         ]
      );

      $this->add_responsive_control(
         'content_margin',
         [
            'label'      => esc_html__( 'Margin', 'quantum-addons' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'rem' ,'em', '%'],
            'selectors'  => [
               '{{WRAPPER}} .el-quantum-content-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
         ]
      );

      $this->add_group_control(
         \Elementor\Group_Control_Border::get_type(),
         [
            'name'     => 'content-cotainer-border',
            'label'    => esc_html__( 'Border', 'quantum-addons' ),
            'selector' => '{{WRAPPER}} .el-quantum-content-container',
         ]
      );

      $this->end_controls_section();

      $this->start_controls_section(
         'heading_style',
         [
            'label' => esc_html__( 'Heading', 'quantum-addons' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
         ]
      );

      $this->add_group_control(
         \Elementor\Group_Control_Typography::get_type(),
         [
            'label'   => esc_html__( 'Typrography', 'quantum-addons' ),
            'name'     => 'content_typography',
            'selector' => '{{WRAPPER}} .el-quantum-title',
         ]
      );

      $this->start_controls_tabs(
         'heading_styles'
      );

      $this->start_controls_tab(
         'heading_style_normal',
         [
            'label' => esc_html__( 'Normal', 'quantum-addons' ),
         ]
      );

      $this->add_control(
         'heading_normal_color',
         [
            'label'     => esc_html__( 'Text Color', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#333',
            'selectors' => [
               '{{WRAPPER}} .el-quantum-title' => 'color: {{VALUE}}',
            ],
         ]
      );

      $this->end_controls_tab();

      $this->start_controls_tab(
         'heading_style_hover',
         [
            'label' => esc_html__( 'Hover', 'quantum-addons' ),
         ]
      );

      $this->add_control(
         'heading_hover_color',
         [
            'label'     => esc_html__( 'Text Color', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
               '{{WRAPPER}} .el-quantum-title:hover' => 'color: {{VALUE}}',
            ],
         ]
      );

      $this->end_controls_tab();

      $this->end_controls_tabs();

      $this->add_responsive_control(
         'title_padding',
         [
            'label'      => esc_html__( 'Padding', 'quantum-addons' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', 'rem' ,'em', '%' ],
            'selectors'  => [
               '{{WRAPPER}} .el-quantum-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
         ]
      );

      $this->add_responsive_control(
         'title_margin',
         [
            'label'      => esc_html__( 'Margin', 'quantum-addons' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'rem' ,'em', '%'],
            'selectors'  => [
               '{{WRAPPER}} .el-quantum-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
         ]
      );

      $this->end_controls_section();

      $this->start_controls_section(
         'paragraph_style',
         [
            'label' => esc_html__( 'Paragraph', 'quantum-addons' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
         ]
      );

      $this->add_group_control(
         \Elementor\Group_Control_Typography::get_type(),
         [
            'label'    => esc_html__( 'Typrography', 'quantum-addons' ),
            'name'     => 'paragraph_typography',
            'selector' => '{{WRAPPER}} .el-quantum-content',
         ]
      );

      $this->start_controls_tabs(
         'paragraph_styles'
      );

      $this->start_controls_tab(
         'paragraph_style_normal',
         [
            'label' => esc_html__( 'Normal', 'quantum-addons' ),
         ]
      );

      $this->add_control(
         'paragraph_normal_color',
         [
            'label'     => esc_html__( 'Text Color', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#5C5C5C',
            'selectors' => [
               '{{WRAPPER}} .el-quantum-content' => 'color: {{VALUE}}',
            ],
         ]
      );

      $this->end_controls_tab();

      $this->start_controls_tab(
         'paragraph_style_hover',
         [
            'label' => esc_html__( 'Hover', 'quantum-addons' ),
         ]
      );

      $this->add_control(
         'paragraph_hover_color',
         [
            'label'     => esc_html__( 'Text Color', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
               '{{WRAPPER}} .el-quantum-content:hover' => 'color: {{VALUE}}',
            ],
         ]
      );

      $this->end_controls_tab();

      $this->end_controls_tabs();

      $this->add_responsive_control(
         'paragraph_padding',
         [
            'label'      => esc_html__( 'Padding', 'quantum-addons' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'rem' ,'em', '%'],
            'selectors'  => [
               '{{WRAPPER}} .el-quantum-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
         ]
      );

      $this->add_responsive_control(
         'paragraph_margin',
         [
            'label'      => esc_html__( 'Margin', 'quantum-addons' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'rem' ,'em', '%'],
            'selectors'  => [
               '{{WRAPPER}} .el-quantum-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
         ]
      );

      $this->end_controls_section();

      ///Additional Content Style Tab

      $this->start_controls_section(
         'additional_style',
         [
            'label' => esc_html__( 'Additional Content', 'quantum-addons' ),
            'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
         ]
      );

      $this->add_group_control(
         \Elementor\Group_Control_Typography::get_type(),
         [
            'label'    => esc_html__( 'Typrography', 'quantum-addons' ),
            'name'     => 'additional_typography',
            'selector' => '{{WRAPPER}} .el-quantum-add-content',
         ]
      );

      $this->start_controls_tabs(
         'additional_styles'
      );

      $this->start_controls_tab(
         'additional_style_normal',
         [
            'label' => esc_html__( 'Normal', 'quantum-addons' ),
         ]
      );

      $this->add_control(
         'additional_normal_color',
         [
            'label'     => esc_html__( 'Text Color', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#eee',
            'selectors' => [
               '{{WRAPPER}} .el-quantum-add-content' => 'color: {{VALUE}}',
            ],
         ]
      );

      $this->end_controls_tab();

      $this->start_controls_tab(
         'additional_style_hover',
         [
            'label' => esc_html__( 'Hover', 'quantum-addons' ),
         ]
      );

      $this->add_control(
         'additional_hover_color',
         [
            'label'     => esc_html__( 'Text Color', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
               '{{WRAPPER}} .el-quantum-add-content:hover' => 'color: {{VALUE}}',
            ],
         ]
      );

      $this->end_controls_tab();

      $this->end_controls_tabs();

      $this->add_responsive_control(
         'additional_padding',
         [
            'label'      => esc_html__( 'Padding', 'quantum-addons' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'rem' ,'em', '%'],
            'selectors'  => [
               '{{WRAPPER}} .el-quantum-add-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
         ]
      );

      $this->add_responsive_control(
         'additional_margin',
         [
            'label'      => esc_html__( 'Margin', 'quantum-addons' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'rem' ,'em', '%'],
            'selectors'  => [
               '{{WRAPPER}} .el-quantum-add-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
         ]
      );

      $this->end_controls_section();

      $this->start_controls_section(
         'style_navigation_controls',
         [
            'label' => esc_html__( 'Navigation Controls', 'quantum-addons' ),
            'tab'   => \ELEMENTOR\Controls_Manager::TAB_STYLE
         ]
      );

      $this->add_group_control(
         \Elementor\Group_Control_Border::get_type(),
         [
            'name'     => 'style_navigation_controls_border',
            'exclude'  => ['color'],
            'selector' => '{{WRAPPER}} .quantum-slider-btn',
         ]
      );

      $this->add_group_control(
         \Elementor\Group_Control_Box_Shadow::get_type(),
         [
            'name'     => 'navigation_controls_box_shadow',
            'label'    => esc_html__( 'Box Shadow', 'quantum-addons' ),
            'selector' => '{{WRAPPER}} .quantum-slider-btn',
         ]
      );

      $this->start_controls_tabs( 'style_navigation_controls_colors_tab' );

      $this->start_controls_tab(
         'style_navigation_controls_normal_colors_tab',
         [
            'label' => esc_html__( 'Normal', 'quantum-addons' ),
         ]
      );

      $this->add_control(
         'style_navigation_controls_normal_icon_color',
         [
            'label'     => esc_html__( 'Icon color', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#fff',
            'selectors' => [
               '{{WRAPPER}} .quantum-slider-btn' => 'color: {{VALUE}}',
            ],
         ]
      );

      $this->add_control(
         'style_navigation_controls_normal_background_color',
         [
            'label'     => esc_html__( 'Background color', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#19A7CE',
            'selectors' => [
               '{{WRAPPER}} .quantum-slider-btn' => 'background-color: {{VALUE}}',
            ],
         ]
      );

      $this->add_control(
         'style_navigation_controls_normal_border_color',
         [
            'label'     => esc_html__( 'Border color', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
               '{{WRAPPER}} .quantum-slider-btn' => 'border-color: {{VALUE}}',
            ],
         ]
      );

      $this->end_controls_tab();

      $this->start_controls_tab(
         'style_navigation_controls_hover_colors_tab',
         [
            'label' => esc_html__( 'Hover', 'quantum-addons' ),
         ]
      );

      $this->add_control(
         'style_navigation_controls_hover_icon_color',
         [
            'label'     => esc_html__( 'Icon color', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
               '{{WRAPPER}} .quantum-slider-btn:hover' => 'color: {{VALUE}}',
            ],
         ]
      );

      $this->add_control(
         'style_navigation_controls_hover_background_color',
         [
            'label'     => esc_html__( 'Background color', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#3A98B9',
            'selectors' => [
               '{{WRAPPER}} .quantum-slider-btn:hover' => 'background-color: {{VALUE}}',
            ],
         ]
      );

      $this->add_control(
         'style_navigation_controls_hover_border_color',
         [
            'label'     => esc_html__( 'Border color', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
               '{{WRAPPER}} .quantum-slider-btn:hover' => 'border-color: {{VALUE}}',
            ],
         ]
      );

      $this->end_controls_tab();

      $this->end_controls_tabs();

      $this->add_responsive_control(
         'style_navigation_controls_padding',
         [
            'label'      => esc_html__( 'Padding', 'quantum-addons' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'separator'  => 'before',
            'size_units' => ['px', 'rem' ,'em', '%'],
            'selectors'  => [
               '{{WRAPPER}} .quantum-slider-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
         ]
      );

      $this->add_responsive_control(
         'style_navigation_controls_border_radius',
         [
            'label'      => esc_html__( 'Border Radius', 'quantum-addons' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'rem' ,'em', '%'],
            'selectors'  => [
               '{{WRAPPER}} .quantum-slider-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
         ]
      );

      $this->end_controls_section();

      $this->start_controls_section(
         'style_pagination',
         [
            'label' => esc_html__( 'Pagination', 'quantum-addons' ),
            'tab'   => \ELEMENTOR\Controls_Manager::TAB_STYLE
         ]
      );

      $this->add_control(
			'style_pagination_horizontal_alignment',
			[
				'label' => esc_html__( 'Alignment', 'quantum-addons' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'quantum-addons' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'quantum-addons' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'quantum-addons' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .quantum-slider-pagination' => 'text-align: {{VALUE}};',
				],
            'condition' => [
               'pagination_type!' => 'progressbar',
            ],
			]
		);

      $this->add_group_control(
         \Elementor\Group_Control_Border::get_type(),
         [
            'name'     => 'style_pagination_dots_border',
            'exclude'  => ['color'],
            'condition' => [
               'pagination_type' => 'bullets',
            ],
            'selector' => '{{WRAPPER}} .quantum-slider-pagination .swiper-pagination-bullet',
         ]
      );

      $this->add_responsive_control(
         'style_pagination_dots_border_radius',
         [
            'label'      => esc_html__( 'Border Radius', 'quantum-addons' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'rem' ,'em', '%'],
            'selectors'  => [
               '{{WRAPPER}} .quantum-slider-pagination .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
            'condition' => [
               'pagination_type' => 'bullets',
            ],
         ]
      );

      $this->add_group_control(
         \Elementor\Group_Control_Box_Shadow::get_type(),
         [
            'name'     => 'style_pagination_dots_box_shadow',
            'label'    => esc_html__( 'Box Shadow', 'quantum-addons' ),
            'condition' => [
               'pagination_type' => 'bullets',
            ],
            'selector' => '{{WRAPPER}} .quantum-slider-pagination .swiper-pagination-bullet',
         ]
      );

      $this->start_controls_tabs( 'style_pagination_dots_colors_tab' );

      $this->start_controls_tab(
         'style_pagination_dots_normal_colors_tab',
         [
            'label' => esc_html__( 'Normal', 'quantum-addons' ),
         ]
      );

      $this->add_control(
         'style_pagination_dots_normal_border_color',
         [
            'label'     => esc_html__( 'Border color', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'condition' => [
               'pagination_type' => 'bullets',
            ],
            'selectors' => [
               '{{WRAPPER}} .quantum-slider-pagination .swiper-pagination-bullet' => 'border-color: {{VALUE}}',
            ],
         ]
      );

      $this->add_control(
         'style_pagination_dots_normal_background_color',
         [
            'label'     => esc_html__( 'Background color', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#00000061',
            'condition' => [
               'pagination_type' => 'bullets',
            ],
            'selectors' => [
               '{{WRAPPER}} .quantum-slider-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}}',
            ],
         ]
      );

      $this->end_controls_tab();

      $this->start_controls_tab(
         'style_pagination_dots_hover_colors_tab',
         [
            'label' => esc_html__( 'Hover', 'quantum-addons' ),
         ]
      );

      $this->add_control(
         'style_pagination_dots_hover_border_color',
         [
            'label'     => esc_html__( 'Border color', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'condition' => [
               'pagination_type' => 'bullets',
            ],
            'selectors' => [
               '{{WRAPPER}} .quantum-slider-pagination .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}}',
            ],
         ]
      );

      $this->add_control(
         'style_pagination_dots_hover_background_color',
         [
            'label'     => esc_html__( 'Background color', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'condition' => [
               'pagination_type' => 'bullets',
            ],
            'selectors' => [
               '{{WRAPPER}} .quantum-slider-pagination .swiper-pagination-bullet:hover' => 'background-color: {{VALUE}}',
            ],
         ]
      );

      $this->end_controls_tab();

      $this->start_controls_tab(
         'style_pagination_dots_active_colors_tab',
         [
            'label' => esc_html__( 'Active', 'quantum-addons' ),
         ]
      );

      $this->add_control(
         'style_pagination_dots_active_border_color',
         [
            'label'     => esc_html__( 'Border color', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'condition' => [
               'pagination_type' => 'bullets',
            ],
            'selectors' => [
               '{{WRAPPER}} .quantum-slider-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'border-color: {{VALUE}}',
            ],
         ]
      );

      $this->add_control(
         'style_pagination_dots_active_background_color',
         [
            'label'     => esc_html__( 'Background color', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => '#000',
            'condition' => [
               'pagination_type' => 'bullets',
            ],
            'selectors' => [
               '{{WRAPPER}} .quantum-slider-pagination .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
            ],
         ]
      );

      $this->end_controls_tab();

      $this->end_controls_tabs();

      $this->add_responsive_control(
         'style_pagination_dots_size',
         [
            'label'      => 'Icon Size',
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'rem', 'em'],
            'default'    => [
               'unit' => 'px',
               'size' => '6'
            ],
            'selectors'  => [
               '{{WRAPPER}} .quantum-slider-pagination .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};'
            ],
            'condition' => [
               'pagination_type' => 'bullets',
            ],
         ]
      );

      $this->add_responsive_control(
         'style_pagination_dots_space',
         [
            'label'      => esc_html__( 'Space Between', 'quantum-addons' ),
            'type'       => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px', 'rem', 'em'],
            'default'    => [
               'unit' => 'px',
               'size' => '4'
            ],
            'selectors'  => [
               '{{WRAPPER}} .quantum-slider-pagination .swiper-pagination-bullet' => '--swiper-pagination-bullet-horizontal-gap: {{SIZE}}{{UNIT}};',
            ],
            'condition' => [
               'pagination_type' => 'bullets',
            ],
         ]
      );

      $this->end_controls_section();

      $this->start_controls_section(
         'style_scrollbar',
         [
            'label' => esc_html__( 'Scrollbar', 'quantum-addons' ),
            'tab'   => \ELEMENTOR\Controls_Manager::TAB_STYLE
         ]
      );

      $this->add_control(
         'style_scrollbar_background_color',
         [
            'label'     => esc_html__( 'Background color', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'rgba(0, 0, 0, 0.1)',
            'selectors' => [
               '{{WRAPPER}} .quantum-slider-scrollbar' => 'background-color: {{VALUE}}',
            ],
         ]
      );

      $this->add_control(
         'style_scrollbar_moveable_element_background_color',
         [
            'label'     => esc_html__( 'Moveable Scrollbar Background color', 'quantum-addons' ),
            'type'      => \Elementor\Controls_Manager::COLOR,
            'default'   => 'rgba(0, 0, 0, 0.5)',
            'selectors' => [
               '{{WRAPPER}} .quantum-slider-scrollbar .swiper-scrollbar-drag' => 'background-color: {{VALUE}}',
            ],
         ]
      );

      $this->add_responsive_control(
         'style_scrollbar_border_radius',
         [
            'label'      => esc_html__( 'Border Radius', 'quantum-addons' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'rem' ,'em', '%'],
            'selectors'  => [
               '{{WRAPPER}} .quantum-slider-scrollbar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
               '{{WRAPPER}} .quantum-slider-scrollbar .swiper-scrollbar-drag' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
         ]
      );

      $this->end_controls_section();
   }

   protected function render()  {
      $settings = $this->get_settings_for_display();
      $slides = $settings['slides'];
      $current_template = $this->slides_templates[$settings['select_template']];
      ?>
      <div class="swiper-container quantum-swiper-container">
         <div class="swiper-wrapper">
            <?php
            foreach( $slides as $slide )  {
               $image = $slide['image'];
               $image_url = trim( $image['url'] );
               $image_alt = isset( $image['alt'] ) ? trim( $image['alt'] ) : '';
               if( $image_alt === '' )  {
                  $image_alt = 'slide image';
               }
               $slide_title = trim( $slide['title'] );
               $slide_para = trim( $slide['content'] );
               $slide_add = trim( $slide['additional_text'] );
               if( isset( $current_template ) )  {
                  $temp_template = $current_template;
                  $temp_template = preg_replace( "/{{Image\.url}}/", $image_url, $temp_template );
                  $temp_template = preg_replace( "/{{Image\.alt}}/", $image_alt, $temp_template );
                  $temp_template = preg_replace( "/{{Title}}/", $slide_title, $temp_template );
                  $temp_template = preg_replace( "/{{Paragraph}}/", $slide_para, $temp_template );
                  $temp_template = preg_replace( "/{{Additional_content}}/", $slide_add, $temp_template );
                  echo '<div class="swiper-slide quantum-slide">' . $temp_template . '</div>';
               }
            }
            ?>
         </div>
         <?php
         if( isset( $settings['is_custom_pagination'] ) && $settings['is_custom_pagination'] === '' )  { ?>
            <div class="quantum-slider-pagination swiper-pagination"></div>
         <?php }

         if( isset( $settings['is_custom_navigation_buttons'] ) && $settings['is_custom_navigation_buttons'] === '' )  { ?>
            <button class="quantum-slider-btn quantum-prev-btn">
               <?php \Elementor\Icons_Manager::render_icon( $settings['navigation_control_prev_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            </button>
            <button class="quantum-slider-btn quantum-next-btn">
               <?php \Elementor\Icons_Manager::render_icon( $settings['navigation_control_next_icon'], [ 'aria-hidden' => 'true' ] ); ?>
            </button>
         <?php }

         if( isset( $settings['is_custom_scrollbar'] ) && $settings['is_custom_scrollbar'] === '' )  { ?>
            <div class="quantum-slider-scrollbar swiper-scrollbar"></div>
         <?php } ?>
      </div>
      <?php
   }
}
