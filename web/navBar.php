<?php session_start(); ?>

<?php
    include_once("../user/loginModal.html");
    include_once("../user/registerModal.html");?>
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <!-- Brand -->
  <a class="navbar-brand" href="/jouer/choixQuestionnaire.php">Questionnaires</a>

  <!-- Links -->
  <ul class="navbar-nav">
  <?php if(!isset($_SESSION["email"])) { ?>
    <li class="nav-item">
      <a class="nav-link" data-toggle="modal" data-target="#modalRegister">Inscription</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="modal" data-target="#modalLogin">Connexion</a>
    </li>


  <?php } else{ ?>

    <li class="nav-item">
      <a class="nav-link" href="/user/logout.php">Déconnexion</a>
    </li>
  <?php } ?>

    <!-- Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
        Dropdown link
      </a>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="#">Link 1</a>
        <a class="dropdown-item" href="#">Link 2</a>
        <a class="dropdown-item" href="#">Link 3</a>
      </div>
    </li>
  </ul>
</nav> 