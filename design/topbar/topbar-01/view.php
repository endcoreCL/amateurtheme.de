<?php
/**
 * Topbar Name: Topbar 01
 */
global $xcore_layout;
$topbar_wrapper_class = $xcore_layout->wrapper_class('topbar', 'wrapper');
$topbar_top = $xcore_layout->option('topbar', 'top');
$topbar_bottom = $xcore_layout->option('topbar', 'bottom');
?>

<section id="topbar" class="topbar-01<?php echo ($topbar_wrapper_class ? ' ' . $topbar_wrapper_class : ''); ?>">

    <?php
    if($topbar_top) {
        $topbar_top_container = $xcore_layout->container_class('topbar', 'top_container');
        $topbar_top_columns = $xcore_layout->option('topbar_top', 'columns');
        ?>
        <div class="collapse" id="collapseTopbar">
            <div class="topbar_top">
                <div class="<?php echo $topbar_top_container; ?>">
                    <div class="row">
                        <?php
                        foreach ($topbar_top_columns as $k => $v) {
                            $class = ($v['class'] ? $v['class'] : 'col-sm');
                            ?>
                            <div class="<?php echo $class; ?>">
                                <div class="topbar_top_inner text-<?php echo $v['align']; ?>">
                                    <?php dynamic_sidebar('topbar_' . $k); ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    if($topbar_bottom) {
        $topbar_bottom_container = $xcore_layout->container_class('topbar', 'bottom_container');
        $topbar_bottom_columns = $xcore_layout->option('topbar_bottom', 'columns');
        ?>
        <div class="topbar_bottom">
            <?php if ($topbar_bottom_columns) { ?>
                <div class="<?php echo $topbar_bottom_container; ?>">
                    <div class="row">
                        <?php
                        $c=1;
                        foreach ($topbar_bottom_columns as $k => $v) {
                            $class = ($v['class'] ? $v['class'] : 'col-sm');
                            ?>
                            <div class="<?php echo $class; ?>">
                                <div class="topbar_bottom_inner text-<?php echo $v['align']; ?>">
                                    <?php
                                    // include collapse button
                                    if($c == count($topbar_bottom_columns) && $topbar_top) {
                                        ?>
                                        <a class="float-right" data-toggle="collapse" href="#collapseTopbar" role="button" aria-expanded="false" aria-controls="collapseTopbar">
                                            <i class="fa fa-chevron-down"></i>
                                        </a>
                                        <?php
                                    }

                                    $type = $v['type'];

                                    if ($type == 'text') {
                                        echo '<p>' . $v['text'] . '</p>';
                                    } else if ($type == 'menu') {
                                        $menu_id = $v['menu'];
                                        wp_nav_menu(
                                            array(
                                                'menu'          => $menu_id,
                                                'menu_id'       => false,
                                                'menu_class'    => 'list-inline',
                                                'container'     => false,
                                                'depth'         => 2,
                                                'fallback_cb'   => 'xcore_nav_walker_topba::fallback',
                                                'walker'        => new xcore_nav_walker_topbar()
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
        </div>
        <?php
    }
    ?>

</section>