<?php

    class about_entry
    {
        public static $entry = array();
        
        public static function configure_entry()
        {
            about_entry::$entry = data::get_entry_by_id(core::$request[core::$request_real_count - 1]['id']);
            /*$result = mysql_query("SELECT * FROM global_structure WHERE id = ".core::$request[core::$request_count - 1]['id']);
            if ($tmp = mysql_fetch_assoc($result))
            {
                about_entry::$entry = array('name' => $tmp['name']);
                $tmp_meta = core::unserialize($tmp['meta'], array('content'));
                about_entry::$entry['content'] = $tmp_meta['content'];
            }*/
        }
    }
    
    about_entry::configure_entry();
    
    include $_SERVER['DOCUMENT_ROOT'].'/views/about_entry.view.php';
