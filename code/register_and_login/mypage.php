<?php
#warning메세지 제거
ini_set('display_errors', '0');
#세션 스타트및 세션값 체크 구문 
session_start();
#세션 유저 아이디 체크 코딩시 검사를 위해 넣어둠
#print_r($_SESSION);
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
        $username = $row['2'];
        $usersex = $row['3'];
        $userbirth = $row['4'];
        $userphonenumber = $row['5'];
        $performanceKey = $row['6'];

    }
    $sql_2 = "SELECT PerformanceName FROM Performance WHERE PerformanceKey =$performanceKey";
    $stmt2 = oci_parse($con, $sql_2);
    oci_execute($stmt2);
    while (($row2 = oci_fetch_array($stmt2, OCI_NUM))) {

        $PerformanceName = $row2[0];

    }

    oci_close($con);
} else {
    echo "<script>alert('로그인이 필요합니다');</script>";
    echo "<script type='text/javascript'>
    location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/index.php'
    </script>";
}

?>
<!DOCUTYPE html>
    <html>

    <head>
        <title>마이페이지</title>
        </<head>
        <link rel="stylesheet" href="/a_team/a_team4/dbproject/css/index.css">
        <link rel="stylesheet" href="/a_team/a_team4/dbproject/css/mypage.css">

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

        <div class="mypage">
            <p>아이디
                <?php echo $userid; ?>
            </p>
            <p>이름
                <?php echo $username; ?>
            </p>
            <p>성별
                <?php
                if ($usersex == 'm') {
                    echo '남자';
                } else {
                    echo '여자';
                } ?>
            </p>
            <p>생일
                <?php
                $userbirth = strtotime($userbirth);
                echo date(" Y-m-d", $userbirth) ?>
            </p>
            <p>전화번호
                <?php echo $userphonenumber; ?>
            </p>
            <p>예약된 공연 목록
                <?php echo  $PerformanceName; ?>
            </p>
            <p>
                <a href="/a_team/a_team4/dbproject/code/register_and_login/changemember.php">회원정보 수정</a>
            </p>
            <p>
                <a href="/a_team/a_team4/dbproject/code/register_and_login/deletemember.php">회원 탈퇴</a>
            </p>
        </div>



    </html>