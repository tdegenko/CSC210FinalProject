$(document).ready(function() {
    $("#tables").on("click",".day a", function(e) {
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
});
