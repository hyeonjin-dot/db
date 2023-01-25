<?php
#warning메세지 제거
ini_set('display_errors', '0');
#세션 스타트및 세션값 체크 구문 
session_start();
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

$CourseName = $_POST['CourseName'];
$CourseStartTime = str_replace('T', ' ', $_POST['CourseStartTime']);
$CourseEndDate = str_replace('T', ' ', $_POST['CourseEndDate']);
$Coursefee = $_POST['Coursefee'];
$TeacherKey = $_COOKIE["teachercookie"];
$TeacherKeyquerry = $_POST['teacherkey'];
if (!is_null($CourseName)) {
  $con = oci_connect("DBA2022G4", "dbdb1234", $db);
  $sql_add_course = "INSERT INTO Course (CourseKey,CourseName,CourseStartTime,CourseEndDate,Coursefee,TeacherKey)
      VALUES (Course_SEQ.NextVAL,'$CourseName',TO_DATE('$CourseStartTime','YYYY-MM-DD hh24:mi'),TO_DATE('$CourseEndDate','YYYY-MM-DD hh24:mi'),'$Coursefee','$TeacherKeyquerry')";
  oci_execute(oci_parse($con, $sql_add_course));
  echo "<script>alert('강좌 등록이 완료되었습니다.');</script>";
  echo "<script type='text/javascript'>
    location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/admin/courselist_admin.php' 
    </script>";


  oci_close($con);
}

?>
<!DOCUTYPE html>
  <html>

  <head>
    <title>관리자계정</title>
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


    <div class="addcourse">
      <h1>강사 정보 기입</h1>
      <form action="courseadd.php" method="POST">
        <div class="data">
          <p>이름</p>
          <input type="text" name="CourseName" placeholder="강좌이름" required>
          <p>강좌 시작 및 시작시간</p>
          <input type="datetime-local" name="CourseStartTime" required>
          <p>강좌 종료일</p>
          <input type="datetime-local" name="CourseEndDate" required>
          <p>강좌요금</p>
          <input type="number" name="Coursefee" required>
          <p>강사키</p>
          <?php echo '<input type="text" name="teacherkey" value=' . $TeacherKey . ' readonly>'; ?>
          <p></p><input type="submit" value="강좌 등록"></p>
        </div>
    </div>





  </body>

  </html>