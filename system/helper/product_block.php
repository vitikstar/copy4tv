<?php
function print_product($product,$registry,$manufacturer=false)
{
    if( !isset($product['minimum'])) {
        $product['minimum'] = 1;
    }

    if($manufacturer) return print_not_logged_product_manufacturer($product,$registry);

    if ($registry->get('customer')->isLogged() && floatval($product['customer_price']) > 0 ) {
        return "<div style='height: 520px' class='prod'>".print_logged_product($product, $registry)."</div>";
    } else {
        return "<div style='height: 520px' class='prod'>".print_not_logged_product($product, $registry)."</div>";
    }
}

function print_not_logged_product($product,$registry) {
    $data = $registry->get('data');
    $button_cart = $data['button_cart'];
    $button_wishlist = $data['button_wishlist'];
    $button_compare = $data['button_compare'];
    $button_product_review = $data['button_product_review'];
    $text_from = $data['text_from'];
    $text_from_detail = $data['text_from_detail'];
    $text_quantity_unit = $data['text_quantity_unit'];
    $text_your_price = $data['text_your_price'];
    $text_retail_price = $data['text_retail_price'];
    ?>

    <input type='hidden'  class='input_product_id' name="input_product_id[<?php echo $product['product_id'] ?>]" value='<?php echo $product['product_id'] ?>'>


    <div class="product" id="product_<?php echo $product['sku']; ?>" style="display: none"  itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">

        <div class="productInner">
            <div class="product-title-price">
                <a href="<?php echo $product['href']; ?>" class="product-title"  itemprop="item"><span itemprop="name"><?php echo $product['name']; ?></span></a>
                <meta itemprop="position" content="<?php echo $product['product_id'] ?>" />
                <!--видно тільки при rows view--->
                <div class="ratingandfeedback">
                    <input data-show-caption="false" data-size="xxs" data-show-clear="false" value="<?php echo $product['rating']; ?>" data-min="0" data-max="5" data-step="1" displayOnly="true" class="ratingMy"><span class="revCount"><a href="#"><?php echo $product['reviews']; ?> отзыва</a></span>
                </div>
                <div class="shortDescMore"><?php echo $product['description']; ?></div>
                <div class="productFeaturesIcon">
                    <ul>
                        <li onclick="wishlist.add('<?php echo $product['product_id']; ?>');" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_wishlist; ?>" class="favorites" style="width: 43%;     justify-content: center;"><i class="icon-favorite-heart-button"></i></li>
                        <li onclick="document.location = '<?php echo $product['href']; ?>#tabVideo';" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_product_review; ?>" class="video" style="display: flex; justify-content: center; width: auto"><i class="icon-video-camera"></i></li>
                        <li onclick="compare.add('<?php echo $product['product_id']; ?>');" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_compare; ?>" class="comparison" style="width: 43%;     justify-content: center;"><i class="icon-comparison"></i></li>
                    </ul>
                </div>
                <!--видно тільки при rows view and--->
            </div>
            <?php if( isset($product['remove'])) { ?><a href="<?php echo $product['remove']; ?>" title="Удалить" class="icon-close-button"></a><?php } ?>
            <div class="product-pic" >
                <?php if( $product['percentage_markup'] ) { ?>
                    <!--<div class="tag tag-sale"><span class="sale">-<?php //echo $product['percentage_markup'] ?>%</span></div>-->
                    <!-- <div class="tag tag-sale"><span class="sale">SALE</span></div>-->
                <?php }
                if( $product['sticker']==4 ) { ?>
                    <div class="tag tag-new"><span class="new">NEW</span></div>
                <?php } else if( $product['sticker']==5 ) { ?>
                    <div class="tag"><span class="green"><i class="icon-exclamation-mark-inside-a-circle"></i></span></div>
                <?php } else if( $product['sticker'] == 1) { ?>
                    <div class="tag  tag-hit"><span class="hit">Хит</span></div>
                <?php } else if( $product['sticker'] == 2) { ?>
                    <!--одарок-->
                    <div class="tag"><span class="gift"><i class="icon-giftbox"></i></span></div>
                <?php } else if( $product['sticker'] == 3) { ?>
                    <div class="tag tag-interesting-things"><span class="green"><i class="icon-exclamation-mark-inside-a-circle"></i></span></div>
                <?php } ?>
                <div style="position: relative" class="parentImg" style="height: 200px"><a href="<?php echo $product['href']; ?>" class="mainImage">
                        <img src="<?php echo $product['thumb']; ?>"  alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" id="thumb_<?php echo $product['sku']; ?>" model="<?php echo $product['sku']; ?>" style="" class="product-first-img"/>
                    </a><i onclick="productQuickview($(this));" class="icon-plus-zoom-or-search-symbol-of-interface zoom"></i></div>
                <input type="hidden" value="<?php echo $product['sku']; ?>" class="sku_fetch">
                <?php //if ($product['thumb'] || $product['images'] ) { ?>
                <?php if ($product['images'] ) { ?>
                    <div class="morePics">
                        <span class="slideUp">
                          <i class="icon-arrow-down-sign-to-navigate"></i>
                        </span>
                        <div class="listOuter">
                            <ul>
                                <?php if ($product['thumb']) { ?>
                                    <li style="width: 40px; height: 40px">
                                        <a class="thumbnail" title="<?php echo $product['name']; ?>">
                                            <div style="background: url('<?php echo $product['thumb']; ?>'); background-size: 40px; height: 40px; width: 40px" data-image="<?php echo $product['thumb']; ?>" onclick="var src=$(this).attr('data-image'); $(this).parent().parent().parent().parent().parent().parent().find('.product-first-img').attr('src',src); console.log(src)"></div>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if ($product['images']) { ?>
                                    <?php foreach ($product['images'] as $image) { ?>
                                        <li class="image-additional" style="width: 40px; height: 40px">
                                            <a class="thumbnail">
                                                <div style="background: url('<?php echo $image; ?>');  background-size: 40px; height: 40px; width: 40px" data-image="<?php echo $image; ?>" onclick="var src=$(this).attr('data-image'); $(this).parent().parent().parent().parent().parent().parent().find('.product-first-img').attr('src',src); console.log(src)"></div>
                                            </a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </div>
                        <span class="slideDown">
                          <i class="icon-arrow-down-sign-to-navigate"></i>
                        </span>
                    </div>
                <?php } ?>
            </div>
            <div class="product-allOther">
                <div class="descPart">
                    <!--видно тільки при rows view--->
                    <?php if ($product['price']) { ?>
                        <?php if (!floatval($product['opt_price']) and !empty($product['opt_price'])) { ?>
                            <div class="priceRetail">
                                <div class="priceActions">

                                </div>
                                <div class="priceInfo">
                                    <span class="number"><?php echo $product['price']; ?></span>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="priceRetail">
                                <div class="priceInfo">
                                    <?php if( $product['special'] ) { ?>
                                        <div class="oldPrice">
                                            <span class="number"><?php echo $product['price']; ?></span>
                                        </div>
                                    <?php }  ?>
                                    <span class="number"  id="sumaRetail_<?php echo $product['product_id']; ?>"><?php if(isset($data['suma_text'])) echo $data['suma_text']; ?>  <?php echo $product['price']; ?></span>
                                    <?php if( isset($product['hide_opt_price']) && !$product['hide_opt_price'] ) { ?>
                                        <!--<p style="text-indent: 4px; font-family: -webkit-body !important; margin-bottom: 0; font-size: 80%"><?php if(isset($product['text_from'])) echo $product['text_from'] ?><?php echo $product['opt_price']; ?></p>-->
                                    <?php }  ?>
                                </div>
                                <?php if ($product['stock']) { ?><div class="stock"><?php echo $product['stock']; ?></div><?php } ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <div class="code">Код:<span><?php echo $product['sku']; ?></span></div>
                    <!--видно тільки при rows view--->
                    <div class="productFeatures">
                        <?php
                        if ($product['price']) { ?>
                            <?php if (!floatval($product['opt_price']) and !empty($product['opt_price'])) { ?>
                                <div class="priceRetail">
                                    <div class="priceActions">
                                        <div class="productQuantity">
                                            <div class="productQuantityInput"><span id="minus_opt">–</span>
                                                <input type="number"   class="QuantityInput" id="retailInput_<?php echo $product['product_id'] ?>" name="retailInput" value="<?php echo $product['minimum']; ?>"/>
                                                <span class="plus" id="plus_opt">+</span>
                                            </div>
                                        </div>
                                        <div class="productBuy"><a class="toCart" onclick="cart.add('<?php echo $product['product_id']; ?>', $(this).parents('.priceActions').find('.QuantityInput').val());"><i class="icon-shopping-cart"></i><span><?php //echo $button_cart; ?></span></a></div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="priceRetail">
                                    <div class="priceActions">
                                        <div class="productQuantity">
                                            <div class="productQuantityInput">
                                                <span class="minus" id="minusRetail_<?php echo $product['product_id']; ?>" onclick="cartPr(<?php echo $product['product_id']; ?> , <?php echo $product['quantity']; ?>,parseInt($(this).parent().find('.QuantityInput').val())-1,'minus',false,<?php echo $product['sku']; ?>)">–</span>
                                                <input type="number" class="QuantityInput" id="retailInput_<?php echo $product['product_id']; ?>" onkeyup="cartPr(<?php echo $product['product_id']; ?> , <?php echo $product['quantity']; ?>,$(this).val(),'plus',true,<?php echo $product['sku']; ?>)"  name="retailInput" value="<?php echo $product['minimum']; ?>" />
                                                <span class="plus" id="plusRetail_<?php echo $product['product_id']; ?>" onclick="cartPr(<?php echo $product['product_id']; ?> , <?php echo $product['quantity']; ?>,parseInt($(this).parent().find('.QuantityInput').val())+1,'plus',false,<?php echo $product['sku']; ?>)">+</span>
                                            </div>
                                        </div>
                                        <div class="productBuy"><a class="toCart" onclick="cart.add('<?php echo $product['product_id']; ?>', $(this).parents('.priceActions').find('.QuantityInput').val() , <?php echo (float)$product['price']; ?>);" ><i class="icon-shopping-cart"></i><span><?php //echo $button_cart; ?></span></a></div>
                                    </div>
                                </div>
                            <?php } ?>

                        <?php } ?>
                        <!--видно тільки при grid view--->
                        <div class="ratingandfeedback">
                            <input data-show-caption="false" data-size="xxs" data-show-clear="false" value="<?php echo $product['rating']; ?>" data-min="0" data-max="5" data-step="1" displayOnly="true" class="ratingMy"><span class="revCount"><a href="#"><?php echo $product['reviews']; ?> отзыва</a></span>
                        </div>
                        <div class="productFeaturesIcon">
                            <ul>
                                <li onclick="wishlist.add('<?php echo $product['product_id']; ?>');" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_wishlist; ?>" class="favorites" style="width: 43%"><i class="icon-favorite-heart-button"></i></li>
                                <li onclick="document.location = '<?php echo $product['href']; ?>#tabVideo';" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_product_review; ?>" class="video" style="display: flex; justify-content: center; width: auto"><i class="icon-video-camera"></i></li>
                                <li onclick="compare.add('<?php echo $product['product_id']; ?>');" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_compare; ?>" class="comparison" style="width: 43%"><i class="icon-comparison"></i></li>
                            </ul>
                        </div>
                        <div class="shortDescMore" style="min-height: 50px; margin: 10px;"><?php echo $product['description']; ?></div>
                        <!--видно тільки при grid view and--->
                    </div>
                </div>
                <ul class="mobileActions">
                    <li class="zoomM"><a href="#" onclick="productQuickview($(this).parents('.productInner').find('.product-pic a:first'));return false;"><i class="icon-plus-zoom-or-search-symbol-of-interface"></i></a></li>
                    <li class="favorites"><a href="#" onclick="wishlist.add('<?php echo $product['product_id']; ?>');return false;" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_wishlist; ?>"><i class="icon-favorite-heart-button"></i></a></li>
                    <li class="mobileCart"><a href="#" onclick="cart.add('<?php echo $product['product_id']; ?>', $('#retailInput_<?php echo $product['product_id']; ?>').val());return false;"><i class="icon-shopping-cart"></i></a></li>
                </ul>
            </div>
        </div>
    </div>

    <style>
        input.input-error {
            color: #ff0000;
            border: 1px solid #ff0000;
            box-shadow: 0 0 5px #ff0000;
        }
    </style>

    <?php
}

function print_not_logged_product_manufacturer($product,$registry) {
    $data = $registry->get('data');
    $button_cart = $data['button_cart'];
    $button_wishlist = $data['button_wishlist'];
    $button_compare = $data['button_compare'];
    $button_product_review = $data['button_product_review'];
    $text_from = $data['text_from'];
    $text_from_detail = $data['text_from_detail'];
    $text_quantity_unit = $data['text_quantity_unit'];
    $text_your_price = $data['text_your_price'];
    $text_retail_price = $data['text_retail_price'];
    ?>
    <input type='hidden'  class='input_product_id' value='<?php echo $product['product_id'] ?>'>
    <div class="product" id="product_<?php echo $product['sku']; ?>" style="display: none">
        <div class="productInner">
            <div class="product-title-price">
                <a href="<?php echo $product['href']; ?>" class="product-title"><?php echo $product['name']; ?></a>
                <!--видно тільки при rows view--->
                <div class="ratingandfeedback">
                    <input data-show-caption="false" data-size="xxs" data-show-clear="false" value="<?php echo $product['rating']; ?>" data-min="0" data-max="5" data-step="1" displayOnly="true" class="ratingMy"><span class="revCount"><a href="#"><?php echo $product['reviews']; ?> отзыва</a></span>
                </div>
                <div class="shortDescMore"><?php echo $product['description']; ?></div>
                <div class="productFeaturesIcon">
                    <ul >
                        <li onclick="wishlist.add('<?php echo $product['product_id']; ?>');" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_wishlist; ?>" class="favorites" style="width: 43%;     justify-content: center;"><i class="icon-favorite-heart-button"></i></li>
                        <li onclick="document.location = '<?php echo $product['href']; ?>#tabVideo';" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_product_review; ?>" class="video" style="display: flex; justify-content: center; width: auto"><i class="icon-video-camera"></i></li>
                        <li onclick="compare.add('<?php echo $product['product_id']; ?>');" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_compare; ?>" class="comparison" style="width: 43%;     justify-content: center;"><i class="icon-comparison"></i></li>
                    </ul>
                </div>
                <!--видно тільки при rows view and--->
            </div>
            <?php if( isset($product['remove'])) { ?><a href="<?php echo $product['remove']; ?>" title="Удалить" class="icon-close-button"></a><?php } ?>
            <div class="product-pic" >
                <?php if( $product['percentage_markup'] ) { ?>
                    <!--<div class="tag tag-sale"><span class="sale">-<?php //echo $product['percentage_markup'] ?>%</span></div>-->
                    <!-- <div class="tag tag-sale"><span class="sale">SALE</span></div>-->
                <?php }
                if( $product['sticker']==4 ) { ?>
                    <div class="tag tag-new"><span class="new">NEW</span></div>
                <?php } else if( $product['sticker']==5 ) { ?>
                    <div class="tag"><span class="green"><i class="icon-exclamation-mark-inside-a-circle"></i></span></div>
                <?php } else if( $product['sticker'] == 1) { ?>
                    <div class="tag  tag-hit"><span class="hit">Хит</span></div>
                <?php } else if( $product['sticker'] == 2) { ?>
                    <!--одарок-->
                    <div class="tag"><span class="gift"><i class="icon-giftbox"></i></span></div>
                <?php } else if( $product['sticker'] == 3) { ?>
                    <div class="tag tag-interesting-things"><span class="green"><i class="icon-exclamation-mark-inside-a-circle"></i></span></div>
                <?php } ?>
                <div style="position: relative" class="parentImg" style="height: 200px">
                    <a href="<?php echo $product['href']; ?>" class="mainImage">
                        <img src="<?php echo $product['thumb']; ?>"  alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" id="thumb_<?php echo $product['sku']; ?>" model="<?php echo $product['sku']; ?>" style="" class="product-first-img"/>
                    </a>
                    <i onclick="productQuickview($(this));" class="icon-plus-zoom-or-search-symbol-of-interface zoom"></i>
                </div>
                <input type="hidden" value="<?php echo $product['sku']; ?>" class="sku_fetch">
                <?php //if ($product['thumb'] || $product['images'] ) { ?>
                <?php if ($product['images'] ) { ?>
                    <div class="morePics">
                        <span class="slideUp">
                          <i class="icon-arrow-down-sign-to-navigate"></i>
                        </span>
                        <div class="listOuter">
                            <ul>
                                <?php if ($product['thumb']) { ?>
                                    <li style="width: 40px; height: 40px">
                                        <a class="thumbnail" title="<?php echo $product['name']; ?>">
                                            <div style="background: url('<?php echo $product['thumb']; ?>'); background-size: 40px; height: 40px; width: 40px" data-image="<?php echo $product['thumb']; ?>" onclick="var src=$(this).attr('data-image'); $(this).parent().parent().parent().parent().parent().parent().find('.product-first-img').attr('src',src)"></div>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if ($product['images']) { ?>
                                    <?php foreach ($product['images'] as $image) { ?>
                                        <li class="image-additional" style="width: 40px; height: 40px">
                                            <a class="thumbnail">
                                                <div style="background: url('<?php echo $image; ?>');  background-size: 40px; height: 40px; width: 40px" data-image="<?php echo $image; ?>" onclick="var src=$(this).attr('data-image'); $(this).parent().parent().parent().parent().parent().parent().find('.product-first-img').attr('src',src)"></div>
                                            </a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </div>
                        <span class="slideDown"><i class="icon-arrow-down-sign-to-navigate"></i></span>
                    </div>
                <?php } ?>
            </div>
            <div class="product-allOther">
                <div class="descPart">
                    <!--видно тільки при rows view--->
                    <?php if ($product['price']) { ?>
                        <?php if (!floatval($product['opt_price']) and !empty($product['opt_price'])) { ?>
                            <div class="priceRetail">
                                <div class="priceActions">

                                </div>
                                <div class="priceInfo">
                                    <span class="number"><?php echo $product['price']; ?></span>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="priceRetail">
                                <div class="priceInfo">
                                    <?php if( $product['special'] ) { ?>
                                        <div class="oldPrice">
                                            <span class="number"><?php echo $product['price']; ?></span>
                                        </div>
                                        <span class="number"><?php //echo $product['special']; ?></span>
                                    <?php }  ?>
                                    <span class="number"  id="sumaRetail_<?php echo $product['product_id']; ?>"><?php echo $data['suma_text']; ?>  <?php echo $product['price']; ?></span>
                                    <?php if( !$product['hide_opt_price'] ) { ?>
                                        <!--<p style="text-indent: 4px; font-family: -webkit-body !important; margin-bottom: 0; font-size: 80%"><?php //echo $product['text_from'] ?><?php //echo $product['opt_price']; ?></p>-->
                                    <?php } ?>
                                </div>
                                <?php if ($product['stock']) { ?><div class="stock"><?php echo $product['stock']; ?></div><?php } ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <div class="code">Код:<span><?php echo $product['sku']; ?></span></div>
                    <!--видно тільки при rows view--->
                    <div class="productFeatures">
                        <?php
                        if ($product['price']) { ?>
                            <?php if (!floatval($product['opt_price']) and !empty($product['opt_price'])) { ?>
                                <div class="priceRetail">
                                    <div class="priceActions">
                                        <div class="productQuantity">
                                            <div class="productQuantityInput"><span id="minus_opt">–</span>
                                                <input type="number" class="QuantityInput" id="retailInput_<?php echo $product['product_id'] ?>" name="retailInput" value="<?php echo $product['minimum']; ?>" />
                                                <span class="plus" id="plus_opt">+</span>
                                            </div>
                                        </div>
                                        <div class="productBuy"><a class="toCart" onclick="cart.add('<?php echo $product['product_id']; ?>', $(this).parents('.priceActions').find('.QuantityInput').val());"><i class="icon-shopping-cart"></i><span><?php //echo $button_cart; ?></span></a></div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="priceRetail">
                                    <div class="priceActions">
                                        <div class="productQuantity">
                                            <div class="productQuantityInput">
                                                <span class="minus" id="minusRetail_<?php echo $product['product_id']; ?>" onclick="cartPr(<?php echo $product['product_id']; ?> , <?php echo $product['quantity']; ?>,parseInt($(this).parent().find('.QuantityInput').val())-1,'minus',false,<?php echo $product['sku']; ?>)">–</span>
                                                <input type="number" class="QuantityInput" id="retailInput_<?php echo $product['product_id']; ?>" onkeyup="cartPr(<?php echo $product['product_id']; ?> , <?php echo $product['quantity']; ?>,$(this).val(),'plus',true,<?php echo $product['sku']; ?>)"  name="retailInput" value="<?php echo $product['minimum']; ?>" />
                                                <span class="plus" id="plusRetail_<?php echo $product['product_id']; ?>" onclick="cartPr(<?php echo $product['product_id']; ?> , <?php echo $product['quantity']; ?>,parseInt($(this).parent().find('.QuantityInput').val())+1,'plus',false,<?php echo $product['sku']; ?>)">+</span>
                                            </div>
                                        </div>
                                        <div class="productBuy"><a class="toCart" onclick="cart.add('<?php echo $product['product_id']; ?>', $(this).parents('.priceActions').find('.QuantityInput').val() , <?php echo (float)$product['price']; ?>);" ><i class="icon-shopping-cart"></i><span><?php //echo $button_cart; ?></span></a></div>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        <!--видно тільки при grid view--->
                        <div class="ratingandfeedback">
                            <input data-show-caption="false" data-size="xxs" data-show-clear="false" value="<?php echo $product['rating']; ?>" data-min="0" data-max="5" data-step="1" displayOnly="true" class="ratingMy"><span class="revCount"><a href="#"><?php echo $product['reviews']; ?> отзыва</a></span>
                        </div>
                        <div class="productFeaturesIcon">
                            <ul>
                                <li onclick="wishlist.add('<?php echo $product['product_id']; ?>');" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_wishlist; ?>" class="favorites" style="width: 43%"><i class="icon-favorite-heart-button"></i></li>
                                <li onclick="document.location = '<?php echo $product['href']; ?>#tabVideo';" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_product_review; ?>" class="video" style="display: flex; justify-content: center; width: auto"><i class="icon-video-camera"></i></li>
                                <li onclick="compare.add('<?php echo $product['product_id']; ?>');" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_compare; ?>" class="comparison" style="width: 43%"><i class="icon-comparison"></i></li>
                            </ul>
                        </div>
                        <div class="shortDescMore" style="min-height: 50px; margin: 10px;"><?php echo $product['description']; ?></div>
                        <!--видно тільки при grid view and--->
                    </div>
                </div>
                <ul class="mobileActions">
                    <li class="zoomM"><a href="#" onclick="productQuickview($(this).parents('.productInner').find('.product-pic a:first'));return false;"><i class="icon-plus-zoom-or-search-symbol-of-interface"></i></a></li>
                    <li class="favorites"><a href="#" onclick="wishlist.add('<?php echo $product['product_id']; ?>');return false;" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_wishlist; ?>"><i class="icon-favorite-heart-button"></i></a></li>
                    <li class="mobileCart"><a href="#" onclick="cart.add('<?php echo $product['product_id']; ?>', $('#retailInput_<?php echo $product['product_id']; ?>').val());return false;"><i class="icon-shopping-cart"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
    <style>
        input.input-error {
            color: #ff0000;
            border: 1px solid #ff0000;
            box-shadow: 0 0 5px #ff0000;
        }
    </style>
    <?php
}

function print_logged_product($product,$registry) {
    $data = $registry->get('data');
    $button_cart = $data['button_cart'];
    $button_wishlist = $data['button_wishlist'];
    $button_compare = $data['button_compare'];
    $button_product_review = $data['button_product_review'];
    $text_from = $data['text_from'];
    $text_from_detail = $data['text_from_detail'];
    $text_quantity_unit = $data['text_quantity_unit'];
    $text_your_price = $data['text_your_price'];
    $text_retail_price = $data['text_retail_price'];
    $customer_group_id = $data['group_id'];       //   1    покупки для себе 12 це розничная 11%
    ?>
    <input type='hidden'  class='input_product_id' name="input_product_id[<?php echo $product['product_id'] ?>]" value='<?php echo $product['product_id'] ?>'>
    <div class="product" data-actions-box="" id="product_<?php echo $product['sku']; ?>" style="display: none">
        <div class="productInner">
            <div class="product-title-price">
                <a href="<?php echo $product['href']; ?>" class="product-title"><?php echo $product['name']; ?></a>
                <!--видно тільки при rows view--->
                <div class="ratingandfeedback">
                    <input data-show-caption="false" data-size="xxs" data-show-clear="false" value="<?php echo $product['rating']; ?>" data-min="0" data-max="5" data-step="1" displayOnly="true" class="ratingMy"><span class="revCount"><a href="#"><?php echo $product['reviews']; ?> отзыва</a></span>
                </div>
                <div class="shortDescMore"><?php echo $product['description']; ?></div>
                <div class="productFeaturesIcon">
                    <ul >
                        <li onclick="wishlist.add('<?php echo $product['product_id']; ?>');" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_wishlist; ?>" class="favorites" style="width: 43%;     justify-content: center;"><i class="icon-favorite-heart-button"></i></li>
                        <li onclick="document.location = '<?php echo $product['href']; ?>#tabVideo';" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_product_review; ?>" class="video" style="display: flex; justify-content: center; width: auto"><i class="icon-video-camera"></i></li>
                        <li onclick="compare.add('<?php echo $product['product_id']; ?>');" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_compare; ?>" class="comparison" style="width: 43%;     justify-content: center;"><i class="icon-comparison"></i></li>
                    </ul>
                </div>
                <!--видно тільки при rows view and--->
            </div>
            <?php if( isset($product['remove'])) { ?><a href="<?php echo $product['remove']; ?>" title="Удалить" class="icon-close-button"></a><?php } ?>
            <div class="product-pic" >
                <?php if( $product['percentage_markup'] ) { ?>
                    <!--<div class="tag tag-sale"><span class="sale">-<?php //echo $product['percentage_markup'] ?>%</span></div>-->
                    <!-- <div class="tag tag-sale"><span class="sale">SALE</span></div>-->
                <?php }
                if( $product['sticker']==4 ) { ?>
                    <div class="tag tag-new"><span class="new">NEW</span></div>
                <?php } else if( $product['sticker']==5 ) { ?>

                    <div class="tag"><span class="green"><i class="icon-exclamation-mark-inside-a-circle"></i></span></div>
                <?php } else if( $product['sticker'] == 1) { ?>
                    <div class="tag  tag-hit"><span class="hit">Хит</span></div>
                <?php } else if( $product['sticker'] == 2) { ?>
                    <!--одарок-->
                    <div class="tag"><span class="gift"><i class="icon-giftbox"></i></span></div>
                <?php } else if( $product['sticker'] == 3) { ?>
                    <div class="tag tag-interesting-things"><span class="green"><i class="icon-exclamation-mark-inside-a-circle"></i></span></div>
                <?php } ?>
                <div style="position: relative" class="parentImg" style="height: 200px"><a href="<?php echo $product['href']; ?>" class="mainImage">
                        <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" id="thumb_<?php echo $product['sku']; ?>" model="<?php echo $product['sku']; ?>" style="" class="product-first-img"/>
                    </a>
                    <i onclick="productQuickview($(this));" class="icon-plus-zoom-or-search-symbol-of-interface zoom"></i>
                </div>
                <input type="hidden" value="<?php echo $product['sku']; ?>" class="sku_fetch">
                <?php //if ($product['thumb'] || $product['images'] ) { ?>
                <?php if ($product['images'] ) { ?>
                    <div class="morePics">
                        <span class="slideUp">
                          <i class="icon-arrow-down-sign-to-navigate"></i>
                        </span>
                        <div class="listOuter">
                            <ul>
                                <?php if ($product['thumb']) { ?>
                                    <li style="width: 40px; height: 40px">
                                        <a class="thumbnail" title="<?php echo $product['name']; ?>">
                                            <div style="background: url('<?php echo $product['thumb']; ?>'); background-size: 40px; height: 40px; width: 40px" data-image="<?php echo $product['thumb']; ?>" onclick="var src=$(this).attr('data-image'); $(this).parent().parent().parent().parent().parent().parent().find('.product-first-img').attr('src',src); console.log(src)"></div>
                                        </a>
                                    </li>
                                <?php } ?>
                                <?php if ($product['images']) { ?>
                                    <?php foreach ($product['images'] as $image) { ?>
                                        <li class="image-additional" style="width: 40px; height: 40px">
                                            <a class="thumbnail">
                                                <div style="background: url('<?php echo $image; ?>');  background-size: 40px; height: 40px; width: 40px" data-image="<?php echo $image; ?>" onclick="var src=$(this).attr('data-image'); $(this).parent().parent().parent().parent().parent().parent().find('.product-first-img').attr('src',src); console.log(src)"></div>
                                            </a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </div>
                        <span class="slideDown">
                          <i class="icon-arrow-down-sign-to-navigate"></i>
                        </span>
                    </div>
                <?php } ?>

            </div>
            <div class="product-allOther">
                <div class="descPart">

                    <!--видно тільки при rows view--->
                    <?php if ($product['price']) { ?>
                        <?php if (!floatval($product['opt_price']) and !empty($product['opt_price'])) { ?>
                            <div class="priceRetail">
                                <div class="priceActions">

                                </div>
                                <div class="priceInfo">
                                    <span class="number"><?php echo $product['price']; ?></span>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="priceRetail">
                                <div class="priceInfo">
                                    <?php if( $product['special'] ) { ?>
                                        <div class="oldPrice">
                                            <span class="number"><?php echo $product['price']; ?></span>
                                        </div>
                                        <span class="number"><?php echo $product['special']; ?></span>
                                    <?php }  ?>
                                    <span class="number"  id="sumaRetail_<?php echo $product['product_id']; ?>" special="<?php echo $product['special'] ?>">         <!------тута вся магія------->
                                        <?php
                                        if( $product['customer_price'] ) {
                                            echo $text_your_price." ".$product['customer_price'];
                                        }else{
                                            echo $product['price_retail'];
                                        }
                                        ?>
                                    </span>
                                    <?php if( $product['customer_price'] ) { ?>
                                        <p style="text-indent: 4px; font-family: -webkit-body !important; margin-bottom: 0; font-size: 80%"><?php echo $text_retail_price.' '.$product['price_retail']; ?></p>
                                    <?php } ?>
                                </div>
                                <?php if ($product['stock']) { ?><div class="stock"><?php echo $product['stock']; ?></div><?php } ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <div class="code">Код:<span><?php echo $product['sku']; ?></span></div>
                    <!--видно тільки при rows view--->
                    <div class="productFeatures">
                        <?php
                        if ($product['price']) { ?>
                            <?php if (!floatval($product['opt_price']) and !empty($product['opt_price'])) { ?>
                                <div class="priceRetail">
                                    <div class="priceActions">
                                        <div class="productQuantity">
                                            <div class="productQuantityInput"><span id="minus_opt">–</span>
                                                <input type="number"   class="QuantityInput" id="retailInput_<?php echo $product['product_id'] ?>" name="retailInput" value="<?php echo $product['minimum']; ?>"/>
                                                <span class="plus" id="plus_opt">+</span>
                                            </div>
                                        </div>
                                        <div class="productBuy"><a class="toCart" onclick="cart.add('<?php echo $product['product_id']; ?>', $(this).parents('.priceActions').find('.QuantityInput').val());"><i class="icon-shopping-cart"></i><span><?php //echo $button_cart; ?></span></a></div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="priceRetail">
                                    <div class="priceActions">
                                        <div class="productQuantity">
                                            <div class="productQuantityInput">
                                                <span class="minus" id="minusRetail_<?php echo $product['product_id']; ?>" onclick="cartPr(<?php echo $product['product_id']; ?> , <?php echo $product['quantity']; ?>,parseInt($(this).parent().find('.QuantityInput').val())-1,'minus',false,<?php echo $product['sku']; ?>)">–</span>
                                                <input type="number"  class="QuantityInput" id="retailInput_<?php echo $product['product_id']; ?>" onkeyup="cartPr(<?php echo $product['product_id']; ?> , <?php echo $product['quantity']; ?>,$(this).val(),'plus',true,<?php echo $product['sku']; ?>)"  name="retailInput" value="<?php echo $product['minimum']; ?>"/>
                                                <span class="plus" id="plusRetail_<?php echo $product['product_id']; ?>" onclick="cartPr(<?php echo $product['product_id']; ?> , <?php echo $product['quantity']; ?>,parseInt($(this).parent().find('.QuantityInput').val())+1,'plus',false,<?php echo $product['sku']; ?>)">+</span>
                                            </div>
                                        </div>
                                        <div class="productBuy"><a class="toCart" onclick="cart.add('<?php echo $product['product_id']; ?>', $(this).parents('.priceActions').find('.QuantityInput').val() , <?php echo (float)$product['price']; ?>);" ><i class="icon-shopping-cart"></i><span><?php //echo $button_cart; ?></span></a></div>
                                    </div>
                                </div>

                            <?php } ?>
                        <?php } ?>
                        <!--видно тільки при grid view--->
                        <div class="ratingandfeedback">
                            <input data-show-caption="false" data-size="xxs" data-show-clear="false" value="<?php echo $product['rating']; ?>" data-min="0" data-max="5" data-step="1" displayOnly="true" class="ratingMy"><span class="revCount"><a href="#"><?php echo $product['reviews']; ?> отзыва</a></span>
                        </div>
                        <div class="productFeaturesIcon">
                            <ul>
                                <li onclick="wishlist.add('<?php echo $product['product_id']; ?>');" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_wishlist; ?>" class="favorites" style="width: 43%"><i class="icon-favorite-heart-button"></i></li>
                                <li onclick="document.location = '<?php echo $product['href']; ?>#tabVideo';" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_product_review; ?>" class="video" style="display: flex; justify-content: center; width: auto"><i class="icon-video-camera"></i></li>
                                <li onclick="compare.add('<?php echo $product['product_id']; ?>');" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_compare; ?>" class="comparison" style="width: 43%"><i class="icon-comparison"></i></li>
                            </ul>
                        </div>
                        <div class="shortDescMore" style="min-height: 50px; margin: 10px;"><?php echo $product['description']; ?></div>
                        <!--видно тільки при grid view and--->
                    </div>
                </div>
                <ul class="mobileActions">
                    <li class="zoomM"><a href="#" onclick="productQuickview($(this).parents('.productInner').find('.product-pic a:first'));return false;"><i class="icon-plus-zoom-or-search-symbol-of-interface"></i></a></li>
                    <li class="favorites"><a href="#" onclick="wishlist.add('<?php echo $product['product_id']; ?>');return false;" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $button_wishlist; ?>"><i class="icon-favorite-heart-button"></i></a></li>
                    <li class="mobileCart"><a href="#" onclick="cart.add('<?php echo $product['product_id']; ?>', $('#retailInput_<?php echo $product['product_id']; ?>').val());return false;"><i class="icon-shopping-cart"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
<?php } ?>