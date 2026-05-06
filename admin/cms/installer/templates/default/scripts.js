/*
Copyright (c) 2025-2026 Ratals Inc.
Licensed under the Apache License, Version 2.0
Full License & Terms: https://www.ratals.com/license/
*/
$(function(){ $(".search-button-toggle").click(function(){ $(".search-bar").slideToggle(); }); });
$(function(){ $(".mobile-menu .mobile-bars").click(function(){ $(".menu").slideToggle(); }); });
$(function(){ $(".mobile-sub-menu-arrow").click(function(){ $(this).next('ul').slideToggle(); }); });
$(function(){ $(".filters-set span.toggle").click(function(){ $(".filters-results-wrap").slideToggle(); }); });