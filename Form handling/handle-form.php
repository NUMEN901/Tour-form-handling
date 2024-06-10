<?php
session_start();

$fname = "";
$lname = "";
$email = "";
$region = "";
$season = "";
$interest = [];
$participant = "";
$message = "";

// Validation
$errors = [];
$data = [];

if (!empty($_POST['fname'])) {
    $fname = $_POST['fname'];
    if (!ctype_alpha(str_replace(" ", "", $fname))) {
        $errors[] = "First name must contain only alphabets and spaces!";
    }
} else {
    $errors[] = "First name field cannot be empty!";
}

if (!empty($_POST['lname'])) {
    $lname = $_POST['lname'];
    if (!ctype_alpha(str_replace(" ", "", $lname))) {
        $errors[] = "Last name must contain only alphabets and spaces!";
    }
} else {
    $errors[] = "Last name field cannot be empty!";
}

if (!empty($_POST['email'])) {
    $email = $_POST['email'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format!";
    }
} else {
    $errors[] = "Email field cannot be empty!";
}

if (!empty($_POST['region'])) {
    $region = $_POST['region'];
} else {
    $errors[] = "Region field cannot be empty!";
}

if (!empty($_POST['season'])) {
    $season = $_POST['season'];
} else {
    $errors[] = "Season field cannot be empty!";
}

if (!empty($_POST['participant'])) {
    $participant = $_POST['participant'];
    if ($participant < 1 || $participant > 10) {
        $errors[] = "Number of participants must be between 1 and 10!";
    }
} else {
    $errors[] = "Participant field cannot be empty!";
}

if (!empty($_POST['message'])) {
    $message = $_POST['message'];
} else {
    $errors[] = "Message field cannot be empty!";
}

if (!empty($_POST['interest'])) {
    $interest = $_POST['interest'];
} else {
    $errors[] = "Interest field cannot be empty!";
}

if ($errors) {
    $_SESSION['status'] = 'errors';
    $_SESSION['errors'] = $errors;
    header('Location: index.php?result=validation_error');
    die();
} else {
    $data = [
        'fname' => $fname,
        'lname' => $lname,
        'email' => $email,
        'region' => $region,
        'season' => $season,
        'interest' => implode(', ', $interest),
        'participant' => $participant,
        'message' => $message
    ];

    $save = save_data($data);

    if ($save[0]) {
        $_SESSION['status'] = 'success';
        $_SESSION['data'] = $data;
        header('Location: index.php?result=success');
        die();
    } else {
        $_SESSION['status'] = 'error';
        $_SESSION['errors'] = [$save[1]];
        header('Location: index.php?result=error');
        die();
    }
}

function save_data($data) {
    try {
        $connection = new PDO("mysql:dbname=codelab;host=localhost", "root", "sc19A0282001@&");
    } catch (PDOException $connect_error) {
        return [false, "Error connecting to database: " . $connect_error->getMessage()];
    }

    $sql = "
        CREATE TABLE IF NOT EXISTS `form_submissions` (
            `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
            `fname` VARCHAR(255),
            `lname` VARCHAR(255),
            `email` VARCHAR(255),
            `region` VARCHAR(255),
            `season` VARCHAR(255),
            `interest` VARCHAR(255),
            `participant` INT(11),
            `message` TEXT
        )
    ";

    try {
        $connection->exec($sql);

        $stmt = $connection->prepare("
            INSERT INTO `form_submissions` (`fname`, `lname`, `email`, `region`, `season`, `interest`, `participant`, `message`)
            VALUES (:fname, :lname, :email, :region, :season, :interest, :participant, :message)
        ");

        $stmt->bindParam(":fname", $data['fname'], PDO::PARAM_STR);
        $stmt->bindParam(":lname", $data['lname'], PDO::PARAM_STR);
        $stmt->bindParam(":email", $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(":region", $data['region'], PDO::PARAM_STR);
        $stmt->bindParam(":season", $data['season'], PDO::PARAM_STR);
        $stmt->bindParam(":interest", $data['interest'], PDO::PARAM_STR);
        $stmt->bindParam(":participant", $data['participant'], PDO::PARAM_INT);
        $stmt->bindParam(":message", $data['message'], PDO::PARAM_STR);

        $stmt->execute();
    } catch (PDOException $e) {
        return [false, "Error saving data: " . $e->getMessage()];
    }

    return [true, "Data saved successfully"];
}
?>
