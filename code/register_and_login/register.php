<?php
#warning메세지 제거
ini_set('display_errors', '0');
#세션 스타트및 세션값 체크 구문 
session_start();
#세션 유저 아이디 체크 코딩시 검사를 위해 혹시 몰라 넣어둠 오류 발생시 체크 해볼것print_r($_SESSION);
include_once "/var/www/html/a_team/a_team4/dbproject/code/register_and_login/encrypted_password.php"; #php 5.3.6 버전 이하에서 password 암호화 위하여 사용
$userid = $_POST['userid'];
$password = $_POST['password'];
$password_confirm = $_POST['password_confirm'];
$username = $_POST['username'];
$usersex = $_POST['usersex'];
$userbirth = $_POST['userbirth'];
$userphonenumber = $_POST['userphonenumber'];
$checkcounter = 0;
#wronguserid and wrongpassword
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
    $sql = "SELECT userid FROM usertable WHERE userid='$userid'";
    #sql분석 및 실행준비 구문
    $stmt = oci_parse($con, $sql);
    oci_execute($stmt);
    while ($row = oci_fetch_array($stmt, OCI_ASSOC)) {
        foreach ($row as $item) {
            $userid_exist = $item;
            if ($userid_exist == $userid) {
                $checkcounter++;
            }
        }

    }
    if ($checkcounter > 0) {
        $wu = 1;
    } elseif ($password != $password_confirm) {
        $wp = 1;
    } else {
        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);
        $sql_add_user = "INSERT INTO UserTable (UserID,UserPassword,UserName,UserSex,UserBirth,UserPhoneNumber,PerformanceKey)
        VALUES ('$userid', '$encrypted_password','$username','$usersex',TO_DATE('$userbirth', 'YYYY-MM-DD'),'$userphonenumber',NULL)";
        oci_execute(oci_parse($con, $sql_add_user));
        echo "<script>alert('회원가입이 완료 되었습니다 로그인을 진행해주세요');</script>";
        echo "<script type='text/javascript'>
        location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/register_and_login/login.php' 
        </script>";
    }
    oci_close($con);
}
?>
<!DOCUTYPE html>
    <html>

    <head>
        <title>회원가입</title>
        </<head>
        <link rel="stylesheet" href="/a_team/a_team4/dbproject/css/index.css">
        <link rel="stylesheet" href="/a_team/a_team4/dbproject/css/register.css">

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
        <div class="signUp">
            <h1>회원 가입</h1>
            <form action="register.php" method="POST">
                <div class="data">
                    <p>아이디</p>
                    <input type="text" name="userid" placeholder="ID" required>
                    <p>비밀번호</p>
                    <input type="password" name="password" placeholder="비밀번호" required>
                    <p>비밀번호 확인</p>
                    <input type="password" name="password_confirm" placeholder="비밀번호 확인" required>
                    <p>이름</p>
                    <input type="text" name="username" placeholder="이름" required>
                    <div class="gender">
                        <p>성별</p>
                        <div>
                            <label><input type="radio" name="usersex" value="m" checked>남
                                <input type="radio" name="usersex" value="f">여</label>
                        </div>
                    </div>
                    <p>생년월일</p>
                    <input type="date" name="userbirth" required>
                    <p>전화번호</p>
                    <input type="text" name="userphonenumber" placeholder="010-0000-0000" required>
                    <p></p><input type="submit" value="회원 가입"></p>
                </div>
        </div>
        <?php
        if ($wu == 1) {
            echo "<script>alert('이미 사용중인 아이디입니다. 다시 작성해주세요');</script>";
        }
        if ($wp == 1) {
            echo "<script>alert('비밀번호가 일치하지 않습니다. 다시 작성해주세요');</script>";
        }
        ?>
        </form>

    </html>