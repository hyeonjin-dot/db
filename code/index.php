<?php
#warning메세지 제거
ini_set('display_errors', '0');
#세션 스타트및 세션값 체크 구문 
session_start();
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
$i=0;
while ($row = oci_fetch_array($result, OCI_NUM)) {
  $P_key[$i] = $row[0];
  $i++;
}
$arraysize = count($P_key); 
$arraysize=$arraysize-1;
?>
<!DOCUTYPE html>
  <html>

  <head>
    <title>문화센터</title>

    <link rel="stylesheet" href="/a_team/a_team4/dbproject/css/index.css">
    <link rel="stylesheet" href="/a_team/a_team4/dbproject/css/slider.css">
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
    <div class="text">
      <h1>현재 진행중인 공연 </h1>
    </div>
    <div class="slider">
      <input type="radio" name="slide" id="slide1" checked>
      <input type="radio" name="slide" id="slide2">
      <input type="radio" name="slide" id="slide3">
      <input type="radio" name="slide" id="slide4">
      <input type="radio" name="slide" id="slide5">
      <input type="radio" name="slide" id="slide6">
      <?php
      echo ('
      <ul id="imgholder" class="imgs">
        <li> <a href="/a_team/a_team4/dbproject/code/print/performlist.php">
            <img alt="" src="/a_team/a_team4/dbproject/img/'.$P_key[$arraysize].'.png" width="500" height="700" />
          </a></li>
        <li><a href="/a_team/a_team4/dbproject/code/print/performlist.php">
            <img alt="" src="/a_team/a_team4/dbproject/img/'.$P_key[$arraysize-1].'.png" width="500" height="700" />
          </a></li>
        <li> <a href="/a_team/a_team4/dbproject/code/print/performlist.php">
            <img alt="" src="/a_team/a_team4/dbproject/img/'.$P_key[$arraysize-2].'.png" width="500" height="700" />
          </a></li>
        <li> <a href="/a_team/a_team4/dbproject/code/print/performlist.php">
            <img alt="" src="/a_team/a_team4/dbproject/img/'.$P_key[$arraysize-3].'.png" width="500" height="700" />
          </a></li>
        <li> <a href="/a_team/a_team4/dbproject/code/print/performlist.php">
            <img alt="" src="/a_team/a_team4/dbproject/img/'.$P_key[$arraysize-4].'.png" width="500" height="700" />
          </a></li>
        <li> <a href="/a_team/a_team4/dbproject/code/print/performlist.php">
            <img alt="" src="/a_team/a_team4/dbproject/img/'.$P_key[$arraysize-5].'.png" width="500" height="700" />
          </a></li>
      </ul>
      ')
        ?>
      <div class="bullets">
        <label for="slide1">&nbsp;</label>
        <label for="slide2">&nbsp;</label>
        <label for="slide3">&nbsp;</label>
        <label for="slide4">&nbsp;</label>
        <label for="slide5">&nbsp;</label>
        <label for="slide6">&nbsp;</label>
      </div>
    </div>





  </body>

  </html>