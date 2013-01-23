<?php

    header('Content-Type: text/html; charset=UTF-8');
    
    include 'include/core.php';
    
    core::database_connect();
    
    $output = '';
    
    if (isset($_POST['data']))
    {
        if ($_POST['name'] != '') $tmp['name'] = core::hsc($_POST['name']);
        if ($_POST['subname'] != '') $tmp['subname'] = core::hsc($_POST['subname']);
        if ($_POST['content'] != '') $tmp['content'] = core::hsc($_POST['content']);
                
        if (count($tmp)) $output = core::hsc(core::hsc(serialize($tmp)));
        else $output = 'error: no input data';
    }
    else if (isset($_POST['run']))
    {
        $tmp_matches = array(array(), array());
        preg_match_all("/\]([^\[\]]+)\[/", ']'."The new Mac Pro offers two advanced processor options from Intel. The Quad-Core Intel Xeon Nehalem processor is available in a single-processor, quad-core configuration at speeds up to 3.2GHz.[img=/public/png/content/foobar-device.png][img=/public/png/content/foobar-device.png]For even greater speed and power, choose the Westmere series, Intels next-generation processor based on its latest 32-nm process technology. Westmere is available in both quad-core and 6-core versions, and the Mac Pro comes with either one or two processors. Which means that you can have a 6-core Mac Pro at 3.33GHz, an 8-core system at 2.4GHz, or, to max out your performance, a 12-core system at up to 2.93GHz.".'[', $tmp_matches);
        var_dump($tmp_matches);
        /*$matches = array();
        $count = preg_match_all("/\[img='\/public\/png\/content\/([\w\-]+)\.png'\]/", , $matches);
        foreach ($matches as $tmp1)
        {
            foreach ($tmp1 as $tmp2) $output .= $tmp2.' ';
        }
        $output .= $count;*/
    }
    
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN">
<html>
    
    <head>
        
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link type="text/css" rel="stylesheet" href="/public/css/global.css" />
        <link rel="icon" type="image/ico" href="/public/ico/favicon.ico" />
        <script type="text/javascript" src="/public/js/jquery-1.5.2.min.js"></script>
        
        <title>Ekselcom / Admin</title>
        
        <style type="text/css">
            
            td
            {
                padding: 12px 0px 0px 12px;
                text-align: left;
                vertical-align: top;
            }
            
            input
            {
                width: 386px;
                height: 32px;
            }
            
            input[type=text]
            {
                padding-left: 8px;
            }
            
            textarea
            {
                width: 512px;
                height: 192px;
                padding: 6px 8px;
            }
            
            .admin-align-right
            {
                text-align: right;
            }
            
            .admin-align-left
            {
                text-align: left;
            }
            
            .admin-position-middle
            {
                padding-top: 18px;
            }
            
        </style>
        
        <script type="text/javascript">

            $(document).ready(function() {
                $('#admin-output').focus();
                $('#admin-output').select();
            });

        </script>
        
    </head>
    
    <body<?php if ($output != '') { ?> onload="select_output()"<?php } ?>>
        
        <form method="POST" action="admin.php" name="admin">
            
            <input type="hidden" name="data" value="1" />
            
            <table>
                
                <tr>
                    <td class="admin-align-right admin-position-middle">name:</td>
                    <td><input type="text" name="name" tabindex="1" /></td>
                    <?php if ($output != '') { ?>
                    <td rowspan="3" class="admin-align-right admin-position-middle">output:</td>
                    <td rowspan="3"><textarea id="admin-output" tabindex="5" class="misc-max-height"><?php echo $output; ?></textarea></td>
                    <?php } ?>
                </tr>
                
                <tr>
                    <td class="admin-align-right admin-position-middle">subname:</td>
                    <td><input type="text" name="subname" tabindex="2" value="<?php echo (isset($_POST['admin']) ?  $_POST['subname'] : ''); ?>" /></td>
                </tr>
                
                <tr>
                    <td class="admin-align-right admin-position-middle">content:</td>
                    <td><textarea name="content" tabindex="3"><?php echo (isset($_POST['admin']) ?  $_POST['content'] : ''); ?></textarea></td>
                </tr>
                
                <tr>
                    <td class="admin-align-right admin-position-middle">submit:</td>
                    <td><input type="submit" value="submit" tabindex="4" /></td>
                </tr>
                
            </table>
            
        </form>
        
        <form method="POST" action="admin.php" name="script">
            
            <input type="hidden" name="run" value="1" />
            
            <table>
                
                <tr>
                    <td class="admin-align-right admin-position-middle">id:</td>
                    <td><input type="text" name="id" tabindex="2" value="<?php echo (isset($_POST['run']) ?  $_POST['id'] : ''); ?>" /></td>
                </tr>
                
                <tr>
                    <td class="admin-align-right admin-position-middle">run script:</td>
                    <td><input type="submit" value="run script" tabindex="4" /></td>
                </tr>
                
            </table>
            
        </form>
        
    </body>
    
</html>
    