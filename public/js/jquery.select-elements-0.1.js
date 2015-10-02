(function($) {
	var defaults = {
		selectedClass : "selected",
		checkSelector : "input[type=checkbox]",
		checkallSelector : "#checkall",
		elToSelect : "div"
	};

	var SelectList = function(table, options) {
		this.options = $.extend(defaults, options);
		this.init(table);
	};

	SelectList.prototype = {
		init : function(parentEl) {
			var obj = this;
			this.$parentEl = $(parentEl);

			if (this.options.checkallSelector) {
				this.checkall = $(this.options.checkallSelector);
				this.checkall.click(function(e) {
					obj.checkallOnClick(e, this)
				});
			}

			this.$parentEl.on("click", this.options.elToSelect, function(e) {
				obj.elOnClick(e, this)
			});

			// no selection when clicking the links
			this.$parentEl.on("click", this.options.elToSelect+" a", function(e) {
				e.stopPropagation();
			});

			// no text selection when using shift
			this.$parentEl.on("mousedown", this.options.elToSelect, function(e) {
				if (e.shiftKey)
					e.preventDefault();

				obj.clear = obj.getSelection().length > 0;
			});

			// highlight based on initial checkbox state
			$(this.options.elToSelect, this.$parentEl).each(function() {
				var $el = $(this);
				var $chk = $(obj.options.checkSelector, $el);
				if ($chk.length > 0) {
					var selected = $chk.prop("checked");
					obj.toggle($el, selected);
				}
			});
		},
		elOnClick : function(e, el) {
			if (this.getSelection().length > 0 || this.clear)
				return;

			var $el = $(el);

			if ($(this.options.checkallSelector, $el).length > 0)
				return;

			var selected = !$el.hasClass(this.options.selectedClass);

			this.toggle($el, selected);

			// (un)select range with shift
			if (e.shiftKey && this.last && this.lastSelected == selected
					&& this.last != $el) {
				var last = this.last.index();
				var curr = $el.index();
				var start = last < curr ? this.last : $el;
				var end = last < curr ? $el : this.last;
				var obj = this;
				start.nextUntil(end).each(function() {
					obj.toggle($(this), selected);
				});
			}

			this.last = $el; // last clicked row
			this.lastSelected = selected; // last clicked row state

			this.allChecked();

			if (this.options.onCheck)
				this.options.onCheck(this);
		},
		checkallOnClick : function(e, checkall) {
			var selected = checkall.checked;
			var obj = this;
			$(this.options.elToSelect, this.$parentEl).each(function() {
				obj.toggle($(this), selected);
			});

			if (this.options.onCheck)
				this.options.onCheck(this);
		},
		toggle : function($el, selected) {
			$el.toggleClass(this.options.selectedClass, selected);

			var $chk = $(this.options.checkSelector, $el);

			if ($chk.length > 0 && $chk.prop("checked") != selected)
				$chk.prop("checked", selected);
		},
		allChecked : function() {
			if (this.checkall) {
				var allchecked = $(this.options.elToSelect + this.options.checkSelector
						+ ":not(:checked," + this.options.checkallSelector
						+ "):first", this.$parentEl).length == 0;
				// allchecked &= $("tbody tr " + this.options.checkSelector +
				// ":not(" + this.options.checkallSelector + "):first",
				// this.$table).length > 0;
				this.checkall.prop("checked", allchecked);
			}
		},
		getSelection : function() {
			if (document.selection && document.selection.empty) {
				return document.selection.toString();
			} else if (window.getSelection) {
				var sel = window.getSelection();
				return sel.toString();
			}
		},
	};

	$.fn.selectElements = function(options) {
		new SelectList(this, options);
	};
}(jQuery));