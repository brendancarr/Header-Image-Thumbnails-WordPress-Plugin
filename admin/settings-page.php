<?php
// admin/settings-page.php

function hit_display_settings_page() {
    ?>
    <div class="wrap">
        <h1>Header Image Thumbnails</h1>
        <P>These images are at least 1600px wide, and should work for header images for your site.</p>
        <P>After you click on one of these images, at the bottom of the image editing page you can duplicate that image, so you can crop it and edit it to suit your needs.</p>

        <div id="hit-thumbnails-container">
            <?php
            $args = array(
                'post_type'      => 'attachment',
                'post_mime_type' => 'image',
                'post_status'    => 'inherit',
                'posts_per_page' => -1,
                
            );

            $query = new WP_Query($args);

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $meta = wp_get_attachment_metadata(get_the_ID());
                    if ($meta['width'] >= 1600) {
                        $attachment_link = get_edit_post_link(get_the_ID());
                        echo '<a href="' . esc_url($attachment_link) . '" target="_blank">';
                        echo wp_get_attachment_image(get_the_ID(), 'thumbnail');
                        echo '</a>';
                    }
                }
            } else {
                echo '<p>No images found with the specified dimensions.</p>';
            }

            wp_reset_postdata();
            ?>
        </div>
    </div>
    <?php
}
