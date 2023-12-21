<?php

add_action('admin_menu', function () {
    $pageTitle = __('Floating Button');
    $menuTitle = $pageTitle;
    $capability = 'edit_pages'; // permission just admin
    $menuSlug = 'floating-button';
    $icon = 'dashicons-buddicons-groups';
    $position = 50; // https://developer.wordpress.org/reference/functions/add_menu_page/#default-bottom-of-menu-structure

    $menu = add_menu_page($pageTitle, $menuTitle, $capability, $menuSlug, function () {
        ob_start();
        include "dashboard.php";
        echo ob_get_clean();
    }, $icon, $position);

    // Enqueue script when in menu page
    add_action('admin_print_scripts-' . $menu, 'media_include_js');
});

// allow editor save floating settings
add_filter(
    hook_name: 'option_page_capability_floating_button_settings',
    callback: fn($capability) => 'edit_pages'
);

// split css feature floating
add_action('wp_print_styles', function () {
    wp_enqueue_style('floating-style', get_stylesheet_directory_uri() . '/assets/feature_floating/floating.css' . '?' . filemtime(get_stylesheet_directory() . '/assets/feature_floating/floating.css'), array(), null);
    wp_enqueue_script('floating-script', get_stylesheet_directory_uri() . '/assets/feature_floating/floating.js', array('jquery'), filemtime(get_stylesheet_directory() . '/assets/feature_floating/floating.js'), true);
});

// attach the settings to the admin_init hook
add_action('admin_init', function () {
    // specify settings name to be saved to wp_options table
    $optionGroup = 'floating_button_settings';
    register_setting(
        option_group: $optionGroup,
        option_name: 'select-style'
    );
    register_setting(
        option_group: $optionGroup,
        option_name: 'floating_image_group'
    );
    register_setting(
        option_group: $optionGroup,
        option_name: 'floating_background_color_group'
    );
    // =====================================
    for($i = 1; $i <= 5; $i++) {
        register_setting(
            option_group: $optionGroup,
            option_name: 'floating_image_'.$i
        );
        register_setting(
            option_group: $optionGroup,
            option_name: 'floating_background_color_'.$i
        );
        register_setting(
            option_group: $optionGroup,
            option_name: 'floating_button_name_'.$i
        );
        register_setting(
            option_group: $optionGroup,
            option_name: 'floating_button_url_'.$i
        );
    }
});

add_shortcode('display_floating', function () {
    $selectstyle = get_option('select-style');
    if ($selectstyle == 'style-1') {
        for ($i = 1; $i <= 5; $i++) {
            $image = get_option('floating_image_'.$i);
            $imageUrl = wp_get_attachment_image_url($image, 'original');

            $bgColor = get_option('floating_background_color_'.$i);
            $url = get_option('floating_button_url_'.$i);
            $name = get_option('floating_button_name_'.$i);

            if (!empty($url) && !empty($name)) {
                ?>
                <a href="<?= $url ?>" class="style-1 button_whatsapp_<?= $i ?>" target="_blank" style="background: <?= $bgColor ?> url('<?= $imageUrl ?>') center center no-repeat;">
                    <span data="<?= $image ?>">
                        <?= $name ?>
                    </span>
                </a>
                <?php
            }
        }
    }
    if ($selectstyle == 'style-2') {
        $image = get_option('floating_image_group');
        $imageUrl = wp_get_attachment_image_url($image, 'original');

        $bgColor = get_option('floating_background_color_group');
        ?>
        <a class="gate-style-2"><span>Call Us</span></a>
        <style>
            .gate-style-2 {
                background: <?= $bgColor ?> url('<?= $imageUrl ?>') center center no-repeat;
            }
            .gate-style-2.x {
                background-color: <?= $bgColor ?>;
            }
        </style>
        <div class="wrap-style-2">
            <?php
            for ($i = 1; $i <= 5; $i++) {
                $image = get_option('floating_image_'.$i);
                $imageUrl = wp_get_attachment_image_url($image, 'original');

                $bgColor = get_option('floating_background_color_'.$i);
                $url = get_option('floating_button_url_'.$i);
                $name = get_option('floating_button_name_'.$i);

                if (!empty($url) && !empty($name)) {
                    ?>
                    <a href="<?= $url ?>" class="style-2 button_whatsapp_<?= $i ?>" target="_blank" style="background: <?= $bgColor ?> url('<?= $imageUrl ?>') center center no-repeat;"><span>
                            <?= $name ?>
                        </span></a>
                    <?php
                }
            }
            ?>
        </div>
        <?php
    }
});

function media_include_js()
{
    // WordPress media uploader scripts
    if (!did_action('wp_enqueue_media')) {
        wp_enqueue_media();
    }
    // our custom JS
    wp_enqueue_script(
        'popup-media',
        get_stylesheet_directory_uri() . '/assets/wordpress-floating-button/media.js',
        array('jquery')
    );
}
