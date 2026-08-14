<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/scripts/scroll-bar-bottom.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/scripts/scroll-bar-bottom.php');
}
else
{
?>
	<script nonce="<?php echo NONCE; ?>">
	//Append fixed scrollbar at the bottom of tables that exceed viewport.
    $(function($) {
		var customScrollbar = $('<div id="fixed-scrollbar"><div></div></div>').appendTo($(document.body));
		var contentPlaceholder = customScrollbar.find('div');
		var currentActiveElement = $([]);
		var previousScrollPosition;
	
		customScrollbar.hide().css({
			overflowX: 'auto',
			position: 'fixed',
			width: '100%',
			bottom: 0,
			left: 0
		});
	
		function getElementTop(element) {
			return element.offset().top;
		}
	
		function getElementBottom(element) {
			return element.offset().top + element.height();
		}
	
		function findCurrentActive() {
			customScrollbar.show();
			var newActiveElement = $([]);
			$('.fixed-scrollbar').each(function() {
				var $element = $(this);
				if (getElementTop($element) < getElementTop(customScrollbar) && getElementBottom($element) > getElementBottom(customScrollbar)) {
					contentPlaceholder.width($element.get(0).scrollWidth);
					contentPlaceholder.height(1);
					newActiveElement = $element;
				}
			});
			adjustScrollbar(newActiveElement);
			return newActiveElement;
		}
	
		function adjustScrollbar(activeElement) {
			if (!activeElement.length) {
				return customScrollbar.hide();
			}
			customScrollbar.css({
				left: activeElement.offset().left,
				width: activeElement.width()
			});
			contentPlaceholder.width(activeElement.get(0).scrollWidth);
			contentPlaceholder.height(1);
			delete previousScrollPosition;
		}
	
		function handleScroll() {
			var previousActiveElement = currentActiveElement;
			currentActiveElement = findCurrentActive();
			if (previousActiveElement.not(currentActiveElement).length) {
				previousActiveElement.unbind('scroll', synchronizeScroll);
			}
			if (currentActiveElement.not(previousActiveElement).length) {
				currentActiveElement.scroll(synchronizeScroll);
			}
			synchronizeScroll();
		}
	
		function synchronizeScroll() {
			if (!currentActiveElement.length) return;
			if (customScrollbar.scrollLeft() === previousScrollPosition) return;
			previousScrollPosition = customScrollbar.scrollLeft();
			currentActiveElement.scrollLeft(previousScrollPosition);
		}
	
		function updateScrollPosition() {
			if (!currentActiveElement.length) return;
			if (currentActiveElement.scrollLeft() === previousScrollPosition) return;
			previousScrollPosition = currentActiveElement.scrollLeft();
			customScrollbar.scrollLeft(previousScrollPosition);
		}
	
		customScrollbar.scroll(synchronizeScroll);
		handleScroll();
		$(window).scroll(handleScroll);
		$(window).resize(handleScroll);
	});
    </script>
<?php } ?>
