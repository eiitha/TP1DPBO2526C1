import java.util.ArrayList;
import java.util.Scanner;

public class Main {
    // Fungsi pembantu untuk mencari indeks film berdasarkan ID
    private static int cariIndexById(ArrayList<Film> listFilm, String id) {
        for (int i = 0; i < listFilm.size(); i++) {
            if (listFilm.get(i).getId().equalsIgnoreCase(id)) {
                return i;
            }
        }
        return -1;
    }

    public static void main(String[] args) {
        ArrayList<Film> daftarFilm = new ArrayList<>();
        Scanner scanner = new Scanner(System.in);
        int pilihan = 0;

        // Data dummy/awal
        daftarFilm.add(new Film("F01", "Avatar 3", "Sci-Fi", 192, 8.5));
        daftarFilm.add(new Film("F02", "Inception", "Action", 148, 8.8));

        do {
            System.out.println("\n====================================");
            System.out.println("    SISTEM MANAJEMEN BIOSKOP        ");
            System.out.println("====================================");
            System.out.println("1. Tambah Film");
            System.out.println("2. Tampilkan Semua Film");
            System.out.println("3. Update Film");
            System.out.println("4. Hapus Film");
            System.out.println("5. Cari Film");
            System.out.println("6. Keluar");
            System.out.print("Pilih menu (1-6): ");

            if (scanner.hasNextInt()) {
                pilihan = scanner.nextInt();
                scanner.nextLine(); // Mencegah bug buffer input
            } else {
                System.out.println("Input harus berupa angka!");
                scanner.nextLine();
                continue;
            }

            switch (pilihan) {
                case 1:
                    System.out.println("\n--- Tambah Data Film ---");
                    System.out.print("ID Film             : ");
                    String id = scanner.nextLine();

                    if (cariIndexById(daftarFilm, id) != -1) {
                        System.out.println(">> Error: ID Film sudah terdaftar!");
                        break;
                    }

                    System.out.print("Judul Film          : ");
                    String judul = scanner.nextLine();
                    System.out.print("Genre               : ");
                    String genre = scanner.nextLine();
                    System.out.print("Durasi (Menit)      : ");
                    int durasi = scanner.nextInt();
                    System.out.print("Rating (0.0 - 10.0) : ");
                    double rating = scanner.nextDouble();
                    scanner.nextLine();

                    daftarFilm.add(new Film(id, judul, genre, durasi, rating));
                    System.out.println(">> Film berhasil ditambahkan!");
                    break;

                case 2:
                    System.out.println("\n--- Daftar Film Bioskop ---");
                    if (daftarFilm.isEmpty()) {
                        System.out.println("Belum ada data film tersimpan.");
                    } else {
                        for (int i = 0; i < daftarFilm.size(); i++) {
                            Film f = daftarFilm.get(i);
                            System.out.println("Data Ke-" + (i + 1));
                            System.out.println("ID          : " + f.getId());
                            System.out.println("Judul       : " + f.getJudul());
                            System.out.println("Genre       : " + f.getGenre());
                            System.out.println("Durasi      : " + f.getDurasi() + " menit");
                            System.out.println("Rating      : " + f.getRating() + "/10");
                            System.out.println("------------------------------------");
                        }
                    }
                    break;

                case 3:
                    System.out.println("\n--- Update Data Film ---");
                    System.out.print("Masukkan ID Film yang ingin diubah: ");
                    String updateId = scanner.nextLine();

                    int idxUpdate = cariIndexById(daftarFilm, updateId);
                    if (idxUpdate != -1) {
                        System.out.print("Judul Baru          : ");
                        String judulBaru = scanner.nextLine();
                        System.out.print("Genre Baru          : ");
                        String genreBaru = scanner.nextLine();
                        System.out.print("Durasi Baru (Menit) : ");
                        int durasiBaru = scanner.nextInt();
                        System.out.print("Rating Baru         : ");
                        double ratingBaru = scanner.nextDouble();
                        scanner.nextLine();

                        Film f = daftarFilm.get(idxUpdate);
                        f.setJudul(judulBaru);
                        f.setGenre(genreBaru);
                        f.setDurasi(durasiBaru);
                        f.setRating(ratingBaru);

                        System.out.println(">> Data film berhasil diperbarui!");
                    } else {
                        System.out.println(">> Film dengan ID tersebut tidak ditemukan!");
                    }
                    break;

                case 4:
                    System.out.println("\n--- Hapus Data Film ---");
                    System.out.print("Masukkan ID Film yang ingin dihapus: ");
                    String deleteId = scanner.nextLine();

                    int idxDelete = cariIndexById(daftarFilm, deleteId);
                    if (idxDelete != -1) {
                        daftarFilm.remove(idxDelete);
                        System.out.println(">> Film berhasil dihapus!");
                    } else {
                        System.out.println(">> Film dengan ID tersebut tidak ditemukan!");
                    }
                    break;

                case 5:
                    System.out.println("\n--- Cari Film ---");
                    System.out.print("Masukkan ID Film: ");
                    String searchId = scanner.nextLine();

                    int idxSearch = cariIndexById(daftarFilm, searchId);
                    if (idxSearch != -1) {
                        Film f = daftarFilm.get(idxSearch);
                        System.out.println("\n[Detail Film Ditemukan]");
                        System.out.println("ID          : " + f.getId());
                        System.out.println("Judul       : " + f.getJudul());
                        System.out.println("Genre       : " + f.getGenre());
                        System.out.println("Durasi      : " + f.getDurasi() + " menit");
                        System.out.println("Rating      : " + f.getRating() + "/10");
                    } else {
                        System.out.println(">> Film tidak ditemukan!");
                    }
                    break;

                case 6:
                    System.out.println("Program selesai. Sampai jumpa!");
                    break;

                default:
                    System.out.println("Pilihan tidak valid. Silakan pilih 1-6.");
                    break;
            }
        } while (pilihan != 6);

        scanner.close();
    }
}