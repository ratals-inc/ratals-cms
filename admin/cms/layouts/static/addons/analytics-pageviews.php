<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/addons/analytics-pageviews.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/addons/analytics-pageviews.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'analytics_pageviews')
	{
	?>
		<script nonce="<?php echo NONCE; ?>">
        //Start show datepicker
        $(function() 
        {
            $( "#from_date" ).datepicker({dateFormat: "yy-mm-dd"});
            $( "#to_date" ).datepicker({dateFormat: "yy-mm-dd"});
            $( "#compare_from_date" ).datepicker({dateFormat: "yy-mm-dd"});
            $( "#compare_to_date" ).datepicker({dateFormat: "yy-mm-dd"});
        });
        //End show datepicker
        //Start pending ajax overly
		let analyticsTimeout = null;
        $(document).ready(function()
        {
            $(".submit-button").click(function()
            {
                //Sleep pending ajax overly after 1 second. This makes it only display if results are taking time to be analyzed.
                analyticsTimeout =  setTimeout(function ()
                {
                    $(".pending-ajax-inner-container span").html("Hang tight... Processing real time analytics takes approximately 1 second for every 100,000 pageviews analyzed.");
                    $("body").addClass("body-pending-ajax");
                    $(".pending-ajax").show();
                }, 1000);
            });
        });
		//Detect back/forward navigation (bfcache)
		window.addEventListener("pageshow", function(event) {
			if (event.persisted) {
				clearTimeout(analyticsTimeout);
				analyticsTimeout = null;
				$("body").removeClass("body-pending-ajax");
				$(".pending-ajax").hide();
			}
		});
		//End pending ajax overly
        </script>
        <div class="results-wrapper">
            <div class="results">
            <form method="get">
            <ul>
                <li><span>Date Range:</span> 
                <input name="from_date" id="from_date" value="<?php if(isset($_GET['from_date']) && !empty($_GET['from_date'])) { echo $_GET['from_date']; } else { echo $todays_date; } ?>" class="date-range" placeholder="From" autocomplete="off" type="text" /> 
                <input name="to_date" id="to_date" value="<?php if(isset($_GET['to_date']) && !empty($_GET['to_date'])) { echo $_GET['to_date']; } else { echo $todays_date; } ?>" class="date-range" placeholder="To" autocomplete="off" type="text" /></li>
                <li><button type="submit" class="submit-button" />Submit</button></li>
            </ul>
            </form>
            </div>
        </div>
        <div class="table-overfollow fixed-scrollbar">
            <div class="table">
                <!-- Start Table Header Row -->
                <ul class="table-row-header">
                    <li class="table-cell-header analytics table-edit">#</li>
                    <li class="table-cell-header analytics no-sorting">Pageviews</li>
                    <li class="table-cell-header analytics no-sorting">URL</li>
                </ul>
                <!-- End Table Header Row -->
                <!-- Start Table Results Row -->
                <?php
                if(!empty($analytics_pageviews))
				{
					$pageviews_counter = 1;
					foreach($analytics_pageviews as $analytics_pageview)
					{
				?>
                <div class="table-row-results-group" id="sortrows">
                    <ul class="table-row-results">
                        <li class="table-cell-results table-edit"><?php echo $pageviews_counter; ?></li>
                        <li class="table-cell-results referer-url"><?php echo $analytics_pageview['pageviews']; ?></li>
                        <li class="table-cell-results referer-url"><?php echo $analytics_pageview['pageview_url']; ?></li>
                    </ul>
                </div>
                <?php	
					$pageviews_counter ++;
					}
				}
				?>
                <!-- End Table Results Row -->
            </div>
        </div>
    <?php }
}