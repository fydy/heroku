<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
// 选择颜色
function begin_color(){
	custom_color();
}
function custom_color(){
	if (zm_get_option("blogname_color")) {
		$blogname_color = substr(zm_get_option("blogname_color"), 1);
	}
	if (zm_get_option("blogdescription_color")) {
		$blogdescription_color = substr(zm_get_option("blogdescription_color"), 1);
	}
	if (zm_get_option("link_color")) {
		$link_color = substr(zm_get_option("link_color"), 1);
	}
	if (zm_get_option("menu_color")) {
		$menu_color = substr(zm_get_option("menu_color"), 1);
	}
	if (zm_get_option("button_color")) {
		$button_color = substr(zm_get_option("button_color"), 1);
	}
	if (zm_get_option("cat_color")) {
		$cat_color = substr(zm_get_option("cat_color"), 1);
	}
	if (zm_get_option("slider_color")) {
		$slider_color = substr(zm_get_option("slider_color"), 1);
	}
	if (zm_get_option("h_color")) {
		$h_color = substr(zm_get_option("h_color"), 1);
	}

if ($blogname_color) {
$styles .= ".site-title a {color: #" . $blogname_color . ";}";}

if ($blogdescription_color) {
$styles .= ".site-description {color: #" . $blogdescription_color . ";}";}

if ($menu_color) {
$styles .= "#site-nav .down-menu > li > a:hover,#site-nav .down-menu > li.sfHover > a {background: #" . $menu_color . "}";}

if ($link_color) {
$styles .= "a:hover, .single-content p a, .single-content p a:visited, .top-menu a:hover, #site-nav .down-menu > .current-menu-item > a, #user-profile a:hover, .top-icon .be, .entry-meta a, .entry-meta-no a, .filter-tag:hover {color: #" . $link_color . ";}
.grid-cat-title:hover .title-i span, .cat-title:hover .title-i span, .cat-square-title:hover .title-i span, .widget-title:hover .title-i span, .cat-grid-title:hover .title-i span, .child-title:hover .title-i span, #respond input[type='text']:focus, #respond textarea:focus  {border: 1px solid #" . $link_color . "}
.single-meta li a:hover, #fontsize:hover {background: #" . $link_color . ";border: 1px solid #" . $link_color . "}.ball-pulse > div {border: 1px solid #" . $link_color . "}";}

if ($button_color) {
$styles .= ".searchbar button, #login input[type='submit'], .log-zd, .read-pl a:hover, .group-phone a, .deanm-main .de-button a {background: #" . $button_color . ";}.cat-con-section{border-bottom: 3px solid #" . $button_color . ";}.nav-search:hover:after{color: #" . $button_color . ";}.down a, .meta-nav:hover, #gallery .callbacks_here a, .link-f a:hover, .ias-trigger-next a:hover, .orderby li a:hover, #respond #submit:hover, .comment-tool a:hover, .login-respond, .filter-on, .widget_categories a:hover, .widget_links a:hover, .tagcloud a:hover,#sidebar .widget_nav_menu a:hover, #sidebar-l .widget_nav_menu a:hover, #cms-widget-one .widget_nav_menu li a:hover {background: #" . $button_color . ";border: 1px solid #" . $button_color . "}.entry-more a {background: #" . $button_color . ";}.entry-more a:hover {background: #666;}@media screen and (min-width: 550px) {.pagination span.current, .pagination a:hover, .favorite-e a:hover, .gr-cat-title a, .group-tab-hd .group-current {background: #" . $button_color . ";border: 1px solid #" . $button_color . "}}@media screen and (max-width: 550px) {.pagination .prev, .pagination .next {background: #" . $button_color . ";border: 1px solid #" . $button_color . "}}@media screen and (min-width: 900px) {#scroll li a:hover, .page-links span, .page-links a:hover span {background: #" . $button_color . ";border: 1px solid #" . $button_color . "}}";}

if ($h_color) {
$styles .= ".single-content .directory {border-left: 5px solid #" . $h_color . ";}";}

if ($slider_color) {
$styles .= ".slider-caption, .header-sub h1 {background: #" . $slider_color . ";}.callbacks_tabs .callbacks_here a {background: #" . $slider_color . ";border: 1px solid #" . $slider_color . "}.callbacks_nav{color: #" . $slider_color . ";}";}

if ($cat_color) {
$styles .= ".thumbnail .cat, .format-img-cat, .title-l, .des-t, .des-p {background: #" . $cat_color . ";}";}

if ($styles) {
	echo "<style>" . $styles . "</style>";
}
}

// 定制CSS
function modify_css(){
	custom_css();
}
function custom_css(){
	if (zm_get_option("custom_css")) {
		$css = substr(zm_get_option("custom_css"), 0);
		echo "<style>" . $css . "</style>";
	}
}

// 自定义宽度
function begin_width(){
	custom_width();
}
function custom_width(){
	if (zm_get_option("custom_width")) {
		$width = substr(zm_get_option("custom_width"), 0);
		echo "<style>#content, .header-sub, .top-nav, #top-menu, #mobile-nav, #main-search, .breadcrumb, .footer-widget, .links-box, .g-col, .links-group #links, .menu-img {width: " . $width . "px;}@media screen and (max-width: " . $width . "px) {#content, .breadcrumb, .footer-widget, .links-box, #top-menu, .top-nav, #main-search, #search-main, #mobile-nav, .header-sub, .breadcrumb, .g-col, .links-group #links, .menu-img {width: 98%;}}</style>";
	}
}


function begin_width_adapt(){
	adapt_width();
}
function adapt_width(){
	if (zm_get_option("adapt_width")) {
		$width = substr(zm_get_option("adapt_width"), 0);
		echo "<style>#content, .header-sub, .top-nav, #top-menu, #mobile-nav, #main-search, .breadcrumb, .footer-widget, .links-box, .g-col, .links-group #links, .menu-img {width: " . $width . "%;}@media screen and (max-width: " . $width . "%) {#content, .breadcrumb, .footer-widget, .links-box, #top-menu, .top-nav, #main-search, #search-main, #mobile-nav, .header-sub, .breadcrumb, .g-col, .links-group #links, .menu-img {width: 98%;}}</style>";
	}
}


// 缩略图宽度
function begin_thumbnail_width(){
	thumbnail_width();
}

function thumbnail_width(){
	if (zm_get_option("thumbnail_width")) {
		$thumbnail = substr(zm_get_option("thumbnail_width"), 0);
		echo "<style>.thumbnail {max-width: " . $thumbnail . "px;}@media screen and (max-width: 620px) {.thumbnail {max-width: 100px;}}</style>";
	}
}

// 调整信息位置
function zm_meta_left(){
	meta_left();
}

function meta_left(){
	if (zm_get_option("meta_left")) {
		$meta = substr(zm_get_option("meta_left"), 0);
		echo "<style>.entry-meta {left: " . $meta . "px;}@media screen and (max-width: 620px) {.entry-meta {left: 130px;}}</style>";
	}
}

// 后台添加文章ID
function ssid_column($cols) {
	$cols['ssid'] = 'ID';
	return $cols;
}

function ssid_value($column_name, $id) {
	if ($column_name == 'ssid')
		echo $id;
}

function ssid_return_value($value, $column_name, $id) {
	if ($column_name == 'ssid')
		$value = $id;
	return $value;
}

function ssid_css() {
?>
<style type="text/css">
#ssid { width: 50px;}
</style>
<?php 
}

function ssid_add() {
	add_action('admin_head', 'ssid_css');

	add_filter('manage_posts_columns', 'ssid_column');
	add_action('manage_posts_custom_column', 'ssid_value', 10, 2);

	add_filter('manage_pages_columns', 'ssid_column');
	add_action('manage_pages_custom_column', 'ssid_value', 10, 2);

	add_filter('manage_media_columns', 'ssid_column');
	add_action('manage_media_custom_column', 'ssid_value', 10, 2);

	add_filter('manage_link-manager_columns', 'ssid_column');
	add_action('manage_link_custom_column', 'ssid_value', 10, 2);

	add_action('manage_edit-link-categories_columns', 'ssid_column');
	add_filter('manage_link_categories_custom_column', 'ssid_return_value', 10, 3);

	foreach ( get_taxonomies() as $taxonomy ) {
		add_action("manage_edit-${taxonomy}_columns", 'ssid_column');
		add_filter("manage_${taxonomy}_custom_column", 'ssid_return_value', 10, 3);
	}

	add_action('manage_users_columns', 'ssid_column');
	add_filter('manage_users_custom_column', 'ssid_return_value', 10, 3);

	add_action('manage_edit-comments_columns', 'ssid_column');
	add_action('manage_comments_custom_column', 'ssid_value', 10, 2);
}

function widget_icon() {
	wp_enqueue_style( 'follow', get_template_directory_uri() . '/inc/options/css/fonts/fonts.css', array(), version );
?>

<style type="text/css">
[id*="zmtabs"] h3:before, 
[id*="mday_post"] h3:before, 
[id*="author_widget"] h3:before, 
[id*="about"] h3:before, 
[id*="feed"] h3:before, 
[id*="img_cat"] h3:before, 
[id*="timing_post"] h3:before, 
[id*="same_post"] h3:before, 
[id*="php_text"] h3:before, 
[id*="like_most"] h3:before, 
[id*="slider_post"] h3:before, 
[id*="advert"] h3:before, 
[id*="wpzm-users_favorites"] h3:before, 
[id*="specified_post"] h3:before,  
[id*="wpzm-most_favorited_posts"] h3:before, 
[id*="show_widget"] h3:before, 
[id*="tao_widget"] h3:before, 
[id*="img_widget"] h3:before, 
[id*="video_widget"] h3:before, 
[id*="new_cat"] h3:before, 
[id*="updated_posts"] h3:before, 
[id*="week_post"] h3:before, 
[id*="hot_commend"] h3:before, 
[id*="hot_comment"] h3:before, 
[id*="hot_post_img"] h3:before, 
[id*="cx_tag_cloud"] h3:before, 
[id*="child_cat"] h3:before, 
[id*="user_login"] h3:before, 
[id*="pages_recent_comments"] h3:before, 
[id*="related_post"] h3:before, 
[id*="site_profile"] h3:before, 
[id*="readers"] h3:before, 
[id*="recent_comments"] h3:before, 
[id*="random_post"] h3:before, 
[id*="wpz_widget"] h3:before, 
[id*="tag_post"] h3:before, 
[id*="tree_menu"] h3:before, 
[id*="ids_post"] h3:before{
	content: "\e600";
	font-family: cx;
	font-size: 16px !important;
	color: #0073aa;
	font-weight: normal;
	vertical-align: middle;
	margin: 0 8px 0 0;
}

#menu-appearance .menu-icon:before {
	content: "\e600";
	font-family: cx;
	font-size: 15px !important;
	font-weight: normal;
	line-height: 23px;
	vertical-align: middle;
}
</style>
<?php 
}
add_action('admin_head', 'widget_icon');