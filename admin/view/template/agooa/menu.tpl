    <?php $jc_i = 16000; ?>
    <?php foreach ($menus as $menu) { ?>
    <li id="<?php echo $menu['id']; ?>" style="display: none;">
      <?php if ($menu['href']) { ?>
      <a href="<?php echo $menu['href']; ?>"><i class="fa <?php echo $menu['icon']; ?> fw"></i> <span><?php echo $menu['name']; ?></span></a>
      <?php } else { ?>
      <a href="#collapse<?php echo $jc_i; ?>" class="parent collapsed" data-toggle="collapse"><i class="fa <?php echo $menu['icon']; ?> fw"></i> <span><?php echo $menu['name']; ?></span></a>
      <?php } ?>
      <?php if ($menu['children']) { ?>
      <ul<?php if (SC_VERSION > 23) { ?> id="collapse<?php echo $jc_i; ?>" class="collapse"<?php } ?>>
        <?php foreach ($menu['children'] as $children_1) { ?>
        <li>
          <?php if ($children_1['href']) { ?>
          <a href="<?php echo $children_1['href']; ?>"><?php echo $children_1['name']; ?></a>
          <?php } else { ?>
          <a href="#collapse<?php echo $jc_i; ?>" class="parent collapsed" data-toggle="collapse"><?php echo $children_1['name']; ?></a>
          <?php } ?>
          <?php if ($children_1['children']) { ?>
          <ul<?php if (SC_VERSION > 23) { ?> id="collapse<?php echo $jc_i; ?>" class="collapse"<?php } ?>>
            <?php foreach ($children_1['children'] as $children_2) { ?>
            <li>
              <?php if ($children_2['href']) { ?>
              <a href="<?php echo $children_2['href']; ?>"><?php echo $children_2['name']; ?></a>
              <?php } else { ?>
              <a href="#collapse<?php echo $jc_i; ?>" class="parent collapsed" data-toggle="collapse"><?php echo $children_2['name']; ?></a>
              <?php } ?>
              <?php if ($children_2['children']) { ?>
              <ul<?php if (SC_VERSION > 23) { ?> id="collapse<?php echo $jc_i; ?>" class="collapse"<?php } ?>>
                <?php foreach ($children_2['children'] as $children_3) { ?>
                <li><a href="<?php echo $children_3['href']; ?>"><?php echo $children_3['name']; ?></a></li>
                <?php } ?>
              </ul>
              <?php } ?>
            </li>
            <?php $jc_i++; ?>
            <?php } ?>
          </ul>
          <?php } ?>
        </li>
        <?php $jc_i++; ?>
        <?php } ?>
      </ul>
      <?php } ?>
    </li>
    <?php $jc_i++; ?>
    <?php } ?>

<script>

	agoo_menu_order_setting = <?php reset($menus); echo key($menus); ?>;
	agoo_menu = $('#<?php echo $menus_id; ?>').clone();
	$('#<?php echo $menus_id; ?>').remove();
    <?php
      if (SC_VERSION < 20) {
      	$agoo_selector = '#menu > ul > li';
      } else {
      	$agoo_selector = '#menu > li';
      }
    ?>
	agoo_menu_li_length = $('<?php echo $agoo_selector; ?>').length;

	if (agoo_menu_order_setting >= agoo_menu_li_length) {
		agoo_menu_order = agoo_menu_li_length;
	} else {
    	agoo_menu_order = agoo_menu_order_setting;
	}

	$('<?php echo $agoo_selector; ?>:nth-child(' + agoo_menu_order + ')').after(agoo_menu);

	<?php if (SC_VERSION < 20) { ?>
	$('<?php echo $agoo_selector; ?> > a').addClass('top');
	<?php } ?>

	$('#<?php echo $menus_id; ?>').show();

</script>

