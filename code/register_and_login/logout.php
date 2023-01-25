<?php
#warning메세지 제거
ini_set('display_errors', '0');
#세션 종료
session_start();
session_destroy();
echo "<script>alert('로그아웃 되었습니다');</script>";
echo "<script type='text/javascript'>
location.href='http://software.hongik.ac.kr/a_team/a_team4/dbproject/code/index.php' 
</script>";
?>