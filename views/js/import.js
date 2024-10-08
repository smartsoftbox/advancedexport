/**
 *  @author Marcin Kubiak
 *  @copyright  Smart Soft
 *  @license    Commercial license
 *  International Registered Trademark & Property of Smart Soft
 */

var importCancelRequest = false;
var importContinueRequest = false;

jQuery(function ($) {
  $(document.body).on('click', 'a.start-import', function (e) {
    e.preventDefault();
    $('#importProgress').modal('show');
    var id = $(this).attr('alt');

    importNow(id, 0, 5, -1, true, {}, 0);
  });

  $(document.body).on('click', 'button#import_stop_button', function (e) {
    if (importContinueRequest) {
      $('#importProgress').modal('hide');
      importContinueRequest = false;
      window.location.href = window.location.href.split('#')[0]; // reload same URL but do not POST again (so in GET without param)
    } else {
      importCancelRequest = true;
      $('#import_details_progressing').hide();
      $('#import_details_finished').hide();
      $('#import_details_stop').show();
      $('#import_stop_button').hide();
      $('#import_close_button').hide();
    }
  });
});

function importNow(id, offset, limit, total, validateOnly, crossStepsVariables, moreStep) {
  if (offset == 0 && validateOnly) updateProgressionInit(); // first step only, in validation mode
  if (offset == 0 && !validateOnly) updateProgression(0, total, limit, false, moreStep, null);

  var startingTime = new Date().getTime();

  $.ajax({
    type: 'POST',
    url: ae_controller_import_url + '&ajax=1&action=import&id=' + id + '&offset=' + offset + '&limit=' + limit + (validateOnly ? '&validateOnly=1' : '')+((moreStep>0)?'&moreStep='+moreStep:''),
    dataType: "json",
    data: {
      ajax: 1,
      controller: ae_controller_import,
      action: 'import',
      crossStepsVars: JSON.stringify(crossStepsVariables)
    },
    success: function (jsonData) {
      if (jsonData.totalCount) {
        total = jsonData.totalCount;
      }

      if (jsonData.informations && jsonData.informations.length > 0) {
        updateValidationInfo('<li>' + jsonData.informations.join('</li><li>') + '</li>');
      }
      if (jsonData.warnings && jsonData.warnings.length > 0) {
        if (validateOnly)
          updateValidationError('<li>' + jsonData.warnings.join('</li><li>') + '</li>', true);
        else
          updateProgressionError('<li>' + jsonData.warnings.join('</li><li>') + '</li>', true);
      }
      if (jsonData.errors && jsonData.errors.length > 0) {
        if (validateOnly)
          updateValidationError('<li>' + jsonData.errors.join('</li><li>') + '</li>', false);
        else
          updateProgressionError('<li>' + jsonData.errors.join('</li><li>') + '</li>', false);
        return; // If errors, stops process
      }

      // Here, no errors returned
      if (!jsonData.isFinished == true) {
        // compute time taken by previous call to adapt amount of elements by call.
        var previousDelay = new Date().getTime() - startingTime;
        var targetDelay = 5000; // try to keep around 5 seconds by call
        // acceleration will be limited to 4 to avoid newLimit to increase too fast (NEVER reach 30 seconds by call!).
        var acceleration = Math.min(4, (targetDelay / previousDelay));
        // keep between 5 to 100 elements to process in one call
        var newLimit = Math.min(100, Math.max(5, Math.floor(limit * acceleration)));
        var newOffset = offset + limit;
        // update progression
        if (validateOnly) {
          updateValidation(jsonData.doneCount, total, jsonData.doneCount + newLimit);
        } else {
          updateProgression(jsonData.doneCount, total, jsonData.doneCount + newLimit, false, moreStep, jsonData.moreStepLabel);
        }

        if (importCancelRequest == true) {
          $('#importProgress').modal('hide');
          importCancelRequest = false;
          window.location.href = window.location.href.split('#')[0]; // reload same URL but do not POST again (so in GET without param)
          return; // stops execution
        }

        // process next group of elements
        importNow(id, newOffset, newLimit, total, validateOnly, jsonData.crossStepsVariables, moreStep);

        // checks if we could go over post_max_size setting. Warns when reach 90% of the actual setting
        if (jsonData.nextPostSize >= jsonData.postSizeLimit * 0.9) {
          var progressionDone = jsonData.doneCount * 100 / total;
          var increase = Math.max(7, parseInt(jsonData.postSizeLimit / (progressionDone * 1024 * 1024))) + 1; // min 8MB
          $('#import_details_post_limit_value').html(increase + " MB");
          $('#import_details_post_limit').show();
        }

      } else {
        if (validateOnly) {
          // update validation bar and process real import
          updateValidation(total, total, total);
          if (!$('#import_details_warning').is(":visible")) {
            // no warning, directly import now
            $('#import_progress_div').show();
            importNow(id, 0, 5, total, false, {}, 0);
          } else {
            // warnings occured. Ask if should continue to true import now
            importContinueRequest = true;
            $('#import_continue_button').show();
          }
        } else {
          if (jsonData.oneMoreStep > moreStep) {
            updateProgression(total, total, total, false, false, null); // do not close now
            importNow(id, 0, 5, total, false, jsonData.crossStepsVariables, jsonData.oneMoreStep);
          } else {
            updateProgression(total, total, total, true, moreStep, jsonData.moreStepLabel);
          }

        }
      }
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
      if (textStatus == 'parsererror') {
        textStatus = 'Technical error: Unexpected response returned by server. Import stopped.';
      }
      if (validateOnly) {
        updateValidationError(textStatus, false);
      } else {
        updateProgressionError(textStatus, false);
      }
    }
  });
}
function updateValidationError(message, forWarnings) {
  $('#import_details_progressing').hide();
  $('#import_details_finished').hide();
  if (forWarnings) {
    $('#import_details_warning ul').append(message);
    $('#import_details_warning').show();
  } else {
    $('#import_details_error ul').append(message);
    $('#import_details_error').show();

    $('#validate_progressbar_next').addClass('progress-bar-danger');
    $('#validate_progressbar_next').removeClass('progress-bar-info');

    $('#import_stop_button').hide();
    $('#import_close_button').show();
  }
}

function updateValidationInfo(message) {
  $('#import_details_progressing').hide();
  $('#import_details_finished').hide();
  $('#import_details_info ul').append(message);
  $('#import_details_info').show();
}

function updateProgressionError(message, forWarnings) {
  $('#import_details_progressing').hide();
  $('#import_details_finished').hide();
  if (forWarnings) {
    $('#import_details_warning ul').append(message);
    $('#import_details_warning').show();
  } else {
    $('#import_details_error ul').append(message);
    $('#import_details_error').show();

    $('#import_progressbar_next').addClass('progress-bar-danger');
    $('#import_progressbar_next').removeClass('progress-bar-success');

    $('#import_stop_button').hide();
    $('#import_close_button').show();
  }
}

function updateProgressionInit() {
  $('#importProgress').modal({backdrop: 'static', keyboard: false, closable: false});
  $('#importProgress').modal('show');
  $('#importProgress').on('hidden.bs.modal', function () {
    // window.location.href = window.location.href.split('#')[0]; // reload same URL but do not POST again (so in GET without param)
  });

  $('#import_details_progressing').show();
  $('#import_details_finished').hide();
  $('#import_details_error').hide();
  $('#import_details_warning, #import_details_info').hide();
  $('#import_details_stop').hide();
  $('#import_details_post_limit').hide();
  $('#import_details_error ul').html('');
  $('#import_details_warning ul, #import_details_info ul').html('');

  $('#import_validation_details').html($('#import_validation_details').attr('default-value'));
  $('#validate_progressbar_done').width('0%');
  $('#validate_progressbar_done').parent().addClass('active progress-striped');
  $('#validate_progression_done').html('0');
  $('#validate_progressbar_done2').width('0%');
  $('#validate_progressbar_next').width('0%');
  $('#validate_progressbar_next').removeClass('progress-bar-danger');
  $('#validate_progressbar_next').addClass('progress-bar-info');

  $('#import_progress_div').hide();
  $('#import_progression_details').html($('#import_progression_details').attr('default-value'));
  $('#import_progressbar_done').width('0%');
  $('#import_progressbar_done').parent().addClass('active progress-striped');
  $('#import_progression_done').html('0');
  $('#import_progressbar_next').width('0%');
  $('#import_progressbar_next').removeClass('progress-bar-danger');
  $('#import_progressbar_next').addClass('progress-bar-success');

  $('#import_stop_button').show();
  $('#import_close_button').hide();
  $('#import_continue_button').hide();
}

function updateValidation(currentPosition, total, nextPosition) {
  if (currentPosition > total) currentPosition = total;
  if (nextPosition > total) nextPosition = total;

  var progressionDone = currentPosition * 100 / total;
  var progressionNext = nextPosition * 100 / total;

  if (total > 0) {
    $('#import_validate_div').show();
    $('#import_validation_details').html(currentPosition + '/' + total);
    $('#validate_progressbar_done').width(progressionDone + '%');
    $('#validate_progression_done').html(parseInt(progressionDone));
    $('#validate_progressbar_next').width((progressionNext - progressionDone) + '%');
  }

  if (currentPosition == total && total == nextPosition) {
    $('#validate_progressbar_done').parent().removeClass('active progress-striped');
  }
}

function updateProgression(currentPosition, total, nextPosition, finish, moreStep, moreStepLabel) {
  if (currentPosition > total) currentPosition = total;
  if (nextPosition > total) nextPosition = total;

  var progressionDone = currentPosition * 100 / total;
  var progressionNext = nextPosition * 100 / total;

  if (total > 0) {
    $('#import_progress_div').show();
    $('#import_progression_details').html(currentPosition + '/' + total);
    if (moreStep == 0) {
      $('#import_progressbar_done').width(progressionDone + '%');
      $('#import_progression_done').html(parseInt(progressionDone));
      $('#import_progressbar_next').width((progressionNext - progressionDone) + '%');
    } else {
      $('#import_progressbar_next').width('0%');
      $('#import_progressbar_done').width((100 - progressionDone) + '%');
      $('#import_progressbar_done2').width(progressionDone + '%');
      if (moreStepLabel) $('#import_progressbar_done2 span').html(moreStepLabel);
    }
  }

  if (finish) {
    $('#import_progressbar_done').parent().removeClass('active progress-striped');
    $('#import_details_post_limit').hide();
    $('#import_details_progressing').hide();
    $('#import_details_finished').show();
    $('#importProgress').modal({keyboard: true, closable: true});
    $('#import_stop_button').hide();
    $('#import_close_button').show();
  }
}

