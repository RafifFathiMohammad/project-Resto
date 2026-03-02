-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2026 at 06:09 AM
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
-- Database: `app_resto`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_pemesanan`
--

CREATE TABLE `detail_pemesanan` (
  `id_detail` int(13) NOT NULL,
  `id_pemesanaan` int(13) NOT NULL,
  `id_menu` int(13) NOT NULL,
  `jumlah` int(13) NOT NULL,
  `subtotal` int(13) NOT NULL,
  `catatan` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pemesanan`
--

INSERT INTO `detail_pemesanan` (`id_detail`, `id_pemesanaan`, `id_menu`, `jumlah`, `subtotal`, `catatan`) VALUES
(12, 1, 1, 12, 144000, '-'),
(13, 19, 1, 5, 60000, '-');

--
-- Triggers `detail_pemesanan`
--
DELIMITER $$
CREATE TRIGGER `kurangi_stok_setelah_pesan` AFTER INSERT ON `detail_pemesanan` FOR EACH ROW BEGIN
    UPDATE menu 
    SET Stok = Stok - NEW.jumlah
    WHERE id_menu = NEW.id_menu;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `kategori` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `kategori`) VALUES
(1, 'Single item'),
(2, 'Set Menu');

-- --------------------------------------------------------

--
-- Table structure for table `meja`
--

CREATE TABLE `meja` (
  `id_meja` int(13) NOT NULL,
  `meja` varchar(200) NOT NULL,
  `jumlah_kursi` int(11) NOT NULL,
  `ketersediaan` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meja`
--

INSERT INTO `meja` (`id_meja`, `meja`, `jumlah_kursi`, `ketersediaan`) VALUES
(2, 'A1', 2, 1),
(10, 'B1', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id_menu` int(13) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `Stok` int(13) NOT NULL,
  `ketersediaan` tinyint(1) NOT NULL DEFAULT 0,
  `harga` int(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `nama`, `id_kategori`, `Stok`, `ketersediaan`, `harga`) VALUES
(1, 'Nasi Goreng', 1, 195, 1, 12000),
(4, 'Baso', 1, 200, 1, 10000);

--
-- Triggers `menu`
--
DELIMITER $$
CREATE TRIGGER `update_ketersediaan_otomatis` BEFORE UPDATE ON `menu` FOR EACH ROW BEGIN
    -- Jika stok baru kurang dari atau sama dengan 0
    IF NEW.Stok <= 0 THEN
        SET NEW.ketersediaan = 0;
        -- Opsional: Memastikan stok tidak negatif
        SET NEW.Stok = 0; 
    ELSE
        -- Jika stok lebih dari 0, otomatis tersedia
        SET NEW.ketersediaan = 1;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id_payment` int(13) NOT NULL,
  `type` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id_payment`, `type`) VALUES
(1, 'Tunai'),
(2, 'Kartu Kredit');

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pemesanan` int(13) NOT NULL,
  `catatan` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id_pemesanan`, `catatan`) VALUES
(1, '-'),
(19, '-');

-- --------------------------------------------------------

--
-- Table structure for table `pengalaman`
--

CREATE TABLE `pengalaman` (
  `id_pengalaman` int(13) NOT NULL,
  `type` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengalaman`
--

INSERT INTO `pengalaman` (`id_pengalaman`, `type`) VALUES
(1, 'Dine In'),
(2, 'Takeout'),
(3, 'Delivery');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id_role` int(13) NOT NULL,
  `pekerjaan` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id_role`, `pekerjaan`) VALUES
(1, 'Admin'),
(2, 'Kasir'),
(4, 'Manager');

-- --------------------------------------------------------

--
-- Table structure for table `struk_pembayaran`
--

CREATE TABLE `struk_pembayaran` (
  `id_struk` int(13) NOT NULL,
  `id_meja` int(13) DEFAULT NULL,
  `id_pemesanan` int(13) NOT NULL,
  `id_pengalaman` int(13) NOT NULL,
  `id_pegawai` int(13) NOT NULL,
  `id_payment` int(13) NOT NULL,
  `total` int(13) NOT NULL,
  `tanggal` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `struk_pembayaran`
--

INSERT INTO `struk_pembayaran` (`id_struk`, `id_meja`, `id_pemesanan`, `id_pengalaman`, `id_pegawai`, `id_payment`, `total`, `tanggal`, `status`) VALUES
(24, NULL, 19, 2, 1, 1, 60000, '2026-03-01', 1);

--
-- Triggers `struk_pembayaran`
--
DELIMITER $$
CREATE TRIGGER `update_meja_setelah_bayar_struk` AFTER UPDATE ON `struk_pembayaran` FOR EACH ROW BEGIN
    DECLARE tipe_pengalaman VARCHAR(50);
    SELECT type INTO tipe_pengalaman FROM Pengalaman WHERE id_Pengalaman = NEW.id_Pengalaman;

    -- Jika status berubah menjadi Sudah Dibayar (1)
    IF NEW.status = 1 THEN
        UPDATE meja SET ketersediaan = 1 WHERE id_meja = NEW.id_meja;
    
    -- Jika status diedit kembali ke Belum Dibayar (0) dan itu Dine In
    ELSEIF NEW.status = 0 AND tipe_pengalaman = 'Dine In' THEN
        UPDATE meja SET ketersediaan = 0 WHERE id_meja = NEW.id_meja;
        
    -- Jika diubah menjadi selain Dine In
    ELSEIF tipe_pengalaman <> 'Dine In' THEN
        UPDATE meja SET ketersediaan = 1 WHERE id_meja = NEW.id_meja;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_meja_setelah_buat_struk` AFTER INSERT ON `struk_pembayaran` FOR EACH ROW BEGIN
    -- Ambil tipe pengalaman untuk mengecek apakah "Dine In"
    DECLARE tipe_pengalaman VARCHAR(50);
    SELECT type INTO tipe_pengalaman FROM Pengalaman WHERE id_Pengalaman = NEW.id_Pengalaman;

    -- Logika: Jika Dine In dan Belum Dibayar (0)
    IF tipe_pengalaman = 'Dine In' AND NEW.status = 0 THEN
        UPDATE meja SET ketersediaan = 0 WHERE id_meja = NEW.id_meja;
    
    -- Jika Dine In dan Sudah Dibayar (1) ATAU Bukan Dine In
    ELSEIF (tipe_pengalaman = 'Dine In' AND NEW.status = 1) OR (tipe_pengalaman <> 'Dine In') THEN
        UPDATE meja SET ketersediaan = 1 WHERE id_meja = NEW.id_meja;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `update_stok_harian`
--

CREATE TABLE `update_stok_harian` (
  `id_update` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `jumlah_porsi` int(200) NOT NULL,
  `tanggal_update` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `update_stok_harian`
--

INSERT INTO `update_stok_harian` (`id_update`, `id_menu`, `jumlah_porsi`, `tanggal_update`) VALUES
(6, 1, 200, '2026-03-01'),
(7, 4, 200, '2026-03-01');

--
-- Triggers `update_stok_harian`
--
DELIMITER $$
CREATE TRIGGER `tambah_stok_setelah_update` AFTER INSERT ON `update_stok_harian` FOR EACH ROW BEGIN
    UPDATE menu 
    SET Stok = Stok + NEW.jumlah_porsi
    WHERE id_menu = NEW.id_menu;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(13) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `id_role` int(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `nama`, `email`, `password`, `id_role`) VALUES
(1, 'Budi', 'admin@gmail.com', 'admin', 1),
(2, 'Blue', 'kasir@gmail.com', 'kasir', 2),
(4, 'manager', 'manager@gmail.com', 'manager', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pemesanaan` (`id_pemesanaan`) USING BTREE,
  ADD KEY `id_menu` (`id_menu`) USING BTREE;

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `meja`
--
ALTER TABLE `meja`
  ADD PRIMARY KEY (`id_meja`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id_menu`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id_payment`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`);

--
-- Indexes for table `pengalaman`
--
ALTER TABLE `pengalaman`
  ADD PRIMARY KEY (`id_pengalaman`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexes for table `struk_pembayaran`
--
ALTER TABLE `struk_pembayaran`
  ADD PRIMARY KEY (`id_struk`),
  ADD KEY `id_meja` (`id_meja`) USING BTREE,
  ADD KEY `id_payment` (`id_payment`) USING BTREE,
  ADD KEY `id_pemesanan` (`id_pemesanan`) USING BTREE,
  ADD KEY `id_pegawai` (`id_pegawai`) USING BTREE,
  ADD KEY `id_pengalaman` (`id_pengalaman`) USING BTREE;

--
-- Indexes for table `update_stok_harian`
--
ALTER TABLE `update_stok_harian`
  ADD PRIMARY KEY (`id_update`),
  ADD KEY `id_menu` (`id_menu`) USING BTREE;

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `id_pekerjaan` (`id_role`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  MODIFY `id_detail` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `meja`
--
ALTER TABLE `meja`
  MODIFY `id_meja` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id_menu` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id_payment` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pemesanan` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `pengalaman`
--
ALTER TABLE `pengalaman`
  MODIFY `id_pengalaman` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `struk_pembayaran`
--
ALTER TABLE `struk_pembayaran`
  MODIFY `id_struk` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `update_stok_harian`
--
ALTER TABLE `update_stok_harian`
  MODIFY `id_update` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pemesanan`
--
ALTER TABLE `detail_pemesanan`
  ADD CONSTRAINT `detail_pemesanan_ibfk_1` FOREIGN KEY (`id_pemesanaan`) REFERENCES `pemesanan` (`id_pemesanan`),
  ADD CONSTRAINT `detail_pemesanan_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`);

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`);

--
-- Constraints for table `struk_pembayaran`
--
ALTER TABLE `struk_pembayaran`
  ADD CONSTRAINT `struk_pembayaran_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `struk_pembayaran_ibfk_2` FOREIGN KEY (`id_pengalaman`) REFERENCES `pengalaman` (`id_pengalaman`),
  ADD CONSTRAINT `struk_pembayaran_ibfk_3` FOREIGN KEY (`id_payment`) REFERENCES `payment` (`id_payment`),
  ADD CONSTRAINT `struk_pembayaran_ibfk_4` FOREIGN KEY (`id_meja`) REFERENCES `meja` (`id_meja`),
  ADD CONSTRAINT `struk_pembayaran_ibfk_5` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`);

--
-- Constraints for table `update_stok_harian`
--
ALTER TABLE `update_stok_harian`
  ADD CONSTRAINT `update_stok_harian_ibfk_1` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
