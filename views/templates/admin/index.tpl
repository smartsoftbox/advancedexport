{*
*  @author Marcin Kubiak
*  @copyright  Smart Soft
*  @license    Commercial license
*  International Registered Trademark & Property of Smart Soft
*}

<div class="row">
  <div class="col-lg-12">
    <div id="loader-container">
      <div id="topLoader"></div>
    </div>
    <div class="row">
      <div class="col-lg-2">
        <div class="list-group" id="entities">
          <a href="#" id="welcome" class="list-group-item"><b>Start</b></a>
            {foreach from=$export_types item=export_type name=blockCategTree}
              <a href="#" id="{$export_type|escape:'html':'UTF-8'}" class="entity list-group-item">
                  {$export_type|escape:'htmlall':'UTF-8'}
              </a>
            {/foreach}
          <a href="#" id="import" class="list-group-item"><b>Import (PrestaShop 1.7)</b></a>
          <a href="#" id="cron" class="list-group-item"><b>Cron</b></a>
        </div>
      </div>
      <div id="right-column" class="form-horizontal col-lg-10">
        <div alt="welcome" class="tab-content list-group">
            {include file=$start}
        </div>
          {foreach from=$export_types item=export_type name=blockCategTree}
            <div alt="{$export_type|escape:'html':'UTF-8'}" class="tab-content list-group">
              <div alt="model-{$export_type|escape:'html':'UTF-8'}"></div>
            </div>
          {/foreach}
        <div alt="import" class="tab-content list-group"></div>
        <div alt="cron" class="tab-content list-group"></div>
        <div id="ajax-loader"><img src="../modules/advancedexport/views/img/ajax-loader.gif"></div>
      </div>
    </div>
  </div>
</div>

