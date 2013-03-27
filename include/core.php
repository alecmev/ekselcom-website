<?php

    class data
    {
        public static function database_connect()
        {
            mysql_connect('%SERVER%', '%USERNAME%', '%PASSWORD%');
            mysql_set_charset('utf8');
            mysql_select_db('development');
        }
        
        public static function parse_entry($entry)
        {
            if ($entry == false) return false;
            if ($entry['data'] != '')
            {
                $tmp_ids = json_decode($entry['data']);
                foreach ($tmp_ids as $tmp_name => $tmp_id)
                {
                    $tmp_value = mysql_fetch_assoc(mysql_query('SELECT * FROM global_data WHERE id = '.$tmp_id));
                    $entry[$tmp_name] = core::bb(core::hscd($tmp_value[core::$language]), ($tmp_name == 'content'));
                    if ($entry[$tmp_name] == '')
                    {
                        foreach (core::$languages as $tmp_language)
                        {
                            if ($tmp_value[$tmp_language] != '')
                            {
                                $entry[$tmp_name] = core::bb(core::hscd($tmp_value[$tmp_language]), ($tmp_name == 'content'));
                                break;
                            }
                        }
                    }
                }
                
            }
            return $entry;
        }
        
        public static function get_entry_by_id($id)
        {
            $tmp_result = mysql_query('
                SELECT * 
                FROM global_structure 
                WHERE id = '.$id
                );
            return ($tmp_result ? data::parse_entry(mysql_fetch_assoc($tmp_result)) : false);
        }
        
        public static function get_entry_by_parent_and_url($parent_id, $url_name)
        {
            $tmp_result = mysql_query('
                SELECT * 
                FROM global_structure 
                WHERE parent_id = '.$parent_id.' AND url_name = "'.core::db($url_name).'"'
                );
            return ($tmp_result ? data::parse_entry(mysql_fetch_assoc($tmp_result)) : false);
        }
        
        public static function get_entries_by_parent($parent_id, $limit = false, $in_menu = false)
        {
            $tmp_result = mysql_query('
                SELECT * 
                FROM global_structure 
                WHERE parent_id = '.$parent_id.($in_menu != false ? ' AND in_menu = '.$in_menu : '').' 
                ORDER BY position ASC, created_on DESC'.(is_int($limit) ? ' LIMIT 0, '.$limit : '')
                );
            $entries = array();
            if ($tmp_result) while ($tmp_entry = data::parse_entry(mysql_fetch_assoc($tmp_result))) { $entries[] = $tmp_entry; }
            else $entries = false;
            return $entries;
        }
        
        public static function get_translations($id, $data_name)
        {
            $tmp_entry = mysql_fetch_assoc(mysql_query('
                SELECT * 
                FROM global_structure 
                WHERE id = '.$id
                ));
            $tmp_data = json_decode($tmp_entry['data'], true);
            if (isset($tmp_data[$data_name]))
            {
                return mysql_fetch_assoc(mysql_query('
                    SELECT *
                    FROM global_data
                    WHERE id = '.$tmp_data[$data_name]
                    ));
            }
            else return false;
        }
        
        public static function add_entry($parent_id, $in_menu, $position, $controller, $url_name, $data)
        {
            foreach ($data as $tmp_key => $tmp_value)
            {
                mysql_query('
                    INSERT INTO global_data (en)
                    VALUES ("'.$tmp_value.'")
                    ');
                $data[$tmp_key] = mysql_insert_id();
            }
            if ($position)
            {
                $tmp_position = mysql_fetch_array(mysql_query('
                    SELECT MAX(position) 
                    FROM global_structure
                    WHERE parent_id = '.$parent_id
                    ));
                $position = $tmp_position[0] + 1;
            }
            else $position = 0;
            $url_postfix = 0;
            while (mysql_num_rows(mysql_query('
                SELECT id 
                FROM global_structure
                WHERE parent_id = '.$parent_id.' AND url_name = "'.core::db($url_name.($url_postfix > 0 ? '-'.$url_postfix : '')).'"'
                )) > 0) ++$url_postfix;
            if ($url_postfix > 0) $url_name .= '-'.$url_postfix;
            mysql_query('
                INSERT INTO global_structure
                VALUES (
                    NULL, 
                    '.$parent_id.', 
                    200, 
                    '.$in_menu.', 
                    '.$position.', 
                    "'.core::db($controller).'", 
                    NOW(), 
                    "'.core::db($url_name).'", 
                    "'.core::db(json_encode($data)).'")
                ');
        }
        
        public static function set_entry_position($id, $is_up)
        {
            $tmp_entry = mysql_fetch_assoc(mysql_query('
                SELECT *
                FROM global_structure
                WHERE id = '.$id
                ));
            if ($is_up)
            {
                if ($tmp_entry['position'] > 0)
                {
                    $tmp_entry_2 = mysql_fetch_assoc(mysql_query('
                        SELECT *
                        FROM global_structure
                        WHERE parent_id = '.$tmp_entry['parent_id'].' AND position = '.($tmp_entry['position'] - 1)
                        ));
                    mysql_query('
                        UPDATE global_structure
                        SET position = '.$tmp_entry_2['position'].'
                        WHERE id = '.$id
                        );
                    mysql_query('
                        UPDATE global_structure
                        SET position = '.$tmp_entry['position'].'
                        WHERE id = '.$tmp_entry_2['id']
                        );
                }
            }
            else
            {
                $tmp_position = mysql_fetch_array(mysql_query('
                    SELECT MAX(position) 
                    FROM global_structure
                    WHERE parent_id = '.$tmp_entry['parent_id']
                    ));
                $tmp_position = $tmp_position[0];
                if ($tmp_entry['position'] < $tmp_position)
                {
                    $tmp_entry_2 = mysql_fetch_assoc(mysql_query('
                        SELECT *
                        FROM global_structure
                        WHERE parent_id = '.$tmp_entry['parent_id'].' AND position = '.($tmp_entry['position'] + 1)
                        ));
                    mysql_query('
                        UPDATE global_structure
                        SET position = '.$tmp_entry_2['position'].'
                        WHERE id = '.$id
                        );
                    mysql_query('
                        UPDATE global_structure
                        SET position = '.$tmp_entry['position'].'
                        WHERE id = '.$tmp_entry_2['id']
                        );
                }
            }
        }
        
        public static function delete_entry($id, $is_first)
        {
            $tmp_result = mysql_query('
                SELECT *
                FROM global_structure
                WHERE parent_id = '.$id
                );
            while ($tmp_entry = mysql_fetch_assoc($tmp_result)) data::delete_entry($tmp_entry['id'], false);
            $tmp_entry = mysql_fetch_assoc(mysql_query('
                SELECT *
                FROM global_structure
                WHERE id = '.$id
                ));
            mysql_query('
                INSERT INTO trash_structure
                VALUES (
                    NULL, 
                    '.$tmp_entry['parent_id'].', 
                    '.$tmp_entry['status'].', 
                    '.$tmp_entry['in_menu'].', 
                    '.$tmp_entry['position'].', 
                    "'.core::db($tmp_entry['controller']).'", 
                    '.$tmp_entry['created_on'].', 
                    "'.core::db($tmp_entry['url_name']).'", 
                    "'.core::db($tmp_entry['data']).'")
                ');
            mysql_query('
                DELETE FROM global_structure
                WHERE id = '.$id
                );
            if ($is_first)
            {
                $tmp_result = mysql_query('
                    SELECT *
                    FROM global_structure
                    WHERE parent_id = '.$tmp_entry['parent_id'].' AND position > '.$tmp_entry['position']
                    );
                while ($tmp_entry = mysql_fetch_assoc($tmp_result))
                {
                    mysql_query('
                        UPDATE global_structure
                        SET position = '.($tmp_entry['position'] - 1).'
                        WHERE id = '.$tmp_entry['id']
                        );
                }
            }
        }
        
        public static function set_entry_url($id, $url_name)
        {
            $tmp_entry = mysql_fetch_assoc(mysql_query('
                SELECT *
                FROM global_structure
                WHERE id = '.$id
                ));
            if ($url_name != $tmp_entry['url_name'])
            {
                $url_postfix = 0;
                while (mysql_num_rows(mysql_query('
                    SELECT id 
                    FROM global_structure
                    WHERE parent_id = '.$tmp_entry['parent_id'].' AND url_name = "'.core::db($url_name.($url_postfix > 0 ? '-'.$url_postfix : '')).'"'
                    )) > 0) ++$url_postfix;
                if ($url_postfix > 0) $url_name .= '-'.$url_postfix;
                mysql_query('
                    UPDATE global_structure
                    SET url_name = "'.$url_name.'"
                    WHERE id = '.$id
                    );
            }
        }
        
        public static function set_translations($id, $data_name, $translations)
        {
            $tmp_entry = mysql_fetch_assoc(mysql_query('
                SELECT *
                FROM global_structure
                WHERE id = '.$id
                ));
            $tmp_data = json_decode($tmp_entry['data'], true);
            $tmp_set = '';
            foreach ($translations as $tmp_language => $tmp_translation) $tmp_set .= ', '.$tmp_language.' = "'.core::db($tmp_translation).'"';
            $tmp_set = substr($tmp_set, 2);
            mysql_query('
                UPDATE global_data
                SET '.$tmp_set.'
                WHERE id = '.$tmp_data[$data_name]
                );
        }
    }

    class core
    {
        public static $status = 200;
        public static $languages = array('en', 'ru', 'lv');
        public static $language = '';
        public static $request = array();
        public static $request_count = 0;
        public static $request_real_count = 0;
        public static $request_url = array();
        public static $request_url_string = '';
        public static $controller = '';
        public static $has_map = false;
        public static $has_admin = false;
        
        public static function db($str) { return mysql_real_escape_string($str); }
        public static function hsc($str) { return htmlspecialchars($str); } //, ENT_COMPAT, 'UTF-8', false
        public static function hscd($str) { return htmlspecialchars_decode($str); }
        public static function url($str) { return rawurlencode($str); }
        public static function sha($str) { return hash_hmac('sha512', $str, '3DDA724D877F8D220F017C7002296BB8C1CFAFF1C75E63314D6BD2D1BAEAE794'); }
        
        public static function bb($str, $is_content)
        {
            if ($is_content && $str != '')
            {
                if ($str[0] == '[') $str = '[br-4]'.$str;
                if ($str[strlen($str) - 1] == ']') $str = $str.'[br-4]';
            
                $tmp_matches = array(array(), array());
                preg_match_all("/\]([^\[\]]+)\[/", ']'.$str.'[', $tmp_matches);
                foreach ($tmp_matches[1] as $tmp_key => $tmp_value) $tmp_matches[1][$tmp_key] = '][br-12]'.$tmp_value.'[';
                $str = str_replace($tmp_matches[0], $tmp_matches[1], ']'.$str.'[');
                $str = substr($str, 1, strlen($str) - 2);

                preg_match_all("/\[img=(\/public\/png\/content\/[\w\-]+\.png)\]/", $str, $tmp_matches);
                foreach ($tmp_matches[1] as $tmp_key => $tmp_value) $tmp_matches[1][$tmp_key] = '[br-12]<img src="'.$tmp_value.'" style="width: 100%; display: block" />';
                $str = str_replace($tmp_matches[0], $tmp_matches[1], $str);
                
                if (strpos($str, '[map]') != false)
                {
                    $str = str_replace('[map]', '[br-12]<div id="map" style="width: 100%; height: 256px"></div>', $str);
                    core::$has_map = true;
                }
                
                $tmp_search = array('[br-4]', '[br-8]', '[br-12]', '[br-16]');
                $tmp_replace = array('<div style="height: 4px"></div>', '<div style="height: 8px"></div>', '<div style="height: 12px"></div>', '<div style="height: 16px"></div>');
                return str_replace($tmp_search, $tmp_replace, $str);
            }
            else return $str;
        }
        
        public static function redirect_to($str)
        {
            header('Location: /'.$str);
            exit();
        }
        
        public static function parse_request()
        {
            $tmp = strlen($_SERVER['REQUEST_URI']) - 1;
            if ($tmp > 0 && $_SERVER['REQUEST_URI'][$tmp] == '/') --$tmp;
            core::$request_url = explode('/', substr($_SERVER['REQUEST_URI'], 1, $tmp));
            core::$language = array_shift(core::$request_url);
            if (!in_array(core::$language, core::$languages))
            {
                if (isset($_COOKIE['language']) && in_array($_COOKIE['language'], core::$languages)) core::redirect_to($_COOKIE['language']);
                core::redirect_to(core::$languages[0]);
            }
            setcookie('language', core::$language, time() + 60 * 60 * 24 * 30 * 60, '/');
            core::$request_count = count(core::$request_url);
            if (core::$request_count == 0) core::$request_url[0] = 'home';
            foreach (core::$request_url as $tmp) core::$request_url_string .= '/'.$tmp;
            core::$request_url_string = '/'.core::$language.core::$request_url_string;
            
            $parent_id = 0;
            foreach (core::$request_url as $tmp_request)
            {
                $tmp_entry = data::get_entry_by_parent_and_url($parent_id, $tmp_request);
                if ($tmp_entry)
                {
                    core::$request[core::$request_real_count] = $tmp_entry;
                    if (core::$request[core::$request_real_count]['status'] != 200 && core::$status == 200) core::$status = core::$request[core::$request_real_count]['status'];
                    $parent_id = core::$request[core::$request_real_count]['id'];
                    if (core::$request[core::$request_real_count++]['url_name'] == 'admin')
                    {
                        core::$has_admin = true;
                        break;
                    }
                }
                else
                {
                    core::$status = 404;
                    break;
                }
            }
            
            if (core::$status == 200) core::$controller = core::$request[core::$request_real_count - 1]['controller'];
            core::$has_admin = core::$has_admin || (isset($_POST['password']) && $_POST['password'] != '') || isset($_COOKIE['password']);
            if (core::$has_admin) admin::parse_request();
        }
        
        /*public static function check_email($str)
        {
            $tmp = db($str);
            if ($str != $tmp) return 3;
            $str = strtolower($tmp);
            if (strlen($str) <= 256 && preg_match("/^[a-z0-9._%-]+@[a-z0-9.-]+\.[a-z]{2,4}$/", $str))
            {
                if (mysql_num_rows(mysql_query("SELECT * FROM users WHERE email = '".$str."'"))) return 1;
                else return 0;
            }
            else return 2;
        }*/
        
        /*public static function unserialize($meta, $fields)
        {
            $tmp_meta = unserialize(core::hscd($meta));
            $tmp_result = array();
            foreach ($fields as $tmp_field) if (isset($tmp_meta[$tmp_field])) $tmp_result[$tmp_field] = core::hscd($tmp_meta[$tmp_field]); else $tmp_result[$tmp_field] = '';
            return $tmp_result;
        }*/
    }
    
    class admin
    {
        public static $is_admin = false;
        public static $image = false;
        public static $entries = array();
        
        public static function parse_request()
        {
            if (isset($_POST['password']))
            {
                if ($_POST['password'] == 'iamadmin')
                {
                    setcookie('password', core::sha($_POST['password']), 0, '/');
                    admin::$is_admin = true;
                }
                else setcookie('password', '', 1, '/');
            }
            else if (isset($_COOKIE['password']) && $_COOKIE['password'] == core::sha('iamadmin')) admin::$is_admin = true;
            
            if (core::$status == 200)
            {
                if (!admin::$is_admin && core::$controller == 'admin_category')
                {
                    admin::$entries[] = array(
                        'name' => 'Log in', 
                        'id' => 'log-in', 
                        'url' => '', 
                        'dialog' => true, 
                        'params' => array(
                            array('name' => 'password', 'type' => 'password', 'value' => '')
                            )
                        );
                }
                else
                {
                    switch (core::$controller)
                    {
                        case 'admin_category':
                            admin::$entries[] = array(
                                'name' => 'Log out', 
                                'id' => 'log-out', 
                                'url' => '', 
                                'dialog' => false, 
                                'params' => array(
                                    array('name' => 'password', 'type' => 'hidden', 'value' => '')
                                    )
                                );
                            
                            if (isset($_POST['data']))
                            {
                                switch ($_POST['data'])
                                {
                                    case 'upload-image':
                                        $tmp_image = false;
                                        switch (strtolower($_FILES['image']['type']))
                                        {
                                            case 'image/png':
                                                $tmp_image = imagecreatefrompng($_FILES['image']['tmp_name']);
                                                break;
                                            case 'image/jpeg':
                                                $tmp_image = imagecreatefromjpeg($_FILES['image']['tmp_name']);
                                                break;
                                        }
                                        if ($tmp_image !== false)
                                        {
                                            $tmp_image2 = imagecreatetruecolor(928, 256);
                                            imagecolortransparent($tmp_image2, imagecolorallocate($tmp_image2, 0, 0, 0));
                                            $src_w = imagesx($tmp_image);
                                            $src_h = imagesy($tmp_image);
                                            $src_c = $src_w / $src_h;
                                            $dst_c = 928 / 256;
                                            $dst_w = ($src_c > $dst_c ? 928 : (256 / $src_h) * $src_w);
                                            $dst_h = ($src_c > $dst_c ? (928 / $src_w) * $src_h : 256);
                                            $dst_x = ($src_c > $dst_c ? 0 : (928 - $dst_w) / 2);
                                            $dst_y = ($src_c > $dst_c ? (256 - $dst_h) / 2 : 0);
                                            imagecopyresampled($tmp_image2, $tmp_image, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
                                            $tmp_postfix = 0;
                                            $tmp_info = pathinfo($_FILES['image']['name']);
                                            $tmp_name = $_SERVER['DOCUMENT_ROOT'].'/public/png/content/'.preg_replace("/[^\w\-]+/", "-", basename(strtolower($_FILES['image']['name']), '.'.$tmp_info['extension']));
                                            while (file_exists($tmp_name.($tmp_postfix > 0 ? $tmp_postfix : '').'.png')) ++$tmp_postfix;
                                            
                                            admin::$image = $tmp_name.($tmp_postfix > 0 ? $tmp_postfix : '').'.png';
                                            
                                            imagepng($tmp_image2, admin::$image, 0);
                                            $matches = array();
                                            preg_match("/(\/public\/png\/content\/[\w\-]+\.png)/", admin::$image, $matches);
                                            admin::$image = $matches[0];
                                        }
                                        break;
                                }
                            }
                            break;
                            
                        case 'products_category':
                            admin::$entries[] = array(
                                'name' => 'Add category', 
                                'id' => 'add-category', 
                                'url' => '', 
                                'dialog' => true, 
                                'params' => array(
                                    array('name' => 'name', 'type' => 'text', 'value' => ''),
                                    array('name' => 'url_name', 'type' => 'text', 'value' => '')
                                    )
                                );
                            
                            admin::$entries[] = array(
                                'name' => 'Add entry', 
                                'id' => 'add-entry', 
                                'url' => '', 
                                'dialog' => true, 
                                'params' => array(
                                    array('name' => 'name', 'type' => 'text', 'value' => ''),
                                    array('name' => 'subname', 'type' => 'text', 'value' => ''),
                                    array('name' => 'url_name', 'type' => 'text', 'value' => ''),
                                    array('name' => 'content', 'type' => 'textarea', 'value' => '')
                                    )
                                );
                            
                            if (isset($_POST['data']))
                            {
                                switch ($_POST['data'])
                                {
                                    case 'add-category':
                                        if (!preg_match("/[^\w\-\s,.!\?'\"]+/u", $_POST['name']) && !preg_match("/[^\w\-]+/u", $_POST['url_name']))
                                        {
                                            data::add_entry(
                                                core::$request[core::$request_real_count - 1]['id'], 
                                                1, 
                                                true, 
                                                'products_category',
                                                preg_replace("/[_]+/", '-', strtolower($_POST['url_name'])),
                                                array('name' => $_POST['name']));
                                        }
                                        break;
                                    case 'add-entry':
                                        if (!preg_match("/[^\w\-\s,.!\?'\"]+/u", $_POST['name'].$_POST['subname']) && !preg_match("/[^\w\-]+/u", $_POST['url_name']))
                                        {
                                            data::add_entry(
                                                core::$request[core::$request_real_count - 1]['id'], 
                                                0,
                                                true,
                                                'products_entry',
                                                preg_replace("/[_]+/", '-', strtolower($_POST['url_name'])),
                                                array('name' => $_POST['name'], 'subname' => $_POST['subname'], 'content' => $_POST['content']));
                                        }
                                        break;
                                    case 'position-up':
                                        data::set_entry_position($_POST['id'], true);
                                        break;
                                    case 'position-down':
                                        data::set_entry_position($_POST['id'], false);
                                        break;
                                    case 'delete-entry':
                                        data::delete_entry($_POST['id'], true);
                                        break;
                                    case 'set-url':
                                        data::set_entry_url($_POST['id'], $_POST['url_name']);
                                        break;
                                    case 'set-name':
                                        $tmp_translations = array();
                                        foreach (core::$languages as $tmp_language) $tmp_translations[$tmp_language] = $_POST[$tmp_language];
                                        data::set_translations($_POST['id'], 'name', $tmp_translations);
                                        break;
                                    case 'set-subname':
                                        $tmp_translations = array();
                                        foreach (core::$languages as $tmp_language) $tmp_translations[$tmp_language] = $_POST[$tmp_language];
                                        data::set_translations($_POST['id'], 'subname', $tmp_translations);
                                        break;
                                    case 'set-content':
                                        $tmp_translations = array();
                                        foreach (core::$languages as $tmp_language) $tmp_translations[$tmp_language] = $_POST[$tmp_language];
                                        data::set_translations($_POST['id'], 'content', $tmp_translations);
                                        break;
                                }
                            }
                            break;
                            
                        case 'home_category':
                            admin::$entries[] = array(
                                'name' => 'Add entry', 
                                'id' => 'add-entry', 
                                'url' => '', 
                                'dialog' => true, 
                                'params' => array(
                                    array('name' => 'name', 'type' => 'text', 'value' => ''),
                                    array('name' => 'url_name', 'type' => 'text', 'value' => ''),
                                    array('name' => 'content', 'type' => 'textarea', 'value' => '')
                                    )
                                );
                            
                            if (isset($_POST['data']))
                            {
                                switch ($_POST['data'])
                                {
                                    case 'add-entry':
                                        if (!preg_match("/[^\w\-\s,.!\?'\"]+/u", $_POST['name']) && !preg_match("/[^\w\-]+/u", $_POST['url_name']))
                                        {
                                            data::add_entry(
                                                core::$request[core::$request_real_count - 1]['id'], 
                                                0,
                                                false,
                                                'home_entry',
                                                preg_replace("/[_]+/", '-', strtolower($_POST['url_name'])),
                                                array('name' => $_POST['name'], 'content' => $_POST['content']));
                                        }
                                        break;
                                    case 'delete-entry':
                                        data::delete_entry($_POST['id'], true);
                                        break;
                                    case 'set-url':
                                        data::set_entry_url($_POST['id'], $_POST['url_name']);
                                        break;
                                    case 'set-name':
                                        $tmp_translations = array();
                                        foreach (core::$languages as $tmp_language) $tmp_translations[$tmp_language] = $_POST[$tmp_language];
                                        data::set_translations($_POST['id'], 'name', $tmp_translations);
                                        break;
                                    case 'set-content':
                                        $tmp_translations = array();
                                        foreach (core::$languages as $tmp_language) $tmp_translations[$tmp_language] = $_POST[$tmp_language];
                                        data::set_translations($_POST['id'], 'content', $tmp_translations);
                                        break;
                                }
                            }
                            break;
                            
                        case 'about_category':
                            admin::$entries[] = array(
                                'name' => 'Add category', 
                                'id' => 'add-category', 
                                'url' => '', 
                                'dialog' => true, 
                                'params' => array(
                                    array('name' => 'name', 'type' => 'text', 'value' => ''),
                                    array('name' => 'url_name', 'type' => 'text', 'value' => '')
                                    )
                                );
                            
                            admin::$entries[] = array(
                                'name' => 'Add entry', 
                                'id' => 'add-entry', 
                                'url' => '', 
                                'dialog' => true, 
                                'params' => array(
                                    array('name' => 'name', 'type' => 'text', 'value' => ''),
                                    array('name' => 'subname', 'type' => 'text', 'value' => ''),
                                    array('name' => 'url_name', 'type' => 'text', 'value' => ''),
                                    array('name' => 'content', 'type' => 'textarea', 'value' => '')
                                    )
                                );
                            
                            if (isset($_POST['data']))
                            {
                                switch ($_POST['data'])
                                {
                                    case 'add-category':
                                        if (!preg_match("/[^\w\-\s,.!\?'\"]+/u", $_POST['name']) && !preg_match("/[^\w\-]+/u", $_POST['url_name']))
                                        {
                                            data::add_entry(
                                                core::$request[core::$request_real_count - 1]['id'], 
                                                1, 
                                                true, 
                                                'about_category',
                                                preg_replace("/[_]+/", '-', strtolower($_POST['url_name'])),
                                                array('name' => $_POST['name']));
                                        }
                                        break;
                                    case 'add-entry':
                                        if (!preg_match("/[^\w\-\s,.!\?'\"]+/u", $_POST['name'].$_POST['subname']) && !preg_match("/[^\w\-]+/u", $_POST['url_name']))
                                        {
                                            data::add_entry(
                                                core::$request[core::$request_real_count - 1]['id'], 
                                                0,
                                                true,
                                                'about_entry',
                                                preg_replace("/[_]+/", '-', strtolower($_POST['url_name'])),
                                                array('name' => $_POST['name'], 'subname' => $_POST['subname'], 'content' => $_POST['content']));
                                        }
                                        break;
                                    case 'position-up':
                                        data::set_entry_position($_POST['id'], true);
                                        break;
                                    case 'position-down':
                                        data::set_entry_position($_POST['id'], false);
                                        break;
                                    case 'delete-entry':
                                        data::delete_entry($_POST['id'], true);
                                        break;
                                    case 'set-url':
                                        data::set_entry_url($_POST['id'], $_POST['url_name']);
                                        break;
                                    case 'set-name':
                                        $tmp_translations = array();
                                        foreach (core::$languages as $tmp_language) $tmp_translations[$tmp_language] = $_POST[$tmp_language];
                                        data::set_translations($_POST['id'], 'name', $tmp_translations);
                                        break;
                                    case 'set-subname':
                                        $tmp_translations = array();
                                        foreach (core::$languages as $tmp_language) $tmp_translations[$tmp_language] = $_POST[$tmp_language];
                                        data::set_translations($_POST['id'], 'subname', $tmp_translations);
                                        break;
                                    case 'set-content':
                                        $tmp_translations = array();
                                        foreach (core::$languages as $tmp_language) $tmp_translations[$tmp_language] = $_POST[$tmp_language];
                                        data::set_translations($_POST['id'], 'content', $tmp_translations);
                                        break;
                                }
                            }
                            break;
                    }
                }
            }
        }
    }
