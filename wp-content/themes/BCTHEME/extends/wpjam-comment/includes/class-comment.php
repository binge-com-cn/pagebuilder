<?php
class WPJAM_Comment{
	use WPJAM_Call_Trait;

	protected $comment	= null;	// 考虑 populated_children 和 children 参数，不能用 ID

	public function __construct($comment){
		$this->comment	= $comment;
	}

	public function __get($key){
		if($key == 'comment'){
			return $this->comment;
		}elseif($key == 'type_object'){
			return wpjam_get_comment_type_object($this->comment);
		}elseif(in_array($key, ['read', 'draft'])){
			return get_comment_meta($this->id, $key, true);
		}elseif(in_array($key, ['id', 'comment_id'])){
			return $this->comment->comment_ID;
		}elseif(in_array($key, ['post_id', 'comment_post_id'])){
			return $this->comment->comment_post_ID;
		}elseif(in_array($key, ['type', 'comment_type'])){
			return $this->comment->comment_type ?: 'comment';
		}

		if(!str_starts_with($key, 'comment_') && isset($this->comment->{'comment_'.$key})){
			return $this->comment->{'comment_'.$key};
		}elseif(isset($this->comment->$key)){
			return $this->comment->$key;
		}

		return wpjam_get_metadata('comment', $this->id, $key, null);
	}

	public function __isset($key){
		return $this->$key !== null;
	}

	public function __call($method, $args){
		return $this->call_dynamic_method($method, ...$args);
	}

	public function get_unserialized(){
		return wpjam_unserialize($this->content, fn($fixed)=> $this->save(['content'=>$fixed])) ?: [];
	}

	public function save($data){
		return self::update($this->id, $data);
	}

	public function approve(){
		return $this->set_status('approve');
	}

	public function unapprove(){
		return $this->set_status('hold');
	}

	public function spam(){
		return wp_spam_comment($this->id);
	}

	public function parse_for_json($args=[]){
		$comment	= $this->comment;
		$timestamp	= strtotime($this->date_gmt);
		$approved	= (int)$this->approved;
		$approved	= ($approved === 0 && $this->read) ? 2 : $approved;
		$content	= is_serialized($this->content) ? $this->get_unserialized() : wp_strip_all_tags($this->content);
		$json		= [
			'id'		=> (int)$this->id,
			'post_id'	=> (int)$this->post_id,
			'type'		=> $this->type,
			'parent'	=> (int)$this->parent,
			'status'	=> $approved,
			'approved'	=> $approved,
			'timestamp'	=> $timestamp,
			'time'		=> wpjam_human_time_diff($timestamp),
			'date'		=> wp_date('Y-m-d', $timestamp),
			'content'	=> $content,
			'user_id'	=> (int)$this->user_id,
			'author'	=> [
				'nickname'	=> $this->author,
				'avatar'	=> get_avatar_url($comment, 200),
				'user_id'	=> (int)$this->user_id
			]
		];

		if($approved == -1){
			$json['reject_reason']	= $this->reject_reason;
		}elseif(!$approved){
			$json['read']	= (bool)$this->read;
			$json['draft']	= (bool)$this->draft;
		}

		$object	= $this->type_object;

		if($object->action){
			$author	= wpjam_pull($json, 'author');
			$json	= array_merge($json, $author);
		}

		$json	= $object->filter_comment_json($json, $comment, $args);
		$json	= $object->call_features('filter_comment_json', $json, $comment, $args);

		return apply_filters('wpjam_comment_json', $json, $comment, $args);
	}

	public function get_status(){
		$status	= wp_get_comment_status($this->id);

		if($status){
			if($status == 'unapproved'){
				if($this->read){
					return 'read';
				}elseif($this->draft){
					return 'draft';
				}
			}
		}else{
			if($this->approved == '-1'){
				return 'rejected';
			}
		}

		return $status;
	}

	public function set_status($status, $args=[]){
		$status	= (string)$status;

		if(in_array($status, ['-1', 'reject', 'rejected'])){
			if(!$GLOBALS['wpdb']->update($GLOBALS['wpdb']->comments, ['comment_approved'=>-1], ['comment_ID'=>$this->id])){
				return new WP_Error('update_error', __('Could not update comment status.'), $GLOBALS['wpdb']->last_error);
			}

			$reject_reason	= wpjam_pull($args, 'reject_reason');

			if($reject_reason){
				update_comment_meta($this->id, 'reject_reason', $reject_reason);
			}

			clean_comment_cache($this->id);

			do_action('wp_set_comment_status', $this->id, 'reject');

			wp_transition_comment_status('reject', $this->approved, $this->comment);

			wp_update_comment_count($this->post_ID );
		}elseif($status == 'read'){
			update_comment_meta($this->id, 'read', 1);
		}elseif($status == 'draft'){
			update_comment_meta($this->id, 'draft', 1);
		}elseif($status == 'unread'){
			delete_comment_meta($this->id, 'read');
		}else{
			return wp_set_comment_status($this->id, $status, true);
		}

		return true;
	}

	public function value_callback($field){
		return wpjam_get_metadata('comment', $this->id, $field);
	}

	public static function get_instance($comment, $wp_error=false){
		$comment	= self::validate($comment);

		return is_wp_error($comment) ? ($wp_error ? $comment : null) : wpjam_get_instance('comment', $comment->comment_ID, fn()=> new self($comment));
	}

	public static function validate($comment, $comment_type=''){
		if($comment && (!$comment instanceof WP_Comment)){
			$comment	= get_comment($comment);
		}

		if(!$comment || !($comment instanceof WP_Comment)){
			return new WP_Error('invalid_comment');
		}

		if(!get_post($comment->comment_post_ID)){
			return new WP_Error('invald_post_id');
		}

		$type	= $comment->comment_type ?: 'comment';

		if(!wpjam_get_comment_type_object($type) || ($comment_type && $comment_type != 'any' && $comment_type != $type)){
			return new WP_Error('invalid_comment_type');
		}

		return $comment;
	}

	public static function is_by_current($comment=null){
		$commenter	= wp_get_current_commenter();
		$current	= !empty($commenter['user_emails']) ? $commenter['user_emails'] : (is_user_logged_in() ? get_current_user_id() : ($commenter['comment_author_email'] ?: null));

		if(!empty($commenter['user_emails'])){
			$current	= $commenter['user_emails'];
		}elseif(is_user_logged_in()){
			$current	= get_current_user_id();
		}else{
			$current	= $commenter['comment_author_email'] ?: null;
		}

		if(!$current || !$comment){
			return $current ?: false;
		}

		if(is_object($comment)){
			$email		= $comment->comment_author_email;
			$user_id	= $comment->user_id;
		}elseif(is_numeric($comment)){
			$user_id	= $comment;
			$email		= '';
		}else{
			$email		= $comment;
			$user_id	= 0;
		}

		if(is_array($current)){
			if(!in_array($email, $current)){
				return false;
			}
		}elseif(is_numeric($current)){
			if($user_id != $current){
				return false;
			}
		}elseif($current){
			if($email != $current){
				return false;
			}
		}

		return $current;
	}

	public static function get($id){
		$data	= get_comment($id, ARRAY_A);

		if($data){
			$data	= wpjam_except($data, ['populated_children', 'post_fields']);

			foreach($data as $key => $value){
				if(str_starts_with($key, 'comment_')){
					$k	= wpjam_remove_prefix($key, 'comment_');

					$data[$k]	= $data[strtolower($k)]	= $value;
				}
			}

			$data['content']	= wp_strip_all_tags($data['content']);
			$data['meta']		= array_map(fn($item)=> maybe_unserialize($item[0]), (get_comment_meta($id) ?: []));
		}

		return $data;
	}

	public static function insert($data){
		if(empty($data['comment_author'])){
			$current	= self::is_by_current();

			if(!$current){
				return new WP_Error('access_denied');
			}

			if(is_numeric($current)){
				$user	= wp_get_current_user();

				$data['comment_author']			= $user->display_name ?: $user->user_login;
				$data['comment_author_email']	= $user->user_email;
				$data['comment_author_url']		= $user->user_url;

				$data['user_id']	= get_current_user_id();
			}else{
				if(is_user_logged_in()){
					$data['user_id']	= get_current_user_id();
				}

				$commenter	= wp_get_current_commenter();

				$data['comment_author']			= $commenter['comment_author'];
				$data['comment_author_email']	= $commenter['comment_author_email'];
				$data['comment_author_url']		= esc_url($commenter['comment_author_url']);
			}
		}

		$post_id	= $data['comment_post_ID']	= wpjam_pull($data, 'post_id') ?: array_get($data, 'comment_post_ID');
		$post_type	= $post_id ? get_post_type($post_id) : null;

		if(!$post_type || !post_type_exists($post_type)){
			return new WP_Error('invalid_post_id');
		}

		foreach(['content', 'type', 'parent', 'date'] as $key){
			if(isset($data[$key])){
				$data['comment_'.$key]	= wpjam_pull($data, $key);
			}
		}

		$meta	= wpjam_pull($data, 'meta');

		$data['comment_content']	= $data['comment'] ?? array_get($data, 'comment_content');
		$data['comment_type']		= array_get($data, 'comment_type') ?: 'comment';
		$data['comment_parent']		= (int)array_get($data, 'comment_parent');
		$data['comment_date']		= array_get($data, 'comment_date') ?: current_time('mysql');
		$data['comment_date_gmt']	= get_gmt_from_date($data['comment_date']);
		$data['comment_author_IP']	= preg_replace('/[^0-9a-fA-F:., ]/', '', $_SERVER['REMOTE_ADDR']);
		$data['comment_agent']		= substr($_SERVER['HTTP_USER_AGENT'], 0, 254);
		$data['comment_meta']		= ($meta && is_array($meta)) ? $meta : [];

		$data	= wp_filter_comment(wp_slash(apply_filters('preprocess_comment', $data)));
		$object	= wpjam_get_comment_type_object($data['comment_type'], $post_type);

		if(isset($data['status'])){
			if(in_array((string)$data['status'], ['draft', 'hold', '0'], true)){
				if($data['status'] == 'draft'){
					$data['comment_meta']['draft']	= 1;
				}

				$data['comment_approved']	= 0;
			}
		}

		if(!isset($data['comment_approved']) || in_array((string)$data['comment_approved'], ['approve', '1'], true)){
			$data['comment_approved']	= $object->pre_approved($data);
		}

		$id	= wp_insert_comment($data) ?: self::retry_insert($data);

		if(!$id){
			return new WP_Error('comment_save_error', ($object->label ?: '评论').'保存失败，请稍后重试！', 500);
		}

		do_action('comment_post', $id, $data['comment_approved'], $data);

		return $id;
	}

	public static function retry_insert($data){
		$wpdb	= $GLOBALS['wpdb'];
		$fields	= ['comment_author', 'comment_author_email', 'comment_author_url', 'comment_content'];
		$data	= wpjam_map($data, fn($v, $k)=> in_array($k, $fields) ? $wpdb->strip_invalid_text_for_column($wpdb->comments, $k, $data[$k]) : $v);

		return wp_insert_comment(wp_filter_comment($data));
	}

	public static function create($data){
		$id	= self::insert($data);

		return is_wp_error($id) ? $id : self::get_instance($id);
	}

	public static function update($id, $data){
		$result	= self::is_allowed($id, 'update');

		if(is_wp_error($result)){
			return $result;
		}

		$update		= [];
		$contents	= wpjam_pull($data, ['comment', 'content']);

		if($contents){
			$update['comment_content']	= reset($contents);
		}

		if(isset($data['author'])){
			$update['comment_author']	= $data['author'];
		}

		if(!empty($data['date'])){
			$update['comment_date']		= $data['date'];
			$update['comment_date_gmt']	= get_gmt_from_date($data['date']);
		}

		if(isset($data['status'])){
			if(in_array((string)$data['status'], ['draft', 'hold', '0'], true)){
				if($data['status'] == 'draft'){
					$meta['draft']	= 1;
				}

				$update['comment_approved']	= 0;
			}elseif(in_array((string)$data['status'], ['approve', '1'], true)){
				$update['comment_approved']	= 1;
			}
		}

		if(isset($update['comment_approved']) && in_array((string)$update['comment_approved'], ['approve', '1'], true)){
			$object	= wpjam_get_comment_type_object($id);

			$update['comment_approved']	= $object->pre_approved($data);
		}

		if(!empty($data['meta'])){
			foreach($data['meta'] as $key => $value){
				if($value === ''){
					delete_comment_meta($id, $key);
				}else{
					$meta[$key]	= $value;
				}
			}

			if(!empty($meta)){
				$update['comment_meta']	= $meta;
			}
		}

		return $update ? wp_update_comment(array_merge($update, ['comment_ID'=>$id]), true) : true;
	}

	public static function delete($id, $force_delete=false){
		$result	= self::is_allowed($id, 'delete');

		return is_wp_error($result) ? $result : wp_delete_comment($id, $force_delete);
	}

	public static function is_allowed($comment=null, $action=''){
		$comment	= self::validate($comment);

		if(is_wp_error($comment)){
			return $comment;
		}

		if(is_user_logged_in() && in_array($action, ['update', 'delete']) && current_user_can('moderate_comments')){
			return true;
		}

		return self::is_by_current($comment) ? true : new WP_Error('access_denied');
	}

	public static function update_caches($ids){
		_prime_comment_caches($ids);
	}

	public static function col_left(){
		$object		= get_screen_option('list_table', 'type_object');
		$query		= get_screen_option('list_table', 'post_query');
		$posts		= $query->posts;

		$pt_label	= get_post_type_object($object->post_type)->label;
		$options	= ['most'=>'最多'.$object->label, 'publish'=>'最新'.$pt_label];
		$options	= ($object->post_meta ? [] : ['new'=>'最新'.$object->label])+$options;
		$field		= wpjam_field([
			'key'		=> 'left_type',
			'type'		=> 'select',
			'class'		=> 'left-filter',
			'value'		=> wpjam_get_data_parameter('left_type'),
			'options'	=> $options
		]);

		if($posts){
			$tag		= wpjam_tag();
			$counts		= [];
			$post_ids	= array_column($posts, 'ID');
			$post_id	= wpjam_get_data_parameter('post_id');

			if($post_id && !in_array($post_id, $post_ids)){ 
				array_unshift($post_ids, $post_id);
				array_unshift($posts, get_post($post_id));
			}

			foreach(WPJAM_Comments::get_counts(['post_ids'=>$post_ids, 'type'=>$object->name]) as $count){
				$comment_post_id	= $count['comment_post_ID'];
				$comment_approved	= $count['comment_approved'];

				$counts[$comment_post_id][$comment_approved]	= $count['count'];
			}

			foreach($posts as $post){ 
				$class		= 'left-item'.($post_id == $post->ID ? ' left-current' : '');
				$count		= $object->get_count($post->ID);

				$approved	= $counts[$post->ID][1] ?? 0;
				$unapproved	= $counts[$post->ID][0] ?? 0;

				// if($approved != $count){
				// 	$diff	= '不相同('.$approved.')';
				// }else{
				// 	$diff	= '';
				// }

				if($object->name == 'comment'){
					$count	= $count ? $count.'条评论': (comments_open($post) ? '暂无评论' : '评论未开启');
				}else{
					// if($diff){
					// 	$count	= $approved;
					// 	update_post_meta($post->ID, $object->plural, $count);
					// }

					$count	= ($count ? $count.' ' : '暂无').$object->label;
				}

				// $count	.= $diff;

				$post_card	= wpjam_tag('span', ['post-title'], get_the_title($post))->after(_post_states($post, false));

				// if($unapproved){
				// 	$post_card->after('<span class="post-com-count post-com-count-pending"><span class="comment-count-pending" aria-hidden="true">'.$unapproved.'</span><span class="screen-reader-text">'.$unapproved.'条待审</span></span>');
				// }

				$post_card->wrap('p', ['row-title', 'column-response']);

				wpjam_tag('span', ['post-time'], get_the_time('Y-m-d H:i:s', $post))->after('span', ['comment-count', 'wp-ui-highlight'], $count)->wrap('p')->insert_after($post_card);

				$post_card->wrap('td')->wrap('tr', ['id'=>'left-'.$post->ID, 'class'=>$class, 'data-id'=>$post->ID])->append_to($tag);
			}
		}else{
			$tag	= wpjam_tag('td', ['colspanchange'], '找不到'.$pt_label)->wrap('tr', ['class'=>'no-items']);
		}

		echo wpjam_tag('div', ['alignleft', 'actions'], $field)->after('br', ['clear'])->wrap('div', ['class'=>'tablenav']);
		echo $tag->wrap('tbody')->before([[$pt_label, 'th'], 'tr'], 'thead')->after([[$pt_label, 'th'], 'tr'], 'tfoot')->wrap('table', ['widefat striped']);

		return ['total_items'=>$query->found_posts, 'per_page'=>10, 'left_value'=>$post_id ?? 0];
	}

	public static function query_items($args){
		return self::call_type('query_items', $args);
	}

	public static function render_item($item){
		return self::call_type('render_item', $item);
	}

	public static function get_fields($action_key='', $id=0){
		return self::call_type('get_fields', $action_key, $id) ?: [];
	}

	public static function get_actions(){
		return self::call_type('get_actions') ?: [];
	}

	protected static function call_type($method, ...$args){
		$object	= get_screen_option('list_table', 'type_object');

		if($object){
			$post_id	= (int)wpjam_get_data_parameter('post_id');
			$args		= $method == 'render_item' ? $args : [$post_id, ...$args];

			return call_user_func([$object, $method], ...$args);
		}
	}

	public static function get_list_table($menu){
		$object		= $menu->type_object;
		$post_id	= (int)wpjam_get_data_parameter('post_id');

		if($post_id){
			$result	= WPJAM_Post::validate($post_id, $object->post_type);

			if(is_wp_error($result)){
				wp_die($result);
			}
		}

		$left_type	= wpjam_get_data_parameter('left_type') ?: ($object->post_meta ? 'most' : 'new');
		$left_paged	= wpjam_get_data_parameter('left_paged') ?: 1;
		$query_args	= ['post_type'=>$object->post_type, 'paged'=>$left_paged, 'post_status'=>'', 'posts_per_page'=>10];

		if($left_type == 'most'){
			if($object->name == 'comment'){
				$query_args['orderby']			= 'comment_count';
				$query_args['comment_count']	= ['value'=> 1, 'compare'=>'>='];
			}else{
				$query_args['orderby']	= 'meta_value_num';
				$query_args['meta_key']	= $object->plural;
			}
		}if($left_type == 'new'){
			$query_args['orderby']		= 'comment_date';
			$query_args['comment_type']	= $object->name;
		}

		$query	= new WP_Query($query_args);

		wp_add_inline_style('list-tables', wpjam_remove_pre_tab('
			table.wp-list-table th,table.wp-list-table td {
				box-shadow: inset 0 -1px 0 rgba(0, 0, 0, 0.1);
			}

			table.wp-list-table tr:last-child th,
			table.wp-list-table tr:last-child td {
				box-shadow: none;
			}

			table.wp-list-table tr.unapproved + tr.approved th,
			table.wp-list-table tr.unapproved + tr.approved td {
				border-top: 1px solid rgba(0, 0, 0, 0.03);
			}

			table.wp-list-table .unapproved th,
			table.wp-list-table .unapproved td{background-color: #fcf9e8;}
			table.wp-list-table .unapproved th.check-column {border-left: 4px solid #d63638;}
			table.wp-list-table .read th.check-column{border-left: 4px solid #0073aa;}
			table.wp-list-table .unapproved th.check-column input,
			table.wp-list-table .read th.check-column input{margin-left: 4px;}
			table.wp-list-table .approve a,
			table.wp-list-table .mark_as_read a{color: #007017;}
			table.wp-list-table .unapprove a{color: #996800;}

			th.manage-column{min-width:28px;}
			th.column-author{width: 30%;}
			th.column-comment_date{width: 12%;}
			.widefat td.column-comment p{margin:0 0 .6em 0;}
			span.author-pad{float:left; margin-right:10px;}

			@media screen and (min-width: 782px){ 
				#col-left{width: 25%;} 
				#col-right{width: 75%}
			}

			div#col-left td{padding-left: 14px; padding-right: 20px;}
			div#col-left td a:focus{box-shadow: inherit;}
			div#col-left td span.post-time{font-size:smaller;}
			div#col-left td span.comment-count{font-size:smaller; float:right; padding:2px 4px;}
		', 3));

		wp_add_inline_script('jquery', wpjam_remove_pre_tab("
			jQuery(function($){
				$('body').on('click', 'a.reply_to', function(event){
					let parent	= $(this).data('parent');
					let top		= $('#comment-'+parent).offset().top-40;

					$('html').animate({scrollTop: top}, 500, function(){
						$('#comment-'+parent).animate({opacity: 0.1}, 500).animate({opacity: 1}, 500);
					});

				    event.preventDefault();
				});
			});
		", 3));

		return [
			'primary_key'	=> $object->post_meta ? '' : 'id',
			'plural'		=> $object->plural,
			'singular'		=> $object->name,
			'per_page'		=> $object->post_meta ? 9999 : 20,
			'model'			=> 'WPJAM_Comment',
			'layout'		=> 'left',
			'left_key'		=> 'post_id',
			'defaults'		=> ['post_id'=>$query->posts ? reset($query->posts)->ID : 0],
			'type_object'	=> $object,
			'post_query'	=> $query
		];
	}

	public static function get_setting($name='', $default=null){
		return wpjam_get_setting('wpjam-comment', $name) ?? $default;
	}

	public static function update_setting(...$args){
		return wpjam_update_setting('wpjam-comment', ...$args);
	}
}

class WPJAM_Comments{
	public static function parse($query, $type=''){
		$query	= is_object($query) ? $query : self::query($query);
		$args	= $query->query_vars;

		foreach($query->comments as $comment){
			$comment_json	= wpjam_get_comment($comment, $args);

			if($type){
				$post_json	= wpjam_get_post($comment_json['post_id'], $args);
			}

			if($type == 'action'){
				if($post_json){
					$comment_type	= $comment_json['type'];
					$parsed[]		= array_merge($post_json, [$comment_type=>$comment_json]);
				}
			}else{
				if($type == 'with_post' && $post_json){
					$post_type		= $post_json['post_type'];
					$comment_json	= array_merge($comment_json, [$post_type=>$post_json]);
				}

				$parsed[]	= $comment_json;
			}
		}

		return $parsed ?? [];
	}

	public static function query($args){
		$args['type']	= array_get($args, 'type') ?: 'comment';
		$post_id		= (int)array_get($args, 'post_id');

		if($post_id){
			$args['status']		= $args['status'] ?? 'approve';
			$filter_unapporeved	= false;
			$include_unapproved	= wpjam_pull($args, 'include_unapproved', true);

			if($args['status'] == 'approve'){
				if($include_unapproved){
					$filter_unapporeved	= true;
					$args['status']		= 'all';
				}
			}

			$number	= wpjam_pull($args, 'number') ?: 0;

			if($number){
				$offset	= wpjam_pull($args, 'offset') ?: 0;
				$paged	= wpjam_pull($args, 'paged') ?: 1;

				$args['no_found_rows']	= true;
			}

			$query	= new WP_Comment_Query($args);

			if($filter_unapporeved){
				$query->comments	= array_filter($query->comments, fn($comment)=> $comment->comment_approved == '1' || wpjam_is_current_commenter($comment));
			}

			if($number){
				$query->found_comments	= count($query->comments);
				$query->max_num_pages	= ceil($query->found_comments/$number);

				$query->query_vars['number']	= $number;
				$query->query_vars['paged']		= $paged;
				$query->query_vars['offset']	= $offset;

				$query->comments	= array_slice($query->comments, ($offset ?: ($paged-1)*$number), $number);
			}

			return $query;
		}else{
			if(!isset($args['update_comment_meta_cache'])){
				$args['update_comment_meta_cache']	= true;
			}

			if(!isset($args['update_comment_post_cache'])){
				$args['update_comment_post_cache']	= true;
			}

			$args['status']	= $args['status'] ?? 'any';
			$args['order']	= $args['order'] ?? 'desc';
			$args['number']	= $args['number'] ?? 20;

			$args['no_found_rows']	= false;

			$current	= wpjam_is_current_commenter();

			if(!$current){
				return new WP_Error('access_denied');
			}

			if(is_numeric($current)){
				$args['user_id']	= $current;
			}elseif(is_array($current)){
				$args['author_email__in']	= $current;
			}else{
				$args['author_email']	= $current;
			}

			return new WP_Comment_Query($args);
		}
	}

	public static function get_counts($args=[]){
		$args	= wp_parse_args($args, ['approved'=>'', 'type'=>'', 'post_id'=>'', 'post_ids'=>[]]);
		$fields	= ['comment_post_ID', 'comment_approved', 'comment_type'];

		if($args['post_ids']){
			$where	= 'comment_post_ID in ('.implode(',', wp_parse_id_list($args['post_ids'])).')';
		}elseif($args['post_id']){
			$where	= 'comment_post_ID = '.(int)$args['post_id'];
		}else{
			return [];
		}

		if(is_numeric($args['approved'])){
			$where	.= ' AND comment_approved = '.(int)$args['approved'];
			$fields	= array_diff($fields, ['comment_approved']);
		}

		if($args['type']){
			$where	.= ' AND comment_type = '."'".esc_sql($args['type'])."'";
			$fields	= array_diff($fields, ['comment_type']);
		}

		$fields	= implode(', ', $fields);
		$sql	= "SELECT count(*) as count, {$fields} FROM {$GLOBALS['wpdb']->comments} WHERE {$where} GROUP BY {$fields}"; 

		return $GLOBALS['wpdb']->get_results($sql, ARRAY_A);
	}

	public static function get_admin_link($post_id){
		$post_type	= get_post_type($post_id);
		$page		= $post_type.'-comments';
		$args		= $post_type == 'post' ? compact('page', 'post_id') : compact('post_type', 'page', 'post_id');

		return admin_url(add_query_arg($args, 'edit.php'));
	}

	public static function builtin_page_load($screen){
		if($screen->base == 'edit-comments'){
			$post_id	= (int)wpjam_get_parameter('p');

			if($post_id){
				wp_redirect(self::get_admin_link($post_id));

				exit;
			}

			$types	= WPJAM_Comment_Type::get_registereds(['sub_name'=>false, 'comment'=>true], 'names');

			if($types){
				add_filter('comments_list_table_query_args', fn($args)=> array_merge($args, ['type__in'=> $types]), 1);
			}

			add_filter('comment_email', fn($email)=> (strpos($email, '.weapp') || strpos($email, '.weixin')) ? '' : $email, 9);

			$GLOBALS['wpjam_list_table']	= new WPJAM_Comments_List_Table();
		}else{
			if(post_type_supports($screen->post_type, 'comments')){
				wpjam_register_list_table_action('add_comment', [
					'title'			=> '添加评论',
					'submit_text'	=> '添加',
					'row_action'	=> false,
					'redirect'		=> [self::class, 'get_admin_link']
				]);

				add_filter('wpjam_single_row',	[self::class, 'filter_post_single_row']);

				add_filter('query',	['WPJAM_Comment_Read', 'filter_query']);
			}
		}
	}

	public static function on_form_before(){
		$reply_to_id	= isset($_GET['replytocom']) ? (int)$_GET['replytocom'] : 0;

		if($reply_to_id && !get_comment($reply_to_id)){	// 防止无效的 ID 引起 warning
			unset($_GET['replytocom']);
		}
	}

	public static function on_transition_comment_status($new_status, $old_status, $comment){
		if($old_status == 'unapproved' && $new_status == 'approved'){
			delete_comment_meta($comment->comment_ID, 'draft');
		}
	}

	public static function on_parse_comment_query($query){
		$vars	= &$query->query_vars;

		if(!empty($vars['cursor'])){
			if(!empty($vars['parent__in'])){
				$vars['date_query']	= [];
			}else{
				$key	= strcasecmp($vars['order'], 'asc') ? 'before' : 'after';

				$vars['date_query']	= [[$key => get_date_from_gmt(date('Y-m-d H:i:s', $vars['cursor']))]];
			}
		}
	}

	public static function on_pre_get_comments($query){
		if(!empty($query->query_vars['author_email__in'])){	// Only use the args defined in the query_var_defaults to compute cache key,
			$query->query_var_defaults['author_email__in']	= [];
		}
	}

	public static function filter_form_default_fields($fields){
		$post_type	= get_post_type();

		if($post_type && WPJAM_Comment::get_setting($post_type.'_comment_disable_form_url')){
			unset($fields['url']);
		}

		return $fields;
	}

	public static function filter_get_author_link($return, $author, $comment_id){
		$post_type	= get_comment($comment_id)->post_type;

		if($post_type && WPJAM_Comment::get_setting($post_type.'_comment_blank_target_author_link')){
			return str_replace("rel='external", "taget='_blank' rel='external", $return);
		}

		return $return;
	}

	public static function filter_pre_content($content){
		if($content && is_serialized($content)){
			$name		= current_filter();
			$callback	= current_user_can('unfiltered_html') ? 'wp_filter_post_kses' : 'wp_filter_kses';

			if(has_filter($name, $callback)){
				remove_filter($name, $callback);
				add_filter($name, fn($content)=> $content && is_serialized($content) ? $content : $callback($content));
			}
		}

		return $content;
	}

	public static function filter_pre_approved($approved, $data){
		if(is_wp_error($approved) || $approved == 0){
			return $approved;
		}

		$post_id	= $data['comment_post_ID'];
		$user_id	= $data['user_id'] ?? '';

		if($user_id && user_can($user_id, 'edit_post', $post_id)){
			return $approved;
		}

		$name		= $data['comment_type'] ?: 'comment';
		$post_type	= get_post_type($post_id);
		$object		= wpjam_get_comment_type_object($name, $post_type);

		if($name == 'comment' && $post_type){
			$min	= WPJAM_Comment::get_setting($post_type.'_comment_minimum_length');
			$max	= WPJAM_Comment::get_setting($post_type.'_comment_maximum_length');
			$length	= mb_strlen($data['comment_content']);

			if($min && $length < $min){
				return new WP_Error('comment_too_short', '评论至少输入'.$min.'个字');
			}

			if($max && $length > $max){
				return new WP_Error('comment_too_long', '评论最多输入'.$max.'个字');
			}
		}

		return $object->supports('moderation') ? 0 : $approved;
	}

	public static function filter_pre_update_count($count, $old, $post_id){
		$counts	= self::get_counts(['post_id'=>$post_id, 'approved'=>1]);
		$counts	= $counts ? array_column($counts, 'count', 'comment_type') : [];

		foreach(WPJAM_Comment_Type::get_registereds(['post_type'=>get_post_type($post_id)]) as $object){
			if($object->name != 'comment' && !$object->post_meta && $object->plural){
				$count	= $counts[$object->name] ?? 0;

				if(get_post_meta($post_id, $object->plural, true) != $count){
					update_post_meta($post_id, $object->plural, $count);
				}
			}
		}

		return $counts['comment'] ?? 0;
	}

	public static function filter_clauses($clauses, $query){
		if(!empty($query->query_vars['author_email__in'])){
			$author_email__in	= array_map(fn($v)=> $GLOBALS['wpdb']->prepare('%s', $v), $query->query_vars['author_email__in']);
			$clauses['where']	.= ' AND comment_author_email in ('.implode(',', $author_email__in).')';
		}

		return $clauses;
	}

	public static function filter_is_flood($is_flood, $ip, $email, $date, $avoid_die=false){
		if(current_user_can('manage_options') || current_user_can('moderate_comments')){
			return false;
		}

		$lasttime	= gmdate('Y-m-d H:i:s', time() - 15);
		$value		= is_user_logged_in() ? get_current_user_id() : $ip;
		$column		= is_user_logged_in() ? '`user_id`' : '`comment_author_IP`';
		$wpdb		= $GLOBALS['wpdb'];
		$sql		= $wpdb->prepare("SELECT `comment_date_gmt` FROM `$wpdb->comments` WHERE `comment_type` = 'comment' AND `comment_date_gmt` >= %s AND ( $column = %s OR `comment_author_email` = %s ) ORDER BY `comment_date_gmt` DESC LIMIT 1", $lasttime, $value, $email);

		if($wpdb->get_var($sql)){
			if($avoid_die){
				return true;
			}

			$message = apply_filters('comment_flood_message', __('You are posting comments too quickly. Slow down.'));

			if(wp_doing_ajax()){
				die($message);
			}

			wp_die($message, 429);
		}

		return false;
	}

	public static function filter_is_protected_meta($protected, $meta_key, $meta_type){
		if($meta_type == 'post' && !$protected){
			foreach(WPJAM_Comment_Type::get_registereds(['post_type'=>false]) as $object){
				if($object->name != 'comment' && $meta_key == $object->plural){
					return true;
				}
			}
		}

		return $protected;
	}

	public static function filter_current_commenter($commenter){
		$commenter	+= ['user_emails'=>[]];
		$current	= wpjam_get_current_user(false);

		if($current){
			if(!empty($current['user_email'])){
				$commenter['comment_author_email']	= $commenter['user_emails'][] = $current['user_email'];
			}

			if(empty($commenter['comment_author'])){
				$commenter['comment_author']	= $current['nickname'];
			}
		}

		return $commenter;
	}

	public static function filter_get_avatar_types($types){
		return array_merge($types, WPJAM_Comment_Type::get_registereds(['post_type'=>false], 'names'));
	}

	public static function filter_post_json($json, $post_id){
		$filter = current_filter();

		foreach(WPJAM_Comment_Type::get_registereds(['post_type'=>$json['post_type']]) as $object){
			if($object->plural){
				$json[$object->name.'_count']	= $object->get_count($post_id);
				$json[$object->name.'_status']	= $object->get_status($post_id);
			}

			$json	= array_merge($json, $object->get_features('setting'));

			if($filter == 'wpjam_post_json'){
				$json	= $object->filter_post_json($json, $post_id);
			}
		}

		return $json;
	}

	public static function filter_posts($posts, $wp_query){
		if(count($posts) > 1 && !$wp_query->is_singular() && wpjam_is_current_commenter()){
			$post_ids	= array_column($posts, 'ID');
			$objects	= WPJAM_Comment_Type::get_registereds(['sub_name'=>false, 'action'=>true, 'post_meta'=>false]);

			array_walk($objects, fn($object)=> $object->is_acted ? wpjam_lazyload('current_'.$object->is_acted, $post_ids) : null);
		}

		return $posts;
	}

	public static function filter_list_table_query_args($args){
		$types	= WPJAM_Comment_Type::get_registereds(['sub_name'=>false, 'comment'=>true], 'names');

		if($types){
			$args['type__in']	??= [];
			$args['type__in']	+= $types;
		}

		return $args;
	}

	public static function filter_post_single_row($single_row){
		return preg_replace_callback('/(<div class="post-com-count-wrapper">)(.*?)<\/div>/is', function($matches){
			$action	= '[row_action name="add_comment" dashicon="plus-alt2" class="row-action"]';

			return strpos($matches[0], '<span aria-hidden="true">&#8212;</span>') !== false ? $matches[1].$matches[2].''.$action.'</div>' : $matches[0].$action;
		}, $single_row);
	}
}

if(is_admin()){
	class WPJAM_Comments_List_Table extends WPJAM_Builtin_List_Table{
		public function __construct(){
			parent::__construct([
				'title'			=> '评论',
				'data_type'		=> 'comment',
				'singular'		=> 'comment',
				'capability'	=> 'moderate_comments',
				'model'			=> 'WPJAM_Comment',
				'hook_part'		=> ['comments', 'comment']
			], 'WP_Comments_List_Table');
		}

		public function single_row($raw_item){
			echo $this->_builtin->single_row(is_numeric($raw_item) ? get_comment($raw_item) : $raw_item);
		}

		public function filter_row_actions($row_actions, $comment){
			$row_actions	= array_merge($row_actions, $this->get_row_actions($comment->comment_ID));
			$row_actions	+= wpjam_pull($row_actions, ['delete', 'trash', 'spam']);

			return array_merge($row_actions, ['comment_id'=>'ID: '.$comment->comment_ID]);
		}
	}
}