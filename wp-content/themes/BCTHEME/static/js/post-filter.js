jQuery(document).ready(function($) {

	// 从 localStorage 恢复选中的标签和分类
	var savedTags = localStorage.getItem('selectedTags') ? localStorage.getItem('selectedTags').split(',') : [];
	var savedCats = localStorage.getItem('selectedCats') ? localStorage.getItem('selectedCats').split(',') : [];

	// 恢复之前的筛选状态
	savedTags.forEach(function(tag) {
		$('input[name="tag"][value="' + tag + '"]').prop('checked', true);
	});

	savedCats.forEach(function(cat) {
		$('input[name="cat"][value="' + cat + '"]').prop('checked', true);
	});

	// 设置高亮状态
	$('input[name="tag"], input[name="cat"]').each(function() {
		if ($(this).is(':checked')) {
			$(this).closest('label').addClass('active');
		}
	});

	// 单选逻辑
	function applySingleSelectionLogic() {
		$('.radio_xintheme').each(function() {
			var $menuItem = $(this).children("ul").children("li");
			var $checkboxes = $menuItem.find('input[type="checkbox"]');

			$checkboxes.on('change', function() {
				if ($(this).is(':checked')) {
					$checkboxes.not(this).prop('checked', false);
				}
			});
		});
	}

	// 初次加载时根据保存的状态筛选文章并应用单选逻辑
	if (savedTags.length > 0 || savedCats.length > 0) {
		filterPosts();
	}
	// 单选逻辑
	applySingleSelectionLogic();

	// 监听复选框变化
	$('input[name="tag"], input[name="cat"]').on('change', function() {
		filterPosts(); // 每当复选框状态变化时，调用 filterPosts 函数

		// 高亮选中的标签和分类
		$('input[name="tag"], input[name="cat"]').each(function() {
			if ($(this).is(':checked')) {
				$(this).closest('label').addClass('active');
			} else {
				$(this).closest('label').removeClass('active');
			}
		});

		// 保存当前的筛选状态到 localStorage
		var tags = [];
		var cats = [];

		$('input[name="tag"]:checked').each(function() {
			tags.push($(this).val());
		});

		$('input[name="cat"]:checked').each(function() {
			cats.push($(this).val());
		});

		localStorage.setItem('selectedTags', tags.join(','));
		localStorage.setItem('selectedCats', cats.join(','));
	});

	function filterPosts(paged = 1) {

		// 获取选中的标签和分类
		var tags = [];
		var cats = [];

		$('input[name="tag"]:checked').each(function() {
			tags.push($(this).val());
		});

		$('input[name="cat"]:checked').each(function() {
			cats.push($(this).val());
		});

		// 发送 AJAX 请求
		$.ajax({
			url: ajaxurl, // WordPress 的全局变量，指向 admin-ajax.php
			type: 'POST',
			data: {
				action: 'dahuzi_filter_posts', // 自定义的 action 名称
				category_id: categoryId,
				tags: tags,
				cats: cats,
				paged: paged, // 传递当前页码
			},
			success: function(response) {
				// 将响应内容插入到结果区域
				$('.post-list-box').html(response.html);
				// 统计筛选结果
				//$('.filter-results-count').text(response.total_posts);
				$('.filter-results-count')
				.parent().removeClass('results-none')  // 移除父元素的 `results-count` class
				.end()  // 返回到 `.filter-results-count` 元素
				.text(response.total_posts)  // 更新文本内容
				.css('display', 'inline-block');  // 设置 `display` 为 `inline-block`

				//移除默认的分页按钮
				$('.theme-pagination').remove();
			},
			error: function() {
				$('.post-list-box').html('<p>无法加载内容，请重试。</p>');
				$('.theme-pagination').remove();
			}
		});
	}

	// 监听分页按钮点击
	$(document).on('click', '.filter-pagination a', function(e) {
		e.preventDefault(); // 阻止默认链接跳转行为
		var paged = $(this).data('page'); // 获取页码
		filterPosts(paged); // 调用 filterPosts 函数并传递页码
		// 平滑滚动到 .nav-filter-box 区域
		$('html, body').animate({
			scrollTop: $('.nav-filter-box').offset().top
		}, 0); // 800 是滚动的时间，单位为毫秒
	});

	// 重置按钮操作
	$('.reset-filters').click(function() {
		// 清除保存的筛选状态
		localStorage.removeItem('selectedTags');
		localStorage.removeItem('selectedCats');

		// 清除选中状态
		$('input[name="tag"], input[name="cat"]').prop('checked', false).closest('label').removeClass('active');

		// 重新应用单选逻辑
		applySingleSelectionLogic();

		// 刷新当前页面
		location.reload();
	});

});