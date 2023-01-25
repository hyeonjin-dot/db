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


    <h1>
      관리자 전용 페이지 입니다.




  </body>

  </html>