<div class="wrap">
    <h1><?php _e('Slides management', 'vitospanel'); ?></h1>
    <h3><?php _e('Add slide', 'vitospanel') ?></h3>
    <?php
        $errors = get_transient('vitospanel_form_erros'); // получим ошибку
        $success = get_transient( 'vitospanel_form_success' );
    ?>

    <?php if ( $errors ): ?>
        <div id="setting-error-settings_updated" class="notice notice-error settings-error is-dismissible">
            <p><strong><?php echo $errors ?></strong></p>
        </div>
        <!--как только время опции завершилось - сразу ее удалим-->
        <?php delete_transient( 'vitospanel_form_erros' ); ?>
    <?php endif; ?>

    <?php if ( $success ): ?>
        <div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible">
            <p><strong><?php echo $success ?></strong></p>
        </div>
        <?php delete_transient( 'vitospanel_form_success' ); ?>
    <?php endif; ?>

    <form action="<?php echo admin_url('admin-post.php') ?>" method="post" class="vitospanel-add-slide">
        <!--wp_nonce_field - здесь хранится спец. код, который позволят понять Wordpress, что данные оптравленны с нашего сайта-->
        <?php wp_nonce_field('vitospanel_action', 'vitospanel_add_slide') ?>
        <input type="hidden" name="action" value="save_slide">
        <table class="form-table">
            <tbody>
            <tr>
                <th>
                    <label for="slide_title"><?php _e('Slide title', 'vitospanel') ?></label>
                </th>
                <td>
                    <input type="text" class="regular-text" name="slide_title" id="slide_title">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="slide_content"><?php _e('Slide content', 'vitospanel') ?></label>
                </th>
                <td>
<!--                    <textarea name="slide_content" id="slide_content" class="large-text code" cols="50"-->
<!--                              rows="10"></textarea>-->
                    <?php wp_editor('', 'slide_content', array(
                            'textarea_name' => 'slide_content', // slide_content принимаем на сервере
                            'textarea_rows' => 10, // высота (кол. строк)
                    )); // в качестве контента ничего не выводим, ?>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit">
            <button class="button button-primary" type="submit"><?php _e('Save slide', 'vitospanel') ?></button>
        </p>
    </form>
    <hr>

    <h3><?php _e( 'Edit slides', 'vitospanel' ) ?></h3>
    <?php
        // Vitospanel_Admin::debug(Vitospanel_Admin::get_slides());
        $vitospanel_slides =  Vitospanel_Admin::get_slides(true); // получим все слайды
    ?>
    <div id="accordion">
        <?php foreach ($vitospanel_slides as $vitospanel_slide): ?>
            <h3><?php echo $vitospanel_slide['title']; ?></h3>
            <div>
                <form action="<?php echo admin_url('admin-post.php') ?>" method="post" class="vitospanel-add-slide">
                    <?php wp_nonce_field('vitospanel_action', 'vitospanel_add_slide') ?>
                    <input type="hidden" name="action" value="save_slide">
                    <input type="hidden" name="slide_id" value="<?php echo $vitospanel_slide['id']; ?>">
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th>
                                <label for="slide_title_<?php echo $vitospanel_slide['id'];?>"><?php _e('Slide title', 'vitospanel') ?></label>
                            </th>
                            <td>
                                <input type="text" value="<?php echo esc_attr__($vitospanel_slide['title']);?>" class="regular-text" name="slide_title" id="slide_title_<?php echo $vitospanel_slide['id'];?>">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="slide_content_<?php echo $vitospanel_slide['id'];?>"><?php _e('Slide content', 'vitospanel') ?></label>
                            </th>
                            <td>
                                <?php wp_editor($vitospanel_slide['content'], "slide_content_{$vitospanel_slide['id']}", array(
                                    'textarea_name' => 'slide_content',
                                    'textarea_rows' => 10,
                                )); ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <p class="submit">
                        <button class="button button-primary" type="submit"><?php _e('Save slide', 'vitospanel') ?></button>
                    </p>
                </form>
            </div>
        <?php endforeach;?>
    </div>
</div>