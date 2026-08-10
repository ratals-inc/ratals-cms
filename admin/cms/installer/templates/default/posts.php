<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//echo '<pre>'; print_r($data_array); echo '</pre>';
include_once('sites/functions.php');
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['site_language']; ?>">
<head>
<?php if(!empty($full_meta_title)) { ?>
<title><?php echo $full_meta_title?></title>
<?php } ?>
<?php if(!empty($meta_description)) { ?>
<meta name="description" content="<?php echo $meta_description; ?>">
<?php } ?>
<?php if(!empty($meta_keywords)) { ?>
<meta name="keywords" content="<?php echo $meta_keywords; ?>">
<?php } ?>
<?php if(!empty($canonical_url)) { ?>
<link rel="canonical" href="<?php echo $canonical_url; ?>">
<?php } ?>
<?php if(!empty($meta_robots)) { ?>
<meta name="robots" content="<?php echo $meta_robots; ?>">
<?php } ?>
<?php if(!empty($pages_data['hreflang'])) { echo $pages_data['hreflang'].'
'; } ?>
<meta property="og:type" content="article">
<?php if(!empty($full_meta_title)) { ?>
<meta property="og:title" content="<?php echo $full_meta_title; ?>">
<?php } ?>
<?php if(!empty($meta_description)) { ?>
<meta property="og:description" content="<?php echo $meta_description; ?>">
<?php } ?>
<?php if(!empty($data_array['pages_url'])) { ?>
<meta property="og:url" content="<?php echo $data_array['pages_url']; ?>">
<?php } ?>
<?php if(!empty($site_name)) { ?>
<meta property="og:site_name" content="<?php echo $site_name; ?>">
<?php } ?>
<?php if(!empty($data_array['media_data'][0]['full_url'])) { ?>
<meta property="og:image" content="<?php echo $data_array['media_data'][0]['full_url']; ?>">
<?php } ?>
<?php if(!empty($data_array['media_data'][0]['media_tag'])) { ?>
<meta property="og:image:alt" content="<?php echo $data_array['media_data'][0]['media_tag']; ?>">
<?php } ?>
<?php include('head-files.php'); ?>
<script nonce="<?php echo NONCE; ?>" type="application/ld+json">
{
	"@context": "https://schema.org",
	"@type": "BlogPosting",
	"mainEntityOfPage": {
		"@type": "WebPage",
		"@id": "<?php echo $data_array['pages_url'] ?? ''; ?>"
	},
	"headline": "<?php echo $data_array['meta_title'] ?? ''; ?>",
	"description": "<?php echo $data_array['meta_description'] ?? ''; ?>",
	"image": ["<?php if(isset($data_array['media_data'][0]['path_url']) && !empty($data_array['media_data'][0]['path_url'])) { echo $domain.'/'.$data_array['media_data'][0]['path_url']; } ?>"],  
	<?php if(isset($data_array['author_bio']['author_name'])) { ?>"author": {
		"@type": "Person",
		"@id": "<?php echo $data_array['author_bio']['author_personal_website_url']; ?>",
		"name": "<?php echo $data_array['author_bio']['author_name']; ?>"<?php if(isset($data_array['author_bio']['author_page_url']) && !empty($data_array['author_bio']['author_page_url'])) { ?>,
		"url": "<?php echo $data_array['author_bio']['author_page_url']; ?>"<?php } ?><?php if(isset($data_array['author_bio']['author_photo_url']) && !empty($data_array['author_bio']['author_photo_url'])) { ?>,
		"image": {
			"@type": "ImageObject",
			"url": "<?php echo $data_array['author_bio']['author_photo_url']; ?>"
		}<?php } ?><?php if(isset($data_array['author_bio']['author_same_as_urls']) && !empty($data_array['author_bio']['author_same_as_urls'])) { ?>,
		<?php
		$same_as_urls = explode(',', $data_array['author_bio']['author_same_as_urls']);
		$same_as_urls = array_map('trim', $same_as_urls);
		$same_as_urls = array_filter($same_as_urls);
		if(!empty($same_as_urls))
		{
			echo '"sameAs": '.json_encode(array_values($same_as_urls), JSON_UNESCAPED_SLASHES).'
	';
	}
	?>},
	<?php } ?><?php } ?>"publisher": {
		"@type": "Organization",
		"name": "<?php echo $site_name ?? ''; ?>",
		"url": "<?php echo $domain ?? ''; ?>"<?php if(isset($logo_media_url) && strpos($logo_media_url, 'http') !== false) {?>,
		"logo": {
			"@type": "ImageObject",
			"url": "<?php echo $logo_media_url ?? ''; ?>"
		}<?php } ?>

	},
	"datePublished": "<?php echo str_replace(' ', 'T', $data_array['created_date']).''.$time_zone_offset ?? ''; ?>",
	"dateModified": "<?php echo str_replace(' ', 'T', $data_array['updated_date']).''.$time_zone_offset ?? ''; ?>"
}
</script>
</head>

<body><?php 
//  START CAUTION!
//  IF YOU MODIFY THE "EDIT IN ADMIN" CODE BELOW, MAKE SURE TO UPDATE THE CODE IN /INDEX.PHP FILE TO PREVENT IT FROM BEING CACHED. ?>
<!-- Start Edit Admin Page -->
<?php
if(isset($_SESSION['user_id'])) 
{
	echo '<div class="edit-page"><a href="/'.$_SESSION['admin_directory'].'/website/'.$pages_data['table_name'].'/edit/?rid='.$id.'" target="_blank">Edit in Admin</a></div>';
}
?>
<!-- End Edit Admin Page --><?php //END CAUTION! ?>
<?php if($commerce_installed) { include('header-free-shipping.php'); } ?>
<?php include('header.php'); ?>
<?php if($commerce_installed) { include('header-customer-account-control.php'); } ?>
<script nonce="<?php echo NONCE; ?>">
//Submit Blog Comment
$(document).ready(function()
{
	$(".submitComment").click(function()
	{
		var dataValues = $(this).attr('data-click');
		var dataArray = dataValues.split(',');
		
		var idOne = dataArray[0];
		var idTwo = dataArray[1];
		var idThree = dataArray[2];
		
		var name = $('input[name=name_'+idThree+']').val();
		var email = $('input[name=email_'+idThree+']').val();
		var comment = $('textarea[name=comment_'+idThree+']').val();
		
		var post = 'one=' + idOne + '&two=' + idTwo + '&three=' + idThree + '&name=' + name + '&email=' + email + '&comment=' + comment + '&type=post_page_submit_comment'; 
		$.post("/sites/submit.php", post, function(theResponse)
		{		
			if(theResponse == 1)
			{
				$("#comment-response-" + idThree + "").html('<div class="color-text-margin"><strong>Thank you for submitting your comment.</strong></div>');
				$('input[name=name_'+idThree+']').val("");
				$('input[name=email_'+idThree+']').val("");
				$('textarea[name=comment_'+idThree+']').val("");
			}
			else
			{
				$("#comment-response-" + idThree + "").html('<div class="color-text-align-margin-bottom"><strong>Please makes sure all fields are filled in with valid information.</strong></div>');
			}
		});
	});
});

$(document).ready(function()
{
	$(".toggleCommentForm").click(function()
	{
		var id = $(this).attr('data-click');
		
		$(".comment_reply_" + id).slideToggle();
	});
});
</script>
<!-- Start Breadcrumbs -->
<nav class="breadcrumbs">
    <div class="breadcrumbs-wrap container-width">
        <?php include('breadcrumbs.php'); ?>
    </div>
</nav>
<!-- End Breadcrumbs -->
<main>
    <!-- Start Blog Post -->
    <div class="blog blog-post">
        <div class="blog-wrap container-width">
            <!-- Start Posts -->
            <div class="posts">
                <div class="top">
                    <?php if(!empty($content_title)) { ?><h1 class="title"><?php echo $content_title; ?></h1><?php } ?>
                    <?php include('author-and-dates-top.php'); ?>
                </div>
                <?php if(!empty($top_content)) { ?><?php echo $top_content; ?><?php } ?>
                <?php if(isset($data_array['author_bio']['author_page_url']) && !empty($data_array['author_bio']['author_page_url'])) { ?>
                <!-- Stat Author Bio -->
                <div class="short-bio-wrapper padding-top">
                    <div class="short-bio container-width">
                        <?php
                        if(isset($data_array['author_bio']['author_media']) && !empty($data_array['author_bio']['author_media']))
                        {
                            echo '<div class="photo">'.$data_array['author_bio']['author_media'].'</div>';
                        }
                        ?>
                        <div>
                        <div class="intro">About the Author</div>
                        <div class="author-name"><a href="<?php echo $data_array['author_bio']['author_page_url']; ?>"><?php echo $data_array['author_bio']['author_name']; ?></a></div>
                        <?php echo $data_array['author_bio']['author_description']; ?>
                        </div>
                    </div>
                </div>
                <!-- End Author Bio -->
                <?php } ?>
            </div>
            <!-- End Posts -->
            <!-- Start Sidebar -->
            <div class="sidebar">
                <!-- Start Sidebar Box -->
                <?php include('sidebar-about.php'); ?>
                <!-- End Sidebar Box -->
                <!-- Start Sidebar Box -->
                <?php include('sidebar-blog-categories.php'); ?>
                <!-- End Sidebar Box -->
                <!-- Start Sidebar Box -->
                <?php include('sidebar-table-of-contents.php'); ?>
                <!-- End Sidebar Box -->
            </div>
            <!-- End Sidebar -->
        </div>
    </div>
    <!-- End Blog Post -->
    <?php if(!empty($data_array['sub_items'])) { ?>
        <!-- Start Sub Items -->
        <div class="post-sub-items-style">
            <?php include('sub-items.php'); ?>
        </div>
        <!-- End Sub Items -->
    <?php } ?>
    <!-- Start Comments -->
    <div class="post-comments">
        <div class="post-comments-wrap container-width">
            <div class="top">
                <div class="title"><?php echo $sql_get_comments_total_check; ?> Comments</div>
                <?php if($_SESSION['allow_comments'] == 'Yes') { ?>
                <div class="add-new-comment"><a href="#post-comment">Post a Comment</a></div>
                <?php } ?>
            </div>
            <div class="comments">
                <ul>
                    <?php 
                    $comments_page_id = $data_array['id']; 
                    if(!empty($comments_page_id))
                    {
                        function displayComments($commentsArray, $allow_comments) 
                        { 
                            if(!empty($commentsArray)) 
                            { 
                                global $id, $site_id;
                            
                                foreach($commentsArray as $comments) 
                                {
                                    $comments['created_date'] = utcToUserTimeZone($comments['created_date'], 'F d, Y - g:i:s A');
                                    
                                    echo "<li>"; 
                                    echo '<div class="by-date">
                                    <div class="by">'.htmlspecialchars($comments['name'] ?? '').' Says: </div>
                                    <div class="date">'.$comments['created_date'].'</div>
                                    </div>';
                                    echo'<div class="comment">
                                    <p>'.htmlspecialchars($comments['comment'] ?? '').'</p>';
                                    
                                    if($allow_comments == 'Yes')
                                    {
                                        echo'<div class="reply">
                                        <div class="reply-toggle toggleCommentForm" data-click="'.$comments['id'].'">Reply</div>
                                        <div class="comment_reply_form comment_reply_'.$comments['id'].' display-none">
                                        <div id="comment-response-'.$comments['id'].'"></div>
                                        <form>
                                        <label>Name <input type="text" name="name_'.$comments['id'].'"></label>
                                        <label>Email <input type="text" name="email_'.$comments['id'].'"></label>
                                        <div class="font-size-13px">Note: Your email address will not be published.</div>
                                        <label>Comment <textarea name="comment_'.$comments['id'].'" cols="" rows="5"></textarea></label>
                                        <button type="button" class="submitComment" data-click="'.$site_id.','.$id.','.$comments['id'].'">Post Reply Comment</button>
                                        </form>
                                        </div>
                                        </div>';
                                    }
                                    
                                    echo'</div>';
                                    
                                    if(!empty($comments['sub_comments'])) 
                                    {
                                        echo '<ul>';
                                        displayComments($comments['sub_comments'], $allow_comments); 
                                        echo '</ul>'; 
                                    } 
                                    
                                    echo "</li>";
                                }
                            }
                        }
                        
                        displayComments(getComments($comments_page_id), $_SESSION['allow_comments']); 
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Comments -->
    <?php 
    if($_SESSION['allow_comments'] == 'Yes') 
    {
    ?>
    <!-- Start Post a Comment -->
    <div class="post-a-comment" id="post-comment">
        <div class="post-a-comment-wrap container-width">
            <div id="comment-response-0"></div>
            <div class="title">Post a New Comment</div>
            <form method="post" id="add-comment">
                <div class="name">
                    <label>Name
                        <input type="text" name="name_0">
                    </label>
                </div>
                <div class="email">
                    <label>Email
                        <input type="text" name="email_0">
                    </label>
                    <div class="font-size-13px">Note: Your email address will not be published.</div>
                </div>
                <div class="comment">
                    <label>Comment
                        <textarea name="comment_0" cols="" rows="5"></textarea>
                    </label>
                </div>
                <div class="button">
                    <button type="button" class="submitComment" data-click="<?php echo $site_id; ?>,<?php echo $pages_data['id']; ?>,0">Post Comment</button>
                </div>
            </form>
        </div>
    </div>
    <!-- End Post a Comment -->
    <?php } ?>
    <?php if(!empty($bottom_content)) { ?>
        <!-- Start Bottom Content -->
        <div class="bottom-content">
            <div class="bottom-content-wrap container-width"><?php echo $bottom_content; ?></div>
        </div>
        <!-- End Bottom Content -->
    <?php } ?>
</main>
<?php include('footer.php'); ?>
<?php include('cookie-notice-banner.php'); ?>
<?php if($commerce_installed) { include('affiliate.php'); } ?>
<?php include('analytics.php'); ?>
</body>
</html>