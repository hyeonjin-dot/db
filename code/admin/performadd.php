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
$performanceName = $_POST['PerformanceName'];
$performanceStartTime = str_replace('T', ' ', $_POST['PerformanceStartTime']);
$performancefee = $_POST['Performancefee'];


if (!is_null($performanceName)) {
  $con = oci_connect("DBA2022G4", "dbdb1234", $db);
  $sql_add_performance = "INSERT INTO Performance (PerformanceKey,PerformanceName,PerformanceStartTime,Performancefee)
      VALUES (Performance_SEQ.NextVAL,'$performanceName',TO_DATE('$performanceStartTime','YYYY-MM-DD hh24:mi'),'$performancefee')";
  oci_execute(oci_parse($con, $sql_add_performance));
  $sql_2 = "SELECT PerformanceKey FROM Performance";
  $result_2 = oci_parse($con, $sql_2);
  oci_execute($result_2);
  while ($row2 = oci_fetch_array($result_2, OCI_NUM)) {
     $P_key = $row2['0'];
  }


  $uploaded_file_name_tmp = $_FILES['myfile']['tmp_name'];
  $uploaded_file_name = $_FILES['myfile']['name'];
  $upload_folder = "/var/www/html/a_team/a_team4/dbproject/img/";
  move_uploaded_file($uploaded_file_name_tmp, $upload_folder . $uploaded_file_name);
  $ext = substr(strrchr($uploaded_file_name, '.'), 1);
  #파일의 확장자를 추출
  rename('/var/www/html/a_team/a_team4/dbproject/img/' . $uploaded_file_name . '', '/var/www/html/a_team/a_team4/dbproject/img/' . $P_key . '.' . $ext . '');
  echo "<script>alert('공연 등록이 완료되었습니다.');</script>";
  #echo "<script type='text/javascript'>
  # location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/admin/perform.php' 
  #</script>";
  oci_close($con);
}

?>
<!DOCUTYPE html>
  <html>

  <head>
    <title>공연 추가</title>

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


    <div class="addperformance">
      <h1>공연 정보 기입</h1>
      <form action="performadd.php" method="POST" enctype="multipart/form-data">
        <div class="data">
          <p>이름</p>
          <input type="text" name="PerformanceName" placeholder="공연이름" required>
          <p>공연 시작시간</p>
          <input type="datetime-local" name="PerformanceStartTime" required>
          <p>공연비</p>
          <input type="text" name="Performancefee" required>
          <p>공연사진</p>
          <p><input type="file" name="myfile" required></p>
          <p></p><input type="submit" value="공연등록"></p>
        </div>
    </div>





  </body>

  </html>