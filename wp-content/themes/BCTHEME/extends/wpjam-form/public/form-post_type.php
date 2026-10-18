<?php
class WPJAM_Form extends WPJAM_Post{
	use WPJAM_Items_Trait;

	private $submissions;

	public function __get($key){
		return $key == 'fields' ? $this->get_items() : parent::__get($key);
	}

	public function get_submissions($type='', $parsed=false){
		if($type == 'user'){
			foreach($this->get_submissions() as $submission){
				if(wpjam_is_current_commenter($submission)){
					if($parsed){
						$object	= wpjam_get_comment_object($submission);
						$data[]	= $object->parse_for_json();
					}else{
						$data[]	= $submission;
					}
				}
			}

			return $data ?? [];
		}else{
			return $this->submissions ??= wpjam_get_post_comments($this->id, 'submit', false);
		}
	}

	public function get_status($output=''){
		$status	= 1;

		if($this->start_time && $this->start_time >= time()){
			$status	= 0;
		}elseif($this->end_time && $this->end_time <= time()){
			$status	= 2;
		}else{
			if($this->per_user && count($this->get_submissions('user')) >= $this->per_user){
				$status	= 4;
			}elseif($this->max && count($this->get_submissions()) >= $this->max){
				$status	= 3;
			}
		}

		if(in_array($output, ['array', 'name'])){
			$label	= [0=> '未开始', 1=> '进行中', 2=> '已结束', 3=> '已完成', 4=> '已提交'][$status];

			return $output == 'array' ? [$status, $label] : $label;
		}

		return $status;
	}

	public function get_items(){
		return $this->parse_fields();
	}

	public function update_items($items){
		return $this->save(['content'=>maybe_serialize(array_values($items))]);
	}

	protected function validate_item($item=null, $key=null, $action=''){
		if($action == 'add' && count($this->get_items()) >= 20){
			return new WP_Error('quota_exceeded', ['字段个数']);
		}

		return true;
	}

	protected function after_delete_item($key){
		if(!$this->get_items()){
			$this->unpublish();
		}
	}

	public function parse_fields($options=[], $wp_error=false){
		$fields	= $this->get_unserialized();

		foreach($fields as &$field){
			$field['required'] ??= (bool)($field['required'] ?? false);

			if(in_array($field['type'], ['select', 'radio', 'checkbox'])){
				if(current_theme_supports('form') && $options && $this->variable && !empty($field['variable'])){
					$field['options']	= $options[$field['name']] ?? [];
				}

				$field['options']	= !empty($field['options']) ? array_values(array_filter($field['options'], fn($v)=> $v || is_numeric($v))) : [];

				if($wp_error && !$field['options']){
					return new WP_Error('empty_options', '「'.$field['name'].'」选项未设置');
				}

				$field['custom_input']	= !empty($field['custom']) ? ['type'=>'text', 'lable'=>'其他', 'placeholder'=>'请输入其他选项'] : null;
			}else{
				unset($field['options']);
			}

			if(!is_admin()){
				unset($field['custom']);
			}
		}

		return $fields;
	}

	public function render_fields($options=[]){
		$i		= 0;
		$fields	= $this->parse_fields($options, true);

		foreach($fields as $field){
			$i++;
			
			$name	= 'field_'.$i;
			$type	= $field['type'];
			$type	= $type == 'time' ? 'datetime-local' : $type;
			$value	= $field['value'] ?? '';
			$args	= [
				'type'			=> $type,
				'value'			=> $value,
				'name'			=> $name,
				'id'			=> $name,
				'placeholder'	=> $field['label'] ?? '',
				'required'		=> !empty($field['required']) ? 'required' : ''
			];

			if($type == 'hidden'){
				echo wpjam_tag('input', $args);	
			}else{ ?>

			<div class="field field-text-field col-sm-12 <?php echo $args['required']; ?>">
				<div class="form-group">
					<div class="field-label-container" onclick="">
						<label class="field-label" for="<?php echo $name; ?>"><?php echo $field['name']; ?></label>
					</div>
					<div class="field-content">
						<?php if(in_array($type, ['select', 'radio', 'checkbox', 'img']) && !empty($field['label'])){ ?><div class="field-description clearfix collapse-details">
							<p><?php echo $field['label']; ?></p>
						</div><?php } ?>

						<?php if(in_array($type, ['text', 'number', 'url', 'email', 'date', 'datetime-local'])){
							echo wpjam_tag('input', $args);
						}elseif($type == 'textarea'){
							echo wpjam_tag('textarea', wpjam_except($args, ['value']), $value);
						}elseif($type == 'select'){ ?>

						<div class="dropdown-wrapper">
							<select class="field-transformed" name="<?php echo $name ?>">
								<option value="">请选择</option>

								<?php foreach($field['options'] as $item){
									echo '<option value="'.$item.'" '.selected($item, $value, false).'>'.$item.'</option>';
								} ?>

							</select>
						</div>

						<?php }elseif($type == 'radio'){ ?>

						<div class="choices">
							<?php foreach($field['options'] as $item){ ?>
							<label class="radio inline">
								<div class="choice-inner">
									<div class="radio-button-wrapper">
										<input type="radio" <?php checked($item, $value); ?> value="<?php echo $item;?>" name="<?php echo $name; ?>" class="field-transformed">
									</div>
									<div class="choice-description"><?php echo $item;?> <span class="quota"></span></div>
								</div>
							</label>
							<?php } ?>
							
							<!-- <div class="other-choice-area inline">
								<label class="choice-inner inline">
								<div class="radio-button-wrapper">
									<input class="other_choice field-transformed" type="radio" value="" name="1">
								</div>
								<div class="choice-description">
									其他 <span class="quota"></span>
								</div>
								</label><span class="other-choice-input-container"><input class="other-choice-input gd-input-medium gd-input-thin fixed-width-control" type="text" name="" id=""></span>
							</div> -->
						</div>

						<?php }elseif($type == 'checkbox'){ ?>

						<div class="field-content">
							<div class="choices">
								<?php foreach($field['options'] as $item){ $checked	= ($value && is_array($value) && in_array($item, $value)) ? 'checked="checked"' : '';?>

								<label class="checkbox inline">
									<div class="choice-inner">
										<div class="check-box-wrapper">
											<input type="checkbox" <?php echo $checked; ?> value="<?php echo $item;?>" name="<?php echo $name; ?>[]" class="field-transformed">
										</div>
										<div class="choice-description">
											<?php echo $item;?> <span class="quota"></span>
										</div>
									</div>
								</label>
								<?php } ?>
								<!-- <div class="other-choice-area inline">
									<label class="choice-inner inline">
									<div class="check-box-wrapper">
										<input class="other_choice field-transformed" type="checkbox" value="" name="">
									</div>
									<div class="choice-description">
										其他 <span class="quota"></span>
									</div>
									</label><span class="other-choice-input-container"><input class="other-choice-input gd-input-medium gd-input-thin fixed-width-control" type="text" name="" id=""></span>
								</div> -->
							</div>
						</div>
						
						<?php }elseif($type == 'img'){ ?>

						<div class="gd-input-container">
							<input class="input-with-icon enhanced-input" accept="image/*" type="file" name="<?php echo $name; ?>">
						</div>

						<?php } ?>
						
					</div>
				</div>
			</div>
			
			<?php }
		}
	}

	public function render($args=[]){
		$title	= wpjam_pull($args, 'title');
		$data	= wpjam_get_ajax_data_attr('form-submit', array_merge($args, ['form_id'=>$this->id]));

		?>
		<form class="wpjam-form" enctype="multipart/form-data" method="post" <?php echo $data; ?>>
			<?php echo $title ? '<h3>'.$title.'</h3>' : ''; ?>
			<?php echo $this->excerpt ? '<p>'.$this->excerpt.'</p>' : ''; ?>

			<?php $this->render_fields(); ?> 
			
			<button type="submit"><?php esc_html_e('Submit'); ?></button>
		</form>
		<?php
	}

	public function submit($args=[], $options=[]){
		if(current_theme_supports('form') && !$options){
			$meta		= $args['meta'] ?? [];
			$source		= array_get($meta, 'source_type') == 'post' ? array_get($meta, 'source_data') : 0;
			$options	= $source ? get_post_meta($source, 'form_options', true) : [];
		}
			
		$fields	= $this->parse_fields($options, true);

		if(is_wp_error($fields)){
			return $fields;
		}

		if(isset($args['data'])){
			$values	= array_column($args['data'], 'value', 'name');
		}else{
			$values	= [];
			$email	= '';
		}

		$i	= 0;

		foreach($fields as &$field){
			$i++;

			$field['title']	= $field['key']	= $name = $field['name'];

			if(!empty($field['options'])){
				$field['options']	= array_combine($field['options'], $field['options']);
			}

			if($field['type'] == 'img'){
				$field['item_type']	= 'url';

				if(!isset($args['data'])){
					$values[$name] = !empty($_FILES['field_'.$i]) ? wpjam_upload('field_'.$i)['url'] : null;
				}
			}else{
				if(!isset($args['data'])){
					$values[$name] = $_POST['field_'.$i] ?? null;

					if($values[$name] && $field['type'] == 'email'){
						$email	??= $values[$name];
					}
				}
			}

			$field['value']	= $values[$name] ?? null;
		}

		$result	= wpjam_catch([wpjam_fields($fields), 'validate'], $values);

		if(is_wp_error($result)){
			return $result;
		}

		if(!isset($args['data'])){
			add_filter('wp_get_current_commenter', fn($commenter)=> $this->filter_current_commenter($commenter, $email), 999);
		}

		$args		= array_merge($args, ['comment'=>maybe_serialize($fields)]);
		$object		= wpjam_get_comment_type_object('submit:form');
		$comment	=!empty($args['id']) ? $object->update($this->id, $args['id'], $args) : $object->add($this->id, $args);

		if(!is_wp_error($comment)){
			$email	= $this->mail ?: self::get_setting('form_mail');

			if($email){
				$subject	= '【'.$this->title.'】-'.get_option('blogname');
				$message	= wpjam_map($comment->get_unserialized(), fn($field)=> $field['name'] . ': ' . $field['value']);
				$message	= '<p>表单有新的提交，提交的内容：</p>'.implode('<br />', $message).'<p>此邮件由 : <a href="'.esc_url(home_url('/')).'" target="_blank">'.get_option('blogname').'</a> 系统发出，请勿直接回复。</p>';

				$mailed		= wp_mail($email, $subject, $message, ['Content-Type: text/html; charset=UTF-8']);

				if(!$mailed){

				}
			}
		}

		return $comment;
	}

	public function is_publishable(){
		return $this->get_items() ? true : new WP_Error('empty_fields', '未设置字段，不能发布');
	}

	public function is_submittable(){
		$result	= is_publishable();
		$status	= $this->get_status('array');

		if(is_wp_error($result)){
			return $result;
		}

		if($status[0] != 1){
			return new WP_Error('invalid_status', $status[1].'，不能提交');
		}

		return true;
	}

	public function export(){
		$names	= array_column($this->get_items(), 'name');
		$data[]	= array_merge(['提交者'], $names, ['时间']);

		foreach(wpjam_get_post_comments($this->id, 'submit', true) as $submission){
			$item		= [$submission['author']['nickname']];
			$content	= array_column($submission['content'], 'value', 'name');

			foreach($names as $name){
				$value	= wpjam_pull($content, $name, '');
				$item[]	= is_array($value) ? implode("\n", $value) : $value;
			}

			$item[]	= wpjam_date('Y-m-d H:i:s', $submission['timestamp']);

			if($content){
				foreach($content as $value){
					$item[]	= is_array($value) ? implode("\n", $value) : $value;
				}
			}

			$data[]	= $item;
		}

		wpjam_export('form-'.$this->id.'.csv', $data);
	}

	public function filter_current_commenter($commenter, $email){
		if(empty($commenter['comment_author'])){
			$commenter['comment_author']	= $email ? explode('@', $email)[0] : 'anonymous-'.time();
		}

		if(empty($commenter['comment_author_email'])){
			$commenter['comment_author_email']	= $email ? $email : 'anonymous-'.time().'@anonymous.anonymous';
		}

		return $commenter;
	}

	public function parse_for_json($args=[]){
		$args	= wp_parse_args($args, [
			'title_display'		=> true,
			'excerpt_display'	=> true,
			'thumbnail_display'	=> true
		]);

		$json	= [];

		if($args['title_display']){
			$json['title']		= $this->title;
		}

		if($args['excerpt_display']){
			$json['excerpt']	= $this->excerpt;
		}

		$thumbnail_id	= $this->_thumbnail_id;

		if($args['thumbnail_display']){
			$query = wpjam_get_image_size($thumbnail_id);

			if($query){
				$ratio	= $query['width'] / 750;
				
				$json['thumbnail_width']	= 750;
				$json['thumbnail_height']	= (int)($query['height'] / $ratio);
			}

			$json['thumbnail']	= $thumbnail_id ? wpjam_get_post_thumbnail_url($this->id, '1200x0') : '';
		}

		$share_image	= $thumbnail_id ? wpjam_get_post_thumbnail_url($this->id, [500,400]) : '';
		$fields			= $this->get_items();
		$user_submits	= $this->get_submissions('user', true);

		if($user_submits){
			$user_submit	= end($user_submits);
			$user_submit	= array_column($user_submit['content'], 'value', 'name');

			foreach($fields as &$field){
				$name	= $field['name'];

				if(isset($user_submit[$name])){
					$field['value']	= $user_submit[$name];
				}
			}
		}

		$json	= array_merge($json, [
			'page_title'	=> $this->title,
			'share_image'	=> $share_image,
			'id'			=> $this->id,
			'status'		=> $this->status,
			'fields'		=> $fields,
			'form_status'	=> $this->get_status(),
			'vertical'		=> (int)$this->vertical,
			'start_time'	=> (int)$this->start_time,
			'end_time'		=> (int)$this->end_time,
			'max'			=> (int)$this->max,
			'per_user'		=> (int)$this->per_user,
			'count'			=> count($this->get_submissions()),
			'times'			=> count($user_submits),
			'submissions'	=> $user_submits
		]);

		return apply_filters('wpjam_form_json', $json, $this->id);
	}

	public static function get_current_post_type(){
		return 'form';
	}

	protected static function validate_data($data, $id=0){
		if(isset($data['title']) && trim($data['title']) == ''){
			return new WP_Error('empty_title', '标题不能为空');
		}

		if(!empty($data['start_time']) && !empty($data['end_time']) && $data['start_time'] >= $data['end_time']){
			return new WP_Error('invalid_time', '开始时间必须小于结束时间');
		}

		return true;
	}

	protected static function sanitize_data($data, $id=0){
		if(!$id){
			$data['post_status']	= 'draft';
		}

		return parent::sanitize_data($data, $id);
	}

	public static function get_setting($name='', $default=null){
		return wpjam_get_setting('wpjam-comment', $name) ?? $default;
	}

	public static function update_setting(...$args){
		flush_rewrite_rules();

		return wpjam_update_setting('wpjam-comment', ...$args);
	}

	public static function register_json($json){
		if($json == 'form.get'){
			wpjam_register_json('form.get', [
				'fields'	=> ['id'=>['type'=>'number', 'required'=>true, 'validate_callback'=>[self::class, 'validate']]],
				'callback'	=> function($data){
					$form	= wpjam_get_post($data['id']);

					return array_merge($form, ['form'=> $form]);	// 兼容代码
				}
			]);
		}
	}

	public static function render_item($item){
		$object	= self::get_instance($item['id']);
		$thumb	= $object->thumbnail ? '<img src="'.$object->get_thumbnail_url([120]).'" '.image_hwstring(60,0).' />' : '';
		$clear	= $object->thumbnail ? '<br class="clear" />' : '';
		$link	= admin_url('page=form-submissions&post_id='.$item['id']);

		$item['title']	= '<strong><a href="'.$link.'" class="wpjam-thumbnail-wrap">'.$thumb.$item['title'].'</a></strong>'.$clear;
		$item['data']	= '<a href="'.$link.'" class="button">查看数据</a>';
		$item['fields']	= ['items'=>$object->get_items(), 'args'=>[
			'item_type'	=> 'text',
			'sortable'	=> true,
			'text_key'	=> 'name',
			'max_items'	=> 20
		]];

		if($item['status'] == 'draft'){
			$item['class']	= 'unapproved';
		}else{
			$item['title']	= '<a href="'.$link.'" class="button alignright">查看数据</a>'.$item['title'];
			$item['status']	= '[filter status="'.$item['status'].'"]'.'<strong>'.$object->get_status('name').'</strong>[/filter]';
			$item['status']	.= "\n\n".'开始时间：'.($object->start_time ? wpjam_date('Y-m-d H:i', $object->start_time) : '立即开始');
			$item['status']	.= "\n".'结束时间：'. ($object->end_time ? wpjam_date('Y-m-d H:i', $object->end_time) : '长期有效');
			$item['status']	= wpautop($item['status']);
		}
		
		return $item;
	}

	public static function get_actions(){
		return WPJAM_Model::get_actions()+self::get_item_actions()+[
			'publish'	=> ['title'=>'发布',	'direct'=>true,		'confirm'=>true, 'show_if'=>['status', '=', 'draft']],
			'unpublish'	=> ['title'=>'撤回',	'direct'=>true,		'confirm'=>true, 'show_if'=>['status', '=', 'publish']],
			'view'		=> ['title'=>'查看',	'redirect'=>fn($id)=> get_permalink($id),	'show_if'=>fn($id)=> get_post_status($id) == 'publish' && !get_post_meta($id, 'variable', true)],
			'set'		=> ['title'=>'设置',	'overall'=>true,	'class'=>'button-primary',	'value_callback'=>[self::class, 'get_setting'],	'callback'=>[self::class, 'update_setting']]
		];
	}

	public static function get_field_type_options(){
		return [
			'text'		=> '输入框',
			'textarea'	=> '文本框',
			'number'	=> '数字输入框',
			'url'		=> '链接输入框',
			'email'		=> '邮件输入框',
			'date'		=> '日期选择框',
			'time'		=> '时间选择框',
			'select'	=> '下拉选择框',
			'radio'		=> '单选框',
			'checkbox'	=> '复选框',
			// 'id'		=> '身份证号',
			// 'tel'	=> '手机号码',
			'img'		=> '上传图片',
			'file'		=> '上传文件'
		];
	}

	public static function get_fields($action_key='', $id=0){
		if($action_key == ''){
			return [
				'title'		=> ['title'=>'标题',	'column'=>['style'=>'min-width: 300px;']],
				'fields'	=> ['title'=>'字段',	'column'=>['style'=>'min-width: 300px;']],
				'status'	=> ['title'=>'状态',	'column'=>['style'=>'min-width: 200px;'],	'options'=>['draft'=>'未发布']],
			];
		}elseif($action_key == 'add' || $action_key == 'edit'){
			$mail	= self::get_setting('form_mail');
			$mail	= ($mail ? '全局设置的邮箱地址是：<code>'.$mail.'</code>' : '你没有设置全局设置的邮箱地址').'，单独设置：';
			$fields	= [
				'title'		=> ['title'=>'标题',	'type'=>'text',		'required'],
				'excerpt'	=> ['title'=>'简介',	'type'=>'textarea',	'class'=>''],
				'limit'		=> ['title'=>'上限',	'type'=>'fields',	'fields'=>[
					'max'	=> ['type'=>'number',	'name'=>'meta_input[max]', 		'before'=>'总共允许', 'after'=>'次提交，', 'class'=>'small-text'],
					'per'	=> ['type'=>'number',	'name'=>'meta_input[per_user]', 'before'=>'每人最多', 'after'=>'次提交，0不限制。', 'class'=>'small-text'],
				]],
				'time'		=> ['title'=>'时间',	'type'=>'fields',	'fields'=>[
					'start'	=> ['type'=>'timestamp',	'name'=>'meta_input[start_time]'],
					'view'	=> ['type'=>'view',			'value'=>' - '],
					'end'	=> ['type'=>'timestamp',	'name'=>'meta_input[end_time]'],
				]],
				'variable'	=> ['title'=>'变量',	'type'=>'checkbox',	'name'=>'meta_input[variable]',		'label'=>'支持变量模式，即下拉选择框 / 单选框 / 多选框的选项使用时再注入。'],
				'vertical'	=> ['title'=>'显示',	'type'=>'radio',	'name'=>'meta_input[vertical]',		'options'=>[0=>'横排', 1=>'竖排']],
				'thumbnail'	=> ['title'=>'头图',	'type'=>'img',		'name'=>'meta_input[_thumbnail_id]'],
				'bg'		=> ['title'=>'背景',	'type'=>'img',		'name'=>'meta_input[background]',	'item_type'=>'url',	'size'=>'1440x0'],
				'mail_set'	=> ['title'=>'邮箱',	'type'=>'fieldset',	'fields'=>[
					'view'	=> ['type'=>'view',		'value'=>$mail],
					'mail'	=> ['type'=>'email',	'name'=>'meta_input[mail]'],
				]],
			];

			if(!current_theme_supports('form')){
				unset($fields['variable']);
			}
		}elseif($action_key == 'add_item' || $action_key == 'edit_item'){
			$object		= self::get_instance($id);
			$fields		= $object->get_items() ?: [];
			$names		= array_column($fields, 'name');
			$names		= [''=>'默认显示', 'key'=>['label'=>'显示规则', 'options'=>array_combine($names, $names)]];
			$compare	= ['', '=', '!=', '>', '>=', '<', '<=', 'IN', 'NOT IN'];
			$show_if	= ['key'=>'key', 'compare'=>'!=', 'value'=>''];
			$show_if	= ['title'=>'显示',	'type'=>'fields',	'fieldset_type'=>'array',	'fields'=>[
				'key'		=> ['type'=>'select',	'options'=>$names,		'class'=>'medium-text'],
				'compare'	=> ['type'=>'select',	'show_if'=>$show_if,	'options'=>array_combine($compare, $compare)],
				'value'		=> ['type'=>'text',		'show_if'=>$show_if,	'class'=>'medium-text']
			]];

			$fields	= [
				'type'		=> ['title'=>'类型',	'type'=>'select',	'options'=>self::get_field_type_options()],
				'name'		=> ['title'=>'名称',	'type'=>'text',		'class'=>'',	'required',	'placeholder'=>'请输入字段名称',	'pattern'=>'^[^\d].*'],		
				'label'		=> ['title'=>'提示',	'type'=>'textarea',	'class'=>'',	'rows'=>3,	'placeholder'=>'输入提示'],
				'required'	=> ['title'=>'必填',	'type'=>'checkbox',	'label'=>'该字段必填',		'show_if'=>['key'=>'type', 'compare'=>'not in', 'value'=>['select', 'radio', 'checkbox']]],
				'variable'	=> ['title'=>'变量',	'type'=>'checkbox',	'label'=>'选项使用时候再注入',	'show_if'=>['key'=>'type', 'compare'=>'in', 'value'=>['select', 'radio', 'checkbox']]],
				'options'	=> ['title'=>'选项',	'type'=>'mu-text',	'class'=>'',	'required',	'placeholder'=>'请输入选项...',	'show_if'=>['key'=>'type', 'compare'=>'in', 'value'=>['select', 'radio', 'checkbox']]],
				'csutom'	=> ['title'=>'其他',	'type'=>'checkbox',	'label'=>'支持通过输入框输入其他选项',	'show_if'=>['key'=>'type', 'compare'=>'in', 'value'=>['radio', 'checkbox']]],
				'show_if'	=> $show_if
			];

			if(current_theme_supports('form') && $object->variable){
				$fields['options']['show_if']	= ['key'=>'variable', 'value'=>false, 'strict'=>true];
			}else{
				unset($fields['variable']);
			}
		}elseif($action_key == 'set'){
			$fields	=[
				'form_permastruct'	=> ['title'=>'固定链接',	'type'=>'select', 	'options'=>['form/%form_id%'=>'使用ID', 'form/%postname%'=>'使用别名']],
				'form_mail'			=> ['title'=>'通知邮箱',	'type'=>'email',	'placeholder'=>'当访客提交信息后，通过邮件发送到这个指定的邮箱'],
				'form_redirect'		=> ['title'=>'跳转链接',	'type'=>'url',		'placeholder'=>'提交成功后跳转到的页面，不填则弹窗显示提交成功'],
			];

			if(did_action('weapp_loaded')){
				$fields['form_weapp_path']	= ['title'=>'小程序路径',	'type'=>'text',	'value'=>'/pages/formPage/formPage?id=%post_id%'];
			}
		}elseif($action_key == 'variable'){
			$object	= self::get_instance($id);

			if($object && $object->variable){
				foreach($object->get_items() as $field){
					if(in_array($field['type'], ['select', 'checkbox', 'radio']) && !empty($field['variable'])){
						$fields[$field['name']]	= ['title'=>$field['name'],	'name'=>'form_options['.$field['name'].']', 'type'=>'mu-text', 'class'=>'', 'required', 'placeholder'=>'请输入选项...'];
					}
				}
			}

			$fields	??= ['form_options'=>['title'=>'变量', 'type'=>'view', 'value'=>'该表单无变量设置']];
		}

		return $fields ?? [];
	}

	public static function get_menu_page(){
		return [
			'menu_slug'		=> 'wpjam-forms',
			'menu_title'	=> '表单',
			'icon'			=> 'dashicons-forms',
			'position'		=> 10,
			'function'		=> 'list',
			'network'		=> false,
			'list_table'	=> [
				'title'		=> '表单',
				'plural'	=> 'wpjam-forms',
				'singular'	=> 'wpjam-form',
				'data_type'	=> 'post_type',
				'post_type'	=> 'form',
				'model'		=> self::class,
			],
		];
	}

	public static function get_admin_load(){
		if(current_theme_supports('form')){
			return[
				'base'	=> 'edit',
				'model'	=> self::class
			];
		}
	}

	public static function builtin_page_load(){
		$support	= get_theme_support('form');
		$support	= is_array($support[0]) ? $support[0] : $support;
		$post_type	= count($support) == 1 && $support[0] === true ? null : $support;

		wpjam_register_list_table_action('choose_form', [
			'title'		=> '设置表单',
			'post_type'	=> $post_type,
			'fields'	=> ['form_id'=> wpjam_get_post_id_field('form')],
			'next'		=> 'set_form',
			'response'	=> 'form'
		]);

		wpjam_register_list_table_action('set_form', [
			'title'		=> '设置表单',
			'post_type'	=> $post_type,
			'fields'	=> fn()=> self::get_fields('variable', wpjam_get_data_parameter('form_id')),
			'callback'	=> fn($id, $data)=> wpjam_update_metadata('post', $id, $data)
		]);
	}

	public static function ajax_callback($data){
		$form		= self::get_instance($data['form_id']);
		$meta		= wp_array_slice_assoc($data, ['source_type', 'source_data']);
		$result		= $form ? $form->submit($data) : wpjam_throw('invalid_form_id', '无效的表单 ID');
		$redirect	= self::get_setting('form_redirect');
		$message	= get_locale() == 'zh_CN' ? '表单已成功提交！' : 'The form has been submitted successfully!';

		return $redirect ? ['redirect'=>$redirect] : ['msg'=>esc_html__($message)];
	}

	public static function on_after_setup_theme(){
		add_action('template_redirect',	function(){
			if(is_singular('form')){
				add_filter('template_include',	fn()=> locate_template('single-form.php') ?: dirname(__DIR__).'/template/single.php');
			}
		});

		wpjam_update_post_type_setting('form', 'permastruct', (self::get_setting('form_permastruct') ?: 'form/%form_id%'));

		if(did_action('weapp_loaded')){
			wpjam_register_path('form',	[
				'path_type'		=> 'weapp',
				'title'			=> '表单页',
				'page_type'		=> 'post_type',
				'post_type'		=> 'form',
				'path'			=> self::get_setting('form_weapp_path') ?: '/pages/formPage/formPage?id=%post_id%'
			]);
		}

		wpjam_register_comment_feature('source', [
			'title'	=> '来源',
			'type'	=> 'submit',
			'model'	=> 'WPJAM_Form_Submit_Source',
		]);

		wpjam_register_comment_type('submit', 'form', [
			'model'		=> 'WPJAM_Form_Submit',
			'label'		=> '提交',
			'plural'	=> 'submissions',
			'order'		=> 'DESC',
			'supports'	=> ['source']
		]);

		wpjam_register_ajax('form-submit',	[
			'nopriv'		=> true,
			'nonce_keys'	=> ['form_id'],
			'fields'		=> ['form_id'=>['required'=>true, 'type'=>'number']],
			'callback'		=> fn($data)=> self::ajax_callback($data)
		]);

		wpjam_script('form-submit', [
			'for'			=> '',
			'src'			=> wpjam_url(dirname(__DIR__).'/template/script.js'),
			'deps'			=> ['wpjam-ajax'],
			'enqueue_if'	=> fn()=> !current_theme_supports('script', 'form-submit')
		]);

		add_shortcode('wpjam_form', fn($atts)=> wpjam_ob_get_contents('wpjam_render_form', array_get((array)$atts, 'id')));

		// add_theme_support('form', 'post');
	}

	public static function add_hooks(){
		add_action('after_setup_theme',	[self::class, 'on_after_setup_theme']);
	}
}

wpjam_register_post_type('form', [
	'label'					=> '表单',
	'supports'				=> ['title', 'thumbnail'],
	'public'				=> true,
	'exclude_from_search'	=> true,
	'show_ui'				=> false,
	'has_archive'			=> false,
	'query_var'				=> false,
	'rewrite'				=> false,
	'model'					=> 'WPJAM_Form',
]);

function wpjam_render_form($id, $title=''){
	$form	= $id ? wpjam_get_post_object($id) : null;

	if($form){
		$args	= $title ? ['title'=>$title] : [];

		if(is_singular()){
			$args['meta']	= ['source_type'=>'post',	'source_data'=>get_queried_object_id()];
		}elseif(is_tax('modulue_page')){
			$args['meta']	= ['source_type'=>'module',	'source_data'=>get_queried_object_id()];
		}elseif(is_front_page()){
			$args['meta']	= ['source_type'=>'home'];
		}

		$form->render($args);
	}else{
		echo '<p>请提供有效的表单ID。</p>';
	}
}
