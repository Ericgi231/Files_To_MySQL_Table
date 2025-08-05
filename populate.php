<?php
    error_reporting(error_level: 0);

    echo "Server:\n";
    $servername = trim(fgets(STDIN));
    echo "User:\n";
    $username = trim(fgets(STDIN));
    echo "Password:\n";
    $password = trim(fgets(STDIN));
    echo "Database:\n";
    $dbname = trim(fgets(STDIN));
    echo "Table:\n";
    $tablename = trim(fgets(STDIN));
    echo "Directory:\n";
    $directory = trim(fgets(STDIN));
    if (substr($directory, -1) != "/") {
        $directory .= "/";
    }

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        echo "Failed to connect to " . $servername;
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "CREATE TABLE IF NOT EXISTS $tablename (name VARCHAR(300) NOT NULL PRIMARY KEY, file_type VARCHAR(30) NOT NULL, created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP);";
    echo "$sql\n";
    if ($conn->query($sql) === FALSE) {
        echo "Error: $sql \n $conn->error \n";
    } 

    foreach (glob("$directory*") as $filename) {
        $file = trim(end(explode('/', $filename)));
        $name = trim(explode('.', $file)[0]);
        $file_type = trim(end(explode('.', $file)));
        $created = filemtime($filename);
        $sql = "INSERT INTO files (name, file_type, created) VALUES ('$name', '$file_type', FROM_UNIXTIME('$created')) ON DUPLICATE KEY UPDATE name=name";
        echo "$sql\n";

        if ($conn->query($sql) === FALSE) {
            echo "Error: $sql \n $conn->error \n";
        } 
    }

    $conn->close();
?>
