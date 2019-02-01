<?php
  header('Content-type: text/html; charset=iso8859-7'); 
  require_once"../config.php";
  require_once"../tools/functions.php";
  //define("L_LANG", "el_GR"); Needs fixing
  require('../tools/calendar/tc_calendar.php');
  
  $mysqlconnection = mysqli_connect($db_host, $db_user, $db_password, $db_name);  
  mysqli_query($mysqlconnection, "SET NAMES 'greek'");
  mysqli_query($mysqlconnection, "SET CHARACTER SET 'greek'");
  
  // Demand authorization                
  include("../tools/class.login.php");
  $log = new logmein();
  if($log->logincheck($_SESSION['loggedin']) == false){
    header("Location: ../tools/login.php");
  }
  $klados_type = 0;
	
?>
<html>
  <head>
	<LINK href="../css/style.css" rel="stylesheet" type="text/css">
    <meta http-equiv="content-type" content="text/html; charset=iso8859-7">
    <title>�����������</title>
	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../js/jquery.validate.js"></script>
	<script type='text/javascript' src='../js/jquery.autocomplete.js'></script>
        <script type="text/javascript" src="../js/jquery.table.addrow.js"></script>
	<script type="text/javascript" src='../tools/calendar/calendar.js'></script>
        <script type="text/javascript" src="../js/jquery_notification_v.1.js"></script>
        <link href="../css/jquery_notification.css" type="text/css" rel="stylesheet"/> 
	<link rel="stylesheet" type="text/css" href="../js/jquery.autocomplete.css" />
	<script type="text/javascript">
           
    $(document).ready(function(){
      $("#wordfrm").validate({
        debug: false,
        submitHandler: function(form) {
          // do other stuff for a valid form
          $.post('vev_yphr_anapl_pw.php', $("#wordfrm").serialize(), function(data) {
            $('#word').html(data);
          });
        }
      });
    });
    var mylink = "<small>�������� ����� ������ ����� � ������������ ���: </small><a target=\"_blank\" href=\"praxi.php\">�������</a>";
        
    $(document).ready(function(){
      $("#updatefrm").validate({
        debug: false,
                          rules: {
          name: "required", surname: "required", afm: "required", klados: "required", praxi: {"required": true, min:2 }, type: "required"
        },
        messages: {
          name: "�������� ����� �����", surname: "�������� ����� �������", afm: "�������� ����� �����o ���",
                                  klados: "�������� ����� ������ ����", praxi: mylink, type: "�������� ����� ������ ����"
        },
        submitHandler: function(form) {
          // do other stuff for a valid form
          $.post('update_ekt.php', $("#updatefrm").serialize(), function(data) {
            $('#results').html(data);
          });
        }
      });
    });
    $().ready(function(){
      $(".slidingDiv").hide();
      $(".show_hide").show();

      $('.show_hide').click(function(){
          $(".slidingDiv").slideToggle();
      });
    });
  </script>

  </head>
  <body> 
  <?php include('../etc/menu.php'); ?>
    <center>
      <?php
        $usrlvl = $_SESSION['userlevel'];
       if ($_GET['op']!="add")
       {
           if ($_GET['sxoletos']) {
               $sxol_etos = $_GET['sxoletos'];
               $sxoletos = $_GET['sxoletos'];
               $query = "SELECT * FROM ektaktoi_old e join yphrethsh_ekt y on e.id = y.emp_id where e.id = ".$_GET['id']." AND y.sxol_etos = $sxol_etos AND e.sxoletos=$sxoletos";
           }
           else {
                $query = "SELECT * FROM ektaktoi e join yphrethsh_ekt y on e.id = y.emp_id where e.id = ".$_GET['id']." AND y.sxol_etos = $sxol_etos";
           }

          $result = mysqli_query($mysqlconnection, $query);
          $num=mysqli_num_rows($result);
          
          if ($num > 0)
          {
              $multi = 1;
              for ($i=0; $i<$num; $i++)
              {
                  $yphr_id_arr[$i] = mysqli_result($result, $i, "yphrethsh");
                  $yphr_arr[$i] = getSchool (mysqli_result($result, $i, "yphrethsh"), $mysqlconnection);
                  $hours_arr[$i] = mysqli_result($result, $i, "hours");
              }            
          }
          else
          {
              $query = "SELECT * from ektaktoi where id=".$_GET['id'];
              $result = mysqli_query($mysqlconnection, $query);
              $num=mysqli_num_rows($result);
              $sx_yphrethshs_id = mysqli_result($result, 0, "sx_yphrethshs");
              $sx_yphrethshs = getSchool ($sx_yphrethshs_id, $mysqlconnection);
          }
            //}
          $id = mysqli_result($result, 0, 0);
          $name = mysqli_result($result, 0, "name");
          $type = mysqli_result($result, 0, "type");
          $surname = mysqli_result($result, 0, "surname");
          $klados_id = mysqli_result($result, 0, "klados");
          $klados = getKlados($klados_id,$mysqlconnection);
          // if nip or nip eidikhs
          if ($klados_id == 1 || $klados_id == 16 || $klados_id == 17)
              $klados_type = 2;
          // if ebp or sx.nosileytes
          elseif (in_array($klados_id, [12, 25, 26, 8, 21, 9, 10, 27, 11])) {
              $klados_type = 0;
          }
          else
              $klados_type = 1;
          $metakinhsh = stripslashes(mysqli_result($result, 0, "metakinhsh"));
          $patrwnymo = mysqli_result($result, 0, "patrwnymo");
          $mhtrwnymo = mysqli_result($result, 0, "mhtrwnymo");
          $afm = mysqli_result($result, 0, "afm");
          $vathm = mysqli_result($result, 0, "vathm");
          $mk = mysqli_result($result, 0, "mk");
          $hm_mk = mysqli_result($result, 0, "hm_mk");
		      $analipsi = mysqli_result($result, 0, "analipsi");
          $hm_anal = mysqli_result($result, 0, "hm_anal");
          $hm_apox = mysqli_result($result, 0, "hm_apox");
          $met_did = mysqli_result($result, 0, "met_did");
          //$ya = mysqli_result($result, 0, "ya");
          //$apofasi = mysqli_result($result, 0, "apofasi");
          $comments = mysqli_result($result, 0, "comments");
          $comments = str_replace(" ", "&nbsp;", $comments);
          $stathero = mysqli_result($result, 0, "stathero");
          $kinhto = mysqli_result($result, 0, "kinhto");
          $praxi = mysqli_result($result, 0, "praxi");
          $updated= mysqli_result($result, 0, "updated");
          $thesi = mysqli_result($result, 0, "thesi");
          $wres = mysqli_result($result, 0, "wres");
          
          $kat = mysqli_result($result, 0, "status");
          switch ($kat)
          {   
              case 1:
                  $katast = "���������";
                  break;
              case 2:
                  $katast = "���� ������ - ���������";
                  break;
              case 3:
                  $katast = "�����";
                  break;
              case 4:
                  $katast = "�������������";
                  break;
          }
       }
                ?>
        <script type="text/javascript">
        $().ready(function() {
		$("#yphr").autocomplete("get_school.php", {
                               extraParams: {type: <?php echo $klados_type; ?>},
				width: 260,
				matchContains: true,
				selectFirst: false
			});
	});
                
        $().ready(function() {
		$(".addRow").btnAddRow(function(row){
                        row.find(".yphrow").autocomplete("get_school.php", {
                                extraParams: {type: <?php echo $klados_type; ?>},
				width: 260,
				matchContains: true,
				selectFirst: false
                        })
                });
                $(".delRow").btnDelRow();
                        $(".yphrow").autocomplete("get_school.php", {
                                extraParams: {type: <?php echo $klados_type; ?>},
				width: 260,
				matchContains: true,
				selectFirst: false
                        });
                });
        </script>
        <?php
if ($_GET['op']=="add")
{
        echo "<h3>�������� ���������� �������������</h3>";
        echo "<form id='updatefrm' action='update_ekt.php' method='POST'>";
        echo "<table class=\"imagetable\" border='1'>";
        
        echo "<tr><td>�������</td><td><input type='text' name='surname' /></td></tr>";
        echo "<tr><td>�����</td><td><input type='text' name='name' /></td></tr>";
        echo "<tr><td>���������</td><td><input type='text' name='patrwnymo' /></td></tr>";
        echo "<tr><td>���������</td><td><input type='text' name='mhtrwnymo' /></td></tr>";
        echo "<tr><td>�.�.�.</td><td><input type='text' name='afm' /></td></tr>";
        echo "<tr><td>�������</td><td><input type='text' name='stathero' /></td></tr>";
        echo "<tr><td>������</td><td><input type='text' name='kinhto' /></td></tr>";
        echo "<tr><td>������</td><td>";
        kladosCmb($mysqlconnection);
        echo "</td></tr>";
        //echo "<tr><td>������</td><td><input type='text' name='vathm' /></td></tr>";
        //echo "<tr><td>�.�.</td><td><input type='text' name='mk' /></td></tr>";
        
        //echo "<tr><td>������� ���������</td><td><input type='text' name='analipsi' /></td></tr>";
        echo "<tr><td>��/��� ��������</td><td>";
        $myCalendar = new tc_calendar("hm_anal", true);
        $myCalendar->setIcon("../tools/calendar/images/iconCalendar.gif");
        $myCalendar->setDate(date("d"), date("m"), date("Y"));
        $myCalendar->setPath("../tools/calendar/");
        $myCalendar->setYearInterval(1970, date("Y"));
        $myCalendar->dateAllow("1970-01-01", date("Y-m-d"));
        $myCalendar->setAlignment("left", "bottom");
        $myCalendar->disabledDay("sun,sat");
        $myCalendar->writeScript();
        echo "</td></tr>";		
                        
        echo "<tr><td>������������/�����������</td><td>";
        metdidCombo(0);		
        
        echo "<tr><td>����� �����������</td><td>";
        typeCmb($mysqlconnection);
        echo "</td></tr>";
        echo "<tr><td>������</td><td><textarea rows=4 cols=80 name='comments' ></textarea></td></tr>";
        //echo "<tr><td>��������� �������</td><td><input type='text' name='ya' /></td></tr>";
        //echo "<tr><td>������� �/���</td><td><input type='text' name='apofasi' /></td></tr>";
        echo "<tr><td>�����:</td><td>";
        tblCmb($mysqlconnection, "praxi",$praxi);
        echo "</td></tr>"; 
        echo "<div id=\"content\">";
        echo "<form autocomplete=\"off\">";
        echo "<tr><td>�������(-�) ����������";
        echo "<a href=\"\" onclick=\"window.open('../help/help.html#school_ekt','', 'width=400, height=250, location=no, menubar=no, status=no,toolbar=no, scrollbars=no, resizable=no'); return false\"><img style=\"border: 0pt none;\" src=\"../images/help.gif\"/></a>";
        //echo "</td><td><input type=\"text\" name=\"yphr\" id=\"yphr\" size=50/>";
        echo "</td><td><input type=\"text\" name=\"yphr[]\" class=\"yphrow\" id=\"yphrow\" />";
        echo "&nbsp;&nbsp;<input type=\"text\" name=\"hours[]\" size=1 />";
        echo "&nbsp;<input class=\"addRow\" type=\"button\" value=\"��������\" />";
        echo "<input class=\"delRow\" type=\"button\" value=\"��������\" />";
        //echo "</form>";
        echo "</div>";
        thesianaplselectcmb(0);
        echo "	</table>";
        echo "	<input type='hidden' name = 'id' value='$id'>";
        // action = 1 gia prosthiki
        echo "  <input type='hidden' name = 'status' value='1'>";
        echo "  <input type='hidden' name = 'action' value='1'>";
        echo "<br>";
        echo "	<input type='submit' value='����������'>";
        echo "	<INPUT TYPE='button' VALUE='��������� ��� ����� �����������' onClick=\"parent.location='ektaktoi_list.php'\">";
        echo "<br>";
        echo "	<br><INPUT TYPE='button' class='btn-red' VALUE='������ ������' onClick=\"parent.location='../index.php'\">";
        echo "	</form>";
?>
<div id='results'></div>
<?php
        echo "    </center>";
        echo "</body>";
        echo "</html>";
}

if ($_GET['op']=="edit")
{
        echo "<h3>����������� ���������� �������������</h3>";
        echo "<form id='updatefrm' name='update' action='update_ekt.php' method='POST'>";
        echo "<table class=\"imagetable\" border='1'>";
        echo "<tr><td>�������</td><td><input type='text' name='surname' value=$surname /></td></tr>";
        echo "<tr><td>�����</td><td><input type='text' name='name' value=$name /></td></tr>";
        echo "<tr><td>���������</td><td><input type='text' name='patrwnymo' value=$patrwnymo /></td></tr>";
        echo "<tr><td>���������</td><td><input type='text' name='mhtrwnymo' value=$mhtrwnymo /></td></tr>";
        echo "<tr><td>�.�.�.</td><td><input type='text' name='afm' value=$afm /></td></tr>";
        echo "<tr><td>�������</td><td><input type='text' name='stathero' value=$stathero /></td></tr>";
        echo "<tr><td>������</td><td><input type='text' name='kinhto' value=$kinhto /></td></tr>";
        echo "<tr><td>������</td><td>";
        kladosCombo($klados_id,$mysqlconnection);
        echo "</td></tr>";
        echo "<tr><td>���������</td><td>";
        katastCmb($kat);
        echo "</td></tr>";
        //echo "<tr><td>������</td><td>";
        //vathmosCmb1($vathm, $mysqlconnection);
        //echo "</td><tr>";
        //<input type='text' name='vathm' value=$vathm /></td></tr>";
        //echo "<tr><td>�.�.</td><td><input type='text' name='mk' value=$mk /></td></tr>";
        echo "<tr><td>����� �����������</td><td>";
        typeCmb1($type, $mysqlconnection);
        echo "</td></tr>";
        echo "<tr><td>�������</td><td><input type='text' name='analipsi' value=$analipsi /></td></tr>";
        
        echo "<tr><td>��/��� ��������</td><td>";
        $myCalendar = new tc_calendar("hm_anal", true);
        $myCalendar->setIcon("../tools/calendar/images/iconCalendar.gif");
        $myCalendar->setDate(date('d',strtotime($hm_anal)),date('m',strtotime($hm_anal)),date('Y',strtotime($hm_anal)));
        $myCalendar->setPath("../tools/calendar/");
        // $myCalendar->setYearInterval(1970, date("Y"));
        // $myCalendar->dateAllow("1970-01-01", date("Y-m-d"));
        $myCalendar->setAlignment("left", "bottom");
        $myCalendar->disabledDay("sun,sat");
        $myCalendar->writeScript();
        echo "</td></tr>";
        echo "<tr><td>��/��� ����������</td><td>";
        $myCalendar = new tc_calendar("hm_apox", true);
        $myCalendar->setIcon("../tools/calendar/images/iconCalendar.gif");
        $myCalendar->setDate(date('d',strtotime($hm_apox)),date('m',strtotime($hm_apox)),date('Y',strtotime($hm_apox)));
        $myCalendar->setPath("../tools/calendar/");
        // $myCalendar->setYearInterval(1970, date("Y"));
        // $myCalendar->dateAllow("1970-01-01", date("Y-m-d"));
        $myCalendar->setAlignment("left", "bottom");
        //$myCalendar->disabledDay("sun,sat");
        $myCalendar->writeScript();
        echo "</td></tr>";		
                        
        echo "<tr><td>������������/�����������</td><td>";
        metdidCombo($met_did);
        //echo "<tr><td>��������� �������</td><td><input size=50 type='text' name='ya' value=$ya /></td></tr>";
        //echo "<tr><td>������� �/���</td><td><input size=50 type='text' name='apofasi' value=$apofasi /></td></tr>";
        echo "<tr><td>�����:</td><td>";
        tblCmb($mysqlconnection, "praxi",$praxi);
        echo "</td></tr>";
        echo "<tr><td>����������� ������</td><td><input type='text' name='wres' value=$wres /></td></tr>";
        echo "<tr><td>������</td><td><textarea rows=4 cols=80 name='comments' >$comments</textarea></td></tr>";
        
        //new 15-02-2012: implemented with jquery.autocomplete
        echo "<div id=\"content\">";
        echo "<form autocomplete=\"off\">";
        
        if ($multi)
        {
                $count = count($yphr_arr);
                for ($i=0; $i<$count; $i++)
                {
                    echo "<tr><td>������� (-�) ����������";
                    echo "<a href=\"\" onclick=\"window.open('help/help.html#school','', 'width=400, height=250, location=no, menubar=no, status=no,toolbar=no, scrollbars=no, resizable=no'); return false\"><img style=\"border: 0pt none;\" src=\"../images/help.gif\"/></a>";
                    echo "</td><td><input type=\"text\" name=\"yphr[]\" value='$yphr_arr[$i]' class=\"yphrow\" id=\"yphrow\" size=40/>";
                    echo "&nbsp;&nbsp;<input type=\"text\" name=\"hours[]\" value='$hours_arr[$i]' size=1 />";
                    echo "&nbsp;<input class=\"addRow\" type=\"button\" value=\"��������\" />";
                    echo "<input class=\"delRow\" type=\"button\" value=\"��������\" />";
                    echo "</tr>";
                }
                }
        else
        {
          echo "<tr><td>������� (-�) ����������";
          echo "<a href=\"\" onclick=\"window.open('help/help.html#school','', 'width=400, height=250, location=no, menubar=no, status=no,toolbar=no, scrollbars=no, resizable=no'); return false\"><img style=\"border: 0pt none;\" src=\"../images/help.gif\"/></a>";
          echo "</td><td><input type=\"text\" name=\"yphr[]\" value='$sx_yphrethshs' class=\"yphrow\" id=\"yphrow\" size=40/>";
          echo "&nbsp;&nbsp;<input type=\"text\" name=\"hours[]\" size=1 />";
          echo "&nbsp;<input class=\"addRow\" type=\"button\" value=\"��������\" />";
          echo "<input class=\"delRow\" type=\"button\" value=\"��������\" />";
          echo "</tr>";
        }
        
        echo "<tr><td>������������<br><br><small><strong>�������:</strong> ����������� �� ����: \"������ ������������ ��� ������ ��� ������ �� ��� ��� ������� ������������ ���\"</small></td>";
        echo "<td><textarea rows=4 cols=50 name='metakinhsh'>$metakinhsh</textarea></td></tr>";
        echo "</form>";
        echo "</div>";
        thesianaplselectcmb($thesi);
        echo "	</table>";
        
        echo "	<input type='hidden' name = 'id' value='$id'>";
        echo "	<input type='submit' value='�����������'>";
        echo "	<INPUT TYPE='button' VALUE='���������' class='btn-red' onClick=\"parent.location='ektaktoi.php?id=$id&op=view'\">";
        echo "	</form>";
        echo "    </center>";
        echo "</body>";
?>
<div id='results'>   </div>
<?php
        echo "</html>";
}
elseif ($_GET['op']=="view")
{
        ?>
        <script type="text/javascript">
        $().ready(function() {
                $("#adeia").click(function() {
                        var MyVar = <?php echo $id; ?>;
                        var sxEtos = <?php echo $sxol_etos; ?>;
                        $("#adeies").load("ekt_adeia_list.php?id="+ MyVar+"&sxol_etos="+sxEtos);
                });
        });
        </script>
<?php
        echo "<br>";
        echo "<table class=\"imagetable\" border='1'>";	
        echo "<tr>";
        //echo "<td colspan=2>ID</td><td colspan=2>$id</td>";
        echo "<th colspan=4 align=center>������� ���������� �������������</th>";
        echo "</tr>";
        echo "<tr><td>�������</td><td>$surname</td><td>�����</td><td>$name</td></tr>";
        echo "<tr><td>���������</td><td>$patrwnymo</td><td>���������</td><td>$mhtrwnymo</td></tr>";

        echo "<tr><td>�.�.�.</td><td>$afm</td><td></td><td></td></tr>";
        echo "<tr><td>������</td><td>".getKlados($klados_id,$mysqlconnection)."</td><td>���������</td><td>$katast</td></tr>";
        echo "<tr><td><a href=\"#\" class=\"show_hide\"><small>��������/��������<br>������������ ���������</small></a></td>";
        echo "<td colspan=3><div class=\"slidingDiv\">";
        echo "���.: $stathero - $kinhto<br>";
        
        echo "</div>";
        echo "</td></tr>";
        
        //$hm_mk = date ('d-m-Y', strtotime($hm_mk));
        //echo "<tr><td>������</td><td>$vathm</td><td>�.�.</td><td>$mk &nbsp;<small>(��� $hm_mk)</small></td></tr>";
        switch ($met_did)
        {
                case 0:
                        $met="���";
                        break;
                case 1:
                        $met="������������";
                        break;
                case 2:
                        $met="�����������";
                        break;
                case 3:
                        $met="���. + ���.";
                        break;
        }
        echo "<tr><td colspan>������������/�����������</td><td colspan=3>$met</td></tr>";
                        
        echo "<tr><td>������<br><br></td><td colspan='3'>$comments</td></tr>"; 
        echo "<tr><td>����������� ������</td><td colspan='3'>$wres</td></tr>";
        
        // check if multiple schools
        if ($multi)
        {
                $count = count($yphr_arr);
                for ($i=0; $i<$count; $i++)
                {
                $sxoleia .=  "<a href=\"../school/school_status.php?org=$yphr_id_arr[$i]\">$yphr_arr[$i]</a> ($hours_arr[$i] ����)<br>";
                $counthrs += $hours_arr[$i];
                }
                if ($count > 1)
                echo "<tr><td>��.����������</td><td colspan=3>$sxoleia<br><small>($counthrs ���� �� $count �������)</small></td></tr>";
                else
                echo "<tr><td>��.����������</td><td colspan=3>$sxoleia</td></tr>";
        }
        else
        {
                echo "<tr><td>��.����������</td><td colspan=3><a href=\"../school/school_status.php?org=$sx_yphrethshs_id\">$sx_yphrethshs</a></td></tr>";
        }
        $typos = get_type($type,$mysqlconnection);
        echo "<tr><td>������� ���������</td><td colspan=3>$analipsi</td>";
        $date_anal = date ("d-m-Y",  strtotime($hm_anal));
        echo "<tr><td>��/��� ��������</td><td colspan=3>$date_anal</td>";
        if ($kat == 2){
                $date_apox = date ("d-m-Y",  strtotime($hm_apox));
                echo "<tr><td>��/��� ����������</td><td colspan=3>$date_apox</td>";
        }
        echo "<tr><td>������������</td><td colspan=3>$metakinhsh</td></tr>";
        echo "<tr><td>����� �����������</td><td colspan=3>$typos</td>";
        echo "<tr><td>�����</td><td colspan=3>";
        echo $sxoletos ? 
                "<a href='ektaktoi_prev.php?praxi=$praxi'>".getNamefromTbl($mysqlconnection, "praxi_old", $praxi)."</a>" :
                "<a href='ektaktoi_list.php?praxi=$praxi'>".getNamefromTbl($mysqlconnection, "praxi", $praxi)."</a>";
        echo "</td></tr>";
        
        $qry = $sxoletos ? 
                "SELECT * FROM praxi_old WHERE id=$praxi AND sxoletos = $sxoletos" :
                "SELECT * FROM praxi WHERE id=$praxi";
        
        $res = mysqli_query($mysqlconnection, $qry);
        $ya = mysqli_result($res, 0, 'ya');
        $apofasi = mysqli_result($res, 0, 'apofasi');
        $ada = mysqli_result($res, 0, 'ada');
        echo "<tr><td>��������� �������</td><td colspan=3>$ya</td></tr>";
        echo "<tr><td>�.�.�.</td><td colspan=3>$ada</td></tr>";
        echo "<tr><td>������� �/���</td><td colspan=3>$apofasi</td></tr>";
        echo "<tr><td>����</td><td colspan=3>".thesianaplcmb($thesi)."</td></tr>";
        

        echo "<tr><td>�������� ��������� ���: </td><td>";
        //stringify schools
        $hour_sum = 0;
        for ($i=0; $i < count($yphr_arr); $i++)
        {
          $schools .=  $yphr_arr[$i] ." (" . $hours_arr[$i] ." ����), ";
          $hour_sum += $hours_arr[$i];
        }
        $schools = substr($schools, 0, -2); 
        
        //Form gia Bebaiwsh
        echo "<form id='wordfrm' name='wordfrm' action='' method='POST'>";
        $myCalendar = new tc_calendar("sel_date", true);
        $myCalendar->setIcon("../tools/calendar/images/iconCalendar.gif");
        $myCalendar->setPath("../tools/calendar/");
        $myCalendar->setDate(date('d'), date('m'), date('Y'));
        $myCalendar->writeScript();
        echo "<br>";
        echo "<input type='hidden' name='surname' value=$surname>";
        echo "<input type='hidden' name='name' value=$name>";
        echo "<input type='hidden' name='patrwnymo' value=$patrwnymo>";
        echo "<input type='hidden' name='klados' value='$klados'>";
        //echo "<input type='hidden' name='afm' value=$afm>";
        echo "<input type='hidden' name='ada' value='$ada'>";
        $meiwmeno = $type == 1 ? true : false;
        echo "<input type='hidden' name='meiwmeno' value=$meiwmeno>";
        echo "<input type='hidden' name='hoursum' value=$hour_sum>";
        echo "<input type='hidden' name='date_anal' value=$date_anal>";
        echo "<input type='hidden' name='date_apox' value=$hm_apox>";
        echo "<input type='hidden' name='ya' value=$ya>";
        echo "<input type='hidden' name='apofasi' value=$apofasi>";
        echo "<input type='hidden' name='sxoletos' value=$sxol_etos>";
        echo "<input type='hidden' name='schools' value='$schools'>";
        echo "<INPUT TYPE='submit' value='�������� ���������'>"; 
        echo "</form>";
        
        ?>
      <div id="word"></div>
        <?php
        echo "</td><td colspan=2></td></tr>";
        
        echo $updated > 0 ? "<tr><td colspan=4 align='right'><small>��������� ���������: ".date("d-m-Y H:i", strtotime($updated))."</small></td></tr>" : null;
        echo "	</table>";
        
        echo "<br>";
        // echo "  <INPUT TYPE='submit' id='adeia' VALUE='������'>"; future use?
        if ($usrlvl < 3){
                $can_edit = $_GET['sxoletos'] ? 'disabled' : '';
                echo "	<INPUT TYPE='button' VALUE='�����������' $can_edit onClick=\"parent.location='ektaktoi.php?id=$id&op=edit'\">";
        }
        echo "  <input type='button' value='��������' onclick='javascript:window.print()' />";
        echo "  <INPUT TYPE='submit' id='adeia' VALUE='������'>";
        echo $sxoletos ?
                "   <INPUT TYPE='button' VALUE='��������� ��� ����� �����������' onClick=\"parent.location='ektaktoi_prev.php?sxoletos=$sxoletos'\">" :
                "   <INPUT TYPE='button' VALUE='��������� ��� ����� �����������' onClick=\"parent.location='ektaktoi_list.php'\">";

        echo "<br><br><INPUT TYPE='button' class='btn-red' VALUE='������ ������' onClick=\"parent.location='../index.php'\">";
        ?>
        <div id="adeies"></div>
        <?php
        
        echo "    </center>";
        echo "</body>";
        echo "</html>";	
}
if ($_GET['op']=="delete")
{
        // Copies the to-be-deleted row to employee_deleted table for backup purposes.Also inserts a row on employee_del_log...
        //$query1 = "INSERT INTO ektaktoi_deleted SELECT e.* FROM ektaktoi e WHERE id =".$_GET['id'];
        //$result1 = mysqli_query($mysqlconnection, $query1)
        //$query1 = "INSERT INTO ektaktoi_log (emp_id, userid, action) VALUES (".$_GET['id'].",".$_SESSION['userid'].", 2)";
        //$result1 = mysqli_query($mysqlconnection, $query1)
        $query = "DELETE from ektaktoi where id=".$_GET['id'];
        $result = mysqli_query($mysqlconnection, $query);
        // Copies the deleted row to employee)deleted
        
        if ($result)
                echo "� ������� �� ������ $id ���������� �� ��������.";
        else
                echo "� �������� �������...";
        echo "	<INPUT TYPE='button' class=btn-red' VALUE='���������' onClick=\"parent.location='ektaktoi_list.php'\">";
}

mysqli_close($mysqlconnection);
?> 
