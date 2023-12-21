<style>
    /* styling dahboard */
    input {
        width: 100%;
    }

    button {
        padding: 5px 20px;
    }

    /* Table */
    .form-table {
        width: 40%;
    }

    th[scope=row] {
        width: 1px !important;
        white-space: nowrap;
        width: 1px !important;
        white-space: nowrap;
    }

    /* image input options */
    .rudr-upload img {
        max-width: 80px;
    }

    .feature-link {
        margin-top: 15px;
    }

    /* Radio Group */
    .radio-group {
        display: flex;
        gap: 7px;
        align-items: center;
        display: flex;
        gap: 7px;
        align-items: center;
    }

    input[type=radio] {
        margin: 0 !important;
    }

    /* Bg Picker */
    .color-picker-wrapper {
        display: flex;
        gap: 10px;
        margin-bottom: 5px;
    }

    .color-picker {
        width: 75px;
        height: 30px;
    }

    .color-picker input {
        height: 100%;
    }

    /* Util */
    .mb-1 {
        margin-bottom: 5px !important;
    }

    .mb-2 {
        margin-bottom: 7px !important;
    }

    .mb-3 {
        margin-bottom: 10px !important;
    }
</style>
<!-- note for style  -->
<!-- -> style-1 : displayed immediately -->
<!-- -> style-2 : 1 button floating wrap all button selected -->

<h1>Floating Button</h1>
<p>Default icon is Whatsapp if you want to change the icon, click upload image button</p>

<form action="options.php" method="POST">
    <?php settings_fields('floating_button_settings'); ?>
    <table class="form-table" role="presentation">
        <tbody>
            <tr>
                <td colspan="2">
                    <p class="mb-1">Please select your favorite floating style:</p>
                    <div class="radio-group mb-2">
                        <input type="radio" name="select-style" value="style-1" id="style-1"
                            <?= (get_option('select-style') == 'style-1') ? 'checked' : '' ?>>
                        <label for="style-1">style 1 - Non Group</label>
                    </div>
                    <div class="radio-group">
                        <input type="radio" name="select-style" value="style-2" id="style-2"
                            <?= (get_option('select-style') == 'style-2') ? 'checked' : '' ?>>
                        <label for="style-2">style 2 - Group (recomended for multi floating)</label>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label>Floating Group</label>
                </th>
                <td>
                    <p class="mb-3">Recomended Image Size: 80px x 80px</p>
                    <div class="mb-1">
                        <?php
                        // Check image is set
                        $image_id = get_option('floating_image_group');
                        if ($image = wp_get_attachment_image_url($image_id, 'medium')): ?>
                            <a href="#" class="rudr-upload">
                                <img src="<?php echo esc_url($image) ?>" />
                            </a>
                            <a href="#" class="rudr-remove">Remove image</a>
                            <input type="hidden" name="floating_image_group" value="<?php echo absint($image_id) ?>">
                        <?php else: ?>
                            <a href="#" class="button rudr-upload">Upload image</a>
                            <a href="#" class="rudr-remove" style="display:none">Remove image</a>
                            <input type="hidden" name="floating_image_group" value="">
                        <?php endif; ?>
                    </div>
                    <div class="color-picker-wrapper">
                        <p>Background Color :</p>
                        <div class="color-picker">
                            <!-- Color Input -->
                            <input type="color" placeholder="Floating background color (hex)"
                                name="floating_background_color_group"
                                value="<?= get_option('floating_background_color_group') ?>">
                        </div>
                    </div>
                </td>
            </tr>
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <tr>
                    <th scope="row">
                        <label>Floating
                            <?= $i ?>
                        </label>
                    </th>
                    <td>
                        <p class="mb-3">Recomended Image Size: 80px x 80px</p>
                        <div class="mb-1">
                            <?php
                            // Check image is set
                            $image_id = get_option('floating_image_' . $i);
                            if ($image = wp_get_attachment_image_url($image_id, 'medium')): ?>
                                <a href="#" class="rudr-upload">
                                    <img src="<?php echo esc_url($image) ?>" />
                                </a>
                                <a href="#" class="rudr-remove">Remove image</a>
                                <input type="hidden" name="floating_image_<?= $i ?>" value="<?php echo absint($image_id) ?>">
                            <?php else: ?>
                                <a href="#" class="button rudr-upload">Upload image</a>
                                <a href="#" class="rudr-remove" style="display:none">Remove image</a>
                                <input type="hidden" name="floating_image_<?= $i ?>" value="">
                            <?php endif; ?>
                        </div>
                        <div class="color-picker-wrapper">
                            <p>Background Color :</p>
                            <div class="color-picker">
                                <!-- Color Input -->
                                <input type="color" placeholder="Floating background color (hex)"
                                    name="floating_background_color_<?= $i ?>"
                                    value="<?= get_option('floating_background_color_' . $i) ?>">
                            </div>
                        </div>
                        <!-- Name Input -->
                        <input type="text" placeholder="nama" name="floating_button_name_<?= $i ?>" class="mb-1"
                            value="<?= get_option('floating_button_name_' . $i) ?>">
                        <!-- Link Input -->
                        <input type="text" placeholder="link Floating" name="floating_button_url_<?= $i ?>"
                            value="<?= get_option('floating_button_url_' . $i) ?>">
                    </td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <button class="button button-primary">Submit</button>

</form>