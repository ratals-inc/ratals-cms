<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/media-popup.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/media-popup.php');
}
else
{
?>
    <!-- Start Media Popup -->
    <script nonce="<?php echo NONCE; ?>">
    function removeMedia(id)
    {
        $("#mediaId_"+id).val("");
        $("#colorswatch_image_"+id).hide();
        $("#select_media_button_"+id).show();
        $("#colorswatch_image_controls_"+id).hide();
        $("#colorswatch_button_controls_"+id).show();
    }
    
    $(document).on('click', '.removeMultipleMedia', function()
    {
        var dataValues = $(this).attr('data-click');
        var dataArray = dataValues.split(',');
        
        var id = dataArray[0];
        var columnName = dataArray[1];
        
        $("#removeMultipleMedia_"+id).remove();
        $('.'+columnName).text('Add Media');
    });
    
    //Show Media Popup and Set Swatch id
    function openMediaPopup(id)
    {
        selectedMediaId = id;
        addMultipleMedia = 'No';
        $("#selectedMediaId").val(selectedMediaId);
        $(".popup_media").show(); 
        $("body").addClass("popup-overflow-hidden"); 
    }
    
    columnNames = {}; //make columnNames global so other funtions can call it
    columnNames.newCounter = 0;
    
    $(document).ready(function()
    {
        $(".openMultipleMediaPopup").click(function()
        {
            var dataValues = $(this).attr('data-click');
            var dataArray = dataValues.split(',');
            
            var columnName = dataArray[0];
            var columnNameOnly = dataArray[1];
            var counter = dataArray[2];
            
            addMultipleMedia = 'Yes';
            $(".popup_media").show(); 
            $("body").addClass("popup-overflow-hidden"); 
            columnNames.columnName = columnName;
            columnNames.columnNameOnly = columnNameOnly;
            columnNames.newCounter = columnNames.newCounter + 1
            columnNames.counter = columnNames.newCounter + (counter - 1);
        });
    });
    
    $(document).ready(function()
    {
        $(".openSingleMediaPopup").click(function()
        {
            var dataValues = $(this).attr('data-click');
            var dataArray = dataValues.split(',');
            
            var columnName = dataArray[0];
            var columnNameOnly = dataArray[1];
            var counter = dataArray[2];
            
            addMultipleMedia = 'No';
            $(".popup_media").show(); 
            $("body").addClass("popup-overflow-hidden"); 
            columnNames.columnName = columnName;
            columnNames.columnNameOnly = columnNameOnly;
            columnNames.newCounter = columnNames.newCounter + 1
            columnNames.counter = columnNames.newCounter + (counter - 1);
        });
    });
    
    //Hide Media Popup
    $(function() { $(".hide-media").click(function()
        { 	
            $(".popup_media").hide();
            $("body").removeClass("popup-overflow-hidden"); 
        });
    });
    
    //Set media id on hidden value and swap images to selected one
    $(document).on('click', '.selectMedia', function()
    {
        var id = $(this).attr('data-click');
        
        if(typeof document.getElementById("selected_image_"+id).src != "undefined")
        {
            var selected_media = document.getElementById("selected_image_"+id).src;
        }
        else
        {
            //Media types of Files use data="" for scr/urls
            var selected_media = document.getElementById("selected_image_"+id).data;
        }
        
        var selected_tag = document.getElementById("selected_tag_"+id).textContent;
        
        var fileExtType = selected_media.split(".").pop();
        var addMedia = <?php echo $_SESSION['multiple_media_counter'] ?? 10000; ?>;
		if(typeof i === 'undefined') { i = parseInt(addMedia) + 1 } else { i = i + 1 };
		var deleteColumnName = 'single-media-button-'+columnNames.columnNameOnly;
		
        if(selected_media.includes("images"))
        {
            var html = '<li class="media" id="removeMultipleMedia_'+i+'"><i class="close removeMultipleMedia" data-click="'+i+','+deleteColumnName+'" title="Remove Media"><svg viewBox="0 0 512 512"><path d="M506 256A250 250 0 1 1 6 256 250 250 0 0 1 506 256M173 151A16 16 0 1 0 151 173L234 256 151 339A16 16 0 0 0 173 361L256 278 339 361A16 16 0 0 0 361 339L278 256 361 173A16 16 0 0 0 339 151L256 234Z"></path></svg></i><i class="move move-handle" aria-hidden="true" title="Sort Media"><svg viewBox="0 0 512 512"><path d="m245 7a16 16 0 0 1 22 0l63 63a16 16 0 0 1-22 22l-36-36v120a16 16 0 0 1-32 0v-120l-36 36a16 16 0 1 1-22-22zm11 312a16 16 0 0 1 16 16v120l36-36a16 16 0 0 1 22 22l-63 63a16 16 0 0 1-22 0l-63-63a16 16 0 0 1 22-22l36 36v-120a16 16 0 0 1 16-16m-249-52a16 16 0 0 1 0-22l63-63a16 16 0 1 1 22 22l-36 36h120a16 16 0 0 1 0 32h-120l36 36a16 16 0 0 1-22 22zm312-11a16 16 0 0 1 16-16h120l-36-36a16 16 0 0 1 22-22l63 63a16 16 0 0 1 0 22l-63 63a16 16 0 0 1-22-22l36-36h-120a16 16 0 0 1-16-16"></path></svg></i><img src="'+selected_media+'"><input name="'+columnNames.columnName+'['+columnNames.counter+'][]" type="hidden" value="'+id+'"><div class="text"><div class="tag"><input name="'+columnNames.columnName+'['+columnNames.counter+'][]" type="text" value="" placeholder="'+selected_tag+'"></div>Media ID: <a href="<?php echo $domain; ?>/<?php echo $_SESSION['admin_directory']; ?>/media/?textfield-id='+id+'" target="_blank">'+id+'</a></div></li>';
        }
        else if(selected_media.includes("files"))
        {
            var html = '<li class="media" id="removeMultipleMedia_'+i+'"><i class="close removeMultipleMedia" data-click="'+i+','+deleteColumnName+'" title="Remove Media"><svg viewBox="0 0 512 512"><path d="M506 256A250 250 0 1 1 6 256 250 250 0 0 1 506 256M173 151A16 16 0 1 0 151 173L234 256 151 339A16 16 0 0 0 173 361L256 278 339 361A16 16 0 0 0 361 339L278 256 361 173A16 16 0 0 0 339 151L256 234Z"></path></svg></i><i class="move move-handle" aria-hidden="true" title="Sort Media"><svg viewBox="0 0 512 512"><path d="m245 7a16 16 0 0 1 22 0l63 63a16 16 0 0 1-22 22l-36-36v120a16 16 0 0 1-32 0v-120l-36 36a16 16 0 1 1-22-22zm11 312a16 16 0 0 1 16 16v120l36-36a16 16 0 0 1 22 22l-63 63a16 16 0 0 1-22 0l-63-63a16 16 0 0 1 22-22l36 36v-120a16 16 0 0 1 16-16m-249-52a16 16 0 0 1 0-22l63-63a16 16 0 1 1 22 22l-36 36h120a16 16 0 0 1 0 32h-120l36 36a16 16 0 0 1-22 22zm312-11a16 16 0 0 1 16-16h120l-36-36a16 16 0 0 1 22-22l63 63a16 16 0 0 1 0 22l-63 63a16 16 0 0 1-22-22l36-36h-120a16 16 0 0 1-16-16"></path></svg></i><object data="'+selected_media+'" type="application/'+fileExtType+'" class="select-media-popup"></object><input name="'+columnNames.columnName+'['+columnNames.counter+'][]" type="hidden" value="'+id+'"><div class="text"><div class="tag"><input name="'+columnNames.columnName+'['+columnNames.counter+'][]" type="text" value="" placeholder="'+selected_tag+'"></div>Media ID: <a href="<?php echo $domain; ?>/<?php echo $_SESSION['admin_directory']; ?>/media/?textfield-id='+id+'" target="_blank">'+id+'</a></div></li>';
        }
        else if(selected_media.includes("videos"))
        {
            var html = '<li class="media" id="removeMultipleMedia_'+i+'"><i class="close removeMultipleMedia" data-click="'+i+','+deleteColumnName+'" title="Remove Media"></i><i class="move move-handle" aria-hidden="true" title="Sort Media"><svg viewBox="0 0 512 512"><path d="m245 7a16 16 0 0 1 22 0l63 63a16 16 0 0 1-22 22l-36-36v120a16 16 0 0 1-32 0v-120l-36 36a16 16 0 1 1-22-22zm11 312a16 16 0 0 1 16 16v120l36-36a16 16 0 0 1 22 22l-63 63a16 16 0 0 1-22 0l-63-63a16 16 0 0 1 22-22l36 36v-120a16 16 0 0 1 16-16m-249-52a16 16 0 0 1 0-22l63-63a16 16 0 1 1 22 22l-36 36h120a16 16 0 0 1 0 32h-120l36 36a16 16 0 0 1-22 22zm312-11a16 16 0 0 1 16-16h120l-36-36a16 16 0 0 1 22-22l63 63a16 16 0 0 1 0 22l-63 63a16 16 0 0 1-22-22l36-36h-120a16 16 0 0 1-16-16"></path></svg></i><video controls="" preload="none" class="select-media-popup"><source src="'+selected_media+'" type="video/'+fileExtType+'"></video><input name="'+columnNames.columnName+'['+columnNames.counter+'][]" type="hidden" value="'+id+'"><div class="text"><div class="tag"><input name="'+columnNames.columnName+'['+columnNames.counter+'][]" type="text" value="" placeholder="'+selected_tag+'"></div>Media ID: <a href="<?php echo $domain; ?>/<?php echo $_SESSION['admin_directory']; ?>/media/?textfield-id='+id+'" target="_blank">'+id+'</a></div></li>';
        }
        //Video Embed
        else
        {
            var html = '<li class="media" id="removeMultipleMedia_'+i+'"><i class="close removeMultipleMedia" data-click="'+i+','+deleteColumnName+'" title="Remove Media"><svg viewBox="0 0 512 512"><path d="M506 256A250 250 0 1 1 6 256 250 250 0 0 1 506 256M173 151A16 16 0 1 0 151 173L234 256 151 339A16 16 0 0 0 173 361L256 278 339 361A16 16 0 0 0 361 339L278 256 361 173A16 16 0 0 0 339 151L256 234Z"></path></svg></i><i class="move move-handle" aria-hidden="true" title="Sort Media"><svg viewBox="0 0 512 512"><path d="m245 7a16 16 0 0 1 22 0l63 63a16 16 0 0 1-22 22l-36-36v120a16 16 0 0 1-32 0v-120l-36 36a16 16 0 1 1-22-22zm11 312a16 16 0 0 1 16 16v120l36-36a16 16 0 0 1 22 22l-63 63a16 16 0 0 1-22 0l-63-63a16 16 0 0 1 22-22l36 36v-120a16 16 0 0 1 16-16m-249-52a16 16 0 0 1 0-22l63-63a16 16 0 1 1 22 22l-36 36h120a16 16 0 0 1 0 32h-120l36 36a16 16 0 0 1-22 22zm312-11a16 16 0 0 1 16-16h120l-36-36a16 16 0 0 1 22-22l63 63a16 16 0 0 1 0 22l-63 63a16 16 0 0 1-22-22l36-36h-120a16 16 0 0 1-16-16"></path></svg></i><div class="video-embed"><iframe class="select-media-popup" src="'+selected_media+'" title="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe></div><input name="'+columnNames.columnName+'['+columnNames.counter+'][]" type="hidden" value="'+id+'"><div class="text"><div class="tag"><input name="'+columnNames.columnName+'['+columnNames.counter+'][]" type="text" value="" placeholder="'+selected_tag+'"></div>Media ID: <a href="<?php echo $domain; ?>/<?php echo $_SESSION['admin_directory']; ?>/media/?textfield-id='+id+'" target="_blank">'+id+'</a></div></li>';
        }
		
		if(addMultipleMedia == 'Yes')
        {
            $(".multiple-media-"+columnNames.columnNameOnly).append(html);
            $("#selectedMediaId").val(i);
            $(".popup_media").hide(); 
            $("body").removeClass("popup-overflow-hidden");
        }
        else
        {
            $(".multiple-media-"+columnNames.columnNameOnly).html(html);
            $("#selectedMediaId").val(i);
            $('.single-media-button-'+columnNames.columnNameOnly).text('Change Media');
            $(".popup_media").hide(); 
            $("body").removeClass("popup-overflow-hidden");
        }
    });
    
    
    $(document).ready(function()
    {
        var limit = 30;
        var start = 0;
        var sortBy = 0;
        var sortBySet = 0;
        var sortByChanged = 'no';
        var mediaType = 0;
        var mediaTypeSet = 0;
        var mediaTypeChanged = 'no';
        var searchMedia = '';
        var searchMediaSet = '';
        var searchMediaChanged = 'no';
        var clearChanged = 'no';
        var action = 'inactive';
        
        function load_images(limit, start, sortBy, mediaType, searchMedia)
        {
           //alert("Limit: " + limit);
           //alert("Start: " + start);
           //alert("Sort By: " + sortBy);
           //alert("Media Type: " + mediaType);
           //alert("Search Media Keyword: " + searchMedia);
            $.ajax(
            {
                 url: "/<?php echo $_SESSION['admin_directory']; ?>/cms/includes/media-popup-ajax.php",
                 method:"POST",
                 data:{limit:limit, start:start, sortBy:sortBy, images:mediaType, searchMedia:searchMedia},
                 cache:false,
                 success:function(data)
                 {
                     $('#load_data').append(data);
                     if(data == '')
                     {
                       //$('#load_data_message').html("<button type='button' class='btn btn-info'>End of Media on File</button>");
                       action = 'active';
                     }
                     else
                     {
                       //$('#load_data_message').html("<button type='button' class='btn btn-warning'>Please Wait....</button>");
                       action = "inactive";
                     }
                 }
            });
        }
        
        if(action == 'inactive')
        {
            action = 'active';
            load_images(limit, start, sortBy, mediaType, searchMedia);
        }
        
        $("#media").scroll(function()
        {
            if($("#media").scrollTop() + (Math.max(window.innerHeight) - 50) > document.getElementById("load_data_message").offsetTop - 600 && action == 'inactive')
            {
                action = 'active';
                start = start + limit;
                if(sortByChanged == 'yes') 
                { 
                    start = 30; 
                    sortByChanged = "no"; 
                }
                
                if(sortBySet == 0)
                {
                   sortBy = 0;
                }
                else
                {
                  sortBy = sortBySet; 
                }
             
                if(mediaTypeChanged == 'yes') 
                { 
                   start = 30; 
                   mediaTypeChanged = "no"; 
                }
                
                if(mediaTypeSet == 0)
                {
                   mediaType = 0;
                }
                else
                {
                  mediaType = mediaTypeSet; 
                }
                
                if(searchMediaChanged == 'yes') 
                { 
                   start = 30; 
                   searchMediaChanged = "no"; 
                }
                
                if(searchMediaSet == '')
                {
                   searchMedia = '';
                }
                else
                {
                  searchMedia = searchMediaSet; 
                }
             
                if(clearChanged == 'yes') 
                { 
                   start = 30; 
                   sortBy = '0';
                   sortBySet = '0';
                   mediaType = '0';
                   mediaTypeSet = '0';
                   searchMedia = '';
                   searchMediaSet = '';
                   clearChanged = "no"; 
                }
             
                setTimeout(function()
                {
                    load_images(limit, start, sortBy, mediaType, searchMedia);
                }, 250);
            }
        });
        
        $('#sort_by').change(function()
        {
            sortBySet = $(this).val();
            action = 'active';
            sortByChanged = 'yes';
            $(".clear-media-search").show();
            $("#load_data").empty();
            load_images(30, 0, sortBySet, mediaTypeSet, searchMediaSet);
        });
        
        $('#media_type').change(function()
        {
            mediaTypeSet = $(this).val();
            action = 'active';
            mediaTypeChanged = 'yes';
            $(".clear-media-search").show();
            $("#load_data").empty();
            load_images(30, 0, sortBySet, mediaTypeSet, searchMediaSet);
        });
        
        $('.search-media-icon').click(function()
        {
            searchMediaSet = $("#search_media").val();
            action = 'active';
            searchMediaChanged = 'yes';
            $(".clear-media-search").show();
            $("#load_data").empty();
            load_images(30, 0, sortBySet, mediaTypeSet, searchMediaSet);
        });
        
        var input = document.getElementById("search_media");
        input.addEventListener("keypress", function(event) 
        {
            if (event.key === "Enter")
            {
                searchMediaSet = $("#search_media").val();
                action = 'active';
                searchMediaChanged = 'yes';
                $(".clear-media-search").show();
                $("#load_data").empty();
                load_images(30, 0, sortBySet, mediaTypeSet, searchMediaSet);
            }
        });
        
        $('#clear-media-search').click(function()
        {
            action = 'active';
            clearChanged = 'yes';
            $("#sort_by").val("0");
            $("#media_type").val("0");
            $("#search_media").val("");
            searchMediaSet = '';
            $(".clear-media-search").hide();
            $("#load_data").empty();
            load_images(30, 0, 0, 0, '');
        });
    });
    </script>
    <div class="popup popup_media admin-width">
      <div class="wrapper">
        <div class="media" id="media">
          <div class="header">
            <div class="header-top">
              <div class="headline">Select Media</div>
              <div class="close hide-media"><i><svg viewBox="0 0 512 512"><path d="M506 256A250 250 0 1 1 6 256 250 250 0 0 1 506 256M173 151A16 16 0 1 0 151 173L234 256 151 339A16 16 0 0 0 173 361L256 278 339 361A16 16 0 0 0 361 339L278 256 361 173A16 16 0 0 0 339 151L256 234Z"></path></svg></i></div>
            </div>
            <div class="header-options">
              <div class="search">
                <span class="media_type"><select name="media_type" id="media_type"><option value="0">All Media</option><option value="1">Only Images</option><option value="2">Only Files</option><option value="3">Only Videos</option><option value="4">Only Video Embeds</option></select></span>
                <select name="sort_by" id="sort_by">
                <option value="0">Sort By: Newest</option>
                <option value="1">Sort By: Oldest</option>
                </select>
                <div class="search-field"><label for="search_media"><input type="text" name="search_media" id="search_media" placeholder="Search media"><i class="search-media-icon"><svg viewBox="0 0 512 512"><path d="M375 332a206 206 0 1 0-44 44h0q1 2 3 4l122 122a32 32 0 0 0 45-45l-122-122a32 32 0 0 0-4-3zM383 211a174 174 0 1 1-348 0 174 174 0 0 1 348 0"></path></svg></i></label></div>
                <button class="clear-media-search" id="clear-media-search">Clear Search</button>
              </div>
            </div>
          </div>
          <ul id="load_data">
          </ul>
          <div id="load_data_message"></div>
        </div>
      </div>
    </div>
    <!-- End Media Popup -->
<?php } ?>
