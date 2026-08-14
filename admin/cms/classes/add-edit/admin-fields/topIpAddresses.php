<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/topIpAddresses.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/topIpAddresses.php');
}
else
{
	if(!class_exists('topIpAddressesAeaf'))
	{
		class topIpAddressesAeaf
		{
			public function topIpAddressesAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values, $site_id)
			{
				echo '
				<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">Top 
				<select name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'" class="ip-hits-table-select">
				<option value="10"'; if($field_value == 10) { echo " selected"; } echo '>10</option>
				<option value="25"'; if($field_value == 25) { echo " selected"; } echo '>25</option>
				<option value="50"'; if($field_value == 50) { echo " selected"; } echo '>50</option>
				<option value="100"'; if($field_value == 100) { echo " selected"; } echo '>100</option>
				<option value="250"'; if($field_value == 250) { echo " selected"; } echo '>250</option>
				<option value="500"'; if($field_value == 500) { echo " selected"; } echo '>500</option>
				<option value="1000"'; if($field_value == 1000) { echo " selected"; } echo '>1000</option>
				</select>
				IP Addresses Hitting The Site in the Last 24 Hours</div>
				<div class="edit-field">';
				
				if(empty($field_value) or !is_numeric($field_value)) { $field_value = 10; }
				$sql = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '`ip_address`,COUNT(`ip_address`) AS counter', 'ddos_tracking', 
				'WHERE `site_id` = ? AND `created_date` > UTC_TIMESTAMP() - INTERVAL ? MINUTE GROUP BY `ip_address` ORDER BY counter DESC LIMIT '.$field_value, [$_SESSION["site_set_for_editing"], 1440]);
				
				if(!empty($sql))
				{
					echo '<div class="ip-hits-table">';
					echo '<ul class="header"><li class="hits">Hits</li><li>IP Address</li></ul>';
					foreach($sql as $sql_rows)
					{
						$dont_block = '';
						if($sql_rows['ip_address'] == $_SERVER['REMOTE_ADDR'])
						{
							$dont_block = ' - This is you. Don\'t block yourself. You should probably add your IP Address to the "Allowed IP Addresses". If you do, you will notice the hits stop counting for you.';
						}
						elseif(strpos($sql_rows['ip_address'], '66.249') !== false)
						{
							$dont_block = ' - This is probably Google Bot for your search rankings. A lot of Google IP\'s start with "66.249"';
						}
						
						echo '<ul><li>'.$sql_rows['counter'].'</li><li>'.$sql_rows['ip_address'].$dont_block.'</li></ul>';
					}
					echo '</div>';
				}
				else
				{
					echo '<div class="ip-hits-no-results">No visitors to the site in the past 24 hours.</div>';
				}
				
				echo '<div class="small-text">'.$admin_field["notes"].'</div>
				</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '</div>'; 	
			}
		}
		
		$class_topIpAddressesAeaf = new topIpAddressesAeaf();
	}
	
	$class_topIpAddressesAeaf->topIpAddressesAeaf($table_name, $admin_field, $field_value, $errors, $post_values, $site_id);
}