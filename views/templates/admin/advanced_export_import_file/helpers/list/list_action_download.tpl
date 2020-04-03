{*
*  @author Marcin Kubiak
*  @copyright  Smart Soft
*  @license    Commercial license
*  International Registered Trademark & Property of Smart Soft
*}

<a href="{$href|escape:'html':'UTF-8'}" title="{$action|escape:'html':'UTF-8'}" class="download">
    {if $is_presta_16}
      <i class="icon-preview"></i>
        {$action|escape:'html':'UTF-8'}
    {else}
      <img src="../img/admin/arrow_down.png" alt="{$action|escape:'html':'UTF-8'}">
    {/if}
</a>
