<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">

<?php echo $agoo_header; ?>

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

<div id="sticky-anchor"></div>
<div style="margin:5px; float:right;" id="sticky">
   <a href="#" class="mbutton blog_save <?php
   if (count($settings_install) > 0 && isset($settings_install['ascp_install_3']) && !$settings_install['ascp_install_3']) {
   ?> asc_blinking <?php
   }
   ?>"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $button_save." (".count($modules).")"; ?></a>
   <a class="addmodule mbuttong nohref"><i class="fa fa-plus-square-o" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $button_add_module; ?></a>
   <?php echo $agoo_save; ?>
</div>

<style>
#sticky {

}

.sticky-back {
background-color: #E1E1E1;
box-shadow: 0 0 16px rgba(0, 0, 0, 0.3) !important;
}

#sticky.stick {
    position: fixed;
    top: 0;
    z-index: 10000;

}

.sc_select_disable {
    border-color: rgba(255, 0, 0, 0.5) !important;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(255, 0, 0, 0.3) !important;
    outline: 0 none !important;
}
.sc_select_enable {
    border-color: rgba(0, 200, 0, 0.5)  !important;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(0, 200, 0, 0.3) !important;
    outline: 0 none !important;
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



<div style="clear: both; line-height: 1px; font-size: 1px;"></div>

<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

<script type="text/javascript">
function delayer(){
    window.location = 'index.php?route=module/blog/schemes&<?php echo $token_name; ?>=<?php echo $token; ?>';
}
</script>

  <div id="tab-general">

   <table class="mytable" id="module" style="width: 100%; ">
     <thead>
      <tr>
       <td class="left" style="min-width: 20%;"><?php echo $entry_layout; ?></td>
       <td class="left"><?php echo $entry_position; ?></td>
       <td class="left"><?php echo $entry_status; ?></td>
       <td class="right"> <?php echo $language->get('type_list'); ?></td>
       <td class="right"><?php echo $entry_sort_order; ?></td>
       <td style="width: 200px;"><?php echo $language->get('text_action'); ?></td>
      </tr>
          <tr>
            <td colspan="5"></td>
            <td class="left"></td>
          </tr>
     </thead>
<?php
   	$module_row = 0;

	 foreach ($modules as $module)   {
		while (!isset($modules[$module_row])) {
			$module_row++;
		}

		foreach ($ascp_widgets as $num =>$list) {
			if ($module['what'] == $num) {
				 $widget_type = $list['type'];
			}
		}
        reset($ascp_widgets);

?>

   <tbody class="view_status <?php echo 'view_status_'.$widget_type; ?>" data-widget-class = "view_status_<?php echo $widget_type; ?>">

         <tr class="module-row<?php echo $module_row; ?>">

   	           <td style="border-top: 1px solid #DDEFD9;">

				<?php if ((isset($module['url']) && trim($module['url'])=='') || (!isset($module['url']))) $hide_url = 'hide_url'; else $hide_url = 'url_active'; ?>

   	           <a onclick="$('.hide_url').toggle(); return false; " class="hrefajax"><?php echo $language->get('entry_url_schemes'); ?></a>

               <span class="<?php echo $hide_url; ?>">
               <?php echo $language->get('entry_url_template'); ?>

				<div class="input-group"><select class="form-control" name="blog_module[<?php echo $module_row; ?>][url_template]">
                  <?php if (isset($module['url_template']) && $module['url_template']) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></div>
                </span>

   	           </td>
	            <td class="left" colspan="5" style="width: 100%; border-top: 1px solid #DDEFD9;">
	         	 <span class="<?php echo $hide_url; ?>" style="width:100%">
		         	 <input class="<?php echo $hide_url; ?>" style="width:100%" type="text" name="blog_module[<?php echo $module_row; ?>][url]" value="<?php if (isset($module['url'])) echo trim($module['url']); ?>"  />
	         	 </span>
	           </td>
         </tr>

           <tr class="module-row<?php echo $module_row; ?>" style="height: 0px; line-height: 0px;" >
           <td style="height: 1px; line-height: 1px; display: none;"></td>
           </tr>

          <tr class="module-row<?php echo $module_row; ?>">

            <td class="left"><!-- <?php echo $module_row; ?>&nbsp; -->

            <!--
            <select name="blog_module[<?php echo $module_row; ?>][layout_id]">
                <?php foreach ($layouts as $layout) { ?>
                <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
                -->

				<div class="sc-block-layouts">
				<div class="scrollbox" style="width: auto; height: 110px;">
                  <?php $class = 'odd'; ?>
                  <?php foreach ($layouts as $layout) { ?>
                  <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                  <div class="<?php echo $class; ?>">
                    <?php
                    if (isset($module['layout_id']) && !is_array($module['layout_id'])) {
                     	$module_array = Array();
                     	$module_array[] = $module['layout_id'];
                     	$module['layout_id'] = $module_array;
                    } else {
                    $module_array[] = Array();
                    }

                    if ((isset($module['layout_id']) && is_array($module['layout_id']) ) && in_array($layout['layout_id'], $module['layout_id'])) { ?>
                    <input type="checkbox" class="sc_select_enable no_change" name="blog_module[<?php echo $module_row; ?>][layout_id][]" value="<?php echo $layout['layout_id']; ?>" checked="checked" />
                    <?php echo $layout['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" class="no_change" name="blog_module[<?php echo $module_row; ?>][layout_id][]" value="<?php echo $layout['layout_id']; ?>" />
                    <?php echo $layout['name']; ?>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>

                <a href="#" onclick="$(this).parent().find(':checkbox').prop('checked', true); return false;" class="nohref"><?php echo $language->get('text_select_all'); ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);" class="nohref"><?php echo $language->get('text_unselect_all'); ?></a>

                </div>

                <div style="color: #777; font-size: 12px; line-height: 14px; ">
                <?php foreach ($layouts as $layout) { ?>
                    <?php
                    if (isset($module['layout_id']) && !is_array($module['layout_id'])) {
                     $module_array = Array();
                     $module_array[] = $module['layout_id'];
                     $module['layout_id'] = $module_array;
                    } else {
                    	$module_array[] = Array();
                    }

                    if ((isset($module['layout_id']) && is_array($module['layout_id']) ) && in_array($layout['layout_id'], $module['layout_id'])) { ?>
                     <?php echo $layout['name']."<br>"; ?>
                    <?php } ?>

                <?php } ?>
                </div>

              </td>

            <td class="left">

            <div class="input-group"><select class="form-control no_change" name="blog_module[<?php echo $module_row; ?>][position]">

			<?php foreach ($ascp_settings['position_type'] as $desc_position => $type_position ) {  ?>
	           <option value="<?php echo $type_position['type_id']; ?>" <?php if (isset($module['position']) && $module['position'] == $type_position['type_id']) { ?> selected="selected" <?php } ?>><?php if (isset($type_position['title'][$config_language_id])) { echo $type_position['title'][$config_language_id]; } else { echo $type_position['type_id']; } ?></option>
            <?php } ?>
              </select></div></td>

            <td class="left">
            <div class="input-group"><select class="form-control" name="blog_module[<?php echo $module_row; ?>][status]" >
                <?php if ($module['status']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></div></td>

              <td class="left">
               <div class="input-group"><select class="form-control no_change" name="blog_module[<?php echo $module_row; ?>][what]">
           	<?php
				foreach ($ascp_widgets as $num =>$list) {

					if (isset($list['title_list_latest'][ $config_language_id]) &&  $list['title_list_latest'][ $config_language_id]!='') {

					    if (isset($ascp_settings['admin_id_view']) && $ascp_settings['admin_id_view']) {
					    	$admin_id_view = " ID:".$num."";
					    } else {
					    	$admin_id_view = "";
					    }

				    	$title = $list['title_list_latest'][ $config_language_id].$admin_id_view;
					} else {
						$title="Widget-".$num;
					}

		    ?>
                <?php if ($module['what']==$num) { ?>
                <option value="<?php echo $num; ?>" selected="selected"><?php echo $title; ?></option>
                <?php } else { ?>
                <option value="<?php echo $num; ?>"><?php echo $title; ?></option>
                <?php } ?>

              <?php
              }
              ?>

              </select></div>
              </td>

            <td class="right"><div class="input-group"><input type="text" class="form-control no_change" name="blog_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></div></td>
            <td class="left">
            <?php if (isset($module['what']) && $module['what']!='hook' ) {
            $button_class ='mbutton button_purple';
			}
            else
            {
           	 $button_class ='markbuttono';
           	}
             ?>
           <div style="float:left; width: 100px;">
             <a onclick="$('.module-row<?php echo $module_row; ?>').remove();" class="<?php echo $button_class; ?> nohref"><?php echo $button_remove; ?></a>
           </div>

             <?php if ($button_class =='markbuttono') {

             ?>
             <div style="float:left;  width: 50%;">
             <?php
             //echo $language->get('hook_not_delete');
             ?>
             </div>
             <?php
            }
            ?>

          </td>
         </tr>
   </tbody>
        <?php
         $module_row++;
        }
        ?>
        <tfoot>
          <tr>
            <td colspan="7">
            <!-- <div style="text-align: right;"><a class="addmodule markbutton nohref"><?php echo $button_add_module; ?></a></div> -->
            </td>

          </tr>
        </tfoot>
      </table>

    </div>

 <div style="clear: both; line-height: 1px; font-size: 1px;"></div>
<!--
    <div class="buttons right" style="margin-top: 20px;float: right;"><a href="#" class="mbutton blog_save"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="mbutton nohref"><?php echo $button_cancel; ?></a></div>
-->


</form>



 </div>
</div>

<script type="text/javascript">
var amodule_row = Array();
<?php
 foreach ($modules as $indx => $module) {
?>
amodule_row.push(<?php echo $indx; ?>);
<?php
}
?>
var module_row = <?php echo $module_row; ?>;

var addModule = function() {
	var aindex = -1;
	for(i=0; i<amodule_row.length; i++) {
	 flg = jQuery.inArray(i, amodule_row);
	 if (flg == -1) {
	  aindex = i;
	 }
	}
	if (aindex == -1) {
	  aindex = amodule_row.length;
	}
	module_row = aindex;
	amodule_row.push(aindex);

	html  = '<tbody id="addmodule-' + module_row + '" class="addnewwidget module-row' + module_row + '">';

    html += '       <tr>';
    html += '       <td><?php echo $language->get('entry_url_schemes'); ?>';
    html += '            <?php echo $language->get('entry_url_template'); ?>';
	html += '			<div class="input-group"><select class="form-control" name="blog_module[' + module_row + '][url_template]">';
    html += '              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>';
    html += '              <option value="1"><?php echo $text_enabled; ?></option>';
    html += '            </select></div>';
    html += '		</td>';
    html += '        <td class="left" colspan="5">';
    html += '     	 <input type="text" name="blog_module[' + module_row + '][url]" value="" style="width:100%" /></div>';
    html += '       </td>';
    html += '       </tr>';

	html += '  <tr>';
	html += '    <td class="left">';
	html += '<div class="scrollbox" style="width: auto; height: 300px;">';
    html += '<?php $class = 'odd'; ?>';
             <?php foreach ($layouts as $layout) { ?>
              <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
	html += '               <div class="<?php echo $class; ?>">';
	html += '               <input type="checkbox" name="blog_module[' + module_row + '][layout_id][]" value="<?php echo $layout['layout_id']; ?>" />';
	html += '                 <?php echo $layout['name']; ?>';
	html += '               </div>';
             <?php } ?>
	html += '             </div>';
	html += '             <a onclick="$(this).parent().find(\':checkbox\').prop(\'checked\', true);" class="nohref"><?php echo $language->get('text_select_all'); ?></a> / <br><a onclick="$(this).parent().find(\':checkbox\').prop(\'checked\', false);" class="nohref"><?php echo $language->get('text_unselect_all'); ?></a></td>';
	html += '    </td>';
	html += '    <td class="left"><div class="input-group"><select class="form-control" name="blog_module[' + module_row + '][position]">';

			<?php foreach ($ascp_settings['position_type'] as $desc_position => $type_position ) {  ?>
	html += '           <option value="<?php echo $type_position['type_id']; ?>"><?php if (isset($type_position['title'][$config_language_id])) { echo $type_position['title'][$config_language_id]; } else { echo $type_position['type_id']; } ?></option>';
            <?php } ?>


	html += '    </select></div></td>';

	html += '    <td class="left"><div class="input-group"><select class="form-control" name="blog_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></div></td>';

	html += '    <td class="left"><div class="input-group"><select class="form-control" name="blog_module[' + module_row + '][what]">';

    <?php
	if (count($ascp_widgets)>0) {
  	 foreach ($ascp_widgets as $num =>$list) {
					if (isset($list['title_list_latest'][ $config_language_id]) &&  $list['title_list_latest'][ $config_language_id] != '') {

					    if (isset($ascp_settings['admin_id_view']) && $ascp_settings['admin_id_view']) {
					    	$admin_id_view = " ID:".$num."";
					    } else {
					    	$admin_id_view = "";
					    }

				    	$title = $list['title_list_latest'][$config_language_id].$admin_id_view;

					} else	{
						$title="Widget-".$num;
					}

		    ?>
	html += '        <option value="<?php echo $num; ?>"><?php echo $title; ?></option>';
	<?php
	 }
	}
	 ?>
	html += '    </select></div></td>';
	html += '    <td class="right"><div class="input-group"><input class="form-control no_change" type="text" name="blog_module[' + module_row + '][sort_order]" value="" size="3" /></div></td>';
	html += '    <td class="left"><a onclick="$(\'.module-row' + module_row + '\').remove();" class="mbutton button_purple nohref"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';

	$('#module tfoot').before(html);

                $('html, body').animate({
                    scrollTop: $('#addmodule-' + module_row).offset().top
                }, 1000);


	module_row++;

	return false;
}
</script>
	<script type="text/javascript">

	 form_submit = function() {
		$('#form').submit();
		return false;
	}
	$('.blog_save').bind('click', form_submit);

	$('.addmodule').bind('click', addModule);

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

			if (this_val == '0' ) {
				$(this).addClass('sc_select_disable');
			}

			if (this_val != '0' && this_val != '1') {
				$(this).addClass('sc_select_other');
			}
		}
	});
}

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

</div>
</div>
</div>


<div style="margin-bottom: 7px;">
   <a style="margin-top: 7px;" class="mbutton button_blue" onclick="$('#view_status_show_hide').toggle('slow'); return false; "><?php  echo $language->get('entry_widgets_show_hide'); ?></a>
</div>

<div id="view_status_show_hide" style="display: none;">
                 <div>
                 <a style="margin-top: 7px; margin-bottom: 7px;" class="mbutton button_blue widgets_show" onclick="$('.view_status').show();  func_widget_show(); return false; "><?php echo $language->get('entry_widgets_show_all'); ?></a>
                 </div>


                 <?php
                 if (isset($ascp_widgets) && is_array($ascp_widgets)) {
				 $ascp_widgets_show_hide = array();
                 reset($ascp_widgets);
                 foreach ($ascp_widgets as $num =>$list) {
                 	$ascp_widgets_show_hide[$list['type']] = $list;
                 }

                 foreach ($ascp_widgets_show_hide as $num =>$list) { ?>
                 <div>
                 <a style="margin-top: 7px;" class="mbutton buttonpurple widget_show_hide" data-widget-class = "view_status_<?php echo $list['type']; ?>"><?php echo $language->get('text_widget_'.$list['type']); ?></a>
                 </div>
                 <?php } ?>
                 <?php } ?>

</div>

<script>
var widgets_is_loaded = true;

	$('.widget_show_hide').on('click', function() {
		data_widget_class = $(this).attr('data-widget-class');

		$('.'+data_widget_class).toggle();

	    if ($('.'+data_widget_class).is(':hidden')) {
        	$(this).addClass('inactive_button');
        	localStorage.setItem(data_widget_class, 'hidden');
	    } else {
        	$(this).removeClass('inactive_button');
        	localStorage.setItem(data_widget_class, '');
	    }
	});

	function widget_show_hide_loaded() {
		if (widgets_is_loaded) {
            clearInterval(timer_widget_show_hide_loaded);
			$('.widget_show_hide').each( function () {
				data_widget_class = $(this).attr('data-widget-class');

		  		if (localStorage.getItem(data_widget_class) == 'hidden') {
			          $('#view_status_show_hide').show();
			          $('.'+data_widget_class).toggle();
			          $(this).addClass('inactive_button');
		  		}
			});
		}
	}

	function func_widget_show() {

			$('.view_status').each( function () {
				data_widget_class = $(this).attr('data-widget-class');

		  		if (localStorage.getItem(data_widget_class) == 'hidden') {

			          $(this).show();
			          $(".widget_show_hide[data-widget-class='"+data_widget_class+"']").removeClass('inactive_button');
			          localStorage.setItem(data_widget_class, '');
		  		}
			});

	}

var timer_widget_show_hide_loaded = setInterval(widget_show_hide_loaded, 1000);

</script>



</div>
<?php echo $footer; ?>
