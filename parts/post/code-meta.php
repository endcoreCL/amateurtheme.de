<ul class="list-meta list-inline">
    <li class="list-inline-item">
        <i class="fa fa-calendar"></i> <?php the_time(  get_option('date_format') ); ?>
    </li>

    <li class="list-inline-item">
        <i class="fa fa-user"></i> <?php echo get_the_author_link(); ?>
    </li>

    <li class="list-inline-item">
        <i class="fa fa-tags"></i> <?php echo get_the_category_list( ', ' ); ?>
    </li>
</ul>