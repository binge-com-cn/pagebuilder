jQuery(function($){
	if(window.location.protocol == 'https:'){
		ajaxurl = ajaxurl.replace('http://', 'https://');
	}

	$.fn.extend({
		wpjam_submit: function(callback){
			let _this   = $(this);
			let data	= new FormData(_this[0]);

			data.append('_ajax_nonce',  $(this).data('nonce'));
			data.append('action',	   $(this).data('action'));
			data.append('defaults',	 $(this).data('data'));
			data.append('data',		 $(this).serialize());

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: data,
				processData: false,
				contentType: false,
				success: function(data){
					callback.call(_this, data);
				}
			});
		},
		wpjam_action: function(callback){
			let _this   = $(this);

			$.post(ajaxurl, {
				_ajax_nonce:	$(this).data('nonce'),
				action:		 $(this).data('action'),
				data:		   $(this).data('data')
			},function(data, status){
				callback.call(_this, data);
			});
		}
	});

	$('body').on('click', '.post-action', function(e){
		e.preventDefault();

		$(this).wpjam_action(function(data){
			if(data.errcode != 0){
				alert(data.errmsg);
			}else{
				$(this).removeClass().addClass(data.class).data('data', data.data);
				$(this).find('i').removeClass().addClass(data.icon);
				$(this).find('span.post-action-count').html(data.count);

				$('body').trigger('wpjam_post_action_success', data);
			}
		});
	});

	$('body').on('click', '.comment-digg', function(e){
		e.preventDefault();

		$(this).wpjam_action(function(data){
			if(data.errcode != 0){
				alert(data.errmsg);
			}else{
				$(this).removeClass().addClass(data.class).data('data', data.data);
				$(this).find('.comment-digg-count').html(data.count);
				$(this).find('i').removeClass().addClass(data.icon);
			}
		});
	});

	$('.wpjam-form').each(function() {
		$(this).on('submit', function(e){
			e.preventDefault();
			var form = $(this);

			// 查找提交按钮
			var submitButton = form.find('button[type="submit"]');
			// 提交时修改按钮文本和添加class “submitting”
			submitButton.addClass("submitting");

			$(this).wpjam_submit(function(data){
				if(data.errcode){
					swal({
					 title: "Error",
					 text: data.errmsg,
					 type: "error",
					 timer: 3000,
					 showConfirmButton: false
					});
				}else{
					form[0].reset(); // 清除表单内容
					if(data.redirect){						
						window.location.href = data.redirect;
					}else{
						swal({
						 title: data.msg,
						 //text: '3秒后自动关闭',
						 type: "success",
						 timer: 3000,
						 showConfirmButton: false
						});
					}
				}

				// 移除class “submitting”
				submitButton.removeClass("submitting");

			});
		});
	});

	$('#toc .toc-controller').on('click',function(){
		if($(this).hasClass('toc-controller-show')){
			$(this).addClass('toc-controller-hide').removeClass('toc-controller-show');
			$(this).html('[显示]');
		}else{
			$(this).addClass('toc-controller-show').removeClass('toc-controller-hide');
			$(this).html('[隐藏]');
		}
		$('#toc ul').toggle();
	});

});



(function($) {
	new WOW({
		mobile: false
	}).init();

	/*-------------------------------------------
	  Fancybox
	-------------------------------------------*/
	Fancybox.bind('[data-fancybox]', {
		//缩略图
		Thumbs: {
			//新版缩略图
			//type: "modern",
			//经典缩略图
			type: "classic",
		},
		Images: {
			//图片保护，禁止鼠标右键下载图片
			protected: true,
		},
		//工具栏
		Toolbar: {
			display: {
				left: [
					"infobar"
				],
				middle: [
					"zoomIn",
					"zoomOut",
					//"toggle1to1",
					"rotateCCW",
					"rotateCW",
					"flipX",
					"flipY",
				],
				right: [
					"slideshow",
					"thumbs",
					"close"
				],
			},
		},
	});

	/* -------------------------------------------------------------------------- */
	/* 页面滚动给header-menu添加active
	/* -------------------------------------------------------------------------- */
	var $headerMain = $(".header-style-1, .header-style-2, .header-style-3");

	// 如果找到具有这些样式的元素，则定义滚动处理函数
	if ($headerMain.length) {
		var headerScroll = function () {
			var scroll = $(window).scrollTop();
			$headerMain.toggleClass("active", scroll > 100);
		};

		// 初始化时调用一次
		headerScroll();

		// 滚动事件绑定
		$(window).scroll(function () {
			headerScroll();
		});
	}

	/* -------------------------------------------------------------------------- */
	/* 手机端菜单
	/* -------------------------------------------------------------------------- */
	$('.hamburger').on('click', function(event) {
		if ($('body').css('position') === 'fixed') {
			// 如果body元素的position为fixed，则移除该样式
			$('body').css('position', '');
		} else {
			// 否则，为body元素添加position: fixed样式
			$('body').css('position', 'fixed');
		}
		
		$(this).toggleClass('h-active');
		$('.header-menu').toggleClass('slidenav');
	});

	$(".header-menu .icon-drop-down").on('click', function(event) {
		var $fl = $(this);
		var $submenu = $fl.next(".sub-menu");

		// 关闭其他兄弟节点的子菜单并重置箭头图标
		$fl.parent().siblings().find('.sub-menu').slideUp();
		$fl.parent().siblings().find('.icon-drop-down').removeClass('ri-arrow-up-s-line').addClass('ri-arrow-down-s-line');

		// 切换当前箭头图标和子菜单的显示状态
		$fl.toggleClass('ri-arrow-up-s-line ri-arrow-down-s-line');
		$submenu.slideToggle();
	});


	/*-------------------------------------------
	 一键复制按钮
	-------------------------------------------*/
	$(".copy-btn").each(function(){
		var dataOpt = $(this).data('settings');
		if (dataOpt) {
			var clipboard = new Clipboard('#'+dataOpt.id);	
			clipboard.on('success', function(e) {	
				if( dataOpt.copy_tip ){
					alert(dataOpt.copy_tip,1500);
				}else{
					alert('【'+dataOpt.copy+'】复制成功',1500);
				}
				if( dataOpt.open_wechat ){
					window.location.href='weixin://';
				}
				//e.clearSelection();	
				//console.log(e.clearSelection);	
			});
		}
	});

	/*-------------------------------------------
	  首页幻灯片/滑块
	-------------------------------------------*/
	let slideVideo = document.querySelector('.swiper-banner .swiper-slide video');
	if (slideVideo) {
		var banner = new Swiper('.swiper-banner', {

			loop: false,
			autoplay: false,
			autoHeight: true,
			navigation: {
				prevEl: '.swiper-banner .swiper-button-prev',
				nextEl: '.swiper-banner .swiper-button-next'
			},
			pagination: {
				el: '.swiper-banner .swiper-pagination',
				clickable: true
			},

			on: {
				slideChange: function () {
					// 暂停并重置所有视频
					document.querySelectorAll('.swiper-slide video').forEach(video => {
						//video.pause();
						video.currentTime = 0; // 重头开始
					});

					// 延迟播放当前幻灯片中的视频，以确保 DOM 更新完成
					requestAnimationFrame(() => {
						const swiperEl = document.querySelector('.swiper-banner');
						let activeSlide = document.querySelector('.swiper-slide-active video');
						if (activeSlide) {
							activeSlide.play();
							// 视频播放结束时自动切换到下一个幻灯片
							activeSlide.onended = function() {
								if (swiperEl.swiper.isEnd) {
									swiperEl.swiper.slideTo(0); // 如果是最后一张幻灯片，回到第一张
								} else {
									swiperEl.swiper.slideNext();
								}
							};
						}
					});

				}
			}
		});

	    // 页面加载后，播放第一个视频
	    window.addEventListener('load', function() {
	    	const swiperEl = document.querySelector('.swiper-banner');
	        let firstVideo = document.querySelector('.swiper-slide-active video');
	        if (firstVideo) {
	            firstVideo.play();
	            // 视频播放结束时自动切换到下一个幻灯片
	            firstVideo.onended = function() {
	                //console.log('First video ended');
	                swiperEl.swiper.slideNext();
	            };
	        }
	    });
	}else{
		var banner = new Swiper('.swiper-banner', {
				loop: true,
				//autoplay: true,
				autoplay: {
					delay: 3000,
					disableOnInteraction: false, //如果设置为false，用户操作swiper之后自动切换不会停止，每次都会重新启动autoplay。
				},
				autoHeight: true,
				navigation: {
					prevEl: '.swiper-banner .swiper-button-prev',
					nextEl: '.swiper-banner .swiper-button-next'
				},
				pagination: {
					el: '.swiper-banner .swiper-pagination',
					clickable: true
				}
			});
	}

	/*-------------------------------------------
	  首页TAB切换 文章模块
	-------------------------------------------*/
	function tabsSwiper(menu, con, allowTouchMove) {
		var swiper = new Swiper(con, {
			speed: 500,
			spaceBetween: 10,
			autoHeight: true,
			allowTouchMove: !allowTouchMove,
			on: {
				slideChangeTransitionStart: function () {
					$(menu).find('li').eq(this.activeIndex).addClass('active').siblings().removeClass('active');
				}
			}
		});
		$(menu).find('li').eq(0).addClass('active');
		$(menu).on('click', 'li', function (e) {
			$(this).addClass('active').siblings().removeClass('active');
			swiper.slideTo($(this).index());
		});
	}

	$(".tab-post-menu").each(function(){
		var dataOpt = $(this).data('settings');
		if (dataOpt) {
			tabsSwiper('.menu-'+dataOpt.id, '.content-'+dataOpt.id);
		}
	});


	//菜单跟随
	$('.tab-menu-left').theiaStickySidebar({
		// Settings
		additionalMarginTop: 75
	});

	/*-------------------------------------------
	  案例展示
	-------------------------------------------*/
	var swiper = new Swiper('.md-case', {
		autoplay: {
			delay: 3000,
			disableOnInteraction: false, //如果设置为false，用户操作swiper之后自动切换不会停止，每次都会重新启动autoplay。
		},
		//slidesPerView: 4,
		//spaceBetween: 20,
		slidesPerGroup: 4,
		loop: true,
		loopFillGroupWithBlank: true,
		breakpoints: {
			768: {
				slidesPerView: 1,
				spaceBetween: 15,
			},
			1020: {
				slidesPerView: 4,
				spaceBetween: 20,
			},
		},
		pagination: {
			el: '.module-theme-case .swiper-pagination',
			clickable: true,
		},
		navigation: {
			nextEl: '.module-theme-case .swiper-button-next',
			prevEl: '.module-theme-case .swiper-button-prev',
		},
	});

	/*-------------------------------------------
	  荣誉资质
	-------------------------------------------*/
	var swiper = new Swiper(".aptitude-list", {
		loop: true,
		autoplay: {
			delay: 3000,
			disableOnInteraction: false, //如果设置为false，用户操作swiper之后自动切换不会停止，每次都会重新启动autoplay。
		},
		//slidesPerGroup: 4,
		//slidesPerView: 4,
		//spaceBetween: 20,
		pagination: {
			el: ".aptitude-list .swiper-pagination",
			clickable: true,
		},
		breakpoints: {
			"@0.00": {
				slidesPerView: 2,
				spaceBetween: 16,
			},
			"@0.75": {
				slidesPerView: 2,
				spaceBetween: 16,
			},
			"@1.00": {
				slidesPerView: 2,
				spaceBetween: 16,
			},
			"@1.50": {
				slidesPerView: 4,
				spaceBetween: 20,
			},
		},
	});

	/*-------------------------------------------
	  产品侧栏菜单
	-------------------------------------------*/
	$('.widget_nav_menu .menu-item-has-children>a').click(function () {
		var ul_dis=$(this).parent().find('.sub-menu').css('display') ;
		if (ul_dis == 'none') {
			$('.widget_nav_menu li').removeClass('hover');
			$(this).parent().addClass('hover');
			$('.widget_nav_menu .sub-menu').slideUp();
			$(this).parent().find('.sub-menu').slideDown();
		}
		else {
			$(this).parent().find('.sub-menu').slideUp();
			$(this).parent().removeClass('hover');
		}
	})

	//分类页面，自动展开当前菜单
	//$('.widget_nav_menu .current-menu-item .sub-menu').css('display','block');
	//$('.widget_nav_menu .current-menu-item').addClass('hover');

	//文章页面，自动展开当前菜单
	//$('.widget_nav_menu .current-post-item .sub-menu').css('display','block');
	//$('.widget_nav_menu .current-post-item').addClass('hover');

	//如果菜单下有二级菜单，移除一级菜单中的跳转链接
	$(".widget_nav_menu .menu-item-has-children>a").attr("href","javascript:;");

	/*-------------------------------------------
	 产品页面TAB切换菜单
	-------------------------------------------*/
	//点击切换栏目
	// 监听 li 标签的点击事件
	$('.product-tab-menu ul li').click(function() {
		// 移除所有 li 的 active 类
		$('.product-tab-menu ul li').removeClass('active');
		// 添加当前点击的 li 的 active 类
		$(this).addClass('active');

		// 获取当前点击的 li 的 data-tab 属性值
		var tabId = $(this).data('tab');

		// 隐藏所有 productTab-content
		$('.productTab-content').removeClass('active');
		// 显示对应的 productTab-content
		$('#' + tabId).addClass('active');
		// 滚动到相应的 tab-content 区域的顶部
		var tabContentTop = $('#' + tabId).offset().top - 135;
		$('html, body').animate({scrollTop: tabContentTop}, 'slow');
	});

	//菜单置顶显示
	//获取目标元素的位置
	$(document).ready(function() {
		//if ($(window).width() > 768) {
			var tabMenu = $('.product-tab-menu');
			if (tabMenu.length > 0) {
				var tabMenuPosition = tabMenu.offset().top;
				var postBodyBottom;

				// 点击事件处理程序 重新计算 postBodyBottom 的值
				$('.product-tab-menu ul li').on('click', function() {
					var postBody = $('.productTab-content.active');
					postBodyBottom = postBody.offset().top + postBody.outerHeight() - 150;
					// 判断是否超出 .post-body 位置
					checkPosition();
				});

				// 初始化时计算 postBodyBottom 的值
				$(window).on('load', function() {
					var postBody = $('.productTab-content.active');
					postBodyBottom = postBody.offset().top + postBody.outerHeight() - 150;
					// 判断是否超出 .post-body 位置
					checkPosition();
				});

				// 监听滚动事件
				$(window).scroll(function() {
					// 获取当前滚动位置
					var scrollPosition = $(window).scrollTop();

					// 判断是否滚动到.product-tab-menu位置
					if (scrollPosition > tabMenuPosition) {
						tabMenu.addClass('active');
						tabMenu.css('opacity', 1);
					} else {
						tabMenu.removeClass('active');
					}

					// 判断是否超出 .post-body 位置
					checkPosition();
				});

				// 检查滚动位置是否超出 .post-body 位置
				function checkPosition() {
					if (postBodyBottom) {
						var scrollPosition = $(window).scrollTop();
						if (scrollPosition >= postBodyBottom) {
							tabMenu.css('opacity', 0);
						} else {
							tabMenu.css('opacity', 1);
						}
					}
				}
			}
		//}
	});




	/*-------------------------------------------
	 侧栏跟随
	-------------------------------------------*/
	$('.sidebar-widget,.page-main').theiaStickySidebar({
		// Settings
		additionalMarginTop: 87
	});

	$('.team-single .team-img').theiaStickySidebar({
		// Settings
		additionalMarginTop: 87
	});

	/*-------------------------------------------
	 常见问题列表 手风琴
	-------------------------------------------*/
	$('.accordion-title').click(function() {
		$(this).parent('.post-accordion').toggleClass('active');
		$(this).next('.accordion-desc').slideToggle(300);
	});

	/*-------------------------------------------
	 如果有提示信息 页面加载完成后自动显示弹窗
	-------------------------------------------*/
	//$('.license-valid').trigger('click');

	/*-------------------------------------------
	 返回顶部
	-------------------------------------------*/
	$(window).on('scroll', function () {
		if ($(this).scrollTop() > 200) {
			$('.scrollup').fadeIn();
		} else {
			$('.scrollup').fadeOut();
		}
	});
	$('.scrollup').on('click', function () {
		$("html, body").animate({
			scrollTop: 0
		}, 1000);
		return false;
	});

	/*-------------------------------------------
	 文章分享
	-------------------------------------------*/
    $(document).ready(function() {

		var $postShare = $(".post-share");

		// 如果找到具有这些样式的元素，则定义滚动处理函数
		if ($postShare.length) {

			$('body').on('click', '.post-share-item:not(".prevent-share-popup")', function(e) {
				e.preventDefault();
				post_share_popup($(this).attr('data-url'));
			});

			$('#sharer-wechat').qrcode({
				text : $('#sharer-wechat').attr('data-url')
			});

		}

	});

	function post_share_popup(data) {
		window.open(data, "Share", 'height=500,width=760,top=' + ($(window).height() / 2 - 250) + ', left=' + ($(window).width() / 2 - 380) + 'resizable=0,toolbar=0,menubar=0,status=0,location=0,scrollbars=0');
	}


})(jQuery);


/*视频模块*/
document.addEventListener("DOMContentLoaded", function () {
	const targetElement = document.querySelector(".video-transition");
	const triggerElement = document.querySelector(".module-theme-video");

	if (!targetElement || !triggerElement) {
		return;
	}

	function handleScroll() {
		const triggerTop = triggerElement.getBoundingClientRect().top;
		const windowHeight = window.innerHeight;

		if (triggerTop < windowHeight && triggerTop > 0) {
			const scrollRatio = Math.min(Math.max((windowHeight - triggerTop) / windowHeight, 0), 1);

			targetElement.style.transform = `translateY(${80 * (1 - scrollRatio)}px) scale(${0.8 + 0.2 * scrollRatio})`;
			targetElement.style.opacity = scrollRatio;
		}
	}

	window.addEventListener("scroll", handleScroll);
	handleScroll();
});

