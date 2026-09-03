<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/editor/insert-links.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/editor/insert-links.php');
}
else
{
	//Get URL array for $editor_urls variables so the link popup can load all URLs.
	require_once(INSTALLATION_ROOT.'/admin/cms/includes/editor/link-url-options.php');
?>
    <div class="editor-link-overlay form-link-modal">
        <div class="editor-link-window">
            <h3>Link</h3>
            <div class="editor-modal-group-last">
                <label>Option 1: Select Internal URL</label>
                <select class="modal-link-id">
                    <option value="">Select URL</option>
                    <?php
                    if(!empty($editor_urls))
                    {
                        foreach($editor_urls as $editor_url_id => $editor_url)
                        {
                            echo '<option value="'.(int)$editor_url_id.'">'.htmlspecialchars($editor_url, ENT_QUOTES, 'UTF-8').'</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="editor-modal-group">
                <label>Option 2: Full URL</label>
                <input type="text" class="modal-link-url" placeholder="https://www.ratals.com">
            </div>
            <div class="editor-modal-group">
                <label>Target - Open Link In</label>
                <select class="modal-link-target">
                    <option value="">Same Window</option>
                    <option value="_blank">New Window / Tab</option>
                    <option value="_parent">Parent Frame</option>
                    <option value="_top">Top Frame</option>
                </select>
            </div>
            <div class="editor-modal-group-last">
                <label>Link Type</label>
                <div class="editor-link-type">
                    <label><input type="checkbox" class="modal-link-nofollow" value="nofollow">Nofollow</label>
                    <label><input type="checkbox" class="modal-link-sponsored" value="sponsored">Sponsored</label>
                    <label><input type="checkbox" class="modal-link-ugc" value="ugc">UGC</label>
                </div>
            </div>
            <div class="editor-modal-actions">
                <button type="button" class="modal-link-cancel">Cancel</button>
                <button type="button" class="modal-link-submit btn-submit">Save</button>
            </div>
        </div>
    </div>
<?php } ?>