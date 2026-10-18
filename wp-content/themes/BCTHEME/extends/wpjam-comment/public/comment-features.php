<?php
class WPJAM_Comment_Moderation{
	public static function get_fields($type){
		if($type == 'setting'){
			if(get_option('comment_moderation') == 1){
				return ['moderation'	=> ['title'=>'人工审核',	'type'=>'view',		'value'=>'全局设置评论必须经人工批准']];
			}else{
				return ['moderation'	=> ['title'=>'人工审核',	'type'=>'checkbox',	'label'=>'评论必须经人工批准']];
			}
		}
	}
}

class WPJAM_Comment_Anonymous{
	public static function get_fields($type){
		if($type == 'meta'){
			return ['anonymous'	=> ['title'=>'匿名',		'type'=>'checkbox',	'label'=>'匿名评论',	'show_admin_column'=>true]];
		}elseif($type == 'setting'){
			return ['anonymous'	=> ['title'=>'匿名评论',	'type'=>'checkbox',	'label'=>'支持匿名评论，即不显示评论者头像和昵称']];
		}
	}

	public static function render_item($item){
		$item['anonymous']	= get_comment_meta($item['id'], 'anonymous', true) ? '是' : '否';

		return $item;
	}

	public static function filter_comment_json($json, $comment){
		if(get_comment_meta($comment->comment_ID, 'anonymous', true)){
			$json['anonymous']	= true;
			$json['user_id']	= 0;
			$json['author']		= ['email'=>'', 'author'=>'匿名', 'nickname'=>'匿名', 'user_id'=>0,  'avatar'=>get_avatar_url('')];
		}

		return $json;
	}
}

class WPJAM_Comment_Images{
	public static function get_fields($type){
		if($type == 'meta'){
			return ['images'	=> ['title'=>'图片',		'type'=>'mu-img',	'item_type'=>'url',	'max_items'=>3]];
		}elseif($type == 'setting'){
			return ['images'	=> ['title'=>'图片评论',	'type'=>'checkbox',	'label'=>'评论的时候支持插入最多三张图片，仅支持小程序']];
		}
	}

	public static function render_item($item){
		$images	= get_comment_meta($item['id'], 'images', true);

		if(is_array($images)){
			$item['comment']	.= "\n\n".implode(' ', array_map(fn($image) => '<a class="thickbox" rel="images-'.$item['id'].'" href="'.wpjam_get_thumbnail($image, 600).'"><img src="'.wpjam_get_thumbnail($image, 120, 120).'" width="60" /></a>', $images));
		}

		return $item;
	}

	public static function filter_comment_json($json, $comment){
		$images = get_comment_meta($comment->comment_ID, 'images', true);
			
		if($images){
			$size	= count($images) > 1 ? '200x200' : '300x0';
			$images	= array_map(fn($image) => [
				'thumb'		=> wpjam_get_thumbnail($image, $size),
				'original'	=> wpjam_get_thumbnail($image, '1080x0')
			], $images);
		}

		$json['images']	= $images ?: [];

		return $json;
	}
}

class WPJAM_Comment_Read{
	public static function get_actions(){
		return [
			'read'		=> [
				'title'			=> '已读',
				'page_title'	=> '标记为已读',
				'direct'		=> true,
				'callback'		=> fn($id) => self::callback($id, 'read'),
				'show_if'		=> fn($id) => wpjam_get_comment_status($id) == 'unapproved' && !self::is($id)
			],
			'unread'	=> [
				'title'			=> '未读',
				'page_title'	=> '标记为未读',
				'direct'		=> true,
				'callback'		=> fn($id) => self::callback($id, 'unread'),
				'show_if'		=> fn($id) => self::is($id)
			]
		];
	}

	public static function is($id){
		return wpjam_get_comment_status($id) == 'read';
	}

	public static function callback($id, $status='read'){
		$result	= wpjam_set_comment_status($id, $status);

		if(is_wp_error($result)){
			return $result;
		}

		return wp_doing_ajax() ? $result : ['comment'=>wpjam_get_comment($id)];
	}

	public static function on_transition_comment_status($new_status, $old_status, $comment){
		if($old_status == 'unapproved' && $new_status == 'approved'){
			delete_comment_meta($comment->comment_ID, 'read');
		}
	}

	public static function filter_list_comments_args($args){
		return $args['callback'] ? array_merge($args, [
			'reserved'	=> $args['callback'],
			'callback'	=> [self::class, 'item_callback']
		]) : $args;
	}

	public static function item_callback($comment, $args, $depth){
		if(self::is($comment)){
			$comment->comment_approved = 2;
		}

		return call_user_func($args['reserved'], $comment, $args, $depth);
	}

	public static function filter_count($stats, $post_id){
		if($post_id){
			return $stats;
		}

		global $wpdb;

		$cache_key		= 'comment_stats:'.wp_cache_get_last_changed('comment');
		$stats_object	= wp_cache_get($cache_key, 'comment');

		if($stats_object === false){
			$stats	= [
				'approved'		=> 0,
				'read'			=> 0,
				'moderated'		=> 0,
				'spam'			=> 0,
				'trash'			=> 0,
				'post-trashed'	=> 0,
			];

			$where	= 'WHERE';
			$types	= WPJAM_Comment_Type::get_registereds(['sub_name'=>false, 'comment'=>true], 'names');

			$where	.= " comment_type IN ('".implode("','", $types)."')";
			$totals	= (array)$wpdb->get_results("SELECT comment_approved, COUNT(*) AS total FROM {$wpdb->comments} {$where} GROUP BY comment_approved", ARRAY_A);

			if($totals){
				$totals	= array_column($totals, 'total', 'comment_approved');
				
				if(isset($totals[0])){
					$totals['read']	= $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->comments} c INNER JOIN {$wpdb->commentmeta} cm ON c.comment_ID = cm.comment_id {$where} AND comment_approved = '0' AND meta_key='read' AND meta_value='1'");
				}else{
					$totals['read']	= 0;
				}

				$totals['moderated']	= (int)wpjam_array_pull($totals, '0') - $totals['read'];

				if(isset($totals['1'])){
					$totals['approved']	= wpjam_array_pull($totals, '1');
				}

				$stats	= wp_parse_args($totals, $stats);
			}

			$stats['all']				= $stats['approved']+$stats['moderated']+$stats['read'];
			$stats['total_comments']	= $stats['all']+$stats['spam'];

			$stats_object	= (object)$stats;

			wp_cache_set($cache_key, $stats_object, 'comment', DAY_IN_SECONDS);
		}

		return $stats_object;
	}

	public static function builtin_page_load(){
		wpjam_map(self::get_actions(), fn($args, $name) => wpjam_register_list_table_action($name, $args));

		wp_add_inline_style('list-tables', '
		#the-comment-list .read th.check-column{border-left: 4px solid #0073aa;}
		#the-comment-list .read td, #the-comment-list .read th{background-color:inherit;}
		');

		add_filter('comments_list_table_query_args',	[self::class, 'filter_list_table_query_args'], 1);
		add_filter('comment_status_links', 				[self::class, 'filter_status_links']);
		add_filter('comment_class', 					[self::class, 'filter_class'], 10, 4);
		add_filter('comment_row_actions',				[self::class, 'filter_row_actions'], 1, 2);

		add_filter('query',	[self::class, 'filter_query']);
	}

	public static function register_json($json, $action){
		if($action == 'read'){
			return wpjam_register_json($json, [
				'auth'		=> true,
				'callback'	=> fn($data) => self::callback($data['comment_id'], 'read'),
				'fields'	=> ['comment_id'=>['required'=>true, 'type'=>'number']]
			]);
		}
	}

	public static function filter_query($query){
		global $wpdb;

		if(strpos($query, 'SELECT comment_post_ID, COUNT(comment_ID) as num_comments FROM '.$wpdb->comments.' WHERE comment_post_ID IN') !== false){
			$query	= str_replace(['COUNT(comment_ID)', 'WHERE'], ['COUNT(c.comment_ID)', 'c LEFT JOIN '.$wpdb->commentmeta.' cm ON (c.comment_ID = cm.comment_id AND cm.meta_key = \'read\') WHERE cm.comment_id IS NULL AND'], $query);
		}

		return $query;
	}

	public static function filter_list_table_query_args($args){
		if($args['status'] == 'hold'){
			$args['meta_query']	= [['key'=>'read', 'compare'=>'NOT EXISTS']];
		}elseif(isset($_REQUEST['comment_status']) && $_REQUEST['comment_status'] == 'read'){
			$args['status']		= 'hold';
			$args['meta_key']	= 'read';
		}

		return $args;
	}

	public static function filter_status_links($status_links){
		$stats_object	= wp_count_comments();

		if(!empty($stats_object->read)){
			$read_link		= preg_replace('#<span class="all-count">(.*?)</span>#is', '<span class="read-count">'.$stats_object->read.'</span>', $status_links['all']);
			$read_link		= str_replace([__('All'), 'all'], ['已读', 'read'], $read_link);
			$offset			= array_search('spam', array_keys($status_links), true);
			$status_links	= array_merge(array_slice($status_links, 0, $offset), ['read'=>$read_link], array_slice($status_links, $offset));

			if(isset($_REQUEST['comment_status']) && $_REQUEST['comment_status'] == 'read'){
				$not_current_key	= 'all';
			}else{
				$not_current_key	= 'read';
			}

			$status_links[$not_current_key]	= str_replace(' class="current" aria-current="page"', '', $status_links[$not_current_key]);
		}

		return $status_links;
	}

	public static function filter_class($classes, $class, $comment_id, $comment){
		return self::is($comment) ? array_merge(array_diff($classes, ['unapproved']), ['read']): $classes;
	}

	public static function filter_row_actions($row_actions, $comment){
		return self::is($comment) ? wpjam_except($row_actions, ['unapprove', 'approve']) : $row_actions;
	}

	public static function add_hooks(){
		add_action('transition_comment_status',	[self::class, 'on_transition_comment_status'], 10, 3);
		add_filter('wp_list_comments_args',		[self::class, 'filter_list_comments_args'], 10, 5);

		if(is_admin()){
			wpjam_add_admin_load(['base'=>'edit-comments', 'model'=>self::class,  'order'=>20]);

			add_filter('wp_count_comments',	[self::class, 'filter_count'], 10 ,2);
		}
	}
}

