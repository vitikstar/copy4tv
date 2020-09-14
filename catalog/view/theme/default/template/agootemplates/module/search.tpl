<div id="content_search" class="content_search">
	<div class="content_search input-group">
  		<input type="text" name="filter_name" class="form-control input-lg seocms-input-search" value="<?php echo $text_for_search; ?>" placeholder="<?php echo $text_for_search; ?>" onclick="this.value = '';"  onkeydown="this.style.color = '000000'"/>
  		<span class="input-group-btn">
    		<button type="button" id="button-search_blog" class="btn btn-default btn-lg button"><i class="fa fa-search"></i><?php if ($SC_VERSION < 20) { echo $text_for_search; } ?></button>
  		</span>
	</div>

   	<div id="search_other" class="input-group seocms-category-search">
		<select name="filter_blog_id" class="ascp_select_search form-control">
			<option value="0"><?php echo $text_blog; ?></option>
			<?php  if (!empty($blogies)) {
						foreach ($blogies as $blog_1) {
			?>
			<?php if (isset($filter_blog_id) && $blog_1['blog_id'] == $filter_blog_id) { ?>
			<option value="<?php echo $blog_1['blog_id']; ?>" selected="selected"><?php echo $blog_1['name']; ?></option>
			<?php } else { ?>
			<option value="<?php echo $blog_1['blog_id']; ?>"><?php echo $blog_1['name']; ?></option>
			<?php } ?>
			<?php foreach ($blog_1['children'] as $blog_2) { ?>
			<?php if (isset($filter_blog_id) && $blog_2['blog_id'] == $filter_blog_id) { ?>
			<option value="<?php echo $blog_2['blog_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $blog_2['name']; ?></option>
			<?php } else { ?>
			<option value="<?php echo $blog_2['blog_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $blog_2['name']; ?></option>
			<?php } ?>
			<?php foreach ($blog_2['children'] as $blog_3) { ?>
			<?php if (isset($filter_blog_id) && $blog_3['blog_id'] == $filter_blog_id) { ?>
			<option value="<?php echo $blog_3['blog_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $blog_3['name']; ?></option>
			<?php } else { ?>
			<option value="<?php echo $blog_3['blog_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $blog_3['name']; ?></option>
			<?php } ?>
			<?php } ?>
			<?php } ?>
			<?php } ?>
			<?php } ?>
		</select>
		<div class="seocms-search-checkbox">
			<input type="checkbox" name="filter_sub_blog" value="1" id="sub_blog" checked="checked" />
			&nbsp;<label for="sub_blog"><?php echo $text_for_childcategory;?></label>
		</div>
		<div class="seocms-search-checkbox">
			<input type="checkbox" name="filter_description" value="1" id="description" checked="checked" />
			&nbsp;<label for="description"><?php echo $text_for_desc;?></label>
		</div>
	</div>
</div>


<script>
		$('#content_search input[name=\'filter_name\']').keydown(function(e) {
			if (e.keyCode == 13) {
				$('#button-search_blog').trigger('click');
			}
		});

		$('#button-search_blog').bind('click', function() {
			var url = '<?php echo $blog_search["href"]; ?>';

			var filter_name = $('#content_search input[name=\'filter_name\']').prop('value');
			if (filter_name) {
				url += '&filter_name=' + encodeURIComponent(filter_name);
			}

			var filter_blog_id = $('#content_search select[name=\'filter_blog_id\']').prop('value');
			if (filter_blog_id > 0) {
				url += '&filter_blog_id=' + encodeURIComponent(filter_blog_id);
			}

			var filter_sub_blog = $('#content_search input[name=\'filter_sub_blog\']:checked').prop('value');
			if (filter_sub_blog) {
				url += '&filter_sub_blog=true';
			}

			var filter_description = $('#content_search input[name=\'filter_description\']:checked').prop('value');
			if (filter_description) {
				url += '&filter_description=true';
			}
			location = url;
		});
</script>
