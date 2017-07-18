<?php
/*
 * Vars
 */
global $indicator, $arrows, $interval, $fade, $images;

if('location' == get_post_type() && is_single()) {
	$teaser_image = get_field('location_teaser_image', 'option');
	$teaser_text = (get_field('location_teaser_placeholder', 'option') ? get_field('location_teaser_placeholder', 'option') : 'Treff jetzt heiße Dates in %s');

	$images[] = array(
		'text'          => '<h1>'. sprintf($teaser_text, ' <span>' . $post->post_title . '</span>') . '</h1>'
	);

	if($teaser_image) {
		$images[0]['image'] = array(
			'url'       => $teaser_image['url'],
			'width'     => $teaser_image['width'],
			'height'    => $teaser_image['height'],
			'alt'       => $teaser_image['alt']
		);
	}
}

if($images) {
	$rand = rand(100,900);
	$srcset = array();
	?>
	<section id="teaser" class="<?php echo at_get_section_layout_class('teaser'); ?>">
		<div id="carousel-teaser-<?php echo $rand; ?>" class="carousel slide <?php if('1' == $fade) echo 'carousel-fade'; ?>" data-ride="carousel" data-interval="<?php echo $interval; ?>">
			<?php if("1" != $indicator && count($images) > '1') { ?>
				<ol class="carousel-indicators">
					<?php for($i=0; $i<count($images); $i++) { ?>
						<li data-target="#carousel-teaser-<?php echo $rand; ?>" data-slide-to="<?php echo $i; ?>" <?php if($i==0) echo 'class="active"'; ?>></li>
					<?php } ?>
				</ol>
			<?php } ?>

			<div class="carousel-inner" role="listbox">
				<?php
				$i=0; foreach($images as $image) {
					$url = (isset($image['url']) ? $image['url'] : '');
					$external = (isset($image['external']) ? $image['external'] : '');
					$nofollow = (isset($image['nofollow']) ? $image['nofollow'] : '');

					/**
					 * Load Responsive Images
					 */
					$image_smartphone = (isset($image['image_smartphone']) ? $image['image_smartphone'] : '');

					if($image_smartphone) {
						$srcset[] = $image_smartphone['url'] . ' 768w';
					}
					?>
					<div class="item<?php if($i==0) echo ' active'; if(!$image['image']) echo ' item-noimg';?>"<?php if(isset($image['background'])) echo ' style="background-color:'.$image['background'].'"'; ?>>
						<?php
						if($url) {
							echo '<a href="' . $url . '" ' . ($external == '1' ? 'target="_blank"' : '') . ' ' . ($nofollow == '1' ? 'rel="nofollow"' : '') . '>';
						}
						if($image['image']) { ?>
							<img
								src="<?php echo $image['image']['url']; ?>"
								<?php
								if($srcset) {
									?>
									srcset="<?php echo $image['image']['url'] . ', ' . implode(', ', $srcset); ?>"
									<?php
								}
								?>
								width="<?php echo $image['image']['width']; ?>"
								height="<?php echo $image['image']['height']; ?>"
								alt="<?php echo $image['image']['alt']; ?>"
							/>
							<?php
						}
						if($image['text']) { ?>
							<div class="container">
								<div class="carousel-caption">
									<?php echo $image['text']; ?>
								</div>
							</div>
						<?php }

						if($url) {
							echo '</a>';
						}
						?>
					</div>
					<?php $i++; } ?>
			</div>

			<?php if("1" != $arrows  && count($images) > '1') { ?>
				<a class="left carousel-control" href="#carousel-teaser-<?php echo $rand; ?>" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				</a>
				<a class="right carousel-control" href="#carousel-teaser-<?php echo $rand; ?>" role="button" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				</a>
			<?php } ?>
		</div>
	</section>
<?php } wp_reset_query(); ?>