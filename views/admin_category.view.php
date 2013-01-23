<?php if (admin::$is_admin) { ?>
<tr style="height: 16px">
    <td></td> 
</tr>
<tr>
    <td class="content-left-title content-left-title-link">
        <div id="dialog-image" style="display: none">
            <form action="" method="POST" id="form-image" enctype="multipart/form-data">
                <input type="hidden" name="data" value="upload-image" />
                <table>
                    <tr>
                        <td style="text-align: right; padding: 14px 12px 0px 0px; vertical-align: top">image</td>
                        <td style="text-align: left; padding: 12px 0px 0px 12px; vertical-align: top">
                            <input type="file" name="image" style="width: 512px; height: 24px; padding-left: 4px" />
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <a class="content-left-title-link-a" href="#" id="button-image">
            <div class="content-left-title-link-containter">
                Upload image
            </div>
        </a>
        <script type="text/javascript" style="display: none">
            $('#dialog-image').dialog({
                autoOpen: false,
                width: 'auto',
                show: 'drop',
                hide: 'drop',
                title: 'Upload image',
                modal: true,
                buttons: {
                    'OK': function() {
                        $('#form-image').submit();
                    },
                    'Cancel': function() {
                        $(this).dialog('close');
                    }
                }
            });

            $('#button-image').click(function() {
                $('#dialog-image').dialog('open');
                return false;
            });
        </script>
    </td>
</tr>
<?php if (admin::$image !== false) { ?>
<tr style="height: 16px">
    <td></td> 
</tr>
<tr>
    <td class="content-left-title content-left-title-link">
        <div id="dialog-image-last" style="display: none">
            <table>
                <tr>
                    <td style="text-align: right; padding: 14px 12px 0px 0px; vertical-align: top">image</td>
                    <td style="text-align: left; padding: 12px 0px 0px 12px; vertical-align: top">
                        <input type="text" value="[img=<?php echo admin::$image; ?>]" style="width: 512px; height: 24px; padding-left: 4px" />
                    </td>
                </tr>
            </table>
        </div>
        <a class="content-left-title-link-a" href="#" id="button-image-last">
            <div class="content-left-title-link-containter">
                Last uploaded image
            </div>
        </a>
        <script type="text/javascript" style="display: none">
            $('#dialog-image-last').dialog({
                autoOpen: false,
                width: 'auto',
                show: 'drop',
                hide: 'drop',
                title: 'Upload image',
                modal: true,
                buttons: {
                    'OK': function() {
                        $(this).dialog('close');
                    }
                }
            });

            $('#button-image-last').click(function() {
                $('#dialog-image-last').dialog('open');
                return false;
            });
            
            $('#dialog-image-last').dialog('open');
        </script>
    </td>
</tr>
<?php } } foreach (admin::$entries as $tmp) { ?>
<tr style="height: 16px">
    <td></td> 
</tr>
<tr>
    <td class="content-left-title content-left-title-link">
        <div id="<?php echo $tmp['id']; ?>-dialog" style="display: none">
            <form action="" method="POST" id="<?php echo $tmp['id']; ?>-form">
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
