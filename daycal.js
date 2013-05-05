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
            sm=0;
        }else{
            if (sm<45){
                sm=30;
            }else{
                sh=sh+1;
            }
        }
        year=$('meta[name=cyear]').attr("content");
        month=$('meta[name=cmonth]').attr("content");
        day=$('meta[name=cday]').attr("content");
        var data = {
            "month":    month,
            "year":     year,
            "day":      day,
            "hour":     sh,
            "min":      sm,
            "friends":  []
        }
        console.log(data);
        $.get("eventgen.php", data,function(data){
            $('#eventgen').slideUp(function(){
                $('#eventgen').html(data).slideDown();
            });
        });
    });
});
