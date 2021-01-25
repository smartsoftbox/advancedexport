{*
*  @author Marcin Kubiak
*  @copyright  Smart Soft
*  @license    Commercial license
*  International Registered Trademark & Property of Smart Soft
*}


<div style="width: 49%; float: right;" class="col-xs-12 col-md-6 panel" id="available_fields_form">
  <div class="">
    <div class="card">
      <h3 class="card-header">
        <i class="material-icons">list</i> Available fields
      </h3>
      <div class="card-block">
        <div class="js-available-field-template d-none"></div>
        <span class="help-box js-available-field-popover-template d-none" data-toggle="popover" data-original-title="" title=""></span>
        <div class="alert alert-info js-available-fields" role="alert" data-url="/admin-dev/index.php/configure/advanced/import/fields?_token=xa9xlB7rW0nuZx2KPEP5RekzlaujFtQGEMUGqnj1o24">
          {foreach from=$available_fields item=available_field name=available_field}
            <div class="">{$available_field.label|escape:'html':'UTF-8'}</div>
          {/foreach}
        </div>
        <p>* Required field</p>
        {if $is_combination}
          <div class="alert alert-warning js-entity-alert" role="alert">
            <ul>
              <li>If you want update existing product combination your Required fields are Product id and Product Attribute Id. Don't include Attribute Name and Value</li>
            </ul>
          </div>
        {/if}
      </div>
    </div>
  </div>
</div>
