<form method="POST" action="" id="all_proxy_list_form">
  <div class="row">
      <div class="col-md-9">
      </div>
      <div class="col-md-3">
        <button 
          class="btn btn-default pull-right"
          id="all_proxy_list_form_update"
          data-update="all_proxy_list"
          data-action="/proxies"
        >
          Обновить
        </button>
        <button 
          type="submit" 
          class="btn btn-default pull-right"
          id="all_proxy_list_form_delete"
          data-type="json"
          data-action="/proxies/delete"
        >
          Удалить
        </button>
      </div>
  </div>
  <table class="table table-striped">
    <thead>
      <tr>
        <th width="2%">
          <input type="checkbox" class="select_all">
        </th>
        <th width="56%">IP</th>
        <th width="10%">Порт</th>
        <th width="10%">Тип</th>
        <th width="10%">Проверен</th>
        <th width="10%">Работает</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($oProxies as $oProxy): ?>
      <tr>
        <td width="2%">
          <input type="checkbox" class="selector" name="bSelected[<?php echo $oProxy->iProxyID?>]">
        </td>    
        <td width="56%"><?php echo $oProxy->sIP?></td>
        <td width="10%"><?php echo $oProxy->iPort?></td>
        <td width="10%"><?php echo $oProxy->sType?></td>
        <td width="10%">
          <?php if ($oProxy->bIsChecked): ?>
            <span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>
          <?php else: ?>
            <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
          <?php endif ?>
        </td>
        <td width="10%">
          <?php if ($oProxy->bIsWork): ?>
            <span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>
          <?php else: ?>
            <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
          <?php endif ?>
        </td>
      </tr>
    <?php endforeach ?>
    </tbody>
  </table>
</form>