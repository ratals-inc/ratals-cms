<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/table/scripts/sort-table-columns.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/table/scripts/sort-table-columns.php');
}
else
{
?>
	<script nonce="<?php echo NONCE; ?>">
    //Make Popup List Sortable for Table Columns
    $(function() { $(".column-list").sortable({ cursor: 'move', handle: ".sort-columns" }); });
    
    //Show List Popup for Table Columns
    $(function()
    {
        $(".show-columns").click(function()
        { 	
            $(".popup").show(); 
            $("body").addClass("popup-overflow-hidden"); 
        });
    });
    
    //Hide List Popup for Table Columns
    $(function()
    {
        $(".hide-columns").click(function()
        { 	
            $(".popup").hide(); 
            $("body").removeClass("popup-overflow-hidden"); 
        });
    });
    </script>
<?php } ?>
