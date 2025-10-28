<?php
class User {
    // ==============================
    // 🔹 Déclaration des propriétés
    // ==============================
    private ?int $id = null;
    public string $login;
    public string $email;
    public string $firstname;
    public string $lastname;

    // ==============================
    // 🔹 Constructeur : crée un utilisateur en mémoire
    // ==============================
    public function __construct(string $login, string $email, string $firstname, string $lastname) {
        $this->login = $login;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    // ==============================
    // 🔹 Connexion à la base de données avec PDO
    // ==============================
    private function connexion_db(): PDO {
        try {
            $dsn = "mysql:host=localhost;dbname=classes;charset=utf8mb4";
            $pdo = new PDO($dsn, "root", "", [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            return $pdo;
        } catch (PDOException $e) {
            throw new Exception("Erreur de connexion PDO : " . $e->getMessage());
        }
    }

    // ==============================
    // 🔹 Inscription (REGISTER)
    // ==============================
    public function register(string $password): ?array {
        $pdo = null;
        try {
            $pdo = $this->connexion_db();
            $pdo->beginTransaction();

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO utilisateurs (login, password, email, firstname, lastname)
                    VALUES (:login, :password, :email, :firstname, :lastname)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ":login" => $this->login,
                ":password" => $hashed_password,
                ":email" => $this->email,
                ":firstname" => $this->firstname,
                ":lastname" => $this->lastname
            ]);

            // On récupère le user inséré pour le retourner
            $sql2 = "SELECT * FROM utilisateurs WHERE email = :email";
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->execute([":email" => $this->email]);
            $user = $stmt2->fetch();

            $pdo->commit();
            return $user;
        } catch (Exception $e) {
            if ($pdo) $pdo->rollBack();
            error_log("Erreur register() : " . $e->getMessage());
            return null;
        }
    }

    // ==============================
    // 🔹 Connexion (CONNECT)
    // ==============================
    public function connect(string $login, string $password): bool {
        try {
            $pdo = $this->connexion_db();

            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE login = :login");
            $stmt->execute([":login" => $login]);
            $user = $stmt->fetch();

            if (!$user) {
                echo "Utilisateur introuvable ❌";
                return false;
            }

            // Vérification du mot de passe
            if (password_verify($password, $user["password"])) {
                $this->id = $user["id"];
                $this->login = $user["login"];
                $this->email = $user["email"];
                $this->firstname = $user["firstname"];
                $this->lastname = $user["lastname"];

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION["isConnected"] = true;
                $_SESSION["id"] = $this->id;

                echo "Connexion réussie ✅";
                return true;
            } else {
                echo "Mot de passe incorrect ❌";
                return false;
            }
        } catch (Exception $e) {
            error_log("Erreur connect() : " . $e->getMessage());
            return false;
        }
    }

    // ==============================
    // 🔹 Déconnexion (DISCONNECT)
    // ==============================
    public function disconnect(): void {
        $this->id = null;
        $this->login = "";
        $this->email = "";
        $this->firstname = "";
        $this->lastname = "";

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();
    }

    // ==============================
    // 🔹 Vérifie si l’utilisateur est connecté
    // ==============================
    public function isConnected(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION["isConnected"]) && $_SESSION["isConnected"] === true;
    }

    // ==============================
    // 🔹 Récupère toutes les infos du user
    // ==============================
    public function getAllInfos(): array|false {
        try {
            if ($this->id === null) {
                throw new Exception("Aucun utilisateur connecté.");
            }

            $pdo = $this->connexion_db();
            $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = :id");
            $stmt->execute([":id" => $this->id]);
            $user = $stmt->fetch();

            return $user ?: false;
        } catch (Exception $e) {
            error_log("Erreur getAllInfos() : " . $e->getMessage());
            return false;
        }
    }

    // ==============================
    // 🔹 Récupère uniquement le login du user
    // ==============================
    public function getLogin(): ?string {
        return $this->login ?? null;
    }

    // ==============================
    // 🔹 Supprimer le compte utilisateur
    // ==============================
    public function delete(): bool {
        try {
            if ($this->id === null) {
                throw new Exception("Aucun utilisateur connecté.");
            }

            $pdo = $this->connexion_db();
            $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = :id");
            $stmt->execute([":id" => $this->id]);

            $this->disconnect(); // on vide aussi la session
            echo "Utilisateur supprimé ✅";
            return true;
        } catch (Exception $e) {
            error_log("Erreur delete() : " . $e->getMessage());
            return false;
        }
    }
}
?>
