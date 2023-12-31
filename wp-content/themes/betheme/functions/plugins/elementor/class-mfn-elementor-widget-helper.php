<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Mfn_Elementor_Widget_Helper extends \Elementor\Widget_Base {

	/**
	 * Widget base constructor
	 */

	public function __construct( $data = [], $args = null ) {

		add_filter( 'wpml_elementor_widgets_to_translate', [ $this, 'wpml_widgets_to_translate_filter' ] );

		parent::__construct( $data, $args );
	}

	/**
	 * WPML compatibility
	 */

	public function wpml_widgets_to_translate_filter( $widgets ) {

	  $widgets[ $this->get_name() ] = [
			'conditions' => [
				'widgetType' => $this->get_name(),
			],
			'fields' => [
			  [
					'field' => 'title',
					'type' => $this->get_title() .'<br />'. __( 'Title', 'mfn-opts' ),
					'editor_type' => 'LINE'
			  ],
			  [
					'field' => 'title1',
					'type' => $this->get_title() .'<br />'. __( 'Title 1', 'mfn-opts' ),
					'editor_type' => 'LINE'
			  ],
			  [
					'field' => 'content1',
					'type' => $this->get_title() .'<br />'. __( 'Content 1', 'mfn-opts' ),
					'editor_type' => 'VISUAL'
			  ],
			  [
					'field' => 'link1',
					'type' => $this->get_title() .'<br />'. __( 'Link 1', 'mfn-opts' ),
					'editor_type' => 'LINE'
			  ],
			  [
					'field' => 'title2',
					'type' => $this->get_title() .'<br />'. __( 'Title 2', 'mfn-opts' ),
					'editor_type' => 'LINE'
			  ],
			  [
					'field' => 'content2',
					'type' => $this->get_title() .'<br />'. __( 'Content 2', 'mfn-opts' ),
					'editor_type' => 'VISUAL'
			  ],
			  [
					'field' => 'link2',
					'type' => $this->get_title() .'<br />'. __( 'Link 2', 'mfn-opts' ),
					'editor_type' => 'LINE'
			  ],
			],
	  ];

	  return $widgets;
	}

	/**
	 * Get widget name
	 */

	public function get_name() {
		return 'mfn_helper';
	}

	/**
	 * Get widget title
	 */

	public function get_title() {
		return __( 'Be • Helper', 'mfn-opts' );
	}

	/**
	 * Get widget icon
	 */

	public function get_icon() {
		return 'fas fa-question';
	}

	/**
	 * Get widget categories
	 */

	public function get_categories() {
		return [ 'mfn_builder' ];
	}

	/**
	 * Register widget controls
	 */

	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'mfn-opts' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', 'mfn-opts' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'This is the heading', 'mfn-opts' ),
			]
		);

    $this->add_control(
			'title_tag',
			[
				'label' => __( 'Title tag', 'mfn-opts' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options'	=> array(
          'h1' => 'H1',
          'h2' => 'H2',
          'h3' => 'H3',
          'h4' => 'H4',
          'h5' => 'H5',
          'h6' => 'H6',
				),
				'default' => 'h4',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'item1_section',
			[
				'label' => __( 'Item 1', 'mfn-opts' ),
			]
		);

    $this->add_control(
      'title1',
      [
        'label' => __( 'Title', 'mfn-opts' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true,
				'default' => __( 'Regular tab', 'mfn-opts' ),
      ]
    );

    $this->add_control(
      'content1',
      [
        'label' => __( 'Content', 'mfn-opts' ),
        'description' => __( 'Some Shortcodes and HTML tags allowed', 'mfn-opts' ),
        'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
        'condition' => [
          'link1' => '',
        ],
      ]
    );

    $this->add_control(
			'link1',
			[
				'label' => __( 'Link', 'mfn-opts' ),
				'description' => __( 'Use this field if you want to link to another page instead of showing the content', 'mfn-opts' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
        'condition' => [
          'content1' => '',
        ],
			]
		);

		$this->add_control(
			'target1',
			[
				'label' => __( 'Target', 'mfn-opts' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options'	=> array(
					0 => __('_self', 'mfn-opts'),
					1 => __('_blank', 'mfn-opts'),
				),
				'default' => 0,
        'condition' => [
          'content1' => '',
        ],
			]
		);

    $this->add_control(
			'class1',
			[
				'label' => __( 'Link class', 'mfn-opts' ),
				'description' => __( 'This option is useful when you want to use <b>prettyphoto</b> or <b>scroll</b>', 'mfn-opts' ),
				'type' => \Elementor\Controls_Manager::TEXT,
        'condition' => [
          'content1' => '',
        ],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'item2_section',
			[
				'label' => __( 'Item 2', 'mfn-opts' ),
			]
		);

    $this->add_control(
      'title2',
      [
        'label' => __( 'Title', 'mfn-opts' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'label_block' => true,
				'default' => __( 'Scroll to ID', 'mfn-opts' ),
      ]
    );

    $this->add_control(
      'content2',
      [
        'label' => __( 'Content', 'mfn-opts' ),
        'description' => __( 'Some Shortcodes and HTML tags allowed', 'mfn-opts' ),
        'type' => \Elementor\Controls_Manager::WYSIWYG,
        'condition' => [
          'link2' => '',
        ],
      ]
    );

    $this->add_control(
			'link2',
			[
				'label' => __( 'Link', 'mfn-opts' ),
				'description' => __( 'Use this field if you want to link to another page instead of showing the content', 'mfn-opts' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'label_block' => true,
				'default' => '#Footer',
        'condition' => [
          'content2' => '',
        ],
			]
		);

		$this->add_control(
			'target2',
			[
				'label' => __( 'Target', 'mfn-opts' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options'	=> array(
					0 => __('_self', 'mfn-opts'),
					1 => __('_blank', 'mfn-opts'),
				),
				'default' => 0,
        'condition' => [
          'content2' => '',
        ],
			]
		);

    $this->add_control(
			'class2',
			[
				'label' => __( 'Link class', 'mfn-opts' ),
				'description' => __( 'This option is useful when you want to use <b>prettyphoto</b> or <b>scroll</b>', 'mfn-opts' ),
				'type' => \Elementor\Controls_Manager::TEXT,
        'default' => 'scroll',
        'condition' => [
          'content2' => '',
        ],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render widget output on the frontend
	 */

	protected function render() {

		$settings = $this->get_settings_for_display();

		echo sc_helper( $settings );

	}

}
