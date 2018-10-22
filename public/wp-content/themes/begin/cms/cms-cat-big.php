<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('cat_big')) { ?>
<div class="line-big sort" name="<?php echo zm_get_option('cat_big_s'); ?>">
	<?php $display_categories =  explode(',',zm_get_option('cat_big_id') ); foreach ($display_categories as $category) { ?>
	<?php if (zm_get_option('no_cat_child')) { ?>
		<?php query_posts( array('cat' => $category ) ); ?>
		<?php query_posts( array( 'showposts' => 1, 'category__in' => array(get_query_var('cat')), 'post__not_in' => $do_not_duplicate ) ); ?>
	<?php } else { ?>
		<?php query_posts( array( 'showposts' => 1, 'cat' => $category, 'post__not_in' => $do_not_duplicate ) ); ?>
	<?php } ?>

	<div class="xl3 xm3">
		<div class="cat-box wow fadeInUp" data-wow-delay="0.3s">
			<h3 class="cat-title"><a href="<?php echo get_category_link($category);?>"><span class="title-i"><span></span><span></span><span></span><span></span></span><?php single_cat_title(); ?><span class="more-i"><span></span><span></span><span></span></span></a></h3>
			<div class="clear"></div>
			<div class="cat-site">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php if (zm_get_option('cat_big_z')) { ?>
						<figure class="small-thumbnail"><?php if (zm_get_option('lazy_s')) { zm_long_thumbnail_h(); } else { zm_long_thumbnail(); } ?></figure>
						<?php the_title( sprintf( '<h2 class="entry-small-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					<?php } else { ?>
						<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<div class="cat-img-small">
							<figure class="thumbnail"><?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?></figure>
							<div class="cat-main">
								<?php if (has_excerpt('')){
										echo wp_trim_words( get_the_excerpt(), 92, '...' );
									} else {
										$content = get_the_content();
										$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
										echo wp_trim_words( $content, 95, '...' );
							        }
								?>
							</div>
						</div>
					<?php } ?>
				<?php endwhile; ?>

				<div class="clear"></div>

				<ul class="cat-list">
					<?php if (zm_get_option('no_cat_child')) { ?>
						<?php query_posts( array( 'showposts' => zm_get_option('cat_big_n'), 'cat' => $category, 'offset' => 1, 'category__in' => array(get_query_var('cat')),  'post__not_in' => $do_not_duplicate ) ); ?>
					<?php } else { ?>
						<?php query_posts( array( 'showposts' => zm_get_option('cat_big_n'), 'cat' => $category, 'offset' => 1, 'post__not_in' => $do_not_duplicate ) ); ?>
					<?php } ?>

					<?php while ( have_posts() ) : the_post(); ?>
						<?php if (zm_get_option('list_date')) { ?>
							<li class="list-date"><?php the_time('m/d'); ?></li>
							<?php if ( get_post_meta($post->ID, 'mark', true) ) {
								the_title( sprintf( '<li class="list-title"><a href="%s" rel="bookmark"><i class="be be-arrowright"></i>', esc_url( get_permalink() ) ), '</a><span class="t-mark">' . $mark = get_post_meta($post->ID, 'mark', true) . '</span></li>' );
							} else {
								the_title( sprintf( '<li class="list-title"><a href="%s" rel="bookmark"><i class="be be-arrowright"></i>', esc_url( get_permalink() ) ), '</a></li>' );
							} ?>
						<?php } else { ?>
							<?php if ( get_post_meta($post->ID, 'mark', true) ) {
								the_title( sprintf( '<li class="list-title-date"><a href="%s" rel="bookmark"><i class="be be-arrowright"></i>', esc_url( get_permalink() ) ), '</a><span class="t-mark">' . $mark = get_post_meta($post->ID, 'mark', true) . '</span></li>' );
							} else {
								the_title( sprintf( '<li class="list-title-date"><a href="%s" rel="bookmark"><i class="be be-arrowright"></i>', esc_url( get_permalink() ) ), '</a></li>' );
							} ?>
						<?php } ?>
					<?php endwhile; ?>
					<?php wp_reset_query(); ?>
				</ul>
			</div>
		</div>
	</div>
	<?php } ?>
	<div class="clear"></div>
</div>
<?php } ?>