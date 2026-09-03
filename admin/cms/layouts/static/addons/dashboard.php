<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/addons/dashboard.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/addons/dashboard.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'dashboard')
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
		<li><span>Interval:</span> <select name="interval">
		<option value="day"<?php if(isset($_GET['interval']) && $_GET['interval'] == 'day') { echo ' selected'; } ?>>Day</option>
		<option value="month"<?php if(isset($_GET['interval']) && $_GET['interval'] == 'month') { echo ' selected'; } ?>>Month</option>
		<option value="year"<?php if(isset($_GET['interval']) && $_GET['interval'] == 'year') { echo ' selected'; } ?>>Year</option>
		</select></li>
		<li><span>Date Range:</span> 
		<input name="from_date" id="from_date" value="<?php if(isset($_GET['from_date']) && !empty($_GET['from_date'])) { echo $_GET['from_date']; } else { echo $todays_date; } ?>" class="date-range" placeholder="From" autocomplete="off" type="text" /> 
		<input name="to_date" id="to_date" value="<?php if(isset($_GET['to_date']) && !empty($_GET['to_date'])) { echo $_GET['to_date']; } else { echo $todays_date; } ?>" class="date-range" placeholder="To" autocomplete="off" type="text" /></li>
		<li><span>Source:</span> <input name="source" value="yes" class="source" type="checkbox"<?php if(isset($_GET['source'])) { echo ' checked'; } ?> /></li>
		<li><button type="submit" class="submit-button" />Submit</button></li>
		</ul>
		</form>
		</div>
        <?php if($commerce_installed) { ?>
		<div class="results-buttons lifetime-sales-amount">Lifetime Sales: <?php echo currencyFormatWithSymbol($sql_lifetime_order_amount['lifetime_order_amount'] ?? 0.00); ?></div>
        <?php } ?>
	</div>
    <?php if(!$commerce_installed) { ?>
    <style nonce="<?php echo NONCE; ?>">
	.bashboard-boxes { --n: 2; }
	</style>
    <?php } ?>
    <?php if(!empty($add_mysql_timezones)) echo $add_mysql_timezones; ?>
	<ul class="bashboard-boxes">
		<?php if(isset($_SESSION['erp_mode']) && $_SESSION['erp_mode'] == 'Enabled') { ?>
		<li class="full-width overviews">Financial Overview</li>
        <li class="boxes">
			<div class="bashboard-box">
				<div class="headline">Cash & Liquidity</div>
				<div class="box numbers-layout">
                    <div class="numbers">
                        <span class="label">Total Bank Balances</span> <span class="value"><a href="">$0.00</a></span>
                    </div>
                    <div class="numbers">
                        <span class="label">Working Capital</span> <span class="value"><a href="">$0.00</a></span>
                    </div>
                    <div class="numbers">
                        <span class="label">Current Ratio</span> <span class="value">0.00x</span>
                    </div>
                    <div class="numbers">
                        <span class="label">Quick Ratio</span> <span class="value">0.00x</span>
                    </div>
                    <div class="numbers">
                        <span class="label">Burn Rate</span> <span class="value">0.00 Months</span>
                    </div>
				</div>
			</div>
		</li>
        <li class="boxes">
			<div class="bashboard-box">
				<div class="headline">Sales Pipeline</div>
				<div class="box numbers-layout">
                    <div class="numbers">
                        <span class="label">Quoted Total</span> <span class="value"><a href="">$0.00</a></span>
                    </div>
                    <div class="numbers">
                        <span class="label">Booked Orders</span> <span class="value"><a href="">$0.00</a></span>
                    </div>
                    <div class="numbers">
                        <span class="label">Invoiced Revenue</span> <span class="value"><a href="">$0.00</a></span>
                    </div>
                    <div class="numbers">
                        <span class="label">Quotes-to-Orders</span> <span class="value">0.00%</span>
                    </div>
                    <div class="numbers">
                        <span class="label">Average Deal Size</span> <span class="value">$0.00</span>
                    </div>
				</div>
			</div>
		</li>
        <li class="boxes">
			<div class="bashboard-box">
				<div class="headline">Accounts Receivable</div>
				<div class="box numbers-layout">
                    <div class="numbers">
                        <span class="label">Total A/R</span> <span class="value"><a href="">$0.00</a></span>
                    </div>
                    <div class="numbers">
                        <span class="label">Past Due AR</span> <span class="value"><a href="">$0.00</a></span>
                    </div>
                    <div class="numbers">
                        <span class="label">Invoices Past Due</span> <span class="value"><a href="">0</a></span>
                    </div>
                    <div class="numbers">
                        <span class="label">AR Aging Buckets</span> <span class="value"><a href="">0-30</a> / <a href="">31-60</a> / <a href="">61-90</a></span>
                    </div>
                    <div class="numbers">
                        <span class="label">Average Days to Collect</span> <span class="value">0 days</span>
                    </div>
				</div>
			</div>
		</li>
        <li class="boxes">
			<div class="bashboard-box">
				<div class="headline">Accounts Payable</div>
				<div class="box numbers-layout">
                    <div class="numbers">
                        <span class="label">Operating Liabilities</span> <span class="value"><a href="">$0.00</a></span>
                    </div>
                    <div class="numbers">
                        <span class="label">Upcoming Large Expenses</span> <span class="value"><a href="">$0.00</a></span>
                    </div>
                    <div class="numbers">
                        <span class="label">Days Payable Overdue</span> <span class="value"><a href="">$0.00</a></span>
                    </div>
                    <div class="numbers">
                        <span class="label">Payable Bills Overdue</span> <span class="value"><a href="">0</a></span>
                    </div>
                    <div class="numbers">
                        <span class="label">Average Days to Pay Vendors</span> <span class="value">0 days</span>
                    </div>
				</div>
			</div>
		</li>
        <li class="boxes">
			<div class="bashboard-box">
				<div class="headline">Profitability Snapshot</div>
				<div class="box numbers-layout">
                    <div class="numbers">
                        <span class="label">Gross Profit Margin</span> <span class="value">0.00%</span>
                    </div>
                    <div class="numbers">
                        <span class="label">Net Profit Margin</span> <span class="value">0.00%</span>
                    </div>
                    <div class="numbers">
                        <span class="label">Operating Margin</span> <span class="value">0.00%</span>
                    </div>
                    <div class="numbers">
                        <span class="label">EBITDA</span> <span class="value">0.00%</span>
                    </div>
                    <div class="numbers">
                        <span class="label">Net Income</span> <span class="value"><a href="">$0.00</a></span>
                    </div>
				</div>
			</div>
		</li>
        <li class="boxes">
			<div class="bashboard-box">
				<div class="headline">General KPIs</div>
				<div class="box numbers-layout">
                    <div class="numbers">
                        <span class="label">Inventory Value On Hand</span> <span class="value"><a href="">$0.00</a></span>
                    </div>
                    <div class="numbers">
                        <span class="label">Inventory Turnover</span> <span class="value">0.00x</span>
                    </div>
                    <div class="numbers">
                        <span class="label">New Customer Ratio</span> <span class="value">0.00%</span>
                    </div>
                    <div class="numbers">
                        <span class="label">Return Customer Ratio</span> <span class="value">0.00%</span>
                    </div>
                    <div class="numbers">
                        <span class="label">Pacing Year-End</span> <span class="value">$0.00</span>
                    </div>
				</div>
			</div>
		</li>
        <?php } ?>
        <?php
        if(empty($_SESSION['user_admin_permissions_set_ids']) 
		|| in_array('analytics/pageviews', $_SESSION['user_permissions_admin_page_system_codes']) !== false 
		|| in_array('analytics/unique-visitors', $_SESSION['user_permissions_admin_page_system_codes']) !== false)
		{
		?>
        <li class="full-width overviews">Analytics Overview</li>
        <li class="full-width">
			<div class="table-overfollow fixed-scrollbar">
				<div class="table">
					<!-- Start Table Header Row -->
					<ul class="table-row-header">
						<li class="table-cell-header analytics table-edit">Dates</li>
						<?php if(isset($_GET['source'])) { echo '<li class="table-cell-header analytics table-edit">Source</li>'; } ?>
						<li class="table-cell-header analytics table-edit">Unique Visitors</li>
						<li class="table-cell-header analytics table-edit">Pageviews</li>
						<?php if($commerce_installed) { ?>
                        <li class="table-cell-header analytics table-edit">Orders</li>
						<li class="table-cell-header analytics table-edit">Conversion Rate</li>
						<li class="table-cell-header analytics table-edit">Products</li>
						<li class="table-cell-header analytics table-edit">Shipping</li>
						<li class="table-cell-header analytics table-edit">Tax</li>
						<li class="table-cell-header analytics table-edit">Discounts</li>
						<li class="table-cell-header analytics table-edit">Order Total<div class="font-size-8px">Tax Not Inclided</div></li>
						<li class="table-cell-header analytics table-edit">Order Landed Cost</li>
						<li class="table-cell-header analytics table-edit">Order Profit / Margin</li>
						<li class="table-cell-header analytics table-edit">Average Order</li>
						<li class="table-cell-header analytics table-edit">Abandonment Carts<div class="font-size-8px">With Contact Info</div></li>
                        <?php } ?>
						<li class="table-cell-header analytics table-edit">Form Conversions</li>
						<li class="table-cell-header analytics table-edit">Form Conversion Value</li>
					</ul>
					<!-- End Table Header Row -->
					<!-- Start Table Results Row -->
					<div class="table-row-results-group" id="sortrows">
						<?php 
						$total_unique_visitors = 0;
						$total_pageviews = 0;
						$total_orders = 0;
						$total_conversion_rate = 0.00;
						$total_products = 0;
						$total_shipping = 0.00;
						$total_tax = 0.00;
						$total_discounts = 0.00;
						$total_average_order = 0.00;
						$total_order_total = 0.00;
						$total_order_cost = 0.00;
						$total_order_profit_amount = 0.00;
						$total_order_profit_percentage = 0.00;
						$total_abandonment_carts = 0;
						$total_form_conversions = 0;
						$total_form_conversion_values = 0;
						
						if(!empty($sql_analytics)) 
						{
							$results_counter = 0;
							$results_count =  count($sql_analytics);
							$interval_counter = 0;
							
							foreach($sql_analytics as $date => $sql_analytic)
							{
								$subtotal_unique_visitors = 0;
								$subtotal_pageviews = 0;
								$subtotal_orders = 0;
								$subtotal_conversion_rate = 0.00;
								$subtotal_products = 0;
								$subtotal_shipping = 0.00;
								$subtotal_tax = 0.00;
								$subtotal_discounts = 0.00;
								$subtotal_average_order = 0.00;
								$subtotal_order_total = 0.00;
								$subtotal_order_cost = 0.00;
								$subtotal_order_profit_amount = 0.00;
								$subtotal_order_profit_percentage = 0.00;
								$subtotal_abandonment_carts = 0;
								$subtotal_form_conversions = 0;
								$subtotal_form_conversion_values = 0;
								
								$results_counter ++;
								foreach($sql_analytic as $source => $analytics)
								{
									$subtotal_unique_visitors += $analytics['unique_visitors'];
									$subtotal_pageviews += $analytics['pageviews'];
									$subtotal_orders += $analytics['orders'];
									$subtotal_products += $analytics['total_product_amount'];
									$subtotal_shipping += $analytics['total_shipping_amount'];
									$subtotal_tax += $analytics['total_tax_amount'];
									$subtotal_discounts += $analytics['total_coupon_discount_amount'];
									$subtotal_order_total += $analytics['order_total'];
									$subtotal_order_cost += $analytics['landed_cost'];
									$subtotal_abandonment_carts += $analytics['abandonment_cart_leads'];
									$subtotal_form_conversions += $analytics['form_conversions'];
									$subtotal_form_conversion_values += $analytics['form_conversion_values'];
									
									$total_unique_visitors += $analytics['unique_visitors'];
									$total_pageviews += $analytics['pageviews'];
									$total_orders += $analytics['orders'];
									$total_products += $analytics['total_product_amount'];
									$total_shipping += $analytics['total_shipping_amount'];
									$total_tax += $analytics['total_tax_amount'];
									$total_discounts += $analytics['total_coupon_discount_amount'];
									$total_order_total += $analytics['order_total'];
									$total_order_cost += $analytics['landed_cost'];
									$total_abandonment_carts += $analytics['abandonment_cart_leads'];
									$total_form_conversions += $analytics['form_conversions'];
									$total_form_conversion_values += $analytics['form_conversion_values'];
									
									if(empty($source))
									{
										$source = 'direct';
									}
									
									$search_date_range = '';
									if($interval == 'day')
									{
										$date_formated = date('M d, Y', strtotime($date));
										
										$search_date_range = 'textfield-created-date-start-range='.$date.'&textfield-created-date-end-range='.$date.'&';
									}
									elseif($interval == 'month')
									{
										$date_formated = date('M Y', strtotime($date));
										
										if(strtotime($from_date_date_only) <= strtotime($date.'-1'.' - '.$interval_counter.' month'))
										{
											$from_date_formatted = date('Y-m-d', strtotime($date.'-1'.' - '.$interval_counter.' month'));
										}
										else
										{
											$from_date_formatted = date($from_date_date_only);
										}
										
										if(strtotime($to_date_date_only) >= strtotime(date('Y-m-d', strtotime(date('Y-m-d', strtotime($date.'-1 last day of this month')).' + '.$interval_counter.' month'))))
										{
											$to_date_formatted = date('Y-m-d', strtotime(date('Y-m-d', strtotime($date.'-1 last day of this month')).' + '.$interval_counter.' month'));
	
										}
										else
										{
											$to_date_formatted = date($to_date_date_only);
										}
										
										$search_date_range = 'textfield-created-date-start-range='.$from_date_formatted.'&textfield-created-date-end-range='.$to_date_formatted.'&';
									}
									elseif($interval == 'year')
									{
										//$search_date = date('Y', strtotime($date));
										$date_formated = $date;
										
										if(strtotime($from_date_date_only) <= strtotime($date.'-01-01'.' - '.$interval_counter.' year'))
										{
											$from_date_formatted = date('Y-m-d', strtotime($date.'-01-01'.' - '.$interval_counter.' year'));
										}
										else
										{
											$from_date_formatted = date($from_date_date_only);
										}
										
										if(strtotime($to_date_date_only) >= strtotime(date('Y-m-d', strtotime(date('Y-m-d', strtotime($date.'-12-31')).' + '.$interval_counter.' year'))))
										{
											$to_date_formatted = date('Y-m-d', strtotime(date('Y-m-d', strtotime($date.'-12-31')).' + '.$interval_counter.' year'));
	
										}
										else
										{
											$to_date_formatted = date($to_date_date_only);
										}
										
										$search_date_range = 'textfield-created-date-start-range='.$from_date_formatted.'&textfield-created-date-end-range='.$to_date_formatted.'&';
									}
									
									$abandonment_cart_leads_data = 0;
									if(!empty($analytics['abandonment_cart_leads']) && isset($_GET['source']))
									{
										$abandonment_cart_leads_data = '<a href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/customers/abandonment-cart-leads/?'.$search_date_range.'textfield-referer-source='.$source.'&dropdown-contact-info-available=Yes">'.$analytics['abandonment_cart_leads'].'</a>';
									}
									elseif(!empty($analytics['abandonment_cart_leads']))
									{
										$abandonment_cart_leads_data = '<a href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/customers/abandonment-cart-leads/?'.$search_date_range.'dropdown-contact-info-available=Yes">'.$analytics['abandonment_cart_leads'].'</a>';
									}
									
									$form_conversions_data = 0;
									if(!empty($analytics['form_conversions']) && isset($_GET['source']))
									{
										$form_conversions_data = '<a href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/customers/leads/?'.$search_date_range.'textfield-referer-source='.$source.'">'.$analytics['form_conversions'].'</a>';
									}
									elseif(!empty($analytics['form_conversions']))
									{
										$form_conversions_data = '<a href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/customers/leads/?'.trim($search_date_range ?? '', '&').'">'.$analytics['form_conversions'].'</a>';
									}
								?>
									<ul class="table-row-results">
										<li class="table-cell-results table-edit"><?php echo $date_formated; ?></li>
										<?php if(isset($_GET['source'])) { echo '<li class="table-cell-results table-edit">'.$source.'</li>'; } ?>
										<li class="table-cell-results table-edit"><?php echo number_format($analytics['unique_visitors']); ?></li>
										<li class="table-cell-results table-edit"><?php echo number_format($analytics['pageviews']); ?></li>
                                        <?php if($commerce_installed) { ?>
										<li class="table-cell-results table-edit"><?php echo number_format($analytics['orders']); ?></li>
										<li class="table-cell-results table-edit"><?php echo number_format($analytics['unique_visitors_conversion_rate'] ?? '0.00','2').'%'; ?></li>
										<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($analytics['total_product_amount'] ?? 0.00); ?></li>
										<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($analytics['total_shipping_amount'] ?? 0.00); ?></li>
										<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($analytics['total_tax_amount'] ?? 0.00); ?></li>
										<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($analytics['total_coupon_discount_amount'] ?? 0.00); ?></li>
										<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($analytics['order_total'] ?? 0.00); ?></li>
										<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($analytics['landed_cost'] ?? 0.00); ?></li>
										<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($analytics['landed_cost_profit_amount'] ?? 0.00); ?> / <?php echo number_format($analytics['landed_cost_profit_percentage'] ?? '0.00','2').'%'; ?></li>
										<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($analytics['average_order_amount'] ?? 0.00); ?></li>
										<li class="table-cell-results table-edit"><?php echo $abandonment_cart_leads_data; ?></li>
                                        <?php } ?>
										<li class="table-cell-results table-edit"><?php echo $form_conversions_data; ?></li>
										<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($analytics['form_conversion_values'] ?? 0.00); ?></li>
									</ul>
								<?php }  ?>
								<?php 
								//Display sub totals if source is on.
								if(isset($_GET['source'])) { ?>
									<?php
									if(!empty($subtotal_orders) && !empty($subtotal_unique_visitors))
									{
										$subtotal_conversion_rate = ($subtotal_orders / $subtotal_unique_visitors) * 100;
									}
									
									if(!empty($subtotal_order_total) && !empty($subtotal_orders))
									{
										$subtotal_average_order = ($subtotal_order_total / $subtotal_orders);
									}
									
									if(!empty($subtotal_order_total) && !empty($subtotal_order_cost))
									{
										$subtotal_order_profit_amount = ($subtotal_order_total - $subtotal_order_cost);
									}
									
									if(!empty($subtotal_order_total) && !empty($subtotal_order_cost))
									{
										$subtotal_order_profit_percentage = (($subtotal_order_total - $subtotal_order_cost) / $subtotal_order_total) * 100;
									}
									?>
									<ul class="table-row-results subtotal-results">
										<li class="table-cell-results table-edit">Subtotals</li>
										<?php if(isset($_GET['source'])) { echo '<li class="table-cell-results table-edit"></li>'; } ?>
										<li class="table-cell-results table-edit"><?php echo number_format($subtotal_unique_visitors); ?></li>
										<li class="table-cell-results table-edit"><?php echo number_format($subtotal_pageviews); ?></li>
                                        <?php if($commerce_installed) { ?>
										<li class="table-cell-results table-edit"><?php echo number_format($subtotal_orders); ?></li>
										<li class="table-cell-results table-edit"><?php echo number_format($subtotal_conversion_rate, '2'); ?>%</li>
										<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($subtotal_products); ?></li>
										<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($subtotal_shipping); ?></li>
										<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($subtotal_tax); ?></li>
										<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($subtotal_discounts); ?></li>
										<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($subtotal_order_total); ?></li>
										<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($subtotal_order_cost); ?></li>
										<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($subtotal_order_profit_amount); ?> / <?php echo number_format($subtotal_order_profit_percentage, '2'); ?>%</li>
										<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($subtotal_average_order); ?></li>
										<li class="table-cell-results table-edit"><?php echo $subtotal_abandonment_carts; ?></li>
                                        <?php } ?>
										<li class="table-cell-results table-edit"><?php echo $subtotal_form_conversions; ?></li>
										<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($subtotal_form_conversion_values ?? 0.00); ?></li>
									</ul>
								<?php }  ?>
								<?php 
								if($results_counter != $results_count || isset($_GET['source'])) 
								{ 
								?>
								<ul class="table-row-results spacer-row">    
									<li class="table-cell-results table-edit no-right-border"></li>
									<?php if(isset($_GET['source'])) { echo '<li class="table-cell-results table-edit no-right-border"></li>'; } ?>
									<li class="table-cell-results table-edit no-right-border"></li>
									<li class="table-cell-results table-edit no-right-border"></li>
                                    <?php if($commerce_installed) { ?>
									<li class="table-cell-results table-edit no-right-border"></li>
									<li class="table-cell-results table-edit no-right-border"></li>
									<li class="table-cell-results table-edit no-right-border"></li>
									<li class="table-cell-results table-edit no-right-border"></li>
									<li class="table-cell-results table-edit no-right-border"></li>
									<li class="table-cell-results table-edit no-right-border"></li>
									<li class="table-cell-results table-edit no-right-border"></li>
									<li class="table-cell-results table-edit no-right-border"></li>
									<li class="table-cell-results table-edit no-right-border"></li>
									<li class="table-cell-results table-edit no-right-border"></li>
									<li class="table-cell-results table-edit no-right-border"></li>
                                    <?php } ?>
									<li class="table-cell-results table-edit no-right-border"></li>
									<li class="table-cell-results table-edit"></li>
								</ul>
								<?php } ?>
							<?php 
							} 
							$interval_counter ++; 
							?>
						<?php } ?>
						<?php
						if(!empty($total_orders) && !empty($total_unique_visitors))
						{
							$total_conversion_rate = ($total_orders / $total_unique_visitors) * 100;
						}
						
						if(!empty($total_order_total) && !empty($total_orders))
						{
							$total_average_order = ($total_order_total / $total_orders);
						}
						
						if(!empty($total_order_total) && !empty($total_order_cost))
						{
							$total_order_profit_amount = ($total_order_total - $total_order_cost);
						}
						
						if(!empty($total_order_total) && !empty($total_order_cost))
						{
							$total_order_profit_percentage = (($total_order_total - $total_order_cost) / $total_order_total) * 100;
						}
						?>
						<ul class="table-row-results total-results">
							<li class="table-cell-results table-edit">Totals</li>
							<?php if(isset($_GET['source'])) { echo '<li class="table-cell-results table-edit"></li>'; } ?>
							<li class="table-cell-results table-edit"><a href="<?php echo INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/analytics/unique-visitors/?from_date='.$from_date_date_only.'&to_date='.$to_date_date_only; ?>"><?php echo number_format($total_unique_visitors); ?></a></li>
							<li class="table-cell-results table-edit"><a href="<?php echo INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/analytics/pageviews/?from_date='.$from_date_date_only.'&to_date='.$to_date_date_only; ?>"><?php echo number_format($total_pageviews); ?></a></li>
                            <?php if($commerce_installed) { ?>
							<li class="table-cell-results table-edit"><?php echo number_format($total_orders); ?></li>
							<li class="table-cell-results table-edit"><?php echo number_format($total_conversion_rate, '2'); ?>%</li>
							<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($total_products); ?></li>
							<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($total_shipping); ?></li>
							<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($total_tax); ?></li>
							<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($total_discounts); ?></li>
							<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($total_order_total); ?></li>
							<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($total_order_cost); ?></li>
							<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($total_order_profit_amount); ?> / <?php echo number_format($total_order_profit_percentage, '2'); ?>%</li>
							<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($total_average_order); ?></li>
							<li class="table-cell-results table-edit"><?php echo $total_abandonment_carts; ?></li>
                            <?php } ?>
							<li class="table-cell-results table-edit"><?php echo $total_form_conversions; ?></li>
							<li class="table-cell-results table-edit"><?php echo currencyFormatWithSymbol($total_form_conversion_values ?? 0.00); ?></li>
						</ul>
					</div>
					<!-- End Table Results Row -->
				</div>
			</div>
		</li>
        <?php } ?>
        <li class="full-width overviews">Website Overview</li>
        <?php
        if(empty($_SESSION['user_admin_permissions_set_ids']) 
		|| in_array('website/interactions/posts-comments', $_SESSION['user_permissions_admin_page_system_codes']) !== false 
		|| in_array('website/interactions/posts-comments/edit', $_SESSION['user_permissions_admin_page_system_codes']) !== false)
		{
		?>
		<li class="boxes">
			<div class="bashboard-box">
				<div class="headline"><a href="<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/website/interactions/posts-comments/">Pending Blog Post Comments</a></div>
				<div class="box">
				<?php 
				if(!empty($sql_pending_posts_comments))
				{
					$posts_comments_conversion_rate = 0.00;
					if(!empty($sql_pending_posts_comments) && !empty($total_unique_visitors))
					{
						$posts_comments_conversion_rate = ($sql_pending_posts_comments / $total_unique_visitors) * 100;
					}
					
					echo 'Pending Post Comments: <a href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/website/interactions/posts-comments/?dropdown-status=2&textfield-created-date-start-range='.$from_date_date_only.'&textfield-created-date-end-range='.$to_date_date_only.'">'.$sql_pending_posts_comments.'</a><br>';
					echo 'Conversion Rate: '.number_format($posts_comments_conversion_rate, '2').'%';
				}
				else
				{
					echo 'No Results';
				}
				?>
				</div>
			</div>
		</li>
        <?php } ?>
        <?php
        if($commerce_installed && 
		(empty($_SESSION['user_admin_permissions_set_ids']) 
		|| in_array('website/products/reviews', $_SESSION['user_permissions_admin_page_system_codes']) !== false 
		|| in_array('website/interactions/reviews', $_SESSION['user_permissions_admin_page_system_codes']) !== false
		|| in_array('website/interactions/reviews/edit', $_SESSION['user_permissions_admin_page_system_codes']) !== false))
		{
		?>
		<li class="boxes">
			<div class="bashboard-box">
				<div class="headline"><a href="<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/website/interactions/reviews/">Pending Product Reviews</a></div>
				<div class="box">
				<?php 
				if(!empty($sql_pending_reviews))
				{
					$reviews_conversion_rate = 0.00;
					if(!empty($sql_pending_reviews) && !empty($total_unique_visitors))
					{
						$reviews_conversion_rate = ($sql_pending_reviews / $total_unique_visitors) * 100;
					}
					
					echo 'Pending Reviews: <a href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/website/interactions/reviews/?dropdown-status=2&textfield-created-date-start-range='.$from_date_date_only.'&textfield-created-date-end-range='.$to_date_date_only.'">'.$sql_pending_reviews.'</a><br>';
					echo 'Conversion Rate: '.number_format($reviews_conversion_rate, '2').'%';
				}
				else
				{
					echo 'No Results';
				}
				?>
				</div>
			</div>
		</li>
        <?php } ?>
        <?php
        if($commerce_installed && 
		(empty($_SESSION['user_admin_permissions_set_ids']) 
		|| in_array('website/interactions/questions-and-answers', $_SESSION['user_permissions_admin_page_system_codes']) !== false 
		|| in_array('website/interactions/questions-and-answers/edit', $_SESSION['user_permissions_admin_page_system_codes']) !== false
		|| in_array('website/products/q-and-a', $_SESSION['user_permissions_admin_page_system_codes']) !== false))
		{
		?>
		<li class="boxes">
			<div class="bashboard-box">
				<div class="headline"><a href="<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/website/interactions/questions-and-answers/">Pending Product Questions</a></div>
				<div class="box">
				<?php 
				if(!empty($sql_pending_q_and_a))
				{
					$q_and_a_conversion_rate = 0.00;
					if(!empty($sql_pending_q_and_a) && !empty($total_unique_visitors))
					{
						$q_and_a_conversion_rate = ($sql_pending_q_and_a / $total_unique_visitors) * 100;
					}
					
					echo 'Pending Q & A: <a href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/website/interactions/questions-and-answers/?dropdown-status=2&textfield-created-date-start-range='.$from_date_date_only.'&textfield-created-date-end-range='.$to_date_date_only.'">'.$sql_pending_q_and_a.'</a><br>';
					echo 'Conversion Rate: '.number_format($q_and_a_conversion_rate, '2').'%';
				}
				else
				{
					echo 'No Results';
				}
				?>
				</div>
			</div>
		</li>
        <?php } ?>
        <?php
        if($commerce_installed &&
		(empty($_SESSION['user_admin_permissions_set_ids']) 
		|| in_array('marketing/affiliates', $_SESSION['user_permissions_admin_page_system_codes']) !== false 
		|| in_array('marketing/affiliates/edit', $_SESSION['user_permissions_admin_page_system_codes']) !== false))
		{
		?>
        <li class="boxes">
			<div class="bashboard-box">
				<div class="headline"><a href="<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/marketing/affiliates/">Pending Affiliate Applications</a></div>
				<div class="box">
				<?php 
				if(!empty($sql_pending_affiliate_applications))
				{
					echo 'Pending Affiliate Applications: <a href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/marketing/affiliates/?dropdown-status=2">'.$sql_pending_affiliate_applications.'</a>';
				}
				else
				{
					echo 'No Results';
				}
				?>
				</div>
			</div>
		</li>
        <?php } ?>
        <?php
        if(empty($_SESSION['user_admin_permissions_set_ids']) 
		|| in_array('customers/junk-leads', $_SESSION['user_permissions_admin_page_system_codes']) !== false 
		|| in_array('customers/junk-leads/edit', $_SESSION['user_permissions_admin_page_system_codes']) !== false
		|| in_array('customers/leads', $_SESSION['user_permissions_admin_page_system_codes']) !== false
		|| in_array('customers/leads/edit', $_SESSION['user_permissions_admin_page_system_codes']) !== false)
		{
		?>
		<li class="boxes">
			<div class="bashboard-box">
				<div class="headline"><a href="<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/customers/leads/">Forms Submissions / Leads</a></div>
				<div class="box">
				<?php 
				if(!empty($sql_form_leads))
				{
					$lead_conversion_rate = 0.00;
					if(!empty($sql_form_leads) && !empty($total_unique_visitors))
					{
						$lead_conversion_rate = ($sql_form_leads / $total_unique_visitors) * 100;
					}
					
					echo 'Total Form Submissions: <a href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/customers/leads/?textfield-created-date-start-range='.$from_date_date_only.'&textfield-created-date-end-range='.$to_date_date_only.'">'.$sql_form_leads.'</a><br>';
					echo 'Conversion Rate: '.number_format($lead_conversion_rate, '2').'%';
				}
				else
				{
					echo 'No Results';
				}
				?>
				</div>
			</div>
		</li>
        <?php } ?>
        <?php
        if($commerce_installed && 
		(empty($_SESSION['user_admin_permissions_set_ids']) 
		|| in_array('purchasing/inventory', $_SESSION['user_permissions_admin_page_system_codes']) !== false 
		|| in_array('purchasing/inventory/add', $_SESSION['user_permissions_admin_page_system_codes']) !== false
		|| in_array('purchasing/inventory/assigned-attributes', $_SESSION['user_permissions_admin_page_system_codes']) !== false
		|| in_array('purchasing/inventory/assigned-to', $_SESSION['user_permissions_admin_page_system_codes']) !== false
		|| in_array('purchasing/inventory/edit', $_SESSION['user_permissions_admin_page_system_codes']) !== false
		|| in_array('purchasing/inventory/variant-builder', $_SESSION['user_permissions_admin_page_system_codes']) !== false
		|| in_array('accounting/inventory', $_SESSION['user_permissions_admin_page_system_codes']) !== false
		|| in_array('accounting/inventory-assets', $_SESSION['user_permissions_admin_page_system_codes']) !== false
		|| in_array('accounting/inventory/add', $_SESSION['user_permissions_admin_page_system_codes']) !== false
		|| in_array('accounting/inventory/adjustments', $_SESSION['user_permissions_admin_page_system_codes']) !== false
		|| in_array('accounting/inventory/assigned-attributes', $_SESSION['user_permissions_admin_page_system_codes']) !== false
		|| in_array('accounting/inventory/assigned-to', $_SESSION['user_permissions_admin_page_system_codes']) !== false
		|| in_array('accounting/inventory/edit', $_SESSION['user_permissions_admin_page_system_codes']) !== false
		|| in_array('accounting/inventory/variant-builder', $_SESSION['user_permissions_admin_page_system_codes']) !== false))
		{
		?>
		<li class="boxes">
			<div class="bashboard-box">
				<div class="headline"><a href="<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/purchasing/inventory/?total-qty-available=ascend&textfield-has-dropship-center=No">Lowest Inventory Available with No Dropship Center</a></div>
				<div class="box">
				<?php 
				if(!empty($sql_lowest_inventory))
				{
					foreach($sql_lowest_inventory as $lowest_inventory)
					{
						echo 'Qty Available: '.$lowest_inventory['total_qty_available'].' - <a href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/purchasing/inventory/edit/?rid='.$lowest_inventory['id'].'">'.$lowest_inventory['name'].'</a><br>';
					}
				}
				else
				{
					echo 'No Results';
				}
				?>
				</div>
			</div>
		</li>
        <?php } ?>
        <?php
        if(empty($_SESSION['user_admin_permissions_set_ids']) 
		|| in_array('website/interactions/site-searches', $_SESSION['user_permissions_admin_page_system_codes']) !== false 
		|| in_array('website/interactions/site-searches/edit', $_SESSION['user_permissions_admin_page_system_codes']) !== false)
		{
		?>
		<li class="boxes">
			<div class="bashboard-box">
				<div class="headline"><a href="<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/website/interactions/site-searches/?total-searches=descend">Top Site Searches</a></div>
				<div class="box">
				<?php 
				if(!empty($sql_top_site_searches))
				{
					foreach($sql_top_site_searches as $top_site_searches)
					{
						echo 'Searches: <a href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/website/interactions/site-searches/?textfield-keyword='.$top_site_searches['keyword'].'&textfield-created-date-start-range='.$from_date_date_only.'&textfield-created-date-end-range='.$to_date_date_only.'">'.$top_site_searches['total_searches'].'</a> - <a href="'.$view_frontend_of_site.INSTALLATION_URL_PATH.'/search/?search='.$top_site_searches['keyword'].'" target="_blank">'.$top_site_searches['keyword'].'</a><br>';
					}
				}
				else
				{
					echo 'No Results';
				}
				?>
				</div>
			</div>
		</li>
        <?php } ?>
        <?php
        if(empty($_SESSION['user_admin_permissions_set_ids']) 
		|| in_array('website/404-urls', $_SESSION['user_permissions_admin_page_system_codes']) !== false 
		|| in_array('website/404-urls/edit', $_SESSION['user_permissions_admin_page_system_codes']) !== false)
		{
		?>
        <li class="boxes">
			<div class="bashboard-box">
				<div class="headline"><a href="<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/website/404-urls/?total-404s=descend">Top 404 Errors</a></div>
				<div class="box">
				<?php 
				if(!empty($sql_top_404_errors))
				{
					foreach($sql_top_404_errors as $top_404_errors)
					{
						echo 'Total: <a href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/website/404-urls/?textfield-url-404='.$top_404_errors['url_404'].'&textfield-created-date-start-range='.$from_date_date_only.'&textfield-created-date-end-range='.$to_date_date_only.'">'.$top_404_errors['total_404s'].'</a> - <a href="'.$top_404_errors['url_404'].'" target="_blank">'.$top_404_errors['url_404'].'</a><br>';
					}
				}
				else
				{
					echo 'No Results';
				}
				?>
				</div>
			</div>
		</li>
        <?php } ?>
        <?php
        if($commerce_installed && 
		(empty($_SESSION['user_admin_permissions_set_ids']) 
		|| in_array('marketing/shopping-feeds', $_SESSION['user_permissions_admin_page_system_codes']) !== false 
		|| in_array('marketing/shopping-feeds/add', $_SESSION['user_permissions_admin_page_system_codes']) !== false
		|| in_array('marketing/shopping-feeds/edit', $_SESSION['user_permissions_admin_page_system_codes']) !== false
		|| in_array('marketing/shopping-feeds/products', $_SESSION['user_permissions_admin_page_system_codes']) !== false
		|| in_array('marketing/shopping-feeds/products/add', $_SESSION['user_permissions_admin_page_system_codes']) !== false
		|| in_array('marketing/shopping-feeds/products/edit', $_SESSION['user_permissions_admin_page_system_codes']) !== false))
		{
		?>
		<li class="boxes">
			<div class="bashboard-box">
				<div class="headline"><a href="<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/marketing/shopping-feeds/">Shopping Feeds</a></div>
				<div class="box">
				<?php 
				$has_results = 'No';
				if(!empty($sql_shipping_feeds))
				{
					foreach($sql_shipping_feeds as $shipping_feeds)
					{
						$shopping_feed_url = adminUrlId($shipping_feeds['urls_id']);
						if(isset($shopping_feed_url['final_url']) && !empty($shopping_feed_url['final_url']))
						{
							$has_results = 'Yes';
							echo 'Products in Feed: '.$shipping_feeds['sub_items'].' - Feed URL: <a href="'.$shopping_feed_url['final_url'].'" target="_blank">'.$shopping_feed_url['final_url'].'</a><br>';
						}
					}
					
					if($has_results == 'No')
					{
						echo 'No Results';
					}
				}
				else
				{
					echo 'No Results';
				}
				?>
				</div>
			</div>
		</li>
        <?php } ?>
        <li class="full-width overviews">Security Overview</li>
        <?php
        if(empty($_SESSION['user_admin_permissions_set_ids']) 
		|| in_array('security/site-security', $_SESSION['user_permissions_admin_page_system_codes']) !== false)
		{
		?>
        <li class="boxes">
			<div class="bashboard-box">
				<div class="headline"><a href="<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/security/site-security/">Top IPs Hitting The Site - Last 24 Hours</a></div>
				<div class="box">
				<?php 
				if(!empty($sql_top_ip_hits))
				{
					foreach($sql_top_ip_hits as $top_ip_hits)
					{
						echo 'Hits: '.$top_ip_hits['counter'].' - '.$top_ip_hits['ip_address'].'<br>';
					}
				}
				else
				{
					echo 'No Results';
				}
				?>
				</div>
			</div>
		</li>
        <?php } ?>
        <?php
        if($commerce_installed && 
		(empty($_SESSION['user_admin_permissions_set_ids']) 
		|| in_array('security/declined-card-attempts', $_SESSION['user_permissions_admin_page_system_codes']) !== false 
		|| in_array('security/declined-card-attempts/edit', $_SESSION['user_permissions_admin_page_system_codes']) !== false))
		{
		?>
		<li class="boxes">
			<div class="bashboard-box">
				<div class="headline"><a href="<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/security/declined-card-attempts/">Top IPs with Declined Card Attempts - Last 24 Hours</a></div>
				<div class="box">
				<?php 
				if(!empty($sql_top_ip_with_declined_card_attempts))
				{
					foreach($sql_top_ip_with_declined_card_attempts as $declined_card_attempts)
					{
						echo 'Declined Card Attempts: '.$declined_card_attempts['counter'].' - IP: <a href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/security/declined-card-attempts/?textfield-ip-address='.$declined_card_attempts['ip_address'].'">'.$declined_card_attempts['ip_address'].'</a><br>';
					}
				}
				else
				{
					echo 'No Results';
				}
				?>
				</div>
			</div>
		</li>
        <?php } ?>
        <?php
        if(empty($_SESSION['user_admin_permissions_set_ids']) 
		|| in_array('security/failed-logins', $_SESSION['user_permissions_admin_page_system_codes']) !== false 
		|| in_array('security/failed-logins/edit', $_SESSION['user_permissions_admin_page_system_codes']) !== false)
		{
		?>
        <li class="boxes">
			<div class="bashboard-box">
				<div class="headline"><a href="<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/security/failed-logins/">Top IPs with Failed Login Attempts - Last Hour</a></div>
				<div class="box">
				<?php 
				if(!empty($sql_top_ip_with_failed_login_attempts))
				{
					foreach($sql_top_ip_with_failed_login_attempts as $failed_login_attempts)
					{
						echo 'Failed Login Attempts: '.$failed_login_attempts['counter'].' - IP: <a href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/security/failed-logins/?textfield-ip-address='.$failed_login_attempts['ip_address'].'">'.$failed_login_attempts['ip_address'].'</a><br>';
					}
				}
				else
				{
					echo 'No Results';
				}
				?>
				</div>
			</div>
		</li>
        <?php } ?>
	</ul>
	<?php } ?>
<?php } ?>