<?php
#ini_set('display_errors', '0');
include_once "/var/www/html/a_team/a_team4/dbproject/code/register_and_login/encrypted_password.php"; #php 5.3.6 버전 이하에서 password 암호화 위하여 사용
$userid = $_POST['userid'];
$password = $_POST['password'];
$encrypted_password = null;
$wu = 0;
$wp = 0;
$db = '
 (DESCRIPTION =
         (ADDRESS_LIST=
                 (ADDRESS = (PROTOCOL = TCP)(HOST = 203.249.87.57)(PORT = 1521))
         )
         (CONNECT_DATA =
         (SID = orcl)
         )
 )';



if (!is_null($userid)) {
  $con = oci_connect("DBA2022G4", "dbdb1234", $db);
  $sql = "SELECT userpassword FROM usertable WHERE userid='$userid'";
  $stmt = oci_parse($con, $sql);
  oci_execute($stmt);
  while ($row = oci_fetch_array($stmt, OCI_ASSOC)) {
    foreach ($row as $item) {
      $encrypted_password = $item;
    }
  }
  if (is_null($encrypted_password)) {
    $wu = 1;
  } else {
    if (password_verify($password, $encrypted_password)) {
      session_start();
      $_SESSION['userid'] = $userid;
      header('Location: login-ok.php');
    } else {
      $wp = 1;
    }
  }
  oci_free_statement($stmt);
  oci_close($con);
}
?>
<!DOCUTYPE html>
  <html>

  <head>
    <title>로그인</title>
    <link rel="stylesheet" href="/a_team/a_team4/dbproject/css/index.css">
    <link rel="stylesheet" href="/a_team/a_team4/dbproject/css/login.css">

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


    <div class="box">
      <form action="login.php" method="POST">
        <p>아이디</p>
        <p><input type="text" name="userid" placeholder="아이디" required></p>
        <p>비밀번호</p>
        <p><input type="password" name="password" placeholder="비밀번호" required></p>
        <div class="submit">
        <p><br></p>
          <p><input type="submit" value="로그인"></p>
        </div>
        <?php
        if ($wu == 1) {
          echo "<script>alert('아이디가 존재하지 않습니다.');</script>";
        }
        if ($wp == 1) {
          echo "<script>alert('비밀번호가 틀렸습니다');</script>";
        }
        ?>
      </form>
    </div>

  </body>

  </html>