<?php
/**
 * LearnDash Settings Section for Admin Users Metabox.
 *
 * @package LearnDash
 * @subpackage Settings
 */

if ( ( class_exists( 'LearnDash_Settings_Section' ) ) && ( ! class_exists( 'LearnDash_Settings_Section_frontned_page_links' ) ) ) {
	/**
	 * Class to create the settings section.
	 */
	class LearnDash_Settings_Section_frontned_page_links extends LearnDash_Settings_Section {

		/**
		 * Protected constructor for class
		 */
		protected function __construct() {
			$this->settings_page_id = 'learndash_lms_settings';

			// This is the 'option_name' key used in the wp_options table.
			$this->setting_option_key = 'learndash_frontend_page_links';

			// This is the HTML form field prefix used.
			$this->setting_field_prefix = 'learndash_frontend_page_links';

			// Used within the Settings API to uniquely identify this section.
			$this->settings_section_key = 'settings_admin_user1';

			// Section label/header.
			$this->settings_section_label = esc_html__( 'Front End Link Field', 'learndash' );

			$this->settings_section_description = 'Controls front end pages link';

			parent::__construct();
		}

		/**
		 * Initialize the metabox settings values.
		 */
		public function load_settings_values() {
			parent::load_settings_values();

			if ( ! isset( $this->setting_option_values['enabled'] ) ) {
				$this->setting_option_values['enabled'] = 'yes';
			}

			if ( ( ! isset( $this->setting_option_values['frontned_class_creation_page'] ) ) || ( empty( $this->setting_option_values['frontned_class_creation_page'] ) ) ) {
				$this->setting_option_values['frontned_class_creation_page'] = '';
			} 
			if ( ( ! isset( $this->setting_option_values['frontned_topic_creation_page'] ) ) || ( empty( $this->setting_option_values['frontned_topic_creation_page'] ) ) ) {
				$this->setting_option_values['frontned_topic_creation_page'] = '';
			}
			if ( ( ! isset( $this->setting_option_values['frontned_quiz_creation_page'] ) ) || ( empty( $this->setting_option_values['frontned_quiz_creation_page'] ) ) ) {
				$this->setting_option_values['frontned_quiz_creation_page'] = '';
			} // 
			if ( ( ! isset( $this->setting_option_values['frontned_question_creation_page'] ) ) || ( empty( $this->setting_option_values['frontned_question_creation_page'] ) ) ) {
				$this->setting_option_values['frontned_question_creation_page'] = '';
			}
            if ( ( ! isset( $this->setting_option_values['frontned_course_show_all'] ) ) || ( empty( $this->setting_option_values['frontned_course_show_all'] ) ) ) {
				$this->setting_option_values['frontned_course_show_all'] = '';
			}
			if ( ( ! isset( $this->setting_option_values['frontned_course_tags_create'] ) ) || ( empty( $this->setting_option_values['frontned_course_tags_create'] ) ) ) {
				$this->setting_option_values['frontned_course_tags_create'] = '';
			}
			if ( ( ! isset( $this->setting_option_values['frontned_course_categories_create'] ) ) || ( empty( $this->setting_option_values['frontned_course_categories_create'] ) ) ) {
				$this->setting_option_values['frontned_course_categories_create'] = '';
			}
		}

		/**
		 * Initialize the metabox settings fields.
		 */
		public function load_settings_fields() {

			$this->setting_option_fields = array(
				'enabled'       => array(
					'name'                => 'enabled',
					'type'                => 'checkbox-switch',
					'label'               => esc_html__( 'Enabled Frontned Class Creation Page', 'learndash' ),
					'help_text'           => esc_html__( 'Customize the LearnDash Frontned Class Creation Page endpoints. Leave text fields blank to revert to default.', 'learndash' ),
					'value'               => 'yes',
					'options'             => array(
						'yes' => array(
							'label'       => '',
							'description' => '',
							'tooltip'     => esc_html__( 'Frontned Class Creation Page must be enabled', 'learndash' ),
						),
					),
					'attrs'               => array(
						'disabled' => 'disabled',
					),
					'child_section_state' => ( 'yes' === $this->setting_option_values['enabled'] ) ? 'open' : 'closed',
				),
				'frontned_class_creation_page'  => array(
					'name'           => 'frontned_class_creation_page',
					'type'           => 'text',
					'label'          => esc_html__( 'Frontned Class Creation Page', 'learndash' ),
					'value'          => $this->setting_option_values['frontned_class_creation_page'],
					'class'          => 'regular-text',
					'parent_setting' => 'enabled',
				),
				'frontned_topic_creation_page'  => array(
					'name'           => 'frontned_topic_creation_page',
					'type'           => 'text',
					'label'          => esc_html__( 'Frontned Topic Creation Page', 'learndash' ),
					'value'          => $this->setting_option_values['frontned_topic_creation_page'],
					'class'          => 'regular-text',
					'parent_setting' => 'enabled',
				),  // 
				'frontned_quiz_creation_page'  => array(
					'name'           => 'frontned_quiz_creation_page',
					'type'           => 'text',
					'label'          => esc_html__( 'Frontned Quiz Creation Page', 'learndash' ),
					'value'          => $this->setting_option_values['frontned_quiz_creation_page'],
					'class'          => 'regular-text',
					'parent_setting' => 'enabled',
				),
				'frontned_question_creation_page'  => array(
					'name'           => 'frontned_question_creation_page',
					'type'           => 'text',
					'label'          => esc_html__( 'Frontned Question Creation Page', 'learndash' ),
					'value'          => $this->setting_option_values['frontned_question_creation_page'],
					'class'          => 'regular-text',
					'parent_setting' => 'enabled',
				),
			'frontned_course_show_all'  => array(
					'name'           => 'frontned_course_show_all',
					'type'           => 'text',
					'label'          => esc_html__( 'Frontned Courses link', 'learndash' ),
					'value'          => $this->setting_option_values['frontned_course_show_all'],
					'class'          => 'regular-text',
					'parent_setting' => 'enabled',
			),
			'frontned_course_tags_create'  => array(
					'name'           => 'frontned_course_tags_create',
					'type'           => 'text',
					'label'          => esc_html__( 'Frontned Tags Create', 'learndash' ),
					'value'          => $this->setting_option_values['frontned_course_tags_create'],
					'class'          => 'regular-text',
					'parent_setting' => 'enabled',
			),
			'frontned_course_categories_create'  => array(
					'name'           => 'frontned_course_categories_create',
					'type'           => 'text',
					'label'          => esc_html__( 'Frontned Categories Create', 'learndash' ),
					'value'          => $this->setting_option_values['frontned_course_categories_create'],
					'class'          => 'regular-text',
					'parent_setting' => 'enabled',
			)
				
			);

			$this->setting_option_fields = apply_filters( 'learndash_settings_fields', $this->setting_option_fields, $this->settings_section_key );

			parent::load_settings_fields();
		}
	}
}
add_action(
	'learndash_settings_sections_init',
	function() {
		LearnDash_Settings_Section_frontned_page_links::add_section_instance();
	}
);