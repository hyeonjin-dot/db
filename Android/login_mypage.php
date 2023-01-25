<?php
#ini_set('display_errors', '0');
include_once "/var/www/html/a_team/a_team4/dbproject/code/register_and_login/encrypted_password.php"; #php 5.3.6 버전 이하에서 password 암호화 위하여 사용
$db = '
(DESCRIPTION =
        (ADDRESS_LIST=
                (ADDRESS = (PROTOCOL = TCP)(HOST = 203.249.87.57)(PORT = 1521))
        )
        (CONNECT_DATA =
        (SID = orcl)
        )
)';

$userid = isset($_POST["userID"]) ? $_POST["userID"] : "";
$password = isset($_POST["userPassword"]) ? $_POST["userPassword"] : "";


$response = array();
if (!is_null($userid)) {
  $response["success"] = true;
  $con = oci_connect("DBA2022G4", "dbdb1234", $db);
  $sql = "SELECT * FROM usertable WHERE userid='$userid'";
  $stmt = oci_parse($con, $sql);
  oci_execute($stmt);
  while (($row = oci_fetch_array($stmt, OCI_NUM))) {
    $encrypted_password = $row['1'];
    $username = $row['2'];
    $usersex = $row['3'];
    $userbirth = $row['4'];
    $userphonenumber = $row['5'];
    $performanceKey = $row['6'];

  }
  if (is_null($encrypted_password)) {
    $response["success"] = false;
  } else {
    if (password_verify($password, $encrypted_password)) {
      $_SESSION['userid'] = $userid;
      #session_start();
      //생일과 성별 처리
      $userbirth = strtotime($userbirth);
      $userbirth = date(" Y-m-d", $userbirth);
      if ($usersex == 'm') {
        $usersex = '남자';
      } else {
        $usersex = '여자';
      }
      $response["userID"] = $userid;
      $response["UserName"] = $username;
      $response["UserSex"] = $usersex;
      $response["UserBirth"] = $userbirth;
      $response["UserPhoneNumber"] = $userphonenumber;
    } else {
      $response["success"] = false;
    }

  }

  oci_free_statement($stmt);
  oci_close($con);
} else {
  $response["success"] = false;
}
echo json_encode($response);
?>