<?php
#warning메세지 제거
ini_set('display_errors', '0');
#세션 스타트및 세션값 체크 구문 
session_start();
#세션 유저 아이디 체크 코딩시 검사를 위해 넣어둠
#print_r($_SESSION);
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
        $usersex = $row['3'];
        $userbirth = $row['4'];
        $userphonenumber = $row['5'];

    }

} else {
    echo "<script>alert('로그인이 필요합니다');</script>";
    echo "<script type='text/javascript'>
    location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/index.php'
    </script>";
}



$currentpassword = $_POST['current_password'];
$changedpassword = $_POST['changed_password'];
$changedpassword_confirm = $_POST['changed_password_confirm'];
$changedusername = $_POST['username'];
$changedusersex = $_POST['usersex'];
$changeduserbirth = $_POST['userbirth'];
$changeduserphonenumber = $_POST['userphonenumber'];
$checkcounter = 0;
if (!empty($currentpassword)) {
    if (password_verify($currentpassword, $encrypted_password)) {
        if (!empty($changedpassword)) {
            if ($changedpassword == $changedpassword_confirm) {
                $changed_encrypted_password = password_hash($changedpassword, PASSWORD_DEFAULT);
                $sql_changed_user = "UPDATE usertable SET userpassword='" . $changed_encrypted_password . "',username='" . $changedusername . "',usersex='" . $changedusersex . "'
                ,userbirth=TO_DATE('$changeduserbirth', 'YYYY-MM-DD'),userphonenumber='" . $changeduserphonenumber . "' WHERE userid='" . $userid . "'";
                oci_execute(oci_parse($con, $sql_changed_user));
                oci_close($con);
                echo "<script>alert('회원정보가 수정 되었습니다');</script>";
                echo "<script type='text/javascript'>
                location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/register_and_login/mypage.php' 
                </script>";
            } else {
                echo "<script>alert('변경될 비밀번호 확인값이 누락됬거나 다릅니다.');</script>";
            }

        } else {
            if (!empty($changedpassword_confirm)) {
                echo "<script>alert('변경될 비밀번호를 입력해주세요.');</script>";
                #확인값만 있을경우
            } else {
                $sql_changed_user = "UPDATE usertable SET username='" . $changedusername . "',usersex='" . $changedusersex . "'
                ,userbirth=TO_DATE('$changeduserbirth', 'YYYY-MM-DD'),userphonenumber='" . $changeduserphonenumber . "' WHERE userid='" . $userid . "'";
                oci_execute(oci_parse($con, $sql_changed_user));
                oci_close($con);
                echo "<script>alert('회원정보가 수정 되었습니다');</script>";
                echo "<script type='text/javascript'>
                location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/register_and_login/mypage.php' 
                </script>";

            }

        }



    }
}
?>
<!DOCUTYPE html>
    <html>

    <head>
        <title>회원정보 수정</title>
        </<head>
        <link rel="stylesheet" href="/a_team/a_team4/dbproject/css/index.css">
        <link rel="stylesheet" href="/a_team/a_team4/dbproject/css/changemember.css">

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

        <div class="change member">
            <h1>회원 정보 수정</h1>
            <form action="changemember.php" method="POST">
                <div class="data">
                    <p>아이디</p>
                    <?php echo '<input type="text" name="userid" value=' . $userid . ' readonly>'; ?>
                    <p>현재 비밀번호</p>
                    <?php echo '<input type="password" name="current_password" placeholder="현재 비밀번호" required>'; ?>
                    <p>변경할 비밀번호</p>
                    <?php echo '<input type="password" name="changed_password" placeholder="변경할 비밀번호" >'; ?>
                    <p>변경할 비밀번호 확인</p>
                    <?php echo '<input type="password" name="changed_password_confirm" placeholder="변경할 비밀번호 확인" >'; ?>
                    <p>이름</p>
                    <?php echo '<input type="text" name="username" value=' . $username . ' required>'; ?>
                    <div class="gender">
                        <p>성별</p>
                        <div>
                            <label>
                                <?php
                                if ($usersex == 'm') {
                                    echo '<input type="radio" name="usersex" value="m" checked>남';
                                    echo '<input type="radio" name="usersex" value="f">여';
                                } else {
                                    echo '<input type="radio" name="usersex" value="m">남';
                                    echo '<input type="radio" name="usersex" value="f" checked>여';
                                } ?>
                            </label>


                        </div>
                    </div>
                    <p>생년월일</p>
                    <?php
                    $userbirth = strtotime($userbirth);
                    $userbirth = date(" Y-m-d", $userbirth) ?>
                    <?php echo '<input type="date" name="userbirth" value=' . $userbirth . ' required>' ?>
                    <p>전화번호</p>
                    <?php echo '<input type="text" name="userphonenumber" value=' . $userphonenumber . ' required>' ?>
                    <p></p><input type="submit" value="회원 정보 수정"></p>
                </div>
        </div>
    </body>

    </html>