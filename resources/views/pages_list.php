<form method="POST" action="" id="pages_list_form">
  <table class="table table-striped">
    <div class="row">
        <div class="col-md-9">
        </div>
        <div class="col-md-3">
          <button 
            class="btn btn-default pull-right"
            id="pages_list_form_update"
            data-update="pages_list"
            data-action="/pages"
          >
            Обновить
          </button>
        </div>
    </div>
    <thead>
      <tr>
        <th width="69%">URL</th>
        <th width="15%">Просканнирована на ссылки</th>
        <th width="15%">Просканнирована на прокси</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($oPages as $oPage): ?>
      <tr>
        <td width="69%"><?php echo $oPage->sURL?></td>
        <td width="15%">
          <?php if ($oPage->bIsLinksScanned): ?>
            <span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>
          <?php else: ?>
            <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
          <?php endif ?>
        </td>
        <td width="15%">
          <?php if ($oPage->bIsProxyScanned): ?>
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