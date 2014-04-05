<?php 

include "system/core/_functions.php";

$pass = "password";

$salt = "59fc5c88b4c4acc93a584381497c40e6";


$password = password_hash($pass, PASSWORD_BCRYPT);


var_dump($password);


var_dump(password_verify($pass, '$2y$10$pHvW/dpzhheEa2HPHGuRouP.G3b54eTHbiCGrCaEtHgfzeUTrNoRa'));