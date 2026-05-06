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
	.display-none { display: none; }
    .display-block { display: block; }
    .mergeModal { display: none; position: fixed; top: 30%; left: 50%; transform: translateX(-50%); background: white; padding: 16px; border: 1px solid #ccc; box-shadow: 0 0 10px rgba(0,0,0,0.2); z-index: 999; font-family: sans-serif; }
    .mergeModal2 { margin: 0 0 10px; }
    .mergeDirectio { margin-right: 12px; }
    .mergeSpan { width: 60px; padding: 4px; margin-left: 5px; }
    .mergeSpan2 { margin-right: 12px; }
    .mergeConfirmBtn { padding: 6px 12px; margin-right: 8px; cursor: pointer; }
    .mergeConfirmBtn2 { padding: 6px 12px; cursor: pointer; }
    .addCellModal { display: none; position: fixed; top: 30%; left: 50%; transform: translateX(-50%); background: white; padding: 16px; border: 1px solid #ccc; box-shadow: 0 0 10px rgba(0,0,0,0.2); z-index: 999; font-family: sans-serif; }
	.addCellModal2 { margin: 0 0 10px; }
    .insertCell { width: 60px; padding: 4px; margin-left: 5px; }
    .insertCell2 { margin-right: 12px; }
    .addCellConfirmBtn { padding: 6px 12px; margin-right: 8px; cursor: pointer; }
    .addCellConfirmBtn2 { padding: 6px 12px; cursor: pointer; }
	
		/* Styles For Content Editor */
		.editor-wrapper { position: relative; width: 100%; overflow-y: auto;  height: 100%; border-radius: 4px; }
		.editor-container { display: flex; gap: 0px; resize: vertical; overflow-y: auto; border-radius: 4px; background-color: #fff; }
		.editor-styles-container { min-width: 280px; width: 280px; height: 100%; }
		.editor-styles { height: calc(100% - 20px); height: 100%; overflow: auto; border-right: 1px solid #ecebeb; background: #f3f3f3; padding: 10px; font-size: 13px; box-sizing: border-box; }
		.editor-styles h3 { margin: 0px 0px 15px 0px; font-size: 18px; }
		.editor-styles input, .editor-styles select { width: 100%; margin: 3px 0px 10px 0px; border-radius: 3px; border: 1px solid #c1c1c1; padding: 4px; box-sizing: border-box; }
		.editor-styles button { margin-right: 5px;background-color: #07344a; border: none; padding: 4px 6px; margin-bottom: 10px; color: #fff; border-radius: 3px; cursor: pointer; font-size: 13px; width: 100%; }
		.style-group { background-color: #e1e1e1; padding: 8px; border-radius: 3px; margin-bottom: 10px; }
		.heading { font-weight: bold; padding: 5px; background-color: #b5b5b5; border-radius: 3px; margin-bottom: 8px; }
		.editor-wrapper button { margin-right: 5px; background-color: #07344a; border: none; padding: 4px 6px; margin-bottom: 10px; color: #fff; border-radius: 3px; cursor: pointer; font-size: 13px; }
		.editor-wrapper select { margin-right: 2px; padding: 3px 6px; margin-bottom: 5px; border-radius: 3px; cursor: pointer; }
		.editor-wrapper button:hover, #tools select:hover { background-color: #106da3; }
		#tools { position: sticky; top: 0px; padding-top: 10px; padding-left: 10px; border-bottom: 2px solid #ccc; background-color: #f3f3f3; z-index: 100;  }
		#tools select { margin-right: 2px; background-color: #07344a; border: none; padding: 3px 6px; margin-bottom: 5px; color: #fff; border-radius: 3px; cursor: pointer; font-size: 13px; }
		#tools button:hover, #tools select:hover { background-color: #106da3; }
		.editor-parent { min-height: 300px; height: auto; overflow-y: auto; padding: 10px; border: 1px solid #ccc; border-top: none; box-sizing: border-box; }
		.editor-parent .element-info { font-size: 10px; font-weight: 400; padding: 2px; display: block; position: absolute; background: #ededed; border: none !important; margin-left: 0px; top: 0; left: 0;  white-space: nowrap; pointer-events: none; }
		.editor-parent .element-info:hover { z-index: 300; }
		.editor-parent * { position: relative; padding-top: 20px; margin: 0px 5px 5px 5px; }
		.editor-parent span.element-highlighter { display: inline-block; }
		.editor-parent strong.element-highlighter, .editor-parent em.element-highlighter, .editor-parent u.element-highlighter, .editor-parent s.element-highlighter { display: inline-block; }
		.bold-text { font-weight: bold; }
		.underline-text { text-decoration: underline; }
		.italic-text { font-style: italic; }
		.strike-text { text-decoration: line-through; }
		.align-left { text-align: left; text-align-last: left; }
		.align-center { text-align: center; text-align-last: center; }
		.align-right { text-align: right; text-align-last: right; }
		.justify-full { text-align: justify; text-align-last: justify; }
		#source-code-popup { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: calc(100% - 40px); height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.7); padding: 20px; }
		#source-content { background-color: #fff; border-radius: 5px; padding: 20px; height: calc(100% - 80px); }
		.source-content-buttons { text-align: right; }
		.source-content-buttons button { cursor: pointer; }
		#source-code { width: calc(100% - 20px); height: 200px; font-family: monospace; border: 1px solid #ccc; border-radius: 5px; padding: 10px; resize: none; margin-bottom: 10px; height: calc(100% - 120px); }
		.highlighted { border: 2px solid #00b8ff!important; }
		.element-highlighter { border: 1px dashed #000; }
		
		.link-manager-popup-wrapper { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: calc(100% - 40px); height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.7); padding: 20px; }
		.link-manager { background-color: #fff; border-radius: 5px; padding: 20px; height: calc(100% - 80px); }
		.link-manager h3 { margin-top: 0; }
		.link-manager input, .link-manager select { width: 100%; padding: 8px; margin-top: 5px; margin-bottom: 10px; }
		.link-manager button { padding: 8px 15px; cursor: pointer; border: none; border-radius: 5px; margin-left: 10px; }
		.link-manager #insertLinkBtn { background-color: #007BFF; color: white; }
		.link-manager #cancelBtn { background-color: #ccc; color: white; }
		.link-manager div { margin-top: 15px; text-align: right; }
		.link-manager-show { display: block; }
		.link-manager-hide { display: none; }
		
		.hidden {
			display: none;
		}
		
		#editStylesPopupWrapper { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: calc(100% - 40px); height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.7); padding: 20px; }
		.editStylesPopup { background-color: #fff; border-radius: 5px; padding: 20px; height: calc(100% - 80px); overflow-y: scroll; }
		.editStylesPopup .heading { background-color: #dddddd; padding: 5px; border-radius: 3px; font-weight: 600; }
		.editStylesPopup form { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 10px; }
		.editStylesPopup label { display: block; margin-bottom: 3px; }
		.editStylesPopup input, .editStylesPopup select { width: 100%; }
		
		/* -- CPL's CSS -- */
		.editor-parent .btn-delete{
			font-size: 10px;
			padding: 2px;
			position: absolute;
			background-color: #ff4545;
			border: none !important;
			margin-right: 0px;
			top: 0;
			right: 0;
			z-index: 1;
		}
		.editor-parent .btn-handle{
			font-size: 10px;
			padding: 2px;
			position: absolute;
			background-color: #525252 !important;
			border: none !important;
			margin-right: 0px;
			top: 0;
			right: 40px;
			cursor: grab;
			user-select: none !important;
			-webkit-user-select: none !important;
			-moz-user-select: none !important;
			-ms-user-select: none !important;
			z-index: 1;
		}
		.btn-handle span {
			padding: 0px !important;
			pointer-events: none; /* Prevents interaction with the text */
		}
		.editor-parent .btn-delete:hover {
			background-color: #f38b8b !important;
		}
		
		#insertLinkButton:disabled {
			background-color: #07344a75 !important;
			cursor: not-allowed;
		}
		
		td.selected {
			background-color: #f0f8ff;
		}
		.drop-placeholder {
			height: 5px !important;
			border: 2px dashed blue;
			width: 100% !important;
		}
    </style>
    
    <div class="editor-container">
        <!-- Start Editor Styles -->
        <div class="editor-styles-container">
            <div class="editor-styles">
                <h3>Edit Styles</h3>
                <div class="form">
                    <div>
                    <label>Display:
                    <select class="display"  name="display">
                        <option value=""></option>
                        <option value="display: block">display: block</option>
                        <option value="display: flex">display: flex</option>
                        <option value="display: grid">display: grid</option>
                        <option value="display: inline">display: inline</option>
                        <option value="display: inline-block">display: inline-block</option>
                        <option value="display: inline-flex">display: inline-flex</option>
                        <option value="display: inline-grid">display: inline-grid</option>
                        <option value="display: inline-table">display: inline-table</option>
                        <option value="display: none">display: none</option>
                        <option value="display: table">display: table</option>
                        <option value="display: table-cell">display: table-cell</option>
                        <option value="display: table-column">display: table-column</option>
                        <option value="display: table-column-group">display: table-column-group</option>
                        <option value="display: table-footer-group">display: table-footer-group</option>
                        <option value="display: table-header-group">display: table-header-group</option>
                        <option value="display: table-row">display: table-row</option>
                        <option value="display: table-row-group">display: table-row-group</option>
                    </select>
                    </label>
                    </div>
                    <div class="style-group display-grid displayGridArea display-none" id="displayGridArea"></div>
                    <div class="style-group display-flex displayFlexArea display-none" id="displayFlexArea"></div>
                    <div class="style-group display-flex generalAlignArea display-block" id="generalAlignArea">
                        <div class="heading">Alignment Options</div>
                        <div>
                            <label>Align Items (Horizontal):
                            <select name="align-items">
                                <option value=""></option>
                                <option value="align-items: start">align-items: start</option>
                                <option value="align-items: center">align-items: center</option>
                                <option value="align-items: end">align-items: end</option>
                            </select>
                            </label>
                        </div>
                    </div>
                    <div>
                    <label >Aspect Ratio:
                    <select name="aspect-ratio">
                        <option value=""></option>
                        <option value="aspect-ratio: 16 / 1">aspect-ratio: 16 / 1</option>
                        <option value="aspect-ratio: 16 / 2">aspect-ratio: 16 / 2</option>
                        <option value="aspect-ratio: 16 / 3">aspect-ratio: 16 / 3</option>
                        <option value="aspect-ratio: 16 / 4">aspect-ratio: 16 / 4</option>
                        <option value="aspect-ratio: 16 / 5">aspect-ratio: 16 / 5</option>
                        <option value="aspect-ratio: 16 / 6">aspect-ratio: 16 / 6</option>
                        <option value="aspect-ratio: 16 / 7">aspect-ratio: 16 / 7</option>
                        <option value="aspect-ratio: 16 / 8">aspect-ratio: 16 / 8</option>
                        <option value="aspect-ratio: 16 / 9">aspect-ratio: 16 / 9</option>
                        <option value="aspect-ratio: 16 / 10">aspect-ratio: 16 / 10</option>
                        <option value="aspect-ratio: 16 / 11">aspect-ratio: 16 / 11</option>
                        <option value="aspect-ratio: 16 / 12">aspect-ratio: 16 / 12</option>
                        <option value="aspect-ratio: 16 / 13">aspect-ratio: 16 / 13</option>
                        <option value="aspect-ratio: 16 / 14">aspect-ratio: 16 / 14</option>
                        <option value="aspect-ratio: 16 / 15">aspect-ratio: 16 / 15</option>
                        <option value="aspect-ratio: 16 / 16">aspect-ratio: 16 / 16</option>
                    </select>
                    </label>
                    </div>
                    <div>
                        <label>Background Color:
                        <input type="color" class="background-color" id="background-color" name="background-color" value="#ffffff" />
                        </label>
                    </div>
                    <div class="style-group">
                        <div class="heading">Border Options</div>
                        <div>
                        <label>Border - All Sides (Thickness):
                        <select name="border">
                            <option value=""></option>
                            <option value="border: 0px">border: 0px</option>
                            <option value="border: 1px">border: 1px</option>
                            <option value="border: 2px">border: 2px</option>
                            <option value="border: 3px">border: 3px</option>
                            <option value="border: 4px">border: 4px</option>
                            <option value="border: 5px">border: 5px</option>
                            <option value="border: 6px">border: 6px</option>
                            <option value="border: 7px">border: 7px</option>
                            <option value="border: 8px">border: 8px</option>
                            <option value="border: 9px">border: 9px</option>
                            <option value="border: 10px">border: 10px</option>
                            <option value="border: 15px">border: 15px</option>
                            <option value="border: 20px">border: 20px</option>
                            <option value="border: 25px">border: 25px</option>
                            <option value="border: 30px">border: 30px</option>
                            <option value="border: 35px">border: 35px</option>
                            <option value="border: 40px">border: 40px</option>
                            <option value="border: 45px">border: 45px</option>
                            <option value="border: 50px">border: 50px</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Border Top (Thickness):
                        <select name="border-top">
                            <option value=""></option>
                            <option value="border-top: 0px">border-top: 0px</option>
                            <option value="border-top: 1px">border-top: 1px</option>
                            <option value="border-top: 2px">border-top: 2px</option>
                            <option value="border-top: 3px">border-top: 3px</option>
                            <option value="border-top: 4px">border-top: 4px</option>
                            <option value="border-top: 5px">border-top: 5px</option>
                            <option value="border-top: 6px">border-top: 6px</option>
                            <option value="border-top: 7px">border-top: 7px</option>
                            <option value="border-top: 8px">border-top: 8px</option>
                            <option value="border-top: 9px">border-top: 9px</option>
                            <option value="border-top: 10px">border-top: 10px</option>
                            <option value="border-top: 15px">border-top: 15px</option>
                            <option value="border-top: 20px">border-top: 20px</option>
                            <option value="border-top: 25px">border-top: 25px</option>
                            <option value="border-top: 30px">border-top: 30px</option>
                            <option value="border-top: 35px">border-top: 35px</option>
                            <option value="border-top: 40px">border-top: 40px</option>
                            <option value="border-top: 45px">border-top: 45px</option>
                            <option value="border-top: 50px">border-top: 50px</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Border Right (Thickness):
                        <select name="border-right">
                            <option value=""></option>
                            <option value="border-right: 0px">border-right: 0px</option>
                            <option value="border-right: 1px">border-right: 1px</option>
                            <option value="border-right: 2px">border-right: 2px</option>
                            <option value="border-right: 3px">border-right: 3px</option>
                            <option value="border-right: 4px">border-right: 4px</option>
                            <option value="border-right: 5px">border-right: 5px</option>
                            <option value="border-right: 6px">border-right: 6px</option>
                            <option value="border-right: 7px">border-right: 7px</option>
                            <option value="border-right: 8px">border-right: 8px</option>
                            <option value="border-right: 9px">border-right: 9px</option>
                            <option value="border-right: 10px">border-right: 10px</option>
                            <option value="border-right: 15px">border-right: 15px</option>
                            <option value="border-right: 20px">border-right: 20px</option>
                            <option value="border-right: 25px">border-right: 25px</option>
                            <option value="border-right: 30px">border-right: 30px</option>
                            <option value="border-right: 35px">border-right: 35px</option>
                            <option value="border-right: 40px">border-right: 40px</option>
                            <option value="border-right: 45px">border-right: 45px</option>
                            <option value="border-right: 50px">border-right: 50px</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Border Bottom (Thickness):
                        <select name="border-bottom">
                            <option value=""></option>
                            <option value="border-bottom: 0px">border-bottom: 0px</option>
                            <option value="border-bottom: 1px">border-bottom: 1px</option>
                            <option value="border-bottom: 2px">border-bottom: 2px</option>
                            <option value="border-bottom: 3px">border-bottom: 3px</option>
                            <option value="border-bottom: 4px">border-bottom: 4px</option>
                            <option value="border-bottom: 5px">border-bottom: 5px</option>
                            <option value="border-bottom: 6px">border-bottom: 6px</option>
                            <option value="border-bottom: 7px">border-bottom: 7px</option>
                            <option value="border-bottom: 8px">border-bottom: 8px</option>
                            <option value="border-bottom: 9px">border-bottom: 9px</option>
                            <option value="border-bottom: 10px">border-bottom: 10px</option>
                            <option value="border-bottom: 15px">border-bottom: 15px</option>
                            <option value="border-bottom: 20px">border-bottom: 20px</option>
                            <option value="border-bottom: 25px">border-bottom: 25px</option>
                            <option value="border-bottom: 30px">border-bottom: 30px</option>
                            <option value="border-bottom: 35px">border-bottom: 35px</option>
                            <option value="border-bottom: 40px">border-bottom: 40px</option>
                            <option value="border-bottom: 45px">border-bottom: 45px</option>
                            <option value="border-bottom: 50px">border-bottom: 50px</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Border Left (Thickness):
                        <select name="border-left">
                            <option value=""></option>
                            <option value="border-left: 0px">border-left: 0px</option>
                            <option value="border-left: 1px">border-left: 1px</option>
                            <option value="border-left: 2px">border-left: 2px</option>
                            <option value="border-left: 3px">border-left: 3px</option>
                            <option value="border-left: 4px">border-left: 4px</option>
                            <option value="border-left: 5px">border-left: 5px</option>
                            <option value="border-left: 6px">border-left: 6px</option>
                            <option value="border-left: 7px">border-left: 7px</option>
                            <option value="border-left: 8px">border-left: 8px</option>
                            <option value="border-left: 9px">border-left: 9px</option>
                            <option value="border-left: 10px">border-left: 10px</option>
                            <option value="border-left: 15px">border-left: 15px</option>
                            <option value="border-left: 20px">border-left: 20px</option>
                            <option value="border-left: 25px">border-left: 25px</option>
                            <option value="border-left: 30px">border-left: 30px</option>
                            <option value="border-left: 35px">border-left: 35px</option>
                            <option value="border-left: 40px">border-left: 40px</option>
                            <option value="border-left: 45px">border-left: 45px</option>
                            <option value="border-left: 50px">border-left: 50px</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Border Style:
                        <select name="border-style">
                            <option value=""></option>
                            <option value="border-style: dashed">border-style: dashed</option>
                            <option value="border-style: dotted">border-style: dotted</option>
                            <option value="border-style: double">border-style: double</option>
                            <option value="border-style: groove">border-style: groove</option>
                            <option value="border-style: hidden">border-style: hidden</option>
                            <option value="border-style: inherit">border-style: inherit</option>
                            <option value="border-style: inset">border-style: inset</option>
                            <option value="border-style: none">border-style: none</option>
                            <option value="border-style: outset">border-style: outset</option>
                            <option value="border-style: ridge">border-style: ridge</option>
                            <option value="border-style: solid">border-style: solid</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Border Color:
                            <input type="color" class="border-color" id="border-color" name="border-color" value="#000000" />
                        </label>
                        </div>
                    </div>
                    <div class="style-group">
                        <div class="heading">Border Radius Options</div>
                        <div>
                        <label>Border Radius (All Sides):
                        <select name="border-radius">
                            <option value=""></option>
                            <option value="border-radius: 0px">border-radius: 0px</option>
                            <option value="border-radius: 1px">border-radius: 1px</option>
                            <option value="border-radius: 2px">border-radius: 2px</option>
                            <option value="border-radius: 3px">border-radius: 3px</option>
                            <option value="border-radius: 4px">border-radius: 4px</option>
                            <option value="border-radius: 5px">border-radius: 5px</option>
                            <option value="border-radius: 6px">border-radius: 6px</option>
                            <option value="border-radius: 7px">border-radius: 7px</option>
                            <option value="border-radius: 8px">border-radius: 8px</option>
                            <option value="border-radius: 9px">border-radius: 9px</option>
                            <option value="border-radius: 10px">border-radius: 10px</option>
                            <option value="border-radius: 15px">border-radius: 15px</option>
                            <option value="border-radius: 20px">border-radius: 20px</option>
                            <option value="border-radius: 25px">border-radius: 25px</option>
                            <option value="border-radius: 30px">border-radius: 30px</option>
                            <option value="border-radius: 35px">border-radius: 35px</option>
                            <option value="border-radius: 40px">border-radius: 40px</option>
                            <option value="border-radius: 45px">border-radius: 45px</option>
                            <option value="border-radius: 50px">border-radius: 50px</option>
                            <option value="border-radius: 75px">border-radius: 75px</option>
                            <option value="border-radius: 100px">border-radius: 100px</option>
                            <option value="border-radius: 1%">border-radius: 1%</option>
                            <option value="border-radius: 2%">border-radius: 2%</option>
                            <option value="border-radius: 3%">border-radius: 3%</option>
                            <option value="border-radius: 4%">border-radius: 4%</option>
                            <option value="border-radius: 5%">border-radius: 5%</option>
                            <option value="border-radius: 6%">border-radius: 6%</option>
                            <option value="border-radius: 7%">border-radius: 7%</option>
                            <option value="border-radius: 8%">border-radius: 8%</option>
                            <option value="border-radius: 9%">border-radius: 9%</option>
                            <option value="border-radius: 10%">border-radius: 10%</option>
                            <option value="border-radius: 15%">border-radius: 15%</option>
                            <option value="border-radius: 20%">border-radius: 20%</option>
                            <option value="border-radius: 25%">border-radius: 25%</option>
                            <option value="border-radius: 30%">border-radius: 30%</option>
                            <option value="border-radius: 35%">border-radius: 35%</option>
                            <option value="border-radius: 40%">border-radius: 40%</option>
                            <option value="border-radius: 45%">border-radius: 45%</option>
                            <option value="border-radius: 50%">border-radius: 50%</option>
                            <option value="border-radius: 75%">border-radius: 75%</option>
                            <option value="border-radius: 100%">border-radius: 100%</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Border Top Left Radius:
                        <select name="border-top-left-radius">
                            <option value=""></option>
                            <option value="border-top-left-radius: 0px">border-top-left-radius: 0px</option>
                            <option value="border-top-left-radius: 1px">border-top-left-radius: 1px</option>
                            <option value="border-top-left-radius: 2px">border-top-left-radius: 2px</option>
                            <option value="border-top-left-radius: 3px">border-top-left-radius: 3px</option>
                            <option value="border-top-left-radius: 4px">border-top-left-radius: 4px</option>
                            <option value="border-top-left-radius: 5px">border-top-left-radius: 5px</option>
                            <option value="border-top-left-radius: 6px">border-top-left-radius: 6px</option>
                            <option value="border-top-left-radius: 7px">border-top-left-radius: 7px</option>
                            <option value="border-top-left-radius: 8px">border-top-left-radius: 8px</option>
                            <option value="border-top-left-radius: 9px">border-top-left-radius: 9px</option>
                            <option value="border-top-left-radius: 10px">border-top-left-radius: 10px</option>
                            <option value="border-top-left-radius: 15px">border-top-left-radius: 15px</option>
                            <option value="border-top-left-radius: 20px">border-top-left-radius: 20px</option>
                            <option value="border-top-left-radius: 25px">border-top-left-radius: 25px</option>
                            <option value="border-top-left-radius: 30px">border-top-left-radius: 30px</option>
                            <option value="border-top-left-radius: 35px">border-top-left-radius: 35px</option>
                            <option value="border-top-left-radius: 40px">border-top-left-radius: 40px</option>
                            <option value="border-top-left-radius: 45px">border-top-left-radius: 45px</option>
                            <option value="border-top-left-radius: 50px">border-top-left-radius: 50px</option>
                            <option value="border-top-left-radius: 75px">border-top-left-radius: 75px</option>
                            <option value="border-top-left-radius: 100px">border-top-left-radius: 100px</option>
                            <option value="border-top-left-radius: 1%">border-top-left-radius: 1%</option>
                            <option value="border-top-left-radius: 2%">border-top-left-radius: 2%</option>
                            <option value="border-top-left-radius: 3%">border-top-left-radius: 3%</option>
                            <option value="border-top-left-radius: 4%">border-top-left-radius: 4%</option>
                            <option value="border-top-left-radius: 5%">border-top-left-radius: 5%</option>
                            <option value="border-top-left-radius: 6%">border-top-left-radius: 6%</option>
                            <option value="border-top-left-radius: 7%">border-top-left-radius: 7%</option>
                            <option value="border-top-left-radius: 8%">border-top-left-radius: 8%</option>
                            <option value="border-top-left-radius: 9%">border-top-left-radius: 9%</option>
                            <option value="border-top-left-radius: 10%">border-top-left-radius: 10%</option>
                            <option value="border-top-left-radius: 15%">border-top-left-radius: 15%</option>
                            <option value="border-top-left-radius: 20%">border-top-left-radius: 20%</option>
                            <option value="border-top-left-radius: 25%">border-top-left-radius: 25%</option>
                            <option value="border-top-left-radius: 30%">border-top-left-radius: 30%</option>
                            <option value="border-top-left-radius: 35%">border-top-left-radius: 35%</option>
                            <option value="border-top-left-radius: 40%">border-top-left-radius: 40%</option>
                            <option value="border-top-left-radius: 45%">border-top-left-radius: 45%</option>
                            <option value="border-top-left-radius: 50%">border-top-left-radius: 50%</option>
                            <option value="border-top-left-radius: 75%">border-top-left-radius: 75%</option>
                            <option value="border-top-left-radius: 100%">border-top-left-radius: 100%</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Border Top Right Radius:
                        <select name="border-top-right-radius">
                            <option value=""></option>
                            <option value="border-top-right-radius: 0px">border-top-right-radius: 0px</option>
                            <option value="border-top-right-radius: 1px">border-top-right-radius: 1px</option>
                            <option value="border-top-right-radius: 2px">border-top-right-radius: 2px</option>
                            <option value="border-top-right-radius: 3px">border-top-right-radius: 3px</option>
                            <option value="border-top-right-radius: 4px">border-top-right-radius: 4px</option>
                            <option value="border-top-right-radius: 5px">border-top-right-radius: 5px</option>
                            <option value="border-top-right-radius: 6px">border-top-right-radius: 6px</option>
                            <option value="border-top-right-radius: 7px">border-top-right-radius: 7px</option>
                            <option value="border-top-right-radius: 8px">border-top-right-radius: 8px</option>
                            <option value="border-top-right-radius: 9px">border-top-right-radius: 9px</option>
                            <option value="border-top-right-radius: 10px">border-top-right-radius: 10px</option>
                            <option value="border-top-right-radius: 15px">border-top-right-radius: 15px</option>
                            <option value="border-top-right-radius: 20px">border-top-right-radius: 20px</option>
                            <option value="border-top-right-radius: 25px">border-top-right-radius: 25px</option>
                            <option value="border-top-right-radius: 30px">border-top-right-radius: 30px</option>
                            <option value="border-top-right-radius: 35px">border-top-right-radius: 35px</option>
                            <option value="border-top-right-radius: 40px">border-top-right-radius: 40px</option>
                            <option value="border-top-right-radius: 45px">border-top-right-radius: 45px</option>
                            <option value="border-top-right-radius: 50px">border-top-right-radius: 50px</option>
                            <option value="border-top-right-radius: 75px">border-top-right-radius: 75px</option>
                            <option value="border-top-right-radius: 100px">border-top-right-radius: 100px</option>
                            <option value="border-top-right-radius: 1%">border-top-right-radius: 1%</option>
                            <option value="border-top-right-radius: 2%">border-top-right-radius: 2%</option>
                            <option value="border-top-right-radius: 3%">border-top-right-radius: 3%</option>
                            <option value="border-top-right-radius: 4%">border-top-right-radius: 4%</option>
                            <option value="border-top-right-radius: 5%">border-top-right-radius: 5%</option>
                            <option value="border-top-right-radius: 6%">border-top-right-radius: 6%</option>
                            <option value="border-top-right-radius: 7%">border-top-right-radius: 7%</option>
                            <option value="border-top-right-radius: 8%">border-top-right-radius: 8%</option>
                            <option value="border-top-right-radius: 9%">border-top-right-radius: 9%</option>
                            <option value="border-top-right-radius: 10%">border-top-right-radius: 10%</option>
                            <option value="border-top-right-radius: 15%">border-top-right-radius: 15%</option>
                            <option value="border-top-right-radius: 20%">border-top-right-radius: 20%</option>
                            <option value="border-top-right-radius: 25%">border-top-right-radius: 25%</option>
                            <option value="border-top-right-radius: 30%">border-top-right-radius: 30%</option>
                            <option value="border-top-right-radius: 35%">border-top-right-radius: 35%</option>
                            <option value="border-top-right-radius: 40%">border-top-right-radius: 40%</option>
                            <option value="border-top-right-radius: 45%">border-top-right-radius: 45%</option>
                            <option value="border-top-right-radius: 50%">border-top-right-radius: 50%</option>
                            <option value="border-top-right-radius: 75%">border-top-right-radius: 75%</option>
                            <option value="border-top-right-radius: 100%">border-top-right-radius: 100%</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Border Bottom Left Radius:
                        <select name="border-bottom-left-radius">
                            <option value=""></option>
                            <option value="border-bottom-left-radius: 0px">border-bottom-left-radius: 0px</option>
                            <option value="border-bottom-left-radius: 1px">border-bottom-left-radius: 1px</option>
                            <option value="border-bottom-left-radius: 2px">border-bottom-left-radius: 2px</option>
                            <option value="border-bottom-left-radius: 3px">border-bottom-left-radius: 3px</option>
                            <option value="border-bottom-left-radius: 4px">border-bottom-left-radius: 4px</option>
                            <option value="border-bottom-left-radius: 5px">border-bottom-left-radius: 5px</option>
                            <option value="border-bottom-left-radius: 6px">border-bottom-left-radius: 6px</option>
                            <option value="border-bottom-left-radius: 7px">border-bottom-left-radius: 7px</option>
                            <option value="border-bottom-left-radius: 8px">border-bottom-left-radius: 8px</option>
                            <option value="border-bottom-left-radius: 9px">border-bottom-left-radius: 9px</option>
                            <option value="border-bottom-left-radius: 10px">border-bottom-left-radius: 10px</option>
                            <option value="border-bottom-left-radius: 15px">border-bottom-left-radius: 15px</option>
                            <option value="border-bottom-left-radius: 20px">border-bottom-left-radius: 20px</option>
                            <option value="border-bottom-left-radius: 25px">border-bottom-left-radius: 25px</option>
                            <option value="border-bottom-left-radius: 30px">border-bottom-left-radius: 30px</option>
                            <option value="border-bottom-left-radius: 35px">border-bottom-left-radius: 35px</option>
                            <option value="border-bottom-left-radius: 40px">border-bottom-left-radius: 40px</option>
                            <option value="border-bottom-left-radius: 45px">border-bottom-left-radius: 45px</option>
                            <option value="border-bottom-left-radius: 50px">border-bottom-left-radius: 50px</option>
                            <option value="border-bottom-left-radius: 75px">border-bottom-left-radius: 75px</option>
                            <option value="border-bottom-left-radius: 100px">border-bottom-left-radius: 100px</option>
                            <option value="border-bottom-left-radius: 1%">border-bottom-left-radius: 1%</option>
                            <option value="border-bottom-left-radius: 2%">border-bottom-left-radius: 2%</option>
                            <option value="border-bottom-left-radius: 3%">border-bottom-left-radius: 3%</option>
                            <option value="border-bottom-left-radius: 4%">border-bottom-left-radius: 4%</option>
                            <option value="border-bottom-left-radius: 5%">border-bottom-left-radius: 5%</option>
                            <option value="border-bottom-left-radius: 6%">border-bottom-left-radius: 6%</option>
                            <option value="border-bottom-left-radius: 7%">border-bottom-left-radius: 7%</option>
                            <option value="border-bottom-left-radius: 8%">border-bottom-left-radius: 8%</option>
                            <option value="border-bottom-left-radius: 9%">border-bottom-left-radius: 9%</option>
                            <option value="border-bottom-left-radius: 10%">border-bottom-left-radius: 10%</option>
                            <option value="border-bottom-left-radius: 15%">border-bottom-left-radius: 15%</option>
                            <option value="border-bottom-left-radius: 20%">border-bottom-left-radius: 20%</option>
                            <option value="border-bottom-left-radius: 25%">border-bottom-left-radius: 25%</option>
                            <option value="border-bottom-left-radius: 30%">border-bottom-left-radius: 30%</option>
                            <option value="border-bottom-left-radius: 35%">border-bottom-left-radius: 35%</option>
                            <option value="border-bottom-left-radius: 40%">border-bottom-left-radius: 40%</option>
                            <option value="border-bottom-left-radius: 45%">border-bottom-left-radius: 45%</option>
                            <option value="border-bottom-left-radius: 50%">border-bottom-left-radius: 50%</option>
                            <option value="border-bottom-left-radius: 75%">border-bottom-left-radius: 75%</option>
                            <option value="border-bottom-left-radius: 100%">border-bottom-left-radius: 100%</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Border Bottom Right Radius:
                        <select name="border-bottom-right-radius">
                            <option value=""></option>
                            <option value="border-bottom-right-radius: 0px">border-bottom-right-radius: 0px</option>
                            <option value="border-bottom-right-radius: 1px">border-bottom-right-radius: 1px</option>
                            <option value="border-bottom-right-radius: 2px">border-bottom-right-radius: 2px</option>
                            <option value="border-bottom-right-radius: 3px">border-bottom-right-radius: 3px</option>
                            <option value="border-bottom-right-radius: 4px">border-bottom-right-radius: 4px</option>
                            <option value="border-bottom-right-radius: 5px">border-bottom-right-radius: 5px</option>
                            <option value="border-bottom-right-radius: 6px">border-bottom-right-radius: 6px</option>
                            <option value="border-bottom-right-radius: 7px">border-bottom-right-radius: 7px</option>
                            <option value="border-bottom-right-radius: 8px">border-bottom-right-radius: 8px</option>
                            <option value="border-bottom-right-radius: 9px">border-bottom-right-radius: 9px</option>
                            <option value="border-bottom-right-radius: 10px">border-bottom-right-radius: 10px</option>
                            <option value="border-bottom-right-radius: 15px">border-bottom-right-radius: 15px</option>
                            <option value="border-bottom-right-radius: 20px">border-bottom-right-radius: 20px</option>
                            <option value="border-bottom-right-radius: 25px">border-bottom-right-radius: 25px</option>
                            <option value="border-bottom-right-radius: 30px">border-bottom-right-radius: 30px</option>
                            <option value="border-bottom-right-radius: 35px">border-bottom-right-radius: 35px</option>
                            <option value="border-bottom-right-radius: 40px">border-bottom-right-radius: 40px</option>
                            <option value="border-bottom-right-radius: 45px">border-bottom-right-radius: 45px</option>
                            <option value="border-bottom-right-radius: 50px">border-bottom-right-radius: 50px</option>
                            <option value="border-bottom-right-radius: 75px">border-bottom-right-radius: 75px</option>
                            <option value="border-bottom-right-radius: 100px">border-bottom-right-radius: 100px</option>
                            <option value="border-bottom-right-radius: 1%">border-bottom-right-radius: 1%</option>
                            <option value="border-bottom-right-radius: 2%">border-bottom-right-radius: 2%</option>
                            <option value="border-bottom-right-radius: 3%">border-bottom-right-radius: 3%</option>
                            <option value="border-bottom-right-radius: 4%">border-bottom-right-radius: 4%</option>
                            <option value="border-bottom-right-radius: 5%">border-bottom-right-radius: 5%</option>
                            <option value="border-bottom-right-radius: 6%">border-bottom-right-radius: 6%</option>
                            <option value="border-bottom-right-radius: 7%">border-bottom-right-radius: 7%</option>
                            <option value="border-bottom-right-radius: 8%">border-bottom-right-radius: 8%</option>
                            <option value="border-bottom-right-radius: 9%">border-bottom-right-radius: 9%</option>
                            <option value="border-bottom-right-radius: 10%">border-bottom-right-radius: 10%</option>
                            <option value="border-bottom-right-radius: 15%">border-bottom-right-radius: 15%</option>
                            <option value="border-bottom-right-radius: 20%">border-bottom-right-radius: 20%</option>
                            <option value="border-bottom-right-radius: 25%">border-bottom-right-radius: 25%</option>
                            <option value="border-bottom-right-radius: 30%">border-bottom-right-radius: 30%</option>
                            <option value="border-bottom-right-radius: 35%">border-bottom-right-radius: 35%</option>
                            <option value="border-bottom-right-radius: 40%">border-bottom-right-radius: 40%</option>
                            <option value="border-bottom-right-radius: 45%">border-bottom-right-radius: 45%</option>
                            <option value="border-bottom-right-radius: 50%">border-bottom-right-radius: 50%</option>
                            <option value="border-bottom-right-radius: 75%">border-bottom-right-radius: 75%</option>
                            <option value="border-bottom-right-radius: 100%">border-bottom-right-radius: 100%</option>
                        </select>
                        </label>
                        </div>
                    </div>
                    <div>
                    <label>Box Sizing:
                    <select name="box-sizing">
                        <option value=""></option>
                        <option value="box-sizing: border-box">box-sizing: border-box</option>
                        <option value="box-sizing: content-box">box-sizing: content-box</option>
                    </select>
                    </label>
                    </div>
                    <div>
                    <label>Clear:
                    <select name="clear">
                        <option value=""></option>
                        <option value="clear: both">clear: both</option>
                        <option value="clear: left">clear: left</option>
                        <option value="clear: none">clear: none</option>
                        <option value="clear: right">clear: right</option>
                    </select>
                    </label>
                    </div>
                    <div>
                    <label>Cursor:
                    <select name="cursor">
                        <option value=""></option>
                        <option value="cursor: alias">cursor: alias</option>
                        <option value="cursor: auto">cursor: auto</option>
                        <option value="cursor: cell">cursor: cell</option>
                        <option value="cursor: col-resize">cursor: col-resize</option>
                        <option value="cursor: copy">cursor: copy</option>
                        <option value="cursor: crosshair">cursor: crosshair</option>
                        <option value="cursor: default">cursor: default</option>
                        <option value="cursor: e-resize">cursor: e-resize</option>
                        <option value="cursor: ew-resize">cursor: ew-resize</option>
                        <option value="cursor: grab">cursor: grab</option>
                        <option value="cursor: grabbing">cursor: grabbing</option>
                        <option value="cursor: help">cursor: help</option>
                        <option value="cursor: move">cursor: move</option>
                        <option value="cursor: n-resize">cursor: n-resize</option>
                        <option value="cursor: ne-resize">cursor: ne-resize</option>
                        <option value="cursor: nesw-resize">cursor: nesw-resize</option>
                        <option value="cursor: no-drop">cursor: no-drop</option>
                        <option value="cursor: none">cursor: none</option>
                        <option value="cursor: not-allowed">cursor: not-allowed</option>
                        <option value="cursor: ns-resize">cursor: ns-resize</option>
                        <option value="cursor: nw-resize">cursor: nw-resize</option>
                        <option value="cursor: nwse-resize">cursor: nwse-resize</option>
                        <option value="cursor: pointer">cursor: pointer</option>
                        <option value="cursor: progress">cursor: progress</option>
                        <option value="cursor: row-resize">cursor: row-resize</option>
                        <option value="cursor: s-resize">cursor: s-resize</option>
                        <option value="cursor: se-resize">cursor: se-resize</option>
                        <option value="cursor: sw-resize">cursor: sw-resize</option>
                        <option value="cursor: text">cursor: text</option>
                        <option value="cursor: vertical-text">cursor: vertical-text</option>
                        <option value="cursor: w-resize">cursor: w-resize</option>
                        <option value="cursor: wait">cursor: wait</option>
                        <option value="cursor: zoom-in">cursor: zoom-in</option>
                        <option value="cursor: zoom-out">cursor: zoom-out</option>
                    </select>
                    </label>
                    </div>
                    <div>
                    <label>Float:
                    <select name="float">
                        <option value=""></option>
                        <option value="float: left">float: Left</option>
                        <option value="float: none">float: None</option>
                        <option value="float: right">float: Right</option>
                    </select>
                    </label>
                    </div>
                    <div class="style-group">
                        <div class="heading">Font Options</div>
                        <div>
                        <label>Font Family:
                        <select name="font-family">
                            <option value=""></option>
                            <option value="font-family: cursive">font-family: cursive</option>
                            <option value="font-family: fantasy">font-family: fantasy</option>
                            <option value="font-family: math">font-family: math</option>
                            <option value="font-family: monospace">font-family: monospace</option>
                            <option value="font-family: none">font-family: none</option>
                            <option value="font-family: sans-serif">font-family: sans-serif</option>
                            <option value="font-family: serif">font-family: serif</option>
                            <option value="font-family: system-ui">font-family: system-ui</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Font Size:
                        <select name="font-size">
                            <option value=""></option>
                            <option value="font-size: 0px">font-size: 0px</option>
                            <option value="font-size: 1px">font-size: 1px</option>
                            <option value="font-size: 2px">font-size: 2px</option>
                            <option value="font-size: 3px">font-size: 3px</option>
                            <option value="font-size: 4px">font-size: 4px</option>
                            <option value="font-size: 5px">font-size: 5px</option>
                            <option value="font-size: 6px">font-size: 6px</option>
                            <option value="font-size: 7px">font-size: 7px</option>
                            <option value="font-size: 8px">font-size: 8px</option>
                            <option value="font-size: 9px">font-size: 9px</option>
                            <option value="font-size: 10px">font-size: 10px</option>
                            <option value="font-size: 11px">font-size: 11px</option>
                            <option value="font-size: 12px">font-size: 12px</option>
                            <option value="font-size: 13px">font-size: 13px</option>
                            <option value="font-size: 14px">font-size: 14px</option>
                            <option value="font-size: 15px">font-size: 15px</option>
                            <option value="font-size: 16px">font-size: 16px</option>
                            <option value="font-size: 17px">font-size: 17px</option>
                            <option value="font-size: 18px">font-size: 18px</option>
                            <option value="font-size: 19px">font-size: 19px</option>
                            <option value="font-size: 20px">font-size: 20px</option>
                            <option value="font-size: 21px">font-size: 21px</option>
                            <option value="font-size: 22px">font-size: 22px</option>
                            <option value="font-size: 23px">font-size: 23px</option>
                            <option value="font-size: 24px">font-size: 24px</option>
                            <option value="font-size: 25px">font-size: 25px</option>
                            <option value="font-size: 26px">font-size: 26px</option>
                            <option value="font-size: 27px">font-size: 27px</option>
                            <option value="font-size: 28px">font-size: 28px</option>
                            <option value="font-size: 29px">font-size: 29px</option>
                            <option value="font-size: 30px">font-size: 30px</option>
                            <option value="font-size: 31px">font-size: 31px</option>
                            <option value="font-size: 32px">font-size: 32px</option>
                            <option value="font-size: 33px">font-size: 33px</option>
                            <option value="font-size: 34px">font-size: 34px</option>
                            <option value="font-size: 35px">font-size: 35px</option>
                            <option value="font-size: 36px">font-size: 36px</option>
                            <option value="font-size: 37px">font-size: 37px</option>
                            <option value="font-size: 38px">font-size: 38px</option>
                            <option value="font-size: 39px">font-size: 39px</option>
                            <option value="font-size: 40px">font-size: 40px</option>
                            <option value="font-size: 41px">font-size: 41px</option>
                            <option value="font-size: 42px">font-size: 42px</option>
                            <option value="font-size: 43px">font-size: 43px</option>
                            <option value="font-size: 44px">font-size: 44px</option>
                            <option value="font-size: 45px">font-size: 45px</option>
                            <option value="font-size: 46px">font-size: 46px</option>
                            <option value="font-size: 47px">font-size: 47px</option>
                            <option value="font-size: 48px">font-size: 48px</option>
                            <option value="font-size: 49px">font-size: 49px</option>
                            <option value="font-size: 50px">font-size: 50px</option>
                            <option value="font-size: 51px">font-size: 51px</option>
                            <option value="font-size: 52px">font-size: 52px</option>
                            <option value="font-size: 53px">font-size: 53px</option>
                            <option value="font-size: 54px">font-size: 54px</option>
                            <option value="font-size: 55px">font-size: 55px</option>
                            <option value="font-size: 56px">font-size: 56px</option>
                            <option value="font-size: 57px">font-size: 57px</option>
                            <option value="font-size: 58px">font-size: 58px</option>
                            <option value="font-size: 59px">font-size: 59px</option>
                            <option value="font-size: 60px">font-size: 60px</option>
                            <option value="font-size: 61px">font-size: 61px</option>
                            <option value="font-size: 62px">font-size: 62px</option>
                            <option value="font-size: 63px">font-size: 63px</option>
                            <option value="font-size: 64px">font-size: 64px</option>
                            <option value="font-size: 65px">font-size: 65px</option>
                            <option value="font-size: 66px">font-size: 66px</option>
                            <option value="font-size: 67px">font-size: 67px</option>
                            <option value="font-size: 68px">font-size: 68px</option>
                            <option value="font-size: 69px">font-size: 69px</option>
                            <option value="font-size: 70px">font-size: 70px</option>
                            <option value="font-size: 71px">font-size: 71px</option>
                            <option value="font-size: 72px">font-size: 72px</option>
                            <option value="font-size: 73px">font-size: 73px</option>
                            <option value="font-size: 74px">font-size: 74px</option>
                            <option value="font-size: 75px">font-size: 75px</option>
                            <option value="font-size: 76px">font-size: 76px</option>
                            <option value="font-size: 77px">font-size: 77px</option>
                            <option value="font-size: 78px">font-size: 78px</option>
                            <option value="font-size: 79px">font-size: 79px</option>
                            <option value="font-size: 80px">font-size: 80px</option>
                            <option value="font-size: 81px">font-size: 81px</option>
                            <option value="font-size: 82px">font-size: 82px</option>
                            <option value="font-size: 83px">font-size: 83px</option>
                            <option value="font-size: 84px">font-size: 84px</option>
                            <option value="font-size: 85px">font-size: 85px</option>
                            <option value="font-size: 86px">font-size: 86px</option>
                            <option value="font-size: 87px">font-size: 87px</option>
                            <option value="font-size: 88px">font-size: 88px</option>
                            <option value="font-size: 89px">font-size: 89px</option>
                            <option value="font-size: 90px">font-size: 90px</option>
                            <option value="font-size: 91px">font-size: 91px</option>
                            <option value="font-size: 92px">font-size: 92px</option>
                            <option value="font-size: 93px">font-size: 93px</option>
                            <option value="font-size: 94px">font-size: 94px</option>
                            <option value="font-size: 95px">font-size: 95px</option>
                            <option value="font-size: 96px">font-size: 96px</option>
                            <option value="font-size: 97px">font-size: 97px</option>
                            <option value="font-size: 98px">font-size: 98px</option>
                            <option value="font-size: 99px">font-size: 99px</option>
                            <option value="font-size: 100px">font-size: 100px</option>
                            <option value="font-size: 101px">font-size: 101px</option>
                            <option value="font-size: 102px">font-size: 102px</option>
                            <option value="font-size: 103px">font-size: 103px</option>
                            <option value="font-size: 104px">font-size: 104px</option>
                            <option value="font-size: 105px">font-size: 105px</option>
                            <option value="font-size: 106px">font-size: 106px</option>
                            <option value="font-size: 107px">font-size: 107px</option>
                            <option value="font-size: 108px">font-size: 108px</option>
                            <option value="font-size: 109px">font-size: 109px</option>
                            <option value="font-size: 110px">font-size: 110px</option>
                            <option value="font-size: 111px">font-size: 111px</option>
                            <option value="font-size: 112px">font-size: 112px</option>
                            <option value="font-size: 113px">font-size: 113px</option>
                            <option value="font-size: 114px">font-size: 114px</option>
                            <option value="font-size: 115px">font-size: 115px</option>
                            <option value="font-size: 116px">font-size: 116px</option>
                            <option value="font-size: 117px">font-size: 117px</option>
                            <option value="font-size: 118px">font-size: 118px</option>
                            <option value="font-size: 119px">font-size: 119px</option>
                            <option value="font-size: 120px">font-size: 120px</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Font Weight:
                        <select name="font-weight">
                            <option value=""></option>
                            <option value="font-weight: 100">font-weight: 100</option>
                            <option value="font-weight: 200">font-weight: 200</option>
                            <option value="font-weight: 300">font-weight: 300</option>
                            <option value="font-weight: 400">font-weight: 400</option>
                            <option value="font-weight: 500">font-weight: 500</option>
                            <option value="font-weight: 600">font-weight: 600</option>
                            <option value="font-weight: 700">font-weight: 700</option>
                            <option value="font-weight: 800">font-weight: 800</option>
                            <option value="font-weight: 900">font-weight: 900</option>
                            <option value="font-weight: bold">font-weight: bold</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Font Color:
                        <input type="color" class="color" id="color" name="color" value="#000000" />
                        </label>
                        </div>
                    </div>
                    <div>
                    <label>Height:
                    <select name="height">
                        <option value=""></option>
                        <option value="height: auto">height: auto</option>
                        <option value="height: 0px">height: 0px</option>
                        <option value="height: 1px">height: 1px</option>
                        <option value="height: 2px">height: 2px</option>
                        <option value="height: 3px">height: 3px</option>
                        <option value="height: 4px">height: 4px</option>
                        <option value="height: 5px">height: 5px</option>
                        <option value="height: 6px">height: 6px</option>
                        <option value="height: 7px">height: 7px</option>
                        <option value="height: 8px">height: 8px</option>
                        <option value="height: 9px">height: 9px</option>
                        <option value="height: 10px">height: 10px</option>
                        <option value="height: 15px">height: 15px</option>
                        <option value="height: 20px">height: 20px</option>
                        <option value="height: 25px">height: 25px</option>
                        <option value="height: 30px">height: 30px</option>
                        <option value="height: 35px">height: 35px</option>
                        <option value="height: 40px">height: 40px</option>
                        <option value="height: 45px">height: 45px</option>
                        <option value="height: 50px">height: 50px</option>
                        <option value="height: 75px">height: 75px</option>
                        <option value="height: 125px">height: 125px</option>
                        <option value="height: 150px">height: 150px</option>
                        <option value="height: 175px">height: 175px</option>
                        <option value="height: 200px">height: 200px</option>
                        <option value="height: 250px">height: 250px</option>
                        <option value="height: 300px">height: 300px</option>
                        <option value="height: 350px">height: 350px</option>
                        <option value="height: 400px">height: 400px</option>
                        <option value="height: 500px">height: 500px</option>
                        <option value="height: 600px">height: 600px</option>
                        <option value="height: 700px">height: 700px</option>
                        <option value="height: 800px">height: 800px</option>
                        <option value="height: 900px">height: 900px</option>
                        <option value="height: 1000px">height: 1000px</option>
                        <option value="height: 1100px">height: 1100px</option>
                        <option value="height: 1200px">height: 1200px</option>
                        <option value="height: 1300px">height: 1300px</option>
                        <option value="height: 1400px">height: 1400px</option>
                        <option value="height: 1500px">height: 1500px</option>
                        <option value="height: 1%">height: 1%</option>
                        <option value="height: 2%">height: 2%</option>
                        <option value="height: 3%">height: 3%</option>
                        <option value="height: 4%">height: 4%</option>
                        <option value="height: 5%">height: 5%</option>
                        <option value="height: 6%">height: 6%</option>
                        <option value="height: 7%">height: 7%</option>
                        <option value="height: 8%">height: 8%</option>
                        <option value="height: 9%">height: 9%</option>
                        <option value="height: 10%">height: 10%</option>
                        <option value="height: 15%">height: 15%</option>
                        <option value="height: 20%">height: 20%</option>
                        <option value="height: 25%">height: 25%</option>
                        <option value="height: 30%">height: 30%</option>
                        <option value="height: 35%">height: 35%</option>
                        <option value="height: 40%">height: 40%</option>
                        <option value="height: 45%">height: 45%</option>
                        <option value="height: 50%">height: 50%</option>
                        <option value="height: 75%">height: 75%</option>
                        <option value="height: 100%">height: 100%</option>
                    </select>
                    </label>
                    </div>
                    <div>
                    <label>Max Height:
                    <select name="max-height">
                        <option value=""></option>
                        <option value="max-height: 0px">max-height: 0px</option>
                        <option value="max-height: 1px">max-height: 1px</option>
                        <option value="max-height: 2px">max-height: 2px</option>
                        <option value="max-height: 3px">max-height: 3px</option>
                        <option value="max-height: 4px">max-height: 4px</option>
                        <option value="max-height: 5px">max-height: 5px</option>
                        <option value="max-height: 6px">max-height: 6px</option>
                        <option value="max-height: 7px">max-height: 7px</option>
                        <option value="max-height: 8px">max-height: 8px</option>
                        <option value="max-height: 9px">max-height: 9px</option>
                        <option value="max-height: 10px">max-height: 10px</option>
                        <option value="max-height: 15px">max-height: 15px</option>
                        <option value="max-height: 20px">max-height: 20px</option>
                        <option value="max-height: 25px">max-height: 25px</option>
                        <option value="max-height: 30px">max-height: 30px</option>
                        <option value="max-height: 35px">max-height: 35px</option>
                        <option value="max-height: 40px">max-height: 40px</option>
                        <option value="max-height: 45px">max-height: 45px</option>
                        <option value="max-height: 50px">max-height: 50px</option>
                        <option value="max-height: 75px">max-height: 75px</option>
                        <option value="max-height: 125px">max-height: 125px</option>
                        <option value="max-height: 150px">max-height: 150px</option>
                        <option value="max-height: 175px">max-height: 175px</option>
                        <option value="max-height: 200px">max-height: 200px</option>
                        <option value="max-height: 250px">max-height: 250px</option>
                        <option value="max-height: 300px">max-height: 300px</option>
                        <option value="max-height: 350px">max-height: 350px</option>
                        <option value="max-height: 400px">max-height: 400px</option>
                        <option value="max-height: 500px">max-height: 500px</option>
                        <option value="max-height: 600px">max-height: 600px</option>
                        <option value="max-height: 700px">max-height: 700px</option>
                        <option value="max-height: 800px">max-height: 800px</option>
                        <option value="max-height: 900px">max-height: 900px</option>
                        <option value="max-height: 1000px">max-height: 1000px</option>
                        <option value="max-height: 1100px">max-height: 1100px</option>
                        <option value="max-height: 1200px">max-height: 1200px</option>
                        <option value="max-height: 1300px">max-height: 1300px</option>
                        <option value="max-height: 1400px">max-height: 1400px</option>
                        <option value="max-height: 1500px">max-height: 1500px</option>
                        <option value="max-height: 1%">max-height: 1%</option>
                        <option value="max-height: 2%">max-height: 2%</option>
                        <option value="max-height: 3%">max-height: 3%</option>
                        <option value="max-height: 4%">max-height: 4%</option>
                        <option value="max-height: 5%">max-height: 5%</option>
                        <option value="max-height: 6%">max-height: 6%</option>
                        <option value="max-height: 7%">max-height: 7%</option>
                        <option value="max-height: 8%">max-height: 8%</option>
                        <option value="max-height: 9%">max-height: 9%</option>
                        <option value="max-height: 10%">max-height: 10%</option>
                        <option value="max-height: 15%">max-height: 15%</option>
                        <option value="max-height: 20%">max-height: 20%</option>
                        <option value="max-height: 25%">max-height: 25%</option>
                        <option value="max-height: 30%">max-height: 30%</option>
                        <option value="max-height: 35%">max-height: 35%</option>
                        <option value="max-height: 40%">max-height: 40%</option>
                        <option value="max-height: 45%">max-height: 45%</option>
                        <option value="max-height: 50%">max-height: 50%</option>
                        <option value="max-height: 75%">max-height: 75%</option>
                        <option value="max-height: 100%">max-height: 100%</option>
                    </select>
                    </label>
                    </div>
                    <div>
                    <label>Line Height:
                    <select name="line-height">
                        <option value=""></option>
                        <option value="line-height: auto">line-height: auto</option>
                        <option value="line-height: 0px">line-height: 0px</option>
                        <option value="line-height: 1px">line-height: 1px</option>
                        <option value="line-height: 2px">line-height: 2px</option>
                        <option value="line-height: 3px">line-height: 3px</option>
                        <option value="line-height: 4px">line-height: 4px</option>
                        <option value="line-height: 5px">line-height: 5px</option>
                        <option value="line-height: 6px">line-height: 6px</option>
                        <option value="line-height: 7px">line-height: 7px</option>
                        <option value="line-height: 8px">line-height: 8px</option>
                        <option value="line-height: 9px">line-height: 9px</option>
                        <option value="line-height: 10px">line-height: 10px</option>
                        <option value="line-height: 15px">line-height: 15px</option>
                        <option value="line-height: 20px">line-height: 20px</option>
                        <option value="line-height: 25px">line-height: 25px</option>
                        <option value="line-height: 30px">line-height: 30px</option>
                        <option value="line-height: 35px">line-height: 35px</option>
                        <option value="line-height: 40px">line-height: 40px</option>
                        <option value="line-height: 45px">line-height: 45px</option>
                        <option value="line-height: 50px">line-height: 50px</option>
                    </select>
                    </label>
                    </div>
                    <div>
                    <label>List Style Type:
                    <select name="list-style-type">
                        <option value=""></option>
                        <option value="list-style-type: auto">list-style-type: auto</option>
                        <option value="list-style-type: circle">list-style-type: circle</option>
                        <option value="list-style-type: decimal">list-style-type: decimal</option>
                        <option value="list-style-type: disc">list-style-type: disc</option>
                        <option value="list-style-type: disclosure-closed">list-style-type: disclosure-closed</option>
                        <option value="list-style-type: disclosure-open">list-style-type: disclosure-open</option>
                        <option value="list-style-type: none">list-style-type: none</option>
                        <option value="list-style-type: square">list-style-type: square</option>
                    </select>
                    </label>
                    </div>
                    <div class="style-group">
                        <div class="heading">Margin Options</div>
                        <div>
                        <label>Margin (All Sides):
                        <select name="margin">
                            <option value=""></option>
                            <option value="margin: 0px">margin: 0px</option>
                            <option value="margin: 1px">margin: 1px</option>
                            <option value="margin: 2px">margin: 2px</option>
                            <option value="margin: 3px">margin: 3px</option>
                            <option value="margin: 4px">margin: 4px</option>
                            <option value="margin: 5px">margin: 5px</option>
                            <option value="margin: 6px">margin: 6px</option>
                            <option value="margin: 7px">margin: 7px</option>
                            <option value="margin: 8px">margin: 8px</option>
                            <option value="margin: 9px">margin: 9px</option>
                            <option value="margin: 10px">margin: 10px</option>
                            <option value="margin: 15px">margin: 15px</option>
                            <option value="margin: 20px">margin: 20px</option>
                            <option value="margin: 25px">margin: 25px</option>
                            <option value="margin: 30px">margin: 30px</option>
                            <option value="margin: 35px">margin: 35px</option>
                            <option value="margin: 40px">margin: 40px</option>
                            <option value="margin: 45px">margin: 45px</option>
                            <option value="margin: 50px">margin: 50px</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Margin Top:
                        <select name="margin-top">
                            <option value=""></option>
                            <option value="margin-top: 0px">margin-top: 0px</option>
                            <option value="margin-top: 1px">margin-top: 1px</option>
                            <option value="margin-top: 2px">margin-top: 2px</option>
                            <option value="margin-top: 3px">margin-top: 3px</option>
                            <option value="margin-top: 4px">margin-top: 4px</option>
                            <option value="margin-top: 5px">margin-top: 5px</option>
                            <option value="margin-top: 6px">margin-top: 6px</option>
                            <option value="margin-top: 7px">margin-top: 7px</option>
                            <option value="margin-top: 8px">margin-top: 8px</option>
                            <option value="margin-top: 9px">margin-top: 9px</option>
                            <option value="margin-top: 10px">margin-top: 10px</option>
                            <option value="margin-top: 15px">margin-top: 15px</option>
                            <option value="margin-top: 20px">margin-top: 20px</option>
                            <option value="margin-top: 25px">margin-top: 25px</option>
                            <option value="margin-top: 30px">margin-top: 30px</option>
                            <option value="margin-top: 35px">margin-top: 35px</option>
                            <option value="margin-top: 40px">margin-top: 40px</option>
                            <option value="margin-top: 45px">margin-top: 45px</option>
                            <option value="margin-top: 50px">margin-top: 50px</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Margin Right:
                        <select name="margin-right">
                            <option value=""></option>
                            <option value="margin-right: 0px">margin-right: 0px</option>
                            <option value="margin-right: 1px">margin-right: 1px</option>
                            <option value="margin-right: 2px">margin-right: 2px</option>
                            <option value="margin-right: 3px">margin-right: 3px</option>
                            <option value="margin-right: 4px">margin-right: 4px</option>
                            <option value="margin-right: 5px">margin-right: 5px</option>
                            <option value="margin-right: 6px">margin-right: 6px</option>
                            <option value="margin-right: 7px">margin-right: 7px</option>
                            <option value="margin-right: 8px">margin-right: 8px</option>
                            <option value="margin-right: 9px">margin-right: 9px</option>
                            <option value="margin-right: 10px">margin-right: 10px</option>
                            <option value="margin-right: 15px">margin-right: 15px</option>
                            <option value="margin-right: 20px">margin-right: 20px</option>
                            <option value="margin-right: 25px">margin-right: 25px</option>
                            <option value="margin-right: 30px">margin-right: 30px</option>
                            <option value="margin-right: 35px">margin-right: 35px</option>
                            <option value="margin-right: 40px">margin-right: 40px</option>
                            <option value="margin-right: 45px">margin-right: 45px</option>
                            <option value="margin-right: 50px">margin-right: 50px</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Margin Bottom:
                        <select name="margin-bottom">
                            <option value=""></option>
                            <option value="margin-bottom: 0px">margin-bottom: 0px</option>
                            <option value="margin-bottom: 1px">margin-bottom: 1px</option>
                            <option value="margin-bottom: 2px">margin-bottom: 2px</option>
                            <option value="margin-bottom: 3px">margin-bottom: 3px</option>
                            <option value="margin-bottom: 4px">margin-bottom: 4px</option>
                            <option value="margin-bottom: 5px">margin-bottom: 5px</option>
                            <option value="margin-bottom: 6px">margin-bottom: 6px</option>
                            <option value="margin-bottom: 7px">margin-bottom: 7px</option>
                            <option value="margin-bottom: 8px">margin-bottom: 8px</option>
                            <option value="margin-bottom: 9px">margin-bottom: 9px</option>
                            <option value="margin-bottom: 10px">margin-bottom: 10px</option>
                            <option value="margin-bottom: 15px">margin-bottom: 15px</option>
                            <option value="margin-bottom: 20px">margin-bottom: 20px</option>
                            <option value="margin-bottom: 25px">margin-bottom: 25px</option>
                            <option value="margin-bottom: 30px">margin-bottom: 30px</option>
                            <option value="margin-bottom: 35px">margin-bottom: 35px</option>
                            <option value="margin-bottom: 40px">margin-bottom: 40px</option>
                            <option value="margin-bottom: 45px">margin-bottom: 45px</option>
                            <option value="margin-bottom: 50px">margin-bottom: 50px</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Margin Left:
                        <select name="margin-left">
                            <option value=""></option>
                            <option value="margin-left: 0px">margin-left: 0px</option>
                            <option value="margin-left: 1px">margin-left: 1px</option>
                            <option value="margin-left: 2px">margin-left: 2px</option>
                            <option value="margin-left: 3px">margin-left: 3px</option>
                            <option value="margin-left: 4px">margin-left: 4px</option>
                            <option value="margin-left: 5px">margin-left: 5px</option>
                            <option value="margin-left: 6px">margin-left: 6px</option>
                            <option value="margin-left: 7px">margin-left: 7px</option>
                            <option value="margin-left: 8px">margin-left: 8px</option>
                            <option value="margin-left: 9px">margin-left: 9px</option>
                            <option value="margin-left: 10px">margin-left: 10px</option>
                            <option value="margin-left: 15px">margin-left: 15px</option>
                            <option value="margin-left: 20px">margin-left: 20px</option>
                            <option value="margin-left: 25px">margin-left: 25px</option>
                            <option value="margin-left: 30px">margin-left: 30px</option>
                            <option value="margin-left: 35px">margin-left: 35px</option>
                            <option value="margin-left: 40px">margin-left: 40px</option>
                            <option value="margin-left: 45px">margin-left: 45px</option>
                            <option value="margin-left: 50px">margin-left: 50px</option>
                        </select>
                        </label>
                        </div>
                    </div>
                    <div>
                    <label>Object Fit:
                    <select name="object-fit">
                        <option value=""></option>
                        <option value="object-fit: contain">object-fit: contain</option>
                        <option value="object-fit: cover">object-fit: cover</option>
                        <option value="object-fit: fill">object-fit: fill</option>
                        <option value="object-fit: none">object-fit: none</option>
                        <option value="object-fit: scale-down">object-fit: scale-down</option>
                    </select>
                    </label>
                    </div>
                    <div>
                    <label>Opacity:
                    <select name="opacity">
                        <option value=""></option>
                        <option value="opacity: 1">opacity: 1</option>
                        <option value="opacity: 09">opacity: 0.9</option>
                        <option value="opacity: 08">opacity: 0.8</option>
                        <option value="opacity: 07">opacity: 0.7</option>
                        <option value="opacity: 06">opacity: 0.6</option>
                        <option value="opacity: 05">opacity: 0.5</option>
                        <option value="opacity: 04">opacity: 0.4</option>
                        <option value="opacity: 03">opacity: 0.3</option>
                        <option value="opacity: 02">opacity: 0.2</option>
                        <option value="opacity: 01">opacity: 0.1</option>
                        <option value="opacity: 0">opacity: 0</option>
                    </select>
                    </label>
                    </div>
                    <div class="style-group">
                        <div class="heading">Overflow Options</div>
                        <div>
                        <label>Overflow (Horizontal & Vertical):
                        <select name="overflow">
                            <option value=""></option>
                            <option value="overflow: auto">overflow: auto</option>
                            <option value="overflow: hidden">overflow: hidden</option>
                            <option value="overflow: scroll">overflow: scroll</option>
                            <option value="overflow: visible">overflow: visible</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Overflow X (Horizontal):
                        <select name="overflow-x">
                            <option value=""></option>
                            <option value="overflow-x: auto">overflow-x: auto</option>
                            <option value="overflow-x: hidden">overflow-x: hidden</option>
                            <option value="overflow-x: scroll">overflow-x: scroll</option>
                            <option value="overflow-x: visible">overflow-x: visible</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Overflow Y (Vertical):
                        <select name="overflow">
                            <option value=""></option>
                            <option value="overflow-y: auto">overflow-y: auto</option>
                            <option value="overflow-y: hidden">overflow-y: hidden</option>
                            <option value="overflow-y: scroll">overflow-y: scroll</option>
                            <option value="overflow-y: visible">overflow-y: visible</option>
                        </select>
                        </label>
                        </div>
                    </div>
                    <div class="style-group">
                        <div class="heading">Padding Options</div>
                        <div>
                        <label>Padding (All Sides):
                        <select name="padding">
                            <option value=""></option>
                            <option value="padding: 0px">padding: 0px</option>
                            <option value="padding: 1px">padding: 1px</option>
                            <option value="padding: 2px">padding: 2px</option>
                            <option value="padding: 3px">padding: 3px</option>
                            <option value="padding: 4px">padding: 4px</option>
                            <option value="padding: 5px">padding: 5px</option>
                            <option value="padding: 6px">padding: 6px</option>
                            <option value="padding: 7px">padding: 7px</option>
                            <option value="padding: 8px">padding: 8px</option>
                            <option value="padding: 9px">padding: 9px</option>
                            <option value="padding: 10px">padding: 10px</option>
                            <option value="padding: 15px">padding: 15px</option>
                            <option value="padding: 20px">padding: 20px</option>
                            <option value="padding: 25px">padding: 25px</option>
                            <option value="padding: 30px">padding: 30px</option>
                            <option value="padding: 35px">padding: 35px</option>
                            <option value="padding: 40px">padding: 40px</option>
                            <option value="padding: 45px">padding: 45px</option>
                            <option value="padding: 50px">padding: 50px</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Padding Top:
                        <select name="padding-top">
                            <option value=""></option>
                            <option value="padding-top: 0px">padding-top: 0px</option>
                            <option value="padding-top: 1px">padding-top: 1px</option>
                            <option value="padding-top: 2px">padding-top: 2px</option>
                            <option value="padding-top: 3px">padding-top: 3px</option>
                            <option value="padding-top: 4px">padding-top: 4px</option>
                            <option value="padding-top: 5px">padding-top: 5px</option>
                            <option value="padding-top: 6px">padding-top: 6px</option>
                            <option value="padding-top: 7px">padding-top: 7px</option>
                            <option value="padding-top: 8px">padding-top: 8px</option>
                            <option value="padding-top: 9px">padding-top: 9px</option>
                            <option value="padding-top: 10px">padding-top: 10px</option>
                            <option value="padding-top: 15px">padding-top: 15px</option>
                            <option value="padding-top: 20px">padding-top: 20px</option>
                            <option value="padding-top: 25px">padding-top: 25px</option>
                            <option value="padding-top: 30px">padding-top: 30px</option>
                            <option value="padding-top: 35px">padding-top: 35px</option>
                            <option value="padding-top: 40px">padding-top: 40px</option>
                            <option value="padding-top: 45px">padding-top: 45px</option>
                            <option value="padding-top: 50px">padding-top: 50px</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Padding Right:
                        <select name="padding-right">
                            <option value=""></option>
                            <option value="padding-right: 0px">padding-right: 0px</option>
                            <option value="padding-right: 1px">padding-right: 1px</option>
                            <option value="padding-right: 2px">padding-right: 2px</option>
                            <option value="padding-right: 3px">padding-right: 3px</option>
                            <option value="padding-right: 4px">padding-right: 4px</option>
                            <option value="padding-right: 5px">padding-right: 5px</option>
                            <option value="padding-right: 6px">padding-right: 6px</option>
                            <option value="padding-right: 7px">padding-right: 7px</option>
                            <option value="padding-right: 8px">padding-right: 8px</option>
                            <option value="padding-right: 9px">padding-right: 9px</option>
                            <option value="padding-right: 10px">padding-right: 10px</option>
                            <option value="padding-right: 15px">padding-right: 15px</option>
                            <option value="padding-right: 20px">padding-right: 20px</option>
                            <option value="padding-right: 25px">padding-right: 25px</option>
                            <option value="padding-right: 30px">padding-right: 30px</option>
                            <option value="padding-right: 35px">padding-right: 35px</option>
                            <option value="padding-right: 40px">padding-right: 40px</option>
                            <option value="padding-right: 45px">padding-right: 45px</option>
                            <option value="padding-right: 50px">padding-right: 50px</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Padding Bottom:
                        <select name="padding-bottom">
                            <option value=""></option>
                            <option value="padding-bottom: 0px">padding-bottom: 0px</option>
                            <option value="padding-bottom: 1px">padding-bottom: 1px</option>
                            <option value="padding-bottom: 2px">padding-bottom: 2px</option>
                            <option value="padding-bottom: 3px">padding-bottom: 3px</option>
                            <option value="padding-bottom: 4px">padding-bottom: 4px</option>
                            <option value="padding-bottom: 5px">padding-bottom: 5px</option>
                            <option value="padding-bottom: 6px">padding-bottom: 6px</option>
                            <option value="padding-bottom: 7px">padding-bottom: 7px</option>
                            <option value="padding-bottom: 8px">padding-bottom: 8px</option>
                            <option value="padding-bottom: 9px">padding-bottom: 9px</option>
                            <option value="padding-bottom: 10px">padding-bottom: 10px</option>
                            <option value="padding-bottom: 15px">padding-bottom: 15px</option>
                            <option value="padding-bottom: 20px">padding-bottom: 20px</option>
                            <option value="padding-bottom: 25px">padding-bottom: 25px</option>
                            <option value="padding-bottom: 30px">padding-bottom: 30px</option>
                            <option value="padding-bottom: 35px">padding-bottom: 35px</option>
                            <option value="padding-bottom: 40px">padding-bottom: 40px</option>
                            <option value="padding-bottom: 45px">padding-bottom: 45px</option>
                            <option value="padding-bottom: 50px">padding-bottom: 50px</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Padding Left:
                        <select name="padding-left">
                            <option value=""></option>
                            <option value="padding-left: 0px">padding-left: 0px</option>
                            <option value="padding-left: 1px">padding-left: 1px</option>
                            <option value="padding-left: 2px">padding-left: 2px</option>
                            <option value="padding-left: 3px">padding-left: 3px</option>
                            <option value="padding-left: 4px">padding-left: 4px</option>
                            <option value="padding-left: 5px">padding-left: 5px</option>
                            <option value="padding-left: 6px">padding-left: 6px</option>
                            <option value="padding-left: 7px">padding-left: 7px</option>
                            <option value="padding-left: 8px">padding-left: 8px</option>
                            <option value="padding-left: 9px">padding-left: 9px</option>
                            <option value="padding-left: 10px">padding-left: 10px</option>
                            <option value="padding-left: 15px">padding-left: 15px</option>
                            <option value="padding-left: 20px">padding-left: 20px</option>
                            <option value="padding-left: 25px">padding-left: 25px</option>
                            <option value="padding-left: 30px">padding-left: 30px</option>
                            <option value="padding-left: 35px">padding-left: 35px</option>
                            <option value="padding-left: 40px">padding-left: 40px</option>
                            <option value="padding-left: 45px">padding-left: 45px</option>
                            <option value="padding-left: 50px">padding-left: 50px</option>
                        </select>
                        </label>
                        </div>
                    </div>
                    <div class="style-group">
                        <div class="heading">Position Options</div>
                        <div>
                        <label>Position:
                        <select name="position">
                            <option value=""></option>
                            <option value="position: absolute">position: absolute</option>
                            <option value="position: fixed">position: fixed</option>
                            <option value="position: relative">position: relative</option>
                            <option value="position: static">position: static</option>
                            <option value="position: sticky">position: sticky</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Top:
                        <select name="top">
                            <option value=""></option>
                            <option value="top: 0px">top: 0px</option>
                            <option value="top: 1px">top: 1px</option>
                            <option value="top: 2px">top: 2px</option>
                            <option value="top: 3px">top: 3px</option>
                            <option value="top: 4px">top: 4px</option>
                            <option value="top: 5px">top: 5px</option>
                            <option value="top: 6px">top: 6px</option>
                            <option value="top: 7px">top: 7px</option>
                            <option value="top: 8px">top: 8px</option>
                            <option value="top: 9px">top: 9px</option>
                            <option value="top: 10px">top: 10px</option>
                            <option value="top: 15px">top: 15px</option>
                            <option value="top: 20px">top: 20px</option>
                            <option value="top: 25px">top: 25px</option>
                            <option value="top: 30px">top: 30px</option>
                            <option value="top: 35px">top: 35px</option>
                            <option value="top: 40px">top: 40px</option>
                            <option value="top: 45px">top: 45px</option>
                            <option value="top: 50px">top: 50px</option>
                            <option value="top: 75px">top: 75px</option>
                            <option value="top: 100px">top: 100px</option>
                            <option value="top: 1%">top: 1%</option>
                            <option value="top: 2%">top: 2%</option>
                            <option value="top: 3%">top: 3%</option>
                            <option value="top: 4%">top: 4%</option>
                            <option value="top: 5%">top: 5%</option>
                            <option value="top: 6%">top: 6%</option>
                            <option value="top: 7%">top: 7%</option>
                            <option value="top: 8%">top: 8%</option>
                            <option value="top: 9%">top: 9%</option>
                            <option value="top: 10%">top: 10%</option>
                            <option value="top: 15%">top: 15%</option>
                            <option value="top: 20%">top: 20%</option>
                            <option value="top: 25%">top: 25%</option>
                            <option value="top: 30%">top: 30%</option>
                            <option value="top: 35%">top: 35%</option>
                            <option value="top: 40%">top: 40%</option>
                            <option value="top: 45%">top: 45%</option>
                            <option value="top: 50%">top: 50%</option>
                            <option value="top: 75%">top: 75%</option>
                            <option value="top: 100%">top: 100%</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Right:
                        <select name="right">
                            <option value=""></option>
                            <option value="right: 0px">right: 0px</option>
                            <option value="right: 1px">right: 1px</option>
                            <option value="right: 2px">right: 2px</option>
                            <option value="right: 3px">right: 3px</option>
                            <option value="right: 4px">right: 4px</option>
                            <option value="right: 5px">right: 5px</option>
                            <option value="right: 6px">right: 6px</option>
                            <option value="right: 7px">right: 7px</option>
                            <option value="right: 8px">right: 8px</option>
                            <option value="right: 9px">right: 9px</option>
                            <option value="right: 10px">right: 10px</option>
                            <option value="right: 15px">right: 15px</option>
                            <option value="right: 20px">right: 20px</option>
                            <option value="right: 25px">right: 25px</option>
                            <option value="right: 30px">right: 30px</option>
                            <option value="right: 35px">right: 35px</option>
                            <option value="right: 40px">right: 40px</option>
                            <option value="right: 45px">right: 45px</option>
                            <option value="right: 50px">right: 50px</option>
                            <option value="right: 75px">right: 75px</option>
                            <option value="right: 100px">right: 100px</option>
                            <option value="right: 1%">right: 1%</option>
                            <option value="right: 2%">right: 2%</option>
                            <option value="right: 3%">right: 3%</option>
                            <option value="right: 4%">right: 4%</option>
                            <option value="right: 5%">right: 5%</option>
                            <option value="right: 6%">right: 6%</option>
                            <option value="right: 7%">right: 7%</option>
                            <option value="right: 8%">right: 8%</option>
                            <option value="right: 9%">right: 9%</option>
                            <option value="right: 10%">right: 10%</option>
                            <option value="right: 15%">right: 15%</option>
                            <option value="right: 20%">right: 20%</option>
                            <option value="right: 25%">right: 25%</option>
                            <option value="right: 30%">right: 30%</option>
                            <option value="right: 35%">right: 35%</option>
                            <option value="right: 40%">right: 40%</option>
                            <option value="right: 45%">right: 45%</option>
                            <option value="right: 50%">right: 50%</option>
                            <option value="right: 75%">right: 75%</option>
                            <option value="right: 100%">right: 100%</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Bottom:
                        <select name="bottom">
                            <option value=""></option>
                            <option value="bottom: 0px">bottom: 0px</option>
                            <option value="bottom: 1px">bottom: 1px</option>
                            <option value="bottom: 2px">bottom: 2px</option>
                            <option value="bottom: 3px">bottom: 3px</option>
                            <option value="bottom: 4px">bottom: 4px</option>
                            <option value="bottom: 5px">bottom: 5px</option>
                            <option value="bottom: 6px">bottom: 6px</option>
                            <option value="bottom: 7px">bottom: 7px</option>
                            <option value="bottom: 8px">bottom: 8px</option>
                            <option value="bottom: 9px">bottom: 9px</option>
                            <option value="bottom: 10px">bottom: 10px</option>
                            <option value="bottom: 15px">bottom: 15px</option>
                            <option value="bottom: 20px">bottom: 20px</option>
                            <option value="bottom: 25px">bottom: 25px</option>
                            <option value="bottom: 30px">bottom: 30px</option>
                            <option value="bottom: 35px">bottom: 35px</option>
                            <option value="bottom: 40px">bottom: 40px</option>
                            <option value="bottom: 45px">bottom: 45px</option>
                            <option value="bottom: 50px">bottom: 50px</option>
                            <option value="bottom: 75px">bottom: 75px</option>
                            <option value="bottom: 100px">bottom: 100px</option>
                            <option value="bottom: 1%">bottom: 1%</option>
                            <option value="bottom: 2%">bottom: 2%</option>
                            <option value="bottom: 3%">bottom: 3%</option>
                            <option value="bottom: 4%">bottom: 4%</option>
                            <option value="bottom: 5%">bottom: 5%</option>
                            <option value="bottom: 6%">bottom: 6%</option>
                            <option value="bottom: 7%">bottom: 7%</option>
                            <option value="bottom: 8%">bottom: 8%</option>
                            <option value="bottom: 9%">bottom: 9%</option>
                            <option value="bottom: 10%">bottom: 10%</option>
                            <option value="bottom: 15%">bottom: 15%</option>
                            <option value="bottom: 20%">bottom: 20%</option>
                            <option value="bottom: 25%">bottom: 25%</option>
                            <option value="bottom: 30%">bottom: 30%</option>
                            <option value="bottom: 35%">bottom: 35%</option>
                            <option value="bottom: 40%">bottom: 40%</option>
                            <option value="bottom: 45%">bottom: 45%</option>
                            <option value="bottom: 50%">bottom: 50%</option>
                            <option value="bottom: 75%">bottom: 75%</option>
                            <option value="bottom: 100%">bottom: 100%</option>
                        </select>
                        </label>
                        </div>
                        <div>
                        <label>Left:
                        <select name="left">
                            <option value=""></option>
                            <option value="left: 0px">left: 0px</option>
                            <option value="left: 1px">left: 1px</option>
                            <option value="left: 2px">left: 2px</option>
                            <option value="left: 3px">left: 3px</option>
                            <option value="left: 4px">left: 4px</option>
                            <option value="left: 5px">left: 5px</option>
                            <option value="left: 6px">left: 6px</option>
                            <option value="left: 7px">left: 7px</option>
                            <option value="left: 8px">left: 8px</option>
                            <option value="left: 9px">left: 9px</option>
                            <option value="left: 10px">left: 10px</option>
                            <option value="left: 15px">left: 15px</option>
                            <option value="left: 20px">left: 20px</option>
                            <option value="left: 25px">left: 25px</option>
                            <option value="left: 30px">left: 30px</option>
                            <option value="left: 35px">left: 35px</option>
                            <option value="left: 40px">left: 40px</option>
                            <option value="left: 45px">left: 45px</option>
                            <option value="left: 50px">left: 50px</option>
                            <option value="left: 75px">left: 75px</option>
                            <option value="left: 100px">left: 100px</option>
                            <option value="left: 1%">left: 1%</option>
                            <option value="left: 2%">left: 2%</option>
                            <option value="left: 3%">left: 3%</option>
                            <option value="left: 4%">left: 4%</option>
                            <option value="left: 5%">left: 5%</option>
                            <option value="left: 6%">left: 6%</option>
                            <option value="left: 7%">left: 7%</option>
                            <option value="left: 8%">left: 8%</option>
                            <option value="left: 9%">left: 9%</option>
                            <option value="left: 10%">left: 10%</option>
                            <option value="left: 15%">left: 15%</option>
                            <option value="left: 20%">left: 20%</option>
                            <option value="left: 25%">left: 25%</option>
                            <option value="left: 30%">left: 30%</option>
                            <option value="left: 35%">left: 35%</option>
                            <option value="left: 40%">left: 40%</option>
                            <option value="left: 45%">left: 45%</option>
                            <option value="left: 50%">left: 50%</option>
                            <option value="left: 75%">left: 75%</option>
                            <option value="left: 100%">left: 100%</option>
                        </select>
                        </label>
                        </div>
                    </div>
                    <div>
                    <label>Scroll Behavior:
                    <select name="scroll-behavior">
                        <option value=""></option>
                        <option value="scroll-behavior: auto">scroll-behavior: auto</option>
                        <option value="scroll-behavior: smooth">scroll-behavior: smooth</option>
                    </select>
                    </label>
                    </div>
                    <div class="style-group">
                        <div class="heading">Table Options (Insert)</div>
                        <div>
                            <label>Rows:
                            <input name="table-rows" class="table-rows" type="text" value="5">
                            </label>
                        </div>
                        <div>
                            <label>Columns:
                            <input name="table-columns" class="table-columns" type="text" value="5">
                            </label>
                        </div>
                        <div>
                            <label>Width: (%)
                            <input name="table-width" class="table-width" type="text" value="90">
                            </label>
                        </div>
                        <div>
                            <label>Border Thickness:
                            <input name="table-border-thickness" class="table-border-thickness" type="text" value="2">
                            </label>
                        </div>
                        <div>
                            <label>Cell Padding:
                            <input name="table-cell-padding" class="table-cell-padding" type="text" value="5">
                            </label>
                        </div>
                        <div>
                            <label>Cell Spacing:
                            <input name="table-cell-spacing" class="table-cell-spacing" type="text" value="5">
                            </label>
                        </div>
                        <div>
                            <button type="button" class="create-table">Add Table</button>
                        </div>
                    </div>
                    <div class="style-group">
                        <div class="heading">Table Options (Edit)</div>
                        <div>
                            <button type="button" class="table-merge-cells">Merge Cells</button>
                        </div>
                        <div>
                            <button type="button" class="table-split-cell">Split Cell</button>
                        </div>
                        <div>
                            <button type="button" class="table-add-cell">Insert Cells</button>
                        </div>
                        <div>
                            <button type="button" class="table-insert-row-before">Insert Row Before</button>
                        </div>
                        <div>
                            <button type="button" class="table-insert-row-after">Insert Row After</button>
                        </div>
                        <div>
                            <button type="button" class="table-insert-column-before">Insert Column Before</button>
                        </div>
                        <div>
                            <button type="button" class="table-insert-column-after">Insert Column After</button>
                        </div>
                    </div>
                    <div>
                    <label>Text Align:
                    <select name="text-align">
                        <option value=""></option>
                        <option value="text-align: left">text-align: left</option>
                        <option value="text-align: center">text-align: center</option>
                        <option value="text-align: right">text-align: right</option>
                    </select>
                    </label>
                    </div>
                    <div>
                    <label>Text Decoration:
                    <select name="text-decoration">
                        <option value=""></option>
                        <option value="text-decoration: line-through">text-decoration: line-through</option>
                        <option value="text-decoration: none">text-decoration: none</option>
                        <option value="text-decoration: overline">text-decoration: overline</option>
                        <option value="text-decoration: underline">text-decoration: underline</option>
                    </select>
                    </label>
                    </div>
                    <div>
                    <label>Transform:
                    <select name="transform">
                        <option value=""></option>
                        <optgroup label="TranslateX (Horizontal)">
                            <option value="transform: translateX(25%)">transform: translateX(25%)</option>
                            <option value="transform: translateX(50%)">transform: translateX(50%)</option>
                            <option value="transform: translateX(75%)">transform: translateX(75%)</option>
                            <option value="transform: translateX(100%)">transform: translateX(100%)</option>
                            <option value="transform: translateX(-25%)">transform: translateX(-25%)</option>
                            <option value="transform: translateX(-50%)">transform: translateX(-50%)</option>
                            <option value="transform: translateX(-75%)">transform: translateX(-75%)</option>
                            <option value="transform: translateX(-100%)">transform: translateX(-100%)</option>
                        </optgroup>
                        <optgroup label="TranslateY (Vertical)">
                            <option value="transform: translateY(25%)">transform: translateY(25%)</option>
                            <option value="transform: translateY(50%)">transform: translateY(50%)</option>
                            <option value="transform: translateY(75%)">transform: translateY(75%)</option>
                            <option value="transform: translateY(100%)">transform: translateY(100%)</option>
                            <option value="transform: translateY(-25%)">transform: translateY(-25%)</option>
                            <option value="transform: translateY(-50%)">transform: translateY(-50%)</option>
                            <option value="transform: translateY(-75%)">transform: translateY(-75%)</option>
                            <option value="transform: translateY(-100%)">transform: translateY(-100%)</option>
                        </optgroup>
                        <optgroup label="Rotate">
                            <option value="transform: rotate(90deg)">transform: rotate(90deg)</option>
                            <option value="transform: rotate(180deg)">transform: rotate(180deg)</option>
                            <option value="transform: rotate(270deg)">transform: rotate(270deg)</option>
                            <option value="transform: rotate(360deg)">transform: rotate(360deg)</option>
                            <option value="transform: rotate(-90deg)">transform: rotate(-90deg)</option>
                            <option value="transform: rotate(-180deg)">transform: rotate(-180deg)</option>
                            <option value="transform: rotate(-270deg)">transform: rotate(-270deg)</option>
                            <option value="transform: rotate(-360deg)">transform: rotate(-360deg)</option>
                        </optgroup>
                    </select>
                    </label>
                    </div>
                    <div>
                    <label>Transition:
                    <select name="transition">
                        <option value=""></option>
                        <option value="transition: 200ms">transition: 200ms</option>
                        <option value="transition: all 0.5s ease">transition: all 0.5s ease</option>
                        <option value="transition: border 0.2s ease">transition: border 0.2s ease</option>
                        <option value="transition: transform 0.5s ease">transition: transform 0.5s ease</option>
                    </select>
                    </label>
                    </div>
                    <div>
                    <label>Vertical Align (vertical-align):
                    <select name="vertical-align">
                        <option value=""></option>
                        <option value="vertical-align: top">vertical-align: top</option>
                        <option value="vertical-align: middle">vertical-align: middle</option>
                        <option value="vertical-align: bottom">vertical-align: bottom</option>
                    </select>
                    </label>
                    </div>
                    <div>
                    <label>White Space:
                    <select name="white-space">
                        <option value=""></option>
                        <option value="white-space: normal">white-space: normal</option>
                        <option value="white-space: nowrap">white-space: nowrap</option>
                    </select>
                    </label>
                    </div>
                    <div>
                    <label>Width:
                    <select name="width">
                        <option value=""></option>
                        <option value="width: auto">width: auto</option>
                        <option value="width: 0px">width: 0px</option>
                        <option value="width: 1px">width: 1px</option>
                        <option value="width: 2px">width: 2px</option>
                        <option value="width: 3px">width: 3px</option>
                        <option value="width: 4px">width: 4px</option>
                        <option value="width: 5px">width: 5px</option>
                        <option value="width: 6px">width: 6px</option>
                        <option value="width: 7px">width: 7px</option>
                        <option value="width: 8px">width: 8px</option>
                        <option value="width: 9px">width: 9px</option>
                        <option value="width: 10px">width: 10px</option>
                        <option value="width: 15px">width: 15px</option>
                        <option value="width: 20px">width: 20px</option>
                        <option value="width: 25px">width: 25px</option>
                        <option value="width: 30px">width: 30px</option>
                        <option value="width: 35px">width: 35px</option>
                        <option value="width: 40px">width: 40px</option>
                        <option value="width: 45px">width: 45px</option>
                        <option value="width: 50px">width: 50px</option>
                        <option value="width: 75px">width: 75px</option>
                        <option value="width: 125px">width: 125px</option>
                        <option value="width: 150px">width: 150px</option>
                        <option value="width: 175px">width: 175px</option>
                        <option value="width: 200px">width: 200px</option>
                        <option value="width: 250px">width: 250px</option>
                        <option value="width: 300px">width: 300px</option>
                        <option value="width: 350px">width: 350px</option>
                        <option value="width: 400px">width: 400px</option>
                        <option value="width: 500px">width: 500px</option>
                        <option value="width: 600px">width: 600px</option>
                        <option value="width: 700px">width: 700px</option>
                        <option value="width: 800px">width: 800px</option>
                        <option value="width: 900px">width: 900px</option>
                        <option value="width: 1000px">width: 1000px</option>
                        <option value="width: 1100px">width: 1100px</option>
                        <option value="width: 1200px">width: 1200px</option>
                        <option value="width: 1300px">width: 1300px</option>
                        <option value="width: 1400px">width: 1400px</option>
                        <option value="width: 1500px">width: 1500px</option>
                        <option value="width: 1%">width: 1%</option>
                        <option value="width: 2%">width: 2%</option>
                        <option value="width: 3%">width: 3%</option>
                        <option value="width: 4%">width: 4%</option>
                        <option value="width: 5%">width: 5%</option>
                        <option value="width: 6%">width: 6%</option>
                        <option value="width: 7%">width: 7%</option>
                        <option value="width: 8%">width: 8%</option>
                        <option value="width: 9%">width: 9%</option>
                        <option value="width: 10%">width: 10% (Ten Coulmns)</option>
                        <option value="width: 11.11%">width: 11.11% (Nine Coulmns)</option>
                        <option value="width: 12.50%">width: 12.50% (Eight Coulmns)</option>
                        <option value="width: 14.28%">width: 14.28% (Seven Coulmns)</option>
                        <option value="width: 15%">width: 15%</option>
                        <option value="width: 16.66%">width: 16.66% (Six Coulmns)</option>
                        <option value="width: 20%">width: 20% (Five Coulmns)</option>
                        <option value="width: 25%">width: 25% (Four Coulmns)</option>
                        <option value="width: 30%">width: 30%</option>
                        <option value="width: 33.33%">width: 33.33% (Three Coulmns)</option>
                        <option value="width: 35%">width: 35%</option>
                        <option value="width: 40%">width: 40%</option>
                        <option value="width: 45%">width: 45%</option>
                        <option value="width: 50%">width: 50% (Two Coulmns)</option>
                        <option value="width: 75%">width: 75%</option>
                        <option value="width: 100%">width: 100% (One Coulmns)</option>
                        <option value="width: calc(100% - 10px)">width: calc(100% - 10px)</option>
                        <option value="width: calc(100% - 15px)">width: calc(100% - 15px)</option>
                        <option value="width: calc(100% - 20px">width: calc(100% - 20px)</option>
                        <option value="width: calc(100% - 25px)">width: calc(100% - 25px)</option>
                        <option value="width: calc(100% - 30px)">width: calc(100% - 30px)</option>
                        <option value="width: calc(100% - 35px)">width: calc(100% - 35px)</option>
                        <option value="width: calc(100% - 40px)">width: calc(100% - 40px)</option>
                        <option value="width: calc(100% - 45px)">width: calc(100% - 45px)</option>
                        <option value="width: calc(100% - 50px)">width: calc(100% - 50px)</option>
                        <option value="width: calc(100% - 75px)">width: calc(100% - 75px)</option>
                        <option value="width: calc(100% - 125px)">width: calc(100% - 125px)</option>
                        <option value="width: calc(100% - 150px)">width: calc(100% - 150px)</option>
                        <option value="width: calc(100% - 175px)">width: calc(100% - 175px)</option>
                        <option value="width: calc(100% - 200px)">width: calc(100% - 200px)</option>
                        <option value="width: calc(100% - 250px)">width: calc(100% - 250px)</option>
                        <option value="width: calc(100% - 300px)">width: calc(100% - 300px)</option>
                        <option value="width: calc(100% - 350px)">width: calc(100% - 350px)</option>
                        <option value="width: calc(100% - 400px)">width: calc(100% - 400px)</option>
                        <option value="width: calc(100% - 500px)">width: calc(100% - 500px)</option>
                    </select>
                    </label>
                    </div>
                    <div>
                    <label>Max Width:
                    <select name="max-width">
                        <option value=""></option>
                        <option value="max-width: 0px">max-width: 0px</option>
                        <option value="max-width: 1px">max-width: 1px</option>
                        <option value="max-width: 2px">max-width: 2px</option>
                        <option value="max-width: 3px">max-width: 3px</option>
                        <option value="max-width: 4px">max-width: 4px</option>
                        <option value="max-width: 5px">max-width: 5px</option>
                        <option value="max-width: 6px">max-width: 6px</option>
                        <option value="max-width: 7px">max-width: 7px</option>
                        <option value="max-width: 8px">max-width: 8px</option>
                        <option value="max-width: 9px">max-width: 9px</option>
                        <option value="max-width: 10px">max-width: 10px</option>
                        <option value="max-width: 15px">max-width: 15px</option>
                        <option value="max-width: 20px">max-width: 20px</option>
                        <option value="max-width: 25px">max-width: 25px</option>
                        <option value="max-width: 30px">max-width: 30px</option>
                        <option value="max-width: 35px">max-width: 35px</option>
                        <option value="max-width: 40px">max-width: 40px</option>
                        <option value="max-width: 45px">max-width: 45px</option>
                        <option value="max-width: 50px">max-width: 50px</option>
                        <option value="max-width: 75px">max-width: 75px</option>
                        <option value="max-width: 125px">max-width: 125px</option>
                        <option value="max-width: 150px">max-width: 150px</option>
                        <option value="max-width: 175px">max-width: 175px</option>
                        <option value="max-width: 200px">max-width: 200px</option>
                        <option value="max-width: 250px">max-width: 250px</option>
                        <option value="max-width: 300px">max-width: 300px</option>
                        <option value="max-width: 350px">max-width: 350px</option>
                        <option value="max-width: 400px">max-width: 400px</option>
                        <option value="max-width: 500px">max-width: 500px</option>
                        <option value="max-width: 600px">max-width: 600px</option>
                        <option value="max-width: 700px">max-width: 700px</option>
                        <option value="max-width: 800px">max-width: 800px</option>
                        <option value="max-width: 900px">max-width: 900px</option>
                        <option value="max-width: 1000px">max-width: 1000px</option>
                        <option value="max-width: 1100px">max-width: 1100px</option>
                        <option value="max-width: 1200px">max-width: 1200px</option>
                        <option value="max-width: 1300px">max-width: 1300px</option>
                        <option value="max-width: 1400px">max-width: 1400px</option>
                        <option value="max-width: 1500px">max-width: 1500px</option>
                        <option value="max-width: 1%">max-width: 1%</option>
                        <option value="max-width: 2%">max-width: 2%</option>
                        <option value="max-width: 3%">max-width: 3%</option>
                        <option value="max-width: 4%">max-width: 4%</option>
                        <option value="max-width: 5%">max-width: 5%</option>
                        <option value="max-width: 6%">max-width: 6%</option>
                        <option value="max-width: 7%">max-width: 7%</option>
                        <option value="max-width: 8%">max-width: 8%</option>
                        <option value="max-width: 9%">max-width: 9%</option>
                        <option value="max-width: 10%">max-width: 10%</option>
                        <option value="max-width: 15%">max-width: 15%</option>
                        <option value="max-width: 20%">max-width: 20%</option>
                        <option value="max-width: 25%">max-width: 25%</option>
                        <option value="max-width: 30%">max-width: 30%</option>
                        <option value="max-width: 35%">max-width: 35%</option>
                        <option value="max-width: 40%">max-width: 40%</option>
                        <option value="max-width: 45%">max-width: 45%</option>
                        <option value="max-width: 50%">max-width: 50%</option>
                        <option value="max-width: 75%">max-width: 75%</option>
                        <option value="max-width: 100%">max-width: 100%</option>
                    </select>
                    </label>
                    </div>
    
                    <div>
                    <label>Z Index:
                    <select name="z-index">
                        <option value=""></option>
                        <option value="z-index: 1">z-index: 1</option>
                        <option value="z-index: 2">z-index: 2</option>
                        <option value="z-index: 3">z-index: 3</option>
                        <option value="z-index: 4">z-index: 4</option>
                        <option value="z-index: 5">z-index: 5</option>
                        <option value="z-index: 6">z-index: 6</option>
                        <option value="z-index: 7">z-index: 7</option>
                        <option value="z-index: 8">z-index: 8</option>
                        <option value="z-index: 9">z-index: 9</option>
                        <option value="z-index: 10">z-index: 10</option>
                        <option value="z-index: 100">z-index: 100</option>
                        <option value="z-index: 200">z-index: 200</option>
                        <option value="z-index: 250">z-index: 250</option>
                        <option value="z-index: 300">z-index: 300</option>
                        <option value="z-index: 350">z-index: 350</option>
                        <option value="z-index: 400">z-index: 400</option>
                        <option value="z-index: 500">z-index: 500</option>
                        <option value="z-index: 600">z-index: 600</option>
                        <option value="z-index: 700">z-index: 700</option>
                        <option value="z-index: 800">z-index: 800</option>
                        <option value="z-index: 900">z-index: 900</option>
                        <option value="z-index: 1000">z-index: 1000</option>
                        <option value="z-index: 1100">z-index: 1100</option>
                        <option value="z-index: 1200">z-index: 1200</option>
                        <option value="z-index: 1300">z-index: 1300</option>
                        <option value="z-index: 1400">z-index: 1400</option>
                        <option value="z-index: 1500">z-index: 1500</option>
                    </select>
                    </label>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Editor Styles -->
    
        <!-- Start Editor Wrapper -->
        <div class="editor-wrapper">
    
            <!-- Start Tools -->
            <div id="tools">
                <button type="button"  class="editStyles">Edit Styles</button>
                <button type="button"  id="undo">Undo</button>
                <button type="button"  id="redo">Redo</button>
                <button type="button"  class="strong">Bold</button>
                <button type="button"  class="italic">Italic</button>
                <button type="button"  class="underline">Underline</button>
                <button type="button"  class="strike-through">Strike</button>
                <button type="button"  class="header-format" data-header="h1">H1</button>
                <button type="button"  class="header-format" data-header="h2">H2</button>
                <button type="button"  class="header-format" data-header="h3">H3</button>
                <button type="button"  class="header-format" data-header="h4">H4</button>
                <button type="button"  class="header-format" data-header="h5">H5</button>
                <button type="button"  class="header-format" data-header="h6">H6</button>
                <button type="button"  class="insertLinkButton">Link</button>
                <button type="button"  class="unlink">Unlink</button>
                <button type="button"  class="ordered-list">Ordered List</button>
                <button type="button"  class="unordered-list">Unordered List</button>
                <select class="element-type" name="element-type">
                    <option value="div">div</option>
                    <option value="span">span</option>
                    <option value="button">button</option>
                    <option value="p">p</option>
                </select>
                <button type="button"  class="addElementButton">Add Element</button>
                <button type="button"  class="show-source">Source Code</button>
            </div>
            <!-- End Tools -->
    
            <!-- Start Editor -->
            <div id="editor-1" class="content-styles editor-parent" contenteditable="true"></div>
            <!-- End Editor -->
    
        </div>
        <!-- End Editor Wrapper -->
    </div>
    
    <!-- Start Source Code -->
    <div id="source-code-popup">
        <div id="source-content">
            <h2>Source Code</h2>
            <textarea id="source-code" name="<?php echo $editor_name; ?>" rows="8"><?php echo $editor_content; ?></textarea>
            <div class="source-content-buttons">
                <button type="button"  id="save-source">Save</button>
                <button type="button"  id="close-source-code">Close</button>
            </div>
        </div>
    </div>
    <!-- End Source Code -->
    
    <!-- Start Link Manager Popup -->
    <div id="link-manager" class="link-manager-popup-wrapper link-manager-hide">
        <div class="link-manager">
            <h3>Insert Link</h3>
            <label for="urlInput">Enter Link URL:</label>
            <input type="text" id="urlInput" class="url-input" placeholder="https://example.com" />
    
            <!-- New URL ID Input -->
            <label for="urlIdInput">Enter URL ID:</label>
            <input type="text" id="urlIdInput" class="url-input" placeholder="ID for the URL" />
    
            <label for="targetType">Choose Target:</label>
            <select id="targetType" class="target-select">
                <option value="">None</option>
                <option value="_self">Same Window</option>
                <option value="_blank">New Tab</option>
                <option value="_parent">Parent Window</option>
                <option value="_top">Topmost Window</option>
            </select>
    
            <label for="relType">Choose Rel:</label>
            <select id="relType" class="rel-select">
                <option value="">None</option>
                <option value="follow">Follow</option>
                <option value="nofollow">NoFollow</option>
            </select>
    
            <div class="link-manager-buttons">
                <button id="insertLinkBtn" class="insert-btn">Insert Link</button>
                <button id="cancelBtn" class="cancel-btn">Cancel</button>
            </div>
        </div>
    </div>
    <!-- End Link Manager Popup -->
    
    <!-- Start Merge Modal Code -->
    <div id="mergeModal" class="mergeModal">
        <h4 class="mergeModal2">Merge Cells</h4>
    
        <label class="mergeDirectio">
            <input type="radio" name="mergeDirection" value="horizontal" checked>
            Horizontal
        </label>
    
        <label class="mergeDirectio">
            <input type="radio" name="mergeDirection" value="vertical">
            Vertical
        </label>
    
        <br><br>
    
        <label class="mergeSpan2">
            Number of cells to merge:
            <input type="number" id="mergeSpan" class="mergeSpan" value="2" min="2">
        </label>
    
        <br><br>
    
        <button id="mergeConfirmBtn" class="mergeConfirmBtn">Merge</button>
        <button onclick="document.getElementById('mergeModal').style.display='none'" class="mergeConfirmBtn2">Cancel</button>
    </div>
    <!-- End Merge Modal Code -->
    
    <!-- Start Insert Cells Modal Code -->
    <div id="addCellModal" class="addCellModal">
        <h4 class="addCellModal2">Insert Cells (Horizontally)</h4>
    
        <label class="insertCell2">
            Number of cells to insert:
            <input type="number" id="insertCell" class="insertCell" value="1" min="1">
        </label>
    
        <br><br>
    
        <button id="addCellConfirmBtn" class="addCellConfirmBtn">Insert</button>
        <button onclick="document.getElementById('addCellModal').style.display='none'" class="addCellConfirmBtn2">Cancel</button>
    </div>
    <!-- End Insert Cells Modal Code -->
<?php } ?>