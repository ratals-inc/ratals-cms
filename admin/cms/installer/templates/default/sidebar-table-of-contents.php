<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(in_array('sidebar-table-of-contents.php', $data_array['active_template_includes']) && !empty($data_array['table_of_contents'])) { ?>
<script nonce="<?php echo NONCE; ?>">
//Scroll to hash name
$(document).ready(function() {
    $('.table-of-contents ul li a').click(function(e) {
        e.preventDefault();

        var hash = $(this).attr('href');
        var hashTarget = $(hash);
		
        if (hashTarget.length) {
            $('html').scrollTop(hashTarget.offset().top - 60);
        }
    });
});

//Highlight link hash name
$(window).scroll(function(){
    var scrollTop = $(document).scrollTop();
    var anchors = $('body').find('.contents-section');
    
    for (var i = 0; i < anchors.length; i++)
	{
        if (scrollTop > $(anchors[i]).offset().top - 61 && scrollTop)
		{
            $('.table-of-contents ul li a').removeClass('active');
			$('.table-of-contents ul li a[href="#' + $(anchors[i]).attr('id') + '"]').addClass('active');
        }
    }
});
</script>
<div class="box sticky">
    <div class="box-inner"><?php echo $data_array['table_of_contents']; ?></div>
</div>
<?php } ?>
