<?php
class WPJAM_Comment_Digg{
	public static function added($object, $added){
		if($added == 1){
			wpjam_register_ajax('comment-digg',	[
				'nopriv'		=> true,
				'nonce_keys'	=> ['comment_id'],
				'callback'		=> fn($data) => self::callback($data['comment_id'], $data['type']),
				'fields'		=> [
					'comment_id'	=> ['required'=>true, 'type'=>'number'],
					'type'			=> ['required'=>true, 'sanitize_callback'=>'sanitize_key'],
				]
			]);

			add_action('wp_enqueue_scripts',	[self::class, 'on_enqueue_scripts'], 20);
			add_filter('comments_clauses',		[self::class, 'filter_comments_clauses'], 10, 2);
			add_filter('comment_text',			[self::class, 'filter_comment_text'], 10, 2);
			add_filter('option_comment_order',	fn() => 'digg_count');
		}
	}

	public static function get_fields($type){
		if($type == 'setting'){
			return [
				'digg'			=> ['title'=>'评论点赞',	'type'=>'checkbox',	'label'=>'开启评论点赞功能'],
				'orderby_digg'	=> ['type'=>'checkbox',	'label'=>'评论按照点赞排序']
			];
		}elseif($type == 'meta'){
			return ['digg_count' => [
				'title'				=> '点赞',
				'show_admin_column'	=> 'only',
				'column'			=> [
					'style'		=> 'width: 70px;',
					'callback'	=> fn($id) => (int)wpjam_get_comment_object($id)->digg_count
				]
			]];
		}
	}

	public static function get_attr($id){
		$object	= wpjam_comment($id);

		if(!$object || !wpjam_is_current_commenter()){
			return;
		}

		$digged	= $object->is_digged();

		return [
			'data'	=> ['comment_id'=>$id, 'type'=>($digged ? 'undigg' : 'digg')],
			'count'	=> (int)$object->digg_count,
			'class'	=> 'comment-digg'.($digged ? ' is-digged' : ''),
			'icon'	=> 'ri-thumb-up-'.($digged ? 'fill' : 'line')
		];
	}

	public static function get_items($data){
		if(did_action('wpjam_account_loaded')){
			$post_type	= $data['post_type'] ?: 'post';

			if(is_string($post_type) && strpos($post_type, ',') !== false){
				$post_type	= wp_parse_list($post_type);
			}

			$query_args	= [
				'number'	=> 20,
				'cursor'	=> $data['cursor'],
				'post_type'	=> $post_type,
			];

			$response	= [];

			$response['page_title']		= $response['share_title'] = '我的评论点赞';
			$response['comments']		= WPJAM_Account_Activity::parse_comments($query_args, $next_cursor);
			$response['next_cursor']	= $next_cursor;

			return $response;
		}
	}

	public static function callback($id, $type){
		$object	= wpjam_comment($id, true);

		if(is_wp_error($object)){
			return $object;
		}

		if(!wpjam_comment_supports($object, 'digg')){
			return new WP_Error('comment_digg_not_supported', '不支持评论点赞');
		}

		$result	= $object->digg($type);

		if(is_wp_error($result)){
			return $result;
		}

		return wp_doing_ajax() ? self::get_attr($id) : ['comment'=>wpjam_get_comment($id)];
	}

	public static function register_json($json, $action){
		if($action == 'digg.list'){
			return wpjam_register_json($json, [
				'auth'		=> true,
				'callback'	=> [self::class, 'get_items'],
				'fields'	=> ['post_type'=>[], 'cursor'=>['type'=>'number']]
			]);
		}elseif(in_array($action, ['digg', 'undigg'])){
			return wpjam_register_json($json, [
				'auth'		=> true,
				'callback'	=> fn($data) => self::callback($data['comment_id'], $action),
				'fields'	=> ['comment_id'=>['required'=>true, 'type'=>'number']]
			]);
		}
	}

	public static function filter_comment_json($json, $comment){
		$object	= wpjam_comment($comment->comment_ID);

		$json['digg_count']	= (int)$object->digg_count;
		$json['is_digged']	= $object->is_digged();

		return $json;
	}

	public static function filter_comments_clauses($clauses, $query){
		$orderby	= $query->query_vars['orderby'];

		if(in_array($orderby, ['comment_date_gmt', '']) && wpjam_comment_supports($query, 'digg', 'orderby_digg')){
			$wpdb	= $GLOBALS['wpdb'];
			$order	= $query->query_vars['order'];

			if(!empty($_GET['unapproved']) && !empty($_GET['moderation-hash'])){
				// 解决 Column 'comment_ID' in where clause is ambiguous错误
				$id	= (int)$_GET['unapproved'];

				$clauses['where']	= str_replace(' AND comment_ID = '.$id, ' AND '.$wpdb->comments.'.comment_ID = '.$id, $clauses['where']);	
			}

			$clauses['fields']	.= ", (COALESCE(jam_cm.meta_value, 0)+0) as digg_count";
			$clauses['join']	.= " LEFT JOIN {$wpdb->commentmeta} jam_cm ON {$wpdb->comments}.comment_ID = jam_cm.comment_id AND jam_cm.meta_key = 'digg_count' ";
			$clauses['orderby']	= "digg_count DESC" . ($clauses['orderby'] ? ', '.$clauses['orderby'] : '');
			// $clauses['groupby']	= "{$wpdb->comments}.comment_ID";

			if($orderby == 'comment_date_gmt' && $order	== 'ASC' && get_option('comment_order') === 'desc'){
				$clauses['orderby']	= str_replace(' ASC', ' DESC', $clauses['orderby']);
			}
		}

		return $clauses;
	}

	public static function filter_comment_text($text, $comment){
		if(!is_admin() && wpjam_comment_supports($comment, 'digg')){
			$attr	= self::get_attr($comment->comment_ID);

			if($attr){
				$title	= '<span class="comment-digg-count">'.$attr['count'].'</span> <i class="'.$attr['icon'].'"></i>';
				$data	= wpjam_get_ajax_data_attr('comment-digg', $attr['data']);

				return wpjam_wrap($title, 'a', ['class'=>$attr['class'], 'data'=>$data]).$text;
			}
		}

		return $text;
	}

	public static function on_enqueue_scripts(){
		if(!current_theme_supports('style', 'comment-digg')){
			wp_add_inline_style('remixicon', join("\n", [
				'a.comment-digg{float: right; margin-left: 10px;}',
				'a.comment-digg:not(.is-digged){color:inherit !important;}'
			]));
		}
	}
}

WPJAM_Comment::add_dynamic_method('get_digg_data', function($data=null){
	$data	= $data ?? ($this->digg_data ?: []);

	if(isset($data['user_ids']) || isset($data['user_emails'])){
		$new	= array_merge(array_get($data, 'user_ids', []), array_get($data, 'user_emails', []));
		$result = update_comment_meta($this->id, 'digg_data', $new, $data);

		wp_cache_delete($this->id, 'comment_meta');

		return $new;
	}

	return $data;
});

WPJAM_Comment::add_dynamic_method('is_digged', function($data=null){
	$data	= $data ?? $this->get_digg_data();

	foreach($data as $item){
		if(wpjam_is_current_commenter($item)){
			return true;
		}
	}

	return false;
});

WPJAM_Comment::add_dynamic_method('digg', function($type='digg'){
	$retry_times	= 10;	// CAS 处理

	do{
		$current	= wpjam_is_current_commenter();

		if(!$current){
			return new WP_Error('access_denied');
		}

		$new_data	= $prev_data = $this->get_digg_data();
		$status		= $type == 'digg';

		if($status == $this->is_digged($new_data)){
			return true;
		}

		if(is_numeric($current)){
			if($type == 'digg'){
				$new_data[]	= $current; 
			}else{
				$new_data	= array_diff($new_data, [$current]); 
			}
		}else{
			if($type == 'digg'){
				$commenter	= wp_get_current_commenter();
				$new_data[]	= $commenter['comment_author_email'];
			}else{
				$new_data	= array_values(array_diff($new_data, (array)$current)); 
			}
		}

		$updated	= update_comment_meta($this->id, 'digg_data', $new_data, $prev_data);

		if($updated){
			update_comment_meta($this->id, 'digg_count', count($new_data));
		}
	}while(!$updated && $retry_times > 0);

	if($updated){
		if($type == 'digg'){
			do_action('wpjam_comment_digged', $this->id);
		}else{
			do_action('wpjam_comment_undigged', $this->id);
		}

		return true;
	}

	return new WP_Error('digg_failed', '点赞失败');
});

