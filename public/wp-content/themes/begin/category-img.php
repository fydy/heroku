<?php
/**
 * 分类图片布局，使用标准缩略图尺寸
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

	<section id="picture" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php if ((zm_get_option('no_child')) && is_category() ) { ?>
				<?php 
					$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
					query_posts(array('category__in' => array(get_query_var('cat')), 'paged' => $paged,));
				?>
			<?php } ?>
			<?php while ( have_posts() ) : the_post(); ?>
			<article class="picture wow fadeInUp" data-wow-delay="0.3s">
				<div class="picture-box">
					<figure class="picture-img">
						<?php if (zm_get_option('hide_box')) { ?>
							<a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><div class="hide-box"></div></a>
							<a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><div class="hide-excerpt"><?php if (has_excerpt('')){ echo wp_trim_words( get_the_excerpt(), 62, '...' ); } else { echo wp_trim_words( get_the_content(), 72, '...' ); } ?></div></a>
						<?php } ?>
						<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
						<?php if ( has_post_format('video') ) { ?><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-play"></i></a><?php } ?>
						<?php if ( has_post_format('quote') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-display"></i></a></div><?php } ?>
						<?php if ( has_post_format('image') ) { ?><div class="img-ico"><a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><i class="be be-picture"></i></a></div><?php } ?>
					</figure>
					<?php the_title( sprintf( '<h2 class="grid-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					<span class="grid-inf">
						<span class="g-cat"><i class="be be-folder"></i> <?php zm_category(); ?></span>
						<span class="grid-inf-l">
							<span class="date"><i class="be be-schedule"></i> <?php the_time( 'm/d' ); ?></span>
							<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
							<?php if ( get_post_meta($post->ID, 'zm_like', true) ) : ?><span class="grid-like"><span class="be be-thumbs-up-o">&nbsp;<?php zm_get_current_count(); ?></span></span><?php endif; ?>
						</span>
		 			</span>
		 			<div class="clear"></div>
				</div>
			</article>
			<?php endwhile;?>
		</main><!-- .site-main -->
		<?php begin_pagenav(); ?>
	</section><!-- .content-area -->
<?php get_footer(); ?>