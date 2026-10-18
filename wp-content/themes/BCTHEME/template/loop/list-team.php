<?php

/**
 * Template: 团队 列表样式
 * @Author: 大胡子
 * @Email:  dahuzi@xintheme.com
 * @Link:   www.xintheme.com
 * @Date:   2022-08-05 17:29:40
 * @Last Modified by:   dahuzi
 * @Last Modified time: 2024-11-12 14:39:12
 */

//成员信息
$team_position = get_post_meta(get_the_ID(), 'team_position', true);
$team_contact = get_post_meta(get_the_ID(), 'team_contact', true);

//获取文章缩略图
$get_post_thumbnail_url = wpjam_get_post_thumbnail_url();

?>
<div class="item">
	<div class="img">
		<?php
		//缩略图
		if(!empty($get_post_thumbnail_url)){
			echo '<a href="'.get_the_permalink().'">';
			echo '<img loading="lazy" src="'.$get_post_thumbnail_url.'" alt="'.get_the_title().'">';
			echo '</a>';
		}

		//联系方式
		if( !empty($team_contact) ){
			echo '<div class="social-icons">';
			$team_contact = array_filter($team_contact, function($team_contact){

				if( $team_contact['team_contact_type'] == 'link' ){
					//跳转链接
					echo '<a href="' . esc_url($team_contact['team_contact_url'] ?? '#') . '" target="_blank" rel="nofollow">';
					echo $team_contact['team_contact_icon'] ?? '';
					echo '</a>';
				}elseif( $team_contact['team_contact_type'] == 'img' ){
					//弹出图片
					$img_id = substr(uniqid(mt_rand(), true), 0, 6);
					echo '<a data-fancybox href="#a'.$img_id.'">';
					echo $team_contact['team_contact_icon'] ?? '';
					echo '</a>';

					echo '<div id="a'.$img_id.'" style="display:none;">';
					echo '<img loading="lazy" src="'.$team_contact['team_contact_img'].'" alt="">';
					echo '</div>';
				}elseif( $team_contact['team_contact_type'] == 'form' ){                            
					//弹出表单
					$fancybox_id = substr(uniqid(mt_rand(), true), 0, 6);
					echo '<a data-fancybox href="#a'.$fancybox_id.'">';
					echo $team_contact['team_contact_icon'] ?? '';
					echo '</a>';

					echo '<div id="a'.$fancybox_id.'" class="fancybox-form" style="display:none;">';
					$form_title = $team_contact['team_contact_form_title'];//表单标题
					$form_id = $team_contact['team_contact_form'];//表单ID
					wpjam_render_form($form_id, $form_title);
					echo '</div>';
				}elseif( $team_contact['team_contact_type'] == 'copy' ){
					//一键复制
					$copy_id = substr(uniqid(mt_rand(), true), 0, 6);
					$data_settings    = wp_json_encode([
						'id'            => 's'.$copy_id,
						'copy'          => $team_contact['team_contact_copy'],
						'copy_tip'      => str_replace(' ', '&nbsp;', $team_contact['team_contact_copy_tip']), // 将空格替换为 &nbsp;
						'open_wechat'   => $team_contact['team_contact_open_wechat'] ?? '',
					]);
					// 将 JSON 数据转义为 HTML 属性
					$data_settings = esc_attr($data_settings);

					echo '<a href="javaScript:;" id="s'.$copy_id.'" class="copy-btn" data-clipboard-text="'.$team_contact['team_contact_copy'].'" data-settings="'.$data_settings.'">';
					echo $team_contact['team_contact_icon'] ?? '';
					echo '</a>';
				}elseif( $team_contact['team_contact_type'] == 'phone' ){
					//拨打电话
					echo '<a href="tel:'.$team_contact['team_contact_phone'].'">';
					echo $team_contact['team_contact_icon'] ?? '';
					echo '</a>';
				}

			});
			echo '</div>';
		}
		?>
	</div>
	<div class="info">
		<h2>
			<a href="<?php the_permalink(); ?>"><?php the_title();?></a>
		</h2>
		<?php
		if(!empty($team_position)){
			echo '<p>'.$team_position.'</p>';
		}?>
	</div>
</div>