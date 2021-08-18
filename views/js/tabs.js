/**
 *  @author Marcin Kubiak
 *  @copyright  Smart Soft
 *  @license    Commercial license
 *  International Registered Trademark & Property of Smart Soft
 */

jQuery(function ($) {
  $('#entities a.entity ').on('click', function () {
    var entity = $(this).attr('id');

    if ($('div[alt="model-' + entity + '"]').is(':empty')) {
      showLoader();
      $.ajax({
        type: 'POST',
        url: ae_controller_model_url + '&type=' + entity,
        data: {
          ajax: 1,
          controller: ae_controller_model,
          action: 'getExportForm'
        },
        success: function (data) {
          $('div[alt="model-' + entity + '"]').html(data);
          hideLoader();
        }
      });
    }
    // if ($('div[alt="file-' + entity + '"]').is(':empty')) {
    //   $.ajax({
    //     type: 'POST',
    //     url: ae_controller_file_url + '&type=' + entity,
    //     data: {
    //       ajax: 1,
    //       controller: ae_controller_file,
    //       action: 'getExportForm'
    //     },
    //     success: function (data) {
    //       $('div[alt="file-' + entity + '"]').html(data);
    //       $('#right-column').fadeIn('fast');
    //     }
    //   });
    // }
  });

  $('#entities #import').on('click', function () {
    if ($('.list-group[alt="import"]').is(':empty')) {
      showLoader();
      $.ajax({
        type: 'POST',
        url: ae_controller_import_url,
        data: {
          ajax: 1,
          controller: ae_controller_import,
          action: 'getExportForm'
        },
        success: function (data) {
          $('.list-group[alt="import"]').html(data);
          hideLoader();
        }
      });
    }
  });

  $('#entities #cron').on('click', function () {
    if ($('.list-group[alt="cron"]').is(':empty')) {
      showLoader();
      $.ajax({
        type: 'POST',
        url: ae_controller_cron_url,
        data: {
          ajax: 1,
          controller: ae_controller_cron,
          action: 'getExportForm'
        },
        success: function (data) {
          $('.list-group[alt="cron"]').html(data);
          hideLoader();
        }
      });
    }
  });

  $('#entities .list-group-item').click(function () {
    var id = $(this).attr('id');
    $('.list-group-item').removeClass('active');
    $(this).addClass('active');

    $('.tab-content').addClass('hide');
    $('.tab-content[alt=' + id + ']').removeClass('hide');

    var current_tab_id = $('div.list-group a.list-group-item.active').attr('id');
    $.cookie('current_tab_id', current_tab_id);

    return false;
  });

  if ($.cookie('current_tab_id')) {
    var current_tab_id = 'a#' + $.cookie('current_tab_id');
    $('div.list-group a.list-group-item').removeClass('active');
    $(current_tab_id).click();
  } else {
    $('div.list-group a:first').click();
  }
});

function showLoader() {
  $('#right-column div.tab-content').hide();
  $('#ajax-loader').show();
}

function hideLoader() {
  $('#ajax-loader').hide();
  $('#right-column div.tab-content').fadeIn();
}

