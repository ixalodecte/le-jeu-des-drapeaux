<?php
session_start();
include_once("../config.php");
$message="";
// test des champs
if(isset($_POST['Login']))
{
    $email=$_POST['email'];
    $password=md5($_POST['password']);
    // en cas des champs non valides:
    if(empty($_POST['email'])){
        $message="<li>mail non valide</li>";
    }
    
    
    //test de  password
    
    elseif(empty($_POST['password']))
    {
        $message="<li>password non valide</li>";
    }
    if(!empty($_POST['email']) && !empty($_POST['password']))
    {
        //recherche si le joueur existe
        $sql="SELECT * FROM joueurs WHERE email=? and password=?";
        $select=$con->prepare($sql);
        
        
        $select->execute([$email, $password]);
        if($select->rowCount()>0)
        {
            $data=$select->fetch();
            $_SESSION['email']=$data['email'];
            header("location:/jouer/choixQuestionnaire.php");
            
        }else 
        echo " invalid email or password";
        
    }
}

?>



