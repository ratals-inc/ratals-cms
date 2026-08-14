<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/sites/functions.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/sites/functions.php');
}
else
{
	//Start function to build review stars
	function getReviewStars($review_score)
	{
		//Start code to build review stars
		$review_score = (float)$review_score;
		$review_score_html = '';
		if($review_score > 0)
		{
			$css_review_score = str_replace('.', '-', $review_score);

			$review_score_html .= '<style nonce="'.NONCE.'">
			.rating-'.$css_review_score.' { --rating: '.$review_score.'; }
			</style>';
			$review_score_html .= '<div class="review-stars rating-'.$css_review_score.'"></div> ';

		}
		else
		{
			$review_score_html .= '<style nonce="'.NONCE.'">
			.rating-0-0 { --rating: 0.0; }
			</style>';
			$review_score_html .= '<div class="review-stars rating-0-0"></div> ';
		}
		return $review_score_html;
	}
	//End function to build review stars
}