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
	let selectedCell = null;
	let placeholder = document.createElement("span");
	placeholder.className = "drop-placeholder";

	const editors = document.querySelectorAll('.editor-container');

	editors.forEach(ed => {
		editor = ed;
		ed.addEventListener('click', function (event) {
			editor = this;

			var selection = window.getSelection();
			if (event.target.classList.contains('btn-delete')) {
				event.stopPropagation();
				event.preventDefault();
				if (confirm('Are you really want to remove this element?')) {
					event.target.parentElement.remove();
					setTimeout(() => removeAllRangesForcefully(), 10);
				}
			}
			if (!event.target.classList.contains('btn-delete')) {
				addHighlight(event);
			}
			if (event.ctrlKey) {
				handleClick(event);
			}
			if (event.target.classList.contains('create-table')) {
				if (!selection.rangeCount) {
					alert('Select any area first');
					return;
				}

				var selectedRange = selection.getRangeAt(0);
				var activeElement = selectedRange.startContainer;

				if (activeElement.parentNode.tagName == 'BUTTON' || activeElement.parentNode.tagName == 'LABEL' || activeElement.parentNode.parentNode.parentNode.classList.contains('editor-styles') || activeElement.parentNode.parentNode.parentNode.classList.contains('form')) {
					alert('Select any area first');
					return;
				}

				// If the selection is inside a text node, get its parent element
				var parentElement = activeElement.nodeType === 3 ? activeElement.parentNode : activeElement;

				// Check if the selection is inside a table, row, or cell
				if (parentElement.closest('td') || parentElement.closest('tr') || parentElement.closest('table')) {
					return;
				}

				createTable();
			}
			if (event.target.classList.contains('table-merge-cells')) {
				if (!selectedCell) {
					alert('Select a cell first!');
					return;
				}
				document.getElementById('addCellModal').style.display='none'
				document.getElementById('mergeModal').style.display = 'block';
			}
			if (event.target.classList.contains('table-split-cell')) {
				if (!selectedCell) {
					alert('Select a cell first!');
					return;
				}

				const colspan = selectedCell.colSpan;
				const rowspan = selectedCell.rowSpan;

				const table = selectedCell.closest('table');
				const rowIndex = selectedCell.parentElement.rowIndex;
				const cellIndex = selectedCell.cellIndex;

				let didSplit = false;

				// Handle horizontal split (colspan)
				if (colspan > 1) {
					selectedCell.colSpan = 1;
					for (let i = 1; i < colspan; i++) {
						const newCell = selectedCell.parentElement.insertCell(cellIndex + i);
						newCell.textContent = 'New Cell';
						applyCellStyles(newCell);
						newCell.addEventListener('click', selectCell);
					}
					didSplit = true;
					addElementInfo();
				}

				// Handle vertical split (rowspan)
				if (rowspan > 1) {
					selectedCell.rowSpan = 1;
					for (let i = 1; i < rowspan; i++) {
						const targetRow = table.rows[rowIndex + i];
						if (targetRow) {
							const newCell = targetRow.insertCell(cellIndex);
							newCell.textContent = 'New Cell';
							applyCellStyles(newCell);
							newCell.addEventListener('click', selectCell);
						}
					}
					didSplit = true;
					addElementInfo();
				}

				if (!didSplit) {
					alert('You cannot split this cell because it is not part of merged cells. Only merged cells can be split.');
				}
			}
			if (event.target.classList.contains('table-add-cell')) {
				if (!selectedCell) {
					alert('Select a cell first!');
					return;
				}
				document.getElementById('mergeModal').style.display='none'
				document.getElementById('addCellModal').style.display = 'block';
			}
			if (event.target.classList.contains('table-insert-row-before')) {
				insertRow('before');
			}
			if (event.target.classList.contains('table-insert-row-after')) {
				insertRow('after');
			}
			if (event.target.classList.contains('table-insert-column-before')) {
				insertColumn('before');
			}
			if (event.target.classList.contains('table-insert-column-after')) {
				insertColumn('after');
			}
			if (selection.rangeCount > 0) {

				// Find the highlighted element instead of using the cursor selection
				var $highlightedElement = $(editor).find('.highlighted');

				if ($highlightedElement.length === 0) {
					console.log("No highlighted element found!");
					return;
				}

				emptyStyleForm();

				// Ensure the highlighted element exists
				var $currentEditorContainer = $highlightedElement.closest('.editor-container');
				var styleMap = {};

				// Get the current class name(s) of the highlighted element
				var currentClass = $highlightedElement.attr('class');

				if (currentClass) {
					// Match both "content-0" and "content-1-0" style class patterns
					var classMatches = currentClass.match(/content-\d+(?:-\d+)?/g);

					if (classMatches) {
						var $styleTags = $currentEditorContainer.find('style');
						if ($styleTags.length > 0) {
							$styleTags.each(function () {
								var styleContent = $(this).html();

								classMatches.forEach(function (className) {
									var classRuleRegex = new RegExp('\\.' + className + '\\s*{([^}]*)}', 'g');
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
												styleMap[property] = value;
											}
										});
									}
								});
							});
						}
					}
				}

				// Loop through each form field in the current editor and set its value based on the collected styles
				$currentEditorContainer.find('.editor-styles .form')
					.find('input, select, textarea')
					.each(function () {
						var fieldProperty = $(this).attr('name');

						if (fieldProperty == 'background-color' || fieldProperty == 'border-color' || fieldProperty == 'color') {
							return;
						}

						if (styleMap[fieldProperty]) {
							var fieldValue = fieldProperty + ': ' + styleMap[fieldProperty].replace(/\s*!important$/, '');
							$(this).val(fieldValue);

							// If it's a select field, select the correct option
							if ($(this).is('select')) {
								var fieldNameBeforeColon = fieldProperty.split(':')[0].trim();
								$(this).find('option').each(function () {
									var optionValue = $(this).val().trim();
									if (optionValue === fieldNameBeforeColon) {
										$(this).prop('selected', true);
									}
								});
							}
						}
					});
			}
		});

		ed.addEventListener('keypress', function (e) {
			if (e.code === 'Enter' || e.code === 'NumpadEnter') {

				var currentPGlobal = false;
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
						.replace(/New p!,/g, '')
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
						btnDel.setAttribute('type', 'button');
						newParagraph.insertBefore(btnDel, newParagraph.firstChild);

						const btnHandle = document.createElement('button');
						btnHandle.classList.add('btn-handle');
						btnHandle.setAttribute('type', 'button');
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

						// currentHeader.childNodes.forEach(node => {
						//     if (node.nodeName === "STRONG" || node.nodeName === "EM" || node.nodeName === "U" || node.nodeName === "S" || node.nodeName === "DIV" || (node.nodeName === "BUTTON" && !node.classList.contains("btn-handle") && !node.classList.contains("btn-delete"))) {
						//         isTag = true;
						//     }
						// });

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
								btnDel.setAttribute('type', 'button');
								newHeader.insertBefore(btnDel, newHeader.firstChild);

								const btnHandle = document.createElement('button');
								btnHandle.classList.add('btn-handle');
								btnHandle.setAttribute('type', 'button');
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
								selection.removeAllRanges();
								selection.addRange(newRange);

							}
						} else {
							// Split the header into two
							const br = document.createElement("br");
							range.insertNode(br);
							selection.removeAllRanges();
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
						currentPGlobal = true;
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
							.replace(/New p!,/g, '')
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
							btnDel.setAttribute('type', 'button');
							btnDel.textContent = 'Delete';
							newParagraph.insertBefore(btnDel, newParagraph.firstChild);

							const btnHandle = document.createElement('button');
							btnHandle.classList.add('btn-handle');
							btnHandle.setAttribute('type', 'button');
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
								if (node.nodeName === "STRONG" || node.nodeName === "EM" || node.nodeName === "U" || node.nodeName === "S" || node.nodeName === "A" || node.nodeName === "SPAN" || node.nodeName === "DIV" || node.nodeName === "P" || (node.nodeName === "BUTTON" && !node.classList.contains('btn-handle') && !node.classList.contains('btn-delete'))) {
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
									btnDel.setAttribute('type', 'button');
									btnDel.textContent = 'Delete';
									newParagraph.insertBefore(btnDel, newParagraph.firstChild);

									const btnHandle = document.createElement('button');
									btnHandle.classList.add('btn-handle');
									btnHandle.setAttribute('type', 'button');
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

									let nodes = Array.from(newParagraph.childNodes);

									var firstMeaningfulNode = nodes.find(node => {
										if (node.nodeType === Node.TEXT_NODE) {
											return node.textContent.trim().length > 0;
										}
										if (node.nodeType === Node.ELEMENT_NODE) {
											return ['STRONG', 'EM', 'U', 'S', 'DIV', 'SPAN'].includes(node.tagName);
										}
										return false;
									});

									if (
										firstMeaningfulNode.tagName === 'STRONG' ||
										firstMeaningfulNode.tagName === 'EM' ||
										firstMeaningfulNode.tagName === 'U' ||
										firstMeaningfulNode.tagName === 'S' ||
										firstMeaningfulNode.tagName === 'DIV' ||
										firstMeaningfulNode.tagName === 'SPAN'
									) {
										let innerElement = newParagraph.querySelector(firstMeaningfulNode.tagName);
										if (innerElement) {
											// Check if <strong> is at the very beginning of the paragraph
											let nodes = Array.from(newParagraph.childNodes);
											let strongIndex = nodes.indexOf(innerElement);

											let hasTextBeforeStrong = nodes
												.slice(0, strongIndex)
												.some(node => node.nodeType === Node.TEXT_NODE && node.textContent.trim().length > 0);

											let newRange = document.createRange();
											if (!hasTextBeforeStrong) {
												// Only set cursor inside <strong> if it's the first real content
												let textNode = Array.from(innerElement.childNodes).find(node =>
													node.nodeType === Node.TEXT_NODE && node.textContent.trim().length > 0
												);
												if (textNode) {
													newRange.setStart(textNode, 0);
												} else {
													newRange.setStart(innerElement, 0); // fallback
												}
											} else {
												// Set cursor at beginning of the paragraph (before strong tag)
												let firstTextNode = nodes.find(node =>
													node.nodeType === Node.TEXT_NODE && node.textContent.trim().length > 0
												);
												if (firstTextNode) {
													newRange.setStart(firstTextNode, 0);
												} else {
													newRange.setStart(newParagraph, 0); // fallback
												}
											}

											newRange.collapse(true);
											selection.removeAllRanges();
											selection.addRange(newRange);
										}
									} else {
										// Set cursor at the start of the new paragraph
										let newRange = document.createRange();
										newRange.setStart(newParagraph, 0);
										newRange.collapse(true);

										selection.removeAllRanges();
										selection.addRange(newRange);
									}
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
								btnDel.setAttribute('type', 'button');
								btnDel.textContent = 'Delete';
								newParagraph.insertBefore(btnDel, newParagraph.firstChild);

								const btnHandle = document.createElement('button');
								btnHandle.classList.add('btn-handle');
								btnHandle.setAttribute('type', 'button');
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
							btnDel.setAttribute('type', 'button');
							btnDel.textContent = 'Delete';
							newParagraph.insertBefore(btnDel, newParagraph.firstChild);

							const btnHandle = document.createElement('button');
							btnHandle.classList.add('btn-handle');
							btnHandle.setAttribute('type', 'button');
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
							var isTag = false;

							currentLi.childNodes.forEach(node => {
								if (node.nodeName === "STRONG" || node.nodeName === "EM" || node.nodeName === "U" || node.nodeName === "S" || node.nodeName === "A" || node.nodeName === "SPAN" || node.nodeName === "DIV" || (node.nodeName === "BUTTON" && !node.classList.contains('btn-handle') && !node.classList.contains('btn-delete'))) {
									isTag = true;
								}
							});

							if (isTag) {
								const newListItem = document.createElement('li');
								newListItem.classList.add('highlighted');
								// newListItem.setAttribute('draggable', true);
								newListItem.innerHTML = '<br>'; //Insert a line break

								const btnDel = document.createElement('button');
								btnDel.classList.add('btn-delete');
								btnDel.setAttribute('type', 'button');
								btnDel.textContent = 'Delete';
								newListItem.insertBefore(btnDel, newListItem.firstChild);

								const btnHandle = document.createElement('button');
								btnHandle.classList.add('btn-handle');
								btnHandle.setAttribute('type', 'button');
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

								currentLi.querySelectorAll('.highlighted').forEach(el => el.classList.remove('highlighted'));
								parentUl.querySelectorAll('.btn-delete').forEach(btn => btn.remove());
								parentUl.querySelectorAll('.btn-handle').forEach(btn => btn.remove());
								//Insert the new <li> after the current one
								parentUl.insertBefore(newListItem, currentLi.nextSibling);

								// Set cursor inside the new <li>
								const range = document.createRange();
								const selection = window.getSelection();

								let textNode = newListItem.lastChild;
								if (textNode && textNode.nodeType === Node.ELEMENT_NODE && textNode.tagName === 'BR') {
									range.setStartAfter(textNode); // Place cursor after <br> so user can type
								} else if (textNode && textNode.nodeType === Node.TEXT_NODE) {
									range.setStart(textNode, 0); // Place cursor at beginning of text
								} else {
									range.setStart(newListItem, newListItem.childNodes.length); // Fallback
								}

								// range.setStart(newListItem, 2); //Set the cursor at the start of the new <li>
								range.collapse(true);
								selection.removeAllRanges();
								selection.addRange(range);
							} else {
								const newListItem = document.createElement('li');
								newListItem.classList.add('highlighted');
								// newListItem.setAttribute('draggable', true);
								newListItem.innerHTML = '<br>'; //Insert a line break

								const btnDel = document.createElement('button');
								btnDel.classList.add('btn-delete');
								btnDel.setAttribute('type', 'button');
								btnDel.textContent = 'Delete';
								newListItem.insertBefore(btnDel, newListItem.firstChild);

								const btnHandle = document.createElement('button');
								btnHandle.classList.add('btn-handle');
								btnHandle.setAttribute('type', 'button');
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
							}
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
							btnDel.setAttribute('type', 'button');
							btnDel.textContent = 'Delete';
							newParagraph.insertBefore(btnDel, newParagraph.firstChild);

							// Create the Handle button
							const btnHandle = document.createElement('button');
							btnHandle.classList.add('btn-handle');
							btnHandle.setAttribute('type', 'button');
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
							btnDel.setAttribute('type', 'button');
							btnDel.textContent = 'Delete';

							// Create Drag button
							const btnHandle = document.createElement('button');
							btnHandle.classList.add('btn-handle');
							btnHandle.setAttribute('type', 'button');
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

							let textNode = null;
							afterParagraph.childNodes.forEach(node => {
								if (node.nodeType === Node.TEXT_NODE && node.textContent.trim() !== '') {
									textNode = node;
								}
							});

							const newRange = document.createRange();
							if (textNode) {
								// Place cursor at start of the text node
								newRange.setStart(textNode, 0);
								newRange.collapse(true);
							} else {
								// Fallback: place at end of paragraph
								newRange.selectNodeContents(afterParagraph);
								newRange.collapse(false);
							}
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
							btnDel.setAttribute('type', 'button');
							btnDel.textContent = 'Delete';

							// Create Drag button
							const btnHandle = document.createElement('button');
							btnHandle.classList.add('btn-handle');
							btnHandle.setAttribute('type', 'button');
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
							btnDel.setAttribute('type', 'button');
							btnDel.textContent = 'Delete';

							// Create Drag button
							const btnHandle = document.createElement('button');
							btnHandle.classList.add('btn-handle');
							btnHandle.setAttribute('type', 'button');
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
			if (e.code === 'Enter' || e.code === 'NumpadEnter') {
				if (currentPGlobal) {
					setTimeout(() => trackCursorPosition(true), 30);
				} else {
					setTimeout(() => trackCursorPosition(), 30);
				}
			}
		});

		ed.addEventListener("keydown", function (e) {
			let selection = window.getSelection();
			if (!selection.rangeCount) return;

			let range = selection.getRangeAt(0);
			let parentElementA = range.commonAncestorContainer.parentElement;

			if (parentElementA && parentElementA.tagName === 'A') {
				let anchorText = parentElementA.textContent;
				let cursorPosition = range.startOffset;

				// Clean up anchor text
				anchorText = anchorText
					.replace(/Delete/g, '')
					.replace(/BUTTON,/g, '')
					.replace(/highlighted/g, '')
					.replace(/SPAN,/g, '')
					.replace(/STRONG,/g, '')
					.replace(/Drag,/g, '')
					.replace(/A,/g, '')
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
					.replace(/content-\d-\d+/g, '')
					.replace(/^\s+|\s+$/g, '');

				// Check if cursor is at the end
				if (parentElementA.classList.contains('highlighted')) {

					if (e.key === "ArrowLeft" || e.key === "ArrowRight") {
						return; // Do nothing, let browser handle movement
					}

					// Handle Backspace: Allow removal of " ."
					if (e.key === "Backspace" || e.key === "Delete") {
						if (anchorText.endsWith(" .") && cursorPosition === anchorText.length - 2) {
							e.preventDefault(); // Prevent default Backspace behavior
							parentElementA.textContent = anchorText.slice(0, -2); // Remove " ."

							// Move cursor to the new end position
							let newRange = document.createRange();
							newRange.setStart(parentElementA.firstChild, anchorText.length - 2);
							newRange.collapse(true);

							selection.removeAllRanges();
							selection.addRange(newRange);
						}
						addElementInfo();

						return; // Exit since Backspace was handled
					}

					// Check if ' .' already exists, and remove it before updating text
					if (anchorText.endsWith(" .")) {
						anchorText = anchorText.slice(0, -2); // Remove existing " ."
					}

					// Check if cursor is at the end
					if (cursorPosition === anchorText.length) {
						e.preventDefault(); // Prevent default typing

						// Capture the typed character
						let newChar = e.key.length === 1 ? e.key : ''; // Ignore non-character keys

						// Update anchor text with new character + " ."
						let updatedText = anchorText + newChar + " .";
						parentElementA.textContent = updatedText;

						// Move the cursor before " ."
						let newRange = document.createRange();
						let newPos = updatedText.length - 2; // Before " ."
						newRange.setStart(parentElementA.firstChild, newPos);
						newRange.collapse(true);

						selection.removeAllRanges();
						selection.addRange(newRange);
					}
				}
				addElementInfo();

			}
		});

		ed.addEventListener("input", function (e) {
			var editorParentCurrent = editor.querySelector(".editor-parent");
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

			if (e.target.classList.contains('background-color')) {
				applyClassStyle("background-color", e.target.value);
			}
			if (e.target.classList.contains('color')) {
				applyClassStyle("color", e.target.value);
			}
			if (e.target.classList.contains('border-color')) {
				applyClassStyle("border-color", e.target.value);
			}
		});

		ed.addEventListener("dragstart", function (e) {
			if (e.target.classList.contains("btn-handle")) {
				draggedElement = e.target.closest(".element-highlighter");
				e.dataTransfer.setData("text/plain", "");

				setTimeout(() => {
					draggedElement.style.opacity = "0.5";
				}, 0);

				// Set placeholder height to match dragged element
				placeholder.style.height = `${draggedElement.offsetHeight}px`;
			} else {
				e.preventDefault();
			}
		});

		ed.addEventListener("dragend", function () {
			if (draggedElement) {
				draggedElement.style.opacity = "1";
			}

			// Remove highlight and placeholder
			document.querySelectorAll(".element-highlighter.highlight").forEach(el => el.classList.remove("highlight"));
			if (placeholder.parentNode) {
				placeholder.parentNode.removeChild(placeholder);
			}
		});

		ed.addEventListener("dragover", function (e) {
			e.preventDefault();
			let dropTarget = e.target.closest(".element-highlighter");

			// Ensure dropTarget exists
			if (!dropTarget) return;

			if (dropTarget.parentElement.classList.contains('editor-parent') || dropTarget.tagName === 'TR' || dropTarget.tagName === 'UL' || dropTarget.tagName === 'OL' || dropTarget.tagName === 'SPAN' || dropTarget.tagName === 'A' || dropTarget.tagName === 'STRONG' || dropTarget.tagName === 'EM' || dropTarget.tagName === 'UNDERLINE' || dropTarget.tagName === 'S') return;

			// If the dropTarget is the direct parent of draggedElement, return
			if (draggedElement && dropTarget === draggedElement.parentElement) {
				return;
			}

			if(draggedElement.parentElement === dropTarget.parentElement && draggedElement.parentElement.parentElement.classList.contains('editor-parent')) {
				return;
			}

			if (dropTarget !== draggedElement) {
				const targetRect = dropTarget.getBoundingClientRect();
				const dropPosition = e.clientY - targetRect.top;

				// Ensure placeholder is in the same parent before inserting
				if (placeholder.parentNode !== dropTarget.parentNode) {
					dropTarget.parentNode.insertBefore(placeholder, dropTarget);
				}

				// Insert placeholder in correct position
				if (dropPosition < targetRect.height / 2) {
					if (placeholder !== dropTarget.previousSibling) {
						dropTarget.parentNode.insertBefore(placeholder, dropTarget);
					}
				} else {
					if (placeholder !== dropTarget.nextSibling) {
						dropTarget.parentNode.insertBefore(placeholder, dropTarget.nextSibling);
					}
				}
			}
		});

		ed.addEventListener("drop", function (e) {
			e.preventDefault();
			const dropTarget = e.target.closest(".element-highlighter");

			// Prevent dropping on itself
			if (dropTarget === draggedElement) {
				return;
			}

			if (draggedElement && placeholder.parentNode) {
				placeholder.parentNode.replaceChild(draggedElement, placeholder);
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

		ed.addEventListener('change', function (e) {
			if (e.target.classList.contains('display')) {
				var displayVal = e.target.value;

				// Scope to the current block
				const editorStyles = e.target.closest('.editor-styles');
				if (!editorStyles) return;

				const flexArea = editorStyles.querySelector('.displayFlexArea');
				const gridArea = editorStyles.querySelector('.displayGridArea');
				const generalAlignArea = editorStyles.querySelector('.generalAlignArea');

				// Reset all
				[flexArea, gridArea, generalAlignArea].forEach(area => {
					if (area) {
						area.style.display = 'none';
						area.innerHTML = '';
					}
				});

				// Call the correct function with the correct element
				if (displayVal === 'display: flex') {
					flexArea.style.display = 'block';
					addFlexStyleHtml(flexArea);
				} else if (displayVal === 'display: grid') {
					gridArea.style.display = 'block';
					addGridStyleHtml(gridArea);
				} else {
					generalAlignArea.style.display = 'block';
					addGeneralAlignStyleHtml(generalAlignArea);
				}
			}
		});
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

	function formatHtml(html) {
		// Extract the <style> tag
		let styleMatch = html.match(/<style[\s\S]*?<\/style>/i);
		let styleTag = styleMatch ? styleMatch[0] : "";

		if (styleTag) {
			// Remove the <style> tag from the original HTML
			html = html.replace(styleTag, "");

			// Clean up multiple !important (e.g., "!important !important !important" => "!important")
			styleTag = styleTag.replace(/(!important\s*){2,}/g, '!important ');

			// Trim spaces before class names inside the <style> tag
			styleTag = styleTag.replace(/(\n|\r)\s+(\..+?{)/g, "\n$2").trim() + "\n";
		}

		// Remove @font-face styles from <style> blocks
		html = html.replace(/@font-face\s*{[^}]+}/gi, '');

		// Preserve inline formatting tags
		const inlineTags = ['strong', 'em', 'u', 's'];
		const inlineTagPattern = new RegExp("(<(" + inlineTags.join('|') + ")[^>]*>.*?</\\2>)", "gi");
		let preserved = [];
		html = html.replace(inlineTagPattern, (match) => {
			preserved.push(match);
			return `__INLINE_TAG_${preserved.length - 1}__`;
		});

		// Format the HTML with proper indentation
		let formatted = html.replace(/(>)(<)(\/*)/g, "$1\n$2$3");
		let lines = formatted.split('\n');
		let indentLevel = 0;
		let formattedHtml = '';

		lines.forEach(line => {
			line = line.trim();
			if (line.match(/^<\//)) {
				indentLevel--;
			}
			indentLevel = Math.max(indentLevel, 0);
			formattedHtml += '    '.repeat(indentLevel) + line + '\n';
			if (line.match(/^<\w[^>]*>$/) && !line.match(/^<\//)) {
				indentLevel++;
			}
		});

		// Restore preserved inline tags
		formattedHtml = formattedHtml.replace(/__INLINE_TAG_(\d+)__/g, (match, index) => preserved[index]);

		// Prepend the cleaned <style> tag
		return styleTag + formattedHtml.trim();
	}

	//START - highlighting all elements inside id="editor" on hover
	let highlightedElement = null; // To store the currently highlighted element
	let brElement = null; // To store the inserted <br> element
	function addHighlight(event) {
		const hoveredElement = event.target;
		var editorParentCurrent = editor.querySelector('.editor-parent');
		if (editorParentCurrent.contains(hoveredElement)) {
			removeAllHighlights();
			if(!hoveredElement.classList.contains('content-styles')) {
				hoveredElement.classList.add('highlighted');
			}

			if (!(hoveredElement.tagName === 'SPAN' || hoveredElement.tagName === 'A' || hoveredElement.tagName === 'BUTTON' || hoveredElement.tagName === 'STRONG' || hoveredElement.tagName === 'EM' || hoveredElement.tagName === 'U' || hoveredElement.tagName === 'S' || hoveredElement.classList.contains('content-styles'))) {
				const btnDel = document.createElement('button');
				btnDel.classList.add('btn-delete');
				btnDel.setAttribute('type', 'button');
				btnDel.textContent = 'Delete';
				hoveredElement.insertBefore(btnDel, hoveredElement.firstChild);

				const btnHandle = document.createElement('button');
				btnHandle.classList.add('btn-handle');
				btnHandle.setAttribute('type', 'button');
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

	function trackCursorPosition(isP = false) {
		const selection = window.getSelection();
		if (!selection || selection.rangeCount === 0) return;

		const range = selection.getRangeAt(0);
		const parentElement = range.startContainer.parentNode;
		if (['element', 'element-info', 'btn-delete', 'btn-handle', 'btn-handle-span'].some(cls => parentElement.classList.contains(cls))) {
			selection.removeAllRanges();
			document.body.focus();
		} else {
			const highlightedContainer = parentElement.querySelector('.highlighted');
			if (!highlightedContainer) return;

			if (isP) {
				let textNode = null;


				// Try to find a real text node
				for (const node of highlightedContainer.childNodes) {
					if (node.nodeType === Node.TEXT_NODE && node.textContent.trim().length > 0) {
						textNode = node;
						break;
					}
				}

				const newRange = document.createRange();

				if (textNode) {
					// Set cursor at beginning of first text node
					newRange.setStart(textNode, 0);
				} else if (highlightedContainer.childNodes.length > 0) {
					// If no text, place cursor after last child (like <br>)
					newRange.setStartAfter(highlightedContainer.lastChild);
				} else {
					// Fallback: Set cursor inside the container
					newRange.setStart(highlightedContainer, 0);
				}

				newRange.collapse(true);
				selection.removeAllRanges();
				selection.addRange(newRange);
			} else {
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
	}

	document.addEventListener('selectionchange', function () {
		const selection = window.getSelection();
		if (!selection || selection.isCollapsed) return;

		var selectedText = selection.toString();
		if (selectedText.trim().length > 0) {
			if (selectedText.match(/Drag|Delete/)) {
				selectedText = '';
				removeAllRangesForcefully();
			}
		}
	});

	//START - Optionally, you can add a keypress listener to support "Ctrl+Z" for undo and "Ctrl+Y" for redo and prevent "DELETE" key
	document.addEventListener('keydown', function (e) {
		if (!(e.key === '' || e.key === 'ArrowUp' || e.key === 'ArrowDown' || e.key === 'ArrowRight' || e.key === 'ArrowLeft' || e.key === 'Backspace' || e.key === 'Delete' || e.key === 'Enter' || e.code === 'NumpadEnter')) {
			if (e.key.length !== 1) return;

			const selection = window.getSelection();
			if (!selection || selection.rangeCount === 0) return;
			const range = selection.getRangeAt(0);
			const currentContainer = range.startContainer;

			if (currentContainer.nodeType === 1 && currentContainer.classList.contains('element-info')) {
				e.preventDefault();
				alert('You have selected the element info, so editing is not allowed here. Please select only inner text.');
				return;
			}

			if (currentContainer.parentElement.classList.contains('btn-delete') || currentContainer.parentElement.classList.contains('btn-handle-span')) {
				removeAllRangesForcefully();
				return;
			}

			if (currentContainer.parentElement.id === 'source-code-popup') {
				return;
			}

			// Check if user selected entire element's text
			const selectedText = range.toString().trim();
			const fullText = currentContainer.textContent.trim();

			if (selectedText && selectedText === fullText) {
				e.preventDefault(); // stop normal delete

				// Clear and insert '...'
				currentContainer.textContent = e.key;

				// Move cursor to end of '...'
				const newRange = document.createRange();
				newRange.setStart(currentContainer, 1); // after 3 dots
				newRange.collapse(true);
				selection.removeAllRanges();
				selection.addRange(newRange);
				return;
			}

			// Agar text "..." se start ho raha hai
			if (currentContainer.textContent.startsWith('...')) {
				// Wait for the character to be inserted, then remove dots
				setTimeout(() => {
					const updatedText = currentContainer.textContent;
					if (updatedText.startsWith('...')) {
						currentContainer.textContent = updatedText.replace(/^\.{3}/, '');

						// Move cursor to end
						const newRange = document.createRange();
						newRange.selectNodeContents(currentContainer);
						newRange.collapse(false);
						selection.removeAllRanges();
						selection.addRange(newRange);
					}
				}, 0);
			}
		} else {
			// Save state when Spacebar is pressed
			if (e.key === ' ') {
				saveState();
			}

			if (e.key === 'ArrowUp' || e.key === 'ArrowDown' || e.key === 'ArrowRight' || e.key === 'ArrowLeft') {
				setTimeout(() => trackCursorPosition(), 30);
			}

			if (e.key === 'Backspace') {
				const selection = window.getSelection();
				if (!selection || selection.rangeCount === 0) return;
				const range = selection.getRangeAt(0);
				const currentContainer = range.startContainer;

				// Check if user selected entire element's text
				const selectedText = range.toString().trim();
				const fullText = currentContainer.textContent.trim();

				if (currentContainer.nodeType === 1 && currentContainer.classList.contains('element-info')) {
					e.preventDefault();
					alert('You have selected the element info, so editing is not allowed here. Please select only inner text.');
					return;
				}

				if (selectedText && selectedText === fullText) {
					e.preventDefault(); // stop normal delete

					// Clear and insert '...'
					currentContainer.textContent = '...';

					// Move cursor to end of '...'
					const newRange = document.createRange();
					newRange.setStart(currentContainer, 3); // after 3 dots
					newRange.collapse(true);
					selection.removeAllRanges();
					selection.addRange(newRange);
					return;
				}

				if (currentContainer.parentElement.id === 'source-code-popup') {
					return;
				}

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

				if (!currentH) {
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
						.replace(/A,/g, '')
						.replace(/LI,/g, '')
						.replace(/H1,/g, '')
						.replace(/H2,/g, '')
						.replace(/H3,/g, '')
						.replace(/H4,/g, '')
						.replace(/H5,/g, '')
						.replace(/H6,/g, '')
						.replace(/DIV,/g, '')
						.replace(/font-size-\d+/g, '')
						.replace(/content-\d-\d+/g, '')
						.replace(/^\s+|\s+$/g, '');
				} else {
					preventText = null;
				}

				// if(cursorPosition === 0){
				//     e.preventDefault();
				//     return;
				// }

				if (
					(
						currentContainer.parentNode.tagName.toLowerCase() === 'span' ||
						currentContainer.parentNode.tagName.toLowerCase() === 'strong' ||
						currentContainer.parentNode.tagName.toLowerCase() === 'em' ||
						currentContainer.parentNode.tagName.toLowerCase() === 'u' ||
						currentContainer.parentNode.tagName.toLowerCase() === 's' ||
						currentContainer.parentNode.tagName.toLowerCase() === 'a'
					)
					&& cursorPosition === 1 && preventText.length === 1
				) {
					e.preventDefault();
					currentContainer.parentElement.remove();
				} else {
					const selectedRange = selection.getRangeAt(0);
					const selectedText = selectedRange.toString().trim();
					const alphaCount = (selectedText.match(/[a-zA-Z]/g) || []).length;

					if (
						(cursorPosition === 3 && alphaCount === 0 && preventText.length === 0) ||
						(cursorPosition === 0 && preventText.length >= 1) ||
						(cursorPosition === 2 && preventText === '' && preventText != null)
					) {
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

				// Check if user selected entire element's text
				const selectedText = range.toString().trim();
				const fullText = currentContainer.textContent.trim();

				if (selectedText && selectedText === fullText) {
					e.preventDefault(); // stop normal delete

					// Clear and insert '...'
					currentContainer.textContent = '...';

					// Move cursor to end of '...'
					const newRange = document.createRange();
					newRange.setStart(currentContainer, 3); // after 3 dots
					newRange.collapse(true);
					selection.removeAllRanges();
					selection.addRange(newRange);
					return;
				}

				if (currentContainer.nodeType === 1 && currentContainer.classList.contains('element-info')) {
					e.preventDefault();
					alert('You have selected the element info, so editing is not allowed here. Please select only inner text.');
				}

				let currentText = currentContainer.innerText || "";

				if (currentContainer.parentElement.id === 'source-code-popup') {
					return;
				}

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
					const selectedRange = selection.getRangeAt(0);
					const selectedText = selectedRange.toString().trim();
					const alphaCount = (selectedText.match(/[a-zA-Z]/g) || []).length;

					//If cursor is at the end of the <p> (no more text after it)
					if ((cursorPosition === 3 && alphaCount === 0) || (cursorPosition === preventText.length) || (cursorPosition === 2 && preventText === "")) {
						e.preventDefault();
					}
				}
			}
		}
	});

	function cleanText(text) {
		if (typeof text !== 'string') {
			return '';
		}

		return text
			.replace(/Delete|BUTTON,|SPAN,|STRONG,|EM,|U,|S,|highlighted|Drag|STRONG,|EM,|U,|S,|\n|P,|LI,|H[1-6],|font-size-\d+|content-\d-\d+/g, '')
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
				const targetMatch = linkHTML.match(/target="([^"]+)"/);
				const relMatch = linkHTML.match(/rel="([^"]+)"/);

				anchorNode.href = url;
				if (targetMatch) {
					anchorNode.target = targetMatch[1];
				} else {
					anchorNode.removeAttribute("target");
				}

				if (relMatch) {
					anchorNode.rel = relMatch[1];
				} else {
					anchorNode.removeAttribute("rel");
				}

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

			const targetMatch = linkHTML.match(/target="([^"]+)"/);
			if (targetMatch) {
				selectedLink.target = targetMatch[1];
			} else {
				selectedLink.removeAttribute("target");
			}

			const relMatch = linkHTML.match(/rel="([^"]+)"/);
			if (relMatch) {
				selectedLink.rel = relMatch[1];
			} else {
				selectedLink.removeAttribute("rel");
			}
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
				document.querySelectorAll(".editor-styles .form input, .editor-styles .form select, .editor-styles .form textarea").forEach(field => {
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

			if (!selection.rangeCount) {
				alert('Please select some text first!');
				return;
			}

			const range = selection.getRangeAt(0);
			const selectedEditor = getClosestEditor(range.startContainer);

			if (alphaCount < 1 || selectedEditor !== editorContainer) {
				alert('Please select some text first!');
				return;
			}

			handleInsertLink();
			addElementInfo();
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
		else if (e.target.classList[0] === 'addElementButton') {
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

			const newRange = document.createRange();
			const textNode = newElement.firstChild;
			if (textNode && textNode.nodeType === Node.TEXT_NODE) {
				const textLength = textNode.textContent.length;
				newRange.setStart(textNode, textLength);
				newRange.setEnd(textNode, textLength);
			} else {
				// fallback if no textNode exists
				newRange.setStartAfter(newElement);
				newRange.setEndAfter(newElement);
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

	$('.editor-styles .form').on('change', function(e) {
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
			$('.editor-styles .form').find('input, select, textarea').each(function () {
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
					removeAllRangesForcefully();
				}
			});
		} else {
			const $highlightedElement = $(editorContainer).find('.highlighted');
			if ($highlightedElement.length === 0) return;

			const $editor = $(editorContainer).find('.editor-parent');
			let $style = $editor.find('style');
			const editorId = $editor.attr('id');
			const editorNumber = editorId ? editorId.replace(/\D+/g, '') : '0';

// Check for existing class using regex like content-1-3
			let existingClassMatch = $highlightedElement.attr('class').match(new RegExp(`content-${editorNumber}-(\\d+)`));
			let styleName = existingClassMatch ? existingClassMatch[0] : '';

// Create <style> tag if missing
			if ($style.length === 0) {
				$style = $('<style></style>');
				$editor.prepend($style);
			}

			if (!styleName) {
				styleName = generateClassName($style, editorNumber); // You already have this method
				$highlightedElement.addClass(styleName);
			}

// Build updated CSS properties block
			let cssProps = '';
			$('.editor-styles .form').find('input, select, textarea').each(function () {
				const val = $(this).val();
				if (
					val &&
					val.trim() !== '' &&
					val.includes(':') &&
					$(this).attr('id') !== 'background-color' &&
					$(this).attr('id') !== 'color'
				) {
					const [prop, value] = val.split(':').map(s => s.trim());
					cssProps += ` ${prop}: ${value};`;
				}
			});

			if (!cssProps) return; // No valid styles to apply

			let styleContent = $style.html().trim();
			const classRuleRegex = new RegExp(`\\.${styleName}\\s*{[^}]*}`, 'g');

			if (styleContent.match(classRuleRegex)) {
				// Replace full class rule, preserving existing + merging new props
				styleContent = styleContent.replace(classRuleRegex, function (match) {
					let updated = match;
					cssProps.trim().split(';').forEach(propLine => {
						if (!propLine) return;
						const [prop, val] = propLine.split(':').map(s => s.trim());
						const propRegex = new RegExp(`${prop}\\s*:\\s*[^;]+;?`);
						if (updated.match(propRegex)) {
							updated = updated.replace(propRegex, `${prop}: ${val} !important;`);
						} else {
							updated = updated.replace('}', ` ${prop}: ${val} !important; }`);
						}
					});
					return updated;
				});
			} else {
				// Add new class rule
				styleContent += ` .${styleName} {${cssProps} }`;
			}

			$style.html(styleContent);
			addElementInfo();

		}
	});

	function applyStyleSingle(property, value) {
		const selection = window.getSelection();
		if (!selection.rangeCount) return;

		const selectedRange = selection.getRangeAt(0);
		const selectedText = selectedRange.toString().trim();
		const alphaCount = (selectedText.match(/[a-zA-Z]/g) || []).length;
		if (alphaCount < 2) return;

		let parentNode = selectedRange.commonAncestorContainer.parentNode;
		if (!parentNode || parentNode.nodeType !== 1) return;

		let $activeEl = $(selectedRange.startContainer.nodeType === 3 ? selectedRange.startContainer.parentNode : selectedRange.startContainer);
		let $editor = $activeEl.closest('.editor-parent');
		const editorId = $editor.attr('id');
		const editorNumber = editorId ? editorId.replace(/\D+/g, '') : '0';

		let $style = $editor.find("style");
		if ($style.length === 0) {
			$style = $("<style></style>");
			$editor.prepend($style);
		}

		// Find existing span with class matching content-{editorNumber}-N
		let existingSpan = null;
		let classRegex = new RegExp(`content-${editorNumber}-(\\d+)`);

		if (parentNode.tagName === "SPAN" && classRegex.test(parentNode.className)) {
			existingSpan = parentNode;
		} else if (
			selectedRange.commonAncestorContainer.nodeType === Node.TEXT_NODE &&
			selectedRange.commonAncestorContainer.parentNode.tagName === "SPAN" &&
			classRegex.test(selectedRange.commonAncestorContainer.parentNode.className)
		) {
			existingSpan = selectedRange.commonAncestorContainer.parentNode;
		}

		let className;
		if (existingSpan) {
			const match = existingSpan.className.match(classRegex);
			className = match ? match[0] : null;
		}

		if (!className) {
			const extractedContent = selectedRange.extractContents();
			if (!extractedContent.textContent.trim()) return;

			const newSpan = document.createElement("span");
			className = generateClassName($style, editorNumber);
			newSpan.classList.add(className);
			newSpan.appendChild(extractedContent);
			selectedRange.deleteContents();
			selectedRange.insertNode(newSpan);
			existingSpan = newSpan;
		}

		// Update or add CSS rule
		let styleContent = $style.html().trim();
		let classRuleRegex = new RegExp(`\\.${className}\\s*{[^}]*}`, "g");
		let propertyRegex = new RegExp(`${property}\\s*:\\s*[^;]+;`);

		if (styleContent.match(classRuleRegex)) {
			styleContent = styleContent.replace(classRuleRegex, function (match) {
				if (match.match(propertyRegex)) {
					return match.replace(propertyRegex, `${property}: ${value} !important;`);
				} else {
					return match.replace("}", ` ${property}: ${value} !important; }`);
				}
			});
		} else {
			const classRule = `.${className} { ${property}: ${value} !important; }`;
			styleContent += classRule;
		}

		$style.html(styleContent);
		addElementInfo();
	}

	function generateClassName($style, editorNumber) {
		let counter = 0;
		let styleContent = $style.html();
		let matches = styleContent.match(new RegExp(`\\.content-${editorNumber}-\\d+`, "g"));

		if (matches && matches.length > 0) {
			let lastClass = matches[matches.length - 1];
			let match = lastClass.match(new RegExp(`content-${editorNumber}-(\\d+)`));
			if (match) {
				counter = parseInt(match[1], 10) + 1;
			}
		}
		return `content-${editorNumber}-${counter}`;
	}

	function applyClassStyle(property, value) {
		let $highlightedElement = $(".editor-parent .highlighted");
		const selection = window.getSelection();

		if (selection && selection.rangeCount > 0 && selection.toString().trim().length > 1) {
			const selectedRange = selection.getRangeAt(0);
			const selectedText = selectedRange.toString().trim();
			let parentNode = selectedRange.commonAncestorContainer.parentNode;

			if (!parentNode || parentNode.nodeType !== 1) return;

			let $editor = $(parentNode).closest('.editor-parent');
			let editorId = $editor.attr('id');
			let editorNumber = editorId ? editorId.replace(/\D+/g, '') : '0';

			let $style = $editor.find("style");
			if ($style.length === 0) {
				$style = $("<style></style>");
				$editor.prepend($style);
			}

			let existingSpan = null;
			if (parentNode.tagName === "SPAN" && /content-\d+(-\d+)?/.test(parentNode.className)) {
				existingSpan = parentNode;
			} else if (
				selectedRange.commonAncestorContainer.nodeType === Node.TEXT_NODE &&
				selectedRange.commonAncestorContainer.parentNode.tagName === "SPAN" &&
				/content-\d+(-\d+)?/.test(selectedRange.commonAncestorContainer.parentNode.className)
			) {
				existingSpan = selectedRange.commonAncestorContainer.parentNode;
			}

			let className;
			if (existingSpan) {
				const match = existingSpan.className.match(/content-\d+(-\d+)?/);
				className = match ? match[0] : null;
			}

			if (!className) {
				let extractedContent = selectedRange.extractContents();
				if (!extractedContent.textContent.trim()) return;

				let newSpan = document.createElement("span");
				className = generateClassName($style, editorNumber);
				newSpan.classList.add(className);
				newSpan.appendChild(extractedContent);
				selectedRange.deleteContents();
				selectedRange.insertNode(newSpan);
				existingSpan = newSpan;
			}

			applyStyleToClass($style, className, property, value, editorId);
			removeAllRangesForcefully();
			removeAllHighlights();
			if (existingSpan) {
				setTimeout(() => {
					existingSpan.classList.add('highlighted');
				}, 5);
			}
			addElementInfo();
		} else if ($highlightedElement.length > 0) {
			let $editor = $highlightedElement.closest('.editor-parent');
			let editorId = $editor.attr('id');
			let editorNumber = editorId ? editorId.replace(/\D+/g, '') : '0';

			let $style = $editor.find("style");
			if ($style.length === 0) {
				$style = $("<style></style>");
				$editor.prepend($style);
			}

			let className = $highlightedElement.attr('class').split(/\s+/).find(cls => cls.startsWith('content-'));
			if (!className) {
				className = generateClassName($style, editorNumber);
				$highlightedElement.addClass(className);
			}

			applyStyleToClass($style, className, property, value, editorId);
		}
	}

	function applyStyleToClass($style, className, property, value, editorId) {
		let styleContent = $style.html();
		let classSelector = `.${className}`;
		let classRuleRegex = new RegExp(`${classSelector}\\s*{([^}]*)}`, "g");

		if (classRuleRegex.test(styleContent)) {
			styleContent = styleContent.replace(classRuleRegex, function (match, innerCSS) {
				// Convert existing properties to an object
				let cssProps = {};
				innerCSS.split(";").forEach(part => {
					let [prop, val] = part.split(":");
					if (prop && val) {
						cssProps[prop.trim()] = val.trim();
					}
				});

				// Update or add the new property
				cssProps[property] = `${value} !important;`;

				// Reconstruct CSS from object
				let updatedCSS = Object.entries(cssProps)
					.map(([k, v]) => `${k}: ${v}`)
					.join("; ");

				return `${classSelector} { ${updatedCSS} }`;
			});
		} else {
			// Create a new rule if it doesn't exist
			let newRule = `${classSelector} { ${property}: ${value} !important; }`;
			styleContent += ` ${newRule}`;
		}

		$style.html(styleContent);
	}

	// Store the selection when text is selected
	document.addEventListener("mouseup", function () {
		const selection = window.getSelection();
		if (selection.rangeCount > 0) {
			selectedRange = selection.getRangeAt(0);
		}
	});

	function emptyStyleForm() {
		const excludedIds = [
			'background-color', 'color', 'table-rows', 'table-columns', 'table-width',
			'table-border-thickness', 'table-cell-padding', 'table-cell-spacing'
		];

		const excludedClasses = [
			'background-color', 'color', 'table-rows', 'table-columns', 'table-width',
			'table-border-thickness', 'table-cell-padding', 'table-cell-spacing'
		];

		document.querySelectorAll('.editor-styles .form input, .editor-styles .form select, .editor-styles .form textarea')
			.forEach(element => {
				const hasExcludedId = excludedIds.includes(element.id);
				const hasExcludedClass = excludedClasses.some(cls => element.classList.contains(cls));

				if (!hasExcludedId && !hasExcludedClass) {
					if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
						if (element.type === 'color') {
							return;
						} else {
							element.value = '';
						}
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

	document.getElementById('mergeConfirmBtn').addEventListener('click', function () {
		const span = parseInt(document.getElementById('mergeSpan').value);
		const direction = document.querySelector('input[name="mergeDirection"]:checked').value;

		if (!span || span <= 1) return alert('Please enter a valid number greater than 1');

		document.getElementById('mergeModal').style.display = 'none';

		if (direction === 'horizontal') {
			// (Your existing and corrected horizontal merge logic here)
			const currentRow = selectedCell.parentElement;
			const initialColSpan = selectedCell.colSpan;
			const targetColIndex = selectedCell.cellIndex;

			// Calculate the effective span we need to cover
			const effectiveSpanToCover = span - initialColSpan;

			if (effectiveSpanToCover <= 0) {
				alert('Cell already has the specified or a larger colspan!');
				return;
			}

			selectedCell.colSpan = span;

			let cellsToDeleteCount = effectiveSpanToCover;
			let currentColSpanCovered = initialColSpan;

			for (let i = targetColIndex + 1; i < currentRow.cells.length && cellsToDeleteCount > 0; i++) {
				const cellToProcess = currentRow.cells[i];
				const cellToProcessColSpan = cellToProcess.colSpan || 1; // Default to 1 if not set

				if (currentColSpanCovered + cellToProcessColSpan <= span) {
					cellsToDeleteCount -= cellToProcessColSpan;
					currentColSpanCovered += cellToProcessColSpan;
					currentRow.deleteCell(i);
					i--; // Adjust index as a cell is deleted
				} else {
					const overlap = (currentColSpanCovered + cellToProcessColSpan) - span;
					cellToProcess.colSpan = cellToProcessColSpan - overlap;
					cellsToDeleteCount = 0;
				}
			}

		} else if (direction === 'vertical') {
			const table = selectedCell.closest('table');
			const startRowIndex = selectedCell.parentElement.rowIndex;
			const startCellIndex = selectedCell.cellIndex; // This is the *physical* cell index in its row

			// Get the current rowSpan of the selected cell, default to 1 if not set
			const currentSelectedRowSpan = selectedCell.rowSpan || 1;

			// If the new span is the same or less than the current, no change needed (or unmerging)
			if (span <= currentSelectedRowSpan) {
				alert('Cell already has the specified or a larger rowspan!');
				return;
			}

			// --- NEW LOGIC FOR VERTICAL MERGE ---

			// 1. Build a grid map of the table to accurately locate cells
			const gridMap = buildTableGridMap(table);

			// 2. Find the conceptual column index of the selected cell in the grid map
			let conceptualColIndex = -1;
			for (let j = 0; j < gridMap[startRowIndex].length; j++) {
				if (gridMap[startRowIndex][j] && gridMap[startRowIndex][j].cell === selectedCell) {
					conceptualColIndex = j;
					break;
				}
			}

			if (conceptualColIndex === -1) {
				console.error("Could not find selected cell in grid map.");
				return; // Should not happen if buildTableGridMap is correct
			}

			// 3. Set the new rowspan
			selectedCell.rowSpan = span;

			// 4. Iterate through the rows that will be "absorbed"
			for (let i = 1; i < span; i++) {
				const currentRowIndex = startRowIndex + i;
				const targetRow = table.rows[currentRowIndex];

				if (targetRow) {
					// Find the cell at the conceptual column index in the target row
					let actualCellToDelete = null;
					let actualCellIndexInRowToDelete = -1; // Physical index in targetRow.cells

					// Iterate through the grid map for this row to find the cell to delete
					for (let j = 0; j < gridMap[currentRowIndex].length; j++) {
						const gridCellInfo = gridMap[currentRowIndex][j];
						if (gridCellInfo && gridCellInfo.col === conceptualColIndex && gridCellInfo.row === currentRowIndex) {
							actualCellToDelete = gridCellInfo.cell;
							actualCellIndexInRowToDelete = gridCellInfo.physicalColIndex;
							break;
						}
					}

					if (actualCellToDelete && actualCellIndexInRowToDelete !== -1) {
						// Check if the cell we are about to delete is currently part of a rowspan
						// If it is, and its rowspan would extend beyond the new merge, we need to handle that.
						// For simplicity, we assume we are deleting cells that are not themselves
						// the start of a new rowspan. If they are, that's another layer of complexity
						// (e.g., splitting a rowspan, or disallowing the merge).
						if (actualCellToDelete.parentNode === targetRow) { // Ensure it's in the correct row
							targetRow.deleteCell(actualCellIndexInRowToDelete);
						}
					}
				}
			}
		}
	});

	function buildTableGridMap(table) {
		const grid = [];
		const rows = table.rows;

		for (let r = 0; r < rows.length; r++) {
			const row = rows[r];
			let c = 0; // Conceptual column index
			let physicalCellIndex = 0; // Actual index in row.cells

			// Ensure the current row in the grid exists
			if (!grid[r]) {
				grid[r] = [];
			}

			for (let i = 0; i < row.cells.length; i++) {
				const cell = row.cells[i];
				const cellColSpan = cell.colSpan || 1;
				const cellRowSpan = cell.rowSpan || 1;

				// Find the next available conceptual column in the current row
				while (grid[r][c]) {
					c++;
				}

				// Fill the grid for this cell based on its colspan and rowspan
				for (let tr = 0; tr < cellRowSpan; tr++) {
					if (!grid[r + tr]) {
						grid[r + tr] = [];
					}
					for (let tc = 0; tc < cellColSpan; tc++) {
						// Make sure we don't overwrite an existing cell in the grid (due to a prior rowspan)
						let currentConceptualCol = c + tc;
						while(grid[r + tr][currentConceptualCol]) {
							currentConceptualCol++; // Skip if already occupied by another rowspan
						}
						grid[r + tr][currentConceptualCol] = {
							cell: cell,
							row: r,
							col: c, // Conceptual starting column
							physicalColIndex: physicalCellIndex // Physical index in its own row's cells collection
						};
					}
				}
				c += cellColSpan; // Move to the next conceptual column
				physicalCellIndex++; // Move to the next physical cell in the row
			}
		}
		return grid;
	}

	document.getElementById('addCellConfirmBtn').addEventListener('click', function () {
		const insertCell = parseInt(document.getElementById('insertCell').value);
		// const insertCellDirection = document.querySelector('input[name="insertCellDirection"]:checked').value;
		const insertCellDirection = 'horizontal';

		if (!selectedCell) return alert('Select a cell first!');
		if (!insertCell || insertCell < 1) return alert('Please enter a valid number greater than 0');

		const table = selectedCell.closest('table');
		const currentRow = selectedCell.parentElement;
		const cellIndex = selectedCell.cellIndex;
		const rowIndex = currentRow.rowIndex;

		const maxRows = parseInt(table.getAttribute('data-rows'));
		const maxColumns = parseInt(table.getAttribute('data-columns'));

		document.getElementById('addCellModal').style.display = 'none';

		// Function to get real column count considering colspan
		function getColCount(row) {
			let count = 0;
			for (let cell of row.cells) {
				count += parseInt(cell.getAttribute('colspan')) || 1;
			}
			return count;
		}

		if (insertCellDirection === 'horizontal') {
			const currentColumnCount = getColCount(currentRow);

			// if (currentColumnCount + insertCell > maxColumns) {
			//     return alert(`Cannot insert. Max allowed columns are ${maxColumns}.`);
			// }

			// Insert after the selected cell
			let insertAfterIndex = selectedCell.cellIndex + 1;

			for (let i = 0; i < insertCell; i++) {
				const newCell = currentRow.insertCell(insertAfterIndex);
				newCell.textContent = 'New Cell';
				applyCellStyles(newCell);
				newCell.addEventListener('click', selectCell);
				insertAfterIndex++; // move ahead after each insertion
			}
		} else if (insertCellDirection === 'vertical') {
			const currentRowCount = table.rows.length;
			const neededRows = rowIndex + insertCell + 1;

			if (neededRows > maxRows) {
				return alert(`Cannot insert. Max allowed rows are ${maxRows}.`);
			}

			const columnCount = getColCount(table.rows[0]);

			for (let i = 0; i < insertCell; i++) {
				const targetRowIndex = rowIndex + 1 + i;

				let row;
				if (targetRowIndex >= table.rows.length) {
					row = table.insertRow();
					for (let j = 0; j < columnCount; j++) {
						const cell = row.insertCell();
						cell.textContent = '';
						applyCellStyles(cell);
						cell.addEventListener('click', selectCell);
					}
				} else {
					row = table.rows[targetRowIndex];
				}

				// Adjusted insertion logic for correct cell index considering colspan
				let realIndex = 0;
				let cellPos = 0;
				for (let c of row.cells) {
					let colspan = parseInt(c.getAttribute('colspan')) || 1;
					if (realIndex >= cellIndex) break;
					realIndex += colspan;
					cellPos++;
				}

				const newCell = row.insertCell(cellPos);
				newCell.textContent = 'New Cell';
				applyCellStyles(newCell);
				newCell.addEventListener('click', selectCell);
			}
		}

		addElementInfo();
	});

	function insertRow(position) {
		if (!selectedCell) return alert('Select a cell first!');

		const table = selectedCell.closest('table');
		const referenceRow = selectedCell.parentElement;
		const rowIndex = referenceRow.rowIndex + (position === 'after' ? 1 : 0);
		const newRow = table.insertRow(rowIndex);

		// Get number of cells from the reference row instead of table.rows[0]
		const columnCount = referenceRow.cells.length;

		for (let i = 0; i < columnCount; i++) {
			const newCell = newRow.insertCell();
			newCell.textContent = 'New Cell';
			applyCellStyles(newCell);
			newCell.addEventListener('click', selectCell);
		}
		addElementInfo();
	}

	function insertColumn(position) {
		if (!selectedCell) return alert('Select a cell first!');

		const table = selectedCell.closest('table');
		const colIndex = selectedCell.cellIndex + (position === 'after' ? 1 : 0);

		for (let row of table.rows) {
			// Ensure colIndex is within valid range
			const maxCells = row.cells.length;
			const insertAt = colIndex > maxCells ? maxCells : colIndex;

			const newCell = row.insertCell(insertAt);
			newCell.textContent = 'New Cell';
			applyCellStyles(newCell);
			newCell.addEventListener('click', selectCell);
		}
		addElementInfo();
	}

	//START - table add & edit start

	function applyCellStyles(cell) {
		const referenceCell = editor.querySelector('table td');
		if (referenceCell) {
			cell.style.border = referenceCell.style.border;
			cell.style.padding = referenceCell.style.padding;
		} else {
			cell.style.border = '1px solid black';
			cell.style.padding = '5px';
		}
	}

	function selectCell(event) {
		if (selectedCell) {
			selectedCell.classList.remove('selected');
		}
		selectedCell = event.target;
		selectedCell.classList.add('selected');
	}

	function createTable() {
		// Find the editor container to append the style tag
		let editorContainer = editor.querySelector('.editor-styles');
		if (!editorContainer) {
			alert('Select any area first');
			return;
		}

		const rows = parseInt(editorContainer.querySelector('.table-rows').value) || 3;
		const columns = parseInt(editorContainer.querySelector('.table-columns').value) || 3;
		const width = editorContainer.querySelector('.table-width').value || '90';
		const borderThickness = parseInt(editorContainer.querySelector('.table-border-thickness').value) || 1;
		const cellPadding = parseInt(editorContainer.querySelector('.table-cell-padding').value) || 5;
		const cellSpacing = parseInt(editorContainer.querySelector('.table-cell-spacing').value) || 0;

		var editorParentCurrent = editor.querySelector('.editor-parent');
		var editorId = editorParentCurrent.getAttribute('id');
		let styleTag = editorParentCurrent.querySelector('style');
		if (!styleTag) {
			styleTag = document.createElement('style');
			editorParentCurrent.appendChild(styleTag);
		}

		// Extract editor number from editorId
		let editorNumberMatch = editorId.match(/\d+$/);
		let editorNumber = editorNumberMatch ? editorNumberMatch[0] : "0";

		// Generate class name like content-1-0
		let styleContent = styleTag.innerHTML.trim();
		let counter = 0;
		let matches = styleContent.match(new RegExp(`\\.content-${editorNumber}-(\\d+)`, "g"));

		if (matches && matches.length > 0) {
			let lastMatch = matches[matches.length - 1];
			let matchNum = lastMatch.match(/-(\d+)$/);
			if (matchNum) {
				counter = parseInt(matchNum[1], 10) + 1;
			}
		}

		let tableClass = `content-${editorNumber}-${counter}`;

		// Create the table element
		const table = document.createElement('table');
		table.classList.add(tableClass);
		table.setAttribute('data-rows', rows);
		table.setAttribute('data-columns', columns);

		for (let i = 0; i < rows; i++) {
			const row = table.insertRow();
			for (let j = 0; j < columns; j++) {
				const cell = row.insertCell();
				cell.textContent = `Row ${i + 1}, Col ${j + 1}`;
				cell.addEventListener('click', selectCell);
			}
		}

		// Append the table to the editor
		const selection = window.getSelection();
		if (selection.rangeCount > 0) {
			const range = selection.getRangeAt(0);
			range.deleteContents();
			range.insertNode(table);
		}

		// Append CSS rules for the new table class
		let newStyleRule = `.${tableClass} { width: ${width}%; border: ${borderThickness}px solid black; border-collapse: separate; border-spacing: ${cellSpacing}px; } .${tableClass} td { border: ${borderThickness}px solid black; padding: ${cellPadding}px; } `;

		styleTag.innerHTML += newStyleRule;
		removeAllRangesForcefully()
		addElementInfo()
	}

	//END - table add & edit start

	//START - flex and grid show hide
	function addGridStyleHtml() {
		var gridHtml = `<div class="heading">Grid Styling Options</div>
				<div>
				<label for="grid-template-columns">Grid Template Columns:
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
				</label>
				</div>
				<div>
				<label for="grid-template-rows">Grid Template Rows:
				<select id="grid-template-rows" name="grid-template-rows">
					<option value=""></option>
						<option value="grid-template-rows: 1fr">grid-template-rows: 1fr</option>
						<option value="grid-template-rows: auto 1fr auto">grid-template-rows: auto 1fr auto</option>
						<option value="grid-template-rows: subgrid">grid-template-rows: subgrid</option>
				</select>
				</label>
				</div>
				<div>
				<label for="column-gap">Column Gap:
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
				</label>
				</div>
				<div>
				<label for="row-gap">Row Gap:
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
				</label>
				</div>
				<div>
				<label for="place-items">Place Items (Horizontal & Vertical Combine):
				<select id="place-items" name="place-items">
					<option value=""></option>
					<option value="place-items: start">place-items: start</option>
					<option value="place-items: center">place-items: center</option>
					<option value="place-items: end">place-items: end</option>
				</select>
				</label>
				</div>
				<div>
				<label for="justify-items">Justify Items (Horizontal Items Align):
				<select id="justify-items" name="justify-items">
					<option value=""></option>
					<option value="justify-items: start">justify-items: start</option>
					<option value="justify-items: center">justify-items: center</option>
					<option value="justify-items: end">justify-items: end</option>
				</select>
				</label>
				</div>
				<div>
				<label for="align-items">Items Align (Vertical Items Align):
				<select id="align-items" name="align-items">
					<option value=""></option>
					<option value="align-items: start">align-items: start</option>
					<option value="align-items: center">align-items: center</option>
					<option value="align-items: end">align-items: end</option>
				</select>
				</label>
				</div>
				<div class="heading">Grid Items Styling Options</div>
				<div>
				<label for="grid-column">Grid Column (Span Accross):
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
				</label>
				</div>
				<div>
				<label for="grid-row">Grid Rows (Span Down):
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
				</label>
				</div>
				<div>
				<label for="text-align-last">Text Align Last (Horizontal Content Align):
				<select id="text-align-last" name="text-align-last">
					<option value=""></option>
					<option value="text-align-last: left">text-align-last: left</option>
					<option value="text-align-last: center">text-align-last: center</option>
					<option value="text-align-last: right">text-align-last: right</option>
				</select>
				</label>
				</div>
				<div>
				<label for="align-content">Align Content (Vertical Content Align):
				<select id="align-content" name="align-content">
					<option value=""></option>
					<option value="align-content: start">align-content: start</option>
					<option value="align-content: center">align-content: center</option>
					<option value="align-content: end">align-content: end</option>
				</select>
				</label>
		  </div>`;

		const editorStyles = editor.querySelector('.editor-styles');
		if (!editorStyles) return;

		const gridArea = editorStyles.querySelector('.displayGridArea');

		if (gridArea) {
			gridArea.innerHTML = gridHtml;
			gridArea.style.display = 'block';
		}
	}

	function addFlexStyleHtml() {
		var flexHtml = `<div class="heading">Flex Styling Options</div>
				<div>
				<label for="flex-direction">Flex Direction:
				<select id="flex-direction" name="flex-direction">
					<option value=""></option>
					<option value="flex-direction: row">flex-direction: row</option>
					<option value="flex-direction: row-reverse">flex-direction: row-reverse</option>
					<option value="flex-direction: column">flex-direction: column</option>
					<option value="flex-direction: column-reverse">flex-direction: column-reverse</option>
				</select>
				</label>
				</div>
				<div>
				<label for="flex-wrap">Flex Wrap:
				<select id="flex-wrap" name="flex-wrap">
					<option value=""></option>
					<option value="flex-wrap: wrap">flex-wrap: wrap</option>
					<option value="flex-wrap: nowrap">flex-wrap: nowrap</option>
				</select>
				</label>
				</div>
				<div>
				<label for="place-items">Place Items (Horizontal & Vertical Combine):
				<select id="place-items" name="place-items">
					<option value=""></option>
					<option value="place-items: start">place-items: start</option>
					<option value="place-items: center">place-items: center</option>
					<option value="place-items: end">place-items: end</option>
				</select>
				</label>
				</label>
				</div>
				<div>
				<label for="justify-content">Justify Content (Vertical / Each Row):
				<select id="justify-content" name="justify-content">
					<option value=""></option>
					<option value="justify-content: flex-start">justify-content: flex-start</option>
					<option value="justify-content: center">justify-content: center</option>
					<option value="justify-content: flex-end">justify-content: flex-end</option>
					<option value="justify-content: space-between">justify-content: space-between</option>
					<option value="justify-content: space-around">justify-content: space-around</option>
					<option value="justify-content: space-evenly">justify-content: space-evenly</option>
				</select>
				</label>
				</div>
				<div>
				<label for="align-content">Align Content (Vertical Entire Container):
				<select id="align-content" name="align-content">
					<option value=""></option>
					<option value="align-content: flex-start">align-content: flex-start</option>
					<option value="align-content: center">align-content: center</option>
					<option value="align-content: flex-end">align-content: flex-end</option>
					<option value="align-content: space-between">align-content: space-between</option>
					<option value="align-content: space-around">align-content: space-around</option>
					<option value="align-content: space-evenly">align-content: space-evenly</option>
				</select>
				</label>
				</div>
				<div>
				<label for="align-items">Align Items (Horizontal):
				<select id="align-items" name="align-items">
					<option value=""></option>
					<option value="align-items: start">align-items: start</option>
					<option value="align-items: center">align-items: center</option>
					<option value="align-items: end">align-items: end</option>
				</select>
				</label>
				</div>
				<div>
				<label for="column-gap">Column Gap:
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
				</label>
				</div>
				<div>
				<label for="row-gap">Row Gap:
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
				</label>
				</div>
				<div class="heading">Flex Items Styling Options</div>
				<div>
				<label for="flex-shrink">Flex Shrink:
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
				</label>
				</div>
				<div>
				<label for="flex-grow">Flex Grow:
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
				</label>
				</div>
				<div>
				<label for="align-self">Align Self:
				<select id="align-self" name="align-self">
					<option value=""></option>
					<option value="align-self: flex-start">align-self: flex-start</option>
					<option value="align-self: center">align-self: center</option>
					<option value="align-self: flex-end">align-self: flex-end</option>
					<option value="align-self: space-between">align-self: space-between</option>
					<option value="align-self: space-around">align-self: space-around</option>
					<option value="align-self: space-evenly">align-self: space-evenly</option>
				</select>
				</label>
				</div>`;

		const editorStyles = editor.querySelector('.editor-styles');
		if (!editorStyles) return;

		const flexArea = editorStyles.querySelector('.displayFlexArea');

		if (flexArea) {
			flexArea.innerHTML = flexHtml;
			flexArea.style.display = 'block';
		}
	}

	function addGeneralAlignStyleHtml() {
		var generalAlignHtml = `<div class="heading">Alignment Options</div>
				<div>
					<label for="align-items">Align Items (Horizontal):
					<select id="align-items" name="align-items">
						<option value=""></option>
						<option value="align-items: start">align-items: start</option>
						<option value="align-items: center">align-items: center</option>
						<option value="align-items: end">align-items: end</option>
					</select>
					</label>
				</div>`;

		const editorStyles = editor.querySelector('.editor-styles');
		if (!editorStyles) return;

		const generalAlignArea = editorStyles.querySelector('.generalAlignArea');

		if (generalAlignArea) {
			generalAlignArea.innerHTML = generalAlignHtml;
			generalAlignArea.style.display = 'block';
		}
	}
	//END - flex and grid show hide
});