<div class="wrap">
    <h1><?php _e('Slides management', 'vitospanel'); ?></h1>
    <h3><?php _e('Add slide', 'vitospanel') ?></h3>
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
                    <label for="slide_content"><?php _e('Slide content', 'wfmpanel') ?></label>
                </th>
                <td>
                    <textarea name="slide_content" id="slide_content" class="large-text code" cols="50"
                              rows="10"></textarea>
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
</div>