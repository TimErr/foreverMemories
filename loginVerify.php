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
//creates username and password in DB
// if ($passwordRepeat === $password) {
//     $pw = password_hash($password, PASSWORD_DEFAULT);
//
//     $db = openDatabase();
//
//     $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
//     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//
//     try {
//         $query = $db->prepare($sql);
//         $query->bindParam(':username', $username);
//         $query->bindParam(':password', $pw);
//         $query->execute();
//         $userId = $db->lastInsertId();
//         viewMemories($userId);
//     } catch (Exception $exception) {
//         echo "{$exception->getMessage()}<br/>";
//     }
//
//
//
// } else {
//     echo("Error creating account passwords do not match\n");
//     echo("<a href='index.html'>Try again</a>");
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
        $passwordHash = $result[0]['password'];
        $verifiedPassword = password_verify($password, $passwordHash);
        if ($verifiedPassword) {
            $verifyUser = $result[0]['username'];
            if($username == $verifyUser){
                $verifiedUserId = $result[0]['userID'];
                viewMemories($verifiedUserId);
            }
        } else {
            echo ("password does not match");
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
