<?php
/*
Plugin Name: WPJAM 置顶文章
Plugin URI: http://blog.wpjam.com/project/wpjam-sticky-posts/
Description: 全局置顶文章排序，分类置顶文章。
Version: 6.3
Author: Denis
Author URI: http://blog.wpjam.com/
Update URI: http://blog.wpjam.com/project/wpjam-sticky-posts/
*/
class WPJAM_Sticky{
	private $value;
	private $type;

	public function __construct($value, $type){
		$this->value	= $value;
		$this->type		= $type;
	}

	public function __get($key){
		if($this->type == 'post_type'){
			if($key == 'key'){
				return self::get_option_name($this->value);
			}elseif($key == 'title'){
				if(is_object_in_taxonomy($this->value, 'category')){
					return '文章';
				}else{
					return wpjam_get_post_type_setting($this->value, 'title');
				}
			}
		}else{
			if($key == 'key'){
				return self::get_meta_key($this->value);
			}elseif($key == 'title'){
				if($this->value == 'category'){
					return '文章';
				}else{
					return wpjam_get_post_type_setting(current(get_taxonomy($this->value)->object_type), 'title');
				}
			}
		}
	}

	public function __call($method, $args){
		$post_type	= $this->value;
		$post_id	= (int)array_shift($args);

		if($this->key == 'sticky_posts'){
			if($method == 'stick'){
				stick_post($post_id);	// 先放在末尾，下面代码把当前文章前置

				$stickies	= get_option('sticky_posts') ?: [];

				array_unshift($stickies, $post_id);

				return update_option('sticky_posts', array_values(array_unique(array_map('intval', $stickies))));
			}elseif($method == 'unstick'){
				unstick_post($post_id);
			}elseif($method == 'is_sticky'){
				return is_sticky($post_id);
			}
		}else{
			$stickies	= $this->get_stickies();

			if($stickies && is_array($stickies)){
				$stickies	= array_unique(array_map('intval', $stickies));
			}

			if($method == 'stick'){
				if(count($stickies) >= 5){
					return new WP_Error('stickies_over_limit', '最多只能置顶5个');
				}

				array_unshift($stickies, $post_id);

				$this->update_stickies($stickies);

				do_action($post_type.'_stuck', $post_id);
			}elseif($method == 'unstick'){
				if(in_array($post_id, $stickies, true)){
					array_splice($stickies, array_search($post_id, $stickies, true), 1);

					$this->update_stickies($stickies);

					do_action($post_type.'_unstuck', $post_id);
				}
			}elseif($method == 'is_sticky'){
				return $stickies ? in_array($post_id, $stickies, true) : false;
			}
		}

		return true;
	}

	public function get_data($id=null){
		return [$this->key => $this->get_stickies($id)];
	}

	public function get_stickies($id=null){
		if($this->type == 'post_type'){
			return wpjam_get_option($this->key) ?: [];
		}elseif($this->type == 'taxonomy'){
			$term		= get_term($id);
			$stickies	= get_term_meta($term->term_id, $this->key, true) ?: [];

			if(wpjam_basic_get_setting('sticky_anywhere', 1)){
				$stickies	= array_merge($stickies, (get_option($this->key) ?: []));
			} 

			$stickies = array_unique($stickies);

			if($stickies){
				_prime_post_caches($stickies, true, true);

				return array_filter($stickies, fn($post_id) => is_object_in_term($post_id, $term->taxonomy, $term->term_id));
			}
		}

		return $stickies;
	}

	public function update_stickies($stickies, $id=null){
		if(isset($stickies[$this->key])){
			$stickies	= $stickies[$this->key];
		}

		$stickies	= array_values($stickies);

		if($this->type == 'post_type'){
			return wpjam_update_option($this->key, $stickies);
		}elseif($this->type == 'taxonomy'){
			$term	= get_term($id);

			return update_term_meta($term->term_id, $this->key, $stickies);
		}
	}

	public function get_fields($id=null){
		if($this->type == 'post_type'){
			if(is_object_in_taxonomy($this->value, 'category')){
				$field	= ['post_type'=>get_taxonomy('category')->object_type];
			}else{
				$field	= ['post_type'=>$this->value,	'max_items'=>5];
			}

			return [$this->key => array_merge($field, ['type'=>'mu-text',	'data_type'=>'post_type'])];
		}else{
			$query_args	= ['tax_query'=>[['taxonomy'=>$this->value, 'field'=>'term_id', 'terms'=>[$id]]]];
			$title		= '置顶'.$this->title;

			return [
				'view'		=> ['title'=>'置顶说明',	'type'=>'view',		'value'=>'分类'.$title.'在分类里优先级高于全局'.$title.'。'],
				$this->key	=> ['title'=>$title,	'type'=>'mu-text',	'data_type'=>'post_type',	'post_type'=>get_taxonomy($this->value)->object_type,	'query_args'=>$query_args,	'max_items'=>5]
			];
		}
	}

	public static function get_option_name($post_type){
		if($post_type == 'post'){
			return 'sticky_posts';
		}elseif($post_type && wpjam_basic_get_setting('sticky_post_type', 1)){
			$pt_object	= get_post_type_object($post_type);

			if($pt_object && !$pt_object->hierarchical && $pt_object->has_archive){
				if(is_object_in_taxonomy($post_type, 'category')){
					return 'sticky_posts';
				}else{
					return 'sticky_'.$post_type.'s';
				}
			}
		}
	}

	public static function get_meta_key($taxonomy){
		if($taxonomy && wpjam_basic_get_setting('sticky_taxonomy', 1)){
			if($taxonomy == 'category'){
				return 'sticky_posts';
			}

			$tax_object	= get_taxonomy($taxonomy);

			if($tax_object && $tax_object->hierarchical && count($tax_object->object_type) == 1){
				$post_type	= current($tax_object->object_type);

				return self::get_option_name($post_type);
			}
		}
	}

	public static function get_instance($value, $type='post_type'){
		if($type == 'post_type'){
			$value	= is_numeric($value) ? get_post_type($value) : $value;
			$has	= (bool)self::get_option_name($value);
		}else{
			$value	= is_numeric($value) ? get_term_taxonomy($value) : $value;
			$has	= (bool)self::get_meta_key($value);
		}

		if($has){
			return wpjam_get_instance('sticky', $value.':'.$type, fn()=>new self($value, $type));
		}
	}
}

class WPJAM_Sticky_Setting{
	public static function get_sections(){
		$fields	= [
			'post_list_sticky'	=> ['type'=>'checkbox',	'description'=>'后台列表操作'],
			'sticky_order'		=> ['type'=>'checkbox',	'description'=>'置顶拖动排序',			'value'=>1],
			'sticky_post_type'	=> ['type'=>'checkbox',	'description'=>'文章类型置顶',			'value'=>1],
			'sticky_taxonomy'	=> ['type'=>'checkbox',	'description'=>'分类置顶',			'value'=>1],
			'sticky_anywhere'	=> ['type'=>'checkbox',	'description'=>'合并全局到分类置顶',	'value'=>1],
		];

		if(!get_post_types(['exclude_from_search'=>false, 'has_archive'=>true, 'hierarchical'=>false, '_builtin'=>false])){
			unset($fields['sticky_post_type']);
		}

		return ['posts'=>['fields'=>['sticky'=>['title'=>'文章置顶', 'type'=>'fields', 'sep'=>'&emsp;', 'fields'=>$fields]]]];
	}

	public static function get_view(){
		$post_type	= get_screen_option('post_type');
		$stickies	= wpjam_get_stickies($post_type);
		$stickies	= $stickies ? WPJAM_Post::get_by_ids($stickies) : [];
		$stickies	= wp_list_filter($stickies, ['post_type'=>$post_type]);

		if($stickies){
			return ['filter'=>['show_sticky'=>1], 'label'=>'置顶',	'count'=>count($stickies)];
		}
	}

	public static function filter_views($views){
		if(isset($views['sticky'])){
			$sticky	= array_pull($views, 'sticky');
			$split	= 1 + array_search((isset($views['publish']) ? 'publish' : 'all'), array_keys($views), true);
			$views	= array_merge(array_slice($views, 0, $split), ['sticky'=>$sticky], array_slice($views, $split));
		}

		return $views;
	}

	public static function on_pre_get_posts($wp_query){
		if($wp_query->is_main_query()){
			$data_type	= get_screen_option('data_type');
			$post_type	= get_screen_option('post_type');

			if($data_type != 'post_type' || !$post_type){
				return;
			}

			$stickies	= wpjam_get_stickies($post_type);

			$wp_query->set('post__in', (array)$stickies);
			$wp_query->set('orderby', 'post__in');
		}
	}

	public static function registers(){
		if(!wpjam_basic_get_setting('post_list_sticky')){
			return;
		}

		$post_type	= get_screen_option('post_type');
		$object		= $post_type ? wpjam_sticky($post_type, 'post_type') : null;

		if(!$object){
			return;
		}

		add_filter('views_'.get_current_screen()->id,	[self::class, 'filter_views'], 99);

		if(wpjam_get_data_parameter('show_sticky')){
			add_action('pre_get_posts',	[self::class, 'on_pre_get_posts']);
		}

		wpjam_register_list_table_view('sticky', [
			'data_type'	=> 'post_type',
			'post_type'	=> $post_type,
			'callback'	=> [self::class, 'get_view'],
		]);

		if(wpjam_basic_get_setting('sticky_order', 1)){
			wpjam_register_list_table_action('set_sticky', [
				'title'			=> '置顶设置',
				'page_title'	=> '置顶设置',
				'submit_text'	=> '设置',
				'data_type'		=> 'post_type',
				'post_type'		=> $post_type,
				'fields'		=> [$object, 'get_fields'],
				'data_callback'	=> [$object, 'get_data'],
				'callback'		=> [$object, 'update_stickies'],
				'overall'		=> true,
				'width'			=> 480,
			]);
		}

		wpjam_register_list_table_action('stick', [
			'title'			=> '置顶',
			'page_title'	=> '置顶',
			'data_type'		=> 'post_type',
			'post_type'		=> $post_type,
			'show_if'		=> fn($id)=> !is_sticky($id) && get_post_status($id) == 'publish',
			'callback'		=> [$object, 'stick'],
			'direct'		=> true,
			'confirm'		=> true
		]);

		wpjam_register_list_table_action('unstick', [
			'title'			=> '取消置顶',
			'page_title'	=> '取消置顶',
			'data_type'		=> 'post_type',
			'post_type'		=> $post_type,
			'show_if'		=> fn($id)=> is_sticky($id),
			'callback'		=> [$object, 'unstick'],
			'direct'		=> true,
			'confirm'		=> true
		]);
	}

	public static function builtin_page_load($screen){
		if($screen->base == 'edit-tags'){
			$object	= wpjam_sticky($screen->taxonomy, 'taxonomy');

			if($object){
				wpjam_register_term_option('sticky_posts', [
					'title'			=> $object->title.'置顶',
					'page_title'	=> $object->title.'置顶',
					'fields'		=> [$object, 'get_fields'],
					'list_table'	=> 'only'
				]);
			}
		}elseif($screen->base == 'edit'){
			self::registers();

			if($screen->post_type != 'post'){
				if(is_object_in_taxonomy($screen->post_type, 'category')){
					if(!wp_doing_ajax()){
						$scripts	= <<<'EOT'
						jQuery(function($){
							$('body').on('quick_edit', '#the-list', function(event, id){
								let edit_row	= $('#edit-'+id);

								if($('input[name="sticky"]', edit_row).length == 0){
									$('.inline-edit-status', edit_row).after('<label class="alignleft"><input type="checkbox" name="sticky" value="sticky"><span class="checkbox-title">置顶这篇文章</span></label>');
									if($('#inline_'+id+' div.sticky').text() == 'sticky'){
										$('input[name="sticky"]', edit_row).prop('checked', true);
									}
								}
							});
						});
						EOT;
						wp_add_inline_script('jquery', $scripts);
					}
				
				}
			}
		}
	}

	public static function plugin_page_load($plugin_page){
		self::registers();
	}

	public static function get_admin_load(){
		return [
			[
				'type'		=> 'plugin_page',
				'model'		=> 'WPJAM_Sticky_Setting'
			],
			[
				'type'		=> 'builtin_page',
				'base'		=> ['edit', 'edit-tags'],
				'model'		=> 'WPJAM_Sticky_Setting'
			]
		];
	}

	public static function filter_posts_results($posts, $wp_query){
		if(!$wp_query->get('ignore_sticky_posts')
			&& $wp_query->get('paged') <= 1 
			&& !$wp_query->is_search 
			&& (($wp_query->is_main_query() && !$wp_query->is_admin)
				|| $wp_query->get('module_query')
			)
		){
			$sticky_posts	= [];

			if($wp_query->is_category || $wp_query->is_tax){
				$term	= $wp_query->get_queried_object();
				$object = $term ? wpjam_sticky($term->taxonomy, 'taxonomy') :null;

				if($object){
					$sticky_posts	= $object->get_stickies($term);
				}
			}elseif($wp_query->is_post_type_archive){
				$post_type	= $wp_query->get('post_type');
				$object		= wpjam_sticky($post_type);

				if($object){
					$sticky_posts	= $object->get_stickies();
				}
			}elseif($wp_query->is_home || ($wp_query->is_admin && $wp_query->get('module_query'))){
				$sticky_posts	= get_option('sticky_posts');

				if($sticky_posts){
					if(wpjam_basic_get_setting('sticky_order', 1)){
						add_filter('pre_option_sticky_posts', [self::class, 'filter_pre_option_sticky_posts']);
					}else{
						$wp_query->set('sticky_posts', $sticky_posts);

						$sticky_posts	= [];
					}
				}
			}

			$sticky_posts	= apply_filters('wpjam_sticky_posts', $sticky_posts, $wp_query);

			if($sticky_posts && !empty($wp_query->get('post__not_in'))){
				$sticky_posts	= array_diff($sticky_posts, $wp_query->get('post__not_in'));
			}

			if($sticky_posts && is_array($sticky_posts)){
				$post_type	= $wp_query->get('post_type') ?: 'post';
				$stickies	= WPJAM_Post::get_by_ids($sticky_posts);
				$stickies	= wpjam_filter($stickies, ['post_type'=>$post_type, 'post_status'=>'publish']);

				if($stickies){
					$stickies		= array_values($stickies);
					$sticky_posts	= array_column($stickies, 'ID');

					$wp_query->set('sticky_posts', $sticky_posts);

					foreach($posts as $post){
						if(!in_array($post->ID, $sticky_posts, true)){
							$stickies[]	= $post;
						}
					}

					$posts	= $stickies;
				}
			}
		}

		return $posts;
	}

	public static function filter_pre_option_sticky_posts($pre){
		remove_filter('pre_option_sticky_posts', __METHOD__);	// 只执行一次

		return [];
	}

	public static function filter_is_sticky($is_sticky, $post_id){
		$post_type	= get_post_type($post_id);

		if($post_type && !is_object_in_taxonomy($post_type, 'category')){
			$object	= wpjam_sticky($post_type);

			return $object ? $object->is_sticky($post_id) : $is_sticky;
		}

		return $is_sticky;
	}

	public static function filter_posts_json($posts_json, $wp_query, $output){
		$sticky_posts	= $wp_query->get('sticky_posts');

		if($sticky_posts){
			foreach($posts_json[$output] as &$post_json){
				if(in_array($post_json['id'], $sticky_posts, true)){
					$post_json['is_sticky']	= true;
				}
			}
		}

		return $posts_json;
	}

	public static function add_hooks(){
		add_filter('posts_results',		[self::class, 'filter_posts_results'], 999, 2);
		add_filter('is_sticky',			[self::class, 'filter_is_sticky'], 10, 2);
		add_filter('wpjam_posts_json',	[self::class, 'filter_posts_json'], 10, 3);
	}
}

function wpjam_sticky($value, $type='post_type'){
	return WPJAM_Sticky::get_instance($value, $type);
}

function wpjam_stick_post($post_id){
	$object	= wpjam_sticky($post_id);

	return $object ? $object->stick($post_id) : null;
}

function wpjam_unstick_post($post_id){
	$object	= wpjam_sticky($post_id);

	return $object ? $object->unstick($post_id) : null;
}

function wpjam_get_stickies($value, $type='post_type'){
	$object	= wpjam_sticky($value, $type);

	return $object ? $object->get_stickies() : [];
}

add_action('plugins_loaded', function(){
	if(wp_installing() || !did_action('wpjam_loaded')){
		return;
	}

	wpjam_add_option_section('wpjam-basic', ['model'=>'WPJAM_Sticky_Setting']);
});
