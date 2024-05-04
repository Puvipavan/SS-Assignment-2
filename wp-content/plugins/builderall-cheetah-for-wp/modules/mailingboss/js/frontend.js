(function($) {

	BACheetahMailingboss = function( settings ){

		this.settings = settings
		this.form = $('.ba-node-' + settings.node + ' form.mailingboss__form')
		this.init()
	};

	BACheetahMailingboss.prototype = {

		init: function(){
			this._initRecaptcha()
			this._initUTMBindings()
		},

		_initUTMBindings: function() {
			if (!this.settings.utm_bindings.enabled){
				return;
			}

			const params = new URLSearchParams(location.search)
			for (const p of params) {
				let [name, value] = p;
				if ((name || '').toLowerCase().startsWith('utm')) {
					let input = this.form.find(`input[name=${name.toUpperCase()}]`)
					if (input) input.val(value)
				}
			}
		},

		_initRecaptcha: function() {

			const self = this;			
			if (!self.settings.recaptcha.enabled){
				return;
			}

			self.form.find('button').on('click', function(e) {
				e.preventDefault();

				const response = self.form.find('#g-recaptcha-response').val();

				if (!self.form[0].checkValidity()) {
					Swal.fire('This form was not filled out correctly')
				}
				else if (!response) {
					Swal.fire('Recaptcha is required!')
				}
				else {
					$.post(self.settings.recaptcha.url, { response }, function(res) {
						self.form.submit()
					}).fail( function( xhr, status, error ){
						Swal.fire('Recaptcha validation error. Please try again', xhr.responseJSON.data.join(','))
						grecaptcha.reset();
					})
				}
			})
		},

	};

})(jQuery);
