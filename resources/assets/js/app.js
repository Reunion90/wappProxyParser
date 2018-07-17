
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

$(document)
	.ready(function() {
		var oCallbacks = {
			process_list_count: function() 
			{
				fnCountTableRows('process_list_count', 'process_list_form');
			},
			pages_list_count: function()
			{
				fnCountTableRows('pages_list_count', 'pages_list_form');
			},
			all_proxy_list_count: function()
			{
				fnCountTableRows('all_proxy_list_count', 'all_proxy_list_form');
			},
			work_proxy_list_count: function()
			{
				fnCountTableRows('work_proxy_list_count', 'work_proxy_list_form');
			},

			process_list_form_update: function()
			{
				fnUpdateHTML('process_list', '/processes', oCallbacks['process_list_count']);
			},
			pages_list_form_update: function()
			{
				fnUpdateHTML('pages_list', '/pages', oCallbacks['pages_list_count']);
			},
			all_proxy_list_form_update: function()
			{
				fnUpdateHTML('all_proxy_list', '/proxies', oCallbacks['all_proxy_list_count']);
			},
			work_proxy_list_form_update: function()
			{
				fnUpdateHTML('work_proxy_list', '/proxies/work', oCallbacks['work_proxy_list_count']);
			},

			processes_and_pages_update: function(aData)
			{
				fnShowNotification(aData);
				fnUpdateHTML('process_list', '/processes', oCallbacks['process_list_count']);
				fnUpdateHTML('pages_list', '/pages', oCallbacks['pages_list_count']);
			},

			google_process_create_form_submit: function(aData) 
			{
				oCallbacks['processes_and_pages_update'](aData);
			},
			site_process_create_form_submit: function(aData) 
			{
				oCallbacks['processes_and_pages_update'](aData);
			},
			process_list_form_kill: function(aData) 
			{
				oCallbacks['processes_and_pages_update'](aData);
			},
			all_proxy_list_form_delete: function(aData) 
			{
				fnShowNotification(aData);
				fnUpdateHTML('all_proxy_list', '/proxies', oCallbacks['all_proxy_list_count']);
			},
			work_proxy_list_form_delete: function(aData) 
			{
				fnShowNotification(aData);
				fnUpdateHTML('all_proxy_list', '/proxies', oCallbacks['all_proxy_list_count']);
				fnUpdateHTML('work_proxy_list', '/proxies/work', oCallbacks['work_proxy_list_count']);
			},
			settings_list_form_save: function(aData) 
			{
				fnShowNotification(aData);
				fnUpdateHTML('settings_list', '/settings');
			},
		};

		function fnUpdateAll()
		{
			fnUpdateHTML('process_list', '/processes', oCallbacks['process_list_count']);
			fnUpdateHTML('pages_list', '/pages', oCallbacks['pages_list_count']);
			fnUpdateHTML('all_proxy_list', '/proxies', oCallbacks['all_proxy_list_count']);
			fnUpdateHTML('work_proxy_list', '/proxies/work', oCallbacks['work_proxy_list_count']);
			fnUpdateHTML('settings_list', '/settings');
		}

		function fnCountTableRows(sLabelID, sTableID)
		{
			$("#"+sLabelID).text($("#"+sTableID).find("tbody tr").length);
		}

		function fnShowNotification(aResult)
		{
			if (!aResult)
				return;

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

		function fnUpdateHTML(sID, sURL, fnSuccess) 
		{
			function fnUpdate(sData)
			{
				$("#"+sID).html(sData);
				fnBindForms();
				if (fnSuccess)
					fnSuccess();
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
					success: oCallbacks[$oSubmit.attr("id")],
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

							fnSendFormData(
								$oElement,
								$this
							);

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

			$('[data-update]:not(.binded)')
				.each(function(iIndex, oElement)
				{
					var $oElement = $(oElement);

					$oElement.addClass("binded");
					$oElement
						.click(function(oEvent) 
						{
							oEvent.preventDefault();

							oCallbacks[$oElement.attr("id")]();

							return false;
						});
				});
		}

		fnUpdateAll();
		//fnBindForms();
	});