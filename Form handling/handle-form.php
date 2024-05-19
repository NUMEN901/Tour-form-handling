<?php
session_start();

$name=" " ;
$email=" ";
$region=" ";
$season=" ";
$interest=" ";
$participant=" ";
$message=" ";

/* validation is highly important 
let go through the validation process */

$errors = [] ;

$data=[];

if (!empty($_POST['fname'])) {
    $name = $_POST['fname'];
    if(!ctype_alpha(str_replace(" ","", $name))) {
        $errors[] = "First name must contain only alphabets and spaces!";
    }
}
else {
    $errors[]= "first name field cannot be empty!";

}
if (!empty($_POST['lname'])){
    $name = $_POST['lname'];
    if(!ctype_alpha(str_replace(" ","", $name))) {
        $errors[] = "Last name must contain only alphabets and spaces!";
    }
}
else {
    $errors[]= "last name field cannot be empty!";

}

if (!empty($_POST['email'])) {
    $email = $_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format!";
    }
}
else {
    $errors[]= "email field cannot be empty!";

}

if (!empty($_POST['region'])) {
    $region = $_POST['region'];
}
else {
    $errors[]= "region field cannot be empty!";

}

if (!empty($_POST['season'])) {
    $season = $_POST['season'];
}
else {
    $errors[]= "season field cannot be empty!";

}
if (!empty($_POST['participant'])) {
    $participant = $_POST['participant'];
    if ($participant < 1 || $participant > 10) {
        $errors[] = "Number of participants must be between 1 and 10!";
    }
}
else {
    $errors[]= "participant field cannot be empty!";

}
if (!empty($_POST['message'])) {
    $message = $_POST['message'];

}else {
    $errors[]= "message field cannot be empty!";

}

if ($errors){
    $_SESSION['status'] = 'errors';
    $_SESSION['errors'] = $errors;
    header('Location: index.php?result=validation_error');
    die();
    
}else {
    echo "ALL FIELDS ARE VALID!" . "<br>";

    $data = [
        'name' => $name,
        'email' => $email,
        'region' => $region,
        'season' => $season,
        'participant' => $participant,
        'message' => $message
    ];

    $_SESSION['status'] = 'success';
    $_SESSION['data'] = $data;
    header('Location: index.php?result=success');
    die();
}



?>
