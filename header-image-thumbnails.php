<?php
/*
Plugin Name: Header Image Thumbnails
Description: Displays thumbnails of media library images that are at least 1600px wide and allows duplication of images for cropping and editing.
Version: 1.1
Author: Infinus
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Include the settings page
require_once plugin_dir_path(__FILE__) . 'admin/settings-page.php';

// Register the admin menu
function hit_register_admin_menu() {
    add_menu_page('Header Image Thumbnails', 'Header Thumbnails', 'manage_options', 'header-image-thumbnails', 'hit_display_settings_page', 'dashicons-images-alt2');
}
add_action('admin_menu', 'hit_register_admin_menu');

function hit_enqueue_admin_styles() {
    wp_enqueue_style('hit-admin-styles', plugin_dir_url(__FILE__) . 'assets/css/admin-styles.css');
}
add_action('admin_enqueue_scripts', 'hit_enqueue_admin_styles');

// Add Duplicate button to the media editor page
function hit_add_duplicate_button($form_fields, $post) {
    if ($post->post_type == 'attachment') {
        $duplicate_url = admin_url('admin.php?action=hit_duplicate_image&post=' . $post->ID);
        $form_fields['hit_duplicate'] = array(
            'label' => 'Duplicate Image',
            'input' => 'html',
            'html'  => '<a href="' . esc_url($duplicate_url) . '" class="button">Duplicate Image</a>',
        );
    }
    return $form_fields;
}
add_filter('attachment_fields_to_edit', 'hit_add_duplicate_button', 10, 2);

// Register the action for duplicating the image
function hit_register_duplicate_image_action() {
    if (isset($_GET['action']) && $_GET['action'] == 'hit_duplicate_image' && isset($_GET['post'])) {
        $post_id = intval($_GET['post']);
        hit_duplicate_image($post_id);
    }
}
add_action('admin_init', 'hit_register_duplicate_image_action');
