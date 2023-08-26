<div class="help">
  <p>{ts}To create an Entity Navigation Link here, use the following information in the appropriate Entity Navigation Link configuration:{/ts}</p>
</div>

<table class="crm-info-panel">
  {foreach from=$rows item=row}
    <tr class="{cycle values="odd-row,even-row"} jentitylink-linkinfo-{$row.key}">
      <td class="label">{$row.label}</td>
      <td>{$row.value}</td>
    </tr>
  {/foreach}
</table>

{capture assign=linksUrl}{crmURL p="civicrm/admin/jentitylink/manage/links" q="reset=1&action=browse"}{/capture}
<p>
  {ts 1=$linksUrl}This information provided by the Entity Navigation Links extensions's Context Inspector. To disable the Context Inspector, visit the <a href="%1">Entity Navigation Links configuration page</a>.{/ts}
</p>