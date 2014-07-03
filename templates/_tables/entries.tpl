<table class="table table-striped">
	<thead>
	<tr>
		<th><span class="glyphicon glyphicon-picture"></span></th>
		{foreach $fields as $f}
		{if $f.table_display == 'Y'}
		<th>{$f.field_name}</th>
		{/if}
		{/foreach}
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
		<td><a href="#"><span class="glyphicon glyphicon-pencil"></span></a></td>
	</tr>
	{/foreach}				
	
</table>