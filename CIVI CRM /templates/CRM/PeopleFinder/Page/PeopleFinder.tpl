{* People Finder main page *}
<div class="crm-container">
  <div class="crm-section">
    <h2>{ts}People Finder{/ts}</h2>
    <p>{ts}Search for friends from the past and connect with them.{/ts}</p>
    
    {if $user}
      <div class="crm-section">
        <h3>{ts}Welcome, {$user.contact.display_name}{/ts}</h3>
        <p><a href="{crmURL p='civicrm/peoplefinder/logout'}" class="button">{ts}Logout{/ts}</a></p>
      </div>
    {/if}

    {* Search Form *}
    <div class="crm-section">
      <h3>{ts}Search for People{/ts}</h3>
      <form id="peoplefinder-search-form">
        <div class="crm-form-block">
          <div class="crm-section">
            <div class="label">{ts}First Name{/ts}</div>
            <div class="content">
              <input type="text" name="first_name" id="first_name" class="form-text" />
            </div>
            <div class="clear"></div>
          </div>
          
          <div class="crm-section">
            <div class="label">{ts}Last Name{/ts}</div>
            <div class="content">
              <input type="text" name="last_name" id="last_name" class="form-text" />
            </div>
            <div class="clear"></div>
          </div>
          
          <div class="crm-section">
            <div class="label">{ts}State/Province{/ts}</div>
            <div class="content">
              <input type="text" name="state_province" id="state_province" class="form-text" />
            </div>
            <div class="clear"></div>
          </div>
          
          <div class="crm-section">
            <div class="label">{ts}City{/ts}</div>
            <div class="content">
              <input type="text" name="city" id="city" class="form-text" />
            </div>
            <div class="clear"></div>
          </div>
          
          <div class="crm-section">
            <div class="content">
              <input type="submit" value="{ts}Search{/ts}" class="form-submit default" />
            </div>
            <div class="clear"></div>
          </div>
        </div>
      </form>
    </div>

    {* Search Results *}
    <div id="search-results" class="crm-section" style="display:none;">
      <h3>{ts}Search Results{/ts}</h3>
      <div id="results-list"></div>
    </div>

    {* Pending Connections *}
    {if $pending_connections}
      <div class="crm-section">
        <h3>{ts}Pending Connection Requests{/ts}</h3>
        <div id="connections-list">
          {foreach from=$pending_connections item=connection}
            <div class="connection-item">
              <p><strong>{$connection.contact.display_name}</strong> - {$connection.state_province}</p>
              <p>{$connection.message}</p>
              <p><small>{ts}Requested on{/ts} {$connection.created_date|date_format:"%B %e, %Y"}</small></p>
            </div>
          {/foreach}
        </div>
      </div>
    {/if}
  </div>
</div>

<script type="text/javascript">
  CRM.$(function($) {
    $('#peoplefinder-search-form').on('submit', function(e) {
      e.preventDefault();
      
      var formData = {
        first_name: $('input[name="first_name"]').val(),
        last_name: $('input[name="last_name"]').val(),
        state_province: $('input[name="state_province"]').val(),
        city: $('input[name="city"]').val(),
      };
      
      CRM.api3('PeopleFinder', 'search', formData).done(function(result) {
        if (result.values && result.values.length > 0) {
          var html = '<ul>';
          result.values.forEach(function(person) {
            html += '<li>';
            html += '<strong>' + person.display_name + '</strong>';
            if (person.state_province) {
              html += ', ' + person.state_province;
            }
            html += ' <button class="button connect-btn" data-contact-id="' + person.contact_id + '">Connect</button>';
            html += '</li>';
          });
          html += '</ul>';
          
          $('#results-list').html(html);
          $('#search-results').show();
          
          // Handle connect button clicks
          $('.connect-btn').on('click', function() {
            var contactId = $(this).data('contact-id');
            var message = prompt('{ts}Enter a message (optional):{/ts}');
            
            CRM.api3('PeopleFinder', 'connect', {
              contact_id: contactId,
              message: message || ''
            }).done(function(result) {
              alert('{ts}Connection request sent!{/ts}');
            }).fail(function(error) {
              alert('{ts}Error: {/ts}' + error.error_message);
            });
          });
        } else {
          $('#results-list').html('<p>{ts}No results found.{/ts}</p>');
          $('#search-results').show();
        }
      }).fail(function(error) {
        alert('{ts}Error: {/ts}' + error.error_message);
      });
    });
  });
</script>

