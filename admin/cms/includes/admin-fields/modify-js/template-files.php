<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/modify-js/template-files.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/modify-js/template-files.php');
}
else
{
	if($_SESSION['admin_table_name'] == 'template_files')
	{
	?>
		<script nonce="<?php echo NONCE; ?>">
        <?php if($_SESSION['admin_type'] == 'add') { ?>
        $( document ).ready(function() 
        {
            $("#template_files_name").on("input", function() 
            {
                var fileName = $("#template_files_name").val();
                var fileName = fileName.toLowerCase().replace(/[^a-z0-9.]/g, "-");
                $("#template_files_filename").val(fileName+'.php');
            });
        });
        <?php
		}
		elseif($_SESSION['admin_type'] == 'edit')
		{
		?>
		$(document).ready(function()
		{
			$(document).on('click', '.duplicate-template-file-button', function(e)
			{
				e.preventDefault();
				
				var button = $(this);
				var templateFileId = button.attr('data-click');
				
				if(!templateFileId)
				{
					alert('A valid Template File ID could not be found.');
					return;
				}
				
				//Prevent multiple copies from being created by repeated clicks.
				if(button.data('copying') === true)
				{
					return;
				}
				
				button.data('copying', true);
				
				var originalText = button.text();
				button.text('Copying...');
				
				$.ajax(
				{
					url: '/<?php echo $_SESSION['admin_directory']; ?>/cms/includes/admin-fields/ajax/template-files.php',
					method: 'POST',
					data:
					{
						template_file_id: templateFileId
					},
					dataType: 'json',
					success: function(response)
					{
						if(response && response.success === true && response.redirect)
						{
							window.location.href = response.redirect;
							return;
						}
						
						var message = 'The template file could not be copied.';
						
						if(response && response.message)
						{
							message = response.message;
						}
						
						alert(message);
						
						button.data('copying', false);
						button.text(originalText);
					},
					error: function(xhr)
					{
						var message = 'The template file could not be copied. Please try again.';
						
						if(xhr.responseJSON && xhr.responseJSON.message)
						{
							message = xhr.responseJSON.message;
						}
						
						alert(message);
						
						button.data('copying', false);
						button.text(originalText);
					}
				});
			});
		});
		<?php } ?>
        </script>
	<?php } ?>
<?php } ?>