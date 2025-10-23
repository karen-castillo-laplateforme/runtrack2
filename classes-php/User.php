<?php
class User {
  //Déclaration des propriétés
  private ?int $id = null;
  public string $login;
  public string $email;
  public string $firstname;
  public string $lastname;
  
  // Constructeur : crée un utilisateur en mémoire
  //Déclaration des méthodes
  public function __construct(string $login, string $email, string $firstname, string $lastname){
    $this->login = $login;
    $this->email = $email;
    $this->firstname = $firstname;
    $this->lastname = $lastname;  
  } 

  // Enregistre l’utilisateur en base
  public function register(string $password): ?array{

    $connexion_db = mysqli_connect('localhost', 'root', '', 'classes');

    if(!$connexion_db){
      throw new Exception("Erreur de connexion : " . mysqli_connect_error());
    }

    mysqli_begin_transaction($connexion_db);

    try{
    // 1️⃣ Hachage du mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 2️⃣ Insertion de l’utilisateur
    $sql1 = "INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES (?, ?, ?, ?, ?)";

    $stmt1 = mysqli_prepare($connexion_db, $sql1);

    mysqli_stmt_bind_param($stmt1, "sssss" , $this->login, $hashed_password, $this->email, $this->firstname, $this->lastname);

    mysqli_stmt_execute($stmt1);

    //Requête 2
    $sql2 = "SELECT * FROM utilisateurs WHERE email = ?";

    $stmt2 = mysqli_prepare($connexion_db, $sql2);

    mysqli_stmt_bind_param($stmt2,"s", $this->email);

    mysqli_stmt_execute($stmt2);

    $result = mysqli_stmt_get_result($stmt2);
    $user = mysqli_fetch_assoc($result);

    mysqli_commit($connexion_db);

    return $user;

    } catch(Exception $e){
        mysqli_rollback($connexion_db);
        error_log("Erreur transaction : " . $e->getMessage());
        return null;
    } finally {
      //s’exécute dans tous les cas, pour fermer la connexion proprement
      mysqli_stmt_close($stmt1);
      mysqli_stmt_close($stmt2);
      mysqli_free_result($result);
      mysqli_close($connexion_db);
    }
  }
}

$user = new User("Renkus", "test@gmail.com", "Karen", "Castillo");
$user->register("test");
?>