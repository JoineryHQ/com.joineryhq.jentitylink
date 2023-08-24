{if $action eq 1 || $action eq 2 || $action eq 4 || $action eq 8}
  {include file="CRM/Jentitylink/Form/Link.tpl"}
{else}

  <div class="help">
    <p>{ts}Manage your Entity Links.{/ts}</p> <a href="#">Enable inspection</a>
  </div>

  <div class="crm-content-block crm-block">
  {if $rows}
    <div class="action-link">
      {crmButton p="civicrm/admin/jentitylink/manage/links" q="action=add&reset=1" icon="plus-circle"}{ts}Add Entity Link{/ts}{/crmButton}
    </div>

  <div id="ltype">
      {strip}
    {* handle enable/disable actions*}
    {include file="CRM/common/enableDisableApi.tpl"}
      {include file="CRM/common/jsortable.tpl"}
          <table id="options" class="display">
          <thead>
          <tr>
            <th id="sortable">{ts}ID{/ts}</th>
            <th id="sortable">{ts}Entity name{/ts}</th>
            <th id="sortable">{ts}Entity type(s){/ts}</th>
            <th id="sortable">{ts}Context(s){/ts}</th>
            <th id="sortable">{ts}Link label{/ts}</th>
            <th></th>
          </tr>
          </thead>
          {foreach from=$rows item=row key=linkId}
            <tr id="jentitylink-{$row.id}" class="crm-entity {cycle values="odd-row,even-row"} {$row.class}{if $row.is_active == 0} sdisabled{/if} ">
              <td class="crm-jentitylink-id" data-field="id">{$row.id}</td>
              <td class="crm-jentitylink-entity-name" data-field="entity_name">{$entity_name_options[$row.entity_name]}</td>
              <td class="crm-jentitylink-entity-type" data-field="entity_type">
                {if empty($row.entity_type)}
                  (Any)
                {else}
                  {foreach from=$row.entity_type item=entity_type}
                    {$entity_type_options.$entity_type}<br>
                  {/foreach}
                {/if}
              </td>
              <td class="crm-jentitylink-ops" data-field="ops">
                {foreach from=$opsByLinkId[$row.id] item=op}
                  {$op}<br>
                {/foreach}
              </td>
              <td class="crm-jentitylink-name" data-field="name">{$row.name}</td>
              <td>{$row.extraAction|replace:'xx':$row.id}{$row.action|replace:'xx':$row.id}</td>
            </tr>
          {/foreach}
          </table>
          {/strip}

  </div>
  {else}
      <div class="messages status no-popup">
        <img src="{$config->resourceBase}i/Inform.gif" alt="{ts}status{/ts}"/>
        {ts}None found.{/ts}
      </div>
  {/if}
    <div class="action-link">
      {crmButton p="civicrm/admin/jentitylink/manage/links" q="action=add&reset=1" icon="plus-circle"}{ts}Add Entity Link{/ts}{/crmButton}
    </div>

  </div>
{/if}