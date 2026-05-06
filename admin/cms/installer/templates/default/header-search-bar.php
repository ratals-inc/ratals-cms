<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(in_array('header-search-bar.php', $data_array['active_template_includes'])) {
$open_search_bar = '';
if(strpos(urlId([SITE_SEARCH_PAGE]), '/?url-id-disabled-or-deleted=') === false) {
if(!isset($search_term)) { $search_term = ''; }
if(empty($search_term) && strpos($url, $domain.'/search/') === false) { $open_search_bar = ' display-none'; }
?>
<div class="search-bar<?php echo $open_search_bar; ?>">
    <div class="container-width">
        <div>
            <form method="GET" action="<?php echo urlId([SITE_SEARCH_PAGE]); echo '?search='.htmlspecialchars($search_term); ?>">
                <input name="search" type="text" value="<?php echo htmlspecialchars($search_term); ?>" placeholder="What can we help you find?">
                <button>Search</button>
            </form>
        </div>
    </div>
</div>
<?php } ?>
<?php } ?>
