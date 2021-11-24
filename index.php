<?php

require_once 'Classes/Database.php';
require_once 'Classes/ErrorHandler.php';
require_once 'Classes/Validator.php';

$db = new Database ; 
$errorHandler = new ErrorHandler; 

if (!empty($_POST)) {
    $validator = new Validator($db , $errorHandler);

    $validation = $validator->check($_POST , [
        'username' =>[
            'required'=>true,
            'maxlength'=>20,
            'minlength'=>3,
            'alphanumeric'=>true
        ],
        'email'=>[
            'required' =>true,
            'email'=>true,
            'maxlength'=>255,
            'unique' => 'users'
        ],
        'password'=>[
            'required'=>true,
            'minlength'=>6
        ],
        'password_confirmation'=>[
            'match' => 'password'
        ]
    ]);
    if ($validation->fails()) {
        echo '<ul>';
        foreach ($validation->errors()->all() as $fieldErrors)
        {
            foreach ($fieldErrors as $error)
            {
                echo '<li>' . $error . '</li>';
            }
        }
        echo '</ul>';
    }else{
        echo '<h3> Data Is Valid :) </h3>';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Validation</title>
</head>
<body>
    <form action="index.php" method="post">
        
    <div>
        <label for="username">Username</label>
        <input style="margin-left: 20px;" type="text" name="username">
    </div>

    <br>
    <div>
        <label for="email">Email</label>
        <input style="margin-left: 47px;" type="email" name="email">
    </div>

    <br>
    <div>
        <label for="password">Password</label>
        <input style="margin-left: 24px;" type="password" name="password">
    </div>
    <br>

    <div>
        <label for="password_confirmation">Confirm Password</label>
        <input style="margin-left: 24px;" type="password" name="password_confirmation">
    </div>
    <br>

    <input type="submit" value="Login">
    </form>
</body>
</html>