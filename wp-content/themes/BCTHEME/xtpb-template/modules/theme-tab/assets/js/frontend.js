(function () {
	'use strict';

	function activate(module, index) {
		var tabs = module.querySelectorAll('.module-tab-menu li');
		var panels = module.querySelectorAll('.module-tab-box > .tab-item');

		tabs.forEach(function (tab, tabIndex) {
			var selected = tabIndex === index;
			var button = tab.querySelector('[role="tab"]');

			tab.classList.toggle('selected', selected);
			if (button) {
				button.setAttribute('aria-selected', selected ? 'true' : 'false');
			}
		});

		panels.forEach(function (panel, panelIndex) {
			var active = panelIndex === index;

			panel.hidden = !active;
			panel.classList.toggle('is-active', active);
			// Remove display:block left by the legacy global TAB handler.
			panel.style.removeProperty('display');
		});
	}

	function init(scope) {
		(scope || document).querySelectorAll('.module-theme-tab').forEach(function (module) {
			if (module.dataset.themeTabBound === '1') {
				return;
			}

			module.dataset.themeTabBound = '1';
			activate(module, 0);

			// Capture the event before the theme's old global .module-tab-menu
			// handler, which uses jQuery.show() and changes flex panels to block.
			module.addEventListener('click', function (event) {
				var tab = event.target.closest('.module-tab-menu li');
				var tabs;
				var index;

				if (!tab || !module.contains(tab)) {
					return;
				}

				tabs = Array.prototype.slice.call(module.querySelectorAll('.module-tab-menu li'));
				index = tabs.indexOf(tab);
				if (index < 0) {
					return;
				}

				event.stopPropagation();
				activate(module, index);
			}, true);
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

	window.xtpbInitThemeTab = init;
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', start);
	} else {
		start();
	}
}());
