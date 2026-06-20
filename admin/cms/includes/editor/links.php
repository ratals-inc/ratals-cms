<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/editor/editor.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/editor/editor.php');
}
else
{
?>
    <div class="editor-link-overlay form-link-modal">
        <div class="editor-link-window">
            <h3>Insert Link</h3>
            <div class="editor-modal-group">
                <label>Option 1: Full URL</label>
                <input type="text" class="modal-link-url" placeholder="https://www.ratals.com">
            </div>
            <div class="editor-modal-group-last">
                <label>Option 2: Database URL ID</label>
                <input type="text" class="modal-link-id" placeholder="123">
            </div>
            <div class="editor-modal-actions">
                <button type="button" class="modal-link-cancel">Cancel</button>
                <button type="button" class="modal-link-submit btn-submit">Insert</button>
            </div>
        </div>
    </div>
<?php } ?>