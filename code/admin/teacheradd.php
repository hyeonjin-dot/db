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
$TeacherName = $_POST['TeacherName'];
$TeacherSex = $_POST['Teachersex'];
$Teacherbirth = $_POST['TeacherBirth'];
$TeacherPhoneNumber = $_POST['TeacherPhoneNumber'];





if (!is_null($TeacherName)) {
  $con = oci_connect("DBA2022G4", "dbdb1234", $db);
  $sql_add_teacher = "INSERT INTO Teacher (TeacherKey,TeacherName,TeacherSex,TeacherBirth,TeacherPhoneNumber)
      VALUES (Teacher_SEQ.NextVAL,'$TeacherName','$TeacherSex',TO_DATE('$Teacherbirth','YYYY-MM-DD'),'$TeacherPhoneNumber')";
  oci_execute(oci_parse($con, $sql_add_teacher));

  $returnteacherkey = "SELECT Teacherkey FROM Teacher";
  $stmt = oci_parse($con, $returnteacherkey);
  oci_execute($stmt);
  while (($row = oci_fetch_array($stmt, OCI_NUM))) {
    $teacherkey = $row['0'];
  }
  setcookie("teachercookie", $teacherkey, time() + 60, "/");
  echo "<script>alert('강사 등록이 완료되었습니다 강좌 등록 페이지로 이동합니다. 등록된 강사의 키값은 $teacherkey 입니다');</script>";
  echo "<script type='text/javascript'>
  location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/admin/courseadd.php' 
  </script>";


  oci_close($con);
}

?>
<!DOCUTYPE html>
  <html>

  <head>
    <title>강사진추가</title>

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


    <div class="addteacher">
      <h1>강사 정보 기입</h1>
      <form action="teacheradd.php" method="POST">
        <div class="data">
          <p>이름</p>
          <input type="text" name="TeacherName" placeholder="강사이름" required>
          <div class="gender">
            <p>성별</p>
            <div>
              <label><input type="radio" name="Teachersex" value="m" checked>남
                <input type="radio" name="Teachersex" value="f">여</label>
            </div>
          </div>
          <p>생년월일</p>
          <input type="date" name="TeacherBirth" required>
          <p>전화번호</p>
          <input type="text" name="TeacherPhoneNumber" placeholder="010-0000-0000" required>
          <p></p><input type="submit" value="강사등록"></p>
        </div>
    </div>





  </body>

  </html>