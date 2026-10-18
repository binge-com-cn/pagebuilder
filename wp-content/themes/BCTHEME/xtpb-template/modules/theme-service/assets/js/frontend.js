(function () {
	'use strict';

	function isEditorPreview() {
		var params = new URLSearchParams(window.location.search);
		return params.get('action') === 'xtpb_editor' || params.get('load_for') === 'xtpb_editor_iframe';
	}

	function init(scope) {
		var editorPreview = isEditorPreview();

		(scope || document).querySelectorAll('.module-theme-service').forEach(function (module) {
			var items = module.querySelectorAll('.service-list li');

			module.classList.toggle('service-editor-preview', editorPreview);

			items.forEach(function (item) {
				if (item.querySelector('.service-item-url')) {
					item.classList.add('has-link');
				}

				if (item.dataset.serviceBound === '1') {
					return;
				}

				item.dataset.serviceBound = '1';
				item.addEventListener('mouseenter', function () {
					if (!editorPreview && window.innerWidth < 1151) {
						return;
					}

					items.forEach(function (other) {
						other.classList.toggle('active', other === item);
					});
				});
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

	window.xtpbInitThemeService = init;

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', start);
	} else {
		start();
	}
}());
