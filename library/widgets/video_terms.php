<?php
/**
 * Widget: Video Terms
 */
class at_video_terms_widget extends WP_Widget {
    public function __construct() {
        $widget_ops = array('classname' => 'widget_term_list widget_block', 'description' => 'Dieses Widget zeigt Einträge einer Taxonomie an.' );
        parent::__construct('at_video_terms_widget', 'amateurtheme.de &raquo; Terms (Kategorie/Darsteller)', $widget_ops);
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);

        // fields
        $taxonomy = (get_field('widget_video_terms_taxonomy', 'widget_' . $args['widget_id']) ? get_field('widget_video_terms_taxonomy', 'widget_' . $args['widget_id']) : 'video_category');
        $limit = (get_field('widget_video_terms_limit', 'widget_' . $args['widget_id']) ? get_field('widget_video_terms_limit', 'widget_' . $args['widget_id']) : 12);
        $orderby = (get_field('widget_video_terms_orderby', 'widget_' . $args['widget_id']) ? get_field('widget_video_terms_orderby', 'widget_' . $args['widget_id']) : 'count');
        $order = (get_field('widget_video_terms_order', 'widget_' . $args['widget_id']) ? get_field('widget_video_terms_order', 'widget_' . $args['widget_id']) : 'desc');
        $count = get_field('widget_video_terms_count', 'widget_' . $args['widget_id']);

        $args = array(
            'hide_empty' => false,
            'number' => $limit,
            'orderby' => $orderby,
            'order' => $order
        );

        $terms = get_terms($taxonomy, $args);

        if($terms) {
            echo $before_widget;

            if ($instance['title']) {
                echo $before_title . $instance['title'] . $after_title;
            }

            echo '<ul class="list-unstyled">';

            foreach($terms as $term) {
                echo '<li class="term term-' . $term->term_id . '"><a href="' . get_term_link($term) . '">' . $term->name . ($count ? ' <span class="badge badge-default term-count">' . $term->count . '</span>' : '') . '</a></li>';
            }

            echo '</ul>';

            echo $after_widget;
        }
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;
    }

    function form($instance) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
        $title = $instance['title'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Titel:', 'amateurtheme'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>">
        </p>
        <?php
    }
}
register_widget('at_video_terms_widget');