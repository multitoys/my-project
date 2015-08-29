$(document).ready(function() {
    $(function(){
        var seconds = $('.zakcia_body').attr('data-time');
        var _date = new Date();
        _date.setSeconds(seconds);
        $('#z_counter').countdown({
             image: '/img/_digits.png',
             startTime: _date
        });
    });
});
