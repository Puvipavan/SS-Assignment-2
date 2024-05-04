(function ($) {

    var $webinars = [];
    const 	$t = BACheetahStrings.webinar;

    BACheetah._registerModuleHelper('webinar', {

        /**
         * The 'init' method is called by the builder when
         * the settings form is opened.
         *
         * @method init
         */
        init: function () {
            const form = $('.ba-cheetah-settings:visible');
            const webinar_id_secret_field = form.find('select[name=webinar_id_secret]');

            webinar_id_secret_field.on('change', this._webinarChanged);

            this.getWebinars();
        },

        rules: {
            webinar_id_secret: {
                required: true
            },
        },

        getWebinars: function () {
            BACheetah.getSelectOptions('get_builderall_webinars', 'webinar_id_secret');
        },

        _webinarChanged: function () {
        },
    });

})(jQuery);
