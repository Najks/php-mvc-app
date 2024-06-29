<?php
/*
    Model za novico. Vsebuje lastnosti, ki definirajo strukturo novice in sovpadajo s stolpci v bazi.

    V modelu moramo definirati tudi relacije oz. povezane entitete/modele. V primeru novice je to $user, ki 
    povezuje novico z uporabnikom, ki je novico objavil. Relacija nam poskrbi za nalaganje podatkov o uporabniku, 
    da nimamo samo user_id, ampak tudi username, ...
*/

require_once 'users.php'; // Vključimo model za uporabnike

class Article
{
    public $id;
    public $title;
    public $abstract;
    public $text;
    public $date;
    public $user;

    // Konstruktor
    public function __construct($id, $title, $abstract, $text, $date, $user_id)
    {
        $this->id = $id;
        $this->title = $title;
        $this->abstract = $abstract;
        $this->text = $text;
        $this->date = $date;
        $this->user = User::find($user_id); //naložimo podatke o uporabniku
    }

    // Metoda, ki iz baze vrne vse novice
    public static function all()
    {
        $db = Db::getInstance(); // pridobimo instanco baze
        $query = "SELECT * FROM articles;"; // pripravimo query
        $res = $db->query($query); // poženemo query
        $articles = array();
        while ($article = $res->fetch_object()) {
            // Za vsak rezultat iz baze ustvarimo objekt (kličemo konstuktor) in ga dodamo v array $articles
            array_push($articles, new Article($article->id, $article->title, $article->abstract, $article->text, $article->date, $article->user_id));
        }
        return $articles;
    }

    // Metoda, ki vrne eno novico z določenim id-jem iz baze
    public static function find($id)
    {
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $id);
        $query = "SELECT * FROM articles WHERE id = '$id';";
        $res = $db->query($query);
        if ($article = $res->fetch_object()) {
            return new Article($article->id, $article->title, $article->abstract, $article->text, $article->date, $article->user_id);
        }
        return null;
    }

    public function save() {
        $db = Db::getInstance();
        // Preverite, ali je datum določen, če ne, uporabite trenutni datum
        $this->date = $this->date ? $this->date : date('Y-m-d H:i:s');
        
        if ($this->id === null) {
            // Vstavljanje nove novice
            $stmt = $db->prepare('INSERT INTO articles (title, abstract, text, date, user_id) VALUES (?, ?, ?, ?, ?)');
            $stmt->bind_param('ssssi', $this->title, $this->abstract, $this->text, $this->date, $this->user->id);
        } else {
            // Posodabljanje obstoječe novice - zagotovite, da ne posodabljate datuma, če to ni potrebno
            $stmt = $db->prepare('UPDATE articles SET title = ?, abstract = ?, text = ?, date = ? WHERE id = ?');
            $stmt->bind_param('ssssi', $this->title, $this->abstract, $this->text, $this->date, $this->id);
        }
    
        $stmt->execute();
    }
    
    // Brisanje novic
    public static function delete($id) {
        $db = Db::getInstance();
        $stmt = $db->prepare('DELETE FROM articles WHERE id = ?');
        return $stmt->execute([$id]);
    }

    //novice od specifičnega userja Moje novice
public static function findByUserId($userId) {
    $db = Db::getInstance();
    $userId = intval($userId); // Zagotovimo, da je ID celo število
    $stmt = $db->prepare('SELECT * FROM articles WHERE user_id = ? ORDER BY date DESC');
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $articles = [];
    while ($article = $result->fetch_object()) {
        $articles[] = new Article($article->id, $article->title, $article->abstract, $article->text, $article->date, $article->user_id);
    }
    return $articles;
}
// Metoda za nalaganje komentarjev po novico
public static function getComments($articleId) {
    $db = Db::getInstance();
    $stmt = $db->prepare('SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE article_id = ? ORDER BY date DESC');
    $stmt->bind_param('i', $articleId);
    $stmt->execute();
    $result = $stmt->get_result();
    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    return $comments;
}
}
