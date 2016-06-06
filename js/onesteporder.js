/*Модуль AJAX Оформления заказа в 1 шаг (версия 2)
 Разработано: © JOrange.ru*/

$(function () {
    $(".phone").mask("+9 (999) 999-9999");

    $('.onesteporder-products-count-icon-p').hover(function () {
        $(this).addClass("onesteporder-products-count-icon-p-hover");
    }, function () {
        $(this).removeClass("onesteporder-products-count-icon-p-hover");
    });
    $('.onesteporder-products-count-icon-p').mouseup(function () {
        $(this).removeClass("onesteporder-products-count-icon-p-active");
    }).mousedown(function () {
        $(this).addClass("onesteporder-products-count-icon-p-active");
    }).mouseout(function () {
        $(this).removeClass("onesteporder-products-count-icon-p-active");
    });

    $('.onesteporder-products-total-submit, .onesteporder-products-total-submit-fast, .onesteporder-noframe-ordering-button').hover(function () {
        $(this).addClass("onesteporder-products-total-submit-hover");
    }, function () {
        $(this).removeClass("onesteporder-products-total-submit-hover");
    });
    $('.onesteporder-products-total-submit, .onesteporder-products-total-submit-fast').mouseup(function () {
        $(this).removeClass("onesteporder-products-total-submit-active");
    }).mousedown(function () {
        $(this).addClass("onesteporder-products-total-submit-active");
    }).mouseout(function () {
        $(this).removeClass("onesteporder-products-total-submit-active");
    });

    $('.onesteporder-products-count-icon-m').hover(function () {
        $(this).addClass("onesteporder-products-count-icon-m-hover");
    }, function () {
        $(this).removeClass("onesteporder-products-count-icon-m-hover");
    });
    $('.onesteporder-products-count-icon-m').mouseup(function () {
        $(this).removeClass("onesteporder-products-count-icon-m-active");
    }).mousedown(function () {
        $(this).addClass("onesteporder-products-count-icon-m-active");
    }).mouseout(function () {
        $(this).removeClass("onesteporder-products-count-icon-m-active");
    });

    $('.onesteporder-contact-button').hover(function () {
        $(this).addClass("onesteporder-contact-button-hover");
    }, function () {
        $(this).removeClass("onesteporder-contact-button-hover");
    });
    $('.onesteporder-contact-button').mouseup(function () {
        $(this).removeClass("onesteporder-contact-button-active");
    }).mousedown(function () {
        $(this).addClass("onesteporder-contact-button-active");
    }).mouseout(function () {
        $(this).removeClass("onesteporder-contact-button-active");
    });


    $('.onesteporder-remove-all-elements a, a.onesteporder-products-remove, a.onesteporder-contact-adresses').hover(function () {
        $(this).find('span').addClass("hover");
    }, function () {
        $(this).find('span').removeClass("hover");
    });
    $('.onesteporder-remove-all-elements a, a.onesteporder-products-remove, a.onesteporder-contact-adresses').mouseup(function () {
        $(this).find('span').removeClass("active");
    }).mousedown(function () {
        $(this).find('span').addClass("active");
    }).mouseout(function () {
        $(this).find('span').removeClass("active");
    });

    if (!$.browser.msie) {
        $('.onesteporder-checkbox').each(function () {
            var cb = $(this);
            cb.addClass('checkbox');
            cb.next().prepend(function () {
                return (
                    cb.is(':checked')
                ) ? '<span class="custom_checkbox custom_checkbox_active" />' : '<span class="custom_checkbox" />';
            });
            cb.click(function () {
                cb.next().children().first().toggleClass('custom_checkbox_active');
            });
        });
    }

    var countryID = $('#ShippingCountryStandart').val();
    var zoneID = $('#ShippingZoneStandart').val();
    ShippingMethods(countryID, zoneID, '');
    $('#first_nameStandart, #last_nameStandart, #ContactInformationStandart input, #ContactInformationStandart textarea').keyup(function () {
        CheckAdressChanged('Standart');
    });

    if (noframe == 'noframe' && ordering == '') adjust_cart_window();

    $(document).click(function (e) {
        $(".onesteporder-configuration-item-url a").show();
        $('.onesteporder-configuration-item-div, .onesteporder-coupon-field').hide();
        $('.onesteporder-products-name a').removeClass('onesteporder-configuration-name-current');
    });
    $('.onesteporder-configuration-item-div, .onesteporder-configuration-item-url a, .onesteporder-coupon-field, .system_CouponDivForm a').click(function (e) {
        e.stopPropagation();
    });

    $(window).resize(function () {
        portable_screen();
    });
    portable_screen();

})

function adjust_cart_window() {
    var wndSize = getWindowSize(parent);
    var scr_h = wndSize[1] - 100;

    if (conf_onesteporder_informer_type == "inform" && ordering == '' && noframe == 'noframe') {
        var wnd_h = getLayer('blck-content').offsetHeight + 25;
        parent.resizeFadeIFrame(410, Math.min(scr_h, wnd_h));
    } else {
        var wnd_h = getLayer('blck-content').offsetHeight + 25;
        parent.resizeFadeIFrame(1010, Math.min(scr_h, wnd_h));
    }
}

function portable_screen() {
    widthMainContent = $('.onesteporder-center').outerWidth(true);
    if (widthMainContent < 900) {
        $('.onesteporder-center').addClass('onesteporder-portable');
    } else {
        $('.onesteporder-center').removeClass('onesteporder-portable');
    }
}


function RadioStyle(type) {
    if (!$.browser.msie) {
        $('input:radio[name=' + type + 'MethodID]').each(function () {
            var cb = $(this);
            cb.addClass('radio');
            cb.next().prepend(function () {
                return (
                    cb.is(':checked')
                ) ? '<span class="custom_radio custom_radio_active" />' : '<span class="custom_radio" />';
            });
            cb.click(function () {
                $("input[name='" + $(this).attr('name') + "']").each(function () {
                    $(this).next().children().first().removeClass('custom_radio_active');
                });
                cb.next().children().first().addClass('custom_radio_active');
            });
        });
    }
}

function RadioStyleSet() {
    $("input:radio[name=shippingMethodID], input:radio[name=paymentMethodID]").each(function () {
        if ($(this).is(":checked")) {
            $(this).next().children().first().addClass('custom_radio_active');
        } else {
            $(this).next().children().first().removeClass('custom_radio_active');
        }
    });
}

function goToByScroll(id) {
    $('html,body').animate({scrollTop: $("#" + id).offset().top}, 'slow');
}

function SelectOrderingType(Url, Type) {
    $('.system_TabOrderingType').removeClass('system_TabOrderingTypeCurrent');
    $('.onesteporder-order-types-information1 span, .onesteporder-order-types-information2 span, .onesteporder-order-types-information3 span').removeClass('current');
    $('.onesteporder-order-types-information-array, .onesteporder-order-types-information-array-visible').removeClass('onesteporder-order-types-information-array-visible');
    $(Url).addClass('system_TabOrderingTypeCurrent');
    $('.onesteporder-order-types-information' + Type + ' span').addClass('current');
    $('#onesteporder-order-types-information-array' +
      Type).addClass('onesteporder-order-types-information-array-visible');
    if (Type == '1') {
        $('#FastOrdering').show();
        $('#StandartOrdering').hide();
        $('#LoginOrdering').hide();
        goToByScroll('FastOrdering');
    } else if (Type == '2') {

        $('#FastOrdering').hide();
        $('#LoginOrdering').hide();
        $('#StandartOrdering').show();
        goToByScroll('StandartOrdering');
    } else if (Type == '3') {

        $('#FastOrdering').hide();
        $('#StandartOrdering').hide();
        $('#LoginOrdering').show();
        goToByScroll('LoginOrdering');
    }
    return false;
};


//Загрузка способов доставки
function BillingMethods() {
    UpdateTotalPrice();
    StyleAction('BillingMethods', 'start', false);
    var SID = (
        $("input:radio[name=shippingMethodID]:checked").val()
    );
    if (SID != 'undefined' || SID != '') {
        $.ajax({
            type: "POST",
            data: "GetBillingMethods=yep&SID=" + SID,
            success: function (ReturnHTML) {
                $('#BillingMethods').html(ReturnHTML);
                StyleAction('BillingMethods', 'end', false);
                SelectFirstBillingMethods();
                RadioStyleSet();
            },
            error: function () {
                StyleAction('BillingMethods', 'end', false);
            }
        });

    }
};

//Способы доставки
function ShippingMethods(countryID, zoneID, shippingAddressID) {
    StyleAction('ShippingMethods', 'start', false);
    if (countryID == undefined || isNaN(countryID)) {
        countryID = ''
    }
    ;
    if (zoneID == undefined || isNaN(zoneID)) {
        zoneID = ''
    }
    ;
    if (shippingAddressID == undefined || isNaN(shippingAddressID)) {
        shippingAddressID = ''
    }
    ;

    $.ajax({
        type: "POST",
        data: "GetShippingMethods=yep&countryID=" + countryID + "&zoneID=" + zoneID + "&shippingAddressID=" +
              shippingAddressID,
        dataType: "json",
        success: function (ReturnHTML) {
            $('#ShippingMethods').html(ReturnHTML.ReturnHTMLShipping);
            $('#BillingMethods').html(ReturnHTML.ReturnHTMLBilling);
            SelectFirstShippingMethods();
            SelectFirstBillingMethods();
            RadioStyleSet();
            UpdateTotalPrice();
            StyleAction('ShippingMethods', 'end', false);
        },
        error: function () {
            StyleAction('ShippingMethods', 'end', false);
        }
    });
};

//Выбор первоого способа оплаты
function SelectFirstBillingMethods() {
    if ($("input:radio[name=paymentMethodID]:checked").length == 0) {
        $("input:radio[name=paymentMethodID]:first").attr('checked', true);
    }
    if ($("input:radio[name=paymentMethodID]:checked").length == 0) {
        $('#SubmitOrderingDivStandart').hide();
    } else {
        $('#SubmitOrderingDivStandart').show();
    }

}

//Выбор первоого способа доставки
function SelectFirstShippingMethods() {
    if ($("input:radio[name=shippingMethodID]:checked").length == 0) {
        $("input:radio[name=shippingMethodID]:first").attr('checked', true);
    }
    UpdateTotalPrice();
}


//Обновление цен для доставки
function UpdateShippingPrices(ReturnData) {
    $.each(ReturnData.ShippingCosts, function (key, ShippingMethod) {
        if (ShippingMethod.length > 1) {
            var toHTML = '<div class="ShippingCostTD">';
            toHTML += '<select name="shippingServiceID[' + key + ']"  id="shippingServiceID' + key +
                      '" onchange="UpdateTotalPrice();" >';
            $.each(ShippingMethod, function (key, Cost) {
                toHTML += '<option value="' + Cost.id + '"  xRate="' + Cost.rateWithOutUnit + '">' + Cost.name + ' - ' +
                          Cost.rateWithUnit + '</option>';
            });
            toHTML += '</select>';
            toHTML += '</div><div class="onesteporder-shipping-n-payment-rate2-text">Варианты доставки:</div>';
            $('#ShippingCostSelect' + key).html(toHTML);
        } else {
            if (ShippingMethod[0].rate > 0) {
                var toHTML = ' ';
                if (ShippingMethod[0].name != '') {
                    toHTML += ShippingMethod[0].name;
                    if (ShippingMethod[0].rate != '') {
                        toHTML += ' - ';
                    }
                }
                if (ShippingMethod[0].rate != '') {
                    toHTML += '<span id="shippingCost' + key + '" xRate="' + ShippingMethod[0].rateWithOutUnit + '">' +
                              ShippingMethod[0].rateWithUnit + '</span>';
                }
                $('#ShippingCostSpan' + key).html(toHTML);
            } else {
                $('#ShippingCostSpan' + key).html('<span id="shippingCost' + key + '" xRate="0"></span>');
            }
        }


        UpdateTotalPrice();
    });
};

//Обнвление суммы заказа с учетом доставки
function UpdateTotalPrice() {
    var CurrentShipping = $("input:radio[name=shippingMethodID]:checked").val();
    if ($("#shippingServiceID" + CurrentShipping).length != 0) {
        var CurrentShippingCost = $("#shippingServiceID" + CurrentShipping + " option:selected").attr('xRate');
    } else if ($("#shippingCost" + CurrentShipping).length != 0) {
        var CurrentShippingCost = $("#shippingCost" + CurrentShipping).attr('xRate');
    } else {
        var CurrentShippingCost = 0;
    }
    $('#TotalOrderPrice').html(formatPrice(parseFloat($('#TotalOrderPriceInput').val()) +
                                           parseFloat(CurrentShippingCost)));
}

//Обновление цен
function UpdatePrePrice(ReturnData) {
    $('#TotalItemPrice').html(ReturnData.TotalItemPrice);
    $('#TotalOrderPrice').html(ReturnData.TotalItemPrice);
    $('#TotalOrderPriceFast').html(ReturnData.TotalItemPrice);
    $('#TotalOrderPriceInput').val(ReturnData.TotalItemPriceWhthoutUnits);
    $('#CouponPrice').html(ReturnData.CouponDiscount);
    if (ReturnData.CartDiscount != '') {
        $('#CartDiscountDiv').show();
        $('#CartDiscount').html(ReturnData.CartDiscount);
        $('#CartDiscountPersent').html(ReturnData.CartDiscountPersent.toFixed(1) + '%');
    } else {
        $('#CartDiscountDiv').hide();
    }

    return false;
};


//Смена региона
function ChangeState(Select, type) {
    var countryID = parseInt($('#ShippingCountry' + type).val());
    var zoneID = parseInt($(Select).val());
    if (type == "Standart") ShippingMethods(countryID, zoneID, '');
    CheckAdressChanged(type);
    return false;
};

//Смена страны
function ChangeCountry(Select, type) {
    var countryID = parseInt($(Select).val());
    var ArrayToStyle = {'type': type, 'Select': Select};
    StyleAction('ChangeCountry', 'start', ArrayToStyle);

    $.ajax({
        type: "POST",
        data: "SetAdress=yep&countryID=" + countryID + "&zoneID=",
        dataType: "json",
        success: function (ReturnHTML) {
            CheckAdressChanged(type);
            $('#ShippingZoneTD' + type).html('');
            if (ReturnHTML.isEmpty == true) {
                $('#ShippingZoneTD' +
                  type).html('<input name="shipping_address[state]" value="" type="text" id="stateStr' + type +
                             '" onkeyup="CheckAdressChanged(\'' + type + '\');" class="onesteporder-contact-input" />');
            } else {
                $('#ShippingZoneTD' + type).html('<select name="shipping_address[zoneID]" id="ShippingZone' + type +
                                                 '" onChange="ChangeState(this, \'' + type +
                                                 '\');" class="onesteporder-contact-select" ></select>');
                $.each(ReturnHTML.zones, function (key, value) {
                    $('#ShippingZone' + type).append('<option value="' + value.zoneID + '">' + value.zone_name +
                                                     '</option>');
                });
            }
            if (type == "Standart") {
                $('#ShippingMethods').html(ReturnHTML.ReturnHTMLShipping);
                $('#BillingMethods').html(ReturnHTML.ReturnHTMLBilling);
                SelectFirstShippingMethods();
                SelectFirstBillingMethods();
                RadioStyleSet();
                UpdateTotalPrice();
            }
            StyleAction('ChangeCountry', 'end', ArrayToStyle);
        },
        error: function () {
            StyleAction('ChangeCountry', 'end', ArrayToStyle);
        }
    });
    return false;
};

//Смена страны для платильщика
function ChangeBillingCountry(Select, type) {
    var countryID = parseInt($(Select).val());
    var ArrayToStyle = {'type': type, 'Select': Select};
    StyleAction('ChangeBillingCountry', 'start', ArrayToStyle);

    $.ajax({
        type: "POST",
        data: "SetAdress=yep&countryID=" + countryID + "&zoneID=",
        dataType: "json",
        success: function (ReturnHTML) {
            CheckAdressChanged(type);
            $('#BillingZoneTD' + type).html('');
            if (ReturnHTML.isEmpty == true) {
                $('#BillingZoneTD' +
                  type).html('<input name="billing_address[state]" value="" type="text"  class="onesteporder-contact-input" />');
            } else {
                $('#BillingZoneTD' + type).html('<select name="billing_address[zoneID]" id="BillingZone' + type +
                                                '" class="onesteporder-contact-select" ></select>');
                $.each(ReturnHTML.zones, function (key, value) {
                    $('#BillingZone' + type).append('<option value="' + value.zoneID + '">' + value.zone_name +
                                                    '</option>');
                });
            }
            StyleAction('ChangeBillingCountry', 'end', ArrayToStyle);
        },
        error: function () {
            StyleAction('ChangeBillingCountry', 'end', ArrayToStyle);
        }
    });
    return false;
};

//Показать все адреса
function SelectUserAdresses(type) {
    $('#AllAdresses' + type).is(":visible") ? $('#AllAdresses' + type).hide() : $('#AllAdresses' + type).show();
};

//Показать все адреса
function UpdateUserAdress(button, first_name, last_name, countryID, zoneID, zip, state, city, address, type, addressID) {
    var ArrayToStyle = {'type': type, 'button': button};
    StyleAction('UpdateUserAdress', 'start', ArrayToStyle);
    $.ajax({
        type: "POST",
        data: "SetAdress=yep&countryID=" + countryID + "&zoneID=" + zoneID,
        dataType: "json",
        success: function (ReturnData) {
            $('#SelectedAdressID' + type).val(addressID);
            $('#addressID' + type).val(addressID);
            $('#first_name' + type).val(first_name);
            $('#last_name' + type).val(last_name);
            $('#zip' + type).val(zip);
            $('#city' + type).val(city);
            $('#address' + type).val(address);
            if ($('#ShippingCountry' + type).val() != countryID) {
                $('#ShippingCountry' + type).val(countryID);
            }
            $('#ShippingZoneTD' + type).html('');
            if (ReturnData.isEmpty == true) {
                $('#ShippingZoneTD' + type).html('<input name="shipping_address[state]" value="' + state +
                                                 '" type="text" id="stateStr' + type +
                                                 '"  onkeyup="CheckAdressChanged(\'' + type +
                                                 '\');" class="onesteporder-contact-input" />');
            } else {
                $('#ShippingZoneTD' + type).html('<select name="shipping_address[zoneID]" id="ShippingZone' + type +
                                                 '" onChange="ChangeState(this, \'' + type +
                                                 '\');" class="onesteporder-contact-select" ></select>');
                $.each(ReturnData.zones, function (key, value) {
                    if (value.zoneID == zoneID) {
                        var checked = 'selected';
                    }
                    $('#ShippingZone' + type).append('<option value="' + value.zoneID + '" ' + checked + '>' +
                                                     value.zone_name + '</option>');
                });
            }
            if (type == "Standart") {
                $('#ShippingMethods').html(ReturnData.ReturnHTMLShipping);
                $('#BillingMethods').html(ReturnData.ReturnHTMLBilling);
                SelectFirstShippingMethods();
                SelectFirstBillingMethods();
                RadioStyleSet();
                UpdateTotalPrice();
            }
            StyleAction('UpdateUserAdress', 'end', ArrayToStyle);
        },
        error: function () {
            StyleAction('UpdateUserAdress', 'end', ArrayToStyle);
        }
    });
    return false;

};

//Проверка формы на вносимость изминений
function CheckAdressChanged(type) {
    if ($('#addressID' + type).val() != 0) {
        var countryID = $('#ShippingCountryStandart').val();
        var zoneID = $('#ShippingZoneStandart').val();
        ShippingMethods(countryID, zoneID, '');
    }
    $('#addressID' + type).val(0);
    $('.system_ChangeAdressButtons').removeAttr('disabled');
    $('.system_ChangeAdressButtons').removeClass('onesteporder-contact-button-disabled');
};


//Удаленить все элементы
function RemoveAllElements() {
    StyleAction('RemoveAllElements', false, false);
    $.ajax({
        type: "POST",
        data: "RemoveAllElements=yep",
        dataType: "json",
        success: function () {
            $('#shpcrtgc', window.parent.document).html(cart_content_empty);
            $('#shpcrtca', window.parent.document).html('&nbsp;');
            parent.closeFadeIFrame();
            $('#RemoveCart, #CartContent').remove();
            $('#EmptyCartMessage').show();

        },
        error: function () {
        }
    });
    return false;
};

//Удаленить элмент из корзины
function RemoveElement(elementID) {
    var countryID = $('#ShippingCountryStandart').val();
    var zoneID = $('#ShippingZoneStandart').val();
    var ArrayToStyle = {'elementID': elementID};
    StyleAction('RemoveElement', 'start', ArrayToStyle);

    $.ajax({
        type: "POST",
        data: "RemoveElement=yep&element=" + elementID + "&countryID=" + countryID + "&zoneID=" + zoneID,
        dataType: "json",
        success: function (ReturnData) {
            $('#CartElement' + elementID).remove();
            UpdatePrePrice(ReturnData);
            UpdateShippingPrices(ReturnData);
            if (ReturnData.isEmpty == true) {
                $('#CartContent').remove();
                $('#EmptyCartMessage').show();
                $('#shpcrtgc', window.parent.document).html(cart_content_empty);
                $('#shpcrtca', window.parent.document).html('&nbsp;');
                parent.closeFadeIFrame();
            } else {
                var ProductsNum = 0;
                $(".onesteporder-products-count-input").each(function () {
                    ProductsNum += parseFloat($(this).val());
                });
                $('#shpcrtgc', window.parent.document).html(ProductsNum + ' ' + srch_products_plural);
                $('#shpcrtca', window.parent.document).html(ReturnData.TotalItemPrice);
            }
            StyleAction('RemoveElement', 'end', ArrayToStyle);
            CartMinimalAmount(ReturnData.isErrorMinimalAmount);
        },
        error: function () {
            StyleAction('RemoveElement', 'end', ArrayToStyle);
        }
    });
    return false;
};


//Конфигурация товара
function ChangeConfigurationItem(Item, in_stock) {

    var datapost = $('#configuration-item-' + Item).serialize();
    var input = $('#ProductQty' + Item).val();
    var countryID = $('#ShippingCountryStandart').val();
    var zoneID = $('#ShippingZoneStandart').val();

    var ArrayToStyle = {'elementID': Item, 'input': input, 'in_stock': in_stock};
    StyleAction('RecalculateCart', 'start', ArrayToStyle);

    $.ajax({
        type: "POST",
        data: "ChangeConfigurationItem=yep&countryID=" + countryID + "&zoneID=" + zoneID + "&" + datapost,
        dataType: "json",
        success: function (ReturnData) {

            if (ReturnData.ProductData) {
                if (ReturnData.ProductData.removeElement) {
                    $('#CartElement' + ReturnData.ProductData.removeElement).remove();
                    $('.onesteporder-products-table tr:last').prev().removeClass('onesteporder-products-tr').addClass('onesteporder-products-tr-prelast');
                }
                if (ReturnData.ProductData.updateElement) {
                    var oldItem = ReturnData.ProductData.updateElement;
                    var newItem = ReturnData.ProductData.id;


                    $('#CartElement' + oldItem + ' input[name="itemID"]').val(newItem);

                    var configuration_item_url = $('#CartElement' + oldItem +
                                                   ' .onesteporder-configuration-item-url a').attr("onclick").replace(Item, newItem);
                    $('#CartElement' + oldItem +
                      ' .onesteporder-configuration-item-url a').attr("onclick", configuration_item_url);
                    $('#CartElement' + oldItem +
                      ' .onesteporder-configuration-item-div').attr('id', 'onesteporder-configuration-item-div-' +
                                                                          newItem);

                    var configuration_item_submit = $('#CartElement' + oldItem +
                                                      ' .configuration-item-submit').attr("onclick").replace(Item, newItem);
                    $('#CartElement' + oldItem +
                      ' .configuration-item-submit').attr("onclick", configuration_item_submit);

                    var products_count_icon_p = "RecalculateCartIcons(true, '" + newItem + "','" +
                                                ReturnData.ProductData.costUC + "','" + in_stock + "');";
                    var products_count_icon_m = "RecalculateCartIcons(false, '" + newItem + "','" +
                                                ReturnData.ProductData.costUC + "','" + in_stock + "');";

                    $('#CartElement' + oldItem +
                      ' .onesteporder-products-count-icon-p').attr("onclick", products_count_icon_p);
                    $('#CartElement' + oldItem +
                      ' .onesteporder-products-count-icon-m').attr("onclick", products_count_icon_m);
                    $('#CartElement' + oldItem +
                      ' .onesteporder-products-count-icon-p').addClass('system_RecalculateCartIconP' +
                                                                       newItem).removeClass('system_RecalculateCartIconP' +
                                                                                            oldItem);
                    $('#CartElement' + oldItem +
                      ' .onesteporder-products-count-icon-m').addClass('system_RecalculateCartIconM' +
                                                                       newItem).removeClass('system_RecalculateCartIconM' +
                                                                                            oldItem);

                    var RemoveElement = "RemoveElement('" + newItem + "');";
                    $('#RemoveElement' + oldItem).attr("onclick", RemoveElement).attr('id', 'RemoveElement' + newItem);

                    $('#CartElement' + oldItem +
                      ' .onesteporder-products-name a:first').html(ReturnData.ProductData.name);
                    $('#ProductPrice' + oldItem).html(ReturnData.ProductData.cost).attr('id', 'ProductPrice' + newItem);

                    $('#CartElement' + oldItem +
                      ' .onesteporder-products-count-input').val(ReturnData.ProductData.quantity);
                    var RecalculateCart = "RecalculateCart(this,'" + newItem + "','" + ReturnData.ProductData.costUC +
                                          "','" + in_stock + "');";
                    $('#CartElement' + oldItem +
                      ' .onesteporder-products-count-input').attr("onBlur", RecalculateCart).attr('name', 'count_' +
                                                                                                          newItem).attr('id', 'ProductQty' +
                                                                                                                              newItem);

                    $('#CartElement' + oldItem).attr('id', 'CartElement' + newItem);
                    $('#configuration-item-' + oldItem).attr('id', 'configuration-item-' + newItem);

                    ArrayToStyle = {'elementID': newItem, 'input': '#ProductQty' + newItem, 'in_stock': in_stock};

                }
            }

            var ProductsNum = 0;
            $(".onesteporder-products-count-input").each(function () {
                ProductsNum += parseFloat($(this).val());
            });
            $('#shpcrtgc', window.parent.document).html(ProductsNum + ' ' + srch_products_plural);
            $('#shpcrtca', window.parent.document).html(ReturnData.TotalItemPrice);
            UpdatePrePrice(ReturnData);
            UpdateShippingPrices(ReturnData);
            StyleAction('RecalculateCart', 'end', ArrayToStyle);
        },
        error: function () {
            StyleAction('RecalculateCart', 'end', ArrayToStyle);
        }
    });

    return false;
};

//Перерасчет кол-ва
function RecalculateCartIcons(action, elementID, costUC, in_stock) {
    if (action == true) {
        $('#ProductQty' + elementID).val($('#ProductQty' + elementID).val() * 1 + 1);
        RecalculateCart('#ProductQty' + elementID, elementID, costUC, in_stock);
    } else {
        if ($('#ProductQty' + elementID).val() > 1) {
            $('#ProductQty' + elementID).val($('#ProductQty' + elementID).val() * 1 - 1);
            RecalculateCart('#ProductQty' + elementID, elementID, costUC, in_stock);
        }
    }
    return false;
};

//Перерасчет кол-ва
function RecalculateCart(input, elementID, costUC, in_stock) {
    var FieldCount = $(input).val();
    if (parseInt(FieldCount) != FieldCount) $(input).val(FieldCount.replace(new RegExp('[^\\d]|[\\s]', 'g'), ''));
    if (parseInt(FieldCount) > parseInt(in_stock)) $(input).val(FieldCount.replace(FieldCount, in_stock));
    if (parseInt($(input).val()) < 1 || $(input).val() == '') $(input).val(1);
    var countryID = $('#ShippingCountryStandart').val();
    var zoneID = $('#ShippingZoneStandart').val();

    var ArrayToStyle = {'elementID': elementID, 'input': input, 'in_stock': in_stock};
    StyleAction('RecalculateCart', 'start', ArrayToStyle);

    $.ajax({
        type: "POST",
        data: "RecalculateCart=yep&element=" + elementID + "&count=" + $(input).val() + "&costUC=" +
              parseFloat(costUC) + "&countryID=" + countryID + "&zoneID=" + zoneID,
        dataType: "json",
        success: function (ReturnData) {
            $('#ProductPrice' + elementID).html(ReturnData.ElementPrice);

            var ProductsNum = 0;
            $(".onesteporder-products-count-input").each(function () {
                ProductsNum += parseFloat($(this).val());
            });

            $('#shpcrtgc', window.parent.document).html(ProductsNum + ' ' + srch_products_plural);
            $('#shpcrtca', window.parent.document).html(ReturnData.TotalItemPrice);

            UpdatePrePrice(ReturnData);
            UpdateShippingPrices(ReturnData);
            CartMinimalAmount(ReturnData.isErrorMinimalAmount);
            StyleAction('RecalculateCart', 'end', ArrayToStyle);
        },
        error: function () {
            StyleAction('RecalculateCart', 'end', ArrayToStyle);
        }
    });

    return true;
};

//ПРименить купон
function ApplyCoupon() {
    var countryID = $('#ShippingCountryStandart').val();
    var zoneID = $('#ShippingZoneStandart').val();
    StyleAction('ApplyCoupon', 'start', '');

    $.ajax({
        type: "POST",
        data: "ApplyCoupon=yep&CouponCode=" + $('#CouponCodeInput').val(),
        dataType: "json",
        success: function (ReturnData) {
            StyleAction('ApplyCoupon', 'end', '');
            $('#TotalItemPrice').html(ReturnData.TotalItemPrice);
            $('#TotalOrderPriceInput').val(ReturnData.TotalItemPriceWhthoutUnits);
            $('#TotalOrderPriceFast').html(ReturnData.TotalItemPrice);
            UpdateShippingPrices(ReturnData);
            if (ReturnData.Applied == 'N') {
                $('#CouponWrong').show();
            } else {
                $(".onesteporder-coupon-field").hide();
                $('#CouponPrice').html(ReturnData.NewCoupon);
                $('#CouponCode').html($('#CouponCodeInput').val());
                $('.system_CouponDivForm').hide();
                $('.system_CouponDivResult').show();
            }
            CartMinimalAmount(ReturnData.isErrorMinimalAmount);
        },
        error: function () {
            StyleAction('ApplyCoupon', 'end', '');
        }
    });

    return true;
};

//Удалить купон
function DeleteCoupon() {
    var countryID = $('#ShippingCountryStandart').val();
    var zoneID = $('#ShippingZoneStandart').val();
    StyleAction('DeleteCoupon', 'start', '');
    $.ajax({
        type: "POST",
        data: "DeleteCoupon=yep",
        dataType: "json",
        success: function (ReturnData) {
            UpdateShippingPrices(ReturnData);
            $('#TotalItemPrice').html(ReturnData.TotalItemPrice);
            $('#TotalOrderPriceInput').val(ReturnData.TotalItemPriceWhthoutUnits);
            $('#TotalOrderPriceFast').html(ReturnData.TotalItemPrice);
            StyleAction('DeleteCoupon', 'end', '');
            $('#CouponCodeInput').val($('#CouponCode').html());
            $('.system_CouponDivResult').hide();
            $('.system_CouponDivForm').show();
            $(".onesteporder-coupon-field").show();
            CartMinimalAmount(ReturnData.isErrorMinimalAmount);
        },
        error: function () {
            StyleAction('DeleteCoupon', 'end', '');
        }
    });
    return true;
};

//Проверка формы
function CheckBeforeSubmit(form) {
    var sendData = $(form).serialize();
    StyleAction('CheckBeforeSubmit', 'start', '');
    $.ajax({
        type: "POST",
        data: "CheckBeforeSubmit=yep&" + sendData,
        dataType: "json",
        success: function (ReturnData) {
            if (ReturnData.Result == 'noErrors') {
                $(form).submit();
            } else {
                alert(ReturnData.Result);
                //$('.onesteporder-code-img').attr('src',url_root+'/imgval.php');
                StyleAction('CheckBeforeSubmit', 'end', '');
                return false;
            }
        },
        error: function () {
            StyleAction('CheckBeforeSubmit', 'end', '');
        }
    });
    return false;
};

//Проверка минимального заказа
function CartMinimalAmount(value) {
    if (value == true) {
        $('#CartMinimalAmount').show();
    } else {
        $('#CartMinimalAmount').hide();
    }
}

//Проверка формы
function showRegistrationForm(checkbox) {
    if ($(checkbox).is(':checked')) {
        $('#RegistrationFieldsStandart').show();
    } else {
        $('#RegistrationFieldsStandart').hide();
    }
};


//Cилизация действий
function StyleAction(Action, Type, Array) {
    switch (Action) {
        case 'ShippingMethods':
            if (Type == 'start') {
                $('#ShippingMethods, #BillingMethods').fadeTo('fast', .5);
                StyleRadioDisable(true);
                StyleSumbitDisable(true, false);
            } else {
                $('#ShippingMethods, #BillingMethods').fadeTo('fast', 1);
                StyleRadioDisable(false);
                StyleSumbitDisable(false, false);
            }
            break
        case 'BillingMethods':
            if (Type == 'start') {
                $('#BillingMethods').fadeTo('fast', 0.5);
                StyleRadioDisable(true);
                StyleSumbitDisable(true, false);
            } else {
                $('#BillingMethods').fadeTo('fast', 1);
                StyleRadioDisable(false);
                StyleSumbitDisable(false, false);
            }
            break
        case 'ChangeCountry':
            if (Type == 'start') {
                $('#ShippingZoneTR' + Array['type']).fadeTo('fast', .5);
                if (Array['type'] == "Standart") {
                    $('#ShippingMethods, #BillingMethods').fadeTo('fast', .5);
                    StyleRadioDisable(true);
                    StyleSumbitDisable(true, false);
                }
                $(Array['Select']).attr('disabled', 'true');
            } else {
                if (Array['type'] == "Standart") {
                    $('#ShippingMethods, #BillingMethods').fadeTo('fast', 1);
                    StyleRadioDisable(false);
                    StyleSumbitDisable(false, false);
                }
                $(Array['Select']).removeAttr('disabled');
                $('#ShippingZoneTR' + Array['type']).fadeTo('fast', 1);
            }
            break
        case 'ChangeBillingCountry':
            if (Type == 'start') {
                $('#BillingZoneTR' + Array['type']).fadeTo('fast', .5);
                if (Array['type'] == "Standart") {
                    StyleRadioDisable(true);
                    StyleSumbitDisable(true, false);
                }
                $(Array['Select']).attr('disabled', 'true');
            } else {
                if (Array['type'] == "Standart") {
                    StyleRadioDisable(false);
                    StyleSumbitDisable(false, false);
                }
                $(Array['Select']).removeAttr('disabled');
                $('#BillingZoneTR' + Array['type']).fadeTo('fast', 1);
            }
            break
        case 'UpdateUserAdress':
            if (Type == 'start') {
                if (Array['type'] == "Standart") {
                    $('#ShippingMethods, #BillingMethods').fadeTo('fast', .5);
                    StyleRadioDisable(true);
                    StyleSumbitDisable(true, false);
                }
                $("#ShippingCountry" + Array['type'] + ",#ShippingZone" + Array['type'] + ",#stateStr" + Array['type'] +
                  ",.system_ChangeAdressButtons").attr('disabled', 'true');
                $(".system_ChangeAdressButtons").addClass('onesteporder-contact-button-disabled');
                $('#ShippingFirstNameTR' + Array['type'] + ',#ShippingLastNameTR' + Array['type'] + ',#ShippingZipTR' +
                  Array['type'] + ',#ShippingCityTR' + Array['type'] + ',#ShippingAdressTR' + Array['type'] +
                  ',#ShippingZoneTR' + Array['type'] + ',#ShippingCountryTR' + Array['type']).fadeTo('fast', .5);
                $('#ShippingFirstNameTR' + Array['type'] + ' input,#ShippingLastNameTR' + Array['type'] +
                  ' input,#ShippingZipTR' + Array['type'] + ' input, #ShippingCityTR' + Array['type'] +
                  ' input,#ShippingAdressTR' + Array['type'] + ' textarea').attr('disabled', 'true');
            } else {
                $('#ShippingFirstNameTR' + Array['type'] + ',#ShippingLastNameTR' + Array['type'] + ',#ShippingZipTR' +
                  Array['type'] + ',#ShippingCityTR' + Array['type'] + ',#ShippingAdressTR' + Array['type'] +
                  ',#ShippingZoneTR' + Array['type'] + ',#ShippingCountryTR' + Array['type']).fadeTo('fast', 1);
                $('#ShippingFirstNameTR' + Array['type'] + ' input,#ShippingLastNameTR' + Array['type'] +
                  ' input,#ShippingZipTR' + Array['type'] + ' input, #ShippingCityTR' + Array['type'] +
                  ' input,#ShippingAdressTR' + Array['type'] + ' textarea').removeAttr('disabled');
                $("#ShippingCountry" + Array['type'] + ",#ShippingZone" + Array['type'] + ",#stateStr" + Array['type'] +
                  ",.system_ChangeAdressButtons").removeAttr('disabled');
                $(".system_ChangeAdressButtons").removeClass('onesteporder-contact-button-disabled');
                $(Array['button']).attr('disabled', 'true');
                $(Array['button']).addClass('onesteporder-contact-button-disabled');
                if (Array['type'] == "Standart") {
                    StyleSumbitDisable(false, false);
                    StyleRadioDisable(false);
                    $('#BillingMethods').fadeTo('fast', 1);
                    $('#ShippingMethods').fadeTo('fast', 1);
                }
            }
            break
        case 'RemoveAllElements':
            $('#CartContent').fadeTo('fast', .5);
            break
        case 'RemoveElement':
            if (Type == 'start') {
                $('.system_ShippingCostTD, #CartElement' + Array['elementID']).fadeTo('fast', .5);
                $('#RemoveElement' + Array['elementID'] + ' span').addClass('disabled');
            } else {
                $('.system_ShippingCostTD, #CartElement' + Array['elementID']).fadeTo('fast', 1);
                $('#RemoveElement' + Array['elementID'] + ' span').removeClass('disabled');
                $('.onesteporder-products-table tr:last').prev().removeClass('onesteporder-products-tr').addClass('onesteporder-products-tr-prelast');
            }
            break
        case 'RecalculateCart':
            if (Type == 'start') {
                $('.system_ShippingCostTD, #CartElement' + Array['elementID']).fadeTo('fast', .5);
                $(Array['input']).attr('disabled', 'true');
                $('.system_RecalculateCartIconP' + Array['elementID'] + ', .system_RecalculateCartIconM' +
                  Array['elementID'] + '').attr('disabled', 'true');
                $('.system_RecalculateCartIconP' +
                  Array['elementID']).addClass('onesteporder-products-count-icon-p-disabled');
                $('.system_RecalculateCartIconM' +
                  Array['elementID']).addClass('onesteporder-products-count-icon-m-disabled');

                StyleSumbitDisable(true, false);
            } else {
                $('.system_ShippingCostTD, #CartElement' + Array['elementID']).fadeTo('fast', 1);
                if ($(Array['input']).val() == 1) {
                    $('.system_RecalculateCartIconP' + Array['elementID']).removeAttr('disabled');
                    $('.system_RecalculateCartIconP' +
                      Array['elementID']).removeClass('onesteporder-products-count-icon-p-disabled');
                } else if ($(Array['input']).val() == Array['in_stock']) {
                    $('.system_RecalculateCartIconM' + Array['elementID']).removeAttr('disabled');
                    $('.system_RecalculateCartIconM' +
                      Array['elementID']).removeClass('onesteporder-products-count-icon-m-disabled');
                } else {
                    $('.system_RecalculateCartIconP' + Array['elementID'] + ', .system_RecalculateCartIconM' +
                      Array['elementID']).removeAttr('disabled');
                    $('.system_RecalculateCartIconP' +
                      Array['elementID']).removeClass('onesteporder-products-count-icon-p-disabled');
                    $('.system_RecalculateCartIconM' +
                      Array['elementID']).removeClass('onesteporder-products-count-icon-m-disabled');
                }
                $(Array['input']).removeAttr('disabled');
                StyleSumbitDisable(false, false);
            }
            break

        case 'ApplyCoupon':
            if (Type == 'start') {
                $('.system_ShippingCostTD, .onesteporder-contact-button, .onesteporder-coupon-field-input').fadeTo('fast', .5);
                $('#CouponCodeButton, #CouponCodeInput').attr('disabled', 'true');
                $('#CouponWrong, #CouponProcessing').hide();

            } else {
                $('.system_ShippingCostTD, .onesteporder-contact-button, .onesteporder-coupon-field-input').fadeTo('fast', 1);
                $('#CouponProcessing').hide();
                $('#CouponCodeButton, #CouponCodeInput').removeAttr('disabled');
            }
            break
        case 'DeleteCoupon':
            if (Type == 'start') {
                $('.system_ShippingCostTD, #CouponTR').fadeTo('fast', .5);
            } else {
                $('.system_ShippingCostTD, #CouponTR').fadeTo('fast', 1);
            }
            break
        case 'CheckBeforeSubmit':
            if (Type == 'start') {
                StyleSumbitDisable(true, true);
            } else {
                StyleSumbitDisable(false, true);
            }
            break
        default:
            return false
    }
}

function StyleSumbitDisable(type, all) {
    if (all == true) {
        var Fast = ', #SubmitOrderingFast';
    } else {
        var Fast = '';
    }
    if (type == true) {
        $('#SubmitOrderingStandart' + Fast).attr('disabled', 'true');
        $('#SubmitOrderingStandart' + Fast).addClass('onesteporder-products-total-submit-disabled');
    } else {
        $('#SubmitOrderingStandart' + Fast).removeAttr('disabled');
        $('#SubmitOrderingStandart' + Fast).removeClass('onesteporder-products-total-submit-disabled');
    }
}

function StyleRadioDisable(type) {
    if (type == true) {
        $("input:radio[name=shippingMethodID], input:radio[name=paymentMethodID]").attr('disabled', 'true');
        $("input:radio[name=shippingMethodID], input:radio[name=paymentMethodID]").each(function () {
            if ($(this).is(":checked")) {
                $(this).next().children().first().addClass('custom_radio_active_disabled');
            } else {
                $(this).next().children().first().addClass('custom_radio_disabled');
            }
        });
    } else {
        $("input:radio[name=shippingMethodID]").removeAttr('disabled');
        $("input:radio[name=shippingMethodID], input:radio[name=paymentMethodID]").each(function () {
            if ($(this).is(":checked")) {
                $(this).next().children().first().removeClass('custom_radio_active_disabled');
            } else {
                $(this).next().children().first().removeClass('custom_radio_disabled');
            }
        });
    }
}

function ShowCouponField() {
    $(".onesteporder-coupon-field").toggle();
}

function ShowConfigurationField(id) {
    $(".onesteporder-configuration-item-div, #CartElement" + id + " .onesteporder-configuration-item-url a").hide();
    $(".onesteporder-products-name a").removeClass('onesteporder-configuration-name-current');
    $("#onesteporder-configuration-item-div-" + id).show();
    $("#CartElement" + id + " .onesteporder-products-name a:first").addClass('onesteporder-configuration-name-current');
}

function HideConfigurationField() {
    $(".onesteporder-configuration-item-div").hide();
    $(".onesteporder-configuration-item-url a").show();
    $(".onesteporder-products-name a").removeClass('onesteporder-configuration-name-current');
}

function BillingAdressForm(type) {
    if ($('#billing_as_shipping' + type).is(':checked')) {
        $('#BillingAdress' + type).hide();
    } else {
        $('#BillingAdress' + type).show();
    }
}


//Маска
(
    function (a) {
        var b = (
                    a.browser.msie ? "paste" : "input"
                ) + ".mask", c = window.orientation != undefined;
        a.mask = {
            definitions: {9: "[0-9]", a: "[A-Za-z]", "*": "[A-Za-z0-9]"},
            dataName: "rawMaskFn"
        }, a.fn.extend({
            caret: function (a, b) {
                if (this.length != 0) {
                    if (typeof a == "number") {
                        b = typeof b == "number" ? b : a;
                        return this.each(function () {
                            if (this.setSelectionRange)this.setSelectionRange(a, b); else if (this.createTextRange) {
                                var c = this.createTextRange();
                                c.collapse(!0), c.moveEnd("character", b), c.moveStart("character", a), c.select()
                            }
                        })
                    }
                    if (this[0].setSelectionRange)a = this[0].selectionStart, b = this[0].selectionEnd; else if (document.selection &&
                                                                                                                 document.selection.createRange) {
                        var c = document.selection.createRange();
                        a = 0 - c.duplicate().moveStart("character", -1e5), b = a + c.text.length
                    }
                    return {begin: a, end: b}
                }
            }, unmask: function () {
                return this.trigger("unmask")
            }, mask: function (d, e) {
                if (!d && this.length > 0) {
                    var f = a(this[0]);
                    return f.data(a.mask.dataName)()
                }
                e = a.extend({placeholder: "_", completed: null}, e);
                var g = a.mask.definitions, h = [], i = d.length, j = null, k = d.length;
                a.each(d.split(""), function (a, b) {
                    b == "?" ? (
                        k--, i = a
                    ) : g[b] ? (
                        h.push(new RegExp(g[b])), j == null && (
                            j = h.length - 1
                        )
                    ) : h.push(null)
                });
                return this.trigger("unmask").each(function () {
                    function v(a) {
                        var b = f.val(), c = -1;
                        for (var d = 0, g = 0; d < k; d++)if (h[d]) {
                            l[d] = e.placeholder;
                            while (g++ < b.length) {
                                var m = b.charAt(g - 1);
                                if (h[d].test(m)) {
                                    l[d] = m, c = d;
                                    break
                                }
                            }
                            if (g > b.length)break
                        } else l[d] == b.charAt(g) && d != i && (
                            g++, c = d
                        );
                        if (!a && c + 1 < i)f.val(""), t(0, k); else if (a || c + 1 >= i)u(), a ||
                                                                                              f.val(f.val().substring(0, c +
                                                                                                                         1));
                        return i ? d : j
                    }

                    function u() {
                        return f.val(l.join("")).val()
                    }

                    function t(a, b) {
                        for (var c = a; c < b && c < k; c++)h[c] && (
                            l[c] = e.placeholder
                        )
                    }

                    function s(a) {
                        var b = a.which, c = f.caret();
                        if (a.ctrlKey || a.altKey || a.metaKey || b < 32)return !0;
                        if (b) {
                            c.end - c.begin != 0 && (
                                t(c.begin, c.end), p(c.begin, c.end - 1)
                            );
                            var d = n(c.begin - 1);
                            if (d < k) {
                                var g = String.fromCharCode(b);
                                if (h[d].test(g)) {
                                    q(d), l[d] = g, u();
                                    var i = n(d);
                                    f.caret(i), e.completed && i >= k && e.completed.call(f)
                                }
                            }
                            return !1
                        }
                    }

                    function r(a) {
                        var b = a.which;
                        if (b == 8 || b == 46 || c && b == 127) {
                            var d = f.caret(), e = d.begin, g = d.end;
                            g - e == 0 && (
                                e = b != 46 ? o(e) : g = n(e - 1), g = b == 46 ? n(g) : g
                            ), t(e, g), p(e, g - 1);
                            return !1
                        }
                        if (b == 27) {
                            f.val(m), f.caret(0, v());
                            return !1
                        }
                    }

                    function q(a) {
                        for (var b = a, c = e.placeholder; b < k; b++)if (h[b]) {
                            var d = n(b), f = l[b];
                            l[b] = c;
                            if (d < k && h[d].test(f))c = f; else break
                        }
                    }

                    function p(a, b) {
                        if (!(
                                a < 0
                            )) {
                            for (var c = a, d = n(b); c < k; c++)if (h[c]) {
                                if (d < k && h[c].test(l[d]))l[c] = l[d], l[d] = e.placeholder; else break;
                                d = n(d)
                            }
                            u(), f.caret(Math.max(j, a))
                        }
                    }

                    function o(a) {
                        while (--a >= 0 && !h[a]);
                        return a
                    }

                    function n(a) {
                        while (++a <= k && !h[a]);
                        return a
                    }

                    var f = a(this), l = a.map(d.split(""), function (a, b) {
                        if (a != "?")return g[a] ? e.placeholder : a
                    }), m = f.val();
                    f.data(a.mask.dataName, function () {
                        return a.map(l, function (a, b) {
                            return h[b] && a != e.placeholder ? a : null
                        }).join("")
                    }), f.attr("readonly") || f.one("unmask", function () {
                        f.unbind(".mask").removeData(a.mask.dataName)
                    }).bind("focus.mask", function () {
                        m = f.val();
                        var b = v();
                        u();
                        var c = function () {
                            b == d.length ? f.caret(0, b) : f.caret(b)
                        };
                        (
                            a.browser.msie ? c : function () {
                                setTimeout(c, 0)
                            }
                        )()
                    }).bind("blur.mask", function () {
                        v(), f.val() != m && f.change()
                    }).bind("keydown.mask", r).bind("keypress.mask", s).bind(b, function () {
                        setTimeout(function () {
                            f.caret(v(!0))
                        }, 0)
                    }), v()
                })
            }
        })
    }
)(jQuery)