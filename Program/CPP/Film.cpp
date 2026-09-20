#include <iostream>
#include <string>

using namespace std;

class Film {
private:
    string id;
    string judul;
    string genre;
    int durasi;     // Durasi film dalam menit
    double rating;   // Rating film (contoh: 8.5)

public:
    // Constructor kosong
    Film() {}

    // Constructor berparameter
    Film(string id, string judul, string genre, int durasi, double rating) {
        this->id = id;
        this->judul = judul;
        this->genre = genre;
        this->durasi = durasi;
        this->rating = rating;
    }

    // Destruktor
    ~Film() {}

    // Getter
    string getId() { return id; }
    string getJudul() { return judul; }
    string getGenre() { return genre; }
    int getDurasi() { return durasi; }
    double getRating() { return rating; }

    // Setter
    void setId(string id) { this->id = id; }
    void setJudul(string judul) { this->judul = judul; }
    void setGenre(string genre) { this->genre = genre; }
    void setDurasi(int durasi) { this->durasi = durasi; }
    void setRating(double rating) { this->rating = rating; }
};