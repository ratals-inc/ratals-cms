<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/editor/editor.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/editor/editor.php');
}
else
{
?>
    <?php require_once(INSTALLATION_ROOT.'/admin/cms/includes/editor/insert-links.php'); ?>
    <?php require_once(INSTALLATION_ROOT.'/admin/cms/includes/media-popup.php'); ?>
    <?php require_once(INSTALLATION_ROOT.'/admin/cms/includes/editor/emded-media.php'); ?>
    <div class="editor" data-target="<?php echo $editor_name; ?>">
        <div class="editor-toolbar">
            <button type="button" data-command="undo"><svg viewBox="0 0 512 512" aria-hidden="true"><path d="M212 96H96l48-48-32-32L8 120l104 104 32-32-48-48h116c110 0 200 90 200 200v40h48v-40c0-137-111-248-248-248z"></path></svg> Undo</button>
            <button type="button" data-command="redo"><svg viewBox="0 0 512 512" aria-hidden="true"><path d="M300 96h116l-48-48 32-32 104 104-104 104-32-32 48-48H300c-110 0-200 90-200 200v40H52v-40C52 207 163 96 300 96z"></path></svg> Redo</button>
            <button type="button" data-command="bold"><svg viewBox="0 0 512 512" aria-hidden="true"><path d="M128 48h160c75 0 128 43 128 108 0 45-25 79-63 96 49 15 79 53 79 104 0 69-55 108-144 108H128V48zm80 64v112h72c35 0 56-20 56-56s-21-56-56-56h-72zm0 176v112h80c40 0 64-19 64-56s-24-56-64-56h-80z"></path></svg> Bold</button>
            <button type="button" data-command="italic"><svg viewBox="0 0 512 512" aria-hidden="true"><path d="M192 48h224v64h-72L240 400h80v64H96v-64h72l104-288h-80V48z"></path></svg> Italic</button>
            <button type="button" data-command="underline"><svg viewBox="0 0 512 512" aria-hidden="true"><path d="M112 48h72v216c0 77 28 112 72 112s72-35 72-112V48h72v216c0 116-57 176-144 176s-144-60-144-176V48zM80 464h352v32H80v-32z"></path></svg> Underline</button>
            <button type="button" data-command="strikeThrough"><svg viewBox="0 0 512 512" aria-hidden="true"><path d="M256 32c-92 0-152 45-152 112 0 42 23 73 67 96H32v48h448v-48H288c-72-19-104-36-104-72 0-31 28-52 72-52 46 0 82 18 112 49l48-45C374 62 321 32 256 32zm-86 304c20 43 56 64 102 64 48 0 80-21 80-53 0-4 0-7-1-11h77c1 6 1 12 1 18 0 76-65 126-157 126-88 0-151-42-177-112l75-32z"></path></svg> Strike</button>
            <button type="button" data-command="insertUnorderedList"><svg viewBox="0 0 512 512" aria-hidden="true"><path d="M64 80a32 32 0 1 1-64 0 32 32 0 1 1 64 0zm64-16h352v32H128V64zM64 256a32 32 0 1 1-64 0 32 32 0 1 1 64 0zm64-16h352v32H128v-32zM64 432a32 32 0 1 1-64 0 32 32 0 1 1 64 0zm64-16h352v32H128v-32z"></path></svg> Bullets</button>
            <button type="button" data-command="insertOrderedList"><svg viewBox="0 0 512 512" aria-hidden="true"><path d="M16 32h32v96H24V64H16V32zm0 144h48v24L32 240h32v24H16v-24l32-40H16v-24zm0 144h48v96H16v-24h24v-12H24v-24h16v-16H16v-24zm112-256h352v32H128V64zm0 176h352v32H128v-32zm0 176h352v32H128v-32z"></path></svg> Numbered</button>
            <select class="heading-select">
                <option value="">Paragraph</option>
                <option value="H1">H1</option>
                <option value="H2">H2</option>
                <option value="H3">H3</option>
                <option value="H4">H4</option>
                <option value="H5">H5</option>
                <option value="H6">H6</option>
            </select>
            <button type="button" data-command="align-left"><svg viewBox="0 0 512 512" aria-hidden="true"><path d="M32 64h448v48H32V64zm0 112h288v48H32v-48zm0 112h448v48H32v-48zm0 112h288v48H32v-48z"></path></svg> Left</button>
            <button type="button" data-command="align-center"><svg viewBox="0 0 512 512" aria-hidden="true"><path d="M32 64h448v48H32V64zm80 112h288v48H112v-48zM32 288h448v48H32v-48zm80 112h288v48H112v-48z"></path></svg> Center</button>
            <button type="button" data-command="align-right"><svg viewBox="0 0 512 512" aria-hidden="true"><path d="M32 64h448v48H32V64zm160 112h288v48H192v-48zM32 288h448v48H32v-48zm160 112h288v48H192v-48z"></path></svg> Right</button>
            <button type="button" class="insert-link"><svg viewBox="0 0 512 512" aria-hidden="true"><path d="M326.6 185.4c-12.5-12.5-32.8-12.5-45.3 0l-96 96c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l96-96c12.5-12.5 12.5-32.8 0-45.3zM160 384c-17 0-33.3-6.7-45.3-18.7s-18.7-28.3-18.7-45.3 6.7-33.3 18.7-45.3l80-80c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-80 80C45.3 253.4 32 285.9 32 320s13.3 66.6 37.5 90.5S125.9 448 160 448s66.6-13.3 90.5-37.5l80-80c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-80 80C193.3 377.3 177 384 160 384zm282.5-282.5C418.6 77.3 386.1 64 352 64s-66.6 13.3-90.5 37.5l-80 80c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l80-80C318.7 134.7 335 128 352 128s33.3 6.7 45.3 18.7S416 175 416 192s-6.7 33.3-18.7 45.3l-80 80c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l80-80C466.7 258.6 480 226.1 480 192s-13.3-66.6-37.5-90.5z"></path></svg> Link</button>
           <button type="button" class="insert-media"><svg viewBox="0 0 512 512" aria-hidden="true"><path d="M464 64H48C21.5 64 0 85.5 0 112v288c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48zM48 112h416v196.7l-89.4-89.4c-6.2-6.2-16.4-6.2-22.6 0L240 331.3l-57.4-57.4c-6.2-6.2-16.4-6.2-22.6 0L48 385.9V112zm416 288H70.6l100.7-100.7 57.4 57.4c6.2 6.2 16.4 6.2 22.6 0L363.3 244.7 464 345.4V400zM144 224c26.5 0 48-21.5 48-48s-21.5-48-48-48-48 21.5-48 48 21.5 48 48 48z"></path></svg> Media</button>
            <button type="button" class="toggle-source"><svg viewBox="0 0 512 512" aria-hidden="true"><path d="M160 128L32 256l128 128 34-34-94-94 94-94-34-34zm192 0l-34 34 94 94-94 94 34 34 128-128-128-128zM294 80l-76 352h-49l76-352h49z"></path></svg> View as Code</button>
        </div>
        <div class="editor-content" contenteditable="true"><?php echo $editor_content; ?></div>
        <textarea class="editor-source hidden-textarea"><?php echo $editor_content; ?></textarea>
    </div>
    <textarea id="<?php echo $editor_name; ?>" class="hidden-textarea" name="<?php echo $editor_name; ?>"></textarea>
<?php } ?>