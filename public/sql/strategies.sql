-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 20, 2023 at 01:48 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `catatcrypto`
--

--
-- Dumping data for table `strategies`
--

INSERT INTO `strategies` (`id`, `user_id`, `category_id`, `name`, `description`, `url_picture`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 1, 'Entry on Breakout', 'Entry on Breakout adalah sebuah strategi trading yang mencoba memanfaatkan momen ketika harga menembus resistance atau support untuk membuka posisi. Dalam strategi ini, trader menentukan level resistance dan support yang signifikan dan menunggu hingga harga menembus salah satu dari kedua level tersebut.

Setelah harga menembus salah satu level, trader akan membuka posisi buy jika harga menembus resistance atau posisi sell jika harga menembus support. Tujuan dari strategi ini adalah untuk memanfaatkan momentum yang terjadi setelah harga menembus level tertentu dan mengharapkan harga akan bergerak lebih jauh dalam arah yang sama.

Namun, perlu diingat bahwa Entry on Breakout bukanlah strategi yang sempurna dan memiliki tingkat risiko yang tinggi. Terkadang, harga bisa saja berbalik arah setelah menembus level tertentu, sehingga trader harus berhati-hati dan memasang stop loss untuk membatasi potensi kerugian. Oleh karena itu, Entry on Breakout lebih baik digunakan sebagai bagian dari portfolio trading yang lebih luas dan diversifikasi.', NULL, NULL, NULL, NULL),
(2, NULL, 1, 'Entry on Events', 'Entry on Events dalam kripto adalah strategi trading yang mencoba memanfaatkan peristiwa-peristiwa penting yang mempengaruhi harga mata uang kripto. Dalam strategi ini, trader mengamati jadwal rilis berita atau peristiwa penting yang mungkin mempengaruhi harga dan membuka posisi sebelum atau setelah peristiwa tersebut.

Peristiwa penting yang dapat mempengaruhi harga kripto dapat berupa rilis berita tentang regulasi atau peraturan baru, rilis produk baru, dan lain-lain. Trader yang menggunakan strategi Entry on Events akan memperkirakan arah pergerakan harga setelah peristiwa terjadi dan membuka posisi buy atau sell sesuai dengan perkiraan tersebut.

Namun, perlu diingat bahwa strategi Entry on Events dalam kripto juga memiliki tingkat risiko yang tinggi. Harga mata uang kripto sangat fluktuatif dan bisa dipengaruhi oleh berbagai faktor, sehingga trader harus berhati-hati dalam menentukan peristiwa penting dan memperkirakan arah pergerakan harga. Oleh karena itu, Entry on Events lebih baik digunakan sebagai bagian dari portfolio trading yang lebih luas dan diversifikasi.', NULL, NULL, NULL, NULL),
(3, NULL, 1, 'Entry on Potential', 'Entry on Potential dalam kripto adalah strategi trading yang mencoba memanfaatkan potensi dari mata uang kripto tertentu untuk berkembang dan meningkatkan nilainya. Dalam strategi ini, trader mencari mata uang kripto yang memiliki prospek baik untuk berkembang di masa depan dan membuka posisi buy dengan harapan harga akan meningkat dalam jangka panjang. Prospek dalam konteks ini tidak selalu berarti potensi perkembangan mata uang kripto dalam jangka panjang saja, tetapi juga bisa berupa potensi pergerakan harga dalam jangka pendek karena terlihat adanya pattern tertentu dalam grafik harga.

Untuk menentukan mata uang kripto dengan potensi yang baik, trader bisa mengamati faktor-faktor seperti kapitalisasi pasar, volume perdagangan, dan kualitas proyek yang mendasari mata uang tersebut. Trader juga bisa memperhatikan tren pasar dan pandangan para analis terkemuka dalam industri kripto. Dalam beberapa kasus, trader bisa memanfaatkan prospek ini untuk membuka posisi buy atau sell saat pattern tertentu sedang dalam proses pembentukan, atau saat harga berada dalam posisi weakness atau sedang berada di tengah-tengah pembentukan pattern.

Namun, perlu diingat bahwa strategi Entry on Potential dalam kripto memiliki tingkat risiko yang tinggi. Pasar kripto sangat fluktuatif dan bisa dipengaruhi oleh berbagai faktor eksternal, sehingga harga mata uang kripto yang dianggap memiliki potensi baik saat ini bisa saja berubah dalam waktu singkat. Oleh karena itu, trader harus berhati-hati dan melakukan analisis yang memadai sebelum membuka posisi dalam strategi Entry on Potential ini.', NULL, NULL, NULL, NULL),
(4, NULL, 1, 'Entry on Retest', 'Entry on Retest adalah strategi trading yang mencoba memanfaatkan peluang dari level harga yang pernah ditembus (breakout) sebelumnya. Dalam strategi ini, trader mencari level harga yang sebelumnya pernah ditembus (breakout) dan kemudian menunggu harga untuk kembali ke tingkat tersebut (retest) sebelum membuka posisi buy atau sell. Tujuan dari strategi ini adalah untuk memastikan bahwa tingkat harga yang sebelumnya pernah ditembus (breakout) memang merupakan tingkat yang kuat dan bisa dipercaya sebagai level support atau resistance.

Untuk menentukan level harga yang pernah ditembus (breakout), trader bisa menggunakan berbagai alat analisis teknikal seperti garis trend, level fibonacci, atau pivot points. Setelah level harga teridentifikasi, trader harus menunggu harga untuk kembali ke tingkat tersebut (retest) dan memastikan bahwa tingkat tersebut masih kuat dan tidak mudah ditembus. Jika tingkat tersebut tetap kuat dan harga tidak mudah ditembus, trader bisa membuka posisi buy atau sell berdasarkan arah pergerakan harga.

Namun, perlu diingat bahwa strategi Entry on Retest dalam kripto juga memiliki tingkat risiko yang tinggi. Pasar kripto sangat fluktuatif dan bisa dipengaruhi oleh berbagai faktor eksternal, sehingga level harga yang sebelumnya pernah ditembus (breakout) bisa saja berubah dalam waktu singkat. Oleh karena itu, trader harus berhati-hati dan melakukan analisis yang memadai sebelum membuka posisi dalam strategi Entry on Retest ini.', NULL, NULL, NULL, NULL),
(5, NULL, 1, 'Entry on Sideways', 'Entry on Sideways adalah strategi trading yang mencoba memanfaatkan kondisi pasar kripto yang sedang sideway. Dalam kondisi sideway, harga tidak menunjukkan tren yang jelas naik atau turun, melainkan bergerak dalam range yang sempit. Dalam strategi ini, trader mencari peluang untuk membuka posisi buy atau sell saat harga mencapai level support atau resistance dalam kondisi sideway.

Untuk menentukan level support dan resistance dalam kondisi sideway, trader bisa menggunakan berbagai alat analisis teknikal seperti garis trend, level fibonacci, atau pivot points. Setelah level support dan resistance teridentifikasi, trader harus menunggu harga untuk mencapai salah satu dari kedua level tersebut dan memastikan bahwa tingkat tersebut masih kuat dan tidak mudah ditembus. Jika tingkat tersebut tetap kuat dan harga tidak mudah ditembus, trader bisa membuka posisi buy atau sell berdasarkan arah pergerakan harga.

Namun, perlu diingat bahwa strategi Entry on Sideways dalam kripto juga memiliki tingkat risiko yang tinggi. Pasar kripto sangat fluktuatif dan bisa dipengaruhi oleh berbagai faktor eksternal, sehingga level support dan resistance yang teridentifikasi bisa saja berubah dalam waktu singkat. Oleh karena itu, trader harus berhati-hati dan melakukan analisis yang memadai sebelum membuka posisi dalam strategi Entry on Sideways ini.', NULL, NULL, NULL, NULL),
(6, NULL, 1, 'Entry on Weakness', 'Entry on Weakness adalah strategi trading yang mencoba memanfaatkan momen kelemahan atau pelemahan harga dalam pasar kripto. Dalam strategi ini, trader mencari peluang untuk membuka posisi buy ketika harga mengalami penurunan atau membuka posisi sell ketika harga mengalami kenaikan yang terlalu tajam dan berpotensi untuk kembali ke level sebelumnya.

Untuk melakukan Entry on Weakness dalam kripto, trader harus memiliki pemahaman yang baik tentang tren pasar dan faktor-faktor yang mempengaruhi harga. Trader juga harus mampu menganalisis grafik harga dan menentukan level support dan resistance. Bila harga bergerak ke arah yang berlawanan dengan tren pasar dan mencapai level support yang kuat, trader bisa membuka posisi buy. Sebaliknya, bila harga bergerak terlalu tajam ke arah tren dan mencapai level resistance yang kuat, trader bisa membuka posisi sell.

Namun, perlu diingat bahwa strategi Entry on Weakness juga memiliki tingkat risiko yang tinggi. Banyak faktor eksternal yang bisa mempengaruhi pergerakan harga dalam pasar kripto, sehingga harga bisa saja melanjutkan pergerakan ke arah yang berlawanan dengan yang diharapkan. Oleh karena itu, trader harus berhati-hati dan memastikan bahwa level support dan resistance yang teridentifikasi masih kuat sebelum membuka posisi dalam strategi Entry on Weakness ini.', NULL, NULL, NULL, NULL),
(7, NULL, 2, 'Fibonacci Extension', NULL, NULL, NULL, NULL, NULL),
(8, NULL, 2, 'Fibonacci Retracement', NULL, NULL, NULL, NULL, NULL),
(9, NULL, 3, 'Abandoned Baby', 'Abandoned Baby adalah sebuah pola candlestick yang muncul dalam grafik harga kripto. Pola ini terdiri dari tiga candle, dimana candle pertama menunjukkan tren yang kuat dalam satu arah, candle kedua adalah doji (candle yang memiliki harga pembukaan dan penutupan yang sama atau hampir sama) dan candle ketiga adalah candle bullish atau bearish yang berlawanan dengan tren pada candle pertama.

Pola Abandoned Baby menunjukkan bahwa para buyer atau seller kuat telah kehilangan kontrol atas pasar dan bahwa pergerakan harga kripto mungkin akan berubah arah. Dalam hal ini, trader bisa memanfaatkan pola Abandoned Baby untuk membuka posisi baru dengan mengikuti arah tren baru yang terbentuk.

Namun, perlu diingat bahwa pola Abandoned Baby tidak selalu menunjukkan perubahan tren yang pasti. Ada kalanya, pergerakan harga hanya merupakan koreksi sementara dan tren akan kembali ke arah sebelumnya. Oleh karena itu, trader harus memastikan bahwa pola Abandoned Baby yang terbentuk benar-benar merupakan tanda perubahan tren yang signifikan sebelum membuka posisi baru.', NULL, NULL, NULL, NULL),
(10, NULL, 3, 'Advance Block', 'Advance Block adalah sebuah pola candlestick yang muncul dalam grafik harga kripto. Pola ini terdiri dari tiga candle yang menunjukkan tren naik atau bullish, namun candle ketiga lebih kecil dibandingkan dengan candle sebelumnya. Ini menunjukkan bahwa tren naik mungkin melemah dan bahwa ada kemungkinan pergerakan harga akan berbalik arah.

Pola Advance Block menunjukkan bahwa para buyer mungkin kehilangan minat dalam membeli kripto, dan bahwa seller mulai memegang kendali atas pasar. Dalam hal ini, trader bisa memanfaatkan pola Advance Block untuk membuka posisi short atau memasang stop loss untuk membatasi kerugian jika tren berbalik arah.

Namun, perlu diingat bahwa pola Advance Block tidak selalu menunjukkan perubahan tren yang pasti. Ada kalanya, pergerakan harga hanya merupakan koreksi sementara dan tren akan kembali ke arah sebelumnya. Oleh karena itu, trader harus memastikan bahwa pola Advance Block yang terbentuk benar-benar merupakan tanda perubahan tren yang signifikan sebelum membuka posisi baru atau memasang stop loss.', NULL, NULL, NULL, NULL),
(11, NULL, 3, 'Bearish Meeting Lines', NULL, NULL, NULL, NULL, NULL),
(12, NULL, 3, 'Bearish Side by Side White Lines', NULL, NULL, NULL, NULL, NULL),
(13, NULL, 3, 'Belt Hold', NULL, NULL, NULL, NULL, NULL),
(14, NULL, 3, 'Breakaway', NULL, NULL, NULL, NULL, NULL),
(15, NULL, 3, 'Bullish Meeting Lines', NULL, NULL, NULL, NULL, NULL),
(16, NULL, 3, 'Bullish Side by Side White Lines', NULL, NULL, NULL, NULL, NULL),
(17, NULL, 3, 'Closing Marubozu', NULL, NULL, NULL, NULL, NULL),
(18, NULL, 3, 'Concealing Baby Shallow', NULL, NULL, NULL, NULL, NULL),
(19, NULL, 3, 'Counterattack', NULL, NULL, NULL, NULL, NULL),
(20, NULL, 3, 'Dark Cloud Cover', NULL, NULL, NULL, NULL, NULL),
(21, NULL, 3, 'Doji', NULL, NULL, NULL, NULL, NULL),
(22, NULL, 3, 'Doji Star', NULL, NULL, NULL, NULL, NULL),
(23, NULL, 3, 'Down Gap Side by Side', NULL, NULL, NULL, NULL, NULL),
(24, NULL, 3, 'Dragonfly Doji', NULL, NULL, NULL, NULL, NULL),
(25, NULL, 3, 'Engulfing', NULL, NULL, NULL, NULL, NULL),
(26, NULL, 3, 'Evening Star', NULL, NULL, NULL, NULL, NULL),
(27, NULL, 3, 'Falling Three', NULL, NULL, NULL, NULL, NULL),
(28, NULL, 3, 'Gravestone Doji', NULL, NULL, NULL, NULL, NULL),
(29, NULL, 3, 'Hammer', NULL, NULL, NULL, NULL, NULL),
(30, NULL, 3, 'Hanging Man', NULL, NULL, NULL, NULL, NULL),
(31, NULL, 3, 'Harami', NULL, NULL, NULL, NULL, NULL),
(32, NULL, 3, 'Harami Cross', NULL, NULL, NULL, NULL, NULL),
(33, NULL, 3, 'High Wave', NULL, NULL, NULL, NULL, NULL),
(34, NULL, 3, 'Hikkake', NULL, NULL, NULL, NULL, NULL),
(35, NULL, 3, 'Homing Pigeon', NULL, NULL, NULL, NULL, NULL),
(36, NULL, 3, 'Identical Three Crows', NULL, NULL, NULL, NULL, NULL),
(37, NULL, 3, 'In Neck', NULL, NULL, NULL, NULL, NULL),
(38, NULL, 3, 'Inverted Hammer', NULL, NULL, NULL, NULL, NULL),
(39, NULL, 3, 'Island Reversal', NULL, NULL, NULL, NULL, NULL),
(40, NULL, 3, 'Key Reversal Bar', NULL, NULL, NULL, NULL, NULL),
(41, NULL, 3, 'Kicker', NULL, NULL, NULL, NULL, NULL),
(42, NULL, 3, 'Ladder Bottom', NULL, NULL, NULL, NULL, NULL),
(43, NULL, 3, 'Ladder Top', NULL, NULL, NULL, NULL, NULL),
(44, NULL, 3, 'Long Legged Doji', NULL, NULL, NULL, NULL, NULL),
(45, NULL, 3, 'Long Line', NULL, NULL, NULL, NULL, NULL),
(46, NULL, 3, 'Marubozu', NULL, NULL, NULL, NULL, NULL),
(47, NULL, 3, 'Matching High', NULL, NULL, NULL, NULL, NULL),
(48, NULL, 3, 'Matching Low', NULL, NULL, NULL, NULL, NULL),
(49, NULL, 3, 'Mat Hold', NULL, NULL, NULL, NULL, NULL),
(50, NULL, 3, 'Modified Hikkake', NULL, NULL, NULL, NULL, NULL),
(51, NULL, 3, 'Morning Star', NULL, NULL, NULL, NULL, NULL),
(52, NULL, 3, 'One Black Crow', NULL, NULL, NULL, NULL, NULL),
(53, NULL, 3, 'One White Soldier', NULL, NULL, NULL, NULL, NULL),
(54, NULL, 3, 'On Neck', NULL, NULL, NULL, NULL, NULL),
(55, NULL, 3, 'Piercing Lines', NULL, NULL, NULL, NULL, NULL),
(56, NULL, 3, 'Rickshaw Man', NULL, NULL, NULL, NULL, NULL),
(57, NULL, 3, 'Rising Three', NULL, NULL, NULL, NULL, NULL),
(58, NULL, 3, 'Separating Lines', NULL, NULL, NULL, NULL, NULL),
(59, NULL, 3, 'Shooting Star', NULL, NULL, NULL, NULL, NULL),
(60, NULL, 3, 'Short Line', NULL, NULL, NULL, NULL, NULL),
(61, NULL, 3, 'Spinning Top', NULL, NULL, NULL, NULL, NULL),
(62, NULL, 3, 'Stalled', NULL, NULL, NULL, NULL, NULL),
(63, NULL, 3, 'Stick Sandwich', NULL, NULL, NULL, NULL, NULL),
(64, NULL, 3, 'Takuri', NULL, NULL, NULL, NULL, NULL),
(65, NULL, 3, 'Tasuki Gap', NULL, NULL, NULL, NULL, NULL),
(66, NULL, 3, 'Three Black Crows', NULL, NULL, NULL, NULL, NULL),
(67, NULL, 3, 'Three Inside Down', NULL, NULL, NULL, NULL, NULL),
(68, NULL, 3, 'Three Inside Up', NULL, NULL, NULL, NULL, NULL),
(69, NULL, 3, 'Three Line Strike', NULL, NULL, NULL, NULL, NULL),
(70, NULL, 3, 'Three Outside Down', NULL, NULL, NULL, NULL, NULL),
(71, NULL, 3, 'Three Outside Up', NULL, NULL, NULL, NULL, NULL),
(72, NULL, 3, 'Three Stars', NULL, NULL, NULL, NULL, NULL),
(73, NULL, 3, 'Three White Soldiers', NULL, NULL, NULL, NULL, NULL),
(74, NULL, 3, 'Thrusting', NULL, NULL, NULL, NULL, NULL),
(75, NULL, 3, 'Tri Star', NULL, NULL, NULL, NULL, NULL),
(76, NULL, 3, 'Tweezer Bottom', NULL, NULL, NULL, NULL, NULL),
(77, NULL, 3, 'Tweezer Top', NULL, NULL, NULL, NULL, NULL),
(78, NULL, 3, 'Two Crows', NULL, NULL, NULL, NULL, NULL),
(79, NULL, 3, 'Unique Three River', NULL, NULL, NULL, NULL, NULL),
(80, NULL, 3, 'Up Gap Side by Side', NULL, NULL, NULL, NULL, NULL),
(81, NULL, 3, 'Upside Gap Three', NULL, NULL, NULL, NULL, NULL),
(82, NULL, 3, 'Upside Gap Two Crows', NULL, NULL, NULL, NULL, NULL),
(83, NULL, 4, 'Ascending Broadening Wedge Continuation', 'Ascending Broadening Wedge Continuation adalah pola chart yang muncul dalam grafik harga kripto. Pola ini terbentuk ketika harga bergerak naik secara bertahap dalam channel yang semakin melebar. Ini menunjukkan bahwa tren naik mungkin akan berlanjut, namun juga menandakan bahwa volatilitas pasar mungkin meningkat.

Ascending Broadening Wedge Continuation biasanya terbentuk ketika ada pertempuran antara buyer dan seller, dimana buyer berusaha mempertahankan harga tinggi, sementara seller berusaha menekan harga turun. Dalam hal ini, trader bisa memanfaatkan pola Ascending Broadening Wedge Continuation untuk membuka posisi long jika mereka yakin tren naik akan berlanjut, atau memasang stop loss jika mereka merasa tren mungkin akan berbalik arah.

Namun, perlu diingat bahwa Ascending Broadening Wedge Continuation bisa juga menunjukkan bahwa tren akan berbalik arah, terutama jika harga melampaui support dalam channel yang melebar. Oleh karena itu, trader harus memastikan bahwa mereka memahami situasi pasar dan mempertimbangkan faktor fundamental sebelum membuka posisi baru atau memasang stop loss.', NULL, NULL, NULL, NULL),
(84, NULL, 4, 'Ascending Broadening Wedge Reversal', 'Ascending Broadening Wedge Reversal adalah pola chart yang muncul dalam grafik harga kripto. Pola ini terbentuk ketika harga bergerak naik secara bertahap dalam channel yang semakin melebar, tetapi kemudian berbalik arah dan menurun. Ini menandakan bahwa tren naik sebelumnya mungkin sudah berakhir dan tren bearish akan muncul.

Ascending Broadening Wedge Reversal biasanya terbentuk ketika seller mulai mendominasi pasar dan memulai tekanan penjualan, sementara buyer tidak lagi mampu mempertahankan harga tinggi. Dalam hal ini, trader bisa memanfaatkan pola Ascending Broadening Wedge Reversal untuk membuka posisi short jika mereka yakin tren bearish akan berlanjut, atau memasang stop loss jika mereka merasa tren mungkin akan berbalik arah.

Namun, perlu diingat bahwa Ascending Broadening Wedge Reversal bisa juga menjadi petunjuk yang salah dan tren naik akan berlanjut. Oleh karena itu, trader harus memastikan bahwa mereka memahami situasi pasar dan mempertimbangkan faktor fundamental sebelum membuka posisi baru atau memasang stop loss. Sangat penting bagi trader untuk memantau pergerakan harga dan memastikan bahwa pola Ascending Broadening Wedge Reversal terkonfirmasi sebelum membuat keputusan trading.', NULL, NULL, NULL, NULL),
(85, NULL, 4, 'Ascending Triangle', 'Ascending Triangle Continuation adalah pola chart dalam grafik harga kripto yang menunjukkan adanya potensi kenaikan harga setelah tren bullish. Pola ini terbentuk ketika harga bergerak naik dan mencapai level resistance yang konsisten, sementara level support terus meningkat.

Ascending Triangle Continuation biasanya terjadi saat buyer mulai mendominasi pasar dan mempertahankan tekanan beli, meskipun seller berusaha mempertahankan level resistance. Dalam situasi ini, trader bisa memanfaatkan Ascending Triangle Continuation untuk membuka posisi long jika mereka yakin tren bullish akan berlanjut, atau memasang stop loss jika mereka merasa tren mungkin akan berbalik arah.

Namun, perlu diingat bahwa Ascending Triangle Continuation bisa juga menjadi petunjuk yang salah dan tren bearish akan muncul. Oleh karena itu, trader harus memastikan bahwa mereka memahami situasi pasar dan mempertimbangkan faktor fundamental sebelum membuka posisi baru atau memasang stop loss. Sangat penting bagi trader untuk memantau pergerakan harga dan memastikan bahwa pola Ascending Triangle Continuation terkonfirmasi sebelum membuat keputusan trading.', NULL, NULL, NULL, NULL),
(86, NULL, 4, 'Ascending Triangle Reversal', 'Ascending Triangle Reversal adalah pola chart dalam grafik harga kripto yang menunjukkan adanya potensi pelemahan harga setelah tren bearish. Pola ini terbentuk ketika harga bergerak naik dan mencapai level resistance yang konsisten, sementara level support tetap statis.

Ascending Triangle Reversal biasanya terjadi saat seller mulai mendominasi pasar dan mempertahankan tekanan jual, meskipun buyer berusaha mempertahankan level support. Dalam situasi ini, trader bisa memanfaatkan Ascending Triangle Reversal untuk membuka posisi short jika mereka yakin tren bearish akan berlanjut, atau memasang stop loss jika mereka merasa tren mungkin akan berbalik arah.

Namun, perlu diingat bahwa Ascending Triangle Reversal bisa juga menjadi petunjuk yang salah dan tren bullish akan muncul. Oleh karena itu, trader harus memastikan bahwa mereka memahami situasi pasar dan mempertimbangkan faktor fundamental sebelum membuka posisi baru atau memasang stop loss. Sangat penting bagi trader untuk memantau pergerakan harga dan memastikan bahwa pola Ascending Triangle Reversal terkonfirmasi sebelum membuat keputusan trading.', NULL, NULL, NULL, NULL),
(87, NULL, 4, 'Bearish 5-0 113 161.8', 'Bearish 5-0 113 161.8 adalah pola chart dalam grafik harga kripto yang menunjukkan potensi pelemahan harga setelah tren bullish. Pola ini memiliki beberapa sifat yang khas, seperti dua wave impuls yang berlawanan arah dan tiga wave korektif yang mengarah ke bawah.

Pola Bearish 5-0 113 161.8 biasanya muncul saat pasar sedang mengalami konsolidasi setelah tren bullish yang kuat. Dalam situasi ini, trader bisa memanfaatkan pola ini untuk membuka posisi short jika mereka yakin tren bearish akan berlanjut. Stop loss bisa ditempatkan pada level resistance untuk membatasi potensi kerugian.

Namun, perlu diingat bahwa pola Bearish 5-0 113 161.8 bisa juga menjadi petunjuk yang salah dan tren bullish akan muncul. Oleh karena itu, trader harus memastikan bahwa mereka memahami situasi pasar dan mempertimbangkan faktor fundamental sebelum membuka posisi baru atau memasang stop loss. Sangat penting bagi trader untuk memantau pergerakan harga dan memastikan bahwa pola Bearish 5-0 113 161.8 terkonfirmasi sebelum membuat keputusan trading.', NULL, NULL, NULL, NULL),
(88, NULL, 4, 'Bearish 5-0 161.8 224', 'Bearish 5-0 161.8 224 adalah pola chart dalam grafik harga kripto yang menunjukkan potensi pelemahan harga setelah tren bullish. Pola ini memiliki beberapa sifat yang khas, seperti dua wave impuls yang berlawanan arah dan empat wave korektif yang mengarah ke bawah.

Pola Bearish 5-0 161.8 224 biasanya muncul saat pasar sedang mengalami konsolidasi setelah tren bullish yang kuat. Dalam situasi ini, trader bisa memanfaatkan pola ini untuk membuka posisi short jika mereka yakin tren bearish akan berlanjut. Stop loss bisa ditempatkan pada level resistance untuk membatasi potensi kerugian.

Namun, perlu diingat bahwa pola Bearish 5-0 161.8 224 bisa juga menjadi petunjuk yang salah dan tren bullish akan muncul. Oleh karena itu, trader harus memastikan bahwa mereka memahami situasi pasar dan mempertimbangkan faktor fundamental sebelum membuka posisi baru atau memasang stop loss. Sangat penting bagi trader untuk memantau pergerakan harga dan memastikan bahwa pola Bearish 5-0 161.8 224 terkonfirmasi sebelum membuat keputusan trading.', NULL, NULL, NULL, NULL),
(89, NULL, 4, 'Bearish AB-CD', NULL, NULL, NULL, NULL, NULL),
(90, NULL, 4, 'Bearish Bat 38.2 38.2 168.2', NULL, NULL, NULL, NULL, NULL),
(91, NULL, 4, 'Bearish Bat 50 88.6 261.8', NULL, NULL, NULL, NULL, NULL),
(92, NULL, 4, 'Bearish Butterfly 38.2 127 161.8', NULL, NULL, NULL, NULL, NULL),
(93, NULL, 4, 'Bearish Butterfly 88.6 161.8 261.8', NULL, NULL, NULL, NULL, NULL),
(94, NULL, 4, 'Bearish Channel', NULL, NULL, NULL, NULL, NULL),
(95, NULL, 4, 'Bearish Crab 32.8 38.2 224', NULL, NULL, NULL, NULL, NULL),
(96, NULL, 4, 'Bearish Crab 61.8 88.6 361.8', NULL, NULL, NULL, NULL, NULL),
(97, NULL, 4, 'Bearish Cypher 32.8 127.2', NULL, NULL, NULL, NULL, NULL),
(98, NULL, 4, 'Bearish Cypher 61.8 141.4', NULL, NULL, NULL, NULL, NULL),
(99, NULL, 4, 'Bearish Deep Crab 38.2 200', NULL, NULL, NULL, NULL, NULL),
(100, NULL, 4, 'Bearish Deep Crab 88.6 361.8', NULL, NULL, NULL, NULL, NULL),
(101, NULL, 4, 'Bearish Flag', NULL, NULL, NULL, NULL, NULL),
(102, NULL, 4, 'Bearish Gartley 38.2 127.2', NULL, NULL, NULL, NULL, NULL),
(103, NULL, 4, 'Bearish Gartley 88.6 161.8', NULL, NULL, NULL, NULL, NULL),
(104, NULL, 4, 'Bearish Megaphone', NULL, NULL, NULL, NULL, NULL),
(105, NULL, 4, 'Bearish Pennant Continuation', NULL, NULL, NULL, NULL, NULL),
(106, NULL, 4, 'Bearish Pennant Reversal', NULL, NULL, NULL, NULL, NULL),
(107, NULL, 4, 'Bearish Rectangle Continuation', NULL, NULL, NULL, NULL, NULL),
(108, NULL, 4, 'Bearish Rectangle Reversal', NULL, NULL, NULL, NULL, NULL),
(109, NULL, 4, 'Bearish Rounding Top', NULL, NULL, NULL, NULL, NULL),
(110, NULL, 4, 'Bearish Shark 88.6', NULL, NULL, NULL, NULL, NULL),
(111, NULL, 4, 'Bearish Shark 113', NULL, NULL, NULL, NULL, NULL),
(112, NULL, 4, 'Bearish Symmetrical Triangle Continuation', NULL, NULL, NULL, NULL, NULL),
(113, NULL, 4, 'Bearish Symmetrical Triangle Reversal', NULL, NULL, NULL, NULL, NULL),
(114, NULL, 4, 'Bearish Three Drives', NULL, NULL, NULL, NULL, NULL),
(115, NULL, 4, 'Broadening Bottom Continuation', NULL, NULL, NULL, NULL, NULL),
(116, NULL, 4, 'Broadening Bottom Reversal', NULL, NULL, NULL, NULL, NULL),
(117, NULL, 4, 'Broadening Top Continuation', NULL, NULL, NULL, NULL, NULL),
(118, NULL, 4, 'Broadening Top Reversal', NULL, NULL, NULL, NULL, NULL),
(119, NULL, 4, 'Bullish 5-0 113 161.8', NULL, NULL, NULL, NULL, NULL),
(120, NULL, 4, 'Bullish 5-0 161.8 224', NULL, NULL, NULL, NULL, NULL),
(121, NULL, 4, 'Bullish AB CD', NULL, NULL, NULL, NULL, NULL),
(122, NULL, 4, 'Bullish Bat 38.2 38.2 168.2', NULL, NULL, NULL, NULL, NULL),
(123, NULL, 4, 'Bullish Bat 50 88.6 261.8', NULL, NULL, NULL, NULL, NULL),
(124, NULL, 4, 'Bullish Butterfly 38.2 127 161.8', NULL, NULL, NULL, NULL, NULL),
(125, NULL, 4, 'Bullish Butterfly 88.6 161.8 261.8 ', NULL, NULL, NULL, NULL, NULL),
(126, NULL, 4, 'Bullish Channel', NULL, NULL, NULL, NULL, NULL),
(127, NULL, 4, 'Bullish Crab 32.8 38.2 224', NULL, NULL, NULL, NULL, NULL),
(128, NULL, 4, 'Bullish Crab 61.8 88.6 361.8', NULL, NULL, NULL, NULL, NULL),
(129, NULL, 4, 'Bullish Cypher 32.8 127.2', NULL, NULL, NULL, NULL, NULL),
(130, NULL, 4, 'Bullish Cypher 61.8 141.4', NULL, NULL, NULL, NULL, NULL),
(131, NULL, 4, 'Bullish Deep Crab 38.2 200', NULL, NULL, NULL, NULL, NULL),
(132, NULL, 4, 'Bullish Deep Crab 88.6 361.8', NULL, NULL, NULL, NULL, NULL),
(133, NULL, 4, 'Bullish Flag', NULL, NULL, NULL, NULL, NULL),
(134, NULL, 4, 'Bullish Gartley 38.2 127.2', NULL, NULL, NULL, NULL, NULL),
(135, NULL, 4, 'Bullish Gartley 88.6 161.8', NULL, NULL, NULL, NULL, NULL),
(136, NULL, 4, 'Bullish Megaphone', NULL, NULL, NULL, NULL, NULL),
(137, NULL, 4, 'Bullish Pennant Continuation', NULL, NULL, NULL, NULL, NULL),
(138, NULL, 4, 'Bullish Pennant Reversal', NULL, NULL, NULL, NULL, NULL),
(139, NULL, 4, 'Bullish Rectangle Continuation', NULL, NULL, NULL, NULL, NULL),
(140, NULL, 4, 'Bullish Rectangle Reversal', NULL, NULL, NULL, NULL, NULL),
(141, NULL, 4, 'Bullish Rounding Top', NULL, NULL, NULL, NULL, NULL),
(142, NULL, 4, 'Bullish Shark 88.6', NULL, NULL, NULL, NULL, NULL),
(143, NULL, 4, 'Bullish Shark 113', NULL, NULL, NULL, NULL, NULL),
(144, NULL, 4, 'Bullish Symmetrical Triangle Continuation', NULL, NULL, NULL, NULL, NULL),
(145, NULL, 4, 'Bullish Symmetrical Triangle Reversal', NULL, NULL, NULL, NULL, NULL),
(146, NULL, 4, 'Bullish Three Dives', NULL, NULL, NULL, NULL, NULL),
(147, NULL, 4, 'Cup and Handle', NULL, NULL, NULL, NULL, NULL),
(148, NULL, 4, 'Deep Crab', NULL, NULL, NULL, NULL, NULL),
(149, NULL, 4, 'Descending Broadening Wedge Continuation', NULL, NULL, NULL, NULL, NULL),
(150, NULL, 4, 'Descending Broadening Wedge Reversal', NULL, NULL, NULL, NULL, NULL),
(151, NULL, 4, 'Descending Triangle', NULL, NULL, NULL, NULL, NULL),
(152, NULL, 4, 'Descending Triangle Reversal', NULL, NULL, NULL, NULL, NULL),
(153, NULL, 4, 'Diamond Bottom', NULL, NULL, NULL, NULL, NULL),
(154, NULL, 4, 'Diamond Top', NULL, NULL, NULL, NULL, NULL),
(155, NULL, 4, 'Double Bottom', NULL, NULL, NULL, NULL, NULL),
(156, NULL, 4, 'Double Top', NULL, NULL, NULL, NULL, NULL),
(157, NULL, 4, 'Falling Wedge Continuation', NULL, NULL, NULL, NULL, NULL),
(158, NULL, 4, 'Falling Wedge Reversal', NULL, NULL, NULL, NULL, NULL),
(159, NULL, 4, 'Head and Shoulder', NULL, NULL, NULL, NULL, NULL),
(160, NULL, 4, 'Inverse Cup and Handle', NULL, NULL, NULL, NULL, NULL),
(161, NULL, 4, 'Inverse Head and Shoulder', NULL, NULL, NULL, NULL, NULL),
(162, NULL, 4, 'Right Angled Ascending Broadening Wedge Continuation', NULL, NULL, NULL, NULL, NULL),
(163, NULL, 4, 'Right Angled Ascending Broadening Wedge Reversal', NULL, NULL, NULL, NULL, NULL),
(164, NULL, 4, 'Right Angled Descending Broadening Wedge Continuation', NULL, NULL, NULL, NULL, NULL),
(165, NULL, 4, 'Right Angled Descending Broadening Wedge Reversal', NULL, NULL, NULL, NULL, NULL),
(166, NULL, 4, 'Rising Wedge Continuation', NULL, NULL, NULL, NULL, NULL),
(167, NULL, 4, 'Rising Wedge Reversal', NULL, NULL, NULL, NULL, NULL),
(168, NULL, 4, 'Rounding Bottom', NULL, NULL, NULL, NULL, NULL),
(169, NULL, 4, 'Triple Bottom', NULL, NULL, NULL, NULL, NULL),
(170, NULL, 4, 'Triple Top', NULL, NULL, NULL, NULL, NULL),
(171, NULL, 4, 'V Bottom Continuation', NULL, NULL, NULL, NULL, NULL),
(172, NULL, 4, 'V Bottom Reversal', NULL, NULL, NULL, NULL, NULL),
(173, NULL, 4, 'V Top', NULL, NULL, NULL, NULL, NULL),
(174, NULL, 5, 'Accumulation Distribution Line', 'Accumulation Distribution Line (ADL) adalah indikator teknikal yang digunakan untuk mengukur sentimen pasar dalam pasar kripto. Indikator ini mengikuti pergerakan harga dan volume untuk membuat keputusan trading. ADL didasarkan pada teori bahwa harga dan volume saling berkaitan dan bahwa harga akan mencerminkan volume.

Untuk menghitung ADL, trader menambahkan volume untuk setiap bar harga saat harga bergerak naik dan mengurangi volume saat harga bergerak turun. Hasil dari perhitungan ini adalah garis ADL yang mengukur aktivitas pembelian dan penjualan pada pasar kripto. Bila garis ADL meningkat, ini menunjukkan bahwa pembelian sedang dominan dan bahwa harga mungkin akan bergerak naik. Sebaliknya, bila garis ADL menurun, ini menunjukkan bahwa penjualan sedang dominan dan bahwa harga mungkin akan bergerak turun.

Trader dapat memanfaatkan ADL untuk membantu menentukan kemana harga akan bergerak dan kapan waktu terbaik untuk membuka atau menutup posisi. ADL bisa digabungkan dengan indikator lain dan analisis grafik untuk memperkuat sinyal trading. Namun, perlu diingat bahwa ADL adalah indikator lagging dan tidak selalu akurat, oleh karena itu trader harus memastikan bahwa mereka memahami situasi pasar dan mempertimbangkan faktor fundamental sebelum membuat keputusan trading.', NULL, NULL, NULL, NULL),
(175, NULL, 5, 'Average Directional Index', 'Average Directional Index (ADX) adalah indikator teknikal yang digunakan untuk mengukur kekuatan trend dalam pasar kripto. Indikator ini membantu trader menentukan apakah suatu pasar sedang mengalami trend bullish (naik) atau trend bearish (turun), dan seberapa kuat trend tersebut. ADX menggunakan rata-rata pergerakan harga untuk menentukan apakah suatu trend sedang membaik atau memburuk.

Indikator ADX terdiri dari tiga garis: ADX sendiri, garis positif (+DI) dan garis negatif (-DI). Garis positif (+DI) mengukur kekuatan uptrend, sementara garis negatif (-DI) mengukur kekuatan downtrend. Jika ADX berada di atas 20, itu menunjukkan bahwa pasar sedang mengalami trend kuat, baik uptrend atau downtrend. Namun, jika ADX berada di bawah 20, itu menunjukkan bahwa pasar sedang sideways dan trendnya lemah.

ADX juga bisa digunakan untuk memprediksi pembalikan tren. Jika ADX meningkat dan berada di atas 20, itu menunjukkan bahwa trend saat ini akan terus berlangsung. Namun, jika ADX menurun setelah mencapai level tertentu, itu bisa menjadi tanda bahwa trend akan segera berbalik arah. Oleh karena itu, ADX adalah alat yang berguna bagi trader kripto yang ingin menentukan kekuatan trend dan memprediksi pembalikan tren.', NULL, NULL, NULL, NULL),
(176, NULL, 5, 'Average True Range', 'Average True Range (ATR) adalah indikator volatilitas yang digunakan dalam analisis teknikal untuk menentukan tingkat pergerakan harga dalam suatu aset, termasuk mata uang kripto. ATR dikembangkan oleh Welles Wilder dan dipresentasikan dalam bentuk grafik yang berdampingan dengan harga.

ATR membantu trader menentukan volatilitas yang tepat dari suatu aset dan memberikan petunjuk mengenai tingkat risiko yang terkait dengan investasi tersebut. Indikator ini membantu trader memperkirakan jarak antara harga terendah dan harga tertinggi dalam periode waktu tertentu, dan memberikan informasi mengenai apakah harga sedang memiliki pergerakan yang kuat atau tidak.

ATR dapat digunakan sebagai alat bantu dalam menentukan stop loss dan target profit. Jika ATR menunjukkan volatilitas yang tinggi, trader dapat mempertimbangkan untuk menempatkan stop loss pada jarak yang lebih besar dari harga saat ini. Sebaliknya, jika ATR menunjukkan volatilitas yang rendah, trader dapat mempertimbangkan untuk menempatkan stop loss pada jarak yang lebih pendek. ATR juga dapat digunakan untuk memperkirakan waktu yang dibutuhkan untuk mencapai target profit.', NULL, NULL, NULL, NULL),
(177, NULL, 5, 'Bollinger Bands', 'Bollinger Bands adalah salah satu indikator teknikal populer yang digunakan dalam analisis pasar kripto. Indikator ini diciptakan oleh John Bollinger pada tahun 1980-an dan sejak saat itu telah menjadi alat yang banyak digunakan oleh trader dan investor untuk memahami volatilitas pasar. Bollinger Bands menggunakan moving average sebagai dasar dan menambahkan dua band yang bergerak seiring dengan pergerakan harga.

Bollinger Bands terdiri dari tiga komponen: garis tengah yang biasanya merupakan moving average sederhana, upper band yang merupakan garis atas, dan lower band yang merupakan garis bawah. Garis tengah ini memberikan informasi tentang trend pasar, sementara upper dan lower band membantu trader mengidentifikasi volatilitas pasar. Upper dan lower band ditempatkan pada jarak yang sama di atas dan di bawah garis tengah, dengan jarak yang ditentukan oleh deviasi standar harga.

Indikator Bollinger Bands dapat digunakan untuk memahami volatilitas pasar dan mengidentifikasi peluang trading. Misalnya, jika harga kripto bergerak dekat dengan upper band, itu dapat menunjukkan bahwa pasar sedang overbought, yang berarti bahwa harga mungkin segera turun. Sebaliknya, jika harga bergerak dekat dengan lower band, itu dapat menunjukkan bahwa pasar sedang oversold, yang berarti bahwa harga mungkin segera naik. Namun, perlu diingat bahwa Bollinger Bands hanya merupakan alat bantu dan tidak dapat digunakan sebagai sinyal trading tunggal.', NULL, NULL, NULL, NULL),
(178, NULL, 5, 'Moving Average Convergence Divergence', 'Moving Average Convergence Divergence (MACD) adalah indikator teknikal yang digunakan dalam analisis pasar kripto untuk menentukan momentum harga dan mengidentifikasi tren. Indikator ini terdiri dari tiga garis: garis MACD, garis sinyal, dan histogram.

Garis MACD didapat dari perbedaan antara dua moving average (MA) yang berbeda periode, biasanya 26 dan 12 hari. Garis sinyal didapat dari 9-day exponential moving average (EMA) dari garis MACD. Histogram adalah visualisasi dari perbedaan antara garis MACD dan garis sinyal.

Bila garis MACD bergerak di atas garis sinyal, ini dapat menunjukkan bahwa momentum harga sedang bullish dan memungkinkan untuk membuka posisi beli. Sebaliknya, bila garis MACD bergerak di bawah garis sinyal, ini dapat menunjukkan bahwa momentum harga sedang bearish dan memungkinkan untuk membuka posisi jual. Oleh karena itu, MACD sering digunakan sebagai alat bantu dalam proses pengambilan keputusan untuk melakukan transaksi beli atau jual.', NULL, NULL, NULL, NULL),
(179, NULL, 5, 'Parabolic Sar', 'Parabolic SAR (Stop and Reverse) adalah indikator teknikal yang digunakan untuk memprediksi pergerakan harga di pasar kripto. Indikator ini diciptakan oleh Welles Wilder dan pertama kali diperkenalkan dalam bukunya, "New Concepts in Technical Trading Systems". Parabolic SAR menggunakan titik-titik yang berubah posisi dan jarak antar titik untuk menentukan tren pasar dan menentukan titik entry dan exit.

Parabolic SAR bekerja dengan menentukan titik-titik pada grafik harga yang mengindikasikan pergerakan harga dalam tren yang berbeda. Titik-titik tersebut akan berubah posisi saat tren berubah. Jika tren menjadi bullish, titik-titik akan berubah posisi dan bergerak ke bawah. Sebaliknya, jika tren menjadi bearish, titik-titik akan berubah posisi dan bergerak ke atas.

Penggunaan Parabolic SAR dalam analisis kripto sangat berguna karena membantu trader memahami pergerakan harga dan membuat keputusan trading yang lebih baik. Namun, indikator ini seharusnya tidak digunakan sebagai alat trading tunggal karena memiliki beberapa kelemahan, seperti memberikan sinyal yang terlambat dan tidak dapat mengidentifikasi tren secara tepat setiap waktu. Oleh karena itu, disarankan untuk menggabungkan Parabolic SAR dengan indikator teknikal lain untuk memperoleh hasil yang lebih baik.', NULL, NULL, NULL, NULL),
(180, NULL, 5, 'Relative Strength Index', 'Relative Strength Index (RSI) adalah indikator teknikal yang digunakan untuk mengukur kekuatan suatu mata uang kripto dalam jangka pendek. RSI membandingkan antara kenaikan harga dengan penurunan harga, dan menghitung nilai RSI berdasarkan rasio antara rata-rata kenaikan dan rata-rata penurunan harga. Indeks ini mengukur kemungkinan overbought atau oversold dari sebuah mata uang kripto.

RSI memiliki skala 0 hingga 100, di mana nilai di bawah 30 menunjukkan bahwa mata uang kripto tersebut sedang oversold, sementara nilai di atas 70 menunjukkan bahwa mata uang kripto tersebut sedang overbought. Trader dapat menggunakan informasi ini untuk menentukan waktu yang tepat untuk membeli atau menjual mata uang kripto.

RSI bisa digunakan secara bersamaan dengan analisis grafik dan indikator teknikal lainnya untuk membantu trader dalam membuat keputusan trading. Namun, harus diingat bahwa RSI hanya memberikan gambaran dari momentum harga dan tidak memberikan informasi tentang tren jangka panjang atau fundamental mata uang kripto. Oleh karena itu, penting bagi trader untuk melakukan analisis dan menggabungkan beberapa indikator teknikal untuk membuat keputusan trading yang tepat.', NULL, NULL, NULL, NULL),
(181, NULL, 5, 'Simple Moving Average', 'Simple Moving Average (SMA) adalah salah satu indikator teknikal paling populer dalam analisis pasar kripto. SMA mengukur rata-rata harga aset dalam periode waktu tertentu, membantu trader menentukan tren pasar dan memprediksi arah pergerakan harga.

SMA dapat digunakan untuk menentukan tren jangka panjang dan jangka pendek. Misalnya, jika SMA 50 memotong SMA 200 dari bawah ke atas, ini sering diinterpretasikan sebagai sinyal beli dan indikasi tren bullish. Begitu juga sebaliknya, jika SMA 50 memotong SMA 200 dari atas ke bawah, ini dapat diinterpretasikan sebagai sinyal jual dan indikasi tren bearish.

Menggunakan SMA bersama dengan indikator teknikal lain dapat membantu trader membuat keputusan yang lebih baik dan memperkuat analisis pasar. Namun, perlu diingat bahwa SMA tidak selalu akurat dan tidak bisa digunakan sebagai satu-satunya metode dalam membuat keputusan trading. Trader harus mempertimbangkan faktor fundamental dan berbagai faktor pasar lainnya sebelum membuat keputusan trading berdasarkan SMA.', NULL, NULL, NULL, NULL),
(182, NULL, 5, 'Stochastic', NULL, NULL, NULL, NULL, NULL),
(183, NULL, 5, 'Stochastic RSI', NULL, NULL, NULL, NULL, NULL),
(184, NULL, 5, 'Trading Volume', NULL, NULL, NULL, NULL, NULL),
(185, NULL, 5, 'Volume Profile', NULL, NULL, NULL, NULL, NULL),
(186, NULL, 5, 'Volume Weighed Average Price', NULL, NULL, NULL, NULL, NULL),
(187, NULL, 5, 'Zigzag', NULL, NULL, NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
