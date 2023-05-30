<?php
// Original string value works to encrypt the value  
$original_string = "Welcome to JavaTpoint learners \n";  
// Print the original input string  
echo "Original String: " .$original_string;  
// Store the cipher method   
$ciphering_value = "AES-128-CTR";   
// Store the encryption key  
$encryption_key = "JavaTpoint";  
// Use openssl_encrypt() function   
$encryption_value = openssl_encrypt($original_string, $ciphering_value, $encryption_key);   
// Display the encrypted input string data  
echo "<br> <br> Encrypted Input String: " . $encryption_value  . "\n";  
$decryption_key = "JavaTpoint";  
// Use openssl_decrypt() function to decrypt the data  
$decryption_value = openssl_decrypt($encryption_value, $ciphering_value, $decryption_key);   
// Display the decrypted string as an original data  
echo "<br> <br> Decrypted Input String: " .$decryption_value. "\n";  

// -----------------------------------
     //encryption start
     $OhostName = $row['hostName'];
     $OuserName = $row['userName'];
     $OdbPassWord = $row['databasePassword'];

     // Store the cipher method   
     $ciphering_value = "AES-128-CTR";
     // Store the encryption key  
     $encryption_key1 = "hostName";   
     $encryption_key2 = "userName";   
     $encryption_key3 = "dbPassWord";
     // Use openssl_encrypt() function   
     $EhostName = openssl_encrypt($OhostName, $ciphering_value, $encryption_key1);
     $EuserName = openssl_encrypt($OuserName, $ciphering_value, $encryption_key2);
     $EdbPassWord = openssl_encrypt($OdbPassWord, $ciphering_value, $encryption_key3);
     echo "org ".$OhostName." ".$OuserName." ".$OdbPassWord."enc".$EhostName." ".$EuserName." ".$EdbPassWord;   


?>