<?php
  header('Content-type: text/html; charset=iso8859-7'); 
  require_once"config.php";
  require_once"functions.php";
  //define("L_LANG", "el_GR"); Needs fixing
  require('calendar/tc_calendar.php');
  
  $mysqlconnection = mysql_connect($db_host, $db_user, $db_password);
  mysql_select_db($db_name, $mysqlconnection);
  mysql_query("SET NAMES 'greek'", $mysqlconnection);
  mysql_query("SET CHARACTER SET 'greek'", $mysqlconnection);
  
  session_start();
  $usrlvl = $_SESSION['userlevel'];
	
?>
<html>
  <head>
	<LINK href="style.css" rel="stylesheet" type="text/css">
    <meta http-equiv="content-type" content="text/html; charset=iso8859-7">
    <title>Employee</title>
	<script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/jquery.tablesorter.js"></script> 
	<script type="text/javascript">
        $(document).ready(function() { 
			$("#mytbl").tablesorter({widgets: ['zebra']}); 
		});     
        
	</script>
  </head>
  <body> 
    <center>
      <?php
		$i = 0;
                $query = "SELECT * from adeia_ekt where emp_id=".$_GET['id'];
		$result = mysql_query($query, $mysqlconnection);
		$num=mysql_numrows($result);
		if (!$num)
                {
                    echo "<br><br><big>�� �������� ������</big>";
                    $emp_id = $_GET['id'];
                    if ($usrlvl < 2)
                        echo "<br><span title=\"�������� ������\"><a href=\"ekt_adeia.php?emp=$emp_id&op=add\"><big>�������� ������</big><img style=\"border: 0pt none;\" src=\"images/user_add.png\"/></a></span>";
                    exit;
                }
                
		
                echo "<br>";
		echo "<table id=\"mytbl\" class=\"imagetable tablesorter\" border='1'>";	
		echo "<thead><tr>";
		echo "<th>ID</th><th>�����</th><th>��.����.</th><th>��.�������</th><th>������</th><th>��.�������</th><th>��.�����</th>";
		echo "</tr></thead>";
                echo "<tbody>";
                
                while ($i<$num)
                {
                    $id = mysql_result($result, $i, "id");
                    $emp_id = mysql_result($result, $i, "emp_id");
                    $type = mysql_result($result, $i, "type");
                    $prot = mysql_result($result, $i, "prot");
                    $date = mysql_result($result, $i, "date");
                    $days = mysql_result($result, $i, "days");
                    $start = mysql_result($result, $i, "start");
                    $finish = mysql_result($result, $i, "finish");
                    $comments = mysql_result($result, $i, "comments");
                                        
                    $query1 = "select type from adeia_type where id=$type";
                    $result1 = mysql_query($query1, $mysqlconnection);
                    $typewrd = mysql_result($result1, 0, "type");
                    if ($usrlvl < 2)
                        echo "<tr><td>$id<span title=\"��������\"><a href=\"javascript:confirmDelete('ekt_adeia.php?adeia=$id&op=delete')\"><img style=\"border: 0pt none;\" src=\"images/delete_action.png\"/></a></span></td><td><a href='ekt_adeia.php?adeia=$id&op=view'>$typewrd</a></td><td>$prot</td><td>".date('d-m-Y',strtotime($date))."</td><td>$days</td><td>".date('d-m-Y',strtotime($start))."</td><td>".date('d-m-Y',strtotime($finish))."</td></tr>";
                    else
                        echo "<tr><td>$id</td><td><a href='ekt_adeia.php?adeia=$id&op=view'>$typewrd</a></td><td>$prot</td><td>".date('d-m-Y',strtotime($date))."</td><td>$days</td><td>".date('d-m-Y',strtotime($start))."</td><td>".date('d-m-Y',strtotime($finish))."</td></tr>";
                    $i++;
                }

		echo "</tbody>";
                if ($usrlvl < 2)
                    echo "<tr><td colspan=8><span title=\"�������� ������\"><a href=\"ekt_adeia.php?emp=$emp_id&op=add\">�������� ������<img style=\"border: 0pt none;\" src=\"images/user_add.png\"/></a></span>";		
                echo "</table>";
		
		echo "</body>";
		echo "</html>";	


	mysql_close();
?>

<style type="text/css">
	body {
		font-family: Arial;
		font-size: 11px;
		color: #000;
	}
	
	h3 {
		margin: 0px;
		padding: 0px;	
	}

	.suggestionsBox {
		font-family: sans-serif;
		position: relative;
		left: 30px;
		margin: 10px 0px 0px 0px;
		width: 200px;
		background-color: #212427;
		-moz-border-radius: 7px;
		-webkit-border-radius: 7px;
		border: 2px solid #000;	
		color: #fff;
	}
	
	.suggestionList {
		margin: 0px;
		padding: 0px;
	}
	
	.suggestionList li {
		
		margin: 0px 0px 3px 0px;
		padding: 3px;
		cursor: pointer;
	}
	
	.suggestionList li:hover {
		background-color: #659CD8;
	}
</style>