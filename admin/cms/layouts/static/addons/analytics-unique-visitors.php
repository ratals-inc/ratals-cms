<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/addons/analytics-unique-visitors.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/addons/analytics-unique-visitors.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'analytics_unique_visitors')
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
                <li><span>Counts by URL:</span> <input name="counts_by_url" value="yes" class="source" type="checkbox"<?php if(isset($_GET['counts_by_url'])) { echo ' checked'; } ?> /></li>
                <li><button type="submit" class="submit-button" />Submit</button></li>
            </ul>
            </form>
            </div>
        </div>
        <?php
        if(!isset($_GET['counts_by_url']))
		{
		?>
        <div class="table-overfollow fixed-scrollbar">
            <div class="table">
                <!-- Start Table Header Row -->
                <ul class="table-row-header">
                    <li class="table-cell-header analytics table-edit">#</li>
                    <li class="table-cell-header analytics no-sorting">Click Path</li>
                    <li class="table-cell-header analytics no-sorting">Conversion Data</li>
                </ul>
                <!-- End Table Header Row -->
                <!-- Start Table Results Row -->
                <?php
				
				//echo '<pre>'; print_r($analytics_unique_visitors); echo '</pre>';
				if(!isset($_GET['counts_by_url']) && !empty($analytics_unique_visitors))
				{
					$row_counter = 1;
					foreach($analytics_unique_visitors as $key => $analytics_unique_visitor)
					{
						$conversion_data = '';
						$conversion_counter = 1;
						unset($referer_url);
						
						$analytics_unique_visitor_reversed = array_reverse($analytics_unique_visitor);
				?>
                <div class="table-row-results-group" id="sortrows">
                    <ul class="table-row-results">
                        <li class="table-cell-results table-edit"><?php echo $row_counter; $row_counter ++; ?></li>
                        <li class="table-cell-results referer-url">
                        <div class="referer-bottom-margin"><strong>Total Pageviews:</strong> <?php echo count($analytics_unique_visitor_reversed); ?></div>
                            <ol>
                            <?php
							$time_difference = 0;
							$last_timestamp = 0;
							$pageview_counter = 0;
							foreach($analytics_unique_visitor_reversed as $unique_visitor)
							{
								$pageview_timestamp = utcToUserTimeZone($unique_visitor['created_date'], 'M. d, Y - h:i:s A');
								
								$time_difference = strtotime($unique_visitor['created_date']) - strtotime($last_timestamp);
								$last_timestamp = $unique_visitor['created_date'];
								
								if($pageview_counter == 0)
								{
									$is_returning = 'First Visit - ';
								}
								elseif($pageview_counter > 1 && $time_difference > 1800)
								{
									$is_returning = 'Returning Visit - ';
								}
								$last_timestamp = $unique_visitor['created_date'];
                            ?>
								<?php 
                                if(!isset($referer_url) || $referer_url != $unique_visitor['referer_url'])
                                {
                                    $referer_url = $unique_visitor['referer_url'];
									
									if(!empty($referer_url)) 
									{
										echo '</ol><div class="referer-bottom-margin"><strong>'.$is_returning.'Referrer URL:</strong> '.$referer_url.'</div><ol>';
										echo '<li>'.$pageview_timestamp.' - '.$unique_visitor['pageview_url'].'</li>';
									}
									else 
									{
										echo '</ol><div class="referer-bottom-margin"><strong>'.$is_returning.'Referrer URL:</strong> Direct</div><ol>'; 
										echo '<li>'.$pageview_timestamp.' - '.$unique_visitor['pageview_url'].'</li>';
									}
                                }
								elseif(!empty($last_timestamp) && $time_difference > 1800)
								{
									echo '</ol><div class="referer-bottom-margin"><strong>'.$is_returning.'Referrer URL:</strong> Direct</div><ol>';
									echo '<li>'.$pageview_timestamp.' - '.$unique_visitor['pageview_url'].'</li>';
								}
								else
								{
									echo '<li>'.$pageview_timestamp.' - '.$unique_visitor['pageview_url'].'</li>';
								}
                                ?>
                            <?php
								if($unique_visitor['total_order_amount'] > 0)
								{
									$conversion_data .= '<div class="conversion-data-wrap">';
									$conversion_data .= '<div class="conversion-count"><strong>Conversion #: '.$conversion_counter.'</strong><div class="conversion-timestamp">On: '.$pageview_timestamp.'</div></div>';
									$conversion_data .= '<div class="conversion-data">Type: Order</div>';
									$conversion_data .= '<div class="conversion-data">Products: '.currencyFormatWithSymbol($unique_visitor['total_product_amount'] ?? 0.00).'</div>';
									$conversion_data .= '<div class="conversion-data">Shipping: '.currencyFormatWithSymbol($unique_visitor['total_shipping_amount'] ?? 0.00).'</div>';
									$conversion_data .= '<div class="conversion-data">Tax: '.currencyFormatWithSymbol($unique_visitor['total_tax_amount'] ?? 0.00).'</div>';
									$conversion_data .= '<div class="conversion-data">Discount: -'.currencyFormatWithSymbol($unique_visitor['total_coupon_discount_amount'] ?? 0.00).'</div>';
									$conversion_data .= '<div class="conversion-data">Total: '.currencyFormatWithSymbol($unique_visitor['total_order_amount'] ?? 0.00).'</div>';
									$conversion_data .= '<div class="conversion-data">Cost: '.currencyFormatWithSymbol($unique_visitor['total_order_landed_cost_amount'] ?? 0.00).'</div>';
									$conversion_data .= '<div class="conversion-data">Profit: '.currencyFormatWithSymbol(($unique_visitor['total_order_amount'] - $unique_visitor['total_order_landed_cost_amount']) ?? 0.00).'</div>';
									$conversion_data .= '<div class="conversion-data">Margin: '.((($unique_visitor['total_order_amount'] - $unique_visitor['total_order_landed_cost_amount']) / $unique_visitor['total_order_amount']) * 100).'%</div>';
									$conversion_data .= '</div>';
									
									$conversion_counter ++;
								}
								
								if($unique_visitor['has_form_conversion'] > 0)
								{
									$conversion_data .= '<div class="conversion-data-wrap">';
									$conversion_data .= '<div class="conversion-count"><strong>Conversion #: '.$conversion_counter.'</strong><div class="conversion-timestamp">On: '.$pageview_timestamp.'</div></div>';
									$conversion_data .= '<div class="conversion-data">Type: Lead Form</div>';
									$conversion_data .= '<div class="conversion-data">Conversion Value: '.currencyFormatWithSymbol($unique_visitor['form_conversion_value'] ?? 0.00).'</div>';
									$conversion_data .= '</div>';
									
									$conversion_counter ++;
								}
								
								$pageview_counter ++;
                            }
                            ?> 
                            </ol>
                        </li>
                        <li class="table-cell-results conversion-data-column"><?php echo $conversion_data; ?></li>
                    </ul>
                </div>
                <?php
					}
				}
				?>
                <!-- End Table Results Row -->
            </div>
        </div>
        <?php 
		}
		//URLs by count.
		elseif(isset($_GET['counts_by_url']))
		{
		?>
        <div class="table-overfollow fixed-scrollbar">
            <div class="table">
                <!-- Start Table Header Row -->
                <ul class="table-row-header">
                    <li class="table-cell-header analytics table-edit">#</li>
                    <li class="table-cell-header analytics no-sorting">Uniques</li>
                    <li class="table-cell-header analytics no-sorting">URL</li>
                    <li class="table-cell-header analytics no-sorting">Referrer Source</li>
                    <li class="table-cell-header analytics no-sorting">Bounces</li>
                    <li class="table-cell-header analytics no-sorting">Bounce Rate</li>
                </ul>
                <!-- End Table Header Row -->
                <?php 
				if(!empty($analytics_unique_visitor_counts))
				{
					$unique_pageviews_counter = 1;
					foreach($analytics_unique_visitor_counts as $analytics_unique_visitor_count)
					{
						$referer_source = $analytics_unique_visitor_count['referer_source'];
						if(empty($referer_source))
						{
							$referer_source = 'Direct';
						}
				?>
                <!-- Start Table Results Row -->
                <div class="table-row-results-group" id="sortrows">
                    <ul class="table-row-results">
                        <li class="table-cell-results table-edit"><?php echo $unique_pageviews_counter; ?></li>
                        <li class="table-cell-results referer-url"><?php echo $analytics_unique_visitor_count['unique_pageviews'] ;?></li>
                        <li class="table-cell-results referer-url"><?php echo $analytics_unique_visitor_count['pageview_url'] ;?></li>
                        <li class="table-cell-results referer-url"><?php echo $referer_source; ?></li>
                        <li class="table-cell-results"><?php echo $analytics_unique_visitor_count['bounces'] ;?></li>
                        <li class="table-cell-results"><?php echo $analytics_unique_visitor_count['bounce_rate'] ;?>%</li>
                    </ul>
                </div>
                <!-- End Table Results Row -->
                <?php 
					$unique_pageviews_counter ++;
					}
				}
				?>
            </div>
        </div>
        <?php 
		}
		?>
    <?php }
}