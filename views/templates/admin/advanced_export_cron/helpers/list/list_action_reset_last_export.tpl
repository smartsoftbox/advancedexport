{*
*  @author Marcin Kubiak
*  @copyright  Smart Soft
*  @license    Commercial license
*  International Registered Trademark & Property of Smart Soft
*}

<a href="{$href|escape:'html':'UTF-8'}" title="{$action|escape:'html':'UTF-8'}" class="resetid">
    {if $is_presta_16}
      <i class="icon-refresh"></i>
        {$action|escape:'html':'UTF-8'}
    {else}
      <img src="../img/admin/refreshid.gif" alt="{$action|escape:'html':'UTF-8'}">
    {/if}
</a>
