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
  
  private function connexion_db(){
    $connexion_db = mysqli_connect('localhost', 'root', '', 'classes');

    if(!$connexion_db){
      throw new Exception("Erreur de connexion : " . mysqli_connect_error());
    }
    return $connexion_db;
  }

  // Enregistre l’utilisateur en base
  public function register(string $password): ?array{

    $connexion_db = $this->connexion_db();
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
      if ($stmt1) mysqli_stmt_close($stmt1);
      if ($stmt2) mysqli_stmt_close($stmt2);
      if ($result) mysqli_free_result($result);

      mysqli_close($connexion_db);
    }
  }

  public function connect(string $login, string $password){
    //Chercher le user dans la bdd à partir du login
    try{
      // 1) obtenir la connexion
      $connexion_db = $this->connexion_db();

      $sql = "SELECT id, login, password, email, firstname, lastname  FROM utilisateurs WHERE login = ?";
    
      // 2) préparer la requête
      $stmt = mysqli_prepare($connexion_db, $sql);

      if (!$stmt) {
      throw new Exception("Erreur préparation requête : " . mysqli_error($connexion_db));
      }

      // 3) lier et exécuter la requête
      mysqli_stmt_bind_param($stmt, "s", $login);

     // 4) récupérer le résultat
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $user = mysqli_fetch_assoc($result);

      if(!$user){
        echo "Utilisateur introuvable ❌";
        return false;
      }

      // 5) vérifier l'existence et le mot de passe
      if(isset($user["password"]) && password_verify($password, $user["password"])){
      $this->id = $user["id"];
      $this->login = $user["login"];
      $this->email = $user["email"];
      $this->firstname = $user["firstname"];
      $this->lastname = $user["lastname"];

      echo "Connexion réussie";
      return true;
    } else {
      echo "Connexion échouée";
      return false;
    }
    
    } catch(Exception $e){
      error_log("Erreur de connexion utilisateur : " . $e->getMessage());
      return false; 
    } finally {
      if ($result) mysqli_free_result($result);
      if ($stmt) mysqli_stmt_close($stmt);
      if ($connexion_db) mysqli_close($connexion_db);
    }
  }

  public function disconnect(){
    $this->login = "";
    $this->email = "";
    $this->firstname = "";
    $this->lastname = "";
    $this->id = null;

    if(session_status() === PHP_SESSION_NONE){
      session_start();
    }

    $_SESSION = [];
    session_destroy();
  }

  public function delete(): bool{
    try{
      if ($this->id === null) {
      throw new Exception("Impossible de supprimer : utilisateur non identifié.");
    }  

      $connexion_db = $this->connexion_db();

      $sql = "DELETE FROM utilisateurs WHERE id = ? ";

      $stmt = mysqli_prepare($connexion_db, $sql);

      if (!$stmt) {
      throw new Exception("Erreur préparation requête : " . mysqli_error($connexion_db));
      }

      mysqli_stmt_bind_param($stmt, "i", $this->id);

      if(!mysqli_stmt_execute($stmt)){
        echo "La suppression n'a pas abouti.";
        return false;
      }

      echo "Utilisatzur supprimé avec succès.";

      if(session_status() === PHP_SESSION_NONE){
        session_start();
      }
      $_SESSION = [];
      session_destroy();

      return true;
      
      } catch(Exception $e){
      error_log("Erreur de suppression utilisateur : " . $e->getMessage());
      return false; 
    } finally {
      if ($stmt) mysqli_stmt_close($stmt);
      if ($connexion_db) mysqli_close($connexion_db);
    }
    
  }
}

$user = new User("Renkus", "test@gmail.com", "Karen", "Castillo");
$user->register("test");
$user->connect("Renkus", "test");
?>