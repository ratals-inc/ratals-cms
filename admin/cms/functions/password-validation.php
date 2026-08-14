<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/functions/password-validation.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/functions/password-validation.php');
}
else
{
	if(!function_exists('passwordValidation'))
	{
		function passwordValidation($password)
		{
			$sepcial_character_in_password = 'No';
			if(!empty($password))
			{
				$password_sepcial_characters = array('`','~','!','@','#','$','%','^','&','*','(',')','-','_','+','=','[','{',']','}','\\','|',';',':','\'','"',',','.','?','/');
				foreach($password_sepcial_characters as $password_sepcial_character)
				{
					if(strpos($password, $password_sepcial_character) !== false)
					{
						$sepcial_character_in_password = 'Yes';
						break;
					}
				}
			}
			
			$letter_in_password = 'No';
			if(!empty($password))
			{
				$password_letters = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
				foreach($password_letters as $password_letter)
				{
					if(strpos(strtolower($password), $password_letter) !== false)
					{
						$letter_in_password = 'Yes';
						break;
					}
				}
			}
			
			$number_in_password = 'No';
			if(!empty($password))
			{
				$password_numbers = array('0','1','2','3','4','5','6','7','8','9');
				foreach($password_numbers as $password_number)
				{
					if(strpos($password, $password_number) !== false)
					{
						$number_in_password = 'Yes';
						break;
					}
				}
			}
			
			return array('sepcial_character_in_password' => $sepcial_character_in_password, 'letter_in_password' => $letter_in_password, 'number_in_password' => $number_in_password);
		}
	}
}