<?php
class WPJAM_Comment_Rating{
	public static function is_supported($object){
		$post_type	= is_object($object) ? $object->post_type : $object;

		return post_type_supports($post_type, 'rating');
	}

	public static function added($object, $added){
		if($added == 1){
			add_action('wp_update_comment_count', fn($post_id, $new, $old) => $new != $old ? wpjam_update_post_rating($post_id) : null, 10, 3);

			add_action('wpjam_post_json', fn($post_json, $post_id) => $post_json + wpjam_get_post_rating($post_id, 'array'), 10, 2);
		}
	}

	public static function get_fields($type){
		if($type == 'meta'){
			return ['rating' => [
				'title'				=> '评分',
				'type'				=> 'number',
				'step'				=> 0.5,
				'min'				=> 0.5,
				'max'				=> 5,
				'value'				=> 5,
				'show_admin_column'	=> true,
				'column_style'		=> 'min-width: 56px;',
				'column_callback'	=> [self::class, 'column_callback']
			]];
		}elseif($type == 'setting'){
			return ['rating' => [
				'title'	=> '评论评分',
				'type'	=> 'checkbox',
				'label'	=> '用户发布评论的时候支持插入1-5评分，需主题支持'
			]];
		}
	}

	public static function column_callback($id){
		$rating	= get_comment_meta($id, 'rating', true);

		if($rating && $rating > 0 && $rating <= 5){
			$result	= str_repeat('<span class="dashicons comment-rating dashicons-star-filled"></span>', (int)$rating);

			if($rating - (int)$rating == 0.5){
				$result	.= '<span class="dashicons comment-rating dashicons-star-half"></span>';
			}

			return $result;
		}

		return '';
	}

	public static function filter_comment_json($json, $comment){
		if(wpjam_comment_supports($comment, 'rating')){
			$json['rating']	= (float)get_comment_meta($comment->comment_ID, 'rating', true);
		}

		return $json;
	}

	public static function on_update_comment_count($post_id, $new, $old){
		wpjam_update_post_rating($post_id);
	}
}

function wpjam_get_post_rating($post_id, $output=''){
	$object	= wpjam_get_post_object($post_id);

	if($object && post_type_supports($object->post_type, 'rating')){
		if(is_single($object->id) && !$object->meta_exists('rating')){
			wpjam_update_post_rating($post_id);	
		}

		$value	= (float)$object->rating ?: 5;
	}else{
		$value	= 0;
	}

	return $output == 'array' ? ($value ? ['rating'=>$value] : []) : $value;
}

function wpjam_update_post_rating($post_id){
	$object	= wpjam_get_post_object($post_id);

	if($object && post_type_supports($object->post_type, 'rating')){
		$comments	= get_comments(['post_id'=>$object->id, 'status'=>'approve', 'order'=>'ASC', 'meta_key'=>'rating']);
		$ratings	= array_map(fn($comment) => get_comment_meta($comment->comment_ID, 'rating', true), $comments);
		$ratings	= array_filter($ratings);

		return $object->meta_input('rating', ($ratings ? round(array_sum($ratings)/count($ratings), 2) : 5));
	}		
}
