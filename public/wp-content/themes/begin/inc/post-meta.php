<?php
// 文章信息
function begin_entry_meta() {
	if ( ! is_single() ) :
	echo '<span class="date">';
		time_ago( $time_type ='post' );
	echo '</span>';
	if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); }
	if ( post_password_required() ) { 
		echo '<span class="comment"><a href=""><i class="icon-scroll-c"></i> ' . sprintf(__( '密码保护', 'begin' )) . '</a></span>';
	} else {
		echo '<span class="comment">';
			comments_popup_link( '<span class="no-comment"><i class="be be-speechbubble"></i> ' . sprintf(__( '发表评论', 'begin' )) . '</span>', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' );
		echo '</span>';
	}
 	else :

	echo '<ul class="single-meta">';
		edit_post_link('' . sprintf(__( '编辑', 'begin' )) . '', '<li class="edit-link">', '</li>' );

		echo '<li class="print"><a href="javascript:printme()" target="_self" title="' . sprintf(__( '打印', 'begin' )) . '"><i class="be be-print"></i></a></li>';

		if ( post_password_required() ) { 
			echo '<li class="comment"><a href="#comments">' . sprintf(__( '密码保护', 'begin' )) . '</a></li>';
		} else {
			echo '<li class="comment">';
				comments_popup_link( '<i class="be be-speechbubble"></i> ' . sprintf(__( '发表评论', 'begin' )) . '', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' );
			echo '</li>';
		}

		if( function_exists( 'the_views' ) ) { the_views(true, '<li class="views"><i class="be be-eye"></i> ','</li>');  }
		echo '<li class="r-hide"><a href="#"><span class="off-side"></span></a></li>';
	echo '</ul>';

	echo '<ul id="fontsize"><li>A+</li></ul>';
	echo '<div class="single-cat-tag">';
		echo '<div class="single-cat">' . sprintf(__( '所属分类', 'begin' )) . '：';
			the_category( ' ' );
		echo '</div>';
	echo '</div>';

	endif;
}

// 日志信息
function begin_format_meta() {
	echo '<span class="date">';
		time_ago( $time_type ='post' );
	echo '</span>';
	echo '<span class="format-cat"><i class="be be-folder"></i> ';
		zm_category();
	echo '</span>';
	if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); }
	if ( post_password_required() ) { 
		echo '<span class="comment"><a href=""><i class="icon-scroll-c"></i> ' . sprintf(__( '密码保护', 'begin' )) . '</a></span>';
	} else {
		echo '<span class="comment">';
			comments_popup_link( '<span class="no-comment"><i class="be be-speechbubble"></i> ' . sprintf(__( '发表评论', 'begin' )) . '</span>', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' );
		echo '</span>';
	}
}

function begin_single_meta() {
	echo '<div class="begin-single-meta">';
		echo '<span class="my-date"><i class="be be-schedule"></i> ';
		time_ago( $time_type ='posts' );
		echo '</span>';
		if ( post_password_required() ) { 
			echo '<span class="comment"><a href="#comments">' . sprintf(__( '密码保护', 'begin' )) . '</a></li>';
		} else {
			echo '<span class="comment">';
				comments_popup_link( '<i class="be be-speechbubble"></i> ' . sprintf(__( '发表评论', 'begin' )) . '', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' );
			echo '</span>';
		}
		if( function_exists( 'the_views' ) ) { the_views(true, '<span class="views"><i class="be be-eye"></i> ','</span>');  }
		echo '<span class="print"><a href="javascript:printme()" target="_self" title="' . sprintf(__( '打印', 'begin' )) . '"><i class="be be-print"></i></a></span>';
		edit_post_link('' . sprintf(__( '编辑', 'begin' )) . '', '<span class="edit-link">', '</span>' );
		echo '<span class="s-hide"><a href="#"><span class="off-side"></span></a></span>';
	echo '</div>';
}

function begin_single_cat() {
	echo '<ul id="fontsize"><li>A+</li></ul>';
	echo '<div class="single-cat-tag">';
		echo '<div class="single-cat">' . sprintf(__( '所属分类', 'begin' )) . '：';
			the_category( ' ' );
		echo '</div>';
	echo '</div>';
}

// 页面信息
function begin_page_meta() {
	echo '<ul class="single-meta">';
		edit_post_link('' . sprintf(__( '编辑', 'begin' )) . '', '<li class="edit-link">', '</li>' );
		echo '<li class="print"><a href="javascript:printme()" target="_self" title="' . sprintf(__( '打印', 'begin' )) . '"><i class="be be-print"></i></a></li>';
		echo '<li class="comment">';
		comments_popup_link( '<i class="be be-speechbubble"></i> ' . sprintf(__( '发表评论', 'begin' )) . '', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' );
		echo '</li>';
		if( function_exists( 'the_views' ) ) { the_views(true, '<li class="views"><i class="be be-eye"></i> ','</li>');  }
		echo '<li class="r-hide"><a href="#"><span class="off-side"></span></a></li>';
	echo '</ul>';
	echo '<ul id="fontsize">A+</ul>';
}

// 其它信息
function begin_grid_meta() {
	echo '<span class="date">';
		the_time( 'm/d' ); 
	echo '</span>';
	if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="be be-eye"></i> ','</span>' ); }
	if ( post_password_required() ) { 
		echo '<span class="comment"><a href=""><i class="icon-scroll-c"></i> ' . sprintf(__( '密码保护', 'begin' )) . '</a></span>';
	} else {
		echo '<span class="comment">';
			comments_popup_link( '<span class="no-comment"><i class="be be-speechbubble"></i> ' . sprintf(__( '发表评论', 'begin' )) . '</span>', '<i class="be be-speechbubble"></i> 1 ', '<i class="be be-speechbubble"></i> %' );
		echo '</span>';
	}
}

// 时间
if (zm_get_option('meta_time')) {
function time_ago( $time_type ){
	switch( $time_type ){
		case 'comment': //评论时间
				printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time());
			break;
		case 'post'; //日志时间
				echo get_the_date();
			break;
		case 'posts'; //日志时间年
				echo get_the_date();
				echo '<i class="i-time">' . get_the_time('H:i:s') . '</i>';
			break;
	}
}
} else { 
function time_ago( $time_type ){
	switch( $time_type ){
		case 'comment': //评论时间
			$time_diff = current_time('timestamp') - get_comment_time('U');
			if( $time_diff <= 300 )
				echo ('刚刚');
			elseif(  $time_diff>=300 && $time_diff <= 86400 ) //24 小时之内
				echo human_time_diff(get_comment_time('U'), current_time('timestamp')).'前';
			else
				printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time());
			break;
		case 'post'; //日志时间
			$time_diff = current_time('timestamp') - get_the_time('U');
			if( $time_diff <= 300 )
				echo ('刚刚');
			elseif(  $time_diff>=300 && $time_diff <= 86400 ) //24 小时之内
				echo human_time_diff(get_the_time('U'), current_time('timestamp')).'前';
			else
				echo the_time( 'm月d日' );
			break;
		case 'posts'; //日志时间年
			//$time_diff = current_time('timestamp') - get_the_time('U');
			//if( $time_diff <= 300 )
				//echo ('刚刚');
			//elseif(  $time_diff>=300 && $time_diff <= 86400 ) //24 小时之内
				//echo human_time_diff(get_the_time('U'), current_time('timestamp')).'前';
			//else
				echo get_the_date();
				echo '<i class="i-time">' . get_the_time('H:i:s') . '</i>';
			break;
	}
}
}