								</table>
							
							</td>
							<!--<td id="content-right">
                                
                                <table class="misc-max-width">
                                
                                	<?php if (false) { ?>
                                    <tr>
                                        <td class="content-right-title">Здесь пока ничего</td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="content-right-title">haxball.lv</td>
                                    </tr>
                                    
                                    <tr>
                                        <td class="content-right-content" style="padding: 0px; line-height: 20px; padding: 12px 16px; text-align: justify">
                                            In suscipit, mauris feugiat egestas imperdiet, purus libero laoreet lorem, id facilisis risus purus sed odio. Nulla varius sapien dapibus nunc rhoncus at ultricies leo dictum. Cras augue neque, molestie nec aliquet id, dignissim eu libero. Vestibulum in lectus eget metus vestibulum ultricies a eu metus. Ut porta faucibus orci in faucibus. Proin pulvinar justo ut mi viverra nec egestas enim posuere. Nunc dapibus dictum nibh vel aliquam.
                                            <div style="display: block; line-height: 16px">
                                                <img src="test.png" style="display: block; float: right; margin-right: -16px; margin-bottom: -12px" onmouseover="this.src='test2.png'" onmouseout="this.src='test.png'" />
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                    
                                </table>
                                
                            </td>-->
                        </tr>
                        
                    </table>
                    
                </td>
            </tr>
            
            <!--<tr>
                <td>
                    <div style="width: 100%; height: 1px; background: #CCCCCC"></div>
                </td>
            </tr>-->
            
            <tr>
                <td>
                    <div style="width: 100%; height: 64px"></div>
                </td>
            </tr>

        </table>
        
        <!--<script type="text/javascript">
        	
        	var username_error = 1;
        	var email_error = 1;
        	var password_error = 1;
        
        	$('#username').change(function() {
        		if ($('#username').val() != '')
        		{
		    		$.post('ajax.php', {
		    				mode: 'username',
							username: $('#username').val()
						}, function(data) {
							username_error = 1;
							if (data.error == 0) {
								username_error = 0;
								if ($('#username').css('color') != '#009900') $('#username').animate({ color: '#009900' }, 1000);
							}
							else {
								if ($('#username').css('color') != '#CC0000') $('#username').animate({ color: '#CC0000' }, 1000);
							}
						}, 'json');
				}
				else $('#username').animate({ color: '#000000' }, 1000);
        	});
        	
        	$('#email').change(function() {
        		if ($('#email').val() != '')
        		{
		    		$.post('ajax.php', {
		    				mode: 'email',
							email: $('#email').val()
						}, function(data) {
							email_error = 1;
							if (data.error == 0) {
								email_error = 0;
								if ($('#email').css('color') != '#009900') $('#email').animate({ color: '#009900' }, 1000);
							}
							else {
								if ($('#email').css('color') != '#CC0000') $('#email').animate({ color: '#CC0000' }, 1000);
							}
						}, 'json');
				}
				else $('#email').animate({ color: '#000000' }, 1000);
        	});
        	
        	function password() {
        		$.post('ajax.php', {
	    				mode: 'password',
						password: $('#password').val()
					}, function(data) {
						password_error = 1;
						if (data.error == 0) {
							if ($('#password').css('color') != '#009900') $('#password').animate({ color: '#009900' }, 1000);
							if ($('#rpassword').val() != '')
							{
								if ($('#password').val() == $('#rpassword').val())
								{
									password_error = 0;
									if ($('#rpassword').css('color') != '#009900') $('#rpassword').animate({ color: '#009900' }, 1000);
								}
								else if ($('#rpassword').css('color') != '#CC0000') $('#rpassword').animate({ color: '#CC0000' }, 1000);
							}
						}
						else {
							if ($('#password').css('color') != '#CC0000') $('#password').animate({ color: '#CC0000' }, 1000);
							if ($('#rpassword').css('color') != '#CC0000') $('#rpassword').animate({ color: '#CC0000' }, 1000);
						}
					}, 'json');
        	}
        	
        	$('#password').change(password);
        	$('#rpassword').change(password);
        	
        	$('#registration').submit(function() {
        		if (username_error || email_error || password_error)
        		{
        			alert('FAIL');
        			return false;
        		}
        		return true;
        	});
        
        </script>-->

    </body>

</html>
