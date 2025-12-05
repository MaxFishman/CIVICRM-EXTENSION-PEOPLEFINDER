{* People Finder login page *}
<div class="crm-container">
  <div class="crm-section">
    <h2>{ts}People Finder - Login{/ts}</h2>
    
    {if $message}
      <div class="crm-section {if $message_type == 'error'}error{else}success{/if}">
        <p>{$message}</p>
      </div>
    {/if}
    
    <div class="crm-section">
      <p>{ts}To use People Finder, you must be registered in our database. Enter your email address to receive a verification link.{/ts}</p>
      
      <form method="post" action="{crmURL p='civicrm/peoplefinder/login'}">
        <div class="crm-form-block">
          <div class="crm-section">
            <div class="label">{ts}Email Address{/ts}</div>
            <div class="content">
              <input type="email" name="email" required class="form-text" />
            </div>
            <div class="clear"></div>
          </div>
          
          <div class="crm-section">
            <div class="content">
              <input type="submit" value="{ts}Send Verification Link{/ts}" class="form-submit default" />
            </div>
            <div class="clear"></div>
          </div>
        </div>
      </form>
    </div>
    
    <div class="crm-section">
      <p><small>{ts}Note: You must already be in our database to register. If you're not sure, contact the administrator.{/ts}</small></p>
    </div>
  </div>
</div>

