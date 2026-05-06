<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/admin-fields/modify-js/add-media.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/admin-fields/modify-js/add-media.php');
}
else
{
	if($_SESSION['admin_table_name'] == 'media' && $_SESSION['admin_type'] == 'add' && $_SESSION['admin_class'] != 'add-video-embed')
	{
	?><script nonce="<?php echo NONCE; ?>">
	$(document).ready(function () {
		function _(el) {
			return document.getElementById(el);
		}
		
		$('#submit').click(function () {
			var files = _('files').files;
			var totalFiles = files.length;
			var maxFileUploads = parseInt('<?php echo ini_get('max_file_uploads'); ?>', 10);
			var uploadMaxFilesize = parseInt('<?php echo ini_get('upload_max_filesize'); ?>', 10) * 1024 * 1024;
			var postMaxSize = parseInt('<?php echo ini_get('post_max_size'); ?>', 10) * 1024 * 1024;
			
			if (totalFiles === 0) {
				alert("Please select at least one media file to upload.");
				return;
			}
			
			if (totalFiles > maxFileUploads) {
				alert('You cannot select more than ' + maxFileUploads + ' files to upload.');
				return;
			}
			
			var formData = new FormData();
			var totalSize = 0;
			//Test are the extensions that can be converted into AVIF and WEBP. This is used to multiple against these for total number being uploaded.
			var allowedExtensions = ['gif', 'jpg', 'jpeg', 'png'];
			var multiplyTotals = 0, dontMultiplyTotals = 0;
			
			//Iterate through selected files
			for (let file of files) {
				totalSize += file.size;
				formData.append("files[]", file);
				
				if (file.size > uploadMaxFilesize) {
					alert('The file "' + file.name + '" exceeds the maximum allowed size of ' + (uploadMaxFilesize / (1024 * 1024)) + ' MB.');
					return;
				}
				
				let fileExtension = file.name.toLowerCase().split('.').pop();
				if (allowedExtensions.includes(fileExtension)) {
					multiplyTotals++;
				} else {
					dontMultiplyTotals++;
				}
			}
			
			if (totalSize > postMaxSize) {
				alert('The total upload size exceeds the allowed limit of ' + (postMaxSize / (1024 * 1024)) + ' MB.');
				return;
			}
			
			//Initialize total images count
			var totalImages = dontMultiplyTotals;
			var createSmallerImages = "No", createAvif = "No", createWebp = "No";
			
			if (multiplyTotals > 0) {
				if ($("#create_smaller_images").is(":checked")) {
					createSmallerImages = "Yes";
				}
				if ($("#create_avif").is(":checked")) {
					createAvif = "Yes";
				}
				if ($("#create_webp").is(":checked")) {
					createWebp = "Yes";
				}
				
				var smallerImagesCount = 0, createAvifCount = 0, createWebpCount = 0, createSmallerMultiplier = 1;
				
				if (createSmallerImages === "Yes") {
					smallerImagesCount = (multiplyTotals * 7) + multiplyTotals;
					createSmallerMultiplier = 7;
				}
				if (createAvif === "Yes") {
					createAvifCount = (multiplyTotals * createSmallerMultiplier) + multiplyTotals;
				}
				if (createWebp === "Yes") {
					createWebpCount = (multiplyTotals * createSmallerMultiplier) + multiplyTotals;
				}
				
				if (createSmallerImages === "No" && createAvif === "No" && createWebp === "No") {
					totalImages += multiplyTotals;
				}
				else {
					totalImages += smallerImagesCount + createAvifCount + createWebpCount;
				}
			}
			
			//Send AJAX request
			$.ajax(
			{
				url: "/<?php echo $_SESSION['admin_directory']; ?>/cms/includes/admin-fields/ajax/add-media.php?total_images=" + totalImages +
					"&create_smaller_images=" + createSmallerImages +
					"&create_avif=" + createAvif +
					"&create_webp=" + createWebp,
				method: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				dataType: 'text',
				success: function (response) {
					completeHandler(response);
				},
				error: function () {
					errorHandler();
				},
				complete: function (xhr) {
					completeHandler(xhr);
				},
				xhr: function () {
					var xhr = new XMLHttpRequest();
					let uploadedImages = 0;
					
					function updateProgress(percent, message) {
						_("progressBar").value = Math.round(percent);
						_("statuss").innerHTML = message;
					}
					
					//Track upload progress (first 50%)
					xhr.upload.addEventListener('progress', function (event) {
						if (event.lengthComputable) {
							let uploadProgress = (event.loaded / totalSize) * 50;
							updateProgress(uploadProgress, "Uploading... " + Math.round(uploadProgress) + "%");
						}
					});
					
					//Track processing progress (50%-99%)
					xhr.addEventListener('progress', function (event) {
						let responseText = event.target.responseText || "";
						let matches = responseText.match(/Uploaded images: (\d+) \/ (\d+)/g);
						
						if (matches) {
							let lastMatch = matches[matches.length - 1].match(/Uploaded images: (\d+) \/ (\d+)/);
							if (lastMatch) {
								uploadedImages = parseInt(lastMatch[1], 10);
							} else {
								uploadedImages = 0;
							}
						} else {
							uploadedImages = 0;
						}
						
						let processingProgress = 50 + ((uploadedImages / totalImages) * 50);
						if (processingProgress >= 99) {
							processingProgress = 99; //Ensure it never reaches 100% so the completed message displays when completed
						}
						
						updateProgress(processingProgress, "Processing upload... " + uploadedImages + " / " + totalImages + " (" + Math.round(processingProgress) + "%)");
					});
					
					return xhr;
				}
			});
		});
		
		function completeHandler(response) {
			_("progressBar").value = 0;
			_("statuss").innerHTML = '';
			
			var responseText = (typeof response === "object" && response.responseText) ? response.responseText : response;
			var finalMessageMatch = responseText.match(/<div class="changes-(saved|error|details)">.*<\/div>/);
			if (finalMessageMatch) {
				_("status").innerHTML = finalMessageMatch[0];
			} else {
				_("status").innerHTML = "Upload complete!";
			}
		}
		
		function errorHandler(xhr, status, error) {
			let errorMessage = "Upload Failed. Please try again.";
			
			if (xhr && xhr.responseText) {
				errorMessage += "<br>Error: " + xhr.responseText;
			} else if (status) {
				errorMessage += "<br>Status: " + status;
			} else if (error) {
				errorMessage += "<br>Details: " + error;
			}
			
			_("status").innerHTML = errorMessage;
		}
	});
	</script>
	<?php } ?>
<?php } ?>