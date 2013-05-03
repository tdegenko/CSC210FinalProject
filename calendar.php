<?php
$monthNames = Array("January", "February", "March", "April", "May", "June", "July", 
"August", "September", "October", "November", "December");

if (!isset($_REQUEST["month"])) $_REQUEST["month"] = date("n");
if (!isset($_REQUEST["year"])) $_REQUEST["year"] = date("Y");

$cMonth = $_REQUEST["month"];
$cYear = $_REQUEST["year"];
 
$prev_year = $cYear;
$next_year = $cYear;
$prev_month = $cMonth-1;
$next_month = $cMonth+1;
 
if ($prev_month == 0 ) {
    $prev_month = 12;
    $prev_year = $cYear - 1;
}
if ($next_month == 13 ) {
    $next_month = 1;
    $next_year = $cYear + 1;
}
?>

<link href="calendar.css" type="text/css" rel="stylesheet" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js" type="text/javascript"></script>
<script src="calendar.js" type="text/javascript"></script>

<div id="tables">
    <table width="100%">
        <tr>
            <td class="title">
                <table width="100%">
                    <tr>
                        <td width="50%" align="left"><a href="<?php echo $_SERVER["PHP_SELF"] . "?month=". $prev_month . "&year=" . $prev_year; ?>" class="title">Previous</a></td>
                        <td width="50%" align="right"><a href="<?php echo $_SERVER["PHP_SELF"] . "?month=". $next_month . "&year=" . $next_year; ?>" class="title">Next</a></td>
                   </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td>
                <table width="100%">
                    <tr>
                        <td colspan="7" class="title"><?php echo $monthNames[$cMonth-1].' '.$cYear; ?></td>
                    </tr>
                    <tr>
                        <td class="title">Sunday</td>
                        <td class="title">Monday</td>
                        <td class="title">Tuesday</td>
                        <td class="title">Wednesday</td>
                        <td class="title">Thursday</td>
                        <td class="title">Friday</td>
                        <td class="title">Saturday</td>
                    </tr>

<?php 
$timestamp = mktime(0,0,0,$cMonth,1,$cYear);
$maxday = date("t",$timestamp);
$thismonth = getdate ($timestamp);
$startday = $thismonth['wday'];
for ($i=0; $i<($maxday+$startday); $i++) {
    if(($i % 7) == 0 ) echo "<tr>";
    if($i < $startday) echo "<td></td>";
    else echo "<td align='center' valign='middle' height='20px'>". "<a href='date.php?day=$i&month=$cMonth&year=$cYear'>". ($i - $startday + 1)."</a>". "</td>";
    if(($i % 7) == 6 ) echo "</tr>";
}
?>

                </table>
            </td>
        </tr>
    </table>
</div>
