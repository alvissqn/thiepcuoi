/*=========================================================================================
	File Name: input-groups.js
	Description: Input Groups js
	----------------------------------------------------------------------------------------
	Item Name: Frest HTML Admin Template
	Version: 1.0
	Author: PIXINVENT
	Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

(function (window, document, $) {
  'use strict';
  var $html = $('html');

  // Default Spin
  $(".touchspin").TouchSpin({
    buttondown_class: "btn btn-sm btn-primary",
    buttonup_class: "btn btn-sm btn-primary",
  });

  // Icon Change
  $(".touchspin-icon").TouchSpin({
    buttondown_txt: '<i class="bx bx-chevron-down"></i>',
    buttonup_txt: '<i class="bx bx-chevron-up"></i>'
  });

  // Min - Max
$(".touchspin-min-max").each(function(){
  var touchspinValue = $(this),
      counterMin = $(this).attr('min'),
      counterMax = $(this).attr('max');
    if (touchspinValue.length > 0) {
      touchspinValue.TouchSpin({
        min: counterMin,
        max: counterMax,
        step: $(this).attr('step'),
        decimals: $(this).attr('data-decimals')
      }).on('touchspin.on.startdownspin', function () {
        var $this = $(this);
        $('.bootstrap-touchspin-up').removeClass("disabled-max-min");
        if ($this.val() == counterMin) {
          $(this).siblings().find('.bootstrap-touchspin-down').addClass("disabled-max-min");
        }
      }).on('touchspin.on.startupspin', function () {
        var $this = $(this);
        $('.bootstrap-touchspin-down').removeClass("disabled-max-min");
        if ($this.val() == counterMax) {
          $(this).siblings().find('.bootstrap-touchspin-up').addClass("disabled-max-min");
        }
      });
    }
});

  // Color Options
  $(".touchspin-color").each(function (index) {
    var down = "btn btn-sm btn-primary",
      up = "btn btn-sm btn-primary",
      $this = $(this);
    if ($this.data('bts-button-down-class')) {
      down = $this.data('bts-button-down-class');
    }
    if ($this.data('bts-button-up-class')) {
      up = $this.data('bts-button-up-class');
    }
    $this.TouchSpin({
      buttondown_class: down,
      buttonup_class: up,
      mousewheel: false,
      buttondown_txt: '<i class="bx bx-minus"></i>',
      buttonup_txt: '<i class="bx bx-plus"></i>'
    });
  });

  // Step
  $(".touchspin-vertical").TouchSpin({
    verticalbuttons: true
  });

})(window, document, jQuery);
