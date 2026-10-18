(function () {
	'use strict';

	function init(scope, attempts) {
		attempts = attempts || 0;

		if (typeof Swiper === 'undefined') {
			if (attempts < 20) {
				setTimeout(function () {
					init(scope, attempts + 1);
				}, 120);
			}
			return;
		}

		(scope || document).querySelectorAll('.module-theme-reviews .reviews-slider').forEach(function (slider) {
			if (slider.swiper) {
				slider.swiper.update();
				return;
			}

			var wrapper = slider.querySelector('.swiper-wrapper');
			var slides = wrapper ? Array.prototype.slice.call(wrapper.children) : [];
			var originalCount = slides.length;
			var items = Math.max(1, parseInt(slider.dataset.items || '3', 10));
			var speed = Math.max(1, parseInt(slider.dataset.speed || '10000', 10));

			if (wrapper && originalCount > 1 && originalCount <= items) {
				var targetCount = items * 2;
				for (var index = originalCount; index < targetCount; index++) {
					wrapper.appendChild(slides[index % originalCount].cloneNode(true));
				}
			}

			new Swiper(slider, {
				breakpointsBase: 'container',
				slidesPerView: 1,
				spaceBetween: 16,
				centeredSlides: true,
				centeredSlidesBounds: true,
				loop: originalCount > 1,
				speed: speed,
				allowTouchMove: slider.dataset.touch === '1',
				autoplay: originalCount > 1 ? {
					delay: 0,
					pauseOnMouseEnter: slider.dataset.pause === '1',
					disableOnInteraction: slider.dataset.disable === '1',
					reverseDirection: slider.dataset.reverse === '1'
				} : false,
				breakpoints: {
					480: {
						slidesPerView: Math.min(items, 2),
						spaceBetween: 20
					},
					900: {
						slidesPerView: Math.min(items, 3),
						spaceBetween: 24
					},
					1200: {
						slidesPerView: items,
						spaceBetween: 30
					}
				}
			});
		});
	}

	function start() {
		init(document);
		if (window.MutationObserver && document.body) {
			new MutationObserver(function () {
				init(document);
			}).observe(document.body, { childList: true, subtree: true });
		}
	}

	window.xtpbInitThemeReviews = init;

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', start);
	} else {
		start();
	}
}());
