<?php

session_start();

if (isset($_POST['submit'])) {

    include 'dbh-inc.php';

    $uid = mysqli_real_escape_string($conn, $_POST['uid']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);

    //Error handlers
    //Check if inputs are empty
    if (empty($uid) || empty($pass)) {
        header("Location: ../login.php?login=empty");
        exit();
    }
    else {
        $sql = "SELECT * FROM users WHERE user_uid = '$uid'";
        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);
        if ($resultCheck < 1) {
            header("Location: ../login.php?login=error");
            exit();
        }
        else {
            if ($row = mysqli_fetch_assoc($result)) {
                //De-hashing pass
                $hashedPassCheck = password_verify($pass, $row['user_pass']);
                if ($hashedPassCheck == false) {
                    header("Location: ../login.php?login=error");
                    exit();
                }
                elseif ($hashedPassCheck == true) {
                    //Login in the user here
                    $_SESSION['u_id'] = $row['user_id'];
                    $_SESSION['u_uid'] = $row['user_uid'];
                    $_SESSION['u_email'] = $row['user_email'];
                    header("Location: ../index.php?login=success");
                    exit();
                }
            }
        }
    }

}
else {
    header("Location: ../index.php?login=error");
    exit();
}
