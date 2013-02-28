<?php foreach (home_category::$entries as $tmp) { ?>
<tr>
    <td class="content-left-title">
        <div style="position: relative">
            <?php echo $tmp['name']; ?><!--&nbsp;<a href="/news/one/<?php //echo $tmp['id'] ?>">⇢</a>-->
            <div class="content-left-title-info">posted <?php echo substr($tmp['created_on'], 0, -3); ?></div>
            <?php if (admin::$is_admin) { ?>
            <div style="display: block; font-size: 14px; color: #444444; position: absolute; top: 12px; right: 12px">
                <div id="<?php echo $tmp['id']; ?>-dialog-name" style="display: none">
                    <form action="" method="POST" id="<?php echo $tmp['id']; ?>-form-name">
                        <input type="hidden" name="data" value="set-name" />
                        <input type="hidden" name="id" value="<?php echo $tmp['id']; ?>" />
                        <table>
                            <?php
                                $tmp_translations = data::get_translations($tmp['id'], 'name');
                                $is_complete = '#00CC00';
                                foreach (core::$languages as $tmp_language) {
                                    if ($tmp_translations[$tmp_language] == '') $is_complete = '#FF0000';
                            ?>
                            <tr>
                                <td style="text-align: right; padding: 14px 12px 0px 0px; vertical-align: top"><?php echo $tmp_language; ?></td>
                                <td style="text-align: left; padding: 12px 0px 0px 12px; vertical-align: top">
                                    <input type="text" name="<?php echo $tmp_language; ?>" value="<?php echo $tmp_translations[$tmp_language]; ?>" style="width: 512px; height: 24px; padding-left: 4px" />
                                </td>
                            </tr>
                            <?php } ?>
                        </table>
                    </form>
                </div>
                <div id="<?php echo $tmp['id']; ?>-button-name">NAME <div style="color: <?php echo $is_complete; ?>; display: inline">█</div></div>
                <script type="text/javascript" style="display: none">
                    $('#<?php echo $tmp['id']; ?>-dialog-name').dialog({
                        autoOpen: false,
                        width: 'auto',
                        show: 'drop',
                        hide: 'drop',
                        title: 'Edit or translate name',
                        modal: true,
                        buttons: {
                            'OK': function() {
                                $('#<?php echo $tmp['id']; ?>-form-name').submit();
                            },
                            'Cancel': function() {
                                $(this).dialog('close');
                            }
                        }
                    });

                    $('#<?php echo $tmp['id']; ?>-button-name').button().click(function() {
                        $('#<?php echo $tmp['id']; ?>-dialog-name').dialog('open');
                        return false;
                    });
                </script>
                
                <div id="<?php echo $tmp['id']; ?>-dialog-content" style="display: none">
                    <form action="" method="POST" id="<?php echo $tmp['id']; ?>-form-content">
                        <input type="hidden" name="data" value="set-content" />
                        <input type="hidden" name="id" value="<?php echo $tmp['id']; ?>" />
                        <table>
                            <?php
                                $tmp_translations = data::get_translations($tmp['id'], 'content');
                                $is_complete = '#00CC00';
                                foreach (core::$languages as $tmp_language) {
                                    if ($tmp_translations[$tmp_language] == '') $is_complete = '#FF0000';
                            ?>
                            <tr>
                                <td style="text-align: right; padding: 14px 12px 0px 0px; vertical-align: top"><?php echo $tmp_language; ?></td>
                                <td style="text-align: left; padding: 12px 0px 0px 12px; vertical-align: top">
                                    <textarea name="<?php echo $tmp_language; ?>" style="width: 512px; height: 192px; padding: 6px 8px"><?php echo $tmp_translations[$tmp_language]; ?></textarea>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>
                    </form>
                </div>
                <div id="<?php echo $tmp['id']; ?>-button-content">CONTENT <div style="color: <?php echo $is_complete; ?>; display: inline">█</div></div>
                <script type="text/javascript" style="display: none">
                    $('#<?php echo $tmp['id']; ?>-dialog-content').dialog({
                        autoOpen: false,
                        width: 'auto',
                        height: 512,
                        show: 'drop',
                        hide: 'drop',
                        title: 'Edit or translate content',
                        modal: true,
                        buttons: {
                            'OK': function() {
                                $('#<?php echo $tmp['id']; ?>-form-content').submit();
                            },
                            'Cancel': function() {
                                $(this).dialog('close');
                            }
                        }
                    });

                    $('#<?php echo $tmp['id']; ?>-button-content').button().click(function() {
                        $('#<?php echo $tmp['id']; ?>-dialog-content').dialog('open');
                        return false;
                    });
                </script>
                
                <div id="<?php echo $tmp['id']; ?>-dialog-url" style="display: none">
                    <form action="" method="POST" id="<?php echo $tmp['id']; ?>-form-url">
                        <input type="hidden" name="data" value="set-url" />
                        <input type="hidden" name="id" value="<?php echo $tmp['id']; ?>" />
                        <table>
                            <tr>
                                <td style="text-align: right; padding: 14px 12px 0px 0px; vertical-align: top">url name</td>
                                <td style="text-align: left; padding: 12px 0px 0px 12px; vertical-align: top">
                                    <input type="text" name="url_name" value="<?php echo $tmp['url_name']; ?>" style="width: 512px; height: 24px; padding-left: 4px" />
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div id="<?php echo $tmp['id']; ?>-button-url">URL</div>
                <script type="text/javascript" style="display: none">
                    $('#<?php echo $tmp['id']; ?>-dialog-url').dialog({
                        autoOpen: false,
                        width: 'auto',
                        show: 'drop',
                        hide: 'drop',
                        title: 'New url name',
                        modal: true,
                        buttons: {
                            'OK': function() {
                                $('#<?php echo $tmp['id']; ?>-form-url').submit();
                            },
                            'Cancel': function() {
                                $(this).dialog('close');
                            }
                        }
                    });

                    $('#<?php echo $tmp['id']; ?>-button-url').button().click(function() {
                        $('#<?php echo $tmp['id']; ?>-dialog-url').dialog('open');
                        return false;
                    });
                </script>
                
                <div id="<?php echo $tmp['id']; ?>-dialog-delete" style="display: none">
                    <form action="" method="POST" id="<?php echo $tmp['id']; ?>-form-delete">
                        <input type="hidden" name="data" value="delete-entry" />
                        <input type="hidden" name="id" value="<?php echo $tmp['id']; ?>" />
                        <table>
                            <tr>
                                <td style="text-align: right; padding: 14px 12px 0px 0px; vertical-align: top">delete entry</td>
                                <td style="text-align: left; padding: 12px 0px 0px 12px; vertical-align: top">
                                    <input type="checkbox" id="<?php echo $tmp['id']; ?>-check-delete" style="width: 256px; height: 24px; padding-left: 4px" />
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div id="<?php echo $tmp['id']; ?>-button-delete">☓</div>
                <script type="text/javascript" style="display: none">
                    $('#<?php echo $tmp['id']; ?>-dialog-delete').dialog({
                        autoOpen: false,
                        width: 'auto',
                        show: 'drop',
                        hide: 'drop',
                        title: 'Are you sure?',
                        modal: true,
                        buttons: {
                            'OK': function() {
                                if ($('#<?php echo $tmp['id']; ?>-check-delete').is(':checked')) $('#<?php echo $tmp['id']; ?>-form-delete').submit();
                            },
                            'Cancel': function() {
                                $(this).dialog('close');
                            }
                        }
                    });

                    $('#<?php echo $tmp['id']; ?>-button-delete').button().click(function() {
                        $('#<?php echo $tmp['id']; ?>-dialog-delete').dialog('open');
                        return false;
                    });
                </script>
            </div>
            <?php } ?>
        </div>
    </td>
</tr>
<tr>
    <td class="content-left-content">
        <?php echo $tmp['content']; ?>
        <!--<div class="content-left-content-readmore"><a href="/news/one/<?php //echo $tmp['id'] ?>">read more ⇢</a></div>-->
    </td>
</tr>
<?php } ?>
<tr style="height: 16px">
    <td></td> 
</tr>
<?php foreach (admin::$entries as $tmp) { ?>
<tr style="height: 16px">
    <td></td> 
</tr>
<tr>
    <td class="content-left-title content-left-title-link">
        <div id="<?php echo $tmp['id']; ?>-dialog" style="display: none">
            <form action="" method="POST" id="<?php echo $tmp['id']; ?>-form">
                <input type="hidden" name="data" value="<?php echo $tmp['id']; ?>" />
                <table>
                    <?php foreach ($tmp['params'] as $tmp_param) { ?>
                    <tr<?php if ($tmp_param['type'] == 'hidden') { ?> style="display: none"<?php } ?>>
                        <td style="text-align: right; padding: 14px 12px 0px 0px; vertical-align: top"><?php echo $tmp_param['name']; ?></td>
                        <td style="text-align: left; padding: 12px 0px 0px 12px; vertical-align: top">
                            <?php if ($tmp_param['type'] != 'textarea') { ?>
                            <input type="<?php echo $tmp_param['type']; ?>" name="<?php echo $tmp_param['name']; ?>" value="<?php echo $tmp_param['value']; ?>" style="width: 256px; height: 24px; padding-left: 4px" />
                            <?php } else { ?>
                            <textarea name="<?php echo $tmp_param['name']; ?>" style="width: 512px; height: 192px; padding: 6px 8px"><?php echo $tmp_param['value']; ?></textarea>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </form>
        </div>
        <a class="content-left-title-link-a" href="#" id="<?php echo $tmp['id']; ?>-button">
            <div class="content-left-title-link-containter">
                <?php echo $tmp['name']; ?>
            </div>
        </a>
    </td>
</tr>
<?php } ?>
<script type="text/javascript" src="/public/js/content_left_title_link.js"></script>
