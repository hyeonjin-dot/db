<?php
#warning메세지 제거
ini_set('display_errors', '0');
session_start();
#세션 스타트및 세션값 체크 구문
#세션 유저 아이디 체크 코딩시 검사를 위해 혹시 몰라 넣어둠 오류 발생시 체크 해볼것print_r($_SESSION);


$db = '
 (DESCRIPTION =
         (ADDRESS_LIST=
                 (ADDRESS = (PROTOCOL = TCP)(HOST = 203.249.87.57)(PORT = 1521))
         )
         (CONNECT_DATA =
         (SID = orcl)
         )
 )';

$userid = $_SESSION['userid'];
$con = oci_connect("DBA2022G4", "dbdb1234", $db);
$sql = "SELECT * FROM Performance order by PerformanceKey ASC";
$result = oci_parse($con, $sql);
oci_execute($result);
$addperform = $_POST['P_key'];
if (!is_null($addperform)) {
    $sql_3 = "SELECT performancename FROM performance where performancekey=$addperform";
    $result3 = oci_parse($con, $sql_3);
    oci_execute($result3);
    while ($row3 = oci_fetch_array($result3, OCI_NUM)) {
        $addperformname = $row3[0];
    }
    $sql_4 = "UPDATE usertable set PerformanceKey=$addperform where userid='$userid'";
    oci_execute(oci_parse($con, $sql_4));

    echo "<script>alert('공연  $addperformname 가 예약되었습니다');</script>";
    echo "<script type='text/javascript'>
    location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/registration/preservation_perform.php' 
    </script>";

}
if (is_null($userid)) {
    echo ("<script>alert('로그인이 필요합니다');</script>");
    echo "<script type='text/javascript'>
    location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/register_and_login/login.php' 
    </script>";
}


?>
<!DOCUTYPE html>
    <html>

    <head>
        <title>공연 예약</title>
        <link rel="stylesheet" href="/a_team/a_team4/dbproject/css/index.css">
        </<head>

    <body>
        <div class="header">
            <h1><a href="/a_team/a_team4/dbproject/code/index.php">홍익문화센터</a></h1>
            <div class="headerinner">
                <?php
                if (is_null($_SESSION['userid'])) {
                    echo "<a href='/a_team/a_team4/dbproject/code/register_and_login/login.php'>로그인</a>";
                    echo "<a href='/a_team/a_team4/dbproject/code/register_and_login/login/register.php'>회원가입</a>";
                } else {
                    echo "<a href='/a_team/a_team4/dbproject/code/register_and_login/logout.php'>로그아웃</a>";
                    echo "<a href='/a_team/a_team4/dbproject/code/register_and_login/mypage.php'>마이페이지</a>";
                }
                ?>
            </div>
        </div>

        <nav>
            <ul>
                <li class="dropdown">
                    <div class="dropdown-menu">공연</div>
                    <div class="dropdown-content">
                        <a href="/a_team/a_team4/dbproject/code/print/performlist.php">공연 목록</a>
                        <a href="/a_team/a_team4/dbproject/code/registration/preservation_perform">공연 예약</a>
                    </div>
                </li>
                <li class="dropdown">
                    <div class="dropdown-menu">강좌</div>
                    <div class="dropdown-content">
                        <a href="/a_team/a_team4/dbproject/code/print/courselist.php">강좌 목록</a>
                        <a href="/a_team/a_team4/dbproject/code/registration/course_registration.php">수강신청</a>
                        <a href="/a_team/a_team4/dbproject/code/print/teacherlist.php">강사진</a>
                    </div>
                </li>
                <li class="home"><a href="/a_team/a_team4/dbproject/code/index.php">Home</a></li>
            </ul>
        </nav>

        <div class="course">
            <h1>공연 예약</h1>
            <?php
            echo ("<table border='2'>");
            echo ("         
            <tr>
            <th>공연명</th>
            <th>공연 시간</th>
            <th>공연 날짜</th>
            <th>공연료</th>
            <th>예약</th>
            </tr>
          ");
            while ($row = oci_fetch_array($result, OCI_NUM)) {
                $sql_2 = "SELECT TO_CHAR(PerformanceStartTime, 'YYYY-MM-DD HH24:MI') from Performance where performancekey=$row[0]";
                $result2 = oci_parse($con, $sql_2);
                oci_execute($result2);
                while ($row2 = oci_fetch_array($result2, OCI_NUM)) {
                    $P_start = $row2[0];
                }

                $yoil_text_set = array(" 일요일 ", " 월요일 ", " 화요일 ", " 수요일 ", " 목요일", " 금요일 ", "토요일 ");
                $yoil = $yoil_text_set[date('w', strtotime(date($P_start)))];
                $starttime = date('H:i', strtotime($P_start));
                $endtime = date('H:i', strtotime($P_start . "+2 hours"));
                $startdate = date('Y-m-d', strtotime($P_start));
                $performkey = $row[0];

                echo ("
              <tr> 
              <td width='100'><p align='center'>{$row[1]}</p></td>
              <td width='100'><p align='center'>" . $starttime . '~' . $endtime . "</p></td>
              <td width='200'><p align='center'>" . $startdate . $yoil . "</p></td>
              <td width='100'><p align='center'>{$row[3]}</p></td>
              <td width='100'><text align='center'>
              <form action=preservation_perform.php method='POST'>
                <p></p><input type=hidden name='P_key'value=$performkey> <p></p>
                <p></p><input type='submit' value='예약'><p></p>
              </form> 
              </p></td>
  	</tr>
                ");
            }
            echo ("</table>");
            ?>

            <?php

            if (!is_null($userid)) {

                $sql_5 = "SELECT * FROM Performance WHERE performancekey =(select performancekey from usertable where userid='$userid')";
                $result5 = oci_parse($con, $sql_5);
                oci_execute($result5);
                while ($row5 = oci_fetch_array($result5, OCI_NUM)) {
                    $performkey_2 = $row5[0];
                    $performname = $row5[1];
                    $performfee = $row5[3];
                }

                $sql_6 = "SELECT TO_CHAR(PerformanceStartTime, 'YYYY-MM-DD HH24:MI') from Performance WHERE performancekey =(select performancekey from usertable where userid='$userid')";
                $result6 = oci_parse($con, $sql_6);
                oci_execute($result6);
                while ($row6 = oci_fetch_array($result6, OCI_NUM)) {
                    $performST = $row6[0];
                }
                if (!is_null($performkey_2)) {
                    echo ("<h1>예약된 공연</h1>");
                    echo ("<table border='2'>");
                    echo ("         
            <tr>
            <th>공연명</th>
            <th>공연 시간</th>
            <th>공연 날짜</th>
            <th>공연료</th>
            </tr>
          ");
                    $yoil2 = $yoil_text_set[date('w', strtotime(date($performST)))];
                    $starttime2 = date('H:i', strtotime($performST));
                    $endtime2 = date('H:i', strtotime($performST . "+2 hours"));
                    $startdate2 = date('Y-m-d', strtotime($performST));

                    echo ("<tr> 
            <td width='100'><p align='center'>{$performname}</p></td>
            <td width='100'><p align='center'>" . $starttime2 . '~' . $endtime2 . "</p></td>
            <td width='200'><p align='center'>" . $startdate . $yoil . "</p></td>
            <td width= '100'><p align= 'center'>{$performfee}</p></td>
    </tr>
            ");
                    echo ("</table>");
                    echo ("<h2>다른 공연을 예약할 시 기존 공연 예약이 취소됩니다.</h2>");
                }
                else{
                    echo ("<h1>예약된 공연이 없습니다.</h1>");
                }
            }

            oci_free_statement($result);
            oci_close($con);
            ?>
        </div>

    </html>