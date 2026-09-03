<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/scripts/design-blocks.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/scripts/design-blocks.php');
}
else
{
if($_SESSION['admin_assigned_type'] == 'design_blocks')
{
?>
	<script nonce="<?php echo NONCE; ?>">
    //Start show datepicker
    $(function() 
    {
        $("#datepicker-product").datepicker({dateFormat: "yy-mm-dd"});
        $("#datepicker-product-from").datepicker({dateFormat: "yy-mm-dd"});
        $("#datepicker-product-to").datepicker({dateFormat: "yy-mm-dd"});
        $("#datepicker-product-search").datepicker({dateFormat: "yy-mm-dd"});
        $("#datepicker-product-search-from").datepicker({dateFormat: "yy-mm-dd"});
        $("#datepicker-product-search-to").datepicker({dateFormat: "yy-mm-dd"});
    });
    
    //Remove groups
    $(function() 
    {
        $(".sortGroups").sortable(
        {
            cursor: 'move',
            handle: ".order-group"
        });
    });
    
    $(function() 
    {
        $(".sortCategories").sortable(
        {
            cursor: 'move',
            handle: ".order-row"
        });
    });
    
    $(document).ready(function()
    {
        $(document).on('change', '#groups, #item-type, #items', function()
        {
            var groupId = $("#groups").val();	
            var GroupName = $("#groups option:selected").text();
            
            var productsId = $('#items').find('input[type="checkbox"]:checked').length;
            $('#items-group-id').val(groupId);
            
            if(groupId != '')
            {
                $('#categories-toggle').hide();
                $('#pages-toggle').hide();
                $('#posts-toggle').hide();
                $('#items-toggle').show();
                
                if(productsId != '')
                {
                    $('#items-submit').show();
                }
                else
                {
                    $('#items-submit').hide();
                }
            }
            else
            {
                $('#items-toggle').hide();
            }
        });
        
        //Display Design Block options on load.
        $('.design-block-type').each(function() 
        {
            var subItemId = $(this).data('design-block-id');
            var displaySection = $(this).find("option:selected").text();
            
            if(displaySection == 'Code (html/css)')
            {
                $('.include-file-'+subItemId).hide();
                $('.items-block-'+subItemId).hide();
                $('.code-'+subItemId).slideDown();
            }
            else if(displaySection == 'Include File')
            {
                $('.code-'+subItemId).hide();
                $('.items-block-'+subItemId).hide();
                $('.include-file-'+subItemId).slideDown();
            }
            else if(displaySection == 'Block Items')
            {
                $('.code-'+subItemId).hide();
                $('.include-file-'+subItemId).hide();
                $('.items-block-'+subItemId).slideDown();
            }
            else
            {
                $('.code-'+subItemId).hide();
                $('.include-file-'+subItemId).hide();
                $('.items-block-'+subItemId).hide();
            }
        });
        
        //Display Design Block options on change.
        $(document).on('change', '.design-block-type', function()
        {
            var subItemId = $(this).data('design-block-id');
            var displaySection = $(this).find("option:selected").text();
            
            if(displaySection == 'Code (html/css)')
            {
                $('.include-file-'+subItemId).hide();
                $('.items-block-'+subItemId).hide();
                $('.code-'+subItemId).slideDown();
            }
            else if(displaySection == 'Include File')
            {
                $('.code-'+subItemId).hide();
                $('.items-block-'+subItemId).hide();
                $('.include-file-'+subItemId).slideDown();
            }
            else if(displaySection == 'Block Items')
            {
                $('.code-'+subItemId).hide();
                $('.include-file-'+subItemId).hide();
                $('.items-block-'+subItemId).slideDown();
            }
            else
            {
                $('.code-'+subItemId).hide();
                $('.include-file-'+subItemId).hide();
                $('.items-block-'+subItemId).hide();
            }
        });
        
        //Display slider options on load if Display group as a slider: Yes.
        $('.display-as-slider').each(function() 
        {
            var sliderId = $(this).data('slider-id');
            var displaySlider = $(this).find("option:selected").text();
            
            if(displaySlider == 'Yes')
            {
                $('.design-blocks-slider-' + sliderId).show();
            }
            else
            {
                $('.design-blocks-slider-' + sliderId).hide();
            }
        });
        
        //Display slider options on change if Display group as a slider: Yes.
        $(document).on('change', '.display-as-slider', function()
        {
            var sliderId = $(this).data('slider-id');
            var displaySlider = $(this).find("option:selected").text();
            
            if(displaySlider == 'Yes')
            {
                $('.design-blocks-slider-'+sliderId).slideDown();
            }
            else
            {
                $('.design-blocks-slider-'+sliderId).slideUp();
            }
        });
        
        //Remove groups.
        $(document).on('click', '.removeGroup', function()
        {
            var id = $(this).attr('data-click');
            
            $(".group_"+id).remove();
        });
        
        //Remove rows.
        $(document).on('click', '.removeRow', function()
        {
            var id = $(this).attr('data-click');
            
            $(".row_"+id).remove();
        });
        
        //Change active status on items in groups.
        $(document).on('click', '.changeActive', function()
        {
            var dataValues = $(this).attr('data-click');
            var dataArray = dataValues.split(',');
            
            var id = dataArray[0];
            var editing = dataArray[1];
            var row_id = dataArray[2];
            var value = dataArray[3];
            
            if(value == 1)
            {
                value = 2;
            }
            else
            {
                value = 1;
            }
            
            $.post(
                "<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/cms/layouts/static/ajax/design-blocks.php",
                {
                    type:'changeActive',
                    id:id,
                    editing:editing,
                    field:row_id,
                    value:value
                },
                function(theResponse)
                {
                    $(".status_"+editing).html(theResponse);
                    $('#item_status_'+editing).val(value);
                }
            );
        });
        
        //Change status on whole group. This is the eye and eye slash icon.
        $(document).on('click', '.groupStatus', function()
        {
            var dataValues = $(this).attr('data-click');
            var dataArray = dataValues.split(',');
            
            var editing = dataArray[0];
            var group_id_count = dataArray[1];
            var group_id = dataArray[2];
            var value = dataArray[3];
            
            if(value == 1)
            {
                value = 2;
            }
            else
            {
                value = 1;
            }
            
            $.post(
                "<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/cms/layouts/static/ajax/design-blocks.php",
                {
                    type:'groupStatus',
                    editing:editing,
                    group_id_count:group_id_count,
                    group_id:group_id,
                    value:value
                },
                function(theResponse)
                {
                    $("#status-group-"+group_id_count).html(theResponse);
                    $('#group-status-'+group_id_count).val(value);
                }
            );
        });
        
        //Search for items that can be assigned to a Design Block.
        $(document).on('click', '.design-block-item-search', function()
        {
            var searchButton = $(this);
            var groupCounter = searchButton.data('group-counter');
            var assignItem = searchButton.closest('.assign-item');
            var assignItemTable = $('#design-block-assign-items-'+groupCounter);
            
            var searchId = assignItem.find('[data-search-field="id"]').val();
            var itemType = assignItem.find('[data-search-field="type"]').val();
            var status = assignItem.find('[data-search-field="status"]').val();
            var searchTitle = assignItem.find('[data-search-field="title"]').val();
            var flatUrl = assignItem.find('[data-search-field="flat_url"]').val();
            var hierarchyUrl = assignItem.find('[data-search-field="hierarchy_url"]').val();
            
            $.post(
                "<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/cms/layouts/static/ajax/design-blocks.php",
                {
                    type:'designBlockItemSearch',
                    group_counter:groupCounter,
                    search_id:searchId,
                    item_type:itemType,
                    status:status,
                    search_title:searchTitle,
                    flat_url:flatUrl,
                    hierarchy_url:hierarchyUrl
                },
                function(theResponse)
                {
                    //Remove only the current result rows.
                    //Keep the table header and search fields in place.
                    assignItemTable.find('.design-block-assign-row, .no-results').remove();
                    
                    //Add the new search results directly to the assignment table.
                    assignItemTable.append(theResponse);
                    
                    //Display Clear Search button.
                    assignItem.find('.design-block-item-search-clear').removeClass('display-as-none');
                }
            );
        });
        
        //Clear Design Block item search and reload the default 100 most recent URLs.
        $(document).on('click', '.design-block-item-search-clear', function()
        {
            var clearButton = $(this);
            var groupCounter = clearButton.data('group-counter');
            var assignItem = clearButton.closest('.assign-item');
            var assignItemTable = $('#design-block-assign-items-'+groupCounter);
            
            //Clear all search fields for this Design Block only.
            assignItem.find('.design-block-search-field').each(function()
            {
                $(this).val('');
            });
            
            $.post(
                "<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/cms/layouts/static/ajax/design-blocks.php",
                {
                    type:'designBlockItemSearch',
                    group_counter:groupCounter,
                    search_id:'',
                    item_type:'',
                    status:'',
                    search_title:'',
                    flat_url:'',
                    hierarchy_url:''
                },
                function(theResponse)
                {
                    //Remove only the current result rows.
                    assignItemTable.find('.design-block-assign-row, .no-results').remove();
                    
                    //Reload the default 100 most recently created URLs.
                    assignItemTable.append(theResponse);
                    
                    //Hide Clear Search button again.
                    clearButton.addClass('display-as-none');
                }
            );
        });
        
        //Allow Enter key to run a Design Block item search.
        $(document).on('keydown', '.design-block-search-field', function(event)
        {
            if(event.key === 'Enter')
            {
                event.preventDefault();
                
                $(this)
                    .closest('.assign-item')
                    .find('.design-block-item-search')
                    .trigger('click');
            }
        });
    });
    </script>
    <?php } ?>
<?php } ?>