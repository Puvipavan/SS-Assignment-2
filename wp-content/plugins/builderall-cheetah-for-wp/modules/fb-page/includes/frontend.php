<div class="ba-cheetah-fb-page">

    <script>

      if (window.fb_script_loaded === undefined) {
        window.fb_script_loaded = true;

        window.fbAsyncInit = function () {
          FB.init({
            //appId            : '253153171495958',
            autoLogAppEvents: true,
            xfbml: true,
            version: 'v3.0'
          });
        };

        (function (d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) {
            return;
          }
          js = d.createElement(s);
          js.id = id;
          js.src = "https://connect.facebook.net/<?= get_locale() ?>/sdk.js#xfbml=1&version=v13.0&appId=2341832949238900";
          js.async = true;
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
      } else {
        setTimeout(() => {
          FB.XFBML.parse();
        }, 1000)
      }

    </script>

    <div class="fb-page"
         data-href="<?php echo $settings->page_url ?>"
         data-tabs="timeline,
            <?php echo $settings->page_show_events === 'true' ? 'events':null ?>,
            <?php echo $settings->page_show_message === 'true' ? 'messages':null ?>"
         data-width="<?php echo $settings->page_width ?>"
         data-height="<?php echo $settings->page_height ?>"
         data-small-header="<?php echo $settings->page_small_header ?>"
         data-adapt-container-width="<?php echo $settings->page_adapt ?>"
         data-hide-cover="<?php echo $settings->page_hide_cover ?>"
         data-show-facepile="<?php echo $settings->page_show_facepile ?>">
        </div>

</div>
