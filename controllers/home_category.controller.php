<?php

    class home_category
    {
        public static $entries = array();
        
        public static function configure_entries()
        {
            home_category::$entries = data::get_entries_by_parent(core::$request[0]['id'], 8);
            /*$result = mysql_query("SELECT * FROM global_structure WHERE parent_id = ".core::$request[0]['id']." ORDER BY created_on DESC LIMIT 0, 8");
            while ($tmp = mysql_fetch_assoc($result))
            {
                $tmp_entity = array('title' => $tmp['name'], 'created_on' => substr($tmp['created_on'], 0, 10));
                $tmp_meta = core::unserialize($tmp['meta'], array('content'));
                $tmp_entity['content'] = $tmp_meta['content'];
                home_cateogry::$entities[] = $tmp_entity;
            }*/
        }
    }
    
    home_category::configure_entries();
    
    include $_SERVER['DOCUMENT_ROOT'].'/views/home_category.view.php';
