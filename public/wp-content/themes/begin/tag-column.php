<?php
/**
 * 专题模板
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>

<!-- 标题 -->
<section id="primary" class="special">
	<div class="special-img">
		<div class="cat-des wow fadeInUp" data-wow-delay="0.3s">
			<img src="<?php if (function_exists('zm_taxonomy_image_url')) echo zm_taxonomy_image_url(); ?>" alt="<?php single_cat_title(); ?>">
			<div class="des-title">
				<div class="des-t"><?php single_cat_title(); ?></div>
				<?php if (zm_get_option('cat_des_p')) { ?><?php echo the_archive_description( '<div class="des-p">', '</div>' ); ?><?php } ?>
			</div>
		</div>
	</div>



	<!-- 最新两篇文章 -->
	<div class="cms-news-grid">
		<?php 
			$tag = get_queried_object();
			$loop = new WP_Query( array( 'meta_key' => $tag->slug, 'posts_per_page' => 2, 'post__not_in' => get_option( 'sticky_posts') ) );
			while ( $loop->have_posts() ) : $loop->the_post();
		?>
			<article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp'); ?> data-wow-delay="0.3s">
				<figure class="thumbnail">
					<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
				</figure>
				<header class="entry-header">
					<?php if ( get_post_meta($post->ID, 'mark', true) ) {
						the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a><span class="t-mark">' . $mark = get_post_meta($post->ID, 'mark', true) . '</span></h2>' );
					} else {
						the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
					} ?>
				</header>

				<div class="entry-content">
					<div class="archive-content">
						<?php if (has_excerpt('')){
								echo wp_trim_words( get_the_excerpt(), 30, '...' );
							} else {
								$content = get_the_content();
								$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
								echo wp_trim_words( $content, 35, '...' );
					        }
						?>
					</div>
					<span class="entry-meta">
						<?php begin_entry_meta(); ?>
					</span>
					<div class="clear"></div>
				</div>
			</article>
		<?php endwhile; ?>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>

<!-- 倒序显示全部专题文章 -->
	<?php 
		$tag = get_queried_object();
		$loop = new WP_Query( array( 'meta_key' => $tag->slug, 'posts_per_page' => 100, 'order' => ASC,'post__not_in' => get_option( 'sticky_posts') ) );
		while ( $loop->have_posts() ) : $loop->the_post();
	?>

	<article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp'); ?> data-wow-delay="0.3s">
		<?php if (zm_get_option('no_rand_img')) { ?>
			<?php if ( get_post_meta($post->ID, 'thumbnail', true) ) { ?>
				<figure class="thumbnail">
					<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
					<span class="cat"><?php zm_category(); ?></span>
				</figure>
			<?php } else { ?>
				<?php 
					$content = $post->post_content;
					preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
					$n = count($strResult[1]);
					if($n > 0) { ?>
					<figure class="thumbnail">
						<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
						<span class="cat"><?php zm_category(); ?></span>
					</figure>
				<?php } ?>
			<?php } ?>
		<?php } else { ?>
			<figure class="thumbnail">
				<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
				<span class="cat"><?php zm_category(); ?></span>
			</figure>
		<?php } ?>

		<header class="entry-header">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<div class="archive-content">
				<?php if (has_excerpt('')){
						echo wp_trim_words( get_the_excerpt(), zm_get_option('word_n'), '...' );
					} else {
						$content = get_the_content();
						$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
						echo wp_trim_words( $content, zm_get_option('words_n'), '...' );
			        }
				?>
			</div>
			<span class="title-l"></span>
			<?php if ( is_sticky() ) { ?>
				<span class="top-icon"><i class="be be-top"></i></span>
			<?php } else { ?>
				<?php get_template_part( 'inc/new' ); ?>
			<?php } ?>

			<?php if (zm_get_option('no_rand_img')) { ?>
				<?php 
					$content = $post->post_content;
					preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
					$n = count($strResult[1]);
					if($n > 0) : ?>
					<span class="entry-meta">
						<?php begin_entry_meta(); ?>
					</span>
				<?php else : ?>
					<span class="entry-meta-no">
						<?php begin_format_meta(); ?>
					</span>
				<?php endif; ?>
			<?php } else { ?>
				<span class="entry-meta">
					<?php begin_entry_meta(); ?>
				</span>
			<?php } ?>
			<div class="clear"></div>
		</div><!-- .entry-content -->

		<span class="entry-more">
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php echo zm_get_option('more_w'); ?></a>
		</span>
	</article>
	<?php endwhile; ?>
</section>

<!-- 侧边栏 -->
<div id="sidebar" class="widget-area all-sidebar">
	<aside class="special-widget wow fadeInUp" data-wow-delay="0.3s">

		<!-- 修改数字为标签ID -->
		<div class="textwidget widget-text">
			<div class="special-more">
				<a href="<?php echo get_tag_link('97'); ?>">
				<?php $tags = get_tags('include=97'); 
					foreach ($tags as $tag) { 
						echo '<span class="special-s">';
						echo '<img src="';
						echo zm_taxonomy_image_url('97');
						echo '">';
						echo '<h4 class="special-t">';
						echo $tag->name;
						echo '</h4>';
						echo '</span>';
				} ?>
				</a>
			</div>

		<!-- 修改数字为标签ID -->
			<div class="special-more">
				<a href="<?php echo get_tag_link('17'); ?>">
				<?php $tags = get_tags('include=17'); 
					foreach ($tags as $tag) { 
						echo '<span class="special-s">';
						echo '<img src="';
						echo zm_taxonomy_image_url('17');
						echo '">';
						echo '<h4 class="special-t">';
						echo $tag->name;
						echo '</h4>';
						echo '</span>';
				} ?>
				</a>
			</div>

		<!-- 修改数字为标签ID -->
			<div class="special-more">
				<a href="<?php echo get_tag_link('42'); ?>">
				<?php $tags = get_tags('include=42'); 
					foreach ($tags as $tag) { 
						echo '<span class="special-s">';
						echo '<img src="';
						echo zm_taxonomy_image_url('42');
						echo '">';
						echo '<h4 class="special-t">';
						echo $tag->name;
						echo '</h4>';
						echo '</span>';
				} ?>
				</a>
			</div>
			<div class="clear"></div>
		</div>

	<!-- 复制上面代码添加更多 -->

	</aside>


	<!-- 最新文章 -->
	<aside class="widget php_text wow fadeInUp" data-wow-delay="0.3s">
		<h3 class="widget-title"><span class="title-i"><span class="title-i-t"></span><span class="title-i-b"></span><span class="title-i-b"></span><span class="title-i-t"></span></span>最新文章</h3>
		<div class="new_cat">
			<ul>
				<?php $q = 'showposts=8&ignore_sticky_posts=1'; if (!empty($instance['cat'])); query_posts($q); while (have_posts()) : the_post(); ?>
				<li>
					<span class="thumbnail">
						<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
					</span>
					<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
					<span class="date"><?php the_time('m/d') ?></span>
					<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); } ?>
				</li>
				<?php endwhile; ?>
				<?php wp_reset_query(); ?>
			</ul>
		</div>
	</aside>
</div>


<style type="text/css">
.special-img img {
	border-radius: 2px;
}

.special-img .des-t {
	position: absolute;
	top: 38%;
	width: 100%;
	color: #fff;
	font-size: 24px;
	text-align: center;
	padding: 6px 15px;
	background: transparent;
}

@media screen and (max-width: 900px) {
	.special-img .des-t {
		font-size: 16px;
	}
}

.special-widget img {
	max-width: 100%;
	width: auto;
	height: auto;
	display: block;
	/* opacity: 0.8; */
	border-radius: 2px;
}

.special-more {
	margin: 0 0 15px 0;
	background: #000;
}

.special-s {
	position: relative;
	display: block;
}

.special-t {
	position: absolute;
	top: 35%;
	width: 100%;
	color: #fff;
	font-size: 15px;
	text-align: center;
}
</style>

<div class="clear"></div>
<?php get_footer(); ?>