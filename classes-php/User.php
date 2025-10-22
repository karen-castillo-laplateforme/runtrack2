<?php
class User {
  //Déclaration des propriétés
  private int $id;
  public string $login;
  public string $email;
  public string $firstname;
  public string $lastname;
  
  //Déclaration des méthodes
  public function __construct(){
    $this->id = $id;
    $this->login = $login;
    $this->email = $email;
    $this->firstname = $firstname;
    $this->lastname = $lastname;  
  } 

  public function register(int $id, string $login, string $email, string $firstname, string $lastname){

    $connexion_db = mysqli_connect('loalhost', 'root', '', 'classes');

    mysqli_begin_transaction($connexion_db);

    //Requête 1
    $sql1 = "INSERT INTO utilisateurs (login, email, firstname, lastname) VALUES ($login, $email, $firstname, $lastname)";

    $stmt1 = mysqli_prepare($connexion_db, $sql1);

    mysqli_stmt_execute($stmt1);

    //Requête 2
    $sql2 = "SELECT login, email, firstname, lastname FROM utilisateurs WHERE email = $email";

    $stmt2 = mysqli_prepare($connexion_db, $sql2);

    $result = mysqli_stmt_get_result($stmt2);
    $user = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt1);
    mysqli_free_result($result);
    mysqli_close($connexion_db);
  }
}
?>