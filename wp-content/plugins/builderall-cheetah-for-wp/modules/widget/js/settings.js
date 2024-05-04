(function($){

    BACheetah.registerModuleHelper('widget', {

        init: function()
        {
            var form    = $('.ba-cheetah-settings'),
                missing = form.find('.ba-cheetah-widget-missing'),
								inputs = form.find('input');

						$.each(inputs, function(i,v){
							$(v).addClass('ba-cheetah-ignore-validation');
						})
            if(missing.length > 0) {
                form.find('.ba-cheetah-settings-save, .ba-cheetah-settings-save-as').hide();
            }
        }
    });

})(jQuery);
