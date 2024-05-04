(function ($) {
	
	var 	$calendars = []
	const 	$t = BACheetahStrings.booking;

	BACheetah._registerModuleHelper('booking', {

		/**
		 * The 'init' method is called by the builder when 
		 * the settings form is opened.
		 *
		 * @method init
		 */
		init: function () {
			const form 				= $( '.ba-cheetah-settings:visible');
			const calendar_field 	= form.find('select[name=calendar]');

			calendar_field.on( 'change', this._calendarChanged );

			this.getUserCalendars()
		},

		rules: {
            calendar: {
                required: true
            },
        },

		getUserCalendars: function() {

			const form 				= $( '.ba-cheetah-settings:visible');
			const calendar_field 	= form.find('select[name=calendar]');
			const group_tr			= form.find('#ba-cheetah-field-group');

			group_tr.css('display', 'none')

			BACheetah.getSelectOptions('get_builderall_bookings', 'calendar', function(response) {
				$calendars = response;
				calendar_field.trigger('change')
			});
		},

		_calendarChanged: function() {
			const form 				= $('.ba-cheetah-settings:visible');
			const settings 			= BACheetah._getOriginalSettings( form, true );
			const calendar_field 	= form.find('select[name=calendar]');
			const group_tr			= form.find('#ba-cheetah-field-group');
			const group_field	 	= group_tr.find('select[name=group]');
			const group_val 		= settings.group
			const active_calendar 	= $calendars.find(c => c.value == calendar_field.val());

			if (active_calendar) {

				// hide group selector if is type gym or event
				group_tr.css('display', 'table-row')

				// update group options
				group_field.empty();
				group_field.append(new Option($t.embed_all_groups, '', !group_val, !group_val));
				(active_calendar?.classes || []).forEach((option) => {
					group_field.append(new Option(
						$t.embed_only + ' ' + option.title,
						option.slug,
						group_val == option.slug,
						group_val == option.slug
					))
				})
			}

			else {
				group_tr.css('display', 'none')
			}

		},
	});

})(jQuery);