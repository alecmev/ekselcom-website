<?php

    class products_category
    {
        public static $entries = array();
        
        public static function configure_entries()
        {
            products_category::$entries = data::get_entries_by_parent(core::$request[core::$request_real_count - 1]['id']);
            foreach (products_category::$entries as $tmp_key => $tmp_entry)
            {
                $tmp_entry['url'] = header::$location[header::$location_count]['url'].'/'.$tmp_entry['url_name'];
                if (!isset($tmp_entry['subname'])) $tmp_entry['subname'] = '';
                products_category::$entries[$tmp_key] = $tmp_entry;
            }
            /*$result = mysql_query("SELECT * FROM global_structure WHERE parent_id = ".core::$request[core::$request_count - 1]['id']." ORDER BY position");
            while ($tmp = mysql_fetch_assoc($result))
            {
                $tmp_entity = array('name' => $tmp['name'], 'url_name' => $tmp['url_name'], 'is_category' => ($tmp['controller'] == 'products_category'), 'subname' => '');
                $tmp_meta = core::unserialize($tmp['meta'], array('subname'));
                if ($tmp_entity['is_category'] == 0) $tmp_entity['subname'] = $tmp_meta['subname'];
                products_cateogry::$entities[] = $tmp_entity;
            }*/
        }
    }
    
    products_category::configure_entries();
    
    include $_SERVER['DOCUMENT_ROOT'].'/views/products_category.view.php';
