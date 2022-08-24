<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$errors = [];
$data = [];

if ($_GET['request'] == 'save') {
    if (empty($_GET['name'])) {
        $errors['name'] = "Name is required";
    }
    if (empty($_GET['email'])) {
        $errors['email'] = "Email is required";
    }
    if (empty($_GET['subject'])) {
        $errors['subject'] = "Subject is required";
    }
    if (empty($_GET['message'])) {
        $errors['message'] = "Message is required";
    }

    if (!empty($errors)) {
        $data["status"] = false;
        $data["message"] = $errors;
    } else {
        $database = "wemabank";
        $username = "root";
        $password = "";
        $host = "localhost";

        $connection = mysqli_connect($host, $username, $password, $database);
        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        } else {
            $name = $_GET['name'];
            $email = $_GET['email'];
            $subject = $_GET['subject'];
            $message = $_GET['message'];

            $query = "INSERT INTO `emails`(`name`, `email`, `subject`, `message`) VALUES ('$name','$email','$subject','$message')";
            $result = mysqli_query($connection, $query);

            if ($result) {
                $data["status"] = true;
                $data["message"] = "Data Saved Successfully";
            } else {
                $data["status"] = false;
                $data["message"] = mysqli_error($connection);
            }
        }
    }
} else if ($_GET['request'] == 'read') {
    $database = "wemabank";
    $username = "root";
    $password = "";
    $host = "localhost";

    $connection = mysqli_connect($host, $username, $password, $database);
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        $query = 'SELECT * FROM `emails`';
        $result = mysqli_query($connection, $query);
        $records = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $records[]= $row;
            }
            $data["status"] = true;
            $data["message"] = $records;
        }
    }
} else if ($_GET['request'] == 'login') { 
    $database = "wemabank";
    $username = "root";
    $password = "";
    $host = "localhost";

    $connection = mysqli_connect($host, $username, $password, $database);
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        $email = $_GET['email'];
        $password = $_GET['password'];
        $query = 'SELECT `user_id`, `user_email`, `user_password` FROM `users` WHERE `user_email` = "'.$email.'" AND `user_password` = "'.md5($password).'"';
        
        $result = mysqli_query($connection, $query);
        if (mysqli_num_rows($result) > 0) {
            $data["status"] = true;
            $data["message"] = "User Exist";
        } else {
            $data["status"] = false;
            $data["message"] = "Invalid email or password";
        }
    }
}

echo json_encode($data);
exit();
