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
	<style nonce="<?php echo NONCE; ?>">
    /* EDITOR */
    .editor { border:1px solid #d2d2d2; background: #fff; }
    .editor-toolbar { display: flex; flex-wrap: wrap; gap: 5px; padding: 10px; border-bottom: 1px solid #d2d2d2; background: #f3f3f3; }
    .editor-toolbar button, .editor-toolbar select { width: auto !important; height: 28px !important; padding: 0px 10px 0px 10px !important; border-radius: 4px !important; border: none !important; background: #0d3549; cursor: pointer; color: #fff; }
    .editor-content{ height: 150px; min-height: 150px !important; padding: 10px !important; outline: none; resize: vertical; overflow: auto; box-sizing: border-box; }
	.editor-content > p:first-child { margin-top: 0px; }
    .editor-source { height: 150px; min-height: 150px !important; width: 100%; border: 0; padding: 10px !important; font-family: monospace; resize: vertical; overflow: auto; box-sizing: border-box; border: none !important; }
    /* LINK INTERCEPT MODAL CONTAINER */
    .editor-link-overlay, .form-link-modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 10000; align-items: center; justify-content: center; }
	.hidden-textarea { display: none !important; }
    .editor-link-window { background: #fff; padding: 20px; border-radius: 4px; width: 320px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); font-family: sans-serif; }
    .editor-link-window h3 { margin-top: 0; margin-bottom: 15px; font-size: 16px; }
    .editor-modal-group { margin-bottom: 12px; }
    .editor-modal-group-last { margin-bottom: 20px; }
    .editor-modal-group label, .editor-modal-group-last label { display: block; font-size: 12px; margin-bottom: 4px; font-weight: bold; }
    .editor-modal-group input, .editor-modal-group-last input {width: 100%; height: 34px; padding: 0 8px; border: 1px solid #d2d2d2; box-sizing: border-box; }
    .editor-modal-actions { display: flex; gap: 10px; justify-content: flex-end; }
    .editor-modal-actions button { height: 34px; padding: 0 12px; border: 1px solid #d2d2d2; background: #fff; cursor: pointer; }
    .editor-modal-actions button.btn-submit { background: #f3f3f3; font-weight: bold; }
    /* ALIGNMENT CLASSES (CSP SAFE) */
    .align-left { text-align: left; }
    .align-center { text-align: center; }
    .align-right { text-align: right; }
    </style>
    
    <?php require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/includes/editor/links.php'); ?>
    
    <div class="editor" data-target="<?php echo $editor_name; ?>">
        <div class="editor-toolbar">
            <button type="button" data-command="undo">Undo</button>
            <button type="button" data-command="redo">Redo</button>
            <button type="button" data-command="bold">Bold</button>
            <button type="button" data-command="italic">Italic</button>
            <button type="button" data-command="underline">Underline</button>
            <button type="button" data-command="strikeThrough">Strike</button>
            <button type="button" data-command="insertUnorderedList">UL</button>
            <button type="button" data-command="insertOrderedList">OL</button>
            <select class="heading-select">
                <option value="">Paragraph</option>
                <option value="H1">H1</option>
                <option value="H2">H2</option>
                <option value="H3">H3</option>
                <option value="H4">H4</option>
                <option value="H5">H5</option>
                <option value="H6">H6</option>
            </select>
            <button type="button" data-command="align-left">Left</button>
            <button type="button" data-command="align-center">Center</button>
            <button type="button" data-command="align-right">Right</button>
            <button type="button" class="insert-link">Link</button>
            <button type="button" class="toggle-source">View as Code</button>
        </div>
        <div class="editor-content" contenteditable="true"><?php echo $editor_content; ?></div>
        <textarea class="editor-source hidden-textarea"><?php echo $editor_content; ?></textarea>
    </div>
    <textarea id="<?php echo $editor_name; ?>" class="hidden-textarea" name="<?php echo $editor_name; ?>"></textarea>
<?php } ?>