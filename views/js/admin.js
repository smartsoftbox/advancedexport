/**
 *  @author Marcin Kubiak
 *  @copyright  Smart Soft
 *  @license    Commercial license
 *  International Registered Trademark & Property of Smart Soft
 */

jQuery(function($) {
     //remove fields if not static
     $("td.ds-table").each(function(){
         if($.trim($(this).html()) != "static") {
             $(this).parent().find("td.ds-return").html("");
             $(this).parent().find("td.ds-return").next().html("");
         }
     });

     var $topLoader = $("#topLoader").percentageLoader({width: 233, height: 233, progress : 0});

     var topLoaderRunning = false;

     $('a.export').click(function() {
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
         var animateFunc = function() {
             $.ajax({
               type: 'POST' ,
               cache: false,
               async: true,
               url: '/modules/advancedexport/progress.txt',
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

     $(".ds-select").each(function() {
         var selectList = $(this).find('option:selected');
         selectList.sort(sort_li);

         var unSelectList = $(this).find("option:not(:selected)");

         $(this).html(selectList);
         $(this).append(unSelectList);
     });

     function sort_li(a, b){
         return ($(b).data('sortby')) < ($(a).data('sortby')) ? 1 : -1;
     }

	$('#entities .list-group-item').click(function(){
		var id = $(this).attr('id');
		$('.list-group-item').removeClass('active');
		$(this).addClass('active');

		$('.product-tab-content').addClass('hide');
		$('.product-tab-content[alt=' + id + ']').removeClass('hide');

        var current_tab_id = $('div.list-group a.list-group-item.active').attr('id');
        $.cookie('current_tab_id', current_tab_id);

        return false;
	});

	$('#save_type').change(function(){
		var id = $(this).val();
		if(parseInt(id) === 3) {
		  id = 1;
    }
		$('.process1').parent().parent().hide();
		$('.process2').parent().parent().hide();
	 	var current = 'process' + id;
		$('.' + current).parent().parent().show();

        return false;
	});

	$('#save_type').change();

    $("input[name='only_new']:radio").change(function () {
        if($(this).val() == 1)
        {
            $('.datetimepicker').closest('div.form-group').slideUp();
            $('#start_id').closest('div.form-group').slideUp();
            $('#end_id').closest('div.form-group').slideUp();
        }
        if($(this).val() == 0)
        {
            $('.datetimepicker').closest('div.form-group').slideDown();
            $('#start_id').closest('div.form-group').slideDown();
            $('#end_id').closest('div.form-group').slideDown();
        }
    });

     if($.cookie('current_tab_id'))
     {
         var current_tab_id = 'a#' + $.cookie('current_tab_id');
         $('div.list-group a.list-group-item').removeClass('active');
         $(current_tab_id).click();
     }
     else
     {
         $('div.list-group a:first').click();
     }

     $('span.cron_url').parent().removeAttr("onclick");

     var clipboard = new Clipboard('span.cron_button');

     clipboard.on('success', function(e) {
         setTooltip(e.trigger, 'Copied!');
         hideTooltip(e.trigger);
     });

     clipboard.on('error', function(e) {
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
         setTimeout(function() {
             $(btn).tooltip('hide');
         }, 1000);
     }

     $('#checkConnection').click(function() {
       let params = {};
       params.hostname = $('#ftp_hostname').val();
       params.port = $('#ftp_port').val();
       params.username = $('#ftp_user_name').val();
       params.password = $('#ftp_user_pass').val();
       params.path = $('#ftp_directory').val();
       params.save_type = $('#save_type').val();

       $(this).text('Please wait...');

       $.post( urlJson + '&ajax=1&action=checkConnection', {'params': params}, function( data ) {
          let errors = JSON.parse(data);
          let ftp_errors = $('#ftp_errors');
          let success = $('#ftp_success');
         if (errors !== null) {
            ftp_errors.html('');
            ftp_errors.removeClass('hide');
            success.addClass('hide');
            $(errors).each(function(key, error){
              ftp_errors.append(error);
            });
          } else {
            ftp_errors.html('');
            success.removeClass('hide');
          }
          $('#checkConnection').text('Test Connection');
       });
     });

     function getUrlParam(name){
         var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
         return results[1] || 0;
     }

     $('#desc-advancedexportfield-save').click(function(){
         var form = $('#form-advancedexportfield');
         var action = form.attr('action').substring(0, form.attr('action').indexOf('#'));
         form.attr('action', action + "&submitSaveFields=1").submit();
     });
 });


