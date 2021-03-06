<?php

session_start();
require_once "../config.php";
require_once '../vendor/phpoffice/phpword/Classes/PHPWord.php';
require_once '../tools/functions.php';

$PHPWord = new PHPWord();
$anadr = false;
if (isset($_POST['yphr'])) {
  $document = $PHPWord->loadTemplate('../word/tmpl/tmpl_vev.docx');
} else {
  $document = $PHPWord->loadTemplate('../word/tmpl/tmpl_anadr.docx');
  $anadr = true;
}

$data_array = $_POST;
//$current_date = date("d/m/Y");
$document->setValue('date', date("d/m/Y"));
$document->setValue("date2", date("d/m/Y"));

$document->setValue('surname', $data_array['surname']);

$document->setValue('name', $data_array['name']);

$document->setValue('father', $data_array['patrwnymo']);

$document->setValue('klados', $data_array['klados']);

$am = $data_array['am'];
$document->setValue('am', $data_array['am']);

$document->setValue('vath', $data_array['vathm']);

$document->setValue('organ', $data_array['sx_organikhs']);

$document->setValue('dior', $data_array['fek_dior']);

$data = date("d-m-Y", strtotime($data_array['hm_dior_org']));
$document->setValue('hmdior', $data);

$data = date("d/m/Y", strtotime($data_array['hm_anal']));
$document->setValue('hmanal', $data);

$document->setValue('yphr', $data_array['ymd']);

// head title & name
$mysqlconnection = mysqli_connect($db_host, $db_user, $db_password, $db_name);  
mysqli_query($mysqlconnection, "SET NAMES 'utf8'");
mysqli_query($mysqlconnection, "SET CHARACTER SET 'utf8'");
$data = getParam('head_title', $mysqlconnection);
$document->setValue('headtitle', $data);
$data = getParam('head_name', $mysqlconnection);
$document->setValue('headname', $data);

$mk = get_mk($data_array['id'], $mysqlconnection)['mk'];
$document->setValue('mk', $mk);

$output1 = $anadr ? 
  "../word/vev_anadr_$am.docx" :
  "../word/vev_yphr_$am.docx";
  
$document->save($output1);

header('Content-type: text/html; charset=utf-8'); 
echo "<html>";
echo "<p><a href=$output1>Ανοιγμα εγγράφου</a></p>";
echo "</html>";
?>
