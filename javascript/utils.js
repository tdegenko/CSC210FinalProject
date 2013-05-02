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
