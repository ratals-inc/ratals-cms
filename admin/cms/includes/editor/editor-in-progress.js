/*
Copyright (c) 2025-2026 Ratals Inc.
Licensed under the Apache License, Version 2.0
Full License & Terms: https://www.ratals.com/license/
*/
document.addEventListener("DOMContentLoaded", function () {

	const editorParents = document.getElementsByClassName('editor-parent');

	// Loop through each 'editor-parent' element
	Array.from(editorParents).forEach(editorParent => {
		const editorElements = editorParent.querySelectorAll('*');

		editorElements.forEach(parentElement => {
			if (parentElement.classList.contains('btn-delete') || parentElement.tagName === 'STYLE' || parentElement.classList.contains('btn-handle') || parentElement.classList.contains('btn-handle-span')) {
				return;
			}
			// Check if the parent element already has an 'element-info' div
			const existingElementInfo = parentElement.querySelector('.element-info');
			if (existingElementInfo) {
				existingElementInfo.remove();
			}
			// Create a new div with class name 'element-info'
			const elementInfoDiv = document.createElement('element'); // Corrected from 'element' to 'div'
			elementInfoDiv.className = 'element-info';

			// Get the parent node type and class names
			const parentNodeType = parentElement.nodeName;
			const classNames = parentElement.className;

			// If 'element-highlighter' is in the class names, remove it
			parentElement.classList.remove('element-highlighter');

			// Set the inner content of the new div
			elementInfoDiv.innerHTML = `${parentNodeType}, ${classNames}`;

			// Prepend the new div to the parent element
			parentElement.prepend(elementInfoDiv);
		});
	});

	function highlightDivsWithBorderInit() {
		const divs = [];
		Array.from(editorParents).forEach(editorParent => {
			divs.push(...editorParent.querySelectorAll('*'));
		});

		divs.forEach(div => {
			if (
				!['BR', 'ELEMENT', 'BUTTON', 'STYLE'].includes(div.tagName.toUpperCase()) &&
				!div.classList.contains('btn-handle-span')
			) {
				div.classList.add('element-highlighter');
			}
		});
	}

	highlightDivsWithBorderInit();

	const divObserver = new MutationObserver(() => {
		highlightDivsWithBorderInit();
	});

	// Observe each 'editor-parent' element
	Array.from(editorParents).forEach(editorParent => {
		divObserver.observe(editorParent, { childList: true, subtree: true });
	});

	var editor = null;
	let selectedRange = null;
	let activeElement;
	let undoStack = [];
	let redoStack = [];
	let draggedElement = null;

	const editors = document.querySelectorAll('.editor-container');

	editors.forEach(ed => {
		editor = ed;
		ed.addEventListener('click', function (event) {
			editor = this;

			var selection = window.getSelection();
			if (event.target.classList.contains('btn-delete')) {
				if (confirm('Are you really want to remove this element?')) {
					event.target.parentElement.remove();
				}
			}
			if (!event.target.classList.contains('btn-delete')) {
				addHighlight(event);
			}
			if (event.ctrlKey) {
				handleClick(event);
			}
			if (selection.rangeCount > 0) {
				var selectedRange = selection.getRangeAt(0);
				activeElement = selectedRange.startContainer; // The node where the cursor is
				emptyStyleForm();

				// Ensure the activeElement exists
				if (activeElement) {
					// If activeElement is a text node, get the parent element
					var $activeElement = $(activeElement.nodeType === 3 ? activeElement.parentNode : activeElement);
					// Determine the current editor container for this active element
					var $currentEditorContainer = $activeElement.closest('.editor-container');
					// Initialize the styles map
					var styleMap = {};

					// Get the current class name(s) of the active element
					var currentClass = $activeElement.attr('class');
					if (currentClass) {
						// Look for all classes matching the pattern "content-\d+" (e.g., content-0, content-1, etc.)
						var classMatches = currentClass.match(/content-\d+/g); // Get **all** matches, not just one

						if (classMatches) {
							// Limit style lookup only to style tags inside the current editor container
							var $styleTags = $currentEditorContainer.find('style');

							if ($styleTags.length > 0) {
								$styleTags.each(function () {
									var styleContent = $(this).html(); // Get CSS content from each <style> tag
									classMatches.forEach(function (className) { // Loop through all matched classes
										var classRuleRegex = new RegExp('\\.' + className + ' {([^}]*)}', 'g');
										var match;

										// Extract multiple occurrences of the same class
										while ((match = classRuleRegex.exec(styleContent)) !== null) {
											var styles = match[1].trim().split(';');

											styles.forEach(function (style) {
												if (style.trim()) {
													var styleParts = style.split(':');
													var property = styleParts[0].trim();
													var value = styleParts[1] ? styleParts[1].trim() : '';

													// Store or update the style properties
													styleMap[property] = value; // Merging multiple class styles
												}
											});
										}
									});
								});
							}
						}
					}

					// Now loop through each form field in the current editor and set its value based on the collected styles
					$currentEditorContainer.find('.editor-styles form')
						.find('input, select, textarea')
						.each(function () {
							var fieldProperty = $(this).attr('name'); // Assuming the field name corresponds to the style property

							if (styleMap[fieldProperty]) {
								var fieldValue = fieldProperty + ': ' + styleMap[fieldProperty].replace(/\s*!important$/, ''); // Strip ' !important' if present
								$(this).val(fieldValue);

								// If it's a select field, select the correct option
								if ($(this).is('select')) {
									var fieldNameBeforeColon = fieldProperty.split(':')[0].trim(); // Get the field name before ':'
									$(this).find('option').each(function () {
										var optionValue = $(this).val().trim();
										if (optionValue === fieldNameBeforeColon) {
											$(this).prop('selected', true);
										}
									});
								}
							}
						});
				} else {
					console.log('No active element found!');
				}
			}
		});

		ed.addEventListener('keypress', function (e) {
			if (e.code === 'Enter') {
				e.preventDefault(); //Prevent the default behavior (newline)
				const selection = window.getSelection();
				if (!selection || selection.rangeCount === 0) return;

				const range = selection.getRangeAt(0);
				const currentContainer = range.startContainer;
				if (currentContainer.parentElement.tagName === 'BUTTON') return;
				let currentHeader = null;
				let currentLi = null;
				let currentP = null;
				let parentUl = null;

				//Check if the current container is inside a header tag (h1, h2, h3, etc.)
				if (currentContainer.nodeType === Node.TEXT_NODE || currentContainer.nodeType === Node.ELEMENT_NODE) {
					let parentElement = currentContainer.nodeType === Node.TEXT_NODE ? currentContainer.parentNode : currentContainer;
					//Traverse up the DOM tree to find the closest <h1>, <h2>, etc.
					while (parentElement && !['H1', 'H2', 'H3', 'H4', 'H5', 'H6'].includes(parentElement.nodeName)) {
						parentElement = parentElement.parentNode;
					}
					//If we find a header element (h1, h2, etc.), assign it to currentHeader
					if (parentElement && ['H1', 'H2', 'H3', 'H4', 'H5', 'H6'].includes(parentElement.nodeName)) {
						currentHeader = parentElement;
					}
				}

				if (currentHeader) {
					currentHeader.querySelector('element').remove();
					const headerTag = currentHeader.tagName.toLowerCase();
					const cursorPosition = range.startOffset;
					let headerText = currentHeader.innerText;

					// Remove the highlighted text
					headerText = headerText
						.replace(/Delete/g, '')
						.replace(/BUTTON,/g, '')
						.replace(/highlighted/g, '')
						.replace(/STRONG,/g, '')
						.replace(/Drag/g, '')
						.replace(/EM,/g, '')
						.replace(/U,/g, '')
						.replace(/S,/g, '')
						.replace(/\n/g, '')
						.replace(/P,/g, '')
						.replace(/New div!,/g, '')
						.replace(/New button!,/g, '')
						.replace(/^\s+|\s+$/g, '');

					if (cursorPosition === headerText.length) {
						// If the cursor is at the end of the header, create a new <p> below the header
						const newParagraph = document.createElement('p');
						// newParagraph.setAttribute('draggable', true);
						newParagraph.innerHTML = '<br>'; // Add a line break in the new <p>
						newParagraph.classList.add('highlighted');
						// Insert the new <p> after the current header
						currentHeader.parentNode.insertBefore(newParagraph, currentHeader.nextSibling);

						const btnDel = document.createElement('button');
						btnDel.classList.add('btn-delete');
						btnDel.textContent = 'Delete';
						newParagraph.insertBefore(btnDel, newParagraph.firstChild);

						const btnHandle = document.createElement('button');
						btnHandle.classList.add('btn-handle');
						btnHandle.setAttribute("draggable", true);

						// Create a span inside the button to prevent text dragging
						const span = document.createElement('span');
						span.classList.add('btn-handle-span');
						span.textContent = 'Drag';
						span.setAttribute("draggable", false);

						btnHandle.appendChild(span);
						newParagraph.insertBefore(btnHandle, newParagraph.firstChild);

						currentHeader.classList.remove('highlighted');
						currentHeader.querySelectorAll('.btn-delete').forEach(btn => btn.remove());
						currentHeader.querySelectorAll('.btn-handle').forEach(btn => btn.remove());

						// Move the cursor to the new <p>
						range.setStart(newParagraph, 0);
						range.collapse(true);
						selection.removeAllRanges();
						selection.addRange(range);

					} else {

						var isTag = false;

						currentHeader.childNodes.forEach(node => {
							if (node.nodeName === "STRONG" || node.nodeName === "EM" || node.nodeName === "U" || node.nodeName === "S" || node.nodeName === "DIV" || node.nodeName === "BUTTON") {
								isTag = true;
							}
						});

						if (isTag) {
							let selection = window.getSelection();
							if (!selection.rangeCount) return;

							let range = selection.getRangeAt(0);
							let offset = range.startOffset;
							let node = range.startContainer;

							if (node.nodeType === Node.TEXT_NODE) {
								let textBefore = node.nodeValue.slice(0, offset);
								let textAfter = node.nodeValue.slice(offset);

								let textNodeBefore = document.createTextNode(textBefore);
								let textNodeAfter = document.createTextNode(textAfter);

								let newHeader = document.createElement(currentHeader.tagName);

								// newHeader.setAttribute('draggable', true);

								// Replace the original text node with the first part
								node.nodeValue = textBefore;

								// Find the parent of the text node
								let parent = node.parentNode;

								if (parent !== currentHeader) {
									let clonedParent = parent.cloneNode(false); // Clone inline parent (e.g., <strong>)

									// Append remaining text inside <strong> to the cloned parent
									clonedParent.appendChild(textNodeAfter);

									// Move all remaining siblings inside the inline parent
									let sibling = node.nextSibling;
									while (sibling) {
										let nextSibling = sibling.nextSibling; // Store next sibling before moving it
										clonedParent.appendChild(sibling);
										sibling = nextSibling;
									}

									newHeader.appendChild(clonedParent);

									// Move remaining inline elements to the new header
									while (node.parentNode && node.parentNode !== currentHeader && node.parentNode.nextSibling) {
										newHeader.appendChild(node.parentNode.nextSibling);
									}
								} else {
									newHeader.appendChild(textNodeAfter);

									// Move remaining inline elements to the new header
									while (node.nextSibling) {
										newHeader.appendChild(node.nextSibling);
									}
								}
								newHeader.classList.add('highlighted');
								if (!newHeader.innerHTML.trim()) {
									newHeader.innerHTML = "<br>"; // Avoid empty headers
								}

								currentHeader.parentNode.insertBefore(newHeader, currentHeader.nextSibling);

								const btnDel = document.createElement('button');
								btnDel.classList.add('btn-delete');
								btnDel.textContent = 'Delete';
								newHeader.insertBefore(btnDel, newHeader.firstChild);

								const btnHandle = document.createElement('button');
								btnHandle.classList.add('btn-handle');
								btnHandle.setAttribute("draggable", true);

								// Create a span inside the button to prevent text dragging
								const span = document.createElement('span');
								span.classList.add('btn-handle-span');
								span.textContent = 'Drag';
								span.setAttribute("draggable", false);

								btnHandle.appendChild(span);
								newHeader.insertBefore(btnHandle, newHeader.firstChild);

								currentHeader.classList.remove('highlighted');
								currentHeader.querySelectorAll('.highlighted').forEach(btn => btn.classList.remove('highlighted'));
								currentHeader.querySelectorAll('.btn-delete').forEach(btn => btn.remove());
								currentHeader.querySelectorAll('.btn-handle').forEach(btn => btn.remove());
								// Set cursor at the start of the new header
								let newRange = document.createRange();
								newRange.setStart(newHeader, 0);
								newRange.collapse(true);
								// trackCursorPosition();
								selection.removeAllRanges();
								selection.addRange(newRange);

							}
						} else {
							// Split the header into two
							const textBeforeCursor = headerText.slice(0, cursorPosition); // Text before the cursor
							const textAfterCursor = headerText.slice(cursorPosition); // Text after the cursor

							// Update the original header with text before the cursor
							currentHeader.innerHTML = textBeforeCursor.replace(/\n/g, "<br>");

							// Create a new header with the text after the cursor
							const newHeader = document.createElement(headerTag);
							newHeader.classList.add('highlighted');
							// newHeader.setAttribute('draggable', true);
							newHeader.innerHTML = textAfterCursor.replace(/\n/g, "<br>");

							// Insert the new header after the original one
							currentHeader.parentNode.insertBefore(newHeader, currentHeader.nextSibling);

							// If the original header is empty after splitting, make sure it has a <br>
							if (textBeforeCursor.trim() === "") {
								currentHeader.innerHTML = "<br>";
							}

							const btnDel = document.createElement('button');
							btnDel.classList.add('btn-delete');
							btnDel.textContent = 'Delete';
							newHeader.insertBefore(btnDel, newHeader.firstChild);

							const btnHandle = document.createElement('button');
							btnHandle.classList.add('btn-handle');
							btnHandle.setAttribute("draggable", true);

							// Create a span inside the button to prevent text dragging
							const span = document.createElement('span');
							span.classList.add('btn-handle-span');
							span.textContent = 'Drag';
							span.setAttribute("draggable", false);

							btnHandle.appendChild(span);
							newHeader.insertBefore(btnHandle, newHeader.firstChild);

							currentHeader.classList.remove('highlighted');
							currentHeader.querySelectorAll('.btn-delete').forEach(btn => btn.remove());
							currentHeader.querySelectorAll('.btn-handle').forEach(btn => btn.remove());

							range.setStart(newHeader, 0);
							range.collapse(true);
							selection.removeAllRanges();
							selection.addRange(range);

							// removeAllRangesForcefully();
						}
					}
				} else {
					//Check if the current container is inside a <p>, <a>, <span>, or <li>
					if (currentContainer.nodeType === Node.TEXT_NODE || currentContainer.nodeType === Node.ELEMENT_NODE) {
						let parentElement = currentContainer.nodeType === Node.TEXT_NODE ? currentContainer.parentNode : currentContainer;
						//Traverse up the DOM tree to find the closest <p>, <a>, <span>, or <li> element
						while (parentElement && parentElement.nodeName !== 'P' && parentElement.nodeName !== 'LI' && parentElement.nodeName !== 'A') {
							parentElement = parentElement.parentNode;
						}
						//If we find a <p> element, assign it to currentP
						if (parentElement && parentElement.nodeName === 'P') {
							currentP = parentElement;
						}
						//If we find a <li> element, assign it to currentLi
						if (parentElement && parentElement.nodeName === 'LI') {
							currentLi = parentElement;
							parentUl = currentLi.parentNode;
						}
						//If we find a <a> element, treat it as a block element (like <p>)
						if (parentElement && parentElement.nodeName === 'A') {
							currentP = parentElement; //Treat link like a paragraph for handling
						}
					}
					//If we're inside a <p> tag (splitting a <p>)
					if (currentP) {
						if (currentP.querySelector('element')) {
							currentP.querySelector('element').remove();
						}
						const cursorPosition = range.startOffset;
						var pText = currentP.innerText;
						pText = pText
							.replace(/Delete/g, '')
							.replace(/BUTTON,/g, '')
							.replace(/highlighted/g, '')
							.replace(/STRONG,/g, '')
							.replace(/Drag/g, '')
							.replace(/EM,/g, '')
							.replace(/U,/g, '')
							.replace(/S,/g, '')
							.replace(/\n/g, '')
							.replace(/P,/g, '')
							.replace(/New div!,/g, '')
							.replace(/New button!,/g, '')
							.replace(/^\s+|\s+$/g, '');
						//If cursor is at the end of the <p> (no more text after it)
						if (cursorPosition === pText.length) {
							//Create a new <p> tag below the current <p>
							const newParagraph = document.createElement('p');
							newParagraph.classList.add('highlighted');
							// newParagraph.setAttribute('draggable', true);
							newParagraph.innerHTML = '<br>'; // Add a line break in the new <p>

							const btnDel = document.createElement('button');
							btnDel.classList.add('btn-delete');
							btnDel.textContent = 'Delete';
							newParagraph.insertBefore(btnDel, newParagraph.firstChild);

							const btnHandle = document.createElement('button');
							btnHandle.classList.add('btn-handle');
							btnHandle.setAttribute("draggable", true);

							// Create a span inside the button to prevent text dragging
							const span = document.createElement('span');
							span.classList.add('btn-handle-span');
							span.textContent = 'Drag';
							span.setAttribute("draggable", false);

							btnHandle.appendChild(span);
							newParagraph.insertBefore(btnHandle, newParagraph.firstChild);

							//Insert the new <p> after the original one
							currentP.classList.remove('highlighted');
							currentP.querySelectorAll('.btn-delete').forEach(btn => btn.remove());
							currentP.querySelectorAll('.btn-handle').forEach(btn => btn.remove());
							// Insert the new <p> after the current <p>
							currentP.parentNode.insertBefore(newParagraph, currentP.nextSibling);
							// Move the cursor to the new <p>
							range.setStart(newParagraph, 0);
							range.collapse(true);
							selection.removeAllRanges();
							selection.addRange(range);

							// removeAllRangesForcefully();
						} else if (currentP.innerHTML !== '<br>') {
							//Split the <p> into two
							var isTag = false;

							currentP.childNodes.forEach(node => {
								if (node.nodeName === "STRONG" || node.nodeName === "EM" || node.nodeName === "U" || node.nodeName === "S" || node.nodeName === "A" || node.nodeName === "SPAN" || node.nodeName === "DIV" || node.nodeName === "BUTTON") {
									isTag = true;
								}
							});

							if (isTag) {
								let selection = window.getSelection();
								if (!selection.rangeCount) return;

								let range = selection.getRangeAt(0);
								let offset = range.startOffset;
								let node = range.startContainer;

								if (node.nodeType === Node.TEXT_NODE) {
									let textBefore = node.nodeValue.slice(0, offset);
									let textAfter = node.nodeValue.slice(offset);

									let textNodeBefore = document.createTextNode(textBefore);
									let textNodeAfter = document.createTextNode(textAfter);

									let newParagraph = document.createElement("p");
									newParagraph.classList.add('highlighted');
									// newParagraph.setAttribute('draggable', true);

									// Replace the original text node with the first part
									node.nodeValue = textBefore;

									// Find the parent of the text node
									let parent = node.parentNode;

									// If inside an inline element (e.g., <strong>), wrap properly
									if (parent !== currentP) {
										let clonedParent = parent.cloneNode(false); // Clone inline parent (e.g., <strong>)

										// Append remaining text inside <strong> to the cloned parent
										clonedParent.appendChild(textNodeAfter);

										// Move all remaining siblings inside the inline parent
										let sibling = node.nextSibling;
										while (sibling) {
											let nextSibling = sibling.nextSibling; // Store next sibling before moving it
											clonedParent.appendChild(sibling);
											sibling = nextSibling;
										}

										newParagraph.appendChild(clonedParent);

										// Move remaining inline elements (outside <strong>) to the new paragraph
										while (node.parentNode && node.parentNode !== currentP && node.parentNode.nextSibling) {
											newParagraph.appendChild(node.parentNode.nextSibling);
										}
									} else {
										newParagraph.appendChild(textNodeAfter);

										// Move remaining inline elements to the new paragraph
										while (node.nextSibling) {
											newParagraph.appendChild(node.nextSibling);
										}
									}

									if (!newParagraph.innerHTML.trim()) {
										newParagraph.innerHTML = "<br>"; // Avoid empty paragraphs
									}
									const btnDel = document.createElement('button');
									btnDel.classList.add('btn-delete');
									btnDel.textContent = 'Delete';
									newParagraph.insertBefore(btnDel, newParagraph.firstChild);

									const btnHandle = document.createElement('button');
									btnHandle.classList.add('btn-handle');
									btnHandle.setAttribute("draggable", true);

									// Create a span inside the button to prevent text dragging
									const span = document.createElement('span');
									span.classList.add('btn-handle-span');
									span.textContent = 'Drag';
									span.setAttribute("draggable", false);

									btnHandle.appendChild(span);
									newParagraph.insertBefore(btnHandle, newParagraph.firstChild);

									//Insert the new <p> after the original one
									currentP.classList.remove('highlighted');
									currentP.querySelectorAll('.highlighted').forEach(btn => btn.classList.remove('highlighted'));
									currentP.querySelectorAll('.btn-delete').forEach(btn => btn.remove());
									currentP.querySelectorAll('.btn-handle').forEach(btn => btn.remove());
									currentP.parentNode.insertBefore(newParagraph, currentP.nextSibling);

									// Set cursor at the start of the new paragraph
									let newRange = document.createRange();
									newRange.setStart(newParagraph, 0);
									newRange.collapse(true);

									selection.removeAllRanges();
									selection.addRange(newRange);
								}
							} else {
								// If donot contain any bold, italic, underline, strike text in current p tag
								const textBeforeCursor = pText.slice(0, cursorPosition); //Text before the cursor
								const textAfterCursor = pText.slice(cursorPosition); //Text after the cursor
								//Update the original <p> with text before the cursor
								currentP.innerHTML = textBeforeCursor.replace(/\n/g, "<br>");
								//Create a new <p> with the text after the cursor
								const newParagraph = document.createElement('p');
								newParagraph.classList.add('highlighted');
								// newParagraph.setAttribute('draggable', true);
								newParagraph.innerHTML = textAfterCursor.replace(/\n/g, "<br>");
								//Handle the case where the new <p> is empty (add <br> if necessary)
								if (newParagraph.innerHTML.trim() === "") {
									newParagraph.innerHTML = "<br>"; //Add <br> if the <p> is empty
								}

								const btnDel = document.createElement('button');
								btnDel.classList.add('btn-delete');
								btnDel.textContent = 'Delete';
								newParagraph.insertBefore(btnDel, newParagraph.firstChild);

								const btnHandle = document.createElement('button');
								btnHandle.classList.add('btn-handle');
								btnHandle.setAttribute("draggable", true);

								// Create a span inside the button to prevent text dragging
								const span = document.createElement('span');
								span.classList.add('btn-handle-span');
								span.textContent = 'Drag';
								span.setAttribute("draggable", false);

								btnHandle.appendChild(span);
								newParagraph.insertBefore(btnHandle, newParagraph.firstChild);

								//Insert the new <p> after the original one
								currentP.classList.remove('highlighted');
								currentP.querySelectorAll('.btn-delete').forEach(btn => btn.remove());
								currentP.querySelectorAll('.btn-handle').forEach(btn => btn.remove());
								currentP.parentNode.insertBefore(newParagraph, currentP.nextSibling);
								//If there is no text before the cursor and the original <p> is empty, make sure it has a <br>
								if (textBeforeCursor.trim() === "") {
									currentP.innerHTML = "<br>"; //Add a <br> if the original <p> is empty
								}
								//Move the cursor to the start of the new <p>
								range.setStart(newParagraph, 0); //Set the cursor at the start of the new <p>
								range.collapse(true);
								selection.removeAllRanges();
								selection.addRange(range);

							}
						} else {
							//Create a new <p> where the cursor is
							const newParagraph = document.createElement('p');
							currentP.classList.add('highlighted');
							// newParagraph.setAttribute('draggable', true);
							newParagraph.innerHTML = '<br>'; //Add a line break in the new <p>
							//Insert the new <p> at the cursor position
							const currentNode = selection.focusNode; //Get the node where the cursor is located
							const currentParent = currentNode.parentNode; //Get the parent of the current node

							const btnDel = document.createElement('button');
							btnDel.classList.add('btn-delete');
							btnDel.textContent = 'Delete';
							newParagraph.insertBefore(btnDel, newParagraph.firstChild);

							const btnHandle = document.createElement('button');
							btnHandle.classList.add('btn-handle');
							btnHandle.setAttribute("draggable", true);

							// Create a span inside the button to prevent text dragging
							const span = document.createElement('span');
							span.classList.add('btn-handle-span');
							span.textContent = 'Drag';
							span.setAttribute("draggable", false);

							btnHandle.appendChild(span);
							newParagraph.insertBefore(btnHandle, newParagraph.firstChild);

							//Insert the new <p> after the original one
							currentP.classList.remove('highlighted');
							currentP.querySelectorAll('.btn-delete').forEach(btn => btn.remove());
							currentP.querySelectorAll('.btn-handle').forEach(btn => btn.remove());
							//Insert the new <p> after the current node
							currentParent.insertBefore(newParagraph, currentNode.nextSibling);
							//Move the cursor to the new <p>
							range.setStart(newParagraph, 0); //Set the cursor at the start of the new <p>
							range.collapse(true);
							selection.removeAllRanges();
							selection.addRange(range);

							// removeAllRangesForcefully();
						}
					} else if (currentLi) {
						//If we're inside a non-empty <li>, create a new <li> below the current one
						if (!isEmptyLi(currentLi)) {
							const newListItem = document.createElement('li');
							newListItem.classList.add('highlighted');
							// newListItem.setAttribute('draggable', true);
							newListItem.innerHTML = '<br>'; //Insert a line break

							const btnDel = document.createElement('button');
							btnDel.classList.add('btn-delete');
							btnDel.textContent = 'Delete';
							newListItem.insertBefore(btnDel, newListItem.firstChild);

							const btnHandle = document.createElement('button');
							btnHandle.classList.add('btn-handle');
							btnHandle.setAttribute("draggable", true);

							// Create a span inside the button to prevent text dragging
							const span = document.createElement('span');
							span.classList.add('btn-handle-span');
							span.textContent = 'Drag';
							span.setAttribute("draggable", false);

							btnHandle.appendChild(span);
							newListItem.insertBefore(btnHandle, newListItem.firstChild);

							//Insert the new <p> after the original one
							currentLi.classList.remove('highlighted');
							parentUl.querySelectorAll('.btn-delete').forEach(btn => btn.remove());
							parentUl.querySelectorAll('.btn-handle').forEach(btn => btn.remove());
							//Insert the new <li> after the current one
							parentUl.insertBefore(newListItem, currentLi.nextSibling);
							//Move the cursor to the new <li>
							range.setStart(newListItem, 0); //Set the cursor at the start of the new <li>
							range.collapse(true);
							selection.removeAllRanges();
							selection.addRange(range);

						} else {
							// If the <li> is empty, remove it and create a new <p> below the <ul>

							// Remove buttons only from currentLi before deleting it
							currentLi.querySelectorAll('.btn-delete, .btn-handle').forEach(btn => btn.remove());
							parentUl.removeChild(currentLi);

							// Create a new <p> tag
							const newParagraph = document.createElement('p');
							newParagraph.innerHTML = '<br>'; // Insert a line break

							// Create the Delete button
							const btnDel = document.createElement('button');
							btnDel.classList.add('btn-delete');
							btnDel.textContent = 'Delete';
							newParagraph.insertBefore(btnDel, newParagraph.firstChild);

							// Create the Handle button
							const btnHandle = document.createElement('button');
							btnHandle.classList.add('btn-handle');
							btnHandle.setAttribute("draggable", true);

							// Create a span inside the button to prevent text dragging
							const span = document.createElement('span');
							span.classList.add('btn-handle-span');
							span.textContent = 'Drag';
							span.setAttribute("draggable", false);

							btnHandle.appendChild(span);
							newParagraph.insertBefore(btnHandle, newParagraph.firstChild);

							// Ensure the parent of <ul> exists before inserting
							if (parentUl.parentNode) {
								parentUl.parentNode.insertBefore(newParagraph, parentUl.nextSibling);
							}

							// Move the cursor to the new <p>
							range.setStart(newParagraph, 0);
							range.collapse(true);
							selection.removeAllRanges();
							selection.addRange(range);
						}
					} else {
						const textBeforeCursor = range.startContainer.textContent.slice(0, range.startOffset);
						const textAfterCursor = range.startContainer.textContent.slice(range.startOffset);
						//Get the parent element of the current container
						let parentElement = range.startContainer.parentNode;

						if (parentElement.classList.contains('editor-wrapper')) {
							e.preventDefault();
							return;
						}

						//Check if we have text before and after the cursor
						if (textBeforeCursor !== '' && textAfterCursor !== '') {
							// Create a new paragraph for the text before the cursor
							const beforeParagraph = document.createElement('p');
							beforeParagraph.textContent = textBeforeCursor;

							// Create a new paragraph for the text after the cursor
							const afterParagraph = document.createElement('p');
							afterParagraph.classList.add('highlighted');
							afterParagraph.textContent = textAfterCursor;

							// Store reference to the original parent
							const originalParent = range.startContainer.parentNode;

						   // Clear only the text content, not the whole parent
							if (range.startContainer.nodeType === Node.TEXT_NODE) {
								range.startContainer.textContent = '';
							}

						   // Insert the before paragraph
							originalParent.insertBefore(beforeParagraph, range.startContainer);

						   // Insert the after paragraph
							originalParent.insertBefore(afterParagraph, beforeParagraph.nextSibling);

						   // Create Delete button
							const btnDel = document.createElement('button');
							btnDel.classList.add('btn-delete');
							btnDel.textContent = 'Delete';

						   // Create Drag button
							const btnHandle = document.createElement('button');
							btnHandle.classList.add('btn-handle');
							btnHandle.setAttribute('draggable', true);

						   // Create a span inside the button to prevent text dragging
							const span = document.createElement('span');
							span.classList.add('btn-handle-span');
							span.textContent = 'Drag';
							span.setAttribute('draggable', false);

						   // Append elements correctly
							btnHandle.appendChild(span);
							afterParagraph.insertBefore(btnDel, afterParagraph.firstChild);
							afterParagraph.insertBefore(btnHandle, afterParagraph.firstChild);

						   // Remove buttons from original parent
							originalParent.classList.remove('highlighted');
							originalParent.querySelectorAll('.btn-delete').forEach(btn => btn.remove());
							originalParent.querySelectorAll('.btn-handle').forEach(btn => btn.remove());

						   // Move the cursor to the beginning of the after paragraph
							const newRange = document.createRange();
							newRange.selectNodeContents(afterParagraph);
							newRange.collapse(true); // Collapse to the start of the after paragraph
							selection.removeAllRanges();
							selection.addRange(newRange);
						}
						//Handle case where there is only text before the cursor
						else if (textBeforeCursor !== '') {
							// Step 1: Create a <p><br></p> element for the line break
							const breakParagraph = document.createElement('p');
							breakParagraph.classList.add('highlighted');
							breakParagraph.innerHTML = '<br>'; // This creates a <p><br></p> for a new line

							// Step 2: Create a new paragraph for the text before the cursor (if any)
							const beforeParagraph = document.createElement('p');
							beforeParagraph.textContent = textBeforeCursor; // Insert the text before the cursor

							// Step 3: Clear the current content in the parent element
							if (range.startContainer.nodeType === Node.TEXT_NODE) {
								range.startContainer.textContent = ''; // Clear only text content
							}

							// Create Delete button
							const btnDel = document.createElement('button');
							btnDel.classList.add('btn-delete');
							btnDel.textContent = 'Delete';

							// Create Drag button
							const btnHandle = document.createElement('button');
							btnHandle.classList.add('btn-handle');
							btnHandle.setAttribute('draggable', true);

							// Create a span inside the button to prevent text dragging
							const span = document.createElement('span');
							span.classList.add('btn-handle-span');
							span.textContent = 'Drag';
							span.setAttribute('draggable', false);

							// Append elements correctly
							btnHandle.appendChild(span);
							breakParagraph.insertBefore(btnDel, breakParagraph.firstChild);
							breakParagraph.insertBefore(btnHandle, breakParagraph.firstChild);

							// Remove buttons from original parent
							parentElement.classList.remove('highlighted');
							parentElement.querySelectorAll('.btn-delete').forEach(btn => btn.remove());
							parentElement.querySelectorAll('.btn-handle').forEach(btn => btn.remove());

							// **Insert elements at cursor position instead of appending at the bottom**
							range.insertNode(breakParagraph);
							range.insertNode(beforeParagraph);

							// Step 7: Move the cursor to the start of the <p><br></p> (the new line break)
							const newRange = document.createRange();
							newRange.setStart(breakParagraph, 0); // Set cursor to the start of the <p><br>
							newRange.setEnd(breakParagraph, 0); // Collapse the range
							selection.removeAllRanges();
							selection.addRange(newRange);
						}
						//Handle case where there is only text after the cursor
						else if (textAfterCursor !== '') {
							// Step 1: Create a <p><br></p> element for the line break
							const breakParagraph = document.createElement('p');
							breakParagraph.innerHTML = '<br>'; // This creates a <p><br></p> for a new line

							// Step 2: Create a new paragraph for the text after the cursor
							const nextParagraph = document.createElement('p');
							nextParagraph.classList.add('highlighted');
							nextParagraph.textContent = textAfterCursor; // Insert the text after the cursor

							// Step 3: Clear the current content in the parent element
							if (range.startContainer.nodeType === Node.TEXT_NODE) {
								range.startContainer.textContent = textBeforeCursor; // Keep only the text before the cursor
							}

							// Step 4: Insert the <p><br></p> for a line break
							parentElement.appendChild(breakParagraph);

							// Step 5: Insert the text after the cursor as a new paragraph
							parentElement.appendChild(nextParagraph);

							// Create Delete button
							const btnDel = document.createElement('button');
							btnDel.classList.add('btn-delete');
							btnDel.textContent = 'Delete';

							// Create Drag button
							const btnHandle = document.createElement('button');
							btnHandle.classList.add('btn-handle');
							btnHandle.setAttribute('draggable', true);

							// Create a span inside the button to prevent text dragging
							const span = document.createElement('span');
							span.classList.add('btn-handle-span');
							span.textContent = 'Drag';
							span.setAttribute('draggable', false);

							// Append elements correctly
							btnHandle.appendChild(span);
							breakParagraph.insertBefore(btnDel, breakParagraph.firstChild);
							breakParagraph.insertBefore(btnHandle, breakParagraph.firstChild);

							// Remove buttons from original parent
							parentElement.classList.remove('highlighted');
							parentElement.querySelectorAll('.btn-delete').forEach(btn => btn.remove());
							parentElement.querySelectorAll('.btn-handle').forEach(btn => btn.remove());

							// Step 6: Move the cursor to the start of the new paragraph
							const newRange = document.createRange();
							newRange.setStart(nextParagraph.firstChild, 0); // Set cursor to the start of the text in the new <p>
							newRange.setEnd(nextParagraph.firstChild, 0); // Collapse the range to the start
							selection.removeAllRanges();
							selection.addRange(newRange);
						}
					}
				}
			}
			setTimeout(() => addElementInfo(), 10);
			if (e.code === 'Enter') {
				setTimeout(() => trackCursorPosition(), 30);
			}
		});

		ed.addEventListener("input", function () {
			var editorParentCurrent = editor.querySelector(".editor-parent")
			editorParentCurrent.querySelectorAll("p").forEach(p => {
				let elements = p.querySelectorAll("ul, ol, div");
				elements.forEach(el => {
					p.parentNode.insertBefore(el, p);
				});
				if (!p.innerHTML.trim()) p.remove();
			});

			const editorContent = editorParentCurrent.innerHTML;
			let tempDiv = document.createElement("div");
			tempDiv.innerHTML = editorContent;

			tempDiv.querySelectorAll(".highlighted, .element-highlighter").forEach(el => {
				el.classList.remove("highlighted", "element-highlighter");
				if (!el.className.trim()) el.removeAttribute("class");
			});

			document.getElementById("source-code").value = formatHtml(tempDiv.innerHTML);
		});

		ed.addEventListener("dragstart", function (e) {
			// Ensure drag only starts when clicking the handler
			if (e.target.classList.contains("btn-handle")) {
				draggedElement = e.target.closest(".element-highlighter"); // Find the closest draggable element
				e.dataTransfer.setData("text/plain", "");

				setTimeout(() => {
					draggedElement.style.opacity = "0.5";
				}, 0);
			} else {
				e.preventDefault(); // Prevent drag from other elements
			}
		});

		ed.addEventListener("dragend", function () {
			if (draggedElement) {
				draggedElement.style.opacity = "1";
			}

			// Remove highlight from all elements after drag ends
			document.querySelectorAll(".element-highlighter.highlight").forEach((el) => {
				el.classList.remove("highlight");
			});
		});

		ed.addEventListener("dragover", function (e) {
			e.preventDefault();
		});

		ed.addEventListener("drop", function (e) {
			e.preventDefault();
			if (draggedElement && e.target !== draggedElement) {
				const dropTarget = e.target.closest(".element-highlighter");
				if (dropTarget && dropTarget !== draggedElement) {
					if (
						draggedElement.tagName === "LI" &&
						(
							dropTarget.tagName !== "UL" &&
							dropTarget.tagName !== "OL" &&
							dropTarget.tagName !== "LI"
						)
					) {
						return;
					}

					// Get bounding box of drop target
					const targetRect = dropTarget.getBoundingClientRect();
					const dropPosition = e.clientY - targetRect.top;

					// Decide whether to insert before or after based on cursor position
					if (dropPosition < targetRect.height / 2) {
						dropTarget.parentNode.insertBefore(draggedElement, dropTarget);
					} else {
						dropTarget.parentNode.insertBefore(draggedElement, dropTarget.nextSibling);
					}
				}
			}
		});

		ed.addEventListener("dragenter", function (e) {
			const dropTarget = e.target.closest(".element-highlighter");
			if (dropTarget && dropTarget !== draggedElement) {
				dropTarget.classList.add("highlight");
			}
		});

		ed.addEventListener("dragleave", function (e) {
			const dropTarget = e.target.closest(".element-highlighter");
			if (dropTarget) {
				dropTarget.classList.remove("highlight");
			}
		});
	});

	document.getElementById('display').addEventListener('change', function () {
		const displayVal = this.value;
		const flexArea = document.getElementById('displayFlexArea');
		const gridArea = document.getElementById('displayGridArea');
		const generalAlignArea = document.getElementById('generalAlignArea');

		gridArea.style.display = 'none';
		gridArea.innerHTML = '';
		flexArea.style.display = 'none';
		flexArea.innerHTML = '';
		generalAlignArea.style.display = 'none';
		generalAlignArea.innerHTML = '';

		if (displayVal === 'display: flex') {
			addFlexStyleHtml();
		} else if (displayVal === 'display: grid') {
			addGridStyleHtml();
		} else {
			addGeneralAlignStyleHtml();
		}
	});

	function addElementInfo() {
		var editorParentCurrent = editor.querySelector('.editor-parent');
		// Get all child elements inside the #editor
		const editorElements = editorParentCurrent.querySelectorAll('*');
		// Loop through each element
		editorElements.forEach(parentElement => {
			if (parentElement.classList.contains('btn-delete') || parentElement.classList.contains('btn-handle') || parentElement.classList.contains('btn-handle-span') || parentElement.tagName === 'STYLE' ) {
				return;
			}
			// Check if the parent element already has an 'element-info' div
			const existingElementInfo = parentElement.querySelector('.element-info');
			// If it exists, remove the current 'element-info' div
			if (existingElementInfo) {
				existingElementInfo.remove();
			}
			// Create a new div with class name 'element-info'
			const elementInfoDiv = document.createElement('element');
			elementInfoDiv.className = 'element-info';
			// Get the parent node type and class names
			const parentNodeType = parentElement.nodeName; // The type of the parent node (e.g., DIV, SECTION)
			const classNames = parentElement.className; // The class names of the parent node
			// If 'element-highlighter' is in the class names, remove it
			if (parentElement.classList.contains('element-highlighter')) {
				parentElement.classList.remove('element-highlighter');
			}
			// Set the inner content of the new div
			elementInfoDiv.innerHTML = `${parentNodeType}, ${parentElement.className}`;
			// Prepend the new div to the parent element (places it at the start)
			parentElement.prepend(elementInfoDiv);
		});
	}

	// Format HTML with line breaks and indentation
	function formatHtml(html) {
		// console.log(html);
		// Remove @font-face styles from <style> blocks
		html = html.replace(/@font-face\s*{[^}]+}/gi, '');
		// console.log(html);

		// Remove empty <style> tags left behind (if any)
		html = html.replace(/<style>\s*<\/style>/gi, '');

		let formatted = html.replace(/(>)(<)(\/*)/g, "$1\n$2$3");
		let lines = formatted.split('\n');
		let indentLevel = 0;
		let formattedHtml = '';

		lines.forEach(line => {
			line = line.trim();
			if (line.match(/^<\/\w/)) {
				indentLevel--;
			}
			indentLevel = Math.max(indentLevel, 0);
			formattedHtml += '    '.repeat(indentLevel) + line + '\n';
			if (line.match(/^<\w[^>]*>$/) && !line.match(/^<\/\w/)) {
				indentLevel++;
			}
		});

		return formattedHtml.trim();
	}

	//START - highlighting all elements inside id="editor" on hover
	let highlightedElement = null; // To store the currently highlighted element
	let brElement = null; // To store the inserted <br> element
	function addHighlight(event) {
		const hoveredElement = event.target;
		var editorParentCurrent = editor.querySelector('.editor-parent');
		if (editorParentCurrent.contains(hoveredElement)) {
			removeAllHighlights();
			hoveredElement.classList.add('highlighted');

			if (!(hoveredElement.tagName === 'SPAN' || hoveredElement.tagName === 'BUTTON' || hoveredElement.tagName === 'STRONG' || hoveredElement.tagName === 'EM' || hoveredElement.tagName === 'U' || hoveredElement.tagName === 'S' || hoveredElement.classList.contains('content-styles'))) {
				const btnDel = document.createElement('button');
				btnDel.classList.add('btn-delete');
				btnDel.textContent = 'Delete';
				hoveredElement.insertBefore(btnDel, hoveredElement.firstChild);

				const btnHandle = document.createElement('button');
				btnHandle.classList.add('btn-handle');
				btnHandle.setAttribute("draggable", true);

				// Create a span inside the button to prevent text dragging
				const span = document.createElement('span');
				span.classList.add('btn-handle-span');
				span.textContent = 'Drag';
				span.setAttribute("draggable", false);

				btnHandle.appendChild(span);
				hoveredElement.insertBefore(btnHandle, hoveredElement.firstChild);
			}

			const elementInfo = hoveredElement.querySelector('.element-info');
			if (elementInfo && !elementInfo.textContent.includes('highlighted')) {
				elementInfo.textContent += ' highlighted';
			}
		}
	}
	
	// Remove highlight when mouse leaves
	function removeAllHighlights() {
		const editorParents = document.getElementsByClassName('editor-parent'); // HTMLCollection
		Array.from(editorParents).forEach(parent => {
			parent.querySelectorAll('.highlighted').forEach(element => {
				element.classList.remove('highlighted');
				const textElement = element.querySelector('.element-info');
				if (textElement) {
					textElement.textContent = textElement.textContent.replace(' highlighted', '').trim();
				}
			});
			parent.querySelectorAll('.btn-delete').forEach(btn => btn.remove());
			parent.querySelectorAll('.btn-handle').forEach(btn => btn.remove());
		});
	}

	let brStart = null;
	let brEnd = null;

	function handleClick(event) {
		const clickedElement = event.target;
		var editorParentCurrent = editor.querySelector('.editor-parent');
		if (
			editorParentCurrent.contains(clickedElement) &&
			['H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'DIV', 'SPAN', 'P'].includes(clickedElement.tagName)
		) {
			if (highlightedElement !== clickedElement) {
				document.querySelectorAll('.space-br').forEach(el => el.remove());
			}
			if (!(clickedElement.firstChild?.tagName === 'BR') && !(clickedElement.lastChild?.tagName === 'BR')) {
				brStart = document.createElement('br');
				brStart.classList.add('space-br');
				const hasHighlighted = clickedElement.parentNode.querySelector('.highlighted');

				if (hasHighlighted && hasHighlighted.parentNode === clickedElement.parentNode) {
					clickedElement.parentNode.insertBefore(brStart, hasHighlighted);
					brEnd = document.createElement('br');
					brEnd.classList.add('space-br');
					clickedElement.parentNode.insertBefore(brEnd, hasHighlighted.nextSibling);
				} else {
					console.error("Highlighted element not found or not a sibling!");
				}
			}
		}
	}

	function setCursorAfterBr(brElement) {
		const range = document.createRange();
		const selection = window.getSelection();
		range.setStartAfter(brElement);
		range.setEndAfter(brElement);
		selection.removeAllRanges();
		selection.addRange(range);
	}

	function saveState() {
		var editorParentCurrent = editor.querySelector('.editor-parent');
		undoStack.push(editorParentCurrent.innerHTML);
		redoStack = [];
	}

	function undo() {
		if (undoStack.length > 0) {
			var editorParentCurrent = editor.querySelector('.editor-parent');
			redoStack.push(editorParentCurrent.innerHTML);
			editorParentCurrent.innerHTML = undoStack.pop();
		}
	}

	function redo() {
		if (redoStack.length > 0) {
			var editorParentCurrent = editor.querySelector('.editor-parent');
			undoStack.push(editorParentCurrent.innerHTML);
			editorParentCurrent.innerHTML = redoStack.pop();
		}
	}

	document.getElementById('undo').addEventListener('click', () => {
		undo();
		addElementInfo();
	});

	document.getElementById('redo').addEventListener('click', () => {
		redo();
		addElementInfo();
	});

	function trackCursorPosition() {
		const selection = window.getSelection();
		if (!selection || selection.rangeCount === 0) return;

		const range = selection.getRangeAt(0);
		const parentElement = range.startContainer.parentNode;
		if (['element', 'element-info', 'btn-delete'].some(cls => parentElement.classList.contains(cls))) {
			selection.removeAllRanges();
			document.body.focus();
		} else {
			const highlightedContainer = parentElement.querySelector('.highlighted');
			if (!highlightedContainer) return;

			const lastButton = highlightedContainer.querySelector('.btn-delete');

			if (lastButton) {
				// Create a new range
				const newRange = document.createRange();

				// Move cursor after the last button
				newRange.setStartAfter(lastButton);
				newRange.collapse(true);

				// Apply the new selection
				selection.removeAllRanges();
				selection.addRange(newRange);
			}
		}
	}

	//START - Optionally, you can add a keypress listener to support "Ctrl+Z" for undo and "Ctrl+Y" for redo and prevent "DELETE" key
	document.addEventListener('keydown', function (e) {
		// Save state when Spacebar is pressed
		if (e.key === ' ') {
			saveState();
		}

		if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
			setTimeout(() => trackCursorPosition(), 30);
		}

		if (e.key === 'Backspace') {
			const selection = window.getSelection();
			if (!selection || selection.rangeCount === 0) return;
			const range = selection.getRangeAt(0);
			const currentContainer = range.startContainer;

			if (
				currentContainer.parentNode.tagName.toLowerCase() === 'element' ||
				currentContainer.parentNode.classList.contains('element-info') ||
				currentContainer.parentNode.classList.contains('btn-delete')
			) {
				e.preventDefault();
				selection.removeAllRanges();
				document.body.focus();
			}

			let currentLi = null;
			let currentH = null;
			let currentP = null;
			let currentA = null;
			let currentDiv = null;
			let currentSpan = null;
			let currentStrong = null;
			let currentItalic = null;
			let currentUnderline = null;
			let currentStrike = null;
			let currentButton = null;
			let preventText = null;

			if (currentContainer.nodeType === Node.TEXT_NODE || currentContainer.nodeType === Node.ELEMENT_NODE) {
				let parentElement = currentContainer.nodeType === Node.TEXT_NODE ? currentContainer.parentNode : currentContainer;
				//Traverse up the DOM tree to find the closest <h1>, <h2>, etc.
				while (parentElement && !['H1', 'H2', 'H3', 'H4', 'H5', 'H6'].includes(parentElement.nodeName)) {
					parentElement = parentElement.parentNode;
				}
				//If we find a header element (h1, h2, etc.), assign it to currentHeader
				if (parentElement && ['H1', 'H2', 'H3', 'H4', 'H5', 'H6'].includes(parentElement.nodeName)) {
					currentH = parentElement;
					preventText = currentH.innerText;
				}
			}

			if (currentContainer.nodeType === Node.TEXT_NODE || currentContainer.nodeType === Node.ELEMENT_NODE) {
				let parentElement = currentContainer.nodeType === Node.TEXT_NODE ? currentContainer.parentNode : currentContainer;
				//Traverse up the DOM tree to find the closest <p>, <a>, <span>, or <li> element
				while (parentElement && parentElement.nodeName !== 'P' && parentElement.nodeName !== 'LI' && parentElement.nodeName !== 'A' && parentElement.nodeName !== 'DIV' && parentElement.nodeName !== 'SPAN' && parentElement.nodeName !== 'BUTTON' && parentElement.nodeName !== 'STRONG' && parentElement.nodeName !== 'EM' && parentElement.nodeName !== 'U' && parentElement.nodeName !== 'S') {
					parentElement = parentElement.parentNode;
				}
				//If we find a <p> element, assign it to currentP
				if (parentElement && parentElement.nodeName === 'P') {
					currentP = parentElement;
					preventText = currentP.innerText;
				}
				//If we find a <li> element, assign it to currentLi
				if (parentElement && parentElement.nodeName === 'LI') {
					currentLi = parentElement;
					preventText = currentLi.innerText;
				}
				//If we find a <a> element, treat it as a block element (like <p>)
				if (parentElement && parentElement.nodeName === 'A') {
					currentA = parentElement;
					preventText = currentA.innerText;
				}
				//If we find a <a> element, treat it as a block element (like <p>)
				if (parentElement && parentElement.nodeName === 'DIV') {
					currentDiv = parentElement;
					preventText = currentDiv.innerText;
				}
				//If we find a <a> element, treat it as a block element (like <p>)
				if (parentElement && parentElement.nodeName === 'SPAN') {
					currentSpan = parentElement;
					preventText = currentSpan.innerText;
				}
				//If we find a <a> element, treat it as a block element (like <p>)
				if (parentElement && parentElement.nodeName === 'STRONG') {
					currentStrong = parentElement;
					preventText = currentStrong.innerText;
				}
				//If we find a <a> element, treat it as a block element (like <p>)
				if (parentElement && parentElement.nodeName === 'EM') {
					currentItalic = parentElement;
					preventText = currentItalic.innerText;
				}
				//If we find a <a> element, treat it as a block element (like <p>)
				if (parentElement && parentElement.nodeName === 'U') {
					currentUnderline = parentElement;
					preventText = currentUnderline.innerText;
				}
				//If we find a <a> element, treat it as a block element (like <p>)
				if (parentElement && parentElement.nodeName === 'S') {
					currentStrike = parentElement;
					preventText = currentStrike.innerText;
				}
				//If we find a <a> element, treat it as a block element (like <p>)
				if (parentElement && parentElement.nodeName === 'BUTTON') {
					currentButton = parentElement;
					preventText = currentButton.innerText;
				}
			}

			const cursorPosition = range.startOffset;

			if (preventText) {
				preventText = preventText
					.replace(/Delete/g, '')
					.replace(/BUTTON,/g, '')
					.replace(/highlighted/g, '')
					.replace(/SPAN,/g, '')
					.replace(/STRONG,/g, '')
					.replace(/Drag,/g, '')
					.replace(/Drag/g, '')
					.replace(/EM,/g, '')
					.replace(/U,/g, '')
					.replace(/S,/g, '')
					.replace(/\n/g, '')
					.replace(/P,/g, '')
					.replace(/LI,/g, '')
					.replace(/H1,/g, '')
					.replace(/H2,/g, '')
					.replace(/H3,/g, '')
					.replace(/H4,/g, '')
					.replace(/H5,/g, '')
					.replace(/H6,/g, '')
					.replace(/font-size-\d+/g, '')
					.replace(/content-\d+/g, '')
					.replace(/^\s+|\s+$/g, '');
			} else {
				preventText = null;
			}

			if (
				(
					currentContainer.parentNode.tagName.toLowerCase() === 'span' ||
					currentContainer.parentNode.tagName.toLowerCase() === 'strong' ||
					currentContainer.parentNode.tagName.toLowerCase() === 'em' ||
					currentContainer.parentNode.tagName.toLowerCase() === 'u' ||
					currentContainer.parentNode.tagName.toLowerCase() === 's'
				)
				&& cursorPosition === 1 && preventText.length === 1
			) {
				e.preventDefault();
				currentContainer.parentElement.remove();
			} else {
				//If cursor is at the end of the <p> (no more text after it)
				if (cursorPosition === 0 || (cursorPosition === 0 && preventText === 1) || (cursorPosition === 2 && preventText === '' && preventText != null)) {
					e.preventDefault();
				}
			}
		}

		if (e.key === "Delete") {
			const selection = window.getSelection();
			if (!selection || selection.rangeCount === 0) return;

			const range = selection.getRangeAt(0);
			const cursorPosition = range.startOffset;
			const currentContainer = range.startContainer;
			let currentText = currentContainer.innerText || "";

			// Remove unwanted elements from text
			currentText = cleanText(currentText);

			if (
				isProtectedElement(currentContainer) ||
				currentText !== ""
			) {
				e.preventDefault();
				selection.removeAllRanges();
				document.body.focus();
			}

			let preventText = findClosestParentText(currentContainer);

			if (preventText) {
				preventText = cleanText(preventText);
			}

			if (
				(
					currentContainer.parentNode.tagName.toLowerCase() === 'span' ||
					currentContainer.parentNode.tagName.toLowerCase() === 'strong' ||
					currentContainer.parentNode.tagName.toLowerCase() === 'em' ||
					currentContainer.parentNode.tagName.toLowerCase() === 'u' ||
					currentContainer.parentNode.tagName.toLowerCase() === 's'
				)
				&& cursorPosition === 1 || cursorPosition === 0 && preventText.length === 1
			) {
				e.preventDefault();
				currentContainer.parentElement.remove();
			} else {
				//If cursor is at the end of the <p> (no more text after it)
				if (cursorPosition === preventText.length || (cursorPosition === 2 && preventText === "")) {
					e.preventDefault();
				}
			}
		}

	});

	function cleanText(text) {
		if (typeof text !== 'string') {
			return '';
		}

		return text
			.replace(/Delete|BUTTON,|SPAN,|STRONG,|EM,|U,|S,|highlighted|Drag|STRONG,|EM,|U,|S,|\n|P,|LI,|H[1-6],|font-size-\d+|content-\d+/g, '')
			.trim();
	}

	function isProtectedElement(element) {
		if (!element.parentNode) return false;
		const parent = element.parentNode;
		return parent.tagName.toLowerCase() === "element" ||
			parent.classList.contains("element-info") ||
			parent.classList.contains("btn-delete");
	}

	function findClosestParentText(node) {
		if (!node) return null;
		let parent = node.nodeType === Node.TEXT_NODE ? node.parentNode : node;

		while (parent && !["H1", "H2", "H3", "H4", "H5", "H6", "P", "LI", "A", "DIV", "SPAN", "BUTTON", "STRONG", "EM", "U", "S",].includes(parent.nodeName)) {
			parent = parent.parentNode;
		}

		return parent ? parent.innerText || "" : "";
	}
	//END - Optionally, you can add a keypress listener to support "Ctrl+Z" for undo and "Ctrl+Y" for redo and prevent "DELETE" key

	function getClosestEditor(node) {
		return node.nodeType === Node.ELEMENT_NODE ? node.closest('.editor-container') : node.parentElement.closest('.editor-container');
	}

	document.querySelectorAll('.editor-container').forEach(editorContainer => {
		editorContainer.querySelectorAll('.strong, .italic, .underline, .strike-through').forEach(button => {
			button.addEventListener('click', (event) => {
				const selection = window.getSelection();
				if (!selection.rangeCount) return;

				const range = selection.getRangeAt(0);
				const selectedText = selection.toString();
				if (!selectedText) return;

				const selectedEditor = getClosestEditor(range.startContainer);
				const buttonEditor = getClosestEditor(event.target);

				// Ensure the button and selection belong to the same editor
				if (selectedEditor === buttonEditor) {
					if (button.classList.contains('strong')) toggleWrapSelection('strong');
					else if (button.classList.contains('italic')) toggleWrapSelection('em');
					else if (button.classList.contains('underline')) toggleWrapSelection('u');
					else if (button.classList.contains('strike-through')) toggleWrapSelection('s');
				} else {
					removeAllRangesForcefully();
					addElementInfo();
				}
			});
		});

		editorContainer.querySelectorAll('.header-format').forEach(button => {
			button.addEventListener('click', function (event) {
				const headerTag = this.getAttribute('data-header');
				const selection = window.getSelection();
				if (!selection.rangeCount) return;

				const range = selection.getRangeAt(0);
				if (range.collapsed) return; // Exit if no text is selected

				// Get the editor of the selected text
				const selectedEditor = getClosestEditor(range.startContainer);
				// Get the editor of the clicked button
				const buttonEditor = getClosestEditor(event.target);

				// Ensure the button and selection belong to the same editor
				if (selectedEditor === buttonEditor) {
					// Extract selected text
					const selectedText = range.extractContents();

					// Create a new header element
					const headerElement = document.createElement(headerTag);
					headerElement.appendChild(selectedText);

					// Insert the new header element
					range.insertNode(headerElement);

					// Preserve selection after applying format
					selection.removeAllRanges();
					const newRange = document.createRange();
					newRange.selectNodeContents(headerElement);
					selection.addRange(newRange);

					removeAllRangesForcefully();
					addElementInfo(); // Update element info text
					saveState();
				} else {
					removeAllRangesForcefully();
					addElementInfo();
				}
			});
		});
	});

	function toggleWrapSelection(tagName) {
		const selection = window.getSelection();
		if (!selection.rangeCount) return;

		const range = selection.getRangeAt(0);
		const selectedText = selection.toString();

		if (!selectedText) return;

		const parentNode = range.startContainer.parentElement;
		const oldParentNode = parentNode.parentNode;
		// Check if the selection is already wrapped in the tag
		if (parentNode && parentNode.tagName.toLowerCase() === tagName) {
			unwrapTag(parentNode); // Remove the wrapping tag
			updateTextStructure();
		} else {
			wrapTextWithTag(range, tagName); // Add the wrapping tag
		}
		removeAllRangesForcefully();
		addElementInfo();
	}

	function unwrapTag(node) {
		const parent = node.parentNode;

		// Replace the wrapping tag with its child nodes
		while (node.firstChild) {
			parent.insertBefore(node.firstChild, node);
		}

		// Remove the empty wrapper tag
		parent.removeChild(node);
	}

	function wrapTextWithTag(range, tagName) {
		const element = document.createElement(tagName);
		const selectedText = range.toString();

		// Append the selected text to the newly created tag
		element.appendChild(document.createTextNode(selectedText));

		// Replace the selected range with the wrapped element
		range.deleteContents();
		range.insertNode(element);

		// Re-select the newly inserted element
		const newRange = document.createRange();
		newRange.selectNodeContents(element);
		const selection = window.getSelection();
		selection.removeAllRanges();
		selection.addRange(newRange);
	}

	function updateTextStructure() {
		if (!editor) return;

		var editorParentCurrent = editor.querySelector('.editor-parent');

		// Step 1: Add the `highlighted` class to the parent
		editorParentCurrent.classList.add("highlighted");

		// Step 2: Move the first `<button>` to the start of the parent, if it exists
		const deleteButton = editorParentCurrent.querySelector(".btn-delete");
		if (deleteButton) {
			editorParentCurrent.insertBefore(deleteButton, editorParentCurrent.firstChild);
		}

		// Step 3: Update the first `<element>` tag to include `highlighted`
		const firstElement = editorParentCurrent.querySelector("element.element-info");
		if (firstElement) {
			firstElement.textContent = "P, highlighted"; // Update its text content
		}

		// Step 4: Remove all extra `<element>` tags except the first one
		const allElements = editorParentCurrent.querySelectorAll("element.element-info");
		allElements.forEach((el, index) => {
			if (index > 0) el.remove(); // Remove all but the first
		});

		// Step 5: Flatten the content by removing unnecessary spaces or tags
		// flattenContent(editorParentCurrent);
	}

	function removeAllRangesForcefully() {
		const selection = window.getSelection();
		selection.removeAllRanges();
	}

	//END - Bold, Italic, Underline, Strike functionality

	function convertList(type) {
		const selection = window.getSelection();
		if (!selection.rangeCount) return;

		const range = selection.getRangeAt(0);
		const selectedElement = range.commonAncestorContainer.nodeType === 3
			? range.commonAncestorContainer.parentElement
			: range.commonAncestorContainer;

		const closestList = selectedElement.closest('ul, ol');

		if (closestList) {
			const newList = document.createElement(type);
			newList.innerHTML = closestList.innerHTML;
			closestList.replaceWith(newList);
			updateElementInfo(newList);
		} else {
			document.execCommand(type === 'ol' ? 'insertOrderedList' : 'insertUnorderedList');

			const newList = selectedElement.closest(type === 'ol' ? 'ol' : 'ul');
			if (newList) {
				updateElementInfo(newList);
			}
		}

		removeAllHighlights();

		removeAllRangesForcefully();
	}

	function updateElementInfo(listElement) {
		const listType = listElement.tagName === 'OL' ? 'OL' : 'UL';
		const listInfo = listElement.querySelector('.element-info');

		if (listInfo) {
			listInfo.textContent = `${listType}, highlighted`;
		}

		listElement.querySelectorAll('li').forEach((li) => {
			const liInfo = li.querySelector('.element-info');
			if (liInfo) {
				liInfo.textContent = 'LI, ';
			}
		});
	}

	//END - unordered list button functionality

	//START - helper function to check if a <li> is empty (contains only <br> or is completely empty)
	function isEmptyLi(li) {
		//Strip HTML tags and check for only whitespace or non-breaking space
		const cleanedHTML = li.innerHTML.replace(/<[^>]*>/g, '').trim();
		//Check if cleaned content is empty or contains only a non-breaking space or <br>
		const nonBreakingSpace = '\u00a0'; //Unicode for non-breaking space
		return cleanedHTML === '' || cleanedHTML === nonBreakingSpace || li.innerHTML.trim() === '<br>';
	}
	//END - helper function to check if a <li> is empty (contains only <br> or is completely empty)

	function handleInsertLink() {
		showLinkPopup().then(({url, target, rel}) => {
			const linkHTML = `<a href="${url}" ${target ? `target="${target}"` : ""} ${
				rel ? `rel="${rel}"` : ""
			}>${url}</a>`;
			updateLinkInEditor(linkHTML);
		});
	}

	function insertLinkIntoEditor(linkHTML, range) {
		const selection = window.getSelection();

		if (editor && range) {
			selection.removeAllRanges();
			selection.addRange(range);

			if (!selection.isCollapsed) {
				const anchorNode = document.createElement("a");
				const url = linkHTML.match(/href="([^"]+)"/)[1];
				const target = linkHTML.match(/target="([^"]+)"/);
				const rel = linkHTML.match(/rel="([^"]+)"/);

				anchorNode.href = url;
				if (target) anchorNode.target = target[1];
				if (rel) anchorNode.rel = rel[1];

				const selectedText = range.toString();
				anchorNode.textContent = selectedText;

				range.deleteContents();
				range.insertNode(anchorNode);

				const newRange = document.createRange();
				newRange.setStartAfter(anchorNode);
				newRange.setEndAfter(anchorNode);
				selection.removeAllRanges();
				selection.addRange(newRange);
			} else {
				const div = document.createElement("div");
				div.innerHTML = linkHTML;
				var editorParentCurrent = editor.querySelector('.editor-parent');
				editorParentCurrent.appendChild(div);
			}
		}
}

	// Show the link input popup
	function showLinkPopup() {
		const linkManager = document.getElementById("link-manager");
		linkManager.classList.add("link-manager-show");
		linkManager.classList.remove("link-manager-hide");

		return new Promise((resolve) => {
			const insertBtn = document.getElementById("insertLinkBtn");
			const cancelBtn = document.getElementById("cancelBtn");
			const urlInput = document.getElementById("urlInput");
			const urlIdInput = document.getElementById("urlIdInput");
			const targetType = document.getElementById("targetType");
			const relType = document.getElementById("relType");

			const selection = window.getSelection();
			savedRange = selection.rangeCount > 0 ? selection.getRangeAt(0) : null;

			const selectedNode = selection.anchorNode;
			if (selectedNode && selectedNode.nodeType === Node.TEXT_NODE) {
				const parentAnchor = selectedNode.parentElement;
				if (parentAnchor && parentAnchor.tagName === "A") {
					selectedLink = parentAnchor;
					const hrefValue = selectedLink.getAttribute("href");
					const targetValue = selectedLink.target || "";
					const relValue = selectedLink.rel || "";

					if (hrefValue.includes("urlId(")) {
						const urlIdMatch = hrefValue.match(/url_id\(([^)]+)\)/);
						if (urlIdMatch && urlIdMatch[1]) {
							urlIdInput.value = urlIdMatch[1];
							urlInput.value = "";
						}
					} else {
						urlInput.value = hrefValue;
						urlIdInput.value = "";
					}
					targetType.value = targetValue;
					relType.value = relValue;
				} else {
					selectedLink = null;
					urlInput.value = "";
					urlIdInput.value = "";
					targetType.value = "";
					relType.value = "";
				}
			} else {
				selectedLink = null;
				urlInput.value = "";
				urlIdInput.value = "";
				targetType.value = "";
				relType.value = "";
			}

			let isProcessing = false;

			insertBtn.addEventListener("click", function () {
				if (isProcessing) return;
				isProcessing = true;

				const url = urlInput.value.trim();
				const urlId = urlIdInput.value.trim();
				const target = targetType.value;
				const rel = relType.value;

				if (url && urlId) {
					alert("Please fill either URL or URL ID, not both.");
					isProcessing = false;
					return;
				}

				let finalUrl = "";
				if (url) {
					finalUrl = url;
				} else if (urlId) {
					finalUrl = `${url}urlId(${urlId()});`;
				} else {
					alert("Please enter a valid URL or URL ID.");
					isProcessing = false;
					return;
				}

				resolve({url: finalUrl, target, rel});
				closeLinkManager(linkManager);
			});

			cancelBtn.addEventListener("click", function () {
				closeLinkManager(linkManager);
			});
		});
	}

	function urlId() {
		return document.getElementById('urlIdInput').value;
	}

	function closeLinkManager(linkManager) {
		linkManager.classList.add("link-manager-hide");
		linkManager.classList.remove("link-manager-show");
	}

	function updateLinkInEditor(linkHTML) {
		if (selectedLink) {
			selectedLink.href = linkHTML.match(/href="([^"]+)"/)[1];

			const target = linkHTML.match(/target="([^"]+)/);
			if (target) selectedLink.target = target[1];

			const rel = linkHTML.match(/rel="([^"]+)/);
			if (rel) selectedLink.rel = rel[1];
		} else {
			insertLinkIntoEditor(linkHTML, savedRange);
		}
	}
	//END - create links

	document.addEventListener("click", function (e) {
		if (e.target.classList[0] === 'editStyles') {
			e.target.parentNode.parentNode.parentNode.querySelector(".editor-styles-container").classList.toggle("hidden");
			// document.querySelector(".editor-styles-container").classList.toggle("hidden");
			emptyStyleForm();
			if (activeElement) {
				const element = activeElement.nodeType === 3 ? activeElement.parentNode : activeElement;
				const currentClass = element.className;
				const styleMap = {};
				if (currentClass) {
					const classMatch = currentClass.match(/content-\d+/);
					if (classMatch) {
						const styleTag = document.querySelector("style");
						if (styleTag) {
							const styleContent = styleTag.innerHTML;
							const classRuleRegex = new RegExp(`\\.${classMatch[0]} {([^}]*)}`, "g");
							const match = classRuleRegex.exec(styleContent);
							if (match) {
								match[1].trim().split(";").forEach(style => {
									if (style.trim()) {
										const [property, value] = style.split(":").map(s => s.trim());
										styleMap[property] = value.replace(/\s*!important$/, "");
									}
								});
							}
						}
					}
				}
				document.querySelectorAll(".editor-styles form input, .editor-styles form select, .editor-styles form textarea").forEach(field => {
					const fieldProperty = field.name;
					if (styleMap[fieldProperty]) {
						field.value = `${fieldProperty}: ${styleMap[fieldProperty]}`;
						if (field.tagName === "SELECT") {
							const fieldNameBeforeColon = fieldProperty.split(":")[0].trim();
							Array.from(field.options).forEach(option => {
								if (option.value.trim() === fieldNameBeforeColon) {
									option.selected = true;
								}
							});
						}
					}
				});
			} else {
				console.log("No active element found!");
			}
		}
		else if (e.target.classList[0] === 'insertLinkButton') {
			const selection = window.getSelection();
			const selectedText = selection.toString();
			const alphaCount = (selectedText.match(/[a-zA-Z]/g) || []).length;

			const clickedButton = e.target;
			const editorContainer = getClosestEditor(clickedButton);

			if (!selection.rangeCount) return;

			const range = selection.getRangeAt(0);
			const selectedEditor = getClosestEditor(range.startContainer);

			if (alphaCount < 1 || selectedEditor !== editorContainer) {
				alert('Please select some text first!');
				return;
			}

			handleInsertLink();
		}
		else if (e.target.classList[0] === 'unlink') {
			const selection = window.getSelection();
			if (selection.rangeCount > 0) {
				const range = selection.getRangeAt(0);
				let anchorTag = null;

				// Check if commonAncestorContainer is an element or a text node
				if (range.commonAncestorContainer.nodeType === Node.ELEMENT_NODE) {
					anchorTag = range.commonAncestorContainer.closest("a");
				} else {
					anchorTag = range.commonAncestorContainer.parentElement.closest("a");
				}

				const clickedButton = e.target;
				const editorContainer = getClosestEditor(clickedButton);

				if (!selection.rangeCount) return;

				const selectedEditor = getClosestEditor(range.startContainer);

				const selectedText = selection.toString();
				const alphaCount = (selectedText.match(/[a-zA-Z]/g) || []).length;

				if (alphaCount < 1 || selectedEditor !== editorContainer) {
					alert("Please select some text first!");
					return;
				}
				removeAllHighlights();

				if (anchorTag) {
					var textNode = document.createTextNode(anchorTag.textContent.replace(/A,/g, ''));
					anchorTag.parentNode.replaceChild(textNode, anchorTag);
				}
			}
		}
		else if (e.target.classList[0] === 'ordered-list') {
			const selection = window.getSelection();

			const clickedButton = e.target;
			const editorContainer = getClosestEditor(clickedButton);

			if (!selection.rangeCount) return;

			const range = selection.getRangeAt(0);
			const selectedEditor = getClosestEditor(range.startContainer);

			if (selectedEditor !== editorContainer) {
				return;
			}
			convertList('ol')
		}
		else if (e.target.classList[0] === 'unordered-list') {
			const selection = window.getSelection();

			const clickedButton = e.target;
			const editorContainer = getClosestEditor(clickedButton);

			if (!selection.rangeCount) return;

			const range = selection.getRangeAt(0);
			const selectedEditor = getClosestEditor(range.startContainer);

			if (selectedEditor !== editorContainer) {
				return;
			}
			convertList('ul')
		}
		else if (e.target.classList[0] === 'addDivButton') {
			const elementType = e.target.parentNode.querySelector('.element-type').value;
			const selection = window.getSelection();
			if (!selection || selection.rangeCount === 0) return;

			const range = selection.getRangeAt(0);
			let currentContainer = range.startContainer;

			const clickedButton = e.target;
			const editorContainer = getClosestEditor(clickedButton);

			if (!selection.rangeCount) return;

			const selectedEditor = getClosestEditor(range.startContainer);

			if (selectedEditor !== editorContainer) {
				return;
			}

			// If selection is inside a text node, get the parent element
			if (currentContainer.nodeType === Node.TEXT_NODE) {
				currentContainer = currentContainer.parentNode;
			}

			// Check if the cursor is inside a <button> element
			if (currentContainer.closest('button')) {
				return; // Exit if inside a button
			}

			// Check if the selection is inside an element with the class "editor-parent"
			if (!currentContainer.closest('.editor-parent')) {
				return; // Exit if not inside .editor-parent
			}

			const newElement = document.createElement(elementType);
			newElement.textContent = 'New ' + elementType + '!';

			const fragment = document.createDocumentFragment();
			fragment.appendChild(newElement);

			range.deleteContents();
			range.insertNode(fragment);

			// Ensure the cursor is set after the new element
			const newRange = document.createRange();
			newRange.setStartAfter(newElement);
			newRange.setEndAfter(newElement);

			// Add a <br> element after inline elements like span and button
			if (['span', 'button'].includes(elementType)) {
				const brElement = document.createElement('br'); // Create a <br> element
				// brElement.classList.add('space-br');
				// newElement.after(brElement);
				// newRange.setStartAfter(brElement);
				// newRange.setEndAfter(brElement);
			}

			selection.removeAllRanges();
			selection.addRange(newRange);
			if (!['button'].includes(elementType)) {
				setTimeout(() => addElementInfo(), 10);
			}
		}
		else if (e.target.classList[0] === 'show-source') {
			var editorParentCurrent = editor.querySelector('.editor-parent');
			const editorContent = editorParentCurrent.innerHTML;
			let tempDiv = document.createElement("div");
			tempDiv.innerHTML = editorContent;

			tempDiv.querySelectorAll(".element-info").forEach(el => el.remove());
			tempDiv.querySelectorAll("p").forEach(p => {
				if (!p.innerHTML.trim()) p.remove();
			});

			// Remove unwanted buttons
			tempDiv.querySelectorAll(".btn-handle, .btn-delete").forEach(btn => btn.remove());

			tempDiv.querySelectorAll(".highlighted, .element-highlighter").forEach(el => {
				el.classList.remove("highlighted", "element-highlighter");
				if (!el.className.trim()) el.removeAttribute("class");
			});

			let formattedHtml = formatHtml(tempDiv.innerHTML);

			formattedHtml = formattedHtml.replace(/<\/style>/g, "\n</style>");

			document.getElementById("source-code").value = formattedHtml;

			document.getElementById("source-code-popup").style.display = "block";
		}
	});

/*

	$('.editor-styles form').on('change', function(e) {
		e.preventDefault();
		const selection = window.getSelection();
		const selectedText = selection.toString();
		const alphaCount = (selectedText.match(/[a-zA-Z]/g) || []).length;

		const clickedButton = e.target;
		const editorContainer = getClosestEditor(clickedButton);

		if (!selection.rangeCount) return;

		const range = selection.getRangeAt(0);
		const selectedEditor = getClosestEditor(range.startContainer);

		if (selectedEditor !== editorContainer) {
			return;
		}

		if (alphaCount > 1) {
			$('.editor-styles form').find('input, select, textarea').each(function () {
				var fieldValue = $(this).val();
				if (fieldValue && fieldValue.trim() !== '' && fieldValue.includes(':')) {
					if ($(this).attr('id') == 'background-color') {
						return;
						// fieldValue = 'background-color: '+ fieldValue;
					}
					if ($(this).attr('id') == 'color') {
						return;
						// fieldValue = 'color: '+ fieldValue;
					}
					// Split the field value to get the property and value
					var fieldParts = fieldValue.split(':');
					var fieldProperty = fieldParts[0].trim();
					var fieldValue = fieldParts[1] ? fieldParts[1].trim() : '';
					applyStyleSingle(fieldProperty, fieldValue);
				}
			});
		} else {
			// Loop through each form field inside the popup
			$('.editor-styles form').find('input, select, textarea').each(function () {
				var fieldValue = $(this).val();
				// Proceed only if the field has a value
				if (fieldValue && fieldValue.trim() !== '' && fieldValue.includes(':')) {
					if ($(this).attr('id') == 'background-color') {
						return;
						// fieldValue = 'background-color: '+ fieldValue;
					}
					if ($(this).attr('id') == 'color') {
						return;
						// fieldValue = 'color: '+ fieldValue;
					}
					// Split the field value to get the property and value
					var fieldParts = fieldValue.split(':');
					var fieldProperty = fieldParts[0].trim();
					var fieldValue = fieldParts[1] ? fieldParts[1].trim() : '';
					// Check if the activeElement is still valid
					if (activeElement) {
						// Wrap the active element in jQuery to use jQuery methods
						var $activeElement = $(activeElement);
						// If the cursor is inside a text node, find the parent element that was clicked
						var $clickedDiv = $activeElement.closest('*');  // The element within editor
						if ($clickedDiv.length > 0) {
							// Initialize the counter if no class is found
							var styleNameBase = 'content';
							var styleName = '';
							var counter = 0;
							var $editor = $(editor); // Convert the editor reference to a jQuery object
							var $style = $editor.find('style');

							// Get the current class names of the clicked div
							var currentClass = $clickedDiv.attr('class');
							var classMatch = currentClass && currentClass.match(/content-\d+$/);
							// Check if the clicked element already has a class with content-\d+
							if (classMatch) {
								// If a class with content-\d+ exists, extract it
								styleName = classMatch[0];
							} else {
								if ($style.length === 0) {
									// If no <style> tag exists, create one and prepend it to the #editor
									$style = $('<style></style>');
									editor.prepend($style);
								} else {
									// If <style> tag exists, check for the last used content-<number> class in the <style> content
									var lastClassName = '';
									var styleContent = $style.html();
									// Get all class names in the style content that match the pattern content-\d+
									var matches = styleContent.match(/\.content-\d+/g);
									if (matches && matches.length > 0) {
										lastClassName = matches[matches.length - 1];
										var match = lastClassName.match(/content-(\d+)$/); // Regex to extract the counter value
										if (match) {
											counter = parseInt(match[1], 10) + 1; // Increment the last counter value by 1
										}
									} else {
										counter = 0; // Reset counter if no matching class is found
									}
								}
								// Generate a new class name based on the counter
								styleName = styleNameBase + '-' + counter;
								// Increment the counter for future use
								counter++;
							}

							// Add or update styles in the <style> tag
							if ($style.length === 0) {
								// If no <style> tag exists, create one and prepend it to the #editor
								$style = $('<style></style>');
								editor.prepend($style);
							}
							// Gather all valid field values and append them as properties to the class rule
							var classRule = '.' + styleName + ' {';
							// Loop again through each form field to append all styles that are not empty
							$('.editor-styles form').find('input, select, textarea').each(function () {
								var fieldValue = $(this).val();
								if ($(this).attr('id') == 'background-color') {
									return;
									// fieldValue = 'background-color: '+ fieldValue;
								}
								if ($(this).attr('id') == 'color') {
									return;
									// fieldValue = 'color: '+ fieldValue;
								}
								if (fieldValue && fieldValue.trim() !== '' && fieldValue.includes(':')) {
									var fieldParts = fieldValue.split(':');
									var fieldProperty = fieldParts[0].trim();
									var fieldValue = fieldParts[1] ? fieldParts[1].trim() : '';
									classRule += ' ' + fieldProperty + ': ' + fieldValue + ' !important;';
								}
							});
							// Close the class rule with }
							classRule += ' }';
							// Check if the class rule already exists and if it does, replace it
							var styleContent = $style.html().trim();
							// var classRuleRegex = new RegExp('\\.' + styleName + ' {.*?}', 'g');
							var classRuleRegex = new RegExp(`\\.${styleName}\\s*{[^}]*}`, 'g');
							if (styleContent.match(classRuleRegex)) {
								// If the rule for this class already exists, clear it and add the new property
								// styleContent = styleContent.replace(classRuleRegex, classRule);
								styleContent = styleContent.replace(classRuleRegex, function (match) {
									return match.replace('}', ` ${fieldProperty}: ${fieldValue} !important; }`);
								});
							} else {
								// If the rule doesn't exist, just append it
								styleContent += classRule;
							}
							// Update the <style> tag with the new content
							$style.html(styleContent);
							// Add the class name to the clicked element
							$clickedDiv.addClass(styleName);

						}
					}
				}
			});
			addElementInfo();
		}

		removeAllRangesForcefully();
	});

	function applyStyleSingle(property, value) {
		const selection = window.getSelection();
		if (!selection.rangeCount) return;

		const selectedRange = selection.getRangeAt(0);
		const selectedText = selectedRange.toString().trim();
		const alphaCount = (selectedText.match(/[a-zA-Z]/g) || []).length;
		if (alphaCount < 2) return; // Ensure at least 2 characters are selected

		let parentNode = selectedRange.commonAncestorContainer.parentNode;
		if (!parentNode || parentNode.nodeType !== 1) return;

		let $editor = $(editor);
		let $style = $editor.find("style");
		if ($style.length === 0) {
			$style = $("<style></style>");
			$editor.prepend($style);
		}

		// Check if the selection is already inside a span with a 'content-*' class.
		let existingSpan = null;
		if (parentNode.tagName === "SPAN" && /content-\d+/.test(parentNode.className)) {
			existingSpan = parentNode;
		} else if (
			selectedRange.commonAncestorContainer.nodeType === Node.TEXT_NODE &&
			selectedRange.commonAncestorContainer.parentNode.tagName === "SPAN" &&
			/content-\d+/.test(selectedRange.commonAncestorContainer.parentNode.className)
		) {
			existingSpan = selectedRange.commonAncestorContainer.parentNode;
		}

		let className;
		if (existingSpan) {
			// Use the existing content-* class
			const match = existingSpan.className.match(/content-\d+/);
			className = match ? match[0] : null;
		}

		if (!className) {
			// No existing styled span: wrap the selected content in a new span.
			let extractedContent = selectedRange.extractContents();
			if (!extractedContent.textContent.trim()) return;

			let newSpan = document.createElement("span");
			className = generateClassName($style);
			newSpan.classList.add(className);
			newSpan.appendChild(extractedContent);
			selectedRange.deleteContents();
			selectedRange.insertNode(newSpan);
			existingSpan = newSpan;
		}

		// Update or add the CSS rule for the class.
		let styleContent = $style.html();
		let classRuleRegex = new RegExp(`\\.${className}\\s*{[^}]*}`, "g");
		let propertyRegex = new RegExp(`${property}\\s*:\\s*[^;]+;`);

		if (styleContent.match(classRuleRegex)) {
			styleContent = styleContent.replace(classRuleRegex, function (match) {
				if (match.match(propertyRegex)) {
					// If the property already exists, replace its value.
					return match.replace(propertyRegex, `${property}: ${value} !important;`);
				} else {
					// Otherwise, append the new property before the closing brace.
					return match.replace("}", ` ${property}: ${value} !important; }`);
				}
			});
		} else {
			// Create a new CSS rule for the new class.
			let classRule = `.${className} { ${property}: ${value} !important; }`;
			styleContent += classRule;
		}

		$style.html(styleContent);
		addElementInfo();
	}

	function generateClassName($style) {
		let counter = 0;
		let styleContent = $style.html();
		let matches = styleContent.match(/\.content-\d+/g);
		if (matches && matches.length > 0) {
			let lastClass = matches[matches.length - 1];
			let match = lastClass.match(/content-(\d+)/);
			if (match) {
				counter = parseInt(match[1], 10) + 1;
			}
		}
		return "content-" + counter;
	}

*/

/*
	function applyStyleSingle(property, value) {
		const selection = window.getSelection();
		if (!selection.rangeCount) return;

		const selectedRange = selection.getRangeAt(0);
		const selectedText = selectedRange.toString().trim();
		const alphaCount = (selectedText.match(/[a-zA-Z]/g) || []).length;
		if (alphaCount < 2) return; // Ensure at least 2 characters are selected

		let parentNode = selectedRange.commonAncestorContainer.parentNode;
		if (!parentNode || parentNode.nodeType !== 1) return;

		let $editor = $(editor);
		let $style = $editor.find("style");
		if ($style.length === 0) {
			$style = $("<style></style>");
			$editor.prepend($style);
		}

		// Check if the selection is already within a span that has a content-* class.
		let existingSpan = null;
		if (parentNode.tagName === "SPAN" && /content-\d+/.test(parentNode.className)) {
			existingSpan = parentNode;
		} else if (selectedRange.commonAncestorContainer.nodeType === Node.TEXT_NODE &&
			selectedRange.commonAncestorContainer.parentNode.tagName === "SPAN" &&
			/content-\d+/.test(selectedRange.commonAncestorContainer.parentNode.className)) {
			existingSpan = selectedRange.commonAncestorContainer.parentNode;
		}

		let className;
		if (existingSpan) {
			// Use the existing content-* class.
			const match = existingSpan.className.match(/content-\d+/);
			className = match ? match[0] : null;
		}

		if (!className) {
			// No existing styled span: wrap the selected content in a new span.
			let extractedContent = selectedRange.extractContents();
			if (!extractedContent.textContent.trim()) return;
			let newSpan = document.createElement("span");
			className = generateClassName($style);
			newSpan.classList.add(className);
			newSpan.appendChild(extractedContent);
			selectedRange.deleteContents();
			selectedRange.insertNode(newSpan);
			existingSpan = newSpan;
		}

		// Update the CSS rule for the class.
		let styleContent = $style.html();
		let classRuleRegex = new RegExp(`\\.${className}\\s*{[^}]*}`, "g");
		// Regex to detect the specific property.
		let propertyRegex = new RegExp(`${property}\\s*:\\s*[^;]+;`);

		if (styleContent.match(classRuleRegex)) {
			styleContent = styleContent.replace(classRuleRegex, function(match) {
				// If the property exists in the rule, replace its value.
				if(match.match(propertyRegex)) {
					return match.replace(propertyRegex, `${property}: ${value} !important;`);
				} else {
					// Otherwise, append the new property before the closing brace.
					return match.replace("}", ` ${property}: ${value} !important; }`);
				}
			});
		} else {
			// Create a new CSS rule for the new class.
			let classRule = `.${className} { ${property}: ${value} !important; }`;
			styleContent += classRule;
		}
		$style.html(styleContent);

		addElementInfo();
	}
*/

	$('.editor-styles form').on('change', function(e) {
		e.preventDefault();
		const selection = window.getSelection();
		const selectedText = selection.toString();
		const alphaCount = (selectedText.match(/[a-zA-Z]/g) || []).length;

		const clickedButton = e.target;
		const editorContainer = getClosestEditor(clickedButton);

		if (!selection.rangeCount) return;

		const range = selection.getRangeAt(0);
		const selectedEditor = getClosestEditor(range.startContainer);

		// Only run if the clicked form belongs to the same editor as the selection.
		if (selectedEditor !== editorContainer) {
			return;
		}

		if (alphaCount > 1) {
			// Loop through each form field in the current form
			$('.editor-styles form').find('input, select, textarea').each(function () {
				var fieldValue = $(this).val();
				if (fieldValue && fieldValue.trim() !== '' && fieldValue.includes(':')) {
					if ($(this).attr('id') == 'background-color' || $(this).attr('id') == 'color') {
						return;
					}
					// Split the field value to get property and value
					var fieldParts = fieldValue.split(':');
					var fieldProperty = fieldParts[0].trim();
					var fieldValuePart = fieldParts[1] ? fieldParts[1].trim() : '';
					applyStyleSingle(fieldProperty, fieldValuePart);
				}
			});
		} else {
			// Loop through each form field to update styles for the active element,
			// but only within the current editor.
			$('.editor-styles form').find('input, select, textarea').each(function () {
				var fieldValue = $(this).val();
				if (fieldValue && fieldValue.trim() !== '' && fieldValue.includes(':')) {
					if ($(this).attr('id') == 'background-color' || $(this).attr('id') == 'color') {
						return;
					}
					var fieldParts = fieldValue.split(':');
					var fieldProperty = fieldParts[0].trim();
					var fieldValuePart = fieldParts[1] ? fieldParts[1].trim() : '';
					if (activeElement) {
						var $activeElement = $(activeElement);
						// Get the element within the editor where the cursor is.
						var $clickedDiv = $activeElement.closest('*');
						if ($clickedDiv.length > 0) {
							var styleNameBase = 'content';
							var styleName = '';
							var counter = 0;
							// Use the current editor from the editor container.
							var $editor = $(editorContainer).find('.editor-parent');
							var $style = $editor.find('style');
							var editorId = $editor.attr('id');

							// Get current class names of the clicked element.
							var currentClass = $clickedDiv.attr('class');
							var classMatch = currentClass && currentClass.match(/content-\d+$/);
							if (classMatch) {
								styleName = classMatch[0];
							} else {
								if ($style.length === 0) {
									$style = $('<style></style>');
									$editor.prepend($style);
								} else {
									var lastClassName = '';
									var styleContent = $style.html();
									var matches = styleContent.match(/\.content-\d+/g);
									if (matches && matches.length > 0) {
										lastClassName = matches[matches.length - 1];
										var match = lastClassName.match(/content-(\d+)$/);
										if (match) {
											counter = parseInt(match[1], 10) + 1;
										}
									} else {
										counter = 0;
									}
								}
								styleName = styleNameBase + '-' + counter;
								counter++;
							}
							if ($style.length === 0) {
								$style = $('<style></style>');
								$editor.prepend($style);
							}
							// Build the class rule with the editor id as a prefix.
							var classRule;
							if (editorId) {
								classRule = `#${editorId} .${styleName} {`;
							} else {
								classRule = `.${styleName} {`;
							}
							// Append all valid field values.
							$('.editor-styles form').find('input, select, textarea').each(function () {
								var fieldValueInner = $(this).val();
								if ($(this).attr('id') == 'background-color' || $(this).attr('id') == 'color') {
									return;
								}
								if (fieldValueInner && fieldValueInner.trim() !== '' && fieldValueInner.includes(':')) {
									var parts = fieldValueInner.split(':');
									var fieldPropertyInner = parts[0].trim();
									var fieldValueInnerVal = parts[1] ? parts[1].trim() : '';
									classRule += ' ' + fieldPropertyInner + ': ' + fieldValueInnerVal + ' !important;';
								}
							});
							classRule += ' }';

							var styleContent = $style.html().trim();
							var classRuleRegex;
							if (editorId) {
								classRuleRegex = new RegExp(`#${editorId}\\s+\\.${styleName}\\s*{[^}]*}`, 'g');
							} else {
								classRuleRegex = new RegExp(`\\.${styleName}\\s*{[^}]*}`, 'g');
							}
							if (styleContent.match(classRuleRegex)) {
								styleContent = styleContent.replace(classRuleRegex, function (match) {
									return match.replace('}', ` ${fieldProperty}: ${fieldValuePart} !important; }`);
								});
							} else {
								styleContent += classRule;
							}
							$style.html(styleContent);
							$clickedDiv.addClass(styleName);
						}
					}
				}
			});
			addElementInfo();
		}

		removeAllRangesForcefully();
	});

	function applyStyleSingle(property, value) {
		const selection = window.getSelection();
		if (!selection.rangeCount) return;

		const selectedRange = selection.getRangeAt(0);
		const selectedText = selectedRange.toString().trim();
		const alphaCount = (selectedText.match(/[a-zA-Z]/g) || []).length;
		if (alphaCount < 2) return; // Ensure at least 2 characters are selected

		let parentNode = selectedRange.commonAncestorContainer.parentNode;
		if (!parentNode || parentNode.nodeType !== 1) return;

		// Determine the current editor using the active selection.
		let $activeEl = $(selectedRange.startContainer.nodeType === 3 ? selectedRange.startContainer.parentNode : selectedRange.startContainer);
		let $editor = $activeEl.closest('.editor-parent');
		var editorId = $editor.attr('id');

		let $style = $editor.find("style");
		if ($style.length === 0) {
			$style = $("<style></style>");
			$editor.prepend($style);
		}

		// Check if the selection is already inside a span with a 'content-*' class.
		let existingSpan = null;
		if (parentNode.tagName === "SPAN" && /content-\d+/.test(parentNode.className)) {
			existingSpan = parentNode;
		} else if (
			selectedRange.commonAncestorContainer.nodeType === Node.TEXT_NODE &&
			selectedRange.commonAncestorContainer.parentNode.tagName === "SPAN" &&
			/content-\d+/.test(selectedRange.commonAncestorContainer.parentNode.className)
		) {
			existingSpan = selectedRange.commonAncestorContainer.parentNode;
		}

		let className;
		if (existingSpan) {
			const match = existingSpan.className.match(/content-\d+/);
			className = match ? match[0] : null;
		}

		if (!className) {
			// Wrap the selected content in a new span.
			let extractedContent = selectedRange.extractContents();
			if (!extractedContent.textContent.trim()) return;

			let newSpan = document.createElement("span");
			className = generateClassName($style);
			newSpan.classList.add(className);
			newSpan.appendChild(extractedContent);
			selectedRange.deleteContents();
			selectedRange.insertNode(newSpan);
			existingSpan = newSpan;
		}

		// Update or add the CSS rule for the class.
		let styleContent = $style.html();
		let classRuleRegex;
		if (editorId) {
			classRuleRegex = new RegExp(`#${editorId}\\s+\\.${className}\\s*{[^}]*}`, "g");
		} else {
			classRuleRegex = new RegExp(`\\.${className}\\s*{[^}]*}`, "g");
		}
		let propertyRegex = new RegExp(`${property}\\s*:\\s*[^;]+;`);

		if (styleContent.match(classRuleRegex)) {
			styleContent = styleContent.replace(classRuleRegex, function (match) {
				if (match.match(propertyRegex)) {
					// Replace the property's value if it already exists.
					return match.replace(propertyRegex, `${property}: ${value} !important;`);
				} else {
					// Otherwise, append the new property.
					return match.replace("}", ` ${property}: ${value} !important; }`);
				}
			});
		} else {
			// Create a new CSS rule with the editor id as a prefix.
			let classRule;
			if (editorId) {
				classRule = `#${editorId} .${className} { ${property}: ${value} !important; }`;
			} else {
				classRule = `.${className} { ${property}: ${value} !important; }`;
			}
			styleContent += classRule;
		}

		$style.html(styleContent);
		addElementInfo();
	}

	function generateClassName($style) {
		let counter = 0;
		let styleContent = $style.html();
		let matches = styleContent.match(/\.content-\d+/g);
		if (matches && matches.length > 0) {
			let lastClass = matches[matches.length - 1];
			let match = lastClass.match(/content-(\d+)/);
			if (match) {
				counter = parseInt(match[1], 10) + 1;
			}
		}
		return "content-" + counter;
	}


	// Store the selection when text is selected
	document.addEventListener("mouseup", function () {
		const selection = window.getSelection();
		if (selection.rangeCount > 0) {
			selectedRange = selection.getRangeAt(0);
		}
	});

	function applyStyle(property, value) {
		if (!selectedRange) return;

		let parentNode = selectedRange.commonAncestorContainer.parentNode;

		if (parentNode.tagName === "SPAN") {
			// Check if the style already exists with the same value
			if (parentNode.style[property] === value) return; // Prevent duplicate styles

			// Update existing style
			parentNode.style[property] = value;
		} else {
			// Extract selected text
			const selectedText = selectedRange.extractContents();
			let existingSpan = null;

			// Check if extracted text is inside a span
			if (selectedText.childNodes.length === 1 && selectedText.firstChild.tagName === "SPAN") {
				existingSpan = selectedText.firstChild;
			}

			if (existingSpan) {
				// Avoid adding duplicate styles
				if (existingSpan.style[property] !== value) {
					existingSpan.style[property] = value;
				}
			} else {
				// Create a new span if none exists
				const span = document.createElement('span');
				span.style[property] = value;
				span.appendChild(selectedText);
				existingSpan = span;
			}

			// Insert updated span back into the range
			selectedRange.deleteContents();
			selectedRange.insertNode(existingSpan);
		}

		selectedRange = null;
		addElementInfo();
	}

	// Apply text color when the input loses focus or changes
	document.getElementById("color").addEventListener("change", function () {
		applyStyle("color", this.value);
	});
	document.getElementById("color").addEventListener("blur", function () {
		applyStyle("color", this.value);
	});

	// Apply background color when the input loses focus or changes
	document.getElementById("background-color").addEventListener("change", function () {
		applyStyle("backgroundColor", this.value);
	});
	document.getElementById("background-color").addEventListener("blur", function () {
		applyStyle("backgroundColor", this.value);
	});

	function emptyStyleForm() {
		const excludedIds = [
			'background-color', 'color', 'table-rows', 'table-columns', 'table-width',
			'table-border-thickness', 'table-cell-padding', 'table-cell-spacing'
		];

		document.querySelectorAll('.editor-styles form input, .editor-styles form select, .editor-styles form textarea')
			.forEach(element => {
				if (!excludedIds.includes(element.id)) {
					if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
						element.value = '';
					} else if (element.tagName === 'SELECT') {
						element.querySelectorAll('option').forEach(option => {
							option.selected = false;
						});
					}
				}
			});
	}

	// Save changes made in source code
	document.getElementById("save-source").addEventListener("click", function () {
		var editorParentCurrent = editor.querySelector('.editor-parent');
		editorParentCurrent.innerHTML = document.getElementById("source-code").value;
		document.getElementById("source-code-popup").style.display = "none";
		addElementInfo();
	});

	// Close the source code popup
	document.getElementById("close-source-code").addEventListener("click", function () {
		document.getElementById("source-code-popup").style.display = "none";
	});

	//START - table add & edit start
	let selectedCell = null;

	const btnCreateTable = document.getElementById('create-table');
	const btnMergeCell = document.getElementById('table-merge-cells');
	const btnSplitCell = document.getElementById('table-split-cell');
	const btnInsertRowBefore = document.getElementById('table-insert-row-before');
	const btnInsertRowAfter = document.getElementById('table-insert-row-after');
	const btnInsertColumnBefore = document.getElementById('table-insert-column-before');
	const btnInsertColumnAfter = document.getElementById('table-insert-column-after');

	btnCreateTable.addEventListener('click', function () {
		var selection = window.getSelection();
		if (!selection.rangeCount) return;

		var selectedRange = selection.getRangeAt(0);
		var activeElement = selectedRange.startContainer;

		// If the selection is inside a text node, get its parent element
		var parentElement = activeElement.nodeType === 3 ? activeElement.parentNode : activeElement;

		// Check if the selection is inside a table, row, or cell
		if (parentElement.closest('td') || parentElement.closest('tr') || parentElement.closest('table') || parentElement.closest('.editor-styles')) {
			return;
		}

		createTable();
	});

	function selectCell(event) {
		if (selectedCell) {
			selectedCell.classList.remove('selected');
		}
		selectedCell = event.target;
		selectedCell.classList.add('selected');
	}

	btnMergeCell.addEventListener('click', function () {
		if (!selectedCell) return alert('Select a cell first!');

		const colspan = parseInt(prompt('Enter number of columns to merge:', 2));
		if (!colspan || colspan <= 1) return;

		selectedCell.colSpan = colspan;

		// Remove merged cells
		for (let i = 1; i < colspan; i++) {
			selectedCell.parentElement.deleteCell(selectedCell.cellIndex + 1);
		}
	});

	btnSplitCell.addEventListener('click', function () {
		if (!selectedCell) return alert('Select a cell first!');

		const colspan = selectedCell.colSpan;
		if (colspan > 1) {
			selectedCell.colSpan = 1;

			// Insert missing cells after splitting
			for (let i = 1; i < colspan; i++) {
				const newCell = selectedCell.parentElement.insertCell(selectedCell.cellIndex + i);
				newCell.textContent = 'New Cell';
				applyCellStyles(newCell);
				newCell.addEventListener('click', selectCell);
			}
		} else {
			alert('Cell is not merged!');
		}
	});

	btnInsertRowBefore.addEventListener('click', function () {
		insertRow('before');
	});

	btnInsertRowAfter.addEventListener('click', function () {
		insertRow('after');
	});

	btnInsertColumnBefore.addEventListener('click', function () {
		insertColumn('before');
	});

	btnInsertColumnAfter.addEventListener('click', function () {
		insertColumn('after');
	});

	function insertRow(position) {
		if (!selectedCell) return alert('Select a cell first!');

		const table = selectedCell.closest('table');
		const rowIndex = selectedCell.parentElement.rowIndex + (position === 'after' ? 1 : 0);
		const newRow = table.insertRow(rowIndex);

		for (let i = 0; i < table.rows[0].cells.length; i++) {
			const newCell = newRow.insertCell();
			newCell.textContent = 'New Cell';
			applyCellStyles(newCell);
			newCell.addEventListener('click', selectCell);
		}
	}

	function insertColumn(position) {
		if (!selectedCell) return alert('Select a cell first!');

		const table = selectedCell.closest('table');
		const colIndex = selectedCell.cellIndex + (position === 'after' ? 1 : 0);

		for (let row of table.rows) {
			const newCell = row.insertCell(colIndex);
			newCell.textContent = 'New Cell';
			applyCellStyles(newCell);
			newCell.addEventListener('click', selectCell);
		}
	}

	function applyCellStyles(cell) {
		const referenceCell = document.querySelector('table td');
		if (referenceCell) {
			cell.style.border = referenceCell.style.border;
			cell.style.padding = referenceCell.style.padding;
		} else {
			cell.style.border = '1px solid black';
			cell.style.padding = '5px';
		}
	}

	function createTable() {
		const rows = parseInt(document.getElementById('table-rows').value) || 3;
		const columns = parseInt(document.getElementById('table-columns').value) || 3;
		const width = document.getElementById('table-width').value || '100';
		const borderThickness = parseInt(document.getElementById('table-border-thickness').value) || 1;
		const cellPadding = parseInt(document.getElementById('table-cell-padding').value) || 0;
		const cellSpacing = parseInt(document.getElementById('table-cell-spacing').value) || 0;

		const table = document.createElement('table');
		table.style.border = `${borderThickness}px solid black`;
		table.style.width = `${width}%`;
		table.cellPadding = cellPadding;
		table.style.borderSpacing = `${cellSpacing}px`;
		table.style.borderCollapse = "separate";

		for (let i = 0; i < rows; i++) {
			const row = table.insertRow();
			for (let j = 0; j < columns; j++) {
				const cell = row.insertCell();
				cell.textContent = `Row ${i + 1}, Col ${j + 1}`;
				cell.style.border = `${borderThickness}px solid black`;
				cell.addEventListener('click', selectCell);
			}
		}

		const selection = window.getSelection();
		if (selection.rangeCount > 0) {
			const range = selection.getRangeAt(0);
			range.deleteContents();
			range.insertNode(table);
		}

	}
	//END - table add & edit start

	//START - flex and grid show hide
	function addGridStyleHtml() {
		var gridHtml = `<div class="heading">Grid Styling Options</div>
				<div>
				<label for="grid-template-columns">Grid Template Columns:</label>
				<select id="grid-template-columns" name="grid-template-columns">
					<option value=""></option>
					<optgroup label="Auto Fill">
						<option value="grid-template-columns: repeat(auto-fill, 1fr)">grid-template-columns: repeat(auto-fill, 1fr)</option>
						<option value="grid-template-columns: repeat(auto-fill, minmax(25px, 1fr))">grid-template-columns: repeat(auto-fill, minmax(25px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fill, minmax(50px, 1fr))">grid-template-columns: repeat(auto-fill, minmax(50px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fill, minmax(75px, 1fr))">grid-template-columns: repeat(auto-fill, minmax(75px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fill, minmax(100px, 1fr))">grid-template-columns: repeat(auto-fill, minmax(100px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fill, minmax(125px, 1fr))">grid-template-columns: repeat(auto-fill, minmax(125px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fill, minmax(150px, 1fr))">grid-template-columns: repeat(auto-fill, minmax(150px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fill, minmax(175px, 1fr))">grid-template-columns: repeat(auto-fill, minmax(175px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fill, minmax(200px, 1fr))">grid-template-columns: repeat(auto-fill, minmax(200px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fill, minmax(225px, 1fr))">grid-template-columns: repeat(auto-fill, minmax(225px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fill, minmax(250px, 1fr))">grid-template-columns: repeat(auto-fill, minmax(250px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fill, minmax(275px, 1fr))">grid-template-columns: repeat(auto-fill, minmax(275px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr))">grid-template-columns: repeat(auto-fill, minmax(300px, 1fr))</option>
					</optgroup>
					<optgroup label="Auto Fit">
						<option value="grid-template-columns: repeat(auto-fit, 1fr)">grid-template-columns: repeat(auto-fit, 1fr)</option>
						<option value="grid-template-columns: repeat(auto-fit, minmax(25px, 1fr))">grid-template-columns: repeat(auto-fit, minmax(25px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fit, minmax(50px, 1fr))">grid-template-columns: repeat(auto-fit, minmax(50px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fit, minmax(75px, 1fr))">grid-template-columns: repeat(auto-fit, minmax(75px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fit, minmax(100px, 1fr))">grid-template-columns: repeat(auto-fit, minmax(100px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fit, minmax(125px, 1fr))">grid-template-columns: repeat(auto-fit, minmax(125px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fit, minmax(150px, 1fr))">grid-template-columns: repeat(auto-fit, minmax(150px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fit, minmax(175px, 1fr))">grid-template-columns: repeat(auto-fit, minmax(175px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr))">grid-template-columns: repeat(auto-fit, minmax(200px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fit, minmax(225px, 1fr))">grid-template-columns: repeat(auto-fit, minmax(225px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr))">grid-template-columns: repeat(auto-fit, minmax(250px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fit, minmax(275px, 1fr))">grid-template-columns: repeat(auto-fit, minmax(275px, 1fr))</option>
						<option value="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr))">grid-template-columns: repeat(auto-fit, minmax(300px, 1fr))</option>
					</optgroup>
					<optgroup label="Fixed Columns">
						<option value="grid-template-columns: repeat(1, 1fr)">grid-template-columns: repeat(1, 1fr)</option>
						<option value="grid-template-columns: repeat(2, 1fr)">grid-template-columns: repeat(2, 1fr)</option>
						<option value="grid-template-columns: repeat(3, 1fr)">grid-template-columns: repeat(3, 1fr)</option>
						<option value="grid-template-columns: repeat(4, 1fr)">grid-template-columns: repeat(4, 1fr)</option>
						<option value="grid-template-columns: repeat(5, 1fr)">grid-template-columns: repeat(5, 1fr)</option>
						<option value="grid-template-columns: repeat(6, 1fr)">grid-template-columns: repeat(6, 1fr)</option>
						<option value="grid-template-columns: repeat(7, 1fr)">grid-template-columns: repeat(7, 1fr)</option>
						<option value="grid-template-columns: repeat(8, 1fr)">grid-template-columns: repeat(8, 1fr)</option>
						<option value="grid-template-columns: repeat(9, 1fr)">grid-template-columns: repeat(9, 1fr)</option>
						<option value="grid-template-columns: repeat(10, 1fr)">grid-template-columns: repeat(10, 1fr)</option>
					</optgroup>
					<optgroup label="Two Column Layouts">
						<option value="grid-template-columns: 100px auto">grid-template-columns: 100px auto</option>
						<option value="grid-template-columns: 200px auto">grid-template-columns: 200px auto</option>
						<option value="grid-template-columns: 300px auto">grid-template-columns: 300px auto</option>
						<option value="grid-template-columns: 400px auto">grid-template-columns: 400px auto</option>
						<option value="grid-template-columns: 500px auto">grid-template-columns: 500px auto</option>
						<option value="grid-template-columns: auto 100px">grid-template-columns: auto 100px</option>
						<option value="grid-template-columns: auto 200px">grid-template-columns: auto 200px</option>
						<option value="grid-template-columns: auto 300px">grid-template-columns: auto 300px</option>
						<option value="grid-template-columns: auto 400px">grid-template-columns: auto 400px</option>
						<option value="grid-template-columns: auto 500px">grid-template-columns: auto 500px</option>
					</optgroup>
					<optgroup label="Three Column Layouts">
						<option value="grid-template-columns: 100px auto 100px">grid-template-columns: 100px auto 100px</option>
						<option value="grid-template-columns: 200px auto 200px">grid-template-columns: 200px auto 200px</option>
						<option value="grid-template-columns: 300px auto 300px">grid-template-columns: 300px auto 300px</option>
						<option value="grid-template-columns: 400px auto 400px">grid-template-columns: 400px auto 400px</option>
						<option value="grid-template-columns: 500px auto 500px">grid-template-columns: 500px auto 500px</option>
					</optgroup>
				</select>
				</div>
				<div>
				<label for="grid-template-rows">Grid Template Rows:</label>
				<select id="grid-template-rows" name="grid-template-rows">
					<option value=""></option>
						<option value="grid-template-rows: 1fr">grid-template-rows: 1fr</option>
						<option value="grid-template-rows: auto 1fr auto">grid-template-rows: auto 1fr auto</option>
						<option value="grid-template-rows: subgrid">grid-template-rows: subgrid</option>
				</select>
				</div>
				<div>
				<label for="column-gap">Column Gap:</label>
				<select id="column-gap" name="column-gap">
					<option value=""></option>
					<option value="column-gap: 1px">column-gap: 1px</option>
					<option value="column-gap: 2px">column-gap: 2px</option>
					<option value="column-gap: 3px">column-gap: 3px</option>
					<option value="column-gap: 4px">column-gap: 4px</option>
					<option value="column-gap: 5px">column-gap: 5px</option>
					<option value="column-gap: 6px">column-gap: 6px</option>
					<option value="column-gap: 7px">column-gap: 7px</option>
					<option value="column-gap: 8px">column-gap: 8px</option>
					<option value="column-gap: 9px">column-gap: 9px</option>
					<option value="column-gap: 10px">column-gap: 10px</option>
					<option value="column-gap: 15px">column-gap: 15px</option>
					<option value="column-gap: 20px">column-gap: 20px</option>
					<option value="column-gap: 25px">column-gap: 25px</option>
					<option value="column-gap: 30px">column-gap: 30px</option>
					<option value="column-gap: 35px">column-gap: 35px</option>
					<option value="column-gap: 40px">column-gap: 40px</option>
					<option value="column-gap: 45px">column-gap: 45px</option>
					<option value="column-gap: 50px">column-gap: 50px</option>
				</select>
				</div>
				<div>
				<label for="row-gap">Row Gap:</label>
				<select id="row-gap" name="row-gap">
					<option value=""></option>
					<option value="row-gap: 1px">row-gap: 1px</option>
					<option value="row-gap: 2px">row-gap: 2px</option>
					<option value="row-gap: 3px">row-gap: 3px</option>
					<option value="row-gap: 4px">row-gap: 4px</option>
					<option value="row-gap: 5px">row-gap: 5px</option>
					<option value="row-gap: 6px">row-gap: 6px</option>
					<option value="row-gap: 7px">row-gap: 7px</option>
					<option value="row-gap: 8px">row-gap: 8px</option>
					<option value="row-gap: 9px">row-gap: 9px</option>
					<option value="row-gap: 10px">row-gap: 10px</option>
					<option value="row-gap: 15px">row-gap: 15px</option>
					<option value="row-gap: 20px">row-gap: 20px</option>
					<option value="row-gap: 25px">row-gap: 25px</option>
					<option value="row-gap: 30px">row-gap: 30px</option>
					<option value="row-gap: 35px">row-gap: 35px</option>
					<option value="row-gap: 40px">row-gap: 40px</option>
					<option value="row-gap: 45px">row-gap: 45px</option>
					<option value="row-gap: 50px">row-gap: 50px</option>
				</select>
				</div>
				<div>
				<label for="place-items">Place Items (Horizontal & Vertical Combine):</label>
				<select id="place-items" name="place-items">
					<option value=""></option>
					<option value="place-items: start">place-items: start</option>
					<option value="place-items: center">place-items: center</option>
					<option value="place-items: end">place-items: end</option>
				</select>
				</div>
				<div>
				<label for="justify-items">Justify Items (Horizontal Items Align):</label>
				<select id="justify-items" name="justify-items">
					<option value=""></option>
					<option value="justify-items: start">justify-items: start</option>
					<option value="justify-items: center">justify-items: center</option>
					<option value="justify-items: end">justify-items: end</option>
				</select>
				</div>
				<div>
				<label for="align-items">Items Align (Vertical Items Align):</label>
				<select id="align-items" name="align-items">
					<option value=""></option>
					<option value="align-items: start">align-items: start</option>
					<option value="align-items: center">align-items: center</option>
					<option value="align-items: end">align-items: end</option>
				</select>
				</div>
				<div class="heading">Grid Items Styling Options</div>
				<div>
				<label for="grid-column">Grid Column (Span Accross):</label>
				<select id="grid-column" name="grid-column">
					<option value=""></option>
					<option value="grid-column: 1 / -1">grid-column: 1 / -1 = Always Span All The Way Accross</option>
					<option value="grid-column: span 1">grid-column: span 1</option>
					<option value="grid-column: span 2">grid-column: span 2</option>
					<option value="grid-column: span 3">grid-column: span 3</option>
					<option value="grid-column: span 4">grid-column: span 4</option>
					<option value="grid-column: span 5">grid-column: span 5</option>
					<option value="grid-column: span 6">grid-column: span 6</option>
					<option value="grid-column: span 7">grid-column: span 7</option>
					<option value="grid-column: span 8">grid-column: span 8</option>
					<option value="grid-column: span 9">grid-column: span 9</option>
					<option value="grid-column: span 10">grid-column: span 10</option>
				</select>
				</div>
				<div>
				<label for="grid-row">Grid Rows (Span Down):</label>
				<select id="grid-row" name="grid-row">
					<option value=""></option>
					<option value="grid-row: 1 / -1">grid-column: 1 / -1 = Always Span All The Way Down</option>
					<option value="grid-row: span 1">grid-row: span 1</option>
					<option value="grid-row: span 2">grid-row: span 2</option>
					<option value="grid-row: span 3">grid-row: span 3</option>
					<option value="grid-row: span 4">grid-row: span 4</option>
					<option value="grid-row: span 5">grid-row: span 5</option>
					<option value="grid-row: span 6">grid-row: span 6</option>
					<option value="grid-row: span 7">grid-row: span 7</option>
					<option value="grid-row: span 8">grid-row: span 8</option>
					<option value="grid-row: span 9">grid-row: span 9</option>
					<option value="grid-row: span 10">grid-row: span 10</option>
				</select>
				</div>
				<div>
				<label for="text-align-last">Text Align Last (Horizontal Content Align):</label>
				<select id="text-align-last" name="text-align-last">
					<option value=""></option>
					<option value="text-align-last: left">text-align-last: left</option>
					<option value="text-align-last: center">text-align-last: center</option>
					<option value="text-align-last: right">text-align-last: right</option>
				</select>
				</div>
				<div>
				<label for="align-content">Align Content (Vertical Content Align):</label>
				<select id="align-content" name="align-content">
					<option value=""></option>
					<option value="align-content: start">align-content: start</option>
					<option value="align-content: center">align-content: center</option>
					<option value="align-content: end">align-content: end</option>
				</select>
		  </div>`;
		var gridArea = document.getElementById('displayGridArea');

		if (gridArea) {
			gridArea.innerHTML = gridHtml;
			gridArea.style.display = 'block';
		}
	}

	function addFlexStyleHtml() {
		var flexHtml = `<div class="heading">Flex Styling Options</div>
				<div>
				<label for="flex-direction">Flex Direction:</label>
				<select id="flex-direction" name="flex-direction">
					<option value=""></option>
					<option value="flex-direction: row">flex-direction: row</option>
					<option value="flex-direction: row-reverse">flex-direction: row-reverse</option>
					<option value="flex-direction: column">flex-direction: column</option>
					<option value="flex-direction: column-reverse">flex-direction: column-reverse</option>
				</select>
				</div>
				<div>
				<label for="flex-wrap">Flex Wrap:</label>
				<select id="flex-wrap" name="flex-wrap">
					<option value=""></option>
					<option value="flex-wrap: wrap">flex-wrap: wrap</option>
					<option value="flex-wrap: nowrap">flex-wrap: nowrap</option>
				</select>
				</div>
				<div>
				<label for="place-items">Place Items (Horizontal & Vertical Combine):</label>
				<select id="place-items" name="place-items">
					<option value=""></option>
					<option value="place-items: start">place-items: start</option>
					<option value="place-items: center">place-items: center</option>
					<option value="place-items: end">place-items: end</option>
				</select>
				</div>
				<div>
				<label for="justify-content">Justify Content (Vertical / Each Row):</label>
				<select id="justify-content" name="justify-content">
					<option value=""></option>
					<option value="justify-content: flex-start">justify-content: flex-start</option>
					<option value="justify-content: center">justify-content: center</option>
					<option value="justify-content: flex-end">justify-content: flex-end</option>
					<option value="justify-content: space-between">justify-content: space-between</option>
					<option value="justify-content: space-around">justify-content: space-around</option>
					<option value="justify-content: space-evenly">justify-content: space-evenly</option>
				</select>
				</div>
				<div>
				<label for="align-content">Align Content (Vertical Entire Container):</label>
				<select id="align-content" name="align-content">
					<option value=""></option>
					<option value="align-content: flex-start">align-content: flex-start</option>
					<option value="align-content: center">align-content: center</option>
					<option value="align-content: flex-end">align-content: flex-end</option>
					<option value="align-content: space-between">align-content: space-between</option>
					<option value="align-content: space-around">align-content: space-around</option>
					<option value="align-content: space-evenly">align-content: space-evenly</option>
				</select>
				</div>
				<div>
				<label for="align-items">Align Items (Horizontal):</label>
				<select id="align-items" name="align-items">
					<option value=""></option>
					<option value="align-items: start">align-items: start</option>
					<option value="align-items: center">align-items: center</option>
					<option value="align-items: end">align-items: end</option>
				</select>
				</div>
				<div>
				<label for="column-gap">Column Gap:</label>
				<select id="column-gap" name="column-gap">
					<option value=""></option>
					<option value="column-gap: 1px">column-gap: 1px</option>
					<option value="column-gap: 2px">column-gap: 2px</option>
					<option value="column-gap: 3px">column-gap: 3px</option>
					<option value="column-gap: 4px">column-gap: 4px</option>
					<option value="column-gap: 5px">column-gap: 5px</option>
					<option value="column-gap: 6px">column-gap: 6px</option>
					<option value="column-gap: 7px">column-gap: 7px</option>
					<option value="column-gap: 8px">column-gap: 8px</option>
					<option value="column-gap: 9px">column-gap: 9px</option>
					<option value="column-gap: 10px">column-gap: 10px</option>
					<option value="column-gap: 15px">column-gap: 15px</option>
					<option value="column-gap: 20px">column-gap: 20px</option>
					<option value="column-gap: 25px">column-gap: 25px</option>
					<option value="column-gap: 30px">column-gap: 30px</option>
					<option value="column-gap: 35px">column-gap: 35px</option>
					<option value="column-gap: 40px">column-gap: 40px</option>
					<option value="column-gap: 45px">column-gap: 45px</option>
					<option value="column-gap: 50px">column-gap: 50px</option>
				</select>
				</div>
				<div>
				<label for="row-gap">Row Gap:</label>
				<select id="row-gap" name="row-gap">
					<option value=""></option>
					<option value="row-gap: 1px">row-gap: 1px</option>
					<option value="row-gap: 2px">row-gap: 2px</option>
					<option value="row-gap: 3px">row-gap: 3px</option>
					<option value="row-gap: 4px">row-gap: 4px</option>
					<option value="row-gap: 5px">row-gap: 5px</option>
					<option value="row-gap: 6px">row-gap: 6px</option>
					<option value="row-gap: 7px">row-gap: 7px</option>
					<option value="row-gap: 8px">row-gap: 8px</option>
					<option value="row-gap: 9px">row-gap: 9px</option>
					<option value="row-gap: 10px">row-gap: 10px</option>
					<option value="row-gap: 15px">row-gap: 15px</option>
					<option value="row-gap: 20px">row-gap: 20px</option>
					<option value="row-gap: 25px">row-gap: 25px</option>
					<option value="row-gap: 30px">row-gap: 30px</option>
					<option value="row-gap: 35px">row-gap: 35px</option>
					<option value="row-gap: 40px">row-gap: 40px</option>
					<option value="row-gap: 45px">row-gap: 45px</option>
					<option value="row-gap: 50px">row-gap: 50px</option>
				</select>
				</div>
				<div class="heading">Flex Items Styling Options</div>
				<div>
				<label for="flex-shrink">Flex Shrink:</label>
				<select id="flex-shrink" name="flex-shrink">
					<option value=""></option>
					<option value="flex-shrink: 0">flex-shrink: 0 (No Shrink)</option>
					<option value="flex-shrink: 1">flex-shrink: 1 (Allow Shrink)</option>
					<option value="flex-shrink: 2">flex-shrink: 2 (Shrink 2 Times Faster)</option>
					<option value="flex-shrink: 3">flex-shrink: 3 (Shrink 3 Times Faster)</option>
					<option value="flex-shrink: 4">flex-shrink: 4 (Shrink 4 Times Faster)</option>
					<option value="flex-shrink: 5">flex-shrink: 5 (Shrink 5 Times Faster)</option>
					<option value="flex-shrink: 6">flex-shrink: 6 (Shrink 6 Times Faster)</option>
					<option value="flex-shrink: 7">flex-shrink: 7 (Shrink 7 Times Faster)</option>
					<option value="flex-shrink: 8">flex-shrink: 8 (Shrink 8 Times Faster)</option>
					<option value="flex-shrink: 9">flex-shrink: 9 (Shrink 9 Times Faster)</option>
					<option value="flex-shrink: 10">flex-shrink: 10 (Shrink 10 Times Faster)</option>
				</select>
				</div>
				<div>
				<label for="flex-grow">Flex Grow:</label>
				<select id="flex-grow" name="flex-grow">
					<option value=""></option>
					<option value="flex-grow: 0">flex-grow: 0 (No Grow)</option>
					<option value="flex-grow: 1">flex-grow: 1 (Allow Grow)</option>
					<option value="flex-grow: 2">flex-grow: 2 (Grow 2 Times Faster)</option>
					<option value="flex-grow: 3">flex-grow: 3 (Grow 3 Times Faster)</option>
					<option value="flex-grow: 4">flex-grow: 4 (Grow 4 Times Faster)</option>
					<option value="flex-grow: 5">flex-grow: 5 (Grow 5 Times Faster)</option>
					<option value="flex-grow: 6">flex-grow: 6 (Grow 6 Times Faster)</option>
					<option value="flex-grow: 7">flex-grow: 7 (Grow 7 Times Faster)</option>
					<option value="flex-grow: 8">flex-grow: 8 (Grow 8 Times Faster)</option>
					<option value="flex-grow: 9">flex-grow: 9 (Grow 9 Times Faster)</option>
					<option value="flex-grow: 10">flex-grow: 10 (Grow 10 Times Faster)</option>
				</select>
				</div>
				<div>
				<label for="align-self">Align Self:</label>
				<select id="align-self" name="align-self">
					<option value=""></option>
					<option value="align-self: flex-start">align-self: flex-start</option>
					<option value="align-self: center">align-self: center</option>
					<option value="align-self: flex-end">align-self: flex-end</option>
					<option value="align-self: space-between">align-self: space-between</option>
					<option value="align-self: space-around">align-self: space-around</option>
					<option value="align-self: space-evenly">align-self: space-evenly</option>
				</select>
				</div>`;

		var flexArea = document.getElementById('displayFlexArea');

		if (flexArea) {
			flexArea.innerHTML = flexHtml;
			flexArea.style.display = 'block';
		}
	}

	function addGeneralAlignStyleHtml() {
		var generalAlignHtml = `<div class="heading">Alignment Options</div>
				<div>
					<label for="align-items">Align Items (Horizontal):</label>
					<select id="align-items" name="align-items">
						<option value=""></option>
						<option value="align-items: start">align-items: start</option>
						<option value="align-items: center">align-items: center</option>
						<option value="align-items: end">align-items: end</option>
					</select>
				</div>`;

		var generalAlignArea = document.getElementById('generalAlignArea');

		if (generalAlignArea) {
			generalAlignArea.innerHTML = generalAlignHtml;
			generalAlignArea.style.display = 'block';
		}
	}
	//END - flex and grid show hide
});