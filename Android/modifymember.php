<?php
#warning메세지 제거
ini_set('display_errors', '0');
#세션 유저 아이디 체크 코딩시 검사를 위해 혹시 몰라 넣어둠 오류 발생시 체크 해볼것print_r($_SESSION);
include_once "/var/www/html/a_team/a_team4/dbproject/code/register_and_login/encrypted_password.php"; #php 5.3.6 버전 이하에서 password 암호화 위하여 사용
$userid = isset($_POST['userID']) ? $_POST["userID"] : "";
$currentpassword = isset($_POST['userPass']) ? $_POST["userPass"] : "";
$changedPass = isset($_POST['changePass']) ? $_POST["changePass"] : "";
$changedPassConfirm = isset($_POST['changePassConfirm']) ? $_POST["changePassConfirm"] : "";
$changeduserbirth = isset($_POST['birth']) ? $_POST["birth"] : "";
$changeduserphonenumber = isset($_POST['number']) ? $_POST["number"] : "";


$db = '
 (DESCRIPTION =
         (ADDRESS_LIST=
                 (ADDRESS = (PROTOCOL = TCP)(HOST = 203.249.87.57)(PORT = 1521))
         )
         (CONNECT_DATA =
         (SID = orcl)
         )
 )';


if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $changeduserbirth)) {
    $checkbirth = 1;
} else {
    $checkbirth = 0;
}

if (!is_null($userid)) {
    $con = oci_connect("DBA2022G4", "dbdb1234", $db);
    $sql = "SELECT UserPassword FROM usertable WHERE userid='$userid'";
    $stmt = oci_parse($con, $sql);
    oci_execute($stmt);
    while (($row = oci_fetch_array($stmt, OCI_NUM))) {
        $encrypted_password = $row['0'];
    }
    if (password_verify($currentpassword, $encrypted_password)) {

        if ($changedPass == $changedPassConfirm) {
            if ($checkbirth == 0) {
                $response["success"] = false;
            } else {
                
                $changed_encrypted_password = password_hash($changedPass, PASSWORD_DEFAULT);
                $response["password"] = $changed_encrypted_password;
                $sql_modify_user = "UPDATE usertable SET UserPassword='" . $changed_encrypted_password . "', 
                UserBirth=TO_DATE('$changeduserbirth', 'YYYY-MM-DD'),UserPhoneNumber='" . $changeduserphonenumber . "' WHERE userid='" . $userid . "'";
                $response["success"] = true;
                oci_execute(oci_parse($con, $sql_modify_user));
                oci_close($con);
                
            }
        } else {
            $response["success"] = false;

        }
    }
    else{
        $response["success"] = false;

    }
    oci_free_statement($stmt);
    oci_close($con);
}
echo json_encode($response);
?>