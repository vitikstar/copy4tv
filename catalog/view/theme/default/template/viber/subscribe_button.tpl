<div class="clearfix" style="margin-bottom:20px;"></div>
<div class="ne-bootstrap ne-modal<?php echo $module; ?> ne-subscribe-button-box">
    <div class="ne-modal">
        <div class="modal-dialog" style="width:100%;padding:0!important;">
            <?php
                $css = '';

                if ($border == 0) {
                    $css .= 'border:0;';
                } elseif ($border == 1) {
                    $css .= 'border:' . $border_width . 'px solid ' . $border_color . ';';
                } elseif ($border == 2) {
                    $css .= 'border:0;border-top:' . $border_width . 'px solid ' . $border_color . ';';
                } elseif ($border == 3) {
                    $css .= 'border:0;border-bottom:' . $border_width . 'px solid ' . $border_color . ';';
                } elseif ($border == 4) {
                    $css .= 'border:0;border-left:' . $border_width . 'px solid ' . $border_color . ';';
                } elseif ($border == 5) {
                    $css .= 'border:0;border-right:' . $border_width . 'px solid ' . $border_color . ';';
                }

                $css .= 'border-radius:' . $border_radius . 'px;';

            ?>
            <div class="modal-content<?php echo $shadow ? '' : ' ne-no-shadow'; ?>" style="<?php echo $css; ?>">
                <div class="modal-body" style="background:<?php echo $form_background_color; ?>;">
                    <div class="form-group" style="margin:0;">
                        <a href="#" class="btn btn-primary ne-submit ne-toggle-modal-<?php echo $module; ?>" style="background:<?php echo $button_background_color; ?>;color:<?php echo $button_text_color; ?>;border-radius:<?php echo $button_border_radius; ?>px;"><?php echo $button_text; ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="ne-bootstrap ne-modal-wrapper ne-popup-inactive <?php echo $class; ?> ne-modal<?php echo $module; ?>" tabindex="-1">
    <div class="ne-modal fade" id="ne-modal<?php echo $module; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" style="max-width:<?php echo $popup_width; ?>px;">
            <?php if ($popup_timeout) { ?>
                <span class="ne-modal-close ne-timeout" id="ne-timer<?php echo $module; ?>"><?php echo $popup_timeout; ?></span>
            <?php } else { ?>
                <a href="#" class="ne-modal-close" data-dismiss="modal"><span>&times;<span></a>
            <?php } ?>
            <?php
                $css = '';

                if ($border == 0) {
                    $css .= 'border:0;';
                } elseif ($border == 1) {
                    $css .= 'border:' . $border_width . 'px solid ' . $border_color . ';';
                } elseif ($border == 2) {
                    $css .= 'border:0;border-top:' . $border_width . 'px solid ' . $border_color . ';';
                } elseif ($border == 3) {
                    $css .= 'border:0;border-bottom:' . $border_width . 'px solid ' . $border_color . ';';
                } elseif ($border == 4) {
                    $css .= 'border:0;border-left:' . $border_width . 'px solid ' . $border_color . ';';
                } elseif ($border == 5) {
                    $css .= 'border:0;border-right:' . $border_width . 'px solid ' . $border_color . ';';
                }

                $css .= 'border-radius:' . $border_radius . 'px;';

            ?>
            <div class="modal-content<?php echo $shadow ? '' : ' ne-no-shadow'; ?>" style="<?php echo $css; ?>">
                <div class="modal-content-inner">
                    <div class="modal-header" style="background:<?php echo $content_background_color; ?>;border-top-left-radius:<?php echo $border_radius; ?>px;border-top-right-radius:<?php echo $border_radius; ?>px;">
                        <?php if ($image) { ?>
                            <?php if ($image_position == 1) { ?>
                                <?php if ($image_circled) { ?>
                                    <div class="img-responsive<?php echo $hide_image_on_mobile ? ' hidden-xs' : ''; ?>" style="background-image:url(<?php echo $image; ?>);background-size:cover;background-position:50%;width:<?php echo $image_width; ?>px;height:<?php echo $image_width; ?>px;border-radius:50%;padding:15px;margin:auto;"></div>
                                <?php } else { ?>
                                    <img src="<?php echo $image; ?>" alt="" class="img-responsive<?php echo $hide_image_on_mobile ? ' hidden-xs' : ''; ?>" style="max-width:<?php echo $image_width; ?>px;">
                                <?php } ?>
                                <div class="ne-modal-text text-center">
                                    <?php if ($heading) { ?>
                                        <h2 style="color:<?php echo $heading_text_color; ?>;"><?php echo $heading; ?></h2>
                                    <?php } ?>
                                    <?php if ($text) { ?>
                                        <p style="color:<?php echo $content_text_color; ?>;"><?php echo $text; ?></p>
                                    <?php } ?>
                                </div>
                            <?php } elseif ($image_position == 2) { ?>
                                <div class="ne-modal-text text-center">
                                    <?php if ($heading) { ?>
                                        <h2 style="color:<?php echo $heading_text_color; ?>;"><?php echo $heading; ?></h2>
                                    <?php } ?>
                                    <?php if ($text) { ?>
                                        <p style="color:<?php echo $content_text_color; ?>;"><?php echo $text; ?></p>
                                    <?php } ?>
                                </div>
                                <?php if ($image_circled) { ?>
                                    <div class="img-responsive<?php echo $hide_image_on_mobile ? ' hidden-xs' : ''; ?>" style="background-image:url(<?php echo $image; ?>);background-size:cover;background-position:50%;width:<?php echo $image_width; ?>px;height:<?php echo $image_width; ?>px;border-radius:50%;padding:15px;margin:auto;"></div>
                                <?php } else { ?>
                                    <img src="<?php echo $image; ?>" alt="" class="img-responsive<?php echo $hide_image_on_mobile ? ' hidden-xs' : ''; ?>" style="max-width:<?php echo $image_width; ?>px;">
                                <?php } ?>
                            <?php } elseif ($image_position == 3) { ?>
                                <div class="row">
                                    <div class="col-sm-12 col-md-4">
                                        <?php if ($image_circled) { ?>
                                            <div class="img-responsive<?php echo $hide_image_on_mobile ? ' hidden-xs' : ''; ?>" style="background-image:url(<?php echo $image; ?>);background-size:cover;background-position:50%;width:<?php echo $image_width; ?>px;height:<?php echo $image_width; ?>px;border-radius:50%;padding:15px;margin:auto;"></div>
                                        <?php } else { ?>
                                            <img src="<?php echo $image; ?>" alt="" class="img-responsive<?php echo $hide_image_on_mobile ? ' hidden-xs' : ''; ?>" style="max-width:<?php echo $image_width; ?>px;">
                                        <?php } ?>
                                    </div>
                                    <div class="col-sm-12 col-md-8">
                                        <div class="ne-modal-text">
                                            <?php if ($heading) { ?>
                                                <h2 style="color:<?php echo $heading_text_color; ?>;"><?php echo $heading; ?></h2>
                                            <?php } ?>
                                            <?php if ($text) { ?>
                                                <p style="color:<?php echo $content_text_color; ?>;"><?php echo $text; ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } elseif ($image_position == 4) { ?>
                                <div class="row">
                                    <div class="col-sm-12 col-md-8">
                                        <div class="ne-modal-text">
                                            <?php if ($heading) { ?>
                                                <h2 style="color:<?php echo $heading_text_color; ?>;"><?php echo $heading; ?></h2>
                                            <?php } ?>
                                            <?php if ($text) { ?>
                                                <p style="color:<?php echo $content_text_color; ?>;"><?php echo $text; ?></p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4">
                                        <?php if ($image_circled) { ?>
                                            <div class="img-responsive<?php echo $hide_image_on_mobile ? ' hidden-xs' : ''; ?>" style="background-image:url(<?php echo $image; ?>);background-size:cover;background-position:50%;width:<?php echo $image_width; ?>px;height:<?php echo $image_width; ?>px;border-radius:50%;padding:15px;margin:auto;"></div>
                                        <?php } else { ?>
                                            <img src="<?php echo $image; ?>" alt="" class="img-responsive<?php echo $hide_image_on_mobile ? ' hidden-xs' : ''; ?>" style="max-width:<?php echo $image_width; ?>px;">
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <div class="ne-modal-text text-center">
                                <?php if ($heading) { ?>
                                    <h2 style="color:<?php echo $heading_text_color; ?>;"><?php echo $heading; ?></h2>
                                <?php } ?>
                                <?php if ($text) { ?>
                                    <p style="color:<?php echo $content_text_color; ?>;"><?php echo $text; ?></p>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="modal-body" style="background:<?php echo $form_background_color; ?>;">
                        <?php if ($content_divider == 2) { ?>
                            <svg class="ne-svg-triangle" xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" height="20" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0 0 L50 100 L100 0 Z" fill="<?php echo $content_background_color; ?>"></path></svg>
                        <?php } ?>
                        <?php if (!$is_logged) { ?>
                            <?php if ($field_orientation == 0) { ?>
                                <?php if ($fields > 1) { ?>
                                    <div class="form-group">
                                        <input type="text" class="form-control" style="border:1px solid <?php echo $field_border_color; ?>;border-radius:<?php echo $field_border_radius; ?>px;" name="ne_name" placeholder="<?php echo $fields == 2 ? $entry_name : $entry_firstname; ?>" />
                                    </div>
                                <?php } ?>
                                <?php if ($fields == 3) { ?>
                                    <div class="form-group">
                                        <input type="text" class="form-control" style="border:1px solid <?php echo $field_border_color; ?>;border-radius:<?php echo $field_border_radius; ?>px;" name="ne_lastname" placeholder="<?php echo $entry_lastname; ?>" />
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <input type="email" class="form-control" style="border:1px solid <?php echo $field_border_color; ?>;border-radius:<?php echo $field_border_radius; ?>px;" id="ne_email" name="ne_email" placeholder="<?php echo $entry_email; ?>" />
                                </div>
                                <?php if ($marketing_list) { ?>
                                    <label><?php echo $entry_list; ?></label>
                                    <div class="form-group">
                                        <?php foreach ($marketing_list as $key => $list) { ?>
                                            <div class="<?php echo $list_type ? 'radio' : 'checkbox'; ?>">
                                                <input class="ne-subscribe-list" id="ne-list<?php echo $key; ?>" name="ne_list[]" type="<?php echo $list_type ? 'radio' : 'checkbox'; ?>" value="<?php echo $key; ?>"><label for="ne-list<?php echo $key; ?>">&nbsp;<?php echo $list[$language_id]; ?></label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php if ($accept) { ?>
                                    <div class="form-group ne-accept">
                                        <div class="checkbox">
                                            <input name="ne_accept" type="checkbox" value="1" id="ne-accept"><label for="ne-accept" style="color:<?php echo $button_background_color; ?>;"><?php echo $accept_text; ?></label>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <a href="#" class="btn btn-primary ne-submit ne-send" style="background:<?php echo $button_background_color; ?>;color:<?php echo $button_text_color; ?>;border-radius:<?php echo $button_border_radius; ?>px;"><?php echo $button_text; ?></a>
                                </div>
                            <?php } elseif ($field_orientation == 1) { ?>
                                <?php if ($fields == 3) { ?>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" style="border:1px solid <?php echo $field_border_color; ?>;border-radius:<?php echo $field_border_radius; ?>px;" name="ne_name" placeholder="<?php echo $fields == 2 ? $entry_name : $entry_firstname; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" style="border:1px solid <?php echo $field_border_color; ?>;border-radius:<?php echo $field_border_radius; ?>px;" name="ne_lastname" placeholder="<?php echo $entry_lastname; ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control" style="border:1px solid <?php echo $field_border_color; ?>;border-radius:<?php echo $field_border_radius; ?>px;" id="ne_email" name="ne_email" placeholder="<?php echo $entry_email; ?>" />
                                    </div>
                                <?php } elseif ($fields == 2) { ?>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="text" class="form-control" style="border:1px solid <?php echo $field_border_color; ?>;border-radius:<?php echo $field_border_radius; ?>px;" name="ne_name" placeholder="<?php echo $fields == 2 ? $entry_name : $entry_firstname; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="email" class="form-control" style="border:1px solid <?php echo $field_border_color; ?>;border-radius:<?php echo $field_border_radius; ?>px;" id="ne_email" name="ne_email" placeholder="<?php echo $entry_email; ?>" />
                                            </div>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="form-group">
                                        <input type="email" class="form-control" style="border:1px solid <?php echo $field_border_color; ?>;border-radius:<?php echo $field_border_radius; ?>px;" id="ne_email" name="ne_email" placeholder="<?php echo $entry_email; ?>" />
                                    </div>
                                <?php } ?>
                                <?php if ($marketing_list) { ?>
                                    <label><?php echo $entry_list; ?></label>
                                    <div class="form-group">
                                        <?php foreach ($marketing_list as $key => $list) { ?>
                                            <div class="<?php echo $list_type ? 'radio' : 'checkbox'; ?>">
                                                <input class="ne-subscribe-list" id="ne-list<?php echo $key; ?>" name="ne_list[]" type="<?php echo $list_type ? 'radio' : 'checkbox'; ?>" value="<?php echo $key; ?>"><label for="ne-list<?php echo $key; ?>">&nbsp;<?php echo $list[$language_id]; ?></label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php if ($accept) { ?>
                                    <div class="form-group ne-accept">
                                        <div class="checkbox">
                                            <input name="ne_accept" type="checkbox" value="1" id="ne-accept"><label for="ne-accept" style="color:<?php echo $button_background_color; ?>;"><?php echo $accept_text; ?></label>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="form-group">
                                    <a href="#" class="btn btn-primary ne-submit ne-send" style="background:<?php echo $button_background_color; ?>;color:<?php echo $button_text_color; ?>;border-radius:<?php echo $button_border_radius; ?>px;"><?php echo $button_text; ?></a>
                                </div>
                            <?php } elseif ($field_orientation == 2) { ?>
                                <?php if ($marketing_list) { ?>
                                    <label><?php echo $entry_list; ?></label>
                                    <div class="form-group">
                                        <?php foreach ($marketing_list as $key => $list) { ?>
                                            <div class="<?php echo $list_type ? 'radio-inline' : 'checkbox-inline'; ?>">
                                                <input class="ne-subscribe-list" id="ne-list<?php echo $key; ?>" name="ne_list[]" type="<?php echo $list_type ? 'radio' : 'checkbox'; ?>" value="<?php echo $key; ?>"><label for="ne-list<?php echo $key; ?>">&nbsp;<?php echo $list[$language_id]; ?></label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php if ($fields == 3) { ?>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" style="border:1px solid <?php echo $field_border_color; ?>;border-radius:<?php echo $field_border_radius; ?>px;" name="ne_name" placeholder="<?php echo $fields == 2 ? $entry_name : $entry_firstname; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <input type="text" class="form-control" style="border:1px solid <?php echo $field_border_color; ?>;border-radius:<?php echo $field_border_radius; ?>px;" name="ne_lastname" placeholder="<?php echo $entry_lastname; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <input type="email" class="form-control" style="border:1px solid <?php echo $field_border_color; ?>;border-radius:<?php echo $field_border_radius; ?>px;" id="ne_email" name="ne_email" placeholder="<?php echo $entry_email; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <a href="#" class="btn btn-primary ne-submit ne-send" style="background:<?php echo $button_background_color; ?>;color:<?php echo $button_text_color; ?>;border-radius:<?php echo $button_border_radius; ?>px;"><?php echo $button_text; ?></a>
                                            </div>
                                        </div>
                                        <?php if ($accept) { ?>
                                            <div class="col-sm-12">
                                                <div class="form-group ne-accept">
                                                    <div class="checkbox">
                                                        <input name="ne_accept" type="checkbox" value="1" id="ne-accept"><label for="ne-accept" style="color:<?php echo $button_background_color; ?>;"><?php echo $accept_text; ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } elseif ($fields == 2) { ?>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" style="border:1px solid <?php echo $field_border_color; ?>;border-radius:<?php echo $field_border_radius; ?>px;" name="ne_name" placeholder="<?php echo $fields == 2 ? $entry_name : $entry_firstname; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <input type="email" class="form-control" style="border:1px solid <?php echo $field_border_color; ?>;border-radius:<?php echo $field_border_radius; ?>px;" id="ne_email" name="ne_email" placeholder="<?php echo $entry_email; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <a href="#" class="btn btn-primary ne-submit ne-send" style="background:<?php echo $button_background_color; ?>;color:<?php echo $button_text_color; ?>;border-radius:<?php echo $button_border_radius; ?>px;"><?php echo $button_text; ?></a>
                                            </div>
                                        </div>
                                        <?php if ($accept) { ?>
                                            <div class="col-sm-12">
                                                <div class="form-group ne-accept">
                                                    <div class="checkbox">
                                                        <input name="ne_accept" type="checkbox" value="1" id="ne-accept"><label for="ne-accept" style="color:<?php echo $button_background_color; ?>;"><?php echo $accept_text; ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <input type="email" class="form-control" style="border:1px solid <?php echo $field_border_color; ?>;border-radius:<?php echo $field_border_radius; ?>px;" id="ne_email" name="ne_email" placeholder="<?php echo $entry_email; ?>" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <a href="#" class="btn btn-primary ne-submit ne-send" style="background:<?php echo $button_background_color; ?>;color:<?php echo $button_text_color; ?>;border-radius:<?php echo $button_border_radius; ?>px;"><?php echo $button_text; ?></a>
                                            </div>
                                        </div>
                                        <?php if ($accept) { ?>
                                            <div class="col-sm-12">
                                                <div class="form-group ne-accept">
                                                    <div class="checkbox">
                                                        <input name="ne_accept" type="checkbox" value="1" id="ne-accept"><label for="ne-accept" style="color:<?php echo $button_background_color; ?>;"><?php echo $accept_text; ?></label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        <?php } else { ?>
                            <?php if ($accept) { ?>
                                <div class="form-group ne-accept">
                                    <div class="checkbox">
                                        <input name="ne_accept" type="checkbox" value="1" id="ne-accept"><label for="ne-accept" style="color:<?php echo $button_background_color; ?>;"><?php echo $accept_text; ?></label>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <a href="#" class="btn btn-primary ne-submit ne-send" style="background:<?php echo $button_background_color; ?>;color:<?php echo $button_text_color; ?>;border-radius:<?php echo $button_border_radius; ?>px;"><?php echo $button_text; ?></a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
jQuery(function ($) {
    $('.ne-modal-wrapper.ne_modal<?php echo $module; ?>').prependTo('body');

    $('a.ne-toggle-modal-<?php echo $module; ?>').click(function(){
        $('#ne-modal<?php echo $module; ?>').modal({
            backdrop: "static",
            keyboard: false
        });
        return false;
    });

    function onResize() {
        var height = $(window).height() * 0.8;
        if (height > $('#ne-modal<?php echo $module; ?> .modal-content-inner').outerHeight()) {
            height = $('#ne-modal<?php echo $module; ?> .modal-content-inner').outerHeight();
        }
        $('#ne-modal<?php echo $module; ?> .modal-content').css('max-height', height + 'px');
        $('#ne-modal<?php echo $module; ?> .modal-content').slimScroll({
            height: height + 'px'
        });
    }

    $(window).resize(onResize);

    $('#ne-modal<?php echo $module; ?> a.ne-submit.ne-send').click(function(){

        $('#ne-modal<?php echo $module; ?> .form-group').addClass('ne-disabled');
        $('#ne-modal<?php echo $module; ?> .modal-body').append('<svg width="40" height="40" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" class="ne-spin"><path d="M526 1394q0 53-37.5 90.5t-90.5 37.5q-52 0-90-38t-38-90q0-53 37.5-90.5t90.5-37.5 90.5 37.5 37.5 90.5zm498 206q0 53-37.5 90.5t-90.5 37.5-90.5-37.5-37.5-90.5 37.5-90.5 90.5-37.5 90.5 37.5 37.5 90.5zm-704-704q0 53-37.5 90.5t-90.5 37.5-90.5-37.5-37.5-90.5 37.5-90.5 90.5-37.5 90.5 37.5 37.5 90.5zm1202 498q0 52-38 90t-90 38q-53 0-90.5-37.5t-37.5-90.5 37.5-90.5 90.5-37.5 90.5 37.5 37.5 90.5zm-964-996q0 66-47 113t-113 47-113-47-47-113 47-113 113-47 113 47 47 113zm1170 498q0 53-37.5 90.5t-90.5 37.5-90.5-37.5-37.5-90.5 37.5-90.5 90.5-37.5 90.5 37.5 37.5 90.5zm-640-704q0 80-56 136t-136 56-136-56-56-136 56-136 136-56 136 56 56 136zm530 206q0 93-66 158.5t-158 65.5q-93 0-158.5-65.5t-65.5-158.5q0-92 65.5-158t158.5-66q92 0 158 66t66 158z" fill="<?php echo $button_background_color; ?>"/></svg>');

        var list = $('#ne-modal<?php echo $module; ?> .ne-subscribe-list:checked').map(function(i,n) {
            return $(n).val();
        }).get();

        $.post("<?php echo $subscribe; ?>", {
            email: $('#ne-modal<?php echo $module; ?> input[name="ne_email"]').val(),
            <?php if ($accept) { ?>accept: ($('#ne-modal<?php echo $module; ?> input[name="ne_accept"]').prop('checked') ? 1 : 0), <?php } ?>
            <?php if ($fields > 1) { ?>name: $('#ne-modal<?php echo $module; ?> input[name="ne_name"]').val(), <?php } ?>
            <?php if ($fields == 3) { ?>lastname: $('#ne-modal<?php echo $module; ?> input[name="ne_lastname"]').val(), <?php } ?>
            'list[]': list
        }, function(data) {
            if (data) {
                $("#ne-modal<?php echo $module; ?> .ne-error").remove();
                $('#ne-modal<?php echo $module; ?> .ne-spin').remove();
                $('#ne-modal<?php echo $module; ?> .form-group').removeClass('ne-disabled');

                if (data.type == 'success') {
                    $.cookie('ne_subscribed', true, {expires: 365, path: '/'});
                    $('#ne-modal<?php echo $module; ?> .modal-body').slideUp(400, function(){
                        $('#ne-modal<?php echo $module; ?> .form-group').remove();
                        $('#ne-modal<?php echo $module; ?> .modal-body').append('<div class="form-group"><p class="text-center" style="color:<?php echo $form_success_text_color; ?>;">' + data.message + '</p></div><div class="form-group"><a href="#" data-dismiss="modal" class="btn btn-primary ne-submit" style="background:<?php echo $button_background_color; ?>;color:<?php echo $button_text_color; ?>;border-radius:<?php echo $button_border_radius; ?>px;"><?php echo $text_close; ?></a></div>');
                        $('#ne-modal<?php echo $module; ?> .modal-body').slideDown(400);
                    });
                } else {
                    $('#ne-modal<?php echo $module; ?> .modal-body').prepend('<p class="ne-error text-center" style="display:none;color:<?php echo $form_error_text_color; ?>;">' + data.message + '</p>');
                }

                $("#ne-modal<?php echo $module; ?> .ne-error").slideDown(200);

                $("#ne-modal<?php echo $module; ?> .ne-error").delay(3000).slideUp(200, function(){
                    $(this).remove();
                });
            } else {
                $('#ne-modal<?php echo $module; ?> .modal-body').prepend('<p class="ne-error text-center" style="display:none;color:<?php echo $form_error_text_color; ?>;"><?php echo $text_connection_error; ?></p>');
            }
        }, "json");

        return false;
    });

    $('#ne-modal<?php echo $module; ?>').on('shown.bs.modal', function () {
        $('.ne-modal-wrapper.ne-modal<?php echo $module; ?>').removeClass('ne-popup-inactive');
        $('body').addClass('modal-open');
        $(window).resize();
    });

    $('#ne-modal<?php echo $module; ?>').on('hidden.bs.modal', function () {
        $('.ne-modal-wrapper.ne-modal<?php echo $module; ?>').addClass('ne-popup-inactive');
        $('body').removeClass('modal-open');
    });
});
//--></script>