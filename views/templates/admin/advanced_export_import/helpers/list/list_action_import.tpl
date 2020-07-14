{*
*  @author Marcin Kubiak
*  @copyright  Smart Soft
*  @license    Commercial license
*  International Registered Trademark & Property of Smart Soft
*}

<a href="{$href|escape:'html':'UTF-8'}" title="{$action|escape:'html':'UTF-8'}" class="start-import" alt="{$id|escape:'html':'UTF-8'}">
    {if $is_presta_16}
      <i class="icon-cloud-upload"></i>
        {$action|escape:'html':'UTF-8'}
    {else}
      <img src="../img/admin/import.gif" alt="{$action|escape:'html':'UTF-8'}">
    {/if}
</a>
