<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/addons/add-a-site.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/addons/add-a-site.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'add_a_site')
	{
	?>
    <div class="edit-wrapper">
        <form action="" method="POST">
            <div class="edit">
            <div class="edit-label">
			<?php if(isset($errors['https_in_url'])) { echo $errors['https_in_url']; } ?>
			<?php if(isset($errors['www_in_url'])) { echo $errors['www_in_url']; } ?>
			<?php if(isset($errors['tld'])) { echo $errors['tld']; } else { echo '<span>Domain</span>'; } ?></div>
            <div class="edit-field">
                <select name="https_in_url" class="http-s">
                    <option value="">Select</option>
                    <option value="Yes"<?php if(isset($_POST['https_in_url']) && $_POST['https_in_url'] == 'Yes') { echo ' selected'; } ?>>https://</option>
                    <option value="No"<?php if(isset($_POST['https_in_url']) && $_POST['https_in_url'] == 'No') { echo ' selected'; } ?>>http://</option>
                </select><select name="www_in_url" class="www">
                    <option value="">Select</option>
                    <option value="Yes"<?php if(isset($_POST['www_in_url']) && $_POST['www_in_url'] == 'Yes') { echo ' selected'; } ?>>www.</option>
                    <option value="No"<?php if(isset($_POST['www_in_url']) && $_POST['www_in_url'] == 'No') { echo ' selected'; } ?>>none</option>
                </select><input name="tld" class="tld" placeholder="your-domain.com" type="text" value="<?php if(isset($_POST['submit'])) { echo $tld; } ?>" />
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><?php if(isset($errors['new_site_name'])) { echo $errors['new_site_name']; } else { echo '<span>Site Name</span>'; } ?></div>
            <div class="edit-field">
                <input name="new_site_name" class="new-site-name" type="text" value="<?php if(isset($_POST['submit'])) { echo $new_site_name; } ?>" />
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><?php if(isset($errors['site_language'])) { echo $errors['site_language']; } else { echo '<span>Site Language</span>'; } ?></div>
            <div class="edit-field">
                <select name="site_language" class="site_language">
                    <?php echo $all_language_options; ?>
                </select>
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><?php if(isset($errors['timezone'])) { echo $errors['timezone']; } else { echo '<span>Time Zone</span>'; } ?></div>
            <div class="edit-field">
                <select name="timezone" class="timezone">
                    <?php echo $all_time_zone_options; ?>
                </select>
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><?php if(isset($errors['load_with_cache'])) { echo $errors['load_with_cache']; } else { echo '<span>Load the Site with Cached Results</span>'; } ?></div>
            <div class="edit-field">
                <select name="load_with_cache" class="load_with_cache">
                    <option value="Yes"<?php if(isset($_POST['load_with_cache']) && $_POST['load_with_cache'] == 'Yes') { echo ' selected'; } ?>>Yes</option>
                    <option value="No"<?php if(isset($_POST['load_with_cache']) && $_POST['load_with_cache'] == 'No') { echo ' selected'; } ?>>No</option>
                </select>
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="header-text margin-bottom-13">
              <div class="text float-none">Currency Format Setup</div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><?php if(isset($errors['currency_type'])) { echo $errors['currency_type']; } else { echo '<span>Currency Type</span>'; } ?></div>
            <div class="edit-field">
                <select name="currency_type">
                    <option value=""></option>
                    <option value="AED"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'AED') { echo ' selected'; } ?>>AED</option>
                    <option value="AUD"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'AUD') { echo ' selected'; } ?>>AUD</option>
                    <option value="BRL"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'BRL') { echo ' selected'; } ?>>BRL</option>
                    <option value="CAD"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'CAD') { echo ' selected'; } ?>>CAD</option>
                    <option value="CHF"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'CHF') { echo ' selected'; } ?>>CHF</option>
                    <option value="CNY"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'CNY') { echo ' selected'; } ?>>CNY</option>
                    <option value="DKK"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'DKK') { echo ' selected'; } ?>>DKK</option>
                    <option value="EUR"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'EUR') { echo ' selected'; } ?>>EUR</option>
                    <option value="GBP"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'GBP') { echo ' selected'; } ?>>GBP</option>
                    <option value="HKD"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'HKD') { echo ' selected'; } ?>>HKD</option>
                    <option value="IDR"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'IDR') { echo ' selected'; } ?>>IDR</option>
                    <option value="INR"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'INR') { echo ' selected'; } ?>>INR</option>
                    <option value="JPY"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'JPY') { echo ' selected'; } ?>>JPY</option>
                    <option value="KRW"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'KRW') { echo ' selected'; } ?>>KRW</option>
                    <option value="MXN"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'MXN') { echo ' selected'; } ?>>MXN</option>
                    <option value="MYR"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'MYR') { echo ' selected'; } ?>>MYR</option>
                    <option value="NOK"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'NOK') { echo ' selected'; } ?>>NOK</option>
                    <option value="NZD"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'NZD') { echo ' selected'; } ?>>NZD</option>
                    <option value="PLN"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'PLN') { echo ' selected'; } ?>>PLN</option>
                    <option value="RUB"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'RUB') { echo ' selected'; } ?>>RUB</option>
                    <option value="SAR"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'SAR') { echo ' selected'; } ?>>SAR</option>
                    <option value="SEK"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'SEK') { echo ' selected'; } ?>>SEK</option>
                    <option value="SGD"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'SGD') { echo ' selected'; } ?>>SGD</option>
                    <option value="THB"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'THB') { echo ' selected'; } ?>>THB</option>
                    <option value="TRY"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'TRY') { echo ' selected'; } ?>>TRY</option>
                    <option value="USD"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'USD') { echo ' selected'; } elseif(!isset($_POST['currency_type'])) { echo ' selected'; } ?>>USD</option>
                    <option value="ZAR"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'ZAR') { echo ' selected'; } ?>>ZAR</option>
                </select>
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><?php if(isset($errors['front_symbol'])) { echo $errors['front_symbol']; } else { echo '<span>Front Symbol</span>'; } ?></div>
            <div class="edit-field">
                <input name="front_symbol" type="text" class="front_symbol_field" value="<?php if(isset($_POST['submit'])) { echo $front_symbol; } else { echo '$'; } ?>" />
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><?php if(isset($errors['back_symbol'])) { echo $errors['back_symbol']; } else { echo '<span>Back Symbol</span>'; } ?></div>
            <div class="edit-field">
                <input name="back_symbol" type="text" class="back_symbol_field" value="<?php if(isset($_POST['submit'])) { echo $back_symbol; } ?>" />
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><?php if(isset($errors['thousand_separator'])) { echo $errors['thousand_separator']; } else { echo '<span>Thousand Separator</span>'; } ?></div>
            <div class="edit-field">
                <select name="thousand_separator" class="thousand_separator_field">
                    <option value=","<?php if(isset($_POST['thousand_separator']) && $_POST['thousand_separator'] == ',') { echo ' selected'; } elseif(!isset($_POST['thousand_separator'])) { echo ' selected'; } ?>>, (comma)</option>
                    <option value="."<?php if(isset($_POST['thousand_separator']) && $_POST['thousand_separator'] == '.') { echo ' selected'; } ?>>. (decimal point)</option>
                    <option value="'"<?php if(isset($_POST['thousand_separator']) && $_POST['thousand_separator'] == "'") { echo ' selected'; } ?>>' (single quote)</option>
                    <option value=" "<?php if(isset($_POST['thousand_separator']) && $_POST['thousand_separator'] == ' ') { echo ' selected'; } ?>>" " (space)</option>
                </select>
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><?php if(isset($errors['fractional_separator'])) { echo $errors['fractional_separator']; } else { echo '<span>Fractional Separator</span>'; } ?></div>
            <div class="edit-field">
                <select name="fractional_separator" class="fractional_separator_field">
                    <option value=","<?php if(isset($_POST['fractional_separator']) && $_POST['fractional_separator'] == ',') { echo ' selected'; } ?>>, (comma)</option>
                    <option value="."<?php if(isset($_POST['fractional_separator']) && $_POST['fractional_separator'] == '.') { echo ' selected'; } elseif(!isset($_POST['fractional_separator'])) { echo ' selected'; } ?>>. (decimal point)</option>
                </select>
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><?php if(isset($errors['zeros_after_separator'])) { echo $errors['zeros_after_separator']; } else { echo '<span>Zeros After Fractional Separator</span>'; } ?></div>
            <div class="edit-field">
                <select name="zeros_after_separator" class="zeros_after_separator_field">
                    <option value="1"<?php if(isset($_POST['zeros_after_separator']) && $_POST['zeros_after_separator'] == '1') { echo ' selected'; } ?>>1</option>
                    <option value="2"<?php if(isset($_POST['zeros_after_separator']) && $_POST['zeros_after_separator'] == '2') { echo ' selected'; } elseif(!isset($_POST['zeros_after_separator'])) { echo ' selected'; } ?>>2</option>
                    <option value="3"<?php if(isset($_POST['zeros_after_separator']) && $_POST['zeros_after_separator'] == '3') { echo ' selected'; } ?>>3</option>
                    <option value="4"<?php if(isset($_POST['zeros_after_separator']) && $_POST['zeros_after_separator'] == '4') { echo ' selected'; } ?>>4</option>
                </select>
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="header-text margin-bottom-13">
              <div class="text float-none">Outgoing SMTP Email Delivery Setup<div class="section-notes">Enter the SMTP email delivery settings for the site you're adding. These settings are used for password recovery, security alerts, and other outgoing email.</div></div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><?php if(isset($errors['smtp_email_name'])) { echo $errors['smtp_email_name']; } else { echo '<span>SMTP Email Name</span>'; } ?></div>
            <div class="edit-field">
                <input name="smtp_email_name" placeholder="i.e. Support" type="text" value="<?php if(isset($_POST['submit'])) { echo $smtp_email_name; } ?>" />
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><?php if(isset($errors['smtp_email_address'])) { echo $errors['smtp_email_address']; } else { echo '<span>SMTP Email Address</span>'; } ?></div>
            <div class="edit-field">
                <input name="smtp_email_address" placeholder="i.e. support@your-domain.com" type="text" value="<?php if(isset($_POST['submit'])) { echo $smtp_email_address; } ?>" />
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><?php if(isset($errors['smtp_email_hostname'])) { echo $errors['smtp_email_hostname']; } else { echo '<span>SMTP Email Hostname</span>'; } ?></div>
            <div class="edit-field">
                <input name="smtp_email_hostname" placeholder="i.e. mail.your-domain.com" type="text" value="<?php if(isset($_POST['submit'])) { echo $smtp_email_hostname; } ?>" />
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><?php if(isset($errors['smtp_email_port'])) { echo $errors['smtp_email_port']; } else { echo '<span>SMTP Email Port</span>'; } ?></div>
            <div class="edit-field">
                <input name="smtp_email_port" placeholder="i.e. 587 or 25 for relay/connector" type="text" value="<?php if(isset($_POST['submit'])) { echo $smtp_email_port; } ?>" />
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><span>SMTP Email Username</span></div>
            <div class="edit-field">
                <input name="smtp_email_username" type="text" value="<?php if(isset($_POST['submit'])) { echo $smtp_email_username; } ?>" />
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><span>SMTP Email Password</span></div>
            <div class="edit-field">
                <input name="smtp_email_password" type="password" value="" />
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="header-text margin-bottom-13">
              <div class="text float-none">Site Contact Information</div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><span>Street Address</span></div>
            <div class="edit-field">
                <input name="street_address" type="text" value="<?php if(isset($_POST['submit'])) { echo $street_address; } ?>" />
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><span>City</span></div>
            <div class="edit-field">
                <input name="city" type="text" value="<?php if(isset($_POST['submit'])) { echo $city; } ?>" />
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><?php if(isset($errors['country'])) { echo $errors['country']; } else { echo '<span>Country</span>'; } ?></div>
            <div class="edit-field">
                <input name="country" type="text" value="<?php if(isset($_POST['submit'])) { echo $country; } ?>" />
            <div class="small-text">Two-letter abbreviation, such as "US".</div>
            </div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><span>State / Province / Region</span></div>
            <div class="edit-field">
                <input name="state" type="text" value="<?php if(isset($_POST['submit'])) { echo $state; } ?>" />
            <div class="small-text">Two-letter abbreviation, such as "NY".</div>
            </div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><span>Postal Code</span></div>
            <div class="edit-field">
                <input name="postal_code" type="text" value="<?php if(isset($_POST['submit'])) { echo $postal_code; } ?>" />
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><span>Phone Number</span></div>
            <div class="edit-field">
                <input name="phone_number" type="text" value="<?php if(isset($_POST['submit'])) { echo $phone_number; } ?>" />
            <div class="small-text"></div>
            </div>
            </div>
            
            <div class="edit">
            <div class="edit-label"><?php if(isset($errors['display_contact_information'])) { echo $errors['display_contact_information']; } else { echo '<span>Display This Contact Information on the Website</span>'; } ?></div>
            <div class="edit-field">
                <select name="display_contact_information" class="display_contact_information">
                    <option value="No"<?php if(isset($_POST['display_contact_information']) && $_POST['display_contact_information'] == 'No') { echo ' selected'; } ?>>No</option>
                    <option value="Yes"<?php if(isset($_POST['display_contact_information']) && $_POST['display_contact_information'] == 'Yes') { echo ' selected'; } ?>>Yes</option>
                </select>
            <div class="small-text"><strong>Note:</strong> First and Last Name will not display on the website.</div>
            </div>
            </div>
            
            <div class="button-right">
                <button name="submit" type="submit" class="button">Add Site</button>
            </div>
        </form>
    </div>
	<?php } ?>
<?php } ?>