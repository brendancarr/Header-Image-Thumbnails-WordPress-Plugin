<?php
// admin/duplicate-image.php

function hit_duplicate_image($post_id) {
    // Verify that the post is an attachment
    if (get_post_type($post_id) != 'attachment') {
        return;
    }

    // Get the attachment data
    $post = get_post($post_id);
    $attachment_data = wp_get_attachment_metadata($post_id);
    $file_path = get_attached_file($post_id);
    $file_name = basename($file_path);

    // Generate a new file name
    $new_file_name = wp_unique_filename(dirname($file_path), 'copy-' . $file_name);

    // Copy the file to the new location
    $new_file_path = dirname($file_path) . '/' . $new_file_name;
    if (!copy($file_path, $new_file_path)) {
        wp_die('Failed to copy image.');
    }

    // Create a new attachment post
    $new_attachment = array(
        'post_mime_type' => $post->post_mime_type,
        'post_title'     => $post->post_title . ' (Copy)',
        'post_content'   => $post->post_content,
        'post_status'    => 'inherit',
        'post_parent'    => $post->post_parent,
    );

    $new_attachment_id = wp_insert_attachment($new_attachment, $new_file_path);

    if (!is_wp_error($new_attachment_id)) {
        // Copy attachment metadata
        wp_update_attachment_metadata($new_attachment_id, $attachment_data);

        // Redirect to the edit page of the new attachment
        wp_redirect(admin_url('post.php?post=' . $new_attachment_id . '&action=edit'));
        exit;
    } else {
        wp_die('Failed to create new attachment.');
    }
}
