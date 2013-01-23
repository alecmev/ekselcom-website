                                    <?php $tmp = $data['item']; ?>
                                    <tr>
                                        <td class="content-left-title">
                                            <?php echo $tmp['title'] ?>
                                            <div class="content-left-title-info">by <a href="/players/one/<?php echo $tmp['username'] ?>"><?php echo $tmp['username'] ?></a>, posted <?php echo $tmp['added_on'] ?><? if ($tmp['edited_on'] != '0000-00-00 00:00:00') { ?>, last edited <?php echo $tmp['edited_on']; } ?></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-left-content">
                                            <?php echo $tmp['content'] ?>
                                        </td>
                                    </tr>
