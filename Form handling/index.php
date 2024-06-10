<?php
session_start();
$token = md5(uniqid(microtime(), true));
$_SESSION['token'] = $token;
include('function.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .errors {
            padding: 1em;
            margin-bottom: 1.25em;
            background:rgba(247, 112, 94, .2);
            border: 1px solid rgba(247, 112, 94, .5);
            list-style-position: inside;
            border-radius: 3px;
        }
        .errors li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
    <h1>Find Tours</h1>
    <p>Fill in the form below to find the perfect tour for you!</p>
    <?php if (isset($_SESSION['status']) && $_SESSION['status'] === 'errors') :
        $errors = $_SESSION['errors'];?>
            <ul class = "errors">
                <?php foreach ($errors as $e) : ?>
                    <li><?php echo $e; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php elseif (isset($_SESSION['status']) && $_SESSION['status'] === 'success'):
            $data = $_SESSION['data']; 
            ?>
            <div class = "success">
                <p>Message sent successfully!</p>
                <p>Here are the details you entered!</p>
                <ul>
                    <li>Fname: <?php echo esc_str( $data['fname']); ?></li>
                    <li>Lname: <?php echo esc_str($data['lname']); ?></li>
                    <li>Email: <?php echo esc_str($data['email']); ?></li>
                    <li>Region: <?php echo esc_str( $data['region']); ?></li>
                    <li>Season: <?php echo esc_str($data['season']); ?></li>
                    <li>Participants: <?php echo esc_str($data['participant']); ?></li>
                    <li>Message: <?php echo esc_str($data['message']); ?></li>
                </ul>
            </div>
            <div class = "ideas">
                <h2> Here are some ideas for you based on the details you entered!</h2>
                <ul>
                    <?php include('destination.php'); ?>
                    <?php 
                    $region_destinations = $destinations[$data['region']] ?? [];
                    if(is_array($region_destinations)):
                        foreach($region_destinations as $d): ?>
                        <li>
                            <a href = "#"><img src="<?= $d[0] ?>" alt="<?= isset($d[1]) ? $d[1] : '' ?>"></a>
                            <h3><?= isset($d[1]) ? $d[1] : '' ?></h3>
                       </li>
                    <?php endforeach; 
                    endif;?>
                </ul>
            </div>  

    <?php endif; ?>
    <form action="handle-form.php" method="POST">
        <div class="field-group">
            First name: <input type="text" name="fname" id="name" class="field-title" placeholder="Enter your first name">
        </div>
        <div class="field-group">
            Last name: <input type="text" name="lname" id="name" class="field-title"  placeholder="Enter your last name">
        </div>
        <div class="field-group">
            Email: <input type="email" name="email" id="email" class="field-title"  placeholder="Enter your email">
        </div>
        <div class="field-group">
            Where would you like to go to ? 
        <select name="region" class="field-title" id = "region">
            <option value="Asia">Asia</option>
            <option value="Oceania">Oceania</option>
            <option value="Africa">Africa</option>
            <option value="Europe">Europe</option>
            <option value="North America">North America</option>
            <option value="Latin America">Latin America</option>
        </select><br>
        </div>

        <div class="field-group">
            <p><b>Preferred Seasons:</b></p>
            Summer<input type="radio" name="season" class="field-title" id="summer" value="Summer">
            Winter<input type="radio" name="season" class="field-title" id="winter" value="Winter">
            Spring<input type="radio" name="season" class="field-title" id="spring" value="Spring">
            Autumn<input type="radio" name="season" class="field-title" id="autumn" value="Autumn">
            Monsoon<input type="radio" name="season" class="field-title" id="monsoon" value="Monsoon">
        </div>
        
        <div class="field-group">
            <p><b>Your Interests :</b></p>
        <input type="checkbox" name="interest[]" id="photography" value="Photography">Beach
        <input type="checkbox" name="interest[]" id="trekking" value="Trekking">Trekking
        <input type="checkbox" name="interest[]" id="star-gazing" value="Star Gazing">Star Gazing
        <input type="checkbox" name="interest[]" id="bird-watching" value="Bird Watching">Bird Watching
        <input type="checkbox" name="interest[]" id="camping" value="Camping">Camping <br>
        <p></p>
        </div>
        <div class="field-group">
            Number of participants ? <input type="number" name="participant" id="participant" placeholder="Number of participants">
        <p></p><br>
        </div>
        <div class="field-group">
            Tell us more about your interest in this tour : <textarea name="message" id="message" cols="30" rows="10"></textarea>
        </div>
        <input type="hidden" name="token" value="<?= $token ?>">
        <input type="submit"><br>
    </form>
    </div>
</body>
</html>
<?php
unset($_SESSION['status']);
unset($_SESSION['errors']);
unset($_SESSION['data']);
?>
