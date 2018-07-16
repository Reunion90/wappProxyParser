
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

$(document)
	.ready(function() {
		var oCallbacks = {
			google_process_create_form: {
				google_process_create_form_submit: function(aData) 
				{
					fnShowNotification(aData);
					fnUpdateHTML('process_list', '/processes');
				},
			},
			site_process_create_form: {
				site_process_create_form_submit: function(aData) 
				{
					fnShowNotification(aData);
					fnUpdateHTML('process_list', '/processes');
				},
			},
			process_list_form: {
				process_list_form_kill: function(aData) 
				{
					fnShowNotification(aData);
					fnUpdateHTML('pages_list', '/pages');
					fnUpdateHTML('process_list', '/processes');
				}
			},
			all_proxy_list_form: {
				all_proxy_list_form_delete: function(aData) 
				{
					fnShowNotification(aData);
					fnUpdateHTML('all_proxy_list', '/proxies');
				}
			},
			work_proxy_list_form: {
				work_proxy_list_form_delete: function(aData) 
				{
					fnShowNotification(aData);
					fnUpdateHTML('work_proxy_list', '/proxies/work');
				}
			},
			settings_list_form: {
				settings_list_form_save: function(aData) 
				{
					fnShowNotification(aData);
					fnUpdateHTML('settings_list', '/settings');
				},
			},
		};

		function fnShowNotification(aResult)
		{
			var sType = aResult['success'] ? 'alert-success' : 'alert-danger';
			var sText = aResult['success'] ? 'Операция выполнена успешно' : 'Произошли ошибки';
			
			if (aResult['errors'])
				sText += ' '+aResult['errors'].join('<br>');

			var $oElement = $(`
				<div class="alert ${sType} alert-dismissible fade in">
	    			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	    			${sText}
	  			</div>
	  		`)
				.appendTo('.notification-wrapper');
					
		  	$('.notification-wrapper .close')
		  		.click(function() {
		  			$(this).parent().remove();
		  		});

		  	setTimeout(
		  		function() 
		  		{
		  			$oElement.remove();
		  		},
		  		5000
		  	);
		}

		function fnUpdateHTML(sID, sURL) 
		{
			function fnUpdate(sData)
			{
				$("#"+sID).html(sData);
				fnBindForms();
			}

			$.ajax(
				sURL,
				{
					method: "GET",
					dataType: "html",
					success: fnUpdate,
					headers: {
    					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  					},
				}
			);

		}

		function fnSendFormData($oForm, $oSubmit) 
		{
			var aData = {};
			var sDataType = $oSubmit.data('type') || 'html';
			var sAction = $oSubmit.data('action') || $oForm.attr("action");

			$oForm
				.find('input, select')
				.each(function(iIndex, oElement)
				{
					var $oElement = $(oElement);

					if ($oElement.prop("tagName") == "INPUT") {
						if ($oElement.attr("type") == "checkbox") {
							if ($oElement.prop("checked"))
								aData[$oElement.attr("name")] = 1;
						} else {
							aData[$oElement.attr("name")] = $oElement.val();
						}
					}

					if ($oElement.prop("tagName") == "SELECT")
						aData[$oElement.attr("name")] = $oElement.find(":selected").val();
				});

			$.ajax(
				sAction,
				{
					method: $oForm.attr("method"),
					data: aData,
					dataType: sDataType,
					success: oCallbacks[$oForm.attr("id")][$oSubmit.attr("id")],
					headers: {
    					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  					},
				}
			);
		}

		function fnBindForms()
		{
			var $oForm = $('form:not(.binded)');
			$oForm
				.each(function(iIndex, oElement)
				{
					var $oElement = $(oElement);

					$oElement.addClass("binded");
					$oElement
						.find('button[type=submit]')
						.click(function(oEvent) 
						{
							oEvent.preventDefault();

							$this = $(this);

							if ($this.data("update")) {
								fnUpdateHTML(
									$this.data("update"),
									$this.data("action")
								);
							} else {
								fnSendFormData(
									$oElement,
									$this
								);
							}

							return false;
						});
					$oElement
						.find('.select_all')
						.click(function(oEvent) 
						{
							$oElement
								.find('.selector')
								.prop('checked', $(this).prop('checked'));
						});
				});
		}

		fnBindForms();
	});