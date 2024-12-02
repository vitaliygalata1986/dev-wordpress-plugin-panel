<div class="wrap">
    <?php
    $vitos_posts = Vitospanel_Admin::getPosts(5);
    $page = $_GET['paged'] ?? 1; // Wordpress writes pagination information to $_GET['paged']
    // echo $page;
    $vitospanel_slides = Vitospanel_Admin::get_slides();
    // Vitospanel_Admin::debug($vitospanel_slides);
    ?>
    <h1><?php _e('Set Slide', 'vitospanel'); ?></h1>
    <p><?php echo __('Number of articles: ', 'vitospanel') . $vitos_posts->found_posts; ?></p>

    <!-- Pagination -->
    <div class="pagination">
        <?php
        $big = 999999999; // need an unlikely integer

        echo paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => $page, // current page number
            'total' => $vitos_posts->max_num_pages, // how many pages in total
            'prev_text' => '&laquo;',
            'next_text' => '&raquo;',
            'mid_size' => 5 // how many pages to show on the sides
        ));
        ?>
    </div>
    <!-- Pagination -->

    <table class="wp-list-table widefat fixed striped table-view-list posts">
        <thead>
        <tr>
            <th class="manage-column column-title column-primary"
                style="width: 50%;"><?php _e('Title', 'vitospanel'); ?></th>
            <th class="manage-column column-categories" style="width: 50%;"><?php _e('Slide', 'vitospanel'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($vitos_posts->have_posts()) : while ($vitos_posts->have_posts()) : $vitos_posts->the_post(); ?>
            <?php $slide_id = get_post_meta(get_the_ID(), 'vitos_panel', true);

            // var_dump($slide_id);
            // получим значение мате-поля для конкретного поста ?>
            <tr>
                <td class="title column-title has-row-actions column-primary page-title"
                    data-colname="<?php _e('Title', 'vitospanel'); ?>">
                    <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
                    <button type="button" class="toggle-row"><span
                                class="screen-reader-text"><?php _e('Show more details', 'vitospanel'); ?></span>
                    </button>
                </td>
                <td class="column-slides" data-colname="<?php _e('Slides', 'vitospanel'); ?>">
                    <select class="vitospanel-select"
                            data-article="<?php the_ID(); // id поста нам доступен, так как мы находимся внутри цикла?>">
                        <option value="0" <?php selected($slide_id, ''); ?>><?php _e('Select slide', 'vitospanel') ?></option>
                        <?php foreach ($vitospanel_slides as $id => $vitospanel_slide): ?>
                            <option <?php selected($slide_id, $id); // сравнивает два значения ?>
                                    value="<?php echo $id; ?>"><?php echo $vitospanel_slide; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
        <?php endwhile; else : ?>
            <p><?php _e('No entries found', 'vitospanel') ?></p>
        <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?php
        $big = 999999999; // need an unlikely integer
        echo paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => $page,
            'total' => $vitos_posts->max_num_pages,
            'prev_text' => '&laquo;',
            'next_text' => '&raquo;',
            'mid_size' => 5
        ));
        ?>
    </div>
    <!-- Pagination -->
    <div id="vitospanel_loader">
        <img src="<?php echo VITOSPANEL_PLUGIN_URL . 'admin/img/ripple.svg' ?>" alt="">
    </div>
</div>