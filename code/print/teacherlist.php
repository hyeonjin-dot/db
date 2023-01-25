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
$sql = "SELECT * FROM Teacher";
$result = oci_parse($con, $sql);
oci_execute($result);

?>
<!DOCTYPE html>
<html>

<head>
  <title>강사진</title>

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

  <div class="teacher">
    <h1>강사진</h1>
    <?php
    echo ("<table border='2'>");
    echo ("       
              <tr>
              <th>강사 이름</th>
              <th>남/여</th>
              <th>생년월일</th>
              <th>핸드폰 번호</th>
              </tr>
              <tr>
              ");
    while ($row = oci_fetch_array($result, OCI_NUM)) {
      $Teacherbirth = strtotime($row[3]);
      echo ("         

              <td width='100'><p align='center'>{$row['1']}</p></td>
            ");

      if ($row['2'] == 'm') {
        echo ("
              <td width='100'><p align='center'>남자</p></td>
              ");
      } else {
        echo ("
              <td width='100'><p align='center'>여자</p></td>
              ");
      }
      echo ("
              <td width='100'><p align='center'>" . date("Y-m-d", $Teacherbirth) . "</p></td>
              <td width='200'><p align='center'>{$row['4']}</p></td>
  	</tr>
                ");
    }
    echo ("</table>");
    oci_free_statement($result);
    oci_close($con);
    ?>
  </div>

</html>