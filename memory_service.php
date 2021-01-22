<?php
    require_once('MemoryManager.php');

    $http_verb = $_SERVER['REQUEST_METHOD'];
    $memory_manager = new MemoryManager();


    function verifyUser($username) {
        $db = new PDO("mysql:host=localhost;dbname=foreverMemories", "student", "student");

        $sql = "SELECT * FROM users WHERE username = :username";
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':username', $username);

            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_ASSOC);
            $verifyUsername = $results[0]['username'];


            if ($username === $verifyUsername ) {
                $verifiedUserId = $results[0]['userID'];

                return $verifiedUserId;
            } else {
                echo("No account found please <a href = 'index.html'>try again</a>");
            }
        } catch (Exception $exception) {
            echo "{$exception->getMessage()}<br/>";
        }
    }

    function getUserMemories($userMemoriesVerb, $verifiedUserId) {
        $db = openDatabase();

        switch($userMemoriesVerb)
        {
            case "createMemories":
            $sql = "SELECT * FROM memories WHERE userID = :userid";
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                        $query = $db->prepare($sql);
                        $query->bindParam(':userid', $verifiedUserId);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_ASSOC);
                        $createsValue =  $results[0]['creates'];
                        return $createsValue;
            } catch (Exception $e) {
            }
            break;

            case "readMemories":
            $sql = "SELECT * FROM memories WHERE userID = :userid";
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                $query = $db->prepare($sql);
                $query->bindParam(':userid', $verifiedUserId);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_ASSOC);

                return $results;

            } catch (Exception $e) {
            }
            break;

            case "readAllMemories":
            $sql = "SELECT * FROM memories WHERE userID = :userid";
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                $query = $db->prepare($sql);
                $query->bindParam(':userid', $verifiedUserId);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_ASSOC);
                //$readsValue = $results[0]['reads_all'];
                return $results;

            } catch (Exception $e) {
            }
            break;

            case "updateMemories":
            $sql = "SELECT * FROM memories WHERE userID = :userid";
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                $query = $db->prepare($sql);
                $query->bindParam(':userid', $verifiedUserId);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_ASSOC);
                //$updatesValue = $results[0]['updates'];
                return $results;
            } catch (Exception $e) {
            }
            break;

            case "deleteMemories":
            $sql = "SELECT * FROM memories WHERE userID = :userid";
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            try {
                $query = $db->prepare($sql);
                $query->bindParam(':userid', $verifiedUserId);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_ASSOC);
                //$deletesValue = $results[0]['deletes'];
                return $results;
            } catch (Exception $e) {
            }
            break;

            default:
    			throw new Exception("Unable to check for user memories.");
    			break;
        }
    }

    switch($http_verb)
    {
        case "POST":
            //create
            if (isset($_POST['description']))
            {
                $db = openDatabase();
                $username = $_POST['username'];
                $verifiedUserId = verifyUser($username);

                echo $task_manager->create($_POST['description'], $verifiedUserId);

                getUserMemories('createMemories', $verifiedUserId);
                // $createsValue = $createsValue + 1;
                //
                // $sql = "UPDATE memories SET `creates`=:createValue WHERE `user_id`=:userid";
                // $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //
                // try {
                //     $query = $db->prepare($sql);
                //     $query->bindParam(':createValue', $createsValue);
                //     $query->bindParam(':userid', $verifiedUserId);
                //     $query->execute();
                //
                // } catch (Exception $e) {}
            }
            else
            {
                throw new Exception("Invalid HTTP POST request parameters");
            }
            break;
        case "GET":
            //read
            header("Content-Type: application/json");
            if (isset($_GET['id']))
            {
                $db = openDatabase();
                $username = $_GET['username'];
                $verifiedUserId = verifyUser($username);
                echo $memory_manager->read($_GET['id']);
                getUserMemories('readMemories', $verifiedUserId);

                //$readsValue = $readsValue + 1;
                //$sql = "UPDATE memories SET `reads`=:readsValue WHERE `user_id`=:userid";
                //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // try {
                //     $query = $db->prepare($sql);
                //     //$query->bindParam(':readsValue', $readsValue);
                //     $query->bindParam(':userid', $verifiedUserId);
                //     $query->execute();
                //
                // } catch (Exception $e) {}
            }
            else
            {
                echo $memory_manager->readAll();

                $db = openDatabase();
                $username = $_GET['username'];
                $verifiedUserId = verifyUser($username);

                getUserMemories('readAllMemories', $verifiedUserId);
                //$readsValue = $readsValue + 1;
                // $sql = "UPDATE user_stats SET `reads_all`=:readsValue WHERE `user_id`=:userid";
                // $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //
                // try {
                //     $query = $db->prepare($sql);
                //     $query->bindParam(':readsValue', $readsValue);
                //     $query->bindParam(':userid', $verifiedUserId);
                //     $query->execute();
                //
                // } catch (Exception $e) {}
            }
            break;
        case "PUT":
            //update
            parse_str(file_get_contents("php://input"),$update_vars);

            if(isset($update_vars['id']) && isset($update_vars['description']))
            {
                echo $memory_manager->update($update_vars['id'], $update_vars['description']);

                $db = openDatabase();
                $username = $update_vars['username'];
                $verifiedUserId = verifyUser($username);

                getUserMemories('updateMemories', $verifiedUserId);
                //$updatesValue = $updatesValue + 1;
                // $sql = "UPDATE memories SET `description`=:description WHERE `userID`=:userid";
                // $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //
                // try {
                //     $query = $db->prepare($sql);
                //     $query->bindParam(':description', $description);
                //     $query->bindParam(':userid', $verifiedUserId);
                //     $query->execute();
                //
                // } catch (Exception $e) {}
            }
            else
            {
                throw new Exception("Invaled HTTP UPDATE request parameters");
            }
            break;
        case "DELETE":
            //delete
            parse_str(file_get_contents("php://input"), $delete_vars);

			if (isset($delete_vars['id']))
			{
				echo $memory_manager->delete($delete_vars['id']);
                $db = openDatabase();
                $username = $delete_vars['username'];
                $verifiedUserId = verifyUser($username);

                getUserMemories('deleteMemories', $verifiedUserId);
                //$deletesValue = $deletesValue + 1;
                // $sql = "UPDATE memories SET `deletes`=:deletesValue WHERE `user_id`=:userid";
                // $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                //
                // try {
                //     $query = $db->prepare($sql);
                //     $query->bindParam(':deletesValue', $deletesValue);
                //     $query->bindParam(':userid', $verifiedUserId);
                //     $query->execute();
                //
                // } catch (Exception $e) {}
			}
			else
			{
				throw new Exception("Invalid HTTP DELETE request parameters.");
			}
			break;

		default:

			throw new Exception("Unsupported HTTP request.");
			break;
    }




function openDatabase() {
    $db = new PDO("mysql:host=localhost;dbname=foreverMemories", "student", "student");
    return $db;
}
 ?>
