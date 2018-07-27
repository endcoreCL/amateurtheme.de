            <?php
            if ( function_exists('yoast_breadcrumb') && ('bottom' == get_field('design_breadcrumbs_pos', 'option'))) { ?>
                <section id="breadcrumbs">
                    <div class="container">
                        <?php
                        $breadcrumbs = yoast_breadcrumb('<nav><ol class="breadcrumb"><li class="breadcrumb-item">','</li></ol></nav>', false);
                        echo str_replace( '»', '</li><li class="breadcrumb-item">', $breadcrumbs );
                        ?>
                    </div>
                </section>
                <?php
            }
            ?>
        </div>

        <footer id="footer">
            <?php
            $footer_columns = get_field('design_footer_columns', 'options');
            if ($footer_columns) { ?>
                <div class="container">
                    <div class="row">
                        <?php
                        $c=1;
                        foreach ($footer_columns as $k => $v) {
                            $class = ($v['class'] ? $v['class'] : 'col-sm');
                            ?>
                            <div class="<?php echo $class; ?>">
                                <div class="footer_bottom_inner text-<?php echo $v['align']; ?>">
                                    <?php
                                    $type = $v['type'];

                                    if ($type == 'text') {
	                                    // year replacement
                                        $v['text'] = str_replace('%%year%%', date('Y'), $v['text']);
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
                                                'fallback_cb'   => 'xcore_nav_walker_topbar::fallback',
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
        </footer>
	</body>

	<?php wp_footer(); ?>

	<?php
	/* Support old Browsers (eg IE6-9) */
	if(get_field('src_ie_support', 'options')) {
		?>
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<?php
	}
	?>
</html>