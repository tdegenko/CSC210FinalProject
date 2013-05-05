function genEvent(access_token,name,start_time,end_time,desc,loc,loc_id,priv){
    var req="https://graph.facebook.com/me/events?";
    var dat={};
    if(!access_token){
        return 1;
    }else{
       dat.access_token=access_token;
    }
    if(!name){
        return 2;
    }else{
       dat.name=name;
    }
    if(!start_time){
        return 3;
    }else{
       dat.start_time=start_time;
    }
    if(end_time){
       dat.end_time=end_time;
    }
    if(desc){
       dat.description=desc;
    }
    if(loc){
       dat.location=loc;
    }
    if(loc_id){
       dat.location_id=loc_id;
    }
    if(priv){
       dat.privacy_type=priv;
    }
    $.ajax({
        type: "POST",
        url: req,
        data:dat,
        success: function(a){
                console.log(a);
            }
    });

}
function fbDate(year, month, day, hour, min){
    if (month<10){
        month="0"+parseInt(month);
    }
    if (day<10){
        day="0"+parseInt(day);
    }
    if (hour<10){
        hour="0"+parseInt(hour);
    }
    if (min<10){
        min="0"+parseInt(min);
    }
    time=year+"-"+month+"-"+day+"T"+hour+":"+min+":00-0000";
    return time;

}
$(document).ready(function() {
    $("#eventgen").on("submit","#eventform", function(e) {
        e.preventDefault();
        var sh = $("#eventform [name='shour']").val();
        var sm = $("#eventform [name='smin']").val();
        var eh = $("#eventform [name='ehour']").val();
        var em = $("#eventform [name='emin']").val();
        var year = $("#eventform #datepicker").val().substring(6);
        var month = $("#eventform #datepicker").val().substring(0,2);
        var day = $("#eventform #datepicker").val().substring(3,5);

        var auth = FB.getAuthResponse()['accessToken'];
        var name = $("#eventform [name='name']").val();
        var stime = fbDate(year,month,day,sh,sm);
        var etime = fbDate(year,month,day,eh,em);
        var desc = $("#eventform [name='description']").val();
        var loc = $("#eventform [name='location']").val();
        var priv = $("#eventform [name='privacy_type']:checked").val();
        genEvent(auth,name,stime,etime,desc,loc,"",priv);
    });
});



