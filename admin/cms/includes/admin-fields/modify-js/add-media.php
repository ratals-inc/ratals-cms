<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/modify-js/add-media.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/modify-js/add-media.php');
}
else
{
	if($_SESSION['admin_table_name'] == 'media' && $_SESSION['admin_type'] == 'add' && $_SESSION['admin_class'] != 'add-video-embed')
	{
	?><script nonce="<?php echo NONCE; ?>">
	$(document).ready(function () 
	{
		function _(el)
		{
			return document.getElementById(el);
		}
		
		$('#submit').click(async function ()
		{
			if(window.uploadingInProgress)
			{
				return;
			}
			
			window.uploadingInProgress = true;
			
			var files = _('files').files;
			var totalFiles = files.length;
			
			var maxFileUploads = parseInt('<?php echo ini_get('max_file_uploads'); ?>', 10);
			var uploadMaxFilesize = parseInt('<?php echo ini_get('upload_max_filesize'); ?>', 10) * 1024 * 1024;
			var postMaxSize = parseInt('<?php echo ini_get('post_max_size'); ?>', 10) * 1024 * 1024;
			
			if(totalFiles === 0)
			{
				alert("Please select at least one media file to upload.");
				window.uploadingInProgress = false;
				return;
			}
			
			if(totalFiles > maxFileUploads)
			{
				alert('You cannot select more than ' + maxFileUploads + ' files.');
				window.uploadingInProgress = false;
				return;
			}
			
			let totalSize = 0;
			
			var createSmallerImages = $("#create_smaller_images").is(":checked") ? "Yes" : "No";
			var createAvif = $("#create_avif").is(":checked") ? "Yes" : "No";
			var createWebp = $("#create_webp").is(":checked") ? "Yes" : "No";
			
			for(let file of files)
			{
				totalSize += file.size;
				
				if(file.size > uploadMaxFilesize)
				{
					alert('File "' + file.name + '" exceeds max size.');
					window.uploadingInProgress = false;
					return;
				}
				
				let ext = file.name.toLowerCase().split('.').pop();
			}
			
			if(totalSize > postMaxSize)
			{
				alert('Total upload size exceeds limit.');
				window.uploadingInProgress = false;
				return;
			}
			
			//Progress tracking
			let uploadedFiles = 0;
			let allResponses = [];
			
			function updateProgress()
			{
				let percent = (uploadedFiles / totalFiles) * 100;
				_("progressBar").value = Math.round(percent);
				_("statuss").innerHTML = "Uploading " + uploadedFiles + " / " + totalFiles;
			}
			
			for(let i = 0; i < files.length; i++)
			{
				try
				{
					let response = await uploadSingleFile(files[i], i + 1, totalFiles, createSmallerImages, createAvif, createWebp);
					
					if(response)
					{
						allResponses.push(response);
					}
					
					uploadedFiles++;
					updateProgress();
				}
				catch(error)
				{
					errorHandler({ responseText: error }, 'error', error);
					window.uploadingInProgress = false;
					return;
				}
			}
			
			completeHandler(allResponses);
			window.uploadingInProgress = false;
		});
		
		function uploadSingleFile(file, index, totalFiles, createSmallerImages, createAvif, createWebp)
		{
			return new Promise(function (resolve, reject)
			{
				let formData = new FormData();
				
				formData.append("files[]", file);
				formData.append("admin_table_name", "<?php echo $_SESSION['admin_table_name']; ?>");
				formData.append("admin_type", "<?php echo $_SESSION['admin_type']; ?>");
				formData.append("admin_class", "<?php echo $_SESSION['admin_class']; ?>");
				formData.append("create_smaller_images", createSmallerImages);
				formData.append("create_avif", createAvif);
				formData.append("create_webp", createWebp);
				formData.append("file_index", index);
				
				let xhr = new XMLHttpRequest();
				
				xhr.open("POST", "/<?php echo $_SESSION['admin_directory']; ?>/cms/includes/admin-fields/ajax/add-media.php", true);
				
				xhr.upload.onprogress = function(event)
				{
					if(event.lengthComputable)
					{
						//Use 80% of each file's progress for the actual upload.
						//Reserve the final 20% for server-side image processing.
						let percent = (event.loaded / event.total) * 0.80;
						let overall = ((index - 1) / totalFiles) + (percent / totalFiles);
						
						_("progressBar").value = Math.round(overall * 100);
						_("statuss").innerHTML = "Uploading file " + index + " / " + totalFiles;
					}
				};
				
				xhr.upload.onload = function()
				{
					//The file has finished uploading, but PHP may still be resizing,
					//creating image variants, and saving the media record.
					let overall = ((index - 1) / totalFiles) + (0.80 / totalFiles);
					
					_("progressBar").value = Math.round(overall * 100);
					_("statuss").innerHTML = "Processing file " + index + " / " + totalFiles;
				};
				
				xhr.onload = function()
				{
					if(xhr.status >= 200 && xhr.status < 300)
					{
						try 
						{
							resolve(JSON.parse(xhr.responseText));
						}
						catch(e)
						{
							reject("Invalid JSON response: " + xhr.responseText);
						}
	
					}
					else
					{
						reject(xhr.responseText);
					}
				};
				
				xhr.onerror = function()
				{
					reject("network error");
				};
				
				xhr.send(formData);
			});
		}
		
		function completeHandler(responses)
		{
			_("progressBar").value = 0;
			_("statuss").innerHTML = '';
			
			let summary = { completed: 0, partial: 0, failed: 0, success: [], duplicates: [], invalid_extensions: [] };
			
			if(!Array.isArray(responses))
			{
				responses = [];
			}
			
			responses.forEach(r => 
			{
				if(!r || !r.status)
				{
					return;
				}
	
				if(r.status === "completed")
				{
					summary.completed++;
				}
				
				if(r.status === "partial")
				{
					summary.partial++;
				}
				
				if(r.status === "failed")
				{
					summary.failed++;
				}
				
				if(Array.isArray(r.success))
				{
					summary.success.push(...r.success);
				}
				
				if(Array.isArray(r.duplicates))
				{
					summary.duplicates.push(...r.duplicates);
				}
				
				if(Array.isArray(r.invalid_extensions))
				{
					summary.invalid_extensions.push(...r.invalid_extensions);
				}
			});
			
			let html = "";
			
			if(summary.success.length)
			{
				html += "<div class='changes-saved'>" + summary.success.length + " media file(s) were uploaded successfully.</div>";
			}
			
			if(summary.duplicates.length)
			{
			
				html += "<div class='changes-details'>";
				html += "List of media file name(s) that were not uploaded because media URL(s) already exists. Please rename these and try again.";
				html += "<ol>";
				summary.duplicates.forEach(function (file) 
				{
					html += "<li>" + file + "</li>";
				});
				html += "</ol></div>";
			}
			
			if(summary.invalid_extensions.length)
			{
				//Get unique extensions only
				let uniqueExtensions = [...new Set(summary.invalid_extensions)];
			
				html += "<div class='changes-error'>";
				html += "<p>The following file(s) were not uploaded because their extension(s) are not approved.</p>";
				html += "<p>To allow uploads of these file types, add the required extensions in: Admin > Admin Field Lists under 'Accepted Image Extension Types', 'Accepted Video Extension Types', or 'Accepted File Extension Types'.</p>";
				html += "<p>Make sure the \"value\" matches the exact file extension you are trying to upload.</p>";
				html += "Unapproved extension(s):<ol>";
			
				uniqueExtensions.forEach(function (ext)
				{
					html += "<li>" + ext + "</li>";
				});
			
				html += "</ol>";
				html += "</div>";
			}
			
			_("status").innerHTML = html;
			
			window.uploadingInProgress = false;
		}
		
		function errorHandler(xhr, status, error)
		{
			let errorMessage = "<div class='changes-error'>Upload Failed. Please try again.";
			
			if(xhr && xhr.responseText)
			{
				errorMessage += "<br>Error: " + xhr.responseText;
			}
			else if(status)
			{
				errorMessage += "<br>Status: " + status;
			}
			else if(error)
			{
				errorMessage += "<br>Details: " + error;
			}
			
			errorMessage += "</div>"
			
			_("status").innerHTML = errorMessage;
			
			window.uploadingInProgress = false;
		}
	});
	</script>
	<?php } ?>
<?php } ?>