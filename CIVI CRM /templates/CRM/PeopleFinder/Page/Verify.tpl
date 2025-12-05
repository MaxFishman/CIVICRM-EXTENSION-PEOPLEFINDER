{* People Finder verification page *}
<div class="crm-container">
  <div class="crm-section">
    <h2>{ts}Email Verification{/ts}</h2>
    
    {if $message}
      <div class="crm-section {if $message_type == 'error'}error{else}success{/if}">
        <p>{$message}</p>
      </div>
    {/if}
    
    {if $redirect_url}
      <div class="crm-section">
        <p><a href="{$redirect_url}" class="button">{ts}Continue to People Finder{/ts}</a></p>
      </div>
    {/if}
  </div>
</div>

