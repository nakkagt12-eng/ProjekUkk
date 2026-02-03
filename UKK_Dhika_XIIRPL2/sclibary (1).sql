-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 03, 2026 at 03:43 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sclibary`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `nama_buku` varchar(200) NOT NULL,
  `penulis` varchar(150) NOT NULL,
  `id_type` int(11) NOT NULL,
  `penjelasan` text DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `stok` int(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `nama_buku`, `penulis`, `id_type`, `penjelasan`, `img`, `stok`) VALUES
(7, 'MTK Kelas 10', '', 1, 'MTK adalah singkatan dari Matematika, yaitu ilmu yang mempelajari angka, struktur, ruang, pola, dan perubahan, serta digunakan sebagai alat berpikir logis dan pemecahan masalah di berbagai bidang kehidupan dan sains.', 'mtk-sma-kelas-10.png', 500),
(8, 'MTK Kelas 11', '', 1, 'MTK adalah singkatan dari Matematika, yaitu ilmu yang mempelajari angka, struktur, ruang, pola, dan perubahan, serta digunakan sebagai alat berpikir logis dan pemecahan masalah di berbagai bidang kehidupan dan sains.', 'MATEMATIKA XI.jpg', 500),
(9, 'MTK Kelas 12', '', 1, 'MTK adalah singkatan dari Matematika, yaitu ilmu yang mempelajari angka, struktur, ruang, pola, dan perubahan, serta digunakan sebagai alat berpikir logis dan pemecahan masalah di berbagai bidang kehidupan dan sains.', 'MATEMATIKA XII.jpg', 500),
(11, 'One Piece Manga 1-10', 'Oda', 2, 'One Piece adalah sebuah seri manga Jepang yang ditulis dan diilustrasikan oleh Eiichiro Oda. Manga ini telah dimuat di majalah Weekly Shōnen Jump milik Shueisha sejak tanggal 22 Juli 1997, dan telah dibundel menjadi 105 volume tankōbon hingga Maret 2023. Ceritanya mengisahkan petualangan Monkey D. Luffy, seorang anak laki-laki yang memiliki kemampuan tubuh elastis seperti karet setelah memakan Buah Iblis secara tidak disengaja. Luffy bersama kru bajak lautnya, yang dinamakan Bajak Laut Topi Jerami, menjelajahi Grand Line untuk mencari harta karun terbesar di dunia yang dikenal sebagai \"One Piece\" dalam rangka untuk menjadi Raja Bajak Laut yang berikutnya.', 'images (1).jpg', 5),
(12, 'Naruto Manga 1-10', 'Masashi Kishimoto', 2, 'Naruto  adalah sebuah serial manga karya Masashi Kishimoto yang diadaptasi menjadi serial anime. Manga Naruto bercerita seputar kehidupan tokoh utamanya, Naruto Uzumaki, seorang ninja yang hiperaktif, periang, dan ambisius yang ingin mewujudkan keinginannya untuk mendapatkan gelar Hokage, pemimpin dan ninja terkuat di desanya. Serial ini didasarkan pada komik one-shot oleh Kishimoto yang diterbitkan dalam edisi Akamaru Jump pada Agustus 1997', 'NarutoCoverTankobon1.jpg', 15),
(13, 'Dragon Ball Super Manga 1-10', 'Akira Toriyama', 2, 'Dragon Ball adalah sebuah manga dan anime karya Akira Toriyama dari tahun 1984 sampai 1995. Albumnya terdiri dari 42 buku dan di Indonesia diterbitkan oleh Elex Media Komputindo. Sebelumnya Dragon Ball juga pernah diterbitkan oleh Rajawali Grafiti.', 'download (3).jpg', 10),
(14, 'Moshuko Tensei Vol 1', 'Rifujin na Magonote', 3, 'Mushoku Tensei: Jobless Reincarnation adalah seri novel ringan Jepang yang ditulis oleh Rifujin na Magonote dan diilustrasikan oleh Shirotaka. Ceritanya mengikuti seorang pria pengangguran yang meninggal setelah menjalani kehidupan hikikomori-nya, dan bereinkarnasi ke dunia fantasi dengan mempertahankan ingatan kehidupan sebelumnya. Ia bertekad menikmati kehidupan barunya tanpa penyesalan sebagai Rudeus Greyrat.', 'download (4).jpg', 4),
(15, 'Maigo Ni Natteita Vol 1', 'Nekokuro', 3, 'Written by Nekokuro, Maigo ni Natteita Yōjo o Tasuketara, Otonari ni Sumu Bishōjo Ryūgakusei ga Ie ni Asobi ni Kuru yō ni Natta Ken ni Tsuite began serialization on the user-generated novel publishing website Shōsetsuka ni Narō on November 26, 2019.[2] It was later acquired by Shueisha who began publishing it with illustrations by You Midorikawa under its Dash X Bunko imprint on February 25, 2022. Seven volumes have been published as of December 25, 2024.', 'download (5).jpg', 4);

-- --------------------------------------------------------

--
-- Table structure for table `buku_genre`
--

CREATE TABLE `buku_genre` (
  `id_buku` int(11) NOT NULL,
  `id_genre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku_genre`
--

INSERT INTO `buku_genre` (`id_buku`, `id_genre`) VALUES
(7, 3),
(7, 6),
(7, 10),
(8, 3),
(8, 6),
(8, 10),
(9, 3),
(9, 6),
(9, 10),
(11, 1),
(11, 5),
(11, 8),
(12, 1),
(12, 8),
(12, 12),
(13, 1),
(13, 5),
(13, 8),
(14, 1),
(14, 2),
(14, 5),
(14, 8),
(15, 2),
(15, 12);

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE `genre` (
  `id_genre` int(11) NOT NULL,
  `nama_genre` varchar(100) NOT NULL,
  `pengertian` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`id_genre`, `nama_genre`, `pengertian`) VALUES
(1, 'Fantasi', 'Berlatar di dunia khayalan yang sering kali mencakup elemen sihir, mitologi, atau makhluk supernatural. Contoh klasik termasuk kisah-kisah dengan dunia yang sepenuhnya baru.'),
(2, 'Romansa', 'Memiliki tema utama cinta dan hubungan antara karakter, sering kali berfokus pada perkembangan hubungan romantis dan emosi yang menghangatkan hati atau menyedihkan.'),
(3, 'Horor', 'Bertujuan untuk menakut-nakuti pembaca, sering kali melibatkan elemen supranatural, kejahatan sadis, atau ketegangan psikologis.'),
(4, 'Fiksi Ilmiah', 'Mengeksplorasi konsep futuristik, teknologi canggih, dan kemungkinan dunia lain, sering kali mempertanyakan \"bagaimana jika?\" tentang perkembangan sains.'),
(5, 'Misteri', 'Fokus pada pemecahan suatu kejahatan atau serangkaian kejahatan oleh seorang detektif atau profesional lainnya.'),
(6, 'Thriller', 'Menekankan ketegangan dan kegembiraan, dirancang untuk membuat pembaca tetap tegang dan penasaran dengan apa yang akan terjadi selanjutnya.'),
(7, 'Sejarah', 'Mengambil latar waktu tertentu dari masa lalu, sering kali memadukan karakter fiktif dengan peristiwa sejarah nyata.'),
(8, 'Petualangan', 'Fokus pada perjalanan, eksplorasi, dan pengalaman mendebarkan yang dialami oleh para tokohnya.'),
(9, 'Biografi/Autobiografi', 'Biografi menceritakan kisah hidup orang lain, sedangkan autobiografi adalah kisah hidup yang ditulis sendiri oleh subjeknya.'),
(10, 'Buku Panduan', 'Menyediakan instruksi atau petunjuk cara melakukan sesuatu, seperti buku resep, manual teknis, atau panduan perjalanan.'),
(11, 'Sains', 'Menjelaskan konsep ilmiah yang kompleks kepada pembaca umum dengan cara yang menarik dan mudah dipahami.'),
(12, 'Motivasi', 'Berisi saran, strategi, dan inspirasi untuk membantu pembaca meningkatkan berbagai aspek kehidupan pribadi atau profesional mereka.'),
(14, 'Kamus', 'Berisi kumpulan informasi faktual yang luas, disusun secara sistematis untuk referensi cepat.');

-- --------------------------------------------------------

--
-- Table structure for table `pinjam`
--

CREATE TABLE `pinjam` (
  `id_pinjam` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali` date DEFAULT NULL,
  `tgl_dikembalikan` date DEFAULT NULL,
  `status` enum('diproses','dipinjam','dikembalikan') DEFAULT 'diproses'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pinjam`
--

INSERT INTO `pinjam` (`id_pinjam`, `id_buku`, `username`, `tgl_pinjam`, `tgl_kembali`, `tgl_dikembalikan`, `status`) VALUES
(18, 8, 'User', '2026-02-03', '2026-02-05', '2026-02-02', 'dikembalikan');

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id_review` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `rating` int(11) NOT NULL,
  `komentar` text DEFAULT NULL,
  `reply` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`id_review`, `id_buku`, `username`, `rating`, `komentar`, `reply`, `created_at`) VALUES
(4, 15, '7179', 3, 'ksndlkjnd', NULL, '2026-02-02 06:29:23');

-- --------------------------------------------------------

--
-- Table structure for table `type_buku`
--

CREATE TABLE `type_buku` (
  `id_type` int(11) NOT NULL,
  `nama_type` varchar(100) NOT NULL,
  `penjelasan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `type_buku`
--

INSERT INTO `type_buku` (`id_type`, `nama_type`, `penjelasan`) VALUES
(1, 'Pembelajaran', 'Buku pembelajaran adalah jenis buku nonfiksi yang disusun secara sistematis untuk tujuan pendidikan atau instruksional'),
(2, 'Komik', 'Komik adalah media komunikasi yang menggunakan gambar-gambar tidak bergerak yang disusun sedemikian rupa untuk membentuk sebuah jalinan cerita.'),
(3, 'Novel', 'Novel adalah karya fiksi prosa yang tertulis secara naratif, biasanya dalam bentuk cerita yang panjang dan kompleks.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `created_at`) VALUES
(1, 'NakkaKunn', '$2y$10$8e3EQoqyVQg8BY72kbUAT.y/CTVkaz7IPRSNp2TrAi1k9AjjJUWAO', 'admin12@gamil.com', 'admin', '2026-01-19 03:43:57'),
(2, 'User', '$2y$10$yqgddjZkTYtYC5BadG4m.O3wWTOOY3/isFIIvSOk5Q7kmRjQStXmO', 'pembeli@gamil.com', 'user', '2026-01-19 05:08:36'),
(4, 'kk', '$2y$10$W6kZf8HnWll/EwGsLFGkseAU6ObS6RrFXWWQLFty.L.yQe5wQ5uTy', 'nakka1@gmail.com', 'user', '2026-01-26 03:18:50'),
(6, '7179', '$2y$10$tKuibTGX8o4uy.oFtRmDle3TI5XwLkB/kmPKTudQXkgzojv3c7z76', 'user@gmail.com', 'user', '2026-02-02 06:28:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD KEY `id_type` (`id_type`);

--
-- Indexes for table `buku_genre`
--
ALTER TABLE `buku_genre`
  ADD PRIMARY KEY (`id_buku`,`id_genre`),
  ADD KEY `id_genre` (`id_genre`);

--
-- Indexes for table `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`id_genre`);

--
-- Indexes for table `pinjam`
--
ALTER TABLE `pinjam`
  ADD PRIMARY KEY (`id_pinjam`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id_review`),
  ADD KEY `id_buku` (`id_buku`);

--
-- Indexes for table `type_buku`
--
ALTER TABLE `type_buku`
  ADD PRIMARY KEY (`id_type`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `genre`
--
ALTER TABLE `genre`
  MODIFY `id_genre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `pinjam`
--
ALTER TABLE `pinjam`
  MODIFY `id_pinjam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id_review` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `type_buku`
--
ALTER TABLE `type_buku`
  MODIFY `id_type` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `buku_ibfk_1` FOREIGN KEY (`id_type`) REFERENCES `type_buku` (`id_type`);

--
-- Constraints for table `buku_genre`
--
ALTER TABLE `buku_genre`
  ADD CONSTRAINT `buku_genre_ibfk_1` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE,
  ADD CONSTRAINT `buku_genre_ibfk_2` FOREIGN KEY (`id_genre`) REFERENCES `genre` (`id_genre`) ON DELETE CASCADE;

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
