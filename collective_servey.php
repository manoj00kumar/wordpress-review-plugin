<?php
/**
 * Plugin Name: Collective survey and Feedback
 * Plugin URI: http://graycell.com
 * Description: Create survey and feedback form to collect user feedback on products.
 * Version: 1.0.0
 * Author:graycllteam
 */




register_activation_hook( __FILE__, 'my_plugin_create_db' );
function my_plugin_create_db() {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
	//survey data table
	$table_name = $wpdb->prefix . 'collective_survey';

	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		survey_id int(6)  NOT NULL,
		title text NOT NULL,
		options text NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
// survey response table
$table_name1 = $wpdb->prefix . 'collective_survey_response';

	$sql1 = "CREATE TABLE IF NOT EXISTS $table_name1 (
		id mediumint(12) NOT NULL AUTO_INCREMENT,
		userid int(10)  NOT NULL,
		survey_id int(10)  NOT NULL,
		response text NOT NULL,
		created varchar(20) NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

dbDelta( $sql1);

}

// delete questions on survey delete
add_action('delete_post', 'my_deleted_post');
function my_deleted_post($post_id){
 
global $wpdb; 
$wpdb->delete("{$wpdb->prefix}collective_survey",array("survey_id"=>$post_id));
  $wpdb->delete("{$wpdb->prefix}collective_survey_response",array("survey_id"=>$post_id));
};

add_action('init', 'register_cli_survey');
add_action('admin_menu','create_admin_menus');
add_action( 'admin_enqueue_scripts', 'my_enqueued_assets' );
add_action( 'wp_enqueue_scripts', 'front_enqueued_assets' );
function my_enqueued_assets() {
	wp_enqueue_style( 'my-admin-theme', plugin_dir_url( __FILE__ ).'css/bootstrap.css');
	//wp_register_script( 'jquery1', plugin_dir_url( __FILE__ ).'js/jquery.min.js');
wp_enqueue_script('jquery1');

	wp_register_script( 'p-script', plugin_dir_url( __FILE__ ).'js/survey.js', array('jquery'), false, false);
wp_enqueue_script('p-script');

wp_localize_script( 'p-script',
	  'auth_ajax', array( 
	  'ajax_url' => admin_url('admin-ajax.php'),
	  'check_nonce' => wp_create_nonce('auth-nonce')
	  ) 
	);
}
function front_enqueued_assets() {
	
 wp_enqueue_script( 'survey_script', plugin_dir_url( __FILE__ ) . 'js/survey.js' );


wp_localize_script( 'survey-script',
	  'auth_ajax', array( 
	  'ajax_url' => admin_url('admin-ajax.php'),
	  'check_nonce' => wp_create_nonce('survey-nonce')
	  ) 
	);
}
require("inc/posttype.php");
require("inc/add-question.php");
require("inc/view.php");

function create_admin_menus() {        
	
	add_submenu_page('edit.php?post_type=cli_survey', __('Collective survey', 'Collective survey'), __('Add Questions', 'Add Questions'), 'manage_options', 'Collective survey',  'my_plugin_settings_page');

	add_submenu_page('edit.php?post_type=cli_survey', __('Questions', 'Questions'), __(' Questions', ' Questions'), 'manage_options', 'view',  'view_questions');

	add_submenu_page('edit.php?post_type=cli_survey', __('Survey Response', 'Response'), __(' Response', ' response'), 'manage_options', 'view_response',  'view_survey_response');
	
	add_submenu_page('options.php?post_type=cli_survey', "Edit question", "Edit question", 'manage_options', 'edit',  'edit_question_data');
	
	
}



require("inc/add-logic.php");

// edit question

require("inc/edit-delete-logic.php");

require("inc/shortcode.php");
require("inc/survey-response.php");
require("inc/save-response.php");
require("inc/save-feedback.php");


require("inc/edit-question.php");





