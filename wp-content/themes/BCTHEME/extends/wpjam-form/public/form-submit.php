<?php
class WPJAM_Form_Submit extends WPJAM_Post_Comment{
	public function api_comment($post_id, $args=[]){
		$args['status']	= wpjam_get_post_parameter('status', ['default'=>'approve']);
		$args['data']	= wpjam_get_post_parameter('data', ['required'=>true]);

		$form	= wpjam_get_post_object($post_id);
		$result	= wpjam_try([$form, 'is_submittable']);
		$submit	= wpjam_try([$form, 'submit'], $args, $options);

		return [
			'submission'	=> $submit->parse_for_json(), 
			'count'			=> count($form->get_submissions()),
			'times'			=> count($form->get_submissions('user'))
		];
	}

	public function render_item($item){
		$item	= parent::render_item($item);
		$object	= wpjam_get_comment_object($item['id']);
		$fields	= $item['content'] ? $object->get_unserialized() : [];

		foreach($fields as $field){
			if($field['type'] == 'img'){
				$value	= $field['value'] ? '<a class="thickbox" href="'.wpjam_get_thumbnail($field['value'], 600).'"><img src="'.wpjam_get_thumbnail($field['value'], 120, 120).'" width="60" /></a>' : '';
			}else{
				$value	= is_array($field['value']) ? implode("&emsp;", $field['value']) : $field['value'];
				$value	= wp_strip_all_tags($value);	
			}
			
			$item[md5($field['name'])]	= $value;
		}

		if(str_contains($item['author_email'], 'anonymous.anonymous')){
			$item['author']	= '不知名网友';
		}

		return $item;
	}

	public function get_actions($post_id){
		return wpjam_except(parent::get_actions($post_id), ['add', 'edit', 'reply'])+['export'=>[
			'title'		=> '导出',
			'export'	=> true,
			'direct'	=> true,
			'overall'	=> true,
			'class'		=> 'button-primary',
			'callback'	=> fn()=> wpjam_get_post_object($post_id)->export()
		],];
	}

	public function get_fields($post_id, $action_key='', $id=0){
		$fields	= parent::get_fields($post_id, $action_key, $id);
		
		if($action_key == ''){
			$fields	= wpjam_except($fields, 'comment');
			$form	= wpjam_get_post_object($post_id);

			if($form){
				foreach($form->get_items() as $field){
					$key	= md5($field['name']);
					$column	= $field['type'] == 'textarea' ? ['style'=>'min-width:200px;'] : ['nowrap'=>true];

					$fields[$key]	= $field+['title'=>$field['name'], 'column'=>$column];
				}
			}
		}

		return $fields;
	}
}

class WPJAM_Form_Submit_Source{
	public static function get_fields($type){
		if($type == 'meta'){
			return [
				'source_type'	=> ['title'=>'来源',	'type'=>'select',	'options'=>[],	'column'=>['nowrap'=>true],	'order'=>0],
				'source_data'	=> ['title'=>'',	'type'=>'text']
			];
		}
	}

	public static function render_item($item){
		$source_type	= get_comment_meta($item['id'], 'source_type', true);

		if($source_type){	//[''=>'默认', 'home'=>'首页', 'post'=>'文章', 'module'=>'页面']
			$source_data	= get_comment_meta($item['id'], 'source_data', true);

			if($source_type == 'home'){
				$item['source_type']	= '来自首页';
			}elseif($source_type == 'post'){
				$title	= $source_data ? esc_html(get_the_title($source_data)) : '';
				$label	= wpjam_get_post_type_setting(get_post_type($source_data), 'title');

				$item['source_type']	= '来自'.$label.'：'."<br />".$title;
			}elseif($source_type == 'module'){
				$page_title	= $source_data ? get_term($source_data, 'module_page')->name : '';

				$item['source_type']	= '来自页面：'."<br />".$page_title;
			}else{
				$item['source_type']	= '';
			}
		}

		return $item;
	}
}