/**
 *  @author Marcin Kubiak
 *  @copyright  Smart Soft
 *  @license    Commercial license
 *  International Registered Trademark & Property of Smart Soft
 */

jQuery(function ($) {

  $('body').on('click', '.list-group .list-group-item', function () {
    $(this).toggleClass('active');
  });
  $('#filter-group select').change(function (e) {
    // get all active filter
    var group = $(this).val();

    if (group == 'all') {
      $(this).closest('.well').find('ul.list-group li').removeClass('filters_hide');
      return false;
    }
    // clean all filters and clean all check groups
    $(this).closest('.well').find('ul.list-group li').addClass('filters_hide');
    // show all li with filter groups and change icon for button

    // remove filters_hide to show
    $(this).closest('.well').find('ul.list-group li[rel="' + group + '"]').removeClass('filters_hide');
    // apply search result to the new filtered list
    $('[name="SearchDualList"]').keyup();

    return false;
  });

  $('#filter-group select').change(); // change on start

  function addSlectedValue() {
    // create hidden field with all ids from right column and store as comma sepparator
    var all_ids = {};
    $('.list-right ul.list-group li').each(function () {
      var id = $(this).attr("id").replace('option-', '');
      all_ids[id] = [];
      all_ids[id].push($(this).find('input').val());
    });
    var all_ids_stringify = JSON.stringify(all_ids);
    $("input#fields").val(all_ids_stringify);
  }

  $('.list-arrows a').click(function (e) {
    var $button = $(this), actives = '';

    if ($button.hasClass('move-left')) {
      // get visible li active (selected) but not hidden by filters (filters_hide)
      actives = $('.list-right ul.list-group li.active:not(.filters_hide)');
      actives.find('span').removeClass('hide');
      actives.find('input').addClass('hide');
      actives.clone().appendTo('.list-left ul.list-group'); //clone
      actives.remove();
      selector = $('.list-left .selector');

      $('.list-left ul li.active').removeClass('active');
      if (selector.hasClass('selected')) {
        // replace icon from i (select all buttton)
        selector.children('i').removeClass('icon-check-square-o').addClass('icon-check-empty');
        selector.removeClass('selected'); // uncheck a element (select all button)
      }
    } else if ($button.hasClass('move-right')) {
      // get visible li active (selected) but not hidden by filters (filters_hide)
      actives = $('.list-left ul.list-group li.active:not(.filters_hide)');
      actives.find('span').addClass('hide');
      actives.find('input').removeClass('hide');
      actives.clone().appendTo('.list-right ul.list-group'); //clone
      actives.remove();
      selector = $('.list-right .selector');

      $('.list-right ul li.active').removeClass('active');
      if (selector.hasClass('selected')) {
        // replace icon from i (select all buttton)
        selector.children('i').removeClass('icon-check-square-o').addClass('icon-check-empty');
        selector.removeClass('selected'); // uncheck a element (select all button)
      }
    } else if ($button.hasClass('move-up')) {
      // get visible li active (selected) but not hidden by filters (filters_hide)
      actives = $('.list-right ul.list-group li.active:not(.filters_hide)');
      // get first elements selected but not hidden by filter
      first = $('.list-right ul li.active:not(.filter_hide):first');
      before = first.prev(); // get element before selected
      actives.each(function () {
        $(this).insertBefore(before); // add each selected before
      });
    } else if ($button.hasClass('move-down')) {
      // get visible li active (selected) but not hidden by filters (filters_hide)
      actives = $('.list-right ul.list-group li.active:not(.filters_hide)');
      // get first elements selected but not hidden by filter
      last = $('.list-right ul li.active:not(.filter_hide):last');
      before = last.next();
      actives.each(function () {
        $(this).insertAfter(before);
      });
    }

    addSlectedValue();

    return false;
  });

  addSlectedValue(); // serialize on start field selected

  // version 1.7
  $('.list-right ul.list-group').on('change', 'input', function (e) {
    addSlectedValue();
  });

  $('.list-right ul.list-group').on('click', 'input', function (event) {
    event.stopPropagation();
  });

  // version 1.6
  // $('.list-right ul.list-group li input').live('change', function (e) {
  //   addSlectedValue();
  // });
  //
  // $('.list-right ul.list-group li input').live('click', function (event) {
  //   $(this).parent().removeClass('active');
  // });

  $('.dual-list .selector').click(function (e) {
    var $checkBox = $(this);
    if (!$checkBox.hasClass('selected')) {
      $checkBox.addClass('selected').closest('.well').find('ul.list-group li:not(.active)').addClass('active');
      $checkBox.children('i').removeClass('icon-check-empty').addClass('icon-check-square-o');
    } else {
      $checkBox.removeClass('selected').closest('.well').find('ul.list-group li.active').removeClass('active');
      $checkBox.children('i').removeClass('icon-check-square-o').addClass('icon-check-empty');
    }
    return false;
  });
  $('.list-left input[name="SearchDualList"]').keyup(function (e) {
    var code = e.keyCode || e.which;
    if (code == '9') return;
    if (code == '27') $(this).val(null);
    var $rows = $(this).closest('.dual-list').find('.list-group li:not(.filters_hide)');
    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
    $rows.show().filter(function () {
      var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
      return !~text.indexOf(val);
    }).hide();
  });

  $('.list-right input[name="SearchDualList"]').keyup(function (e) {
    var code = e.keyCode || e.which;
    if (code == '9') return;
    if (code == '27') $(this).val(null);
    var $rows = $(this).closest('.dual-list').find('.list-group li:not(.filters_hide)');
    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
    $rows.show().filter(function () {
      var text = $(this).find('input').val().replace(/\s+/g, ' ').toLowerCase();
      return !~text.indexOf(val);
    }).hide();
  });
});



function randomToInput()
{
  var filename = '';
  var chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  for ( var i = 0; i < 15; i++ ) {
    filename += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  document.getElementById('filename').value = filename;
}
