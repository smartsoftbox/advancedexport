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
      <div class="col-lg-2 col-md-3">
        <div class="list-group" id="entities">
          <a href="#" id="welcome" class="list-group-item"><b>Start</b></a>
            {foreach from=$export_types item=export_type name=blockCategTree}
              <a href="#" id="{$export_type|escape:'htmlall':'UTF-8'}" class="list-group-item">
                  {$export_type|escape:'htmlall':'UTF-8'}
              </a>
            {/foreach}
        </div>
      </div>
      <div class="form-horizontal col-lg-10">
        <div class="list-group">
