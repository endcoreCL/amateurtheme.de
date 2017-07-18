<?php $mobile = get_field('design_nav_mobile_searchform', 'option'); ?>
<form class="navbar-form navbar-right form-search <?php if('1' == $mobile && '12' == at_header_structure()) echo 'visible-xs'; ?>" action="<?php echo esc_url( home_url() ); ?>" role="search">
	<div class="input-group">
		<input type="text" class="form-control" name="s" id="name" placeholder="<? echo __('Suche nach' , 'amateurtheme'); ?>">
		<span class="input-group-btn">
			<button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
		</span>
	</div>
</form>