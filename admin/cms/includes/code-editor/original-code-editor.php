<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/code-editor/code-editor.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/code-editor/code-editor.php');
}
else
{
	include('color-highlighter.php');
	?>
	<style nonce="<?php echo NONCE; ?>">
	#container {
		display: flex;
	}
	
	#phpCode {
		display: flex;
        flex-direction: column;
		width: 100%;
		font-family: monospace;
		font-size: 16px;
		padding: 10px;
		white-space: pre-wrap;
		word-wrap: break-word;
		margin-bottom: 20px;
		border: 1px solid #ccc;
		background-color: #f9f9f9;
		overflow-y: auto;
	}

    /* Container for each line of code and its line number */
    .line-container {
        display: flex;
        width: 100%;
    }

    /* Line number styles */
    .line-number {
		-webkit-user-select: none; /* Safari */        
		-moz-user-select: none; /* Firefox */
		-ms-user-select: none; /* IE10+/Edge */
		user-select: none; /* Standard */
        width: 40px;
        text-align: right;
        margin-right: 10px;
        font-family: monospace;
        color: #888;
        flex-shrink: 0; /* Prevents line numbers from shrinking */
    }

    /* Code line styles */
    .code {
        font-family: monospace;
        flex-grow: 1; /* Allow code to take up remaining space */
        word-wrap: break-word;
        white-space: pre-wrap;
    }
	
	.green {
		color: green;
	}
	
	.red {
		color: red;
	}
	
	.blue {
		color: #2c94f1;
	}
	</style>
	<script nonce="<?php echo NONCE; ?>">
        //Function to format PHP code with indentation and split braces on new lines
        function formatPhpCode(phpCode) {
            
            phpCode = phpCode.replace(/\r\n/g, '\n');
            phpCode = phpCode.replace(/\r/g, '\n');
            phpCode = phpCode.replace(/if \(/g, 'if(');
            phpCode = phpCode.replace(/\?/g, '&#63;');
            phpCode = phpCode.replace(/&lt;&#63;php/g, '&lt;&#63;php');
            phpCode = phpCode.replace(/&#63;&gt;/g, '&#63;&gt;');
            phpCode = phpCode.replace(/{/g, "\n{\n");
            phpCode = phpCode.replace(/}/g, '\n}\n');
    
            //Step 1: Split code into lines and filter out empty lines
            let lines = phpCode.split('\n').filter(line => line.trim() !== '');
    
            const green = ["<?php echo implode('","', $php_green); ?>"];
            const greenRegex = new RegExp(`\\b(${green.join('|')})\\b`, 'g');
            
            const red = ["<?php echo implode('","', $php_tags); ?>"];
            const redRegex = new RegExp(`(${red.join('|')})`, 'g');
            
            const blue_1_Regex = ["<?php echo implode('","', $php_blue_1); ?>"];
    
            const blue_2 = ["<?php echo implode('","', $php_blue_2); ?>"];
            const blue_2_Regex = new RegExp(`(${blue_2.join('|')})`, 'g');
            
            let indentLevel = 0;
            let formattedPhp = '';
    
            //Loop through each line and format it
            lines.forEach(function(line) {
                line = line.trim();
                
                //Green array
                line = line.replace(greenRegex, (match) => `<span class="green">${match}</span>`);
                
                //Red array
                line = line.replace(redRegex, (match) => `<span class="red">${match}</span>`);
                
                //Blue 2 array
                line = line.replace(blue_2_Regex, (match, offset, string) => {
                    
                    //Make sure the match and string are valid
                    if(!match || offset < 0 || offset >= string.length) {
                        return match;
                    }
                
                    //Check the character before the match in the full line
                    const before = string > 0 ? line.charAt(string - 1) : '';
                
                    //Check the character after the match in the full line
                    const after = string > 0 ? line.charAt(string + offset.length) : '';
                    
                    if(after === '('
								   //|| after === ' (' || after === '' || (before === ' ' && after === ' ')
																		   ) {
                        return `<span class="blue">${match}</span>`;
                    }
                    
                    return match;
                });
                
                //Blue 1 array
                function replaceBlueText(line, blue_1_Array) {
                    blue_1_Array.forEach(blue_1_String => {
                        let index = 0;
                        while ((index = line.indexOf(blue_1_String, index)) !== -1) {
                            line = line.slice(0, index) + `<span class="blue">${blue_1_String}</span>` + line.slice(index + blue_1_String.length);
                            index += `<span class="blue">${blue_1_String}</span>`.length;
                        }
                    });
                    return line;
                }
                let blue_1_Array = ["<?php echo implode('","', $php_blue_1); ?>"];
                line = replaceBlueText(line, blue_1_Array);
                
                //Decreasing the indent level if '}'
                if(line === '<span class="blue">}</span>') {
                    if(indentLevel > 0) {
                        indentLevel--;
                    }
                }
                //Add the appropriate indentation before the line
                formattedPhp += '    '.repeat(indentLevel) + line + '\n';
                //Increasing the indent level '{'
                if(line === '<span class="blue">{</span>') {
                    indentLevel++;
                }
            });
    
            //Ensure only a single \n between each line
            formattedPhp = formattedPhp.replace(/\n+/g, '\n').trim();
    
            //Return formatted PHP code
            return formattedPhp;
        }
        
    // Function to add line numbers
    function addLineNumbers(codeEditor) {

        // Split the code into lines based on the newline characters
		let lines = codeEditor.split('\n').filter(line => line.trim() !== '');

        // Create line numbers for each line of code
        let lineNumberHTML = '';
        lines.forEach((line, index) => {
            lineNumberHTML += `
                <div class="line-container">
                    <div class="line-number" contenteditable="false">${index + 1}</div>
                    <div class="code">${line}</div>
                </div>\n
            `;
        });

        // Return the HTML with line numbers
        return lineNumberHTML;
    }

    // jQuery input event for real-time formatting
    $(document).ready(function() {
        // Initial format on page load
        let initialPhpCode = $('#phpCode').html();
        // Remove all existing <span> tags before processing
        initialPhpCode = initialPhpCode.replace(/<span class="green"[^>]*>(.*?)<\/span>/g, '$1');
        initialPhpCode = initialPhpCode.replace(/<span class="red"[^>]*>(.*?)<\/span>/g, '$1');
        initialPhpCode = initialPhpCode.replace(/<span class="blue"[^>]*>(.*?)<\/span>/g, '$1');
		
        initialPhpCode = formatPhpCode(initialPhpCode);
        initialPhpCode = addLineNumbers(initialPhpCode);
		
        // Combine formatted PHP code with line numbers
        $('#phpCode').html(initialPhpCode);
    });
    </script>
    
    <div id="container">
        <div id="phpCode" contenteditable="true"><?php echo htmlspecialchars($code_in_file ?? ''); ?></div>
    </div>
    <textarea name="<?php echo htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? ''); ?>"><?php echo htmlspecialchars($code_in_file ?? ''); ?></textarea>
<?php } ?>
