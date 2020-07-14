{*
*  @author Marcin Kubiak
*  @copyright  Smart Soft
*  @license    Commercial license
*  International Registered Trademark & Property of Smart Soft
*}

{extends file="helpers/form/form.tpl"}

{block name="input"}
    {if $input.type == 'html'}
        {if isset($input.html_content)}
            {$input.html_content|escape:'html':'UTF-8'}
        {else}
            {$input.name|escape:'html':'UTF-8'}
        {/if}
    {elseif $input.type == 'duallist'}
      <div class="alert alert-info" role="alert">
        <p class="alert-text">
          Do you need new fields or connect to other tables ? Please email us with this link
          <a href="https://addons.prestashop.com/contact-form.php?id_product=6927" class="_blank" target="_blank">
            contact link.
          </a>
        </p>
      </div>
      <div class="dual-list list-left col-md-5" style="padding-left: 0px;">
        <div class="well text-right">
          <div class="row">
            <div class="col-md-10">
              <div class="input-group">
                <span class="input-group-addon glyphicon-search">
                    <i class="icon-search"></i>
                </span>
                <input type="text" name="SearchDualList" class="form-control" placeholder="search"/>
              </div>
            </div>
            <div class="col-md-2">
              <div class="btn-group">
                <a class="btn btn-default button selector" title="select all">
                  all
                </a>
              </div>
            </div>
          </div>
          <div class="row" id="filter-group">
            <select>
              <option value="all">All Tabs</option>
                {foreach $input.options.optiongroup.query AS $optiongroup}
                  <option value="{$optiongroup[$input.options.optiongroup.label]|escape:'htmlall':'UTF-8'}">
                      {$optiongroup[$input.options.optiongroup.label]|escape:'htmlall':'UTF-8'}
                  </option>
                {/foreach}
            </select>
          </div>
          <div class="row">
            <ul class=" list-group" style="height: 400px; overflow: auto">
                {foreach $input.options.optiongroup.query AS $optiongroup}
                    {foreach $optiongroup[$input.options.options.query] as $option}
                        {if !$option[$input.options.options.id]|array_key_exists:$fields_value[$input.name]}
                          <li id="option-{$option[$input.options.options.id]|escape:'htmlall':'UTF-8'}"
                              class="list-group-item"
                              rel="{$optiongroup[$input.options.optiongroup.label]|escape:'htmlall':'UTF-8'}">
                            <span>{$option[$input.options.options.name]|escape:'htmlall':'UTF-8'}</span>
                            <input class="hide" type="text"
                                   value="{$option[$input.options.options.name]|escape:'htmlall':'UTF-8'}"/>
                          </li>
                        {/if}
                    {/foreach}
                {/foreach}
            </ul>
            <div class="additional-buttons">
              <div class="col-md-5">
                <a href="{$link|escape:'html':'UTF-8'}" class="btn button btn-default" title="change global field name">
                  Global Field Name
                </a>
              </div>
              <div class="btn-group col-md-5">
                <a href="{$link|escape:'html':'UTF-8'}&addadvancedexportfield=1" class="btn button btn-default"
                   title="add static field">
                  Add Static Field
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="list-arrows col-md-1 text-center">
        <a href="#" class="btn btn-default btn-sm button move-left">Left</a>
        <a href="#" class="btn btn-default btn-sm button move-right">Right</a>
      </div>
      <div class="dual-list list-right col-md-5">
        <div class="well">
          <div class="row">
            <div class="col-md-10">
              <div class="input-group">
                <span class="input-group-addon glyphicon-search">
                    <i class="icon-search"></i>
                </span>
                <input type="text" name="SearchDualList" class="form-control" placeholder="search"/>
              </div>
            </div>
            <div class="col-md-2">
              <div class="btn-group">
                <a class="btn btn-default button selector" id="select_all" title="select all">
                  all
                </a>
              </div>
            </div>
          </div>
          <div class="row">
            <ul class="list-group" style="height: 480px; overflow: auto">
                {foreach from=$fields_value[$input.name] key=key item=field}
                    {foreach $input.options.optiongroup.query AS $optiongroup}
                        {foreach $optiongroup[$input.options.options.query] as $option}
                            {if $option[$input.options.options.id] == $key}
                              <li id="option-{$option[$input.options.options.id]|escape:'htmlall':'UTF-8'}"
                                  class="list-group-item"
                                  rel="{$optiongroup[$input.options.optiongroup.label]|escape:'htmlall':'UTF-8'}">
                                <span class="hide">{$option[$input.options.options.name]|escape:'htmlall':'UTF-8'}</span>
                                <input class="" type="text"
                                       value="{$fields_value[$input.name][$option[$input.options.options.id]][0]|escape:'htmlall':'UTF-8'}"/>
                              </li>
                            {/if}
                        {/foreach}
                    {/foreach}
                {/foreach}
            </ul>
            <input type="hidden" name="{$input.name|escape:'htmlall':'UTF-8'}" id="{$input.id|escape:'htmlall':'UTF-8'}"
                   value=""/>
          </div>
        </div>
      </div>
      <div class="list-arrows col-md-1 text-center">
        <a href="#" class="btn btn-default btn-sm button move-up">Up</a>
        <a href="#" class="btn btn-default btn-sm button move-down">Down</a>
      </div>
    {else}
        {$smarty.block.parent}
    {/if}
{/block}
