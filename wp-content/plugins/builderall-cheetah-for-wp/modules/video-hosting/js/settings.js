(function ($) {

    var $videohostings = [];
    const 	$t = BACheetahStrings.video_hosting;

    BACheetah._registerModuleHelper('video-hosting', {

        /**
         * The 'init' method is called by the builder when
         * the settings form is opened.
         *
         * @method init
         */
        init: function () {
            this.getVideoHosting();
        },

        rules: {
            video_hosting: {
                required: true
            },
        },

        getVideoHosting: function () {
			BACheetah.getSelectOptions('get_builderall_videos_hosting', 'video_hosting');
        },
    });

})(jQuery);
