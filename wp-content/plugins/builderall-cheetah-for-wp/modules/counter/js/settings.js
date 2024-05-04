(function($){

    BACheetah._registerModuleHelper('counter', {

        rules: {
            from: {
                number: true,
				required: true,
				min: 0,
            },
            to: {
                number: true,
				required: true,
				min: () => parseInt($(".ba-cheetah-settings input[name='from']").val()),
            }
        },
    });


})(jQuery);