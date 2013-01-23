<?php

    class header
    {
        public static $title = 'Ekselcom';
        public static $language_postfix = '';
        public static $menu_big = array();
        public static $menu_small = array();
        public static $location = array();
        public static $location_count = 0;
        public static $location_failure = '';
    
        public static function configure_title()
        {
            foreach (core::$request as $tmp_request) header::$title .= ' / '.$tmp_request['name'];
            if (core::$status != 200)
            {
                header::$title .= ' / ';
                switch (core::$status) {
                    case 404:
                        header::$title .= '404 - Not Found';
                        break;
                    case 503:
                        header::$title .= '503 - Service Unavailable';
                        break;
                    default:
                        header::$title .= '500 - Internal Server Error';
                        break;
                }
            }
        }
        
        public static function configure_menu()
        {
            foreach (core::$request as $tmp) header::$language_postfix .= '/'.$tmp['url_name'];
            $tmp_entries = data::get_entries_by_parent(0, false, 1);
            foreach ($tmp_entries as $tmp_entry)
            {
                $tmp_entry['active'] = (core::$request_real_count > 0 ? $tmp_entry['id'] == core::$request[0]['id'] : false);
                $tmp_entry['url'] = '/'.core::$language.'/'.$tmp_entry['url_name'];
                $tmp_entry['name'] = mb_strtolower($tmp_entry['name'], 'UTF-8');
                header::$menu_big[] = $tmp_entry;
            }
            if (core::$request_real_count > 0 && core::$request[0]['in_menu'] == 1)
            {
                $tmp_entries = data::get_entries_by_parent(core::$request[0]['id'], false, 1);
                foreach ($tmp_entries as $tmp_entry)
                {
                    $tmp_entry['active'] = (core::$request_real_count > 1 ? $tmp_entry['id'] == core::$request[1]['id'] : 0);
                    $tmp_entry['url'] = '/'.core::$language.'/'.core::$request[0]['url_name'].'/'.$tmp_entry['url_name'];
                    $tmp_entry['name'] = mb_strtolower($tmp_entry['name'], 'UTF-8');
                    header::$menu_small[] = $tmp_entry;
                }
            }
        }
        
        public static function configure_location()
        {
            $tmp_url = '/'.core::$language;
            header::$location[] = array('url' => $tmp_url, 'name' => 'Ekselcom');
            foreach (core::$request as $tmp_request)
            {
                $tmp_url .= '/'.$tmp_request['url_name'];
                header::$location[] = array('url' => $tmp_url, 'name' => $tmp_request['name']);
                ++header::$location_count;
            }
            if (core::$status != 200)
            {
                switch (core::$status) {
                    case 404:
                        header::$location_failure .= 'Not Found';
                        break;
                    case 503:
                        header::$location_failure .= 'Service Unavailable';
                        break;
                    default:
                        header::$location_failure .= 'Internal Server Error';
                        break;
                }
            }
        }
    }
    
    header::configure_title();
    header::configure_menu();
    header::configure_location();
    
    include $_SERVER['DOCUMENT_ROOT'].'/views/header.view.php';
