<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Your Memories</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/css/bootstrap.min.css" integrity="sha384-SI27wrMjH3ZZ89r4o+fGIJtnzkAnFs3E4qz9DIYioCQ5l9Rd/7UAa8DHcaL8jkWt" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.0/js/bootstrap.min.js" integrity="sha384-3qaqj0lc6sV/qpzrc1N5DC6i1VRn/HyX4qdPaiEFbn54VjQBEU341pvjz7Dv3n6P" crossorigin="anonymous"></script>
    </head>
    <body>
        <nav class="navbar navbar-inverse">
          <div class="container-fluid">
            <div class="navbar-header">
              <a class="navbar-brand" href="index.html">Forever Memories</a>
            </div>

            <ul class="nav navbar-nav">
              <li><a href="#">Add Memory</a></li>
              <li><a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a></li>
            </ul>
          </div>
        </nav>
        <table class="table table-dark table-striped table-bordered">
            <tr>

                <th scope="col">Date</th>
                <th scope="col">Place</th>
                <th scope="col">Description</th>


            </tr>

        <?php
        session_start();
        $userID = $_SESSION['id'];

        $db = new PDO("mysql:host=localhost;dbname=foreverMemories", "student", "student");
        $sql = "SELECT b.username, a.place, a.date, a.description FROM users b INNER JOIN memories a USING (userID) WHERE userID= :userID";
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $query = $db->prepare($sql);
            $query->bindParam(':userID', $userID);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($results as $row) {
                echo "<tr><td>" . $row['date'] . "</td><td>"
                    . $row['place'] ."</td><td>"
                    . $row['description'] . "</td></tr>";

            }

        } catch (Exception $e) {}
         ?>
