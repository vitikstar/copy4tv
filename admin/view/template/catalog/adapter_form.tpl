<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="scp_grad" style="overflow: hidden;">


<div style="margin-left: 10px; float:left; margin-top: 10px;">
<?php echo strip_tags($heading_title); ?> <?php echo $blog_version; ?>
</div>

    <div class="scp_grad_green" style=" height: 40px; float:right; ">
      <div style="margin-top: 2px; line-height: 18px; margin-left: 9px; margin-right: 9px; overflow: hidden;"><?php echo $language->get('heading_dev'); ?></div>
    </div>

</div>

   <div class="page-header">
    <div class="container-fluid">


<div id="content1" style="border: none;">

<div style="clear: both; line-height: 1px; font-size: 1px;"></div>


<?php if ($error_warning) { ?>
    <div class="alert alert-danger warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php } ?>

<?php if ($success) { ?>
    <div class="alert alert-success success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
<?php } ?>


<div class="box1">

<div class="content">

<?php echo $agoo_menu; ?>




      <div class="buttons" style="float:right;">
      	<a onclick="$('#form').submit();" class="markbuttono asc_blinking nohref"><?php echo $language->get('button_adapter'); ?></a>
      	<a onclick="location = '<?php echo $cancel; ?>';" class="markbuttono  nohref"><?php echo $language->get('button_cancel'); ?></a>
      </div>

      <div style="width: 100%; overflow: hidden; clear: both; height: 1px; line-height: 1px;">&nbsp;</div>


  <div class="box">
   <div class="content">

    	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
          <table class="form">
            <tr>

              <td colspan=2>
							<div class="input-group marginbottom5px">
							<span class="input-group-addon"><?php echo $language->get('entry_success')."<b>".$theme."</b> ...<br>/".$file_theme; ?></span>
								<textarea class="form-control" name="success_file" rows="25" style="width: 100%;"><?php echo $success_data; ?></textarea>
							</div>



              </td>
            </tr>

            <tr>
              <td><?php echo $language->get('entry_replace_breadcrumb'); ?></td>
              <td>
              <div class="input-group">
              <?php if ($replace_breadcrumb) { ?>
                <label class="radio-inline"><input type="radio" name="replace_breadcrumb" value="1" checked="checked" class="form-control"/><?php echo $text_yes; ?></label>
                <label class="radio-inline"><input type="radio" name="replace_breadcrumb" value="0" class="form-control"/><?php echo $text_no; ?></label>
                <?php } else { ?>
                <label class="radio-inline"><input type="radio" name="replace_breadcrumb" value="1" class="form-control"/><?php echo $text_yes; ?></label>
                <label class="radio-inline"><input type="radio" name="replace_breadcrumb" value="0" checked="checked" class="form-control"/><?php echo $text_no; ?></label>
                <?php } ?>

                </div>
                </td>
            </tr>

            <tr>
              <td><?php echo $language->get('entry_replace_breadcrumb_name'); ?></td>
              <td>
              <div class="input-group">
              	<input type="text" name="replace_breadcrumb_name" value="<?php echo $replace_breadcrumb_name; ?>" class="form-control" size="40" />
              </div>
              </td>
            </tr>

            <tr>
              <td><?php echo $language->get('entry_replace_main_name'); ?></td>
              <td>
              <div class="input-group">
              <input type="text" name="replace_main_name" value="<?php echo $replace_main_name; ?>" class="form-control" size="40" />
              </div>
              </td>
            </tr>

            <tr>
              <td><?php echo $language->get('entry_remove_tag'); ?></td>
              <td>
              <div class="checkbox">
               <?php
              if (isset($success_tag)) {
              foreach ($success_tag as $num => $tag) {
              if (in_array($tag, $remove_tag[$theme])) {

              ?>
              <label><input type="checkbox" value="<?php echo $tag; ?>" name="selected_tag[]" checked="checked" >
              <?php
              print_r($tag);
              } else {
              ?>
               <label><input type="checkbox" value="<?php echo $tag; ?>" name="selected_tag[]">
              <?php
              print_r($tag);
              }
               ?></label>
              <br>
              <?php
              }
              }
               ?>
              </div>
              </td>
            </tr>


            <tr>
              <td><?php echo $language->get('entry_remove_id'); ?></td>
              <td>
               <div class="checkbox">
               <?php
              if (isset($success_id)) {
              foreach ($success_id as $num => $id) {
              if (in_array($id, $remove_id[$theme])) {

              ?>
               <label><input type="checkbox" value="<?php echo $id; ?>" name="selected_id[]" checked="checked" >
              <?php
              print_r($id);
              } else {
              ?>
               <label><input type="checkbox" value="<?php echo $id; ?>" name="selected_id[]">
              <?php
              print_r($id);
              }
               ?></label>
              <br>
              <?php
              }
              }
               ?>
               </div>
              </td>
            </tr>

            <tr>
              <td><?php echo $language->get('entry_remove_class'); ?></td>
              <td>
               <div class="checkbox">
               <?php
              if (isset($success_class)) {
              foreach ($success_class as $num => $class) {              	foreach ($class as $n => $classic) {
              if (in_array($classic, $remove_class[$theme])) {
              ?>
               <label><input type="checkbox" value="<?php echo $classic; ?>" name="selected_class[<?php echo $classic; ?>][]" checked="checked" >
              <?php
              print_r($classic);
              } else {              ?>
               <label><input type="checkbox" value="<?php echo $classic; ?>" name="selected_class[<?php echo $classic; ?>][]">
              <?php
              print_r($classic);
              }
               ?></label>
              <br>
              <?php
              }
              }
              }
               ?>
               </div>
              </td>
            </tr>

            <tr>
              <td></td>
              <td>

              </td>
            </tr>


          </table>


   		</form>
    </div>
  </div>
</div>

</div>
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

function select_this(ithis) {

if (!$(ithis).hasClass('no_change')) {
	        $(ithis).removeClass('sc_select_enable');
	        $(ithis).removeClass('sc_select_disable');

			this_val = $(ithis).find('option:selected').val()

			if (this_val == '1' ) {
				$(ithis).addClass('sc_select_enable');
			}

			if (this_val == '0' || this_val == '' ) {
				$(ithis).addClass('sc_select_disable');
			}

			if (this_val != '0' && this_val != '1' && this_val != '') {
				$(ithis).addClass('sc_select_other');
			}
		}
}


function input_this(ithis) {

		if (!$(ithis).hasClass('no_change')) {
	        $(ithis).removeClass('sc_select_enable');
	        $(ithis).removeClass('sc_select_disable');

			if ( $(ithis).val() != '' ) {
				$(ithis).addClass('sc_select_enable');
			} else {
				$(ithis).addClass('sc_select_disable');
			}
		}
}



function input_select_change() {
	$('input').each(function(){
		input_this(this);
	});

	$('select').each(function(){
		select_this(this);
	});
}

$(document).ready(function(){

$(document).on('change', 'select', function(event) {
		select_this(this);
	  });

$(document).on('blur', 'input', function(event) {
		input_this(this)
	  });
input_select_change();
});



</script>

<?php echo $footer; ?>