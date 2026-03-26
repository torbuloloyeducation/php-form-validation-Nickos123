<!DOCTYPE html>
<?php
function test_input($data) {
    $data = trim($data);         
    $data = stripslashes($data);  
    $data = htmlspecialchars($data); 
    return $data;
}

$nameErr = $emailErr   = $genderErr = "";
$websiteErr = $phoneErr   = "";
$passErr = $passConfErr = $termsErr = "";

$name    = $email   = $gender  = "";
$website = $comment = $phone   = "";
$password = $passConfirm = "";


    /* Exercise 1: Phone Number field */
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required";
    } else {
        $phone = test_input($_POST["phone"]);
        if (!preg_match('/^\[+]?[0-9 \-]{7,15}$/', $phone)) {
            $phoneErr = "Invalid phone format";
        }
    }

    /* Exercise 2: Website validation with error */
    if (!empty($_POST["website"])) {
        $website = test_input($_POST["website"]);
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            $websiteErr = "Invalid URL format";
            // keep the typed value so the user can correct it
        }
    }

    /* Comment (optional, no validation needed) */
    $comment = test_input($_POST["comment"] ?? "");

    /* Exercise 3: Password + Confirm Password */
    if (empty($_POST["password"])) {
        $passErr = "Password is required";
    } else {
        $password = $_POST["password"]; // don't echo back, just validate
        if (strlen($password) < 8) {
            $passErr = "Password must be at least 8 characters";
        }
    }

    if (empty($_POST["passConfirm"])) {
        $passConfErr = "Please confirm your password";
    } else {
        $passConfirm = $_POST["passConfirm"];
        if ($passErr === "" && $passConfirm !== $password) {
            $passConfErr = "Passwords do not match";
        }
    }

    /* Exercise 4: Terms checkbox */
    if (!isset($_POST["terms"])) {
        $termsErr = "You must agree to the terms and conditions";
    }


/* Exercise 5: Submission counter (challenge) */
$submitCount = 0;


/* Process form only on POST */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

   
    $submitCount = isset($_POST["submit_count"])
        ? (int)$_POST["submit_count"] + 1
        : 1;

    /* Name (required) */
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
    }

    /* Email (required + format) */
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    /* Gender (required) */
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }

}
/* Determine whether the whole form is valid */
$formValid = ($nameErr === "" && $emailErr === "" && $genderErr === ""
           && $phoneErr === "" && $websiteErr === ""
           && $passErr === "" && $passConfErr === ""
           && $termsErr === "");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Form Validation</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; }
        .error { color: red; font-size: 0.9em; }
        input[type=text], input[type=email], input[type=password],
        select, textarea { width: 100%; padding: 6px; margin: 4px 0 2px; box-sizing: border-box; }
        label { font-weight: bold; }
        .counter { color: #891c1c; font-style: italic; margin-bottom: 10px; }
        .result  { background: #301ad7; border: 1px solid #5610b7; padding: 12px; margin-top: 20px; }
    </style>
</head>
<body>

<h2>PHP Form Validation</h2>

<!-- Exercise 5: show submission attempt count -->
<?php if ($submitCount > 0): ?>
    <p class="counter">Submission attempt: <?= $submitCount ?></p>
<?php endif; ?>

<form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">

    <!-- Exercise 5: hidden counter field -->
    <input type="hidden" name="submit_count" value="<?= $submitCount ?>">

    <!--Base fields-->
    <label>Name: <span class="error">*</span></label>
    <input type="text" name="name" value="<?= $name ?>">
    <span class="error"><?= $nameErr ?></span><br><br>

    <label>E-mail: <span class="error">*</span></label>
    <input type="text" name="email" value="<?= $email ?>">
    <span class="error"><?= $emailErr ?></span><br><br>

    <label>Gender: <span class="error">*</span></label>
    <input type="radio" name="gender" value="female"
        <?= ($gender == "female") ? "checked" : "" ?>> Female
    <input type="radio" name="gender" value="male"
        <?= ($gender == "male")   ? "checked" : "" ?>> Male
    <input type="radio" name="gender" value="other"
        <?= ($gender == "other")  ? "checked" : "" ?>> Other
    <span class="error"><?= $genderErr ?></span><br><br>

    <label>Comment: <small>(optional)</small></label>
    <textarea name="comment" rows="4"><?= $comment ?></textarea><br><br>

    <hr>

    <!--Exercise 1: Phone Number-->
    <h4>Exercise 1 – Phone Number</h4>
    <label>Phone Number: <span class="error">*</span></label>
    <input type="text" name="phone" value="<?= $phone ?>"
           placeholder="e.g. +1 800-555-1234">
    <span class="error"><?= $phoneErr ?></span><br><br>

    <!--Exercise 2: Website Validation-->
    <h4>Exercise 2 – Website Validation</h4>
    <label>Website: <small>(optional)</small></label>
    <input type="text" name="website" value="<?= $website ?>"
           placeholder="https://example.com">
    <span class="error"><?= $websiteErr ?></span><br><br>

    <!--Exercise 3: Password + Confirm-->
    <h4>Exercise 3 – Password & Confirm Password</h4>
    <label>Password: <span class="error">*</span></label>
    <input type="password" name="password">
    <span class="error"><?= $passErr ?></span><br><br>

    <label>Confirm Password: <span class="error">*</span></label>
    <input type="password" name="passConfirm">  
    <span class="error"><?= $passConfErr ?></span><br><br>

    <!--Exercise 4: Terms & Conditions-->
    <h4>Exercise 4 – Terms & Conditions</h4>
    <label>
        <input type="checkbox" name="terms"
            <?= isset($_POST["terms"]) ? "checked" : "" ?>>
        I agree to the Terms and Conditions <span class="error">*</span>
    </label>
    <span class="error"><?= $termsErr ?></span><br><br>

    <!--Exercise 5: Submission Counter (hidden)-->
    <input type="submit" value="Submit">
</form>

<!--Show results only when the whole form is valid -->
<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $formValid): ?>
<div class="result">
    <h3>✅ Form submitted successfully!</h3>
    <p><strong>Name:</strong>    <?= $name ?></p>
    <p><strong>Email:</strong>   <?= $email ?></p>
    <p><strong>Phone:</strong>   <?= $phone ?></p>
    <p><strong>Website:</strong> <?= $website ?: "—" ?></p>
    <p><strong>Comment:</strong> <?= nl2br($comment) ?: "—" ?></p>
    <p><strong>Gender:</strong>  <?= $gender ?></p>
    <!-- Password is intentionally NOT shown -->
</div>
<?php endif;?>

</body>
</html>