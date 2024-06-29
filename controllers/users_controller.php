<?php
/*
    Controller za uporabnike. Vključuje naslednje standardne akcije:
        create: izpiše obrazec za registracijo
        store: obdela podatke iz obrazca za registracijo in ustvari uporabnika v bazi
        edit: izpiše obrazec za urejanje profila
        update: obdela podatke iz obrazca za urejanje profila in jih shrani v bazo
*/



class users_controller
{
    function create(){
        $error = "";
        if(isset($_GET["error"])){
            switch($_GET["error"]){
                case 1: $error = "Izpolnite vse podatke"; break;
                case 2: $error = "Gesli se ne ujemata."; break;
                case 3: $error = "Uporabniško ime je že zasedeno."; break;
                default: $error = "Prišlo je do napake med registracijo uporabnika.";
            }
        }
        require_once('views/users/create.php');
    }
    
    function store(){
        //Preveri če so vsi podatki izpolnjeni
        if(empty($_POST["username"]) || empty($_POST["email"]) || empty($_POST["password"])){
            header("Location: /users/create?error=1"); 
        }
        //Preveri če se gesli ujemata
        else if($_POST["password"] != $_POST["repeat_password"]){
            header("Location: /users/create?error=2"); 
        }
        //Preveri ali uporabniško ime obstaja
        else if(User::is_available($_POST["username"])){
            header("Location: /users/create?error=3"); 
        }
        //Podatki so pravilno izpolnjeni, registriraj uporabnika
        else if(User::create($_POST["username"], $_POST["email"], $_POST["password"])){
            header("Location: /auth/login");
        }
        //Prišlo je do napake pri registraciji
        else{
            header("Location: /users/create?error=4"); 
        }
        die();
    }

    function edit(){
        if(!isset($_SESSION["USER_ID"])){
            header("Location: /pages/error");
            die();
        }
        $user = User::find($_SESSION["USER_ID"]);
        $error = "";
        if(isset($_GET["error"])){
            switch($_GET["error"]){
                case 1: $error = "Izpolnite vse podatke"; break;
                case 2: $error = "Uporabniško ime je že zasedeno."; break;
                default: $error = "Prišlo je do napake med urejanjem uporabnika.";
            }
        }
        require_once('views/users/edit.php');
    }

    function update(){
        if(!isset($_SESSION["USER_ID"])){
            header("Location: /pages/error");
            die();
        }
        $user = User::find($_SESSION["USER_ID"]);
        //Preveri če so vsi podatki izpolnjeni
        if(empty($_POST["username"]) || empty($_POST["email"])){
            header("Location: /users/edit?error=1"); 
        }
        //Preveri ali uporabniško ime obstaja
        else if($user->username != $_POST["username"] && User::is_available($_POST["username"])){
            header("Location: /users/edit?error=2"); 
        }
        //Podatki so pravilno izpolnjeni, registriraj uporabnika
        else if($user->update($_POST["username"], $_POST["email"])){
            header("Location: /");
        }
        //Prišlo je do napake pri registraciji
        else{
            header("Location: /users/edit?error=3"); 
        }
        die();
    }

    public function profile() {
        require_once('models/articles.php'); 
        if (!isset($_SESSION['USER_ID'])) {
            header('Location: ?controller=auth&action=login');
            return;
        }
    
        $user_id = $_SESSION['USER_ID'];
        $user = User::find($user_id);
        $num_articles = User::countArticlesByUserId($user_id);    
        $num_comments = User::countCommentsByUserId($user_id);
        require_once('views/users/profile.php');
    }

    //dodatna
    public function changePassword() {
        require_once('models/users.php');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['USER_ID'];
            $oldPassword = $_POST['oldPassword'];
            $newPassword = $_POST['newPassword'];
            $confirmNewPassword = $_POST['confirmNewPassword'];
    
            // Preverjanje ujemanja novih gesel in preverjanje starega gesla
            if ($newPassword == $confirmNewPassword && User::changePasswords($userId, $oldPassword, $newPassword)) {
                // Geslo je bilo uspešno spremenjeno
                // Preusmeritev na profil ali prikaz uspešnega sporočila
                header('Location: ?controller=users&action=profile');
            } else {
                // Napaka pri spremembi gesla
                // Lahko prikažete napako ali ponovno prikažete obrazec za spremembo gesla z obvestilom o napaki
                $error = 'Napaka pri spremembi gesla. Preverite vaše podatke.';
                require_once('views/users/change_password.php');
            }
        } else {
            // Če zahteva ni POST, prikažemo obrazec za spremembo gesla
            require_once('views/users/change_password.php');
        }
    }
}