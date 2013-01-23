<?php

	header('Content-Type: text/html; charset=UTF-8');
    
    include 'include/core.php';
    
    data::database_connect();
    core::parse_request();
	
	include 'controllers/header.controller.php';
    if (core::$status == 200) include 'controllers/'.core::$controller.'.controller.php';
	else if (core::$status == 404) include 'controllers/not_found.controller.php';
	else if (core::$status == 503) include 'controllers/service_unavailable.controller.php';
    else if (core::$status == 500) include 'controllers/internal_server_error.controller.php';
	include 'controllers/footer.controller.php';
