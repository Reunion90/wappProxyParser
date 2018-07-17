<form method="POST" action="" id="process_list_form">
  <div class="row">
      <div class="col-md-9">
      </div>
      <div class="col-md-3">
        <button 
          class="btn btn-default pull-right"
          id="process_list_form_update"
          data-update="process_list"
          data-action="/processes"
        >
          Обновить
        </button>
        <button 
          type="submit" 
          class="btn btn-default pull-right"
          id="process_list_form_kill"
          data-type="json" 
          data-action="/processes/kill"
        >
          Завершить
        </button>
      </div>
  </div>
  <table class="table table-striped">
    <thead>
      <tr>
        <th width="2%">
          <input type="checkbox" class="select_all">
        </th>
        <th width="8%">PID</th>
        <th width="15%">Тип</th>
        <th width="30%">Команда</th>
        <th width="43%">Параметры</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($oProcesses as $oProcess): ?>
      <tr>
        <td width="2%">
          <input type="checkbox" class="selector" name="bSelected[<?php echo $oProcess->iPID?>]">
        </td>
        <td width="8%"><?php echo $oProcess->iPID?></td>
        <td width="15%"><?php echo $oProcess->sType?></td>
        <td width="30%"><?php echo $oProcess->sCommand?></td>
        <td width="43%"><?php echo $oProcess->sParameters?></td>
      </tr>
    <?php endforeach ?>
    </tbody>
  </table>
</form>