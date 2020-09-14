	<div class="group_button_top" id="agoo_second_menu">

	<span id="agoo_first_menu_block">
		<a href="<?php echo $agoo_menu_url_options; ?>" class="markbutton<?php echo $agoo_menu_active_options; ?>"><div><i class="fa fa-home" aria-hidden="true"></i></div>
		<div><?php echo $agoo_menu_options; ?></div></a>

		<a href="<?php echo $agoo_menu_url_layouts; ?>" class="markbutton<?php echo $agoo_menu_active_layouts; ?>"><div><i class="fa fa-bars" aria-hidden="true"></i></div>
		<div><?php echo $agoo_menu_layouts; ?></div></a>


		<a href="<?php echo $agoo_menu_url_widgets; ?>" class="markbutton<?php echo $agoo_menu_active_widgets; ?>"><div><i class="fa fa-asterisk fa-fw" aria-hidden="true"></i></div>
		<div style=" "><?php echo $agoo_menu_widgets; ?></div></a>

    </span>

    <span id="agoo_second_menu_block">
		<?php if (isset($ascp_settings['latest_widget_status']) && $ascp_settings['latest_widget_status']) { ?>
		<a href="<?php echo $agoo_menu_url_categories; ?>" class="markbutton<?php echo $agoo_menu_active_categories; ?>"><div><i class="fa fa-list-ul" aria-hidden="true"></i></div>
		<div style=" "><?php echo $agoo_menu_categories; ?></div></a>

		<a href="<?php echo $agoo_menu_url_records; ?>" class="markbutton<?php echo $agoo_menu_active_records; ?>"><div><i class="fa fa-align-justify" aria-hidden="true"></i></div>
		<div><?php echo $agoo_menu_records; ?></div></a>
        <?php } ?>

		<?php if (isset($ascp_settings['reviews_widget_status']) && $ascp_settings['reviews_widget_status']) { ?>
		<?php if (isset($ascp_settings['latest_widget_status']) && $ascp_settings['latest_widget_status']) { ?>
		<a href="<?php echo $agoo_menu_url_comments; ?>" class="markbutton<?php echo $agoo_menu_active_comments; ?>"><div><i class="fa fa-comments-o" aria-hidden="true"></i></div>
		<div><?php echo $agoo_menu_comments; ?></div></a>
        <?php } ?>

		<a href="<?php echo $agoo_menu_url_reviews; ?>" class="markbutton<?php echo $agoo_menu_active_reviews; ?>"><div><i class="fa fa-comments" aria-hidden="true"></i></div>
		<div><?php echo $agoo_menu_reviews; ?></div></a>
         <?php } ?>
	</span>


    <span id="agoo_third_menu_block" style="display: none;">
		<a href="<?php echo $agoo_menu_url_adapter; ?>" class="markbutton<?php echo $agoo_menu_active_adapter; ?>"><div><i class="fa fa-refresh" aria-hidden="true"></i></div>
		<div style=" "><?php echo $agoo_menu_adapter; ?></div></a>

		<?php if (isset( $ascp_settings['reviews_widget_status']) && $ascp_settings['reviews_widget_status']) { ?>
		<a href="<?php echo $agoo_menu_url_fields; ?>" class="markbutton<?php echo $agoo_menu_active_fields; ?>"><div><i class="fa fa-file-text" aria-hidden="true"></i></div>
		<div><?php echo $agoo_menu_fields; ?></div></a>
        <?php } ?>
		<a href="<?php echo $agoo_menu_url_sitemap; ?>" class="markbutton<?php echo $agoo_menu_active_sitemap; ?>"><div><i class="fa fa-sitemap"></i></div>
		<div style=" "><?php echo $agoo_menu_sitemap; ?></div></a>

	</span>


		<a href="<?php echo $agoo_menu_url_modules; ?>" class="markbutton"><div><i class="fa fa-angle-double-left" aria-hidden="true"></i></div>
		<div><?php echo $agoo_menu_modules; ?></div></a>

	</div>


	<div style="margin-left:0px; float:left;" id="agoo_first_menu">

		<a onclick="$('.group_button_top').toggle('slow', function() {
		        if ($('.updown').hasClass('button_down')) {
		            $('.updown').removeClass('button_down').addClass('button_up');
					$('.updown').html('<i class=\'fa fa-angle-double-up\' aria-hidden=\'true\'></i>');
		            $('.up_down').removeClass('markactive');
		        } else {
		            $('.updown').removeClass('button_up').addClass('button_down');
		            $('.updown').html('<i class=\'fa fa-angle-double-down\' aria-hidden=\'true\'></i>');
		            $('.up_down').addClass('markactive');
		        }
		    });" class="up_down markbutton"><div class="updown button_up"></div>
		</a>


	</div>

<script>
	$('#agoo_first_menu').append( $('#<?php echo $agoo_menu_block;?>').show() );
	$('.updown').html('<i class=\'fa fa-angle-double-up\' aria-hidden=\'true\'></i>');
</script>