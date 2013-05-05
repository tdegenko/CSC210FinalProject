$(document).ready(function() {
    $("#tables").on("click",".day", function(e) {
        e.preventDefault();
        var data = {
                "month":    $('meta[name=cmonth]').attr("content"),
                "year":     $('meta[name=cyear]').attr("content"),
                "day":      $(this).text()
            }
        $.get("daycal.php", data,function(data){
            $('#daycal').slideUp(function(){
                $('#daycal').html(data).slideDown();
            });
        });
    });
    $('#daycal').on("click",'td', function(e){
        sm=e.offsetY;
        sh=$.trim($(this).text());
        if (sm<15){
            sm="00";
        }else{
            if (sm<45){
                sm=30;
            }else{
                sh=sh+1;
            }
        }
        if(sh<10){
            sh="0"+sh;
        }
        year=$('meta[name=cyear]').attr("content");
        month=$('meta[name=cmonth]').attr("content");
        if(month<10){
            month="0"+month;
        }
        day=$('meta[name=cday]').attr("content");
        if(day<10){
            day="0"+day;
        }
        time=year+"-"+month+"-"+day+"T"+sh+":"+sm+":00";
        console.log(time);
    });
});
