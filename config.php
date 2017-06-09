<?php

require_once 'congif_param.php';

$conn = new mysqli($host, $db_user, $db_password, $db_name);
if ($conn->connect_error) {
    echo 'Błąd połączenia.' . $conn->connect_error . '<br/>';
} else {
    echo 'Połączenie działa.<br/>';
}

