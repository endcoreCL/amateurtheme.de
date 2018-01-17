<?php
/**
 * Widget: Video Kategorien
 */
class nearby_locations_widget extends WP_Widget {
    public function __construct() {
        $widget_ops = array('classname' => 'widget_location_list widget_block', 'description' => 'Dieses Widget zeigt alle Städte in einem bestimmten Umkreis an.' );
        parent::__construct('nearby_locations_widget', 'datingtheme.io &raquo; Städte in der Nähe', $widget_ops);
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);
        global $post;

        $distance = (isset($instance['distance']) ? $instance['distance'] : 10);
        $limit = (isset($instance['limit']) ? $instance['limit'] : 10);
        $display_distance = (isset($instance['display_distance']) ? $instance['display_distance'] : 'off');

        $nearby = dt_locations_geodata_nearby($post->ID, $distance, $limit);

        if($nearby) {

            echo $before_widget;

            if($instance['title']) { echo $before_title . $instance['title'] . $after_title; }

            ?><ul class="list-unstyled"><?php
            foreach($nearby as $item) {
                ?>
                <li>
                    <a href="<?php echo get_permalink($item->pid); ?>"><?php echo get_the_title($item->pid); ?>
                        <?php if($display_distance == 'on') { ?>
                            <small>(<?php echo round($item->distance, 2); ?> km)</small>
                        <?php } ?>
                    </a>
                </li>
                <?php
            }
            ?>
            </ul>
            <div class="clearfix"></div>
            <?php

            echo $after_widget;
        }
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['distance'] = strip_tags($new_instance['distance']);
        $instance['limit'] = strip_tags($new_instance['limit']);
        $instance['display_distance'] = strip_tags($new_instance['display_distance']);

        return $instance;
    }

    function form($instance) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'distance' => '', 'limit' => '', 'display_distance' => 'off') );
        $title = $instance['title'];
        $distance = $instance['distance'];
        $limit = $instance['limit'];
        $display_distance = $instance['display_distance'];
        ?>
        <p>
            <?php printf(__('<strong>Hinweis:</strong> Um diese Funktion nutzen zu können, benötigst du einen gefüllten Index von Geodaten. Prüfe deinen Index <a href="%s">hier</a>.', 'datingtheme'), admin_url('edit.php?post_type=location&page=dt-location-geodata')); ?>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Titel:', 'datingtheme'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('distance'); ?>"><?php _e('maximale Distanz (in km):', 'datingtheme'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('distance'); ?>" name="<?php echo $this->get_field_name('distance'); ?>" value="<?php echo $distance; ?>" type="number">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Anzahl der Städte:', 'datingtheme'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" value="<?php echo $limit; ?>" type="number">
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $instance[ 'display_distance' ], 'on' ); ?> id="<?php echo $this->get_field_id( 'display_distance' ); ?>" name="<?php echo $this->get_field_name( 'display_distance' ); ?>" />
            <label for="<?php echo $this->get_field_id( 'display_distance' ); ?>"><?php _e('Zeige die Entferung (in km) an.', 'datingtheme'); ?></label>
        </p>
        <?php
    }
}
register_widget('nearby_locations_widget');