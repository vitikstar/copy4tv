function megamenuSubmit() {
    for (var i = 1; i <= 5; i++) {
        jQuery('#column_menu_' + i + ' .menu_area li.dd-item').each(function (index, value) {
            if (jQuery(this).children('.dd-list').length > 0) {
                var parent_id = index + 1;
                jQuery(this).children('.dd-list').children('li.dd-item').each(function () {
                    jQuery(this).find('input.parent_id').val(parent_id);
                })
            }
        });
        jQuery('#column_menu_' + i + ' .menu_area').children('.dd-list').children('li').children('.info').children('.panel-body').children('.parent_id').val(0);
    }
}

function remove_item(obj) {
    var parent = jQuery(obj).parent();
    jQuery(parent).remove();
}


function add_menu(obj, item) {
    jQuery('.right .menu_area > ol', jQuery(item).parents('.container_mega_menu')).append(obj);
}

function get_content_obj(obj, container) {
    var url = '';
    var title = jQuery(obj).attr('data');
    var type_id = jQuery(obj).attr('value');
    console.log(obj);

    var lang_title = "";
    for (i = 0; i < langs.length; i++) {
        lang_title += "<div class='input-group input-item'><span class='input-group-addon'><img src='view/image/flags/" + langs[i]["image"] + "' title='" + langs[i]["name"] + "' ></span><input class='form-control' type='text' name='title" + container + "[" + langs[i]["id"] + "][]' value='" + title + "'  /> </div>";
    }
    var url_title = "";
    for (i = 0; i < langs.length; i++) {
        url_title += "<input type='hidden' name='url" + container + "[" + langs[i]["id"] + "][]' value=''/>";
    }

    var params_title = "";
    params_title += "<input class='form-control' type='text' name='params" + container + "[]' value='Параметр'  />";

    var result =
        "<li class='dd-item'>" +
        "<div class='dd-handle'>" +
        "<div class='bar'>" +
        "<span class='title'>" + jQuery(obj).attr('data') + "</span>" +
        "</div>" +
        "</div>" +
        "<div class='panel panel-default info hide'>" +
        "<div class='panel-body'>" +
        "<input type='hidden' class='type' name='type" + container + "[]' value='" + jQuery(obj).attr('class') + "'/>" +
        "<input type='hidden' class='parent_id' name='parent_id" + container + "[]' value=''/>" +
        "<input type='hidden' class='type_id' name='type_id" + container + "[]' value='" + type_id + "'/>" +
        "<div class='form-group'>" +
        "<label>Название: </label>" + lang_title +
        "</div>" +
        "<div class='form-group'><label>" + jQuery(obj).attr('class') + "</label>" + url_title +
        "</div>" +
        "<div class='form-group'><label>Параметр</label>" + params_title +
        "</div>" +
        "</div>" +
        "</div>" +
        "<a class='btn btn-xs btn-danger remove' onclick='remove_item(this);'><i class='fa fa-trash-o'></i></a>" +
        "<a class='btn btn-xs btn-default explane' onclick='explane(this)'><i class='fa fa-chevron-down' aria-hidden='true'></i></a>"
    "</li>";
    return result;
}

function get_content_obj_custom(obj, container) {
    var url = jQuery(obj).parent().find('input.url').val();
    var title = jQuery(obj).parent().find('input.title').val();
    var params = jQuery(obj).parent().find('input.class_menu').val();

    var lang_title = "";
    for (i = 0; i < langs.length; i++) {
        lang_title += "<div class='input-group input-item'><span class='input-group-addon'><img src='view/image/flags/" + langs[i]["image"] + "' title='" + langs[i]["name"] + "' ></span><input class='form-control' type='text' name='title" + container + "[" + langs[i]["id"] + "][]' value='" + title + "'  /></div>";
    }

    var url_title = "";
    for (i = 0; i < langs.length; i++) {
        url_title += "   <div class='input-group input-item'><span class='input-group-addon'><img src='view/image/flags/" + langs[i]["image"] + "' title='" + langs[i]["name"] + "' ></span><input class='form-control' type='text' name='url" + container + "[" + langs[i]["id"] + "][]' value='" + url + "'  /></div>";
    }
    var params_title = "";
    params_title += "<input class='form-control' type='text' name='params" + container + "[]' value='" + params + "'  />";


    var result =
        "<li class='dd-item'>" +
        "<div class='dd-handle'>" +
        "<div class='bar'>" +
        "<span class='title'>" + title + "</span>" +
        "</div>" +
        "</div>" +
        "<div class='panel panel-default info hide'>" +
        "<div class='panel-body'>" +
        "<input type='hidden' class='type' name='type" + container + "[]' value='custom'/>" +
        "<input type='hidden' class='parent_id' name='parent_id" + container + "[]' value=''/>" +
        "<input type='hidden' class='type_id' name='type_id" + container + "[]' value=''/>" +
        "<p class='input-item'><span class='type'>Type: Custom</span></p>" +
        "<div class='form-group'>" +
        "<label>Название: </label>" + lang_title + "<br />" +
        "<label>Ссылка: </label>" + url_title + "<br />" +
        "</div>" +
        "<div class='form-group'>" +
        "<label>Параметр: </label>" + params_title + "<br />" +
        "</div>" +
        "</div>" +
        "</div>" +
        "<a class='btn btn-xs btn-danger remove' onclick='remove_item(this);'><i class='fa fa-trash-o'></i></a>" +
        "<a class='btn btn-xs btn-default explane' onclick='explane(this)'><i class='fa fa-chevron-down' aria-hidden='true'></i></a>" +
        "</li>";
    return result;
}
function explane(obj) {
    if (jQuery(obj).parent().children('.info').hasClass('hide') == true) {
        jQuery(obj).parent().children('.info').show();
        jQuery(obj).parent().children('.info').removeClass('hide');
        jQuery(obj).html('<i class="fa fa-chevron-up" aria-hidden="true"></i>');
    } else {
        jQuery(obj).parent().children('.info').hide();
        jQuery(obj).parent().children('.info').addClass('hide');
        jQuery(obj).html('<i class="fa fa-chevron-down" aria-hidden="true"></i>');
    }
}

jQuery(document).ready(function () {
    jQuery('a.add-to-menu').click(function () {
        var parent = jQuery(this).parent().children('div');
        var container = jQuery(this).parents('.container_mega_menu').attr('id');
        if (container.indexOf("column_menu_") != -1) {
            container = container.replace("column_menu_", "");
        } else {
            container = "";
        }
        jQuery(parent).find('input').each(function () {
            if (jQuery(this).is(':checked')) {
                var obj = get_content_obj(this, container);
                add_menu(obj, this);
                jQuery(this).attr('checked', false);
            }
        });
    });


    jQuery('a.add-to-menu_custom').click(function () {
        var container = jQuery(this).parents('.container_mega_menu').attr('id');

        if (container.indexOf("column_menu_") != -1) {
            container = container.replace("column_menu_", "");
        } else {
            container = "";
        }
        var obj = get_content_obj_custom(this, container);
        add_menu(obj, this);
    });

    jQuery('.menu_area').nestable({
        group: 1
    });
});