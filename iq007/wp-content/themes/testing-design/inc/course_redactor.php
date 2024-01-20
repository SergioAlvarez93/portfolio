<div class="course-redactor">
	<div class="row justify-content-center">
		<div class="col-lg-8 col-sm-10">
			<?php if ($value = get_sub_field('title')) { ?>
				<div class="course-redactor__inner-title text-center"><?= $value; ?></div>
			<?php } ?>
			<?php if ($value = get_sub_field('course_wysiwyg')) { ?>
				<div class="course__wysiwyg-item"><?= $value; ?></div>
			<?php } ?>
		</div>	
	</div>		  
</div>