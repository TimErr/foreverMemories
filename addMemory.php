<?php
session_start();

require_once "config.php";

$place = $description = $date = $fileName = "";
$place_err = $date_err = $description_err = "";
$userID = $_SESSION['id'];


$statusMsg = '';
$backlink = ' <a href="./">Go back</a>';



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //check for required data in fields, display error if empty
    if(empty(trim($_POST["place"]))){
        $place_err = "Please Enter a place you visited";
    } else {
        $place = trim($_POST["place"]);
    }


    if(empty(trim($_POST["date"]))){
        $date_err = "Please enter a date for your memory";
    } else {
        $date = trim($_POST["date"]);
    }

    if(empty(trim($_POST["description"]))) {
        $description_err = "Please enter a description of your memory";
    } else {
        $description = trim($_POST["description"]);
    }

    if(empty($place_err) && empty($date_err) && empty($description_err)) {


        $sql = "INSERT INTO memories (place, date, description, file_name, userID) VALUES (:place, :date, :description, :fileName, :userID)";

        if ($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":place", $place, PDO::PARAM_STR);
            $stmt->bindParam(":date", $date, PDO::PARAM_STR);
            $stmt->bindParam(":description", $description, PDO::PARAM_STR);
            $stmt->bindParam(":fileName", $fileName, PDO::PARAM_STR);
            $stmt->bindParam(":userID", $userID, PDO::PARAM_STR);

            if($stmt->execute()){

                $allowTypes = array('jpg','png','jpeg','gif');
                // File upload path
                $targetDir = "uploads/";
                $fileName = basename($_FILES["file"]["name"]);
                $targetFilePath = $targetDir . $fileName;
                $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);

                if (!file_exists($targetFilePath)) {
                    if(in_array($fileType, $allowTypes)){
                            // Upload file to server
                        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
                            header("location: viewMemories.php");
                        }else{
                            $statusMsg = "Sorry, there was an error uploading your file." . $backlink;
                        }
                    }else{
                        $statusMsg = "Sorry, only JPG, JPEG, PNG, GIF files are allowed to upload." . $backlink;
                    }
                }else{
                        $statusMsg = "The file <b>".$fileName. "</b> is already exist." . $backlink;
                    }
            }else{
                $statusMsg = 'Please select a file to upload.' . $backlink;
            }
        }
    }
}
?>



<!DOCTYPE html>
<html>
<head>
  <title>Upload Image</title>
</head>
<body>
    <nav class="navbar navbar-inverse">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="index.html">Forever Memories</a>
        </div>
      </div>
    </nav>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
     <div class="form-group <?php echo (!empty($place_err)) ? 'has-error' : ''; ?>">
         <label> Place you Visited</label>
         <input type="text" name="place" class="form-control" value="<?php echo $place; ?>">
         <span class="help-block"><?php echo $place_err; ?></span>
    </div>
    <div class="form-group <?php echo (!empty($date_err)) ? 'has-error' : ''; ?>">
        <label> Date</label>
        <input type="date" name="date" class="form-control" value="<?php echo $date; ?>">
        <span class="help-block"><?php echo $date_err; ?></span>
   </div>
   <div class="form-group <?php echo (!empty($description_err)) ? 'has-error' : ''; ?>">
       <label>Description of your Visit</label>
       <textarea name="description" class="form-control" value="<?php echo $description; ?>"></textarea>
       <span class="help-block"><?php echo $description_err; ?></span>
  </div>

        Select Image File to Upload:
      <input type="file" name="file" value="<?php echo $fileName?>">
      <input type="submit" name="submit" value="Upload">
  </form>
  <hr>


</body>
</html>
