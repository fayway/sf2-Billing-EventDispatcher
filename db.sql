--
-- Database: `symfony-billing`
--

--
-- Table structure for table `mobile_call`
--

CREATE TABLE IF NOT EXISTS `mobile_call` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` varchar(150) NOT NULL,
  `recipient` varchar(150) NOT NULL,
  `time` datetime NOT NULL COMMENT 'date time of the call',
  `duration` int(11) NOT NULL COMMENT 'in seconds ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `mobile_call`
--

INSERT INTO `mobile_call` (`id`, `customer`, `recipient`, `time`, `duration`) VALUES
(1, 'Mr Bob', '0537664422', '2012-09-04 01:09:31', 45),
(2, 'Mr Bob', '0537149855', '2012-09-07 13:12:24', 85),
(3, 'Jean-Pierre', '0645335598', '2012-09-10 00:00:00', 145),
(4, 'Mr Bob', '0033986532', '2012-09-01 00:00:00', 25),
(5, 'Mr Bob', '0676757428', '2012-09-11 00:00:00', 225),
(6, 'Jean-Pierre', '0800787878', '2012-09-11 00:00:00', 35),
(7, 'Jean-Pierre', '0656544545', '2012-09-11 00:00:00', 100);