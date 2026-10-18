<?php

/** --------------------------------------------------------------------------------- *
 *  给菜单添加图片
 *  --------------------------------------------------------------------------------- */
add_action( 'wp_nav_menu_item_custom_fields', function($item_id, $item, $depth, $args){
    // 获取正在编辑的菜单id
    $menu_id = isset($_GET['menu']) ? (int)$_GET['menu'] : 0;
	
	// 获取所有菜单位置及其对应的菜单ID
	$locations = get_nav_menu_locations();
	// 获取 'primary' 位置所绑定的菜单ID
	$target_menu_id = $locations['primary'] ?? 0;

    // 仅对 primary 菜单生效 且菜单类型为img
	$menu_giant = get_post_meta($item->menu_item_parent, 'menu_giant', true);
	//echo wpjam_print_r( $item );

    if ( ($menu_id === $target_menu_id && $item->menu_item_parent != 0) && $menu_giant == 'img') {
        $icon = get_post_meta( $item_id, '_menu_item_icon', true );
        ?>
        <div class="field-subtitle_menu_meta description-wide" style="margin-bottom: 10px">
            <label for="edit-menu-item-title-<?php echo $item_id; ?>">
                <!-- 图片<br> -->
                <?php echo wpjam_field(['key'=>'menu-item-attr-icon-'.$item_id, 'name'=>'menu-item-attr-icon['.$item_id.']', 'value'=>$icon, 'type'=>'img', 'button_text' => '上传图片', 'item_type'=>'url', 'style' => 'display:block']); ?>
            </label>
        </div>
        <?php
    }
}, 10, 4);

add_action( 'wp_update_nav_menu_item', function($menu_id, $menu_item_db_id, $args ){
    // 获取正在编辑的菜单id
    $menu_id = isset($_GET['menu']) ? (int)$_GET['menu'] : 0;

    // 获取所有菜单位置及其对应的菜单ID
	$locations = get_nav_menu_locations();
	// 获取 'primary' 位置所绑定的菜单ID
	$target_menu_id = $locations['primary'] ?? 0;

    // 仅对 primary 菜单生效
    if ($menu_id === $target_menu_id) {
        $url = $_POST['menu-item-attr-icon'][$menu_item_db_id] ?? null;
        if ( $url ) {
            update_post_meta( $menu_item_db_id, '_menu_item_icon', $url );
        } else {
            delete_post_meta( $menu_item_db_id, '_menu_item_icon' );
        }
    }
}, 10, 3);

/** --------------------------------------------------------------------------------- *
 *  给菜单添加图片
 *  --------------------------------------------------------------------------------- */
function dahuzi_menu_icons($items, $args) {

	//手机端不使用菜单扩展项
	if(wp_is_mobile()){
		return $items;
	}

	// 初始化一个数组来存储需要添加类的一级菜单项 ID
	$parent_ids = array();
	$parent_big = array();

	//echo wpjam_print_r( $args );
	// 获取所有菜单位置及其对应的菜单ID
	$locations = get_nav_menu_locations();
	// 获取 'primary' 位置所绑定的菜单ID
	$target_menu_id = $locations['primary'] ?? 0;

	// 检查当前菜单是否为目标菜单ID
	if (isset($args->menu->term_id) && $args->menu->term_id == $target_menu_id) {
		foreach ($items as $item) {

			// 获取菜单项的ID
			$menu_item_id = $item->ID;

			// 菜单样式必须为 img
			$menu_giant = get_post_meta($item->menu_item_parent, 'menu_giant', true);
			if( $menu_giant == 'img' ){

				// 获取菜单项的icon数据
				$menu_item_icon = get_post_meta($menu_item_id, '_menu_item_icon', true);

				// 在标题前输出菜单项的icon数据
				if (!empty($menu_item_icon) && $item->menu_item_parent != 0) {
					// 在标题前输出菜单项的icon数据
					$item->title = '<span class="menu-item-icon"><img src="'.esc_url($menu_item_icon).'" alt="" /></span>'.$item->title;
					// 如果自定义字段存在，则记录其父级菜单项 ID
					$parent_ids[] = $item->menu_item_parent;
				}
			}elseif( $menu_giant == 'big' ){
				$parent_big[] = $item->menu_item_parent;
			}
		}

		// 遍历菜单项，给需要的一级菜单项添加类
		foreach ($items as &$item) {
			// 如果当前菜单项的 ID 在需要添加类的 ID 列表中
			if ($item->menu_item_parent == 0 && in_array($item->ID, $parent_ids)) {
				$item->classes[] = 'menu-item-img';
			}

			if ($item->menu_item_parent == 0 && in_array($item->ID, $parent_big)) {
				$item->classes[] = 'menu-item-big';
			}
		}

    }

    return $items;
}
add_filter('wp_nav_menu_objects', 'dahuzi_menu_icons', 10, 2);

/** --------------------------------------------------------------------------------- *
 *  为菜单增加扩展选项
 *  --------------------------------------------------------------------------------- */
add_action('wp_nav_menu_item_custom_fields', function($item_id, $item) {
    // 获取正在编辑的菜单id
    $menu_id = isset($_GET['menu']) ? (int)$_GET['menu'] : 0;

	// 获取所有菜单位置及其对应的菜单ID
	$locations = get_nav_menu_locations();
	// 获取 'primary' 位置所绑定的菜单ID
	$target_menu_id = $locations['primary'] ?? 0;

    // 仅对 primary 菜单生效 且菜单类型为img
	//echo wpjam_print_r( $item );
    $menu_giant = get_post_meta($item_id, 'menu_giant', true);
    
    if ( $menu_id === $target_menu_id && $item->menu_item_parent == 0) {
    ?>
    <p class="field-menu-giant description description-wide">
        菜单样式<br>
        <label for="menu-default-<?php echo $item_id; ?>" >
            <input type="radio" 
                id="menu-default-<?php echo $item_id; ?>" 
                name="menu-giant[<?php echo $item_id; ?>]"
                value="default" 
                checked="checked"
                <?php checked($menu_giant, 'default', true); ?> 
            />默认菜单
        </label>
        <label for="menu-img-<?php echo $item_id; ?>" >
            <input type="radio" 
                id="menu-img-<?php echo $item_id; ?>" 
                name="menu-giant[<?php echo $item_id; ?>]"
                value="img" 
                <?php checked($menu_giant, 'img'); ?> 
            />图片(仅支持2级菜单)
        </label>
        <label for="menu-big-<?php echo $item_id; ?>" >
            <input type="radio" 
                id="menu-big-<?php echo $item_id; ?>" 
                name="menu-giant[<?php echo $item_id; ?>]"
                value="big" 
                <?php checked($menu_giant, 'big'); ?> 
            />大菜单
        </label>
    </p>
	<?php }?>

    <?php
}, 10, 2);

add_action('wp_update_nav_menu_item', function($menu_id, $menu_item_db_id) {
    if (isset($_POST['menu-giant'][$menu_item_db_id])) {
        update_post_meta($menu_item_db_id, 'menu_giant', $_POST['menu-giant'][$menu_item_db_id]);
    }
}, 10, 2);
