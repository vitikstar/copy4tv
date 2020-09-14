<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-language" data-toggle="tab"><?php echo $tab_language; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
                <div class="col-sm-10"> <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <div class="checkbox">
                      <label>
                        <?php if (in_array(0, $store)) { ?>
                        <input type="checkbox" name="store[]" value="0" checked="checked" />
                        <?php echo $text_default; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="store[]" value="0" />
                        <?php echo $text_default; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php foreach ($stores as $store) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($store['store_id'], $store)) { ?>
                        <input type="checkbox" name="store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                        <?php echo $store['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="store[]" value="<?php echo $store['store_id']; ?>" />
                        <?php echo $store['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-priority"><?php echo $entry_customer_group_id; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($customer_groups as $customer_group) { ?>
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($customer_group['customer_group_id'], $menu_customer_groups)) { ?>
                        <input type="checkbox" name="customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                        <?php echo $customer_group['name']; ?>
                        <?php } else { ?>
                        <input type="checkbox" name="customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
                        <?php echo $customer_group['name']; ?>
                        <?php } ?>
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $text_type; ?></label>
                <div class="col-sm-10">
                  <select name="item_type" class="form-control">
                    <option value="0" <?php echo $item_type == 0 ? 'selected="selected"' : ''; ?> ><?php echo $text_select; ?></option>
                    <option value="1" <?php echo $item_type == 1 ? 'selected="selected"' : ''; ?> ><?php echo $text_type_1; ?></option>
                    <option value="2" <?php echo $item_type == 2 ? 'selected="selected"' : ''; ?> ><?php echo $text_type_2; ?></option>
                    <option value="3" <?php echo $item_type == 3 ? 'selected="selected"' : ''; ?> ><?php echo $text_type_3; ?></option>
                    <!-- <option value="4" <?php echo $item_type == 4 ? 'selected="selected"' : ''; ?> ><?php echo $text_type_4; ?></option>-->
                    <option value="5" <?php echo $item_type == 5 ? 'selected="selected"' : ''; ?> ><?php echo $text_type_5; ?></option>
                    <!-- <option value="6" <?php echo $item_type == 6 ? 'selected="selected"' : ''; ?> ><?php echo $text_type_6; ?></option> -->
                    <option value="7" <?php echo $item_type == 7 ? 'selected="selected"' : ''; ?> ><?php echo $text_type_7; ?></option>
                  </select>
                </div>
              </div>
              <div style="<?php echo $item_type == 2 ? '' : 'display:none;'; ?>" id="oct_megamenu_categories">
                <div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $text_enter_category; ?></label>
                  <div class="col-sm-10">
                    <div class="well well-sm" style="height: 392px; overflow: auto;">
                      <?php foreach ($categories as $category) { ?>
                        <div class="checkbox" style="padding-top: 1px; min-height: auto;">
                          <label>
                            <?php if (in_array($category['category_id'], $category_id)) { ?>
                            <input type="checkbox" name="oct_megamenu_categories[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                            <?php echo $category['name']; ?>
                            <?php } else { ?>
                            <input type="checkbox" name="oct_megamenu_categories[]" value="<?php echo $category['category_id']; ?>" />
                            <?php echo $category['name']; ?>
                            <?php } ?>
                          </label>
                        </div>
                      <?php } ?>
                    </div>
                    <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-sub_categories"><?php echo $entry_sub_categories; ?></label>
                  <div class="col-sm-10">
                    <select name="sub_categories" id="input-sub_categories" class="form-control">
                      <?php if ($sub_categories) { ?>
                      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                      <option value="0"><?php echo $text_disabled; ?></option>
                      <?php } else { ?>
                      <option value="1"><?php echo $text_enabled; ?></option>
                      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group" style="<?php echo $item_type == 3 ? '' : 'display:none;'; ?>" id="oct_megamenu_manufacturers">
                <label class="col-sm-2 control-label" for="input-manufacturer"><?php echo $text_enter_manufacturer; ?></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 392px; overflow: auto;">
                    <?php foreach ($manufacturers as $manufacturer) { ?>
                      <div class="checkbox" style="padding-top: 1px; min-height: auto;">
                        <label>
                          <?php if (in_array($manufacturer['manufacturer_id'], $manufacturer_id)) { ?>
                          <input type="checkbox" name="oct_megamenu_manufacturers[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" checked="checked" />
                          <?php echo $manufacturer['name']; ?>
                          <?php } else { ?>
                          <input type="checkbox" name="oct_megamenu_manufacturers[]" value="<?php echo $manufacturer['manufacturer_id']; ?>" />
                          <?php echo $manufacturer['name']; ?>
                          <?php } ?>
                        </label>
                      </div>
                    <?php } ?>
                  </div>
                  <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
                </div>
              </div>
              <div class="form-group" style="<?php echo $item_type == 4 ? '' : 'display:none;'; ?>" id="oct_megamenu_products">
                <label class="col-sm-2 control-label" for="input-product"><?php echo $text_enter_product; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="product" value="" placeholder="<?php echo $text_enter_product; ?>" id="input-product" class="form-control" />
                  <div id="featured-product" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($products as $product) { ?>
                    <div id="featured-product<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
                      <input type="hidden" name="oct_megamenu_products[]" value="<?php echo $product['product_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div style="<?php echo $item_type == 5 ? '' : 'display:none;'; ?>" id="oct_megamenu_informations">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-information"><?php echo $text_enter_information; ?></label>
                  <div class="col-sm-10">
                    <div class="well well-sm" style="height: 392px; overflow: auto;">
                      <?php foreach ($informations as $information) { ?>
                        <div class="checkbox" style="padding-top: 1px; min-height: auto;">
                          <label>
                            <?php if (in_array($information['information_id'], $information_id)) { ?>
                            <input type="checkbox" name="oct_megamenu_informations[]" value="<?php echo $information['information_id']; ?>" checked="checked" />
                            <?php echo $information['title']; ?>
                            <?php } else { ?>
                            <input type="checkbox" name="oct_megamenu_informations[]" value="<?php echo $information['information_id']; ?>" />
                            <?php echo $information['title']; ?>
                            <?php } ?>
                          </label>
                        </div>
                      <?php } ?>
                    </div>
                    <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-open_link_type"><?php echo $entry_open_link_type; ?></label>
                  <div class="col-sm-10">
                    <select name="open_link_type" id="input-open_link_type" class="form-control">
                      <?php if ($open_link_type) { ?>
                      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                      <option value="0"><?php echo $text_disabled; ?></option>
                      <?php } else { ?>
                      <option value="1"><?php echo $text_enabled; ?></option>
                      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group" style="<?php echo $item_type == 7 ? '' : 'display:none;'; ?>" id="oct_megamenu_custom_html">
                <label class="col-sm-2 control-label" for="input-manufacturer"><?php echo $entry_custom_html; ?></label>
                <div class="col-sm-10">
                  <ul class="nav nav-tabs" id="custom_html">
                    <?php foreach ($languages as $language) { ?>
                    <li><a href="#custom_html<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" />  <?php echo $language['name']; ?></a></li>
                    <?php } ?>
                  </ul>
                  <div class="tab-content">
                    <?php foreach ($languages as $language) { ?>
                    <div class="tab-pane" id="custom_html<?php echo $language['language_id']; ?>">
                      <textarea name="oct_megamenu_description[<?php echo $language['language_id']; ?>][custom_html]" placeholder="<?php echo $entry_description; ?>" id="input-custom_html<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($oct_megamenu_description[$language['language_id']]) ? $oct_megamenu_description[$language['language_id']]['custom_html'] : ''; ?></textarea>
                    </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div style="<?php echo ($item_type == 2) ? '' : 'display:none;'; ?>" id="additional_setting_block">
                <div class="form-group" style="<?php echo ($display_type == 2) ? '' : 'display:none;'; ?>">
                  <label class="col-sm-2 control-label" for="input-show_img"><?php echo $entry_show_img; ?></label>
                  <div class="col-sm-10">
                    <select name="show_img" id="input-show_img" class="form-control">
                      <?php if ($show_img) { ?>
                      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                      <option value="0"><?php echo $text_disabled; ?></option>
                      <?php } else { ?>
                      <option value="1"><?php echo $text_enabled; ?></option>
                      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group" style="<?php echo ($display_type == 2) ? '' : 'display:none;'; ?>">
                  <label class="col-sm-2 control-label" for="input-img_width"><?php echo $entry_img_width; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="img_width" value="<?php echo $img_width; ?>" placeholder="<?php echo $entry_img_width; ?>" id="input-img_width" class="form-control" />
                  </div>
                </div>
                <div class="form-group" style="<?php echo ($display_type == 2) ? '' : 'display:none;'; ?>">
                  <label class="col-sm-2 control-label" for="input-img_height"><?php echo $entry_img_height; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="img_height" value="<?php echo $img_height; ?>" placeholder="<?php echo $entry_img_height; ?>" id="input-img_height" class="form-control" />
                  </div>
                </div>
                <div class="form-group" style="<?php echo ($display_type == 2) ? '' : 'display:none;'; ?>">
                  <label class="col-sm-2 control-label" for="input-limit_item"><?php echo $entry_limit_item; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="limit_item" value="<?php echo $limit_item; ?>" placeholder="<?php echo $entry_limit_item; ?>" id="input-limit_item" class="form-control" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $entry_display_type; ?></label>
                  <div class="col-sm-10">
                    <select name="display_type" class="form-control">
                      <option value="1" <?php echo $display_type == 1 ? 'selected="selected"' : ''; ?> ><?php echo $text_display_type_1; ?></option>
                      <option value="2" <?php echo $display_type == 2 ? 'selected="selected"' : ''; ?> ><?php echo $text_display_type_2; ?></option>
                      <!-- <option value="2" <?php echo $display_type == 2 ? 'selected="selected"' : ''; ?> style="<?php echo ($display_type != 2) ? 'display:none;' : ''; ?>" class="not-for-category"><?php echo $text_display_type_2; ?></option>
                      <option value="3" <?php echo $display_type == 3 ? 'selected="selected"' : ''; ?> style="<?php echo ($display_type != 2) ? 'display:none;' : ''; ?>" class="not-for-category"><?php echo $text_display_type_3; ?></option>
                      <option value="4" <?php echo $display_type == 4 ? 'selected="selected"' : ''; ?> class="for-category-only"><?php echo $text_display_type_4; ?></option>
                      <option value="5" <?php echo $display_type == 5 ? 'selected="selected"' : ''; ?> class="for-category-only"><?php echo $text_display_type_5; ?></option> -->
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-info_text"><?php echo $entry_info_text; ?></label>
                <div class="col-sm-10">
                  <select name="info_text" id="input-info_text" class="form-control">
                    <?php if ($info_text) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="status" id="input-status" class="form-control">
                    <?php if ($status) { ?>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-language">
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" />  <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-link<?php echo $language['language_id']; ?>"><?php echo $entry_link; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="oct_megamenu_description[<?php echo $language['language_id']; ?>][link]" value="<?php echo isset($oct_megamenu_description[$language['language_id']]) ? $oct_megamenu_description[$language['language_id']]['link'] : ''; ?>" placeholder="<?php echo $entry_link; ?>" id="input-link<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_link[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_link[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-title<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="oct_megamenu_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($oct_megamenu_description[$language['language_id']]) ? $oct_megamenu_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control" />
                      <?php if (isset($error_title[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="oct_megamenu_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control summernote"><?php echo isset($oct_megamenu_description[$language['language_id']]) ? $oct_megamenu_description[$language['language_id']]['description'] : ''; ?></textarea>
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<script type="text/javascript"><!--
// Products
$('input[name=\'product\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',     
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['product_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'product\']').val('');
    
    $('#octemplates_megamenu-product' + item['value']).remove();
    
    $('#octemplates_megamenu-product').append('<div id="octemplates_megamenu-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="octemplates_megamenu_product[]" value="' + item['value'] + '" /></div>');  
  } 
});

$('#octemplates_megamenu-product').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});
//--></script>
<script type="text/javascript"><!--
<?php if ($ckeditor) { ?>
	<?php foreach ($languages as $language) { ?>
		ckeditorInit('input-description<?php echo $language['language_id']; ?>', getURLVar('token'));
		ckeditorInit('input-custom_html<?php echo $language['language_id']; ?>', getURLVar('token'));																			 
	<?php } ?>
<?php } ?>
//--></script>
<script type="text/javascript"><!--
$('#language a:first').tab('show');
$('#custom_html a:first').tab('show');
//--></script>
<script type="text/javascript"><!--
$('.date').datetimepicker({
  pickTime: false
});

$('.time').datetimepicker({
  pickDate: false
});

$('.datetime').datetimepicker({
  pickDate: true,
  pickTime: true
});
//--></script> 
<script type="text/javascript"><!--
  $('select[name=item_type]').change(function() {
    var val = $(this).val();
    if (val == 1) {
      $('#oct_megamenu_categories').hide();
      $('#oct_megamenu_manufacturers').hide();
      $('#oct_megamenu_products').hide();
      $('#oct_megamenu_informations').hide();
      $('#oct_megamenu_custom_html').hide();
      $('#additional_setting_block').hide();
      $('.for-category-only').hide();
      $('.not-for-category').show();
      $('#input-columns').attr('readonly', true);
    } else if(val == 2) {
      $('#oct_megamenu_categories').show();
      $('#oct_megamenu_manufacturers').hide();
      $('#oct_megamenu_products').hide();
      $('#oct_megamenu_informations').hide();
      $('#oct_megamenu_custom_html').hide();
      $('#additional_setting_block').show();
      $('.for-category-only').show();
      $('.not-for-category').hide();
      $('#input-columns').attr('readonly', false);
    } else if (val == 3) {
      $('#oct_megamenu_categories').hide();
      $('#oct_megamenu_manufacturers').show();
      $('#oct_megamenu_products').hide();
      $('#oct_megamenu_informations').hide();
      $('#oct_megamenu_custom_html').hide();
      $('#additional_setting_block').show();
      $('.for-category-only').hide();
      $('.not-for-category').show();
      $('#input-columns').attr('readonly', true);
    } else if (val == 4) {
      $('#oct_megamenu_categories').hide();
      $('#oct_megamenu_manufacturers').hide();
      $('#oct_megamenu_products').show();
      $('#oct_megamenu_informations').hide();
      $('#oct_megamenu_custom_html').hide();
      $('#additional_setting_block').show();
      $('.for-category-only').hide();
      $('.not-for-category').show();
      $('#input-columns').attr('readonly', true);
    } else if (val == 5) {
      $('#oct_megamenu_categories').hide();
      $('#oct_megamenu_manufacturers').hide();
      $('#oct_megamenu_products').hide();
      $('#oct_megamenu_informations').show();
      $('#oct_megamenu_custom_html').hide();
      $('#additional_setting_block').hide();
      $('.for-category-only').hide();
      $('.not-for-category').show();
      $('#input-columns').attr('readonly', true);
    } else if (val == 7) {
      $('#oct_megamenu_categories').hide();
      $('#oct_megamenu_manufacturers').hide();
      $('#oct_megamenu_products').hide();
      $('#oct_megamenu_informations').hide();
      $('#oct_megamenu_custom_html').show();
      $('#additional_setting_block').hide();
      $('.for-category-only').hide();
      $('.not-for-category').show();
      $('#input-columns').attr('readonly', true);
    } else {
      $('#oct_megamenu_categories').hide();
      $('#oct_megamenu_manufacturers').hide();
      $('#oct_megamenu_products').hide();
      $('#oct_megamenu_informations').hide();
      $('#oct_megamenu_custom_html').hide();
      $('#additional_setting_block').hide();
      $('.for-category-only').hide();
      $('.not-for-category').show();
      $('#input-columns').attr('readonly', true);
    }
  });
  
  $('select[name=display_type]').change(function() {
    var val = $(this).val();
    if (val == 2) {
      $('[name=show_img]').parent().parent().show();
      $('[name=img_width]').parent().parent().show();
      $('[name=img_height]').parent().parent().show();
      $('[name=limit_item]').parent().parent().show();
    } else if(val == 3) {
      $('[name=show_img]').parent().parent().show();
      $('[name=img_width]').parent().parent().show();
      $('[name=img_height]').parent().parent().show();
      $('[name=limit_item]').parent().parent().show();
      $('#input-columns').attr('readonly', true);
    } else if(val == 4) {
      $('[name=show_img]').parent().parent().show();
      $('[name=img_width]').parent().parent().show();
      $('[name=img_height]').parent().parent().show();
      $('[name=limit_item]').parent().parent().show();
    } else if(val == 5) {
      $('[name=show_img]').parent().parent().show();
      $('[name=img_width]').parent().parent().show();
      $('[name=img_height]').parent().parent().show();
      $('[name=limit_item]').parent().parent().show();
    } else {
      $('[name=show_img]').parent().parent().hide();
      $('[name=img_width]').parent().parent().hide();
      $('[name=img_height]').parent().parent().hide();
      $('[name=limit_item]').parent().parent().hide();
    }
  });

  $(function() {
    $('select[name=type]').trigger('change');
    $('select[name=display_type]').trigger('change');
  });
</script>
<script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
  source: function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['product_id']
          }
        }));
      }
    });
  },
  select: function(item) {
    $('input[name=\'product\']').val('');
    $('#featured-product' + item['value']).remove();
    $('#featured-product').append('<div id="featured-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="oct_megamenu_products[]" value="' + item['value'] + '" /></div>');
  }
});

$('#featured-product').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});
</script>
</div>
<?php echo $footer; ?>