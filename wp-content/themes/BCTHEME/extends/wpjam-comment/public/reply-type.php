<?php
class WPJAM_Comment_Reply{
	public static function get_fields($type){
		if($type == 'setting'){
			return ['reply_type'=>['title'=>'回复方式', 'type'=>'select', 'value'=>'all', 'options'=>[
				'all'			=> '任何人都可回复',
				'admin_reply'	=> '仅管理员可回复'
			]]];
		}
	}

	public static function get_children($comment, $args, $reply_type){
		$children	= $comment->get_children();

		if($children){
			$children	= array_filter($children, fn($child) => $child->comment_approved == '1' || wpjam_is_current_commenter($child));

			foreach($children as $child){
				if($reply_type == 'admin_reply'){
					if($child->user_id && user_can($child->user_id, 'manage_options')){
						$parsed[]	= wpjam_get_comment($child, $args);
					}
				}else{
					$parsed[]	= wpjam_get_comment($child, $args);
					$parsed		= array_merge($parsed, self::get_children($child, $args, $reply_type));
				}
			}
		}

		return $parsed ?? [];
	}

	public static function render_item($item){
		$comment	= get_comment($item['id']);
		$reply_type	= wpjam_comment_supports($comment, 'reply_type');
		
		if($reply_type == 'admin_reply'){
			$child	= wpjam_find($comment->get_children(['format'=>'flat']), fn($child) => $child->user_id && user_can($child->user_id, 'manage_options'));

			if($child){
				$item['comment']	.= '<div class="admin_reply">'.wpautop('管理员回复（[row_action name="reply" title="修改" id="'.$item['id'].'" data="reply_id='.$child->comment_ID.'"]）：'.$child->comment_content).'</div>'; 

				unset($item['row_actions']['reply']);
			} 
		}elseif($reply_type == 'all'){
			static $top_comment_id;

			if($item['comment_parent']){
				$parent	= $item['comment_parent'];

				if(isset($top_comment_id) && $top_comment_id != $parent){
					$parent_comment		= get_comment($parent);
					$item['comment']	= ($parent_comment ? '<a class="reply_to" data-parent="'.$parent.'" href="#comment-'.$parent.'">@'.$parent_comment->comment_author.'</a> ':'').$item['comment'];
				}
			}else{
				$top_comment_id	= $item['id'];
			}
		}

		return $item;
	}

	public static function filter_comments($comments, $query){
		$reply_type	= wpjam_comment_supports($query, 'reply_type');

		if($reply_type == 'all'){
			foreach(array_values($comments) as $comment){
				$items[]	= $comment;
				$items		= array_merge($items, $comment->get_children(['format'=>'flat']));
			}

			$query->query_vars['hierarchical']	= false;

			return $items ?? [];
		}elseif($reply_type == 'admin_reply'){
			wp_add_inline_style('list-tables', "\n".'td div.admin_reply{background: #ffe; padding:1px 4px;}');
		}

		return $comments;
	}

	public static function filter_comment_json($json, $comment, $args){
		$reply_type	= wpjam_comment_supports($comment, 'reply_type');

		if(!empty($args['post_id']) && $reply_type){
			if($json['parent']){
				if($reply_type == 'all'){
					if(empty($args['top_comment_id']) || $args['top_comment_id'] != $json['parent']){
						$parent_comment		= get_comment($json['parent']);
						$json['reply_to']	= $parent_comment ? $parent_comment->comment_author : '';
						$json['reply_text']	= $parent_comment ? $parent_comment->comment_content : '';
					}
				}else{
					$json['author']	= [];
				}
			}else{
				$json['children']	= self::get_children($comment, array_merge($args, ['top_comment_id'=>$json['id']]), $reply_type);
			}	
		}

		return $json;
	}

	public static function filter_comment_text($text, $comment){
		if(is_singular() && $comment && $comment->comment_post_ID == get_queried_object_id()){
			// static $top_comment_id;

			if($comment->comment_parent){
				// if(isset($top_comment_id) && $comment->comment_parent != $top_comment_id){
					$parent	= get_comment($comment->comment_parent);
					$text	= '<a href="'.esc_url(get_comment_link($parent)).'">@'.$parent->comment_author.'</a> '.$text;
				// }
			}else{
				// $top_comment_id	= $comment->comment_ID;
			}
		}

		return $text;
	}

	public static function on_pre_get_comments($query){
		$reply_type	= wpjam_comment_supports($query, 'reply_type');

		if($reply_type){
			$query->query_vars['hierarchical']	= 'threaded';

			if($reply_type == 'admin_reply'){
				add_filter('comment_reply_link',	'__return_empty_string');
			}

			add_filter('comment_text',	[self::class, 'filter_comment_text'], 10, 2);
		}
	}

	public static function add_hooks(){
		add_action('pre_get_comments',	[self::class, 'on_pre_get_comments']);

		if(is_admin()){
			add_filter('the_comments',	[self::class, 'filter_comments'], 11, 2);
		}

		// add_filter('pre_option_thread_comments_depth', fn() => 2);
	}
}