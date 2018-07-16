<form method="POST" action="" id="work_proxy_list_form">
  <div class="row">
      <div class="col-md-9">
      </div>
      <div class="col-md-3">
        <button 
          type="submit" 
          class="btn btn-default pull-right"
          id="work_proxy_list_form_update"
          data-update="work_proxy_list"
          data-action="/proxies/work"
        >
          Обновить
        </button>
        <button 
          type="submit" 
          class="btn btn-default pull-right"
          id="work_proxy_list_form_delete"
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
        <th width="77%">IP</th>
        <th width="10%">Порт</th>
        <th width="10%">Тип</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($oWorkProxies as $oProxy): ?>
      <tr>
        <td width="2%">
          <input type="checkbox" class="selector" name="bSelected[<?php echo $oProxy->iProxyID?>]">
        </td>    
        <td width="77%"><?php echo $oProxy->sIP?></td>
        <td width="10%"><?php echo $oProxy->iPort?></td>
        <td width="10%"><?php echo $oProxy->sType?></td>
      </tr>
    <?php endforeach ?>
    </tbody>
  </table>
</form>