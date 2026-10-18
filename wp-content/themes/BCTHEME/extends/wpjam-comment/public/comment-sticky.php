<?php
class WPJAM_Comment_Sticky{
	public static function added($object, $added){
		if($added == 1){
			add_filter('the_comments',			[self::class, 'filter_comments'], 10, 2);
			add_filter('get_comment_author',	[self::class, 'filter_comment_author'], 10, 3);
		}
	}

	public static function get_fields($type){
		if($type == 'setting'){
			return ['sticky'=>['title'=>'评论置顶', 'type'=>'checkbox', 'value'=>1, 'label'=>'开启评论置顶功能']];
		}
	}

	public static function get_actions(){
		return [
			'stick'		=> [
				'title'		=> '置顶',
				'callback'	=> fn($id) => wpjam_comment($id)->stick(),
				'show_if'	=> fn($id) => !wpjam_comment($id)->is_sticky() && !get_comment_parent($id) && wp_get_comment_status($id) == 'approved',
				'direct'	=> true,
				'confirm'	=> true
			],
			'unstick'	=> [
				'title'		=> '取消置顶',
				'callback'	=> fn($id) => wpjam_comment($id)->unstick(),
				'show_if'	=> fn($id) => wpjam_comment($id)->is_sticky() && !get_comment_parent($id),
				'direct'	=> true,
				'confirm'	=> true
			]
		];
	}

	public static function filter_comments($comments, $query){
		if(wpjam_comment_supports($query, 'sticky') 
			&& $query->query_vars['offset'] == 0 
			&& $query->query_vars['paged'] == 1 
			&& (int)get_query_var('cpage') <= 1
		){
			$orderby	= $query->query_vars['orderby'];
			$order		= $query->query_vars['order'];
			$post_id	= $query->query_vars['post_id'];
			$stickies	= wpjam_get_sticky_comments($post_id);

			if($stickies && is_array($stickies)){
				$stickies	= array_reverse($stickies);
				$comments	= array_combine(array_column($comments, 'comment_ID'), $comments);

				foreach($stickies as $i => $sticky_id){
					$sticky	= array_pull($comments, $sticky_id);

					if(!is_null($sticky)){
						unset($stickies[$i]);
					}

					if($orderby == 'comment_date_gmt' && $order	== 'ASC' && get_option('comment_order') === 'desc'){
						$comments	= $comments+[$sticky_id=>$sticky];
					}else{
						$comments	= [$sticky_id=>$sticky]+$comments;
					}
				}

				if(!empty($stickies)){
					$stickies	= get_comments([
						'comment__in'	=> $stickies,
						'orderby'		=> 'comment__in',
						'type'			=> 'comment'
					]);

					foreach($stickies as $sticky){
						$comments[$sticky->comment_ID]	= $sticky;
					}
				}

				return array_values(array_filter($comments));
			}
		}

		return $comments;
	}

	public static function filter_comment_author($author, $comment_id, $comment){
		if(wpjam_comment_supports($comment, 'sticky')){
			return (wpjam_comment($comment_id)->is_sticky() ? '<i class="ri-pushpin-2-fill"></i> ' : '').$author;
		}

		return $author;
	}

	public static function filter_comment_json($json, $comment){
		if(wpjam_comment_supports($comment, 'sticky') && $json['parent'] == 0){
			$post_id	= $json['post_id'];
			$post_type	= get_post_type($post_id);

			if(is_singular($post_type) && $post_id == get_queried_object_id()){
				$json['is_sticky']	= wpjam_comment($comment)->is_sticky();
			}
		}

		return $json;
	}
}

function wpjam_get_sticky_comments($post_id){
	return array_filter(get_post_meta($post_id, 'sticky_comments', true) ?: []);
}

function wpjam_update_sticky_comments($post_id, $data){
	if($data){
		return update_post_meta($post_id, 'sticky_comments', array_values(array_unique($data)));
	}else{
		return delete_post_meta($post_id, 'sticky_comments');
	}
}

WPJAM_Comment::add_dynamic_method('is_sticky', function(){
	if($this->parent == 0){
		$stickies	= wpjam_get_sticky_comments($this->post_id);

		return $stickies && in_array($this->id, $stickies);
	}

	return false;
});

WPJAM_Comment::add_dynamic_method('stick', function(){
	if($this->parent != 0){
		return new WP_Error('sticky_disabled', '只有第一级评论才支持置顶。');
	}

	if(!$this->is_sticky()){
		$stickies	= wpjam_get_sticky_comments($this->post_id);

		if(count($stickies) >= 5){
			return new WP_Error('sticky_comments_over_quato', '最多5个置顶评论。');
		}

		wpjam_update_sticky_comments($this->post_id, array_merge([$this->id], $stickies));

		do_action('wpjam_stick_comment', $this->id);
	}

	return true;
});

WPJAM_Comment::add_dynamic_method('unstick', function(){
	if($this->is_sticky()){
		$stickies	= wpjam_get_sticky_comments($this->post_id);

		wpjam_update_sticky_comments($this->post_id, array_diff($stickies, [$this->id]));

		do_action('wpjam_unstick_comment', $this->id);
	}

	return true;
});