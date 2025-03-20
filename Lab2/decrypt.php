<?php
$uploads_dir = __DIR__ . "/uploads";
$key = "tajni_kljuc";

if ($handle = opendir($uploads_dir)) {
    while (false !== ($file = readdir($handle))) {
        if (strpos($file, ".enc") !== false) {
            $file_path = "$uploads_dir/$file";
            $data = file_get_contents($file_path);
            $decrypted = openssl_decrypt($data, "AES-128-ECB", $key);
            $new_file = str_replace(".enc", "", $file);

            file_put_contents("$uploads_dir/$new_file", $decrypted);

            echo "<a href='uploads/$new_file' download>$new_file</a><br>";
        }
    }
    closedir($handle);
}
?>
