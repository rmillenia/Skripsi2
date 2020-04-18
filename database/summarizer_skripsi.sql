-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 18 Apr 2020 pada 18.05
-- Versi Server: 10.1.29-MariaDB
-- PHP Version: 7.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `summarizer_skripsi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `no_perkara` varchar(100) DEFAULT NULL,
  `file` varchar(100) NOT NULL,
  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `documents`
--

INSERT INTO `documents` (`id`, `no_perkara`, `file`, `date_time`) VALUES
(1, ' 966/PID.SUS/2018/PN.SDA  ', 'EMMIRIANTO.pdf', '2020-04-18 22:55:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sentence`
--

CREATE TABLE `sentence` (
  `id_sentence` int(11) NOT NULL,
  `sentence` varchar(300) NOT NULL,
  `fk_documents` int(11) NOT NULL,
  `f1` float NOT NULL,
  `f2` float NOT NULL,
  `bobot` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `sentence`
--

INSERT INTO `sentence` (`id_sentence`, `sentence`, `fk_documents`, `f1`, `f2`, `bobot`) VALUES
(1, '  Majelis Hakim yang mulia Jaksa Penuntut Umum yang terhormat Sidang yang berbahagia', 1, 0, 0, 0),
(2, 'Pertama-tama Kami panjatkan puji syukur kehadirat ALLAH YANG MAHA ESA atas Rachmat-NYA  kita  dapat  bertemu  dalam  keadaan  sehat  sehingga  dapat  melanjutkan  persidangan dalam perkara ini', 1, 0, 0, 0),
(3, 'Selanjutnya  pada  kesempatan  ini,  Kami  selaku  Penasihat  Hukum  Terdakwa EMIRRIANTO, menghaturkan terimakasih  yang  sedalam-dalamnya  kepada Majelis Hakim atas kesempatan yang telah diberikan kepada Kami guna menyampaikan pembelaan atas diri Terdakwa dalam persidangan perkara ini', 1, 0, 0, 0),
(4, 'Puji  syukur  kehadirat ALLAH  YANG  MAHA  ESA,  karena  Kami  berhasil  menyusun  dan mengemukakan Pembelaan ini demi terciptanya kebenaran yang akhirnya dapat melahirkan rasa  keadilan  bagi  masyarakat  pada  umumnya  dan  bagi  Terdakwa EMIRRIANTO khususnya, untuk itu semoga ALLAH YANG MAHA ESA ', 1, 0, 0, 0),
(5, 'Bahwa  sebagaimana  telah  kita  ketahui,  Jaksa  Penuntut  Umum dalam  Surat  Dakwaannya No.Reg.Perk. :  PDM-560  / SIDOA/ Euh.2/ 10/ 2018  tertanggal  24  Oktober  2018 telah mendakwa  Terdakwa EMIRRIANTO telah  melakukan  perbuatan pidana  sebagaimana diatur dan diancam pidana yaitu dalam Dakwaan', 1, 0, 0, 0),
(6, 'Dan  dalam  persidangan  yang  lalu,  Jaksa  Penuntut  Umum  dalam  Surat  Tuntutan  No.  Reg. Perkara : 560/Sidoa/Euh.2/10/2018 tertanggal 3  Januari 2019 telah  menuntut  Terdakwa dianggap telah melakukan tindak pidana sebagaimana diatur dalam pasal 45 A ayat (2) UU RI No.19 Th. 2016 Jo pasal 28 a', 1, 0, 0, 0),
(7, 'Persyaratan  mutlak  negara  hukum  adalah  negara  berkewajiban  untuk  melindungi  dan menghormati  hak-hak  asasi  manusia,  sehingga  kebebasasan  berekspresi  dalam menyampaikan pendapat adalah merupakan bagian yang tak dapat dipisahkan dengan Hak asasi  manusia', 1, 0, 0, 0),
(8, 'Bahwa  terdakwa EMMIRIANTO adalah  seorang  laki-laki  yang berumur 56 tahun dari keluarga yang sederhana,yang pekerjaannya yaitu seorang guru', 1, 0, 0, 0),
(9, 'Olehnya itu apa yang dituliskan oleh EMIRRIANTO dalam status Facebook miliknya haruslah dipandang  sebagai  pengunaan  dan  penikmatan  hak  atas  kebebasan  berpendapat  dan berekspresi sebagai hak konstitusionalnya dalam kerangka Negara hukum dan demokrasi, yang dijamin dan dilindungi oleh pelbaga', 1, 0, 0, 0),
(10, 'Emmirianto  memosting  status  tersebut  hanya  dalam  waktu  kurang  dari  1  hari  dan  belum berdampak  bagi  masyarakat', 1, 0, 0, 0),
(11, 'Lalu  ia  melakukan  perbuatan  tersebut  atas  dasar  keinginan sendiri  dan  hal  tersebut  merupakan  bentuk  emosi  dari  terdakwa  yang  akhirnya mengalahkan akal sehatnya', 1, 0, 0, 0),
(12, 'Sehingga terdakwa tidak kontrol dan merasa jengkel', 1, 0, 0, 0),
(13, 'Di sisi lain pelapor yang merupakan pejabat publik seharusnya bisa menempatkan dirinya sebagai  pengayom  masyarakat,  bukan  sebaliknya hendak  memenjara EMIRRIANTO yang tentu  awam  dengan  hukum', 1, 0, 0, 0),
(14, 'Saat  persidiangan  dengan  bangganya  mengatakan melaporkan terdakwa karna ingin memberikan pelajaran bagi terdakwa.3  Kami  sangat  berkeyakinan  bahwa  berdasarkan  fakta-fakta  secara  keseluruhan sebagaimana  terungkap  di  persidangan, kita  semua  terutama  Majelis  Hakim  Yang  Mulia yang  m', 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `pic` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sentence`
--
ALTER TABLE `sentence`
  ADD PRIMARY KEY (`id_sentence`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `sentence`
--
ALTER TABLE `sentence`
  MODIFY `id_sentence` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
