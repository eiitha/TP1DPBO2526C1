class Film:
    def __init__(self, id_film="", judul="", genre="", durasi=0, rating=0.0):
        self._id = id_film
        self._judul = judul
        self._genre = genre
        self._durasi = durasi
        self._rating = rating

    # Getter
    def get_id(self):
        return self._id

    def get_judul(self):
        return self._judul

    def get_genre(self):
        return self._genre

    def get_durasi(self):
        return self._durasi

    def get_rating(self):
        return self._rating

    # Setter
    def set_id(self, id_film):
        self._id = id_film

    def set_judul(self, judul):
        self._judul = judul

    def set_genre(self, genre):
        self._genre = genre

    def set_durasi(self, durasi):
        self._durasi = durasi

    def set_rating(self, rating):
        self._rating = rating