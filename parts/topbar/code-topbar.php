<?php
$topbar_bg = ( get_field( 'design_topbar_bg', 'options' ) ? get_field( 'design_topbar_bg', 'options' ) : 'white' );

$attributes = array(
    'id'    => array( 'topbar' ),
    'class' => array()
);

$attributes['class'][] = at_design_bg_classes( 'topbar', $topbar_bg );
?>
<section <?php echo at_attribute_array_html( $attributes ); ?>>
    <?php
    $topbar_columns = get_field( 'design_topbar_columns', 'options' );
    if ( $topbar_columns ) { ?>
        <div class="container">
            <div class="row">
                <?php
                $c = 1;
                foreach ( $topbar_columns as $k => $v ) {
                    $class = ( $v['class'] ? $v['class'] : 'col-sm' );
                    ?>
                    <div class="<?php echo $class; ?>">
                        <div class="topbar_bottom_inner text-<?php echo $v['align']; ?>">
                            <?php
                            $type = $v['type'];

                            if ( $type == 'text' ) {
                                echo '<p>' . $v['text'] . '</p>';
                            } elseif ( $type == 'menu' ) {
                                $menu_id = $v['menu'];
                                wp_nav_menu(
                                    array(
                                        'menu'        => $menu_id,
                                        'menu_id'     => false,
                                        'menu_class'  => 'list-inline',
                                        'container'   => false,
                                        'depth'       => 2,
                                        'fallback_cb' => 'xcore_nav_walker_topbar::fallback',
                                        'walker'      => new xcore_nav_walker_topbar()
                                    )
                                );
                            }
                            ?>
                        </div>
                    </div>
                    <?php

                    $c++;
                }
                ?>
            </div>
        </div>
    <?php } ?>
</section>