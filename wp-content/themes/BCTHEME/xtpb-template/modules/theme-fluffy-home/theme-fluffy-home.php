<?php
if ( ! defined('ABSPATH') ) { exit; }
/** Fluffy homepage modules. PHP rendering is also used by the live editor (AJAX). */
abstract class Theme_Fluffy_Module {
    protected $kind;
    public function get_name() { return 'theme_fluffy_'.$this->kind; }
    public function get_title() { $names=array('menu'=>'精选菜单','gallery'=>'照片画廊','philosophy'=>'品牌理念','press'=>'媒体与口碑','visit'=>'订位与到店'); return 'Fluffy · '.$names[$this->kind]; }
    public function get_category_name() { return 'Fluffy Brunch'; }
    public function get_addon_class() { return 'fluffy-module'; }
    public function get_css_files() { return array(__DIR__.'/frontend.css'); }
    public function get_js_files() { return array(__DIR__.'/frontend.js'); }
    public function get_thumb() { return get_template_directory_uri().'/xtpb-template/modules/theme-fluffy-home/preview.svg'; }
    public function defaults() { static $data; if (!$data) { $data=json_decode(file_get_contents(__DIR__.'/defaults.json'),true); } return $data[$this->kind] ?? array(); }
    private function field($key,$value) {
        $labels=array('eyebrow'=>'小标题','title'=>'标题','intro'=>'简介','button'=>'按钮文字','url'=>'链接','image'=>'图片','text'=>'说明','price'=>'价格','category'=>'菜单分类','quote'=>'品牌引言','caption'=>'图片说明','value'=>'数字或媒体名称','brand'=>'品牌名称','tagline'=>'品牌副标题','address'=>'地址','hours'=>'营业时间','phone'=>'电话','email'=>'邮箱','map'=>'地图链接','map_label'=>'地图按钮文字','footer_text'=>'页脚品牌介绍','copyright'=>'版权文字');
        $type=in_array($key,array('url','map'),true)?'link':($key==='image'?'media':(in_array($key,array('intro','text','quote','footer_text'),true)?'textarea':'text'));
        return array('type'=>$type,'title'=>$labels[$key]??$key,'std'=>$value,'section'=>'内容设置');
    }
    public function get_settings() {
        $fields=array();
        foreach($this->defaults() as $key=>$value) {
            if(in_array($key,array('items','links'),true)) {
                $attrs=array(); foreach(($value[0]??array()) as $k=>$v) { $attrs[$k]=$this->field($k,$k==='image'?array('url'=>''):($k==='url'?array('link'=>''):'')); unset($attrs[$k]['section']); }
                $fields[$key]=array('type'=>'repeatable','title'=>$key==='links'?'导航链接':'内容项目','label'=>'title','attr'=>$attrs,'std'=>$value,'section'=>'内容设置');
            } else { $fields[$key]=$this->field($key,$value); }
        }
        $fields['shape_image']=array('type'=>'media','title'=>'标题装饰图','std'=>array('url'=>get_template_directory_uri().'/xtpb-template/modules/theme-fluffy-home/assets/h-shap.png'),'section'=>'模块设置');
        $fields['anchor']=array('type'=>'text','title'=>'锚点 ID','desc'=>'用于页内导航，同一页面请使用唯一 ID。','std'=>array('menu'=>'menu','gallery'=>'galleria','philosophy'=>'filosofia','press'=>'stampa','visit'=>'visita')[$this->kind],'section'=>'模块设置');
        $fields['accent']=array('type'=>'color','title'=>'强调色','std'=>'#E4C590','tab'=>'style','selector'=>'{{SELECTOR}} .fluffy-block { --fl-accent: {{data.accent}}; }');
        $fields['surface']=array('type'=>'color','title'=>'背景色','std'=>'','tab'=>'style','selector'=>'{{SELECTOR}} .fluffy-block { background-color: {{data.surface}}; }');
        return $fields;
    }
    protected function image($value,$alt='',$class='') {
        $url=is_array($value)?($value['url']??''):'';
        if($url) { echo '<img class="'.esc_attr($class).'" src="'.esc_url($url).'" alt="'.esc_attr($alt).'" loading="lazy" decoding="async">'; }
    }
    protected function button($value,$label,$class='fl-button') {
        $v=is_array($value)?$value:array('link'=>$value); $url=$v['link']??'';
        if(!$label||!$url){return;}
        $rel=array();if(!empty($v['window'])){$rel[]='noopener';$rel[]='noreferrer';}if(!empty($v['nofolow'])||!empty($v['nofollow'])){$rel[]='nofollow';}
        echo '<a class="'.esc_attr($class).'" href="'.esc_url($url).'"'.(!empty($v['window'])?' target="_blank"':'').($rel?' rel="'.esc_attr(implode(' ',$rel)).'"':'').'>'.esc_html($label).'</a>';
    }
    protected function heading($s) { ?>
        <div class="fl-heading"><p class="fl-eyebrow"><?php echo esc_html($s['eyebrow']??''); ?></p><?php $this->image($s['shape_image']??array(),'', 'fl-heading-shape'); ?><h2><?php echo esc_html($s['title']??''); ?></h2><?php if(!empty($s['intro'])): ?><p class="fl-intro"><?php echo esc_html($s['intro']); ?></p><?php endif; ?></div>
    <?php }
    public function render($data=null) {
        $s=array_merge(array('shape_image'=>array('url'=>get_template_directory_uri().'/xtpb-template/modules/theme-fluffy-home/assets/h-shap.png')),$this->defaults(),is_array($data['settings']??null)?$data['settings']:array());
        $items=is_array($s['items']??null)?$s['items']:array();
        $anchor=sanitize_title($s['anchor']??'');
        $textures=array('menu'=>'bg2.jpg','gallery'=>'bg4.jpg','philosophy'=>'bg3.jpg','press'=>'bg8.jpg','visit'=>'bg3.jpg');
        $texture=get_template_directory_uri().'/xtpb-template/modules/theme-fluffy-home/assets/'.$textures[$this->kind];
        ob_start(); ?>
        <div class="fluffy-block fl-<?php echo esc_attr($this->kind); ?>" style="--fl-texture:url(<?php echo esc_url($texture); ?>)"<?php if($anchor){echo ' id="'.esc_attr($anchor).'"';} ?>>
        <?php if($this->kind==='menu'): ?>
            <section class="fl-container fl-space"><?php $this->heading($s); ?>
            <?php $groups=array(); foreach($items as $item){if(is_array($item)){$groups[$item['category']??'Menu'][]=$item;}} ?>
            <div class="fl-menu-filters" role="group" aria-label="Categorie del menu"><button type="button" data-fl-filter="all" aria-pressed="true">Tutti</button><?php foreach(array_keys($groups) as $category): ?><button type="button" data-fl-filter="<?php echo esc_attr($category); ?>" aria-pressed="false"><?php echo esc_html($category); ?></button><?php endforeach; ?></div>
            <div class="fl-menu-grid"><?php foreach($groups as $category=>$dishes): ?>
                <article class="fl-menu-card" data-fl-category="<?php echo esc_attr($category); ?>"><div class="fl-menu-photo"><?php foreach($dishes as $dish){if(!empty($dish['image']['url'])){$this->image($dish['image'],$category);break;}} ?></div><div class="fl-menu-body"><h3><?php echo esc_html($category); ?></h3>
                <?php foreach($dishes as $dish): ?><div class="fl-dish"><div><h4><?php echo esc_html($dish['title']??''); ?></h4><span><?php echo esc_html($dish['price']??''); ?></span></div><p><?php echo esc_html($dish['text']??''); ?></p></div><?php endforeach; ?></div></article>
            <?php endforeach; ?></div><div class="fl-center"><?php $this->button($s['url'],$s['button']); ?></div></section>
        <?php elseif($this->kind==='gallery'): ?>
            <section class="fl-container fl-space"><div class="fl-heading-row"><?php $this->heading($s); ?><?php $this->button($s['url'],$s['button'],'fl-text-link'); ?></div>
            <div class="fl-gallery-grid"><?php foreach(array_slice($items,0,6) as $item): ?><a href="<?php echo esc_url($item['image']['url']??''); ?>" class="fl-gallery-item" data-fl-lightbox aria-label="<?php echo esc_attr($item['title']??''); ?>"><?php $this->image($item['image']??array(),$item['title']??''); ?><span><?php echo esc_html($item['title']??''); ?> <b aria-hidden="true">↗</b></span></a><?php endforeach; ?></div>
            <?php if(count($items)>6): ?><details class="fl-gallery-more"><summary>Altre foto <span aria-hidden="true">＋</span></summary><div class="fl-gallery-grid"><?php foreach(array_slice($items,6) as $item): ?><a href="<?php echo esc_url($item['image']['url']??''); ?>" class="fl-gallery-item" data-fl-lightbox aria-label="<?php echo esc_attr($item['title']??''); ?>"><?php $this->image($item['image']??array(),$item['title']??''); ?><span><?php echo esc_html($item['title']??''); ?></span></a><?php endforeach; ?></div></details><?php endif; ?></section>
        <?php elseif($this->kind==='philosophy'): ?>
            <section class="fl-container fl-space fl-philosophy-grid"><div class="fl-philosophy-photo"><?php $this->image($s['image'],$s['title']); ?><blockquote>“<?php echo esc_html($s['quote']); ?>”<cite>— Fluffy Brunch</cite></blockquote></div><div><?php $this->heading($s); ?><div class="fl-values"><?php foreach($items as $i=>$item): ?><article><span><?php echo esc_html(sprintf('%02d',$i+1)); ?></span><div><h3><?php echo esc_html($item['title']??''); ?></h3><p><?php echo esc_html($item['text']??''); ?></p></div></article><?php endforeach; ?></div></div></section>
        <?php elseif($this->kind==='press'): ?>
            <section class="fl-container fl-space"><?php $this->heading($s); ?><div class="fl-press-grid"><figure><a href="<?php echo esc_url($s['image']['url']??''); ?>" data-fl-lightbox><?php $this->image($s['image'],$s['caption']); ?></a><figcaption><?php echo esc_html($s['caption']); ?></figcaption></figure><div class="fl-proof"><?php foreach($items as $item): ?><article><strong><?php echo esc_html($item['value']??''); ?></strong><div><h3><?php echo esc_html($item['title']??''); ?></h3><p><?php echo esc_html($item['text']??''); ?></p></div></article><?php endforeach; ?></div></div></section>
        <?php elseif($this->kind==='visit'): ?>
            <section class="fl-container fl-space fl-visit-grid"><div><?php $this->heading($s); ?><dl><div><dt>DOVE SIAMO</dt><dd><?php echo esc_html($s['address']); ?></dd></div><div><dt>ORARI</dt><dd><?php echo esc_html($s['hours']); ?></dd></div><div><dt>CONTATTI</dt><dd><a href="<?php echo esc_url('tel:+39'.preg_replace('/[^0-9]/','',$s['phone'])); ?>"><?php echo esc_html($s['phone']); ?></a><br><a href="<?php echo esc_url('mailto:'.sanitize_email($s['email'])); ?>"><?php echo esc_html($s['email']); ?></a></dd></div></dl><div class="fl-actions"><?php $this->button($s['url'],$s['button']); $this->button($s['map'],$s['map_label'],'fl-text-link'); ?></div></div><div class="fl-visit-photo"><?php $this->image($s['image'],'Fluffy Brunch, Venezia'); ?></div></section>
<?php endif; ?></div>
        <?php return ob_get_clean();
    }
}
class Theme_Fluffy_Menu extends Theme_Fluffy_Module { protected $kind='menu'; }
class Theme_Fluffy_Gallery extends Theme_Fluffy_Module { protected $kind='gallery'; }
class Theme_Fluffy_Philosophy extends Theme_Fluffy_Module { protected $kind='philosophy'; }
class Theme_Fluffy_Press extends Theme_Fluffy_Module { protected $kind='press'; }
class Theme_Fluffy_Visit extends Theme_Fluffy_Module { protected $kind='visit'; }
add_filter('xtpb_available_modules',function($modules){return array_merge($modules,array('Theme_Fluffy_Menu','Theme_Fluffy_Gallery','Theme_Fluffy_Philosophy','Theme_Fluffy_Press','Theme_Fluffy_Visit'));});

add_filter('xtpb_add_layout', function($layouts){
    $preview=get_template_directory_uri().'/xtpb-template/modules/theme-fluffy-home/home-preview.jpg';
    $layouts[]=array('name'=>'Fluffy Brunch · 品牌首页','category'=>array('Fluffy Brunch'),'preview'=>$preview,'preview2x'=>$preview,'single'=>array(array('name'=>'Fluffy Brunch 首页','preview'=>$preview,'preview2x'=>$preview,'file'=>get_template_directory().'/xtpb-template/layouts/fluffy-brunch-home.json','liveurl'=>home_url('/fluffy-home/'))));
    return $layouts;
});
