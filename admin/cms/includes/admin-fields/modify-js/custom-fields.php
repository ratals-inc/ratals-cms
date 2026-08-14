<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/modify-js/custom-fields.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/modify-js/custom-fields.php');
}
else
{
	if($_SESSION['admin_table_name'] == 'custom_fields')
	{
	?>
	<script nonce="<?php echo NONCE; ?>">
	$(document).ready(function()
	{
		$("#custom_fields_field_type").change(function()
		{
			if(this.value == 'Content Field')
			{						
				$("#display_custom_fields").show();
				$('#custom_fields_cf_display_as option[value="boxes"]').css('display','none');
				$('#custom_fields_cf_display_as option[value="swatch"]').css('display','none');
				$('#custom_fields_cf_display_as option[value="dropdownId"]').css('display','block');
				$('#custom_fields_cf_display_as option[value="singleMedia"]').css('display','block');
				$('#custom_fields_cf_display_as option[value="textarea"]').css('display','block');
				$('#custom_fields_cf_display_as option[value="textareaWithEditor"]').css('display','block');
				$('#custom_fields_cf_display_as option[value="textfield"]').css('display','block');
			}
			else if(this.value == 'Inventory Attribute')
			{
				$("#display_custom_fields").show();
				$('#custom_fields_cf_display_as option[value="boxes"]').css('display','block');
				$('#custom_fields_cf_display_as option[value="swatch"]').css('display','block');
				$('#custom_fields_cf_display_as option[value="dropdownId"]').css('display','block');
				$('#custom_fields_cf_display_as option[value="singleMedia"]').css('display','none');
				$('#custom_fields_cf_display_as option[value="textarea"]').css('display','none');
				$('#custom_fields_cf_display_as option[value="textareaWithEditor"]').css('display','none');
				$('#custom_fields_cf_display_as option[value="textfield"]').css('display','none');
			}
			else if(this.value == 'Product Option')
			{
				$("#display_custom_fields").show();
				$('#custom_fields_cf_display_as option[value="boxes"]').css('display','block');
				$('#custom_fields_cf_display_as option[value="swatch"]').css('display','block');
				$('#custom_fields_cf_display_as option[value="dropdownId"]').css('display','block');
				$('#custom_fields_cf_display_as option[value="singleMedia"]').css('display','none');
				$('#custom_fields_cf_display_as option[value="textarea"]').css('display','block');
				$('#custom_fields_cf_display_as option[value="textareaWithEditor"]').css('display','none');
				$('#custom_fields_cf_display_as option[value="textfield"]').css('display','block');
			}
			else
			{
				$("#display_custom_fields").hide();
			}
		});
		
		var fieldType = $("#custom_fields_field_type").val();
		
		if(fieldType == 'Content Field')
		{						
			$("#display_custom_fields").show();
			$('#custom_fields_cf_display_as option[value="boxes"]').css('display','none');
			$('#custom_fields_cf_display_as option[value="swatch"]').css('display','none');
			$('#custom_fields_cf_display_as option[value="dropdownId"]').css('display','block');
			$('#custom_fields_cf_display_as option[value="singleMedia"]').css('display','block');
			$('#custom_fields_cf_display_as option[value="textarea"]').css('display','block');
			$('#custom_fields_cf_display_as option[value="textareaWithEditor"]').css('display','block');
			$('#custom_fields_cf_display_as option[value="textfield"]').css('display','block');
		}
		else if(fieldType == 'Inventory Attribute')
		{
			$("#display_custom_fields").show();
			$('#custom_fields_cf_display_as option[value="boxes"]').css('display','block');
			$('#custom_fields_cf_display_as option[value="swatch"]').css('display','block');
			$('#custom_fields_cf_display_as option[value="dropdownId"]').css('display','block');
			$('#custom_fields_cf_display_as option[value="singleMedia"]').css('display','none');
			$('#custom_fields_cf_display_as option[value="textarea"]').css('display','none');
			$('#custom_fields_cf_display_as option[value="textareaWithEditor"]').css('display','none');
			$('#custom_fields_cf_display_as option[value="textfield"]').css('display','none');
		}
		else if(fieldType == 'Product Option')
		{
			$("#display_custom_fields").show();
			$('#custom_fields_cf_display_as option[value="boxes"]').css('display','block');
			$('#custom_fields_cf_display_as option[value="swatch"]').css('display','block');
			$('#custom_fields_cf_display_as option[value="dropdownId"]').css('display','block');
			$('#custom_fields_cf_display_as option[value="singleMedia"]').css('display','none');
			$('#custom_fields_cf_display_as option[value="textarea"]').css('display','block');
			$('#custom_fields_cf_display_as option[value="textareaWithEditor"]').css('display','none');
			$('#custom_fields_cf_display_as option[value="textfield"]').css('display','block');
		}
		else
		{
			$("#display_custom_fields").hide();
		}
	
	});
	</script>
	<?php } ?>
<?php } ?>
