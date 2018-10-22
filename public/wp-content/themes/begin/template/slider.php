<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('slider')) { ?>
<div id="slideshow"  class="wow fadeInUp" data-wow-delay="0.3s">
	<ul class="rslides" id="slider">
		<?php if (zm_get_option('show_order')) { ?>
			<?php
				$posts = get_posts( array(
					'numberposts' => zm_get_option('slider_n'),
					'post_type' => 'any', 
					'meta_key' => 'show', 
					'meta_key' => 'show_order',
					'orderby' => 'meta_value',
					'order' => 'date',
					'ignore_sticky_posts' => 1
				) );
			?>
		<?php } else { ?>
			<?php
				$posts = get_posts( array(
					'numberposts' => zm_get_option('slider_n'),
					'post_type' => 'any', 
					'meta_key' => 'show', 
					'ignore_sticky_posts' => 1
				) );
			?>
		<?php } ?>
		<?php if($posts) : foreach( $posts as $post ) : setup_postdata( $post );$do_not_duplicate[] = $post->ID; $do_show[] = $post->ID; ?>
		<?php $image = get_post_meta($post->ID, 'show', true); ?>
		<?php $go_url = get_post_meta($post->ID, 'show_url', true); ?>
			<li>
				<?php if ( get_post_meta($post->ID, 'show_url', true) ) : ?>
				<a href="<?php echo $go_url; ?>" target="_blank" rel="external nofollow"><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>" /></a>
				<?php else: ?>
				<a href="<?php the_permalink() ?>" rel="bookmark"><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>" /></a>
				<?php endif; ?>

				<?php if ( get_post_meta($post->ID, 'no_slide_title', true) ) : ?>
				<?php else: ?>
					<?php if ( get_post_meta($post->ID, 'slide_title', true) ) : ?>
					<?php $slide_title = get_post_meta($post->ID, 'slide_title', true); ?>
						<p class="slider-caption"><?php echo $slide_title; ?></p>
					<?php else: ?>
						<p class="slider-caption"><?php the_title(); ?></p>
					<?php endif; ?>
				<?php endif; ?>
			</li>
			
		<?php endforeach; endif; ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>
<?php } ?>