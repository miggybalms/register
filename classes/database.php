<?php
class database {
    function opencon(): PDO {
        return new PDO(
            'mysql:host=localhost;dbname=dbs_db',
            'root',
            ''
        );
    }
 
    function signupUser($firstname, $lastname, $birthday, $email, $sex, $phone, $username, $password, $profile_picture_path){
        $con = $this->opencon();
        try {
            $con->beginTransaction();
           
            $stmt = $con->prepare("INSERT INTO Users (user_Fn, user_Ln, user_birthday, user_sex, user_email, user_phone, user_username, user_password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$firstname, $lastname, $birthday, $sex, $email, $phone, $username, $password]);
 
            $userId = $con->lastInsertId();
 
            $stmt = $con->prepare("INSERT INTO users_pictures (user_id, user_pic_url) VALUES (?, ?)");
            $stmt->execute([$userId, $profile_picture_path]);
 
            $con->commit();
            return $userId;
        } catch (PDOException $e) {
            $con->rollBack();
            return false;
        }
    }
 
    function insertAddress($userID, $street, $barangay, $city, $province){
        $con = $this->opencon();
        try {
            $con->beginTransaction();
 
            $stmt = $con->prepare("INSERT INTO Address (ba_street, ba_barangay, ba_city, ba_province) VALUES (?, ?, ?, ?)");
            $stmt->execute([$street, $barangay, $city, $province]);
 
            $addressId = $con->lastInsertId();
 
            $stmt = $con->prepare("INSERT INTO Users_Address (user_id, address_id) VALUES (?, ?)");
            $stmt->execute([$userID, $addressId]);
 
            $con->commit();
            return true;
        } catch (PDOException $e) {
            $con->rollBack();
            return false;
        }
    }
        function loginUser($username, $password) {
 
        $con = $this->opencon();
        $stmt = $con->prepare("SELECT * FROM users WHERE user_username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($user && password_verify($password, $user['user_password'])){
            return $user;
        } else {
            return false;
        }
    }

        function saveAuthor($firstName, $lastName, $birthDate, $nationality) {
            
        $con = $this->opencon();
         $stmt = $con->prepare("SELECT * FROM authors (author_FN, author_LN, author_birthday, author_nat) VALUES (?, ?, ?, ?)");
         $stmt->execute([$author]);
          $user = $stmt->fetch(PDO::FETCH_ASSOC);
          return $user;
                 } else {
                    return false;
                 }
    

}
?>