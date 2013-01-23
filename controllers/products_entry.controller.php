<?php

    class products_entry
    {
        public static $entry = array();
        
        public static function configure_entry()
        {
            products_entry::$entry = data::get_entry_by_id(core::$request[core::$request_real_count - 1]['id']);
            if (!isset(products_entry::$entry['sub_name'])) products_entry::$entry['sub_name'] = '';
            /*$result = mysql_query("SELECT * FROM global_structure WHERE id = ".core::$request[core::$request_count - 1]['id']);
            if ($tmp = mysql_fetch_assoc($result))
            {
                products_entity::$entity = array('name' => $tmp['name']);
                $tmp_meta = core::unserialize($tmp['meta'], array('subname', 'content'));
                products_entity::$entity['subname'] = $tmp_meta['subname'];
                products_entity::$entity['content'] = $tmp_meta['content'];
            }*/
        }
    }
    
    products_entry::configure_entry();
    
    include $_SERVER['DOCUMENT_ROOT'].'/views/products_entry.view.php';
