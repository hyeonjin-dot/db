<?php
#warning메세지 제거
ini_set('display_errors', '0');
#세션 스타트및 세션값 체크 구문 
session_start();
#세션 유저 아이디 체크 코딩시 검사를 위해 혹시 몰라 넣어둠 오류 발생시 체크 해볼것print_r($_SESSION);
include_once "/var/www/html/a_team/a_team4/dbproject/code/register_and_login/encrypted_password.php"; #php 5.3.6 버전 이하에서 password 암호화 위하여 사용
$userid = $_SESSION['userid'];
$db = '
 (DESCRIPTION =
         (ADDRESS_LIST=
                 (ADDRESS = (PROTOCOL = TCP)(HOST = 203.249.87.57)(PORT = 1521))
         )
         (CONNECT_DATA =
         (SID = orcl)
         )
 )';
if (!is_null($_SESSION['userid'])) {
    $con = oci_connect("DBA2022G4", "dbdb1234", $db);
    $sql = "SELECT * FROM usertable WHERE userid='$userid'";
    $stmt = oci_parse($con, $sql);
    oci_execute($stmt);
    while (($row = oci_fetch_array($stmt, OCI_NUM))) {
        $encrypted_password = $row['1'];
        $username = $row['2'];
    }
} else {
    echo "<script>alert('로그인이 필요합니다');</script>";
    echo "<script type='text/javascript'>
    location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/index.php'
    </script>";
}
$wp = 0;
$password = $_POST['password'];
if ($_SESSION['userid'] == 'Administrator') {
    echo "<script>alert('관리자 계정은 탈퇴 할 수 없습니다. DB관리자에게 문의하세요');</script>";
    echo "<script type='text/javascript'>
    location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/admin/administrator.php' 
    </script>";
}
if (!is_null($password)) {
    if (password_verify($password, $encrypted_password)) {
        $con = oci_connect("DBA2022G4", "dbdb1234", $db);
        $sql = "DELETE FROM usertable WHERE userid='" . $userid . "'";
        oci_execute(oci_parse($con, $sql));
        oci_close($con);
        echo "<script>alert('회원탈퇴가 완료되었습니다 감사합니다');</script>";
        echo "<script type='text/javascript'>
        location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/register_and_login/logout.php' 
        </script>";
    } else {
        $wp = 1;

    }
}

?>
<!DOCUTYPE html>
    <html>

    <head>
        <title>회원탈퇴</title>

        </<head>
        <link rel="stylesheet" href="/a_team/a_team4/dbproject/css/index.css">
        <link rel="stylesheet" href="/a_team/a_team4/dbproject/css/delete.css">


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

        <div class="delete member">
            <h1>회원탈퇴</h1>
            <form action="deletemember.php" method="POST">
                <div class="data">
                    <p>아이디</p>
                    <?php echo '<input type="text" name="userid" value=' . $userid . ' readonly>'; ?>
                    <p>현재 비밀번호</p>
                    <?php echo '<input type="password" name="password" placeholder="현재 비밀번호" required>'; ?>
                    <p><br></p>
                    <p></p><input type="submit" value="회원 탈퇴"></p>
                </div>


        </div>
        <?php
        if ($wp == 1) {
            echo "<script>alert('비밀번호가 틀렸습니다. 다시입력해주세요');</script>";
        } ?>



    </body>

    </html>