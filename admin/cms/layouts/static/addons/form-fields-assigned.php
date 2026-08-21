<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/addons/form-fields-assigned.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/addons/form-fields-assigned.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'form_fields_assigned')
	{
	?>
	  <!-- Start Form Fields Assigned -->
	  <div class="edit-wrapper">
	  <?php if(empty($errors) && isset($_GET['updated']) && $_GET['updated'] == 'success') { echo '<div class="changes-saved">Updated successfully.</div>'; } ?>
	  <div class="margin-border-padding-overflow">
	  <div class="float-font-font-padding">Assigned Form Fields</div>
	  <div class="float-right">
		  <div class="results-buttons">
		  <button type="button" id="show-fields">Manage Form Fields</button>
		  </div>
	  </div>
	  </div>
	  
	  <div class="overflow-x-auto fixed-scrollbar">
	  <div class="edit-table fixed">
	  
	  <div class="edit-table-row header">
	  <div class="edit-table-cell header move-width">ID</div>
	  <div class="edit-table-cell header">Frontend Name</div>
	  <div class="edit-table-cell header">Admin Name</div>
	  <div class="edit-table-cell header">Field Type</div>
	  <div class="edit-table-cell header">Auto Complete</div>
	  <div class="edit-table-cell header">Required</div>
	  <div class="edit-table-cell header">Frontend Name Class</div>
	  </div>
	  
	  <div class="edit-table-group" id="form_fields-assigned">
	  <?php
	  if(!empty($form_fields_assigned_array))
	  {
		  foreach($form_fields_assigned_array as $form_fields_assigned)
		  {
	  ?>
	  <ul class="edit-table-row">
		<input name="assigned_form_fields[]" type="hidden" value="<?php echo $form_fields_assigned['id']; ?>">
		<div class="edit-table-cell"><a href="/<?php echo $_SESSION['admin_directory']; ?>/website/form-fields/edit/?rid=<?php echo $form_fields_assigned['id']; ?>" target="_blank"><?php echo $form_fields_assigned['id']; ?></a></div>
		<div class="edit-table-cell"><?php echo $form_fields_assigned['frontend_name']; ?></div>
		<div class="edit-table-cell"><?php echo $form_fields_assigned['admin_name']; ?></div>
		<div class="edit-table-cell"><?php echo $form_fields_assigned['form_field_type']; ?></div>
		<div class="edit-table-cell"><?php echo $form_fields_assigned['auto_complete']; ?></div>
		<div class="edit-table-cell"><?php echo $form_fields_assigned['required']; ?></div>
		<div class="edit-table-cell"><?php echo $form_fields_assigned['name_class']; ?></div>
	  </ul>
	  <?php
		  }
	  ?>
	  </div>
	  
	  </div>
	  </div>
	  <?php
	  }
	  else
	  {
		  echo '</div></div>';
		  echo '<div class="table-no-results">No Form Fields Assigned</div></div>';
	  }
	  ?>
	  
		<div class="edit margin-top-25px">
		<div class="edit-label">Updated Date</div>
		<div class="edit-field text"><?php echo utcToUserTimeZone($sql_record_data_rows["updated_date"], 'F d, Y - g:i:s A'); ?></div>
		</div>
		
		<div class="edit">
		<div class="edit-label">Updated By</div>
		<div class="edit-field text"><?php echo $sql_record_data_rows["updated_by"]; ?></div>
		</div>
		
		<div class="edit">
		<div class="edit-label">Created Date</div>
		<div class="edit-field text"><?php echo utcToUserTimeZone($sql_record_data_rows["created_date"], 'F d, Y - g:i:s A'); ?></div>
		</div>
		
		<div class="edit">
		<div class="edit-label">Created By</div>
		<div class="edit-field text"><?php echo $sql_record_data_rows["created_by"]; ?></div>
		</div>
	
	  </div>
	  <!-- End Form Fields Assigned -->
	  
	  <!-- Start Popup for Form Fields Assigned -->
	  <?php
	  $form_fields_assigned_array = array();
	  $form_fields_not_assigned_array = array();
	  
	  $sql_get_form_fields = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'form_fields', 'ORDER BY `frontend_name` ASC', []);
	  
	  if(!empty($sql_get_form_fields))
	  {
		  foreach($sql_get_form_fields as $all_form_fields)
		  {
			  if(in_array($all_form_fields['id'], $form_fields_assigned_ids))
			  {
				  $form_fields_assigned_array[] = $all_form_fields;
			  }
			  else
			  {			
				  $form_fields_not_assigned_array[] = $all_form_fields;
			  }
		  }
	  }
	  ?>
	  <div class="popup admin-width" id="form_fields">
	  <div class="columns">
	  <div class="header">
	  <div class="header-top">
	  <div class="headline">Manage Form Fields</div>
	  <div class="close" id="hide-form_fields"><i><svg viewBox="0 0 512 512"><path d="M506 256A250 250 0 1 1 6 256 250 250 0 0 1 506 256M173 151A16 16 0 1 0 151 173L234 256 151 339A16 16 0 0 0 173 361L256 278 339 361A16 16 0 0 0 361 339L278 256 361 173A16 16 0 0 0 339 151L256 234Z"></path></svg></i></div>
	  </div>
	  </div>
		  <form method="post">
		  <ul id="sort-form_fields">
			  <?php 
			  if(!empty($form_fields_assigned_array))
			  {
				  foreach($form_fields_assigned_ids as $form_fields_ids_in_order)
				  {
					  foreach($form_fields_assigned_array as $form_fields_assigned)
					  {
						  if($form_fields_ids_in_order == $form_fields_assigned['id'])
						  {
						  ?>
							  <li>
							  <div id="sort-fields-handle">
							  <div class="column-box">
							  <div class="column-box-left"><input name="form_fields[]" type="checkbox" value="<?php echo $form_fields_assigned['id']; ?>" checked></div>
							  <div class="column-box-right">
							  <div class="column-box-name"><?php echo $form_fields_assigned['frontend_name']; ?></div>
							  <div class="column-box-type">ID: <a href="/<?php echo $_SESSION['admin_directory']; ?>/website/form-fields/edit/?rid=<?php echo $form_fields_assigned['id']; ?>" target="_blank"><?php echo $form_fields_assigned['id']; ?></a> | Field Type: <?php echo $form_fields_assigned['form_field_type']; ?></div>
							  </div>
							  </div>
							  </div>
							  </li>
						  <?php
						  }
					  }
				  }
			  }
			  
			  if(!empty($form_fields_not_assigned_array))
			  {
				  foreach($form_fields_not_assigned_array as $form_fields_not_assigned)
				  {
				  ?>
					  <li>
					  <div id="sort-fields-handle">
					  <div class="column-box">
					  <div class="column-box-left"><input name="form_fields[]" type="checkbox" value="<?php echo $form_fields_not_assigned['id']; ?>"></div>
					  <div class="column-box-right">
					  <div class="column-box-name"><?php echo $form_fields_not_assigned['frontend_name']; ?></div>
					  <div class="column-box-type">ID: <a href="/<?php echo $_SESSION['admin_directory']; ?>/website/form-fields/edit/?rid=<?php echo $form_fields_not_assigned['id']; ?>" target="_blank"><?php echo $form_fields_not_assigned['id']; ?></a> | Field Type: <?php echo $form_fields_not_assigned['form_field_type']; ?></div>
					  </div>
					  </div>
					  </div>
					  </li>
				  <?php
				  }
			  }
			  ?>
	  
		  </ul>
		  <div class="popup-button"><button type="submit" name="add_from_fields">Save</button></div>
		  </form>
	  </div>
	  </div>
	  <!-- End Popup for Form Fields Assigned -->
	<?php } ?>
<?php } ?>