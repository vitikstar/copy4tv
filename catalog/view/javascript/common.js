$('.ac-personal-data-3').find('.socnetauth2_buttons').on('click',function () {
	popupCenter({url: $(this).attr('data-href'), title: 'xtf', w: 900, h: 500});
  })
function getURLVar(key) {
	var value = [];

	var query = String(document.location).split('?');

	if (query[1]) {
		var part = query[1].split('&');

		for (i = 0; i < part.length; i++) {
			var data = part[i].split('=');

			if (data[0] && data[1]) {
				value[data[0]] = data[1];
			}
		}

		if (value[key]) {
			return value[key];
		} else {
			return '';
		}
	}
}
function register() {
	$('#text-login-register').modal('hide');
	$('.register-modal').modal('show');
	$.ajax({
		url: 'index.php?route=extension/module/sirius_auth_register_popup/loadTemplateRegister',
		dataType: 'html',
		success: function (html) {
			$('.register-modal-main-block').html(html);
		}
	});
}
$(document).ready(function() {

	// Highlight any found errors
	$('.text-danger').each(function() {
		var element = $(this).parent().parent();

		if (element.hasClass('form-group')) {
			element.addClass('has-error');
		}
	});

	// Currency
	$('#form-currency .currency-select').on('click', function(e) {
		e.preventDefault();

		$('#form-currency input[name=\'code\']').val($(this).attr('name'));

		$('#form-currency').submit();
	});

	// Language
	$('#form-language .language-select').on('click', function(e) {
		e.preventDefault();

		$('#form-language input[name=\'code\']').val($(this).attr('name'));

		$('#form-language').submit();
	});

	/* Search */
	$('#search input[name=\'search\']').parent().find('button').on('click', function() {
		var url = $('base').attr('href') + 'index.php?route=product/search';

		var value = $('header #search input[name=\'search\']').val();

		if (value) {
			url += '&search=' + encodeURIComponent(value);
		}

		location = url;
	});

	$('#search input[name=\'search\']').on('keydown', function(e) {
		if (e.keyCode == 13) {
			$('header #search input[name=\'search\']').parent().find('button').trigger('click');
		}
	});

	// Menu
	$('#menu .dropdown-menu').each(function() {
		var menu = $('#menu').offset();
		var dropdown = $(this).parent().offset();

		var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

		if (i > 0) {
			$(this).css('margin-left', '-' + (i + 10) + 'px');
		}
	});

	// Product List
	$('#list-view').click(function() {
		$('#content .product-grid > .clearfix').remove();

		$('#content .row > .product-grid').attr('class', 'product-layout product-list col-xs-12');
		$('#grid-view').removeClass('active');
		$('#list-view').addClass('active');

		localStorage.setItem('display', 'list');
	});

	// Product Grid
	$('#grid-view').click(function() {
		// What a shame bootstrap does not take into account dynamically loaded columns
		var cols = $('#column-right, #column-left').length;

		if (cols == 2) {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12');
		} else if (cols == 1) {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-4 col-md-4 col-sm-6 col-xs-12');
		} else {
			$('#content .product-list').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12');
		}

		$('#list-view').removeClass('active');
		$('#grid-view').addClass('active');

		localStorage.setItem('display', 'grid');
	});

	if (localStorage.getItem('display') == 'list') {
		$('#list-view').trigger('click');
		$('#list-view').addClass('active');
	} else {
		$('#grid-view').trigger('click');
		$('#grid-view').addClass('active');
	}

	// Checkout
	$(document).on('keydown', '#collapse-checkout-option input[name=\'email\'], #collapse-checkout-option input[name=\'password\']', function(e) {
		if (e.keyCode == 13) {
			$('#collapse-checkout-option #button-login').trigger('click');
		}
	});

	// tooltips on hover
	$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});

	// Makes tooltips work on ajax generated content
	$(document).ajaxStop(function() {
		$('[data-toggle=\'tooltip\']').tooltip({container: 'body'});
	});

});

function loadingAjax(open=true) {
	if(open){
		$.magnificPopup.open({
			items: {
				src: 'catalog/view/theme/4tv/template/checkout/loading/loading.svg',
				type: 'image'
			},
			showCloseBtn: false
		});
	}else{
		$.magnificPopup.close();

	}
}

function login() {
	$('#text-login-register').modal('show');
};
function logout() {
	$.ajax({
		url: 'index.php?route=account/logout',
		type: 'post',
		dataType: 'json',
		success: function(json) {
			location = '/';
		}
	});
};
// Cart add remove functions
var cart = {
	'add': function(product_id, quantity) {

		$.ajax({
			url: 'index.php?route=checkout/cart/add',
			type: 'post',
			data: 'product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				$('.alert-dismissible, .text-danger').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
					// ми сюди попаждаєм якщо в адмінці відключуний модуль попап корзини бо якщо включений то внього є такий самий обработчик і він перебиває
					//setTimeout(function () {
						$('#cart #cart-total').html(json['total']);
					//}, 100);

					//$('html, body').animate({ scrollTop: 0 }, 'slow');

					$('#cart > ul').load('index.php?route=common/cart/info ul li');

					$('.pr-header-cart-dropdown-wrap').html(json['popup_small_window']);
				}
			}
		});
	},
	'update': function(key, quantity) {
		$.ajax({
			url: 'index.php?route=checkout/cart/edit',
			type: 'post',
			data: 'key=' + key + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {

				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
                    $('#cart #cart-total').html(json['total']);
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			}
		});
	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
                    $('#cart #cart-total').html(json['total']);
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			}
		});
	}
}

var voucher = {
	'add': function() {

	},
	'remove': function(key) {
		$.ajax({
			url: 'index.php?route=checkout/cart/remove',
			type: 'post',
			data: 'key=' + key,
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
                    $('#cart #cart-total').html(json['total']);
				}, 100);

				if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
					location = 'index.php?route=checkout/cart';
				} else {
					$('#cart > ul').load('index.php?route=common/cart/info ul li');
				}
			}
		});
	}
}
                    function sliderBuyProduct() {
								setTimeout(
									function(){
										                      $(".buy-width-product-block-wrap").each(function () {
                                            var wrapper = $(this);
                                            var length = wrapper.find(
                                              ".swiper-slide"
                                            ).length;

                                            if (window.innerWidth > 1800) {
                                              if (length <= 3) {
                                                wrapper
                                                  .find(".swiper-pager")
                                                  .hide();
                                                wrapper
                                                  .find(".swiper-pagination")
                                                  .hide();
                                              }
                                            }
                                            if (
                                              window.innerWidth <= 1800 &&
                                              window.innerWidth > 1200
                                            ) {
                                              if (length <= 3) {
                                                wrapper
                                                  .find(".swiper-pager")
                                                  .hide();
                                                wrapper
                                                  .find(".swiper-pagination")
                                                  .hide();
                                              }
                                            }
                                            if (
                                              window.innerWidth <= 1200 &&
                                              window.innerWidth > 768
                                            ) {
                                              if (length <= 3) {
                                                wrapper
                                                  .find(".swiper-pager")
                                                  .hide();
                                                wrapper
                                                  .find(".swiper-pagination")
                                                  .hide();
                                              }
                                            }
                                            if (
                                              window.innerWidth <= 767 &&
                                              window.innerWidth > 500
                                            ) {
                                              if (length <= 2) {
                                                wrapper
                                                  .find(".swiper-pager")
                                                  .hide();
                                                wrapper
                                                  .find(".swiper-pagination")
                                                  .hide();
                                              }
                                            }
                                            if (window.innerWidth <= 500) {
                                              if (length <= 1) {
                                                wrapper
                                                  .find(".swiper-pager")
                                                  .hide();
                                                wrapper
                                                  .find(".swiper-pagination")
                                                  .hide();
                                              }
                                            }

                                            wrapper
                                              .find(".products-slider")
                                              .swiper({
                                                slidesPerView: 3,
                                                //            pagination: '.bestseller-slider-wrapper .swiper-pagination',
                                                //            paginationClickable: true,
                                                nextButton: wrapper.find(
                                                  ".swiper-button-next"
                                                ),
                                                prevButton: wrapper.find(
                                                  ".swiper-button-prev"
                                                ),
                                                pagination: wrapper.find(
                                                  ".swiper-pagination"
                                                ),
                                                paginationClickable: true,
                                                breakpoints: {
                                                  1800: {
                                                    slidesPerView: 3,
                                                  },
                                                  1200: {
                                                    slidesPerView: 3,
                                                  },
                                                  768: {
                                                    slidesPerView: 2,
                                                  },
                                                  500: {
                                                    slidesPerView: "auto",
                                                  },
                                                },
                                                //              loop: true
                                              });
										  });
									},10
								);

                    }
var wishlist = {
	'add': function(product_id,initial_page='') {
		$.ajax({
			url: 'index.php?route=account/wishlist/add',
			type: 'post',
			data: {
				'product_id':product_id,
				'initial_page':initial_page
			},
			dataType: 'json',
			success: function(json) {
				//$('.alert-dismissible').remove();
				if ($(".product" + product_id).hasClass("products-wish-btn-active")){
					  $(".product" + product_id).removeClass("products-wish-btn-active");
					  $(".product" + product_id).addClass("products-wish-btn");
				}else{
        			  $(".product" + product_id).addClass("products-wish-btn-active");
				}
				// if (json['redirect']) {
				// 	location = json['redirect'];
				// }

				// if (json['success']) {
				// 	$('#content').parent().before('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				// }

				console.log(json);

				if(initial_page=='product'){
					$("#wishlist_text span").html(json['add_wishlist_text']);
					$("#wishlist_button").addClass('active');
				}

				$('#wishlist-total .header-cart-text-number').text(json['count']);
				$('.tooltip').remove();
				//$('html, body').animate({ scrollTop: 0 }, 'slow');
			}
		});
	},
	'remove': function() {

	}
}



$('#compare_text').click(function(){
	$('#art_compre_product_modal').modal('show');
})

var compare = {
	'add': function(product_id,initial_page='') {
		$.ajax({
			url: 'index.php?route=product/compare/add',
			type: 'post',
			data: {
				'product_id':product_id,
				'initial_page':initial_page
			},
			dataType: 'json',
			success: function(json) {
				console.log(product_id);
				if (json['success']) {
					console.log(json);
					if(json['access']){
						$('#compare-modal .modal-body').html(json['access']);
						$('#compare-modal').modal('show');
					}else{
						//$('#content').parent().before('<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						$('#compare-total').html(json['total']);
						$('.header-compare-only .header-cart-text-number').text(json['total_number']);
						$(".product_compare_number"+product_id).html(json['total_number']);
						$(".product_compare"+product_id).find('img').attr('src','/catalog/view/theme/4tv/image/icons-bg/compare-icon-2.png');
						$('.tooltip').remove();
						//$('html, body').animate({ scrollTop: 0 }, 'slow');
						if(initial_page=='product'){
							$("#compare_text span").html(json['add_compare_text']);
							$("#compare_button").addClass('active');
						}
					}
					comparePopup();
				}
			}
		});
	},
	'remove': function() {

	}
}

function comparePopup(){
	console.log('comparePopup');
	$.ajax({
		url: 'index.php?route=product/compare/popup',
		type: 'post',
		data: {},
		dataType: 'html',
		success: function(html) {
			$('#compare-popup').html(html);
			$('#art_compre_product_modal .modal-body').html(html);
		}
	});
}
comparePopup();

$('.products-wrapper .delete-1').on('click',function () {
	var close_obj = $(this)
	var data_remove = close_obj.attr('data-remove');
	loadingAjax(true);
	$.ajax( {
		type: "POST",
		url: 'index.php?route=account/wishlist/remove',
		data: {
			'remove': data_remove
		},
		dataType: 'json',
		success: function(json) {
			$('#wishlist-total .header-cart-text-number').text(json['count']);
			close_obj.parent().parent().remove();
			loadingAjax(false);
		}
	} );
})
$('.compare-table .delete-1').on('click',function () {
	var close_obj = $(this)
	var data_remove = close_obj.attr('data-remove');
	var category_id = close_obj.attr('category_id');
	loadingAjax(true);
	$.ajax( {
		type: "POST",
		url: 'index.php?route=product/compare/remove',
		data: {
			'remove': data_remove,
			'category_id': category_id,
		},
		dataType: 'json',
		success: function(json) {
			$('.product-td-compare-id-'+data_remove).remove();


			$('#compare-total').html(json['total']);
			$('.header-compare-only .header-cart-text-number').text(json['total_number']);
			$(".product_compare_number"+data_remove).html(json['total_number']);
			$(".product_compare"+data_remove).find('img').attr('src','/catalog/view/theme/4tv/image/icons-bg/compare-icon-2.png');
			$('.tooltip').remove();
			comparePopup();
			loadingAjax(false);
			if (json['redirect']) {
				$('.compare-tr-attr').remove();
				$('.compare-tr-option').remove();
				//location = json['redirect'];
			}
		}
	} );
})

$('.header-compare-only').on('click',function (e) {
	if($('.header-actions__dropdown.active').text()){
		$('.header-actions__dropdown').removeClass('active');
	}else{
		$('.header-actions__dropdown').addClass('active');
	}
})

/* Agree to Terms */
$(document).delegate('.agree', 'click', function(e) {
	e.preventDefault();

	$('#modal-agree').remove();

	var element = this;

	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		dataType: 'html',
		success: function(data) {
			html  = '<div id="modal-agree" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
			html += '      </div>';
			html += '      <div class="modal-body">' + data + '</div>';
			html += '    </div>';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			$('#modal-agree').modal('show');
		}
	});
});

// Autocomplete */
(function($) {
	$.fn.autocomplete = function(option) {
		return this.each(function() {
			this.timer = null;
			this.items = new Array();

			$.extend(this, option);

			$(this).attr('autocomplete', 'off');

			// Focus
			$(this).on('focus', function() {
				this.request();
			});

			// Blur
			$(this).on('blur', function() {
				setTimeout(function(object) {
					object.hide();
				}, 200, this);
			});

			// Keydown
			$(this).on('keydown', function(event) {
				switch(event.keyCode) {
					case 27: // escape
						this.hide();
						break;
					default:
						this.request();
						break;
				}
			});

			// Click
			this.click = function(event) {
				event.preventDefault();

				value = $(event.target).parent().attr('data-value');

				if (value && this.items[value]) {
					this.select(this.items[value]);
				}
			}

			// Show
			this.show = function() {
				var pos = $(this).position();

				$(this).siblings('ul.dropdown-menu').css({
					top: pos.top + $(this).outerHeight(),
					left: pos.left
				});

				$(this).siblings('ul.dropdown-menu').show();
			}

			// Hide
			this.hide = function() {
				$(this).siblings('ul.dropdown-menu').hide();
			}

			// Request
			this.request = function() {
				clearTimeout(this.timer);

				this.timer = setTimeout(function(object) {
					object.source($(object).val(), $.proxy(object.response, object));
				}, 200, this);
			}

			// Response
			this.response = function(json) {
				html = '';

				if (json.length) {
					for (i = 0; i < json.length; i++) {
						this.items[json[i]['value']] = json[i];
					}

					for (i = 0; i < json.length; i++) {
						if (!json[i]['category']) {
							html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
						}
					}

					// Get all the ones with a categories
					var category = new Array();

					for (i = 0; i < json.length; i++) {
						if (json[i]['category']) {
							if (!category[json[i]['category']]) {
								category[json[i]['category']] = new Array();
								category[json[i]['category']]['name'] = json[i]['category'];
								category[json[i]['category']]['item'] = new Array();
							}

							category[json[i]['category']]['item'].push(json[i]);
						}
					}

					for (i in category) {
						html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

						for (j = 0; j < category[i]['item'].length; j++) {
							html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
						}
					}
				}

				if (html) {
					this.show();
				} else {
					this.hide();
				}

				$(this).siblings('ul.dropdown-menu').html(html);
			}

			$(this).after('<ul class="dropdown-menu"></ul>');
			$(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));

		});
	}
})(window.jQuery);
