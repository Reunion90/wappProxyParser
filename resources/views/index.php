<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#create_process">Запустить парсер</a></li>
  <li><a data-toggle="tab" href="#process_list">Запущенные процессы</a></li>
  <li><a data-toggle="tab" href="#pages_list">Страницы</a></li>
  <li><a data-toggle="tab" href="#proxy_list">Прокси</a></li>
  <li><a data-toggle="tab" href="#settings_list">Настройки</a></li>
</ul>

<div class="notification-wrapper">
</div>

<div class="tab-content">
  <div id="create_process" class="tab-pane in active">
  	<div class="row">
	  	<div class="col-md-2">
	  		
	  	</div>
		<ul class="nav nav-pills col-md-10">
		  <li class="active"><a data-toggle="pill" href="#site_process_create">Поиск по сайту</a></li>
		  <li><a data-toggle="pill" href="#google_process_create">Поиск в Google</a></li>
		</ul>
	</div>
	<div class="tab-content">
		<div id="google_process_create" class="tab-pane">
	    	<form class="form-horizontal" id="google_process_create_form" method="POST">
				<div class="form-group">
					<label class="control-label col-sm-2" for="sSearchString">Поисковый запрос:</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="sSearchString" name="sSearchString" placeholder="" value="free proxy">
					</div>
				</div>
				<div class="form-group"> 
					<div class="col-sm-offset-2 col-sm-10">
						<button 
							type="submit" 
							class="btn btn-default"
							id="google_process_create_form_submit"
							data-type="json" 
							data-action="/processes/create/google"
						>
							Запустить
						</button>
					</div>
				</div>
			</form>
	    </div>
	    <div id="site_process_create" class="tab-pane in active">
	    	<form class="form-horizontal" id="site_process_create_form" method="POST">
				<div class="form-group">
					<label class="control-label col-sm-2" for="sURL">Страница:</label>
					<div class="col-sm-10">
						<input 
							type="text" 
							class="form-control" 
							id="sURL" 
							name="sURL" 
							placeholder="http://domain.com/page.php"
							value="https://free-proxy-list.net"
						>
					</div>
				</div>
				<div class="form-group"> 
					<div class="col-sm-offset-2 col-sm-10">
						<div class="checkbox">
							<label>
								<input type="checkbox" name="bScanFullSite">
								Сканировать весь сайт целиком
							</label>
						</div>
					</div>
				</div>
				<div class="form-group"> 
					<div class="col-sm-offset-2 col-sm-10">
						<button 
							type="submit" 
							class="btn btn-default" 
							id="site_process_create_form_submit"
							data-type="json"
							data-action="/processes/create/site"
						>
							Запустить
						</button>
					</div>
				</div>
			</form>
	    </div>
	</div>
  </div>
  <div id="process_list" class="tab-pane">
    <?php echo $sProcesses ?>
  </div>
  <div id="pages_list" class="tab-pane">
    <?php echo $sPages ?>
  </div>
  <div id="proxy_list" class="tab-pane">
	<ul class="nav nav-tabs">
	  <li class="active"><a data-toggle="tab" href="#all_proxy_list">Все</a></li>
	  <li><a data-toggle="tab" href="#work_proxy_list">Рабочие</a></li>
	</ul>
	<div class="tab-content">
		<div id="all_proxy_list" class="tab-pane in active">
			<?php echo $sAllProxies ?>
		</div>
		<div id="work_proxy_list" class="tab-pane">
			<?php echo $sWorkProxies ?>
		</div>
	</div>
    
  </div>
  <div id="settings_list" class="tab-pane">
    <?php echo $sSettings ?>
  </div>
</div>