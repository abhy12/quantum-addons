<?php

if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( "VESRION", time() );

class advance_slider extends \Elementor\Widget_Base {
    public $templates = [], $template_names = [];
    public $theme_template_path;

    public function __construct( $data = [], $args = null )  {
        parent::__construct( $data, $args );
        wp_register_script( 'advance-slider-script', plugins_url( 'assets/js/advance-slider.js', __DIR__ ), ['jquery', 'elementor-frontend'], VESRION, true );
        wp_register_style( 'advance-slider-styles', plugins_url( 'assets/css/advance-slider.css', __DIR__ ), null, VESRION );

        $this->theme_template_path = get_stylesheet_directory() . '/quantum-addons/advance-slider';
        $this->init_templates();
    }

    public function init_templates()  {
        $this->templates['default'] = $this->get_default_template();
        $this->template_names['default'] = 'Default';

        if( file_exists( $this->theme_template_path ) && is_dir( $this->theme_template_path ) && is_readable( $this->theme_template_path ) )  {
            $files = array_filter( glob( $this->theme_template_path.'/*.html*' ), 'is_file' );

            foreach( $files as $file )  {
                $file_name = basename( $file, '.html' );
                $file_name = ucfirst( trim( preg_replace( '/[-_]/', ' ', $file_name  ) ) );
                $template = file_get_contents( $file );
                $this->templates[$file_name] = $template;
                $this->template_names[$file_name] = $file_name;
            }
        }
    }

    public function get_default_template()  {
        $template = <<<'Template'
        <div class="swiper-slide quantum-slide">
            <img class="slider-image" src="{{Image.src}}" alt="{{Image.alt}}">
              <div class="el-quantum-content-container">
                <h3 class="el-quantum-title">{{Title}}</h3>
                <p class="el-quantum-content">{{Paragraph}}</p>
                <p class="el-quantum-add-content">{{Additional_content}}</p>
            </div>
        </div>
        Template;
        return $template;
    }

    public function get_template_tags()  {
        return ['Image\.src', 'Image\.alt', 'Title', 'Paragraph', 'Additional_content'];
    }

    public function get_name()  {
        return 'Advance_slider';
    }

    public function get_title()  {
       return esc_html__( 'Advance Slider' );
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
                'label'  => esc_html__( 'Content' ),
                'tab'    => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'select_template',
            [
                'label'    => esc_html__( 'Select Template' ),
                'type'     => \Elementor\Controls_Manager::SELECT,
                'options'  => $this->template_names,
                'default'  => 'default'
            ],
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'image',
            [
                'label'    => esc_html__( 'Image' ),
                'type'     => \Elementor\Controls_Manager::MEDIA,
                'default'  => [
                'url'  => \Elementor\Utils::get_placeholder_image_src(),
                    ],
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label'        => esc_html__( 'Heading' ),
                'type'         => \Elementor\Controls_Manager::TEXT,
                'placeholder'  =>  esc_html__( 'Write someting...' )
            ]
        );

        $repeater->add_control(
            'content',
            [
                'label'  => esc_html__( 'Paragraph' ),
                'type'   => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder'  =>  esc_html__( 'Write someting...' ),
            ]
        );

        $repeater->add_control(
          'additional_text',
          [
               'label'        => esc_html__( 'Additonal Content' ),
               'type'         => \Elementor\Controls_Manager::WYSIWYG,
               'default'      => esc_html__( 'additional content...', 'plugin-name' ),
          ]
        );

        $this->add_control(
            'slides',
            [
                'label'   => esc_html__( 'Slides' ),
                'type'    => \Elementor\Controls_Manager::REPEATER,
                'fields'  => $repeater->get_controls(),
                'default' => [
                    [
                        'title'    => 'Slide #',
                        'content'  => "Lorem ipsum, dolor sit amet consectetur adipisicing elit. Delectus earum fuga nam.",
                    ]
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'slider-options',
            [
                'label'   =>  esc_html__( 'Slider Options' ),
                'tab'    => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'slide_per_view',
            [
                'label'    => esc_html__( 'Slide Per View' ),
                'type'     => \Elementor\Controls_Manager::NUMBER,
                'frontend_available' => true,
                'desktop_default' =>  3,
				'tablet_default'  =>  2,
				'mobile_default'  =>  1,
            ]
        );

        $this->add_responsive_control(
            'slide_per_group',
            [
                'label'    => esc_html__( 'Slide Per Group' ),
                'type'     => \Elementor\Controls_Manager::SELECT,
                'options'  => [
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
                'desktop_default' => '1',
				'tablet_default'  => '1',
				'mobile_default'  => '1',
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'center_slide',
            [
               'label'   =>  esc_html__( 'Center Slides' ),
               'type'    =>  \Elementor\Controls_Manager::SELECT,
               'options' =>  [
                  '1'  => 'Yes',
                  '0'  => 'No',
               ],
               'desktop_default' => '1',
			   'tablet_default'  => '1',
			   'mobile_default'  => '1',
               'default'  => '1',
               'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'space_between',
            [
                'label'    => esc_html__( 'Space Between' ),
                'type'     => \Elementor\Controls_Manager::NUMBER,
                'desktop_default' =>  30,
				'tablet_default'  =>  20,
				'mobile_default'  =>  10,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'loop',
            [
                'label'          => esc_html__( 'Infinite loop' ),
                'type'           => \Elementor\Controls_Manager::SWITCHER,
                'label_on'       =>  esc_html__( 'Yes' ),
                'label_off'      =>  esc_html__( 'No' ),
                'return_value'   => '1',
                'default'        => '1',
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'container_style',
            [
                'label' =>  esc_html__( 'Container' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'container_overflow',
            [
                'label'    => esc_html__( 'Overflow' ),
                'type'     => \Elementor\Controls_Manager::SELECT,
                'options'  => [
                    'hidden'  => 'Hidden',
                    'visible' => 'Visible',
                ],
                'default'  => 'hidden',
                'selectors' => [
                    '{{WRAPPER}} .quantum-swiper-container' => 'overflow: {{VALUE}}'
                ]
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label'       => esc_html__( 'Padding' ),
                'type'        => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units'  => [ 'px', 'rem' ,'em', '%' ],
                'description' => esc_html__( 'Note: if you change padding you may want to reload the browser (if slider not working properly)' ),
                'selectors' => [
					'{{WRAPPER}} .quantum-swiper-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
        );

        $this->add_responsive_control(
            'container_margin',
            [
                'label'      => esc_html__( 'Margin' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem' ,'em', '%' ],
                'selectors' => [
					'{{WRAPPER}} .quantum-swiper-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'slide_style',
            [
                'label'  => esc_html__( 'Slide' ),
                'tab'  => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => esc_html__( 'Border', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .quantum-slide',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'label' => esc_html__( 'Box Shadow', 'plugin-name' ),
				'selector' => '{{WRAPPER}} .quantum-slide',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => esc_html__( 'Background', 'plugin-name' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .quantum-slide',
			]
		);

        $this->add_responsive_control(
            'slide_padding',
            [
                'label'      => esc_html__( 'Padding' ),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem' ,'em', '%'],
                'selectors' => [
					'{{WRAPPER}} .quantum-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'image_style',
           [
            'label' => 'Image',
            'tab'  => \Elementor\Controls_Manager::TAB_STYLE,
           ]
        );

        $this->add_control(
            'image_width',
            [
                'label'       => 'Image Width',
                'type'        => \Elementor\Controls_Manager::SLIDER,
                'size_units'  => ['px', '%', 'rem', 'em'],
                'default'     => [
                    'unit'  => '%',
                    'size'  => '100'
                ],
                'selectors'  => [
                    '{{WRAPPER}} .slider-image' => 'width: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->add_control(
           'image_height',
           [
               'label'    => esc_html__( 'Image Height' ),
               'type'     => \Elementor\Controls_Manager::NUMBER,
               'selectors'  => [
                  '{{WRAPPER}} .slider-image' => 'height: {{SIZE}}px;'
               ],
           ]
        );

        $this->add_control(
            'object-fit',
            [
                'label'    => esc_html__( 'Object Fit' ),
                'type'     => \Elementor\Controls_Manager::SELECT,
                'options'  => [
                    'none'     => 'Default',
                    'cover'    => 'Cover',
                    'contain'  => 'Contain',
                    'fill'     => 'Fill',
                ],
                'selectors'   => [
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
                'label'   => esc_html__( 'Content Container' ),
                'tab'     => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label'   => esc_html__( 'Padding ' ),
                'type'    => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem' ,'em', '%' ],
                'selectors' => [
					'{{WRAPPER}} .el-quantum-content-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label'   => esc_html__( 'Margin' ),
                'type'    => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem' ,'em', '%' ],
                'selectors' => [
					'{{WRAPPER}} .el-quantum-content-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'content_background',
				'label' => esc_html__( 'Background', 'plugin-name' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .el-quantum-content-container',
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'heading_style',
            [
               'label'    => esc_html__( 'Heading' ),
               'tab'      => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[   'label'    => esc_html__( 'Title Typrography' ),
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
                'label'   => esc_html__( 'Normal' ),
            ]
        );

        $this->add_control(
            'heading_normal_color',
            [
                'label'    => esc_html__( 'Text Color' ),
                'type'     => \Elementor\Controls_Manager::COLOR,
                'default'  => '#333',
                'selectors'  => [
                    '{{WRAPPER}} .el-quantum-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'heading_style_hover',
            [
                'label'   => esc_html__( 'Hover' ),
            ]
        );

        $this->add_control(
            'heading_hover_color',
            [
                'label'    => esc_html__( 'Text Color' ),
                'type'     => \Elementor\Controls_Manager::COLOR,
                'selectors'  => [
                    '{{WRAPPER}} .el-quantum-title:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'title_padding',
            [
                'label'   => esc_html__( 'Padding ' ),
                'type'    => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem' ,'em', '%' ],
                'selectors' => [
					'{{WRAPPER}} .el-quantum-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'   => esc_html__( 'Margin' ),
                'type'    => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem' ,'em', '%' ],
                'selectors' => [
					'{{WRAPPER}} .el-quantum-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'paragraph_style',
            [
                'label'    => esc_html__( 'Paragraph' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[   'label'    => esc_html__( 'Title Typrography' ),
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
                'label'   => esc_html__( 'Normal' ),
            ]
        );

        $this->add_control(
            'paragraph_normal_color',
            [
                'label'    => esc_html__( 'Text Color' ),
                'type'     => \Elementor\Controls_Manager::COLOR,
                'default'  => '#5C5C5C',
                'selectors'  => [
                    '{{WRAPPER}} .el-quantum-content' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'paragraph_style_hover',
            [
                'label'   => esc_html__( 'Hover' ),
            ]
        );

        $this->add_control(
            'paragraph_hover_color',
            [
                'label'    => esc_html__( 'Text Color' ),
                'type'     => \Elementor\Controls_Manager::COLOR,
                'selectors'  => [
                    '{{WRAPPER}} .el-quantum-content:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'paragraph_padding',
            [
                'label'   => esc_html__( 'Padding ' ),
                'type'    => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem' ,'em', '%' ],
                'selectors' => [
					'{{WRAPPER}} .el-quantum-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
        );

        $this->add_responsive_control(
            'paragraph_margin',
            [
                'label'   => esc_html__( 'Margin' ),
                'type'    => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem' ,'em', '%' ],
                'selectors' => [
					'{{WRAPPER}} .el-quantum-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
        );

        $this->end_controls_section();

        ///Additional Content Style Tab

        $this->start_controls_section(
            'additional_style',
            [
                'label'    => esc_html__( 'Additional Content' ),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[   'label'    => esc_html__( 'Content Typrography' ),
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
                'label'   => esc_html__( 'Normal' ),
            ]
        );

        $this->add_control(
            'additional_normal_color',
            [
                'label'    => esc_html__( 'Text Color' ),
                'type'     => \Elementor\Controls_Manager::COLOR,
                'default'  => '#eee',
                'selectors'  => [
                    '{{WRAPPER}} .el-quantum-add-content' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'additional_style_hover',
            [
                'label'   => esc_html__( 'Hover' ),
            ]
        );

        $this->add_control(
            'additional_hover_color',
            [
                'label'    => esc_html__( 'Text Color' ),
                'type'     => \Elementor\Controls_Manager::COLOR,
                'selectors'  => [
                    '{{WRAPPER}} .el-quantum-add-content:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'additional_padding',
            [
                'label'   => esc_html__( 'Padding' ),
                'type'    => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem' ,'em', '%' ],
                'selectors' => [
					'{{WRAPPER}} .el-quantum-add-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
        );

        $this->add_responsive_control(
            'additional_margin',
            [
                'label'   => esc_html__( 'Margin' ),
                'type'    => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem' ,'em', '%' ],
                'selectors' => [
					'{{WRAPPER}} .el-quantum-add-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()  {
      $settings = $this->get_settings_for_display();
      $slides = $settings['slides'];
      $current_template = $this->templates[$settings['select_template']];
      ?>
       <div class="swiper-container quantum-swiper-container">
          <div class="swiper-wrapper">
            <?php
            foreach( $slides as $slide ) {
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
                // foreach( $this->get_template_tags() as $tag )  {
                //   $current_template = preg_replace( "/{{$tag}}/", $image_url, $current_template );
                // }
                $temp_template = preg_replace( "/{{Image\.src}}/", $image_url, $temp_template );
                $temp_template = preg_replace( '/{{Image\.alt}}/', $image_alt, $temp_template );
                $temp_template = preg_replace( '/{{Title}}/', $slide_title, $temp_template );
                $temp_template = preg_replace( '/{{Paragraph}}/', $slide_para, $temp_template );
                $temp_template = preg_replace( '/{{Additional_content}}/', $slide_add, $temp_template );
                echo $temp_template;
              }
            } ?>
          </div>
          <div class="swiper-pagination"></div>
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
          <div class="swiper-scrollbar"></div>
       </div>
      <?php
   }
}
