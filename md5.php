
<?php

echo "Clave: faz";

echo "<br>";

echo md5(faz);

echo "<br>";

echo "<br>";

echo "MD5: 4ede7e9c86da1e61a2ac91f21c7a6c13";

echo "<br>";

//$q="4ede7e9c86da1e61a2ac91f21c7a6c13";

    $cryptKey  = '4ede7e9c86da1e61a2ac91f21c7a6c13';
    $qDecoded      = rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( $cryptKey ), base64_decode( $q ), MCRYPT_MODE_CBC, md5( md5( $cryptKey ) ) ), "\0");

echo "<br>";

echo "$qDecoded";


?>