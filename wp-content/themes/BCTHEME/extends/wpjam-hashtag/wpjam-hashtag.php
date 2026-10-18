<?php
/*
Plugin Name: WPJAM 内部链接
Plugin URI: http://blog.wpjam.com/project/wpjam-hashtag/
Description: 内部链接插件将文章内容中 <strong>#话题标签#</strong> 格式的文本自动转换成内部链接，支持预定义的内部链接关键字，标签和分类关键字，也支持自定义关键字和自动标签内链（需开启）。
Requires Plugins: wpjam-basic
Version: 6.5
Requires at least: 6.2
Tested up to: 6.4
Requires PHP: 7.4
Author: Denis
Author URI: http://blog.wpjam.com/
Update URI: http://blog.wpjam.com/project/wpjam-hashtag/
*/
class WPJAM_Hashtag{
	public static function __callStatic($method, $args){
		$handler	= wpjam_get_handler([
			'unique_key'	=> 'keyword',
			'unique_title'	=> '关键字',
			'option_name'	=> 'wpjam-links',
			'items_field'	=> 'links'
		]);

		return wpjam_call_handler($handler, $method, ...$args);
	}

	public static function get_fields($action_key='', $item_id=0){
		if($action_key == 'set'){
			$options	= [
				''		=> ['label'=>'仅限前后有#的文本',		'description'=>'效率更高，但是需要在文本左右加#'],
				'auto'	=> ['label'=>'所有(包含前后有#的)文本',	'description'=>'效率较低，但是无需在文本左右加#',	'fields'=>['tag_internal'=>['title'=>'标签内链', 'type'=>'checkbox', 'label'=>'文章内容中标签自动添加链接']]]
			];

			return [
				'internal_type'	=> ['title'=>'匹配方式',	'type'=>'select',	'options'=>$options],
				'hashtag'		=> ['title'=>'链接前后',	'type'=>'fieldset',	'fields'=>[
					'hashtag'			=> ['type'=>'view',	'value'=>'以下设置仅适用于前后有#的文本'],
					'link_hashtag'		=> ['title'=>'内部链接',	'type'=>'text',	'class'=>'',	'value'=>'#'],
					'tag_hashtag'		=> ['title'=>'分类标签',	'type'=>'text',	'class'=>'',	'value'=>'#'],
					'search_hashtag'	=> ['title'=>'搜索结果',	'type'=>'text',	'class'=>'',	'value'=>'#'],
				]],
			];
		}

		return [
			'keyword'	=> ['title'=>'关键字',	'type'=>'text',	'show_admin_column'=>true,	'class'=>'all-options'],
			'link'		=> ['title'=>'链接',		'type'=>'url',	'show_admin_column'=>true],
		];
	}

	public static function get_actions(){
		return [
			'add'		=> ['title'=>'新建',	'dismiss'=>true],
			'edit'		=> ['title'=>'编辑'],
			'delete'	=> ['title'=>'删除',	'direct'=>true, 	'confirm'=>true,	'bulk'=>true],
			'set'		=> ['title'=>'设置',	'overall'=>true,	'class'=>'button-primary',	'value_callback'=>[self::class, 'get_setting'], 'callback'=>[self::class, 'update_setting']]
		];
	}

	public static function get_list_table(){
		return [
			'plural'	=> 'hashtags',
			'singular'	=> 'hashtag',
			'model'		=> self::class
		];
	}

	protected static function hashtg_replace($content){
		if(preg_match_all('/#([\w\s\-\_\.]+?)#/u', $content, $matches)){
			$search		= array_values(array_unique($matches[0]));
			$replace	= $keywords	= array_values(array_unique($matches[1]));
			$links		= self::parse_items();
			$parser 	= function($keyword, $type=''){
				if($type == 'search'){			
					$link	= get_search_link($keyword);
				}elseif($type == 'tag'){
					[$link, $keyword]	= [get_term_link($keyword), $keyword->name];
				}else{
					[$link, $type]	= [$type, 'link'];
				}

				$hashtag	= self::get_setting($type.'_hashtag', '#');
				$keyword	= $hashtag.$keyword.$hashtag;

				return wpjam_tag('a', ['href'=>$link, 'class'=>['hashtag', $type.'-hashtag']], $keyword);
			};

			if($links){
				foreach($keywords as $i => $keyword){
					foreach($links as $link){
						if(strnatcasecmp($link['keyword'], $keyword) === 0){
							$replace[$i]	= $parser($keyword, $link['link']);

							unset($keywords[$i]); break;
						}
					}
				}
			}

			$terms	= $keywords ? get_terms(['name'=>$keywords]) : [];

			if($terms){
				foreach($keywords as $i => $keyword){
					foreach($terms as $term){
						if(get_taxonomy($term->taxonomy) && strnatcasecmp($term->name, $keyword) === 0){
							$replace[$i]	= $parser($term, 'tag');

							unset($keywords[$i]); break;
						}
					}
				}
			}

			if($keywords){
				$replace	= array_map(fn($keyword) => is_object($keyword) ? $keyword : $parser($keyword, 'search'), $replace);
			}

			return str_replace($search, $replace, $content);
		}

		return $content;
	}

	protected static function auto_replace($content){
		$links	= self::parse_items();
		$links	= $links ? wpjam_sort(array_column($links, 'link', 'keyword'), 'strlen', 'key') : [];

		if(self::get_setting('tag_internal')){
			$terms	= wpjam_array(get_terms(['taxonomy'=>'post_tag']), fn($k, $term)=> [$term->name, get_term_link($term)]);
			$links	+= $terms ? wpjam_sort($terms, 'strlen', 'key') : [];
		}

		if(!$links){
			return $content;
		}

		$pattern	= '/('.implode('|', array_map(fn($keyword)=> preg_quote($keyword, '/'), array_keys($links))).')/u';
		$ignores	= ['a', 'pre', 'code'];
		$ignore		= '';
		$pieces		= wp_html_split($content);
		$replaced	= function($keyword){
			static $replaced = [];
		
			if(in_array($keyword, $replaced)){
				return true;
			}

			$replaced[]	= $keyword;

			return false;
		};

		foreach($pieces as &$piece){
			if(!$ignore){
				if(str_starts_with($piece, '<')){
					$ignore	= wpjam_find($ignores, fn($tag)=> str_starts_with($piece, '<'.$tag.' '));
				}elseif(trim($piece)){
					$piece	= preg_replace_callback($pattern, fn($m)=> $replaced($m[1]) ? $m[1] : '<a href="'.$links[$m[1]].'">'.$m[1].'</a>', $piece);
				}
			}else{
				if(str_starts_with($piece, '</') && wpjam_find($ignores, fn($tag)=> $ignore == $tag && str_starts_with($piece, '</'.$tag.'>'))){
					$ignore	= '';
				}
			}
		}

		return implode('', $pieces);
	}

	public static function filter_content($content){
		if(doing_filter('get_the_excerpt') || !is_singular() || get_the_ID() != get_queried_object_id()){ 
			return $content;
		}

		$content	= self::hashtg_replace($content);

		if(self::get_setting('internal_type') == 'auto'){
			$content	= self::auto_replace($content);
		}

		return $content;
	}
}

add_action('plugins_loaded', function(){
	if(!did_action('wpjam_loaded')){
		return;
	}

	wpjam_add_menu_page('hashtag', [
		'plugin_page'	=> 'wpjam-links',
		'title'			=> '内部链接',
		'summary'		=> __FILE__,
		'function'		=> 'list',
		'hooks'			=> ['the_content', ['WPJAM_Hashtag', 'filter_content'], 2],
		'list_table'	=> 'WPJAM_Hashtag'
	]);
});
