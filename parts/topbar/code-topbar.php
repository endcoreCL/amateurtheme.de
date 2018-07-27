<section id="topbar">
	<?php
	$topbar_columns = get_field('design_topbar_columns', 'options');
	if ($topbar_columns) { ?>
		<div class="container">
			<div class="row">
				<?php
				$c=1;
				foreach ($topbar_columns as $k => $v) {
					$class = ($v['class'] ? $v['class'] : 'col-sm');
					?>
					<div class="<?php echo $class; ?>">
						<div class="topbar_bottom_inner text-<?php echo $v['align']; ?>">
							<?php
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
</section>