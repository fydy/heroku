<?php
// 添加视频
function my_videos( $atts, $content = null ) {
	extract( shortcode_atts( array (
		'href' => '',
		 'img' => '<img class="aligncenter" src="'.$content.'">'
	), $atts ) );
	return '<div class="video-content"><a class="videos" href="'.$href.'" title="播放视频">'.$img.'<i class="be be-play"></i></a></div>';
}

// 评论可见
function reply_read($atts, $content=null) {
	extract(shortcode_atts(array("notice" => '
	<div class="reply-read">
		<div class="reply-ts">
			<div class="read-sm"><i class="be be-info"></i>' . sprintf(__( '此处为隐藏的内容！', 'begin' )) . '</div>
			<div class="read-sm"><i class="be be-loader"></i>' . sprintf(__( '发表评论并刷新，才能查看', 'begin' )) . '</div>
		</div>
		<div class="read-pl"><a href="#respond"><i class="be be-speechbubble"></i>' . sprintf(__( '发表评论', 'begin' )) . '</a></div>
		<div class="clear"></div>
    </div>'), $atts));
	$email = null;
	$user_ID = (int) wp_get_current_user()->ID;
	if ($user_ID > 0) {
		$email = get_userdata($user_ID)->user_email;
		if ( current_user_can('level_10') ) {
			return '<p class="secret-password"><i class="be be-clipboard"></i>隐藏的内容：<br />'.do_shortcode( $content ).'</p>';
		}
	} else if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {
		$email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);
	} else {
		return $notice;
	}
    if (empty($email)) {
		return $notice;
	}
	global $wpdb;
	$post_id = get_the_ID();
	$query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";
	if ($wpdb->get_results($query)) {
		return do_shortcode('<p class="secret-password"><i class="be be-clipboard"></i>隐藏的内容：<br />'.do_shortcode( $content ).'</p>');
	} else {
		return $notice;
	}
}

// 登录可见
function login_to_read($atts, $content = null) {
	extract(shortcode_atts(array("notice" =>'
	<div class="reply-read">
		<div class="reply-ts">
			<div class="read-sm"><i class="be be-info"></i>' . sprintf(__( '此处为隐藏的内容！', 'begin' )) . '</div>
			<div class="read-sm"><i class="be be-loader"></i>' . sprintf(__( '登录后才能查看！', 'begin' )) . '</div>
		</div>
		<div class="read-pl"><a href="#login" class="flatbtn" id="login-see" ><i class="be be-timerauto"></i>' . sprintf(__( '登录', 'begin' )) . '</a></div>
		<div class="clear"></div>
	</div>'), $atts));
	if (is_user_logged_in()) {
		return do_shortcode( $content );
	} else {
		return $notice;
	}
}

// 加密内容
function secret($atts, $content=null){
extract(shortcode_atts(array('key'=>null), $atts));
if ( current_user_can('level_10') ) {
	return '<p class="secret-password"><i class="be be-clipboard"></i>加密的内容：<br />'.do_shortcode( $content ).'</p>';
}
if(isset($_POST['secret_key']) && $_POST['secret_key']==$key){
	return '<p class="secret-password"><i class="be be-clipboard"></i>加密的内容：<br />'.do_shortcode( $content ).'</p>';
	} else {
		return '
		<form class="post-password-form" action="'.get_permalink().'" method="post">
			<div class="post-secret"><i class="be be-info"></i>' . sprintf(__( '输入密码查看加密内容：', 'begin' )) . '</div>
			<p>
				<input id="pwbox" type="password" size="20" name="secret_key">
				<input type="submit" value="' . sprintf(__( '提交', 'begin' )) . '" name="Submit">
			</p>
		</form>	';
	}
}

// 解压密码
function reply_password($atts, $content=null) {
	extract(shortcode_atts(array("notice" => '<div class="reply_pass">' . sprintf(__( '<strong>下载密码：</strong>发表评论并刷新可见！', 'begin' )) . '</div>'), $atts));
	$email = null;
	$user_ID = (int) wp_get_current_user()->ID;
	if ($user_ID > 0) {
		$email = get_userdata($user_ID)->user_email;
		if ( current_user_can('level_10') ) {return ''.do_shortcode( $content ).'';}
	} else if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {
		$email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);
	} else {
		return $notice;
	}
    if (empty($email)) {
		return $notice;
	}
	global $wpdb;
	$post_id = get_the_ID();
	$query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";
	if ($wpdb->get_results($query)) {
		return do_shortcode(''.do_shortcode( $content ).'');
	} else {
		return $notice;
	}
}

// 幻灯
function gallery($atts, $content=null){
	return '<div id="gallery" class="slide-h"><div class="rslides" id="slide">'.$content.'</div></div>
	<div class="img-n">共<span class="myimg"></span></div>
	<script type="text/javascript" src="'.get_template_directory_uri().'/js/slides.js"></script>';
}

function image($atts, $content=null){
extract(shortcode_atts(array('h'=>null), $atts));
	return '<div id="gallery" class="slides-h" style="height:'.$h.'px"><div class="rslides" id="slides">'.$content.'</div></div>
	<div class="img-n">共<span class="mimg"></span></div>
	<script type="text/javascript" src="'.get_template_directory_uri().'/js/slides.js"></script>';
}

// 下载按钮
function button_a($atts, $content = null) {
	return '<div class="down"><a class="d-popup" title="下载链接" href="#button_file"><i class="be be-download"></i>下载地址</a><div class="clear"></div></div>';
}

// 自定义按钮
function button_b($atts, $content = null) {
	return '<div class="down"><a class="d-popup" title="下载链接" href="#button_file"><i class="be be-download"></i>'.$content.'</a><div class="clear"></div></div>';
}

// 链接按钮
function button_url($atts,$content=null){
	extract(shortcode_atts(array("href"=>'http://'),$atts));
	return '<div class="down down-link"><a href="'.$href.'" rel="external nofollow" target="_blank"><i class="be be-download"></i>'.$content.'</a><div class="clear"></div></div><div class="down-line"></div>';
}

// fieldset标签
function fieldset_label($atts, $content = null) {
	return $content;
}

// 添加<code>
function addcode($atts, $content=null, $code="") {
 $return = '<code>';
 $return .= $content;
 $return .= '</code>';
 return $return;
}

// 添加宽图
function add_full_img($atts, $content=null, $full_img="") {
 $return = '<div class="full_img">';
 $return .= $content;
 $return .= '</div>';
 return $return;
}

// 文字展开
function show_more($atts, $content = null) {
	return '<span class="show-more" title="' . (__( '文字折叠', 'begin' )) . '"><span><i class="be be-squareplus"></i>' . sprintf(__( '展开', 'begin' )) . '</span></span>';
}

function section_content($atts, $content = null) {
	return '<div class="section-content">'.do_shortcode( $content ).'</p></div><p>';
}

// 短代码广告
function post_ad(){

if ( wp_is_mobile() ) {
		return '<div class="post-ad"><div class="ad-m ad-site">'.stripslashes( zm_get_option('ad_s_z_m') ).'</div></div>';
	} else {
		return '<div class="post-ad"><div class="ad-pc ad-site">'.stripslashes( zm_get_option('ad_s_z') ).'</div></div>';
	}
}

// 添加按钮
function begin_select(){
echo '
<select id="sc_select">
	<option value="您需要选择一个短代码">插入短代码</option>
	<option value="[url href=链接地址]按钮名称[/url]">链接按钮</option>
	<option value="[videos href=视频代码]图片链接[/videos]">添加视频</option>
	<option value="[img]插入图片[/img]">添加相册</option>
	<option value="[reply]隐藏的内容[/reply]">回复可见</option>
	<option value="[login]隐藏的内容[/login]">登录可见</option>
	<option value="[password key=密码]加密的内容[/password]">密码保护</option>
	<option value="[code]代码[/code]">添加代码</option>
	<option value="[full_img][/full_img]">添加宽图</option>
	<option value="[s][p]隐藏的文字[/p]">文字折叠</option>
	<option value="<fieldset><legend>我是标题</legend>这里是内容</fieldset>">fieldset标签</option>
	<option value="[ad]">插入广告</option>
</select>';
}

function begin_button() {
echo '<script type="text/javascript">
jQuery(document).ready(function(){
	jQuery("#sc_select").change(function() {
	send_to_editor(jQuery("#sc_select :selected").val());
	return false;
	});
});
</script>';
}

// 可视化按钮
function tinymce_button() {
	if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
		add_filter( 'mce_buttons', 'begin_register_tinymce_button' );
		add_filter( 'mce_external_plugins', 'begin_add_tinymce_button' );
	}
}

function begin_register_tinymce_button( $buttons ) {
	array_push( $buttons, "", "url", "videos", "img", "reply", "login", "password", "addcode", "add_full_img", "addfolding", "field", "ad" );
	return $buttons;
}

function begin_add_tinymce_button( $plugin_array ) {
	$plugin_array['begin_button_script'] = get_bloginfo( 'template_url' ) . '/js/buttons.js';
	return $plugin_array;
}