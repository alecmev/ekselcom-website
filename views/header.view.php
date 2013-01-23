<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN">
<html>

    <head>
        
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link type="text/css" rel="stylesheet" href="/public/css/global.css" />
        <link type="text/css" rel="stylesheet" href="/public/css/header.css" />
        <link type="text/css" rel="stylesheet" href="/public/css/content.css" />
        <link type="text/css" rel="stylesheet" href="/public/css/settings.css" />
        <link type="text/css" rel="stylesheet" href="/public/css/jquery-ui-1.8.16.custom.css" />
		<link rel="icon" type="image/ico" href="/public/ico/favicon.ico" />
        <script type="text/javascript" src="/public/js/jquery-1.6.2.min.js"></script>
        <script type="text/javascript" src="/public/js/jquery-ui-1.8.16.custom.min.js"></script>
        <?php if (core::$has_map) { ?>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
        <?php } ?>
        <script type="text/javascript">

            function initialize()
            {
                <?php if (admin::$is_admin) { ?>
                $('#log-out-button-header').click(function() {
                    $('#log-out-form-header').submit();
                });
                <?php } foreach (admin::$entries as $tmp) { if ($tmp['dialog']) { ?>
                $('#<?php echo $tmp['id']; ?>-dialog').dialog({
                    autoOpen: false,
                    width: 'auto',
                    show: 'drop',
                    hide: 'drop',
                    title: '<?php echo $tmp['name']; ?>',
                    modal: true,
                    buttons: {
                        'OK': function() {
                            $('#<?php echo $tmp['id']; ?>-form').submit();
                        },
                        'Cancel': function() {
                            $(this).dialog('close');
                        }
                    }
                });

                $('#<?php echo $tmp['id']; ?>-button').click(function() {
                    $('#<?php echo $tmp['id']; ?>-dialog').dialog('open');
                });
                <?php } else { ?>
                $('#<?php echo $tmp['id']; ?>-button').click(function() {
                    $('#<?php echo $tmp['id']; ?>-form').submit();
                });    
                <?php } } if (core::$has_map) { ?>
                var point = new google.maps.LatLng(56.95505, 24.196186);

                var myMapOptions = {
                    zoom: 15,
                    center: point,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };

                var map = new google.maps.Map(document.getElementById("map"),myMapOptions);

                var image = new google.maps.MarkerImage(
                    '/public/png/marker.png',
                    new google.maps.Size(36,44),
                    new google.maps.Point(0,0),
                    new google.maps.Point(18,42)
                );

                var shape = {
                    coord: [2,2, 34,2, 34,34, 26,34, 18,42, 10,34, 2,34, 2,2],
                    type: 'poly'
                };

                new google.maps.Marker({
                    draggable: false,
                    raiseOnDrag: false,
                    icon: image,
                    shape: shape,
                    map: map,
                    position: point
                });
                <?php } ?>
            }

        </script>
        
        <title><?php echo header::$title; ?></title>
        
    </head>

    <body onload="initialize()">
        
        <div id="header-background"></div>
        <div id="header-shadow"></div>

        <table id="main-table">
            
            <tr>
                <td>
                    
                    <table class="misc-max-width">
                        
                        <tr>
							<td id="header-logo-cell"><a id="header-logo-link" href="<?php echo header::$location[0]['url']; ?>" title="to the beginning"></a></td>
                            <td>
                                
                                <div id="header-menu-language-container">
                                    <div id="header-menu-language">
                                        <?php if (admin::$is_admin) { ?>
                                        <form action="" method="POST" id="log-out-form-header" style="display: none"><input type="hidden" name="password" value="" /></form>
                                        <div class="header-menu-language-item"><a href="#" id="log-out-button-header">log out</a></div>
                                        <?php } foreach (core::$languages as $tmp_language) { ?>
                                        <div class="header-menu-language-item<?php if ($tmp_language == core::$language) echo " misc-active-link"; ?>"><a href="/<?php echo $tmp_language.header::$language_postfix; ?>"><?php echo $tmp_language; ?></a></div>
                                        <?php } ?>
                                    </div>
                                </div>
							
								<div id="header-menu-navigation-big-container">
                                    <div id="header-menu-navigation-big">
                                        <?php foreach (header::$menu_big as $tmp) { ?>
                                        <div class="header-menu-navigation-big-item<?php if ($tmp['active']) echo " misc-active-link"; ?>"><a href="<?php echo $tmp['url']; ?>"><?php echo $tmp['name']; ?></a></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                
                                <div id="header-menu-navigation-small-container">
                                    <!--<div id="header-menu-navigation-small">
                                        <?php if (false) { foreach (header::$menu_small as $tmp) { ?>
                                        <div class="header-menu-navigation-small-item<?php if ($tmp['active']) echo " misc-active-link"; ?>"><a href="<?php echo $tmp['url']; ?>"><?php echo $tmp['name']; ?></a></div>
                                        <?php } } ?>
                                    </div>-->
                                </div>
                                
                            </td>
                        </tr>
                        
                    </table>
                
                </td>
            </tr>
            
            <tr>
                <td>
                    
                    <table id="content-table">
                        
                        <tr>
                            <td id="content-left">
                                
                                <div id="content-left-location">
                                    <?php foreach (header::$location as $tmp) { ?>
                                    <?php if (($tmp_end = ($tmp != end(header::$location))) || core::$request_count > core::$request_real_count) { ?><a href="<?php echo $tmp['url']; ?>"><?php echo $tmp['name']; ?></a><?php if ($tmp_end) echo ' / '; } else echo $tmp['name']; ?>
                                    <?php } if (header::$location_failure != '') echo ' - '.header::$location_failure; ?>
                                </div>
                            
                            	<table class="misc-max-width">
