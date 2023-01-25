<?php
#warning메세지 제거
ini_set('display_errors', '1');
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
$sql = "SELECT * FROM Course order by coursekey ASC" ;
$result = oci_parse($con, $sql);
oci_execute($result);

?>
<!DOCUTYPE html>
    <html>

    <head>
        <title>강좌 목록</title>
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
            <h1>강좌목록</h1>
            <?php
            echo ("<table border='2'>");
            echo ("         
            <tr>
            <th>강좌 키</th>
            <th>강좌 이름</th>
            <th>강좌 시간</th>
            <th>강좌기간</th>
            <th>수강료</th>
            <th>강사</th>
            </tr>
          ");
            while ($row = oci_fetch_array($result, OCI_NUM)) {
                $sql_2 = "SELECT teachername from teacher where teacherkey=(select teacherkey from course where coursekey=$row[0])";
                $result2 = oci_parse($con, $sql_2);
                oci_execute($result2);
                while ($row2 = oci_fetch_array($result2, OCI_NUM)) {
                    $teachername = $row2[0];
                }
                $sql_3 = "SELECT TO_CHAR(coursestarttime, 'YYYY-MM-DD HH24:MI') from course where teacherkey=(select teacherkey from course where coursekey=$row[0])";
                $result3 = oci_parse($con, $sql_3);
                oci_execute($result3);
                while ($row3 = oci_fetch_array($result3, OCI_NUM)) {
                    $coursestarttime = $row3[0];
                }
                $sql_4 = "SELECT TO_CHAR(CourseEndDate, 'YYYY-MM-DD HH24:MI') from course where teacherkey=(select teacherkey from course where coursekey=$row[0])";
                $result4 = oci_parse($con, $sql_4);
                oci_execute($result4);
                while ($row4 = oci_fetch_array($result4, OCI_NUM)) {
                    $courseenddate = $row4[0];
                }

                $yoil_text_set = array(" 매주 일요일 ", " 매주월요일 ", " 매주 화요일 ", " 매주 수요일 ", " 매주 목요일", " 매주 금요일 ", "매주 토요일 ");
                $yoil = $yoil_text_set[date('w', strtotime(date($coursestarttime)))];
                $starttime = date('H:i', strtotime($coursestarttime));
                $endtime = date('H:i', strtotime($coursestarttime . "+1 hours"));
                $startdate = date('Y-m-d', strtotime($coursestarttime));
                $enddate = date('Y-m-d', strtotime($courseenddate));



                echo ("
              <tr> 
              <td width='100'><p align='center'>{$row['0']}</p></td>
              <td width='100'><p align='center'>{$row['1']}</p></td>
              <td width='100'><p align='center'>" . $yoil . $starttime . '~' . $endtime . "</p></td>
              <td width='200'><p align='center'>" . $startdate . '~' . $enddate . "</p></td>
              <td width='100'><p align='center'>{$row['4']}</p></td>
              <td width='100'><p align='center'>{$teachername}
  	</tr>
                ");
            }
            echo ("</table>");
            oci_free_statement($result);
            oci_close($con);
            ?>
        </div>

    </html>