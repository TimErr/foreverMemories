<?php
require_once('Memory.php');
require_once('IMemoryManager.php');



    class MemoryManager implements IMemoryManager {

        public function openDatabase(){
            $db = new PDO("mysql:host=localhost;dbname=foreverMemories", "student", "student");
            return $db;
        }

        public function create($place, $date, $description, $picture, $userId) {

            $ret_val = null;

            $db = $this->openDatabase();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "INSERT INTO memories (place, date, description, picture, user_id) VALUES (:place, :date, :description, :picture, :userId)";

            try
            {
                $query = $db->prepare($sql);

                $query->execute(array(":place"=> $place, ":date"=> $date, ":description"=> $description, ":picture"=> $picture, ":userId"=> $userId));

                $ret_val = $db->lastInsertId();


            } catch (Exception $e) {
                echo "{$e->getMessage()}<br/>\n";
            }return $ret_val;
        }

        public function read($id) {
            $ret_val = null;

            $db = $this->openDatabase();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * from memories WHERE id= :id";

            try
            {
                $query = $db->prepare($sql);
                $query->bindParam(":id" , $id);
                $query->execute();

                $results = $query->fetchAll(PDO::FETCH_ASSOC);
                $ret_val = json_encode($results, JSON_PRETTY_PRINT);

            } catch (Exception $e) {
                echo "{$e->getMessage()}<br/>\n";
            }
            return $ret_val;
        }

        public function readAll() {

            $ret_val = null;

            $db = $this->openDatabase();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "SELECT * from memories";

            try
            {
                $query = $db->prepare($sql);
                $query->execute();

                $results = $query->fetchAll(PDO::FETCH_ASSOC);
                $ret_val = json_encode($results, JSON_PRETTY_PRINT);

            } catch (Exception $e) {
                echo "{$e->getMessage()}<br/>\n";
            }


            return $ret_val;

        }

        public function update($id, $place, $date, $description, $picture) {


            $ret_val = null;

            $db = $this->openDatabase();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "UPDATE memories SET description= :description, place= :place, date= :date, picture= :picture WHERE id= :id";

            try
            {
                $query = $db->prepare($sql);

                $query->execute(array(":place"=> $place, ":date"=> $date,":description"=> $desc, ":picture"=> $picture, ":id"=> $id));

                $ret_val = $query->rowCount();


            } catch (Exception $e) {
                echo "{$e->getMessage()}<br/>\n";
            }return $ret_val;
        }

        public function delete($id) {
            $ret_val = null;

            $db = $this->openDatabase();
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "DELETE FROM memories WHERE memoryID= :id";

            try
            {
                $query = $db->prepare($sql);
                $query->bindParam(":id" , $id);
                $query->execute();


                $ret_val = $query->rowCount();

            } catch (Exception $e) {
                echo "{$e->getMessage()}<br/>\n";
            }


            return $ret_val;

            // $db = $this->openDatabase();
            //
            // $sql = "DELETE FROM task WHERE id=:id";
            //
			// $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //
			// try
			// {
            //
			// $query = $db->prepare($sql);
			// $query->bindParam(':id', $id);
			// $query->execute();
			// $rows_affected = $query->rowCount();
			// }
			// catch (Exception $ex)
			// {
			// 	echo "{$ex->getMessage()}<br/>";
            //
			// }
			// return $rows_affected; // returns the number of rows affected by the delete


        }

    }

 ?>
