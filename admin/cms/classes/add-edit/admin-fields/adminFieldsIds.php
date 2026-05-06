<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/adminFieldsIds.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/adminFieldsIds.php');
}
else
{
	if(!class_exists('adminFieldsIdsAeaf'))
	{
		class adminFieldsIdsAeaf
		{
			public function adminFieldsIdsAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values, $domain)
			{
				$database_table_column_names = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'admin_fields', 'ORDER BY `id` ASC', []);
				
				$new_talbe_column_name_list = '';
				
				foreach($database_table_column_names as $database_table_column_name)
				{
					$new_talbe_column_name_list .= '<option value="'.$database_table_column_name['id'].'">'.$database_table_column_name['id'].' - '.$database_table_column_name['column_name'].'</option>';
				}
				
				$add_new_table_column = '<ul class="table_column_\'+ i +\'"><li class="count">\'+ i +\'</li><li class="move"><i><svg viewBox="0 0 512 512"><path d="m245 7a16 16 0 0 1 22 0l63 63a16 16 0 0 1-22 22l-36-36v120a16 16 0 0 1-32 0v-120l-36 36a16 16 0 1 1-22-22zm11 312a16 16 0 0 1 16 16v120l36-36a16 16 0 0 1 22 22l-63 63a16 16 0 0 1-22 0l-63-63a16 16 0 0 1 22-22l36 36v-120a16 16 0 0 1 16-16m-249-52a16 16 0 0 1 0-22l63-63a16 16 0 1 1 22 22l-36 36h120a16 16 0 0 1 0 32h-120l36 36a16 16 0 0 1-22 22zm312-11a16 16 0 0 1 16-16h120l-36-36a16 16 0 0 1 22-22l63 63a16 16 0 0 1 0 22l-63 63a16 16 0 0 1-22-22l36-36h-120a16 16 0 0 1-16-16"></path></svg></i></li><li><select name="database_tables[admin_fields_ids][]">'.$new_talbe_column_name_list.'</select></li><li></li><li></li><li></li><li></li><li></li><li></li><li><span class="remove removeTableColumn" data-click="\'+ i +\'" title="Remove database column from this database table.">Remove</span></li></ul>';
				
				echo '<script nonce="'.NONCE.'">
		//Make jquery draggable work on mobile with touch
		!function(a){function f(a,b){if(!(a.originalEvent.touches.length>1)){a.preventDefault();var c=a.originalEvent.changedTouches[0],d=document.createEvent("MouseEvents");d.initMouseEvent(b,!0,!0,window,1,c.screenX,c.screenY,c.clientX,c.clientY,!1,!1,!1,!1,0,null),a.target.dispatchEvent(d)}}if(a.support.touch="ontouchend"in document,a.support.touch){var e,b=a.ui.mouse.prototype,c=b._mouseInit,d=b._mouseDestroy;b._touchStart=function(a){var b=this;!e&&b._mouseCapture(a.originalEvent.changedTouches[0])&&(e=!0,b._touchMoved=!1,f(a,"mouseover"),f(a,"mousemove"),f(a,"mousedown"))},b._touchMove=function(a){e&&(this._touchMoved=!0,f(a,"mousemove"))},b._touchEnd=function(a){e&&(f(a,"mouseup"),f(a,"mouseout"),this._touchMoved||f(a,"click"),e=!1)},b._mouseInit=function(){var b=this;b.element.bind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),c.call(b)},b._mouseDestroy=function(){var b=this;b.element.unbind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),d.call(b)}}}(jQuery);
		
				//Sort table row that is a column in db.
				$(function() 
				{
					$("#sortrows").sortable(
					{
						cursor: "move", handle: ".move"								  
					});
				});
				
				//Remove table row that is a column in db.
				$(document).on("click", ".removeTableColumn", function()
				{
					var id = $(this).attr("data-click");
					
					$(".table_column_"+id).remove();
				});
				
				//Add new table row that is a column in db.
				$(document).ready(function () 
				{
					//Display antoher row to add table column
					$(".add-table-column").click(function()
					{
						var addColumn = $("#addColumn").val();
						var i = parseInt(addColumn) + 1;
						var html = \''.$add_new_table_column.'\';
						$("#sortrows").append(html);
						$("#addColumn").val(i);
					});
				});
				</script>';
				
				$field_value_items = '';
				$column_counter = 0;
				
				if(!empty($field_value))
				{
					$all_field_values = explode(',', trim($field_value, ','));
					
					foreach($all_field_values as $all_field_value)
					{
						$column_counter ++;
						$new_talbe_column_name_list = '';
						
						$admin_fields_id = '';
						$admin_fields_data_type = '';
						$admin_fields_character_set_collate = '';
						$admin_fields_is_nullable = '';
						$admin_fields_is_primary_key = '';
						$admin_fields_is_auto_increment = '';
						
						foreach($database_table_column_names as $database_table_column_name)
						{
							$selected = '';
							
							if($all_field_value == $database_table_column_name['id'])
							{
								$selected = ' selected';
								$admin_fields_id = $database_table_column_name['id'];
								$admin_fields_data_type = $database_table_column_name['data_type'];
								$admin_fields_character_set_collate = $database_table_column_name['character_set_and_collate'];
								$admin_fields_is_nullable = $database_table_column_name['is_nullable'];
								$admin_fields_is_primary_key = $database_table_column_name['is_primary_key'];
								$admin_fields_is_auto_increment = $database_table_column_name['is_auto_increment'];
							}
							
							$new_talbe_column_name_list .= '<option value="'.$database_table_column_name['id'].'"'.$selected.'>'.$database_table_column_name['id'].' - '.$database_table_column_name['column_name'].'</option>';
						}
						
						$field_value_items .= '<ul class="table_column_'.$column_counter.'"><li class="count">'.$column_counter.'</li><li class="move"><i><svg viewBox="0 0 512 512"><path d="m245 7a16 16 0 0 1 22 0l63 63a16 16 0 0 1-22 22l-36-36v120a16 16 0 0 1-32 0v-120l-36 36a16 16 0 1 1-22-22zm11 312a16 16 0 0 1 16 16v120l36-36a16 16 0 0 1 22 22l-63 63a16 16 0 0 1-22 0l-63-63a16 16 0 0 1 22-22l36 36v-120a16 16 0 0 1 16-16m-249-52a16 16 0 0 1 0-22l63-63a16 16 0 1 1 22 22l-36 36h120a16 16 0 0 1 0 32h-120l36 36a16 16 0 0 1-22 22zm312-11a16 16 0 0 1 16-16h120l-36-36a16 16 0 0 1 22-22l63 63a16 16 0 0 1 0 22l-63 63a16 16 0 0 1-22-22l36-36h-120a16 16 0 0 1-16-16"></path></svg></i></li><li><select name="database_tables[admin_fields_ids][]">'.$new_talbe_column_name_list.'</select></li><li>'.$admin_fields_id.'</li><li>'.$admin_fields_data_type.'</li><li>'.$admin_fields_character_set_collate.'</li><li>'.$admin_fields_is_nullable.'</li><li>'.$admin_fields_is_primary_key.'</li><li>'.$admin_fields_is_auto_increment.'</li><li><span class="remove removeTableColumn" data-click="'.$column_counter.'" title="Remove database column from this database table.">Remove</span></li></ul>';
					}
				}
				
				echo '
				<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
				<div class="edit-field">
				  <div class="overflow-x-border-radius">
					<div class="database-table-columns">
					  <ul>
						<li class="header count">#</li>
						<li class="header order">Order</li>
						<li class="header">Table Column Name</li>
						<li class="header">Admin Field ID</li>
						<li class="header">Data Type</li>
						<li class="header">Character Set & Collate</li>
						<li class="header">Null</li>
						<li class="header">Primary Key</li>
						<li class="header">Auto Increment</li>
						<li class="header">Remove</li>
					  </ul>
					  <div id="sortrows" class="display-table-row-group">
						'.$field_value_items.'
					  </div>
					</div>
				  </div>
				  <input type="hidden" id="addColumn" value="'.$column_counter.'" />
				  <div class="small-text">'.$admin_field["notes"].'</div>
				</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '
				<span class="add-table-column">+ Add Tabel Column</span>
				</div>';
			}
		}
	
		$class_adminFieldsIdsAeaf = new adminFieldsIdsAeaf();
	}
	
	$class_adminFieldsIdsAeaf->adminFieldsIdsAeaf($table_name, $admin_field, $field_value, $errors, $post_values, $domain);
}