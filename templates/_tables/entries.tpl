{include "pagination.tpl"}

<form method="post">
<table class="table table-striped">
	<thead>
	<tr>
		<th><span class="glyphicon glyphicon-picture"></span></th>
		{$cnt = 0}
		{foreach $fields as $f}
		{if $f.table_display == 'Y'}
		{$cnt = $cnt + 1}
		<th>{$f.field_name}</th>
		{/if}
		{/foreach}
		<th>View</th>
		<th></th>
	</tr>
	</thead>
	
	{foreach $entries as $e}
	<tr>
		<td>
			{if $e.images.0 != ''}
				{image image_id=$e.images.0 w=100}
			{/if}
		</td>
		{foreach $fields as $f}
		{if $f.table_display == 'Y'}
		<td>{$values[$e.values[$f.field_id]]}</td>
		{/if}
		{/foreach}
		<td><a href="{$link_root}edit/{$e.entry_id}/"><span class="glyphicon glyphicon-eye-open"></span></a></td>
		<td><input type="checkbox" name="entry_ids[]" value="{$e.entry_id}" /></td>
	</tr>
	{/foreach}				
	
	<tr>
		<td></td>
		<td colspan="{$cnt}"></td>
		<td></td>
		<td><input type="hidden" name="action" value="delete_entry" />
	<button type="submit" class="btn btn-default btn-xs btn-danger">Delete Selected</button></td>
	</tr>
	
</table>
</form>

{include "pagination.tpl"}
