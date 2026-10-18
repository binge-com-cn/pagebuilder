<?php
class WPJAM_Form_Module extends WPJAM_Module{
	public function parse_setting($args){
		$setting	= parent::parse_setting($args);
		$fields		= $this->get_fields('setting');

		foreach($fields['display']['fields'] as $sub_key => $sub_field){
			$setting[$sub_key]	= (bool)($setting[$sub_key] ?? false);
		}

		return $setting;
	}

	public function parse_content($args){
		$content	= parent::parse_content($args);
		$form_id	= (int)$content['form_id'];
		$object		= $form_id ? wpjam_get_post_object($form_id) : null;

		return $object ? $object->parse_for_json($args) : null;
	}

	public function render_setting(){
		$rendered	= parent::render_setting();
		$fields		= $this->get_fields('setting');
		$setting	= $this->setting;
		$display	= [];

		foreach($fields['display']['fields'] as $sub_key => $sub_field){
			if(!empty($setting[$sub_key])){
				$display[]	= $sub_field['label'];
			}
		}

		$rendered['display']	= [$fields['display']['title'], $display ?: '无'];

		return $rendered;
	}

	public function render_content(){
		$content	= parent::render_content();
		$form_id	= $content['form_id'];

		return $form_id ? get_the_title($form_id) : '';	
	}

	public static function get_fields($action_key='', $args=[]){
		if($action_key == 'setting'){
			$display	= ['title'=>'标题', 'excerpt'=>'摘要', 'thumbnail'=>'头图'];
			$fields		= wpjam_array($display, fn($k, $v) => ['form_'.$k.'_display', ['type'=>'checkbox', 'label'=>$v]]);

			return ['display'=>['title'=>'显示', 'type'=>'fields', 'sep'=>'&emsp;', 'fields'=>$fields]];
		}elseif($action_key == 'create' || $action_key == 'edit'){
			return ['form_id'=>array_merge(wpjam_get_post_id_field('form'), ['title'=>'表单', 'name'=>'content[form_id]', 'required'])];
		}
	}
}

wpjam_register_module_type('form', [
	'title'			=> '表单',
	'dashicon'		=> 'forms',
	'model'			=> 'WPJAM_Form_Module',
	'order'			=> 20,
	'max_modules'	=> 1
]);