<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  	<div class="jetcache-top-heading">
	    <div style="float:left; margin-top: 10px; width: 24px;">
	    	<!-- <img src="<?php echo $icon; ?>" style="height: 24px; margin-left: 10px; " > -->
	    </div>
		<div style="margin-left: 5px; float:left; margin-top: 12px;">
			<ins style="color: #fff;  font-weight: normal;  text-decoration: none; ">
			<?php echo strip_tags($heading_title); ?>&nbsp;<?php echo $langmark_version; ?>
			</ins>
		</div>
	    <div class="jetcache-top-copyright">
	      <div style="color: #fff; font-size: 12px; margin-top: 2px; line-height: 18px; margin-left: 9px; margin-right: 9px; overflow: hidden;"><?php echo $language_heading_dev; ?></div>
	    </div>
	</div>

<div class="page-header">
    <div class="container-fluid">
		<div id="content1" style="border: none;">
			<div style="clear: both; line-height: 1px; font-size: 1px;"></div>

			<?php if (isset($error_warning) && $error_warning) { ?>

			<div class="alert alert-danger warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
			<?php } ?>

			<?php if ($success) { ?>
			<div class="alert alert-success success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
			<?php } ?>


<div id="content" style="border: none;">

				<div style="clear: both; line-height: 1px; font-size: 1px;"></div>

				<?php if (isset($session_success)) {  unset($session_success); ?>
				<div class="success"><?php echo $language_text_success; ?></div>
				<?php } ?>


				<div class="box1">

					<div class="content">

					<?php echo $agoo_menu; ?>

                    <div id="sticky-anchor"></div>

					<div id="sticky" style="margin:5px; float:right;">
					   <a href="#" class="mbutton langmark_save"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $button_save; ?></a>
					</div>

					<div style="clear: both; line-height: 1px; font-size: 1px;"></div>
<style>

.sticky-back {
	background-color: #E1E1E1;
	box-shadow: 0 0 16px rgba(0, 0, 0, 0.3) !important;
}

#sticky.stick {
    position: fixed;
    top: 0;
    z-index: 10000;
}

</style>
<script>

function sticky_relocate() {
    var window_top = $(window).scrollTop();
    var div_top = $('#sticky-anchor').offset().top;

    if (window_top > div_top) {
        $('#sticky').addClass('stick');
        $('#sticky').addClass('sticky-back');
        $('#sticky').css( { "right" : "0px" } );

    } else {
        $('#sticky').removeClass('stick');
        $('#sticky').removeClass('sticky-back');
        $('#sticky').css( { "margin-left" : "0px" } );
    }
}

$(function () {
    div_left = $('#sticky').offset().left-60;
    $(window).scroll(sticky_relocate);
    sticky_relocate();
});
</script>


				 <?php if (isset($stores) && is_array($stores) && !empty($stores)) { ?>
                 <div style="display: flex; align-items: center; margin-top: 4px; margin-bottom: 8px;">
	                 <div>
		                 <?php echo $language->get('entry_store'); ?>&nbsp;&nbsp;
	                 </div>

		               <div class="input-group">
		               	<select class="form-control sc_select_other" id="asc_langmark_store_id" name="asc_langmark_store_id">
		                 <?php foreach ($stores as $store) { ?>
		                   <option value="<?php echo $store['store_id']; ?>" <?php if (isset($store_id) && $store_id == $store['store_id']) { ?> selected="selected" <?php } ?>><?php echo $store['url']; ?> - <?php echo $store['name']; ?></option>
		                 <?php } ?>
		              	</select>
		              </div>
	              </div>
                 <?php } ?>




<script>
    $('#asc_langmark_store_id').change(function(){
      var store_id = $(this).val();
      window.location = 'index.php?route=catalog/langmark&<?php echo $token_name; ?>=<?php echo $token; ?>&store_id=' + store_id;
    });
</script>



<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">


                 <div style="display: flex; align-items: center; margin-top: 4px; margin-bottom: 8px;">
                 <div>
					  <?php echo $entry_widget_status; ?> <?php if (SC_VERSION > 15) { ?><i class="fa fa-compass" aria-hidden="true"></i> <?php } ?><?php echo strip_tags($heading_title); ?>&nbsp;<?php echo $langmark_version; ?>&nbsp;&nbsp;
                 </div>

	               <div class="input-group">
						<select class="form-control" name="ascp_settings[langmark_widget_status]">
						    <?php if (isset($ascp_settings['langmark_widget_status']) && $ascp_settings['langmark_widget_status']) { ?>
						    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						    <option value="0"><?php echo $text_disabled; ?></option>
						    <?php } else { ?>
						    <option value="1"><?php echo $text_enabled; ?></option>
						    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
						    <?php } ?>
						</select>
	              </div>
	             </div>



<div id="tabs" class="htabs">
	<a href="#tab-options" class="lm-tab"><?php echo $tab_options; ?></a>
	<a href="#tab-other" class="lm-tab"><?php echo $tab_other; ?></a>
	<a href="#tab-ex" class="lm-tab"><?php echo $tab_ex; ?></a>
	<a href="#tab-pagination" class="lm-tab"><?php echo $tab_pagination; ?></a>
	<a href="#tab-install" class="lm-tab"> <?php echo $entry_install_update; ?></a>
</div>

<div id="tab-options">
	<table class="mynotable" style="width: 100%; margin-bottom:20px; background: white; vertical-align: center;">
	    <tr style="width: 100%;">
    		<td style="width: 100%;">
            <div id="block_multi" style="width: 100%;">
	<div id="tabs-multi" class="htabs">
	<?php
		$multi_name_row_header = 0;
		if (!empty($asc_langmark['multi'])) {
			foreach ($asc_langmark['multi'] as $multi_name => $multi) {
	?>
     <a href="#tab-multi<?php echo $multi_name_row_header; ?>" id="tab-multi-row<?php echo $multi_name_row_header; ?>" class="lm-tab">
			<?php echo $multi_name; ?>&nbsp;&nbsp;&nbsp;
			<span onclick="$('#tab-multi-row<?php echo $multi_name_row_header; ?>').remove(); $('#tab-multi<?php echo $multi_name_row_header; ?>').remove(); return false;" class="markbutton button_purple nohref"><i class="fa fa-times" aria-hidden="true"></i></span>
     </a>

	<?php
				$multi_name_row_header++;
			}
		} else {
		?>

         <?php echo $text_multi_empty; ?>

		<?php
		}
	?>
		<a href="#tab-multi-add" id="tab-multi-row-add">
			<span onclick="multi_add(); return false;" class="markbutton button_green nohref"><i class="fa fa-plus" aria-hidden="true"></i></span>
		</a>
    </div>


	<script>

    	multi_name_row = <?php echo $multi_name_row_header; ?>;

	    function tab_multi_click() {
	    	$('a[href="#tab-multi'+ (multi_name_row - 1) + '"]').click();
	    }

	    function multi_add() {

	    	html_tab = '<a href="#tab-multi' + multi_name_row + '" id="tab-multi-row' + multi_name_row + '" class="lm-tab">';
	    	html_tab += 'Region-' + multi_name_row + '&nbsp;&nbsp;&nbsp;';
	    	html_tab += '<span onclick="$(\'#tab-multi-row' + multi_name_row + '\').remove(); $(\'#tab-multi' + multi_name_row + '\').remove(); return false;" class="markbutton button_purple nohref"><i class="fa fa-times" aria-hidden="true"></i></span>';
	    	html_tab += '</a>';

            html_tab_content = '';
            html_tab_content += '';

	    	$('#tab-multi-row-add').before(html_tab);

			$.ajax({
				url: '<?php echo $url_add_multi; ?>',
				dataType: 'html',
				type: 'post',
				data: { multi_name_row: multi_name_row, store_id: '<?php echo $store_id; ?>' },
				beforeSend: function() {
					$('#tab-multi-row' + multi_name_row).append('<div id="add_multi_loading"><?php echo $language->get('text_loading_langmark'); ?></div>');
				},
				success: function(ajax_html) {

					$('#add_multi_loading').remove();
					$('#block_multi').append(ajax_html);
	    			$('#tabs-multi > a').tabs();
		            setTimeout('tab_multi_click()', 500);
				},
				error: function(ajax_html) {
					$('#tab-multi-row' + multi_name_row).append('error');
				}
			});

	    	multi_name_row++;
	    }
	</script>
	<style>
	.flex-box {
		display: flex;
		align-items: center;
		align-content: stretch;
		justify-content: space-between;
	}
	.flex-box > div {
		 width: 33.3%;
	}
	</style>

<script>
	var shortcode_num_array = new Array()
</script>

<?php
if (function_exists('modification') && file_exists(modification(DIR_TEMPLATE . 'catalog/langmark_multi.tpl')))  {
	include(modification(DIR_TEMPLATE . 'catalog/langmark_multi.tpl'));
} else {
	include(DIR_TEMPLATE . 'catalog/langmark_multi.tpl');
}
?>

            </div>
			</td>
		</tr>
	</table>

</div>


<script>
function addShortcode(add_multi_name_row, store_id, name, md5_name) {

shortcode_num = shortcode_num_array[add_multi_name_row];

html  = '				<div id="shortcode-' + store_id + '-' + md5_name + '-' + shortcode_num + '" class="flex-box" style="text-align: center;">';
html += '					<div style="text-align: center; padding: 8px; width: 45%;">';
html += '						<div class="input-group">';
html += '							<span class="input-group-addon"></span>';
html += '							<textarea class="form-control" cols="50" rows="3" name="asc_langmark_' + store_id + '[multi][' + name + '][shortcodes][' + shortcode_num + '][in]"></textarea>';
html += '		               	</div>';
html += '	                </div>';
html += '';
html += '					<div style="text-align: center; padding: 8px; width: 45%;">';
html += '						<div class="input-group">';
html += '							<span class="input-group-addon"></span>';
html += '							<textarea class="form-control" cols="50" rows="3" name="asc_langmark_' + store_id + '[multi][' + name + '][shortcodes][' + shortcode_num + '][out]"></textarea>';
html += '		               	</div>';
html += '	                </div>';
html += '';
html += '					<div style="text-align: center; padding: 8px; width: 10%;">';
html += '                    	<a onclick="$(\'#shortcode-' + store_id + '-' + md5_name + '-' + shortcode_num + '\').remove();" class="markbutton button_purple nohref"><?php echo $button_remove; ?></a>';
html += '	                </div>';
html += '                </div>';

    shortcode_num_array[add_multi_name_row]++;
	$('#shortcodes-'+ store_id + '-' + md5_name).append(html);

}


function copyShortcode(store_id, name, md5_name, store_id_0, multi_name_0, multi_name_md5_0) {

	shortcodes_0 = $('#shortcodes-' + store_id_0 + '-' + multi_name_md5_0).html();


	shortcodes_0 = shortcodes_0.split(multi_name_md5_0).join(md5_name);
	shortcodes_0 = shortcodes_0.split('[multi][' + multi_name_0 + '][shortcodes]').join('[multi][' + name + '][shortcodes]');

    $('#shortcodes-'+ store_id + '-' + md5_name).html(shortcodes_0);

}
</script>





<div id="tab-pagination">
   <table class="mynotable" style="margin-bottom:20px; background: white; vertical-align: center;">

          <tr>
              <td><?php echo $language->get('entry_pagination'); ?></td>
              <td><div class="input-group"><select class="form-control" name="asc_langmark_<?php echo $store_id ?>[pagination]">
                  <?php if (isset($asc_langmark['pagination']) && $asc_langmark['pagination']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></div></td>
            </tr>
          <tr>
              <td><?php echo $language->get('entry_remove_description_status'); ?></td>
              <td><div class="input-group"><select class="form-control" name="asc_langmark_<?php echo $store_id ?>[description_status]">
                  <?php if (isset($asc_langmark['description_status']) && $asc_langmark['description_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></div></td>
            </tr>

          <tr>
              <td><?php echo $language->get('entry_url_close_slash'); ?></td>
              <td><div class="input-group">
              <select class="form-control" name="asc_langmark_<?php echo $store_id ?>[url_close_slash]">
                  <?php if (isset($asc_langmark['url_close_slash']) && $asc_langmark['url_close_slash']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></div></td>
            </tr>

    <tr>
     <td class="left"><?php echo $language->get('entry_pagination_prefix'); ?></td>
     <td class="left">
     <div class="input-group">
     <span class="input-group-addon"></span>

      <input type="text" class="form-control" name="asc_langmark_<?php echo $store_id ?>[pagination_prefix]" value="<?php  if (isset($asc_langmark['pagination_prefix'])) echo $asc_langmark['pagination_prefix']; ?>" size="20" />
     </div>
     </td>
    </tr>

	<?php
	$multi_name_row_header = 0;
	if (!empty($asc_langmark['multi'])) {
		foreach ($asc_langmark['multi'] as $multi_name => $multi) {
	?>
	<tr>
		<td class="left">
			<?php echo $language->get('entry_title_pagination'); ?> (<?php echo $multi['name']; ?>)
		</td>
		<td>

			<div style="clear: both; margin-top:5px;">
				<div class="input-group">
					<span class="input-group-addon"></span>
					<input type="text" class="form-control" name="asc_langmark_<?php echo $store_id ?>[multi][<?php echo $multi_name; ?>][pagination_title]" value="<?php if (isset($asc_langmark['multi'][$multi_name]['pagination_title']) && $asc_langmark['multi'][$multi_name]['pagination_title'] != '') { echo $multi['pagination_title']; } else { echo ''; } ?>">
	  			</div>
			</div>

		</td>

	</tr>
   <?php } } ?>


<?php if (SC_VERSION > 15) { ?>

	  <tr>
	 	<td colspan="3" style="text-align: center; background-color: #EEE;">
         &nbsp;
		</td>
		<td></td>
	  </tr>

					<tr>
						<td class="left">
							<?php echo $language->get('entry_desc_types'); ?>
						</td>



							<td>
								<div style="float: left;">

				   <table id="desc_types" class="list">
					   <thead>
				             <tr>
				                <td class="left"><?php echo $language->get('entry_id'); ?></td>
				                <td><?php echo $language->get('entry_title_template'); ?></td>
				                <td><?php echo $language->get('entry_title_values'); ?></td>
				                <td></td>
				             </tr>

				      </thead>

				      <?php if (isset($asc_langmark['desc_type']) && !empty($asc_langmark['desc_type'])) { ?>
				      <?php foreach ($asc_langmark['desc_type'] as $desc_type_id => $desc_type) { ?>
				      <?php $desc_type_row = $desc_type_id; ?>
				      <tbody id="desc_type_row<?php echo $desc_type_row; ?>">
				          <tr>
				               <td class="left">
								<input type="text" name="asc_langmark_<?php echo $store_id ?>[desc_type][<?php echo $desc_type_id; ?>][type_id]" value="<?php if (isset($desc_type['type_id'])) echo $desc_type['type_id']; ?>" size="3">
				               </td>

								<td class="right">

									<div style="margin-bottom: 3px;">
									<input type="text" name="asc_langmark_<?php echo $store_id ?>[desc_type][<?php echo $desc_type_id; ?>][title]" value="<?php if (isset($desc_type['title'])) echo $desc_type['title']; ?>" style="width: 300px;">
									</div>

								</td>

								<td class="right">

									<div style="margin-bottom: 3px;">
										<textarea name="asc_langmark_<?php echo $store_id ?>[desc_type][<?php echo $desc_type_id; ?>][vars]" style="width: 300px;"><?php if (isset($desc_type['title'])) echo $desc_type['vars']; ?></textarea>
									</div>

								</td>

				                <td class="left"><a onclick="$('#desc_type_row<?php echo $desc_type_row; ?>').remove();" class="markbutton button_purple nohref"><?php echo $button_remove; ?></a></td>
				              </tr>

				            </tbody>

				            <?php } ?>
				            <?php } ?>
				            <tfoot>
				              <tr>
				                <td colspan="4">
				                <a onclick="addDescType();" class="markbutton nohref floatright"><?php echo $language->get('entry_add_rule'); ?></a>
				                </td>
				              </tr>
				            </tfoot>
				          </table>


								</div>
							</td>
					</tr>


<?php } ?>


    <tr>
     <td></td>
     <td></td>
    </tr>
   </table>
</div>


<div id="tab-other">
	<table class="mynotable" style="margin-bottom:20px; background: white; vertical-align: center;">

	  <tr>
	 	<td colspan="3" style="text-align: center; background-color: #EEE;">&nbsp;
		</td>
		<td></td>
	  </tr>


          <tr>
              <td><?php echo $entry_access; ?></td>
              <td>
              <div class="input-group">
              <select class="form-control" name="asc_langmark_<?php echo $store_id ?>[access]">
                  <?php if (isset($asc_langmark['access']) && $asc_langmark['access']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></div>
                </td>
            </tr>

	  <tr>
	 	<td colspan="3" style="text-align: center; background-color: #EEE;">&nbsp;
		</td>
		<td></td>
	  </tr>
      <tr>
	      <td><?php echo $language->get('entry_hreflang_status'); ?></td>
          	<td>
            	<div class="input-group">
	            	<select class="form-control" name="asc_langmark_<?php echo $store_id ?>[hreflang_status]">
	                  <?php if (isset($asc_langmark['hreflang_status']) && $asc_langmark['hreflang_status']) { ?>
	                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
	                  <option value="0"><?php echo $text_disabled; ?></option>
	                  <?php } else { ?>
	                  <option value="1"><?php echo $text_enabled; ?></option>
	                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
	                  <?php } ?>
	                </select>
        	</div>
      	</td>
      </tr>


	  <tr>
	 	<td colspan="3" style="text-align: center; background-color: #EEE;">&nbsp;
		</td>
		<td></td>
	  </tr>


          <tr>
              <td><?php echo $language->get('entry_currency_switch'); ?></td>
              <td>
              <div class="input-group"><select class="form-control" name="asc_langmark_<?php echo $store_id ?>[currency_switch]">
                  <?php if (isset($asc_langmark['currency_switch']) && $asc_langmark['currency_switch']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></div>
                </td>
            </tr>




	  <tr>
	 	<td colspan="3" style="text-align: center; background-color: #EEE;">&nbsp;
		</td>
		<td></td>
	  </tr>



          <tr>
              <td><?php echo $language->get('entry_use_link_status'); ?></td>
              <td>
              <div class="input-group"><select class="form-control" name="asc_langmark_<?php echo $store_id ?>[use_link_status]">
                  <?php if (isset($asc_langmark['use_link_status']) && $asc_langmark['use_link_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></div>
                </td>
            </tr>


	  <tr>
	 	<td colspan="3" style="text-align: center; background-color: #EEE;">&nbsp;
		</td>
		<td></td>
	  </tr>

          <tr>
              <td><?php echo $language->get('entry_commonhome_status'); ?></td>
              <td>
              <div class="input-group"><select class="form-control" name="asc_langmark_<?php echo $store_id ?>[commonhome_status]">
                  <?php if (isset($asc_langmark['commonhome_status']) && $asc_langmark['commonhome_status']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></div>
                </td>
            </tr>




	  <tr>
	 	<td colspan="3" style="text-align: center; background-color: #EEE;">&nbsp;
		</td>
		<td></td>
	  </tr>

	          <tr>
	              <td><?php echo $language->get('entry_two_status'); ?></td>
	              <td>
	              <div class="input-group"><select class="form-control" name="asc_langmark_<?php echo $store_id ?>[two_status]">
	                  <?php if (isset($asc_langmark['two_status']) && $asc_langmark['two_status']) { ?>
	                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
	                  <option value="0"><?php echo $text_disabled; ?></option>
	                  <?php } else { ?>
	                  <option value="1"><?php echo $text_enabled; ?></option>
	                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
	                  <?php } ?>
	                </select></div>
	                </td>
	            </tr>







	</table>
</div>

<div id="tab-ex">
   <table class="mynotable" style="margin-bottom:20px; background: white; vertical-align: center;">

	  <tr>
	 	<td colspan="3" style="text-align: center; background-color: #EEE;">
         <?php echo $language->get('entry_ex_multilang'); ?>  <span class="table-help-href">?</span>
		</td>
		<td></td>
	  </tr>

	<tr>
		<td class="left">
			<?php echo $language->get('entry_ex_multilang_route'); ?>
		</td>
		<td>
			<div style="float: left;">
				<div class="input-group">
				<span class="input-group-addon"></span>
					<textarea class="form-control" cols="50" rows="3" name="asc_langmark_<?php echo $store_id ?>[ex_multilang_route]"><?php if (isset($asc_langmark['ex_multilang_route'])) { echo $asc_langmark['ex_multilang_route']; } else { echo ''; } ?></textarea>
				</div>
			</div>
		</td>
	</tr>

	<tr>
		<td class="left">
			<?php echo $language->get('entry_ex_multilang_uri'); ?>
		</td>
		<td>
			<div style="float: left;">
				<div class="input-group">
				<span class="input-group-addon"></span>
				<textarea class="form-control" cols="50" rows="3" name="asc_langmark_<?php echo $store_id ?>[ex_multilang_uri]"><?php if (isset($asc_langmark['ex_multilang_uri'])) { echo $asc_langmark['ex_multilang_uri']; } else { echo ''; } ?></textarea>
			</div>
			</div>
		</td>
	</tr>

	  <tr>
	 	<td colspan="3" style="text-align: center; background-color: #EEE;">
         <?php echo $language->get('entry_ex_url'); ?> <span class="table-help-href">?</span>
		</td>
		<td></td>
	  </tr>

	<tr>
		<td class="left">
			<?php echo $language->get('entry_ex_url_route'); ?>
		</td>
		<td>
			<div style="float: left;">
				<div class="input-group">
				<span class="input-group-addon"></span>
				<textarea class="form-control" cols="50" rows="3" name="asc_langmark_<?php echo $store_id ?>[ex_url_route]"><?php if (isset($asc_langmark['ex_url_route'])) { echo $asc_langmark['ex_url_route']; } else { echo ''; } ?></textarea>
			</div>
			</div>
		</td>
	</tr>

	<tr>
		<td class="left">
			<?php echo $language->get('entry_ex_url_uri'); ?>
		</td>
		<td>
			<div style="float: left;">
				<div class="input-group">
				<span class="input-group-addon"></span>
				<textarea class="form-control" cols="50" rows="3" name="asc_langmark_<?php echo $store_id ?>[ex_url_uri]"><?php if (isset($asc_langmark['ex_url_uri'])) { echo $asc_langmark['ex_url_uri']; } else { echo ''; } ?></textarea>
			</div>
			</div>
		</td>
	</tr>

 <tr>
     <td></td>
     <td></td>
    </tr>
   </table>
</div>




<div id="tab-install">
 <div id="create_tables"></div>
    <a href="#" onclick="
		$.ajax({
			url: '<?php echo $url_create; ?>',
			dataType: 'html',
			beforeSend: function()
				{
                     $('#create_tables').html('<?php echo $language->get('text_loading_small'); ?>');
				},

			success: function(json) {
				$('#create_tables').html(json);
				setTimeout('delayer()', 2000);
			},
			error: function(json) {
			$('#create_tables').html('error');
			}
		}); return false;" class="markbuttono" style=""><?php echo $url_create_text; ?></a>


   <a href="#" onclick="
		$.ajax({
			url: '<?php echo $url_delete; ?>',
			dataType: 'html',
			beforeSend: function()
					{
                     $('#create_tables').html('<?php echo $language->get('text_loading_small'); ?>');
					},
			success: function(json) {
				$('#create_tables').html(json);
				//setTimeout('delayer()', 2000);
			},
			error: function(json) {
			$('#create_tables').html('error');
			}
		}); return false;" class="mbuttonr" style=""><?php echo $url_delete_text; ?></a>


<?php if ($store_id != 0) { ?>

   <a href="#" onclick="
		$.ajax({
			url: '<?php echo $url_store_id_related; ?>',
			dataType: 'html',
			beforeSend: function()
					{
                     $('#create_tables').html('<?php echo $language->get('text_loading_small'); ?>');
					},
			success: function(json) {
				$('#create_tables').html(json);
			},
			error: function(json) {
			$('#create_tables').html('error');
			}
		}); return false;" class="mbuttonr" style=""><?php echo $url_store_id_repated_text; ?></a>


<?php } ?>


<?php if (isset($text_update) && $text_update != '' ) { ?>
<div style="font-size: 18px; color: red;"><?php echo $text_update; ?></div>
<?php } ?>

</div>



 </form>
</div>

<?php if (SC_VERSION > 15) { ?>
<script type="text/javascript">

var array_desc_type_row = Array();
array_desc_type_row.push(0);
<?php
 foreach ($asc_langmark['desc_type'] as $indx => $desc_type) {
?>
array_desc_type_row.push(<?php echo $indx; ?>);
<?php
}
?>

var desc_type_row = <?php echo $desc_type_row + 1; ?>;

function addDescType() {

	var aindex = -1;
	for(i = 0; i < array_desc_type_row.length; i++) {
	 flg = jQuery.inArray(i, array_desc_type_row);
	 if (flg == -1) {
	  aindex = i;
	 }
	}
	if (aindex == -1) {
	  aindex = array_desc_type_row.length;
	}
	desc_type_row = aindex;
	array_desc_type_row.push(aindex);

    html  = '<tbody id="desc_type_row' + desc_type_row + '">';
	html += '  <tr>';
    html += '  <td class="left">';
	html += ' 	<input type="text" name="asc_langmark_<?php echo $store_id ?>[desc_type]['+ desc_type_row +'][type_id]" value="'+ desc_type_row +'" size="3">';
    html += '  </td>';

 	html += '  <td class="right">';


	html += '	<div style="margin-bottom: 3px;">';
	html += '		<input type="text" name="asc_langmark_<?php echo $store_id ?>[desc_type]['+ desc_type_row +'][title]" value="" style="width: 300px;">';
	html += '	</div>';
    html += '  </td>';

	html += ' <td class="right">';

	html += ' <div style="margin-bottom: 3px;">';
	html += ' <textarea name="asc_langmark_<?php echo $store_id ?>[desc_type]['+ desc_type_row +'][vars]" style="width: 300px;">description</textarea>';
	html += ' </div>';

	html += ' </td>';

    html += '  <td class="left"><a onclick="$(\'#desc_type_row'+desc_type_row+'\').remove(); array_desc_type_row.remove(desc_type_row);" class="markbutton button_purple nohref"><?php echo $button_remove; ?></a></td>';

	html += '  </tr>';
	html += '</tbody>';

	$('#desc_types tfoot').before(html);

	desc_type_row++;
}
</script>
<?php } ?>

<script>
	 form_submit = function() {
		$('#form').submit();
		return false;
	}
	$('.langmark_save').bind('click', form_submit);
</script>

<script>
$('#tabs a').tabs();
</script>

<script>
$('#tabs-multi > a').tabs();
</script>

<script type="text/javascript">

function odd_even() {
	var kz = 0;
	$('table tr').each(function(i,elem) {
	$(this).removeClass('odd');
	$(this).removeClass('even');
		if ($(this).is(':visible')) {
			kz++;
			if (kz % 2 == 0) {
				$(this).addClass('odd');
			}
		}
	});
}

$(document).ready(function(){
	odd_even();

	$('.htabs a').click(function() {
		odd_even();
	});

	$('.vtabs a').click(function() {
		odd_even();
	});

});

function input_select_change() {

	$('input').each(function(){
		if (!$(this).hasClass('no_change')) {
	        $(this).removeClass('sc_select_enable');
	        $(this).removeClass('sc_select_disable');

			if ( $(this).val() != '' ) {
				$(this).addClass('sc_select_enable');
			} else {
				$(this).addClass('sc_select_disable');
			}
		}
	});

	$('select').each(function(){
		if (!$(this).hasClass('no_change')) {
	        $(this).removeClass('sc_select_enable');
	        $(this).removeClass('sc_select_disable');

			this_val = $(this).find('option:selected').val()

			if (this_val == '1' ) {
				$(this).addClass('sc_select_enable');
			}

			if (this_val == '0' || this_val == '') {
				$(this).addClass('sc_select_disable');
			}

			if (this_val != '0' && this_val != '1' && this_val != '') {
				$(this).addClass('sc_select_other');
			}
		}
	});
}


$('.table-help-href').on('click', function() {
	$('.help').toggle();
});


$(document).ready(function(){
	$('.help').hide();

	input_select_change();

	$( "select" )
	  .change(function () {
		input_select_change();

	  });

	$( "input" )
	  .blur(function () {
		input_select_change();
	  });


});
</script>

<script>
function delayer(){
    window.location = 'index.php?route=catalog/langmark&<?php echo $token_name; ?>=<?php echo $token; ?>&store_id=<?php echo $store_id; ?>';
}
</script>

<script>
	string_lm_tabs_click = localStorage.getItem('lm_tabs_click');

	if (string_lm_tabs_click == null) {
		var array_lm_tabs_click = [];
	} else {
		var array_lm_tabs_click = JSON.parse(string_lm_tabs_click);

		array_lm_tabs_click.forEach(function(item, index, array) {
			$('a[href="'+ item + '"]').click();
			console.log(item);
		});
	}

	$('.lm-tab').on('click', function() {
		lm_tab_href = $(this).attr('href');
        array_lm_tabs_click.push(lm_tab_href);
        if (array_lm_tabs_click.length > 3) {
        	array_lm_tabs_click.shift();
        }
        localStorage.setItem('lm_tabs_click', JSON.stringify(array_lm_tabs_click));
	});
</script>

</div>
</div>
<?php echo $footer; ?>
