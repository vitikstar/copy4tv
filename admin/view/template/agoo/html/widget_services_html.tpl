									<div id="mytabs_cache">
									<div class="tabcontent" id="list_default">

										<table class="mynotable" style="margin-bottom:20px; background: white; vertical-align: center;">
					  					          <tr>
										              <td style="width: 220px;"><?php echo $language->get('entry_httpsfix_ocmod_refresh'); ?></td>
										              <td>
															<div style="margin-bottom: 5px;">
															    <a href="#" id="httpsfix_ocmod_refresh" onclick="
																	$.ajax({
																		url: '<?php echo $url_ocmod_refresh; ?>',
																		dataType: 'html',
																		beforeSend: function()
																		{
															               $('#div_ocmod_refresh').html('<?php echo $language->get('text_loading_main'); ?>');
																		},
																		success: function(content) {
																			if (content) {
																				$('#div_ocmod_refresh').html('<span style=\'color:green\'><?php echo $language->get('text_ocmod_refresh_success'); ?><\/span>');
																				//setTimeout('delayer()', 2000);
																			}
																		},
																		error: function(content) {
																			$('#div_ocmod_refresh').html('<span style=\'color:red\'><?php echo $language->get('text_ocmod_refresh_fail'); ?><\/span>');
																		}
																	}); return false;" class="markbuttono sc_button" style=""><?php echo $language->get('text_url_ocmod_refresh'); ?></a>
															<div id="div_ocmod_refresh"></div>
															</div>
										                </td>
										            </tr>

					  					          <tr>
										              <td style="width: 220px;"><?php echo $language->get('entry_httpsfix_cache_remove'); ?></td>
										              <td>
															<div style="margin-bottom: 5px;">
															    <a href="#" id="httpsfix_cache_remove" onclick="
																	$.ajax({
																		url: '<?php echo $url_cache_remove; ?>',
																		dataType: 'html',
																		beforeSend: function()
																		{
															               $('#div_cache_remove').html('<?php echo $language->get('text_loading_main'); ?>');
																		},
																		success: function(content) {
																			if (content) {
																				$('#div_cache_remove').html('<span style=\'color:green\'>'+content+'<\/span>');
																				//setTimeout('delayer()', 2000);
																			}
																		},
																		error: function(content) {
																			$('#div_cache_remove').html('<span style=\'color:red\'><?php echo $language->get('text_cache_remove_fail'); ?><\/span>');
																		}
																	}); return false;" class="markbuttono sc_button" style=""><?php echo $language->get('text_url_cache_remove'); ?></a>
															<div id="div_cache_remove"></div>
															</div>
										                </td>
										            </tr>

					  					          <tr>
										              <td style="width: 220px;"><?php echo $language->get('entry_httpsfix_cache_image_remove'); ?></td>
										              <td>
															<div style="margin-bottom: 5px;">
															    <a href="#" id="httpsfix_cache_image_remove" onclick="
																	$.ajax({
																		url: '<?php echo $url_cache_image_remove; ?>',
																		dataType: 'html',
																		beforeSend: function()
																		{
															               $('#div_cache_image_remove').html('<?php echo $language->get('text_loading_main'); ?>');
																		},
																		success: function(content) {
																			if (content) {
																				$('#div_cache_image_remove').html('<span style=\'color:green\'>'+content+'<\/span>');
																				//setTimeout('delayer()', 2000);
																			}
																		},
																		error: function(content) {
																			$('#div_cache_image_remove').html('<span style=\'color:red\'><?php echo $language->get('text_cache_remove_fail'); ?><\/span>');
																		}
																	}); return false;" class="markbuttono sc_button" style=""><?php echo $language->get('text_url_cache_image_remove'); ?></a>
															<div id="div_cache_image_remove"></div>
															</div>
										                </td>
										            </tr>

										   </table>

									</div>
									</div>

