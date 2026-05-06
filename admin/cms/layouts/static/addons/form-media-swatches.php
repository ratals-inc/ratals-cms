<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/addons/form-media-swatches.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/addons/form-media-swatches.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'form_media_swatches')
	{
	?>
	  <!-- Start Edit View -->
	  <div class="edit-wrapper">
	  
	  <?php if(empty($errors) && isset($_GET['updated']) && $_GET['updated'] == 'success') { echo '<div class="changes-saved">Updated successfully.</div>'; } ?>
	  
	  <form method="post">
	  <div class="margin-border-padding-overflow">
	  <div class="font-font-padding">Assigned Media for Swatch Variants</div>
	  </div>
	  
	  <div class="edit" id="image_swatches">
	  <div class="edit-field">
	  <!-- Start Image Swatch Table -->
	  <div class="overflow-x-auto">
	  <div class="edit-table fixed">
	  <div class="edit-table-row header">
	  <div class="edit-table-cell header order">#</div>
	  <?php 
	  if(!empty($all_variants))
	  {
		  foreach($all_variants as $values)
		  {
			  foreach($values as $key => $value)
			  {
				  if($key != 'swatch_data_array')
				  { 
					  echo '<div class="edit-table-cell header name">'.$key.'</div>';
				  } 
			  }
			  break;
		  }
	  }
	  ?>
	  <div class="edit-table-cell header image">Media</div>
	  <div class="edit-table-cell header notes">Manufacturer</div>
	  <div class="edit-table-cell header notes">Item Number</div>
	  <div class="edit-table-cell header notes">Notes</div>
	  </div>
	  <div class="edit-table-group">
	  <?php 
	  if(!empty($all_variants))
	  {
		  $row_counter = 1;
		  $multiple_media_counter = 1;
		  
		  foreach($all_variants as $keys => $values)
		  {
				  $swatch_media_data = '';
				  $sql_get_swatch_media_rows = '';
				  $number_swatch_selected = '';
				  $color_swatch_selected = '';
				  $counter = 0;
				  
				  $media = array();
				  
				  if(!empty($values['swatch_data_array']["media"]))
				  {
					  $multiple_media = array();
					   
					  //!~~! splits each media
					  if(strpos($values['swatch_data_array']["media"], '*||*') !== false)
					  {
						  $multiple_media = explode('*||*', $values['swatch_data_array']["media"]);
					  }
					  else
					  {
						  $multiple_media[] = $values['swatch_data_array']["media"];
					  }
					  
					  foreach($multiple_media as $media_ids_tags)
					  {
						  //!~~! splits media id and media tag
						  $media_id_tag = explode('~||~',  $media_ids_tags);
						  
						  //Query db for media
						  $sql_media_rows = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ?', [$media_id_tag[0]]);
						  
						  if(!empty($sql_media_rows))
						  {
							  if($sql_media_rows['media_type'] == 'Image')
							  {
								  $original_media_id = $sql_media_rows['original_media_id'];
								  
								  $media[] = array('type' => 'Image', 'media_url' => $domain.'/sites/media/images/'.$original_media_id.'/'.$sql_media_rows['media_url'], 'media_tag' => $sql_media_rows['media_tag'], 'media_tag_value' => $media_id_tag[1], 'media_id_value' => $media_id_tag[0]);
							  }
							  elseif($sql_media_rows['media_type'] == 'File')
							  {
								  $media[] = array('type' => 'File', 'media_url' => $domain.'/sites/media/files/'.$sql_media_rows['media_url'], 'media_tag' => $sql_media_rows['media_tag'], 'media_tag_value' => $media_id_tag[1], 'media_id_value' => $media_id_tag[0]);
							  }
							  elseif($sql_media_rows['media_type'] == 'Video')
							  {
								  $media[] = array('type' => 'Video', 'media_url' => $domain.'/sites/media/videos/'.$sql_media_rows['media_url'], 'media_tag' => $sql_media_rows['media_tag'], 'media_tag_value' => $media_id_tag[1], 'media_id_value' => $media_id_tag[0]);
							  }
							  elseif($sql_media_rows['media_type'] == 'Video Embed')
							  {
								  $media[] = array('type' => 'Video Embed', 'media_url' => $sql_media_rows['media_url'], 'media_tag' => $sql_media_rows['media_tag'], 'media_tag_value' => $media_id_tag[1], 'media_id_value' => $media_id_tag[0]);
							  }
						  }
					  }
				  }
				  
				  $swatch_media_data .= '<ul class="multiple-media_forms multiple-media-'.htmlspecialchars(str_replace(array(',', '|'), '', $keys ?? '')).'" id="sortMultipleMedia-'.htmlspecialchars(str_replace(array(',', '|'), '', $keys ?? '')).'">';
				  
				  //$counter = 0;
				  
				  if(!empty($media))
				  {
					  foreach($media as $media_data)
					  {
						  if($media_data['type'] == 'Image')
						  {
							  $swatch_media_data .= '<li class="media" id="removeMultipleMedia_'.$multiple_media_counter.'">
							  <i class="close removeMultipleMedia" data-click="'.$multiple_media_counter.',single-media-button-'.htmlspecialchars($admin_field["column_name"] ?? '').'" title="Remove Media"><svg viewBox="0 0 512 512"><path d="M506 256A250 250 0 1 1 6 256 250 250 0 0 1 506 256M173 151A16 16 0 1 0 151 173L234 256 151 339A16 16 0 0 0 173 361L256 278 339 361A16 16 0 0 0 361 339L278 256 361 173A16 16 0 0 0 339 151L256 234Z"></path></svg></i>
							  <i class="move move-handle" aria-hidden="true" title="Sort Media"><svg viewBox="0 0 512 512"><path d="m245 7a16 16 0 0 1 22 0l63 63a16 16 0 0 1-22 22l-36-36v120a16 16 0 0 1-32 0v-120l-36 36a16 16 0 1 1-22-22zm11 312a16 16 0 0 1 16 16v120l36-36a16 16 0 0 1 22 22l-63 63a16 16 0 0 1-22 0l-63-63a16 16 0 0 1 22-22l36 36v-120a16 16 0 0 1 16-16m-249-52a16 16 0 0 1 0-22l63-63a16 16 0 1 1 22 22l-36 36h120a16 16 0 0 1 0 32h-120l36 36a16 16 0 0 1-22 22zm312-11a16 16 0 0 1 16-16h120l-36-36a16 16 0 0 1 22-22l63 63a16 16 0 0 1 0 22l-63 63a16 16 0 0 1-22-22l36-36h-120a16 16 0 0 1-16-16"></path></svg></i>
							  <img src="'.htmlspecialchars($media_data['media_url'] ?? '').'" id="media_swap_image_'.$multiple_media_counter.'">
							  <input name="swatches['.$order_counter_swatches.'][swatch_media_id]['.$counter.'][]" type="hidden" value="'.htmlspecialchars($media_data['media_id_value'] ?? '').'">
							  <div class="text"><div class="tag"><input name="swatches['.$order_counter_swatches.'][swatch_media_id]['.$counter.'][]" type="text" value="'.htmlspecialchars($media_data['media_tag_value'] ?? '').'" placeholder="'.htmlspecialchars($media_data['media_tag'] ?? '').'"></div>Media ID: <a href="'.$domain.'/'.$_SESSION['admin_directory'].'/media/?textfield-id='.htmlspecialchars($media_data['media_id_value'] ?? '').'" target="_blank">'.htmlspecialchars($media_data['media_id_value'] ?? '').'</a>
							  </div>
							  </li>';
						  }
						  elseif($media_data['type'] == 'File')
						  {
							  $media_output_data = explode('.', $media_data['media_url']);
							  $swatch_media_data .= '<li class="media" id="removeMultipleMedia_'.$multiple_media_counter.'">
							  <i class="close removeMultipleMedia" data-click="'.$multiple_media_counter.',single-media-button-'.htmlspecialchars($admin_field["column_name"] ?? '').'" title="Remove Media"><svg viewBox="0 0 512 512"><path d="M506 256A250 250 0 1 1 6 256 250 250 0 0 1 506 256M173 151A16 16 0 1 0 151 173L234 256 151 339A16 16 0 0 0 173 361L256 278 339 361A16 16 0 0 0 361 339L278 256 361 173A16 16 0 0 0 339 151L256 234Z"></path></svg></i>
							  <i class="move move-handle" aria-hidden="true" title="Sort Media"><svg viewBox="0 0 512 512"><path d="m245 7a16 16 0 0 1 22 0l63 63a16 16 0 0 1-22 22l-36-36v120a16 16 0 0 1-32 0v-120l-36 36a16 16 0 1 1-22-22zm11 312a16 16 0 0 1 16 16v120l36-36a16 16 0 0 1 22 22l-63 63a16 16 0 0 1-22 0l-63-63a16 16 0 0 1 22-22l36 36v-120a16 16 0 0 1 16-16m-249-52a16 16 0 0 1 0-22l63-63a16 16 0 1 1 22 22l-36 36h120a16 16 0 0 1 0 32h-120l36 36a16 16 0 0 1-22 22zm312-11a16 16 0 0 1 16-16h120l-36-36a16 16 0 0 1 22-22l63 63a16 16 0 0 1 0 22l-63 63a16 16 0 0 1-22-22l36-36h-120a16 16 0 0 1-16-16"></path></svg></i>
							  <object data="'.htmlspecialchars($media_data['media_url'] ?? '').'" type="application/'.end($media_output_data).'" class="display-width-max-height-aspect-16-15"></object>
							  <input name="swatches['.$order_counter_swatches.'][swatch_media_id]['.$counter.'][]" type="hidden" value="'.htmlspecialchars($media_data['media_id_value'] ?? '').'">
							  <div class="text"><div class="tag"><input name="swatches['.$order_counter_swatches.'][swatch_media_id]['.$counter.'][]" type="text" value="'.htmlspecialchars($media_data['media_tag_value'] ?? '').'" placeholder="'.htmlspecialchars($media_data['media_tag'] ?? '').'"></div>Media ID: <a href="'.$domain.'/'.$_SESSION['admin_directory'].'/media/?textfield-id='.htmlspecialchars($media_data['media_id_value'] ?? '').'" target="_blank">'.htmlspecialchars($media_data['media_id_value'] ?? '').'</a>
							  </div></li>';
						  }
						  elseif($media_data['type'] == 'Video')
						  {
							  $media_output_data = explode('.', $media_data['media_url']);
							  $swatch_media_data .= '<li class="media" id="removeMultipleMedia_'.$multiple_media_counter.'">
							  <i class="close removeMultipleMedia" data-click="'.$multiple_media_counter.',single-media-button-'.htmlspecialchars($admin_field["column_name"] ?? '').'" title="Remove Media"><svg viewBox="0 0 512 512"><path d="M506 256A250 250 0 1 1 6 256 250 250 0 0 1 506 256M173 151A16 16 0 1 0 151 173L234 256 151 339A16 16 0 0 0 173 361L256 278 339 361A16 16 0 0 0 361 339L278 256 361 173A16 16 0 0 0 339 151L256 234Z"></path></svg></i>
							  <i class="move move-handle" aria-hidden="true" title="Sort Media"><svg viewBox="0 0 512 512"><path d="m245 7a16 16 0 0 1 22 0l63 63a16 16 0 0 1-22 22l-36-36v120a16 16 0 0 1-32 0v-120l-36 36a16 16 0 1 1-22-22zm11 312a16 16 0 0 1 16 16v120l36-36a16 16 0 0 1 22 22l-63 63a16 16 0 0 1-22 0l-63-63a16 16 0 0 1 22-22l36 36v-120a16 16 0 0 1 16-16m-249-52a16 16 0 0 1 0-22l63-63a16 16 0 1 1 22 22l-36 36h120a16 16 0 0 1 0 32h-120l36 36a16 16 0 0 1-22 22zm312-11a16 16 0 0 1 16-16h120l-36-36a16 16 0 0 1 22-22l63 63a16 16 0 0 1 0 22l-63 63a16 16 0 0 1-22-22l36-36h-120a16 16 0 0 1-16-16"></path></svg></i>
							  <video class="display-width-max-height-aspect-16-15" controls="" preload="none"><source src="'.htmlspecialchars($media_data['media_url'] ?? '').'" type="video/'.end($media_output_data).'"></video>
							  <input name="swatches['.$order_counter_swatches.'][swatch_media_id]['.$counter.'][]" type="hidden" value="'.htmlspecialchars($media_data['media_id_value'] ?? '').'">
							  <div class="text"><div class="tag"><input name="swatches['.$order_counter_swatches.'][swatch_media_id]['.$counter.'][]" type="text" value="'.htmlspecialchars($media_data['media_tag_value'] ?? '').'" placeholder="'.htmlspecialchars($media_data['media_tag'] ?? '').'"></div>Media ID: <a href="'.$domain.'/'.$_SESSION['admin_directory'].'/media/?textfield-id='.htmlspecialchars($media_data['media_id_value'] ?? '').'" target="_blank">'.htmlspecialchars($media_data['media_id_value'] ?? '').'</a>
							  </div></li>';
						  }
						  elseif($media_data['type'] == 'Video Embed')
						  {
							  $media_output_data = explode('.', $media_data['media_url']);
							  $swatch_media_data .= '<li class="media" id="removeMultipleMedia_'.$multiple_media_counter.'">
							  <i class="close removeMultipleMedia" data-click="'.$multiple_media_counter.',single-media-button-'.htmlspecialchars($admin_field["column_name"] ?? '').'" title="Remove Media"><svg viewBox="0 0 512 512"><path d="M506 256A250 250 0 1 1 6 256 250 250 0 0 1 506 256M173 151A16 16 0 1 0 151 173L234 256 151 339A16 16 0 0 0 173 361L256 278 339 361A16 16 0 0 0 361 339L278 256 361 173A16 16 0 0 0 339 151L256 234Z"></path></svg></i>
							  <i class="move move-handle" aria-hidden="true" title="Sort Media"><svg viewBox="0 0 512 512"><path d="m245 7a16 16 0 0 1 22 0l63 63a16 16 0 0 1-22 22l-36-36v120a16 16 0 0 1-32 0v-120l-36 36a16 16 0 1 1-22-22zm11 312a16 16 0 0 1 16 16v120l36-36a16 16 0 0 1 22 22l-63 63a16 16 0 0 1-22 0l-63-63a16 16 0 0 1 22-22l36 36v-120a16 16 0 0 1 16-16m-249-52a16 16 0 0 1 0-22l63-63a16 16 0 1 1 22 22l-36 36h120a16 16 0 0 1 0 32h-120l36 36a16 16 0 0 1-22 22zm312-11a16 16 0 0 1 16-16h120l-36-36a16 16 0 0 1 22-22l63 63a16 16 0 0 1 0 22l-63 63a16 16 0 0 1-22-22l36-36h-120a16 16 0 0 1-16-16"></path></svg></i>
							  <iframe class="display-width-max-height-aspect-16-15" src="'.htmlspecialchars($media_data['media_url'] ?? '').'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
							  <input name="swatches['.$order_counter_swatches.'][swatch_media_id]['.$counter.'][]" type="hidden" value="'.htmlspecialchars($media_data['media_id_value'] ?? '').'">
							  <div class="text"><div class="tag"><input name="swatches['.$order_counter_swatches.'][swatch_media_id]['.$counter.'][]" type="text" value="'.htmlspecialchars($media_data['media_tag_value'] ?? '').'" placeholder="'.htmlspecialchars($media_data['media_tag'] ?? '').'"></div>Media ID: <a href="'.$domain.'/'.$_SESSION['admin_directory'].'/media/?textfield-id='.htmlspecialchars($media_data['media_id_value'] ?? '').'" target="_blank">'.htmlspecialchars($media_data['media_id_value'] ?? '').'</a>
							  </div></li>';
						  }
						  $counter ++;
						  $multiple_media_counter++;
					  }
				  }
				  $swatch_media_data .= '<input name="next_media_counter" id="selectedMediaId" type="hidden" value="'.($multiple_media_counter - 1).'">';
				  $swatch_media_data .= '</ul>';
				  
				  echo '<ul class="edit-table-row">';
				  echo '<div class="edit-table-cell border-left-1px-solid-d6d6d6">'.$row_counter.'</div>';
				  foreach($values as $key => $value)
				  {
					  if($key != 'swatch_data_array')
					  {
						  echo '<div class="edit-table-cell">'.htmlspecialchars($value["label"] ?? '').'</div>';
					  }
				  }
				  
				  echo '<input name="swatches['.$order_counter_swatches.'][swatch_id]" type="hidden" value="'.htmlspecialchars($keys ?? '').'">
				  
				  <div class="edit-table-cell">
					'.$swatch_media_data.'
					
					<div class="button text-align-left">
					  <button type="button" class="openMultipleMediaPopup" data-click="'.htmlspecialchars('swatches['.$order_counter_swatches.'][swatch_media_id]' ?? '').','.htmlspecialchars(str_replace(array(',', '|'), '', $keys ?? '')).','.$counter.'">Add Media</button>
					</div>
					
				  </div>
				  
				  <div class="edit-table-cell"><input name="swatches['.$order_counter_swatches.'][swatch_manufacturer]" type="text" value="'.htmlspecialchars($values['swatch_data_array']["manufacturer"] ?? '').'"></div>
				  <div class="edit-table-cell"><input name="swatches['.$order_counter_swatches.'][swatch_item_number]" type="text" value="'.htmlspecialchars($values['swatch_data_array']["item_number"] ?? '').'"></div>
				  <div class="edit-table-cell"><input name="swatches['.$order_counter_swatches.'][swatch_notes]" type="text" value="'.htmlspecialchars($values['swatch_data_array']["notes"] ?? '').'"></div>
				  </ul>';
				  
				  $order_counter_swatches++;
				  $row_counter++;
		  }
	  }
	  ?>
	  </div>
	  
	  </div>
	  </div>
	  <!-- End Image Swatch Table -->
	  <?php 
	  if(empty($all_variants))
	  {
		  echo '<div class="table-no-results">You have no form fields assigned that are a Swatch.</div>';
	  }
	  ?>
	  </div>
	  </div>
	  
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
	  
	  <div class="button-right"><button type="submit" name="submit">Save</button></div>
	  </form>
	  </div>
	  <!-- End Edit View -->
	  <?php include_once $_SERVER['DOCUMENT_ROOT'].'/admin/cms/includes/media-popup.php'; ?>
	<?php } ?>
<?php } ?>