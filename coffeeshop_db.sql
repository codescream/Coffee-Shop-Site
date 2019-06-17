-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2019 at 03:34 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `coffeeshop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cart`
--

CREATE TABLE `tbl_cart` (
  `cart_id` int(11) NOT NULL,
  `cart_open` datetime NOT NULL,
  `cart_close` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_cart`
--

INSERT INTO `tbl_cart` (`cart_id`, `cart_open`, `cart_close`, `user_id`) VALUES
(1, '2019-06-01 23:59:30', '2019-06-14 17:25:53', 33),
(2, '2019-06-02 19:44:45', '2019-06-14 16:33:32', 32),
(4, '2019-06-02 20:24:18', '2019-06-14 16:42:23', 34),
(21, '2019-06-06 20:41:23', '2019-06-14 21:30:58', 55),
(27, '2019-06-11 01:51:44', '2019-06-14 16:52:57', 61),
(28, '2019-06-14 16:41:30', NULL, 32),
(29, '2019-06-14 16:56:32', '2019-06-14 20:09:45', 61),
(32, '2019-06-14 17:37:04', '2019-06-14 20:13:25', 33),
(36, '2019-06-14 20:12:05', '2019-06-14 20:42:47', 61),
(37, '2019-06-14 20:13:36', NULL, 33),
(38, '2019-06-14 20:43:31', '2019-06-14 21:03:27', 61),
(39, '2019-06-14 21:03:46', '2019-06-14 21:11:50', 61),
(40, '2019-06-14 21:12:02', '2019-06-14 21:15:10', 61),
(41, '2019-06-14 21:15:18', '2019-06-14 21:18:16', 61),
(42, '2019-06-14 21:18:24', '2019-06-14 21:18:56', 61),
(43, '2019-06-14 21:19:10', '2019-06-14 21:24:16', 61),
(44, '2019-06-14 21:24:27', NULL, 61),
(45, '2019-06-14 21:31:17', '2019-06-14 21:32:03', 55),
(46, '2019-06-14 21:32:59', '2019-06-14 21:33:40', 62);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_item`
--

CREATE TABLE `tbl_item` (
  `item_id` int(11) NOT NULL,
  `prod_id` int(11) NOT NULL,
  `size_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_item`
--

INSERT INTO `tbl_item` (`item_id`, `prod_id`, `size_id`, `qty`, `cart_id`) VALUES
(134, 49, 3, 1, 1),
(135, 71, 2, 1, 1),
(136, 48, 1, 1, 2),
(137, 71, 2, 1, 2),
(144, 48, 2, 2, 27),
(145, 71, 3, 1, 27),
(146, 65, 2, 1, 27),
(147, 68, 3, 2, 4),
(148, 80, 2, 2, 4),
(149, 56, 3, 2, 4),
(150, 80, 3, 2, 4),
(152, 48, 3, 2, 21),
(153, 48, 2, 1, 21),
(155, 65, 2, 3, 21),
(156, 71, 3, 1, 1),
(157, 79, 3, 2, 1),
(158, 46, 3, 1, 1),
(160, 34, 2, 2, 1),
(161, 48, 1, 2, 1),
(162, 48, 1, 1, 1),
(163, 71, 3, 1, 2),
(164, 85, 2, 1, 2),
(165, 65, 3, 1, 27),
(166, 48, 2, 1, 29),
(167, 48, 2, 1, 1),
(168, 48, 3, 1, 32),
(175, 48, 3, 1, 29),
(176, 49, 3, 1, 32),
(177, 52, 3, 1, 32),
(178, 48, 2, 1, 32),
(179, 79, 3, 1, 36),
(180, 65, 2, 1, 36),
(181, 71, 2, 1, 37),
(182, 85, 3, 1, 36),
(183, 62, 2, 1, 36),
(184, 85, 2, 3, 38),
(185, 80, 3, 1, 39),
(186, 53, 3, 1, 40),
(187, 65, 3, 1, 41),
(188, 48, 3, 1, 42),
(189, 85, 2, 1, 43),
(190, 84, 3, 2, 43),
(191, 34, 2, 1, 43),
(192, 71, 2, 1, 43),
(193, 71, 3, 2, 44),
(194, 65, 2, 1, 44),
(195, 53, 2, 2, 44),
(196, 65, 3, 1, 45),
(197, 66, 2, 2, 45),
(198, 27, 2, 2, 45),
(199, 48, 2, 1, 46);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_prodcategory`
--

CREATE TABLE `tbl_prodcategory` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_prodcategory`
--

INSERT INTO `tbl_prodcategory` (`cat_id`, `cat_name`) VALUES
(1, 'COFFEE'),
(2, 'ESPRESSO'),
(3, 'LATTE'),
(4, 'COLD_BREW'),
(5, 'TEA'),
(6, 'FROZEN'),
(7, 'BLENDED'),
(8, 'DESSERTS'),
(9, 'FRUIT_YOGURT'),
(10, 'FRAPPUCCINO'),
(11, 'REFRESHERS'),
(12, 'BAKERY'),
(13, 'BREAKFAST'),
(14, 'LUNCHBOX'),
(15, 'PRODUCTS');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `prod_id` int(11) NOT NULL,
  `prod_name` varchar(50) NOT NULL,
  `prod_desc` varchar(250) NOT NULL,
  `prod_img` varchar(120) NOT NULL,
  `prod_price` float NOT NULL,
  `prod_stock` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`prod_id`, `prod_name`, `prod_desc`, `prod_img`, `prod_price`, `prod_stock`, `cat_id`) VALUES
(26, 'original', 'original roast coffee', 'original', 1.5, 20, 1),
(27, 'Dark Roast', 'strong taste of dark roast coffee beans', 'darkRost', 1.75, 20, 1),
(30, 'cappuccino', 'Dark, rich espresso lies in wait under a smoothed and stretched layer of thick foam.', 'cappuccino', 2.5, 20, 2),
(31, 'espresso ', 'concentrated form of coffee that is served in “shots.”', 'espresso', 2, 20, 2),
(33, 'coffee_mocha', 'espresso marked ever-so-lovingly by a dollop of steamed milk', 'coffeeMocha', 2.5, 20, 3),
(34, 'cinnamon_latte', 'espresso marked ever-so-lovingly by a dollop of steamed milk and sinnammon', 'cinnamonLatte', 3, 20, 3),
(37, 'iced_tea', 'Premium black tea is lightly sweetened ', 'icedTea', 1.5, 20, 4),
(38, 'decaf', 'Savour the great tasting flavour you love, decaffeinated.', 'decaf', 1.5, 2, 1),
(39, 'americano', 'A celebration of espresso ,water poured over rich espresso ', 'americano', 2, 20, 2),
(42, 'coffee_latte', 'Delicious espresso and steamed milk ', 'coffeelatte', 2, 20, 3),
(45, 'iced_Mocchioto', 'espresso classic combines shots of espresso, whole milk and ice ', 'icedMocha', 2.25, 20, 4),
(46, 'iced_caffee', 'Iced Coffee Blend served chilled and lightly sweetened over ice', 'icedCoffee', 2, 20, 4),
(48, 'chai_tea', 'original Indian chai flavors in mind', 'chaiTea', 1.7, 20, 5),
(49, 'grean_tea', 'Green tea is a type of tea that is made from Camellia sinensis leaves ', 'greenTea', 1.8, 20, 5),
(52, 'peach_palmer', 'peach&Palmer blends  ', 'peachPalmer', 2, 20, 5),
(53, 'granite', 'semi-frozen dessert made from sugar, water and various flavorings', 'granita', 3, 20, 6),
(54, 'protein_velvet', 'Blended into a velvety-smooth concoction made from our homemade', 'proteinVelvet', 3, 20, 6),
(55, 'mocha_velvet', 'frosty cold, incredibly smooth and totally addictive', 'mochaValvet', 2.5, 20, 6),
(56, 'chocolate_chip', 'This creamy treat is sure to satisfy your cravings with chocolatey syrup, whipped topping and mini chocolate chips.', 'chocolateChip', 3, 20, 7),
(57, 'vanilla_beans', 'vanilla bean blend contains only milk, vanilla bean and ice.', 'vanillaBean', 3, 20, 7),
(58, 'matcha_green', 'Matcha comes from the same plant as green tea', 'matchaGreen', 3, 20, 7),
(59, 'mocha', ' chocolate-flavored variant of a caffè latte', 'mocha', 2, 20, 10),
(60, 'caramel', 'made with expertly roasted espresso beans', 'caramel', 3, 20, 10),
(61, 'vanilla', 'espresso, creamy steamed milk and our classic vanilla syrup.', 'vanilla', 2, 20, 10),
(62, 'dragon_drink', 'dragon fruit and ice', 'dragonDrink', 4, 20, 11),
(63, 'mango dragon drink', 'mango and dragon fruit with iced', 'mangoDragon', 4, 20, 11),
(64, 'strawberry Acai', 'Strawberry Acai Base [Water, Sugar, White Grape Juice Concentrate, Citric Acid', 'strawberryAcai', 2.5, 20, 11),
(65, 'croissant', 'A croissant is a buttery, flaky, viennoiserie pastry ', 'croissant', 1.5, 20, 12),
(66, 'everything bagel', 'topped with a mixture of every bagel topping used in the bakery', 'everythingBagel', 1.25, 20, 12),
(67, 'butter croissant', ' butter melts and gives off steam croissant', 'butterCroissant', 1.6, 20, 12),
(68, 'egg & cheese wrap', 'crunchy egg and cheese burrito wraps', 'eggCheeseWrap', 2, 20, 13),
(69, 'spinach&feta wrap', ' spinach, feta cheese and tomatoes inside a whole wheat wrap', 'spinachFetawrap', 3, 20, 13),
(70, 'whole grain oatmeal', 'Whole grains are a much better source of fiber and other important nutrients than refined grains are', 'wholeGrainOtmeal', 4, 20, 13),
(71, 'chocolate cake', 'cake flavored with melted chocolate, cocoa powder', 'chocolateCake', 3, 20, 8),
(78, 'chocolate chip cookie', 'Graves Wakefield added chopped up bits from a Nestlé semi-sweet chocolate bar into a cookie.', 'chocolateChip', 1.5, 20, 8),
(79, 'brownies', 'chocolate brownie is a square, baked, chocolate dessert', 'brawnies', 1, 20, 8),
(80, 'fruit salad', ' dish consisting of various kinds of fruit, sometimes served in a liquid', 'fruitSalad', 4, 20, 9),
(81, 'Greek & honey yogurt', 'Greek yogurt with honey and walnuts', 'greekHony', 4, 20, 9),
(82, 'strawberry parfait', 'creamy and smooth strawberry parfaits taste as good as they look', 'strawbaryParfait', 2.5, 20, 9),
(83, 'fruit & cheese box', 'Brie, Gouda, two-year aged Cheddar cheeses, nine-grain crackers, apples, dried cranberries and roasted almonds', 'cheeseFruit', 5, 20, 14),
(84, 'egg & cheese box', 'Cage free hardboiled eggs, sliced tart apples, grapes and white Cheddar cheese come together with multigrain', 'eggCheese', 7, 20, 14),
(85, 'arabica beans', 'Coffea arabica, also known as the Arabian coffee, \"coffee shrub of Arabia\", \"mountain coffee\"', 'arabica', 16, 20, 15),
(86, 'robusta beans', 'Robusta coffee is coffee made from the Coffea canephora plant', 'robusta', 16, 20, 15),
(87, 'Turkish beans', 'Turkish coffee is made from medium to dark roasted Arabica coffee beans that are ground to a super-fine', 'turkish', 17, 20, 15),
(88, 'Liberace beans', 'species of flowering plant in the family Rubiaceae from which coffee is produced', 'liberace', 18, 20, 15),
(89, 'excelsa beans ', 'cultivated in medium altitudes and possess “teardrop” shape', 'excelsa', 20, 20, 15),
(90, 'Thai chicken box', 'Grilled chicken breast is tossed in a savory peanut-coconut sauce, topped with a lively chile-lime veggie slaw, red bell peppers, lettuce', 'chickenThai', 7, 20, 14);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sizemultiplier`
--

CREATE TABLE `tbl_sizemultiplier` (
  `size_id` int(11) NOT NULL,
  `multiplier_desc` varchar(20) NOT NULL,
  `multiplier_size` char(1) NOT NULL,
  `multiplier` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_sizemultiplier`
--

INSERT INTO `tbl_sizemultiplier` (`size_id`, `multiplier_desc`, `multiplier_size`, `multiplier`) VALUES
(1, 'Small', 'S', 0.5),
(2, 'Medium', 'M', 0.7),
(3, 'Large', 'L', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `password` varchar(1500) NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'A'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`user_id`, `username`, `password`, `status`) VALUES
(32, 'ogilom@gmail.com', '$2y$09$yZoI1nEW9Tw2BwR.KmBR9OtV1DAniOtySZGUb23pVDRJa3atePT8C', 'A'),
(33, 'markogilo@yahoo.com', '$2y$10$9CUl.f61coxICJD59HuvfuAIt9aFJ3AHHnOZZGR5NS/rxapg9fSxi', 'A'),
(34, 'osama@gmail.com', '$2y$10$tZbPTswgVw0UnUfNk4fLueRAfMgPJDr0cQOh5tyihK.ab79bwMh..', 'A'),
(55, 'osama@yahoo.com', '$2y$10$f2t1r6CbE5rloQjNVZoW1uzPJpfZK1HgzuMqGhUyBWs6JNdAI8flm', 'A'),
(61, 'test@test.ca', '$2y$10$6phdy8OQw8QAmC.349m8J.J25MKGo0AlBC1qDRuZ5cmbWGdTmpFE.', 'A'),
(62, 'michaellewis@gmail.ca', '$2y$10$J0qudS8RRYwhScCtebwai.YsKsp0C2x2.yjZg1MqShIsS5QJjSJZy', 'A');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_item`
--
ALTER TABLE `tbl_item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `tbl_item_product_fk` (`prod_id`),
  ADD KEY `tbl_item_cart_fk` (`cart_id`),
  ADD KEY `tbl_item_sizemulti_fk` (`size_id`);

--
-- Indexes for table `tbl_prodcategory`
--
ALTER TABLE `tbl_prodcategory`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`prod_id`),
  ADD KEY `tbl_product_category_fk` (`cat_id`);

--
-- Indexes for table `tbl_sizemultiplier`
--
ALTER TABLE `tbl_sizemultiplier`
  ADD PRIMARY KEY (`size_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `tbl_item`
--
ALTER TABLE `tbl_item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;

--
-- AUTO_INCREMENT for table `tbl_prodcategory`
--
ALTER TABLE `tbl_prodcategory`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `prod_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `tbl_sizemultiplier`
--
ALTER TABLE `tbl_sizemultiplier`
  MODIFY `size_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_cart`
--
ALTER TABLE `tbl_cart`
  ADD CONSTRAINT `tbl_cart_tbl_user_fk` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`);

--
-- Constraints for table `tbl_item`
--
ALTER TABLE `tbl_item`
  ADD CONSTRAINT `tbl_item_cart_fk` FOREIGN KEY (`cart_id`) REFERENCES `tbl_cart` (`cart_id`),
  ADD CONSTRAINT `tbl_item_product_fk` FOREIGN KEY (`prod_id`) REFERENCES `tbl_product` (`prod_id`),
  ADD CONSTRAINT `tbl_item_sizemulti_fk` FOREIGN KEY (`size_id`) REFERENCES `tbl_sizemultiplier` (`size_id`);

--
-- Constraints for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD CONSTRAINT `tbl_product_category_fk` FOREIGN KEY (`cat_id`) REFERENCES `tbl_prodcategory` (`cat_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
