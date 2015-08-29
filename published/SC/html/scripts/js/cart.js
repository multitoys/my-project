function del_items()
{
  $(".del_chkbtn:checked").each(function(){
    var id = $(this).attr('pid');
    var query = '?ukey=cart&view=noframe&remove='+id;
    $.ajax({
      type: "GET",
      url: query,
      dataType: "html",
      async: false
    });
  });
  $('#recalculate').attr('name','recalculate');
  $('form').submit();
}