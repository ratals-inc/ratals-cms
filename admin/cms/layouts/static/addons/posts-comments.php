<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/addons/posts-comments.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/addons/posts-comments.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'posts_comments')
	{
	?>
		<!-- Start Edit View -->
		<div class="edit-wrapper">
		<!-- Start Pending Approval -->
		<?php
		$sql_get_pending_comments = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'comments', 'WHERE `site_id` = ? AND `post_url_id` = ? AND `status` = ? ORDER BY id DESC', [$_SESSION["site_set_for_editing"], trim($_GET["rid"] ?? ''), '2']);
		
		if(!empty($sql_get_pending_comments))
		{
		?>
			<div class="margin-border-padding">
			<div class="font-size-18px-weight-600">Pending Comments (<?php echo count($sql_get_pending_comments); ?>)</div>
			</div>
			<?php
			foreach($sql_get_pending_comments as $sql_get_pending_comments_rows)
			{
			?>
			<div class="edit background-padding-margin">
			<div class="edit-field">
			<div class="margin-12px-0px-8px-0px"><?php echo htmlspecialchars($sql_get_pending_comments_rows['comment'] ?? ''); ?></div>
			<div class="font-size-margin-bottom">
			<strong>From:</strong> <?php echo htmlspecialchars($sql_get_pending_comments_rows['name'] ?? ''); ?> | 
			<strong>Created:</strong> <?php echo utcToUserTimeZone($sql_get_pending_comments_rows['created_date'], 'F d, Y - g:i:s A'); ?></div>
			</div>
			
			<div class="results-buttons float-none-margin-top">
			<form method="post">
			<input name="id" type="hidden" value="<?php echo $sql_get_pending_comments_rows['id']; ?>">
			<button type="submit" name="approve">Approve</button> <button type="submit" name="delete">Delete</button>
			</form>
			</div>
			</div>
			<?php
			}
		}
		else
		{
		?>
		<div class="margin-border-padding">
			<div class="font-font-padding">Pending Comments (0)</div>
			</div>
		<?php
		}
		?>
		<!-- End Pending Approval -->
		
		
		<!-- Start Approved -->
		<?php
		
		$sql_get_pending_comments = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'comments', 'WHERE `site_id` = ? AND `post_url_id` = ? AND `status` = ? ORDER BY id DESC', [$_SESSION["site_set_for_editing"], trim($_GET["rid"] ?? ''), '1']);
		
		if(!empty($sql_get_pending_comments))
		{
		?>
			<div class="margin-border-padding">
			<div class="font-font-padding">Approved Comments (<?php echo count($sql_get_pending_comments); ?>)</div>
			</div>
			<?php
			foreach($sql_get_pending_comments as $sql_get_pending_comments_rows)
			{
			?>
			<div class="edit background-padding-margin">
			
			<div class="edit-field">
			<div class="margin-12px-0px-8px-0px"><?php echo htmlspecialchars($sql_get_pending_comments_rows['comment'] ?? ''); ?></div>
			<div class="font-size-margin-bottom">
			<strong>From:</strong> <?php echo htmlspecialchars($sql_get_pending_comments_rows['name'] ?? ''); ?> | 
			<strong>Created:</strong> <?php echo utcToUserTimeZone($sql_get_pending_comments_rows['created_date'], 'F d, Y - g:i:s A'); ?>
			</div>
			
			<div class="font-size-margin-bottom"><strong>Approved By:</strong> <?php echo $sql_get_pending_comments_rows['approved_by']; ?> | <strong>Approved Date:</strong> <?php echo $sql_get_pending_comments_rows['approved_date']; ?></div>
			
			</div>
			<div class="results-buttons float-none-margin-top">
			<form method="post">
			<input name="id" type="hidden" value="<?php echo $sql_get_pending_comments_rows['id']; ?>">
			<button type="submit" name="delete">Delete</button>
			</form>
			</div>
			</div>
			<?php
			}
		}
		else
		{
		?>
		<div class="margin-border-padding">
			<div class="font-font-padding">Approved Comments (0)</div>
			</div>
		<?php
		}
		?>
		<!-- End Approved -->
		
		
		<div class="edit margin-top-25px">
		<div class="edit-label">Updated Date</div>
		<div class="edit-field text"><?php echo utcToUserTimeZone($sql_record_data_rows["updated_date"], 'F d, Y - g:i:s A'); ?></div>
		</div>
		
		<div class="edit">
		<div class="edit-label">Updated By</div>
		<div class="edit-field text"><?php echo $sql_record_data_rows["updated_by"]; ?></div>
		</div>
		
		<div class="edit">
		<div class="edit-label">Created Date</div>
		<div class="edit-field text"><?php echo utcToUserTimeZone($sql_record_data_rows["created_date"], 'F d, Y - g:i:s A'); ?></div>
		</div>
		
		<div class="edit">
		<div class="edit-label">Created By</div>
		<div class="edit-field text"><?php echo $sql_record_data_rows["created_by"]; ?></div>
		</div>
		
		
		</div>
		<!-- End Edit View -->
	<?php } ?>
<?php } ?>