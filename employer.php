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
    if (empty($_GET['companyName'])) {
        $errors['companyName'] = "Company name is required";
    }
    if (empty($_GET['companyReg'])) {
        $errors['companyReg'] = "Company Registration is required";
    }
    if (empty($_GET['password'])) {
        $errors['password'] = "Password is required";
    }

    if (!empty($errors)) {
        $data["status"] = false;
        $data["message"] = $errors;
    } else {
        $database = "customers";
        $username = "root";
        $password = "";
        $host = "localhost";

        $connection = mysqli_connect($host, $username, $password, $database);
        if (!$connection) {
            die("Connection failed: " . mysqli_connect_error());
        } else {
            $name = $_GET['name'];
            $email = $_GET['email'];
            $company_name = $_GET['companyName'];
            $company_no = $_GET['companyReg'];
            $password = $_GET['password'];

            $query = "INSERT INTO `employer`(`name`, `email`, `company_name`, `company_no`, `password`) VALUES ('$name','$email','$company_name','$company_no','$password')";
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
    $database = "customers";
    $username = "root";
    $password = "";
    $host = "localhost";
    $userId = $_GET['user_id'];

    $connection = mysqli_connect($host, $username, $password, $database);
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        $query = "SELECT * FROM `employer` where `id` = $userId";
        $result = mysqli_query($connection, $query);
        $records = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $records= $row;
            }
            $data["status"] = true;
            $data["message"] = $records;
        }
    }
} else if ($_GET['request'] == 'login') { 
    $database = "customers";
    $username = "root";
    $password = "";
    $host = "localhost";

    $connection = mysqli_connect($host, $username, $password, $database);
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        $email = $_GET['email'];
        $password = $_GET['password'];
        $query = 'SELECT * FROM `employer` WHERE `email` = "'.$email.'" AND `password` = "'.$password.'"';
        $result = mysqli_query($connection, $query);
        $user_row = [];
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)){
                $user_row = $row;
            }
            $data["status"] = true;
            $data["message"] = $user_row;
        } else {
            $data["status"] = false;
            $data["message"] = "Invalid email or password";
        }
    }
}

echo json_encode($data);
exit();
