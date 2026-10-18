<?php
add_action('registered_post_type',	['WPJAM_Comment_Type', 'on_registered_post_type'], 99, 2);

add_action('parse_comment_query',	['WPJAM_Comments', 'on_parse_comment_query']);
add_action('pre_get_comments',		['WPJAM_Comments', 'on_pre_get_comments']);
add_action('comment_form_before',	['WPJAM_Comments', 'on_form_before']);

add_action('transition_comment_status',	['WPJAM_Comments', 'on_transition_comment_status'], 10, 3);

remove_action('check_comment_flood',	'check_comment_flood_db', 10, 4);

add_filter('is_protected_meta',			['WPJAM_Comments', 'filter_is_protected_meta'], 10, 3);
add_filter('get_avatar_comment_types',	['WPJAM_Comments', 'filter_get_avatar_types'], 10);
add_filter('wpjam_post_json',			['WPJAM_Comments', 'filter_post_json'], 1, 2);
add_filter('wpjam_related_post_json',	['WPJAM_Comments', 'filter_post_json'], 1, 2);
add_filter('the_posts',					['WPJAM_Comments', 'filter_posts'], 10, 2);
add_filter('wp_get_current_commenter',	['WPJAM_Comments', 'filter_current_commenter'], 10, 1);

add_filter('comments_clauses',			['WPJAM_Comments', 'filter_clauses'], 10, 2);
add_filter('wp_is_comment_flood',		['WPJAM_Comments', 'filter_is_flood'], 10, 5);
add_filter('pre_comment_content',		['WPJAM_Comments', 'filter_pre_content'], 1);
add_filter('pre_comment_approved',		['WPJAM_Comments', 'filter_pre_approved'], 99, 2);

add_filter('pre_wp_update_comment_count_now',	['WPJAM_Comments', 'filter_pre_update_count'], 10, 3);

add_filter('comment_form_default_fields',	['WPJAM_Comments', 'filter_form_default_fields']);
add_filter('get_comment_author_link',		['WPJAM_Comments', 'filter_get_author_link'], 10, 3);

add_filter('pre_option_comment_whitelist',	'__return_zero');

wpjam_register_meta_type('comment', [
	'order'			=> 19,
	'object_model'	=> 'WPJAM_Comment',
	'object_column'	=> 'comment_content',
	'object_key'	=> 'comment_ID'
]);

wpjam_register_ajax('post-action',	[
	'nopriv'		=> true,
	'nonce_keys'	=> ['post_id'],
	'callback'		=> fn($data) => wpjam_post_act($data['post_id'], $data['action'], 'attr'),
	'fields'		=> [
		'post_id'	=> ['required'=>true,	'type'=>'number'],
		'action'	=> ['required'=>true,	'sanitize_callback'=>'sanitize_key'],
	]
]);

//wpjam_style('remixicon');

wpjam_script('comment-action', [
	'for'		=> '',
	'src'		=> wpjam_url(dirname(__DIR__).'/static/ajax-action.js'),
	'deps'		=> ['wpjam-ajax'],
	'enqueue_if'=> fn() => !current_theme_supports('script', 'comment-action')
]);

if(is_admin()){
	wpjam_add_admin_load([
		'base'	=> ['edit', 'edit-comments'],
		'model'	=> 'WPJAM_Comments'
	]);
}

function wpjam_comment_get_setting($name, $default=null){
	return WPJAM_Comment::get_setting($name, $default);
}

//  wpjam_register_comment_type($name, $args);
//  wpjam_register_comment_type($name, $post_type, $args);
function wpjam_register_comment_type($name, ...$args){
	return WPJAM_Comment_Type::create($name, ...$args);
}

function wpjam_get_comment_type_object($name, $sub=null){
	$name	= is_numeric($name) ? get_comment($name) : $name;

	if(!$name){
		return null;
	}

	if(is_object($name)){
		if(is_a($name, 'WP_Comment_Query')){
			$vars	= $name->query_vars;

			if($vars['post_id'] && empty($vars['parent__in'])){
				// if((is_singular() && $vars['post_id'] == get_queried_object_id()) || is_admin()){
					$name	= $vars['type'] ?: 'comment';
					$sub	= get_post_type($vars['post_id']);
				// }
			}
		}elseif(is_a($name, 'WP_Comment')){
			$sub	= $name->post_type;
			$name	= $name->comment_type ?: 'comment';
		}elseif(is_a($name, 'WPJAM_Comment')){
			$sub	= $name->post_type;
			$name	= $name->type;
		}

		if(empty($sub)){
			return null;
		}
	}

	return WPJAM_Comment_Type::get(wpjam_join(':', $name, $sub));
}

function wpjam_comment_supports($comment, $feature, $key=''){
	$object	= wpjam_get_comment_type_object($comment);

	return $object ? $object->supports($feature, $key) : null; 
}

function wpjam_register_comment_feature($name, $args=[]){
	return WPJAM_Comment_Feature::register($name, $args);
}

function wpjam_get_comment_object($id){
	return WPJAM_Comment::get_instance($id);
}

function wpjam_comment($id, $wp_error=false){
	return WPJAM_Comment::get_instance($id, $wp_error);
}

function wpjam_get_comment($id, $args=[]){
	$object	= wpjam_get_comment_object($id);

	return $object ? $object->parse_for_json($args) : [];
}

function wpjam_get_comment_status($id){
	$object	= wpjam_get_comment_object($id);

	return $object ? $object->get_status() : false;
}

function wpjam_set_comment_status($id, $status, $args=[]){
	$object	= wpjam_get_comment_object($id);

	return $object ? $object->set_status($status, $args) : new WP_Error('invalid_comment_id');
}

function wpjam_is_current_commenter($comment=null){
	return WPJAM_Comment::is_by_current($comment);
}

function wpjam_get_comment_query($args){
	return WPJAM_Comments::query($args);
}

function wpjam_get_comments($args, $type=''){
	return WPJAM_Comments::parse($args, $type);
}

function wpjam_validate_post_comment($post_id, $type){
	$object	= wpjam_get_comment_type_object($type);

	return $object ? $object->validate_by_post($post_id) : new WP_Error('invalid_comment_type');
}

function wpjam_get_post_comments($post_id, $type, $parse=true){
	$object	= wpjam_validate_post_comment($post_id, $type);

	return is_wp_error($object) ? [] : $object->get_data($post_id, $parse);
}

function wpjam_get_post_action_button($post_id, $action='like', $title=true){
	$object	= wpjam_validate_post_comment($post_id, $action);
	$attr	= is_wp_error($object) ? '' : $object->get_attr($post_id);

	if($attr && !is_wp_error($attr)){
		$icon	= '<i class="'.$attr['icon'].'"></i>';
		$title	= $title === true ? $icon.' '.$attr['label'] : ($title ?: $icon);
		$title	.= ' <span class="post-action-count">'.$attr['count'].'</span>';
		$data	= wpjam_get_ajax_data_attr('post-action', $attr['data']);
		
		return wpjam_wrap($title, 'a', ['class'=>$attr['class'], 'data'=>$data]);
	}
}

function wpjam_add_post_comment($post_id, $data){
	$type	= $data['type'] = empty($data['type']) ? 'comment': $data['type'];
	$object	= wpjam_validate_post_comment($post_id, $type);

	return is_wp_error($object) ? $object : $object->add($post_id, $data);
}

function wpjam_post_act($post_id, $action='fav', $output=''){
	if(is_array($action)){
		$data	= $action;
		$action	= array_pull($data, 'action');
	}else{
		$data	= [];
	}

	$type	= wpjam_remove_prefix($action, 'un');
	$status	= $type == $action;
	$object	= wpjam_validate_post_comment($post_id, $type);
	$result	= is_wp_error($object) ? $object : $object->act($post_id, $status, $data);

	if(is_wp_error($result)){
		return $result;
	}

	if($output == 'object'){
		return $object;
	}elseif($output == 'attr'){
		return $object->get_attr($post_id);
	}else{
		return $result;
	}
}

function wpjam_is_post_acted($post_id, $action='fav', $wp_error=true){
	$object	= wpjam_validate_post_comment($post_id, $action);

	return is_wp_error($object) ? false : $object->is_acted($post_id);
}

// compact
if(!function_exists('get_comment_parent')){
	function get_comment_parent($comment_id){
		$comment	= get_comment($comment_id);

		return $comment ? $comment->comment_parent : null;
	}
}

function wpjam_post_action($post_id, $action='fav'){
	return wpjam_post_act($post_id, $action);
}

function wpjam_did_post_action($post_id, $action='fav'){
	return wpjam_is_post_acted($post_id, $action);
}

