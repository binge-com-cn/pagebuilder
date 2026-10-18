<?php
/**
* @config model=object menu_page register_json
**/
#[config(['model'=>'object', 'menu_page', 'register_json'])]
class WPJAM_Comment_Type extends WPJAM_Register{
	public function __call($method, $args){
		if(method_exists($this->model, $method)){
			$this->model->object	= $this;

			return call_user_func_array([$this->model, $method], $args);
		}

		return array_shift($args);
	}

	public function __get($key){
		if($key == 'post_type'){
			return $this->sub_name;
		}elseif($key == 'model'){
			if($this->post_type){
				return ($this->get_parent())->model;
			}
		}

		$value	= parent::__get($key);

		if(is_null($value)){
			if($key == 'is_acted'){
				return $this->is_did;	// 兼容
			}
		}

		return $value;
	}

	public function pre_approved($data){
		$approved	= $this->action ? apply_filters('pre_comment_approved', $this->approve, $data) : wp_allow_comment($data, true);

		if(is_wp_error($approved)){
			$label	= $this->label ?: '评论';

			if($approved->get_error_code() == 'comment_duplicate'){
				return new WP_Error('comment_duplicate', '检测到重复'.$label.'，您似乎已经提交过这条'.$label.'了！');
			}elseif($approved->get_error_code() == 'comment_flood'){
				return new WP_Error('comment_flood', '您提交'.$label.'的速度太快了，请稍后再提交。');
			}

			return $approved;
		}

		return (!$this->action && current_user_can('moderate_comments')) ? 1 : $approved;
	}

	protected function parse_method($method, $type=null){
		if($this->post_type && in_array($method, ['register_json', 'get_menu_page'])){
			return null;
		}

		return parent::parse_method($method, $type);
	}

	public function create_sub($post_type){
		$sub	= $this->get_sub($post_type);

		if(!$sub){
			$args	= wpjam_except($this->args, ['model', 'subs']);
			$sub	= $this->register_sub($post_type, $args);

			foreach($sub->get_available_features() as $feature => $object){
				$value	= $object->get_setting($sub);

				if($value){
					$sub->add_feature($feature, $value);
				}
			}
		}

		return $sub;
	}

	public function supports($feature, $key=null){
		$value	= $this->get_item($feature);

		if(is_null($value)){
			return false;
		}

		if(!$key){
			return $value;
		}

		$object	= WPJAM_Comment_Feature::get($feature); 

		return $object->get_setting($this, $key);
	}

	public function add_feature($feature, $value=true){
		if(is_array($feature)){
			foreach($feature as $f => $v){
				if(is_numeric($f)){
					$f	= $v;
					$v	= true;
				}

				$this->add_feature($f, $v);
			}
		}else{
			$object	= WPJAM_Comment_Feature::get($feature);

			if($object){
				$this->add_item($feature, $value);

				$object->added($this);
			}
		}
	}

	public function get_features($output='object'){
		$result	= [];

		foreach($this->get_items() as $feature => $value){
			$object	= WPJAM_Comment_Feature::get($feature);

			if($object){
				if($output == 'object'){
					$result[$feature]	= $object;
				}elseif($output == 'setting'){
					$key	= $object->prefix($this, $feature, false);
					$result	+= [$key=> is_numeric($value) ? (bool)$value : $value];
				}elseif($output == 'meta_fields'){
					$result	= array_merge($result, ($object->get_fields($this, 'meta') ?: []));
				}elseif($output == 'actions'){
					$result	= array_merge($result, ($object->get_actions($this) ?: []));
				}
			}
		}

		return $result;
	}

	public function call_features($method, ...$args){
		foreach($this->get_items() as $feature => $value){
			$object	= WPJAM_Comment_Feature::get($feature);

			if($object){
				$result	= call_user_func([$object, $method], ...$args);

				if(!is_null($result) && $args){
					$args[0]	= $result;
				}
			}
		}

		return $args[0] ?? null;
	}

	public function get_available_features(){
		return WPJAM_Comment_Feature::get_registereds(['type'=>['value'=>$this->name, 'if_null'=>true]]);
	}

	public static function create($comment_type, ...$args){
		if(count($args) >= 2){
			$post_type	= $args[0];
			$args		= $args[1];
		}else{
			$args		= $args[0] ?? [];
			$post_type	= wpjam_pull($args, 'post_type', wpjam_pull($args, 'object_type'));
		}

		$args	= wp_parse_args($args, [
			'name'		=> $comment_type,
			'plural'	=> $comment_type.'s',
			'label'		=> '',
			'order'		=> 'ASC',
			'approve'	=> 1,
			'action'	=> false,
			'comment'	=> false // 是否在后台留言页显示
		]);

		$object	= self::get($comment_type);
		$object	= $object ?: self::register($comment_type, $args);

		return $post_type ? $object->create_sub($post_type) : $object;
	}

	public static function on_registered_post_type($post_type, $pt_object){
		if($pt_object->_builtin && !in_array($post_type, ['post','page'])){
			return;
		}

		if(post_type_supports($post_type, 'comments')){
			$object	= self::create('comment', $post_type, [
				'model'		=> 'WPJAM_Post_Comment',
				'label'		=> '评论',
				'plural'	=> 'comments',
				'acted'		=> 'commented',
				'order'		=> get_option('comment_order', 'asc'),
				'paged'		=> get_option('page_comments'),
				'number'	=> get_option('comments_per_page'),
				'comment'	=> true
			]);
		}

		if(post_type_supports($post_type, 'favs')){
			self::create('fav', $post_type, [
				'model'		=> 'WPJAM_Post_Action',
				'label'		=> '收藏',
				'plural'	=> 'favs',
				'action'	=> true,
				'is_acted'	=> 'is_faved',
				'acted'		=> 'faved',
				'icon'		=> ['ri-star-fill', 'ri-star-line'],
			]);
		}

		if(post_type_supports($post_type, 'likes')){
			self::create('like', $post_type, [
				'model'		=> 'WPJAM_Post_Like',
				'label'		=> '点赞',
				'plural'	=> 'likes',
				'action'	=> true,
				'post_meta'	=> true,
				'cookie'	=> true,
				'is_acted'	=> 'is_liked',
				'acted'		=> 'liked',
				'icon'		=> ['ri-heart-fill', 'ri-heart-line'],
			]);
		}
	}
}

class WPJAM_Comment_Feature extends WPJAM_Register{
	public function __call($method, $args){
		if($method == 'added'){
			$args[]	= $this->added = (int)$this->added+1;
		}

		return $this->call_method($method, ...$args);
	}

	public function prefix($object, $key='', $post_type=true){
		$prefix	= $this->prefix === false ? '' : $object->name.'_';
		$prefix	= $post_type ? $object->post_type.'_'.$prefix : $prefix;

		return $prefix.$key;
	}

	public function get_fields($object, $type, $parse=true){
		if($type == 'setting' && $this->is_supported($object) === false){
			return [];
		}

		$fields	= $this->call_method('get_fields', $type, $object);

		if($type == 'setting' && $fields && $parse){
			$prefix		= $this->prefix($object);
			$show_if	= ['key'=>$prefix.$this->name, 'value'=>1];
			$fields		= wpjam_array($fields, fn($k, $v)=> [$prefix.$k, ($k == $this->name ? $v : $v+['show_if'=>$show_if])]);

			if(count($fields) > 1){
				$title	= wpjam_pull($fields[$prefix.$this->name], 'title');
				$fields	= [$prefix.$this->name=> ['title'=>$title, 'type'=>'fieldset', 'fields'=>$fields]];
			}

			return $fields;
		}

		return $fields;
	}

	public function get_setting($object, $key=null){
		$fields	= $this->get_fields($object, 'setting', false);
		$key	= $key ?: $this->name;

		if($key == $this->name){
			$result	= $this->is_supported($object);

			if(!is_null($result)){
				return $result;
			}

			if(!isset($fields[$key])){
				return true;
			}
		}

		if(isset($fields[$key])){
			$default	= $fields[$key]['value'] ?? null;
			$key		= $this->prefix($object, $key);
			$value 		= wpjam_comment_get_setting($key, $default);

			if($key == 'reply_type' && $value == 'disabled'){
				return 'all';	// 兼容
			}

			return $value;
		}
	}

	public static function get_defaults(){
		$dir		= dirname(__DIR__).'/public';
		$defaults	= [
			'digg'		=> [
				'type'	=> 'comment',
				'title'	=> '点赞',
				'model'	=> 'WPJAM_Comment_Digg',
				'file'	=> $dir.'/comment-digg.php'
			],
			'sticky'	=> [
				'type'	=> 'comment',
				'title'	=> '置顶',
				'model'	=> 'WPJAM_Comment_Sticky',
				'file'	=> $dir.'/comment-sticky.php'
			],
			'rating'	=> [
				'type'	=> 'comment',
				'model'	=> 'WPJAM_Comment_Rating',
				'file'	=> $dir.'/comment-rating.php'
			],
			'read'		=> [
				'type'	=> 'comment',
				'model'	=> 'WPJAM_Comment_Read',
				'file'	=> $dir.'/comment-features.php',
			],
		];

		if(did_action('weapp_loaded')){
			$defaults['anonymous']	= [
				'type'	=> 'comment',
				'model'	=> 'WPJAM_Comment_Anonymous',
				'file'	=> $dir.'/comment-features.php',
			];

			$defaults['images'] 	= [
				'model'	=> 'WPJAM_Comment_Images',
				'file'	=> $dir.'/comment-features.php',
			];

			$defaults['moderation']	= [
				'type'	=> 'comment',
				'model'	=> 'WPJAM_Comment_Moderation',
				'file'	=> $dir.'/comment-features.php',
			];
		}

		if(did_action('wpjam_account_loaded')){
			$defaults['account'] 	= [
				'model'	=> 'WPJAM_Comment_Account',
				'file'	=> $dir.'/comment-account.php',
			];
		}

		if(get_option('thread_comments')){
			$defaults['reply_type']	=  [
				'type'		=> 'comment',
				'prefix'	=> false,
				'model'		=> 'WPJAM_Comment_Reply',
				'file'		=> $dir.'/reply-type.php'
			];
		}

		return $defaults;
	}
}

class WPJAM_Post_Comment extends WPJAM_Args{
	protected function cache($action, ...$args){
		if($this->action){
			$current	= wpjam_is_current_commenter();
			$key		= is_array($current) ? current($current) : $current;
			$group		= $this->is_acted;
			$cache		= wp_cache_get($key, $group) ?: [];
			$post_id	= array_shift($args);

			if($action == 'get'){
				return $post_id ? ($cache[$post_id] ?? null) : $cache;
			}elseif($action == 'set'){
				$data	= is_array($post_id) ? $post_id : [$post_id=> array_shift($args)];
				$cache	= array_replace($cache, $data);

				return wp_cache_set($key, $cache, $group, DAY_IN_SECONDS);
			}elseif($action == 'del'){
				if(isset($cache[$post_id])){
					return wp_cache_set($key, wpjam_except($cache, $post_id), $group, DAY_IN_SECONDS);
				}

				return true;
			}
		}
	}

	public function get_count($post_id){
		if($this->name == 'comment'){
			return (int)get_comments_number($post_id);
		}else{
			return (int)get_post_meta($post_id, $this->plural, true);
		}
	}

	public function get_status($post_id){
		if($this->name == 'comment'){
			return comments_open($post_id) ? 'open' : 'closed';
		}

		return 'open';
	}

	public function get_data($post_id, $parse=false){
		return $parse ? wpjam_get_comments($this->query(['post_id'=>$post_id])) : get_comments([
			'post_id'	=> $post_id,
			'type'		=> $this->name,
			'order'		=> $this->order,
			'status'	=> 'any'
		]);
	}

	public function validate_by_post($post_id, $post_type=null){
		$post	= WPJAM_Post::validate($post_id, $post_type);

		if(is_wp_error($post)){
			return $post;
		}

		$post_type	= get_post_type($post);

		if(!current_user_can('moderate_comments') && 'publish' != get_post_status($post)){
			return new WP_Error('invalid_post_status', [wpjam_get_post_type_setting($post_type, 'title')]);
		}

		$sub	= $this->object->get_sub($post_type);

		return $sub ?: new WP_Error($this->name.'_not_supported', '不支持'.$this->label);
	}

	public function add($post_id, $data){
		if(!current_user_can('moderate_comments')){
			if($this->name == 'comment' && !comments_open($post_id)){
				return new WP_Error('comment_closed', '评论已关闭。');
			}
		}

		$data['post_id']	= $post_id;
		$data['type']		= $this->name;

		$contents	= wpjam_pull($data, ['comment', 'content']);

		if($contents){
			$data['comment_content']	= reset($contents);
		}

		if(empty($data['comment_content'])){
			return new WP_Error('comment_required', '内容不能为空');
		}

		$data['comment_content']	= trim(wp_strip_all_tags($data['comment_content']));

		return WPJAM_Comment::create($data);
	}

	public function reply($post_id, $id, $data){
		$comment	= get_comment($id);

		if(!$comment || $comment->comment_post_ID != $post_id){
			return new WP_Error('invalid_comment_id');
		}

		if(empty($data['reply_text'])){
			return new WP_Error('empty_reply', '回复的文本不能为空');
		}

		if(!empty($data['reply_id'])){
			return $this->update($post_id, $data['reply_id'], ['content'=>$data['reply_text']]);
		}else{
			if(!$comment->comment_approved){
				wpjam_set_comment_status($id, 'read');
			}

			return $this->add($post_id, wp_parse_args($data, ['content'=>$data['reply_text'], 'parent'=>$id]));
		}
	}

	public function update($post_id, $id, $data){
		$comment	= get_comment($id);

		if(!$comment || $comment->comment_post_ID != $post_id){
			return new WP_Error('invalid_comment_id');
		}

		$contents	= wpjam_pull($data, ['comment', 'content']);
		$data		= $contents ? array_merge($data, ['comment_content'=>reset($contents)]) : $data;

		if(isset($data['comment_content'])){
			if($data['comment_content']){
				$data['content']	= trim(wp_strip_all_tags($data['comment_content']));
			}else{
				return new WP_Error('comment_required', '内容不能为空');
			}
		}

		$result	= WPJAM_Comment::update($id, $data);

		return is_wp_error($result) ? $result : wpjam_get_comment_object($id);
	}

	public function query($args){
		if(!empty($args['post_id'])){
			$args	= wp_parse_args($args, ['order'=>$this->order, 'number'=>($this->paged ? $this->number : 0)]);
		}else{
			if(!wpjam_is_current_commenter()){
				return new WP_Error('access_denied');
			}
		}

		return wpjam_get_comment_query(array_merge($args, ['type'=>$this->name]));
	}

	public function filter_post_json($json, $post_id){
		if($this->plural && is_single($post_id)){
			if($this->name == 'comment' && $this->paged){
				$json['page_comments']	= true;
			}

			$json[$this->plural]	= $this->get_data($post_id, true);
		}

		return $json;
	}

	public function register_json($json){
		$post_type	= explode('.', $json)[0];

		if(!post_type_exists($post_type) || ($post_type != 'post' && !$this->object->get_sub($post_type))){
			return;
		}

		$name	= $post_type.'.'.$this->name;

		if($this->action){
			if($json == $name){
				return wpjam_register_json($json, [
					'post_type'	=> $post_type,
					'auth'		=> true,
					'status'	=> 1,
					'callback'	=> [$this, 'api_callback']
				]);
			}elseif($json == $post_type.'.un'.$this->name && $this->unaction !== false){
				return wpjam_register_json($json, [
					'post_type'	=> $post_type,
					'auth'		=> true,
					'status'	=> 0,
					'callback'	=> [$this, 'api_callback']
				]);
			}
		}else{
			if($json == $name){
				return wpjam_register_json($json, [
					'post_type'	=> $post_type,
					'auth'		=> true,
					'callback'	=> [$this, 'api_callback']
				]);
			}elseif($json == $name.'.get'){
				return wpjam_register_json($json, [
					'title'		=> $this->label.'详情',
					'callback'	=> [$this, 'api_callback'],
					'fields'	=> ['id'=>['required'=>true, 'type'=>'number']]
				]);
			}elseif($json == $name.'.update'){
				return wpjam_register_json($json, [
					'callback'	=> [$this, 'api_callback'],
					'fields'	=> ['id'=>['required'=>true, 'type'=>'number']]
				]);
			}
		}

		if($json == $name.'.list'){
			return wpjam_register_json($json, [
				'post_type'	=> $post_type,
				'callback'	=> [$this, 'api_callback']
			]);
		}

		if(str_starts_with($json, $name.'.')){
			foreach($this->object->get_available_features() as $feature){
				$feature->register_json($json, wpjam_remove_prefix($json, $name.'.'));
			}
		}
	}

	public function api_callback($args, $json){
		if(str_ends_with($json, '.get')){
			$comment	= wpjam_try(['WPJAM_Comment', 'validate'], $args['id'], $this->name);
			$json		= wpjam_get_comment($args['id']);
			$post_json	= wpjam_get_post($json['post_id']);
			$post_type	= $post_json['post_type'];

			return [
				$this->name	=> $json,
				$post_type	=> $post_json
			];
		}elseif(str_ends_with($json, '.list')){
			$post_type	= wpjam_pull($args, 'post_type') ?: 'post';

			if($post_type == 'post'){
				$post_type	= wpjam_get_parameter('post_type') ?: $post_type;
			}

			if(is_string($post_type) && strpos($post_type, ',') !== false){
				$post_type	= wp_parse_list($post_type);
			}

			$post_id	= wpjam_get_parameter('post_id');

			if(is_null($post_id) && !is_array($post_type) && $post_type != 'post'){
				$post_id	= wpjam_get_parameter($post_type.'_id');
			}

			$response	= [];
			$query_args	= [];
			$output		= wpjam_pull($args, 'output');

			if($post_id){
				$output	= $output ?: $this->plural;
				$object	= wpjam_try([$this, 'validate_by_post'], $post_id, $post_type);

				$response['page_title']	= $response['share_title'] = $this->label.'列表';
				$query_args['post_id']	= $post_id;
			}else{
				if(!$output){
					if($this->action){
						$output	= is_string($post_type) ? wpjam_get_post_type_setting($post_type, 'plural') : '';
						$output	= $output ?: 'posts';
					}else{
						$output	= $this->plural;
					}
				}

				$response['page_title']	= $response['share_title'] = '我的'.$this->label;

				$query_args['post_type']	= $post_type;

				if($this->post_meta){
					if(did_action('wpjam_account_loaded')){
						$query_args	= [
							'number'	=> $args['number'] ?? 20,
							'cursor'	=> (int)wpjam_get_parameter('cursor'),
							'post_type'	=> $post_type
						];

						$response[$output]			= WPJAM_Account_Activity::parse_posts($query_args, $next_cursor);
						$response['next_cursor']	= $next_cursor;

						return $response;
					}

					return new WP_Error($this->name.'_not_supported', '操作不支持');
				}
			}

			if(!$post_id || $this->paged){
				$query_args['cursor']	= (int)wpjam_get_parameter('cursor');

				if(!$query_args['cursor']){
					$paged	= (int)wpjam_get_parameter('paged');

					if($paged){
						$query_args['paged']	= $paged;
					}
				}
			}

			$query	= wpjam_try([$this, 'query'], $query_args);
			$type	= '';

			if(!$post_id){
				$type	= $this->action ? 'action' : 'with_post';

				if($this->action && $query->comments){
					$data	= wp_list_pluck($query->comments, 'comment_ID', 'comment_post_ID');
					$data	= array_diff_key($data, $this->cache('get'));

					if($data){
						$this->cache('set', $data);
					}
				}
			}

			$items	= wpjam_get_comments($query, $type);

			$response[$output]	= $items;

			if(!$post_id || $this->paged){
				if($query_args['cursor'] || !$paged){
					$response['next_cursor']	= $query->max_num_pages > 1 ? end($items)['timestamp'] : 0;
				}

				if(!$query_args['cursor']){
					$response['total']			= (int)$query->found_comments;
					$response['total_pages']	= (int)$query->max_num_pages;
					$response['current_page']	= (int)($query->query_vars['paged'] ?: 1);
				}
			}

			return $response;
		}else{
			if(str_ends_with($json, '.update')){
				$comment	= wpjam_try(['WPJAM_Comment', 'validate'], $args['id'], $this->name);
				$post_id	= $comment->comment_post_ID;
				$post_type	= $post_id ? get_post_type($post_id) : null;
			}else{
				$post_type	= wpjam_pull($args, 'post_type') ?: 'post';
				$post_id	= (int)wpjam_get_post_parameter('post_id', ['required'=>($post_type == 'post')]);

				if(!$post_id && $post_type != 'post'){
					$post_id	= (int)wpjam_get_post_parameter($post_type.'_id', ['required'=>true]);
				}else{
					$post_type	= null;
				}
			}

			$object			= wpjam_try([$this, 'validate_by_post'], $post_id, $post_type);
			$meta_fields	= $object->get_features('meta_fields');

			if($meta_fields){
				$args['meta']	= wpjam_try([wpjam_fields($meta_fields), 'validate']);
			}

			return $object->api_comment($post_id, $args);
		}
	}

	public function api_comment($post_id, $data=[]){
		$object	= wpjam_try([$this, 'add'], $post_id, array_merge($data, [
			'comment'	=> wpjam_get_post_parameter('comment', ['required'=>true, 'fallback'=>'text']),
			'parent'	=> (int)wpjam_get_post_parameter('parent', ['default'=>0, 'fallback'=>'reply_to'])
		]));

		return [$this->name=> $object->parse_for_json()];
	}

	public function get_menu_page(){
		if(!$this->plural){
			return;
		}

		foreach($this->object->get_subs() as $post_type => $sub){
			$pt_object	= wpjam_get_post_type_object($post_type);
			$parent		= $pt_object ? ($pt_object->show_ui ? $post_type.'s' : $pt_object->get_menu_slug()) : '';

			if($parent){
				$menu_page[]	= [
					'parent'		=> $parent,
					'menu_slug'		=> $post_type.'-'.$this->plural,
					'menu_title'	=> ($pt_object->show_ui ? $pt_object->title : '').$this->label,
					'function'		=> 'list',
					'list_table'	=> 'WPJAM_Comment',
					'post_type'		=> $post_type,
					'comment_type'	=> $this->name,
					'type_object'	=> $sub,
					'capability'	=> 'edit_posts'
				];
			}
		}

		return $menu_page ?? [];
	}

	public function query_items($post_id, $args){
		if(!$post_id){
			return [];
		}

		if($this->post_meta){
			return $this->get_data($post_id, true);
		}

		$query	= $this->query(array_merge($args, [
			'status'		=> 'all',
			'post_id'		=> $post_id,
			'hierarchical'	=> $this->name == 'comment' && get_option('thread_comments') ? 'threaded' : false
		]));

		return ['items'=>$query->comments, 'total'=>$query->found_comments];
	}

	public function render_item($item){
		if($this->post_meta){
			$item['author']	= '<img src="'.$item['avatar'].'" width="32">'.$item['nickname'];
		}else{
			$parent		= $item['comment_parent'];
			$pad		= $parent ? '<span class="author-pad">&emsp;</span>' : '';
			$width		= $parent ?  24 : 32;
			$content	= $item['comment_content'];
			$content	= $content && !is_serialized($content) ? $content : '';
			$ip_data	= wpjam_parse_ip($item['author_ip']);
			$location	= array_reduce(['country', 'region', 'city'], fn($location, $k)=> wpjam_join('-', [$location, $ip_data[$k]]), '');

			$item['id']			= $id = $item['comment_ID'];
			$item['class']		= wpjam_get_comment_status($id);
			$item['comment']	= $item['comment'] ?? '';
			$item['comment']	= $content.$item['comment'];
			$item['comment']	= $item['comment'] ? wpautop($item['comment']) : '';
			$item['author']		= $pad.'<img src="'.get_avatar_url(get_comment($id), 64).'" width="'.$width.'">'.get_comment_author($id);

			$item['date']		= $item['date'].'<br />'.$location.'（'.$item['author_ip'].'）';//.'<br />'.$item['agent'];
		}

		return $this->object->call_features('render_item', $item);
	}

	public function list_action($post_id, $action_key, ...$args){
		$data	= array_pop($args);
		$data	= array_merge($data, ['user_id'=>array_get($data, 'commenter_type') == 'admin' ? get_current_user_id() : 0]);

		if($action_key == 'reply'){
			$id		= array_shift($args);
			$object	= wpjam_try([$this, 'reply'], $post_id, $id, $data);

			return ['type'=>'add', 'after'=>$id, 'id'=>$object->id];
		}else{
			$object	= wpjam_try([$this, 'add'], $post_id, $data);

			return strcasecmp($this->order, 'asc') ? ['last'=>true, 'id'=>$object->id] : $object->id;
		}
	}

	public function get_actions($post_id){
		if($this->action){
			return [];
		}

		$show_if	= fn($v)=> ['key'=>'approved', 'value'=>$v]; 
		$actions	= [
			'add'		=> ['title'=>'新增',	'callback'=>fn($data)=> $this->list_action($post_id, 'add', $data)],
			'edit'		=> ['title'=>'编辑',	'callback'=>fn($id, $data)=> WPJAM_Comment::update($id, $data)],
			'reply'		=> ['title'=>'回复',	'callback'=>fn($id, $data)=> $this->list_action($post_id, 'reply', $id, $data)],
			'approve'	=> ['title'=>'批准',	'order'=>20,	'direct'=>true,	'show_if'=>$show_if(0)],
			'unapprove'	=> ['title'=>'驳回',	'order'=>20,	'direct'=>true,	'show_if'=>$show_if(1)],
			'spam'		=> ['title'=>'垃圾',	'order'=>2,		'direct'=>true,	'response'=>'delete',	'show_if'=>$show_if(0)],
			'delete'	=> ['title'=>'删除',	'order'=>1,		'direct'=>true, 'confirm'=>true, 'bulk'=>true],
		];

		if($this->name == 'comment'){
			$actions['set']	= [
				'title'				=> '设置',
				'overall'			=> true,
				'class'				=> 'button-primary',
				'value_callback'	=> ['WPJAM_Comment', 'get_setting'],
				'callback'			=> ['WPJAM_Comment', 'update_setting'],
				'capability'		=> is_multisite() ? 'manage_sites' : 'manage_options',
			];
		}

		return wpjam_merge($actions, $this->object->get_features('actions'));
	}

	public function get_fields($post_id, $action_key='', $id=0){
		$object	= $this->object;
		$fields	= [];

		if($action_key == 'set'){
			if($this->name == 'comment'){
				$prefix	= $object->post_type.'_comment';
				$fields	= [
					$prefix.'_length'	=> ['title'=>'评论字数',	'type'=>'fields',	'fields'=>[
						$prefix.'_minimum_length'	=> ['type'=>'number',	'class'=>'medium-text',	'placeholder'=>'最少的字数'],
						$prefix.'_length_sep'		=> ['type'=>'view',		'value'=>'-'],
						$prefix.'_maximum_length'	=> ['type'=>'number',	'class'=>'medium-text',	'placeholder'=>'最多的字数'],
					]],

					$prefix.'_disable_form_url'			=> ['title'=>'屏蔽网址', 'type'=>'checkbox',	'label'=>'屏蔽评论框网址输入框'],
					$prefix.'_blank_target_author_link'	=> ['title'=>'作者链接', 'type'=>'checkbox',	'label'=>'评论作者链接新窗口打开'],
				];

				return array_reduce($object->get_available_features(), fn($fields, $feature)=> array_merge($fields, $feature->get_fields($object, 'setting') ?: []), $fields);
			}
		}elseif(in_array($action_key, ['add', 'reply', 'edit'])){
			$fields	= [
				'date'		=> ['title'=>'时间',		'type'=>'datetime-local'],
				'content'	=> ['title'=>'内容',		'type'=>'textarea'],
				'type'		=> ['type'=>'hidden',	'value'=>$this->name],
			];

			if($this->name == 'comment' && in_array($action_key, ['add', 'reply'])){
				$show_if			= ['key'=>'commenter_type', 'value'=>'custom'];
				$commenter_fields	= ['commenter'=>['title'=>'身份',	'type'=>'fieldset',	'fields'=>[
					'commenter_type'	=> ['title'=>'类型',	'type'=>'select',	'options'=>[
						'admin'		=> '登录用户',
						'custom'	=> ['label'=>'自定义', 'fields'=>[
							'comment_author'	=> ['title'=>'昵称',	'type'=>'text',	'class'=>'',	'value_callback'=>fn()=>''],
							'avatarurl'			=> ['title'=>'头像',	'type'=>'img',	'item_type'=>'url',	'size'=>'100x100',	'name'=>'meta[avatarurl]']
						]]
					]]
				]]];

				if($action_key == 'add'){
					$fields	= $commenter_fields+$fields;
				}elseif($action_key == 'reply'){
					$reply_id	= wpjam_get_data_parameter('reply_id');
					$reply_text	= $reply_id ? get_comment_text($reply_id) : '';
					$comment	= get_comment($id);
					$view		= wpjam_tag('img', ['src'=>get_avatar_url($comment, 96), 'width'=>48])->after(get_comment_author($comment), 'strong')->wrap('div', ['column-author'])->after(wpautop(wp_strip_all_tags(get_comment_text($comment, []))));

					$fields		= ['view'=> ['type'=>'view','value'=>$view]]+($reply_id ? [] : $commenter_fields)+[
						'reply_id'		=> ['type'=>'hidden',	'value'=>$reply_id],
						'reply_text'	=> ['type'=>'textarea',	'value'=>$reply_text,	'title'=>'回复',	'required'=>true],
						'type'			=> ['type'=>'hidden',	'value'=>$this->name],
					];
				}
			}
		}elseif($action_key == ''){
			$fields['author']	= ['title'=>'用户',	'column'=>['sticky'=>true, 'style'=>'min-width:180px']];

			if(!$this->action || $this->content){
				$fields['comment']	= ['title'=>($this->label ?: '评论'), 'type'=>'view', 'column'=>['style'=>'min-width:540px']];
			}

			if(!$this->post_meta){
				$fields['date']	= ['title'=>'提交于',	'order'=>1, 'column'=>['style'=>'min-width:150px']];
			}
		}

		if(in_array($action_key, ['add', 'edit', ''])){
			$meta_fields	= $object->get_features('meta_fields');
			$meta_fields	= $action_key ? wpjam_map($meta_fields, fn($field, $k)=> array_merge($field, ['name'=>'meta['.$k.']'])) : $meta_fields;

			$fields	= array_merge($fields, $meta_fields);
		}

		return $object->call_features('filter_fields', $fields, $post_id, $action_key, $id);
	}
}

class WPJAM_Post_Action extends WPJAM_Post_Comment{
	public function update_acteds($post_ids){
		$cache		= $this->cache('get');
		$post_ids	= array_diff($post_ids, array_keys($cache));

		if($post_ids){
			$query	= $this->query([
				'post__in'	=> $post_ids,
				'type'		=> $this->name,
				'status'	=> 'any'
			]);

			$data	= array_fill_keys($post_ids, false);
			$data	= array_replace($data, wp_list_pluck($query->comments, 'comment_ID', 'comment_post_ID'));

			$this->cache('set', $data);
		}
	}

	public function is_acted($post_id){
		if(wpjam_is_current_commenter()){
			wpjam_load_pending('current_'.$this->is_acted, [$this, 'update_acteds']);

			$acted	= $this->cache('get', $post_id);

			if(is_null($acted)){
				foreach($this->get_data($post_id) as $item){
					if(wpjam_is_current_commenter($item)){
						$acted	= $item->comment_ID;

						break;
					}
				}

				$this->cache('set', $post_id, ($acted ?: false));
			}

			return $acted;
		}

		return 0;
	}

	public function act($post_id, $status=1, $data=[]){
		$current	= wpjam_is_current_commenter();

		if(!$current){
			return new WP_Error('access_denied');
		}

		if(is_array($status)){
			$data	= $status;
			$status	= wpjam_pull($data, 'status');
		}

		$acted	= $this->is_acted($post_id);

		$this->cache('del', $post_id);

		if($acted){
			return $status == 1 ? true : wp_delete_comment($acted, $force_delete=true);
		}else{
			return $status != 1 ? true : WPJAM_Comment::insert(array_merge($data, ['post_id'=>$post_id, 'type'=>$this->name]));
		}
	}

	public function filter_post_json($json, $post_id){
		if($this->is_acted){
			$json[$this->is_acted]	= (bool)$this->is_acted($post_id);
		}

		return $json;
	}

	public function api_comment($post_id, $data=[]){
		if($this->content){
			$data['comment']	= wpjam_get_post_parameter('comment', ['required'=>true]);
		}

		$result	= wpjam_try([$this, 'act'], $post_id, $data);

		return true;
	}

	public function get_attr($post_id){
		$acted	= $this->is_acted($post_id);
		$data	= ['post_id'=>$post_id, 'action'=>($acted ? 'un' : '').$this->name];
		$class	= ($acted ? str_replace('_', '-', $this->is_acted) : '');

		return [
			'label'	=> $this->label,
			'count'	=> $this->get_count($post_id),
			'data'	=> $data,
			'icon'	=> $acted ? $this->icon[0] : $this->icon[1],
			'class'	=> 'post-action post-'.$this->name.' '.$class,
		];
	}

	// 兼容
	public function action($post_id, $status=1, $data=[]){
		return $this->act($post_id, $status, $data);
	}

	public function is_did($post_id){
		trigger_error('id_did');
		return $this->is_acted($post_id);
	}
}

class WPJAM_Post_Like extends WPJAM_Post_Action{
	public function get_data($post_id, $parse=false){
		$data	= get_post_meta($post_id, $this->name.'_data', true) ?: [];

		if(isset($data['user_ids']) || isset($data['user_emails'])){
			$ids	= $data['user_ids'] ?? [];
			$new	= $data['user_emails'] ?? [];

			foreach($ids as $id){
				$user	= get_userdata($id);

				if($user){
					$new[$id]	= $user->display_name;
				}
			}

			update_post_meta($post_id, $this->name.'_data', $new, $data);

			$data	= $new;
		}

		if(!$parse){
			return $data;
		}

		$parsed	= $user_ids	= [];

		foreach($data as $key => $value){
			if(is_numeric($key)){
				$user_ids[]	= $key;
			}
		}

		if($user_ids){
			cache_users($user_ids);
		}

		foreach($data as $key => $value){
			if(is_numeric($key)){
				if(!get_userdata($key)){
					continue;
				}

				$user_id	= (int)$key;
			}else{
				$user_id	= 0;
			}

			$nickname	= $value;
			$avatar		= get_avatar_url($key, 200);
			$parsed[]	= compact('nickname', 'user_id', 'avatar');
		}

		return $parsed;
	}

	public function is_acted($post_id, $data=null){
		$current	= wpjam_is_current_commenter();

		if($current){
			$data	= $data ?? $this->get_data($post_id);

			foreach($data as $key => $value){
				if(wpjam_is_current_commenter($key)){
					return true;
				}
			}
		}

		return $this->cookie ? isset($_COOKIE[$this->is_acted.'_'.$post_id]) : false;
	}

	public function act($post_id, $status=1, $data=[]){
		$retry_times	= 10;

		if(is_array($status)){
			$data	= $status;
			$status	= wpjam_pull($data, 'status');
		}

		if($this->unaction === false && $status == 0){
			return new WP_Error('un'.$this->name.'_not_supported', '不支持取消'.$this->label);
		}

		do{
			$current	= wpjam_is_current_commenter();

			if($current){
				$new_data	= $prev_data = $this->get_data($post_id);

				if(((bool)$status) === $this->is_acted($post_id, $new_data)){
					return true;
				}

				if(is_numeric($current)){
					if($status == 1){
						$new_data[$current]	= get_userdata($current)->display_name;;
					}else{
						unset($new_data[$current]);
					}
				}else{
					if($status == 1){
						$commenter	= wp_get_current_commenter();
						$email		= $commenter['comment_author_email'];

						$new_data[$email]	= $commenter['comment_author'];
					}else{
						foreach((array)$current as $email){
							unset($new_data[$email]);
						}
					}
				}

				if($this->cookie && $status == 0){
					wpjam_clear_cookie($this->is_acted.'_'.$post_id);
				}

				$updated	= update_post_meta($post_id, $this->name.'_data', $new_data, $prev_data);
			}else{
				if(!$this->cookie){
					return new WP_Error('access_denied');
				}

				if((bool)$status == $this->is_acted($post_id)){
					return true;
				}

				$cookie	= $this->is_acted.'_'.$post_id;

				if($status == 1){
					wpjam_set_cookie($cookie, $post_id);
				}else{
					wpjam_clear_cookie($cookie);
				}

				$updated	= true;
			}

			if($updated && $this->plural){
				if($this->cookie){
					$count	= (int)get_post_meta($post_id, $this->plural, true);

					if($status == 1){
						$count	= $count + 1;
					}else{
						$count	= $count > 1 ? $count - 1 : 0;
					}
				}else{
					$count	= count($new_data);
				}

				update_post_meta($post_id, $this->plural, $count);
			}

			$retry_times --;
		}while(!$updated && $retry_times > 0);

		if($updated){
			if($status){
				do_action('wpjam_post_liked', $post_id, $data);
			}else{
				do_action('wpjam_post_unliked', $post_id, $data);
			}
		}

		return $updated;
	}
}