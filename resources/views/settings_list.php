<form method="POST" action="" id="settings_list_form">
  <div class="row">
      <div class="col-md-10">
      </div>
      <div class="col-md-2">
        <button 
          type="submit" 
          class="btn btn-default pull-right"
          id="settings_list_form_save"
          data-type="json" 
          data-action="/settings/save"
        >
          Сохранить
        </button>
      </div>
  </div>
  <table class="table table-striped">
    <thead>
      <tr>
        <th width="30%">Имя</th>
        <th width="69%">Значение</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($oSettings as $oSetting): ?>
      <tr>
        <td width="30%"><?php echo $oSetting->sDescribtionName?></td>

        <?php if ($oSetting->sType == "text"): ?>
        <td width="69%">
          <input 
            type="text" 
            class="form-control" 
            name="sValue[<?php echo $oSetting->iSettingID?>]"
            value="<?php echo $oSetting->sValue?>"
          >
        </td>
        <?php elseif ($oSetting->sType == "boolean"): ?>
        <td width="69%" class="form-inline">
          <label>
            Да
            <input 
              type="radio" 
              name="sValue[<?php echo $oSetting->iSettingID?>]"
              <?php if ($oSetting->sValue == '1'): ?>checked="checked"<?php endif ?>
              value="1"
            >
          </label>
          <label>
            Нет
            <input 
              type="radio" 
              name="sValue[<?php echo $oSetting->iSettingID?>]"
              <?php if ($oSetting->sValue == '0'): ?>checked="checked"<?php endif ?>
              value="0"
            >
          </label>          
        </td>
        <?php endif ?>
      </tr>
    <?php endforeach ?>
    </tbody>
  </table>
</form>