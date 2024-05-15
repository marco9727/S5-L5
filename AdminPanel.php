<?php
class AdminPanel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getBooks() {
        $stmt = $this->db->query("SELECT * FROM libri");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookById($id) {
        $stmt = $this->db->prepare("SELECT * FROM libri WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addBook($title, $author, $genre, $published_year) {
        $stmt = $this->db->prepare("INSERT INTO libri (titolo, autore, genere, anno_pubblicazione) VALUES (:title, :author, :genre, :published_year)");
        return $stmt->execute(['title' => $title, 'author' => $author, 'genre' => $genre, 'published_year' => $published_year]);
    }

    public function updateBook($id, $title, $author, $genre, $published_year) {
        $stmt = $this->db->prepare("UPDATE libri SET titolo = :title, autore = :author, genere = :genre, anno_pubblicazione = :published_year WHERE id = :id");
        return $stmt->execute(['id' => $id, 'title' => $title, 'author' => $author, 'genre' => $genre, 'published_year' => $published_year]);
    }

    public function deleteBook($id) {
        $stmt = $this->db->prepare("DELETE FROM libri WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?>