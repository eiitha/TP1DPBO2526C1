#include <iostream>
#include <vector>
#include <string>
#include "Film.cpp"

using namespace std;

// Fungsi untuk mencari indeks film berdasarkan ID
int cariIndexById(vector<Film>& listFilm, string id) {
    for (size_t i = 0; i < listFilm.size(); i++) {
        if (listFilm[i].getId() == id) {
            return i;
        }
    }
    return -1;
}

int main() {
    vector<Film> daftarFilm;
    int pilihan;

    // Data awal / dummy (ID, Judul, Genre, Durasi, Rating)
    daftarFilm.push_back(Film("F01", "Inception", "Action", 148, 8.8));

    do {
        cout << "\n====================================\n";
        cout << "    SISTEM MANAJEMEN BIOSKOP        \n";
        cout << "====================================\n";
        cout << "1. Tambah Film\n";
        cout << "2. Tampilkan Semua Film\n";
        cout << "3. Update Film\n";
        cout << "4. Hapus Film\n";
        cout << "5. Cari Film\n";
        cout << "6. Keluar\n";
        cout << "Pilih menu (1-6): ";
        cin >> pilihan;
        cin.ignore(); // Mencegah bug buffer input

        if (pilihan == 1) {
            string id, judul, genre;
            int durasi;
            double rating;

            cout << "\n--- Tambah Data Film ---\n";
            cout << "ID Film             : "; getline(cin, id);

            if (cariIndexById(daftarFilm, id) != -1) {
                cout << ">> Error: ID Film sudah terdaftar!\n";
                continue;
            }

            cout << "Judul Film          : "; getline(cin, judul);
            cout << "Genre               : "; getline(cin, genre);
            cout << "Durasi (Menit)      : "; cin >> durasi;
            cout << "Rating (0.0 - 10.0) : "; cin >> rating; cin.ignore();

            daftarFilm.push_back(Film(id, judul, genre, durasi, rating));
            cout << ">> Film berhasil ditambahkan!\n";

        } else if (pilihan == 2) {
            cout << "\n--- Daftar Film Bioskop ---\n";
            if (daftarFilm.empty()) {
                cout << "Belum ada data film tersimpan.\n";
            } else {
                for (size_t i = 0; i < daftarFilm.size(); i++) {
                    cout << "Data Ke-" << (i + 1) << "\n";
                    cout << "ID          : " << daftarFilm[i].getId() << "\n";
                    cout << "Judul       : " << daftarFilm[i].getJudul() << "\n";
                    cout << "Genre       : " << daftarFilm[i].getGenre() << "\n";
                    cout << "Durasi      : " << daftarFilm[i].getDurasi() << " menit\n";
                    cout << "Rating      : " << daftarFilm[i].getRating() << "/10\n";
                    cout << "------------------------------------\n";
                }
            }

        } else if (pilihan == 3) {
            string id;
            cout << "\n--- Update Data Film ---\n";
            cout << "Masukkan ID Film yang ingin diubah: "; getline(cin, id);

            int idx = cariIndexById(daftarFilm, id);
            if (idx != -1) {
                string judul, genre;
                int durasi;
                double rating;

                cout << "Judul Baru          : "; getline(cin, judul);
                cout << "Genre Baru          : "; getline(cin, genre);
                cout << "Durasi Baru (Menit) : "; cin >> durasi;
                cout << "Rating Baru         : "; cin >> rating; cin.ignore();

                daftarFilm[idx].setJudul(judul);
                daftarFilm[idx].setGenre(genre);
                daftarFilm[idx].setDurasi(durasi);
                daftarFilm[idx].setRating(rating);

                cout << ">> Data film berhasil diperbarui!\n";
            } else {
                cout << ">> Film dengan ID tersebut tidak ditemukan!\n";
            }

        } else if (pilihan == 4) {
            string id;
            cout << "\n--- Hapus Data Film ---\n";
            cout << "Masukkan ID Film yang ingin dihapus: "; getline(cin, id);

            int idx = cariIndexById(daftarFilm, id);
            if (idx != -1) {
                daftarFilm.erase(daftarFilm.begin() + idx);
                cout << ">> Film berhasil dihapus!\n";
            } else {
                cout << ">> Film dengan ID tersebut tidak ditemukan!\n";
            }

        } else if (pilihan == 5) {
            string id;
            cout << "\n--- Cari Film ---\n";
            cout << "Masukkan ID Film: "; getline(cin, id);

            int idx = cariIndexById(daftarFilm, id);
            if (idx != -1) {
                cout << "\n[Detail Film Ditemukan]\n";
                cout << "ID          : " << daftarFilm[idx].getId() << "\n";
                cout << "Judul       : " << daftarFilm[idx].getJudul() << "\n";
                cout << "Genre       : " << daftarFilm[idx].getGenre() << "\n";
                cout << "Durasi      : " << daftarFilm[idx].getDurasi() << " menit\n";
                cout << "Rating      : " << daftarFilm[idx].getRating() << "/10\n";
            } else {
                cout << ">> Film tidak ditemukan!\n";
            }

        } else if (pilihan == 6) {
            cout << "Program selesai. Sampai jumpa!\n";
        } else {
            cout << "Pilihan tidak valid. Silakan pilih 1-6.\n";
        }

    } while (pilihan != 6);

    return 0;
}