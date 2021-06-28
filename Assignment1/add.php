<?php   
    session_start();
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=microease', 'microease', 'huyankai');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  

    if (!isset($_SESSION['login'])) {
        die("ACCESS DENIED");
    } else if (isset($_POST['cancel'])) {
        header('Location: index.php');
        return ;
    } else {
        if (isset($_POST['first_name']) and isset($_POST['last_name']) and isset($_POST['email']) and isset($_POST['headline']) and isset($_POST['summary'])) {
            $firstName = $_POST['first_name'];
            $lastName = $_POST['last_name'];
            $email = $_POST['email'];
            $headline = $_POST['headline'];
            $summary = $_POST['summary'];

            if ($firstName == "" or $lastName == "" or $email == "" or $headline == "" or $summary == "") {
                $_SESSION['errors'] = "All fields are required"; 
                header("Location: add.php");
                return ;
            } else {
                if (strpos($email, '@') == false) {
                    $_SESSION['errors'] = "Email address must contain @";
                    header("Location: add.php");
                    return ;
                } else {
                    $stmt = $pdo->prepare('INSERT INTO Profile
                                        (user_id, first_name, last_name, email, headline, summary) VALUES (:userId, :firstName, :lastName, :email, :headline, :summary)');
                    $stmt->execute(array(':userId' => $_SESSION['user_id'],
                                         ':firstName' => $firstName,
                                         ':lastName' => $lastName,
                                         ':email' => $email,
                                         ':headline' => $headline,
                                         ':summary' => $summary)
                                        );

                    $_SESSION['success'] = "Profile added.";
                    header("Location: index.php");
                    return ;
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>microease's Profile Add</title>

        <link rel="stylesheet" 
            href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
            integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
            crossorigin="anonymous">

        <link rel="stylesheet" 
            href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
            integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
            crossorigin="anonymous">

        <link rel="stylesheet" 
            href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">

        <script
        src="https://code.jquery.com/jquery-3.2.1.js"
        integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
        crossorigin="anonymous"></script>

        <script
        src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
        integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
        crossorigin="anonymous"></script>

    </head>

    <body>
        <div class="container">
            <h1>Adding Profile for  
                <?php
                    echo htmlentities($_SESSION['name']);
                ?>
            </h1>

            <div style="color: red;"> 
                <?php 
                    echo $_SESSION['errors'];
                    unset($_SESSION['errors'])
                ?>    
            </div>
            
            <form method="post">
                <p>First Name:
                    <input type="text" name="first_name" size="60"/>
                </p>
                    
                <p>Last Name:
                    <input type="text" name="last_name" size="60"/>
                </p>
                
                <p>
                    Email:
                    <input type="text" name="email" size="30"/>
                </p>
                
                <p>
                    Headline:<br/>
                    <input type="text" name="headline" size="80"/>
                </p>
                
                <p>
                    Summary:<br/>
                    <textarea name="summary" rows="8" cols="80"></textarea>
                <p>
                    <input type="submit" value="Add">
                    <input type="submit" name="cancel" value="Cancel">
                </p>
            </form>
        </div>
        
        <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    </body>
</html>
