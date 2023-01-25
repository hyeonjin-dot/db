<?php
#warning메세지 제거
ini_set('display_errors', '0');
#세션 유저 아이디 체크 코딩시 검사를 위해 혹시 몰라 넣어둠 오류 발생시 체크 해볼것print_r($_SESSION);
include_once "/var/www/html/a_team/a_team4/dbproject/code/register_and_login/encrypted_password.php"; #php 5.3.6 버전 이하에서 password 암호화 위하여 사용
$userid = isset($_POST['userID']) ? $_POST["userID"] : "";
$password = isset($_POST['userPass']) ? $_POST["userPass"] : "";
$password_confirm = isset($_POST['userPassConfirm']) ? $_POST["userPassConfirm"] : "";
$username = isset($_POST['userName']) ? $_POST["userName"] : "";
$usersex = isset($_POST['sex']) ? strtolower($_POST["sex"]) : "";
$userbirth = isset($_POST['birth']) ? $_POST["birth"] : "";
$userphonenumber = isset($_POST['number']) ? $_POST["number"] : "";
$checkcounter = 0;
$response["success"] = false;

$db = '
 (DESCRIPTION =
         (ADDRESS_LIST=
                 (ADDRESS = (PROTOCOL = TCP)(HOST = 203.249.87.57)(PORT = 1521))
         )
         (CONNECT_DATA =
         (SID = orcl)
         )
 )';
if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $userbirth)) {
    $checkbirth = 1;
} else {
    $checkbirth = 0;
}


if (!is_null($userid)) {
    $con = oci_connect("DBA2022G4", "dbdb1234", $db);
    $sql = "SELECT userid FROM usertable WHERE userid='$userid'";
    #sql분석 및 실행준비 구문
    $stmt = oci_parse($con, $sql);
    oci_execute($stmt);
    while ($row = oci_fetch_array($stmt, OCI_ASSOC)) {
        foreach ($row as $item) {
            $userid_exist = $item;
            if ($userid_exist == $userid) {
                $checkcounter++;
            }
        }

    }
    if ($checkcounter > 0) {
        $response["success"] = false;
        echo json_encode($response);
    } else if ($password != $password_confirm) {
        $response["success"] = false;
        echo json_encode($response);
    } else if ($checkbirth == 0) {
        $response["success"] = false;
        echo json_encode($response);
    } else if ($usersex != 'm' && $usersex != 'f') {
        $response["success"] = false;
        echo json_encode($response);
    } else {
        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
        $sql_add_user = "INSERT INTO UserTable (UserID,UserPassword,UserName,UserSex,UserBirth,UserPhoneNumber,PerformanceKey)
        VALUES ('$userid', '$encrypted_password','$username','$usersex',TO_DATE('$userbirth', 'YYYY-MM-DD'),'$userphonenumber',NULL)";
        oci_execute(oci_parse($con, $sql_add_user));
        $response["success"] = true;
        echo json_encode($response);
    }
    oci_free_statement($stmt);
    oci_close($con);
}
?>