<?php
#warning메세지 제거
ini_set('display_errors', '0');
session_start();
#세션 스타트및 세션값 체크 구문
#세션 유저 아이디 체크 코딩시 검사를 위해 혹시 몰라 넣어둠 오류 발생시 체크 해볼것print_r($_SESSION);
if ($_SESSION['userid'] != 'Administrator') {
    echo "<script>alert('허용되지 않은 접근입니다.');</script>";
    echo "<script type='text/javascript'>
    location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/index.php' 
    </script>";
  }


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
$Delete_P_Key = $_POST['P_key'];
if (!is_null($Delete_P_Key)) {
    $sql_3 = "SELECT performancename FROM performance where performancekey=$Delete_P_Key";
    $result3 = oci_parse($con, $sql_3);
    oci_execute($result3);
    while ($row3 = oci_fetch_array($result3, OCI_NUM)) {
        $Delete_P_name = $row3[0];
    }
    $sql_4 = "DELETE FROM performance WHERE performancekey='$Delete_P_Key'";
    oci_execute(oci_parse($con, $sql_4));
    oci_close($con);
    echo "<script>alert('공연  $Delete_P_name 가 삭제 되었습니다.');</script>";
    echo "<script type='text/javascript'>
    location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/admin/delete_perform.php' 
    </script>";
}

?>
<!DOCUTYPE html>
    <html>

    <head>
        <title>공연 삭제</title>
        <link rel="stylesheet" href="/a_team/a_team4/dbproject/css/admin.css">
        </<head>

    <body>
        <div class="header">
            <h1><a href="/a_team/a_team4/dbproject/code/admin/administrator.php">홍익문화센터</a></h1>
            <div class="headerinner">
                <?php
                if (is_null($_SESSION['userid'])) {
                    echo "<a href='/a_team/a_team4/dbproject/code/register_and_login/login.php'>로그인</a>";
                    echo "<a href='register.php'>회원가입</a>";
                } else {
                    echo "<a href='/a_team/a_team4/dbproject/code/register_and_login/logout.php'>로그아웃</a>";
                    echo "<a href='/a_team/a_team4/dbproject/code/admin/administratorchange.php'>관리자정보수정</a>";
                }
                ?>

            </div>
        </div>
        <nav>
            <ul>
                <li class="dropdown">
                    <div class="dropdown-menu">공연</div>
                    <div class="dropdown-content">
                        <a href="/a_team/a_team4/dbproject/code/admin/performadd.php">공연추가</a>
                        <a href="/a_team/a_team4/dbproject/code/admin/delete_perform.php">공연삭제</a>
                        <a href="/a_team/a_team4/dbproject/code/admin/performlist_admin.php">공연목록</a>
                    </div>
                </li>
                <li class="dropdown">
                    <div class="dropdown-menu">강좌</div>
                    <div class="dropdown-content">
                        <a href="/a_team/a_team4/dbproject/code/admin/teacheradd.php">강사 및 강좌 추가</a>
                        <a href="/a_team/a_team4/dbproject/code/admin/delete_course_and_teacher.php">강사 및 강좌 삭제</a>
                        <a href="/a_team/a_team4/dbproject/code/admin/teacherlist_admin.php">강사진 목록</a>
                        <a href="/a_team/a_team4/dbproject/code/admin/courselist_admin.php">강좌 목록</a>
                    </div>
                </li>
                <li class="home"><a href="/a_team/a_team4/dbproject/code/admin/administrator.php">Home</a></li>
            </ul>
        </nav>

        <div class="course">
            <h1>공연 삭제</h1>
            <?php
            echo ("<table border='2'>");
            echo ("         
            <tr>
            <th>공연키</th>
            <th>공연명</th>
            <th>공연 시간</th>
            <th>공연 날짜</th>
            <th>공연료</th>
            </tr>
          ");
            while ($row = oci_fetch_array($result, OCI_NUM)) { {
                    $P_key = $row[0];
                    $P_name = $row[1];
                }
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
              <td width='100'><p align='center'>{$P_key}</p></td>
              <td width='100'><p align='center'>{$P_name}</p></td>
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
        </div>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <div class="deletePerform">
            <h1>삭제할 공연 키를 입력해주세요</h1>
            <h1>공연이 삭제됩니다.</h1>
            <form action="delete_perform.php" method="POST">
                <div class="data">
                    <p>공연키 입력</p>
                    <input type="text" name="P_key" placeholder="공연키를 입력해주세요" required>
                    <p></p><input type="submit" value="삭제"></p>
                </div>
        </div>
    </html>