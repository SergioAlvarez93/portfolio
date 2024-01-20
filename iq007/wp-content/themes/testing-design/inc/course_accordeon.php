<div class="course-accordion">
	<div class="row justify-content-center">
		<div class="col-lg-8 col-sm-10">
			<?php if ($value = get_sub_field('accordeon_main_title')) { ?>
				<div class="course-accordion__main-title"><?= $value; ?></div>		
			<?php } ?>
			<?php $index = get_row_index(); ?>	  
			<div class="accordion" id="accordion<?= $index; ?>">
				<?php if (have_rows('course_accordeon_repeater')) { ?>
				<?php while (have_rows('course_accordeon_repeater')) { the_row(); ?>
					<div class="accordion-item border-0">
						<div class="accordion-header" id="heading<?= $index . get_row_index(); ?>">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index . get_row_index(); ?>" aria-expanded="true" aria-controls="collapse<?= $index . get_row_index(); ?>">
								<?php if ($value = get_sub_field('course_accordeon_title')) { ?>
									<?= $value; ?>		
								<?php } ?>	  
							</button>
						</div>
						<div id="collapse<?= $index . get_row_index(); ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $index . get_row_index(); ?>" data-bs-parent="#accordion<?= $index; ?>">
							<div class="accordion-body">
								<?php if ($value = get_sub_field('course_accordeon_txt')) { ?>
									<?= $value; ?>		
								<?php } ?>	  
							</div>
						</div>
					</div>
				<?php } ?>
				<?php } ?>
			</div>
		</div>	
	</div>		  
</div>