<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/sites/slider-js.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/sites/slider-js.php');
}
else
{
?>
<script nonce="<?php echo NONCE; ?>">
function slider(slidersId, shouldAutoSlide, usePagination, useThumbnails, slideAllAtOnce, slidesInView, slideSpeed, pauseTime, slideItemGap, minSlideWidth, totalSlidesInit, totalNumOfSlides, videoIconSrc, FileIconSrc) {
	
	if (parseInt(slideSpeed) >= parseInt(pauseTime)) {
		pauseTime = parseInt(slideSpeed)+parseInt(pauseTime);
	}
	
	const $sliderId = document.querySelector(slidersId);
	let currentIndex = slidesInView;
	let calculatedSlideWidth = 0;
	let sliderInterval;
	let isAnimating = false;

	function cloneSlides() {
		const $sliderHolder = $sliderId.querySelector('.slider-holder');
		const $slides = Array.from($sliderHolder.children);
		if (slideAllAtOnce === 'yes') {
			const $firstSlides = $slides.slice(0, slidesInView).map(slide => slide.cloneNode(true));
			$firstSlides.forEach(slide => $sliderHolder.appendChild(slide));
		} else {
			const $firstSlides = $slides.slice(0, slidesInView).map(slide => {
				const clone = slide.cloneNode(true);
				clone.classList.add('cloned');
				return clone;
			});
			const $lastSlides = $slides.slice(-slidesInView).map(slide => {
				const clone = slide.cloneNode(true);
				clone.classList.add('cloned');
				return clone;
			});

			$firstSlides.forEach(slide => $sliderHolder.appendChild(slide));
			$lastSlides.reverse().forEach(slide => $sliderHolder.insertBefore(slide, $sliderHolder.firstChild));
		}
		
		const slideWidth = calculatedSlideWidth + slideItemGap;
		$sliderHolder.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
	}

	function removeClonedSlides() {
		const $sliderHolder = $sliderId.querySelector('.slider-holder');
		const $allSlides = Array.from($sliderHolder.children);

		$allSlides.forEach(slide => {
			if (slide.classList.contains('cloned')) {
				$sliderHolder.removeChild(slide);
			}
		});
	}

	function adjustSlidesInView() {
		const viewportWidth = window.innerWidth;
		const maxSlides = Math.floor(viewportWidth / minSlideWidth);

		if (slideAllAtOnce === "yes") {
			slidesInView = Math.min(maxSlides, totalNumOfSlides);
			if (slidesInView > totalSlidesInit) {
				slidesInView = totalSlidesInit;
			}
		} else {
			slidesInView = maxSlides;
			if (slidesInView > totalSlidesInit) {
				slidesInView = totalSlidesInit;
			}
		}
		if (slidesInView < 1) {
			slidesInView = 1;
		}
		if (slideAllAtOnce === "yes") {
			updatePagination();
			adjustSlideWidth();
		} else {
			removeClonedSlides();
			cloneSlides();
			adjustSlideWidth();
			const $sliderHolder = $sliderId.querySelector('.slider-holder');
			const clonedDivs = $sliderHolder.querySelectorAll('.cloned').length;
			currentIndex = clonedDivs / 2;

			updateSlider('', true);
			updateActivePagerOnResize();
		}
	}

	function updatePagination() {
		if (usePagination === "yes" && useThumbnails === "yes") {
			const $sliderPager = $sliderId.querySelector(".slider-pager");
			$sliderPager.innerHTML = '';
			
			for (let count = 0; count < totalNumOfSlides; count += parseInt(slidesInView, 10)) {
				let picture;
				
				const container = $sliderId.querySelectorAll('.slider .container')[count];
				if (container.querySelector('picture')) {
					picture = container.querySelector('picture');
					const pictureClone = picture.cloneNode(true);
					pictureClone.classList.add('thumbnail');
					pictureClone.setAttribute('data-index', count);
					$sliderPager.appendChild(pictureClone);
				} 
				else if (container.querySelector('video') || container.querySelector('iframe')) {
					const videoThumbnail = document.createElement('picture');
					videoThumbnail.innerHTML = videoIconSrc;
					videoThumbnail.classList.add('thumbnail');
					videoThumbnail.setAttribute('data-index', count);
					$sliderPager.appendChild(videoThumbnail);
				}
				else if (container.querySelector('object')) {
					const objectThumbnail = document.createElement('picture');
					objectThumbnail.innerHTML = FileIconSrc;
					objectThumbnail.classList.add('thumbnail');
					objectThumbnail.setAttribute('data-index', count);
					$sliderPager.appendChild(objectThumbnail);
				}
			}
			
			addIndexesInPictures();
			addImagesInPagers();
			
		} else if (usePagination === "yes") {
			const $sliderPager = $sliderId.querySelector(".slider-pager");
			$sliderPager.innerHTML = '';

			for (let count = 0; count < totalNumOfSlides; count += parseInt(slidesInView, 10)) {
				const pager = document.createElement('span');
				pager.className = 'pager';
				pager.dataset.index = count;
				$sliderPager.appendChild(pager);
			}
		}
	}

	function adjustSlideWidth() {
	
		const $slidesContainer = document.querySelector(slidersId + ' .slider-holder');
	
		const sliderWidth = $slidesContainer.parentElement.offsetWidth;
	
		//Remove total gap space from usable width
		const totalGapWidth = (slidesInView - 1) * slideItemGap;
		
		calculatedSlideWidth = (sliderWidth - totalGapWidth) / slidesInView;
	
		const $containers = $sliderId.querySelectorAll('.container');
	
		$containers.forEach(container => {
			container.style.width = `${calculatedSlideWidth}px`;
			container.style.flexShrink = '0';
		});
	
		if (slideAllAtOnce === "yes") {
			updateSlider('', true);
		}
	}

	function updateSlider(pager = '', instant = false) {
		if (slideAllAtOnce === 'yes') {
			
			const slideWidth = calculatedSlideWidth + slideItemGap;

			const sliderHolder = $sliderId.querySelector('.slider-holder');

			sliderHolder.style.transition = instant ? 'none' : `transform ${slideSpeed}ms ease`;
			currentIndex = instant ? 0 : currentIndex;
			sliderHolder.style.transform = `translateX(-${currentIndex * slideWidth}px)`;

			if (currentIndex === 0) {
				setTimeout(() => {
					sliderHolder.style.transition = 'none';
					sliderHolder.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
				}, slideSpeed);
			} else if (currentIndex === (parseInt(totalNumOfSlides) + parseInt(slidesInView))) {
				setTimeout(() => {
					sliderHolder.style.transition = 'none';
					currentIndex = slidesInView;
					sliderHolder.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
				}, slideSpeed);
			}
			
			if (usePagination === "yes" && useThumbnails === "yes") {
				const pagers = $sliderId.querySelectorAll('.thumbnail');
				pagers.forEach(pager => pager.classList.remove('active'));

				const pagerIndex = slideAllAtOnce === "yes" ? Math.floor(currentIndex / slidesInView) : currentIndex;
				if (pagers[pagerIndex]) pagers[pagerIndex].classList.add('active');
			} else {
				const pagers = $sliderId.querySelectorAll('.pager');
				pagers.forEach(pager => pager.classList.remove('active'));

				const pagerIndex = slideAllAtOnce === "yes" ? Math.floor(currentIndex / slidesInView) : currentIndex;
				if (pagers[pagerIndex]) pagers[pagerIndex].classList.add('active');
			}

			togglePrevButton();
		} else {
			if (pager !== '') {
				currentIndex = parseInt(currentIndex) + parseInt(slidesInView);
			}
			
			const slideWidth = calculatedSlideWidth + slideItemGap;
			const $sliderHolder = $sliderId.querySelector('.slider-holder');
			const countDivs = $sliderHolder.querySelectorAll('.container').length;
			const clones = countDivs - totalNumOfSlides;

			$sliderHolder.style.transition = instant ? 'none' : `transform ${slideSpeed}ms ease`;
			$sliderHolder.style.transform = `translateX(-${currentIndex * slideWidth}px)`;

			if (currentIndex === 0) {
				setTimeout(() => {
					$sliderHolder.style.transition = 'none';
					currentIndex = totalNumOfSlides; //Jump to the last real slide
					$sliderHolder.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
				}, slideSpeed);
			} else if (currentIndex === (parseInt(totalNumOfSlides) + parseInt(slidesInView))) {
				setTimeout(() => {
					$sliderHolder.style.transition = 'none';
					currentIndex = slidesInView; //Jump to the first real slide
					$sliderHolder.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
				}, slideSpeed);
			}

			if (usePagination === "yes" && useThumbnails === "yes") {
				const pagers = $sliderId.querySelectorAll('.thumbnail');
				pagers.forEach(pager => pager.classList.remove('active'));

				let pagerIndex = slideAllAtOnce === "yes"
					? Math.floor(currentIndex / slidesInView)
					: (currentIndex - slidesInView) % totalNumOfSlides;

				if (pagerIndex < 0) pagerIndex += totalNumOfSlides;
				if (pagerIndex >= totalNumOfSlides) pagerIndex -= totalNumOfSlides;

				if (pagers[pagerIndex]) pagers[pagerIndex].classList.add('active');
			} else if (usePagination === "yes" && useThumbnails === "no"){
				const pagers = $sliderId.querySelectorAll('.pager');
				pagers.forEach(pager => pager.classList.remove('active'));

				let pagerIndex = slideAllAtOnce === "yes"
					? Math.floor(currentIndex / slidesInView)
					: (currentIndex - slidesInView) % totalNumOfSlides;

				if (pagerIndex < 0) pagerIndex += totalNumOfSlides;
				if (pagerIndex >= totalNumOfSlides) pagerIndex -= totalNumOfSlides;

				if (pagers[pagerIndex]) pagers[pagerIndex].classList.add('active');
			}
		}
	}

	function togglePrevButton() {
		const firstPager = document.querySelector('.slider-pager .pager:first-child');
		const prevButton = document.querySelector('.prev');

		if (firstPager && firstPager.classList.contains('active')) {
			prevButton.classList.add('disabled');
		} else {
			prevButton.classList.remove('disabled');
		}
	}

	function updateActivePagerOnResize() {
		let pagerIndex = slideAllAtOnce === "yes"
			? Math.floor(currentIndex / slidesInView)
			: (currentIndex - slidesInView) % totalNumOfSlides;

		if (pagerIndex < 0) pagerIndex += totalNumOfSlides;
		if (pagerIndex >= totalNumOfSlides) pagerIndex -= totalNumOfSlides;

		const pagers = $sliderId.querySelectorAll('.pager');
		pagers.forEach(pager => pager.classList.remove('active'));
		if (pagers[pagerIndex]) pagers[pagerIndex].classList.add('active');
	}

	function nextSlide() {
	
		if (isAnimating) {
			return;
		}
	
		isAnimating = true;
	
		if (slideAllAtOnce === 'yes') {
			currentIndex += parseInt(slidesInView, 10);
			if (currentIndex >= totalNumOfSlides) {
				currentIndex = 0;
			}
		} else {
			currentIndex++;
			const nextButton = $sliderId.querySelector('.next');
			nextButton.classList.add('disabled');
			setTimeout(() => nextButton.classList.remove('disabled'), slideSpeed / 2);
		}
	
		updateSlider();
		restartAutoSlide();
	
		setTimeout(() => {
			isAnimating = false;
		}, slideSpeed);
	}

	function prevSlide() {
	
		if (isAnimating) {
			return;
		}
	
		isAnimating = true;
	
		if (slideAllAtOnce === "yes") {
			currentIndex -= slidesInView;
		
			if (currentIndex < 0) {
		
				//Get last valid grouped index
				currentIndex = Math.floor((totalNumOfSlides - 1) / slidesInView) * slidesInView;
			}
		} else {
			currentIndex--;
			if (currentIndex < 0) {
				currentIndex = totalNumOfSlides - 1;
			}
	
			const prevButton = $sliderId.querySelector('.prev');
			prevButton.classList.add('disabled');
			setTimeout(() => prevButton.classList.remove('disabled'), slideSpeed / 2);
		}
	
		updateSlider();
		restartAutoSlide();
	
		setTimeout(() => {
			isAnimating = false;
		}, slideSpeed);
	}

	function restartAutoSlide() {
		if (sliderInterval) {
			clearInterval(sliderInterval);
		}
		if (shouldAutoSlide === "yes") {
			sliderInterval = setInterval(nextSlide, pauseTime);
		}
	}

	function updatePaginationThumbHtml() {
		const $sliderPager = $sliderId.querySelector(".slider-pager");
		$sliderPager.innerHTML = '';
		
		for (let count = 0; count < totalNumOfSlides; count++) {
			let picture;
			const container = $sliderId.querySelectorAll('.slider .container')[count];
			
			if (container.querySelector('picture')) {
				picture = container.querySelector('picture');
				const pictureClone = picture.cloneNode(true);
				pictureClone.classList.add('thumbnail');
				pictureClone.setAttribute('data-index', count);
				$sliderPager.appendChild(pictureClone);
			} 
			else if (container.querySelector('video') || container.querySelector('iframe')) {
				const videoThumbnail = document.createElement('picture');
				videoThumbnail.innerHTML = videoIconSrc;
				videoThumbnail.classList.add('thumbnail');
				videoThumbnail.setAttribute('data-index', count);
				$sliderPager.appendChild(videoThumbnail);
			}
			else if (container.querySelector('object')) {
				const objectThumbnail = document.createElement('picture');
				objectThumbnail.innerHTML = FileIconSrc;
				objectThumbnail.classList.add('thumbnail');
				objectThumbnail.setAttribute('data-index', count);
				$sliderPager.appendChild(objectThumbnail);
			}
		}
	}

	function addIndexesInPictures() {
		const $sliderHolder = $sliderId.querySelector('.slider-holder');
		const $allSlides = Array.from($sliderHolder.children);
		var index = 0;
		$allSlides.forEach(slide => {
			if (!(slide.classList.contains('cloned'))) {
				var picture = slide.querySelector('picture') || slide.querySelector('video') || slide.querySelector('iframe') || slide.querySelector('object');
				picture.setAttribute('data-index', index);
				index++;
			}
		});
	}

	function addImagesInPagers() {
		const $sliderPagerHolder = $sliderId.querySelector('.slider-pager');
		const $allPagers = Array.from($sliderPagerHolder.children);
		const $sliderHolder = $sliderId.querySelector('.slider-holder');
		const $allSlides = Array.from($sliderHolder.children);
	
		$allPagers.forEach(thumbnail => {
			if (thumbnail.classList.contains('thumbnail')) {
				const dataIndexThumb = thumbnail.dataset.index;
	
				$allSlides.forEach(slide => {
					if (!slide.classList.contains('cloned')) {
						const picture = slide.querySelector('picture') || slide.querySelector('video') || slide.querySelector('iframe') || slide.querySelector('object');
						if (!picture) return;
						const dataIndexPic = picture.dataset.index;
	
						if (parseInt(dataIndexPic) === parseInt(dataIndexThumb)) {
							if (picture.tagName.toLowerCase() === 'picture') {
								const sourceElementsInSlide = Array.from(picture.querySelectorAll('source'));
								const sourceElementsInThumbnail = Array.from(thumbnail.querySelectorAll('source'));
	
								sourceElementsInSlide.forEach(slideSource => {
									const type = slideSource.getAttribute('type');
									const media = slideSource.getAttribute('media');
									const srcset = slideSource.getAttribute('srcset');
	
									if (srcset) {
										sourceElementsInThumbnail.forEach(thumbnailSource => {
											if (
												thumbnailSource.getAttribute('type') === type &&
												thumbnailSource.getAttribute('media') === media
											) {
												thumbnailSource.setAttribute('srcset', srcset);
											}
										});
									}
								});
								
								const imgSrc = picture.querySelector('img').srcset;
								if (imgSrc) {
									const picImgElement = thumbnail.querySelector('img');
									picImgElement.srcset = imgSrc;
								}
							}
						}
					}
				});
			}
		});
	}
	
	$sliderId.querySelector('.next').addEventListener('click', nextSlide);
	$sliderId.querySelector('.prev').addEventListener('click', prevSlide);

	if (shouldAutoSlide === "yes") {
		setTimeout(() => {
			updateSlider();
		}, 50);
		sliderInterval = setInterval(nextSlide, pauseTime);
	}

	if (usePagination === "yes" && useThumbnails === "yes") {
		if (slideAllAtOnce === 'yes') {
			const $sliderPager = $sliderId.querySelector(".slider-pager");
			$sliderPager.addEventListener('click', (e) => {
				const thumbnail = e.target.closest('.thumbnail'); //Find the closest `.thumbnail` element
				if (thumbnail) {
					currentIndex = parseInt(thumbnail.dataset.index, 10);
					updateSlider();
					restartAutoSlide();
				}
			});
		} else {
			updatePaginationThumbHtml();
			addIndexesInPictures();
			addImagesInPagers();
			$sliderId.querySelectorAll('.thumbnail').forEach(thumbnail => {
				thumbnail.addEventListener('click', function () {
					currentIndex = parseInt(this.dataset.index);
					updateSlider('pager');
					restartAutoSlide();
				});
			});
		}
	}

	if (usePagination === "yes" && useThumbnails === "no") {
		if (slideAllAtOnce === 'yes') {
			const $sliderPager = $sliderId.querySelector(".slider-pager");
			$sliderPager.addEventListener('click', (e) => {
				if (e.target.classList.contains('pager')) {
					currentIndex = parseInt(e.target.dataset.index, 10);
					updateSlider();
					restartAutoSlide();
				}
			});
		} else {
			$sliderId.querySelectorAll('.pager').forEach(pager => {
				pager.addEventListener('click', function () {
					currentIndex = parseInt(this.dataset.index);
					updateSlider('pager');
					restartAutoSlide();
				});
			});
		}
	}

	adjustSlidesInView();

	if (slideAllAtOnce === 'yes') {
		cloneSlides();
	}

	window.addEventListener('resize',adjustSlidesInView);

	window.addEventListener('load', function () {
		if (slideAllAtOnce === 'no') {
			updateSlider('', true);
		}
	});

	const $sliderHolder = $sliderId.querySelector('.slider-holder');
	$sliderHolder.addEventListener('mouseenter', function () {
		if (sliderInterval) {
			clearInterval(sliderInterval);
		}
	});
	$sliderHolder.addEventListener('mouseleave', function () {
		if (shouldAutoSlide === "yes") {
			sliderInterval = setInterval(nextSlide, pauseTime);
		}
	});

	let touchStartX = 0;
	let touchEndX = 0;

	$sliderId.addEventListener('touchstart', function (e) {
		touchStartX = e.touches[0].clientX;
	}, { passive: true });

	$sliderId.addEventListener('touchend', function (e) {
		touchEndX = e.changedTouches[0].clientX;

		if (touchEndX < touchStartX) {
			nextSlide();
		} else if (touchEndX > touchStartX) {
			prevSlide();
		}
	}, { passive: true });
}
</script>
<?php } ?>