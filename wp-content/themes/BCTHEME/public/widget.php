<?php
/**
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.dahuzi.me
 * @Date:   2020-11-05 14:16:03
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-08-28 16:28:18
 */

/** --------------------------------------------------------------------------------- *
 *  激活小工具
 *  --------------------------------------------------------------------------------- */
if( function_exists('register_sidebar') ) {

	// 分类页面侧栏
	register_sidebar(array(
		'name'			=> '分类页面侧栏',
		'id'			=> 'widget_category',
		'before_widget'	=> '<div class="widget %2$s">', 
		'after_widget'	=> '</div>', 
		'before_title'	=> '<div class="widget-title"><h3>', 
		'after_title'	=> '</h3></div>' 
	));

	// 文章页侧栏
	register_sidebar(array(
		'name'			=> '文章页侧栏',
		'id'			=> 'widget_post',
		'before_widget'	=> '<div class="widget %2$s">', 
		'after_widget'	=> '</div>', 
		'before_title'	=> '<div class="widget-title"><h3>', 
		'after_title' 	=> '</h3></div>' 
	));

	// 产品页侧栏
	register_sidebar(array(
		'name'			=> '产品页侧栏',
		'id'			=> 'widget_product',
		'before_widget'	=> '<div class="widget %2$s">', 
		'after_widget'	=> '</div>', 
		'before_title'	=> '<div class="widget-title"><h3>', 
		'after_title' 	=> '</h3></div>' 
	));

	// 页面侧栏
	register_sidebar(array(
		'name'          => '页面侧栏',
		'id'            => 'widget_page',
		'before_widget' => '<div class="widget %2$s">', 
		'after_widget' 	=> '</div>', 
		'before_title' 	=> '<div class="widget-title"><h3>', 
		'after_title'	=> '</h3></div>' 
	));
	
	// 其它页面
	/*
	register_sidebar(array(
		'name'			=> '其它页面，标签页面等',
		'id'			=> 'widget_other',
		'before_widget'	=> '<div class="widget %2$s">', 
		'after_widget'	=> '</div>', 
		'before_title'	=> '<div class="widget-title"><h3>', 
		'after_title'	=> '</h3></div>' 
	));
	*/

	// 页脚小工具
	register_sidebar(array(
		'name'			=> '页脚小工具',
		'id'			=> 'widget_footer',
		'before_widget'	=> '<div class="widget %2$s">', 
		'after_widget'	=> '</div>', 
		'before_title'	=> '<div class="widget-title"><h3>', 
		'after_title'	=> '</h3></div>' 
	));

	// FAQ侧栏
	register_sidebar(array(
		'name'			=> 'FAQ页侧栏',
		'id'			=> 'widget_faq',
		'before_widget'	=> '<div class="widget %2$s">', 
		'after_widget'	=> '</div>', 
		'before_title'	=> '<div class="widget-title"><h3>', 
		'after_title'	=> '</h3></div>' 
	));

	// Download侧栏
	register_sidebar(array(
		'name'			=> '下载页侧栏',
		'id'			=> 'widget_download',
		'before_widget'	=> '<div class="widget %2$s">', 
		'after_widget'	=> '</div>', 
		'before_title'	=> '<div class="widget-title"><h3>', 
		'after_title'	=> '</h3></div>' 
	));

}

/** --------------------------------------------------------------------------------- *
 *  移除/注册 小工具
 *  --------------------------------------------------------------------------------- */
function unregister_widgets() {
	unregister_widget("WP_Widget_Pages");//页面
	unregister_widget("WP_Widget_Calendar");//文章日程表
	unregister_widget("WP_Widget_Archives");//文章归档
	unregister_widget("WP_Widget_Meta");//登入/登出，管理，Feed 和 WordPress 链接
	unregister_widget("WP_Widget_Search");//搜索
	unregister_widget("WP_Widget_Categories");//分类目录
	unregister_widget("WP_Widget_Recent_Posts");//近期文章
	unregister_widget("WP_Widget_Recent_Comments");//近期评论
	unregister_widget("WP_Widget_RSS");//RSS订阅
	unregister_widget("WP_Widget_Links");//链接
	//unregister_widget("WP_Widget_Text");//文本
	unregister_widget("WP_Widget_Tag_Cloud");//标签云
	unregister_widget("WP_Widget_Block");//古腾堡块
	//unregister_widget("WP_Nav_Menu_Widget");//自定义菜单
	unregister_widget("WP_Widget_Media_Audio");//音频
	//unregister_widget("WP_Widget_Media_Image");//图片
	unregister_widget("WP_Widget_Media_Video");//视频
	//unregister_widget("WP_Widget_Media_Gallery");//画廊

	register_widget('xintheme_postlist');
	register_widget('xintheme_postlist_img');
	register_widget('xintheme_postlist_related');
	register_widget('xintheme_tag');
	register_widget('xintheme_widget_search');
}
add_action("widgets_init", "unregister_widgets");

/** --------------------------------------------------------------------------------- *
 *  小工具显示分类ID
 *  --------------------------------------------------------------------------------- */
function show_category() {
    global $wpdb;
    $request = "SELECT $wpdb->terms.term_id, name FROM $wpdb->terms ";
    $request .= " LEFT JOIN $wpdb->term_taxonomy ON $wpdb->term_taxonomy.term_id = $wpdb->terms.term_id ";
    $request .= " WHERE $wpdb->term_taxonomy.taxonomy = 'category' ";
    $request .= " ORDER BY term_id asc";
    $categorys = $wpdb->get_results($request);
    echo '<div class="uk-panel uk-panel-box" style="margin-bottom: 20px;"><h3 style="margin-top: 0; margin-bottom: 15px; font-size: 18px; line-height: 24px; font-weight: 400; text-transform: none; color: #666;">可能会用到的分类ID</h3>';
    echo "<ul>";
    foreach ($categorys as $category) { 
        echo  '<li style="margin-right: 10px;float:left;">'.$category->name."（<code>".$category->term_id.'</code>）</li>';
    }
    echo "</ul></div>";
}

/** --------------------------------------------------------------------------------- *
 *  标签云小工具-热门标签
 *  --------------------------------------------------------------------------------- */
function xintheme_hot_tag_list( $num = null , $hot = null ){
    $num = $num ? $num : 14;
    
    $output = '<div class="tags-item">';
    $tags = get_tags(array("number" => $num,
        "orderby"=>"count",
		"order" => "DESC",
    ));
    foreach($tags as $tag){
        $count = intval( $tag->count );
        $name = $tag->name;
        $output .= '<a href="'. esc_attr( get_tag_link( $tag->term_id ) ) .'" class="tag-item" title="#'. $name . '# 共有'. $tag->count .'篇文章">'.$name.' <!--sup>（'. $tag->count .'）</sup--></a>';

    }
    $output .= '</div>';
    return $output;

}

class xintheme_tag extends WP_Widget {
	function __construct() {
		$widget_ops = array( 'classname' => 'widget_tag_cloud', 'description' => '主题自带标签云小工具，显示热门标签，可自定义显示数量' );
		parent::__construct('xintheme_tag', '热门标签 ', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );
		echo $before_widget;
		$title = apply_filters('widget_name', $instance['title']);
		$count = $instance['count'];
		echo $before_title.$title.$after_title; 
		echo xintheme_hot_tag_list($count);
		echo $after_widget;
	}

	function form($instance) {
	    $instance = wp_parse_args( (array) $instance, array( 
			'title' => '热门标签',
			'count' => '15',
			) 
		);
?>

<p>
	<label> 名称：
		<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" class="widefat" />
	</label>
</p>
<p>
	<label> 显示数量：
		<input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="number" value="<?php echo $instance['count']; ?>" class="widefat" />
	</label>
</p>
<?php
	}
}


/** --------------------------------------------------------------------------------- *
 *  文章展示小工具（小图）
 *  --------------------------------------------------------------------------------- */
class xintheme_postlist_img extends WP_Widget {

	public function __construct() {
		$widget_ops = array( 'description' => '可以选择显示最新文章、随机文章。' );
		parent::__construct('xintheme_postlist_img', '文章展示（有图）', $widget_ops);
	}

    function widget($args, $instance) {
        extract( $args );
		$limit = $instance['limit'];
		$title = apply_filters('widget_name', $instance['title']);
		$cat          = $instance['cat'];
		$orderby      = $instance['orderby'];
		echo $before_widget;
		echo $before_title.$title.$after_title; 
        echo xintheme_widget_postlist_img($orderby,$limit,$cat);
        echo $after_widget;	
    }

	function form($instance) {
		$instance['title'] = ! empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$instance['orderby'] = ! empty( $instance['orderby'] ) ? esc_attr( $instance['orderby'] ) : '';
		$instance['cat'] = ! empty( $instance['cat'] ) ? esc_attr( $instance['cat'] ) : '';
		$instance['limit']    = isset( $instance['limit'] ) ? absint( $instance['limit'] ) : 6;
?>
<p style="clear: both;padding-top: 5px;">
	<label>显示标题：（例如：最新文章、随机文章）
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
	</label>
</p>
<p>
	<label> 排序方式：
		<select style="width:100%;" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" style="width:100%;">
			<option value="date" <?php selected('date', $instance['orderby']); ?>>发布时间</option>
			<option value="rand" <?php selected('rand', $instance['orderby']); ?>>随机文章</option>
		</select>
	</label>
</p>
<p>
	<label>
		分类限制：
		<p>只显示指定分类，填写数字，用英文逗号隔开，例如：1,2 </p>
		<p>排除指定分类的文章，填写负数，用英文逗号隔开，例如：-1,-2。</p>
		<input style="width:100%;" id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>" type="text" value="<?php echo $instance['cat']; ?>" size="24" />
	</label>
</p>
<p>
	<label> 显示数目：
		<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo $instance['limit']; ?>" />
	</label>
</p>
<p><?php show_category();?><br/><br/></p>
<?php
	}
}

function xintheme_widget_postlist_img($orderby,$limit,$cat){

	$args = array(
		'post_status'			=> 'publish', // 只选公开的文章.
		'ignore_sticky_posts'	=> 1, // 排除置頂文章.
		'orderby'				=>  $orderby, // 排序方式.
		'cat'					=> $cat,
		'order'					=> 'DESC',
		'showposts'				=> $limit,
		'tax_query'				=> array( array( 
		'taxonomy'				=> 'post_format',
		'field'					=> 'slug',
		'terms'					=> array(
			//请根据需要保留要排除的文章形式
			//'post-format-aside',
		),
		'operator' => 'NOT IN',
		) ),
	);

	// 仅在单篇文章页面时排除当前文章
	if (is_singular('post')) {
		$args['post__not_in'] = array(get_the_ID());
	}

	$query_posts = new WP_Query($args);
	//$query_posts = Dahuzi_Query( $args );
	//$query_posts->query($args);
	echo '<div class="post-list">';
	while( $query_posts->have_posts() ) { $query_posts->the_post();

	//文章图片
	$post_image = wpjam_get_post_thumbnail_url();

	//图片Alt标签
	$img_alt = get_the_title();
	?>
		<a href="<?php the_permalink();?>" class="post-item">
			<div class="post-img">
				<img src="<?php echo $post_image;?>" alt="<?php echo $img_alt;?>">
			</div>
			<div class="post-title">
				<h2><?php the_title();?></h2>
			</div>
		</a>
	<?php } wp_reset_query();
	echo '</div>';
}

/** --------------------------------------------------------------------------------- *
 *  文章展示小工具（无图）
 *  --------------------------------------------------------------------------------- */
class xintheme_postlist extends WP_Widget {

	public function __construct() {
		$widget_ops = array( 'description' => '可以选择显示最新文章、随机文章。' );
		parent::__construct('xintheme_postlist', '文章展示（无图）', $widget_ops);
	}

    function widget($args, $instance) {
        extract( $args );
		$limit = $instance['limit'];
		$title = apply_filters('widget_name', $instance['title']);
		$cat          = $instance['cat'];
		$orderby      = $instance['orderby'];
		echo $before_widget;
		echo $before_title.$title.$after_title; 
        echo xintheme_widget_postlist($orderby,$limit,$cat);
        echo $after_widget;	
    }

	function form($instance) {
		$instance['title'] = ! empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$instance['orderby'] = ! empty( $instance['orderby'] ) ? esc_attr( $instance['orderby'] ) : '';
		$instance['cat'] = ! empty( $instance['cat'] ) ? esc_attr( $instance['cat'] ) : '';
		$instance['limit']    = isset( $instance['limit'] ) ? absint( $instance['limit'] ) : 5;
?>
<p style="clear: both;padding-top: 5px;">
	<label>显示标题：（例如：最新文章、随机文章）
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
	</label>
</p>
<p>
	<label> 排序方式：
		<select style="width:100%;" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" style="width:100%;">
			<option value="date" <?php selected('date', $instance['orderby']); ?>>发布时间</option>
			<option value="rand" <?php selected('rand', $instance['orderby']); ?>>随机文章</option>
		</select>
	</label>
</p>
<p>
	<label>
		分类限制：
		<p>只显示指定分类，填写数字，用英文逗号隔开，例如：1,2 </p>
		<p>排除指定分类的文章，填写负数，用英文逗号隔开，例如：-1,-2。</p>
		<input style="width:100%;" id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>" type="text" value="<?php echo $instance['cat']; ?>" size="24" />
	</label>
</p>
<p>
	<label> 显示数目：
		<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo $instance['limit']; ?>" />
	</label>
</p>
<p><?php show_category();?><br/><br/></p>
<?php
	}
}

function xintheme_widget_postlist($orderby,$limit,$cat){
	$args = array(
		'post_status'			=> 'publish', // 只选公开的文章.
		'ignore_sticky_posts'	=> 1, // 排除置頂文章.
		'orderby'				=>  $orderby, // 排序方式.
		'cat'					=> $cat,
		'order'					=> 'DESC',
		'showposts'				=> $limit,
		'tax_query'				=> array( array( 
		'taxonomy'				=> 'post_format',
		'field'					=> 'slug',
		'terms'					=> array(
			//请根据需要保留要排除的文章形式
			//'post-format-aside',
		),
		'operator' => 'NOT IN',
		) ),
	);

	// 仅在单篇文章页面时排除当前文章
	if (is_singular('post')) {
		$args['post__not_in'] = array(get_the_ID());
	}

	$query_posts = new WP_Query($args);
	//$query_posts = Dahuzi_Query( $args );
	//$query_posts->query($args);
	echo '<div class="widget-list">';
	while( $query_posts->have_posts() ) { $query_posts->the_post(); ?>
	<div class="widget-item">
		<div class="item-title">
			<span><?php echo get_the_date();?></span>
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</div>
	</div>
	<?php } wp_reset_query();
	echo '</div>';
}


/** --------------------------------------------------------------------------------- *
 *  相关文章推荐小工具（小图）
 *  --------------------------------------------------------------------------------- */
class xintheme_postlist_related extends WP_Widget {

	public function __construct() {
		$widget_ops = array( 'description' => '相关文章推荐，仅在文章页面显示' );
		parent::__construct('xintheme_postlist_related', '相关推荐（有图）', $widget_ops);
	}

    function widget($args, $instance) {
        extract( $args );
		$limit = $instance['limit'];
		$title = apply_filters('widget_name', $instance['title']);
		echo $before_widget;
		echo $before_title.$title.$after_title;
        echo xintheme_widget_postlist_related($limit);
        echo $after_widget;	
    }

	function form($instance) {
		$instance['title'] = ! empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$instance['limit']    = isset( $instance['limit'] ) ? absint( $instance['limit'] ) : 6;
?>
<p style="clear: both;padding-top: 5px;">
	<label>显示标题：（例如：猜你喜欢、相关推荐）
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
	</label>
</p>
<p>
	<label> 显示数目：
		<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo $instance['limit']; ?>" />
	</label>
</p>
<!--p><?php //show_category();?><br/><br/></p-->
<?php
	}
}

function xintheme_widget_postlist_related($limit){

if( is_single() ){
	global $post;
	$exclude_id = $post->ID;
	$posttags = get_the_tags(); $i = 0;
	echo '<div class="post-list">';
	if ( $posttags ) {
		$tags = '';
		foreach ( $posttags as $tag ) $tags .= $tag->term_id . ',';
		$args = array(
			'post_status' => 'publish',
			'tag__in' => explode(',', $tags),
			'post__not_in' => explode(',', $exclude_id),
			'ignore_sticky_posts' => 1,
			//'orderby' => 'comment_date',
			'showposts' => $limit
		);
		query_posts($args);
		while( have_posts() ) { the_post();

		//文章图片
		$post_image = wpjam_get_post_thumbnail_url();

		//图片Alt标签
		$img_alt = get_the_title();
		?>
		<a href="<?php the_permalink();?>" class="post-item">
			<div class="post-img">
				<img src="<?php echo $post_image;?>" alt="<?php echo $img_alt;?>">
			</div>
			<div class="post-title">
				<h2><?php the_title();?></h2>
			</div>
		</a>
		<?php
		$exclude_id .= ',' . $post->ID; $i ++;
		}
		wp_reset_query();
	}
	if ( $i < $limit ) {
		$cats = ''; foreach ( get_the_category() as $cat ) $cats .= $cat->cat_ID . ',';
		$args = array(
			'category__in' => explode(',', $cats),
			'post__not_in' => explode(',', $exclude_id),
			'ignore_sticky_posts' => 1,
			//'orderby' => 'comment_date',
			'showposts' => $limit - $i
		);
		query_posts($args);
		while( have_posts() ) {the_post();
		//文章图片
		$post_image = wpjam_get_post_thumbnail_url();

		//图片Alt标签
		$img_alt = get_the_title();
		?>
		<a href="<?php the_permalink();?>" class="post-item">
			<div class="post-img">
				<img src="<?php echo $post_image;?>" alt="<?php echo $img_alt;?>">
			</div>
			<div class="post-title">
				<h2><?php the_title();?></h2>
			</div>
		</a>
		<?php
		$i++; }
		wp_reset_query();
	}
	echo '</div>';
	if ( $i  == 0 )  echo '<li>暂无相关文章!</li>';
}else{
	echo '「相关推荐」小工具，仅在文章页面有效！请添加至「文章页侧栏」';
}

}


/** --------------------------------------------------------------------------------- *
 *  搜索框小工具
 *  --------------------------------------------------------------------------------- */
function xintheme_search( $placeholder = null ){

	$output = '<form class="search-box" role="search" method="get" action="'.esc_url( home_url('/') ).'">';
	$output .= '<input type="text" placeholder="'.$placeholder.'" name="s" class="form-control" />';
	$output .= '<button type="submit" class="ri-search-line"></button>';
    $output .= '</form>';

    return $output;

}

class xintheme_widget_search extends WP_Widget {
	function __construct() {
		$widget_ops = array( 'classname' => 'widget_search', 'description' => '主题自带搜索小工具，可自定义搜索框默认显示文本' );
		parent::__construct('xintheme_widget_search', '站内搜索 ', $widget_ops);
	}

	function widget( $args, $instance ) {

		//如果开启了全站禁用搜索，不显示搜索小工具
		$remove_search = wpjam_theme_get_setting('remove_search');
		if( !empty($remove_search) ){
			return;
		}

		extract( $args );
		echo $before_widget;
		$title = apply_filters('widget_name', $instance['title']);
		$placeholder = $instance['placeholder'];
		echo $before_title.$title.$after_title; 
		echo xintheme_search($placeholder);
		echo $after_widget;
	}

	function form($instance) {
	    $instance = wp_parse_args( (array) $instance, array( 
			'title' => __( '站内搜索', 'modular_theme' ),
			'placeholder' => __( '输入关键词进行搜索...', 'modular_theme' ),
		) );
	?>

	<p>
		<label> 标题：
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" class="widefat" />
		</label>
	</p>
	<p>
		<label> 搜索框默认显示文本：
			<input id="<?php echo $this->get_field_id('placeholder'); ?>" name="<?php echo $this->get_field_name('placeholder'); ?>" type="text" value="<?php echo $instance['placeholder']; ?>" class="widefat" />
		</label>
	</p>

	<?php
	}
}
