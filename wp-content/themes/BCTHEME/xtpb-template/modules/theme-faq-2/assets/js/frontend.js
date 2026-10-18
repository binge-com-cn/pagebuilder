(function () {
	'use strict';

	function toggle(title) {
		var item = title.closest('.post-accordion');
		var desc = title.nextElementSibling;
		var open = item.classList.toggle('active');

		title.setAttribute('aria-expanded', open ? 'true' : 'false');
		if (desc) {
			desc.style.display = open ? 'block' : 'none';
		}
	}

	function init(scope) {
		(scope || document).querySelectorAll('.module-theme-faq-2 .faq-2-accordion-title').forEach(function (title) {
			if (title.dataset.faqReady === '1') {
				return;
			}

			title.dataset.faqReady = '1';
			title.addEventListener('click', function () {
				toggle(title);
			});
			title.addEventListener('keydown', function (event) {
				if (event.key === 'Enter' || event.key === ' ') {
					event.preventDefault();
					toggle(title);
				}
			});
		});
	}

	window.xtpbInitThemeFaq2 = init;

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () {
			init(document);
		});
	} else {
		init(document);
	}

	if (window.MutationObserver && document.body) {
		new MutationObserver(function () {
			init(document);
		}).observe(document.body, { childList: true, subtree: true });
	}
}());
