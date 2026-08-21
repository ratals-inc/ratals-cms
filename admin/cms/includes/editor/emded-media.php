<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/editor/emded-media.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/editor/emded-media.php');
}
else
{
?>
<div class="editor-media-options-overlay">
    <div class="editor-media-options-window">
        <h3>Insert Media</h3>

<div class="editor-modal-group">
    <label>Loading</label>
    <select class="editor-media-lazy-load">
        <option value="lazyLoadYes">Lazy Loading</option>
        <option value="lazyLoadNo">No Lazy Loading</option>
    </select>
    <div class="editor-modal-note">Use Lazy Loading when media requires scrolling to view.</div>
</div>

<div class="editor-modal-group-last">
    <label>Fetch Priority</label>
    <select class="editor-media-fetch-priority">
        <option value="fetchPriorityAuto">Auto</option>
        <option value="fetchPriorityHigh">High</option>
    </select>
    <div class="editor-modal-note">Use High when media is visible without scrolling on page load.</div>
</div>

        <div class="editor-modal-actions">
            <button type="button" class="editor-media-options-cancel">Cancel</button>
            <button type="button" class="editor-media-options-insert btn-submit">Insert</button>
        </div>
    </div>
</div>
<?php } ?>