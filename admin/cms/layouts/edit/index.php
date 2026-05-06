<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/edit/index.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/edit/index.php');
}
else
{
	include_once $_SERVER['DOCUMENT_ROOT'].'/admin/cms/includes/admin-fields/get-fields.php';
	include_once $_SERVER['DOCUMENT_ROOT'].'/admin/cms/includes/admin-fields/get-values.php';
	include_once $_SERVER['DOCUMENT_ROOT'].'/admin/cms/includes/admin-fields/modify.php';
	include_once $_SERVER['DOCUMENT_ROOT'].'/admin/cms/includes/admin-fields/validation.php';
	include_once $_SERVER['DOCUMENT_ROOT'].'/admin/cms/includes/custom-fields/validation.php';
	//echo '<pre>'; print_r($post_values); echo '</pre>';
	//echo '<pre>'; print_r($errors); echo '</pre>';
	//die;
	include_once 'includes/update-data.php';
	
?>
    <!DOCTYPE html>
    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php if(!empty($head_title_name)) { echo $head_title_name.' '; } echo $_SESSION['admin_title']; ?></title>
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/includes/head-files.php'); ?>
    <?php include_once $_SERVER['DOCUMENT_ROOT'].'/admin/cms/includes/admin-fields/modify-js.php'; ?>
    </head>
    <body>
    <!-- Start Pending Ajax Overlay -->
	<style nonce="<?php echo NONCE; ?>">.pending-ajax { display: none; }</style>
	<div class="pending-ajax">
	  <div class="pending-ajax-outer-container">
		<div class="pending-ajax-inner-container">
		  <span>Updating...</span>
		</div>
	  </div>
	</div>
	<!-- End Pending Ajax Overlay -->
    <?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/includes/navigation.php');?>
    <div class="right">
      <!-- Start Notices -->
      <?php 
	  include($_SERVER['DOCUMENT_ROOT'].'/admin/cms/includes/notices/index.php');
	  echo $display_message; 
	  ?>
      <!-- End Notices -->
      <?php if($level >= $_SESSION['admin_page_level']) { ?>
      <!-- Start Edit View -->
      <!-- Start Header -->
      <div class="header-text">
        <div class="text"><?php echo $_SESSION['admin_title']; ?></div>
        <div class="header-right">
          <?php if(!empty($_SESSION['admin_help_video_url'])) { ?>
          <a href="<?php echo $_SESSION['admin_help_video_url']; ?>" target="_blank">
          <div class="header-video"><i><svg viewBox="0 0 512 512"><path d="M4 162a63 63 0 0 1 63-63h236a63 63 0 0 1 62 55l98-44A31 31 0 0 1 508 139v235a31 31 0 0 1-44 29l-98-44A63 63 0 0 1 303 413H67a63 63 0 0 1-63-63z"></path></svg></i> Tutorial</div>
          </a>
          <?php } ?>
          <div class="toggle-results">Results</div>
        </div>
      </div>
      <!-- End Header -->
      <?php 
    include_once $_SERVER['DOCUMENT_ROOT'].'/admin/cms/includes/sub-navigation.php';
    if(!empty($sub_menu)) { echo $sub_menu; }
    ?>
      <?php if($admin_fields_has_url == 'Yes') { include_once 'includes/urls-header.php'; } ?>
      <div class="edit-wrapper">
        <?php if(!empty($errors)) { echo '<div class="changes-error">Oops! It looks like you missed something.</div>'; } ?>
        <script nonce="<?php echo NONCE; ?>">//$(function() { $(".changes-saved").delay(3000).fadeOut(300); });</script>
        <?php if(empty($errors) && isset($_GET['updated']) && $_GET['updated'] == 'success') { echo '<div class="changes-saved">Updated successfully.</div>'; } ?>
        <?php if(empty($errors) && isset($_GET['redirects']) && $_GET['redirects'] == 'canceled') { echo '<div class="changes-saved">URL changes were canceled successfully.</div>'; } ?>
        <?php if(empty($errors) && isset($_GET['redirects']) && $_GET['redirects'] == 'deleted') { echo '<div class="changes-saved">Conflicting URL redirects were delete successfully. Please try changing the URL again.</div>'; } ?>
        <?php if(empty($errors) && isset($_GET['redirects']) && $_GET['redirects'] == 'updated') { echo '<div class="changes-saved">URL was updated successfully.</div>'; } ?>
        <?php if(empty($errors) && isset($_GET['redirects']) && $_GET['redirects'] == 'updated-created') { echo '<div class="changes-saved">URLs were updated and redirects have been created successfully.</div>'; } ?>
        <?php if(empty($errors) && isset($_GET['q-and-a-admin-user-email']) && $_GET['q-and-a-admin-user-email'] == 'no') { echo '<div class="changes-error">Oops! You need to add an "email address" to your admin user profile as well as fill in your SMTP Email Server information to email this customer. You can do that <a href="/'.$_SESSION['admin_directory'].'/admin/users/edit/?rid='.$_SESSION['user_id'].'" target="_blank">here</a>.</div>'; } ?>
        <?php if(empty($errors) && isset($_GET['paypal-connect']) && $_GET['paypal-connect'] == 'failed') { echo '<div class="changes-error">We saved your settings, but couldn\'t connect to PayPal. Please double-check your PayPal credentials and try again.</div>'; } ?>
        <?php if($status_lock_insert_update_delete == 'Yes') { echo '<div class="changes-error">Locked from editing as status is <strong>'.$admin_page_status.'</strong>.</div>'; }?>
        <?php if($status_lock_insert_update_delete == 'No') { ?>
        <form method="post" enctype="multipart/form-data">
        <?php } ?>
          <!-- Start Admin Fields -->
          <?php include_once $_SERVER['DOCUMENT_ROOT'].'/admin/cms/includes/admin-fields/fields.php'; ?>
          <!-- End Admin Fields -->
          <?php if($status_lock_insert_update_delete == 'No') { ?>
          <div class="button-right">
            <button type="<?php echo $_SESSION['admin_submit_button_type']; ?>" name="submit" id="submit"><?php echo $_SESSION['admin_submit_button_label']; ?></button>
          </div>
          <?php } ?>
        <?php if($status_lock_insert_update_delete == 'No') { ?>
        </form>
        <?php } ?>
      </div>
      <!-- End Edit View -->
      <?php 
      } 
      else
      {
      ?>
      <div class="header-text">
      <div class="text"><?php echo $_SESSION['admin_title']; ?></div>
      </div>
      <!-- End Header -->
      <?php
      echo $account_message;
      }
      ?>
    </div>
    <?php include_once $_SERVER['DOCUMENT_ROOT'].'/admin/cms/includes/media-popup.php'; ?>
    </body>
    </html>
<?php } ?>