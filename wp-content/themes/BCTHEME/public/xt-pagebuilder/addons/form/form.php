<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class XTPB_Addon_Form{

	public function __construct() {
		//Register scripts
		add_action('wp_enqueue_scripts', array($this, 'register_required_script'));
		add_action('xtpb_enqueue_scripts', array($this, 'register_required_script'));

		//Load scripts at builder
		add_action('xtpb_enqueue_scripts', array($this, 'add_scripts'));

		//Process Form
		add_action('wp_ajax_xtpb_form_process', array($this, 'xtpb_form_process'));
		add_action('wp_ajax_nopriv_xtpb_form_process', array($this, 'xtpb_form_process'));
	}

	public function get_name(){
		return 'xtpb_form';
	}
	public function get_title(){
		return __( '表单', 'xintheme-pagebuilder' );
	}
	public function get_icon() {
		return 'xtpb-font-envelope';
	}

	public function register_required_script(){
		wp_register_script('xtpb-form.main.js', XTPB_DIR_URL.'addons/form/assets/js/main.js', array('jquery'), false, true);
		wp_localize_script('xtpb-form.main.js', "xtpb_form", array('ajax_url' => admin_url('admin-ajax.php'), 'form_addon_dir_url' => XTPB_DIR_URL.'addons/form/'));


		$pageID = get_the_ID();
		if ($pageID){
			if (xtpb_helper()->is_xtpb_single() && ! xtpb_helper()->is_editor_screen() && ! xtpb_helper()->is_load_editor_iframe() ){
				$reCapchaJS = 'https://www.google.com/recaptcha/api.js';

				$savedAddons = xtpb_get_saved_addons_by_name('xtpb_form', $pageID);
				$isGoogleRecaptchaEnable = false;

				if (is_array($savedAddons) && count($savedAddons)){
					foreach ($savedAddons as $saved_addon){
						$s = $saved_addon['settings'];
						if (isset($s['enable_google_recaptcha'])){
							$isEnable = (bool) $s['enable_google_recaptcha'];
							if ($isEnable){
								$isGoogleRecaptchaEnable = true;
								break;
							}
						}
					}
				}
				if ($isGoogleRecaptchaEnable){
					wp_enqueue_script('xtpb-form-google-recaptcha.js', $reCapchaJS, array('jquery'), false, true);
				}
			}
		}
	}

	public function get_enqueue_script(){
		return array( 'xtpb-form.main.js', 'xtpb-form-google-recaptcha');
	}

	public function get_enqueue_style(){
		return array();
	}
	//Add Frontend builder Script
	public function add_scripts(){
		if ( xtpb_helper()->is_editor_screen() && ! xtpb_helper()->is_load_editor_iframe() ) {
			return;
		}

		$scripts = $this->get_enqueue_script();
		$styles = $this->get_enqueue_style();

		if (is_array($scripts) && count($scripts)){
			foreach ($scripts as $script){
				wp_enqueue_script($script);
			}
		}

		if (is_array($styles) && count($styles)){
			foreach ($styles as $style){
				wp_enqueue_style($style);
			}
		}
	}


	public function get_settings() {

		$admin_email = get_option('admin_email');

		$settings = array(
			'xtpb_default_form' => array(
				'title' => __('表单字段','xintheme-pagebuilder'),
				'type' => 'repeatable',
				'label' => 'label',
				'std' => array(
					array(
						'label' => __( '名字', 'xintheme-pagebuilder' ),
						'field_type' => 'text',
						'placeholder' => __( '请输入名字', 'xintheme-pagebuilder' ),
						'field_width' => array(
							'md' => '50%',
							'sm' => '',
							'xs' => '',
						),
					),
					array(
						'label' => __( '姓氏', 'xintheme-pagebuilder' ),
						'field_type' => 'text',
						'placeholder' => __( '请输入姓氏', 'xintheme-pagebuilder' ),
						'field_width' => array(
							'md' => '50%',
							'sm' => '',
							'xs' => '',
						),
					),
					array(
						'label' => __( '邮箱', 'xintheme-pagebuilder' ),
						'field_type' => 'email',
						'placeholder' => __( '邮箱', 'xintheme-pagebuilder' ),
						'require'     => '1',
						'field_width' => array(
							'md' => '50%',
							'sm' => '',
							'xs' => '',
						),
					),
					array(
						'label' => __( '主题', 'xintheme-pagebuilder' ),
						'field_type' => 'text',
						'placeholder' => __( '主题', 'xintheme-pagebuilder' ),
						'field_width' => array(
							'md' => '50%',
							'sm' => '',
							'xs' => '',
						),
					),
					array(
						'label' => __( '留言内容', 'xintheme-pagebuilder' ),
						'field_type' => 'textarea',
						'placeholder' => __( '请在这里输入留言内容', 'xintheme-pagebuilder' ),
						'field_width' => array(
							'md' => '100%',
							'sm' => '',
							'xs' => '',
						),
					),
				),
				'attr' => array(
					'label' => array(
						'type' => 'text',
						'title' => __('标签','xintheme-pagebuilder'),
					),

					'field_id' => array(
						'type' => 'text',
						'title' => __('字段 ID','xintheme-pagebuilder'),
						'desc' => __('定义此字段的唯一 ID。只能使用英文字母，且不能包含特殊字符和空格。', 'xintheme-pagebuilder')
					),

					'placeholder' => array(
						'type' => 'text',
						'title' => __('占位文本','xintheme-pagebuilder'),
						'std' => '',
						'depends' => array(array('field_type', '=', array('text', 'email', 'textarea'))),
					),

					'require' => array(
						'type' => 'switch',
						'title' => __('必填字段', 'xintheme-pagebuilder'),
						'std' => '1',
						'desc' => __('定义该字段是必填还是选填。', 'xintheme-pagebuilder'),
					),

					'field_width' => array(
						'type' 		  => 'slider',
						'title' 	  => __( '字段宽度', 'xintheme-pagebuilder' ),
						'unit' 		  => array( 'px','em','%' ),
						'range' => array(
							'em' => array(
								'min' => 0,
								'max' => 50,
								'step' => .1,
							),
							'px' => array(
								'min' => 1,
								'max' => 400,
								'step' => 1,
							),
							'%' => array(
								'min' => 0,
								'max' => 100,
								'step' => 1,
							),
						),
						'std'		  => array(
							'md' => '50%',
							'sm' => '50%',
						),
						'responsive'  => true,
						'selector' => '{{SELECTOR}} { width: {{data.field_width}}; }',
					),
					'field_type' => array(
						'type' => 'select',
						'title' => __( '字段类型', 'xintheme-pagebuilder' ),
						'values' => array(
							'text'      => __( '文本', 'xintheme-pagebuilder' ),
							'email'     => __( '邮箱', 'xintheme-pagebuilder' ),
							'radio'     => __( '单选', 'xintheme-pagebuilder' ),
							'checkbox'  => __( '复选框', 'xintheme-pagebuilder' ),
							'textarea'  => __( '文本域', 'xintheme-pagebuilder' ),
							'select'    => __( '下拉框', 'xintheme-pagebuilder' ),
						),
						'std' => 'text',
					),
					'radio_field_options' => array(
						'title'     => __('添加选项', 'xintheme-pagebuilder'),
						'type'      => 'repeatable',
						'label' => 'label',
						'std'       => array(),
						'attr' => array(
							'label' => array(
								'type' => 'text',
								'title' => __('单选名称','xintheme-pagebuilder'),
								'std' => '',
							),
						),
						'depends' => array(array('field_type', '=', 'radio')),
					),
					'checkbox_field_options' => array(
						'title'     => __('添加选项', 'xintheme-pagebuilder'),
						'type'      => 'repeatable',
						'std'       => array(),
						'label' => 'label',
						'attr' => array(
							'label' => array(
								'type' => 'text',
								'title' => __('复选框名称','xintheme-pagebuilder'),
								'std' => '',
							),
						),
						'depends' => array(array('field_type', '=', 'checkbox')),
					),

					'select_options' => array(
						'title'     => __('下拉选项', 'xintheme-pagebuilder'),
						'type'      => 'repeatable',
						'std'       => array(),
						'label' => 'label',
						'attr' => array(
							'label' => array(
								'type' => 'text',
								'title' => __('选项名称','xintheme-pagebuilder'),
								'std' => '',
							),
						),
						'depends' => array(array('field_type', '=', 'select')),
					),
				),
			),

			'xtpb_default_form_to_email' => array(
				'type' => 'text',
				'title' => __('收件邮箱','xintheme-pagebuilder'),
				'std' => $admin_email,
				'section' => __( '邮箱', 'xintheme-pagebuilder' ),
			),
			'xtpb_default_form_from_email' => array(
				'type' => 'text',
				'title' => __('发件邮箱','xintheme-pagebuilder'),
				'std' => $admin_email,
				'section' => __( '邮箱', 'xintheme-pagebuilder' ),
			),
			'xtpb_default_form_subject' => array(
				'type' => 'text',
				'title'     => __('主题','xintheme-pagebuilder'),
				'std'       => __('在 {datetime} 收到一条新的咨询','xintheme-pagebuilder'),
				'section'   => __( '邮箱', 'xintheme-pagebuilder' ),
				'desc' => __('你可以为日期时间设置变量，可用变量：<strong>{date}, {time}, {datetime}</strong>', 'xintheme-pagebuilder'),
			),
			'email_format' => array(
				'type' => 'textarea',
				'title' => __('消息格式', 'xintheme-pagebuilder'),
				'std' => '',
				'section' => __( '邮箱', 'xintheme-pagebuilder' ),
				'desc' => __('自定义此表单的消息格式，例如：<strong>我的消息是 {Message}</strong>。{Message} 会显示所有提交字段。', 'xintheme-pagebuilder'),
			),
			'success_message' => array(
				'type' => 'text',
				'title' => __('成功提示','xintheme-pagebuilder'),
				'std' => '',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'desc' => __('表单提交成功后在表单上方显示的提示。留空则不显示。', 'xintheme-pagebuilder'),
			),
			'enable_redirect_url' => array(
				'type' => 'switch',
				'title' => __('启用重定向 URL', 'xintheme-pagebuilder'),
				'std' => '0', // value 0 or 1,
				'section' => __( '重定向', 'xintheme-pagebuilder' ),
				'desc' => __('表单提交成功后跳转到指定 URL。', 'xintheme-pagebuilder'),
			),
			'redirect_url' => array(
				'type' => 'text',
				'title' => __('重定向 URL','xintheme-pagebuilder'),
				'std' => '',
				'section' => __( '重定向', 'xintheme-pagebuilder' ),
				'desc' => __('在此填写重定向 URL', 'xintheme-pagebuilder'),
				'depends' => array(array('enable_redirect_url', '=', '1') ),
			),

			'enable_simple_recaptcha' => array(
				'type' => 'switch',
				'title' => __('启用简易 ReCAPTCHA', 'xintheme-pagebuilder'),
				'std' => '0', // value 0 or 1,
				'section' => __( '安全', 'xintheme-pagebuilder' ),
				'desc' => __('启用或禁用简易 ReCAPTCHA', 'xintheme-pagebuilder'),
				'depends' => array(array('enable_google_recaptcha', '!=', '1')),
			),
			'enable_google_recaptcha' => array(
				'type' => 'switch',
				'title' => __('启用 Google ReCAPTCHA', 'xintheme-pagebuilder'),
				'std' => '0', // value 0 or 1,
				'section' => __( '安全', 'xintheme-pagebuilder' ),
				'desc' => __('启用或禁用 Google ReCAPTCHA', 'xintheme-pagebuilder'),
				'depends' => array(array('enable_simple_recaptcha', '!=', '1')),
			),
			/*
			'xtpb_form_recaptcha_question' => array(
				'type' => 'text',
				'title' => __('设置问题','xintheme-pagebuilder'),
				'std' => '3+6',
				'desc' => __('设置一个需要用户回答的问题，例如：{addition 3+6 ?}', 'xintheme-pagebuilder'),
				'section' => __( '安全', 'xintheme-pagebuilder' ),
				'depends' => array(array('enable_simple_recaptcha', '=', '1')),
			),

			'xtpb_form_recaptcha_answer' => array(
				'type' => 'text',
				'title' => __('设置答案','xintheme-pagebuilder'),
				'std' => '9',
				'desc' => __('用户的回答必须与此一致。', 'xintheme-pagebuilder'),
				'section' => __( '安全', 'xintheme-pagebuilder' ),
				'depends' => array(array('enable_simple_recaptcha', '=', '1')),
			),*/

			'google_recaptcha_site_key' => array(
				'type' => 'text',
				'title' => __('站点密钥','xintheme-pagebuilder'),
				'std' => '',
				'desc' => __('将此密钥用于网站向用户输出的 HTML 代码中。', 'xintheme-pagebuilder'),
				'section' => __( '安全', 'xintheme-pagebuilder' ),
				'depends' => array(array('enable_google_recaptcha', '=', '1')),
			),
			'google_recaptcha_secret_key' => array(
				'type' => 'text',
				'title' => __('密钥','xintheme-pagebuilder'),
				'std' => '',
				'desc' => __('将此密钥用于你的网站与 Google 之间的通信，请务必妥善保管。', 'xintheme-pagebuilder'),
				'section' => __( '安全', 'xintheme-pagebuilder' ),
				'depends' => array(array('enable_google_recaptcha', '=', '1')),
			),

			'button_text' => array(
				'type' => 'text',
				'title' => __('按钮文字','xintheme-pagebuilder'),
				'std' => __( '提交表单', 'xintheme-pagebuilder' ),
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
			),
			'button_success_text' => array(
				'type' => 'text',
				'title' => __('提交成功文字','xintheme-pagebuilder'),
				'std' => __( '提交成功', 'xintheme-pagebuilder' ),
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'desc' => __('表单提交成功后，按钮短暂显示的文字。', 'xintheme-pagebuilder'),
			),
			'icon_list' => array(
				'type' => 'icon',
				'title' => __('按钮图标','xintheme-pagebuilder'),
				'std' => '',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
			),
			'icon_position' => array(
				'type' => 'select',
				'title' => __('按钮图标位置','xintheme-pagebuilder'),
				'depends' => array(array('icon_list', '!=', '')),
				'values' => array(
					'left' => __('左侧','xintheme-pagebuilder'),
					'right' => __('右侧','xintheme-pagebuilder'),
				),
				'std' => 'left',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
			),
			'fullwidth_button' => array(
				'type' => 'select',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'title' => __('全宽','xintheme-pagebuilder'),
				'values' => array(
					'full-yes' => __('是','xintheme-pagebuilder'),
					'full-no' => __('否','xintheme-pagebuilder'),
				),
				'std' => 'no',
			),

			// form style
			'vertical_form_space' => array(
				'type' => 'slider',
				'title' => __('垂直间距','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 15,
						'step' => .1,
					),
					'px' => array(
						'min' => 0,
						'max' => 200,
						'step' => 1,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
						'step' => 1,
					),
				),
				'std' => array(
					'md' => '15px',
					'sm' => '',
					'xs' => ''
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '表单样式', 'xintheme-pagebuilder' ),
				'selector' => array(
					'{{SELECTOR}} .xtpb-form-field-item-wrap { margin-left: -{{data.vertical_form_space}}; }',
					'{{SELECTOR}} .xtpb-form-field-item-wrap { margin-right: -{{data.vertical_form_space}}; }',
					'{{SELECTOR}} .xtpb-form-field-repeat { padding-left: {{data.vertical_form_space}}; }',
					'{{SELECTOR}} .xtpb-form-field-repeat { padding-right: {{data.vertical_form_space}}; }',
				),
			),
			'horizontal_form_space' => array(
				'type' => 'slider',
				'title' => __('水平间距','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'range' => array(
					'em' => array(
						'min' => 0,
						'max' => 15,
						'step' => .1,
					),
					'px' => array(
						'min' => 0,
						'max' => 200,
						'step' => 1,
					),
					'%' => array(
						'min' => 0,
						'max' => 100,
						'step' => 1,
					),
				),
				'std' => array(
					'md' => '25px',
					'sm' => '',
					'xs' => ''
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '表单样式', 'xintheme-pagebuilder' ),
				'selector' => array(
					'{{SELECTOR}} .xtpb-form-field-repeat { margin-bottom: {{data.horizontal_form_space}}; }',
				),
			),
			'input_fontstyle' => array(
				'type' => 'typography',
				'title' => __('输入框排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'14px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '400',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} select, {{SELECTOR}} input[type=text], {{SELECTOR}} input[type=email], {{SELECTOR}} textarea, {{SELECTOR}} .xtpb-form-field-input input, {{SELECTOR}} .xtpb-form-field-email input, {{SELECTOR}} .xtpb-form-field-textarea textarea',
				'depends' => array(array('label_show', '=', '1')),
				'section' => __( '表单样式', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'input_color' => array(
				'type' => 'color',
				'title' => __('输入框颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '表单样式', 'xintheme-pagebuilder' ),
				'std' => '#454545',
				'selector' => '{{SELECTOR}} select, {{SELECTOR}} input[type=text], {{SELECTOR}} input[type=email], {{SELECTOR}} textarea, {{SELECTOR}} .xtpb-form-field-input input, {{SELECTOR}} .xtpb-form-field-email input, {{SELECTOR}} .xtpb-form-field-textarea textarea { color: {{data.input_color}}; }'
			),
			'input_shadow' => array(
				'type' => 'boxshadow',
				'tab' => 'style',
				'section' => __( '表单样式', 'xintheme-pagebuilder' ),
				'title' => __('输入框阴影','xintheme-pagebuilder'),
				'std' => array(
						'shadowValue'=> array( 'top' => '0px', 'right' => '0px', 'bottom' => '10px', 'left' => '0px' ), 
						'shadowColor' 	=> '#333' 
					),
				'selector' => '{{SELECTOR}} select, {{SELECTOR}} input[type=text], {{SELECTOR}} input[type=email], {{SELECTOR}} textarea, {{SELECTOR}} .xtpb-form-field-input input, {{SELECTOR}} .xtpb-form-field-email input, {{SELECTOR}} .xtpb-form-field-textarea textarea'
			),
			'paceholder_color' => array(
				'type' => 'color',
				'title' => __('占位文本颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '表单样式', 'xintheme-pagebuilder' ),
				'std' => '#d5d5d5',
				'selector' => '{{SELECTOR}} .xtpb-form-field-input input::-webkit-input-placeholder, {{SELECTOR}} .xtpb-form-field-email input[type=email]::-webkit-input-placeholder, {{SELECTOR}} .xtpb-form-field-textarea textarea::-webkit-input-placeholder, span.xtpb-form-field-item.xtpb-form-field-select select { color: {{data.paceholder_color}}; }'
			),
			'input_bg' => array(
				'type' => 'color',
				'title' => __('输入框背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '表单样式', 'xintheme-pagebuilder' ),
				'std' => '',
				'selector' => '{{SELECTOR}} select, {{SELECTOR}} input[type=text], {{SELECTOR}} input[type=email], {{SELECTOR}} textarea, {{SELECTOR}} .xtpb-form-field-input input, {{SELECTOR}} .xtpb-form-field-email input, {{SELECTOR}} .xtpb-form-field-textarea textarea { background: {{data.input_bg}}; }'
			),
			'radio_bg' => array(
				'type' => 'color',
				'title' => __('单选/复选激活背景颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '表单样式', 'xintheme-pagebuilder' ),
				'std' => '#00a7ff',
				'selector' => '{{SELECTOR}} .xtpb-form-field-radio input[type="radio"]:checked + label:before, {{SELECTOR}} .xtpb-form-field-checkbox input[type="checkbox"]:checked + label:before  { background: {{data.radio_bg}}; }'
			),
			'input_border' => array(
				'type' => 'border',
				'title' => __( '输入框边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'itemOpenBorder' => 1,
					'borderWidth' => array( 'top' => '1px', 'right' => '1px', 'bottom' => '1px', 'left' => '1px' ),
					'borderStyle' => 'solid',
					'borderColor' => '#e5e5e5'
				),
				'tab' => 'style',
				'section' => __( '表单样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} textarea, {{SELECTOR}} input[type=url], {{SELECTOR}} input[type=email], {{SELECTOR}} input[type=text], {{SELECTOR}} select, {{SELECTOR}} .xtpb-form-field-input input, {{SELECTOR}} .xtpb-form-field-email input, {{SELECTOR}} .xtpb-form-field-textarea textarea'
			),
			'input_focus_color' => array(
				'type' => 'border',
				'title' => __('输入框聚焦边框颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '表单样式', 'xintheme-pagebuilder' ),
				'std' => array(
					'itemOpenBorder' => 1,
					'borderWidth' => array( 'top' => '1px', 'right' => '1px', 'bottom' => '1px', 'left' => '1px' ),
					'borderStyle' => 'solid',
					'borderColor' => '#3666E4'
				),
				'selector' => '{{SELECTOR}} select:focus, {{SELECTOR}} textarea:focus, {{SELECTOR}} input[type=url]:focus, {{SELECTOR}} input[type=email]:focus, {{SELECTOR}} input[type=text]:focus, {{SELECTOR}} .xtpb-form-field-input input:focus, {{SELECTOR}} .xtpb-form-field-email input:focus, {{SELECTOR}} .xtpb-form-field-textarea textarea:focus'
			),
			'input_radius' => array(
				'type' => 'dimension',
				'title' => __('输入框边框圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'std' => array(
					'md' => array( 'top' => '3px', 'right' => '3px', 'bottom' => '3px', 'left' => '3px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '表单样式', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} textarea, {{SELECTOR}} input[type=url], {{SELECTOR}} input[type=email], {{SELECTOR}} input[type=text], {{SELECTOR}} .xtpb-form-field-input input, {{SELECTOR}} .xtpb-form-field-email input, {{SELECTOR}} .xtpb-form-field-textarea textarea, span.xtpb-form-field-item.xtpb-form-field-select select { border-radius: {{data.input_radius}}; }'
			),
			'input_padding' => array(
				'type' => 'dimension',
				'title' => __( '输入框内边距', 'xintheme-pagebuilder' ),
				'std' => array(
					'md' => array( 'top' => '10px', 'right' => '15px', 'bottom' => '10px', 'left' => '15px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
				),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'section' => __( '表单样式', 'xintheme-pagebuilder' ),
				'tab' => 'style',
				'selector' => '{{SELECTOR}} textarea, {{SELECTOR}} input[type=url], {{SELECTOR}} input[type=email], {{SELECTOR}} input[type=text], {{SELECTOR}} .xtpb-form-field-input input, {{SELECTOR}} .xtpb-form-field-email input, {{SELECTOR}} .xtpb-form-field-textarea textarea, span.xtpb-form-field-item.xtpb-form-field-select select { padding: {{data.input_padding}}; }'
			),
			'textarea_resize' => array(
				'type' => 'switch',
				'title' => __('文本域缩放','xintheme-pagebuilder'),
				'std' => '0',
				'tab' => 'style',
				'section' => __( '表单样式', 'xintheme-pagebuilder' ),
			),

			//label style
			'label_show' => array(
				'type' => 'switch',
				'title' => __('标签按钮','xintheme-pagebuilder'),
				'std' => '1',
				'tab' => 'style',
				'section' => __( '标签', 'xintheme-pagebuilder' ),
			),
			'label_fontstyle' => array(
				'type' => 'typography',
				'title' => __('标签排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'14px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '400',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-form-field-label, {{SELECTOR}} .xtpb-global-form-content label, {{SELECTOR}} label',
				'depends' => array(array('label_show', '=', '1')),
				'section' => __( '标签', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'label_color' => array(
				'type' => 'color',
				'title' => __('标签颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '标签', 'xintheme-pagebuilder' ),
				'std'     => '#353535',
				'depends' => array(array('label_show', '=', '1')),
				'selector' => '{{SELECTOR}} .xtpb-form-field-label, {{SELECTOR}} .xtpb-global-form-content label, {{SELECTOR}} label { color: {{data.label_color}}; min-height: 24px; min-width: 20px; }'
			),

			'label_margin' => array(
				'type' => 'dimension',
				'title' => __( '标签外边距', 'xintheme-pagebuilder' ),
				'std' => array(
					'md' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '10px', 'left' => '0px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
				),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '标签', 'xintheme-pagebuilder' ),
				'depends' => array(array('label_show', '=', '1')),
				'selector' => '{{SELECTOR}} .xtpb-form-field-label, {{SELECTOR}} .xtpb-global-form-content label, {{SELECTOR}} label { margin: {{data.label_margin}}; }'
			),

			//button style
			'button_fontstyle' => array(
				'type' => 'typography',
				'title' => __('排版','xintheme-pagebuilder'),
				'std' => array(
					'fontFamily' => '',
					'fontSize' => array( 'md'=>'12px', 'sm'=>'', 'xs'=>'' ),
					'lineHeight' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
					'fontWeight' => '400',
					'textTransform' => '',
					'fontStyle' => '',
					'letterSpacing' => array( 'md'=>'', 'sm'=>'', 'xs'=>'' ),
				),
				'selector' => '{{SELECTOR}} .xtpb-btn-forms',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'tab' => 'style',
			),
			'button_color' => array(
				'type' => 'color',
				'title' => __('颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'std'     => '#ffffff',
				'selector' => '{{SELECTOR}} .xtpb-btn-forms { color: {{data.button_color}}; }'
			),
			'button_hcolor' => array(
				'type' => 'color',
				'title' => __('悬停颜色','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'std'     => '#ffffff',
				'selector' => '{{SELECTOR}} .xtpb-btn-forms:hover { color: {{data.button_hcolor}}; }'
			),
			'button_bg' => array(
				'type' => 'color2',
				'title' => __('背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'clip' => false,
				'std' => array(
					'clip' => false,
					'colorType' => 'color',
					'colorColor' => '#23cf5f',
				),
				'selector' => '{{SELECTOR}} .xtpb-btn-forms'
			),

			'button_hover_bg' => array(
				'type' => 'color2',
				'title' => __('悬停背景','xintheme-pagebuilder'),
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'clip' => false,
				'std' => array(
					'clip' => false,
					'colorType' => 'color',
					'colorColor' => '#1fae51',
				),
				'selector' => '{{SELECTOR}} .xtpb-btn-forms:before'
			),
			'button_border' => array(
				'type' => 'border',
				'title' => __( '边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'itemOpenBorder' => 1,
					'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
					'borderStyle' => 'solid',
					'borderColor' => '#cccccc'
				),
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-btn-forms'
			),
			'button_border_hcolor' => array(
				'type' => 'border',
				'title' => __( '悬停边框', 'xintheme-pagebuilder' ),
				'std' => array(
					'itemOpenBorder' => 1,
					'borderWidth' => array( 'top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px' ),
					'borderStyle' => 'solid',
					'borderColor' => '#cccccc'
				),
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-btn-forms:hover'
			),
			'button_radius' => array(
				'type' => 'dimension',
				'title' => __('按钮圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'std' => array(
					'md' => array( 'top' => '5px', 'right' => '5px', 'bottom' => '5px', 'left' => '5px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-btn-forms { border-radius: {{data.button_radius}}; }'
      ),
      'button_hover_radius' => array(
				'type' => 'dimension',
				'title' => __('按钮悬停圆角','xintheme-pagebuilder'),
				'unit' => array( 'px','%','em' ),
				'std' => array(
					'md' => array( 'top' => '5px', 'right' => '5px', 'bottom' => '5px', 'left' => '5px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ), 
				),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-btn-forms:hover { border-radius: {{data.button_hover_radius}}; }'
			),
			'button_padding' => array(
				'type' => 'dimension',
				'title' => __( '按钮内边距', 'xintheme-pagebuilder' ),
				'std' => array(
					'md' => array( 'top' => '10px', 'right' => '20px', 'bottom' => '10px', 'left' => '20px' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
				),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'tab' => 'style',
				'selector' => '{{SELECTOR}} .xtpb-btn-forms { padding: {{data.button_padding}}; }'
			),
			'button_margin' => array(
				'type' => 'dimension',
				'title' => __( '按钮外边距', 'xintheme-pagebuilder' ),
				'std' => array(
					'md' => array( 'top' => '15px', 'right' => '0px', 'bottom' => '0', 'left' => '0' ),
					'sm' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
					'xs' => array( 'top' => '', 'right' => '', 'bottom' => '', 'left' => '' ),
				),
				'unit' => array( 'px','em','%' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-btn-forms { margin: {{data.button_margin}}; }'
			),
			'button_align' => array(
				'type' => 'alignment',
				'title' => __('对齐方式','xintheme-pagebuilder'),
				'std' => array( 'md' => 'left', 'sm' => '', 'xs' => '' ),
				'responsive' => true,
				'tab' => 'style',
				'section' => __( '按钮', 'xintheme-pagebuilder' ),
				'selector' => '{{SELECTOR}} .xtpb-form-btn-wrap { text-align: {{data.button_align}}; }',
			),

		);
		return $settings;
	}

	public function generateDefaultForm($data = array()){
		$settings 				= $data['settings'];
		$classlist				= '';
		$button_text 			= isset($settings["button_text"]) ? sanitize_text_field( $settings["button_text"] ) : '提交表单';
		$button_success_text 	= isset($settings["button_success_text"]) ? sanitize_text_field( $settings["button_success_text"] ) : __('提交成功', 'xintheme-pagebuilder');
		$icon_list 				= isset($settings["icon_list"]) ? sanitize_text_field( $settings["icon_list"] ) : '';
		$icon_position 			= isset($settings["icon_position"]) ? sanitize_text_field( $settings["icon_position"] ) : '';
		$textarea_resize 		= isset($settings["textarea_resize"]) ? sanitize_text_field( $settings["textarea_resize"] ) : '';
		$fullwidth_button 		= isset($settings["fullwidth_button"]) ? sanitize_text_field( $settings["fullwidth_button"] ) : '';
		
		$classlist .= (isset($fullwidth_button) && $fullwidth_button) ? ' xtpb-btn-' . $fullwidth_button : '';

		$enableSimpleRecaptcha = (bool) isset($settings['enable_simple_recaptcha']) ? $settings['enable_simple_recaptcha'] : false;
		$enableGoogleRecaptcha = (bool) isset($settings['enable_google_recaptcha']) ? $settings['enable_google_recaptcha'] : false;

		$input_items = isset($settings['xtpb_default_form']) ? $settings['xtpb_default_form'] : array();

		$form_data = array();
		$form_data['addon_id'] = $data['id'];
		$form_data['page_id'] = get_the_ID();
		$form_data['source_url'] = $this->get_current_source_url( get_the_ID() );

		$htmlForm = '';
		ob_start();
		if (is_array($input_items) && count($input_items)){
			?>
            <form method="post" enctype="multipart/form-data" class="xtpb-form-addon" data-form-id="<?php echo esc_attr( $form_data['addon_id'] ); ?>">
				<?php wp_nonce_field( 'xtpb_form_submit_action', 'xtpb_form_nonce_field' ); ?>
                <input type="hidden" name="xtpb_form_data" value="<?php echo esc_attr(json_encode($form_data)); ?>">
                <div class="xtpb-form-msg"></div>
                <div class="xtpb-form-field-item-wrap">
					<?php
					foreach ($input_items as  $fieldIndex => $field){
						$fieldAttr = ' ';
						$fieldAttrA = array();
						if ( ! empty($field['placeholder'])){
							$fieldAttrA['placeholder'] = sanitize_text_field( $field['placeholder'] );
						}
						if ( isset($field['require']) && (int) $field['require'] === 1) {
							$fieldAttrA['data-required'] = 'true';
							$fieldAttrA['data-label'] = ( isset($field['label']) ? sanitize_text_field( $field['label'] ) : '' );
						}
						foreach ($fieldAttrA as $attrKey => $attr){
							$fieldAttr .= $attrKey.'="'.$attr.'" ';
						}
						?>
                        <div class="xtpb-form-field-repeat repeater-<?php echo $fieldIndex;?>">
                            <div class="xtpb-form-field-wrap">
								<?php if ( isset($field['label']) && $field['label'] && isset($settings['label_show']) && $settings['label_show'] ) { ?>
									<label class="xtpb-form-field-label"><?php echo esc_html( $field['label'] ); ?><?php if( (isset($field['require']) && $field['require']) == 1) { echo "<span class='require-sign'>*</span>";}?></label>
								<?php }?>
								<?php if(isset($field['field_type']) && $field['field_type'] ){ ?>
									<?php include XTPB_DIR_PATH."addons/form/fields/{$field['field_type']}.php"; ?>
								<?php } ?>
                            </div>
                        </div>
						<?php
					}
					?>
                </div>

				<?php if ($enableSimpleRecaptcha){ ?>
                    <div class="xtpb-custom-recaptcha">
                        <span class="xtpb-form-recaptcha-input"><input type="text" name="xtpb_default_form[xtpb_form_recaptcha_answer]" data-required="true" placeholder="10+5 = ?" data-label="<?php esc_attr_e("XTPB 简易验证码", 'xintheme-pagebuilder'); ?>"/></span>
                    </div>
				<?php } ?>

				<?php
				if ($enableGoogleRecaptcha){
					$google_recaptcha_site_key = isset($settings['google_recaptcha_site_key']) ? $settings['google_recaptcha_site_key'] : '';
					?>
                    <div class="xtpb-form-google-recaptcha google_recaptcha_wrap">
                        <div class="g-recaptcha" data-sitekey="<?php echo esc_attr( $google_recaptcha_site_key ); ?>"></div>
                    </div>
					<?php
				} ?>

				<?php
				if($icon_position == 'left') {
					$button = (esc_attr($icon_list)) ? '<i class="' . esc_attr($icon_list) . '"></i> <span>' . esc_attr($button_text).'</span>' : '<span>' . esc_attr($button_text). '</span>';
				} else {
					$button = (esc_attr($icon_list)) ? '<span>' . esc_attr($button_text) . '</span> <i class="' . esc_attr($icon_list) . '"></i><span>' : esc_attr($button_text).'</span>';
				}
				?>

                <div class="xtpb_form_addon_submit_btn_wrap xtpb-form-btn-wrap">
                    <button class="xtpb-btn-forms xtpb_form_addon_submit <?php echo esc_attr($classlist); ?>" type="submit" data-success-text="<?php echo esc_attr( $button_success_text ); ?>"> <?php echo $button; ?> </button>
                </div>

                <div class="xtpb_form_response"></div>
            </form>

			<?php
		}

		$htmlForm .= ob_get_clean();

		return $htmlForm;
	}

	/**
	 * Process Form when user submit
	 */
	public function xtpb_form_process(){
		$this->check_wp_nonce();

		//Getting basic form information
		$formAddonData = sanitize_text_field(stripslashes($_POST['xtpb_form_data']));
		$formAddonData = json_decode($formAddonData, true);

		if ( ! isset($formAddonData['addon_id']) || ! isset($formAddonData['page_id'])){
			wp_send_json_error( __('表单数据错误', 'xintheme-pagebuilder') );
		}

		$addon_id = (int) sanitize_text_field($formAddonData['addon_id']);
		$page_id = (int) sanitize_text_field($formAddonData['page_id']);

		//Getting addon settings from database
		$formData = xtpb_get_saved_addon_settings($addon_id, $page_id);

		//Getting only settings from form
		$formSettings = $formData['settings'];
		unset($formSettings['xtpb_default_form'], $formSettings['addon_background']);

		//Response variable

		$responseData = array();
		$responseData['enable_redirect_url'] = false;
		//$responseData['msg'] = __('发生错误，请稍后重试', 'xintheme-pagebuilder');


		//Checking Recaptcha if exists?
		$enableSimpleRecaptcha = false;
		if ( ! empty($formSettings['enable_simple_recaptcha'])){
			$enableSimpleRecaptcha = (bool) $formSettings['enable_simple_recaptcha'];
		}

		if ($enableSimpleRecaptcha){
			//$recaptchaAnswer = (string)  ! empty($formSettings['xtpb_form_recaptcha_answer']) ?
            // $formSettings['xtpb_form_recaptcha_answer'] : '';
			$usersAnswere = (string) ! empty($_POST['xtpb_default_form']['xtpb_form_recaptcha_answer']) ? sanitize_text_field($_POST['xtpb_default_form']['xtpb_form_recaptcha_answer']) : '';

			if (15 != $usersAnswere){
				$responseData['msg'] = __('验证码答案不匹配', 'xintheme-pagebuilder');
				wp_send_json_error($responseData);
			}
		}

		$enableGoogleRecaptcha = false;
		if ( ! empty($formSettings['enable_google_recaptcha'])){
			$enableGoogleRecaptcha = (bool) $formSettings['enable_google_recaptcha'];
		}
		if ($enableGoogleRecaptcha){
			$g_captcha_secret_key = isset($formSettings['google_recaptcha_secret_key']) ? $formSettings['google_recaptcha_secret_key'] : '';
			$this->googleReCaptchaVerify($g_captcha_secret_key);
		}

		$formField = array();

		if ( ! empty($formData['settings']['xtpb_default_form'])){
			$formField = $formData['settings']['xtpb_default_form'];
		}

		$submitedRowData = array();
		if (isset($_POST['xtpb_default_form'])){
			$submitedRowData = $_POST['xtpb_default_form'];
		}

		//Getting only fields from form
		$submittedFormData = array();
		foreach ($submitedRowData as $key => $rowData){
			if ( ! isset( $formField[$key] ) ) {
				continue;
			}
			$field = $formField[$key];
			$field['submitted_data'] = $rowData;

			$submittedFormData[] = $field;
		}

		$source_url = ! empty( $formAddonData['source_url'] ) ? esc_url_raw( $formAddonData['source_url'] ) : '';
		$source_url = $this->normalize_source_url( $source_url, $page_id );

		if ( class_exists( 'XTPB_Form_Records' ) ) {
			XTPB_Form_Records::insert_entry( $addon_id, $page_id, $submittedFormData, $formSettings, $source_url );
		}

		$submitter_ip = class_exists( 'XTPB_Form_Records' ) ? XTPB_Form_Records::get_client_ip() : $this->getIP();
		$source_page_title = $page_id ? get_the_title( $page_id ) : '';
		$source_page_url = $source_url ? $source_url : ( $page_id ? get_permalink( $page_id ) : '' );

		//Get Email Template and parse IT
		$email_format = ! empty($formSettings['email_format']) ? $formSettings['email_format'] : null;
		if ($email_format){
			$messageRows = array();
			foreach ($submittedFormData as $submittedData){
				$submittedValue = '';
				if (isset($submittedData['submitted_data'])){
					if (is_array($submittedData['submitted_data'])){
						$submittedValue = implode('<br />', $submittedData['submitted_data']);
					}else{
						$submittedValue = $submittedData['submitted_data'];
					}
				}
				$submittedValue = '' !== trim( wp_strip_all_tags( (string) $submittedValue ) ) ? $submittedValue : '-';

				if (! empty($submittedData['label'])){
					$messageRows[] = '<strong>' . esc_html($submittedData['label']) . '：</strong>' . wp_kses_post($submittedValue);
				}
			}
			$email_format = str_replace('{Message}', implode('<br />', $messageRows), $email_format);
		}


		$toEmail = ! empty($formSettings['xtpb_default_form_to_email']) ? sanitize_email( $formSettings['xtpb_default_form_to_email'] ) : '';
		$fromEmail = ! empty($formSettings['xtpb_default_form_from_email']) ? sanitize_email( $formSettings['xtpb_default_form_from_email'] ) : '';
		$subject = ! empty($formSettings['xtpb_default_form_subject']) ? sanitize_text_field( $formSettings['xtpb_default_form_subject'] ) : '';

		$date = date(get_option('date_format'));
		$time = date(get_option('time_format'));
		$subjectVariable = array(
            '{date}' => $date,
            '{time}' => $time,
            '{datetime}' => $date.' '.$time
		);
		$subject = str_replace(array_keys($subjectVariable), array_values($subjectVariable), $subject);

		$email_custom_message = $email_format;

		//Getting E-Mail Template
		ob_start();
		include XTPB_DIR_PATH."addons/form/email-template/default.php";
		$emailHtml = ob_get_clean();
		$htmlEmail = apply_filters('xtpb_form', $emailHtml, $submittedFormData);

		//Setting Mail Headers
		$headers = array('Content-Type: text/html; charset=UTF-8');
		//Send E-Mail Now or through error msg
		try{
			$mail_error = null;
			$mail_error_handler = function( $error ) use ( &$mail_error ) {
				if ( is_wp_error( $error ) ) {
					$mail_error = $error;
				}
			};
			add_action( 'wp_mail_failed', $mail_error_handler );

			$isMail = wp_mail($toEmail, $subject, $htmlEmail, $headers );
			remove_action( 'wp_mail_failed', $mail_error_handler );

			if ( $isMail ) {
				if ( ! empty($formSettings['success_message'])){
					$responseData['msg'] = $formSettings['success_message'];
				}

				if ( ! empty($formSettings['enable_redirect_url'])){
					$responseData['enable_redirect_url'] = (bool) $formSettings['enable_redirect_url'];
					$responseData['redirect_url'] = $formSettings['redirect_url'];
				}
				wp_send_json_success($responseData);
			} else {
				$responseData['msg'] = __('邮件发送失败，请检查邮箱或 SMTP 设置。', 'xintheme-pagebuilder');
				if ( is_wp_error( $mail_error ) ) {
					$responseData['errcode'] = $mail_error->get_error_code();
					$responseData['errmsg'] = $mail_error->get_error_message();
					$responseData['msg'] = $mail_error->get_error_message();
				}
				wp_send_json_error( $responseData );
			}
		}catch (\Exception $e){
			if ( isset( $mail_error_handler ) ) {
				remove_action( 'wp_mail_failed', $mail_error_handler );
			}
			$responseData['msg'] = $e->getMessage();
			wp_send_json_error($responseData);
		}
		wp_send_json_error($responseData);
	}

	/**
	 * Check nonce
	 */
	public function check_wp_nonce(){
		if (
			! isset( $_POST['xtpb_form_nonce_field'] )
			|| ! wp_verify_nonce( $_POST['xtpb_form_nonce_field'], 'xtpb_form_submit_action' )
		) {
			exit;
		}
	}

	public function googleReCaptchaVerify($recapcha_secret_key = ''){
		$response_msg = array();
		$response_msg['msg'] = __('发生错误，请稍后重试', 'xintheme-pagebuilder');

		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$g_response = isset($_POST['g-recaptcha-response']) ? sanitize_text_field($_POST['g-recaptcha-response']) : '';

		if ( ! $g_response){
			$response_msg['msg'] = __('请先完成 reCAPTCHA 验证', 'xintheme-pagebuilder');
			wp_send_json_error($response_msg);
		}

		$params = array(
			'secret' => $recapcha_secret_key,
			'response' => $g_response,
			'remoteip' => $this->getIP(),
		);
		$response = wp_remote_post( $url, array( 'body' => $params ) );

		if (is_wp_error($response)){
			wp_send_json_error(array('msg' => $response->get_error_messages()));
		}

		$res = json_decode($response['body'], true);

		if ($res['success']){
			return true;
		}else{
			$response_msg['msg'] = implode(', ', $res['error-codes']);
		}

		wp_send_json_error($response_msg);
	}

	public function getIP(){
		$ip = false;
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}


	// Form
	public function render($data = null){
		$settings 				= $data['settings'];
		$textarea_resize 		= isset($settings["textarea_resize"]) ? $settings["textarea_resize"] : '';

		$output = '';

		$output .= '<div class="xtpb-global-form-content xtpb-textarea-disbale-'.$textarea_resize.'">';
		$output .= $this->generateDefaultForm($data);
		$output .= '</div>';

		return $output;
	}

	public function getTemplate(){
		$output = '
		<# var classList = " xtpb-btn-"+data.fullwidth_button; #>
		<div class="xtpb-form-content">
                <# if(data.xtpb_default_form) { #>
                    <div class="xtpb-form-field-item-wrap">
                        <# __.forEach(data.xtpb_default_form, function(field, key){ #>
                            <div class="xtpb-form-field-repeat repeater-{{key}}">
                                <div class="xtpb-form-field-wrap">
                                    <# if( data.label_show == 1 ) { #>
                                    <# if(field.label) { #>
                                        <label class="xtpb-form-field-label">
                                            {{field.label}} {{{ (field.require == 1) ? "<span class=\"require-sign\">*</span>" : "" }}}
                                        </label>
                                    <# } #>
                                    <# } #>
                                    
                                    <# switch(field.field_type){ case "text" : #>
                                        <span class="xtpb-form-field-item xtpb-form-field-input">
                                            <input type="text" <# if(field.placeholder) { #> placeholder="{{field.placeholder}}" <# } #> />
                                        </span>	
                                    <# break; #>

                                    <# case "email" :  #>
                                        <span class="xtpb-form-field-item xtpb-form-field-email">
                                            <input type="email" <# if(field.placeholder) { #> placeholder="{{field.placeholder}}" <# } #> />
                                        </span>	
                                    <# break; #>

                                    <# case "textarea" :  #>
                                        <span class="xtpb-form-field-item xtpb-form-field-textarea xtpb-textarea-disbale-{{data.textarea_resize}}">
                                            <textarea <# if(field.placeholder) { #> placeholder="{{field.placeholder}}" <# } #> ></textarea>
                                        </span>
                                    <# break; #>

                                    <# case "radio" : 
                                    if(field.radio_field_options){ __.forEach(field.radio_field_options, function(radioField){ #>
                                        <span class="xtpb-form-field-item xtpb-form-field-radio"><input type="radio" value="{{radioField.label}}" /> <label for="{{radioField.label}}">{{radioField.label}}</label></span>
                                        <# });
                                    } break; #>
                                    
                                    <# case "checkbox" : 
                                    if(field.checkbox_field_options){ __.forEach(field.checkbox_field_options, function(checkBoxField){ #>
                                        <span class="xtpb-form-field-item xtpb-form-field-checkbox"><input type="checkbox" value="{{checkBoxField.label}}" /> <label for="{{checkBoxField.label}}"> {{checkBoxField.label}} </label></span>
                                    <# }); 
                                    } break; #>

                                    <# case "select" : #>
                                        <span class="xtpb-form-field-item xtpb-form-field-select">
                                        <select>
                                            <# __.forEach(field.select_options, function(selectOption){ #>
                                            <option value="{{selectOption.label}}">{{selectOption.label}}</option>
                                            <# }); #>
                                        </select>
                                        </span>
                                    <# break; #>
                                    <# } #>
                                </div>
                            </div>
                        <# }); #>
                    </div>
                        
                    <# if(data.enable_simple_recaptcha == 1){ #>
                        <div class="xtpb-custom-recaptcha">
                            <span class="xtpb-form-recaptcha-input"><input type="text" placeholder="10+5 = ?" name="xtpb_default_form[xtpb_form_recaptcha_answer]" /></span>
                        </div>
                    <# } #>

                    <# if(data.enable_google_recaptcha == 1){ #>
                        <div class="xtpb-form-google-recaptcha google_recaptcha_wrap">
                            <img src="{{xtpb_form.form_addon_dir_url}}/assets/img/recaptcha-img.png" />
                        </div>
                        <# } #>
                        <div class="xtpb-form-btn-wrap">
                            <button class="xtpb-btn-forms {{classList}}" type="button"><# if(data.icon_position == "left" && !_.isEmpty(data.icon_list)) { #><i class="{{ data.icon_list }}"></i> <# } #><span>{{ data.button_text ? data.button_text : "提交表单" }}</span><# if(data.icon_position == "right" && !_.isEmpty(data.icon_list)) { #> <i class="{{ data.icon_list }}"></i><# } #></button>
                        </div>
                    <# } #>		

            </div>
		';
		return $output;
	}

	private function get_current_source_url( $page_id = 0 ) {
		$page_id = absint( $page_id );
		$url = $page_id ? get_permalink( $page_id ) : home_url( '/' );

		if ( ! empty( $_GET ) ) {
			$query_args = array();
			foreach ( wp_unslash( $_GET ) as $key => $value ) {
				if ( is_scalar( $value ) ) {
					$query_args[ sanitize_key( $key ) ] = sanitize_text_field( $value );
				}
			}

			if ( ! empty( $query_args ) ) {
				$url = add_query_arg( $query_args, $url );
			}
		}

		return esc_url_raw( $url );
	}

	private function normalize_source_url( $source_url = '', $page_id = 0 ) {
		$page_id = absint( $page_id );

		if ( ! empty( $source_url ) ) {
			$source_url = esc_url_raw( $source_url );
			$path = wp_parse_url( $source_url, PHP_URL_PATH );
			$query = wp_parse_url( $source_url, PHP_URL_QUERY );

			if ( $page_id && ( '' === (string) $path || '/' === $path ) && ! empty( $query ) ) {
				return esc_url_raw( get_permalink( $page_id ) . '?' . $query );
			}

			return $source_url;
		}

		return $this->get_current_source_url( $page_id );
	}
}
