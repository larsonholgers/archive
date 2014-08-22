<div class="well">
<form role="form" method="post" enctype="multipart/form-data">
	
	{if $message != ''}
	<div class="row">
		<div class="col-md-6 col-md-offset-3 message">
			{$message}
		</div>
	</div>
	{/if}
	
	<div class="row">
		<div class="col-md-3">
			<div class="form-group">	
			<label for="record_name">Record Name</label>
			<input type="text" name="record_name" class="form-control" placeholder="Record Name" value="{$entry.record_name}">
			</div>
			
			<div class="form-group">
			<label for="entry_comments">Comments</label>
			<textarea name="entry_comments" class="form-control" rows="3">{$entry.entry_comments}</textarea>
			</div>
			
			<div class="form-group">
			<label for="year">Year</label>
			{formDropDown options=$years name="year" selected=$entry.year no_selection="- Select 'Year'"}
			</div>
			
			<div class="form-group">
				<label class="control-label">Date of Loan Receipt</label>				
				<div class="input-group date date-picker" data-date="{$entry.loan_date}" data-date-format="yyyy-mm-dd">
				  <input class="form-control" name="loan_date" type="text" value="{$entry.loan_date}" readonly="">
				  <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
				</div>
			</div>
			
		</div>
		
		
			
		{$total_fields = $fields|@count}
		{$columns = 2}
		{$per_col = ($total_fields/$columns)|ceil}
		{$cnt = 0}
		{foreach $fields as $f}
		{$cnt = $cnt + 1}
		
		{if ($cnt == 1 || $cnt == $per_col + 1)}
		<div class="col-md-3">
		{/if}
		
			<div class="form-group">
				<label for="{$f.input_dropdown}">
					{$f.field_name}
					{if $f.hide_textline == 'Y' && $f.can_add == 'Y'}
					<button type="button" class="btn btn-default btn-xs add_field" data-field="add_field_{$f.field_id}">
					<span class="glyphicon glyphicon-plus-sign"></span>
					</button>
					{/if}
				</label>
			{if $f.field_type == 'dropdown'}
					{if $f.values|@count > 0 && $f.values != ''}
					{formDropDown options=$f.values name=$f.input_dropdown selected=$entry.fields[$f.field_id] no_selection="- Select '"|cat:$f.field_name|cat:"'"}
					{/if}
					
					<div style="margin-top: 5px;">
					
					{if $f.can_add == 'Y'}
					<div id="add_field_{$f.field_id}" {if $f.hide_textline == 'Y'}class="hide"{/if} style="margin-top: 5px;">
						<input type="text" name="{$f.input_textline}" class="form-control" placeholder="Add a new &lsquo;{$f.field_name}&rsquo;">
					</div>
					{/if}
					</div>
				{/if}
			</div>
				<input type="hidden" name="fields[]" value="{$f.field_id}" />
		
		{if ($cnt == $total_fields || $cnt == $per_col)}
		</div>
		{/if}
		
		{/foreach}
		
		<div class="col-md-3">
			
			<div class="form-group">
			<label>Image{if $entry.images|@count > 0}s{/if} : </label>
			{if $entry.images|@count > 0}
				{foreach $entry.images as $img}
					<p>
						{image image_id=$img w=240}
						<br /><a href="{$link_root}edit/{$entry.entry_id}/?action=remove_image&image_id={$img}&entry_id={$entry.entry_id}"><span class="glyphicon glyphicon-trash"></span></a>
					</p>
				{/foreach}
			{/if}
			{if $entry.entry_id != ''}
			<p>Add an additional image</p>
			{/if}
			<input class="form-control" name="image_upload" type="file" />
			</div>
			
			{if $entry.entry_id == ''}
			<input type="hidden" name="action" value="add_entry" />
			<button type="submit" class="btn btn-default">Add Entry</button>
			{else}
			<input type="hidden" name="entry_id" value="{$entry.entry_id}" />
			<input type="hidden" name="action" value="edit_entry" />
			<button type="submit" class="btn btn-default">Update Entry</button>
			{/if}
		
		</div>
			
	</div>
</form>
</div>