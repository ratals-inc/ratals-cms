/*
Copyright (c) 2025-2026 Ratals Inc.
Licensed under the Apache License, Version 2.0
Full License & Terms: https://www.ratals.com/license/
*/

document.addEventListener('DOMContentLoaded', function()
{
    document.querySelectorAll('.editor').forEach(initEditor);
	
    document.querySelectorAll('form').forEach(function(form)
    {
        form.addEventListener('submit', syncAll);
    });
});

function initEditor(wrapper)
{
    const editor = wrapper.querySelector('.editor-content');
    const source = wrapper.querySelector('.editor-source');
    const target = document.getElementById(wrapper.dataset.target);
	
    if(target.value)
    {
        editor.innerHTML = target.value;
    }
	
    editor.addEventListener('focus', function()
    {
        try
        {
            document.execCommand('defaultParagraphSeparator', false, 'p');
        }
        catch(e){}
    });
	
	editor.addEventListener('keydown', function(e)
    {
        if(e.key === 'Enter')
        {
            const selection = window.getSelection();
			
            if(selection && selection.rangeCount > 0)
			{
                let node = selection.anchorNode;
                let insideListOrHeading = false;
				
                while(node && node !== editor)
				{
                    if(['LI', 'UL', 'OL', 'H1', 'H2', 'H3', 'H4', 'H5', 'H6'].includes(node.tagName))
					{
                        insideListOrHeading = true;
                        break;
                    }
                    node = node.parentNode;
                }
				
                if(!insideListOrHeading)
				{
                    try
					{
                        document.execCommand('formatBlock', false, 'p');
                    }
					catch(err)
					{
					}
                }
            }
        }
    });
	
    wrapper.querySelectorAll('[data-command]').forEach(function(btn)
    {
        btn.addEventListener('mousedown', function(e)
        {
            e.preventDefault();
            editor.focus();
			
            const cmd = btn.dataset.command;
			
            if(cmd === 'align-left')
            {
                setAlign(editor, 'left');
                return;
            }
			
            if(cmd === 'align-center')
            {
                setAlign(editor, 'center');
                return;
            }
			
            if(cmd === 'align-right')
            {
                setAlign(editor, 'right');
                return;
            }
			
            document.execCommand(cmd, false, null);
			
			if(cmd === 'insertUnorderedList' || cmd === 'insertOrderedList')
			{
                editor.querySelectorAll('p > ul, p > ol').forEach(function(list)
				{
                    const parentP = list.parentNode;
                    parentP.parentNode.insertBefore(list, parentP);
					
                    if(!parentP.textContent.trim() && (parentP.innerHTML === '' || parentP.innerHTML === '<br>'))
					{
                        parentP.parentNode.removeChild(parentP);
                    }
                });
				
                Array.from(editor.childNodes).forEach(function(node)
				{
                    const blockTags = ['P', 'DIV', 'H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'UL', 'OL', 'BLOCKQUOTE'];
					
                    if(node.nodeType === 3 || (node.nodeType === 1 && !blockTags.includes(node.tagName)))
					{
                        if(node.nodeType === 3 && !node.textContent.trim()) return;

                        const p = document.createElement('p');
                        node.parentNode.insertBefore(p, node);
                        p.appendChild(node);
                    }
                });
            }
			
            if(cmd !== 'undo' && cmd !== 'redo')
			{
                sync(wrapper);
            }
			else
			{
                const target = document.getElementById(wrapper.dataset.target);
				
                if(source.style.display !== 'block')
				{
                    target.value = formatHTML(editor.innerHTML);
                }
            }
        });
    });
	
    wrapper.querySelector('.heading-select')
	.addEventListener('change', function()
    {
        editor.focus();
        document.execCommand('formatBlock', false, this.value || 'P');
        sync(wrapper);
    });
	
	wrapper.querySelector('.insert-link')
	.addEventListener('pointerdown', function(e)
	{
		e.preventDefault();
	
		const selection = window.getSelection();
	
		if(!selection || !selection.rangeCount)
		{
			alert('Please select the text you want to link, then click Link again.');
			return;
		}
	
		//Save the selected text before changing focus or opening the modal.
		const savedRange = selection.getRangeAt(0).cloneRange();
	
		//Determine whether the start or end of the saved selection is inside a link.
		let existingLinkNode = savedRange.startContainer;
		
		if(existingLinkNode && existingLinkNode.nodeType === 3)
		{
			existingLinkNode = existingLinkNode.parentNode;
		}
		
		while(existingLinkNode && existingLinkNode !== editor)
		{
			if(existingLinkNode.tagName === 'A')
			{
				break;
			}
		
			existingLinkNode = existingLinkNode.parentNode;
		}
		
		//If the start of the selection was not inside a link, check the end of the selection.
		if(!existingLinkNode || existingLinkNode.tagName !== 'A')
		{
			existingLinkNode = savedRange.endContainer;
		
			if(existingLinkNode && existingLinkNode.nodeType === 3)
			{
				existingLinkNode = existingLinkNode.parentNode;
			}
		
			while(existingLinkNode && existingLinkNode !== editor)
			{
				if(existingLinkNode.tagName === 'A')
				{
					break;
				}
		
				existingLinkNode = existingLinkNode.parentNode;
			}
		}
		
		//Require selected text when creating a new link.
		if((!existingLinkNode || existingLinkNode.tagName !== 'A') && savedRange.collapsed)
		{
			alert('Please select the text you want to link, then click Link again.');
			return;
		}
	
		const parentForm = wrapper.closest('form');
	
		if(!parentForm)
		{
			return;
		}
	
		const modal = parentForm.querySelector('.form-link-modal');
		const urlInput = modal.querySelector('.modal-link-url');
		const idInput = modal.querySelector('.modal-link-id');
		const targetInput = modal.querySelector('.modal-link-target');
		const nofollowInput = modal.querySelector('.modal-link-nofollow');
		const sponsoredInput = modal.querySelector('.modal-link-sponsored');
		const ugcInput = modal.querySelector('.modal-link-ugc');
		const cancelBtn = modal.querySelector('.modal-link-cancel');
		const submitBtn = modal.querySelector('.modal-link-submit');
	
		//Reset link fields.
		urlInput.value = '';
		idInput.value = '';
		targetInput.value = '';
		nofollowInput.checked = false;
		sponsoredInput.checked = false;
		ugcInput.checked = false;
	
		//Load existing link settings when editing a link.
		if(existingLinkNode && existingLinkNode.tagName === 'A')
		{
			const rawHref = existingLinkNode.getAttribute('href') || '';
			const idMatch = rawHref.match(/^urlId\((.*?)\);?$/);
	
			if(idMatch)
			{
				idInput.value = idMatch[1];
			}
			else
			{
				urlInput.value = rawHref;
			}
	
			targetInput.value = existingLinkNode.getAttribute('target') || '';
	
			const existingRel = (existingLinkNode.getAttribute('rel') || '').split(/\s+/);
	
			nofollowInput.checked = existingRel.includes('nofollow');
			sponsoredInput.checked = existingRel.includes('sponsored');
			ugcInput.checked = existingRel.includes('ugc');
		}
		
		selection.removeAllRanges();
		modal.style.display = 'flex';
	
		const newCancelBtn = cancelBtn.cloneNode(true);
		const newSubmitBtn = submitBtn.cloneNode(true);
	
		cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);
		submitBtn.parentNode.replaceChild(newSubmitBtn, submitBtn);
	
		newCancelBtn.addEventListener('click', function()
		{
			modal.style.display = 'none';
		});
	
		newSubmitBtn.addEventListener('click', function()
		{
			let finalValue = '';
	
			if(idInput.value.trim() !== '')
			{
				finalValue = 'urlId(' + idInput.value.trim() + ');';
			}
			else if(urlInput.value.trim() !== '')
			{
				finalValue = urlInput.value.trim();
			}
	
			const linkTarget = targetInput.value;
	
			const linkTypes = [];
	
			if(nofollowInput.checked)
			{
				linkTypes.push('nofollow');
			}
	
			if(sponsoredInput.checked)
			{
				linkTypes.push('sponsored');
			}
	
			if(ugcInput.checked)
			{
				linkTypes.push('ugc');
			}
	
			if(linkTarget === '_blank')
			{
				linkTypes.push('noopener');
			}
	
			modal.style.display = 'none';
	
			/*
			If editing an existing link, update the actual anchor directly.
			This does not depend on the mobile browser keeping the selection alive.
			*/
			if(existingLinkNode && existingLinkNode.tagName === 'A')
			{
				if(finalValue !== '')
				{
					existingLinkNode.setAttribute('href', finalValue);
	
					if(linkTarget !== '')
					{
						existingLinkNode.setAttribute('target', linkTarget);
					}
					else
					{
						existingLinkNode.removeAttribute('target');
					}
	
					if(linkTypes.length > 0)
					{
						existingLinkNode.setAttribute('rel', linkTypes.join(' '));
					}
					else
					{
						existingLinkNode.removeAttribute('rel');
					}
				}
				else
				{
					//Remove the link while preserving its text/content.
					const parentNode = existingLinkNode.parentNode;
	
					while(existingLinkNode.firstChild)
					{
						parentNode.insertBefore(existingLinkNode.firstChild, existingLinkNode);
					}
	
					parentNode.removeChild(existingLinkNode);
				}
			}
			else if(finalValue !== '')
			{
				/*
				Create a new link using a temporary unique href.
				This lets us find the newly created anchor without depending
				on selection.anchorNode after createLink runs.
				*/
				selection.removeAllRanges();
				selection.addRange(savedRange);
				editor.focus();
	
				const temporaryHref = 'ratalsTemporaryLink' + Date.now();
	
				document.execCommand('createLink', false, temporaryHref);
	
				let linkNode = null;
	
				editor.querySelectorAll('a').forEach(function(anchor)
				{
					if(anchor.getAttribute('href') === temporaryHref)
					{
						linkNode = anchor;
					}
				});
	
				if(linkNode)
				{
					linkNode.setAttribute('href', finalValue);
	
					if(linkTarget !== '')
					{
						linkNode.setAttribute('target', linkTarget);
					}
	
					if(linkTypes.length > 0)
					{
						linkNode.setAttribute('rel', linkTypes.join(' '));
					}
				}
			}
	
			sync(wrapper);
		});
	});
	
	wrapper.querySelector('.insert-media')
	.addEventListener('pointerdown', function(e)
	{
		e.preventDefault();
		
		const selection = window.getSelection();
		
		//Save the current editor/cursor position for inserting the selected media later.
		window.wysiwygMediaRange = null;
		
		if(selection && selection.rangeCount)
		{
			const currentRange = selection.getRangeAt(0);
			
			if(editor.contains(currentRange.startContainer) && editor.contains(currentRange.endContainer))
			{
				window.wysiwygMediaRange = currentRange.cloneRange();
			}
		}
		
		//Do not open the media popup if the cursor is not currently inside the editor.
		if(!window.wysiwygMediaRange)
		{
			alert('Please place the cursor in the editor where you want the media inserted, then click Media again.');
			return;
		}
		
		//Set the popup mode so the existing media selection code knows
		//the media was requested by the WYSIWYG editor.
		window.mediaPopupMode = 'editor';
		
		//Save which editor opened the popup.
		window.wysiwygMediaWrapper = wrapper;
		window.wysiwygMediaEditor = editor;
		
		//Remove the visible mobile text selection before showing the popup.
		if(selection)
		{
			selection.removeAllRanges();
		}
		
		//Open the existing media popup.
		$(".popup_media").show();
		$("body").addClass("popup-overflow-hidden");
	});
	
	document.querySelectorAll('.editor-media-options-cancel').forEach(function(cancelBtn)
	{
		cancelBtn.addEventListener('click', function()
		{
			$(".editor-media-options-overlay").hide();
			
			window.wysiwygSelectedMediaId = null;
			window.mediaPopupMode = '';
			window.wysiwygMediaWrapper = null;
			window.wysiwygMediaEditor = null;
			window.wysiwygMediaRange = null;
			
			$("body").removeClass("popup-overflow-hidden");
		});
	});
	
	document.querySelectorAll('.editor-media-options-insert').forEach(function(insertBtn)
	{
		insertBtn.addEventListener('click', function()
		{
			if(!window.wysiwygSelectedMediaId || !window.wysiwygMediaEditor || !window.wysiwygMediaRange)
			{
				return;
			}
			
			const optionsWindow = insertBtn.closest('.editor-media-options-window');
			const lazyLoad = optionsWindow.querySelector('.editor-media-lazy-load').value;
			const fetchPriority = optionsWindow.querySelector('.editor-media-fetch-priority').value;
			const maxDisplayWidth = optionsWindow.querySelector('.editor-media-max-display-width').value.trim();
			
			const mediaEmbed = 'mediaId(' + window.wysiwygSelectedMediaId + ', ' + lazyLoad + ', ' + fetchPriority + ', maxDisplayPixelWidth("' + maxDisplayWidth + '"), altTitleTag(""));';
			
			const selection = window.getSelection();
			
			selection.removeAllRanges();
			selection.addRange(window.wysiwygMediaRange);
			
			window.wysiwygMediaEditor.focus();
			
			document.execCommand('insertText', false, mediaEmbed);
			
			if(window.wysiwygMediaWrapper)
			{
				sync(window.wysiwygMediaWrapper);
			}
			
			$(".editor-media-options-overlay").hide();
			$("body").removeClass("popup-overflow-hidden");
			
			window.wysiwygSelectedMediaId = null;
			window.mediaPopupMode = '';
			window.wysiwygMediaWrapper = null;
			window.wysiwygMediaEditor = null;
			window.wysiwygMediaRange = null;
		});
	});
	
    wrapper.querySelector('.toggle-source')
    .addEventListener('click', function()
    {
		const toggleBtn = this;
		
        if(source.classList.contains('hidden-textarea'))
        {
            let currentContent = editor.innerHTML.trim();
            const testingContent = currentContent.replace(/<p>\s*(?:<br[^>]*>)?\s*<\/p>|<br[^>]*>/gi, '').trim();
			
            if(testingContent === '')
			{
                currentContent = '';
            }
			
            source.value = formatHTML(currentContent);
            editor.style.setProperty('display', 'none', 'important');
            source.classList.remove('hidden-textarea');
            source.style.setProperty('display', 'block', 'important');
			toggleBtn.innerHTML = '<svg viewBox="0 0 512 512" aria-hidden="true"><path d="M362.7 19.3c25.8-25.8 67.6-25.8 93.4 0l36.6 36.6c25.8 25.8 25.8 67.6 0 93.4L179.6 462.4 64 488l25.6-115.6L362.7 19.3zM112 392l-13.3 60 60-13.3L416 181.3 370.7 136 112 392z"></path></svg> View as Text';
        }
        else
        {
			editor.style.setProperty('display', 'block', 'important');
			editor.focus();
			
			const cleanValue = source.value.replace(/\n\s*/g, '');
			
			editor.innerHTML = cleanValue;
			
			const selection = window.getSelection();
			
			if(selection)
			{
				selection.removeAllRanges();
				
				const range = document.createRange();
				range.selectNodeContents(editor);
				range.collapse(true);
			
				selection.addRange(range);
			}
			
			source.classList.add('hidden-textarea');
			source.style.setProperty('display', 'none', 'important');
			
			toggleBtn.innerHTML = '<svg viewBox="0 0 512 512" aria-hidden="true"><path d="M160 128L32 256l128 128 34-34-94-94 94-94-34-34zm192 0l-34 34 94 94-94 94 34 34 128-128-128-128zM294 80l-76 352h-49l76-352h49z"></path></svg> View as Code';
        }
        
        sync(wrapper);
    });
	
    editor.addEventListener('input', function()
    {
        sync(wrapper);
    });
}

function setAlign(editor, type)
{
    const selection = window.getSelection();
	
    if(!selection || !selection.rangeCount)
    {
        return;
    }
	
    let node = selection.anchorNode;
	
    if(node.nodeType === 3)
    {
        node = node.parentNode;
    }
	
    while(node && node !== editor)
    {
        if(['P','DIV','H1','H2','H3','H4','H5','H6','LI'].includes(node.tagName))
        {
            node.classList.remove('align-left','align-center','align-right');
            node.classList.add('align-' + type);
            break;
        }
		
        node = node.parentNode;
    }
	
    syncAll();
}

function sync(wrapper)
{
    const editor = wrapper.querySelector('.editor-content');
    const source = wrapper.querySelector('.editor-source');
    const target = document.getElementById(wrapper.dataset.target);
	
    if(source.style.display === 'block')
    {
        target.value = source.value;
    }
    else
    {
        let rawHtml = editor.innerHTML.trim();
		
        const testingContent = rawHtml.replace(/<p>\s*(?:<br[^>]*>)?\s*<\/p>|<br[^>]*>/gi, '').trim();
        if(testingContent === '')
		{
            rawHtml = '';
        }
		
        target.value = formatHTML(rawHtml);
    }
}

function syncAll()
{
    document.querySelectorAll('.editor').forEach(sync);
}

function formatHTML(html)
{
    let formatted = html
        .replace(/<(style|script)\s+nonce=["']\s*["']([^>]*)>/gi, '<$1 nonce="nonce"$2>')
		
		.replace(/<b(\s[^>]*)?>/gi, '<strong$1>')
        .replace(/<\/b>/gi, '</strong>')
        .replace(/<i(\s[^>]*)?>/gi, '<em$1>')
        .replace(/<\/i>/gi, '</em>')
		.replace(/<strike(\s[^>]*)?>/gi, '<s$1>')
		.replace(/<\/strike>/gi, '</s>')
		
        .replace(/\n\s*/g, '')
        .replace(/(<\/(?:p|li|ul|ol|h[1-6]|div|blockquote)>)/gi, '$1\n')
        .replace(/(<(?:ul|ol)[^>]*>)/gi, '$1\n')
        .replace(/\n\s*\n/g, '\n')
        .trim();
		
    let indentLevel = 0;
    const indentString = '    ';
    
    return formatted.split('\n').map(function(line)
	{
        line = line.trim();
		
        if(line.match(/^<\/(?:ul|ol)>/i))
		{
            indentLevel = Math.max(0, indentLevel - 1);
        }
		
        let paddedLine = indentString.repeat(indentLevel) + line;
		
        if(line.match(/^<(?:ul|ol)[^>]*>/i))
		{
            indentLevel++;
        }
        
        return paddedLine;
    }).join('\n');
}