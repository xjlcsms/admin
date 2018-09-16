/* 浏览条数设置 start */
(function(){
  $("#selectPage").change(function () {
    var my_href=window.location.href;
    var index=my_href.indexOf("?");
    var index_pagelimit=my_href.indexOf("pagelimit");

    if(index > -1){
      if(index_pagelimit > -1){
        if(index>index_pagelimit){
          var first_half=my_href.substring(0,index_pagelimit+10);
          var second_half=my_href.substring(index)
          var new_href=first_half+$("#selectPage").val()+second_half;
        }else{
          var first_half=my_href.substring(0,index_pagelimit);
          var new_href=first_half+"pagelimit="+$("#selectPage").val();
        } 
      }else{
        var new_href=my_href+"&pagelimit="+$("#selectPage").val();
      }
    }else{
        if(index_pagelimit>-1){
          var first_half=my_href.substring(0,index_pagelimit);
          var new_href=first_half+"pagelimit/"+$("#selectPage").val();
        }else{
          if(my_href[my_href.length-1] == "/"){
            var new_href=my_href+"pagelimit/"+$("#selectPage").val();
          }else{
            var new_href=my_href+"/pagelimit/"+$("#selectPage").val();
          }
        }
      }
    window.location.href=new_href;
  });
})();