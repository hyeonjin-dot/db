<?php
#warning 메세지 제거
ini_set('display_errors', '0');
#세션 스타트및 세션값 체크 구문 
session_start();
$userid=$_SESSION['userid'];
$db = '
 (DESCRIPTION =
         (ADDRESS_LIST=
                 (ADDRESS = (PROTOCOL = TCP)(HOST = 203.249.87.57)(PORT = 1521))
         )
         (CONNECT_DATA =
         (SID = orcl)
         )
 )';
if (!is_null($_SESSION['userid'])) {
    $con = oci_connect("DBA2022G4", "dbdb1234", $db);
    $sql = "SELECT * FROM usertable WHERE userid='$userid'";
    $stmt = oci_parse($con, $sql);
    oci_execute($stmt);
    while (($row = oci_fetch_array($stmt, OCI_NUM))) {
        $username = $row['2'];
    }
    oci_close($con);
} else {
    echo "<script>alert('로그인이 필요합니다');</script>";
    echo "<script type='text/javascript'>
    location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/index.php'
    </script>";
}
if ($_SESSION['userid'] == 'Administrator') {
    echo "<script>alert('관리자 계정으로 접속하였습니다');</script>";
    echo "<script type='text/javascript'>
    location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/admin/administrator.php' 
    </script>";

} else {
    echo "<script>alert('환영합니다 $username 님');</script>";
    echo "<script type='text/javascript'>
    location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/index.php' 
    </script>";
}
?>