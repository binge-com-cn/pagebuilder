(function () {
	'use strict';

	function initThemeAptitude(scope, attempts) {
		attempts = attempts || 0;
		if (typeof Swiper === 'undefined') {
			if (attempts < 20) {
				window.setTimeout(function () { initThemeAptitude(scope, attempts + 1); }, 120);
			}
			return;
		}

		(scope || document).querySelectorAll('.xtpb-theme-aptitude-slider').forEach(function (slider) {
			if (slider.swiper) {
				slider.swiper.update();
				return;
			}

			var count = slider.querySelectorAll('.swiper-slide').length;
			new Swiper(slider, {
				loop: count > 1,
				autoplay: count > 1 ? { delay: 3000, disableOnInteraction: false } : false,
				pagination: { el: slider.querySelector('.swiper-pagination'), clickable: true },
				breakpoints: {
					0: { slidesPerView: 2, spaceBetween: 16 },
					1140: { slidesPerView: 4, spaceBetween: 20 }
				}
			});
		});
	}

	function observe() {
		if (!window.MutationObserver || !document.body) { return; }
		var timer;
		new MutationObserver(function (mutations) {
			var found = mutations.some(function (mutation) {
				return Array.prototype.some.call(mutation.addedNodes, function (node) {
					return node.nodeType === 1 && ((node.matches && node.matches('.xtpb-theme-aptitude-slider')) || (node.querySelector && node.querySelector('.xtpb-theme-aptitude-slider')));
				});
			});
			if (found) {
				window.clearTimeout(timer);
				timer = window.setTimeout(function () { initThemeAptitude(document); }, 80);
			}
		}).observe(document.body, { childList: true, subtree: true });
	}

	window.xtpbInitThemeAptitude = initThemeAptitude;
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { initThemeAptitude(document); observe(); });
	} else {
		initThemeAptitude(document);
		observe();
	}

	if (window.jQuery) {
		window.jQuery(document).on('rendered_addon', function (event, addon) {
			if (addon && addon.name && addon.name !== 'theme_aptitude') { return; }
			var iframe = window.frames['xtpb-builder-view'];
			if (iframe && iframe.window && iframe.window.document) {
				iframe.window.setTimeout(function () {
					if (iframe.window.xtpbInitThemeAptitude) { iframe.window.xtpbInitThemeAptitude(iframe.window.document); }
				}, 60);
			} else {
				window.setTimeout(function () { initThemeAptitude(document); }, 60);
			}
		});
	}
})();
