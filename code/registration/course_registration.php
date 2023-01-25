<?php
#warning메세지 제거
ini_set('display_errors', '0');
session_start();
#세션 스타트및 세션값 체크 구문
#세션 유저 아이디 체크 코딩시 검사를 위해 혹시 몰라 넣어둠 오류 발생시 체크 해볼것print_r($_SESSION);
$userid = $_SESSION['userid'];
if (is_null($userid)) {
    echo ("<script>alert('로그인이 필요합니다');</script>");
    echo "<script type='text/javascript'>
    location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/register_and_login/login.php' 
    </script>";
}
$i = 0;
$j = 0;
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
$sql = "SELECT * FROM Course Order By coursekey ASC ";
$result = oci_parse($con, $sql);
oci_execute($result);
$postcoursekey = $_POST['postcoursekey'];
$postdelete_coursekey = $_POST['delete_coursekey'];




?>
<!DOCTYPE html>
<html>

<head>
    <title>수강신청</title>
    <link rel="stylesheet" href="/a_team/a_team4/dbproject/css/teacherlist.css">
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
        <h1>강좌목록</h1>
        <?php
        echo ("<table border='2'>");
        echo ("         
            <tr>
            <th>강좌 이름</th>
            <th>강좌 시간</th>
            <th>강좌기간</th>
            <th>수강료</th>
            <th>강사</th>
            <th>수강신청</th>
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
            $coursekey = $row[0];

            echo ("
              <tr> 
              <td width='100'><p align='center'>{$row['1']}</p></td>
              <td width='100'><p align='center'>" . $yoil . $starttime . '~' . $endtime . "</p></td>
              <td width='200'><p align='center'>" . $startdate . '~' . $enddate . "</p></td>
              <td width='100'><p align='center'>{$row['4']}</p></td>
              <td width='100'><p align='center'>{$teachername}
              <td width='100'><text align='center'>
              <form action=course_registration.php method='POST'>
                <p></p><input type=hidden name='postcoursekey'value=$coursekey> <p></p>
                <p></p><input type='submit' value='수강신청'><p></p>
              </form> 
              </p></td>
              
  	</tr> 
                ");


        }
        echo ("</table>");
        #수강중인 강좌 테이블 null시 비활성화
        $sql_99 = "SELECT * FROM Course WHERE CourseKey  in (select CourseKey  from CourseRegistration where userid='$userid')";
        $resultcheck = oci_parse($con, $sql_99);
        oci_execute($resultcheck);
        while ($checkrow = oci_fetch_array($resultcheck, OCI_NUM)) {
            $checkkey = $checkrow[0];
        }
        if (!is_null($checkkey)) {

            echo "<br>";
            echo "<br>";
            echo "<br>";
            echo "<h1>수강중인 강좌 목록</h1>";
            echo ("<table border='2'>");
            echo ("         
            <tr>
            <th>강좌 이름</th>
            <th>강좌 시간</th>
            <th>강좌기간</th>
            <th>수강료</th>
            <th>강사</th>
            <th>수강철회</th>
            </tr>
          ");

            #주의 모든 변수를 재사용 하였음에 유의 sql_5의 select문을 이용함 row5로 대체
            $sql_5 = "SELECT * FROM Course WHERE CourseKey  in (select CourseKey  from CourseRegistration where userid='$userid')";
            $result5 = oci_parse($con, $sql_5);
            oci_execute($result5);
            while ($row5 = oci_fetch_array($result5, OCI_NUM)) {
                $sql_2 = "SELECT teachername from teacher where teacherkey=(select teacherkey from course where coursekey=$row5[0])";
                $result2 = oci_parse($con, $sql_2);
                oci_execute($result2);
                while ($row2 = oci_fetch_array($result2, OCI_NUM)) {
                    $teachername = $row2[0];
                }
                $sql_3 = "SELECT TO_CHAR(coursestarttime, 'YYYY-MM-DD HH24:MI') from course where teacherkey=(select teacherkey from course where coursekey=$row5[0])";
                $result3 = oci_parse($con, $sql_3);
                oci_execute($result3);
                while ($row3 = oci_fetch_array($result3, OCI_NUM)) {
                    $coursestarttime = $row3[0];
                }
                $sql_4 = "SELECT TO_CHAR(CourseEndDate, 'YYYY-MM-DD HH24:MI') from course where teacherkey=(select teacherkey from course where coursekey=$row5[0])";
                $result4 = oci_parse($con, $sql_4);
                oci_execute($result4);
                while ($row4 = oci_fetch_array($result4, OCI_NUM)) {
                    $courseenddate = $row4[0];
                }
                #_r은 신청한 변수값
                $yoil = $yoil_text_set[date('w', strtotime(date($coursestarttime)))];
                $starttime = date('H:i', strtotime($coursestarttime));
                $endtime = date('H:i', strtotime($coursestarttime . "+1 hours"));
                $startdate = date('Y-m-d', strtotime($coursestarttime));
                $enddate = date('Y-m-d', strtotime($courseenddate));
                $array[$i] = $row5[0];
                $i++;
                $coursekey = $row5[0];
                echo ("
              <tr> 
              <td width='100'><p align='center'>{$row5['1']}</p></td>
              <td width='100'><p align='center'>" . $yoil . $starttime . '~' . $endtime . "</p></td>
              <td width='200'><p align='center'>" . $startdate . '~' . $enddate . "</p></td>
              <td width='100'><p align='center'>{$row5['4']}</p></td>
              <td width='100'><p align='center'>{$teachername}
              <td width='100'><text align='center'>
              <form action=course_registration.php method='POST'>
                <p></p><input type=hidden name='delete_coursekey'value=$coursekey> <p></p>
                <p></p><input type='submit' value='수강철회'><p></p>
              </form> 
              </p></td>

        </tr> 
                ");
            }
        } else {
            echo ("<h2>수강중인 강좌가 없습니다</h2>");
        }
        #수강 철회
        if (!is_null($postdelete_coursekey)) {
            $sql_delete_course = "DELETE FROM CourseRegistration WHERE userid='$userid' and CourseKey ='$postdelete_coursekey'";
            oci_execute(oci_parse($con, $sql_delete_course));
            echo "<script>alert('수강 철회가 완료되었습니다.');</script>";
            echo "<script type='text/javascript'>
            location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/registration/course_registration.php' 
            </script>";

        } else {
            #nothing to do
        }

        #수강신청 
        $j = sizeof($array);
        echo ("</table>");
        $checkcounter = 0; #중복시간을 체크하기위한 변수
        if (!is_null($postcoursekey)) {

            /*위의 내용을 출력이 아닌 알고리즘 구현에 재사용한다.
            요구조건 강좌가 겹친다면 수강신청이 되지 않아야한다
            모든 강좌에 대해 요일값을 가져와서  같은 요일 이라면 겹치는 강의 시간이 있는지로 비교한다.*/
            $result6 = oci_parse($con, $sql_5); #result6로 위의 select문 재사용
            oci_execute($result6);
            for ($i = 0; $i < $j; $i++) {

                if ($array[$i] == $postcoursekey) {
                    echo "<script>alert(' 이미 신청된 강좌입니다.');</script>";
                    echo "<script type='text/javascript'>
                location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/registration/course_registration.php' 
                </script>";

                } else {
                    #nothing to do ;
                }
            }
            $count = 0;
            echo $count;
            $sql_8 = "SELECT * FROM Course WHERE CourseKey  in (select CourseKey  from CourseRegistration where userid='$userid')";
            $result8 = oci_parse($con, $sql_8);
            oci_execute($result8);
            while ($row8 = oci_fetch_array($result8, OCI_NUM)) {
                $count++;
            }
            if ($count == 0) {
                $sql_course_registration = "INSERT INTO CourseRegistration (UserID,CourseKey) 
                VALUES ('$userid',$postcoursekey)";
                oci_execute(oci_parse($con, $sql_course_registration));
                echo "<script>alert('수강 신청이 완료되었습니다.');</script>";
                echo "<script type='text/javascript'>
                location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/registration/course_registration.php' 
                </script>";
            } else {
                #nothing to do ;
            }


            while ($row5 = oci_fetch_array($result6, OCI_NUM)) {

                $sql_3 = "SELECT TO_CHAR(coursestarttime, 'YYYY-MM-DD HH24:MI') from course where teacherkey=(select teacherkey from course where coursekey=$row5[0])";
                $result3 = oci_parse($con, $sql_3);
                oci_execute($result3);
                while ($row3 = oci_fetch_array($result3, OCI_NUM)) {
                    $coursestarttime = $row3[0];
                }
                #신청된 강좌의 변수값
                $yoil = $yoil_text_set[date('w', strtotime(date($coursestarttime)))];
                $alreadystarttime = date('H:i', strtotime($coursestarttime));
                $alreadystarttime = strtotime($alreadystarttime);
                $alreadyendtime = date('H:i', strtotime($coursestarttime . "+1 hours"));
                $alreadyendtime = strtotime($alreadyendtime);
                ##############################
        
                #after는 신청할 변수값
                $sql_7 = "SELECT TO_CHAR(coursestarttime, 'YYYY-MM-DD HH24:MI') from course where CourseKey =(select CourseKey  from course where coursekey=$postcoursekey)";
                $result7 = oci_parse($con, $sql_7);
                oci_execute($result7);
                while ($row6 = oci_fetch_array($result7, OCI_NUM)) {
                    $coursestarttime_2 = $row6[0];
                }
                $yoil_2 = $yoil_text_set[date('w', strtotime(date($coursestarttime_2)))];
                $afterstarttime = date('H:i', strtotime($coursestarttime_2));
                $afterstarttime = strtotime($afterstarttime);
                $afterendtime = date('H:i', strtotime($coursestarttime_2 . "+1 hours"));
                $afterendtime = strtotime($afterendtime);
                if ($yoil == $yoil_2) {
                    if ($afterstarttime > $alreadystarttime) #신청할 강좌의 시작시간이 신청된 강좌의 시작시간보다 늦을때
                    {
                        if ($alreadyendtime > $afterstarttime) {
                            $checkcounter++;

                        } else {
                            #nothing to do;
                        }
                    } else if ($afterstarttime == $alreadystarttime) {
                        $checkcounter++;
                    } else #신청할 강좌의 시작시간이 신청된 강좌의 시작시간보다 빠를때
                    {
                        if ($alreadystarttime < $afterendtime) {
                            $checkcounter++;
                        } else {
                            #nothing to do;
        
                        }
                    }

                } else {
                    #nothing to do ;
                }
                echo $checkcounter;

            }
            if ($checkcounter > 0) {
                echo "<script>alert('신청된 강좌와 수강시간이 중복됩니다.');</script>";
                echo "<script type='text/javascript'>
            location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/registration/course_registration.php' 
            </script>";

            } else {

                $sql_course_registration = "INSERT INTO CourseRegistration (UserID,CourseKey) 
                 VALUES ('$userid',$postcoursekey)";
                oci_execute(oci_parse($con, $sql_course_registration));
                echo "<script>alert('수강 신청이 완료되었습니다.');</script>";
                echo "<script type='text/javascript'>
                location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/registration/course_registration.php' 
                </script>";

            }
        } else {
            #nothing to do ;
        
        }




        oci_close($con);
        ?>
    </div>

</html>