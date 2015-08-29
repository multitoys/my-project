function select_shipping_methodClickHandler() {
    document.getElementById('checkout_button').disabled = true;
    var r_shipping_method = getElementsByClass('radio_shipping_method', document.MainForm);
    for (var i = r_shipping_method.length - 1; i >= 0; i--) {
        r_shipping_method[i].onclick = select_shipping_methodClickHandler;
        if (!r_shipping_method[i].checked)continue;
        document.getElementById('checkout_button').disabled = false;
        break;
    }
}
select_shipping_methodClickHandler();
disable_button = function () {
    setTimeout(
        function () {
            var button = document.getElementById('checkout_button');
            if (button)button.disabled = true;
        }, 50);
};