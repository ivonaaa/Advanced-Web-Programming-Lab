<?php
$host = 'localhost';
$user = 'root';
$pass = ''; 
$db_name = 'lab2';

$backup_dir = "backup";
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0777, true);
}

$conn = new mysqli($host, $user, $pass, $db_name);
if ($conn->connect_error) {
    die("Greška pri spajanju: " . $conn->connect_error);
}

$tables = $conn->query("SHOW TABLES");
if ($tables->num_rows > 0) {
    while ($row = $tables->fetch_array()) {
        $table = $row[0];
        $timestamp = time();
        $backup_file = "$backup_dir/{$table}_{$timestamp}.txt";

        $query = "SELECT * FROM $table";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            $fp = fopen($backup_file, 'w');
            while ($data = $result->fetch_assoc()) {
                $columns = implode(", ", array_keys($data));
                $values = implode("', '", array_map('addslashes', array_values($data)));
                $sql = "INSERT INTO $table ($columns) VALUES ('$values');\n";
                fwrite($fp, $sql);
            }
            fclose($fp);
            echo "Backup spremljen: <b>$backup_file</b><br>";

            $gz_file = "$backup_file.gz";
            $fp_gz = gzopen($gz_file, 'w9');
            gzwrite($fp_gz, file_get_contents($backup_file));
            gzclose($fp_gz);

            echo "Kreiran kompresirani backup: <b>$gz_file</b><br>";
        } else {
            echo "Tablica '$table' je prazna, preskačem.<br>";
        }
    }
} else {
    echo "Baza nema tablica!";
}

$conn->close();
?>
