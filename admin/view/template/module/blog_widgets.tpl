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
<div style="margin-right:5px; float:right;" id="sticky">
 <a href="#" class="mbutton blog_save_new <?php
   if (count($settings_install) > 0 && isset($settings_install['ascp_install_4']) && !$settings_install['ascp_install_4']) {
   ?> asc_blinking <?php
   }
   ?>"><i class="fa fa-floppy-o" aria-hidden="true"></i>&nbsp;&nbsp;<?php echo $button_save; ?></a>
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
    div_left = $('#sticky').offset().left-82;
    $(window).scroll(sticky_relocate);
    sticky_relocate();
});
</script>

<div style=" clear: both; line-height: 1px; font-size: 1px;"></div>

<style>
.help {
display: none;
}
</style>

<?php
if (SC_VERSION < 20) {
?>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<?php } ?>

<div id="debug"></div>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

<script type="text/javascript">
var tab = 'amytabs1';
var widgets_is_loaded = false;
var myEditor = new Array();

function delayer(){
  window.location = 'index.php?route=module/blog/widgets&tab='+tab+'&<?php echo $token_name; ?>=<?php echo $token; ?>';
}


</script>
<?php  if (count($ascp_widgets)>0) { ?>
<div id="widgets_loading" style="width: 100%; height: 24px; line-height: 24px;  background-color: #EEE; margin-bottom: 5px;">&nbsp;</div>
<?php } ?>

<div id="tab-list">
	<div id="lists">

		<div id="mytabs" class="vtabs" style="padding-top: 0px;">
			<a href="#mytabs_add" style="color: #FFF; background: green; text-align: right;  "><span style="text-align: center; padding: 0px; margin: 0px; font-size: 21px; background-color: #2EC22E; border: none; line-height: 21px; height: 21px; width: 21px; color: #FFF; ">+</span><?php echo $language->get('text_add'); ?></a>
		</div>


<div id="mytabs_add">

<div class="sc_table">
<div>
 <?php  echo $language->get('type_list');   ?>
</div>
<div class="input-group sc_table_row">
         <select id="ascp_widgets-what" class="form-control" name="ascp_widgets-what">
                <?php foreach ($widget_list as $w_l) { ?>
                 <option value="<?php echo $w_l; ?>"><?php echo $language->get('text_widget_'.$w_l); ?></option>
                 <?php } ?>
         </select>
         </div>
      <div class="buttons">

    <a onclick="
    ascp_widgets_num++;
    type_what = $('#ascp_widgets-what :selected').val();
    this_block_html = $('#mytabs_add').html();
 	$.ajax({
					url: 'index.php?route=module/blog/ajax_list&<?php echo $token_name; ?>=<?php echo $token; ?>',
					type: 'post',
					async: true,
					data: { type: type_what, num: ascp_widgets_num },
					dataType: 'html',
					beforeSend: function()
					{
                      $('#mytabs_add').html('<?php echo $language->get('text_loading'); ?>');
					},
					success: function(html) {					$('#mytabs_add').html(this_block_html);
						if (html) {
							$('#mytabs').append('<a href=\'#mytabs' + ascp_widgets_num + '\' id=\'amytabs'+ascp_widgets_num+'\'>Widget-' + ascp_widgets_num + '<\/a>');
							$('#lists').append('<div id=\'mytabs'+ascp_widgets_num+'\'>'+html+'<\/div>');
							$('#mytabs a:visible').tabs();
							$('#amytabs' + ascp_widgets_num).click();
                            tab = 'amytabs' + ascp_widgets_num;

	                         template_auto();
	                         admins_auto();
	                         input_select_change();
	                         odd_even();
	                         $('.help').hide();

							$('.blog_save_new').html('<?php echo $button_save; ?> ('+ $('.hremove[value!=\'remove\']').length +')' );
						}
						$('.mbutton').removeClass('loader');


					}
				});


      return false; " class="mbutton"><?php echo $language->get('button_add_list'); ?></a>
      </div>

 </div>



</div>

	</div>

	<script type="text/javascript">

	 form_submit = function() {

		$('#form').submit();
		return false;
	}

</script>
<?php if (SC_VERSION > 15) { ?>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<?php } ?>

<?php
if (SC_VERSION > 15) {
?>
<script>


function delegateEditorClick () {
		$('#modal-image').remove();

		$(this).parents('.note-editor').find('.note-editable').focus();

		$.ajax({
			url: 'index.php?route=common/filemanager&<?php echo $token_name; ?>=' + getURLVar('<?php echo $token_name; ?>'),
			dataType: 'html',
			beforeSend: function() {
				$('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
				$('#button-image').prop('disabled', true);
			},
			complete: function() {
				$('#button-image i').replaceWith('<i class="fa fa-upload"></i>');
				$('#button-image').prop('disabled', false);
			},
			success: function(html) {
				$('body').append('<div id="modal-image" class="modal">' + html + '</div>');

				$('#modal-image').modal('show');
			}
		});
	}


function load_delegate(idName) {
	$('.' + idName + ' button[data-event=\'showImageDialog\']').attr('data-toggle', 'image').removeAttr('data-event');
	$(document).on('.' + idName + ' button[data-toggle=\'image\']', 'click', delegateEditorClick);
}

function load_summernote(idName, idHeight) {

         	<?php
         	if (SC_VERSION > 21) {
         	?>

		$(idName).summernote({
			height: idHeight,
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'underline', 'clear']],
				['fontname', ['fontname']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['table', ['table']],
				['insert', ['link', 'image', 'video']],
				['view', ['fullscreen', 'codeview', 'help']]
			],
			buttons: {
    			image: function() {
					var ui = $.summernote.ui;

					// create button
					var button = ui.button({
						contents: '<i class="fa fa-image" />',
						tooltip: $.summernote.lang[$.summernote.options.lang].image.image,
						click: function () {
							$('#modal-image').remove();

							$.ajax({
								url: 'index.php?route=common/filemanager&<?php echo $token_name; ?>=' + getURLVar('<?php echo $token_name; ?>'),
								dataType: 'html',
								beforeSend: function() {
									$('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
									$('#button-image').prop('disabled', true);
								},
								complete: function() {
									$('#button-image i').replaceWith('<i class="fa fa-upload"></i>');
									$('#button-image').prop('disabled', false);
								},
								success: function(html) {
									$('body').append('<div id="modal-image" class="modal">' + html + '</div>');

									$('#modal-image').modal('show');

									$('#modal-image').delegate('a.thumbnail', 'click', function(e) {
										e.preventDefault();

										$(idName).summernote('insertImage', $(this).attr('href'));

										$('#modal-image').modal('hide');
									});
								}
							});
						}
					});

					return button.render();
				}
  			}
		});

<?php } else { ?>

$(idName).summernote({height: idHeight});

<?php } ?>


}


function load_editor(idName, idHeight) {

	if (myEditor.indexOf(idName)== -1) {

		if("destroy" in $('#'+idName)) {
			$('#'+idName).destroy();
		} else {
          	$('#'+idName).summernote('destroy');
		}

		load_summernote('#'+idName);

        myEditor.push(idName);
		load_delegate(idName);
	} else {

		if("code" in $('#'+idName)) {
			var html = $('#'+idName).code();
		} else {
          	var html = $('#'+idName).summernote('code');
		}

		if("destroy" in $('#'+idName)) {
			$('#'+idName).destroy();
		} else {
          	$('#'+idName).summernote('destroy');
		}

        var currentVal = '';
        $('#'+idName).val(currentVal + html);

		myEditor.splice(myEditor.indexOf(idName), 1);

	}
	return false;
}

</script>


 <style>
.note-editable {
min-height: 300px !important;
}
</style>
<?php } else { ?>

<script language="javascript">

function load_editor(idName, idHeight) {
	if (!myEditor[idName]) {
	    CKEDITOR.remove(idName);
		var html = $('#'+idName).html();
		var config = {
						filebrowserBrowseUrl: 'index.php?route=common/filemanager&<?php echo $token_name; ?>=<?php echo $token; ?>',
						filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&<?php echo $token_name; ?>=<?php echo $token; ?>',
						filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&<?php echo $token_name; ?>=<?php echo $token; ?>',
						filebrowserUploadUrl: 'index.php?route=common/filemanager&<?php echo $token_name; ?>=<?php echo $token; ?>',
						filebrowserImageUploadUrl: 'index.php?route=common/filemanager&<?php echo $token_name; ?>=<?php echo $token; ?>',
						filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&<?php echo $token_name; ?>=<?php echo $token; ?>',
						enterMode 	: CKEDITOR.ENTER_BR,
						entities 	: false,
						htmlEncodeOutput : false
					};

		myEditor[idName] = CKEDITOR.replace(idName, config, html );

	  }	else {
		var currentVal = '';
		$('#'+idName).html(currentVal + myEditor[idName].getData());

		if("destroy" in myEditor[idName]) {
			myEditor[idName].destroy();
		} else {
          	myEditor[idName].summernote('destroy');
		}

		myEditor[idName] = null;
	}
}
</script>
<?php } ?>


<script>

form_submit_new = function() {

    $('#widgets_loading').html('');
    $('#widgets_loading').show();

<?php
if (SC_VERSION > 15) {
?>
     for (var idm = 0; idm < myEditor.length; idm++) {        idName = myEditor[idm];

		if("code" in $('#'+idName)) {
			var html = $('#'+idName).code();
		} else {
          	var html = $('#'+idName).summernote('code');
		}

		$('#'+idName).html(html);

		if("destroy" in $('#'+idName)) {
			$('#'+idName).destroy();
		} else {
          	$('#'+idName).summernote('destroy');
		}

  		$('#'+idName).html(html);
	}
<?php } ?>

    var seq = new Array();
    for (i = 0; i < $('.ascp_widgets_form').length; i++) {
        seq.push(i);
    }

    finishCallback = function(){		$.ajax({
		                url: 'index.php?route=module/blog/ascp_widgets_save&<?php echo $token_name; ?>=<?php echo $token; ?>',
		                type: 'post',
							dataType: 'html',
							async: true,
							success: function(html) {
								 delayer();
								}
		            });
		}

function go() {

        if(seq.length) {          num = seq.shift();

<?php  if (SC_VERSION < 20) { ?>
			for(name in CKEDITOR.instances)
			{
				CKEDITOR.instances[name].destroy()
			}
<?php } ?>

          kf = $('.ascp_widgets_form')[num];
          //console.log($(kf).html());

            $.ajax({
                url: 'index.php?route=module/blog/ascp_widgets_save&num='+num+'&<?php echo $token_name; ?>=<?php echo $token; ?>',
                type: 'post',
					data: $(kf).serialize(),
					dataType: 'html',
					async: true,
					beforeSend: function() {

					 $('a.mbutton, a.mbuttonr').addClass('loader');
					 $('.blog_save_new').unbind('click');

					},
					success: function(html) {
						old_html = $('#debug').html();
						$('#debug').html(old_html + html);
						if (seq.length==0) {
						 $('a.mbutton, a.mbuttonr').removeClass('loader');
						 $('.blog_save_new').bind('click', form_submit_new);
						 $('#widgets_loading').hide();
						 delayer();
						}
					},
                	complete: function(){						loading_recent = Math.round((100*($('.ascp_widgets_form').length - seq.length))/$('.ascp_widgets_form').length);
                        $('#widgets_loading').html('<div style=\"height: 24px; line-height: 24px; text-align: center; width:'+loading_recent+'%; color: white;background-color: orange;\">'+loading_recent+'%<\/div>');
                    	go();
                	}
            });
        } else {        	finishCallback();
        }
    }

    go();
    return false;

}
</script>

<script>
$('#mytabs a').tabs();
var progress_num = 0;
</script>

<?php
if (count($ascp_widgets) > 0) {
	reset($ascp_widgets);


	if (isset($tab)) {
		$first_key = '#'.$tab;
	} else {		$first_key = '#amytabs'.key($ascp_widgets);
	}

    $zamena = array ("`", "'", '"', "<", ">");
	$ki=0;


 $currentMax = 0;


 foreach ($ascp_widgets as $num =>$list) {

    if ($num >= $currentMax) {
    	$currentMax = $num;
    }

	$ki++;
	$slist = serialize($list);

	if (isset($list['title_list_latest'][ $config_language_id]) &&  $list['title_list_latest'][ $config_language_id]!='')
	{
    	$title= str_replace($zamena,"", $list['title_list_latest'][ $config_language_id]);	}
	else
	{		$title="Widget-".$num;
	}

?>
	<script type="text/javascript">

	var ascp_widgets_num=<?php echo $num; ?>;
	$('#mytabs').append('<a href=\"#mytabs<?php echo $num; ?>\" id=\"amytabs<?php echo $num; ?>\" class=\"tableft view_status <?php echo 'view_status_'.$list['type']; ?> tabclick\" data-widget-class = \"view_status_<?php echo $list['type']; ?>\"><?php if (isset($ascp_settings['admin_id_view']) && $ascp_settings['admin_id_view']) { ?><div style=\"float:left\"><?php echo 'ID:'.$num; ?><\/div><?php } ?><div style=\"float:right\"><?php echo $title; ?><\/div><\/a>');
    $('#lists').addClass('sc_hide');

    var allcount = <?php echo (count($ascp_widgets)); ?>;
		$.ajax({
					url: 'index.php?route=module/blog/ajax_list&<?php echo $token_name; ?>=<?php echo $token; ?>',
					type: 'post',
					async: true,
					data: { list: '<?php echo base64_encode($slist); ?>', num: '<?php echo $num; ?>' },
					dataType: 'html',
					beforeSend: function() {
						$('a.mbutton').addClass('loader');
						$('.blog_save').unbind('click');
					},
					complete: function() {

					},
					error: function() {
                    	alert('Error loading widget. Please reload!');
					},
					success: function(html) {
						if (html) {							$('#lists').append('<div id=\"mytabs<?php echo $num; ?>\" class=\"tabcontent\">'+html+'<\/div>');
                            $('#mytabs a').tabs();
	                        $("#amytabs<?php echo $num; ?>").on("click", function() {
								tab = $(this).prop('id');
							});
	        				$('<?php echo $first_key; ?>').click();
	                		$('.blog_save_new').html('<?php echo $button_save; ?> ('+$('.hremove[value!=\'remove\']').length+')' );
						} else {							alert('May be error loading widget. Please reload!')
						}

						progress_num++;
						loading_recent = Math.round((100*progress_num)/allcount);

                        if  (loading_recent > 99) {
							$('a.mbutton').removeClass('loader');
							$('.blog_save').bind('click', form_submit);
							$('#widgets_loading').hide();
							$('#lists').removeClass('sc_hide');

	                        template_auto();
	                        admins_auto();
	                        input_select_change();
	                        odd_even();
	                        $('.help').hide();
	                        widgets_is_loaded = true;
                        }

                        $('#widgets_loading').html('<div style=\"height: 24px; line-height: 24px; text-align: center; width:'+loading_recent+'%; color: white;background-color: #00C600;\">'+loading_recent+'%<\/div>');
					}
				});

		</script>
 <?php   } 	?>
<script type="text/javascript">
	ascp_widgets_num = <?php echo $currentMax ?>;
</script>

<?php  	} 	else 	{     ?>
<script type="text/javascript">
	var ascp_widgets_num = 0;
</script>
<?php } ?>

</div>
</div>

    </form>
     <div style="clear: both; line-height: 1px; font-size: 1px;"></div>
      <div class="buttons right" style="margin-top: 20px;float: right;">

    <a href="#" class="mbutton blog_save_new"><?php echo $button_save; ?></a>
    <a onclick="location = '<?php echo $cancel; ?>';" class="mbutton"><?php echo $button_cancel; ?></a>

      </div>

  </div>



  </div>


</div>

<script>
template_auto = function() {	$('.template').each(function() {

		var e = this;
		var iname = $(e).prop('name');
		var path  = $(e).nextAll('input:first').prop('value');

		$(e).autocomplete({
			'source': function(request, response) {
				$.ajax({
					url: 'index.php?route=module/blog/autocomplete_template&path='+path+'&<?php echo $token_name; ?>=<?php echo $token; ?>',
					dataType: 'json',
					success: function(json) {						response($.map(json, function(item) {
							return {
								label: item.name + ' -> ' + path,
								value: item.name
							}
						}));
					}
				});

			},
			'select': function(event, ui) {

         	<?php
         	if (SC_VERSION < 20) {
         	?>
         	 var veli = ui.item.value;
         	<?php
         	} else {
         	?>
         	var veli = event['value'];
         	<?php
         	}
         	?>
			$('input[name=\''+ iname +'\']').val(veli);

			input_select_change();
			return false;
			}
		});

	});
}
</script>

<script type="text/javascript">

	$('.blog_save').bind('click', form_submit);
    $('.blog_save_new').bind('click', form_submit_new);
</script>

<script>
function odd_even() {

	var kz = 0;
	$('table tr').each(function(i,elem) {
	$(this).removeClass('odd');
	$(this).removeClass('even');
		//if ($(this).is(':visible') && !$(this).hasClass('noeven')) {
		if ($(this) && !$(this).hasClass('noeven')) {
			kz++;
			if (kz % 2 == 0) {
				$(this).addClass('odd');
			}
		}
	});

}


function input_select_change() {

	$('input').each(function(){
		input_this(this);
	});

	$('select').each(function(){
		select_this(this);
	});
}


function select_this(ithis) {
if (!$(ithis).hasClass('no_change')) {
	        $(ithis).removeClass('sc_select_enable');
	        $(ithis).removeClass('sc_select_disable');

			this_val = $(ithis).find('option:selected').val()

			if (this_val == '1' ) {
				$(ithis).addClass('sc_select_enable');
			}

			if (this_val == '0' ) {
				$(ithis).addClass('sc_select_disable');
			}

			if (this_val != '0' && this_val != '1') {
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




$(document).ready(function(){


$(document).on('change', 'select', function(event) {
		select_this(this);
	  });

$(document).on('blur', 'input', function(event) {
		input_this(this)
	  });

});
</script>

<script>

$(document).ready(function(){


//	$('.htabs a').click(function() {
//		odd_even();
//	});

//	$('.vtabs a').click(function() {
//		odd_even();
//	});

});
</script>


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
		if (widgets_is_loaded) {            clearInterval(timer_widget_show_hide_loaded);
			$('.widget_show_hide').each( function () {
				data_widget_class = $(this).attr('data-widget-class');

		  		if (localStorage.getItem(data_widget_class) == 'hidden') {			          $('#view_status_show_hide').show();
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


<script>

admins_auto = function() {
	$('.admins').each(function() {

		var e = this;
		var this_widget = $(e).next().prop('value');

		$(e).autocomplete({
			'source': function(request, response) {         	<?php
         	if (SC_VERSION < 20) {
         	?>
         	 var irequest = request.term;
         	<?php
         	} else {
         	?>
         	var irequest = request;
         	<?php
         	}
         	?>
				$.ajax({
				url: 'index.php?route=agooa/author/authorcomplete&<?php echo $token_name; ?>=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(irequest),
					dataType: 'json',
					success: function(json) {
						response($.map(json, function(item) {
							return {
								label: item.name,
								value: item.customer_id,
							}
						}));
					}
				});

			},
			'select': function(event, ui) {
	         	<?php
	         	if (SC_VERSION < 20) {
	         	?>
	         	 var ilabel = ui.item.label;
	         	 var ivalue = ui.item.value;
	         	<?php
	         	} else {
	         	?>
	         		var ilabel = event['label'];
	         		var ivalue = event['value'];
	         	<?php
	         	}
	         	?>

				$('#admins_'+this_widget+'_' + ivalue).remove();
				$('#admins'+this_widget).append('<div id="admins_'+this_widget+'_' + ivalue + '">' + ilabel + '<img src="view/image/delete.png" class="admins_remove"><input type="hidden" name="ascp_widgets['+this_widget+'][admins][]" value="' + ivalue + '" /></div>');
				$('#admins'+this_widget+'_'+ivalue+' div:odd').prop('class', 'odd');
				$('#admins'+this_widget+'_'+ivalue+' div:even').prop('class', 'even');

				return false;
			}
		});

	});
}

if ($.isFunction($.fn.on)) {

$(document).on('click', '.admins_remove', function() {
	$(this).parent().remove();
});

} else {

$('.admins_remove').live('click',  function() {
	$(this).parent().remove();
});
}
</script>




</div>

<?php echo $footer; ?>
