/**
 *  @author Marcin Kubiak
 *  @copyright  Smart Soft
 *  @license    Commercial license
 *  International Registered Trademark & Property of Smart Soft
 */

jQuery(function ($) {
  //remove fields if not static
  $("table.advancedexportfield tbody tr td:nth-child(5)").each(function () {
    if ($.trim($(this).html()) != "static") {
      $(this).parent().find("td:nth-child(6)").html("");
      $(this).parent().find("td:nth-child(6)").next().html("");
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

  $('span.exportspan').hide();

  $('#save_type').change(function () {
    var id = $(this).val();
    if (parseInt(id) === 3) {
      id = 1;
    }
    $('.process1').parent().prev().hide();
    $('.process1').parent().hide();
    $('.process2').parent().prev().hide();
    $('.process2').parent().hide();
    $('.process0').parent().prev().hide();
    $('.process0').parent().hide();
    var current = 'process' + id;
    $('.' + current).parent().prev().show();
    $('.' + current).parent().show();

    return false;
  });

  $('#save_type').change();

  $("input[name='only_new']:radio").change(function () {
    if ($(this).val() == 1) {
      $('.datetimepicker').closest('div.margin-form').prev().hide();
      $('.datetimepicker').closest('div.margin-form').hide();
      $('#start_id').closest('div.margin-form').prev().hide();
      $('#start_id').closest('div.margin-form').hide();
      $('#end_id').closest('div.margin-form').prev().hide();
      $('#end_id').closest('div.margin-form').hide();

    }
    if ($(this).val() == 0) {
      $('.datetimepicker').closest('div.margin-form').prev().show();
      $('.datetimepicker').closest('div.margin-form').show();
      $('#start_id').closest('div.margin-form').prev().show();
      $('#start_id').closest('div.margin-form').show();
      $('#end_id').closest('div.margin-form').prev().show();
      $('#end_id').closest('div.margin-form').show();
    }
  });

  $('span.cron_url').parent().removeAttr("onclick");

  var clipboard = new Clipboard('span.cron_button');

  clipboard.on('success', function (e) {
    alert('Copied!');
  });

  clipboard.on('error', function (e) {
    alert('Press Ctrl+C to copy');
  });

  $('#checkConnection').click(function () {
    let params = {};
    params.hostname = $('#ftp_hostname').val();
    params.port = $('#ftp_port').val();
    params.username = $('#ftp_user_name').val();
    params.password = $('#ftp_user_pass').val();
    params.path = $('#ftp_directory').val();
    params.save_type = $('#save_type').val();

    $(this).text('Please wait...');

    $.post(urlJson + '&ajax=1&action=checkConnection', {'params': params}, function (data) {
      let errors = JSON.parse(data);
      let ftp_errors = $('#ftp_errors');
      let success = $('#ftp_success');
      if (errors !== null) {
        ftp_errors.html('');
        ftp_errors.removeClass('hide');
        success.addClass('hide');
        $(errors).each(function (key, error) {
          ftp_errors.append(error);
        });
      } else {
        ftp_errors.html('');
        success.removeClass('hide');
      }
      $('#checkConnection').text('Test Connection');
    });
  });

  function getUrlParam(name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    return results[1] || 0;
  }

  $('#desc-advancedexportfield-save').click(function () {
    var form = $('.form');
    var action = form.attr('action').substring(0, form.attr('action').indexOf('#'));
    form.attr('action', action + "&submitSaveFields=1").submit();
  });
});
