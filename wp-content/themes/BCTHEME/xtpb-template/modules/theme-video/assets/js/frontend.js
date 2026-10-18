(function () {
	'use strict';

	var instances = [];
	var listening = false;

	function update() {
		var windowHeight = window.innerHeight;

		instances = instances.filter(function (section) {
			if (!document.documentElement.contains(section)) {
				return false;
			}

			var triggerTop = section.getBoundingClientRect().top;
			var ratio = Math.min(Math.max((windowHeight - triggerTop) / windowHeight, 0), 1);

			section.style.transform = 'translateY(' + (80 * (1 - ratio)) + 'px) scale(' + (0.8 + 0.2 * ratio) + ')';
			section.style.opacity = ratio;
			return true;
		});
	}

	function init(scope) {
		(scope || document).querySelectorAll('.module-theme-video .video-transition').forEach(function (section) {
			if (section.dataset.videoTransitionReady === '1') {
				return;
			}

			section.dataset.videoTransitionReady = '1';
			instances.push(section);
		});

		if (!listening) {
			window.addEventListener('scroll', update, { passive: true });
			window.addEventListener('resize', update);
			listening = true;
		}

		update();
	}

	window.xtpbInitThemeVideo = init;

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { init(document); });
	} else {
		init(document);
	}

	if (window.MutationObserver && document.body) {
		new MutationObserver(function (mutations) {
			var added = mutations.some(function (mutation) {
				return Array.prototype.some.call(mutation.addedNodes, function (node) {
					return node.nodeType === 1 && ((node.matches && node.matches('.module-theme-video')) || (node.querySelector && node.querySelector('.module-theme-video')));
				});
			});

			if (added) {
				init(document);
			}
		}).observe(document.body, { childList: true, subtree: true });
	}
})();
