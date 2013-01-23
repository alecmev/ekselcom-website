									<?php foreach ($data['items'] as $tmp) { ?>
                                    <tr>
                                        <td class="content-left-title">
                                            <?php echo $tmp['title'] ?>&nbsp;<a href="/news/one/<?php echo $tmp['id'] ?>">⇢</a>
                                            <div class="content-left-title-info">by <a href="/players/one/<?php echo $tmp['username'] ?>"><?php echo $tmp['username'] ?></a>, posted <?php echo $tmp['added_on'] ?><?php if ($tmp['edited_on'] != '0000-00-00 00:00:00') { ?>, last edited <?php echo $tmp['edited_on']; } ?></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-left-content">
                                            <?php echo $tmp['content'] ?>
                                            <div class="content-left-content-readmore"><a href="/news/one/<?php echo $tmp['id'] ?>">read more ⇢</a></div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                    	<td id="content-left-pages">
                                    		<?php for ($i = 1; $i <= $data['limit']; ++$i) { ?>
				                            <div id="content-left-pages-item"<?php if ($i == $request[1]) echo " class=\"misc-active-link\""; ?>><a href="/news/<?php echo $i ?>"><?php echo $i ?></a></div>
				                    		<?php } ?>
                                    	</td>
                                    </tr>
