(function () {
	'use strict';

	function initThemeNews2(scope, attempts) {
		attempts = attempts || 0;
		if (typeof Swiper === 'undefined') {
			if (attempts < 20) { window.setTimeout(function () { initThemeNews2(scope, attempts + 1); }, 120); }
			return;
		}

		(scope || document).querySelectorAll('.module-theme-news-2').forEach(function (module) {
			var slider = module.querySelector('.theme-news-2-slider');
			var menuItems = module.querySelectorAll('.tab-post-menu li');
			if (!slider) { return; }
			if (slider.swiper) { slider.swiper.updateAutoHeight(); return; }

			var swiper = new Swiper(slider, {
				speed: 500,
				spaceBetween: 10,
				autoHeight: true,
				allowTouchMove: false,
				on: {
					slideChangeTransitionStart: function () {
						menuItems.forEach(function (item, index) { item.classList.toggle('active', index === swiper.activeIndex); });
					}
				}
			});

			menuItems.forEach(function (item, index) {
				item.addEventListener('click', function () { swiper.slideTo(index); });
			});
		});
	}

	function start() {
		initThemeNews2(document);
		if (!window.MutationObserver || !document.body) { return; }
		var timer;
		new MutationObserver(function (mutations) {
			var found = mutations.some(function (mutation) {
				return Array.prototype.some.call(mutation.addedNodes, function (node) {
					return node.nodeType === 1 && ((node.matches && node.matches('.module-theme-news-2')) || (node.querySelector && node.querySelector('.module-theme-news-2')));
				});
			});
			if (found) { window.clearTimeout(timer); timer = window.setTimeout(function () { initThemeNews2(document); }, 80); }
		}).observe(document.body, { childList: true, subtree: true });
	}

	window.xtpbInitThemeNews2 = initThemeNews2;
	if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', start); } else { start(); }
})();
