<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(isset($sql_get_messages) && !empty($sql_get_messages))
{
	echo 
	'<script nonce="'.NONCE.'">
	//Toggle status
	$(document).ready(function()
	{
		$(".mark-as-read").click(function()
		{
			var messageId = $(this).attr("data-click");
			
			$(".pending-ajax-inner-container span").html("Updating... Hang tight.")
			$("body").addClass("body-pending-ajax");
			$(".pending-ajax").show();
			
			$.post("'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/cms/includes/notices/ajax.php",{type:"markAdRead",id:messageId},
			function(data)
			{
				if(data == 1)
				{
					location.reload();
				}
				else
				{
					alert(data);
					location.reload();
				}
			});
		});
	});
	</script>
	';
	
	if($software_update_message == 'Yes')
	{
		echo 
		'<script nonce="'.NONCE.'">
		//Toggle status
		$(document).ready(function()
		{
			$(".update-software-now").click(function()
			{
				var updateButton = $(this);
				updateButton.prop("disabled", true);
				updateButton.text("Starting Update...");
				
				$(".pending-ajax-inner-container span").html("Starting Update... Hang tight.")
				$("body").addClass("body-pending-ajax");
				$(".pending-ajax").show();
				
				var dataValues = $(this).attr("data-click");
				var dataArray = dataValues.split(",");
				
				var rowId = dataArray[0];
				var newVersionNumber = dataArray[1];
				var upgradeToName = dataArray[2];
				
				$.post("'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/cms/includes/notices/start-update.php",{type:"updateSoftwareNow",noticeId:rowId,versionNumber:newVersionNumber,upgradeTo:upgradeToName},
				function(data)
				{
					try
					{
						var updateResult = JSON.parse(data);
						
						if(updateResult.status === "started")
						{
							location.reload();
						}
						else
						{
							fetch("'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/cms/includes/notices/unset-progress-session.php")
							.finally(() => 
							{
								alert("Error: " + updateResult.message);
								location.reload();
							});
						}
					}
					catch(e)
					{
						fetch("'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/cms/includes/notices/unset-progress-session.php")
						.finally(() => 
						{
							alert("Unexpected response: " + data);
							location.reload();
						});
					}
				});
			});
		});
		</script>
		';
	}
}

//If update in progress and page reloaded from above js/ajax, run below JS to poll progress. This is outside of the $sql_get_messages condition so its included on every admin page load so user can see progress.
//If file exists let this js/ajax continue checking if the update is completed.
if(isset($_SESSION['current_update_log']) && file_exists($_SESSION['current_update_log']))
{
	echo 
	'<script nonce="'.NONCE.'">
	var lastPercentage = 0;
	async function checkProgress()
	{
		var updateResponse = await fetch("'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/cms/includes/notices/update-progress.php");
		var updateData = await updateResponse.json();
		
		var bar = document.querySelector("#progress-bar");
		var text = document.querySelector("#progress-text");
		
		if(bar && text)
		{
			//Update the progress bar only if progress > 0 or if the bar has already started.
			//This prevents a brief gray bar from appearing after the update completes and before the page reloads.
			if(updateData.progress > 0 || parseFloat(bar.style.width) > 0)
			{
				if(updateData.progress >= lastPercentage)
				{
					lastPercentage = updateData.progress;
					bar.style.width = updateData.progress + "%";
					text.textContent = updateData.step_name + " (" + updateData.progress + "%)";
				}
			}
		}
		
		//If the update failed, stop polling and show the error.
		if(updateData.status === "error")
		{
			if(bar && text)
			{
				text.textContent = updateData.error_message;
			}
			
			//Unset update session so progress bar and this js no longer loads.
			fetch("'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/cms/includes/notices/unset-progress-session.php")
			.finally(() =>
			{
				alert(updateData.error_message);
				location.reload();
			});
		}
		else if(updateData.log_exists)
		{
			setTimeout(checkProgress, 2000);
		}
		//When update log file is deleted as the update is completed, reload the page to remove the progress bar.
		else
		{
			if(bar && text)
			{
				bar.style.width = "100%";
				text.textContent = "Software update process completed successfully. (100%)";
			}
			
			//Unset update session so progress bar and this js no longer loads.
			fetch("'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/cms/includes/notices/unset-progress-session.php")
			
			//Delay the page reload by 1 second so user can read progress bar step.
			setTimeout(() => 
			{
				location.reload();
			}, 1000);
		}
	}
	
	checkProgress();
	</script>
	';
}
elseif(isset($_SESSION['current_update_log']))
{
	unset($_SESSION['current_update_log']);
}