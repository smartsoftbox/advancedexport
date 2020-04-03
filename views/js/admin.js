/**
 *  @author Marcin Kubiak
 *  @copyright  Smart Soft
 *  @license    Commercial license
 *  International Registered Trademark & Property of Smart Soft
 */

jQuery(function ($) {
  //remove fields if not static
  $("td.ds-table").each(function () {
    if ($.trim($(this).html()) != "static") {
      $(this).parent().find("td.ds-return").html("");
      $(this).parent().find("td.ds-return").next().html("");
    }
  });

  var $topLoader = $("#topLoader").percentageLoader({width: 233, height: 233, progress: 0});
  var topLoaderRunning = false;

  $(document.body).on('click', 'a.export', function () {
    $('#loader-container').css("display", "block");
    // Ignore the click if the animation is already in progress
    if (topLoaderRunning) {
      return false;
    }
    // Set some initial values
    topLoaderRunning = true;
    $topLoader.setProgress(0);
    // We're pretending
    $topLoader.setValue('');

    // A function representing a single 'frame' of our animation
    var animateFunc = function () {
      $.ajax({
        type: 'POST',
        cache: false,
        async: true,
        url: '/modules/advancedexport/classes/Export/progress.txt',
        dataType: "json",
        success: function (response) {
          $topLoader.setProgress(response.current / response.total);
          // $topLoader.setValue(dataObject.total.toString());

          if (response.current < response.total) {
            setTimeout(animateFunc, 1000);
          } else {
            topLoaderRunning = false;
            clearInterval(animateFunc);
          }
        }
      });
    };
    setTimeout(animateFunc, 1000);
  });

  $(".ds-select").each(function () {
    var selectList = $(this).find('option:selected');
    selectList.sort(sort_li);

    var unSelectList = $(this).find("option:not(:selected)");

    $(this).html(selectList);
    $(this).append(unSelectList);
  });

  function sort_li(a, b) {
    return ($(b).data('sortby')) < ($(a).data('sortby')) ? 1 : -1;
  }

  $('#save_type').change(function () {
    var id = $(this).val();
    if (parseInt(id) === 3) {
      id = 1;
    }
    $('.process1').closest('.form-group').hide();
    $('.process2').closest('.form-group').hide();
    $('.process0').closest('.form-group').hide();
    var current = 'process' + id;
    $('.' + current).closest('.form-group').show();
    return false;
  });

  $('#import_from').change();
  $('#import_from').change(function () {
    var id = $(this).val();
    $('.import_from_model').closest('.form-group').hide();
    $('#advancedexportimport_form #filename').parents(".form-group").eq(1).hide();
    $('.import_from_url').closest('.form-group').hide();
    $('.custom-hide').closest('.form-group').hide();

    if (parseInt(id) === 0) {
      $('.import_from_model').closest('.form-group').show();
    }
    if (parseInt(id) === 1) {
      $('#advancedexportimport_form #filename').parents(".form-group").eq(1).show();
      $('.custom-hide').closest('.form-group').show();
    }
    if (parseInt(id) === 2) {
      $('.import_from_url').closest('.form-group').show();
      $('.custom-hide').closest('.form-group').show();
    }

    return false;
  });

  $('select#entity').on('change', function () {
    $('input#truncate_on').closest('.form-group').show();
    $('input#regenerate_on').closest('.form-group').show();
    $('input#forceIDs_on').closest('.form-group').show();

    if (!importEntities[this.value]['delete']) {
      $('input#truncate_on').closest('.form-group').hide();
    }
    if (!importEntities[this.value]['skip']) {
      $('input#regenerate_on').closest('.form-group').hide();
    }
    if (!importEntities[this.value]['force']) {
      $('input#forceIDs_on').closest('.form-group').hide();
    }
  });

  $('#save_type').change();

  $("input[name='only_new']:radio").change(function () {
    if ($(this).val() == 1) {
      $('.datetimepicker').closest('div.form-group').slideUp();
      $('#start_id').closest('div.form-group').slideUp();
      $('#end_id').closest('div.form-group').slideUp();
    }
    if ($(this).val() == 0) {
      $('.datetimepicker').closest('div.form-group').slideDown();
      $('#start_id').closest('div.form-group').slideDown();
      $('#end_id').closest('div.form-group').slideDown();
    }
  });

  $('span.cron_url').parent().removeAttr("onclick");

  var clipboard = new Clipboard('span.cron_button');

  clipboard.on('success', function (e) {
    setTooltip(e.trigger, 'Copied!');
    hideTooltip(e.trigger);
  });

  clipboard.on('error', function (e) {
    setTooltip(e.trigger, 'Press Ctrl+C to copy');
    hideTooltip(e.trigger);
  });

  $('span.cron_button').tooltip({
    trigger: 'click',
    placement: 'bottom'
  });

  function setTooltip(btn, message) {
    $(btn).tooltip('hide')
      .attr('data-original-title', message)
      .tooltip('show');
  }

  function hideTooltip(btn) {
    setTimeout(function () {
      $(btn).tooltip('hide');
    }, 1000);
  }


  function getUrlParam(name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    return results[1] || 0;
  }

  $('#desc-advancedexportfield-save').click(function () {
    var form = $('#form-advancedexportfield');
    var action = form.attr('action').substring(0, form.attr('action').indexOf('#'));
    form.attr('action', action + "&submitSaveFields=1").submit();
  });


  $(document.body).on('click', 'a.files-import', function (e) {
    $('#form-importfiles').hide();
    $('#ajax-loader').show();
    e.preventDefault();
    $.ajax({
      type: 'POST',
      url: $(this).attr('href') + '&ajax=1&action=getExportForm',
      success: function (data) {
        if($('#form-importfiles').length) {
          $('#form-importfiles').replaceWith(data);
        } else {
          $('#form-advancedexportimport').after(data);
        }
        $('#ajax-loader').hide();
        $('#form-importfiles').fadeIn();
      }
    });
  });
});


