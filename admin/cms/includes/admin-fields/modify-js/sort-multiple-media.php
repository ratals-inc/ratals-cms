<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/admin-fields/modify-js/sort-multiple-media.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/admin-fields/modify-js/sort-multiple-media.php');
	exit();
}
?>

<script nonce="<?php echo NONCE; ?>">
$(function() 
{
	$("#sortMultipleMedia-<?php echo htmlspecialchars($admin_field["column_name"] ?? ''); ?>").sortable({ cursor: 'move', handle: ".move-handle" });
});
</script>
