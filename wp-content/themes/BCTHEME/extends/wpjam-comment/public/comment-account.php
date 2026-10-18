<?php
class WPJAM_Comment_Account{
	public static function added($object, $added){
		$name		= $object->name;
		$label		= $object->label;
		$post_type	= $object->post_type;
		$title		= wpjam_get_post_type_setting($post_type, 'title');
		$args		= ['data_type'=>'post_type', 'post_type'=>$post_type, 'page_key'=>$post_type, 'unique'=>$object->action];

		if(post_type_supports($post_type, 'activity')){
			wpjam_register_account_activity_type($post_type.'_'.$name, $args + ['title'=>$label.'了'.$title]);

			if($name == 'comment'){
				wpjam_register_account_activity_type($post_type.'_reply', $args + ['title'=>'回复了'.$title.$label]);
			}

			if($object->supports('digg')){
				wpjam_register_account_activity_type($post_type.'_'.$name.'_digg', $args+['title'=>'点赞了'.$label, 'unique'=>true]);
			}
		}

		if(post_type_supports($post_type, 'message')){
			wpjam_register_account_message_type($post_type.'_'.$name, $args + ['title'=>$label.'了您的'.$title]);

			if($name == 'comment'){
				wpjam_register_account_message_type($post_type.'_reply', $args + ['title'=>'回复您的'.$title.$label]);
			}
		}

		if(did_action('wpjam_account_credit_loaded') && post_type_supports($post_type, 'credit')){
			wpjam_register_account_credit_type($post_type.'_'.$name, $args + ['title'=>$label.$title]);

			if($name == 'comment'){
				wpjam_register_account_credit_type($post_type.'_reply', $args + ['title'=>'回复'.$title.$label]);
			}

			if($object->acted){
				wpjam_register_account_credit_type($post_type.'_'.$object->acted, $args + ['title'=>$title.'被'.$label]);
			}
		}
	}

	public static function filter_fields($fields, $post_id, $action_key, $id){
		if(isset($fields['commenter'])){
			$options	= &$fields['commenter']['fields']['commenter_type']['options'];
			$options	+= ['account_id'=>['label'=>'前台账号', 'fields'=>['account_id'=> [
				'title'				=> '账号',
				'type'				=> 'text',
				'class'				=> 'all-options',
				'required'			=> true,
				'data_type'			=> 'model',
				'query_args'		=> ['model'=>'WPJAM_Account', 'blog_id'=>get_current_blog_id(), 'label_key'=>'nickname'],
				'value_callback'	=> '__return_empty_string'
			]]]];
		}

		return $fields;
	}

	public static function filter_current_commenter($commenter){
		if(wpjam_get_data_parameter('commenter_type') == 'account_id'){
			$account_id	= wpjam_get_data_parameter('account_id');
			$account	= wpjam_get_account_object($account_id);

			if($account){
				$commenter['user_emails']			= $account->get_user_emails();
				$commenter['comment_author_email']	= $account->get_user_email();
				$commenter['comment_author']		= $account->display_name;
			}
		}

		return $commenter;
	}

	public static function on_comment_post($comment){
		$comment	= get_comment($comment);

		if(!$comment->comment_approved){
			return;
		}

		$object		= wpjam_get_comment_type_object($comment);
		$account_id	= $object ? wpjam_get_comment_account_id($comment) : null;

		if(!$account_id){
			return;
		}

		$comment_id		= $comment->comment_ID;
		$comment_type	= $comment->comment_type ?: 'comment';
		$post_id		= $comment->comment_post_ID;
		$post_type		= $comment->post_type;
		$post_account	= wpjam_get_post_account_id($post_id);
		$parent_account	= 0;
		$query_args		= [$post_type.'_id' => $post_id];

		if($comment_type == 'comment' && $comment->comment_parent){
			$comment_parent	= $comment->comment_parent;
			$parent_account = wpjam_get_comment_account_id($comment_parent);

			if($parent_account && $parent_account != $account_id){
				wpjam_create_account_message($post_type.'_reply',	[
					'sender'			=> $account_id,
					'receiver'			=> $parent_account,
					'related_account'	=> $parent_account,
					'query_id'			=> $post_id,
					'related_id'		=> $comment_id,
					'thumbnail'			=> wpjam_get_post_thumbnail_url($post_id, 'full'),
					'content'			=> wp_strip_all_tags($comment->comment_content),
					'query_args'		=> $query_args
				]);

				wpjam_create_account_activity($post_type.'_reply',	[
					'account_id'		=> $account_id,
					'query_id'			=> $post_id,
					'related_id'		=> $comment_id,
					'related_account'	=> $parent_account,
					'content'			=> wp_strip_all_tags($comment->comment_content),
					'query_args'		=> $query_args
				]);

				if(did_action('wpjam_account_credit_loaded')){
					wpjam_create_account_credit($account_id, $post_type.'_reply', [
						'related_id'		=> $comment_id,
						'related_account'	=> $parent_account,
					]);
				}

				return; // ?? 是否要给 post_account 发送
			}
		}

		if($post_account != $account_id && (!$parent_account || $parent_account != $post_account)){
			if($post_account){
				wpjam_create_account_message($post_type.'_'.$comment_type,	[
					'sender'			=> $account_id,
					'receiver'			=> $post_account,
					'related_id'		=> $comment_id,
					'query_id'			=> $post_id,
					'related_account'	=> $parent_account,
					'thumbnail'			=> wpjam_get_post_thumbnail_url($post_id, 'full'),
					'content'			=> wp_strip_all_tags($comment->comment_content),
					'query_args'		=> $query_args
				]);

				if(did_action('wpjam_account_credit_loaded')){
					wpjam_create_account_credit($post_account, $post_type.'_'.$object->acted, [
						'related_id'		=> $comment_id,
						'related_account'	=> $parent_account,
					]);
				}
			}

			wpjam_create_account_activity($post_type.'_'.$comment_type, [
				'account_id'	=> $account_id,
				'related_id'	=> $comment_id,
				'query_id'		=> $post_id,
				'content'		=> wp_strip_all_tags($comment->comment_content),
				'query_args'	=> $query_args
			]);

			if(did_action('wpjam_account_credit_loaded')){
				wpjam_create_account_credit($account_id, $post_type.'_'.$comment_type, [
					'related_id'		=> $comment_id,
					'related_account'	=> $parent_account,
				]);
			}
		}
	}

	public static function on_transition_comment_status($new_status, $old_status, $comment) {
		if($old_status != $new_status && $new_status == 'approved'){
			self::on_comment_post($comment);
		}
	}

	public static function on_post_liked($post_id){
		$account_id		= wpjam_get_current_account_id();
		$post_type		= get_post_type($post_id);
		$post_account	= wpjam_get_post_account_id($post_id);

		if($account_id && $post_type && $account_id != $post_account){
			if(doing_action('wpjam_post_unliked')){
				wpjam_delete_account_activity($post_type.'_like',	[
					'account_id'	=> $account_id,
					'related_id'	=> $post_id,
				]);
			}else{
				$query_args	= [$post_type.'_id' => $post_id];

				if($post_account){
					wpjam_create_account_message($post_type.'_like',	[
						'sender'		=> $account_id,
						'receiver'		=> $post_account,
						'query_id'		=> $post_id,
						'related_id'	=> $post_id,
						'thumbnail'		=> wpjam_get_post_thumbnail_url($post_id, 'full'),
						'content'		=> '',
						'query_args'	=> $query_args
					]);

					if(did_action('wpjam_account_credit_loaded')) {
						wpjam_create_account_credit($post_account, $post_type.'_liked', [
							'related_id'		=> $post_id,
							'related_account'	=> $account_id,
						]);
					}
				}

				wpjam_create_account_activity($post_type.'_like',	[
					'account_id'	=> $account_id,
					'query_id'		=> $post_id,
					'related_id'	=> $post_id,
					'content'		=> '',
					'query_args'	=> $query_args
				]);

				if(did_action('wpjam_account_credit_loaded')){
					wpjam_create_account_credit($account_id, $post_type.'_like', [
						'related_id'		=> $post_id,
						'related_account'	=> $account_id,
					]);
				}
			}
		}
	}

	public static function on_comment_digged($comment_id){
		$account_id		= wpjam_get_current_account_id();
		$comment		= get_comment($comment_id);
		$comment_type	= $comment->comment_type ?: 'comment';
		$post_type		= $comment->post_type;
		$parent_account	= wpjam_get_comment_account_id($comment);

		if($account_id && $post_type && $account_id != $parent_account){
			if(doing_action('wpjam_comment_undigged')){
				wpjam_delete_account_activity($post_type.'_'.$comment_type.'_digg',	[
					'account_id'	=> $account_id,
					'related_id'	=> $comment_id,
				]);
			}else{
				$post_id	= $comment->comment_post_ID;
				$query_args	= [$post_type.'_id' => $post_id];

				wpjam_create_account_activity($post_type.'_'.$comment_type.'_digg',	[
					'account_id'		=> $account_id,
					'query_id'			=> $post_id,
					'related_id'		=> $comment_id,
					'related_account'	=> $parent_account,
					'content'			=> '',
					'query_args'		=> $query_args
				]);
			}
		}
	}

	public static function add_hooks(){
		add_action('comment_post',				[self::class, 'on_comment_post']);
		add_action('transition_comment_status',	[self::class, 'on_transition_comment_status'], 10, 3);
		add_action('wpjam_post_liked',			[self::class, 'on_post_liked']);
		add_action('wpjam_post_unliked',		[self::class, 'on_post_liked']);
		add_action('wpjam_comment_digged',		[self::class, 'on_comment_digged']);
		add_action('wpjam_comment_undigged',	[self::class, 'on_comment_digged']);

		if(wp_doing_ajax()){
			add_filter('wp_get_current_commenter',	[self::class, 'filter_current_commenter']);
		}
	}
}

function wpjam_get_comment_account_id($comment){
	return wpjam_get_email_account_id($comment->comment_author_email);
}