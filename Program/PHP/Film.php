<?php
class Film {
    private string $id;
    private string $judul;
    private string $genre;
    private int $durasi;
    private float $rating;
    private string $gambar;

    public function __construct(
        string $id = "",
        string $judul = "",
        string $genre = "",
        int $durasi = 0,
        float $rating = 0.0,
        string $gambar = ""
    ) {
        $this->id = $id;
        $this->judul = $judul;
        $this->genre = $genre;
        $this->durasi = $durasi;
        $this->rating = $rating;
        $this->gambar = $gambar;
    }

    // Getter
    public function getId(): string {
        return $this->id;
    }

    public function getJudul(): string {
        return $this->judul;
    }

    public function getGenre(): string {
        return $this->genre;
    }

    public function getDurasi(): int {
        return $this->durasi;
    }

    public function getRating(): float {
        return $this->rating;
    }

    public function getGambar(): string {
        return $this->gambar;
    }

    // Setter
    public function setId(string $id): void {
        $this->id = $id;
    }

    public function setJudul(string $judul): void {
        $this->judul = $judul;
    }

    public function setGenre(string $genre): void {
        $this->genre = $genre;
    }

    public function setDurasi(int $durasi): void {
        $this->durasi = $durasi;
    }

    public function setRating(float $rating): void {
        $this->rating = $rating;
    }

    public function setGambar(string $gambar): void {
        $this->gambar = $gambar;
    }
}
?>