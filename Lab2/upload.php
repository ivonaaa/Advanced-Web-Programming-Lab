<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $uploads_dir = __DIR__ . "/uploads";
    if (!is_dir($uploads_dir)) mkdir($uploads_dir, 0777, true);

    $file_path = $uploads_dir . "/" . basename($file["name"]);
    move_uploaded_file($file["tmp_name"], $file_path);

    $key = "tajni_kljuc";
    $data = file_get_contents($file_path);
    $encrypted = openssl_encrypt($data, "AES-128-ECB", $key);
    
    file_put_contents($file_path . ".enc", $encrypted);
    unlink($file_path); 
    
    echo "<p>Datoteka je uspješno kriptirana!</p>";
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="file">
    <button type="submit">Upload</button>
</form>
