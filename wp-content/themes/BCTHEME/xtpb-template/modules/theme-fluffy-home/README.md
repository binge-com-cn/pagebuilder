# Fluffy Brunch 首页（Restaurantly 风格）

预览：http://pagebuilder/fluffy-home/
编辑：http://pagebuilder/wp-admin/post.php?post=89&action=xtpb_editor

## 当前实现

- 页面模板 `page-fluffy-home.php` 调用 BCTHEME 的 `get_header()` / `get_footer()`；Logo、菜单、页脚小工具和版权继续由主题现有设置控制。
- 页面包含 7 个可编辑内容区：原 Hero、原关于我们、精选菜单、画廊、品牌理念、媒体口碑、订位与到店。
- 已移除首版独立导航和页脚。新增模块为 5 个，位于「模块 → Fluffy Brunch」。
- 样式参考用户提供的 Restaurantly HTML：原 bg2 / bg3 / bg4 / bg8 背景纹理、h-shap 标题装饰、#E4C590 金色、深色背景及描边按钮。未引入模板的全局 CSS，避免覆盖主题头尾。
- Hero / 关于我们仍使用原 Theme_Rst_Banner 与 Theme_Rst_About；原模块代码未修改。新页面仅填入客户内容、背景和装饰图配置。
- 新模块通过 PHP render() 及构建器已有 AJAX 回退机制渲染，未另写一套实时模板。

## 编辑说明

菜品按分类分组，分类图片取每组第一张非空图片，支持前台分类筛选。名称、价格、说明和图片在重复列表中编辑。

画廊保留客户原首页 12 张照片，先显示 6 张，其余通过 Altre foto 展开；支持放大、关闭按钮和 Esc。

订位与到店仍是内容模块，不再包含独立页脚。菜单和订位链接沿用客户原站；联系方式可编辑。

整页已注册到模板库，数据在 `../../layouts/fluffy-brunch-home.json`。导入时使用「Fluffy · 品牌首页（主题头尾）」页面模板。

## 内容与检查

文案和照片来自客户原站 https://fluffy-brunch.com/ ，素材已进入本地媒体库。源地址保存在附件 `_fluffy_source_url` 中。

价格保持原首页展示值；原首页 Matcha Latte €5.50 与完整菜单 €6.50 存在差异，交付前应向客户核对。媒体评分和奖项按客户页面保留。

已检查：PHP/JS 语法、模块字段渲染和转义、空列表、有效导出 JSON、桌面 1440 / 平板 768 / 手机 390 和 320 布局、图片加载、菜单筛选、灯箱与 Esc。页面只有 1 个主题头部和 1 个主题页脚，没有自定义头尾。没有浏览器 JS 异常。

未通过登录后编辑器 UI 实测拖拽保存；已验证模块注册和编辑渲染接口。新首页为本地独立预览页，未修改全站首页选项或 Catering 页面数据。
