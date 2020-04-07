/**
 *  @author Marcin Kubiak
 *  @copyright  Smart Soft
 *  @license    Commercial license
 *  International Registered Trademark & Property of Smart Soft
 */

jQuery(function ($) {
  $('#import_from').change(function () {
    var id = parseInt($(this).val());

    $('.import_from_model').closest('.form-group').hide();
    $('#advancedexportimport_form #upload_file').parents(".form-group").eq(1).hide();
    $('.import_from_url').closest('.form-group').hide();
    $('.import_from_ftp').closest('.form-group').hide();

    $('.custom-hide').closest('.form-group').hide();
    $('#filename').closest('.form-group').hide();

    if (id === 0) {
      $('.import_from_model').closest('.form-group').show();
    }
    if (id === 1) {
      $('#advancedexportimport_form #upload_file').parents(".form-group").eq(1).show();
      $('.custom-hide').closest('.form-group').show();
    }
    if (id === 2) {
      $('.import_from_url').closest('.form-group').show();
      $('.custom-hide').closest('.form-group').show();
    }

    if (id === 3 || id === 4) {
      $('.import_from_ftp').closest('.form-group').show();
      $('#filename').closest('.form-group').show();
      $('.custom-hide').closest('.form-group').show();
    }

    return false;
  });

  $('#import_from').change();

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

  $('button#auto-select').on('click', function () {
    var fields = document.querySelectorAll('select[id^="fields"]');
    fields.forEach(function(field) {
      // console.log(field);
      var parent = field.closest('div.form-group');
      var text = parent.querySelector('label').textContent.trim();
      // console.log(text);
      var selectOptions = field.options;
      for (var opt, j = 0; opt = selectOptions[j]; j++) {
        if (opt.text.toLowerCase() === text.toLocaleLowerCase()) {
          console.log(field);
          field.selectedIndex = j;
          $(field).trigger("chosen:updated");
          break;
        }
      }
    });
  });
});


