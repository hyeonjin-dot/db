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

$con = oci_connect("DBA2022G4", "dbdb1234", $db);
$sql = "SELECT * FROM Performance order by PerformanceKey ASC";
$result = oci_parse($con, $sql);
oci_execute($result);

?>
<!DOCUTYPE html>
    <html>

    <head>
        <title>공연 목록</title>
        <link rel="stylesheet" href="/a_team/a_team4/dbproject/css/index.css">
        </<head>

    <body>
        <div class="header">
            <h1><a href="/a_team/a_team4/dbproject/code/index.php">홍익문화센터</a></h1>
            <div class="headerinner">
                <?php
                if (is_null($_SESSION['userid'])) {
                    echo "<a href='/a_team/a_team4/dbproject/code/register_and_login/login.php'>로그인</a>";
                    echo "<a href='/a_team/a_team4/dbproject/code/register_and_login/register.php'>회원가입</a>";
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
            <h1>공연 목록</h1>
            <?php
            echo ("<table border='2'>");
            echo ("         
            <tr>
            <th>공연사진</th>
            <th>공연명</th>
            <th>공연 시간</th>
            <th>공연 날짜</th>
            <th>공연료</th>
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
                $endtime = date('H:i', strtotime($P_start . "+1 hours"));
                $startdate = date('Y-m-d', strtotime($P_start));

                echo ("
              <tr> 
              <td width='400' height='500' ><p align='center'><img src='/a_team/a_team4/dbproject/img/$row[0].png' width='380' height='480'></p></td>
              <td width='100'><p align='center'>{$row[1]}</p></td>
              <td width='100'><p align='center'>" . $starttime . '~' . $endtime . "</p></td>
              <td width='200'><p align='center'>" . $startdate . $yoil . "</p></td>
              <td width= '100'><p align= 'center'>{$row[3]}</p></td>
  	</tr>
                ");
            }
            echo ("</table>");
            oci_free_statement($result);
            oci_close($con);
            ?>

    </html>