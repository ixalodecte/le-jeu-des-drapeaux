<?php session_start(); ?>

<?php
    include_once("../user/loginModal.html");
    include_once("../user/registerModal.html");?>
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <!-- Brand -->
  <a class="navbar-brand" href="/jouer/choixQuestionnaire.php">Jeu des drapeaux</a>

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
    <?php if($_SESSION["email"] == "admin@admin.fr") { ?>
      <li class="nav-item">
        <a class="nav-link" href="/admin/questionnaire.php">modifier questionnaire</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/admin/joue.php">liste des joueur</a>
      </li>
  <?php }} ?>

  </ul>
</nav> 