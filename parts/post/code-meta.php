<ul class="list-meta list-inline">
    <li class="list-inline-item">
        <i class="fa fa-calendar"></i> <?php the_time(  get_option('date_format') ); ?>
    </li>

    <li class="list-inline-item">
        <i class="fa fa-user"></i> <a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_the_author(); ?></a>
    </li>

    <li class="list-inline-item">
        <i class="fa fa-tags"></i> <?php echo get_the_category_list( ', ' ); ?>
    </li>
</ul>