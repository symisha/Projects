<!-- email = email i.e one and the same thing -->
<!-- convert to mysqli -->
<?php  
    require_once("config.php");
    session_start();
    $logged = false;   //checking if anyone(admin/email)is logged in or not
    if(isset($_SESSION['logged']))
    {
        if ($_SESSION['logged'] == true)
        {
            $logged = true ;
        }
    }
    else
        $logged=false;

    if($logged != true)
    {
       header("Location: C:/xampp/htdocs/Project1/DB/Project/home/home.php");
    }
?>