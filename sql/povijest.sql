-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Feb 05, 2024 at 06:07 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zerjav`
--

-- --------------------------------------------------------

--
-- Table structure for table `povijest`
--

CREATE TABLE `povijest` (
  `stavka` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `vozilo` varchar(255) NOT NULL,
  `datum` date NOT NULL,
  `kilometri` varchar(255) NOT NULL,
  `naslov` text NOT NULL,
  `opis` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `povijest`
--

INSERT INTO `povijest` (`stavka`, `id`, `vozilo`, `datum`, `kilometri`, `naslov`, `opis`) VALUES
(18, 5, 'Kamion', '2020-02-14', '192568km', 'Promjena prvih guma', 'Promijenjene prve vodeće gume(Michelin)'),
(19, 5, 'Kamion', '2020-08-18', '264776km', 'Novi akumulatori', 'Stavljeni novi akumulatori BANNER. Nabavljeno u AutoHrvatski'),
(20, 5, 'Prikolica', '2020-08-19', '264776km', 'Promjene gume prve osovine', 'Promijenjene gume na prvoj osovini(Apollo)'),
(21, 5, 'Prikolica', '2021-03-30', '343463km', 'Gume prva i treća osovina', 'Promijenjene gume na prvoj i trećoj osovini(Apollo)'),
(25, 5, 'Kamion', '2021-05-28', '367431km', 'Dopuna klime', 'Nadopunjena klima u kamionu'),
(26, 5, 'Kamion', '2021-10-11', '419417km', 'Vanjski filter getribe', 'Promijenjen vanjski filter getribe'),
(27, 5, 'Kamion', '2022-02-12', '466222km', 'Pranje modine', 'Oprana modina'),
(28, 5, 'Prikolica', '2022-03-26', '483954km', 'Gume prve osovine', 'Promijenjena gume na prvoj osovini(Apollo)'),
(29, 5, 'Kamion', '2022-09-17', '548068km', 'Prve gume', 'Nove vodeće gume(Michelin)'),
(30, 5, 'Kamion', '2022-09-19', '548071km', 'Kuplunga, NOX, gume', 'Novo: set kuplunge(Valeo), semering radilice i lager, vilica mjenjača, cijevi vode, NOX senzor motora(Feros,MAN), vodeće gume(Michelin)'),
(32, 5, 'Prikolica', '2023-03-12', '582694km', 'Kočnice na prikolici', 'Promijenjene kočnice na prikolici'),
(33, 5, 'Kamion', '2023-08-19', '642156km', 'NOX auspuha', 'Novi NOX auspuha(original AutoHrvatska)'),
(34, 5, 'Prikolica', '2023-08-19', '642156km', 'Ventil podizne', 'Promijenjen ventil podizne osovine na prikolici'),
(35, 5, 'Kamion', '2023-11-11', '675238km', 'Nova modina', 'Stavljena nova modina(Feros)'),
(37, 10, 'Kamion', '2023-12-29', '283279km', 'POLUGENERALKA', 'Novo: karike, leteći i glavni ležajevi'),
(38, 3, 'Kamion', '2022-12-20', '418000km', 'Preventivno(kupljen kamion)', 'Pranje modine i DPF-a, provjera ventila getribe, štelanje ventila, nova D cijel vode intardera i novi remen'),
(39, 3, 'Kamion', '2023-09-20', '473155km', 'Ležajevi', 'Novo: glavni i leteći ležajevi(original MAN)'),
(41, 9, 'Kamion', '2023-11-08', '278893km', 'Novi akumulatori', 'Promijenjeni akumulatori'),
(43, 4, 'Kamion', '2023-12-26', '440000km', 'Ležajevi', 'Novi glavni i leteći ležajevi'),
(44, 4, 'Kamion', '2023-12-30', '440216km', 'Plovak ADBLUE', 'Novi adblue plovak(original AutoHrvatska)'),
(45, 22, 'Kamion', '2021-03-01', '246450km', 'Štelanje ventila', 'Šantek naštelao ventile'),
(46, 22, 'Kamion', '2021-04-05', '253878km', 'Novi akumulatori', 'Novi akumulatori(AutoHrvatska)'),
(47, 22, 'Kamion', '2021-04-25', '265653km', 'Ventil turbine', 'Promijenjen ventil turbine'),
(48, 22, 'Kamion', '2021-05-13', '270605km', 'Pranje modine', 'Oprana modina'),
(49, 22, 'Prikolica', '2021-05-28', '285000km', 'Punjenje klime', 'Napunjena klima na kamionu'),
(50, 22, 'Kamion', '2021-05-24', '322000km', 'Prve gume', 'Promijenjene vodeće gume'),
(51, 22, 'Kamion', '2021-10-10', '328400km', 'Nove pogonske gume', 'Stavljene nove pogonske gume'),
(52, 22, 'Prikolica', '2022-08-20', '449290km', 'Diskovi,lageri,pločice', 'Promijenjeni diskovi, lageri i pločice. Nove gume na prvoj osovini'),
(53, 22, 'Kamion', '2023-06-09', '558617km', 'POLUGENERALKA', 'Zamjena glavnih i letećih ležajeva. Promijenjeno kvačilo, cijevi intardera(L+D), remen i zatezač'),
(54, 22, 'Kamion', '2023-07-07', '568746km', 'NOX motor', 'Promijenjen NOX motora(HELLA scuba)'),
(55, 22, 'Kamion', '2023-09-04', '584363km', 'Nova modina', 'Promijenjena modina'),
(56, 22, 'Kamion', '2023-11-04', '607651km', 'NOX motor', 'Novi NOX motora(original MAN(Europart))'),
(57, 14, 'Kamion', '2021-05-03', '153885km', 'Novi akumulatori', 'Novi akumulatori(Banner AutoHrvatska)'),
(58, 14, 'Kamion', '2021-11-17', '224036km', 'Prve gume', 'Promijenjene prve vodeće gume(Michelin)'),
(59, 14, 'Kamion', '2022-01-29', '249249km', 'Senzor turbine', 'Promijenjen senzor turbine'),
(60, 14, 'Kamion', '2023-12-07', '474801km', 'NOX motor', 'Novi NOX motora(Europart)'),
(61, 14, 'Prikolica', '2023-12-26', '483000km', 'Pločice', 'Nove pločice'),
(62, 14, 'Kamion', '2023-12-26', '483000km', 'Ležajevi', 'Novi glavni i leteći ležajevi'),
(65, 2, 'Prikolica', '2022-07-20', '435585km', 'Nove felge i gume', 'Sve felge nove i nove gume na prvoj i trećoj osovini'),
(66, 2, 'Kamion', '2022-05-15', '408157km', 'Nova modina', 'Nova modina(Feros)'),
(67, 2, 'Prikolica', '2022-10-08', '470000km', 'Diskovi i pločice', 'Novi diskovi i pločice'),
(68, 2, 'Kamion', '2023-02-04', '510730km', 'Mala turbina', 'Mijenjana mala turbina'),
(69, 2, 'Kamion', '2022-02-18', '516000km', 'ADBLUE plovak', 'Novi ADBLUE plovak'),
(70, 2, 'Kamion', '2023-05-20', '537000km', 'GENERALKA', 'Novo: radilica, glavni i leteći ležajevi, uljna pumpa, kuplunga set, cijev retardera(desna)'),
(71, 2, 'Kamion', '2023-08-26', '570748km', 'Birač', 'Popravljen birač'),
(72, 2, 'Prikolica', '2023-10-14', '583846km', 'Zadnja osovina', 'Nove gume na zadnjoj osovini'),
(73, 2, 'Prikolica', '2023-12-01', '601000km', 'Prva osovina', 'Nove guma na prvoj osovini'),
(74, 2, 'Kamion', '2023-11-03', '592067km', 'Kočnice', 'Zamijenjene prednje i zadnje kočnice'),
(75, 16, 'Kamion', '2022-02-05', '123333km', 'Lamela,vilica,ležaj', 'Novo: lamela(original MAN), vilica i ležaj mjenjača'),
(76, 16, 'Kamion', '2023-01-12', '183000km', 'AGR ventil', 'Promijenjen AGR ventil(AutoHrvatska, garancija)'),
(78, 8, 'Kamion', '2021-01-02', '113288km', 'Pogonske gume', 'Stavljene nove pogonske gume'),
(79, 8, 'Prikolica', '2021-01-02', '113288km', 'Gume,diskovi,pločice', 'Stavljene nove gume(Apollo), novi diskovi i pločice i podmazani ležajevi'),
(80, 8, 'Kamion', '2021-06-11', '168937km', 'Ventil turbine', 'Promijenjen ventil turbine'),
(81, 8, 'Kamion', '2021-11-09', '223924km', 'Prve gume', 'Nove vodeće gume(Good Year)'),
(83, 8, 'Kamion', '2023-02-08', '389829km', 'Nova modina', 'Stavljena nova modina'),
(84, 8, 'Kamion', '2023-09-10', '437593km', 'Novi akumulatori', 'Novi akumulatori(Banner, AutoHrvatska)'),
(86, 6, 'Kamion', '2015-03-20', '100701km', 'Novi akumulatori', 'Novi akumulatori'),
(87, 6, 'Kamion', '2018-02-24', '409000km', 'Lager getriba', 'Novi lager getribe'),
(88, 6, 'Prikolica', '2018-07-11', '441000km', 'Kočnice', 'Nove kočnice'),
(89, 6, 'Kamion', '2019-01-28', '508589km', 'Prve gume', 'Nove vodeće gume'),
(90, 6, 'Kamion', '2019-08-03', '574000km', 'Pločice', 'Nove pločice'),
(91, 6, 'Kamion', '2020-02-06', '631839km', 'Interkuler', 'Stavljen novi interkuler'),
(92, 6, 'Kamion', '2019-12-23', '619442km', 'Remen i rolice', 'Novi natezač remena i nove rolice'),
(93, 6, 'Prikolica', '2020-04-23', '658381km', 'Gume i kočnice', 'Nove gume na zadnjoj osovini(Michelin), zamjena pločica na sve 3 osovine'),
(94, 6, 'Prikolica', '2020-09-15', '708688km', 'Prva osovina', 'Stavljene nove gume na prvoj osovini(Apollo)'),
(95, 6, 'Kamion', '2020-11-28', '736874km', 'Novi akumulatori', 'Zamijenjeni akumulatori(AutoHrvatska)'),
(96, 6, 'Kamion', '2021-01-05', '747821km', 'POLUGENERALKA', 'POLUGENERALKA'),
(97, 6, 'Kamion', '2021-05-03', '770607km', 'Vilica i lager', 'Nova vilica i lager(valeo)'),
(98, 6, 'Kamion', '2022-03-03', '859940km', 'GENERALKA', 'Novo: radilica, kuplung set i vodna pumpa'),
(99, 9, 'Kamion', '2023-12-30', '295315km', 'POLUGENERALKA', 'Nove karike i ležajevi'),
(100, 18, 'Kamion', '2019-10-19', '192000km', 'Pogonske gume i akumulatori', 'Nove pogonske gume(Michelin) i novi akumulatori(AutoHrvatska)'),
(101, 18, 'Kamion', '2020-08-19', '288000km', 'Prve gume', 'Nove vodeće gume(Good Year)'),
(102, 18, 'Prikolica', '2020-09-15', '299645km', 'Zadnja osovina', 'Promijenjene gume na zadnjoj osovini(Apollo)'),
(103, 18, 'Prikolica', '2020-12-24', '334017km', 'Prva osovina', 'Promijenjene gume na prvoj osovini(Apollo)'),
(104, 18, 'Kamion', '2022-06-20', '512000km', 'Punjenje klime', 'Napunjena klima'),
(105, 18, 'Kamion', '2022-10-17', '555838km', 'Kuplunga i prve gume', 'Novo: set kuplunge, vilica, lager i semening radilice i prve vodeće gume(Michelin)'),
(106, 18, 'Kamion', '2023-07-25', '631561km', 'Ulji kiler getribe i birač', 'Novi uljni kiler getribe. Nove podloške i štift u biraču getribe'),
(107, 18, 'Kamion', '2023-09-27', '653210km', 'Kočnice mjenjača', 'Nove kočnice mjenjača(original MAN)'),
(108, 18, 'Prikolica', '2023-12-22', '682000km', 'Kočnice', 'Nove kočnice'),
(109, 21, 'Kamion', '2019-12-31', '320909km', 'Štelanje ventila i cijev volana', 'Štelanje ventila i nova cijev volana'),
(110, 21, 'Kamion', '2020-01-03', '321089km', 'Prve gume', 'Nove vodeće gume(Michelin)'),
(111, 21, 'Kamion', '2020-06-12', '367591km', 'Cijevi intardera i kuplunga', 'Nove cijevi intardera i novi set kuplunga(komplet)'),
(112, 21, 'Prikolica', '2020-09-19', '402963km', 'Zadnja osovina', 'Nove gume na zadnjoj osovini(Apollo)'),
(113, 21, 'Kamion', '2021-06-11', '500000km', 'Kiler klime', 'Novi kiler klime i punjenje klime'),
(114, 21, 'Kamion', '2021-06-14', '500000km', 'Diza ADBLUE', 'Nova diza adblue'),
(115, 21, 'Kamion', '2021-07-23', '515408km', 'Pogonske gume', 'Nove pogonske gume'),
(116, 21, 'Prikolica', '2022-03-01', '580000km', 'Pločice', 'Nove pločice'),
(117, 21, 'Kamion', '2022-09-09', '647203km', 'NOX i mjenjač', 'Novo: NOX senzori auspuha i motora(Feros), hladnjak ulja mjenjača, potisni ležaj, lamelice mjenjača'),
(118, 21, 'Kamion', '2022-12-23', '678296km', 'Kočnice', 'Nove kočnice'),
(119, 21, 'Kamion', '2023-05-04', '721000km', 'Nova modina', 'Nova modina(Feros)'),
(120, 21, 'Kamion', '2023-05-19', '725685km', 'Ležajevi', 'Novi glavni(Kolben Schmidt) i leteći(original MAN) ležajevi'),
(121, 21, 'Kamion', '2023-08-17', '746051km', 'Birač', 'Popravak birača, štift, pločice i semening'),
(122, 24, 'Kamion', '2017-11-01', '167700km', 'Nove pogonske gume', 'Nove pogonske gume'),
(123, 24, 'Kamion', '2018-11-01', '301500km', 'Prve gume i akumulatori', 'Nove vodeće gume i novi akumulatori'),
(124, 24, 'Prikolica', '2019-12-17', '436500km', 'Kočnice', 'Nove kočnice, širenje zadnjih stupova'),
(125, 24, 'Kamion', '2020-01-03', '436538km', 'Prve gume', 'Nove vodeće gume(Michelin)'),
(126, 24, 'Kamion', '2020-03-01', '455420km', 'Štelanje ventila', 'Štelanje ventila nakon zamjene glave '),
(127, 24, 'Kamion', '2020-08-27', '520140km', 'Klapna EGR', 'Klapna EGR(Ciak)'),
(128, 24, 'Kamion', '2020-10-01', '534280km', 'Pogonske gume', 'Nove pogonske gume(Michelin)'),
(129, 24, 'Kamion', '2021-04-03', '597000km', 'Mjenjač', 'Novi ležaj mjenjača(valeo) i nova vilica'),
(130, 24, 'Kamion', '2021-03-15', '589732km', 'POLUGENERALKA', 'Novi klipovi, pikse i glava motora'),
(131, 24, 'Kamion', '2021-06-06', '633470km', 'Prve gume, spone', 'Nove vodeće gume(Michelin), nova pokretna i gurajuća spona'),
(132, 24, 'Kamion', '2021-11-18', '684263km', 'Pločice, spone, cilindri', 'Nove pločice na kamionu, ruke(spone) gornje i kočioni cilindri pogon'),
(133, 24, 'Kamion', '2022-02-28', '718074km', 'Novi akumulatori', 'Novi akumulatori(AutoHrvatska)'),
(134, 24, 'Prikolica', '2022-03-19', '725166km', 'Diskovi i pločice', 'Novi diskovi i pločice, podmazani lageri'),
(135, 24, 'Kamion', '2022-11-29', '809667km', 'Popravak kompresora', 'Popravak kompresora(LUDBREG)'),
(136, 24, 'Kamion', '2023-12-13', '918221km', 'NOX motor', 'Novi NOX motora(EuroPart)'),
(137, 12, 'Kamion', '2020-04-09', '227636km', 'Prve gume', 'Nove vodeće gume(Continental)'),
(138, 12, 'Kamion', '2020-10-10', '290000km', 'Pogonske gume', 'Pogonske gume(Continental)'),
(139, 12, 'Kamion', '2021-05-16', '366370km', 'Plovak  ADBLUE i modina', 'Plovak  ADBLUE(skinut s 708 HN) i oprana modina'),
(140, 12, 'Kamion', '2021-08-12', '397423km', 'Novi akumulatori', 'Novi akumulatori(Banner AutoHrvatska)'),
(141, 12, 'Prikolica', '2021-08-26', '403048km', 'Zadnja osovina', 'Nove gume zadnja osovina(Apollo)'),
(142, 12, 'Kamion', '2021-11-27', '435971km', 'Potisni ležaj', 'Mijenjan potisni ležaj(valeo) i semening spojničke'),
(143, 12, 'Kamion', '2023-05-20', '613807km', 'Glavni i leteći ležajevi', 'Promijenjeni glavni i leteći ležajevi(original MAN)'),
(144, 12, 'Kamion', '2023-07-12', '629150km', 'Novi akumulatori', 'Novi akumulatori(Banner)'),
(145, 12, 'Kamion', '2023-07-21', '632041km', 'Birač', 'Popravljen birač(podloške i štift)'),
(146, 12, 'Kamion', '2023-08-04', '632042km', 'Nova modina', 'Promijenjena modina(komplet s novim uloškom)'),
(147, 12, 'Prikolica', '2023-10-02', '653568km', 'Kočnice i gume', 'Nove kočnice i nove gume na 1. i 3. osovini'),
(148, 20, 'Kamion', '2020-03-28', '240000km', 'Prve gume', 'Nove vodeće gume(Michelin)'),
(150, 20, 'Kamion', '2022-08-18', '541000km', 'Novi akumulatori', 'Novi akumulatori(Ciak)'),
(151, 20, 'Prikolica', '2022-02-12', '474000km', 'Kočnice', 'Promijenjene kočnice'),
(152, 20, 'Kamion', '2022-10-15', '564000km', 'Mjenjač i prve gume', 'Novo: set kuplunge, vilica mjenjača, špule(kutija iznad kardana), senzor okretaja mjenjača i nove vodeće gume(Michelin)'),
(153, 20, 'Kamion', '2023-01-29', '595000km', 'NOX auspuha', 'Stavljen novi NOX auspuha(Tokić original MAN)'),
(154, 20, 'Kamion', '2023-06-09', '640166km', 'NOX motor', 'Novi NOX motora(Tokić) i drugi EGR ventili'),
(155, 20, 'Kamion', '2023-07-12', '651892km', 'Birač', 'Popravak birača(SCANIA) i nove podloške i štift'),
(156, 19, 'Kamion', '2017-10-11', '130000km', 'Pogonske gume i akumulatori', 'Nove pogonske gume(Michelin) i novi akumulatori(MAN)'),
(157, 19, 'Prikolica', '2019-03-30', '326602km', 'Kočnice i gume', 'Prva i zadnja osovina nove kočnice i gume'),
(158, 19, 'Kamion', '2019-07-04', '362293km', 'Sedlo', 'Sedlo nove plastike'),
(159, 19, 'Kamion', '2019-08-09', '372466km', 'Kuplung kompresora', 'Novi kuplung kompresora'),
(160, 19, 'Kamion', '2019-08-20', '375594km', 'Novi akumulatori', 'Novi akumulatori(MAN-ZG)'),
(161, 19, 'Kamion', '2020-10-23', '512000km', 'Pogonske gume', 'Nove pogonske gume'),
(162, 19, 'Prikolica', '2019-09-17', '499956km', 'Zadnja osovina', 'Nove gume na zadnjoj osovini(Apollo)'),
(163, 19, 'Kamion', '2020-11-23', '524017km', 'Modina, EGR i interkuler', 'Modina(Vlašić), EGR(Šantek), visokotlačni interkuler(Intercars)'),
(164, 19, 'Prikolica', '2020-12-24', '536788km', 'Kočnice i gume', 'Nove kočnice na sve tri osovine i nove gume na prvoj osovini'),
(165, 19, 'Kamion', '2021-06-08', '599767km', 'Novi akumulatori', 'Novi akumulatori'),
(166, 19, 'Kamion', '2022-03-10', '690000km', 'Pločice', 'Nove pločice'),
(167, 19, 'Kamion', '2022-04-28', '709038km', 'Prve gume', 'Nove prve vodeće gume(Michelin)'),
(168, 19, 'Prikolica', '2022-05-28', '721218km', 'Prva i zadnja osovina', ' Nove gume prva i zadnja osovina'),
(169, 19, 'Kamion', '2022-08-08', '747729km', 'Kompresor klime i interkuler', 'Novi kompresor klime i interkuler'),
(170, 19, 'Kamion', '2022-09-13', '759326km', 'NOX senzori', 'Novi NOX motora i auspuha(Feros)'),
(171, 19, 'Kamion', '2023-07-02', '819880km', 'Sedlo i donje ruke', 'Podmazivanje sedla i zamjena donjih ruka'),
(172, 19, 'Prikolica', '2023-10-02', '850154km', 'Diskovi i pločice', 'Novi diskovi i pločice, podmazani lageri');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `povijest`
--
ALTER TABLE `povijest`
  ADD PRIMARY KEY (`stavka`),
  ADD KEY `test` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `povijest`
--
ALTER TABLE `povijest`
  MODIFY `stavka` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `povijest`
--
ALTER TABLE `povijest`
  ADD CONSTRAINT `test` FOREIGN KEY (`id`) REFERENCES `popis` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
