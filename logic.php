<?php

/**
 * Format backslash to slash
 * @param string $text text to be formatted
 * @return string formatted text
 */
function format_slash($text) {
    return str_replace( "\\",  "/", $text);
}

/**
 * Get formatted WP absolute path
 * @return string WP absolute path (formatted)
 */
function get_absolute_path() {
    return format_slash(rtrim(ABSPATH, "/"));
}

/**
 * Get formatted relative directory of feature
 * @param string $path path to file or folder
 * @return string relative directory of feature (formatted)
 */
function feature_dir($path = "") {
    return str_replace(get_absolute_path(), "", feature_absolute_dir($path));
}

/**
 * Get formatted absolute directory of feature
 * @param string $path path to file or folder
 * @return string absolute directory of feature (formatted)
 */
function feature_absolute_dir($path = "") {
    return format_slash(realpath(dirname(__FILE__) ."/". $path));
}

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
    wp_enqueue_style('floating-style', feature_dir('floating.css'), array(), filemtime(feature_absolute_dir('floating.css')));
    wp_enqueue_script('floating-script', feature_dir('floating.js'), array('jquery'), filemtime(feature_absolute_dir('floating.js')), true);
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
            $imageUrl = wp_get_attachment_image_url($image, 'original') ?: feature_dir('./img/wa-xl.svg');

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
        feature_dir('media.js'),
        array('jquery')
    );
}
