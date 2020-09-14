<?php if ($items) { ?>
<style>
	.show-menu{
		margin-top: 0 !important;
		opacity: 1 !important;
		visibility: visible !important;
	}
</style>
<script>
    function showMenu(){
        if($(".megamenu-wrapper").attr('class')=='megamenu-wrapper') $(".megamenu-wrapper").addClass("show-menu");
        else $(".megamenu-wrapper").removeClass("show-menu");
    }
</script>
<div class="col-md-3">
	<div id="megamenu" class="oct_container-megamenu vertical">
		<div id="oct_menu_header">
			<div class="oct_megamenutoggle-wrapper" onclick="showMenu()"><i class="fa fa-bars"></i><?php echo $text_category; ?></div>
		</div>
		<div class="megamenu-wrapper">
			<ul class="oct_megamenu shift-left">
				<li>
					<p class="oct_close-menu"></p>
					<p class="oct_open-menu mobile-disabled"></p>
					<a href="<?php echo $href_all_product; ?>" class="clearfix"><?php echo $text_all_product; ?></a>
				</li>
				<?php foreach ($items as $item) { ?>
				<?php if ($item['children']) { ?>
				<?php if ($item['item_type'] == 2) { ?>
				<?php #start menu type - category ?>
				<?php if ($item['display_type'] == 1) { ?>
				<li class="with-sub-menu hover simple-menu">
					<p class="oct_close-menu"></p>
					<p class="oct_open-menu"></p>
					<a href="<?php echo $item['href']; ?>" class="clearfix" <?php echo ($item['open_link_type']) ? 'target="_blank"' : ''; ?>><?php if ($item['image']) { ?><img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" /><?php } ?><?php echo $item['title']; ?></a>
					<div class="sub-menu">
						<div class="content">
							<div class="row">
								<div class="col-sm-12 mobile-enabled">
									<div class="row">
										<?php foreach ($item['children'] as $children) { ?>
										<?php if ($children['children']) { ?>
										<div class="col-sm-12 static-menu parent-static-menu">
											<div class="menu">
												<a href="<?php echo $children['href']; ?>" class="main-menu with-submenu"><?php echo $children['name']; ?></a>
												<div class="oct_show_cat"></div>
												<div class="oct_hide_cat"></div>
												<ul class="children-classic">
													<?php foreach ($children['children'] as $child) { ?>
													<li><a href="<?php echo $child['href']; ?>" ><?php echo $child['name']; ?></a></li>
													<?php } ?>
												</ul>
											</div>
										</div>
										<?php }  else { ?>
										<div class="col-sm-12 static-menu">
											<div class="menu">
												<ul>
													<li>
														<a href="<?php echo $children['href']; ?>" class="main-menu with-submenu"><?php echo $children['name']; ?></a>

													</li>
												</ul>
											</div>
										</div>
										<?php } ?>
										<?php } ?>
									</div>
								</div>
								<?php if ($item['description']) { ?>
								<div class="col-sm-4 mobile-enabled ocmm-description"><?php echo $item['description']; ?></div>
								<?php } ?>
							</div>
						</div>
					</div>
				</li>
				<?php } else { ?>
				<li class="with-sub-menu hover">
					<p class="oct_close-menu"></p>
					<p class="oct_open-menu"></p>
					<a href="<?php echo $item['href']; ?>" class="clearfix" <?php echo ($item['open_link_type']) ? 'target="_blank"' : ''; ?>><?php if ($item['image']) { ?><img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" /><?php } ?><?php echo $item['title']; ?></a>

					<div class="sub-menu wide-sub-menu" style="width: 340%; right: 0px;">
						<div class="content">
							<div class="row">
								<div class="col-sm-<?php if (strlen($item['description']) < 15) { ?>12<?php } else { ?>8<?php } ?> mobile-enabled">
									<div class="row wide-menu-row">
										<?php foreach ($item['children'] as $children) { ?>
										<?php if ($children['children']) { ?>
										<div class="col-sm-<?php if (strlen($item['description']) < 15) { ?>3<?php } else { ?>4<?php } ?> static-menu <?php if ($item['show_img'] == 1) {echo "with-img";} else {echo "without-img";} ?>">
											<div class="menu">
												<a href="<?php echo $children['href']; ?>" class="main-menu with-submenu"><?php echo $children['name']; ?>
													<?php if ($item['show_img'] == 1) { ?>
													<img class="menu-cats-img" src="<?php echo $children['thumb']; ?>" alt="<?php echo $children['name']; ?>"/>
													<?php } ?></a>
												<div class="oct_show_cat"></div>
												<div class="oct_hide_cat"></div>
												<ul>
													<?php $countstop = 0; ?>
													<?php foreach ($children['children'] as $child) { ?>
													<?php $countstop++; ?>
													<li><a href="<?php echo $child['href']; ?>" ><?php echo $child['name']; ?></a></li>
													<?php if($countstop > $item['limit_item']) { ?>

													<li><a class="see-all-cats" href="<?php echo $children['href']; ?>" ><?php echo $text_all_category; ?>...</a></li>
													<?php break; } ?>
													<?php } ?>
												</ul>
											</div>
										</div>
										<?php }  else { ?>
										<div class="col-sm-<?php if (strlen($item['description']) < 15) { ?>3<?php } else { ?>4<?php } ?> static-menu">
											<div class="menu">
												<ul>
													<li>
														<a href="<?php echo $children['href']; ?>" class="main-menu with-submenu"><?php echo $children['name']; ?>
															<?php if ($item['show_img'] == 1) { ?>
															<img class="menu-cats-img" src="<?php echo $children['thumb']; ?>" alt="<?php echo $children['name']; ?>"/>
															<?php } ?></a>
													</li>
												</ul>
											</div>
										</div>
										<?php } ?>
										<?php } ?>
									</div>
								</div>
								<?php if ($item['description']) { ?>
								<div class="col-sm-4 mobile-enabled ocmm-description"><?php echo $item['description']; ?></div>
								<?php } ?>
							</div>
						</div>
					</div>
				</li>
				<?php } ?>
				<?php } ?>

				<?php if ($item['item_type'] == 3) { ?>
				<?php #start menu type - brands ?>
				<li class="with-sub-menu hover">
					<p class="oct_close-menu"></p>
					<p class="oct_open-menu"></p>
					<a href="<?php echo $item['href']; ?>" class="clearfix" <?php echo ($item['open_link_type']) ? 'target="_blank"' : ''; ?>><?php if ($item['image']) { ?><img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" /><?php } ?><?php echo $item['title']; ?></a>

					<div class="sub-menu wide-sub-menu" style="width: 340%; right: 0px;">
						<div class="content">
							<div class="row">
								<div class="col-sm-<?php if (strlen($item['description']) < 15) { ?>12<?php } else { ?>8<?php } ?> mobile-enabled">
									<div class="row">
										<?php if ($item['children']) { ?>
										<?php foreach ($item['children'] as $children) { ?>
										<div class="col-sm-<?php if (strlen($item['description']) < 15) { ?>2<?php } else { ?>4<?php } ?> static-menu">
											<div class="menu">
												<ul class="brands-ul">
													<li>
														<a href="<?php echo $children['href']; ?>" class="main-menu with-submenu"><img src="<?php echo $children['thumb']; ?>" alt="alt" class="img-responsive" /><span><?php echo $children['name']; ?></span></a>
													</li>
												</ul>
											</div>
										</div>
										<?php } ?>
										<?php } ?>
									</div>
								</div>
								<?php if ($item['description']) { ?>
								<div class="col-sm-4 mobile-enabled ocmm-description"><?php echo $item['description']; ?></div>
								<?php } ?>
							</div>
						</div>
					</div>
				</li>
				<?php #end menu type - brands ?>
				<?php } ?>


				<?php if ($item['item_type'] == 5) { ?>
				<?php #start menu type - information ?>
				<li class="with-sub-menu hover simple-menu">
					<p class="oct_close-menu"></p>
					<p class="oct_open-menu"></p>
					<a href="<?php echo $item['href']; ?>" class="clearfix" <?php echo ($item['open_link_type']) ? 'target="_blank"' : ''; ?>><?php if ($item['image']) { ?><img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" /><?php } ?><?php echo $item['title']; ?></a>

					<div class="sub-menu" style="width: 100%; right: 0px;">
						<div class="content">
							<div class="row">
								<div class="col-sm-12 mobile-enabled">
									<div class="row">
										<?php if ($item['children']) { ?>
										<?php foreach ($item['children'] as $children) { ?>
										<div class="col-sm-12 static-menu">
											<div class="menu">
												<ul>
													<li>
														<a href="<?php echo $children['href']; ?>" class="main-menu with-submenu"><span><?php echo $children['title']; ?></span></a>
													</li>
												</ul>
											</div>
										</div>
										<?php } ?>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</li>
				<?php #end menu type - information ?>
				<?php } ?>

				<?php } else { ?>
				<?php if ($item['item_type'] == 7 && $item['custom_html']) { ?>
				<?php #start menu type - custom html ?>
				<li class="with-sub-menu hover">
					<p class="oct_close-menu"></p>
					<p class="oct_open-menu mobile-disabled"></p>
					<a href="<?php echo $item['href']; ?>" class="clearfix" <?php echo ($item['open_link_type']) ? 'target="_blank"' : ''; ?>><?php if ($item['image']) { ?><img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" /><?php } ?><?php echo $item['title']; ?></a>

					<div class="sub-menu wide-sub-menu" style="width: 340%; right: 0px;">
						<div class="content html-content">
							<div class="row">
								<div class="col-sm-<?php if (strlen($item['description']) < 15) { ?>12<?php } else { ?>8<?php } ?> mobile-enabled">
									<?php echo $item['custom_html']; ?>
								</div>
								<?php if ($item['description']) { ?>
								<div class="col-sm-4 mobile-enabled ocmm-description"><?php echo $item['description']; ?></div>
								<?php } ?>
							</div>
						</div>
					</div>
				</li>
				<?php #end menu type - custom html ?>
				<?php } else { ?>
				<?php #start menu type - link ?>
				<li>
					<p class="oct_close-menu"></p>
					<p class="oct_open-menu mobile-disabled"></p>
					<a href="<?php echo $item['href']; ?>" class="clearfix"<?php echo ($item['open_link_type']) ? 'target="_blank"' : ''; ?>><?php if ($item['image']) { ?><img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" /><?php } ?><?php echo $item['title']; ?></a>
				</li>
				<?php #end menu type - link ?>
				<?php } ?>


				<?php } ?>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>
<script>
    function setEqualHeight(columns) {
        var tallestcolumn = 0;
        columns.each(
            function() {
                currentHeight = $(this).height();
                if(currentHeight > tallestcolumn) {
                    tallestcolumn = currentHeight;
                }
            }
        );
        columns.height(tallestcolumn);
    }

    function setEqualHeightNoImage(columns) {
        var tallestcolumn1 = 0;
        columns.each(
            function() {
                currentHeight1 = $(this).height();
                if(currentHeight1 > tallestcolumn1) {
                    tallestcolumn1 = currentHeight1;
                }
            }
        );
        columns.height(tallestcolumn1);
    }
    function viewport() {
        var e = window, a = 'inner';
        if (!('innerWidth' in window )) {
            a = 'client';
            e = document.documentElement || document.body;
        }
        return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
    }

    $(document).ready(function() {



        var b4 = viewport().width;


        if (b4 >= 992) {
            setEqualHeight($(".wide-menu-row .static-menu.with-img"));
            setEqualHeightNoImage($(".wide-menu-row .static-menu.without-img"));
        }

        if (b4 <= 992) {

            $( ".mob-menu-ul" ).append( "<div class=\"sidebar-account-header oct-sidebar-header\"><?php echo $text_acc; ?></div>" );
            $( ".sidebar-account" ).clone().appendTo( ".mob-menu-ul" );
            $("#menu-mobile").append( $( ".mob-menu-ul" ) );

            $( ".mob-menu-ul" ).append( "<div class=\"sidebar-info-header oct-sidebar-header\"><?php echo $text_info; ?></div>" );
            $(".mob-menu-ul").append( $( ".sidebar-info" ) );

            $( ".mob-menu-ul" ).append( "<div class=\"sidebar-settings-header oct-sidebar-header\"><?php echo $text_settings; ?></div>" );
            $(".mob-menu-ul").append($("#currency-div"));

            $( "#currency-div .dropdown-toggle" ).click(function() {
                $( "#currency-div .dropdown-menu" ).toggleClass('opened');
            });
            $(".mob-menu-ul").append($("#language-div"));

            $( "#language-div .dropdown-toggle" ).click(function() {
                $( "#language-div .dropdown-menu" ).toggleClass('opened');
            });

            $(".mob-menu-ul").append($("#sidebar-contacts"));
            $(".mob-menu-ul").append($("#sidebar-map"));
            $( "<div class=\"sidebar-contacts-header oct-sidebar-header\"><?php echo $text_contacts; ?></div>" ).insertBefore( "#sidebar-contacts" );

            $(".new-menu-togglee").one("click", false);

            $('nav#menu-mobile').mmenu({
                extensions: ['effect-menu-fade', 'effect-panels-slide-0', 'effect-listitems-fade', 'shadow-page', 'shadow-panels'],
                counters: false,
                navbar: {
                    title: "<?php echo $text_menu; ?>"
                },
                navbars: [{
                    position: 'top',
                    content: [
                        'prev',
                        'title',
                        'close'
                    ]
                }],
                offCanvas: {
                    position: 'top',
                    zposition: 'front'
                }
            });
        }



    });

    $(window).on('resize', function(){

        var b5 = viewport().width;

        if (b5 > 992) {
            $(".top-currency").append($("#currency-div"));
            $(".top-language").append($("#language-div"));
        } else {
            $("#currency-div").insertBefore('#sidebar-contacts');
            $("#language-div").insertBefore('#sidebar-contacts');

        }
    });
    var b6 = viewport().width;
    if (b6 > 992) {
        $('.oct_megamenu').hover(function () {
            $('#bluring').css('visibility', 'visible')},function () {
            $('#bluring').css('visibility', 'hidden')
        });
        $('#bluring').bind('touchstart touchend', function(e) {
            e.preventDefault();
            $('#bluring').css('visibility', 'visible')},function () {
            $('#bluring').css('visibility', 'hidden')
        });

        var menuHeight = $('.oct_megamenu').outerHeight();
        $('.oct_megamenu li.with-sub-menu .sub-menu .content').css('height', menuHeight);
        $('.with-sub-menu').mouseenter(function(){
            menuHeight = $(this).parent().outerHeight();
            var childMenuHeight = $(this).find('.sub-menu .content').outerHeight();
            if (childMenuHeight > menuHeight) {
                var menuHeight = childMenuHeight;
                $('.oct_megamenu').css({height:menuHeight + 'px'});
            }
        });
    }
</script>

<!-- Mobile -->



<ul class="mob-menu-ul sort-ord">
	<?php foreach ($items as $item) { ?>
	<?php if ($item['item_type'] == 2) { ?>
	<?php #start menu type - category ?>
	<li class="sss">
		<a href="<?php echo $item['href']; ?>" <?php echo ($item['open_link_type']) ? 'target="_blank"' : ''; ?>><?php echo $item['title']; ?></a>
		<ul>
			<?php foreach ($item['children'] as $children) { ?>

			<li>
				<a href="<?php echo $children['href']; ?>"><?php echo $children['name']; ?></a>
				<?php if ($children['children']) { ?>
				<ul>
					<?php foreach ($children['children'] as $child) { ?>
					<li>
						<a href="<?php echo $child['href']; ?>" ><?php echo $child['name']; ?></a>
					</li>
					<?php } ?>
				</ul>
				<?php } ?>
			</li>
			<?php } ?>
			</li>
		</ul>
	</li>
	<?php } ?>
	<?php if ($item['children']) { ?>
	<?php if ($item['item_type'] == 3) { ?>
	<?php #start menu type - brand ?>
	<li>
		<a href="<?php echo $item['href']; ?>" <?php echo ($item['open_link_type']) ? 'target="_blank"' : ''; ?>><?php echo $item['title']; ?></a>
		<ul>
			<?php if ($item['children']) { ?>
			<?php foreach ($item['children'] as $children) { ?>
			<li>
				<a href="<?php echo $children['href']; ?>"><?php echo $children['name']; ?></a>

			</li>
			<?php } ?>
			<?php } ?>
		</ul>
	</li>
	<?php } ?>
	<?php } ?>
	<?php if ($item['item_type'] == 5) { ?>
	<?php #start menu type - information ?>
	<li>
		<a href="<?php echo $item['href']; ?>" <?php echo ($item['open_link_type']) ? 'target="_blank"' : ''; ?>><?php echo $item['title']; ?></a>
		<ul>
			<?php if ($item['children']) { ?>
			<?php foreach ($item['children'] as $children) { ?>
			<li>
				<a href="<?php echo $children['href']; ?>"><?php echo $children['title']; ?></a>

			</li>
			<?php } ?>
			<?php } ?>
		</ul>
	</li>
	<?php } ?>
	<?php if ($item['item_type'] == 1) { ?>
	<?php #start menu type - link ?>
	<li>
		<a href="<?php echo $item['href']; ?>" <?php echo ($item['open_link_type']) ? 'target="_blank"' : ''; ?>><?php echo $item['title']; ?></a>
	</li>
	<?php } ?>
	<?php } ?>
</ul>


<?php } ?>