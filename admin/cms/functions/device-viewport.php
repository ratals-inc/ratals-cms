<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/functions/device-viewport.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/functions/device-viewport.php');
}
else
{
	//Get correct count of slides to show based on device type. This is important for Cumulative Layout Shifts.
	if(!function_exists('getDeviceType'))
	{
		function getDeviceType($min_slide_width, $slides_in_view)
		{
			if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(iphone|ipod|android.*mobile|windows.*phone|blackberry|webos)/i', $_SERVER['HTTP_USER_AGENT']))
			{
				if(round(375 / $min_slide_width) < $slides_in_view)
				{
					$slides_in_view = round(375 / $min_slide_width);
				}
			}
			elseif(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(ipad|android.*tablet|windows.*tablet|firefox.*tablet)/i', $_SERVER['HTTP_USER_AGENT']))
			{
				if(round(720 / $min_slide_width) < $slides_in_view)
				{
					$slides_in_view = round(720 / $min_slide_width);
				}
			}
			else
			{
				if(round(1200 / $min_slide_width) < $slides_in_view)
				{
					$slides_in_view = round(1200 / $min_slide_width);
				}
			}
			
			if($slides_in_view < 1)
			{
				$slides_in_view = 1;
			}
			
			return $slides_in_view;
		}
	}
}