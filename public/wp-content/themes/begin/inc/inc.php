<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
// Ajax加载
function ajax_scroll_js() {
if ( !is_singular() && !is_paged() ) { ?>
<script type="text/javascript">var ias=$.ias({container:"#main",item:"article",pagination:"#nav-below",next:"#nav-below .nav-previous a",});ias.extension(new IASTriggerExtension({text:'<i class="be be-circledown"></i>更多',offset:<?php echo zm_get_option('scroll_n');?>,}));ias.extension(new IASSpinnerExtension());ias.extension(new IASNoneLeftExtension({text:'已是最后',}));ias.on('rendered',function(items){$("img").lazyload({effect: "fadeIn",failure_limit: 70});})</script>
<?php }
}

function ajax_c_scroll_js() {
if ( is_single() ) { ?>
<script type="text/javascript">var ias=$.ias({container:"#comments",item:".comment-list",pagination:".scroll-links",next:".scroll-links .nav-previous a",});ias.extension(new IASTriggerExtension({text:'<i class="be be-circledown"></i>更多',offset: 0,}));ias.extension(new IASSpinnerExtension());ias.extension(new IASNoneLeftExtension({text:'已是最后',}));ias.on('rendered',function(items){$("img").lazyload({effect: "fadeIn",failure_limit: 10});});</script>
<?php }
}

// 只搜索文章标题
function wpse_11826_search_by_title( $search, $wp_query ) {
	if ( ! empty( $search ) && ! empty( $wp_query->query_vars['search_terms'] ) ) {
		global $wpdb;
		$q = $wp_query->query_vars;
		$n = ! empty( $q['exact'] ) ? '' : '%';
		$search = array();
		foreach ( ( array ) $q['search_terms'] as $term )
			$search[] = $wpdb->prepare( "$wpdb->posts.post_title LIKE %s", $n . $wpdb->esc_like( $term ) . $n );
		if ( ! is_user_logged_in() )
			$search[] = "$wpdb->posts.post_password = ''";
		$search = ' AND ' . implode( ' AND ', $search );
	}
	return $search;
}

// gravatar头像调用
function cn_avatar($avatar) {
	$avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/','<img src="http://cn.gravatar.com/avatar/$1?s=$2&d=mm" alt="avatar" class="avatar avatar-$2" height="$2" width="$2">',$avatar);
	return $avatar;
}

function ssl_avatar($avatar) {
	$avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/','<img src="https://secure.gravatar.com/avatar/$1?s=$2&d=mm" alt="avatar" class="avatar avatar-$2" height="$2" width="$2">',$avatar);
	return $avatar;
}

if (zm_get_option('no') !== 'no') :
	if (!zm_get_option('gravatar_url') || (zm_get_option("gravatar_url") == 'cn')) {
		add_filter('get_avatar', 'cn_avatar');
	}

	if (zm_get_option('gravatar_url') == 'ssl') {
		add_filter('get_avatar', 'ssl_avatar');
	}
endif;

if (zm_get_option('first_avatar')) {require get_template_directory() . '/inc/first-letter-avatar.php';}
// 标签
require get_template_directory() . '/inc/tag-letter.php';

// 字数统计
function count_words ($text) {
	global $post;
	if ( '' == $text ) {
		$text = $post->post_content;
		if (mb_strlen($output, 'UTF-8') < mb_strlen($text, 'UTF-8')) $output .= ''.sprintf(__( '共', 'begin' )).' ' . mb_strlen(preg_replace('/\s/','',html_entity_decode(strip_tags($post->post_content))),'UTF-8') . ' '.sprintf(__( '字', 'begin' )).'';
		return $output;
	}
}

// 分类优化
function zm_category() {
	$category = get_the_category();
	if($category[0]){
	echo '<a href="'.get_category_link($category[0]->term_id ).'">'.$category[0]->cat_name.'</a>';
	}
}

// 索引
function zm_get_current_count() {
	global $wpdb;
	$current_post = get_the_ID();
	$query = "SELECT post_id, meta_value, post_status FROM $wpdb->postmeta";
	$query .= " LEFT JOIN $wpdb->posts ON post_id=$wpdb->posts.ID";
	$query .= " WHERE post_status='publish' AND meta_key='zm_like' AND post_id = '".$current_post."'";
	$results = $wpdb->get_results($query);
	if ($results) {
		foreach ($results as $o):
			echo $o->meta_value;
		endforeach;
	}else {echo( '0' );}
}

if (zm_get_option('index_c')) {
// 目录
function article_catalog($content) {
	$matches = array();
	$ul_li = '';
	$r = "/<h4>([^<]+)<\/h4>/im";

	if(preg_match_all($r, $content, $matches)) {
		foreach($matches[1] as $num => $title) {
			$content = str_replace($matches[0][$num], '<span class="directory"></span><h4 id="title-'.$num.'">'.$title.'</h4>', $content);
			$ul_li .= '<li><i class="be be-arrowright"></i> <a href="#title-'.$num.'" title="'.$title.'">'.$title."</a></li>\n";
		}
		$content = "
			\n<div id=\"log-box\">
				<div id=\"catalog\"><ul id=\"catalog-ul\">\n" . $ul_li . "</ul><span class=\"log-zd\"><span class=\"log-close\"><a title=\"" . sprintf(__( '隐藏目录', 'begin' )) . "\"><i class=\"be be-cross\"></i><strong>" . sprintf(__( '目录', 'begin' )) . "</strong></a></span></span></div>
			</div>\n" . $content;
	}
	return $content;
}
add_filter( "the_content", "article_catalog" );
}

if (zm_get_option('tag_c')) {
// 关键词加链接
$match_num_from = 1; //一个关键字少于多少不替换
$match_num_to = zm_get_option('chain_n');

add_filter('the_content','tag_link',1);

function tag_sort($a, $b){
	if ( $a->name == $b->name ) return 0;
	return ( strlen($a->name) > strlen($b->name) ) ? -1 : 1;
}

function tag_link($content){
global $match_num_from,$match_num_to;
$posttags = get_the_tags();
	if ($posttags) {
		usort($posttags, "tag_sort");
		foreach($posttags as $tag) {
			$link = get_tag_link($tag->term_id);
			$keyword = $tag->name;
			if (preg_match_all('|(<h[^>]+>)(.*?)'.$keyword.'(.*?)(</h[^>]*>)|U', $content, $matchs)) {continue;}
			if (preg_match_all('|(<a[^>]+>)(.*?)'.$keyword.'(.*?)(</a[^>]*>)|U', $content, $matchs)) {continue;}

			$cleankeyword = stripslashes($keyword);
			$url = "<a href=\"$link\" title=\"".str_replace('%s',addcslashes($cleankeyword, '$'),__('查看与 %s 相关的文章', 'begin' ))."\"";
			$url .= ' target="_blank"';
			$url .= ">".addcslashes($cleankeyword, '$')."</a>";
			$limit = rand($match_num_from,$match_num_to);

			$content = preg_replace( '|(<a[^>]+>)(.*)('.$ex_word.')(.*)(</a[^>]*>)|U'.$case, '$1$2%&&&&&%$4$5', $content);
			$content = preg_replace( '|(<img)(.*?)('.$ex_word.')(.*?)(>)|U'.$case, '$1$2%&&&&&%$4$5', $content);
			$cleankeyword = preg_quote($cleankeyword,'\'');
			$regEx = '\'(?!((<.*?)|(<a.*?)))('. $cleankeyword . ')(?!(([^<>]*?)>)|([^>]*?</a>))\'s' . $case;
			$content = preg_replace($regEx,$url,$content,$limit);
			$content = str_replace( '%&&&&&%', stripslashes($ex_word), $content);
		}
	}
	return $content;
}
}

// 图片alt
if (zm_get_option('image_alt')) {
function image_alt($c) {
	global $post;
	$title = $post->post_title;
	$s = array('/src="(.+?.(jpg|bmp|png|jepg|gif))"/i' => 'src="$1" alt="'.$title.'"');
	foreach($s as $p => $r){
	$c = preg_replace($p,$r,$c);
	}
	return $c;
}
add_filter( 'the_content', 'image_alt' );
}

// 形式名称
function begin_post_format( $safe_text ) {
	if ( $safe_text == '引语' )
		return '软件';
	return $safe_text;
}

// 分页
function begin_pagenav() {
if (zm_get_option('scroll')) {
	global $wp_query;
	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="nav-below">
			<div class="nav-next"><?php previous_posts_link(''); ?></div>
			<div class="nav-previous"><?php next_posts_link(''); ?></div>
		</nav>
	<?php endif;
}
	the_posts_pagination( array(
		'mid_size'           => 4,
		'prev_text'          => '<i class="be be-arrowleft"></i>',
		'next_text'          => '<i class="be be-arrowright"></i>',
		'before_page_number' => '<span class="screen-reader-text">'.sprintf(__( '第', 'begin' )).' </span>',
		'after_page_number'  => '<span class="screen-reader-text"> '.sprintf(__( '页', 'begin' )).'</span>',
	) );
}

// 点击最多文章
function get_timespan_most_viewed($mode = '', $limit = 10, $days = 7, $display = true) {
	global $wpdb, $post;
	$limit_date = current_time('timestamp') - ($days*86400);
	$limit_date = date("Y-m-d H:i:s",$limit_date);	
	$where = '';
	$temp = '';
	if(!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");
	if($most_viewed) {
		$i = 1;
		foreach ($most_viewed as $post) {
			$post_title =  get_the_title();
			$post_views = intval($post->views);
			$post_views = number_format($post_views);
			$temp .= "<li><span class='li-icon li-icon-$i'>$i</span><a href=\"".get_permalink()."\">$post_title</a></li>";
			$i++;
		}
	} else {
		$temp = '<li>暂无文章</li>';
	}
	if($display) {
		echo $temp;
	} else {
		return $temp;
	}
}

// 热门文章
function get_timespan_most_viewed_img($mode = '', $limit = 10, $days = 7, $display = true) {
	global $wpdb, $post;
	$limit_date = current_time('timestamp') - ($days*86400);
	$limit_date = date("Y-m-d H:i:s",$limit_date);	
	$where = '';
	$temp = '';
	if(!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");
	if($most_viewed) {
		foreach ($most_viewed as $post) {
			$post_title = get_the_title();
			$post_views = intval($post->views);
			$post_views = number_format($post_views);
			echo "<li>";
			echo "<span class='thumbnail'>";
			echo zm_thumbnail();
			echo "</span>"; 
			echo the_title( sprintf( '<span class="new-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></span>' ); 
			echo "<span class='date'>";
			echo the_time('m/d');
			echo "</span>";
			echo the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>');
			echo "</li>"; 
		}
	}
}

function get_timespan_most_viewed_img_h($mode = '', $limit = 10, $days = 7, $display = true) {
	global $wpdb, $post;
	$limit_date = current_time('timestamp') - ($days*86400);
	$limit_date = date("Y-m-d H:i:s",$limit_date);	
	$where = '';
	$temp = '';
	if(!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");
	if($most_viewed) {
		foreach ($most_viewed as $post) {
			$post_title = get_the_title();
			$post_views = intval($post->views);
			$post_views = number_format($post_views);
			echo "<li>";
			echo "<span class='thumbnail'>";
			echo zm_thumbnail_h();
			echo "</span>"; 
			echo the_title( sprintf( '<span class="new-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></span>' ); 
			echo "<span class='date'>";
			echo the_time('m/d');
			echo "</span>";
			echo the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>');
			echo "</li>"; 
		}
	}
}

//点赞最多文章
function get_like_most($mode = '', $limit = 10, $days = 7, $display = true) {
	global $wpdb, $post;
	$limit_date = current_time('timestamp') - ($days*86400);
	$limit_date = date("Y-m-d H:i:s",$limit_date);
	$where = '';
	$temp = '';
	if(!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS zm_like FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'zm_like' AND post_password = '' ORDER  BY zm_like DESC LIMIT $limit");
	if($most_viewed) {
		$i = 1;
		foreach ($most_viewed as $post) {
			$post_title = get_the_title();
			$post_like = intval($post->like);
			$post_like = number_format($post_like);
			$temp .= "<li><span class='li-icon li-icon-$i'>$i</span><a href=\"".get_permalink()."\">$post_title</a></li>";
			$i++;
		}
	} else {
		$temp = '<li>暂无文章</li>';
	}
	if($display) {
		echo $temp;
	} else {
		return $temp;
	}
}

// 点赞最多有图
function get_like_most_img($mode = '', $limit = 10, $days = 7, $display = true) {
	global $wpdb, $post;
	$limit_date = current_time('timestamp') - ($days*86400);
	$limit_date = date("Y-m-d H:i:s",$limit_date);
	$where = '';
	$temp = '';
	if(!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS zm_like FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'zm_like' AND post_password = '' ORDER  BY zm_like DESC LIMIT $limit");
	if($most_viewed) {
		$i = 1;
		foreach ($most_viewed as $post) {
			$post_title = get_the_title();
			$post_like = intval($post->like);
			$post_like = number_format($post_like);
			echo "<li>";
			echo "<span class='thumbnail'>";
			echo zm_thumbnail();
			echo "</span>";
			echo the_title( sprintf( '<span class="new-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></span>' );
			echo "<span class='discuss'><i class='be be-thumbs-up-o'>&nbsp;";
			echo zm_get_current_count();
			echo "</i></span>";
			echo "<span class='date'>";
			echo the_time( 'm/d' );
			echo "</span>";
			echo "</li>";
		}
	}
}

function get_like_most_img_h($mode = '', $limit = 10, $days = 7, $display = true) {
	global $wpdb, $post;
	$limit_date = current_time('timestamp') - ($days*86400);
	$limit_date = date("Y-m-d H:i:s",$limit_date);
	$where = '';
	$temp = '';
	if(!empty($mode) && $mode != 'both') {
		$where = "post_type = '$mode'";
	} else {
		$where = '1=1';
	}
	$most_viewed = $wpdb->get_results("SELECT $wpdb->posts.*, (meta_value+0) AS zm_like FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'zm_like' AND post_password = '' ORDER  BY zm_like DESC LIMIT $limit");
	if($most_viewed) {
		$i = 1;
		foreach ($most_viewed as $post) {
			$post_title = get_the_title();
			$post_like = intval($post->like);
			$post_like = number_format($post_like);
			echo "<li>";
			echo "<span class='thumbnail'>";
			echo zm_thumbnail_h();
			echo "</span>";
			echo the_title( sprintf( '<span class="new-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></span>' );
			echo "<span class='discuss'><i class='be be-thumbs-up-o'>&nbsp;";
			echo zm_get_current_count();
			echo "</i></span>";
			echo "<span class='date'>";
			echo the_time( 'm/d' );
			echo "</span>";
			echo "</li>";
		}
	}
}

// 点赞
function begin_ding(){
	global $wpdb,$post;
	$id = $_POST["um_id"];
	$action = $_POST["um_action"];
	if ( $action == 'ding'){
		$bigfa_raters = get_post_meta($id,'zm_like',true);
		$expire = time() + 99999999;
		$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
		setcookie('zm_like_'.$id,$id,$expire,'/',$domain,false);
		if (!$bigfa_raters || !is_numeric($bigfa_raters)) {
			update_post_meta($id, 'zm_like', 1);
		}
		else {
			update_post_meta($id, 'zm_like', ($bigfa_raters + 1));
		}
		echo get_post_meta($id,'zm_like',true);
	}
	die;
}

if (zm_get_option('baidu_submit')) {
// 主动推送
if(!function_exists('Baidu_Submit')){
    function Baidu_Submit($post_ID) {
		$WEB_DOMAIN = get_option('home');
		if(get_post_meta($post_ID,'Baidusubmit',true) == 1) return;
		$url = get_permalink($post_ID);
		$api = 'http://data.zz.baidu.com/urls?site='.$WEB_DOMAIN.'&token='.zm_get_option('token_p');
		$request = new WP_Http;
		$result = $request->request( $api , array( 'method' => 'POST', 'body' => $url , 'headers' => 'Content-Type: text/plain') );
		$result = json_decode($result['body'],true);
		if (array_key_exists('success',$result)) {
		    add_post_meta($post_ID, 'Baidusubmit', 1, true);
		}
	}
	add_action('publish_post', 'Baidu_Submit', 0);
}
}

// 评论贴图
if (zm_get_option('embed_img')) {
add_action('comment_text', 'comments_embed_img', 2);
}
function comments_embed_img($comment) {
	$size = 'auto';
	$comment = preg_replace(array('#(http://([^\s]*)\.(jpg|gif|png|JPG|GIF|PNG))#','#(https://([^\s]*)\.(jpg|gif|png|JPG|GIF|PNG))#'),'<img src="$1" alt="评论" style="width:'.$size.'; height:'.$size.'" />', $comment);
	return $comment;
}

// title
if (zm_get_option('wp_title')) {
} else {
function begin_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}
	$title .= get_bloginfo( 'name', 'display' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'twentyfourteen' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'begin_wp_title', 10, 2 );
}

if (zm_get_option('refused_spam')) {
	// 禁止无中文留言
	if ( current_user_can('level_10') ) {
	} else {
	function refused_spam_comments( $comment_data ) {
		$pattern = '/[一-龥]/u';  
		if(!preg_match($pattern,$comment_data['comment_content'])) {
			err('评论必须含中文！');
		}
		return( $comment_data );
	}
	add_filter('preprocess_comment','refused_spam_comments');
	}
}
// @回复
if (zm_get_option('at')) {
function comment_at( $comment_text, $comment = '') {
	if( $comment->comment_parent > 0) {
		$comment_text = '<span class="at">@<a href="#comment-' . $comment->comment_parent . '">'.get_comment_author( $comment->comment_parent ) . '</a></span> ' . $comment_text;
	}
	return $comment_text;
}
add_filter( 'comment_text' , 'comment_at', 20, 2);
}

// 浏览总数
function all_view(){
global $wpdb;
$count=0;
$views= $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE meta_key='views'");
foreach($views as $key=>$value)
	{
		$meta_value=$value->meta_value;
		if($meta_value!=' '){
			$count+=(int)$meta_value;
		}
	}
return $count;
}

// 编辑_blank
function autoblank($text) {
	$return = str_replace('<a', '<a target="_blank"', $text);
	return $return;
}
add_filter('edit_post_link', 'autoblank');

// 登录
function custom_login_head(){
$imgurl=zm_get_option('login_img');
$logourl=zm_get_option('logo');
echo'<style type="text/css">
body{
	font-family: "Microsoft YaHei", Helvetica, Arial, Lucida Grande, Tahoma, sans-serif;
	background: url('.$imgurl.');
	width:100%;
	height:100%;
}
.login h1 a {
	background:url('.$logourl.') no-repeat;
	background-size: 220px 50px;
	width: 220px;
	height: 50px;
	padding: 0;
	margin: 0 auto 1em;
}
.login form, .login .message {
	background: #fff;
	background: rgba(255, 255, 255, 0.6);
	border-radius: 2px;
	border: 1px solid #fff;
}
.login label {
	color: #000;
	font-weight: bold;
}
.login .message {
	color: #000;
}
#backtoblog a, #nav a {
	color: #fff !important;
}
</style>';

}
if (zm_get_option('custom_login')) {
	add_action('login_head', 'custom_login_head');
}

// 登录提示
function  zm_login_title() {
	return '欢迎您光临本站！';
}
add_filter('login_headertitle', 'zm_login_title');
add_filter('login_headerurl', create_function(false,"return get_bloginfo('url');"));

// 列表按钮
function spces_code_plugin() {
	if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
		return;
	}
	if (get_user_option('rich_editing') == 'true') {
		add_filter('mce_external_plugins', 'specs_mce_external_plugins_filter');
		add_filter('mce_buttons', 'specs_mce_buttons_filter');
	}
}

function specs_mce_external_plugins_filter($plugin_array) {
	$plugin_array['specs_code_plugin'] = get_template_directory_uri() . '/inc/addlist/list-btn.js';
	return $plugin_array;
}

function specs_mce_buttons_filter($buttons) {
	array_push($buttons, 'specs_code_plugin');
	return $buttons;
}

add_shortcode('wplist', 'wplist_shortcode');
function wplist_shortcode($atts, $content = '') {
	$atts['content'] = $content;
	$out = '<div class="wplist-item"><a href="' . $atts['link'] . '" target="_blank" isconvert="1" rel="nofollow" >';
	$out.= '<div class="wplist-item-img"><img itemprop="image" src="' . $atts['img'] . '" alt="' . $atts['title'] . '" /></div>';
	$out.= '<div class="wplist-title">' . $atts['title'] . '</div>';
	$out.= '<p class="wplist-des">' . $atts['content'] . '</p>';
	if (!empty($atts['price'])) {
		$out.= '<div class="wplist-oth"><div class="wplist-res wplist-price">' . $atts['price'] . '</div>';
		if (!empty($atts['oprice'])) {
			$out.= '<div class="wplist-res wplist-old-price"><del>' . $atts['oprice'] . '</del></div>';
		}
		$out.= '</div>';
	}
	$out.= '<div class="wplist-btn">' . $atts['btn'] . '</div><div class="clear"></div>';
	$out.= '</a><div class="clear"></div></div>';
	return $out;
}

// 后台样式
function admin_style(){
	echo'<style type="text/css">body{ font-family: Microsoft YaHei;}#activity-widget #the-comment-list .avatar {width: 48px;height: 48px;}.show-id {float: left;color: #999;width: 50%;margin: 0;padding: 3px 0;}.clear {clear: both;margin: 0 0 8px 0}</style>';
}
add_action('admin_head', 'admin_style');

// 外链跳转
if (zm_get_option('link_to')) {
	add_filter('the_content','link_to_jump',999);
	function link_to_jump($content){
		preg_match_all('/<a(.*?)href="(.*?)"(.*?)>/',$content,$matches);
		if($matches){
		    foreach($matches[2] as $val){
			    if(strpos($val,'://')!==false && strpos($val,home_url())===false && !preg_match('/\.(jpg|jepg|png|ico|bmp|gif|tiff)/i',$val) && !preg_match('/(ed2k|thunder|Flashget|flashget|qqdl):\/\//i',$val)){
			    	$content=str_replace("href=\"$val\"", "href=\"".get_template_directory_uri()."/inc/go.php?url=$val\" ",$content);
				}
			}
		}
		return $content;
	}

	// 评论者链接跳转并新窗口打开
	function commentauthor($comment_ID = 0) {
		$url    = get_comment_author_url( $comment_ID );
		$author = get_comment_author( $comment_ID );
		if ( empty( $url ) || 'http://' == $url )
		echo $author;
		else
		echo "<a href='".get_template_directory_uri()."/inc/go.php?url=$url' rel='external nofollow' target='_blank' class='url'>$author</a>";
	}

	// 下载外链跳转
	function link_nofollow($url) {
		if(strpos($url,'://')!==false && strpos($url,home_url())===false && !preg_match('/(ed2k|thunder|Flashget|flashget|qqdl):\/\//i',$url)) {
			$url = str_replace($url, get_template_directory_uri()."/inc/go.php?url=".$url,$url);
		}
		return $url;
	}
}

// 网址跳转
function sites_nofollow($url) {
	$url = str_replace($url, get_template_directory_uri()."/inc/go.php?url=".$url,$url);
	return $url;
}

// 添加斜杠
function nice_trailingslashit($string, $type_of_url) {
	if ( $type_of_url != 'single' && $type_of_url != 'page' && $type_of_url != 'single_paged' )
		$string = trailingslashit($string);
	return $string;
}
if (zm_get_option('category_x')) {
add_filter('user_trailingslashit', 'nice_trailingslashit', 10, 2);
}
function html_page_permalink() {
	global $wp_rewrite;
	if ( !strpos($wp_rewrite->get_page_permastruct(), '.html')){
		$wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
	}
}

// 禁止登录后台
function redirect_non_admin_user() {
	if ( ! current_user_can( 'manage_options' ) && '/wp-admin/admin-ajax.php' != $_SERVER['PHP_SELF'] ) {
		wp_redirect( home_url() );
		exit;
	}
}
// if (zm_get_option('no_admin')) {add_action( 'admin_init', 'redirect_non_admin_user' );}
function begin_user_contact($user_contactmethods){
	//去掉默认联系方式
	unset($user_contactmethods['aim']);
	unset($user_contactmethods['yim']);
	unset($user_contactmethods['jabber']);

	//添加自定义联系方式
	$user_contactmethods['qq'] = 'QQ';
	$user_contactmethods['weixin'] = '微信';
	$user_contactmethods['weibo'] = '微博';
	$user_contactmethods['phone'] = '电话';

    return $user_contactmethods;
}

// 用户文章
function num_of_author_posts($authorID=''){
	if ($authorID) {
		$author_query = new WP_Query( 'posts_per_page=-1&author='.$authorID );
		$i=0;
		while ($author_query->have_posts()) : $author_query->the_post(); ++$i; endwhile; wp_reset_postdata();
		return $i;
	}
	return false;
}

// 密码提示
function change_protected_title_prefix() {
	return '%s';
}
add_filter('protected_title_format', 'change_protected_title_prefix');

// 评论等级
if (zm_get_option('vip')) {
	function get_author_class($comment_author_email,$user_id){
		global $wpdb;
		$author_count = count($wpdb->get_results(
		"SELECT comment_ID as author_count FROM $wpdb->comments WHERE comment_author_email = '$comment_author_email' "));
		$adminEmail = get_option('admin_email');if($comment_author_email ==$adminEmail) return;
		if($author_count>=0 && $author_count<2)
			echo '<a class="vip vip0" title="评论达人 VIP.0"><i class="be be-favoriteoutline"></i><span class="lv">0</span></a>';
		else if($author_count>=2 && $author_count<5)
			echo '<a class="vip vip1" title="评论达人 VIP.1"><i class="be be-favorite"></i><span class="lv">1</span></a>';
		else if($author_count>=5 && $author_count<10)
			echo '<a class="vip vip2" title="评论达人 VIP.2"><i class="be be-favorite"></i><span class="lv">2</span></a>';
		else if($author_count>=10 && $author_count<20)
			echo '<a class="vip vip3" title="评论达人 VIP.3"><i class="be be-favorite"></i><span class="lv">3</span></a>';
		else if($author_count>=20 && $author_count<50)
			echo '<a class="vip vip4" title="评论达人 VIP.4"><i class="be be-favorite"></i><span class="lv">4</span></a>';
		else if($author_count>=50 && $author_count<100)
			echo '<a class="vip vip5" title="评论达人 VIP.5"><i class="be be-favorite"></i><span class="lv">5</span></a>';
		else if($author_count>=100 && $author_count<200)
			echo '<a class="vip vip6" title="评论达人 VIP.6"><i class="be be-favorite"></i><span class="lv">6</span></a>';
		else if($author_count>=200 && $author_count<300)
			echo '<a class="vip vip7" title="评论达人 VIP.7"><i class="be be-favorite"></i><span class="lv">7</span></a>';
		else if($author_count>=300 && $author_count<400)
			echo '<a class="vip vip8" title="评论达人 VIP.8"><i class="be be-favorite"></i><span class="lv">8</span></a>';
		else if($author_count>=400)
			echo '<a class="vip vip9" title="评论达人 VIP.9"><i class="be be-favorite"></i><span class="lv">9</span></a>';
	}
}

// admin
function get_author_admin($comment_author_email,$user_id){
	global $wpdb;
	$author_count = count($wpdb->get_results(
	"SELECT comment_ID as author_count FROM $wpdb->comments WHERE comment_author_email = '$comment_author_email' "));
	$adminEmail = get_option('admin_email');if($comment_author_email ==$adminEmail) echo '<span class="author-admin">Admin</span>';
}

// 自定义图标
class iconfont {
	function __construct(){
		add_filter( 'nav_menu_css_class', array( $this, 'nav_menu_css_class' ) );
		add_filter( 'walker_nav_menu_start_el', array( $this, 'walker_nav_menu_start_el' ), 10, 4 );
	}
	function nav_menu_css_class( $classes ){
		if( is_array( $classes ) ){
			$tmp_classes = preg_grep( '/^(zm)(-\S+)?$/i', $classes );
			if( !empty( $tmp_classes ) ){
				$classes = array_values( array_diff( $classes, $tmp_classes ) );
			}
		}
		return $classes;
	}

	protected function replace_item( $item_output, $classes ){
		$spacer = 1 == $settings[ 'spacing' ] ? ' ' : '';
		if( !in_array( 'zm', $classes ) ){
			array_unshift( $classes, 'zm' );
		}
		$before = true;
		$icon = '<i class="' . implode( ' ', $classes ) . '"></i>';
		preg_match( '/(<a.+>)(.+)(<\/a>)/i', $item_output, $matches );
		if( 4 === count( $matches ) ){
			$item_output = $matches[1];
			if( $before ){
				$item_output .= $icon . '<span class="font-text">' . $spacer . $matches[2] . '</span>';
			} else {
				$item_output .= '<span class="font-text">' . $matches[2] . $spacer . '</span>' . $icon;
			}
			$item_output .= $matches[3];
		}
		return $item_output;
	}

	function walker_nav_menu_start_el( $item_output, $item, $depth, $args ){
		if( is_array( $item->classes ) ){
			$classes = preg_grep( '/^(zm)(-\S+)?$/i', $item->classes );
			if( !empty( $classes ) ){
				$item_output = $this->replace_item( $item_output, $classes );
			}
		}
		return $item_output;
	}
}
new iconfont();

// 图标
class be_font {
	function __construct(){
		add_filter( 'nav_menu_css_class', array( $this, 'nav_menu_css_class' ) );
		add_filter( 'walker_nav_menu_start_el', array( $this, 'walker_nav_menu_start_el' ), 10, 4 );
	}
	function nav_menu_css_class( $classes ){
		if( is_array( $classes ) ){
			$tmp_classes = preg_grep( '/^(be)(-\S+)?$/i', $classes );
			if( !empty( $tmp_classes ) ){
				$classes = array_values( array_diff( $classes, $tmp_classes ) );
			}
		}
		return $classes;
	}

	protected function replace_item( $item_output, $classes ){
		$spacer = 1 == $settings[ 'spacing' ] ? ' ' : '';
		if( !in_array( 'be', $classes ) ){
			array_unshift( $classes, 'be' );
		}
		$before = true;
		$icon = '<i class="' . implode( ' ', $classes ) . '"></i>';
		preg_match( '/(<a.+>)(.+)(<\/a>)/i', $item_output, $matches );
		if( 4 === count( $matches ) ){
			$item_output = $matches[1];
			if( $before ){
				$item_output .= $icon . '<span class="font-text">' . $spacer . $matches[2] . '</span>';
			} else {
				$item_output .= '<span class="font-text">' . $matches[2] . $spacer . '</span>' . $icon;
			}
			$item_output .= $matches[3];
		}
		return $item_output;
	}

	function walker_nav_menu_start_el( $item_output, $item, $depth, $args ){
		if( is_array( $item->classes ) ){
			$classes = preg_grep( '/^(be)(-\S+)?$/i', $item->classes );
			if( !empty( $classes ) ){
				$item_output = $this->replace_item( $item_output, $classes );
			}
		}
		return $item_output;
	}
}
new be_font();

// 冒充
function usercheck($incoming_comment) {
	$isSpam = 0;
	if (trim($incoming_comment['comment_author']) == ''.zm_get_option('admin_name').'')
	$isSpam = 1;
	if (trim($incoming_comment['comment_author_email']) == ''.zm_get_option('admin_email').'')
	$isSpam = 1;
	if(!$isSpam)
	return $incoming_comment;
	err('<i class="be be-info"></i>请勿冒充管理员发表评论！');
}

// 页面添加标签
class PTCFP{
	function __construct(){
	add_action( 'init', array( $this, 'taxonomies_for_pages' ) );
		if ( ! is_admin() ) {
			add_action( 'pre_get_posts', array( $this, 'tags_archives' ) );
		}
	}
	function taxonomies_for_pages() {
		register_taxonomy_for_object_type( 'post_tag', 'page' );
	}
	function tags_archives( $wp_query ) {
	if ( $wp_query->get( 'tag' ) )
		$wp_query->set( 'post_type', 'any' );
	}
}
$ptcfp = new PTCFP();

// 分类标签
function get_category_tags($args) {
	global $wpdb;
	$tags = $wpdb->get_results ("
		SELECT DISTINCT terms2.term_id as tag_id, terms2.name as tag_name
		FROM
			$wpdb->posts as p1
			LEFT JOIN $wpdb->term_relationships as r1 ON p1.ID = r1.object_ID
			LEFT JOIN $wpdb->term_taxonomy as t1 ON r1.term_taxonomy_id = t1.term_taxonomy_id
			LEFT JOIN $wpdb->terms as terms1 ON t1.term_id = terms1.term_id,

			$wpdb->posts as p2
			LEFT JOIN $wpdb->term_relationships as r2 ON p2.ID = r2.object_ID
			LEFT JOIN $wpdb->term_taxonomy as t2 ON r2.term_taxonomy_id = t2.term_taxonomy_id
			LEFT JOIN $wpdb->terms as terms2 ON t2.term_id = terms2.term_id
		WHERE
			t1.taxonomy = 'category' AND p1.post_status = 'publish' AND terms1.term_id IN (".$args['categories'].") AND
			t2.taxonomy = 'post_tag' AND p2.post_status = 'publish'
			AND p1.ID = p2.ID
			ORDER by tag_name
	");
	$count = 0;

    if($tags) {
		foreach ($tags as $tag) {
			$mytag[$count] = get_term_by('id', $tag->tag_id, 'post_tag');
			$count++;
		}
	} else {
      $mytag = NULL;
    }
    return $mytag;
}

// 获取当前页面地址
function currenturl() {
	$current_url = home_url(add_query_arg(array()));
	if (is_single()) {
		$current_url = preg_replace('/(\/comment|page|#).*$/','',$current_url);
	} else {
		$current_url = preg_replace('/(comment|page|#).*$/','',$current_url);
	}
	echo $current_url;
}

// 自定义类型面包屑
function begin_taxonomy_terms( $product_id, $taxonomy, $args = array() ) {
    $terms = wp_get_post_terms( $product_id, $taxonomy, $args );
  return apply_filters( 'begin_taxonomy_terms' , $terms, $product_id, $taxonomy, $args );
}

// 子分类
function get_category_id($cat) {
	$this_category = get_category($cat);
	while($this_category->category_parent) {
		$this_category = get_category($this_category->category_parent);
	}
	return $this_category->term_id;
}


function child_cat() {
	if(get_category_children(get_category_id(the_category_ID(false)))!= "" ){
		echo '<div class="header-sub"><ul class="child-cat wow fadeInUp" data-wow-delay="0.3s">';
		echo wp_list_categories("child_of=".get_category_id(the_category_ID(false)). "&depth=1&hide_empty=0&title_li=&orderby=id&order=ASC");
		echo '</ul></div>';
	}
}

// 评论加nofollow
function nofollow_comments_popup_link(){
	return ' rel="external nofollow"';
}

// 图片数量
if( !function_exists('get_post_images_number') ){
	function get_post_images_number(){
		global $post;
		$content = $post->post_content; 
		preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $result, PREG_PATTERN_ORDER);
		return count($result[1]);
	}
}

// 头部冗余代码
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action( 'wp_head', 'feed_links', 2 );
remove_action( 'wp_head', 'feed_links_extra', 3 );
remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

// 编辑器增强
function enable_more_buttons($buttons) {
	$buttons[] = 'hr';
	$buttons[] = 'del';
	$buttons[] = 'sub';
	$buttons[] = 'sup';
	$buttons[] = 'fontselect';
	$buttons[] = 'fontsizeselect';
	$buttons[] = 'cleanup';
	$buttons[] = 'styleselect';
	$buttons[] = 'wp_page';
	$buttons[] = 'anchor';
	$buttons[] = 'backcolor';
	return $buttons;
}
add_filter( "mce_buttons_2", "enable_more_buttons" );

// 禁止代码标点转换
remove_filter( 'the_content', 'wptexturize' );

if (zm_get_option('xmlrpc_no')) {
// 禁用xmlrpc
add_filter('xmlrpc_enabled', '__return_false');
}

// 禁止评论自动超链接
remove_filter('comment_text', 'make_clickable', 9);

// 禁止评论HTML
if (zm_get_option('comment_html')) {
add_filter('comment_text', 'wp_filter_nohtml_kses');
add_filter('comment_text_rss', 'wp_filter_nohtml_kses');
add_filter('comment_excerpt', 'wp_filter_nohtml_kses');
}


// 链接管理
add_filter( 'pre_option_link_manager_enabled', '__return_true' );

// 显示全部设置
function all_settings_link() {
    add_options_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
}
if (zm_get_option('all_settings')) {
add_action('admin_menu', 'all_settings_link');
}
// 屏蔽自带小工具
function unregister_default_wp_widgets() {
    unregister_widget('WP_Widget_Recent_Comments');
    unregister_widget('WP_Widget_Tag_Cloud');
}
add_action('widgets_init', 'unregister_default_wp_widgets', 1);

// 禁用版本修订
if (zm_get_option('revisions_no')) {
	add_filter( 'wp_revisions_to_keep', 'disable_wp_revisions_to_keep', 10, 2 );
}
function disable_wp_revisions_to_keep( $num, $post ) {
	return 0;
}

// 禁用自动保存
if (zm_get_option('autosave_no')) {
add_action('admin_print_scripts', create_function( '$a', "wp_deregister_script('autosave');"));
}

// 禁止后台加载谷歌字体
function wp_remove_open_sans_from_wp_core() {
	wp_deregister_style( 'open-sans' );
	wp_register_style( 'open-sans', false );
	wp_enqueue_style('open-sans','');
}
add_action( 'init', 'wp_remove_open_sans_from_wp_core' );

// 禁用emoji
 function disable_emojis() {
 	remove_action( 'wp_print_styles', 'print_emoji_styles' );
 }
 add_action( 'init', 'disable_emojis' );

// 禁用oembed/rest
function disable_embeds_init() {
	global $wp;
	$wp->public_query_vars = array_diff( $wp->public_query_vars, array(
		'embed',
	) );
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );
	add_filter( 'embed_oembed_discover', '__return_false' );
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' );
	add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
}
if (zm_get_option('embed_no')) {
	add_action( 'init', 'disable_embeds_init', 9999 );
}

remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );

function disable_embeds_tiny_mce_plugin( $plugins ) {
	return array_diff( $plugins, array( 'wpembed' ) );
}
function disable_embeds_rewrites( $rules ) {
	foreach ( $rules as $rule => $rewrite ) {
		if ( false !== strpos( $rewrite, 'embed=true' ) ) {
			unset( $rules[ $rule ] );
		}
	}
	return $rules;
}
function disable_embeds_remove_rewrite_rules() {
	add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'disable_embeds_remove_rewrite_rules' );
function disable_embeds_flush_rewrite_rules() {
	remove_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'disable_embeds_flush_rewrite_rules' );

// 禁止dns-prefetch
function remove_dns_prefetch( $hints, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		return array_diff( wp_dependencies_unique_hosts(), $hints );
	}
	return $hints;
}
add_filter( 'wp_resource_hints', 'remove_dns_prefetch', 10, 2 );

// 禁用REST API
if (zm_get_option('disable_api')) {
	add_filter('rest_enabled', '_return_false');
	add_filter('rest_jsonp_enabled', '_return_false');
}

// 移除wp-json链接
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

if (zm_get_option('my_author')) {
// 替换用户链接
add_filter( 'request', 'my_author' );
function my_author( $query_vars ) {
	if ( array_key_exists( 'author_name', $query_vars ) ) {
		global $wpdb;
		$author_id = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key='first_name' AND meta_value = %s", $query_vars['author_name'] ) );
		if ( $author_id ) {
			$query_vars['author'] = $author_id;
			unset( $query_vars['author_name'] );
		}
	}
	return $query_vars;
}

add_filter( 'author_link', 'my_author_link', 10, 3 );
function my_author_link( $link, $author_id, $author_nicename ) {
	$my_name = get_user_meta( $author_id, 'first_name', true );
	if ( $my_name ) {
		$link = str_replace( $author_nicename, $my_name, $link );
	}
	return $link;
}
}
// 屏蔽用户名称类
function remove_comment_body_author_class( $classes ) {
	foreach( $classes as $key => $class ) {
	if(strstr($class, "comment-author-")||strstr($class, "author-")) {
			unset( $classes[$key] );
		}
	}
	return $classes;
}

// 最近更新过
function recently_updated_posts($num=10,$days=7) {
	if( !$recently_updated_posts = get_option('recently_updated_posts') ) {
		query_posts('post_status=publish&orderby=modified&posts_per_page=-1');
		$i=0;
		while ( have_posts() && $i<$num ) : the_post();
			if (current_time('timestamp') - get_the_time('U') > 60*60*24*$days) {
				$i++;
				$the_title_value=get_the_title();
				$recently_updated_posts.='<li><i class="be be-arrowright"></i><a href="'.get_permalink().'" title="'.$the_title_value.'">'
				.$the_title_value.'</a></li>';
			}
		endwhile;
		wp_reset_query();
		if ( !empty($recently_updated_posts) ) update_option('recently_updated_posts', $recently_updated_posts);
	}
	$recently_updated_posts=($recently_updated_posts == '') ? '<li>目前没有文章被更新</li>' : $recently_updated_posts;
	echo $recently_updated_posts;
}

function clear_cache_recently() {
	update_option('recently_updated_posts', '');
}
add_action('save_post', 'clear_cache_recently');

// code button
if (zm_get_option('gcp_code')) {require get_template_directory() . '/inc/code/code-button.php';}

// edd custom-fields
if (zm_get_option('edd')) {
$download_args = array('supports' => apply_filters( 'edd_download_supports', array( 'custom-fields') ),);
register_post_type( 'download', apply_filters( 'edd_download_post_type_args', $download_args ) );
}

// 注册时间
function user_registered(){
	$userinfo=get_userdata(get_current_user_id());
	$authorID= $userinfo->ID;
	$user = get_userdata( $authorID );
	$registered = $user->user_registered;
	echo '' . date( "" . sprintf(__( 'Y年m月d日', 'begin' )) . "", strtotime( $registered ) );
}

// 文章归档更新
function clear_archives() {
	update_option('cx_archives_list', '');
	update_option('up_archives_list', '');
}

// 登录时间
function user_last_login($user_login) {
	global $user_ID;
	date_default_timezone_set(PRC);
	$user = get_user_by( 'login', $user_login );
	update_user_meta($user->ID, 'last_login', date('Y-m-d H:i:s'));
}

function get_last_login($user_id) {
	$last_login = get_user_meta($user_id, 'last_login', true);
	$date_format = get_option('date_format') . ' ' . get_option('time_format');
	$the_last_login = mysql2date($date_format, $last_login, false);
	echo $the_last_login;
}

// 登录角色
function get_user_role() {
	global $current_user;
	$user_roles = $current_user->roles;
	$user_role = array_shift($user_roles);
	return $user_role;
}

// 读者排行
function top_comment_authors($amount = 98) {
	global $wpdb;
		$prepared_statement = $wpdb->prepare(
		'SELECT
		COUNT(comment_author) AS comments_count, comment_author, comment_author_url, comment_author_email, MAX( comment_date ) as last_commented_date
		FROM '.$wpdb->comments.'
		WHERE comment_author != "" AND comment_type = "" AND comment_approved = 1  AND user_id = ""
		GROUP BY comment_author
		ORDER BY comments_count DESC, comment_author ASC
		LIMIT %d',
		$amount);
	$results = $wpdb->get_results($prepared_statement);
	$output = '<div class="top-comments">';
	foreach($results as $result) {
		$c_url = $result->comment_author_url;
		$output .= '
		<div class="lx8">
			<div class="top-author">
				<div class="top-comment"><a href="' . get_template_directory_uri()."/inc/go.php?url=". $c_url . '" target="_blank" rel="external nofollow">' . get_avatar($result->comment_author_email, 96) . '<div class="author-url"><strong> ' . $result->comment_author . '</div></strong></a></div>
				<div class="top-comment">'.$result->comments_count.'条留言</div>
				<div class="top-comment">最后' . human_time_diff(strtotime($result->last_commented_date)) . '前</div>
			</div>
		</div>';
	}
	$output .= '<div class="clear"></div></div>';
	echo $output;
}


function top_comments($number = 98) {
	global $wpdb;
	$counts = wp_cache_get( 'mostactive' );
	if ( false === $counts ) {
		$counts = $wpdb->get_results("SELECT COUNT(comment_author) AS cnt, comment_author, comment_author_url, comment_author_email
		FROM {$wpdb->prefix}comments
		WHERE comment_date > date_sub( NOW(), INTERVAL 90 DAY )
		AND comment_approved = '1'
		AND comment_author_email != 'example@example.com'
		AND comment_author_email != ''
		AND comment_author_url != ''
		AND comment_type = ''
		AND user_id = '0'
		GROUP BY comment_author_email
		ORDER BY cnt DESC
		LIMIT $number");
	}
	$mostactive =  '<div class="top-comments">';
	if ( $counts ) {
		wp_cache_set( 'mostactive', $counts );
		foreach ($counts as $count) {
			$c_url = $count->comment_author_url;
			$mostactive .= '
			<div class="lx8">
				<div class="top-author">
					<div class="top-comment"><a href="' . get_template_directory_uri()."/inc/go.php?url=". $c_url . '" target="_blank" rel="external nofollow">' . get_avatar($count->comment_author_email, 96). '<div class="author-url"><strong> ' . $count->comment_author . '</div></strong></a></div>
					<div class="top-comment">'.$count->cnt.'个脚印</div>
				</div>
			</div>';
		}
		$mostactive .= '<div class="clear"></div></div>';
		echo $mostactive;
	}
}
if (zm_get_option('meta_delete')) {} else {require get_template_directory() . '/inc/meta-delete.php';}
require get_template_directory() . '/inc/meta-boxes.php';
require get_template_directory() . '/inc/show-meta.php';
// 邀请码
if (zm_get_option('invitation_code')) {
	if ( ! is_admin() ) {
		require get_template_directory() . '/inc/invitation/front-end.php';
	} else {
		require get_template_directory() . '/inc/invitation/back-end.php';
	}
}

// 删除图片附件
function delete_post_and_attachments($post_ID) {
	global $wpdb;
	$thumbnails = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE meta_key = '_thumbnail_id' AND post_id = $post_ID" );
	foreach ( $thumbnails as $thumbnail ) {
		wp_delete_attachment( $thumbnail->meta_value, true );
	}

	$attachments = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE post_parent = $post_ID AND post_type = 'attachment'" );
	foreach ( $attachments as $attachment ) {
		wp_delete_attachment( $attachment->ID, true );
	}

	$wpdb->query( "DELETE FROM $wpdb->postmeta WHERE meta_key = '_thumbnail_id' AND post_id = $post_ID" );
}
if (zm_get_option('attachments_delete')) {
	add_action('before_delete_post', 'delete_post_and_attachments');
}

// 分类ID
function show_id() {
	global $wpdb;
	$request = "SELECT $wpdb->terms.term_id, name FROM $wpdb->terms ";
	$request .= " LEFT JOIN $wpdb->term_taxonomy ON $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id ";
	$request .= " WHERE $wpdb->term_taxonomy.taxonomy = 'category' ";
	$request .= " ORDER BY term_id asc";
	$categorys = $wpdb->get_results($request);
	foreach ($categorys as $category) { 
		$output = '<ol class="show-id">'.$category->name.' [ ' .$category->term_id.' ]</ol>';
		echo $output;
	}
}

function search_cat(){
	$categories = get_categories();
	foreach ($categories as $cat) {
	$output = '<option value="'.$cat->cat_ID.'">'.$cat->cat_name.'</option>';
		echo $output;
	}

	// $categories = get_categories(array('taxonomy' => 'gallery'));
	// foreach ($categories as $cat) {
	// $output = '<option value="'.$cat->cat_ID.'">'.$cat->cat_name.'</option>';
	// echo $output;
	// }
}

// 热评文章
function hot_comment_viewed($number, $days){
	global $wpdb;
	$sql = "SELECT ID , post_title , comment_count
			FROM $wpdb->posts
			WHERE post_type = 'post' AND post_status = 'publish' AND TO_DAYS(now()) - TO_DAYS(post_date) < $days
			ORDER BY comment_count DESC LIMIT 0 , $number ";
	$posts = $wpdb->get_results($sql);
	$i = 1;
	$output = "";
	foreach ($posts as $post){
		$output .= "\n<li><span class='li-icon li-icon-$i'>$i</span><a href= \"".get_permalink($post->ID)."\" rel=\"bookmark\" title=\" (".$post->comment_count."条评论)\" >".$post->post_title."</a></li>";
		$i++;
	}
	echo $output;
}

// menu description
function begin_nav_description( $item_output, $item, $depth, $args ) {
	if ( 'primary' == $args->theme_location && $item->description ) {
		$item_output = str_replace( $args->link_after . '</a>', '<div class="menu-des">' . $item->description . '</div>' . $args->link_after . '</a>', $item_output );
	}
	return $item_output;
}
if (zm_get_option('menu_des')) {
add_filter( 'walker_nav_menu_start_el', 'begin_nav_description', 10, 4 );
}

// menu post
function cat_content( ) {
	global $post;
	$cat_content_output = '';
	$cat_post_query = new WP_Query( array ( 'meta_key' => 'menu_post', 'showposts' => $number, 'ignore_sticky_posts' => 1 )  );
	if( $cat_post_query->have_posts() ) {
		$cat_content_output .= '<div class="cat-con-section">';
		while( $cat_post_query->have_posts() ) {
			$cat_post_query->the_post();
			if ( get_post_meta($post->ID, 'thumbnail', true) ) {
				$thumbnail  = get_post_meta(get_the_ID(),'thumbnail',true);
				$thumb_img =  '<img src="'.$thumbnail.'">';
			} else {
				$content = $post->post_content;
				preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
				$thumb_img = '<img src="'.get_template_directory_uri().'/thumbnail.php?src='.$strResult[1][0].'&w='.zm_get_option('img_w').'&h='.zm_get_option('img_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /><div class="clear"></div>';
			}
			$cat_content_output .= '<div class="menu-post-block"><a href="'.get_the_permalink().'">'.$thumb_img.'</a><h3><a href="'.get_the_permalink().'">'.get_the_title().'</a></h3></div>';
		}
		$cat_content_output .= '</div>';
		wp_reset_postdata();
	} else {
		$cat_content_output .= '<div class="cat-con-section"><div class="menu-post-block"><h3>编辑文章在“将文章添加到”面板中，勾选“菜单图文”，将指定文章添加到此模块中。</h3></div></div>';
	}
	return $cat_content_output;
}

function add_custom_post($items, $args) {
	$custom_items = '<li class="menu-img-box"><a href="#"><i class="'.zm_get_option('menu_post_ico').'"></i>'.zm_get_option('menu_post_t').'</a><ul class="menu-img"><li>'. cat_content( $mega_category ) . '</li></ul></li>';
	if( $args->theme_location == 'primary') {
		return $items.$custom_items;
	}
	return $items;
}

if (zm_get_option('menu_post')) {
	add_filter('wp_nav_menu_items', 'add_custom_post', 10, 2);
}
// custum font
function custum_font_family($initArray){
   $initArray['font_formats'] = "微软雅黑='微软雅黑';华文彩云='华文彩云';华文行楷='华文行楷';华文琥珀='华文琥珀';华文新魏='华文新魏';华文中宋='华文中宋';华文仿宋='华文仿宋';华文楷体='华文楷体';华文隶书='华文隶书';华文细黑='华文细黑';宋体='宋体';仿宋='仿宋';黑体='黑体';隶书='隶书';幼圆='幼圆'";
   return $initArray;
}

 // 删除文章菜单
function remove_menus(){
	remove_menu_page( 'edit.php?post_type=bulletin' );// 公告
	remove_menu_page( 'edit.php?post_type=picture' );// 图片* 
	remove_menu_page( 'edit.php?post_type=video' );// 视频
	remove_menu_page( 'edit.php?post_type=tao' );// 商品
	remove_menu_page( 'edit.php?post_type=sites' );// 网址
	remove_menu_page( 'edit.php?post_type=show' );// 产品
	remove_menu_page( 'link-manager.php' );// 链接
	remove_menu_page( 'upload.php' );//媒体
	remove_menu_page( 'edit-comments.php' );// 评论
	remove_menu_page( 'tools.php' );// 工具
}

function disable_create_newpost() {
	global $wp_post_types;
if (zm_get_option('no_bulletin')) {
	$wp_post_types['bulletin']->cap->create_posts = 'do_not_allow';
}
if (zm_get_option('no_gallery')) {
	$wp_post_types['picture']->cap->create_posts = 'do_not_allow';
}
if (zm_get_option('no_videos')) {
	$wp_post_types['video']->cap->create_posts = 'do_not_allow';
}
if (zm_get_option('no_tao')) {
	$wp_post_types['tao']->cap->create_posts = 'do_not_allow';
}
if (zm_get_option('no_favorites')) {
	$wp_post_types['sites']->cap->create_posts = 'do_not_allow';
}
if (zm_get_option('no_products')) {
	$wp_post_types['show']->cap->create_posts = 'do_not_allow';
}
}

if (zm_get_option('no_type')) {
	if ($current_user->user_level < zm_get_option('user_level')) { // 作者及投稿者不可见
		add_action( 'admin_menu', 'remove_menus' );
		add_action('init','disable_create_newpost');
	}
}

// 支持中文用户名
function zm_sanitize_user ($username, $raw_username, $strict) {
	$username = wp_strip_all_tags( $raw_username );
	$username = remove_accents( $username );
	$username = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $username );
	$username = preg_replace( '/&.+?;/', '', $username );
	if ($strict) {
		$username = preg_replace ('|[^a-z\p{Han}0-9 _.\-@]|iu', '', $username);
	}
	$username = trim( $username );
	$username = preg_replace( '|\s+|', ' ', $username );

	return $username;
}

// 隐藏后台标题中的“WordPress”
add_filter('admin_title', 'zm_custom_admin_title', 10, 2);
	function zm_custom_admin_title($admin_title, $title){
		return $title.' &lsaquo; '.get_bloginfo('name');
}
add_filter('login_title', 'zm_custom_login_title', 10, 2);
	function zm_custom_login_title($login_title, $title){
		return $title.' &lsaquo; '.get_bloginfo('name');
}
// 隐藏左上角WordPress标志
function hidden_admin_bar_remove() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wp-logo');
}
add_action('wp_before_admin_bar_render', 'hidden_admin_bar_remove', 0);

// 修改登录链接
function login_protect(){
	if($_GET[''.zm_get_option('pass_h').''] != ''.zm_get_option('word_q').'')header('Location: '.zm_get_option('go_link').'');// 忘了删除
}