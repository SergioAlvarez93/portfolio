<?php if (have_rows('course_icons_repeater')) { ?>
<div class="course__icons-box">
	<div class="row">
		<?php while (have_rows('course_icons_repeater')) {
			the_row();
		?>
			<div class="col-md-4 col-sm-6 gy-5 text-center">
				<?php if ($value = get_sub_field('course_icon_img')) { ?>
					<img class="course__icons-box-img" src="<?= $value; ?>" alt=""> 		
				<?php } ?>
				<?php if ($value = get_sub_field('course_icon_title')) { ?>
					<div class="course__icons-box-title mt-5"><?= $value; ?></div>		
				<?php } ?>	  
			</div>		
		<?php } ?>
	</div>
</div>
<?php } ?>