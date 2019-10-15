{*
*  @author Marcin Kubiak
*  @copyright  Smart Soft
*  @license    Commercial license
*  International Registered Trademark & Property of Smart Soft
*}

{extends file="helpers/form/form.tpl"}

{block name="input"}
    {if $input.type == 'bsmselect'}
        <select name="{$input.name|escape:'htmlall':'UTF-8'}"
                class="{if isset($input.class)}{$input.class|escape:'htmlall':'UTF-8'}{/if}"
                id="{if isset($input.id)}{$input.id|escape:'htmlall':'UTF-8'}{else}{$input.name|escape:'htmlall':'UTF-8'}{/if}"
                {if isset($input.multiple)}multiple="multiple" {/if}
                {if isset($input.size)}size="{$input.size|escape:'htmlall':'UTF-8'}"{/if}
                {if isset($input.onchange)}onchange="{$input.onchange|escape:'htmlall':'UTF-8'}"{/if}>
            {if isset($input.options.default)}
                <option value="{$input.options.default.value|intval}">{$input.options.default.label|escape:'htmlall':'UTF-8'}</option>
            {/if}
            {if isset($input.options.optiongroup)}
                {foreach $input.options.optiongroup.query AS $optiongroup}
                    <optgroup label="{$optiongroup[$input.options.optiongroup.label]|escape:'htmlall':'UTF-8'}">
                        {foreach name=option from=$optiongroup[$input.options.options.query] item="option"}
                            <option value="{$option[$input.options.options.id]|escape:'htmlall':'UTF-8'}"
                                    {if isset($input.multiple)}
                                        {foreach name=field_value from=$fields_value[$input.name] item="field_value"}
                                            {if $field_value == $option[$input.options.options.id]}
                                                data-sortby="{$smarty.foreach.field_value.iteration|intval}"
                                                selected="selected"
                                            {/if}
                                        {/foreach}
                                    {else}
                                        {if $fields_value[$input.name] == $option[$input.options.options.id]}selected="selected"{/if}
                                    {/if}
                                    >{$option[$input.options.options.name]|escape:'htmlall':'UTF-8'}</option>
                        {/foreach}
                    </optgroup>
                {/foreach}
            {else}
                {foreach name=test from=$input.options.query item="option"}
                    {if is_object($option)}
                        <option value="{$option->$input.options.id|escape:'htmlall':'UTF-8'}"
                                {if isset($input.multiple)}
                                    {foreach name=field_value from=$fields_value[$input.name] item="field_value"}
                                        {if $field_value == $option->$input.options.id}
                                            data-sortby="{$smarty.foreach.field_value.iteration|intval}"
                                            selected="selected"
                                        {/if}
                                    {/foreach}
                                {else}
                                    {if $fields_value[$input.name] == $option->$input.options.id}
                                        selected="selected"
                                    {/if}
                                {/if}
                                >{$option->$input.options.name|escape:'htmlall':'UTF-8'}</option>
                    {elseif $option == "-"}
                        <option value="">-</option>
                    {else}
                        <option value="{$option[$input.options.id]|escape:'htmlall':'UTF-8'}"
                                {if isset($input.multiple)}
                                    {foreach name=field_value from=$fields_value[$input.name] item="field_value"}
                                        {if $field_value == $option[$input.options.id]}
                                            data-sortby="{$smarty.foreach.field_value.iteration|intval}"
                                            selected="selected"
                                        {/if}
                                    {/foreach}
                                {else}
                                    {if $fields_value[$input.name] == $option[$input.options.id]}
                                        selected="selected"
                                    {/if}
                                {/if}
                                >{$option[$input.options.name]|escape:'htmlall':'UTF-8'}</option>

                    {/if}
                {/foreach}
            {/if}
        </select>
        {if !empty($input.hint)}<span class="hint" name="help_box">{$input.hint|escape:'htmlall':'UTF-8'}<span class="hint-pointer">&nbsp;</span></span>{/if}
    {elseif $input.type == 'duallist'}

        <div class="dual-list list-left col-md-5" style="padding-left: 0px;">
            <div class="well text-right">
                <div class="row">
                    <div class="col-md-10">
                        <div class="input-group">
                            <span class="input-group-addon glyphicon-search">
                                <i class="icon-search"></i>
                            </span>
                            <input type="text" name="SearchDualList" class="form-control" placeholder="search" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="btn-group">
                            <a class="btn btn-default selector" title="select all">
                                <i class="icon-check-empty"></i>
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
              <div class="row"">
                <ul class=" list-group" style="height: 400px; overflow: auto">
                    {foreach $input.options.optiongroup.query AS $optiongroup}
                        {foreach $optiongroup[$input.options.options.query] as $option}
                            {if !$option[$input.options.options.id]|array_key_exists:$fields_value[$input.name]}
                                <li id="option-{$option[$input.options.options.id]|escape:'htmlall':'UTF-8'}" class="list-group-item"
                                    rel="{$optiongroup[$input.options.optiongroup.label]|escape:'htmlall':'UTF-8'}">
                                  <span>{$option[$input.options.options.name]|escape:'htmlall':'UTF-8'}</span>
                                  <input class="hide" type="text" value="{$option[$input.options.options.name]|escape:'htmlall':'UTF-8'}" />
                                </li>
                            {/if}
                        {/foreach}
                    {/foreach}
                </ul>
                <div class="">
                  <div class="col-md-5">
                    <a href="{$link|escape:'html'}&editfields=1" class="btn btn-default" title="change global field name">
                      <i class="icon-pencil"></i> Global Field Name
                    </a>
                  </div>
                  <div  class="btn-group col-md-5">
                    <a href="{$link|escape:'html'}&addfield=1" class="btn btn-default" title="add static field">
                      <i class="icon-pencil"></i> Add Static Field
                    </a>
                  </div>
              </div>
            </div>
        </div>
      </div>

        <div class="list-arrows col-md-1 text-center">
            <a href="#" class="btn btn-default btn-sm move-left">
                <i class="icon-arrow-left"></i>
            </a>

            <a href="#" class="btn btn-default btn-sm move-right">
                <i class="icon-arrow-right"></i>
            </a>
        </div>

        <div class="dual-list list-right col-md-5">
            <div class="well">
                <div class="row">
                    <div class="col-md-10">
                        <div class="input-group">
                            <span class="input-group-addon glyphicon-search">
                                <i class="icon-search"></i>
                            </span>
                            <input type="text" name="SearchDualList" class="form-control" placeholder="search" />
                       </div>
                    </div>
                    <div class="col-md-2">
                        <div class="btn-group">
                            <a class="btn btn-default selector" title="select all">
                                <i class="icon-check-empty"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row">
                  <ul class="list-group" style="height: 480px; overflow: auto">
                      {foreach $input.options.optiongroup.query AS $optiongroup}
                          {foreach $optiongroup[$input.options.options.query] as $option}
                              {if $option[$input.options.options.id]|array_key_exists:$fields_value[$input.name]}
                                  <li id="option-{$option[$input.options.options.id]|escape:'htmlall':'UTF-8'}" class="list-group-item"
                                      rel="{$optiongroup[$input.options.optiongroup.label]|escape:'htmlall':'UTF-8'}">
                                      <span class="hide">{$option[$input.options.options.name]|escape:'htmlall':'UTF-8'}</span>
                                      <input class="" type="text"
                                           value="{$fields_value[$input.name][$option[$input.options.options.id]][0]|escape:'htmlall':'UTF-8'}" />
                                  </li>
                              {/if}
                          {/foreach}
                      {/foreach}
                    </ul>
                    <input type="hidden" name="{$input.name|escape:'htmlall':'UTF-8'}" id="{$input.id|escape:'htmlall':'UTF-8'}" value="" />
                </div>
            </div>
        </div>

        <div class="list-arrows col-md-1 text-center">
            <a href="#" class="btn btn-default btn-sm move-up">
                <i class="icon-arrow-up"></i>
            </a>

            <a href="#" class="btn btn-default btn-sm move-down">
                <i class="icon-arrow-down"></i>
            </a>
        </div>
    {else}
        {$smarty.block.parent}
    {/if}
{/block}
