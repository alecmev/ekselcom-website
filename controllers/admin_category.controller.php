<?php

    class admin_category
    {
        public static $entries = array();
        
        public static function configure_admin_category()
        {
            /*admin_category::$entries = data::get_entries_by_parent(core::$request[core::$request_real_count - 1]['id']);
            if (core::$is_admin_category && core::$request_real_count > 1) { }
            else if (core::$is_admin_category) admin_category::$entries = array_slice(admin_category::$entries, 1);
            else admin_category::$entries = array_slice(admin_category::$entries, 0, 1);
            
            if (core::$is_admin_category || core::$request_real_count == 1)
            {
                foreach (admin_category::$entries as $tmp_key => $tmp_entry)
                {
                    $tmp_entry['url'] = header::$location[header::$location_count]['url'].'/'.$tmp_entry['url_name'];
                    if (!isset($tmp_entry['subname'])) $tmp_entry['subname'] = '';
                    admin_category::$entries[$tmp_key] = $tmp_entry;
                }
            }*/
            /*admin_category::$entries = data::get_entries_by_parent(core::$request[core::$request_real_count - 1]['id']);
            foreach (admin_category::$entries as $tmp_key => $tmp_entry)
            {
                $tmp_entry['url'] = header::$location[header::$location_count]['url'].'/'.$tmp_entry['url_name'];
                if (!isset($tmp_entry['subname'])) $tmp_entry['subname'] = '';
                admin_category::$entries[$tmp_key] = $tmp_entry;
            }*/
        }
    }
    
    admin_category::configure_admin_category();
    
    include $_SERVER['DOCUMENT_ROOT'].'/views/admin_category.view.php';
