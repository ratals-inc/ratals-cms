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
        <div class="editor-modal-group">
            <label>Fetch Priority</label>
            <select class="editor-media-fetch-priority">
                <option value="fetchPriorityAuto">Auto</option>
                <option value="fetchPriorityHigh">High</option>
            </select>
            <div class="editor-modal-note">Use High when media is visible without scrolling on page load.</div>
        </div>
        <div class="editor-modal-group">
            <label>Maximum Display Width</label>
            <input type="number" class="editor-media-max-display-width" min="1" step="1" placeholder="Leave blank for automatic">
            <div class="editor-modal-note">Optional. Enter the maximum width in pixels that this media should display. Leave blank for it to size automatically.</div>
        </div>
        <div class="editor-modal-group-last">
            <div class="editor-modal-note"><strong>Note:</strong> These values can also be changed directly in the media tag after insertion. For example, changing the Maximum Display Width from 500 to 250 will display the media smaller and help the browser select a smaller image variant when available.</div>
        </div>
        <div class="editor-modal-actions">
            <button type="button" class="editor-media-options-cancel">Cancel</button>
            <button type="button" class="editor-media-options-insert btn-submit">Insert</button>
        </div>
    </div>
</div>
<?php } ?>