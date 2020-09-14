
	<?php

	if (!isset($multi_name_row)) {
    	$multi_name_row = 0;
	}
	if (!empty($asc_langmark['multi'])) {
		foreach ($asc_langmark['multi'] as $multi_name => $multi) {
	?>
	<div id="tab-multi<?php echo $multi_name_row; ?>">
      <table id="multi_name_row<?php echo $multi_name_row; ?>" style="width: 100%;">
	  <tr>
	 	<td colspan="3" style="text-align: center; background-color: #EEE;">

			<div class="flex-box">
				  <div>
				   &nbsp;
				  </div>

				  <div>
                   <span class="markbutton button_blue nohref" style="font-size: 16px;"><?php echo $multi_name; ?></span>
				  </div>

				  <div  style="text-align: right;">
			<a onclick="$('#tab-multi-row<?php echo $multi_name_row; ?>').remove(); $('#tab-multi<?php echo $multi_name_row; ?>').remove(); return false;" class="markbutton button_purple nohref">
				<?php echo $button_remove; ?>
				<span onclick="$('#tab-multi-row<?php echo $multi_name_row; ?>').remove(); $('#tab-multi<?php echo $multi_name_row; ?>').remove(); return false;" class="markbutton button_purple nohref"><i class="fa fa-times" aria-hidden="true"></i></span>
			</a>

			<a onclick="multi_add(); return false;" id="tab-multi-row-add-<?php echo $multi_name_row; ?>" class="markbutton button_green nohref">
			<?php echo $entry_add; ?>
			<span onclick="multi_add(); return false;" class="markbutton button_green nohref"><i class="fa fa-plus" aria-hidden="true"></i></span>
			</a>
				  </div>
            </div>


		</td>

	  </tr>

	<tr>
		<td>
			<div style="clear: both; margin-top:5px;">
				<div>
				<?php echo $entry_name; ?>
				</div>

				<div class="input-group">
					<span class="input-group-addon"></span>
						<input type="text" class="form-control" name="asc_langmark_<?php echo $store_id ?>[multi][<?php echo $multi_name; ?>][name]" value="<?php if (isset($asc_langmark['multi'][$multi_name]['name'])) { echo $multi_name; } else { echo ''; } ?>">
				</div>
			</div>

			<div style="clear: both; margin-top:5px;">
				<div>
				<?php echo $entry_prefix; ?>
				</div>
				<div class="input-group">
					<span class="input-group-addon"></span>
						<input type="text" class="form-control" name="asc_langmark_<?php echo $store_id ?>[multi][<?php echo $multi_name; ?>][prefix]" value="<?php if (isset($asc_langmark['multi'][$multi_name]['prefix']) && $asc_langmark['multi'][$multi_name]['prefix'] != '') { echo $multi['prefix']; } else { echo ''; } ?>">
  					</div>
			</div>

			<div style="clear: both; margin-top:10px; width: 200px;">
				<div>
				<?php echo $entry_hreflang; ?>
				</div>
				<div class="input-group">
					<span class="input-group-addon"></span>
					<input type="text" class="form-control" name="asc_langmark_<?php echo $store_id ?>[multi][<?php echo $multi_name; ?>][hreflang]" value="<?php if (isset($asc_langmark['multi'][$multi_name]['hreflang']) && $asc_langmark['multi'][$multi_name]['hreflang'] != '') { echo $multi['hreflang']; } else { echo ''; } ?>">
               	</div>
			</div>

            <div style="clear: both; margin-top:10px;">
				 <?php if (isset($languages) && is_array($languages) && !empty($languages)) { ?>
                 <div style="clear: both; margin-top:10px;">
                 <?php echo $entry_languages; ?>
                 </div>

	               <div class="input-group">
	               <select class="form-control" name="asc_langmark_<?php echo $store_id ?>[multi][<?php echo $multi_name; ?>][language_id]">
	                 <?php foreach ($languages as $num => $lang) { ?>
	                   <option value="<?php echo $lang['language_id']; ?>" <?php if (isset($asc_langmark['multi'][$multi_name]['language_id']) && $asc_langmark['multi'][$multi_name]['language_id'] == $lang['language_id']) { ?> selected="selected" <?php } ?>><?php echo $lang['language_id']; ?>&nbsp;>&nbsp;<?php echo $lang['name']; ?></option>
	                  <?php } ?>
	                 </select>
	                 </div>
                 <?php } ?>
			</div>

			<div style="clear: both; margin-top:10px;">
				 <?php if (isset($currencies) && is_array($currencies) && !empty($currencies)) { ?>
                 <div>
                 <?php echo $language->get('entry_currencies'); ?>
                 </div>

	               <div class="input-group">
	               <select class="form-control" name="asc_langmark_<?php echo $store_id ?>[multi][<?php echo $multi_name; ?>][currency]">
	                 <option value=""></option>
	                 <?php foreach ($currencies as $num => $currency) { ?>
	                   <option value="<?php echo $currency['code']; ?>" <?php if (isset($asc_langmark['multi'][$multi_name]['currency']) && $asc_langmark['multi'][$multi_name]['currency'] == $currency['code']) { ?> selected="selected" <?php } ?>><?php echo $currency['title']; ?></option>
	                  <?php } ?>
	                 </select>
	                 </div>
                 <?php } ?>
			</div>


            <div style="clear: both; margin-top:10px;">
				 <?php if (isset($stores) && is_array($stores) && !empty($stores)) { ?>
                 <div style="clear: both; margin-top:10px;">
                 <?php echo $entry_store_id_related; ?>
                 </div>

	               <div class="input-group">
	               <select class="form-control sc_select_other" name="asc_langmark_<?php echo $store_id ?>[multi][<?php echo $multi_name; ?>][store_id]">
	                 <?php foreach ($stores as $num => $store) { ?>
	                   <?php
						if (!isset($asc_langmark['multi'][$multi_name]['store_id'])) {
	                   		$asc_langmark['multi'][$multi_name]['store_id'] = $store_id;
	                   	}
	                   ?>
	                   <option value="<?php echo $store['store_id']; ?>" <?php if (isset($asc_langmark['multi'][$multi_name]['store_id']) &&  $asc_langmark['multi'][$multi_name]['store_id'] == $store['store_id']) { ?> selected="selected" <?php } ?>><?php echo $store['store_id']; ?>&nbsp;>&nbsp;<?php echo $store['url']; ?>&nbsp;>&nbsp;<?php echo $store['name']; ?></option>
	                  <?php } ?>
	                 </select>
	                 </div>
                 <?php } ?>
			</div>



			<div style="clear: both; margin-top:10px;">
				<div>
				<?php echo $entry_prefix_switcher; ?>
				</div>
				<div class="input-group">
				<select class="form-control" name="asc_langmark_<?php echo $store_id ?>[multi][<?php echo $multi_name; ?>][prefix_switcher]">
                  <?php if (isset($asc_langmark['multi'][$multi_name]['prefix_switcher']) && $asc_langmark['multi'][$multi_name]['prefix_switcher']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
                </div>
			</div>

			<div style="clear: both; margin-top:10px;">
				<div>
				<?php echo $entry_prefix_switcher_stores; ?>
				</div>
				<div class="input-group">
				<select class="form-control" name="asc_langmark_<?php echo $store_id ?>[multi][<?php echo $multi_name; ?>][prefix_switcher_stores]">
                  <?php if (isset($asc_langmark['multi'][$multi_name]['prefix_switcher_stores']) && $asc_langmark['multi'][$multi_name]['prefix_switcher_stores']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
                </div>
			</div>





			<div style="clear: both; margin-top:10px;">
				<div>
				<?php echo $entry_hreflang_switcher; ?>
				</div>
				<div class="input-group">
				<select class="form-control" name="asc_langmark_<?php echo $store_id ?>[multi][<?php echo $multi_name; ?>][hreflang_switcher]">
                  <?php if (isset($asc_langmark['multi'][$multi_name]['hreflang_switcher']) && $asc_langmark['multi'][$multi_name]['hreflang_switcher']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
                </div>
			</div>



			<div class="flex-box" style="text-align: center; background-color: #EEE; padding: 8px; margin-top: 16px;">
				  <div>
				   &nbsp;
				  </div>

				  <div>
                   <span class="markbutton button_blue nohref" style="font-size: 16px;"><?php echo $tab_main; ?></span>
				  </div>

				  <div>
				   &nbsp;
				  </div>


            </div>




			<div style="clear: both; margin-top:10px;">
				<div style="width: 100%">
				<?php echo $entry_main_prefix_status; ?>
				</div>
				<div class="input-group">
				<select class="form-control" name="asc_langmark_<?php echo $store_id ?>[multi][<?php echo $multi_name; ?>][main_prefix_status]">
                  <?php if (isset($asc_langmark['multi'][$multi_name]['main_prefix_status']) && $asc_langmark['multi'][$multi_name]['main_prefix_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
                </div>
			</div>



			<div style="clear: both; margin-top:10px;">
				<div>
				<?php echo $entry_main_title; ?>
				</div>
				<div class="input-group">
					<span class="input-group-addon"></span>
					<textarea class="form-control" cols="50" rows="3" name="asc_langmark_<?php echo $store_id ?>[multi][<?php echo $multi_name; ?>][main_title]"><?php if (isset($asc_langmark['multi'][$multi_name]['main_title']) && $asc_langmark['multi'][$multi_name]['main_title'] != '') { echo $multi['main_title']; } else { echo ''; } ?></textarea>
               	</div>
			</div>

			<div style="clear: both; margin-top:10px;">
				<div>
				<?php echo $entry_main_description; ?>
				</div>
				<div class="input-group">
					<span class="input-group-addon"></span>
					<textarea class="form-control" cols="50" rows="3" name="asc_langmark_<?php echo $store_id ?>[multi][<?php echo $multi_name; ?>][main_description]"><?php if (isset($asc_langmark['multi'][$multi_name]['main_description']) && $asc_langmark['multi'][$multi_name]['main_description'] != '') { echo $multi['main_description']; } else { echo ''; } ?></textarea>
               	</div>
			</div>

			<div style="clear: both; margin-top:10px;">
				<div>
				<?php echo $entry_main_keywords; ?>
				</div>
				<div class="input-group">
					<span class="input-group-addon"></span>
					<textarea class="form-control" cols="50" rows="3" name="asc_langmark_<?php echo $store_id ?>[multi][<?php echo $multi_name; ?>][main_keywords]"><?php if (isset($asc_langmark['multi'][$multi_name]['main_keywords']) && $asc_langmark['multi'][$multi_name]['main_keywords'] != '') { echo $multi['main_keywords']; } else { echo ''; } ?></textarea>
               	</div>
			</div>

			<div class="flex-box" style="text-align: center; background-color: #EEE; padding: 8px; margin-top: 16px;">
				  <div>
				   &nbsp;
				  </div>

				  <div>
                   <span class="markbutton button_blue nohref" style="font-size: 16px;"><?php echo $entry_shortcodes; ?></span>
				  </div>

				  <div>
				   &nbsp;
				  </div>


            </div>

				<div class="flex-box" style="margin-top: 6px; text-align: center; font-weight: 500; color: #000; background-color: #EFEFEF;">
					<div style="text-align: center; width: 45%;">
                    	<?php echo $text_shortcodes_in; ?>
	                </div>

					<div style="text-align: center; width: 45%;">
                    	<?php echo $text_shortcodes_out; ?>
	                </div>

					<div style="text-align: center; width: 10%;">
	                	<?php echo $text_shortcodes_action; ?>
	                </div>
                </div>

			<div id="shortcodes-<?php echo $store_id ?>-<?php echo md5($multi_name); ?>" style="clear: both; width: 100%">
              <?php
                $shortcode_num[$multi_name_row] = 0;
                if (!empty($multi['shortcodes'])) {
                	foreach ($multi['shortcodes'] as $sc_num => $shortcode) {
              ?>
				<div id="shortcode-<?php echo $store_id ?>-<?php echo md5($multi_name); ?>-<?php echo $shortcode_num[$multi_name_row]; ?>" class="flex-box" style="text-align: center;">
					<div style="text-align: center; padding: 8px; width: 45%;">
						<div class="input-group">
							<span class="input-group-addon"></span>
							<textarea class="form-control" cols="50" rows="3" name="asc_langmark_<?php echo $store_id ?>[multi][<?php echo $multi_name; ?>][shortcodes][<?php echo $shortcode_num[$multi_name_row]; ?>][in]"><?php if (isset($multi['shortcodes'][$sc_num]['in']) && $multi['shortcodes'][$sc_num]['in'] != '') { echo $multi['shortcodes'][$sc_num]['in']; } else { echo ''; } ?></textarea>
		               	</div>
	                </div>

					<div style="text-align: center; padding: 8px; width: 45%;">
						<div class="input-group">
							<span class="input-group-addon"></span>
							<textarea class="form-control" cols="50" rows="3" name="asc_langmark_<?php echo $store_id ?>[multi][<?php echo $multi_name; ?>][shortcodes][<?php echo $shortcode_num[$multi_name_row]; ?>][out]"><?php if (isset($multi['shortcodes'][$sc_num]['out']) && $multi['shortcodes'][$sc_num]['out'] != '') { echo $multi['shortcodes'][$sc_num]['out']; } else { echo ''; } ?></textarea>
		               	</div>
	                </div>

					<div style="text-align: center; padding: 8px; width: 10%;">
                    	<a onclick="$('#shortcode-<?php echo $store_id ?>-<?php echo md5($multi_name); ?>-<?php echo $shortcode_num[$multi_name_row]; ?>').remove();" class="markbutton button_purple nohref"><?php echo $button_remove; ?></a>
	                </div>
                </div>

              <?php
              			$shortcode_num[$multi_name_row]++;
					}
				}
              ?>

			</div>
	<?php if ($multi_name_row == 0) { ?>
    <script>
	     store_id_0 = <?php echo (int)$store_id; ?>;
	     multi_name_0 = '<?php echo $multi_name; ?>';
	     multi_name_md5_0 = '<?php echo md5($multi_name); ?>';
    </script>
    <?php } ?>


             <div id="shortcodes-<?php echo $store_id ?>-<?php echo md5($multi_name); ?>-add" style="clear: both; text-align: center; margin-top:10px; width: 100%">
	            <a id="shortcodes-<?php echo $store_id ?>-<?php echo md5($multi_name); ?>-add-a"  onclick="addShortcode('<?php echo $multi_name_row; ?>', '<?php echo $store_id; ?>', '<?php echo $multi_name; ?>', '<?php echo md5($multi_name); ?>');" class="markbutton nohref"><?php echo $language->get('entry_add_rule'); ?></a>

				<?php if ($multi_name_row != 0) { ?>
	            <a onclick="$('#shortcodes-<?php echo $store_id ?>-<?php echo md5($multi_name); ?>-add-a').remove(); this.remove(); copyShortcode('<?php echo $store_id ?>', '<?php echo $multi_name; ?>', '<?php echo md5($multi_name); ?>', store_id_0, multi_name_0, multi_name_md5_0);" class="markbutton nohref"><?php echo $language->get('entry_copy_rules'); ?></a>
                <?php } ?>
			</div>




		</td>

	</tr>
    </table>
	</div>
	<?php

			$multi_name_row++;
		}
	} else {
	?>


	<?php
	}
	?>
<script>

<?php
	foreach ($shortcode_num as $sc_multi_name_row => $sc_count) {
?>
shortcode_num_array[<?php echo $sc_multi_name_row; ?>] = <?php echo $sc_count; ?>;
<?php
}
?>
</script>
