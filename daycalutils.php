<?php
function genLoc($stime){
    $stime=strtotime($stime);
    $h=date("G",$stime);
    $m=date("i",$stime);
    return (60*$h)+$m;
}

function genH($stime,$etime){
    $stime=strtotime($stime);
    if ($etime){
        $etime=strtotime($etime);
    }else{
        $etime=strtotime(date("Y-m-d\T24:00",$stime));
    }
    return ($etime-$stime)/60;
}

?>
