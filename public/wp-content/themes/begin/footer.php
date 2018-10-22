<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
	</div><!-- .site-content -->
	<div class="clear"></div>
	<?php if (zm_get_option('footer_link')) { ?>
		<?php get_template_part( 'template/footer-links' ); ?>
	<?php } ?>
	<?php get_template_part( 'template/footer-widget' ); ?>
	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php echo zm_get_option('footer_inf_t'); ?>
			<span class="add-info">
				<?php echo zm_get_option('footer_inf_b'); ?>
				<?php echo zm_get_option('tongji_f'); ?>
			</span>
		</div><!-- .site-info -->
		<?php if (zm_get_option('footer_menu') && wp_is_mobile()) { ?>
		<div class="footer-clear"></div>
		<nav class="footer-nav">
		<?php
			wp_nav_menu( array(
				'theme_location'=> 'footer',
				'menu_class'	=> 'footer-menu',
				'fallback_cb'	=> 'default_menu'
			) );
		?>
		</nav>
		<?php } ?>
	</footer><!-- .site-footer -->
<?php if (zm_get_option('login')) { ?>
<?php get_template_part( 'template/login' ); ?>
<?php } ?>
<?php get_template_part( 'template/scroll' ); ?>
<?php get_template_part( 'template/the-blank' ); ?>
<?php if (zm_get_option('weibo_t')) { ?>
	<script src="https://tjs.sjs.sinajs.cn/open/api/js/wb.js" type="text/javascript" charset="utf-8"></script>
	<html xmlns:wb="https://open.weibo.com/wb">
<?php } ?>
</div><!-- .site -->
<?php wp_footer(); ?>
</body>
</html>