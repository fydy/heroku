<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div id="search-main">
	<div class="off-search wow fadeInRight animated" data-wow-delay="0.4s"></div>
	<div class="search-wrap">
		<?php if (zm_get_option('wp_s')) { ?><?php get_search_form(); ?><?php } ?>

		<?php if (zm_get_option('baidu_s')) { ?>
		<div class="searchbar">
			<form method="get" id="baiduform" action="<?php echo get_permalink( zm_get_option('baidu_url') ); ?>" target="_blank">
				<span class="search-input">
					<input type="hidden" value="1" name="entry">
					<input class="swap_value" placeholder="<?php _e( '输入百度站内搜索关键词', 'begin' ); ?>" name="q">
					<button type="submit" id="searchbaidu"><i class="be be-baidu"></i></button>
				</span>
			</form>
		</div>
		<?php } ?>

		<?php if (zm_get_option('360_s')) { ?>
		<div class="searchbar">
			<form action="https://www.so.com/s" target="_blank" id="so360form">
				<span class="search-input">
					<input type="text" autocomplete="off"  placeholder="<?php _e( '输入360站内搜索关键词', 'begin' ); ?>" name="q" id="so360_keyword">
					<button type="submit" id="so360_submit">360</button>
					<input type="hidden" name="ie" value="utf-8">
					<input type="hidden" name="src" value="zz_<?php echo $_SERVER['SERVER_NAME']; ?>">
					<input type="hidden" name="site" value="<?php echo $_SERVER['SERVER_NAME']; ?>">
					<input type="hidden" name="rg" value="1">
					<input type="hidden" name="inurl" value="">
				</span>
			</form>
		</div>
		<?php } ?>

		<?php if (zm_get_option('sogou_s')) { ?>
		<div class="searchbar">
			<form action="https://www.sogou.com/web" target="_blank" name="sogou_queryform">
				<span class="search-input">
					<input type="text" placeholder="<?php _e( '输入搜狗站内搜索关键词', 'begin' ); ?>" name="query">
					<button type="submit" id="sogou_submit"  onclick="check_insite_input(document.sogou_queryform, 1)">搜狗</button>
					<input type="hidden" name="insite" value="<?php echo $_SERVER['SERVER_NAME']; ?>">
				</span>
			</form>
		</div>
		<?php } ?>
		<div class="clear"></div>

		<?php if (zm_get_option('search_nav')) { ?>
		
		<nav class="search-nav">
			<h4><?php _e( '搜索热点', 'begin' ); ?></h4>
			<div class="clear"></div>
			<?php
				wp_nav_menu( array(
					'theme_location'=> 'search',
					'menu_class'	=> 'search-menu',
					'fallback_cb'	=> 'default_menu'
				) );
			?>
		</nav>
		<?php } ?>
	</div>
</div>