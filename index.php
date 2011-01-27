<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>

<head>
    <title>What's the Best Halloween Candy?</title>
    <style type="text/css">
        <!-- 

        .records TD { text-align: center; }
        INPUT { font-size: 18pt; }
        .candy_header { background: black; color: white; font-weight: bold; }
        .question { font-weight: bold; font-size: 16pt; }

        // -->
    </style>
</head>

<body>

<?php

// load db connection info
require 'db.php';
$dbh=mysql_connect ($userhost, $username, $userpass) or die ('Unable to connect to the database.');
@mysql_select_db($dbname) or die( "Unable to select database.");

// list of candy
$result = mysql_query("SELECT * FROM candy_records WHERE id=1 LIMIT 1");
$row = mysql_fetch_array($result);

// makes a $candies array out of the field names which contain all the candy names
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

$candies = mysql_field_array($result);

// begin class CandyRecord
class CandyRecord {
    var $candy_name;
    var $wins;
    var $losses;
    var $overall_wins;
    var $overall_losses;

    // explodes the first candy's record, e.g. a record like 5-3 into two variables, discarding hyphen
    function explode_record($record) {
        $records = explode("-",$record);
        if (($records[0] == '') || ($records[0] == '-')) { $records[0] = 0; }
        if (($records[1] == '') || ($records[1] == '-')) { $records[1] = 0; }
        $this->wins = $records[0];
        $this->losses = $records[1];
        
        return $records;
    }

    // swaps first candy's record (e.g. 5-3) to (3-5) for the second candy to be evaluated
    function swap_records($records) {
        $a = $records[0];
        $this->wins = $records[1];
        $this->losses = $a;

        return $records;
    }

    // fills overall win/loss records, sets to 0-0 if none existing found
    function overall_record($candy_name) {
        $result = mysql_query("SELECT Overall FROM candy_records WHERE Name='$candy_name' LIMIT 1");
        while ($row = mysql_fetch_assoc($result)) {
            $record_overall = $row['Overall'];
        }
        if ($record_overall == '') {
            $record_overall = '0-0';
        }
        $record_overall_exploded = explode("-",$record_overall);
        $this->overall_wins = $record_overall_exploded[0];
        $this->overall_losses = $record_overall_exploded[1];

        return $record_overall_exploded;
    }

}

// end class CandyRecord

// tests if URL has &match=true and that both candy names have been POSTed

if (($_POST['match'] == 'true') && (isset($_POST['candy1'])) && (isset($_POST['candy2']))) {

    $record1 = new CandyRecord;
    $record2 = new CandyRecord;

    // escape strings for security

    $record1->candy_name = mysql_real_escape_string($_POST['candy1']);
    $record2->candy_name = mysql_real_escape_string($_POST['candy2']);
    
    $record1->overall_record($record1->candy_name);
    $record2->overall_record($record2->candy_name);

    // pulls a record for a certain candy, e.g. pulling the "Twix" cell from the Skittles record
    $result = mysql_query("SELECT `$record2->candy_name` FROM candy_records WHERE Name='$record1->candy_name' LIMIT 1");

    while ($row = mysql_fetch_assoc($result)) {
        $vs_record = $row[$record2->candy_name];
    }
    
    if (empty($vs_record)) {
        $vs_record = '0-0';
    }

    $record1_exploded = $record1->explode_record($vs_record);
    $record2_exploded = $record2->swap_records($record1_exploded);

    // do the candy names exist in the array?

    if ((!in_array($record1->candy_name,$candies)) || (!in_array($record2->candy_name,$candies))) {
        echo "Candy not found...  Nice try.";
    }
    // if yes, then pick the winner and update all win-loss records
    else {
        if ($_POST['winner'] == 1) {
            $winner = $record1->candy_name;
            $loser = $record2->candy_name;
            $record1->wins++;
            $record2->losses++;
            $record1->overall_wins++;
            $record2->overall_losses++;
        }
        else if ($_POST['winner'] == 2) {
            $winner = $record2->candy_name;
            $loser = $record1->candy_name;
            $record2->wins++;
            $record1->losses++;
            $record2->overall_wins++;
            $record1->overall_losses++;
        }
        $candy1_record_final = $record1->wins . '-' . $record1->losses;
        $candy2_record_final = $record2->wins . '-' . $record2->losses;
        $candy1_overall_record_final = $record1->overall_wins . '-' . $record1->overall_losses;
        $candy2_overall_record_final = $record2->overall_wins . '-' . $record2->overall_losses;
        
        $query = "UPDATE candy_records SET `$record2->candy_name`='$candy1_record_final' WHERE Name='$record1->candy_name'";
        mysql_query($query);
        $query = "UPDATE candy_records SET `$record1->candy_name`='$candy2_record_final' WHERE Name='$record2->candy_name'";
        mysql_query($query);
        mysql_query("UPDATE Overall SET Overall=Overall+1 WHERE id=1");
        $query = "UPDATE candy_records SET Overall='$candy1_overall_record_final' WHERE Name='$record1->candy_name'";
        mysql_query($query);
        $query = "UPDATE candy_records SET Overall='$candy2_overall_record_final' WHERE Name='$record2->candy_name'";
        mysql_query($query);

        $vote_time = time();
        $ip_address = $_SERVER['REMOTE_ADDR'];
        mysql_query("INSERT INTO Results (VoteTime, IPAddress, Winner, Loser) VALUES ('$vote_time', '$ip_address', '$winner', '$loser')");
    }

}

// randomly pick two candies that are different

$candy_random1 = rand(0,8);

$found = false;
while ($found == false) {
    $candy_random2 = rand(0,8);
    if ($candy_random2 != $candy_random1) {
        $found = true;
    }
}

?>

<!-- Yeah I know, I'm using tables... -->

<BR><BR><BR><BR><BR>
<CENTER>

<!-- FACEOFF BEGIN -->
<SPAN CLASS=question>What's your favorite Halloween candy?</SPAN><BR>
<table border=0><tr><td valign=middle align=center class=faceoff>

<form name=candy_form1 id=candy_form1 method=post action="./">
<input type=hidden name=candy1 value="<?php echo $candies[$candy_random1]; ?>">
<input type=hidden name=candy2 value="<?php echo $candies[$candy_random2]; ?>">
<input type=hidden name=winner value="1">
<input type=hidden name=match value="true">
<input type=submit name=button_candy1 id=button_candy1 value="<?php echo $candies[$candy_random1]; ?>"></form>

</td>
<td valign=top align=center>

vs.

</td>
<td valign=middle align=center>

<form name=candy_form2 id=candy_form2 method=post action="./">
<input type=hidden name=candy1 value="<?php echo $candies[$candy_random1]; ?>">
<input type=hidden name=candy2 value="<?php echo $candies[$candy_random2]; ?>">
<input type=hidden name=winner value="2">
<input type=hidden name=match value="true">
<input type=submit name=button_candy2 id=button_candy2 value="<?php echo $candies[$candy_random2]; ?>"></form>

</td>
</tr></table>
<!-- FACEOFF END -->

<?php
if (($_POST['match'] == 'true') && (isset($_POST['candy1'])) && (isset($_POST['candy2']))) {
?>

<BR>
<b>results from the last vote</b><br>
<B>you voted for:  <?php echo $winner; ?></B><BR>
<?php echo '# of votes:  ' . $record1->candy_name . ' (' . $record1->wins . ') vs. ' . $record2->candy_name . ' (' . $record2->wins . ")\n"; ?>

<BR><BR><BR><BR>

<TABLE BORDER=0 CELLSPACING=10><TR>
<TD VALIGN=top>

<B>heads-up records</B><BR>

<!-- begin vs record table -->
<table class="records" border=1 cellspacing=0 cellpadding=7>
<tr>
<TH></TH>
<?php

echo "<TH>" . $record1->candy_name . "</TH><TH>" . $record2->candy_name . "</TH></TR>\n";

$result_headsup1 = mysql_query("SELECT * FROM candy_records WHERE Name='$record1->candy_name' LIMIT 1");
$result_headsup2 = mysql_query("SELECT * FROM candy_records WHERE Name='$record2->candy_name' LIMIT 1");
/* $count - mysql_num_rows($result_headsup1);
for ($i=0;$i<$count;$i++) {
    echo mysql_result($result_headsup1,$i,"
} */
while (($row_headsup1 = mysql_fetch_array($result_headsup1, MYSQL_ASSOC)) && ($row_headsup2 = mysql_fetch_array($result_headsup2, MYSQL_ASSOC))) {
    foreach ($candies as $value) {
        echo "<TR><TH>" . $value . "</TH><TD>" . $row_headsup1[$value] . "</TD><TD>" . $row_headsup2[$value] . "</TD></TR>";
    } 
}

?>

</table>
<!-- end vs record table -->

<?php
}
?>

</TD><TD VALIGN=top>

<B>overall records</B><BR>

<table class="records" border=1 cellspacing=0 cellpadding=7> <!-- begin record table -->
<tr><TH>Overall</TH><TH>Record</TH></TR>

<?php

$result = mysql_query("SELECT Name, Overall FROM candy_records ORDER BY Name ASC");

while ($row3 = mysql_fetch_array($result, MYSQL_ASSOC)) {
    echo "<TR><TD>" . $row3['Name'] . "</TD><TD>" . $row3['Overall'] . "</TD></TR>\n";
/*    $candies_count = count($candies);
    for ($i=0;$i<$candies_count;$i++) {
        echo "<TD>" . $row3[$candies[$i]] . "</TD>\n";
    } */
}

mysql_close();

?>

</tr>
</table> <!-- end record table -->

</TD>
</TR></TABLE>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // ...
    });
</script>

</body>

</html>