<div class="wrap">
    <?php
    $vitos_posts = Vitospanel_Admin::getPosts(5);
    $page = $_GET['paged'] ?? 1; // Wordpress информацию о пагинации записывает в $_GET['paged']
    // echo $page;
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
            'current' => $page, // номер текущей страницы
            'total' => $vitos_posts->max_num_pages, // сколько всего страниц
            'prev_text' => '&laquo;',
            'next_text' => '&raquo;',
            'mid_size' => 5 // сколько по бокам показывать страниц
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
            <tr>
                <td class="title column-title has-row-actions column-primary page-title"
                    data-colname="<?php _e('Title', 'vitospanel'); ?>">
                    <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
                    <button type="button" class="toggle-row"><span
                                class="screen-reader-text"><?php _e('Show more details', 'vitospanel'); ?></span>
                    </button>
                </td>
                <td class="column-slides" data-colname="<?php _e( 'Slides', 'vitospanel' ); ?>">
                    <select name="" id="">
                        <option value="">Lorem ipsum dolor sit amet.</option>
                        <option value="">Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet.</option>
                        <option value="">Lorem ipsum dolor sit amet.</option>
                    </select>
                </td>
            </tr>
        <?php endwhile; else : ?>
            <p><?php _e('No entries found', 'wfmpanel') ?></p>
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
</div>