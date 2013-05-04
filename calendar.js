$(document).ready(function() {
    $('#next').click(function(e) {
        e.preventDefault();
        var data = {
                "month":    $('meta[name=nmonth]').attr("content"),
                "year":     $('meta[name=nyear]').attr("content")
            }
        console.log(data);
        $.get("calendar.php", data,function(data) {
            $('#tables').html(data);
        });
    });
    $('#prev').click(function(e) {
        e.preventDefault();
        var data = {
                "month":    $('meta[name=pmonth]').attr("content"),
                "year":     $('meta[name=pyear]').attr("content")
            }
        console.log(data);
        $.get("calendar.php", data,function(data) {
            $('#tables').html(data);
        });
    });
});
