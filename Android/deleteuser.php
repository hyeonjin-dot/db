<?php
ini_set('display_errors', '0');
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
$password = isset($_POST["userPass"]) ? $_POST["userPass"] : "";
$db = '
 (DESCRIPTION =
         (ADDRESS_LIST=
                 (ADDRESS = (PROTOCOL = TCP)(HOST = 203.249.87.57)(PORT = 1521))
         )
         (CONNECT_DATA =
         (SID = orcl)
         )
 )';
 if (!is_null($userid)) {
    $response["success"] = true;
    $con = oci_connect("DBA2022G4", "dbdb1234", $db);
    $sql = "SELECT * FROM usertable WHERE userid='$userid'";
    $stmt = oci_parse($con, $sql);
    oci_execute($stmt);
    while (($row = oci_fetch_array($stmt, OCI_NUM))) {
        $encrypted_password = $row['1'];
    }
    if (!is_null($password)) {
        if (password_verify($password, $encrypted_password)) {
            $con = oci_connect("DBA2022G4", "dbdb1234", $db);
            $sql = "DELETE FROM usertable WHERE userid='" . $userid . "'";
            oci_execute(oci_parse($con, $sql));
            oci_close($con);

        } else
        {
            $response["success"] = false;
    
        }
    }
    echo json_encode($response);   
} 