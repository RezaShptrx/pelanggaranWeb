Berikut adalah teks biasa dari panduan dekontaminasi "AI Slop" yang dapat Anda salin langsung:

PANDUAN DEKONTAMINASI "AI SLOP"
Prinsip & Standar Kerja Eksklusif untuk Desainer & Pengembang Web

Tentang Panduan Ini:
Dokumen ini disusun sebagai acuan teknis untuk mencegah degradasi kualitas visual akibat ketergantungan penuh pada generatif AI. Tujuannya adalah memastikan setiap produk digital tetap memiliki identitas yang kuat, fungsional, adaptif, dan berorientasi pada kenyamanan pengguna nyata (human-centric).

1. Kurasi Aset Visual & Ilustrasi
Penggunaan gambar hasil generator AI (seperti Midjourney atau Stable Diffusion) diperbolehkan hanya sebagai starting point atau eksplorasi ide kasar, bukan hasil akhir yang langsung dipasang di production assembly.

JANGAN LAKUKAN:
Memasang gambar hero section yang memiliki cacat anatomis (jumlah jari keliru, distorsi bayangan), tekstur permukaan yang terlalu mengkilap seperti lilin (glossy plastik), atau teks latar belakang yang acak (gibberish text).

REKOMENDASI:

Lakukan manual retouching menggunakan Photoshop/Figma untuk memperbaiki anomali piksel dan detail anatomi.

Kurangi opacity atau berikan overlay filter bertekstur (seperti efek grain halus) untuk memecah kesan artifisial AI.

Pastikan resolusi aset telah dikompresi menggunakan format modern (.webp atau .svg) guna menghindari penurunan performa pemuatan halaman.

2. Diversifikasi Layout & Komponen UI
AI cenderung memberikan rekomendasi tata letak yang sangat linear dan seragam karena dilatih menggunakan jutaan template standar di internet.

JANGAN LAKUKAN:
Membuat website dengan struktur monoton berulang tanpa variasi spacing: Hero > 3 Fitur Ikon > Testimoni Kartu > Tabel Harga standar bawaan library UI tanpa modifikasi warna atau radius sudut.

REKOMENDASI:

Terapkan prinsip asymmetrical layout atau manfaatkan pemanfaatan white space secara berani untuk memberikan ruang bernapas pada mata.

Ubah properti bawaan framework (seperti Tailwind/shadcn default) secara signifikan, termasuk penyesuaian nilai border-radius, box-shadow, dan ketebalan garis tepi.

3. Otentisitas Tipografi & Copywriting
Gabungan antara tipografi yang terlalu generik dengan teks hasil ChatGPT yang klise adalah indikator terkuat sebuah website dinilai murah.

JANGAN LAKUKAN:
Menggunakan paduan font yang terlalu sering direkomendasikan sistem AI (seperti Inter + Plus Jakarta Sans mentah) dikombinasikan dengan kalimat pemasaran hambar seperti "Revolusikan masa depan Anda bersama sinergi kami".

REKOMENDASI:

Gunakan kombinasi font yang memiliki kontras karakter kuat (misalnya pasangkan font Serif klasik untuk Headline dengan Sans-Serif geometris untuk Body Text).

Tulis ulang (humanize) semua draf teks dari AI. Fokuslah pada penyampaian keunggulan produk secara langsung (Clear > Clever). Hindari jargon puitis yang tidak memiliki substansi nyata.

4. Harmonisasi Warna & Aksesibilitas
Skema warna bawaan AI sering kali terlalu mengandalkan warna-warna saturasi tinggi (neon/cyberpunk glow) yang melelahkan visual mata manusia dalam jangka panjang.

JANGAN LAKUKAN:
Memasang gradasi ungu-pink-biru menyala di seluruh elemen interaktif, serta menumpuk teks putih tipis di atas gambar latar belakang yang penuh dengan titik cahaya terang.

REKOMENDASI:

5. Skema Warna Aplikasi
Berdasarkan pembaruan terbaru, skema warna yang digunakan pada aplikasi ini adalah:
- **Primary**: `#aacddc` (Biru pastel terang) - Digunakan untuk elemen interaktif utama, badge, dan background tombol sekunder.
- **Secondary**: `#6FA8BF` (Biru pastel gelap) - Digunakan untuk efek hover pada elemen *Primary* atau aksen gelap.
- **Tertiary**: `#EFBE9D` (Persik / Oranye pastel) - Digunakan untuk aksen pelengkap yang hangat.

Aturan Tambahan:
Batasi penggunaan warna aksen mencolok maksimal 10% dari total luas UI (ikuti kaidah 60-30-10). Gunakan warna dasar netral yang teduh.

Uji tingkat keterbacaan kontras teks menggunakan standar WCAG 2.1 AA (minimal rasio kontras 4.5:1 untuk teks normal). Gunakan lapisan solid overlay gelap jika teks diletakkan di atas gambar.