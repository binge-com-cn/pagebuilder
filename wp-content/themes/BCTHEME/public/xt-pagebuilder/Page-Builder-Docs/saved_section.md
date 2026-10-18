# 收藏（保存行）说明

这份文档说明编辑器左侧 `收藏` 菜单的用途、数据结构与当前预览图生成规则。

## 功能定位

`收藏` 用来保存当前页面中的一整行内容，方便后续重复拖入页面。

它保存的是：

- 行数据
- 行内的列结构
- 列内组件配置
- 收藏标题
- 收藏缩略图

它不是：

- 整页布局
- 区块模板库
- 单个组件

## 保存入口

在行工具栏中点击：

- `添加到收藏`

会弹出一个输入标题的对话框。

保存成功后，这条收藏会出现在左侧：

- `收藏`

菜单中。

## 当前交互规则

### 保存时

保存收藏时会执行以下流程：

1. 读取当前行数据
2. 使用编辑器 iframe 中的真实行 DOM 生成缩略图
3. 通过 AJAX 保存收藏数据
4. 服务端把缩略图写入 uploads 目录
5. 左侧收藏列表显示新卡片

### 拖入页面

收藏项可以像区块一样拖入页面。

拖入后插入的是：

- 该收藏对应的整行数据

而不是图片本身。

### 删除时

删除收藏时会同时删除：

- 收藏数据
- 对应的缩略图文件

避免残留无用图片。

## 数据结构

收藏数据保存在 `xtpb_section` 中。

每一项通常包含：

```php
array(
    'id'      => 1710000000000,
    'title'   => '示例收藏',
    'block'   => array(...),
    'preview' => 'http://example.com/wp-content/uploads/xt-pagebuilder/saved-sections/section-1710000000000.webp',
)
```

字段说明：

- `id`
  收藏唯一 ID
- `title`
  收藏标题
- `block`
  行数据
- `preview`
  缩略图 URL

## 缩略图生成规则

当前缩略图不是实时渲染预览，而是：

- 保存收藏时截图
- 服务端保存为图片文件
- 列表里直接显示这张图片

### 前端截图

前端使用：

- `html2canvas`

对当前行进行截图。

截图时会忽略编辑器控制元素，例如：

- 行工具条
- 列工具条
- 组件工具条
- placeholder
- resize handle

### 图片格式

当前会优先生成：

- `webp`

不支持时回退为：

- `png`

### 存储位置

缩略图文件保存在：

- `wp-content/uploads/xt-pagebuilder/saved-sections/`

文件名格式：

- `section-<id>.webp`
- `section-<id>.png`

## 编辑器列表展示

收藏列表当前是：

- 两列
- 高度自适应
- 堆砌显示

每张卡片显示：

- 缩略图
- 标题
- 删除按钮

## 与其他能力的区别

### 与组件的区别

组件是单个 addon。

收藏保存的是：

- 一整行结构

### 与模块的区别

模块是代码级注册的固定布局组件。

收藏保存的是：

- 当前编辑器里实际存在的一条行数据

### 与区块的区别

区块更接近预制模板库。

收藏更接近：

- 当前站点编辑器里的私人复用片段

## 相关文件

如果后续维护收藏功能，优先看这些文件：

- `assets/reactjs/src/helpers/RowSaveModal.js`
- `assets/reactjs/src/components/MySectionItem.js`
- `assets/reactjs/src/components/MySection.js`
- `classes/Ajax.php`
- `assets/css/edit.css`
- `assets/js/vendor/html2canvas.min.js`
