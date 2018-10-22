<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<?php if (zm_get_option('cat_big_not')) { ?>
<div class="line-big sort" name="<?php echo zm_get_option('cat_big_not_s'); ?>">
	<?php $display_categories =  explode(',',zm_get_option('cat_big_not_id') ); foreach ($display_categories as $category) { ?>
	<?php if (zm_get_option('no_cat_child')) { ?>
		<?php query_posts( array('cat' => $category ) ); ?>
		<?php query_posts( array( 'showposts' => zm_get_option('cat_big_not_n'), 'cat' => $category, 'category__in' => array(get_query_var('cat')), 'post__not_in' => $do_not_duplicate ) ); ?>
	<?php } else { ?>
		<?php query_posts( array( 'showposts' => zm_get_option('cat_big_not_n'), 'cat' => $category, 'post__not_in' => $do_not_duplicate ) ); ?>
	<?php } ?>

	<div class="xl3 xm3">
		<div class="cat-box wow fadeInUp" data-wow-delay="0.3s">
			<h3 class="cat-title"><a href="<?php echo get_category_link($category);?>"><span class="title-i"><span></span><span></span><span></span><span></span></span><?php single_cat_title(); ?><span class="more-i"><span></span><span></span><span></span></span></a></h3>
			<div class="clear"></div>
			<div class="cat-site">
				<ul class="cat-list">
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