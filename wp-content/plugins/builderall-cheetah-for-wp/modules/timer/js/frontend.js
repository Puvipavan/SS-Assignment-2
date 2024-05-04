(function($) {

	BACheetahTimer = function( data ){

		this.timer_type 		= data?.timer_type || null;
		this.final_due_date 	= data?.final_due_date || null;
		this.cookie_name 		= data?.cookie_name || null;
		this.from_now_seconds 	= data?.from_now_seconds || null;
		this.action 			= data?.action || null;
		this.action_url			= data?.action_url || null;
		this.node_id 			= data.node;
		this.node_selector		= '.ba-node-' + data.node;

		this._initTimer()
	};

	BACheetahTimer.prototype = {

		_initTimer: function() {

			const end_date 	= this._getEndDate()
			const wrapper 	= $(this.node_selector + ' .timer__counter')
			const that 		= this;

			wrapper.countdown(end_date, function(event) {
				wrapper.find('.timer__output__days').html(event.strftime('%D'));
				wrapper.find('.timer__output__hours').html(event.strftime('%H'));
				wrapper.find('.timer__output__minutes').html(event.strftime('%M'));
				wrapper.find('.timer__output__seconds').html(event.strftime('%S'));
		
				if (event.type == 'finish') {
					that._timerFinished()
				}
			});
		},


		_getEndDate: function() {

			// mode due date returns the date
			if (this.timer_type == 'due_date') {
				return this.final_due_date
			} 
			else {

				const end_from_now = new Date(new Date().getTime() + this.from_now_seconds)

				// mode from now returns now + from_now_unix_seconds
				if (this.timer_type == 'from_now') {
					return end_from_now;
				}

				// mode from now cookie
				else if (this.timer_type == 'from_now_cookie') {
					let started_at = Cookies.get(this.cookie_name)

					// if the cookie is not set, set and count from now
					if (typeof started_at == 'undefined') {
						Cookies.set(this.cookie_name, new Date().toISOString())
						return end_from_now;

					// if the cookie is set, it counts from it
					} else {
						const end_from_cookie = new Date(new Date(started_at).getTime() + this.from_now_seconds)
						return end_from_cookie;
					}
				}
			}
		},

		_timerFinished: function() {
			switch (this.action) {
				case 'display_message':
					$(this.node_selector + ' .timer__message').show()
					break;

				case 'hide':
					$(this.node_selector).hide()
					break;

				case 'redirect_to_url':
					// dont redirect in editing mode
					if (this.action_url && typeof BACheetahConfig === 'undefined') {
						location.href = this.action_url
					}
					break;

				default:
					break;
			}
		}

	};

})(jQuery);
