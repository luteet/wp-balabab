<?php get_header(); 

	$home_id = get_option("page_on_front");
	
	$current_salon_id = get_queried_object()->term_id;
	$current_salon = get_term($current_salon_id, "salons");
	$google_feedback = get_field("google_feedback");

	$query = new WP_Query([
		"posts_per_page" => -1,
		"post_type" => "masters",
		'tax_query' => [
			'relation' => 'AND',
			[
				'taxonomy' => 'salons',
				'field'    => 'id',
				'terms'    => array( $current_salon_id ),
			]
		]
	]);
?>

<main class="main" data-id="<?= $current_salon_id; ?>">
	<section class="rating">
		<div class="rating__inner">
			<form class="rating__block is-active is-visible">
				<h1 class="rating__title">
					<?php
						$title = get_field("rating_block_title", $home_id);
						echo replaceString($title, "i");
					?>
				</h1>
				<div class="rating__field">
					<input type="radio" name="rating" value="5" id="rating-5" required>
					<label for="rating-5">
						<svg width="24" height="24">
							<use href="<?= get_template_directory_uri() . '/assets/' ?>img/sprites.svg#star"></use>
						</svg>
					</label>
					<input type="radio" name="rating" value="4" id="rating-4" required>
					<label for="rating-4">
						<svg width="24" height="24">
							<use href="<?= get_template_directory_uri() . '/assets/' ?>img/sprites.svg#star"></use>
						</svg>
					</label>
					<input type="radio" name="rating" value="3" id="rating-3" required>
					<label for="rating-3">
						<svg width="24" height="24">
							<use href="<?= get_template_directory_uri() . '/assets/' ?>img/sprites.svg#star"></use>
						</svg>
					</label>
					<input type="radio" name="rating" value="2" id="rating-2" required>
					<label for="rating-2">
						<svg width="24" height="24">
							<use href="<?= get_template_directory_uri() . '/assets/' ?>img/sprites.svg#star"></use>
						</svg>
					</label>
					<input type="radio" name="rating" value="1" id="rating-1" required>
					<label for="rating-1">
						<svg width="24" height="24">
							<use href="<?= get_template_directory_uri() . '/assets/' ?>img/sprites.svg#star"></use>
						</svg>
					</label>
				</div>
				<?php if($query->have_posts()) { ?>
				<div class="rating__masters masters">
					<php class="masters__inner swiper">
						<input type="text" name="master-id" hidden class="masters__current visually-hidden">
						<input type="text" name="master-name" hidden class="masters__name visually-hidden">
						<div class="masters__wrapper swiper-wrapper">
							<?php while($query->have_posts()) { $query->the_post(); 
								$avatar = get_field("avatar");
							?>
							<div class="masters__card masters-card swiper-slide" data-id="<?= get_the_ID(); ?>" data-name="<?php the_title(); ?>">
								<div class="masters-card__avatar">
									<?php if($avatar) { ?>
									<?= get_img($avatar, ["size" => 60]); ?>
									<?php } else { ?>
									<svg width="24" height="24">
										<use href="<?= get_template_directory_uri() . '/assets/' ?>img/sprites.svg#avatar"></use>
									</svg>
									<?php } ?>
								</div>
								<div class="masters-card__name">
									<?php the_title(); ?>
								</div>
							</div>
							<?php } ?>
							<?php if($query->post_count >= 2) { ?>
								<?php while($query->have_posts()) { $query->the_post(); 
									$avatar = get_field("avatar");
								?>
								<div class="masters__card masters-card swiper-slide" data-id="<?= get_the_ID(); ?>" data-name="<?php the_title(); ?>">
									<div class="masters-card__avatar">
										<?php if($avatar) { ?>
										<?= get_img($avatar, ["size" => 60]); ?>
										<?php } else { ?>
										<svg width="24" height="24">
											<use href="<?= get_template_directory_uri() . '/assets/' ?>img/sprites.svg#avatar"></use>
										</svg>
										<?php } ?>
									</div>
									<div class="masters-card__name">
										<?php the_title(); ?>
									</div>
								</div>
								<?php } ?>
								<?php while($query->have_posts()) { $query->the_post(); 
									$avatar = get_field("avatar");
								?>
								<div class="masters__card masters-card swiper-slide" data-id="<?= get_the_ID(); ?>" data-name="<?php the_title(); ?>">
									<div class="masters-card__avatar">
										<?php if($avatar) { ?>
										<?= get_img($avatar, ["size" => 60]); ?>
										<?php } else { ?>
										<svg width="24" height="24">
											<use href="<?= get_template_directory_uri() . '/assets/' ?>img/sprites.svg#avatar"></use>
										</svg>
										<?php } ?>
									</div>
									<div class="masters-card__name">
										<?php the_title(); ?>
									</div>
								</div>
								<?php } ?>
							<?php } ?>
						</div>
					</php>
					<button class="masters__arrow is-prev" type="button">
						<svg width="16" height="16">
							<use href="<?= get_template_directory_uri() . '/assets/' ?>img/sprites.svg?v=1#arrow-prev"></use>
						</svg>
					</button>
					<button class="masters__arrow is-next" type="button">
						<svg width="16" height="16">
							<use href="<?= get_template_directory_uri() . '/assets/' ?>img/sprites.svg?v=1#arrow-next"></use>
						</svg>
					</button>
				</div>
				<?php wp_reset_postdata(); } ?>
				<div class="rating__form">
					<label class="rating__form-label">
						<input type="text" name="name" placeholder="<?php the_field("rating_block_input_name", $home_id); ?>" class="rating__form-input">
					</label>
					<label class="rating__form-label visually-hidden">
						<input type="text" name="email" placeholder="Email" class="rating__form-input">
					</label>
					<label class="rating__form-label" data-value="">
						<textarea name="message" row="0" placeholder="<?php the_field("rating_block_textarea_name", $home_id); ?>" class="rating__form-textarea"></textarea>
					</label>
					<button class="rating__form-submit button" type="submit" aria-label="<?php the_field("rating_block_submit_text", $home_id); ?>">
						<span><?php the_field("rating_block_submit_text", $home_id); ?></span>
						<span>
							<div class="loader"></div>
						</span>
					</button>
				</div>
			</form>
			<div class="rating__block" id="thanks-extend">
				<h2 class="rating__title">
					<i><?php the_field("thanks_extend_title", $home_id); ?></i>
				</h2>
				<div class="rating__text">
					<?php the_field("thanks_extend_text", $home_id); ?>
				</div>
				<?php if($google_feedback) { ?>
				<a href="<?= $google_feedback; ?>" class="rating__button button">
					<?php the_field("thanks_extend_google_button", $home_id); ?>
				</a>
				<br>
				<?php } ?>
				<?php 
				$link = get_field("thanks_extend_link", $home_id);
				if($link) { ?>
				<a href="<?= $link["url"]; ?>" class="rating__link link">
					<?= $link["title"]; ?>
				</a>
				<?php } ?>
			</div>
			<div class="rating__block" id="thanks-standard">
				<h2 class="rating__title">
					<i><?php the_field("thanks_standard_title", $home_id); ?></i>
				</h2>
				<div class="rating__text">
					<?php the_field("thanks_standard_text", $home_id); ?>
				</div>
				<?php $link = get_field("thanks_standard_button", $home_id); if($link) { ?>
				<a href="<?= $link["url"]; ?>" class="rating__button button">
					<?= $link["title"]; ?>
				</a>
				<?php } ?>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>