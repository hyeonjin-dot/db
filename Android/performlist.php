<?php
ini_set('display_errors', '0');
$userid = isset($_POST['userID']) ? $_POST["userID"] : "";
$db = '
(DESCRIPTION =
        (ADDRESS_LIST=
                (ADDRESS = (PROTOCOL = TCP)(HOST = 203.249.87.57)(PORT = 1521))
        )
        (CONNECT_DATA =
        (SID = orcl)
        )
)';
$response = array();
if (!is_null($userid)) {
    $response["success"] = true;
    $con = oci_connect("DBA2022G4", "dbdb1234", $db);
    $sql = "SELECT * FROM Performance WHERE performancekey =(select performancekey from usertable where userid='$userid')";
    $result = oci_parse($con, $sql);
    oci_execute($result);
    while ($row = oci_fetch_array($result, OCI_NUM)) {
        $performname = $row['1'];
        $performfee = $row['3'];
    }
    $sql_2 = "SELECT TO_CHAR(PerformanceStartTime, 'YYYY-MM-DD HH24:MI') from Performance WHERE performancekey =(select performancekey from usertable where userid='$userid')";
    $result2 = oci_parse($con, $sql_2);
    oci_execute($result2);
    while ($row2 = oci_fetch_array($result2, OCI_NUM)) {
       $performstarttime = $row2['0'];

    }
    $yoil_text_set = array(" 일요일 ", " 월요일 ", " 화요일 ", " 수요일 ", " 목요일", " 금요일 ", "토요일 ");
    $startdate = date('Y-m-d', strtotime($performstarttime));
    $starttime = date('H:i', strtotime($performstarttime));
    $endtime = date('H:i', strtotime($starttime . "+2 hours"));
    $yoil = $yoil_text_set[date('w', strtotime(date($performstarttime)))];
    $response["performname"] = $performname;
    $response["startdate"] = $startdate;
    $response["starttime"] = $starttime;
    $response["endtime"] = $endtime;
    $response["performfee"] = $performfee;
    $response["yoil"] = $yoil;
    echo json_encode($response);

}

oci_free_statement($result);
oci_close($con);
?>