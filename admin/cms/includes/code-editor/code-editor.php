<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/code-editor/code-editor.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/code-editor/code-editor.php');
}
else
{
?>
	<textarea name="<?php echo htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? ''); ?>" rows="8"><?php echo htmlspecialchars($code_in_file ?? ''); ?></textarea>
<?php } ?>
