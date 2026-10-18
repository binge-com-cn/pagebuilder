(function () {
	'use strict';

	function initThemeCase(scope, attempts) {
		attempts = attempts || 0;
		if (typeof Swiper === 'undefined') {
			if (attempts < 20) {
				window.setTimeout(function () { initThemeCase(scope, attempts + 1); }, 120);
			}
			return;
		}

		(scope || document).querySelectorAll('.module-theme-case').forEach(function (module) {
			var slider = module.querySelector('.md-case');
			var count;
			if (!slider) { return; }
			if (slider.swiper) { slider.swiper.update(); return; }
			count = slider.querySelectorAll('.swiper-slide').length;
			new Swiper(slider, {
				slidesPerView: 1,
				slidesPerGroup: 1,
				spaceBetween: 15,
				loop: count > 4,
				autoplay: count > 1 ? { delay: 3000, disableOnInteraction: false } : false,
				breakpoints: { 1020: { slidesPerView: 4, slidesPerGroup: 4, spaceBetween: 20 } },
				pagination: { el: module.querySelector('.swiper-pagination'), clickable: true },
				navigation: { nextEl: module.querySelector('.swiper-button-next'), prevEl: module.querySelector('.swiper-button-prev') }
			});
		});
	}

	function start() {
		initThemeCase(document);
		if (!window.MutationObserver || !document.body) { return; }
		var timer;
		new MutationObserver(function (mutations) {
			var found = mutations.some(function (mutation) {
				return Array.prototype.some.call(mutation.addedNodes, function (node) {
					return node.nodeType === 1 && ((node.matches && node.matches('.module-theme-case')) || (node.querySelector && node.querySelector('.module-theme-case')));
				});
			});
			if (found) { window.clearTimeout(timer); timer = window.setTimeout(function () { initThemeCase(document); }, 80); }
		}).observe(document.body, { childList: true, subtree: true });
	}

	window.xtpbInitThemeCase = initThemeCase;
	if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', start); } else { start(); }
	if (window.jQuery) {
		window.jQuery(document).on('rendered_addon', function (event, addon) {
			if (addon && addon.name && addon.name !== 'theme_case') { return; }
			var iframe = window.frames['xtpb-builder-view'];
			if (iframe && iframe.window && iframe.window.document) {
				iframe.window.setTimeout(function () {
					if (iframe.window.xtpbInitThemeCase) { iframe.window.xtpbInitThemeCase(iframe.window.document); }
				}, 60);
			} else { window.setTimeout(function () { initThemeCase(document); }, 60); }
		});
	}
})();
