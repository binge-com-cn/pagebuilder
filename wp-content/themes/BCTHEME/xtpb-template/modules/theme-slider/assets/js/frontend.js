(function () {
	'use strict';

	function playActiveVideo(swiper) {
		var activeVideo = swiper.el.querySelector('.swiper-slide-active video');

		if (!activeVideo) {
			return;
		}

		activeVideo.play().catch(function () {});
		activeVideo.onended = function () {
			if (swiper.isEnd) {
				swiper.slideTo(0);
			} else {
				swiper.slideNext();
			}
		};
	}

	function resetVideos(swiper) {
		swiper.el.querySelectorAll('.swiper-slide video').forEach(function (video) {
			video.currentTime = 0;
		});
	}

	function initThemeSlider(scope, attempts) {
		attempts = attempts || 0;

		if (typeof Swiper === 'undefined') {
			if (attempts < 20) {
				window.setTimeout(function () {
					initThemeSlider(scope, attempts + 1);
				}, 120);
			}
			return;
		}

		(scope || document).querySelectorAll('.xtpb-theme-slider-banner').forEach(function (slider) {
			if (slider.swiper) {
				slider.swiper.update();
				return;
			}

			var slideCount = slider.querySelectorAll('.swiper-slide').length;
			var hasVideo = !!slider.querySelector('.swiper-slide video');
			var options = {
				loop: !hasVideo && slideCount > 1,
				autoHeight: true,
				navigation: {
					prevEl: slider.querySelector('.swiper-button-prev'),
					nextEl: slider.querySelector('.swiper-button-next')
				},
				pagination: {
					el: slider.querySelector('.swiper-pagination'),
					clickable: true
				}
			};

			if (hasVideo) {
				options.autoplay = false;
				options.on = {
					init: function (swiper) {
						playActiveVideo(swiper);
					},
					slideChange: function (swiper) {
						resetVideos(swiper);
						requestAnimationFrame(function () {
							playActiveVideo(swiper);
						});
					}
				};
			} else if (slideCount > 1) {
				options.autoplay = {
					delay: 3000,
					disableOnInteraction: false
				};
			}

			new Swiper(slider, options);
		});
	}

	function watchThemeSliderRender() {
		var timer;

		if (!window.MutationObserver || !document.body) {
			return;
		}

		new MutationObserver(function (mutations) {
			var shouldInit = mutations.some(function (mutation) {
				return Array.prototype.some.call(mutation.addedNodes, function (node) {
					return node.nodeType === 1 && (
						(node.matches && node.matches('.xtpb-theme-slider-banner')) ||
						(node.querySelector && node.querySelector('.xtpb-theme-slider-banner'))
					);
				});
			});

			if (!shouldInit) {
				return;
			}

			window.clearTimeout(timer);
			timer = window.setTimeout(function () {
				initThemeSlider(document);
			}, 80);
		}).observe(document.body, {
			childList: true,
			subtree: true
		});
	}

	window.xtpbInitThemeSlider = initThemeSlider;

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () {
			initThemeSlider(document);
			watchThemeSliderRender();
		});
	} else {
		initThemeSlider(document);
		watchThemeSliderRender();
	}

	if (window.jQuery) {
		window.jQuery(document).on('rendered_addon', function (event, addon) {
			var iframe;

			if (addon && addon.name && addon.name !== 'theme_slider') {
				return;
			}

			iframe = window.frames['xtpb-builder-view'];
			if (iframe && iframe.window && iframe.window.document) {
				iframe.window.setTimeout(function () {
					if (iframe.window.xtpbInitThemeSlider) {
						iframe.window.xtpbInitThemeSlider(iframe.window.document);
					} else if (window.xtpbInitThemeSlider) {
						window.xtpbInitThemeSlider(iframe.window.document);
					}
				}, 60);
			} else {
				window.setTimeout(function () {
					initThemeSlider(document);
				}, 60);
			}
		});
	}
})();
