<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<!--

NOTES:

To add new comparison objects, you'll have to add a new row to the candy_records table, as well as a new column within that table.

comp = comparison/compare

-->

<HTML>

<HEAD>
    <TITLE>What's the Best Halloween Candy?</TITLE>
    <STYLE type="text/css">
        <!-- 

        BODY { background-color: black; background-image: url('img/darkpumpkin.jpg'); color: #ffa500; background-repeat: repeat-x; font-family: Helvetica, Georgia; }
        INPUT { font-size: 18pt; }
        A { color: #ffefc9;}
        TABLE { border-color: #ffa500; }
        
        .records TH, .records TD { text-align: center; font-size: 8pt; }
        .records TH { background: #333333;}
        .compNames { background: #222222;}

        .heading { font-weight: bold; font-size: 16pt; color: #9acd32; }
        .heading2 { font-weight: bold; font-size: 22pt; color: #9acd32; }
        .buttonComp { background-color: #ffcc00; color: black; }
        .datatables { padding: 15px;}

        // -->
    </STYLE>
</HEAD>

<BODY>

<?php

// load db connection info
require 'db.php';
$dbh=mysql_connect ($userhost, $username, $userpass) or die ('Unable to connect to the database.');
@mysql_select_db($dbname) or die( "Unable to select database.");

// list of things to compare
$result = mysql_query("SELECT * FROM candy_records WHERE id=1 LIMIT 1");
$row = mysql_fetch_array($result);

// makes a $compares array out of the field names which contain all the compared item names
function mysql_field_array($query) {
    $field = mysql_num_fields($query);
    for ($i = 0; $i < $field; $i++) {
        $temp = mysql_field_name($query, $i);
        if (($temp != 'id') && ($temp != 'Overall') && ($temp != 'Name')) {
            $names[] = $temp;
        }
    }
    return $names;
}

$compares = mysql_field_array($result);

// begin class CompareRecord
class CompareRecord {
    var $compName;
    var $wins;
    var $losses;
    var $overallWins;
    var $overallLosses;

    // explodes the first compared item's record, e.g. a record like 5-3 into two variables, discarding hyphen
    function explode_record($record) {
        $records = explode("-",$record);
        if (($records[0] == '') || ($records[0] == '-')) { $records[0] = 0; }
        if (($records[1] == '') || ($records[1] == '-')) { $records[1] = 0; }
        $this->wins = $records[0];
        $this->losses = $records[1];
        
        return $records;
    }

    // swaps first item's record (e.g. 5-3) to (3-5) for the second item to be evaluated
    function swap_records($records) {
        $a = $records[0];
        $this->wins = $records[1];
        $this->losses = $a;

        return $records;
    }

    // fills overall win/loss records, sets to 0-0 if none existing found
    function overall_record($compName) {
        $result = mysql_query("SELECT Overall FROM candy_records WHERE Name='$compName' LIMIT 1");
        while ($row = mysql_fetch_assoc($result)) {
            $record_overall = $row['Overall'];
        }
        if ($record_overall == '') {
            $record_overall = '0-0';
        }
        $record_overall_exploded = explode("-",$record_overall);
        $this->overallWins = $record_overall_exploded[0];
        $this->overallLosses = $record_overall_exploded[1];

        return $record_overall_exploded;
    }

}
// end class CompareRecord

// tests if URL has &match=true and that both items' names have been POSTed
if (($_POST['match'] == 'true') && (isset($_POST['comp1'])) && (isset($_POST['comp2']))) {

    $record1 = new CompareRecord;
    $record2 = new CompareRecord;

    // escape strings for security
    $record1->compName = mysql_real_escape_string($_POST['comp1']);
    $record2->compName = mysql_real_escape_string($_POST['comp2']);
    
    $record1->overall_record($record1->compName);
    $record2->overall_record($record2->compName);

    // pulls a record for a certain item, e.g. pulling the "Twix" cell from the Skittles record
    $result = mysql_query("SELECT `$record2->compName` FROM candy_records WHERE Name='$record1->compName' LIMIT 1");

    while ($row = mysql_fetch_assoc($result)) {
        $vs_record = $row[$record2->compName];
    }
    
    if (empty($vs_record)) {
        $vs_record = '0-0';
    }

    $record1_exploded = $record1->explode_record($vs_record);
    $record2_exploded = $record2->swap_records($record1_exploded);

    // do the item names exist in the array?
    if ((!in_array($record1->compName,$compares)) || (!in_array($record2->compName,$compares))) {
        echo "<B>Item not found...  Nice try.</B>";
    }
    // if yes, then pick the winner and update all win-loss records
    else {
        if ($_POST['winner'] == 1) {
            $winner = $record1->compName;
            $loser = $record2->compName;
            $record1->wins++;
            $record2->losses++;
            $record1->overallWins++;
            $record2->overallLosses++;
            updateRecords();
        }
        else if ($_POST['winner'] == 2) {
            $winner = $record2->compName;
            $loser = $record1->compName;
            $record2->wins++;
            $record1->losses++;
            $record2->overallWins++;
            $record1->overallLosses++;
            updateRecords();
        }
        else { // if someone tries to insert a value other than 1 or 2
            echo "<B>Whether 1 or 2 won, you still lose, loser.  OUT OF BOUNDS.</B>";
        }
        // removed func
    }

}

// randomly pick two compared items that are different
// in the db, first id is 1, in rand() below, start w/ array[0]
$compRandom1 = rand(0,27);

$found = false;
while ($found == false) {
    $compRandom2 = rand(0,27);
    if ($compRandom2 != $compRandom1) {
        $found = true;
    }
}

// called in if..then statement where winners/losers assigned
function updateRecords() {
    $comp1RecordFinal = $record1->wins . '-' . $record1->losses;
    $comp2RecordFinal = $record2->wins . '-' . $record2->losses;
    $comp1OverallRecordFinal = $record1->overallWins . '-' . $record1->overallLosses;
    $comp2OverallRecordFinal = $record2->overallWins . '-' . $record2->overallLosses;
        
    $query = "UPDATE candy_records SET `$record2->compName`='$comp1RecordFinal' WHERE Name='$record1->compName'";
    mysql_query($query);
    $query = "UPDATE candy_records SET `$record1->compName`='$comp2RecordFinal' WHERE Name='$record2->compName'";
    mysql_query($query);
    mysql_query("UPDATE Overall SET Overall=Overall+1 WHERE id=1");
    $query = "UPDATE candy_records SET Overall='$comp1OverallRecordFinal' WHERE Name='$record1->compName'";
    mysql_query($query);
    $query = "UPDATE candy_records SET Overall='$comp2OverallRecordFinal' WHERE Name='$record2->compName'";
    mysql_query($query);

    $vote_time = time();
    $ip_address = $_SERVER['REMOTE_ADDR'];
    mysql_query("INSERT INTO Results (VoteTime, IPAddress, Winner, Loser) VALUES ('$vote_time', '$ip_address', '$winner', '$loser')");
}

?>

<!-- Yeah I know, I'm using tables... -->

<BR><BR><BR>
<CENTER>

<!-- FACEOFF BEGIN -->
<SPAN CLASS=heading2>Which Halloween candy do you prefer?</SPAN><BR>
<TABLE border=0><TR><TD valign=middle align=center class=faceOff>

<FORM name=compForm1 id=compForm1 method=post action="./">
<INPUT type=hidden name=comp1 value="<?php echo $compares[$compRandom1]; ?>">
<INPUT type=hidden name=comp2 value="<?php echo $compares[$compRandom2]; ?>">
<INPUT type=hidden name=winner value="1">
<INPUT type=hidden name=match value="true">
<INPUT type=submit name=buttonComp1 class=buttonComp id=buttonComp1 value="<?php echo $compares[$compRandom1]; ?>"></form>

</TD>
<TD valign=middle align=center>

vs.

</TD>
<TD valign=middle align=center>

<form name=compForm2 id=compForm2 method=post action="./">
<INPUT type=hidden name=comp1 value="<?php echo $compares[$compRandom1]; ?>">
<INPUT type=hidden name=comp2 value="<?php echo $compares[$compRandom2]; ?>">
<INPUT type=hidden name=winner value="2">
<INPUT type=hidden name=match value="true">
<INPUT type=submit name=buttonComp2 class=buttonComp id=buttonComp2 value="<?php echo $compares[$compRandom2]; ?>"></form>

</TD>
</TR></TABLE>
<!-- FACEOFF END -->

<BR><BR>

<?php
if (($_POST['match'] == 'true') && (isset($_POST['comp1'])) && (isset($_POST['comp2']))) {
?>

<SPAN CLASS="heading"><B>results from the last vote</B></SPAN><br>
<B>you voted for:  <?php echo $winner; ?></B><BR>
<?php echo '# of votes:  ' . $record1->compName . ' (' . $record1->wins . ') vs. ' . $record2->compName . ' (' . $record2->wins . ")\n"; ?>

<BR><BR>

<TABLE BORDER=0 CELLSPACING=10><TR>
<TD VALIGN=top CLASS=datatables>

<!-- shows last two compared items' heads-up records -->

<SPAN CLASS="heading"><B>last pair's heads-up records</B></SPAN><BR>

<!-- begin vs record table -->
<table class="records" border=1 cellspacing=0 cellpadding=7 TITLE="view this as the last two paired competitors' columns with their heads-up records against all the other compared items on the left">
<?php

echo "<TR><TH STYLE=\"background-color: '';\"></TH><TH>" . $record1->compName . "</TH><TH>" . $record2->compName . "</TH></TR>\n";

$result_headsup1 = mysql_query("SELECT * FROM candy_records WHERE Name='$record1->compName' LIMIT 1");
$result_headsup2 = mysql_query("SELECT * FROM candy_records WHERE Name='$record2->compName' LIMIT 1");
while (($row_headsup1 = mysql_fetch_array($result_headsup1, MYSQL_ASSOC)) && ($row_headsup2 = mysql_fetch_array($result_headsup2, MYSQL_ASSOC))) {
    foreach ($compares as $value) {
        echo "<TR><TH class=compNames>" . $value . "</TH><TD>" . $row_headsup1[$value] . "</TD><TD>" . $row_headsup2[$value] . "</TD></TR>";
    } 
}

?>

</table>
<!-- end vs record table -->

<?php
}
else {
    echo "<TABLE BORDER=0 CELLSPACING=10><TR><TD>";
}

?>

</TD><TD VALIGN=top CLASS=datatables>

<!-- prints out overall record chart, alphabetically -->

<SPAN CLASS="heading"><B>overall records</B></SPAN><BR>

<table class="records" border=1 cellspacing=0 cellpadding=7> <!-- begin record table -->
<TR><TH>Overall</TH><TH>Record</TH></TR>

<?php

$result = mysql_query("SELECT Name, Overall FROM candy_records ORDER BY Name ASC");

while ($row3 = mysql_fetch_array($result, MYSQL_ASSOC)) {
    echo "<TR><TD class=compNames>" . $row3['Name'] . "</TD><TD>" . $row3['Overall'] . "</TD></TR>\n";
}

?>

</table>
<!-- end record table -->

</TD><TD VALIGN=top CLASS=datatables>

<!-- check win/loss percentages, sort, display -->

<SPAN CLASS="heading"><B>top candies</B></SPAN><BR>

<table class="records" border=1 cellspacing=0 cellpadding=7> <!-- begin record table -->
<TR><TH>Best</TH><TH>Record</TH><TH>Win %</TH><TH TITLE="wins divided by losses">Win Ratio</TH></TR>

<?php

$result = mysql_query("SELECT Name, Overall FROM candy_records ORDER BY Name ASC");

$i = 0;

while ($i < mysql_num_rows($result)) {
    $bestRecords[$i] = explode("-", mysql_result($result,$i,"Overall"));
    $bestRecords[$i][2] = mysql_result($result,$i,"Name");
    $bestRecords[$i][3] = mysql_result($result,$i,"Overall");
    $i++;
}

$i = 0;
$j = 0;

while ($i < count($bestRecords)) {
    // wins / losses
    $bestRecords[$i][4] = $bestRecords[$i][0] / $bestRecords[$i][1];
    // wins / (wins + losses)
    $bestRecords[$i][5] = $bestRecords[$i][0] / ($bestRecords[$i][0] + $bestRecords[$i][1]);
    $i++;
}

usort($bestRecords, "custom_sort");
function custom_sort($a, $b) {
    return $a['4']<$b['4'];
}

$i = 0;
while ($i < count($bestRecords)) {
    echo "<TR><TD class=compNames>" . $bestRecords[$i][2] . "</TD><TD>" . $bestRecords[$i][3] . "</TD><TD>" . round($bestRecords[$i][5],2) . "</TD><TD>" . round($bestRecords[$i][4],2) . "</TD></TR>\n";
    $i++;
}

mysql_close();

?>

</table>
<!-- end record table -->

</TD>
</TR></TABLE>

<BR><BR>

<center><font size=1>
created by <a href="http://benturner.com/">ben turner</A> for fun. <a href="http://benturner.com/contact_me.php">contact me</a> with feedback.<br>
<a href="https://github.com/Xeus/compare">check out the code</a> on github.
</font></center>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // ...
    });
</script>

</BODY>

</HTML>