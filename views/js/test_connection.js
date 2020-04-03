/**
 *  @author Marcin Kubiak
 *  @copyright  Smart Soft
 *  @license    Commercial license
 *  International Registered Trademark & Property of Smart Soft
 */

jQuery(function ($) {

  $('#checkConnection').click(function () {
    let params = {};
    params.hostname = $('#ftp_hostname').val();
    params.port = $('#ftp_port').val();
    params.username = $('#ftp_user_name').val();
    params.password = $('#ftp_user_pass').val();
    params.path = $('#ftp_directory').val();

    if($('#save_type').length) {
      params.save_type = $('#save_type').val();
      params.export = true;
    } else {
      params.import_from = $('#import_from').val();
      params.filename = $('#filename').val();
    }

    $(this).text('Please wait...');

    $.ajax({
      type: 'POST',
      url: ae_controller_model_url + '&ajax=1',
      dataType: "json",
      data: {
        ajax: 1,
        controller: ae_controller_model,
        action: 'checkConnection',
        params: params
      },
      success: function (errors) {
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
      }
    });
  });
});
