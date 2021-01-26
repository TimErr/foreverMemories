<?php
//$passwordRepeat = $_POST['passwordRepeat'];
$username = $_POST['username'];
$password = $_POST['password'];

function openDatabase() {
    $db = new PDO("mysql:host=localhost;dbname=foreverMemories", "student", "student");
    return $db;
}

//create dummy user
    // $db = openDatabase();
    // $pw = password_hash('4567', PASSWORD_DEFAULT);
    // $sql = "INSERT INTO users (username, password) VALUES ('Timmy', :password)";
    //
    // try {
    //     $query = $db->prepare($sql);
    //     $query->bindParam(':password' ,$pw);
    //     $query->execute();
    // } catch (Exception $exception) {
    //     echo "{$exception->getMessage()}<br/>";
    // }


//verify login info against DB
    $pw = password_hash($password, PASSWORD_DEFAULT);

    $db = openDatabase();

    $sql = "SELECT * FROM users WHERE username= :username";
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        $query = $db->prepare($sql);
        $query->bindParam(':username', $username);
        //$query->bindParam(':password', $pw);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)) {
            echo ("No user exists please register");
            echo '<div>
                <button><a href="register.html">Register</a></button>';
        } else {
            $passwordHash = $result[0]['password'];
            $verifiedPassword = password_verify($password, $passwordHash);
            if ($verifiedPassword) {
                $verifyUser = $result[0]['username'];
                if($username == $verifyUser){
                    $verifiedUserId = $result[0]['userID'];
                    viewMemories($verifiedUserId);
                }
            } else {
                echo ("username or password does not match");
                echo '<div>
                        <a href="login.html">Try Again</a>
                      </div>';

            }
        }
    } catch (Exception $exception) {
        echo "{$exception->getMessage()}<br/>";
    }

function viewMemories($userId) {

     $db = openDatabase();
     $sql = "SELECT userID FROM users";
     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        $query = $db->prepare($sql);
        $query->bindParam(':userID', $userId);
        $query->execute();
        header('Location: viewMemories.php?userId='.$userId);
     } catch (Exception $exception) {
         echo "{$exception->getMessage()}<br/>";
     }
}
 ?>
