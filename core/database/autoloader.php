<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

spl_autoload_register(function ($autoloader_class_name)
{
	$base_directory_path = realpath(__DIR__.'/../../');

	$class_full_path = $base_directory_path.'/'.str_replace('\\', '/', $autoloader_class_name).'.php';

	if(file_exists($class_full_path))
	{
		require_once $class_full_path;
	}
});