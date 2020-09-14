<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">

  <div class="page-header">

    <div class="container-fluid">

      <div class="pull-right">

        <button type="submit" form="form" formaction="<?php echo $action; ?>" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>

        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>

        <?php if ($oct_megamenus) { ?>

        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form').submit() : false;"><i class="fa fa-trash-o"></i></button>

        <?php } ?>

        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>

      </div>

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

    <?php if ($success) { ?>

    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>

      <button type="button" class="close" data-dismiss="alert">&times;</button>

    </div>

    <?php } ?>

    <div class="panel panel-default">

      <div class="panel-heading">

        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>

      </div>

      <div class="panel-body">

        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">      

          <ul class="nav nav-tabs">

            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>

          </ul> 

          <div class="tab-content">

            <div class="tab-pane active" id="tab-general">

              <div class="form-group">

                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>

                <div class="col-sm-10">

                  <select name="oct_megamenu_data[status]" id="input-status" class="form-control">

                    <?php if ($oct_megamenu_data['status']) { ?>

                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>

                    <option value="0"><?php echo $text_disabled; ?></option>

                    <?php } else { ?>

                    <option value="1"><?php echo $text_enabled; ?></option>

                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>

                    <?php } ?>

                  </select>

                </div>

              </div>

              <div class="table-responsive">

                <table class="table table-bordered table-hover">

                  <thead>

                    <tr>

                      <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>

                      <td class="text-left"><?php if ($sort == 'otmmd.title') { ?>

                        <a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_title; ?></a>

                        <?php } else { ?>

                        <a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>

                        <?php } ?></td>

                       <td class="text-left"><?php if ($sort == 'otmmd.link') { ?>

                        <a href="<?php echo $sort_link; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_link; ?></a>

                        <?php } else { ?>

                        <a href="<?php echo $sort_link; ?>"><?php echo $column_link; ?></a>

                        <?php } ?></td>

                      <td class="text-right"><?php if ($sort == 'otmm.sort_order') { ?>

                        <a href="<?php echo $sort_sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>

                        <?php } else { ?>

                        <a href="<?php echo $sort_sort_order; ?>"><?php echo $column_sort_order; ?></a>

                        <?php } ?></td>

                      <td class="text-right"><?php echo $column_action; ?></td>

                    </tr>

                  </thead>

                  <tbody>

                    <?php if ($oct_megamenus) { ?>

                    <?php foreach ($oct_megamenus as $oct_megamenu) { ?>

                    <tr>

                      <td class="text-center"><?php if (in_array($oct_megamenu['megamenu_id'], $selected)) { ?>

                        <input type="checkbox" name="selected[]" value="<?php echo $oct_megamenu['megamenu_id']; ?>" checked="checked" />

                        <?php } else { ?>

                        <input type="checkbox" name="selected[]" value="<?php echo $oct_megamenu['megamenu_id']; ?>" />

                        <?php } ?></td>

                      <td class="text-left"><?php echo $oct_megamenu['title']; ?></td>

                      <td class="text-left"><?php echo $oct_megamenu['link']; ?></td>

                      <td class="text-right"><?php echo $oct_megamenu['sort_order']; ?></td>

                      <td class="text-right"><a href="<?php echo $oct_megamenu['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>

                    </tr>

                    <?php } ?>

                    <?php } else { ?>

                    <tr>

                      <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>

                    </tr>

                    <?php } ?>

                  </tbody>

                </table>

              </div>

              <div class="row">

                <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>

                <div class="col-sm-6 text-right"><?php echo $results; ?></div>

              </div>

            </div>

          </div>

        </form>

      </div>

    </div>

  </div>

</div>

<?php echo $footer; ?>