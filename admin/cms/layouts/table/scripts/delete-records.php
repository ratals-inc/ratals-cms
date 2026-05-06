<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/table/scripts/delete-records.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/table/scripts/delete-records.php');
}
elseif(!empty($_SESSION['admin_js_name']))
{
?>
	<script nonce="<?php echo NONCE; ?>">
    $(document).ready(function()
    {
        $(document).on('click', '.<?php echo $_SESSION['admin_js_name']; ?>', function()
        {
			<?php if(isset($_GET["rid"])) { echo 'var getRid = '.$_GET["rid"].';'; } else { echo 'var getRid = "";'; } ?>
			<?php if(isset($_GET["sub-rid"])) { echo 'var getSubRid = '.$_GET["sub-rid"].';'; } else { echo 'var getSubRid = "";'; } ?>
			
			var tableName = $(this).attr('data-click');
			
            var deleteRow = new Array();
            $("[id='delete']:checked").each(function ()
            {
                deleteRow.push(this.value);
            });
			
            if(deleteRow.length>0 && confirm("Are you sure you want to permanently delete the selected rows?"))
            {
                <?php
				$ajax_url_request = ''; 
				if(file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/cms/layouts/table/ajax/'.$_SESSION['admin_js_name'].'.php')) 
				{
					$ajax_url_request = '/'.$_SESSION['admin_directory'].'/cms/layouts/table/ajax/'.$_SESSION['admin_js_name'].'.php';
				}
				elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/commerce/layouts/table/ajax/'.$_SESSION['admin_js_name'].'.php')) 
				{
					$ajax_url_request = '/'.$_SESSION['admin_directory'].'/commerce/layouts/table/ajax/'.$_SESSION['admin_js_name'].'.php';
				}
				elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/erp/layouts/table/ajax/'.$_SESSION['admin_js_name'].'.php')) 
				{
					$ajax_url_request = '/'.$_SESSION['admin_directory'].'/erp/layouts/table/ajax/'.$_SESSION['admin_js_name'].'.php';
				}
				elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/ai/layouts/table/ajax/'.$_SESSION['admin_js_name'].'.php')) 
				{
					$ajax_url_request = '/'.$_SESSION['admin_directory'].'/ai/layouts/table/ajax/'.$_SESSION['admin_js_name'].'.php';
				}
				?>
				<?php if(!empty($ajax_url_request)) { ?>
				$(".pending-ajax-inner-container span").html("Updating... Hang tight.")
                $("body").addClass("body-pending-ajax");
                $(".pending-ajax").show();
				
                $.post("<?php echo $ajax_url_request; ?>",{getRid:getRid,getSubRid:getSubRid,deleteRow:deleteRow,tableName:tableName,type:'<?php echo $_SESSION['admin_js_name']; ?>'},
                function(data)
                {
                    if(data == 1)
                    {
                        location.reload();
                        $(".deleteCheckBox").attr('checked', false); 
                    }
                    else
                    {
                        alert(data);
                        location.reload();
                    }
                });
				<?php } ?>
            }
            else if(deleteRow.length==0)
            {
                alert('Please select at least 1 row to delete.');
            }
        });
    });
    </script>
<?php } ?>
