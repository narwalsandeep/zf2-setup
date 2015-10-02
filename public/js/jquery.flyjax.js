/**
 * @author narwal sandeep at gee mail dot com
 * @param $
 * 
 * @license Licensed under the MIT licenses:
 *          http://www.opensource.org/licenses/mit-license.php
 */
(function($) {

	var defaults = {
		identifier : "flyjax",
		disableSubmitButton : true,
		disabledSubmitButtonText : "Sending ...",

		noty : "", // user must assign noty from config e.g. noty:noty
		enableNoty : true,
		notySuccess : "",
		notyFailure : "",
		notyLayout : 'topRight',
		notyTheme : 'relax',

	}

	var SubmitMethod = function(form, options, success, failure) {

		this.options = $.extend(defaults, options);
		this.form = form;
		this.successCallback = success;
		this.failureCallback = failure;
		this.init();
	}

	SubmitMethod.prototype = {
		init : function() {

			this.setSubmitButtonText(this.getSubmitButton().html());

			// in case noty is not defined, disable its use
			if (this.getOptions().noty == "") {
				this.getOptions().enableNoty = false;
			}
			this.triggerSubmit();
		},
		triggerSubmit : function() {
			instance = this.getInstance();
			this.getForm().submit(function(event) {
				if (!instance.getSubmitButton()) {
					return false;
				}
				if (instance.getOptions().disableSubmitButton) {
					instance.toggleSubmitButton(true);
				}
				$.ajax({
					type : instance.getForm().attr('method'),
					url : instance.getForm().attr('action'),
					data : instance.getForm().serialize()
				}).done(function(response) {
					instance.triggerSuccess(response);
				}).fail(function(response) {
					instance.triggerFailure(response);
				});
				event.preventDefault();
			});
		},
		getInstance : function() {
			return this;
		},
		getForm : function() {
			return this.form;
		},
		getOptions : function() {
			return this.options;
		},
		getSubmitButton : function() {
			btn = $(this.getForm()).find("button[type=submit]").first();

			if (btn.attr("type") == "submit") {
				return btn;
			}

			alert("To use flyjax, you must have a <button> with type='submit' within the form.");
			return false;

		},
		getSuccessCallback : function() {
			return this.successCallback;
		},
		getFailureCallback : function() {
			return this.failureCallback;
		},
		setSubmitButtonText : function(text) {
			this.submitButtonText = text;
		},
		getSubmitButtonText : function() {
			return this.submitButtonText;
		},
		toggleSubmitButton : function(state) {
			if (state) {
				$(this.getSubmitButton()).text(
						this.getOptions().disabledSubmitButtonText);
				$(this.getSubmitButton()).prop("disabled", true);
			} else {
				$(this.getSubmitButton()).text(this.submitButtonText);
				$(this.getSubmitButton()).prop("disabled", false);
			}

		},
		triggerSuccess : function(response) {
			this.successCallback(this.getForm(), response);
			this.triggerNoty("success");
		},
		triggerFailure : function(response) {
			this.failureCallback(this.getForm(), response);
			this.triggerNoty("failure");
		},
		triggerNoty : function(status) {
			if (status == "success") {
				msg = this.getOptions().notySuccess;
				cls = "success";
			}
			if (status == "failure") {
				msg = this.getOptions().notyFailure;
				cls = "error";
			}
			if (this.getOptions().enableNoty) {
				this.getOptions().noty({
					text : msg,
					type : cls,
					layout : this.getOptions().notyLayout,
					theme : this.getOptions().notyTheme
				});
			}
			this.toggleSubmitButton(false);

		}
	}

	$.fn.flyjax = function(options, successCallback, errorCallback) {
		new SubmitMethod(this, options, successCallback, errorCallback);
	}

}(jQuery));