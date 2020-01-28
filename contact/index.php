<?php
//execute composer generated autoloader 
require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['message'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');
    $body = file_get_contents($_SERVER["DOCUMENT_ROOT"].'/templates/email/staff.html');

    $m = new Mustache_Engine;
    $body = $m->render($body, array('message' => $message)); // "Hello World!"

    //todo send email to staff
    $subject = "New message recieved at " . date("d/m/Y h:i:sa");
    $headers = "From: webmaster@example.com" . "\r\n";
    $headers .= "From: webmaster@example.com" . "CC: somebodyelse@example.com" . "\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    mail($email,$subject,$body,$headers);

    //todo send email to user

  }
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/css/Styles.min.css">
  <script src="https://kit.fontawesome.com/38670082c8.js" crossorigin="anonymous"></script>
  <title>Made With Love | Contact Us</title>
</head>
<body>
  <div class="header">
    <img class="branding" src="/img/branding.png" alt="">
    <input type="checkbox" id="chk">
    <label for="chk" class="show-menu-btn">
      <a class="fas fa-ellipsis-h"></a>
    </label>

    <ul class="menu">
      <a href="/" active>Home</a>
      <a href="/contact">Contact</a>
      <a href="/donate">Donate</a>
      <label for="chk" class="hide-menu-btn">
        <a class="fas fa-times"></a>
      </label>
    </ul>
  </div>

  <div class="content">
    <form class="contact-form" action="" id="contactform" method="post">
      <h1>Contact Us</h1>
      <input name="name" type="text" class="contact-form-text" placeholder="Your name" required>
      <input name="email" type="email" class="contact-form-text" placeholder="Your email" required>
      <input name="phone" type="tel" class="contact-form-text" placeholder="Your phone number" required>
      <textarea class="contact-form-text" name="message" cols="30" rows="10" placeholder="Your message" required></textarea>
      <input type="submit" class="contact-form-text" value="Send">
    </form>

    <h1 id="thanks" style="display:none;">Thanks! <br> We will be in contact soon</h1>
  </div>

</body>
</html>
