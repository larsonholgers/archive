<form role="form" method="post" enctype="multipart/form-data">
	<div class="row">
		<div class="col-md-3">
			<div class="form-group">	
			<label for="record_id">Record Number</label>
			<input type="text" name="record_id" class="form-control" placeholder="Record ID" value="{$entry.record_id}">
			</div>
			
			<div class="form-group">
			<label for="entry_description">Comments</label>
			<textarea name="entry_comments" class="form-control" rows="3">{$entry.entry_comments}</textarea>
			</div>
			
			<div class="form-group">
			<label for="year">Year</label>
			{formDropDown options=$years name="year" no_selection="- Select 'Year'"}
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
					<span class="glyphicon glyphicon-plus-sign"></span><span class="add_text"> Add '{$f.field_name}'</span>
					</button>
					{/if}
				</label>
			{if $f.field_type == 'dropdown'}
					{if $f.values|@count > 0 && $f.values != ''}
					{formDropDown options=$f.values name=$f.input_dropdown no_selection="- Select '"|cat:$f.field_name|cat:"'"}
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
			<label>Image: </label>
			<input class="form-control" name="image_upload" type="file" />
			</div>
			
			<input type="hidden" name="action" value="add_entry" />
			<button type="submit" class="btn btn-default">Add Entry</button>
		
		</div>
			
	</div>
</form>