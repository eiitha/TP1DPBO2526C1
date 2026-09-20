from Film import Film

def cari_index_by_id(daftar_film, id_film):
    for i, film in enumerate(daftar_film):
        if film.get_id().lower() == id_film.lower():
            return i
    return -1

def main():
    # Data awal/dummy
    daftar_film = [
        Film("F01", "Inception", "Action", 148, 8.8)
    ]

    while True:
        print("\n====================================")
        print("    SISTEM MANAJEMEN BIOSKOP        ")
        print("====================================")
        print("1. Tambah Film")
        print("2. Tampilkan Semua Film")
        print("3. Update Film")
        print("4. Hapus Film")
        print("5. Cari Film")
        print("6. Keluar")

        try:
            pilihan = int(input("Pilih menu (1-6): "))
        except ValueError:
            print("Input harus berupa angka!")
            continue

        if pilihan == 1:
            print("\n--- Tambah Data Film ---")
            id_film = input("ID Film             : ")

            if cari_index_by_id(daftar_film, id_film) != -1:
                print(">> Error: ID Film sudah terdaftar!")
                continue

            judul = input("Judul Film          : ")
            genre = input("Genre               : ")
            try:
                durasi = int(input("Durasi (Menit)      : "))
                rating = float(input("Rating (0.0 - 10.0) : "))
            except ValueError:
                print(">> Input durasi/rating tidak valid!")
                continue

            daftar_film.append(Film(id_film, judul, genre, durasi, rating))
            print(">> Film berhasil ditambahkan!")

        elif pilihan == 2:
            print("\n--- Daftar Film Bioskop ---")
            if not daftar_film:
                print("Belum ada data film tersimpan.")
            else:
                for i, film in enumerate(daftar_film):
                    print(f"Data Ke-{i + 1}")
                    print(f"ID          : {film.get_id()}")
                    print(f"Judul       : {film.get_judul()}")
                    print(f"Genre       : {film.get_genre()}")
                    print(f"Durasi      : {film.get_durasi()} menit")
                    print(f"Rating      : {film.get_rating()}/10")
                    print("------------------------------------")

        elif pilihan == 3:
            print("\n--- Update Data Film ---")
            id_film = input("Masukkan ID Film yang ingin diubah: ")
            idx = cari_index_by_id(daftar_film, id_film)

            if idx != -1:
                judul_baru = input("Judul Baru          : ")
                genre_baru = input("Genre Baru          : ")
                try:
                    durasi_baru = int(input("Durasi Baru (Menit) : "))
                    rating_baru = float(input("Rating Baru         : "))
                except ValueError:
                    print(">> Input durasi/rating tidak valid!")
                    continue

                film = daftar_film[idx]
                film.set_judul(judul_baru)
                film.set_genre(genre_baru)
                film.set_durasi(durasi_baru)
                film.set_rating(rating_baru)

                print(">> Data film berhasil diperbarui!")
            else:
                print(">> Film dengan ID tersebut tidak ditemukan!")

        elif pilihan == 4:
            print("\n--- Hapus Data Film ---")
            id_film = input("Masukkan ID Film yang ingin dihapus: ")
            idx = cari_index_by_id(daftar_film, id_film)

            if idx != -1:
                daftar_film.pop(idx)
                print(">> Film berhasil dihapus!")
            else:
                print(">> Film dengan ID tersebut tidak ditemukan!")

        elif pilihan == 5:
            print("\n--- Cari Film ---")
            id_film = input("Masukkan ID Film: ")
            idx = cari_index_by_id(daftar_film, id_film)

            if idx != -1:
                film = daftar_film[idx]
                print("\n[Detail Film Ditemukan]")
                print(f"ID          : {film.get_id()}")
                print(f"Judul       : {film.get_judul()}")
                print(f"Genre       : {film.get_genre()}")
                print(f"Durasi      : {film.get_durasi()} menit")
                print(f"Rating      : {film.get_rating()}/10")
            else:
                print(">> Film tidak ditemukan!")

        elif pilihan == 6:
            print("Program selesai. Sampai jumpa!")
            break
        else:
            print("Pilihan tidak valid. Silakan pilih 1-6.")

if __name__ == "__main__":
    main()