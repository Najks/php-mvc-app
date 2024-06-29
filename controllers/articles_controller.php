<?php
/*
    Controller za novice. Vključuje naslednje standardne akcije:
        index: izpiše vse novice
        show: izpiše posamezno novico
        
    TODO:
        list: izpiše novice prijavljenega uporabnika
        create: izpiše obrazec za vstavljanje novice
        store: vstavi novico v bazo
        edit: izpiše vmesnik za urejanje novice
        update: posodobi novico v bazi
        delete: izbriše novico iz baze
*/

class articles_controller
{
    public function index()
    {
        //s pomočjo statične metode modela, dobimo seznam vseh novic
        //$ads bo na voljo v pogledu za vse oglase index.php
        $articles = Article::all();

        //pogled bo oblikoval seznam vseh oglasov v html kodo
        require_once('views/articles/index.php');
    }

    public function show()
    {
        //preverimo, če je uporabnik podal informacijo, o oglasu, ki ga želi pogledati
        if (!isset($_GET['id'])) {
            return call('pages', 'error'); //če ne, kličemo akcijo napaka na kontrolerju stran
            //retun smo nastavil za to, da se izvajanje kode v tej akciji ne nadaljuje
        }
        //drugače najdemo oglas in ga prikažemo
        $article = Article::find($_GET['id']);
        $comments = Article::getComments($_GET['id']);
        require_once('views/articles/show.php');
        
    }

    public function create(){
        require_once('views/articles/create.php');
    }

    public function store() {
        // Preverjanje POST zahtevka
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = $_POST['title'];
            $abstract = $_POST['abstract'];
            $text = $_POST['text'];
            $userId = $_SESSION['USER_ID']; // ID trenutno prijavljenega uporabnika iz seje
            
            // Ustvarjanje novega članka
            $article = new Article(null, $title, $abstract, $text, date("Y-m-d H:i:s"), $userId);
            
            // Shranjevanje članka
            if($article->save()) {
                // Preusmeritev na seznam vseh člankov
                header('Location: ?controller=articles&action=index');
            } else {
                // Napaka pri shranjevanju, prikaz obrazca znova
                require_once('views/articles/create.php');
            }
        }
    }

    // V razredu articles_controller
public function myArticles() {
    if (!isset($_SESSION['USER_ID'])) {
        // Če uporabnik ni prijavljen, ga preusmerite na stran za prijavo
        header('Location: ?controller=auth&action=login');
        return;
    }
    $userId = $_SESSION['USER_ID'];
    $articles = Article::findByUserId($userId);
    require_once('views/articles/my_articles.php');
}

// V razredu articles_controller
public function edit() {
    if (!isset($_GET['id'])) return call('pages', 'error');

    $article = Article::find($_GET['id']);
    require_once('views/articles/edit.php');
}

public function update() {
    if (!isset($_POST['title']) || !isset($_POST['abstract']) || !isset($_POST['text'])) {
        // dodajte obdelavo napak
        return;
    }
    $id = $_GET['id'];
    $title = $_POST['title'];
    $abstract = $_POST['abstract'];
    $text = $_POST['text'];

    // Ustvarite instanco Article in posodobite
    $article = new Article($id, $title, $abstract, $text, null, $_SESSION['USER_ID']);
    if ($article->save()) {
        header('Location: ?controller=articles&action=myArticles');
    } else {
        // dodajte obdelavo napak
    }
}

public function delete(){
    if (!isset($_SESSION['USER_ID'])) {
        // Če uporabnik ni prijavljen, ga preusmerite na stran za prijavo ali prikažite sporočilo o napaki
        header('Location: ?controller=auth&action=login');
        return;
    }
    
    // Preverjanje, ali je ID novica posredovan
    if (!isset($_GET['id'])) {
        // Če ID novica ni posredovan, prikažite sporočilo o napaki ali preusmerite na relevantno stran
        header('Location: ?controller=articles&action=index');
        return;
    }

    $id = $_GET['id'];
    // Klic metode delete na modelu Article
    Article::delete($id);

    // Po uspešnem brisanju preusmerite uporabnika na stran z vsemi njegovimi novicami ali na domačo stran
    header('Location: ?controller=articles&action=myArticles');
}

//dodatna
public function addComment() {
    if (!isset($_SESSION['USER_ID']) || !isset($_POST['content']) || !isset($_POST['article_id'])) {
        // Preusmeritev ali napaka
        return;
    }
    // Vstavite komentar v bazo
    $db = Db::getInstance();
    $stmt = $db->prepare('INSERT INTO comments (content, user_id, article_id) VALUES (?, ?, ?)');
    $stmt->bind_param('sii', $_POST['content'], $_SESSION['USER_ID'], $_POST['article_id']);
    $stmt->execute();
    // Preusmeritev nazaj na novico
    header('Location: ?controller=articles&action=show&id=' . $_POST['article_id']);
}
}