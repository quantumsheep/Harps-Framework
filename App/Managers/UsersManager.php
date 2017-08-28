<?php
namespace App\Managers;

class UsersManager
{
    public function getUser(mysqli $conn, string $username) {
        $result = $conn->query("select * from users WHERE username='" . $username . "'");

        $final = array();

        if($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $final[] = $row;
            }
        }

        return $final;
    }

    public function newUser(mysqli $conn, string $username, string $email, string $password) {
        
    }
}