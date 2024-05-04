(function ($) {

  $('.float-button__menu-toggle').on("click", function () {
    $(this).parent().toggleClass('open');
    $('.option').toggleClass('scale-on');
  });

})(jQuery);
