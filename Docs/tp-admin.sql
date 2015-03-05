-- phpMyAdmin SQL Dump
-- version 4.2.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2015-03-05 10:59:42
-- 服务器版本： 5.5.38-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tp-admin`
--

-- --------------------------------------------------------

--
-- 表的结构 `xy_access`
--

CREATE TABLE IF NOT EXISTS `xy_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `siteid` tinyint(4) NOT NULL DEFAULT '1' COMMENT '站点ID'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xy_access`
--

INSERT INTO `xy_access` (`role_id`, `node_id`, `siteid`) VALUES
(2, 41, 1),
(2, 25, 1),
(2, 69, 1),
(2, 63, 1),
(2, 197, 1),
(2, 47, 1),
(2, 46, 1),
(2, 45, 1),
(2, 196, 1),
(2, 43, 1),
(2, 32, 1),
(2, 5, 1),
(2, 58, 1),
(2, 59, 1),
(2, 53, 2),
(2, 66, 2),
(2, 32, 2),
(2, 5, 2),
(2, 58, 2),
(2, 59, 2);

-- --------------------------------------------------------

--
-- 表的结构 `xy_attachment`
--

CREATE TABLE IF NOT EXISTS `xy_attachment` (
`id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL COMMENT '所属栏目',
  `title` varchar(255) NOT NULL COMMENT '自定义标题',
  `description` varchar(255) NOT NULL COMMENT '自定义描述',
  `path` varchar(255) NOT NULL COMMENT '附件路径',
  `name` varchar(255) NOT NULL COMMENT '附件本身名字',
  `size` int(10) NOT NULL COMMENT '附件大小',
  `ext` char(10) NOT NULL COMMENT '附件扩展名',
  `user_id` int(11) NOT NULL COMMENT '上传用户',
  `upload_ip` char(15) NOT NULL COMMENT '上传IP',
  `upload_time` int(10) NOT NULL COMMENT '上传时间',
  `compression_image` varchar(255) NOT NULL,
  `sort` smallint(6) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `width` smallint(6) DEFAULT NULL,
  `height` smallint(6) DEFAULT NULL,
  `compression_url` varchar(255) DEFAULT NULL,
  `siteid` tinyint(4) NOT NULL DEFAULT '1' COMMENT '站点ID'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1417594339 ;

--
-- 转存表中的数据 `xy_attachment`
--

INSERT INTO `xy_attachment` (`id`, `category_id`, `title`, `description`, `path`, `name`, `size`, `ext`, `user_id`, `upload_ip`, `upload_time`, `compression_image`, `sort`, `url`, `width`, `height`, `compression_url`, `siteid`) VALUES
(1415523687, 0, '', '', '/uploads/2014/11/09/7af40ad162d9f2d3faf867d9aaec8a136327cc1f.jpg', '7af40ad162d9f2d3faf867d9aaec8a136327cc1f.jpg', 95661, 'jpg', 0, '127.0.0.1', 1415523687, '/uploads/2014/11/09/compression-7af40ad162d9f2d3faf867d9aaec8a136327cc1f.jpg', 0, 'http://www.cdfdc.com/c_esf/public/uploads/2014/11/09/7af40ad162d9f2d3faf867d9aaec8a136327cc1f.jpg', 1251, 702, 'http://www.cdfdc.com/c_esf/public/uploads/2014/11/09/compression-7af40ad162d9f2d3faf867d9aaec8a136327cc1f.jpg', 1),
(1416140868, 0, '', '', '/uploads/2014/11/16/QQ20141115-5.png', 'QQ20141115-5.png', 93674, 'png', 0, '127.0.0.1', 1416140868, '/uploads/2014/11/16/compression-QQ20141115-5.png', 0, 'http://www.cdfdc.com/c_esf/public/uploads/2014/11/16/QQ20141115-5.png', 987, 399, 'http://www.cdfdc.com/c_esf/public/uploads/2014/11/16/compression-QQ20141115-5.png', 1),
(1417055276, 0, '', '', '/uploads/2014/11/27/1.jpg', '1.jpg', 222741, 'jpg', 0, '218.75.134.211', 1417055276, '/uploads/2014/11/27/compression-1.jpg', 0, 'http://my.0736fdc.com/uploads/2014/11/27/1.jpg', 690, 386, 'http://my.0736fdc.com/uploads/2014/11/27/compression-1.jpg', 1),
(1417055280, 0, '', '', '/uploads/2014/11/27/DSC_5469_副本.jpg', 'DSC_5469_副本.jpg', 293748, 'jpg', 0, '218.75.134.211', 1417055280, '/uploads/2014/11/27/compression-DSC_5469_副本.jpg', 0, 'http://my.0736fdc.com/uploads/2014/11/27/DSC_5469_副本.jpg', 690, 386, 'http://my.0736fdc.com/uploads/2014/11/27/compression-DSC_5469_副本.jpg', 1),
(1417055285, 0, '', '', '/uploads/2014/11/27/os-zh-02-副本1.jpg', 'os-zh-02-副本1.jpg', 378847, 'jpg', 0, '218.75.134.211', 1417055285, '/uploads/2014/11/27/compression-os-zh-02-副本1.jpg', 0, 'http://my.0736fdc.com/uploads/2014/11/27/os-zh-02-副本1.jpg', 690, 386, 'http://my.0736fdc.com/uploads/2014/11/27/compression-os-zh-02-副本1.jpg', 1),
(1417055293, 0, '', '', '/uploads/2014/11/27/QQ截图20140812155333.png', 'QQ截图20140812155333.png', 440186, 'png', 0, '218.75.134.211', 1417055293, '/uploads/2014/11/27/compression-QQ截图20140812155333.png', 0, 'http://my.0736fdc.com/uploads/2014/11/27/QQ截图20140812155333.png', 461, 563, 'http://my.0736fdc.com/uploads/2014/11/27/compression-QQ截图20140812155333.png', 1),
(1417055300, 0, '', '', '/uploads/2014/11/27/QQ截图20140819102615.png', 'QQ截图20140819102615.png', 804105, 'png', 0, '218.75.134.211', 1417055300, '/uploads/2014/11/27/compression-QQ截图20140819102615.png', 0, 'http://my.0736fdc.com/uploads/2014/11/27/QQ截图20140819102615.png', 904, 529, 'http://my.0736fdc.com/uploads/2014/11/27/compression-QQ截图20140819102615.png', 1),
(1417055309, 0, '', '', '/uploads/2014/11/27/德景园.jpg', '德景园.jpg', 8325, 'jpg', 0, '218.75.134.211', 1417055309, '/uploads/2014/11/27/compression-德景园.jpg', 0, 'http://my.0736fdc.com/uploads/2014/11/27/德景园.jpg', 215, 154, 'http://my.0736fdc.com/uploads/2014/11/27/compression-德景园.jpg', 1),
(1417055318, 0, '', '', '/uploads/2014/11/27/滨湖新天地.jpg', '滨湖新天地.jpg', 8431, 'jpg', 0, '218.75.134.211', 1417055318, '/uploads/2014/11/27/compression-滨湖新天地.jpg', 0, 'http://my.0736fdc.com/uploads/2014/11/27/滨湖新天地.jpg', 215, 154, 'http://my.0736fdc.com/uploads/2014/11/27/compression-滨湖新天地.jpg', 1),
(1417055324, 0, '', '', '/uploads/2014/11/27/高尚名门.jpg', '高尚名门.jpg', 23100, 'jpg', 0, '218.75.134.211', 1417055324, '/uploads/2014/11/27/compression-高尚名门.jpg', 0, 'http://my.0736fdc.com/uploads/2014/11/27/高尚名门.jpg', 382, 191, 'http://my.0736fdc.com/uploads/2014/11/27/compression-高尚名门.jpg', 1),
(1417055733, 0, '', '', '/uploads/2014/11/27/滨湖新天地1.jpg', '滨湖新天地.jpg', 232004, 'jpg', 0, '218.75.134.211', 1417055733, '/uploads/2014/11/27/compression-滨湖新天地1.jpg', 0, 'http://my.0736fdc.com/uploads/2014/11/27/滨湖新天地1.jpg', 1123, 802, 'http://my.0736fdc.com/uploads/2014/11/27/compression-滨湖新天地1.jpg', 1),
(1417055736, 0, '', '', '/uploads/2014/11/27/德景园1.jpg', '德景园.jpg', 209470, 'jpg', 0, '218.75.134.211', 1417055736, '/uploads/2014/11/27/compression-德景园1.jpg', 0, 'http://my.0736fdc.com/uploads/2014/11/27/德景园1.jpg', 1123, 802, 'http://my.0736fdc.com/uploads/2014/11/27/compression-德景园1.jpg', 1),
(1417055738, 0, '', '', '/uploads/2014/11/27/经泽景园.jpg', '经泽景园.jpg', 153937, 'jpg', 0, '218.75.134.211', 1417055738, '/uploads/2014/11/27/compression-经泽景园.jpg', 0, 'http://my.0736fdc.com/uploads/2014/11/27/经泽景园.jpg', 1123, 802, 'http://my.0736fdc.com/uploads/2014/11/27/compression-经泽景园.jpg', 1),
(1417055740, 0, '', '', '/uploads/2014/11/27/泓鑫桃林.jpg', '泓鑫桃林.jpg', 264299, 'jpg', 0, '218.75.134.211', 1417055740, '/uploads/2014/11/27/compression-泓鑫桃林.jpg', 0, 'http://my.0736fdc.com/uploads/2014/11/27/泓鑫桃林.jpg', 1123, 802, 'http://my.0736fdc.com/uploads/2014/11/27/compression-泓鑫桃林.jpg', 1),
(1417055744, 0, '', '', '/uploads/2014/11/27/三一.jpg', '三一.jpg', 489233, 'jpg', 0, '218.75.134.211', 1417055744, '/uploads/2014/11/27/compression-三一.jpg', 0, 'http://my.0736fdc.com/uploads/2014/11/27/三一.jpg', 1987, 994, 'http://my.0736fdc.com/uploads/2014/11/27/compression-三一.jpg', 1),
(1417055843, 0, '', '', '/uploads/2014/11/27/高尚名门1.jpg', '高尚名门.jpg', 422352, 'jpg', 0, '218.75.134.211', 1417055843, '/uploads/2014/11/27/compression-高尚名门1.jpg', 0, 'http://my.0736fdc.com/uploads/2014/11/27/高尚名门1.jpg', 1984, 994, 'http://my.0736fdc.com/uploads/2014/11/27/compression-高尚名门1.jpg', 1),
(1417056339, 0, '', '', '/uploads/2014/11/27/金色世纪.jpg', '金色世纪.jpg', 368965, 'jpg', 0, '218.75.134.211', 1417056339, '/uploads/2014/11/27/compression-金色世纪.jpg', 0, 'http://my.0736fdc.com/uploads/2014/11/27/金色世纪.jpg', 1982, 994, 'http://my.0736fdc.com/uploads/2014/11/27/compression-金色世纪.jpg', 1),
(1417056343, 0, '', '', '/uploads/2014/11/27/天源星城.jpg', '天源星城.jpg', 187892, 'jpg', 0, '218.75.134.211', 1417056343, '/uploads/2014/11/27/compression-天源星城.jpg', 0, 'http://my.0736fdc.com/uploads/2014/11/27/天源星城.jpg', 1123, 802, 'http://my.0736fdc.com/uploads/2014/11/27/compression-天源星城.jpg', 1),
(1417056640, 0, '', '', '/uploads/2014/11/27/DSC_5469_副本1.jpg', 'DSC_5469_副本.jpg', 293748, 'jpg', 0, '218.75.134.211', 1417056640, '/uploads/2014/11/27/compression-DSC_5469_副本1.jpg', 0, 'http://my.0736fdc.com/uploads/2014/11/27/DSC_5469_副本1.jpg', 690, 386, 'http://my.0736fdc.com/uploads/2014/11/27/compression-DSC_5469_副本1.jpg', 1),
(1417056958, 0, '', '', '/uploads/2014/11/27/DSC_5469_副本2.jpg', 'DSC_5469_副本.jpg', 293748, 'jpg', 0, '218.75.134.211', 1417056958, '/uploads/2014/11/27/compression-DSC_5469_副本2.jpg', 0, 'http://my.0736fdc.com/uploads/2014/11/27/DSC_5469_副本2.jpg', 690, 386, 'http://my.0736fdc.com/uploads/2014/11/27/compression-DSC_5469_副本2.jpg', 1),
(1417057136, 0, '', '', '/uploads/2014/11/27/DSC_5469_副本3.jpg', 'DSC_5469_副本.jpg', 293748, 'jpg', 0, '218.75.134.211', 1417057136, '/uploads/2014/11/27/compression-DSC_5469_副本3.jpg', 0, 'http://my.0736fdc.com/uploads/2014/11/27/DSC_5469_副本3.jpg', 690, 386, 'http://my.0736fdc.com/uploads/2014/11/27/compression-DSC_5469_副本3.jpg', 1),
(1417141363, 0, '', '', '/uploads/2014/11/28/QQ截图20140819102615.png', 'QQ截图20140819102615.png', 804105, 'png', 0, '218.75.134.211', 1417141363, '/uploads/2014/11/28/compression-QQ截图20140819102615.png', 0, 'http://my.0736fdc.com/uploads/2014/11/28/QQ截图20140819102615.png', 904, 529, 'http://my.0736fdc.com/uploads/2014/11/28/compression-QQ截图20140819102615.png', 1),
(1417588470, 0, '', '', '/uploads/2014/12/03/QQ图片20141110084426.jpg', 'QQ图片20141110084426.jpg', 56637, 'jpg', 0, '222.246.98.84', 1417588470, '/uploads/2014/12/03/compression-QQ图片20141110084426.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/QQ图片20141110084426.jpg', 493, 398, 'http://my.0736fdc.com/uploads/2014/12/03/compression-QQ图片20141110084426.jpg', 1),
(1417588471, 0, '', '', '/uploads/2014/12/03/QQ图片20141110084446.jpg', 'QQ图片20141110084446.jpg', 43425, 'jpg', 0, '222.246.98.84', 1417588471, '/uploads/2014/12/03/compression-QQ图片20141110084446.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/QQ图片20141110084446.jpg', 502, 397, 'http://my.0736fdc.com/uploads/2014/12/03/compression-QQ图片20141110084446.jpg', 1),
(1417588518, 0, '', '', '/uploads/2014/12/03/DSC03143.jpg', 'DSC03143.jpg', 56761, 'jpg', 0, '222.246.98.84', 1417588518, '/uploads/2014/12/03/compression-DSC03143.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/DSC03143.jpg', 500, 375, 'http://my.0736fdc.com/uploads/2014/12/03/compression-DSC03143.jpg', 1),
(1417588519, 0, '', '', '/uploads/2014/12/03/DSC03147.jpg', 'DSC03147.jpg', 45791, 'jpg', 0, '222.246.98.84', 1417588519, '/uploads/2014/12/03/compression-DSC03147.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/DSC03147.jpg', 500, 375, 'http://my.0736fdc.com/uploads/2014/12/03/compression-DSC03147.jpg', 1),
(1417588521, 0, '', '', '/uploads/2014/12/03/DSC03148.jpg', 'DSC03148.jpg', 44225, 'jpg', 0, '222.246.98.84', 1417588521, '/uploads/2014/12/03/compression-DSC03148.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/DSC03148.jpg', 500, 375, 'http://my.0736fdc.com/uploads/2014/12/03/compression-DSC03148.jpg', 1),
(1417588530, 0, '', '', '/uploads/2014/12/03/DSC03149.jpg', 'DSC03149.jpg', 62843, 'jpg', 0, '222.246.98.84', 1417588530, '/uploads/2014/12/03/compression-DSC03149.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/DSC03149.jpg', 500, 375, 'http://my.0736fdc.com/uploads/2014/12/03/compression-DSC03149.jpg', 1),
(1417588536, 0, '', '', '/uploads/2014/12/03/QQ图片20141110084538.jpg', 'QQ图片20141110084538.jpg', 51896, 'jpg', 0, '222.246.98.84', 1417588536, '/uploads/2014/12/03/compression-QQ图片20141110084538.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/QQ图片20141110084538.jpg', 479, 338, 'http://my.0736fdc.com/uploads/2014/12/03/compression-QQ图片20141110084538.jpg', 1),
(1417588584, 0, '', '', '/uploads/2014/12/03/DSC03152.jpg', 'DSC03152.jpg', 51094, 'jpg', 0, '222.246.98.84', 1417588584, '/uploads/2014/12/03/compression-DSC03152.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/DSC03152.jpg', 500, 375, 'http://my.0736fdc.com/uploads/2014/12/03/compression-DSC03152.jpg', 1),
(1417588589, 0, '', '', '/uploads/2014/12/03/QQ图片20141110084510.jpg', 'QQ图片20141110084510.jpg', 50447, 'jpg', 0, '222.246.98.84', 1417588589, '/uploads/2014/12/03/compression-QQ图片20141110084510.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/QQ图片20141110084510.jpg', 381, 399, 'http://my.0736fdc.com/uploads/2014/12/03/compression-QQ图片20141110084510.jpg', 1),
(1417588594, 0, '', '', '/uploads/2014/12/03/DSC03144.jpg', 'DSC03144.jpg', 45760, 'jpg', 0, '222.246.98.84', 1417588594, '/uploads/2014/12/03/compression-DSC03144.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/DSC03144.jpg', 500, 375, 'http://my.0736fdc.com/uploads/2014/12/03/compression-DSC03144.jpg', 1),
(1417588623, 0, '', '', '/uploads/2014/12/03/DSC03150.jpg', 'DSC03150.jpg', 44637, 'jpg', 0, '222.246.98.84', 1417588623, '/uploads/2014/12/03/compression-DSC03150.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/DSC03150.jpg', 500, 375, 'http://my.0736fdc.com/uploads/2014/12/03/compression-DSC03150.jpg', 1),
(1417588626, 0, '', '', '/uploads/2014/12/03/DSC031491.jpg', 'DSC03149.jpg', 62843, 'jpg', 0, '222.246.98.84', 1417588626, '/uploads/2014/12/03/compression-DSC031491.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/DSC031491.jpg', 500, 375, 'http://my.0736fdc.com/uploads/2014/12/03/compression-DSC031491.jpg', 1),
(1417588628, 0, '', '', '/uploads/2014/12/03/DSC031471.jpg', 'DSC03147.jpg', 45791, 'jpg', 0, '222.246.98.84', 1417588628, '/uploads/2014/12/03/compression-DSC031471.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/DSC031471.jpg', 500, 375, 'http://my.0736fdc.com/uploads/2014/12/03/compression-DSC031471.jpg', 1),
(1417588630, 0, '', '', '/uploads/2014/12/03/DSC03145.jpg', 'DSC03145.jpg', 51130, 'jpg', 0, '222.246.98.84', 1417588630, '/uploads/2014/12/03/compression-DSC03145.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/DSC03145.jpg', 500, 375, 'http://my.0736fdc.com/uploads/2014/12/03/compression-DSC03145.jpg', 1),
(1417588718, 0, '', '', '/uploads/2014/12/03/1.jpg', '1.jpg', 200363, 'jpg', 0, '222.246.98.84', 1417588718, '/uploads/2014/12/03/compression-1.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/1.jpg', 502, 397, 'http://my.0736fdc.com/uploads/2014/12/03/compression-1.jpg', 1),
(1417588724, 0, '', '', '/uploads/2014/12/03/2.jpg', '2.jpg', 166992, 'jpg', 0, '222.246.98.84', 1417588724, '/uploads/2014/12/03/compression-2.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/2.jpg', 500, 399, 'http://my.0736fdc.com/uploads/2014/12/03/compression-2.jpg', 1),
(1417588730, 0, '', '', '/uploads/2014/12/03/3.jpg', '3.jpg', 251398, 'jpg', 0, '222.246.98.84', 1417588730, '/uploads/2014/12/03/compression-3.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/3.jpg', 499, 395, 'http://my.0736fdc.com/uploads/2014/12/03/compression-3.jpg', 1),
(1417588734, 0, '', '', '/uploads/2014/12/03/4.jpg', '4.jpg', 153722, 'jpg', 0, '222.246.98.84', 1417588734, '/uploads/2014/12/03/compression-4.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/4.jpg', 497, 394, 'http://my.0736fdc.com/uploads/2014/12/03/compression-4.jpg', 1),
(1417588747, 0, '', '', '/uploads/2014/12/03/21.jpg', '2.jpg', 166992, 'jpg', 0, '222.246.98.84', 1417588747, '/uploads/2014/12/03/compression-21.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/21.jpg', 500, 399, 'http://my.0736fdc.com/uploads/2014/12/03/compression-21.jpg', 1),
(1417588969, 0, '', '', '/uploads/2014/12/03/11.jpg', '11.jpg', 89230, 'jpg', 0, '222.246.98.84', 1417588969, '/uploads/2014/12/03/compression-11.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/11.jpg', 508, 403, 'http://my.0736fdc.com/uploads/2014/12/03/compression-11.jpg', 1),
(1417588970, 0, '', '', '/uploads/2014/12/03/22.jpg', '22.jpg', 44503, 'jpg', 0, '222.246.98.84', 1417588970, '/uploads/2014/12/03/compression-22.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/22.jpg', 502, 401, 'http://my.0736fdc.com/uploads/2014/12/03/compression-22.jpg', 1),
(1417589085, 0, '', '', '/uploads/2014/12/03/DSC03158.jpg', 'DSC03158.jpg', 54597, 'jpg', 0, '222.246.98.84', 1417589085, '/uploads/2014/12/03/compression-DSC03158.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/DSC03158.jpg', 500, 375, 'http://my.0736fdc.com/uploads/2014/12/03/compression-DSC03158.jpg', 1),
(1417589088, 0, '', '', '/uploads/2014/12/03/DSC03160.jpg', 'DSC03160.jpg', 55718, 'jpg', 0, '222.246.98.84', 1417589088, '/uploads/2014/12/03/compression-DSC03160.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/DSC03160.jpg', 500, 375, 'http://my.0736fdc.com/uploads/2014/12/03/compression-DSC03160.jpg', 1),
(1417589090, 0, '', '', '/uploads/2014/12/03/DSC03163.jpg', 'DSC03163.jpg', 43196, 'jpg', 0, '222.246.98.84', 1417589090, '/uploads/2014/12/03/compression-DSC03163.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/DSC03163.jpg', 500, 375, 'http://my.0736fdc.com/uploads/2014/12/03/compression-DSC03163.jpg', 1),
(1417589091, 0, '', '', '/uploads/2014/12/03/DSC03166.jpg', 'DSC03166.jpg', 42739, 'jpg', 0, '222.246.98.84', 1417589091, '/uploads/2014/12/03/compression-DSC03166.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/DSC03166.jpg', 500, 375, 'http://my.0736fdc.com/uploads/2014/12/03/compression-DSC03166.jpg', 1),
(1417589093, 0, '', '', '/uploads/2014/12/03/DSC03168.jpg', 'DSC03168.jpg', 39967, 'jpg', 0, '222.246.98.84', 1417589093, '/uploads/2014/12/03/compression-DSC03168.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/DSC03168.jpg', 500, 375, 'http://my.0736fdc.com/uploads/2014/12/03/compression-DSC03168.jpg', 1),
(1417589385, 0, '', '', '/uploads/2014/12/03/QQ图片20140902095838.jpg', 'QQ图片20140902095838.jpg', 195770, 'jpg', 0, '222.246.98.84', 1417589385, '/uploads/2014/12/03/compression-QQ图片20140902095838.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/QQ图片20140902095838.jpg', 960, 720, 'http://my.0736fdc.com/uploads/2014/12/03/compression-QQ图片20140902095838.jpg', 1),
(1417589392, 0, '', '', '/uploads/2014/12/03/QQ图片20140902095821.jpg', 'QQ图片20140902095821.jpg', 72071, 'jpg', 0, '222.246.98.84', 1417589392, '/uploads/2014/12/03/compression-QQ图片20140902095821.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/QQ图片20140902095821.jpg', 960, 720, 'http://my.0736fdc.com/uploads/2014/12/03/compression-QQ图片20140902095821.jpg', 1),
(1417589589, 0, '', '', '/uploads/2014/12/03/QQ图片20141203145418.jpg', 'QQ图片20141203145418.jpg', 36836, 'jpg', 0, '222.246.98.84', 1417589589, '/uploads/2014/12/03/compression-QQ图片20141203145418.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/QQ图片20141203145418.jpg', 471, 398, 'http://my.0736fdc.com/uploads/2014/12/03/compression-QQ图片20141203145418.jpg', 1),
(1417589591, 0, '', '', '/uploads/2014/12/03/QQ图片20141203145453.jpg', 'QQ图片20141203145453.jpg', 42746, 'jpg', 0, '222.246.98.84', 1417589591, '/uploads/2014/12/03/compression-QQ图片20141203145453.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/QQ图片20141203145453.jpg', 472, 399, 'http://my.0736fdc.com/uploads/2014/12/03/compression-QQ图片20141203145453.jpg', 1),
(1417589602, 0, '', '', '/uploads/2014/12/03/QQ图片201412031455141.jpg', 'QQ图片20141203145514.jpg', 26817, 'jpg', 0, '222.246.98.84', 1417589602, '/uploads/2014/12/03/compression-QQ图片201412031455141.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/QQ图片201412031455141.jpg', 472, 401, 'http://my.0736fdc.com/uploads/2014/12/03/compression-QQ图片201412031455141.jpg', 1),
(1417589604, 0, '', '', '/uploads/2014/12/03/QQ图片201412031454531.jpg', 'QQ图片20141203145453.jpg', 42746, 'jpg', 0, '222.246.98.84', 1417589604, '/uploads/2014/12/03/compression-QQ图片201412031454531.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/QQ图片201412031454531.jpg', 472, 399, 'http://my.0736fdc.com/uploads/2014/12/03/compression-QQ图片201412031454531.jpg', 1),
(1417589622, 0, '', '', '/uploads/2014/12/03/QQ图片20140806151534.jpg', 'QQ图片20140806151534.jpg', 68240, 'jpg', 0, '222.246.98.84', 1417589622, '/uploads/2014/12/03/compression-QQ图片20140806151534.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/QQ图片20140806151534.jpg', 541, 960, 'http://my.0736fdc.com/uploads/2014/12/03/compression-QQ图片20140806151534.jpg', 1),
(1417589625, 0, '', '', '/uploads/2014/12/03/QQ图片20140806151422.jpg', 'QQ图片20140806151422.jpg', 94443, 'jpg', 0, '222.246.98.84', 1417589625, '/uploads/2014/12/03/compression-QQ图片20140806151422.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/QQ图片20140806151422.jpg', 541, 960, 'http://my.0736fdc.com/uploads/2014/12/03/compression-QQ图片20140806151422.jpg', 1),
(1417594142, 0, '', '', '/uploads/2014/12/03/20141201_112443.jpg', '20141201_112443.jpg', 108924, 'jpg', 0, '222.246.98.84', 1417594142, '/uploads/2014/12/03/compression-20141201_112443.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/20141201_112443.jpg', 600, 800, 'http://my.0736fdc.com/uploads/2014/12/03/compression-20141201_112443.jpg', 1),
(1417594170, 0, '', '', '/uploads/2014/12/03/20141201_112509.jpg', '20141201_112509.jpg', 91173, 'jpg', 0, '222.246.98.84', 1417594170, '/uploads/2014/12/03/compression-20141201_112509.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/20141201_112509.jpg', 600, 800, 'http://my.0736fdc.com/uploads/2014/12/03/compression-20141201_112509.jpg', 1),
(1417594177, 0, '', '', '/uploads/2014/12/03/20141201_112550.jpg', '20141201_112550.jpg', 97918, 'jpg', 0, '222.246.98.84', 1417594177, '/uploads/2014/12/03/compression-20141201_112550.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/20141201_112550.jpg', 600, 800, 'http://my.0736fdc.com/uploads/2014/12/03/compression-20141201_112550.jpg', 1),
(1417594184, 0, '', '', '/uploads/2014/12/03/20141201_112609.jpg', '20141201_112609.jpg', 92262, 'jpg', 0, '222.246.98.84', 1417594184, '/uploads/2014/12/03/compression-20141201_112609.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/20141201_112609.jpg', 600, 800, 'http://my.0736fdc.com/uploads/2014/12/03/compression-20141201_112609.jpg', 1),
(1417594191, 0, '', '', '/uploads/2014/12/03/20141201_112630.jpg', '20141201_112630.jpg', 105263, 'jpg', 0, '222.246.98.84', 1417594191, '/uploads/2014/12/03/compression-20141201_112630.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/20141201_112630.jpg', 600, 800, 'http://my.0736fdc.com/uploads/2014/12/03/compression-20141201_112630.jpg', 1),
(1417594196, 0, '', '', '/uploads/2014/12/03/20141201_112709.jpg', '20141201_112709.jpg', 80112, 'jpg', 0, '222.246.98.84', 1417594196, '/uploads/2014/12/03/compression-20141201_112709.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/20141201_112709.jpg', 600, 450, 'http://my.0736fdc.com/uploads/2014/12/03/compression-20141201_112709.jpg', 1),
(1417594202, 0, '', '', '/uploads/2014/12/03/QQ图片20140902112656.jpg', 'QQ图片20140902112656.jpg', 52151, 'jpg', 0, '222.246.98.84', 1417594202, '/uploads/2014/12/03/compression-QQ图片20140902112656.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/QQ图片20140902112656.jpg', 496, 402, 'http://my.0736fdc.com/uploads/2014/12/03/compression-QQ图片20140902112656.jpg', 1),
(1417594204, 0, '', '', '/uploads/2014/12/03/QQ图片20140902112754.jpg', 'QQ图片20140902112754.jpg', 66806, 'jpg', 0, '222.246.98.84', 1417594204, '/uploads/2014/12/03/compression-QQ图片20140902112754.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/QQ图片20140902112754.jpg', 502, 407, 'http://my.0736fdc.com/uploads/2014/12/03/compression-QQ图片20140902112754.jpg', 1),
(1417594220, 0, '', '', '/uploads/2014/12/03/20141201_1127201.jpg', '20141201_112720.jpg', 79126, 'jpg', 0, '222.246.98.84', 1417594220, '/uploads/2014/12/03/compression-20141201_1127201.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/03/20141201_1127201.jpg', 600, 450, 'http://my.0736fdc.com/uploads/2014/12/03/compression-20141201_1127201.jpg', 1),
(1417594221, 0, '', '', '/uploads/2014/12/10/moren.jpg', 'moren.jpg', 40353, 'jpg', 0, '218.75.134.211', 1418179704, '/uploads/2014/12/10/compression-moren.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/10/moren.jpg', 356, 267, 'http://my.0736fdc.com/uploads/2014/12/10/compression-moren.jpg', 1),
(1417594222, 0, '', '', '/uploads/2014/12/10/IMG_2470.jpg', 'IMG_2470.JPG', 137897, 'JPG', 0, '218.75.134.211', 1418179724, '/uploads/2014/12/10/compression-IMG_2470.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/10/IMG_2470.jpg', 960, 1280, 'http://my.0736fdc.com/uploads/2014/12/10/compression-IMG_2470.jpg', 1),
(1417594223, 0, '', '', '/uploads/2014/12/10/IMG_2471.jpg', 'IMG_2471.JPG', 100168, 'JPG', 0, '218.75.134.211', 1418179724, '/uploads/2014/12/10/compression-IMG_2471.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/10/IMG_2471.jpg', 960, 1280, 'http://my.0736fdc.com/uploads/2014/12/10/compression-IMG_2471.jpg', 1),
(1417594224, 0, '', '', '/uploads/2014/12/10/index.png', 'index.png', 3238, 'png', 0, '218.75.134.211', 1418179821, '/uploads/2014/12/10/compression-index.png', 0, 'http://my.0736fdc.com/uploads/2014/12/10/index.png', 92, 22, 'http://my.0736fdc.com/uploads/2014/12/10/compression-index.png', 1),
(1417594225, 0, '', '', '/uploads/2014/12/10/qq.png', 'qq.png', 2936, 'png', 0, '218.75.134.211', 1418179937, '/uploads/2014/12/10/compression-qq.png', 0, 'http://my.0736fdc.com/uploads/2014/12/10/qq.png', 54, 54, 'http://my.0736fdc.com/uploads/2014/12/10/compression-qq.png', 1),
(1417594226, 0, '', '', '/uploads/2014/12/10/IMG_24701.jpg', 'IMG_2470.JPG', 137897, 'JPG', 0, '218.75.134.211', 1418179959, '/uploads/2014/12/10/compression-IMG_24701.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/10/IMG_24701.jpg', 960, 1280, 'http://my.0736fdc.com/uploads/2014/12/10/compression-IMG_24701.jpg', 1),
(1417594227, 0, '', '', '/uploads/2014/12/10/IMG_24711.jpg', 'IMG_2471.JPG', 100168, 'JPG', 0, '218.75.134.211', 1418179959, '/uploads/2014/12/10/compression-IMG_24711.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/10/IMG_24711.jpg', 960, 1280, 'http://my.0736fdc.com/uploads/2014/12/10/compression-IMG_24711.jpg', 1),
(1417594228, 0, '', '', '/uploads/2014/12/10/ad4.jpg', 'ad4.jpg', 44178, 'jpg', 0, '218.75.134.211', 1418179966, '/uploads/2014/12/10/compression-ad4.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/10/ad4.jpg', 1000, 90, 'http://my.0736fdc.com/uploads/2014/12/10/compression-ad4.jpg', 1),
(1417594229, 0, '', '', '/uploads/2014/12/10/ad3_01.jpg', 'ad3_01.jpg', 47753, 'jpg', 0, '218.75.134.211', 1418180011, '/uploads/2014/12/10/compression-ad3_01.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/10/ad3_01.jpg', 1001, 365, 'http://my.0736fdc.com/uploads/2014/12/10/compression-ad3_01.jpg', 1),
(1417594230, 0, '', '', '/uploads/2014/12/10/Dealer-list_09.png', 'Dealer-list_09.png', 2062, 'png', 0, '50.116.5.59', 1418184922, '/uploads/2014/12/10/compression-Dealer-list_09.png', 0, 'http://my.0736fdc.com/uploads/2014/12/10/Dealer-list_09.png', 21, 20, 'http://my.0736fdc.com/uploads/2014/12/10/compression-Dealer-list_09.png', 1),
(1417594231, 0, '', '', '/uploads/2014/12/10/ad3_011.jpg', 'ad3_01.jpg', 47753, 'jpg', 0, '218.75.134.211', 1418194766, '/uploads/2014/12/10/compression-ad3_011.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/10/ad3_011.jpg', 1001, 365, 'http://my.0736fdc.com/uploads/2014/12/10/compression-ad3_011.jpg', 1),
(1417594232, 0, '', '', '/uploads/2014/12/10/ad3_03.jpg', 'ad3_03.jpg', 114502, 'jpg', 0, '218.75.134.211', 1418195196, '/uploads/2014/12/10/compression-ad3_03.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/10/ad3_03.jpg', 1001, 365, 'http://my.0736fdc.com/uploads/2014/12/10/compression-ad3_03.jpg', 1),
(1417594233, 0, '', '', '/uploads/2014/12/10/德景园.jpg', '德景园.jpg', 8325, 'jpg', 0, '218.75.134.211', 1418196584, '/uploads/2014/12/10/compression-德景园.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/10/德景园.jpg', 215, 154, 'http://my.0736fdc.com/uploads/2014/12/10/compression-德景园.jpg', 1),
(1417594234, 0, '', '', '/uploads/2014/12/10/DSC_5469_副本.jpg', 'DSC_5469_副本.jpg', 293748, 'jpg', 0, '218.75.134.211', 1418196589, '/uploads/2014/12/10/compression-DSC_5469_副本.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/10/DSC_5469_副本.jpg', 690, 386, 'http://my.0736fdc.com/uploads/2014/12/10/compression-DSC_5469_副本.jpg', 1),
(1417594235, 0, '', '', '/uploads/2014/12/12/QQ截图20141212090303.jpg', 'QQ截图20141212090303.jpg', 211331, 'jpg', 0, '222.246.126.124', 1418346990, '/uploads/2014/12/12/compression-QQ截图20141212090303.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/12/QQ截图20141212090303.jpg', 640, 384, 'http://my.0736fdc.com/uploads/2014/12/12/compression-QQ截图20141212090303.jpg', 1),
(1417594236, 0, '', '', '/uploads/2014/12/12/QQ截图20141212090314.jpg', 'QQ截图20141212090314.jpg', 279228, 'jpg', 0, '222.246.126.124', 1418346990, '/uploads/2014/12/12/compression-QQ截图20141212090314.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/12/QQ截图20141212090314.jpg', 642, 388, 'http://my.0736fdc.com/uploads/2014/12/12/compression-QQ截图20141212090314.jpg', 1),
(1417594237, 0, '', '', '/uploads/2014/12/12/QQ截图20141212090346.jpg', 'QQ截图20141212090346.jpg', 230861, 'jpg', 0, '222.246.126.124', 1418346992, '/uploads/2014/12/12/compression-QQ截图20141212090346.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/12/QQ截图20141212090346.jpg', 642, 384, 'http://my.0736fdc.com/uploads/2014/12/12/compression-QQ截图20141212090346.jpg', 1),
(1417594238, 0, '', '', '/uploads/2014/12/12/QQ截图20141212090412.jpg', 'QQ截图20141212090412.jpg', 193442, 'jpg', 0, '222.246.126.124', 1418346993, '/uploads/2014/12/12/compression-QQ截图20141212090412.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/12/QQ截图20141212090412.jpg', 383, 613, 'http://my.0736fdc.com/uploads/2014/12/12/compression-QQ截图20141212090412.jpg', 1),
(1417594239, 0, '', '', '/uploads/2014/12/12/QQ截图20141212090434.jpg', 'QQ截图20141212090434.jpg', 210253, 'jpg', 0, '222.246.126.124', 1418346994, '/uploads/2014/12/12/compression-QQ截图20141212090434.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/12/QQ截图20141212090434.jpg', 640, 381, 'http://my.0736fdc.com/uploads/2014/12/12/compression-QQ截图20141212090434.jpg', 1),
(1417594240, 0, '', '', '/uploads/2014/12/12/QQ截图20141212090242.jpg', 'QQ截图20141212090242.jpg', 207056, 'jpg', 0, '222.246.126.124', 1418346995, '/uploads/2014/12/12/compression-QQ截图20141212090242.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/12/QQ截图20141212090242.jpg', 641, 384, 'http://my.0736fdc.com/uploads/2014/12/12/compression-QQ截图20141212090242.jpg', 1),
(1417594241, 0, '', '', '/uploads/2014/12/12/1.jpg', '1.jpg', 48029, 'jpg', 0, '222.246.126.124', 1418347935, '/uploads/2014/12/12/compression-1.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/12/1.jpg', 174, 200, 'http://my.0736fdc.com/uploads/2014/12/12/compression-1.jpg', 1),
(1417594242, 0, '', '', '/uploads/2014/12/16/n_s02510691242873195164.jpg', 'n_s02510691242873195164.jpg', 65959, 'jpg', 0, '222.246.62.223', 1418692125, '/uploads/2014/12/16/compression-n_s02510691242873195164.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_s02510691242873195164.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_s02510691242873195164.jpg', 1),
(1417594243, 0, '', '', '/uploads/2014/12/16/n_s12510690775683006156.jpg', 'n_s12510690775683006156.jpg', 39777, 'jpg', 0, '222.246.62.223', 1418692125, '/uploads/2014/12/16/compression-n_s12510690775683006156.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_s12510690775683006156.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_s12510690775683006156.jpg', 1),
(1417594244, 0, '', '', '/uploads/2014/12/16/n_s12510690965544645277.jpg', 'n_s12510690965544645277.jpg', 41401, 'jpg', 0, '222.246.62.223', 1418692126, '/uploads/2014/12/16/compression-n_s12510690965544645277.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_s12510690965544645277.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_s12510690965544645277.jpg', 1),
(1417594245, 0, '', '', '/uploads/2014/12/16/n_s12510691368096259165.jpg', 'n_s12510691368096259165.jpg', 30806, 'jpg', 0, '222.246.62.223', 1418692126, '/uploads/2014/12/16/compression-n_s12510691368096259165.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_s12510691368096259165.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_s12510691368096259165.jpg', 1),
(1417594246, 0, '', '', '/uploads/2014/12/16/n_t0934e53a1a7e78001c87e.jpg', 'n_t0934e53a1a7e78001c87e.jpg', 47226, 'jpg', 0, '222.246.62.223', 1418692127, '/uploads/2014/12/16/compression-n_t0934e53a1a7e78001c87e.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t0934e53a1a7e78001c87e.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t0934e53a1a7e78001c87e.jpg', 1),
(1417594247, 0, '', '', '/uploads/2014/12/16/n_t0934ec155aee48001c872.jpg', 'n_t0934ec155aee48001c872.jpg', 98803, 'jpg', 0, '222.246.62.223', 1418692127, '/uploads/2014/12/16/compression-n_t0934ec155aee48001c872.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t0934ec155aee48001c872.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t0934ec155aee48001c872.jpg', 1),
(1417594248, 0, '', '', '/uploads/2014/12/16/n_t07ea12434984e80018603.jpg', 'n_t07ea12434984e80018603.jpg', 48957, 'jpg', 0, '222.246.62.223', 1418692454, '/uploads/2014/12/16/compression-n_t07ea12434984e80018603.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t07ea12434984e80018603.jpg', 337, 450, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t07ea12434984e80018603.jpg', 1),
(1417594249, 0, '', '', '/uploads/2014/12/16/n_t07ea12784989680018606.jpg', 'n_t07ea12784989680018606.jpg', 52908, 'jpg', 0, '222.246.62.223', 1418692455, '/uploads/2014/12/16/compression-n_t07ea12784989680018606.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t07ea12784989680018606.jpg', 600, 337, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t07ea12784989680018606.jpg', 1),
(1417594250, 0, '', '', '/uploads/2014/12/16/n_s12405966006405903086.jpg', 'n_s12405966006405903086.jpg', 54613, 'jpg', 0, '222.246.62.223', 1418700761, '/uploads/2014/12/16/compression-n_s12405966006405903086.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_s12405966006405903086.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_s12405966006405903086.jpg', 1),
(1417594251, 0, '', '', '/uploads/2014/12/16/n_s12405966040813025136.jpg', 'n_s12405966040813025136.jpg', 55856, 'jpg', 0, '222.246.62.223', 1418700761, '/uploads/2014/12/16/compression-n_s12405966040813025136.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_s12405966040813025136.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_s12405966040813025136.jpg', 1),
(1417594252, 0, '', '', '/uploads/2014/12/16/n_s12405966082247471104.jpg', 'n_s12405966082247471104.jpg', 56923, 'jpg', 0, '222.246.62.223', 1418700762, '/uploads/2014/12/16/compression-n_s12405966082247471104.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_s12405966082247471104.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_s12405966082247471104.jpg', 1),
(1417594253, 0, '', '', '/uploads/2014/12/16/n_s12483573297072831077.jpg', 'n_s12483573297072831077.jpg', 53600, 'jpg', 0, '222.246.62.223', 1418700940, '/uploads/2014/12/16/compression-n_s12483573297072831077.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_s12483573297072831077.jpg', 517, 386, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_s12483573297072831077.jpg', 1),
(1417594254, 0, '', '', '/uploads/2014/12/16/n_t030a6e543c10a80009a0a.jpg', 'n_t030a6e543c10a80009a0a.jpg', 58097, 'jpg', 0, '222.246.62.223', 1418700941, '/uploads/2014/12/16/compression-n_t030a6e543c10a80009a0a.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t030a6e543c10a80009a0a.jpg', 522, 382, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t030a6e543c10a80009a0a.jpg', 1),
(1417594255, 0, '', '', '/uploads/2014/12/16/n_t030a6e700c14180009a16.jpg', 'n_t030a6e700c14180009a16.jpg', 45499, 'jpg', 0, '222.246.62.223', 1418700941, '/uploads/2014/12/16/compression-n_t030a6e700c14180009a16.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t030a6e700c14180009a16.jpg', 518, 384, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t030a6e700c14180009a16.jpg', 1),
(1417594256, 0, '', '', '/uploads/2014/12/16/n_t030a6e905c17d80009a05.jpg', 'n_t030a6e905c17d80009a05.jpg', 40973, 'jpg', 0, '222.246.62.223', 1418700942, '/uploads/2014/12/16/compression-n_t030a6e905c17d80009a05.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t030a6e905c17d80009a05.jpg', 518, 385, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t030a6e905c17d80009a05.jpg', 1),
(1417594257, 0, '', '', '/uploads/2014/12/16/n_s02561659360088391165.jpg', 'n_s02561659360088391165.jpg', 45057, 'jpg', 0, '222.246.62.223', 1418701181, '/uploads/2014/12/16/compression-n_s02561659360088391165.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_s02561659360088391165.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_s02561659360088391165.jpg', 1),
(1417594258, 0, '', '', '/uploads/2014/12/16/n_s12561659453625004164.jpg', 'n_s12561659453625004164.jpg', 36154, 'jpg', 0, '222.246.62.223', 1418701182, '/uploads/2014/12/16/compression-n_s12561659453625004164.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_s12561659453625004164.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_s12561659453625004164.jpg', 1),
(1417594259, 0, '', '', '/uploads/2014/12/16/n_s12561659496480806007.jpg', 'n_s12561659496480806007.jpg', 49397, 'jpg', 0, '222.246.62.223', 1418701182, '/uploads/2014/12/16/compression-n_s12561659496480806007.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_s12561659496480806007.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_s12561659496480806007.jpg', 1),
(1417594260, 0, '', '', '/uploads/2014/12/16/n_t04cba3a9ac0de80048295.jpg', 'n_t04cba3a9ac0de80048295.jpg', 49455, 'jpg', 0, '222.246.62.223', 1418701184, '/uploads/2014/12/16/compression-n_t04cba3a9ac0de80048295.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t04cba3a9ac0de80048295.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t04cba3a9ac0de80048295.jpg', 1),
(1417594261, 0, '', '', '/uploads/2014/12/16/n_t04cba3e3fc14e80048296.jpg', 'n_t04cba3e3fc14e80048296.jpg', 42019, 'jpg', 0, '222.246.62.223', 1418701185, '/uploads/2014/12/16/compression-n_t04cba3e3fc14e80048296.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t04cba3e3fc14e80048296.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t04cba3e3fc14e80048296.jpg', 1),
(1417594262, 0, '', '', '/uploads/2014/12/16/n_t04cba33e0c0188004828e.jpg', 'n_t04cba33e0c0188004828e.jpg', 45144, 'jpg', 0, '222.246.62.223', 1418701185, '/uploads/2014/12/16/compression-n_t04cba33e0c0188004828e.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t04cba33e0c0188004828e.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t04cba33e0c0188004828e.jpg', 1),
(1417594263, 0, '', '', '/uploads/2014/12/16/n_t04cba477dc24d800482a0.jpg', 'n_t04cba477dc24d800482a0.jpg', 76931, 'jpg', 0, '222.246.62.223', 1418701186, '/uploads/2014/12/16/compression-n_t04cba477dc24d800482a0.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t04cba477dc24d800482a0.jpg', 600, 450, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t04cba477dc24d800482a0.jpg', 1),
(1417594264, 0, '', '', '/uploads/2014/12/16/n_t04cba4173c1a380048292.jpg', 'n_t04cba4173c1a380048292.jpg', 45489, 'jpg', 0, '222.246.62.223', 1418701186, '/uploads/2014/12/16/compression-n_t04cba4173c1a380048292.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t04cba4173c1a380048292.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t04cba4173c1a380048292.jpg', 1),
(1417594265, 0, '', '', '/uploads/2014/12/16/n_s12539024937030645163.jpg', 'n_s12539024937030645163.jpg', 75806, 'jpg', 0, '222.246.62.223', 1418701389, '/uploads/2014/12/16/compression-n_s12539024937030645163.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_s12539024937030645163.jpg', 640, 457, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_s12539024937030645163.jpg', 1),
(1417594266, 0, '', '', '/uploads/2014/12/16/n_s12539025099853786156.jpg', 'n_s12539025099853786156.jpg', 76757, 'jpg', 0, '222.246.62.223', 1418701390, '/uploads/2014/12/16/compression-n_s12539025099853786156.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_s12539025099853786156.jpg', 480, 640, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_s12539025099853786156.jpg', 1),
(1417594267, 0, '', '', '/uploads/2014/12/16/n_t0fa624d3818b180031077.jpg', 'n_t0fa624d3818b180031077.jpg', 71507, 'jpg', 0, '222.246.62.223', 1418701391, '/uploads/2014/12/16/compression-n_t0fa624d3818b180031077.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t0fa624d3818b180031077.jpg', 640, 301, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t0fa624d3818b180031077.jpg', 1),
(1417594268, 0, '', '', '/uploads/2014/12/16/n_t0fa625e9319d680031081.jpg', 'n_t0fa625e9319d680031081.jpg', 27307, 'jpg', 0, '222.246.62.223', 1418701391, '/uploads/2014/12/16/compression-n_t0fa625e9319d680031081.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t0fa625e9319d680031081.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t0fa625e9319d680031081.jpg', 1),
(1417594269, 0, '', '', '/uploads/2014/12/16/n_t0fa62387d175880031085.jpg', 'n_t0fa62387d175880031085.jpg', 77111, 'jpg', 0, '222.246.62.223', 1418701392, '/uploads/2014/12/16/compression-n_t0fa62387d175880031085.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t0fa62387d175880031085.jpg', 640, 457, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t0fa62387d175880031085.jpg', 1),
(1417594270, 0, '', '', '/uploads/2014/12/16/n_t0fa62562f193780031077.jpg', 'n_t0fa62562f193780031077.jpg', 21128, 'jpg', 0, '222.246.62.223', 1418701392, '/uploads/2014/12/16/compression-n_t0fa62562f193780031077.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t0fa62562f193780031077.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t0fa62562f193780031077.jpg', 1),
(1417594271, 0, '', '', '/uploads/2014/12/16/n_s12546083857664061057.jpg', 'n_s12546083857664061057.jpg', 75910, 'jpg', 0, '222.246.62.223', 1418701559, '/uploads/2014/12/16/compression-n_s12546083857664061057.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_s12546083857664061057.jpg', 640, 425, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_s12546083857664061057.jpg', 1),
(1417594272, 0, '', '', '/uploads/2014/12/16/n_t014104cc9a9f580036fe8.jpg', 'n_t014104cc9a9f580036fe8.jpg', 98446, 'jpg', 0, '222.246.62.223', 1418701560, '/uploads/2014/12/16/compression-n_t014104cc9a9f580036fe8.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t014104cc9a9f580036fe8.jpg', 640, 426, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t014104cc9a9f580036fe8.jpg', 1),
(1417594273, 0, '', '', '/uploads/2014/12/16/n_t014105c09ac3d80036fe8.jpg', 'n_t014105c09ac3d80036fe8.jpg', 91525, 'jpg', 0, '222.246.62.223', 1418701561, '/uploads/2014/12/16/compression-n_t014105c09ac3d80036fe8.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t014105c09ac3d80036fe8.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t014105c09ac3d80036fe8.jpg', 1),
(1417594274, 0, '', '', '/uploads/2014/12/16/n_t0141068b7add480036fdf.jpg', 'n_t0141068b7add480036fdf.jpg', 20804, 'jpg', 0, '222.246.62.223', 1418701561, '/uploads/2014/12/16/compression-n_t0141068b7add480036fdf.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t0141068b7add480036fdf.jpg', 338, 220, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t0141068b7add480036fdf.jpg', 1),
(1417594275, 0, '', '', '/uploads/2014/12/16/n_t01410464ea91a80036fd2.jpg', 'n_t01410464ea91a80036fd2.jpg', 53100, 'jpg', 0, '222.246.62.223', 1418701562, '/uploads/2014/12/16/compression-n_t01410464ea91a80036fd2.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t01410464ea91a80036fd2.jpg', 536, 294, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t01410464ea91a80036fd2.jpg', 1),
(1417594276, 0, '', '', '/uploads/2014/12/16/n_t014105325aae880036fdf.jpg', 'n_t014105325aae880036fdf.jpg', 58933, 'jpg', 0, '222.246.62.223', 1418701562, '/uploads/2014/12/16/compression-n_t014105325aae880036fdf.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t014105325aae880036fdf.jpg', 622, 640, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t014105325aae880036fdf.jpg', 1),
(1417594277, 0, '', '', '/uploads/2014/12/16/n_t014106699ad9080036fe2.jpg', 'n_t014106699ad9080036fe2.jpg', 73720, 'jpg', 0, '222.246.62.223', 1418701563, '/uploads/2014/12/16/compression-n_t014106699ad9080036fe2.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t014106699ad9080036fe2.jpg', 640, 370, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t014106699ad9080036fe2.jpg', 1),
(1417594278, 0, '', '', '/uploads/2014/12/16/n_s12520913057898683297.jpg', 'n_s12520913057898683297.jpg', 84065, 'jpg', 0, '222.246.62.223', 1418701771, '/uploads/2014/12/16/compression-n_s12520913057898683297.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_s12520913057898683297.jpg', 640, 478, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_s12520913057898683297.jpg', 1),
(1417594279, 0, '', '', '/uploads/2014/12/16/n_s12520915945427282156.jpg', 'n_s12520915945427282156.jpg', 44123, 'jpg', 0, '222.246.62.223', 1418701772, '/uploads/2014/12/16/compression-n_s12520915945427282156.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_s12520915945427282156.jpg', 600, 349, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_s12520915945427282156.jpg', 1),
(1417594280, 0, '', '', '/uploads/2014/12/16/n_t0b87e1c6677be80023d94.jpg', 'n_t0b87e1c6677be80023d94.jpg', 99704, 'jpg', 0, '222.246.62.223', 1418701772, '/uploads/2014/12/16/compression-n_t0b87e1c6677be80023d94.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t0b87e1c6677be80023d94.jpg', 640, 477, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t0b87e1c6677be80023d94.jpg', 1),
(1417594281, 0, '', '', '/uploads/2014/12/16/n_t0b87e2796783580023d89.jpg', 'n_t0b87e2796783580023d89.jpg', 51452, 'jpg', 0, '222.246.62.223', 1418701773, '/uploads/2014/12/16/compression-n_t0b87e2796783580023d89.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t0b87e2796783580023d89.jpg', 640, 478, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t0b87e2796783580023d89.jpg', 1),
(1417594282, 0, '', '', '/uploads/2014/12/16/11.jpg', '11.jpg', 48391, 'jpg', 0, '222.246.62.223', 1418701925, '/uploads/2014/12/16/compression-11.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/11.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-11.jpg', 1),
(1417594283, 0, '', '', '/uploads/2014/12/16/222.jpg', '222.jpg', 52773, 'jpg', 0, '222.246.62.223', 1418701926, '/uploads/2014/12/16/compression-222.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/222.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-222.jpg', 1),
(1417594284, 0, '', '', '/uploads/2014/12/16/3333.jpg', '3333.jpg', 73963, 'jpg', 0, '222.246.62.223', 1418701927, '/uploads/2014/12/16/compression-3333.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/3333.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-3333.jpg', 1),
(1417594285, 0, '', '', '/uploads/2014/12/16/4444.jpg', '4444.jpg', 32053, 'jpg', 0, '222.246.62.223', 1418701927, '/uploads/2014/12/16/compression-4444.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/4444.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/16/compression-4444.jpg', 1),
(1417594286, 0, '', '', '/uploads/2014/12/16/n_s125209130578986832971.jpg', 'n_s12520913057898683297.jpg', 84065, 'jpg', 0, '222.246.62.223', 1418701928, '/uploads/2014/12/16/compression-n_s125209130578986832971.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_s125209130578986832971.jpg', 640, 478, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_s125209130578986832971.jpg', 1),
(1417594287, 0, '', '', '/uploads/2014/12/16/n_s125209159454272821561.jpg', 'n_s12520915945427282156.jpg', 44123, 'jpg', 0, '222.246.62.223', 1418701928, '/uploads/2014/12/16/compression-n_s125209159454272821561.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_s125209159454272821561.jpg', 600, 349, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_s125209159454272821561.jpg', 1),
(1417594288, 0, '', '', '/uploads/2014/12/16/n_t0b87e1c6677be80023d941.jpg', 'n_t0b87e1c6677be80023d94.jpg', 99704, 'jpg', 0, '222.246.62.223', 1418701929, '/uploads/2014/12/16/compression-n_t0b87e1c6677be80023d941.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t0b87e1c6677be80023d941.jpg', 640, 477, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t0b87e1c6677be80023d941.jpg', 1),
(1417594289, 0, '', '', '/uploads/2014/12/16/n_t0b87e2796783580023d891.jpg', 'n_t0b87e2796783580023d89.jpg', 51452, 'jpg', 0, '222.246.62.223', 1418701929, '/uploads/2014/12/16/compression-n_t0b87e2796783580023d891.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/16/n_t0b87e2796783580023d891.jpg', 640, 478, 'http://my.0736fdc.com/uploads/2014/12/16/compression-n_t0b87e2796783580023d891.jpg', 1),
(1417594290, 0, '', '', '/uploads/2014/12/17/n_s12445328316338839165.jpg', 'n_s12445328316338839165.jpg', 62073, 'jpg', 0, '117.114.130.17', 1418778808, '/uploads/2014/12/17/compression-n_s12445328316338839165.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/17/n_s12445328316338839165.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/17/compression-n_s12445328316338839165.jpg', 1),
(1417594291, 0, '', '', '/uploads/2014/12/17/n_s12445328337769915218.jpg', 'n_s12445328337769915218.jpg', 53507, 'jpg', 0, '117.114.130.17', 1418778809, '/uploads/2014/12/17/compression-n_s12445328337769915218.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/17/n_s12445328337769915218.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/17/compression-n_s12445328337769915218.jpg', 1),
(1417594292, 0, '', '', '/uploads/2014/12/17/n_s12445328346679329064.jpg', 'n_s12445328346679329064.jpg', 66501, 'jpg', 0, '117.114.130.17', 1418778809, '/uploads/2014/12/17/compression-n_s12445328346679329064.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/17/n_s12445328346679329064.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/17/compression-n_s12445328346679329064.jpg', 1),
(1417594293, 0, '', '', '/uploads/2014/12/17/n_s12445328350436301257.jpg', 'n_s12445328350436301257.jpg', 49290, 'jpg', 0, '117.114.130.17', 1418778810, '/uploads/2014/12/17/compression-n_s12445328350436301257.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/17/n_s12445328350436301257.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/17/compression-n_s12445328350436301257.jpg', 1),
(1417594294, 0, '', '', '/uploads/2014/12/17/n_s12445328351808010096.jpg', 'n_s12445328351808010096.jpg', 64177, 'jpg', 0, '117.114.130.17', 1418778810, '/uploads/2014/12/17/compression-n_s12445328351808010096.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/17/n_s12445328351808010096.jpg', 640, 480, 'http://my.0736fdc.com/uploads/2014/12/17/compression-n_s12445328351808010096.jpg', 1),
(1417594295, 0, '', '', '/uploads/2014/12/19/n_s12492443597820469188.jpg', 'n_s12492443597820469188.jpg', 46251, 'jpg', 0, '222.246.58.124', 1418971943, '/uploads/2014/12/19/compression-n_s12492443597820469188.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/19/n_s12492443597820469188.jpg', 480, 640, 'http://my.0736fdc.com/uploads/2014/12/19/compression-n_s12492443597820469188.jpg', 1);
INSERT INTO `xy_attachment` (`id`, `category_id`, `title`, `description`, `path`, `name`, `size`, `ext`, `user_id`, `upload_ip`, `upload_time`, `compression_image`, `sort`, `url`, `width`, `height`, `compression_url`, `siteid`) VALUES
(1417594296, 0, '', '', '/uploads/2014/12/19/n_t050ec00b16f928000f887.jpg', 'n_t050ec00b16f928000f887.jpg', 43933, 'jpg', 0, '222.246.58.124', 1418971944, '/uploads/2014/12/19/compression-n_t050ec00b16f928000f887.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/19/n_t050ec00b16f928000f887.jpg', 480, 640, 'http://my.0736fdc.com/uploads/2014/12/19/compression-n_t050ec00b16f928000f887.jpg', 1),
(1417594297, 0, '', '', '/uploads/2014/12/19/n_t050ec02a86fcb8000f897.jpg', 'n_t050ec02a86fcb8000f897.jpg', 42012, 'jpg', 0, '222.246.58.124', 1418971944, '/uploads/2014/12/19/compression-n_t050ec02a86fcb8000f897.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/19/n_t050ec02a86fcb8000f897.jpg', 480, 640, 'http://my.0736fdc.com/uploads/2014/12/19/compression-n_t050ec02a86fcb8000f897.jpg', 1),
(1417594298, 0, '', '', '/uploads/2014/12/19/n_t050ec00406f868000f886.jpg', 'n_t050ec00406f868000f886.jpg', 39785, 'jpg', 0, '222.246.58.124', 1418971945, '/uploads/2014/12/19/compression-n_t050ec00406f868000f886.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/19/n_t050ec00406f868000f886.jpg', 480, 640, 'http://my.0736fdc.com/uploads/2014/12/19/compression-n_t050ec00406f868000f886.jpg', 1),
(1417594299, 0, '', '', '/uploads/2014/12/19/n_t050ec02136fb98000f88c.jpg', 'n_t050ec02136fb98000f88c.jpg', 48652, 'jpg', 0, '222.246.58.124', 1418971945, '/uploads/2014/12/19/compression-n_t050ec02136fb98000f88c.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/19/n_t050ec02136fb98000f88c.jpg', 480, 640, 'http://my.0736fdc.com/uploads/2014/12/19/compression-n_t050ec02136fb98000f88c.jpg', 1),
(1417594300, 0, '', '', '/uploads/2014/12/19/n_t050ec03376fd78000f894.jpg', 'n_t050ec03376fd78000f894.jpg', 46810, 'jpg', 0, '222.246.58.124', 1418971946, '/uploads/2014/12/19/compression-n_t050ec03376fd78000f894.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/19/n_t050ec03376fd78000f894.jpg', 480, 640, 'http://my.0736fdc.com/uploads/2014/12/19/compression-n_t050ec03376fd78000f894.jpg', 1),
(1417594301, 0, '', '', '/uploads/2014/12/19/n_t0a7c1a1b66fc2800207d8.jpg', 'n_t0a7c1a1b66fc2800207d8.jpg', 38225, 'jpg', 0, '222.246.58.124', 1418972094, '/uploads/2014/12/19/compression-n_t0a7c1a1b66fc2800207d8.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/19/n_t0a7c1a1b66fc2800207d8.jpg', 400, 301, 'http://my.0736fdc.com/uploads/2014/12/19/compression-n_t0a7c1a1b66fc2800207d8.jpg', 1),
(1417594302, 0, '', '', '/uploads/2014/12/19/n_t0a7c1afb470d7800207d9.jpg', 'n_t0a7c1afb470d7800207d9.jpg', 31285, 'jpg', 0, '222.246.58.124', 1418972094, '/uploads/2014/12/19/compression-n_t0a7c1afb470d7800207d9.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/19/n_t0a7c1afb470d7800207d9.jpg', 427, 240, 'http://my.0736fdc.com/uploads/2014/12/19/compression-n_t0a7c1afb470d7800207d9.jpg', 1),
(1417594303, 0, '', '', '/uploads/2014/12/19/n_t0a7c1c9a372df800207df.jpg', 'n_t0a7c1c9a372df800207df.jpg', 65010, 'jpg', 0, '222.246.58.124', 1418972095, '/uploads/2014/12/19/compression-n_t0a7c1c9a372df800207df.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/19/n_t0a7c1c9a372df800207df.jpg', 480, 640, 'http://my.0736fdc.com/uploads/2014/12/19/compression-n_t0a7c1c9a372df800207df.jpg', 1),
(1417594304, 0, '', '', '/uploads/2014/12/20/n_s12554351483127704056.jpg', 'n_s12554351483127704056.jpg', 27733, 'jpg', 0, '222.246.52.229', 1419054459, '/uploads/2014/12/20/compression-n_s12554351483127704056.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/20/n_s12554351483127704056.jpg', 271, 177, 'http://my.0736fdc.com/uploads/2014/12/20/compression-n_s12554351483127704056.jpg', 1),
(1417594305, 0, '', '', '/uploads/2014/12/20/n_s12554351553154141116.jpg', 'n_s12554351553154141116.jpg', 23686, 'jpg', 0, '222.246.52.229', 1419054459, '/uploads/2014/12/20/compression-n_s12554351553154141116.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/20/n_s12554351553154141116.jpg', 269, 179, 'http://my.0736fdc.com/uploads/2014/12/20/compression-n_s12554351553154141116.jpg', 1),
(1417594306, 0, '', '', '/uploads/2014/12/20/n_t032241f1c9b948003fc53.jpg', 'n_t032241f1c9b948003fc53.jpg', 22693, 'jpg', 0, '222.246.52.229', 1419054459, '/uploads/2014/12/20/compression-n_t032241f1c9b948003fc53.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/20/n_t032241f1c9b948003fc53.jpg', 500, 280, 'http://my.0736fdc.com/uploads/2014/12/20/compression-n_t032241f1c9b948003fc53.jpg', 1),
(1417594307, 0, '', '', '/uploads/2014/12/20/n_t0322422bc9c198003fc3e.jpg', 'n_t0322422bc9c198003fc3e.jpg', 29517, 'jpg', 0, '222.246.52.229', 1419054460, '/uploads/2014/12/20/compression-n_t0322422bc9c198003fc3e.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/20/n_t0322422bc9c198003fc3e.jpg', 560, 420, 'http://my.0736fdc.com/uploads/2014/12/20/compression-n_t0322422bc9c198003fc3e.jpg', 1),
(1417594308, 0, '', '', '/uploads/2014/12/20/n_t0322425a39c788003fc40.jpg', 'n_t0322425a39c788003fc40.jpg', 16692, 'jpg', 0, '222.246.52.229', 1419054460, '/uploads/2014/12/20/compression-n_t0322425a39c788003fc40.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/20/n_t0322425a39c788003fc40.jpg', 560, 420, 'http://my.0736fdc.com/uploads/2014/12/20/compression-n_t0322425a39c788003fc40.jpg', 1),
(1417594309, 0, '', '', '/uploads/2014/12/28/600x600-1.jpg', '600x600-1.jpg', 64205, 'jpg', 0, '222.246.63.144', 1419756979, '/uploads/2014/12/28/compression-600x600-1.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/28/600x600-1.jpg', 600, 448, 'http://my.0736fdc.com/uploads/2014/12/28/compression-600x600-1.jpg', 1),
(1417594310, 0, '', '', '/uploads/2014/12/28/600x600.jpg', '600x600.jpg', 38985, 'jpg', 0, '222.246.63.144', 1419756979, '/uploads/2014/12/28/compression-600x600.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/28/600x600.jpg', 600, 448, 'http://my.0736fdc.com/uploads/2014/12/28/compression-600x600.jpg', 1),
(1417594311, 0, '', '', '/uploads/2014/12/29/IMG_20141222_123313.jpg', 'IMG_20141222_123313.jpg', 953130, 'jpg', 0, '218.75.137.66', 1419825268, '/uploads/2014/12/29/compression-IMG_20141222_123313.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/29/IMG_20141222_123313.jpg', 1944, 2592, 'http://my.0736fdc.com/uploads/2014/12/29/compression-IMG_20141222_123313.jpg', 1),
(1417594312, 0, '', '', '/uploads/2014/12/29/IMG_20141224_124324.jpg', 'IMG_20141224_124324.jpg', 742639, 'jpg', 0, '218.75.137.66', 1419841035, '/uploads/2014/12/29/compression-IMG_20141224_124324.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/29/IMG_20141224_124324.jpg', 1944, 2592, 'http://my.0736fdc.com/uploads/2014/12/29/compression-IMG_20141224_124324.jpg', 1),
(1417594313, 0, '', '', '/uploads/2014/12/29/IMG_20141224_124356.jpg', 'IMG_20141224_124356.jpg', 604490, 'jpg', 0, '218.75.137.66', 1419841062, '/uploads/2014/12/29/compression-IMG_20141224_124356.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/29/IMG_20141224_124356.jpg', 1944, 2592, 'http://my.0736fdc.com/uploads/2014/12/29/compression-IMG_20141224_124356.jpg', 1),
(1417594314, 0, '', '', '/uploads/2014/12/29/主房到客厅.jpg', '主房到客厅.jpg', 84161, 'jpg', 0, '218.75.144.104', 1419842536, '/uploads/2014/12/29/compression-主房到客厅.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/29/主房到客厅.jpg', 1280, 960, 'http://my.0736fdc.com/uploads/2014/12/29/compression-主房到客厅.jpg', 1),
(1417594315, 0, '', '', '/uploads/2014/12/29/厨房和餐厅.jpg', '厨房和餐厅.jpg', 92616, 'jpg', 0, '218.75.144.104', 1419842536, '/uploads/2014/12/29/compression-厨房和餐厅.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/29/厨房和餐厅.jpg', 1280, 960, 'http://my.0736fdc.com/uploads/2014/12/29/compression-厨房和餐厅.jpg', 1),
(1417594316, 0, '', '', '/uploads/2014/12/29/厨房设备.jpg', '厨房设备.jpg', 107047, 'jpg', 0, '218.75.144.104', 1419842537, '/uploads/2014/12/29/compression-厨房设备.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/29/厨房设备.jpg', 1280, 960, 'http://my.0736fdc.com/uploads/2014/12/29/compression-厨房设备.jpg', 1),
(1417594317, 0, '', '', '/uploads/2014/12/29/电视和矮组.jpg', '电视和矮组.jpg', 104946, 'jpg', 0, '218.75.144.104', 1419842537, '/uploads/2014/12/29/compression-电视和矮组.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/29/电视和矮组.jpg', 1280, 960, 'http://my.0736fdc.com/uploads/2014/12/29/compression-电视和矮组.jpg', 1),
(1417594318, 0, '', '', '/uploads/2014/12/29/客房看主房.jpg', '客房看主房.jpg', 95391, 'jpg', 0, '218.75.144.104', 1419842538, '/uploads/2014/12/29/compression-客房看主房.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/29/客房看主房.jpg', 1280, 960, 'http://my.0736fdc.com/uploads/2014/12/29/compression-客房看主房.jpg', 1),
(1417594319, 0, '', '', '/uploads/2014/12/29/客房沙发床.jpg', '客房沙发床.jpg', 93449, 'jpg', 0, '218.75.144.104', 1419842538, '/uploads/2014/12/29/compression-客房沙发床.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/29/客房沙发床.jpg', 1280, 960, 'http://my.0736fdc.com/uploads/2014/12/29/compression-客房沙发床.jpg', 1),
(1417594320, 0, '', '', '/uploads/2014/12/29/客厅2.jpg', '客厅2.jpg', 92571, 'jpg', 0, '218.75.144.104', 1419842539, '/uploads/2014/12/29/compression-客厅2.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/29/客厅2.jpg', 1280, 960, 'http://my.0736fdc.com/uploads/2014/12/29/compression-客厅2.jpg', 1),
(1417594321, 0, '', '', '/uploads/2014/12/29/客厅.jpg', '客厅.jpg', 98325, 'jpg', 0, '218.75.144.104', 1419842540, '/uploads/2014/12/29/compression-客厅.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/29/客厅.jpg', 1280, 960, 'http://my.0736fdc.com/uploads/2014/12/29/compression-客厅.jpg', 1),
(1417594322, 0, '', '', '/uploads/2014/12/29/沙发.jpg', '沙发.jpg', 110909, 'jpg', 0, '218.75.144.104', 1419842541, '/uploads/2014/12/29/compression-沙发.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/29/沙发.jpg', 1280, 960, 'http://my.0736fdc.com/uploads/2014/12/29/compression-沙发.jpg', 1),
(1417594323, 0, '', '', '/uploads/2014/12/29/主房.jpg', '主房.jpg', 96771, 'jpg', 0, '218.75.144.104', 1419842542, '/uploads/2014/12/29/compression-主房.jpg', 0, 'http://my.0736fdc.com/uploads/2014/12/29/主房.jpg', 1280, 960, 'http://my.0736fdc.com/uploads/2014/12/29/compression-主房.jpg', 1),
(1417594324, 1, '', '', '/uploads/2014/12/30/1419929425.png', 'mqfc1.png', 406848, 'png', 0, '50.116.5.59', 1419929425, '/uploads/2014/12/30/compression-1419929425.png', 0, 'http://esf.0736fdc.com/uploads/2014/12/30/1419929425.png', 605, 406, NULL, 1),
(1417594325, 0, '', '', '/uploads/2015/01/09/psb.jpeg', 'psb.jpeg', 52704, 'jpeg', 0, '127.0.0.1', 1420769416, '/uploads/2015/01/09/compression-psb.jpeg', 0, 'http://www.cdfdc.com/c_esf/public/uploads/2015/01/09/psb.jpeg', 670, 502, 'http://www.cdfdc.com/c_esf/public/uploads/2015/01/09/compression-psb.jpeg', 1),
(1417594326, 0, '', '', '/uploads/2015/01/09/psb1.jpeg', 'psb.jpeg', 52704, 'jpeg', 0, '127.0.0.1', 1420769582, '/uploads/2015/01/09/compression-psb1.jpeg', 0, 'http://www.cdfdc.com/c_esf/public/uploads/2015/01/09/psb1.jpeg', 670, 502, 'http://www.cdfdc.com/c_esf/public/uploads/2015/01/09/compression-psb1.jpeg', 1),
(1417594327, 0, '', '', '/uploads/2015/01/09/psb2.jpeg', 'psb.jpeg', 52704, 'jpeg', 0, '127.0.0.1', 1420769612, '/uploads/2015/01/09/compression-psb2.jpeg', 0, 'http://www.cdfdc.com/c_esf/public/uploads/2015/01/09/psb2.jpeg', 670, 502, 'http://www.cdfdc.com/c_esf/public/uploads/2015/01/09/compression-psb2.jpeg', 1),
(1417594328, 0, '', '', '/uploads/2015/01/09/psb3.jpeg', 'psb.jpeg', 52704, 'jpeg', 0, '127.0.0.1', 1420769681, '/uploads/2015/01/09/compression-psb3.jpeg', 0, 'http://www.cdfdc.com/c_esf/public/uploads/2015/01/09/psb3.jpeg', 670, 502, 'http://www.cdfdc.com/c_esf/public/uploads/2015/01/09/compression-psb3.jpeg', 1),
(1417594329, 0, '', '', '/uploads/2015/01/09/psb4.jpeg', 'psb.jpeg', 52704, 'jpeg', 0, '127.0.0.1', 1420769726, '/uploads/2015/01/09/compression-psb4.jpeg', 0, 'http://www.cdfdc.com/c_esf/public/uploads/2015/01/09/psb4.jpeg', 670, 502, 'http://www.cdfdc.com/c_esf/public/uploads/2015/01/09/compression-psb4.jpeg', 1),
(1417594330, 1, '', '', '/uploads/2015/01/09/1420770430.jpeg', 'psb.jpeg', 52704, 'jpeg', 0, '127.0.0.1', 1420770430, '/uploads/2015/01/09/compression-1420770430.jpeg', 0, 'http://esf.0736fdc.com/uploads/2015/01/09/1420770430.jpeg', 670, 502, NULL, 1),
(1417594331, 0, '', '', '/uploads/2015/01/09/psb5.jpeg', 'psb.jpeg', 52704, 'jpeg', 0, '127.0.0.1', 1420771256, '/uploads/2015/01/09/compression-psb5.jpeg', 0, 'http://www.cdfdc.com/c_esf/public/uploads/2015/01/09/psb5.jpeg', 670, 502, 'http://www.cdfdc.com/c_esf/public/uploads/2015/01/09/compression-psb5.jpeg', 1),
(1417594332, 0, '', '', '/uploads/2015/01/09/psb6.jpeg', 'psb.jpeg', 52704, 'jpeg', 0, '127.0.0.1', 1420771346, '/uploads/2015/01/09/compression-psb6.jpeg', 0, 'http://www.cdfdc.com/c_esf/public/uploads/2015/01/09/psb6.jpeg', 670, 502, 'http://www.cdfdc.com/c_esf/public/uploads/2015/01/09/compression-psb6.jpeg', 1),
(1417594333, 0, '', '', '/uploads/2015/01/09/psb7.jpeg', 'psb.jpeg', 52704, 'jpeg', 0, '127.0.0.1', 1420771511, '/uploads/2015/01/09/compression-psb7.jpeg', 0, 'http://www.cdfdc.com/c_esf/public/uploads/2015/01/09/psb7.jpeg', 670, 502, 'http://www.cdfdc.com/c_esf/public/uploads/2015/01/09/compression-psb7.jpeg', 1),
(1417594334, 0, '', '', '/uploads/2015/01/09/psb8.jpeg', 'psb.jpeg', 52704, 'jpeg', 0, '127.0.0.1', 1420771702, '/uploads/2015/01/09/compression-psb8.jpeg', 0, 'http://www.cdfdc.com/c_esf/public/uploads/2015/01/09/psb8.jpeg', 670, 502, 'http://www.cdfdc.com/c_esf/public/uploads/2015/01/09/compression-psb8.jpeg', 1),
(1417594335, 0, '', '', '/uploads/2015/01/14/QQ20150113-2.png', 'QQ20150113-2.png', 38498, 'png', 0, '127.0.0.1', 1421222230, '/uploads/2015/01/14/compression-QQ20150113-2.png', 0, 'http://www.cdfdc.com/c_esf/public/uploads/2015/01/14/QQ20150113-2.png', 884, 454, 'http://www.cdfdc.com/c_esf/public/uploads/2015/01/14/compression-QQ20150113-2.png', 1),
(1417594336, 0, '', '', '/uploads/2015/01/29/QQ20150128-1.png', 'QQ20150128-1.png', 138122, 'png', 0, '127.0.0.1', 1422498222, '/uploads/2015/01/29/compression-QQ20150128-1.png', 0, 'http://my.cdfdc.com/uploads/2015/01/29/QQ20150128-1.png', 359, 538, 'http://my.cdfdc.com/uploads/2015/01/29/compression-QQ20150128-1.png', 1),
(1417594337, 0, '', '', '/uploads/2015/01/29/QQ截图20150129100400.png', 'QQ截图20150129100400.png', 408287, 'png', 0, '127.0.0.1', 1422498670, '/uploads/2015/01/29/compression-QQ截图20150129100400.png', 0, 'http://my.cdfdc.com/uploads/2015/01/29/QQ截图20150129100400.png', 468, 619, 'http://my.cdfdc.com/uploads/2015/01/29/compression-QQ截图20150129100400.png', 1),
(1417594338, 0, '', '', '/uploads/2015/01/29/IMG_0198.jpg', 'IMG_0198.jpg', 1492656, 'jpg', 0, '127.0.0.1', 1422499232, '/uploads/2015/01/29/compression-IMG_0198.jpg', 0, 'http://my.cdfdc.com/uploads/2015/01/29/IMG_0198.jpg', 3264, 2448, 'http://my.cdfdc.com/uploads/2015/01/29/compression-IMG_0198.jpg', 1);

-- --------------------------------------------------------

--
-- 表的结构 `xy_category`
--

CREATE TABLE IF NOT EXISTS `xy_category` (
`id` smallint(5) unsigned NOT NULL,
  `siteid` smallint(5) NOT NULL DEFAULT '0',
  `catname` varchar(30) NOT NULL,
  `catdir` varchar(30) NOT NULL,
  `parentdir` varchar(100) NOT NULL,
  `arrparentid` varchar(255) NOT NULL,
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `child` tinyint(1) NOT NULL DEFAULT '0',
  `arrchildid` varchar(255) NOT NULL,
  `modelid` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `module` char(24) NOT NULL,
  `title` varchar(150) NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `image` varchar(100) NOT NULL,
  `url` varchar(150) NOT NULL,
  `level` smallint(2) NOT NULL,
  `setting` mediumtext NOT NULL,
  `ismenu` tinyint(1) NOT NULL DEFAULT '0',
  `sethtml` tinyint(1) NOT NULL DEFAULT '0',
  `letter` varchar(255) NOT NULL,
  `hits` int(10) NOT NULL DEFAULT '0',
  `items` mediumint(8) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `xy_category`
--

INSERT INTO `xy_category` (`id`, `siteid`, `catname`, `catdir`, `parentdir`, `arrparentid`, `parentid`, `child`, `arrchildid`, `modelid`, `module`, `title`, `keywords`, `description`, `listorder`, `image`, `url`, `level`, `setting`, `ismenu`, `sethtml`, `letter`, `hits`, `items`, `type`) VALUES
(1, 1, '头条', 'toutiao', '', '0', 0, 0, '1', 16, '内容模型', '', '', '', 0, '', '', 1, 'array (\n  ''meta_title'' => '''',\n  ''meta_keywords'' => '''',\n  ''meta_description'' => '''',\n)', 1, 0, '', 0, 0, 1),
(2, 1, '导购', 'daogou', '', '0', 0, 0, '2', 16, '内容模型', '', '', '', 0, '', '', 1, 'array (\n  ''meta_title'' => '''',\n  ''meta_keywords'' => '''',\n  ''meta_description'' => '''',\n)', 1, 0, '', 0, 0, 1),
(3, 1, '快讯', 'kuaixun', '', '0', 0, 0, '3', 16, '内容模型', '', '', '', 0, '', '', 1, 'array (\n  ''meta_title'' => '''',\n  ''meta_keywords'' => '''',\n  ''meta_description'' => '''',\n)', 1, 0, '', 0, 0, 1),
(4, 1, '热点', 'redian', 'top/', '0,6', 6, 0, '4', 16, '内容模型', '', '', '', 0, '', '', 2, 'array (\n  ''meta_title'' => '''',\n  ''meta_keywords'' => '''',\n  ''meta_description'' => '''',\n)', 1, 0, '', 0, 0, 1),
(6, 1, '顶级栏目', 'top', '', '0', 0, 1, '6,4', 16, '内容模型', '', '', '', 0, '', '', 1, 'array (\n  ''meta_title'' => '''',\n  ''meta_keywords'' => '''',\n  ''meta_description'' => '''',\n)', 1, 0, '', 0, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `xy_house`
--

CREATE TABLE IF NOT EXISTS `xy_house` (
`id` mediumint(8) NOT NULL,
  `siteid` smallint(5) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `proaddr` varchar(255) NOT NULL,
  `areaid` smallint(2) NOT NULL,
  `tradeid` smallint(2) NOT NULL,
  `propertyid` varchar(255) NOT NULL,
  `policyid` smallint(2) NOT NULL,
  `decorationid` smallint(2) NOT NULL,
  `sellid` smallint(2) NOT NULL,
  `layerid` varchar(255) NOT NULL,
  `traffic` varchar(255) NOT NULL,
  `map` varchar(255) NOT NULL,
  `tell` varchar(50) NOT NULL,
  `opendate` int(10) NOT NULL,
  `transferdate` int(10) NOT NULL,
  `selladdr` varchar(255) NOT NULL,
  `presellnum` varchar(255) NOT NULL,
  `floorarea` float NOT NULL,
  `coveredarea` float NOT NULL,
  `dfl` float NOT NULL,
  `lhl` float NOT NULL,
  `rjl` float NOT NULL,
  `propertycompany` varchar(255) NOT NULL,
  `propertyfee` varchar(255) NOT NULL,
  `schoolarea` varchar(255) NOT NULL,
  `propertyaddr` varchar(255) NOT NULL,
  `garage` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `setting` text NOT NULL,
  `islink` tinyint(1) NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL,
  `status` smallint(2) NOT NULL,
  `type` tinyint(2) NOT NULL DEFAULT '1',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `inputtime` int(10) NOT NULL,
  `updatetime` int(10) NOT NULL,
  `developer` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `xy_house`
--

INSERT INTO `xy_house` (`id`, `siteid`, `title`, `price`, `proaddr`, `areaid`, `tradeid`, `propertyid`, `policyid`, `decorationid`, `sellid`, `layerid`, `traffic`, `map`, `tell`, `opendate`, `transferdate`, `selladdr`, `presellnum`, `floorarea`, `coveredarea`, `dfl`, `lhl`, `rjl`, `propertycompany`, `propertyfee`, `schoolarea`, `propertyaddr`, `garage`, `description`, `thumb`, `setting`, `islink`, `url`, `status`, `type`, `listorder`, `inputtime`, `updatetime`, `developer`) VALUES
(2, 1, '天源星城', 0, '武陵区', 5, 0, '2,3', 0, 3, 2, '3,4', '', '111.695187, 29.043195', '', 1401206400, 1401206400, '', '', 0, 0, 0, 0, 0, '', '', '', '', '', '', '', '', 0, '/wechat/Wx/Index/project/id/2.html', 99, 1, 0, 1401235200, 0, '2|1'),
(3, 1, '金色世纪', 0, '常德市紫菱路与荷花路交汇处东北角', 6, 0, '1,2,3,4', 0, 0, 1, '4,5', '11,14,15,101路公交车经过', '111.695187, 29.043195', '7176699', 1401379200, 1401379200, '常德市紫菱路与荷花路交汇处东北角', '2013013 2012042', 60587.2, 156281, 0, 30, 2.58, '', '', '', '', '', '', 'http://www.cdfdc.com/wechat/Uploads/2014/01/23/1390468861.jpg', '', 0, '/xiaoyaocms/Wx/Index/project/id/3.html', 99, 1, 0, 1401410857, 0, '2');

-- --------------------------------------------------------

--
-- 表的结构 `xy_house_data`
--

CREATE TABLE IF NOT EXISTS `xy_house_data` (
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `relation` text NOT NULL,
  `album` varchar(255) NOT NULL,
  `huxing` varchar(255) NOT NULL,
  `intent` varchar(255) NOT NULL,
  `readpoint` smallint(5) unsigned NOT NULL DEFAULT '0',
  `groupids_view` varchar(100) NOT NULL,
  `paginationtype` tinyint(1) NOT NULL,
  `maxcharperpage` mediumint(6) NOT NULL,
  `template` varchar(30) NOT NULL,
  `allow_comment` tinyint(1) unsigned NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xy_house_data`
--

INSERT INTO `xy_house_data` (`id`, `content`, `relation`, `album`, `huxing`, `intent`, `readpoint`, `groupids_view`, `paginationtype`, `maxcharperpage`, `template`, `allow_comment`) VALUES
(2, '<p><img alt="" src="http://www.cdfdc.com/cdtyzj/Uploads/2014/03/03/1393821888.png" style="height:366px; width:259px" />delete_housedelete_housedelete_housedelete_housedelete_housedelete_house</p>\r\n', '', '|3', '1|2', '', 0, '', 0, 0, '', 1),
(3, '', 'array (\n  ''ids'' => ''2|3|813'',\n  ''cats'' => ''2|1|1'',\n  ''title'' => ''“七里桥堡相约爱琴海”千人相亲交友会盛大启动|关于加强诚信教育 进一步严肃考风考纪的通知|超大市政广场是我家的大花园'',\n)', '3|1', '1', '', 0, '', 0, 0, '', 1);

-- --------------------------------------------------------

--
-- 表的结构 `xy_linkage`
--

CREATE TABLE IF NOT EXISTS `xy_linkage` (
`id` smallint(5) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `style` varchar(35) NOT NULL,
  `parentid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `child` tinyint(1) NOT NULL,
  `arrchildid` mediumtext NOT NULL,
  `keyid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `description` varchar(255) DEFAULT NULL,
  `setting` varchar(255) DEFAULT NULL,
  `siteid` smallint(5) NOT NULL DEFAULT '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3783 ;

--
-- 转存表中的数据 `xy_linkage`
--

INSERT INTO `xy_linkage` (`id`, `name`, `style`, `parentid`, `child`, `arrchildid`, `keyid`, `listorder`, `description`, `setting`, `siteid`) VALUES
(3722, '临夏州', '', 3388, 0, '3722', 3360, 0, '', NULL, 0),
(3723, '甘州', '', 3388, 0, '3723', 3360, 0, '', NULL, 0),
(3724, '西宁市', '', 3389, 0, '3724', 3360, 0, '', NULL, 0),
(3725, '海东地区', '', 3389, 0, '3725', 3360, 0, '', NULL, 0),
(3726, '海州', '', 3389, 0, '3726', 3360, 0, '', NULL, 0),
(3727, '黄南州', '', 3389, 0, '3727', 3360, 0, '', NULL, 0),
(3728, '海南州', '', 3389, 0, '3728', 3360, 0, '', NULL, 0),
(3729, '果洛州', '', 3389, 0, '3729', 3360, 0, '', NULL, 0),
(3721, '陇南市', '', 3388, 0, '3721', 3360, 0, '', NULL, 0),
(3719, '庆阳市', '', 3388, 0, '3719', 3360, 0, '', NULL, 0),
(3717, '平凉市', '', 3388, 0, '3717', 3360, 0, '', NULL, 0),
(3714, '天水市', '', 3388, 0, '3714', 3360, 0, '', NULL, 0),
(3715, '武威市', '', 3388, 0, '3715', 3360, 0, '', NULL, 0),
(3712, '金昌市', '', 3388, 0, '3712', 3360, 0, '', NULL, 0),
(3713, '白银市', '', 3388, 0, '3713', 3360, 0, '', NULL, 0),
(3711, '嘉峪关市', '', 3388, 0, '3711', 3360, 0, '', NULL, 0),
(3708, '安康市', '', 3387, 0, '3708', 3360, 0, '', NULL, 0),
(3709, '商洛市', '', 3387, 0, '3709', 3360, 0, '', NULL, 0),
(3707, '榆林市', '', 3387, 0, '3707', 3360, 0, '', NULL, 0),
(3704, '渭南市', '', 3387, 0, '3704', 3360, 0, '', NULL, 0),
(3705, '延安市', '', 3387, 0, '3705', 3360, 0, '', NULL, 0),
(3702, '宝鸡市', '', 3387, 0, '3702', 3360, 0, '', NULL, 0),
(3703, '咸阳市', '', 3387, 0, '3703', 3360, 0, '', NULL, 0),
(3701, '铜川市', '', 3387, 0, '3701', 3360, 0, '', NULL, 0),
(3697, '那曲', '', 3386, 0, '3697', 3360, 0, '', NULL, 0),
(3698, '阿里', '', 3386, 0, '3698', 3360, 0, '', NULL, 0),
(3699, '林芝', '', 3386, 0, '3699', 3360, 0, '', NULL, 0),
(3694, '昌都', '', 3386, 0, '3694', 3360, 0, '', NULL, 0),
(3695, '山南', '', 3386, 0, '3695', 3360, 0, '', NULL, 0),
(3696, '日喀则', '', 3386, 0, '3696', 3360, 0, '', NULL, 0),
(3692, '迪庆', '', 3385, 0, '3692', 3360, 0, '', NULL, 0),
(3693, '拉萨市', '', 3386, 0, '3693', 3360, 0, '', NULL, 0),
(3690, '德宏', '', 3385, 0, '3690', 3360, 0, '', NULL, 0),
(3691, '怒江', '', 3385, 0, '3691', 3360, 0, '', NULL, 0),
(3687, '文山州', '', 3385, 0, '3687', 3360, 0, '', NULL, 0),
(3688, '西双版纳', '', 3385, 0, '3688', 3360, 0, '', NULL, 0),
(3689, '大理', '', 3385, 0, '3689', 3360, 0, '', NULL, 0),
(3684, '临沧市', '', 3385, 0, '3684', 3360, 0, '', NULL, 0),
(3685, '楚雄州', '', 3385, 0, '3685', 3360, 0, '', NULL, 0),
(3686, '红河州', '', 3385, 0, '3686', 3360, 0, '', NULL, 0),
(3681, '昭通市', '', 3385, 0, '3681', 3360, 0, '', NULL, 0),
(3682, '丽江市', '', 3385, 0, '3682', 3360, 0, '', NULL, 0),
(3683, '思茅市', '', 3385, 0, '3683', 3360, 0, '', NULL, 0),
(3678, '曲靖市', '', 3385, 0, '3678', 3360, 0, '', NULL, 0),
(3679, '玉溪市', '', 3385, 0, '3679', 3360, 0, '', NULL, 0),
(3680, '保山市', '', 3385, 0, '3680', 3360, 0, '', NULL, 0),
(3676, '黔南州', '', 3384, 0, '3676', 3360, 0, '', NULL, 0),
(3677, '昆明市', '', 3385, 0, '3677', 3360, 0, '', NULL, 0),
(3675, '黔东南州', '', 3384, 0, '3675', 3360, 0, '', NULL, 0),
(3671, '安顺市', '', 3384, 0, '3671', 3360, 0, '', NULL, 0),
(3672, '铜仁地区', '', 3384, 0, '3672', 3360, 0, '', NULL, 0),
(3670, '遵义市', '', 3384, 0, '3670', 3360, 0, '', NULL, 0),
(3669, '六盘水市', '', 3384, 0, '3669', 3360, 0, '', NULL, 0),
(3667, '凉山州', '', 3383, 0, '3667', 3360, 0, '', NULL, 0),
(3665, '阿坝州', '', 3383, 0, '3665', 3360, 0, '', NULL, 0),
(3666, '甘孜州', '', 3383, 0, '3666', 3360, 0, '', NULL, 0),
(3661, '达州市', '', 3383, 0, '3661', 3360, 0, '', NULL, 0),
(3662, '雅安市', '', 3383, 0, '3662', 3360, 0, '', NULL, 0),
(3663, '巴中市', '', 3383, 0, '3663', 3360, 0, '', NULL, 0),
(3660, '广安市', '', 3383, 0, '3660', 3360, 0, '', NULL, 0),
(3659, '宜宾市', '', 3383, 0, '3659', 3360, 0, '', NULL, 0),
(3658, '眉山市', '', 3383, 0, '3658', 3360, 0, '', NULL, 0),
(3656, '乐山市', '', 3383, 0, '3656', 3360, 0, '', NULL, 0),
(3652, '绵阳市', '', 3383, 0, '3652', 3360, 0, '', NULL, 0),
(3641, '陵水黎族自治县', '', 3382, 0, '3641', 3360, 0, '', NULL, 0),
(3640, '乐东黎族自治县', '', 3382, 0, '3640', 3360, 0, '', NULL, 0),
(3639, '昌江黎族自治县', '', 3382, 0, '3639', 3360, 0, '', NULL, 0),
(3636, '澄迈县', '', 3382, 0, '3636', 3360, 0, '', NULL, 0),
(3635, '屯昌县', '', 3382, 0, '3635', 3360, 0, '', NULL, 0),
(3634, '定安县', '', 3382, 0, '3634', 3360, 0, '', NULL, 0),
(3633, '东方市', '', 3382, 0, '3633', 3360, 0, '', NULL, 0),
(3631, '文昌市', '', 3382, 0, '3631', 3360, 0, '', NULL, 0),
(3630, '儋州市', '', 3382, 0, '3630', 3360, 0, '', NULL, 0),
(3616, '北海市', '', 3381, 0, '3616', 3360, 0, '', NULL, 0),
(3606, '清远市', '', 3380, 0, '3606', 3360, 0, '', NULL, 0),
(3605, '阳江市', '', 3380, 0, '3605', 3360, 0, '', NULL, 0),
(3602, '梅州市', '', 3380, 0, '3602', 3360, 0, '', NULL, 0),
(3599, '茂名市', '', 3380, 0, '3599', 3360, 0, '', NULL, 0),
(3598, '湛江市', '', 3380, 0, '3598', 3360, 0, '', NULL, 0),
(3597, '江门市', '', 3380, 0, '3597', 3360, 0, '', NULL, 0),
(3588, '怀化市', '', 3379, 0, '3588', 3360, 0, '', NULL, 0),
(3567, '孝感市', '', 3378, 0, '3567', 3360, 0, '', NULL, 0),
(3544, '开封市', '', 3377, 0, '3544', 3360, 0, '', NULL, 0),
(3514, '宁德市', '', 3374, 0, '3514', 3360, 0, '', NULL, 0),
(3506, '福州市', '', 3374, 0, '3506', 3360, 0, '', NULL, 0),
(3501, '巢湖市', '', 3373, 0, '3501', 3360, 0, '', NULL, 0),
(3484, '金华市', '', 3372, 0, '3484', 3360, 0, '', NULL, 0),
(3476, '泰州市', '', 3371, 0, '3476', 3360, 0, '', NULL, 0),
(3465, '南京市', '', 3371, 0, '3465', 3360, 0, '', NULL, 0),
(3443, '长春市', '', 3369, 0, '3443', 3360, 0, '', NULL, 0),
(3432, '抚顺市', '', 3368, 0, '3432', 3360, 0, '', NULL, 0),
(3418, '包头市', '', 3367, 0, '3418', 3360, 0, '', NULL, 0),
(3417, '呼和浩特市', '', 3367, 0, '3417', 3360, 0, '', NULL, 0),
(3416, '吕梁市', '', 3366, 0, '3416', 3360, 0, '', NULL, 0),
(3412, '晋中市', '', 3366, 0, '3412', 3360, 0, '', NULL, 0),
(3389, '青海省', '', 0, 1, '3389,3724,3725,3726,3727,3728,3729,3730,3731', 3360, 25, '', NULL, 0),
(3388, '甘肃省', '', 0, 1, '3388,3710,3711,3712,3713,3714,3715,3716,3717,3718,3719,3720,3721,3722,3723', 3360, 24, '', NULL, 0),
(3384, '贵州省', '', 0, 1, '3384,3668,3669,3670,3671,3672,3673,3674,3675,3676', 3360, 20, '', NULL, 0),
(3382, '海南省', '', 0, 1, '3382,3626,3627,3628,3629,3630,3631,3632,3633,3634,3635,3636,3637,3638,3639,3640,3641,3642,3643,3644,3645,3646', 3360, 18, '', NULL, 0),
(3380, '广东省', '', 0, 1, '3380,3591,3592,3593,3594,3595,3596,3597,3598,3599,3600,3601,3602,3603,3604,3605,3606,3607,3608,3609,3610,3611', 3360, 16, '', NULL, 0),
(3378, '湖北省', '', 0, 1, '3378,3560,3561,3562,3563,3564,3565,3566,3567,3568,3569,3570,3571,3572,3573,3574,3575,3576', 3360, 14, '', NULL, 0),
(3376, '山东省', '', 0, 1, '3376,3526,3527,3528,3529,3530,3531,3532,3533,3534,3535,3536,3537,3538,3539,3540,3541,3542', 3360, 12, '', NULL, 0),
(3374, '福建省', '', 0, 1, '3374,3506,3507,3508,3509,3510,3511,3512,3513,3514', 3360, 10, '', NULL, 0),
(3373, '安徽省', '', 0, 1, '3373,3489,3490,3491,3492,3493,3494,3495,3496,3497,3498,3499,3500,3501,3502,3503,3504,3505', 3360, 9, '', NULL, 0),
(3371, '江苏省', '', 0, 1, '3371,3465,3466,3467,3468,3469,3470,3471,3472,3473,3474,3475,3476,3477', 3360, 7, '', NULL, 0),
(3370, '黑龙江省', '', 0, 1, '3370,3452,3453,3454,3455,3456,3457,3458,3459,3460,3461,3462,3463,3464', 3360, 6, '', NULL, 0),
(3369, '吉林省', '', 0, 1, '3369,3443,3444,3445,3446,3447,3448,3449,3450,3451', 3360, 5, '', NULL, 0),
(3780, '直辖市', '', 0, 1, '3780,3361,3362,3363,3364', 3360, 0, '', NULL, 0),
(3730, '玉树州', '', 3389, 0, '3730', 3360, 0, '', NULL, 0),
(3720, '定西市', '', 3388, 0, '3720', 3360, 0, '', NULL, 0),
(3718, '酒泉市', '', 3388, 0, '3718', 3360, 0, '', NULL, 0),
(3716, '张掖市', '', 3388, 0, '3716', 3360, 0, '', NULL, 0),
(3710, '兰州市', '', 3388, 0, '3710', 3360, 0, '', NULL, 0),
(3706, '汉中市', '', 3387, 0, '3706', 3360, 0, '', NULL, 0),
(3700, '西安市', '', 3387, 0, '3700', 3360, 0, '', NULL, 0),
(3674, '毕节地区', '', 3384, 0, '3674', 3360, 0, '', NULL, 0),
(3673, '黔西南州', '', 3384, 0, '3673', 3360, 0, '', NULL, 0),
(3668, '贵阳市', '', 3384, 0, '3668', 3360, 0, '', NULL, 0),
(3664, '资阳市', '', 3383, 0, '3664', 3360, 0, '', NULL, 0),
(3657, '南充市', '', 3383, 0, '3657', 3360, 0, '', NULL, 0),
(3655, '内江市', '', 3383, 0, '3655', 3360, 0, '', NULL, 0),
(3654, '遂宁市', '', 3383, 0, '3654', 3360, 0, '', NULL, 0),
(3653, '广元市', '', 3383, 0, '3653', 3360, 0, '', NULL, 0),
(3651, '德阳市', '', 3383, 0, '3651', 3360, 0, '', NULL, 0),
(3650, '泸州市', '', 3383, 0, '3650', 3360, 0, '', NULL, 0),
(3649, '攀枝花市', '', 3383, 0, '3649', 3360, 0, '', NULL, 0),
(3648, '自贡市', '', 3383, 0, '3648', 3360, 0, '', NULL, 0),
(3647, '成都市', '', 3383, 0, '3647', 3360, 0, '', NULL, 0),
(3646, '中沙群岛的岛礁及其海域', '', 3382, 0, '3646', 3360, 0, '', NULL, 0),
(3645, '南沙群岛', '', 3382, 0, '3645', 3360, 0, '', NULL, 0),
(3644, '西沙群岛', '', 3382, 0, '3644', 3360, 0, '', NULL, 0),
(3643, '琼中黎族苗族自治县', '', 3382, 0, '3643', 3360, 0, '', NULL, 0),
(3642, '保亭黎族苗族自治县', '', 3382, 0, '3642', 3360, 0, '', NULL, 0),
(3638, '白沙黎族自治县', '', 3382, 0, '3638', 3360, 0, '', NULL, 0),
(3637, '临高县', '', 3382, 0, '3637', 3360, 0, '', NULL, 0),
(3632, '万宁市', '', 3382, 0, '3632', 3360, 0, '', NULL, 0),
(3629, '琼海市', '', 3382, 0, '3629', 3360, 0, '', NULL, 0),
(3628, '五指山市', '', 3382, 0, '3628', 3360, 0, '', NULL, 0),
(3627, '三亚市', '', 3382, 0, '3627', 3360, 0, '', NULL, 0),
(3626, '海口市', '', 3382, 0, '3626', 3360, 0, '', NULL, 0),
(3625, '崇左市', '', 3381, 0, '3625', 3360, 0, '', NULL, 0),
(3624, '来宾市', '', 3381, 0, '3624', 3360, 0, '', NULL, 0),
(3623, '河池市', '', 3381, 0, '3623', 3360, 0, '', NULL, 0),
(3622, '贺州市', '', 3381, 0, '3622', 3360, 0, '', NULL, 0),
(3621, '百色市', '', 3381, 0, '3621', 3360, 0, '', NULL, 0),
(3620, '玉林市', '', 3381, 0, '3620', 3360, 0, '', NULL, 0),
(3619, '贵港市', '', 3381, 0, '3619', 3360, 0, '', NULL, 0),
(3618, '钦州市', '', 3381, 0, '3618', 3360, 0, '', NULL, 0),
(3617, '防城港市', '', 3381, 0, '3617', 3360, 0, '', NULL, 0),
(3615, '梧州市', '', 3381, 0, '3615', 3360, 0, '', NULL, 0),
(3614, '桂林市', '', 3381, 0, '3614', 3360, 0, '', NULL, 0),
(3613, '柳州市', '', 3381, 0, '3613', 3360, 0, '', NULL, 0),
(3612, '南宁市', '', 3381, 0, '3612', 3360, 0, '', NULL, 0),
(3611, '云浮市', '', 3380, 0, '3611', 3360, 0, '', NULL, 0),
(3610, '揭阳市', '', 3380, 0, '3610', 3360, 0, '', NULL, 0),
(3609, '潮州市', '', 3380, 0, '3609', 3360, 0, '', NULL, 0),
(3608, '中山市', '', 3380, 0, '3608', 3360, 0, '', NULL, 0),
(3607, '东莞市', '', 3380, 0, '3607', 3360, 0, '', NULL, 0),
(3604, '河源市', '', 3380, 0, '3604', 3360, 0, '', NULL, 0),
(3603, '汕尾市', '', 3380, 0, '3603', 3360, 0, '', NULL, 0),
(3601, '惠州市', '', 3380, 0, '3601', 3360, 0, '', NULL, 0),
(3600, '肇庆市', '', 3380, 0, '3600', 3360, 0, '', NULL, 0),
(3596, '佛山市', '', 3380, 0, '3596', 3360, 0, '', NULL, 0),
(3595, '汕头市', '', 3380, 0, '3595', 3360, 0, '', NULL, 0),
(3594, '珠海市', '', 3380, 0, '3594', 3360, 0, '', NULL, 0),
(3593, '深圳市', '', 3380, 0, '3593', 3360, 0, '', NULL, 0),
(3592, '韶关市', '', 3380, 0, '3592', 3360, 0, '', NULL, 0),
(3591, '广州市', '', 3380, 0, '3591', 3360, 0, '', NULL, 0),
(3590, '湘西土家族苗族自治州', '', 3379, 0, '3590', 3360, 0, '', NULL, 0),
(3589, '娄底市', '', 3379, 0, '3589', 3360, 0, '', NULL, 0),
(3587, '永州市', '', 3379, 0, '3587', 3360, 0, '', NULL, 0),
(3586, '郴州市', '', 3379, 0, '3586', 3360, 0, '', NULL, 0),
(3585, '益阳市', '', 3379, 0, '3585', 3360, 0, '', NULL, 0),
(3584, '张家界市', '', 3379, 0, '3584', 3360, 0, '', NULL, 0),
(3583, '常德市', '', 3379, 0, '3583', 3360, 0, '', NULL, 0),
(3582, '岳阳市', '', 3379, 0, '3582', 3360, 0, '', NULL, 0),
(3581, '邵阳市', '', 3379, 0, '3581', 3360, 0, '', NULL, 0),
(3580, '衡阳市', '', 3379, 0, '3580', 3360, 0, '', NULL, 0),
(3579, '湘潭市', '', 3379, 0, '3579', 3360, 0, '', NULL, 0),
(3578, '株洲市', '', 3379, 0, '3578', 3360, 0, '', NULL, 0),
(3577, '长沙市', '', 3379, 0, '3577', 3360, 0, '', NULL, 0),
(3576, '神农架林区', '', 3378, 0, '3576', 3360, 0, '', NULL, 0),
(3575, '天门市', '', 3378, 0, '3575', 3360, 0, '', NULL, 0),
(3574, '潜江市', '', 3378, 0, '3574', 3360, 0, '', NULL, 0),
(3573, '仙桃市', '', 3378, 0, '3573', 3360, 0, '', NULL, 0),
(3572, '恩施土家族苗族自治州', '', 3378, 0, '3572', 3360, 0, '', NULL, 0),
(3571, '随州市', '', 3378, 0, '3571', 3360, 0, '', NULL, 0),
(3570, '咸宁市', '', 3378, 0, '3570', 3360, 0, '', NULL, 0),
(3569, '黄冈市', '', 3378, 0, '3569', 3360, 0, '', NULL, 0),
(3568, '荆州市', '', 3378, 0, '3568', 3360, 0, '', NULL, 0),
(3566, '荆门市', '', 3378, 0, '3566', 3360, 0, '', NULL, 0),
(3565, '鄂州市', '', 3378, 0, '3565', 3360, 0, '', NULL, 0),
(3564, '襄樊市', '', 3378, 0, '3564', 3360, 0, '', NULL, 0),
(3563, '宜昌市', '', 3378, 0, '3563', 3360, 0, '', NULL, 0),
(3562, '十堰市', '', 3378, 0, '3562', 3360, 0, '', NULL, 0),
(3561, '黄石市', '', 3378, 0, '3561', 3360, 0, '', NULL, 0),
(3560, '武汉市', '', 3378, 0, '3560', 3360, 0, '', NULL, 0),
(3559, '驻马店市', '', 3377, 0, '3559', 3360, 0, '', NULL, 0),
(3558, '周口市', '', 3377, 0, '3558', 3360, 0, '', NULL, 0),
(3557, '信阳市', '', 3377, 0, '3557', 3360, 0, '', NULL, 0),
(3556, '商丘市', '', 3377, 0, '3556', 3360, 0, '', NULL, 0),
(3555, '南阳市', '', 3377, 0, '3555', 3360, 0, '', NULL, 0),
(3554, '三门峡市', '', 3377, 0, '3554', 3360, 0, '', NULL, 0),
(3553, '漯河市', '', 3377, 0, '3553', 3360, 0, '', NULL, 0),
(3552, '许昌市', '', 3377, 0, '3552', 3360, 0, '', NULL, 0),
(3551, '濮阳市', '', 3377, 0, '3551', 3360, 0, '', NULL, 0),
(3550, '焦作市', '', 3377, 0, '3550', 3360, 0, '', NULL, 0),
(3549, '新乡市', '', 3377, 0, '3549', 3360, 0, '', NULL, 0),
(3548, '鹤壁市', '', 3377, 0, '3548', 3360, 0, '', NULL, 0),
(3547, '安阳市', '', 3377, 0, '3547', 3360, 0, '', NULL, 0),
(3546, '平顶山市', '', 3377, 0, '3546', 3360, 0, '', NULL, 0),
(3545, '洛阳市', '', 3377, 0, '3545', 3360, 0, '', NULL, 0),
(3543, '郑州市', '', 3377, 0, '3543', 3360, 0, '', NULL, 0),
(3542, '荷泽市', '', 3376, 0, '3542', 3360, 0, '', NULL, 0),
(3541, '滨州市', '', 3376, 0, '3541', 3360, 0, '', NULL, 0),
(3540, '聊城市', '', 3376, 0, '3540', 3360, 0, '', NULL, 0),
(3539, '德州市', '', 3376, 0, '3539', 3360, 0, '', NULL, 0),
(3538, '临沂市', '', 3376, 0, '3538', 3360, 0, '', NULL, 0),
(3537, '莱芜市', '', 3376, 0, '3537', 3360, 0, '', NULL, 0),
(3536, '日照市', '', 3376, 0, '3536', 3360, 0, '', NULL, 0),
(3535, '威海市', '', 3376, 0, '3535', 3360, 0, '', NULL, 0),
(3534, '泰安市', '', 3376, 0, '3534', 3360, 0, '', NULL, 0),
(3533, '济宁市', '', 3376, 0, '3533', 3360, 0, '', NULL, 0),
(3532, '潍坊市', '', 3376, 0, '3532', 3360, 0, '', NULL, 0),
(3531, '烟台市', '', 3376, 0, '3531', 3360, 0, '', NULL, 0),
(3530, '东营市', '', 3376, 0, '3530', 3360, 0, '', NULL, 0),
(3529, '枣庄市', '', 3376, 0, '3529', 3360, 0, '', NULL, 0),
(3528, '淄博市', '', 3376, 0, '3528', 3360, 0, '', NULL, 0),
(3527, '青岛市', '', 3376, 0, '3527', 3360, 0, '', NULL, 0),
(3526, '济南市', '', 3376, 0, '3526', 3360, 0, '', NULL, 0),
(3525, '上饶市', '', 3375, 0, '3525', 3360, 0, '', NULL, 0),
(3524, '抚州市', '', 3375, 0, '3524', 3360, 0, '', NULL, 0),
(3523, '宜春市', '', 3375, 0, '3523', 3360, 0, '', NULL, 0),
(3522, '吉安市', '', 3375, 0, '3522', 3360, 0, '', NULL, 0),
(3521, '赣州市', '', 3375, 0, '3521', 3360, 0, '', NULL, 0),
(3520, '鹰潭市', '', 3375, 0, '3520', 3360, 0, '', NULL, 0),
(3519, '新余市', '', 3375, 0, '3519', 3360, 0, '', NULL, 0),
(3518, '九江市', '', 3375, 0, '3518', 3360, 0, '', NULL, 0),
(3517, '萍乡市', '', 3375, 0, '3517', 3360, 0, '', NULL, 0),
(3516, '景德镇市', '', 3375, 0, '3516', 3360, 0, '', NULL, 0),
(3515, '南昌市', '', 3375, 0, '3515', 3360, 0, '', NULL, 0),
(3513, '龙岩市', '', 3374, 0, '3513', 3360, 0, '', NULL, 0),
(3512, '南平市', '', 3374, 0, '3512', 3360, 0, '', NULL, 0),
(3511, '漳州市', '', 3374, 0, '3511', 3360, 0, '', NULL, 0),
(3510, '泉州市', '', 3374, 0, '3510', 3360, 0, '', NULL, 0),
(3509, '三明市', '', 3374, 0, '3509', 3360, 0, '', NULL, 0),
(3508, '莆田市', '', 3374, 0, '3508', 3360, 0, '', NULL, 0),
(3507, '厦门市', '', 3374, 0, '3507', 3360, 0, '', NULL, 0),
(3505, '宣城市', '', 3373, 0, '3505', 3360, 0, '', NULL, 0),
(3504, '池州市', '', 3373, 0, '3504', 3360, 0, '', NULL, 0),
(3503, '亳州市', '', 3373, 0, '3503', 3360, 0, '', NULL, 0),
(3502, '六安市', '', 3373, 0, '3502', 3360, 0, '', NULL, 0),
(3500, '宿州市', '', 3373, 0, '3500', 3360, 0, '', NULL, 0),
(3499, '阜阳市', '', 3373, 0, '3499', 3360, 0, '', NULL, 0),
(3498, '滁州市', '', 3373, 0, '3498', 3360, 0, '', NULL, 0),
(3497, '黄山市', '', 3373, 0, '3497', 3360, 0, '', NULL, 0),
(3496, '安庆市', '', 3373, 0, '3496', 3360, 0, '', NULL, 0),
(3495, '铜陵市', '', 3373, 0, '3495', 3360, 0, '', NULL, 0),
(3494, '淮北市', '', 3373, 0, '3494', 3360, 0, '', NULL, 0),
(3493, '马鞍山市', '', 3373, 0, '3493', 3360, 0, '', NULL, 0),
(3492, '淮南市', '', 3373, 0, '3492', 3360, 0, '', NULL, 0),
(3491, '蚌埠市', '', 3373, 0, '3491', 3360, 0, '', NULL, 0),
(3490, '芜湖市', '', 3373, 0, '3490', 3360, 0, '', NULL, 0),
(3489, '合肥市', '', 3373, 0, '3489', 3360, 0, '', NULL, 0),
(3488, '丽水市', '', 3372, 0, '3488', 3360, 0, '', NULL, 0),
(3487, '台州市', '', 3372, 0, '3487', 3360, 0, '', NULL, 0),
(3486, '舟山市', '', 3372, 0, '3486', 3360, 0, '', NULL, 0),
(3485, '衢州市', '', 3372, 0, '3485', 3360, 0, '', NULL, 0),
(3483, '绍兴市', '', 3372, 0, '3483', 3360, 0, '', NULL, 0),
(3482, '湖州市', '', 3372, 0, '3482', 3360, 0, '', NULL, 0),
(3481, '嘉兴市', '', 3372, 0, '3481', 3360, 0, '', NULL, 0),
(3480, '温州市', '', 3372, 0, '3480', 3360, 0, '', NULL, 0),
(3479, '宁波市', '', 3372, 0, '3479', 3360, 0, '', NULL, 0),
(3478, '杭州市', '', 3372, 0, '3478', 3360, 0, '', NULL, 0),
(3477, '宿迁市', '', 3371, 0, '3477', 3360, 0, '', NULL, 0),
(3475, '镇江市', '', 3371, 0, '3475', 3360, 0, '', NULL, 0),
(3474, '扬州市', '', 3371, 0, '3474', 3360, 0, '', NULL, 0),
(3473, '盐城市', '', 3371, 0, '3473', 3360, 0, '', NULL, 0),
(3472, '淮安市', '', 3371, 0, '3472', 3360, 0, '', NULL, 0),
(3471, '连云港市', '', 3371, 0, '3471', 3360, 0, '', NULL, 0),
(3470, '南通市', '', 3371, 0, '3470', 3360, 0, '', NULL, 0),
(3469, '苏州市', '', 3371, 0, '3469', 3360, 0, '', NULL, 0),
(3468, '常州市', '', 3371, 0, '3468', 3360, 0, '', NULL, 0),
(3467, '徐州市', '', 3371, 0, '3467', 3360, 0, '', NULL, 0),
(3466, '无锡市', '', 3371, 0, '3466', 3360, 0, '', NULL, 0),
(3464, '大兴安岭地区', '', 3370, 0, '3464', 3360, 0, '', NULL, 0),
(3463, '绥化市', '', 3370, 0, '3463', 3360, 0, '', NULL, 0),
(3462, '黑河市', '', 3370, 0, '3462', 3360, 0, '', NULL, 0),
(3461, '牡丹江市', '', 3370, 0, '3461', 3360, 0, '', NULL, 0),
(3460, '七台河市', '', 3370, 0, '3460', 3360, 0, '', NULL, 0),
(3459, '佳木斯市', '', 3370, 0, '3459', 3360, 0, '', NULL, 0),
(3458, '伊春市', '', 3370, 0, '3458', 3360, 0, '', NULL, 0),
(3457, '大庆市', '', 3370, 0, '3457', 3360, 0, '', NULL, 0),
(3456, '双鸭山市', '', 3370, 0, '3456', 3360, 0, '', NULL, 0),
(3455, '鹤岗市', '', 3370, 0, '3455', 3360, 0, '', NULL, 0),
(3454, '鸡西市', '', 3370, 0, '3454', 3360, 0, '', NULL, 0),
(3453, '齐齐哈尔市', '', 3370, 0, '3453', 3360, 0, '', NULL, 0),
(3452, '哈尔滨市', '', 3370, 0, '3452', 3360, 0, '', NULL, 0),
(3451, '延边', '', 3369, 0, '3451', 3360, 0, '', NULL, 0),
(3450, '白城市', '', 3369, 0, '3450', 3360, 0, '', NULL, 0),
(3449, '松原市', '', 3369, 0, '3449', 3360, 0, '', NULL, 0),
(3448, '白山市', '', 3369, 0, '3448', 3360, 0, '', NULL, 0),
(3447, '通化市', '', 3369, 0, '3447', 3360, 0, '', NULL, 0),
(3446, '辽源市', '', 3369, 0, '3446', 3360, 0, '', NULL, 0),
(3445, '四平市', '', 3369, 0, '3445', 3360, 0, '', NULL, 0),
(3444, '吉林市', '', 3369, 0, '3444', 3360, 0, '', NULL, 0),
(3442, '葫芦岛市', '', 3368, 0, '3442', 3360, 0, '', NULL, 0),
(3441, '朝阳市', '', 3368, 0, '3441', 3360, 0, '', NULL, 0),
(3440, '铁岭市', '', 3368, 0, '3440', 3360, 0, '', NULL, 0),
(3439, '盘锦市', '', 3368, 0, '3439', 3360, 0, '', NULL, 0),
(3438, '辽阳市', '', 3368, 0, '3438', 3360, 0, '', NULL, 0),
(3437, '阜新市', '', 3368, 0, '3437', 3360, 0, '', NULL, 0),
(3436, '营口市', '', 3368, 0, '3436', 3360, 0, '', NULL, 0),
(3435, '锦州市', '', 3368, 0, '3435', 3360, 0, '', NULL, 0),
(3434, '丹东市', '', 3368, 0, '3434', 3360, 0, '', NULL, 0),
(3433, '本溪市', '', 3368, 0, '3433', 3360, 0, '', NULL, 0),
(3431, '鞍山市', '', 3368, 0, '3431', 3360, 0, '', NULL, 0),
(3430, '大连市', '', 3368, 0, '3430', 3360, 0, '', NULL, 0),
(3429, '沈阳市', '', 3368, 0, '3429', 3360, 0, '', NULL, 0),
(3428, '阿拉善盟', '', 3367, 0, '3428', 3360, 0, '', NULL, 0),
(3427, '锡林郭勒盟', '', 3367, 0, '3427', 3360, 0, '', NULL, 0),
(3426, '兴安盟', '', 3367, 0, '3426', 3360, 0, '', NULL, 0),
(3425, '乌兰察布市', '', 3367, 0, '3425', 3360, 0, '', NULL, 0),
(3424, '巴彦淖尔市', '', 3367, 0, '3424', 3360, 0, '', NULL, 0),
(3423, '呼伦贝尔市', '', 3367, 0, '3423', 3360, 0, '', NULL, 0),
(3422, '鄂尔多斯市', '', 3367, 0, '3422', 3360, 0, '', NULL, 0),
(3421, '通辽市', '', 3367, 0, '3421', 3360, 0, '', NULL, 0),
(3420, '赤峰市', '', 3367, 0, '3420', 3360, 0, '', NULL, 0),
(3419, '乌海市', '', 3367, 0, '3419', 3360, 0, '', NULL, 0),
(3415, '临汾市', '', 3366, 0, '3415', 3360, 0, '', NULL, 0),
(3414, '忻州市', '', 3366, 0, '3414', 3360, 0, '', NULL, 0),
(3413, '运城市', '', 3366, 0, '3413', 3360, 0, '', NULL, 0),
(3411, '朔州市', '', 3366, 0, '3411', 3360, 0, '', NULL, 0),
(3410, '晋城市', '', 3366, 0, '3410', 3360, 0, '', NULL, 0),
(3409, '长治市', '', 3366, 0, '3409', 3360, 0, '', NULL, 0),
(3408, '阳泉市', '', 3366, 0, '3408', 3360, 0, '', NULL, 0),
(3407, '大同市', '', 3366, 0, '3407', 3360, 0, '', NULL, 0),
(3406, '太原市', '', 3366, 0, '3406', 3360, 0, '', NULL, 0),
(3405, '衡水市', '', 3365, 0, '3405', 3360, 0, '', NULL, 0),
(3404, '廊坊市', '', 3365, 0, '3404', 3360, 0, '', NULL, 0),
(3403, '沧州市', '', 3365, 0, '3403', 3360, 0, '', NULL, 0),
(3402, '承德市', '', 3365, 0, '3402', 3360, 0, '', NULL, 0),
(3401, '张家口市', '', 3365, 0, '3401', 3360, 0, '', NULL, 0),
(3400, '保定市', '', 3365, 0, '3400', 3360, 0, '', NULL, 0),
(3399, '邢台市', '', 3365, 0, '3399', 3360, 0, '', NULL, 0),
(3398, '邯郸市', '', 3365, 0, '3398', 3360, 0, '', NULL, 0),
(3397, '秦皇岛市', '', 3365, 0, '3397', 3360, 0, '', NULL, 0),
(3396, '唐山市', '', 3365, 0, '3396', 3360, 0, '', NULL, 0),
(3395, '石家庄市', '', 3365, 0, '3395', 3360, 0, '', NULL, 0),
(3394, '澳门', '0', 3781, 0, '3394', 3360, 30, '', 'array (\n  ''level'' => ''0'',\n)', 0),
(3393, '香港', '0', 3781, 0, '3393', 3360, 29, '', 'array (\n  ''level'' => ''0'',\n)', 0),
(3392, '台湾省', '', 0, 1, '3392,3755,3756,3757,3758,3759,3760,3761,3762,3763,3764,3765,3766,3767,3768,3769,3770,3771,3772,3773,3774,3775,3776,3777,3778,3779', 3360, 28, '', NULL, 0),
(3391, '新疆', '', 0, 1, '3391,3737,3738,3739,3740,3741,3742,3743,3744,3745,3746,3747,3748,3749,3750,3751,3752,3753,3754', 3360, 27, '', NULL, 0),
(3390, '宁夏', '', 0, 1, '3390,3732,3733,3734,3735,3736', 3360, 26, '', NULL, 0),
(3387, '陕西省', '', 0, 1, '3387,3700,3701,3702,3703,3704,3705,3706,3707,3708,3709', 3360, 23, '', NULL, 0),
(3386, '西藏', '', 0, 1, '3386,3693,3694,3695,3696,3697,3698,3699', 3360, 22, '', NULL, 0),
(3385, '云南省', '', 0, 1, '3385,3677,3678,3679,3680,3681,3682,3683,3684,3685,3686,3687,3688,3689,3690,3691,3692', 3360, 21, '', NULL, 0),
(3383, '四川省', '', 0, 1, '3383,3647,3648,3649,3650,3651,3652,3653,3654,3655,3656,3657,3658,3659,3660,3661,3662,3663,3664,3665,3666,3667', 3360, 19, '', NULL, 0),
(3381, '广西省', '0', 0, 1, '3381,3612,3613,3614,3615,3616,3617,3618,3619,3620,3621,3622,3623,3624,3625', 3360, 17, '', 'array (\n  ''level'' => ''0'',\n)', 0),
(3379, '湖南省', '', 0, 1, '3379,3577,3578,3579,3580,3581,3582,3583,3584,3585,3586,3587,3588,3589,3590', 3360, 15, '', NULL, 0),
(3377, '河南省', '', 0, 1, '3377,3543,3544,3545,3546,3547,3548,3549,3550,3551,3552,3553,3554,3555,3556,3557,3558,3559', 3360, 13, '', NULL, 0),
(3375, '江西省', '', 0, 1, '3375,3515,3516,3517,3518,3519,3520,3521,3522,3523,3524,3525', 3360, 11, '', NULL, 0),
(3372, '浙江省', '', 0, 1, '3372,3478,3479,3480,3481,3482,3483,3484,3485,3486,3487,3488', 3360, 8, '', NULL, 0),
(3368, '辽宁省', '', 0, 1, '3368,3429,3430,3431,3432,3433,3434,3435,3436,3437,3438,3439,3440,3441,3442', 3360, 4, '', NULL, 0),
(3367, '内蒙古', '', 0, 1, '3367,3417,3418,3419,3420,3421,3422,3423,3424,3425,3426,3427,3428', 3360, 3, '', NULL, 0),
(3366, '山西省', '', 0, 1, '3366,3406,3407,3408,3409,3410,3411,3412,3413,3414,3415,3416', 3360, 2, '', NULL, 0),
(3365, '河北省', '', 0, 1, '3365,3395,3396,3397,3398,3399,3400,3401,3402,3403,3404,3405', 3360, 1, '', NULL, 0),
(3364, '重庆市', '0', 3780, 0, '3364', 3360, 0, '', 'array (\n  ''level'' => ''0'',\n)', 0),
(3363, '天津市', '0', 3780, 0, '3363', 3360, 0, '', 'array (\n  ''level'' => ''0'',\n)', 0),
(3362, '上海市', '0', 3780, 0, '3362', 3360, 0, '', 'array (\n  ''level'' => ''0'',\n)', 0),
(3361, '北京市', '0', 3780, 0, '3361', 3360, 0, '', 'array (\n  ''level'' => ''0'',\n)', 0),
(3360, '城市列表', '1', 0, 0, '', 0, 0, '', 'array (\n  ''level'' => ''0'',\n)', 0),
(3781, '特别行政区', '', 0, 1, '3781,3393,3394', 3360, 32, '', NULL, 0),
(3731, '海西州', '', 3389, 0, '3731', 3360, 0, '', NULL, 0),
(3732, '银川市', '', 3390, 0, '3732', 3360, 0, '', NULL, 0),
(3733, '石嘴山市', '', 3390, 0, '3733', 3360, 0, '', NULL, 0),
(3734, '吴忠市', '', 3390, 0, '3734', 3360, 0, '', NULL, 0),
(3735, '固原市', '', 3390, 0, '3735', 3360, 0, '', NULL, 0),
(3736, '中卫市', '', 3390, 0, '3736', 3360, 0, '', NULL, 0),
(3737, '乌鲁木齐市', '', 3391, 0, '3737', 3360, 0, '', NULL, 0),
(3738, '克拉玛依市', '', 3391, 0, '3738', 3360, 0, '', NULL, 0),
(3739, '吐鲁番地区', '', 3391, 0, '3739', 3360, 0, '', NULL, 0),
(3740, '哈密地区', '', 3391, 0, '3740', 3360, 0, '', NULL, 0),
(3741, '昌吉州', '', 3391, 0, '3741', 3360, 0, '', NULL, 0),
(3742, '博尔州', '', 3391, 0, '3742', 3360, 0, '', NULL, 0),
(3743, '巴音郭楞州', '', 3391, 0, '3743', 3360, 0, '', NULL, 0),
(3744, '阿克苏地区', '', 3391, 0, '3744', 3360, 0, '', NULL, 0),
(3745, '克孜勒苏柯尔克孜自治州', '', 3391, 0, '3745', 3360, 0, '', NULL, 0),
(3746, '喀什地区', '', 3391, 0, '3746', 3360, 0, '', NULL, 0),
(3747, '和田地区', '', 3391, 0, '3747', 3360, 0, '', NULL, 0),
(3748, '伊犁州', '', 3391, 0, '3748', 3360, 0, '', NULL, 0),
(3749, '塔城地区', '', 3391, 0, '3749', 3360, 0, '', NULL, 0),
(3750, '阿勒泰地区', '', 3391, 0, '3750', 3360, 0, '', NULL, 0),
(3751, '石河子市', '', 3391, 0, '3751', 3360, 0, '', NULL, 0),
(3752, '阿拉尔市', '', 3391, 0, '3752', 3360, 0, '', NULL, 0),
(3753, '图木舒克市', '', 3391, 0, '3753', 3360, 0, '', NULL, 0),
(3754, '五家渠市', '', 3391, 0, '3754', 3360, 0, '', NULL, 0),
(3755, '台北市', '', 3392, 0, '3755', 3360, 0, '', NULL, 0),
(3756, '高雄市', '', 3392, 0, '3756', 3360, 0, '', NULL, 0),
(3757, '基隆市', '', 3392, 0, '3757', 3360, 0, '', NULL, 0),
(3758, '新竹市', '', 3392, 0, '3758', 3360, 0, '', NULL, 0),
(3759, '台中市', '', 3392, 0, '3759', 3360, 0, '', NULL, 0),
(3760, '嘉义市', '', 3392, 0, '3760', 3360, 0, '', NULL, 0),
(3761, '台南市', '', 3392, 0, '3761', 3360, 0, '', NULL, 0),
(3762, '台北县', '', 3392, 0, '3762', 3360, 0, '', NULL, 0),
(3763, '桃园县', '', 3392, 0, '3763', 3360, 0, '', NULL, 0),
(3764, '新竹县', '', 3392, 0, '3764', 3360, 0, '', NULL, 0),
(3765, '苗栗县', '', 3392, 0, '3765', 3360, 0, '', NULL, 0),
(3766, '台中县', '', 3392, 0, '3766', 3360, 0, '', NULL, 0),
(3767, '彰化县', '', 3392, 0, '3767', 3360, 0, '', NULL, 0),
(3768, '南投县', '', 3392, 0, '3768', 3360, 0, '', NULL, 0),
(3769, '云林县', '', 3392, 0, '3769', 3360, 0, '', NULL, 0),
(3770, '嘉义县', '', 3392, 0, '3770', 3360, 0, '', NULL, 0),
(3771, '台南县', '', 3392, 0, '3771', 3360, 0, '', NULL, 0),
(3772, '高雄县', '', 3392, 0, '3772', 3360, 0, '', NULL, 0),
(3773, '屏东县', '', 3392, 0, '3773', 3360, 0, '', NULL, 0),
(3774, '宜兰县', '', 3392, 0, '3774', 3360, 0, '', NULL, 0),
(3775, '花莲县', '', 3392, 0, '3775', 3360, 0, '', NULL, 0),
(3776, '台东县', '', 3392, 0, '3776', 3360, 0, '', NULL, 0),
(3777, '澎湖县', '', 3392, 0, '3777', 3360, 0, '', NULL, 0),
(3778, '金门县', '', 3392, 0, '3778', 3360, 0, '', NULL, 0),
(3779, '连江县', '', 3392, 0, '3779', 3360, 0, '', NULL, 0);

-- --------------------------------------------------------

--
-- 表的结构 `xy_log`
--

CREATE TABLE IF NOT EXISTS `xy_log` (
`id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL COMMENT '用户',
  `module` char(40) NOT NULL COMMENT '模块',
  `action` varchar(20) NOT NULL COMMENT '操作',
  `querystring` varchar(255) NOT NULL COMMENT 'URL',
  `userid` smallint(5) NOT NULL COMMENT '用户ID',
  `ip` varchar(50) NOT NULL COMMENT 'IP',
  `date` datetime NOT NULL COMMENT '时间',
  `status` tinyint(1) NOT NULL COMMENT '0：登陆失败; 1：操作成功；2：无权限'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `xy_model`
--

CREATE TABLE IF NOT EXISTS `xy_model` (
`id` tinyint(3) unsigned NOT NULL,
  `siteid` smallint(5) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `tablename` varchar(50) NOT NULL DEFAULT '',
  `controller` varchar(255) NOT NULL,
  `description` varchar(200) NOT NULL DEFAULT '',
  `typeid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `issearch` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `listfields` varchar(255) NOT NULL DEFAULT '',
  `setup` mediumtext NOT NULL,
  `listorder` smallint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `postgroup` varchar(100) NOT NULL DEFAULT '',
  `ispost` tinyint(1) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- 转存表中的数据 `xy_model`
--

INSERT INTO `xy_model` (`id`, `siteid`, `name`, `tablename`, `controller`, `description`, `typeid`, `issystem`, `issearch`, `listfields`, `setup`, `listorder`, `status`, `postgroup`, `ispost`) VALUES
(16, 1, '内容模型', 'news', 'Content', '新闻模型', 0, 0, 0, '', '', 0, 0, '', 0),
(17, 1, '楼盘模型', 'house', 'House', '', 1, 0, 0, '', '', 0, 0, '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `xy_model_field`
--

CREATE TABLE IF NOT EXISTS `xy_model_field` (
`fieldid` mediumint(8) unsigned NOT NULL,
  `modelid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `siteid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `field` varchar(20) NOT NULL,
  `name` varchar(30) NOT NULL,
  `tips` text NOT NULL,
  `css` varchar(30) NOT NULL,
  `minlength` int(10) unsigned NOT NULL DEFAULT '0',
  `maxlength` int(10) unsigned NOT NULL DEFAULT '0',
  `pattern` varchar(255) NOT NULL,
  `errortips` varchar(255) NOT NULL,
  `formtype` varchar(20) NOT NULL,
  `setting` mediumtext NOT NULL,
  `formattribute` varchar(255) NOT NULL,
  `unsetgroupids` varchar(255) NOT NULL,
  `unsetroleids` varchar(255) NOT NULL,
  `iscore` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isunique` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isbase` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `issearch` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isadd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isfulltext` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isposition` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `listorder` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isomnipotent` tinyint(1) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=105 ;

--
-- 转存表中的数据 `xy_model_field`
--

INSERT INTO `xy_model_field` (`fieldid`, `modelid`, `siteid`, `field`, `name`, `tips`, `css`, `minlength`, `maxlength`, `pattern`, `errortips`, `formtype`, `setting`, `formattribute`, `unsetgroupids`, `unsetroleids`, `iscore`, `issystem`, `isunique`, `isbase`, `issearch`, `isadd`, `isfulltext`, `isposition`, `listorder`, `disabled`, `isomnipotent`) VALUES
(54, 16, 1, 'catid', '栏目', '', '', 1, 6, '/^[0-9]{1,6}$/', '请选择栏目', 'catid', 'array (\n  ''defaultvalue'' => '''',\n)', '', '-99', '-99', 0, 1, 0, 1, 1, 1, 0, 0, 1, 0, 0),
(55, 16, 1, 'typeid', '类别', '', '', 0, 0, '', '', 'typeid', 'array (\n  ''minnumber'' => '''',\n  ''defaultvalue'' => '''',\n)', '', '', '', 0, 1, 0, 1, 1, 1, 0, 0, 2, 1, 0),
(56, 16, 1, 'title', '标题', '', 'inputtitle', 1, 80, '', '请输入标题', 'title', '', '', '', '', 0, 1, 0, 1, 1, 1, 1, 1, 4, 0, 0),
(57, 16, 1, 'keywords', '关键词', '多关键词之间用空格或者“,”隔开', '', 0, 40, '', '', 'keyword', 'array (\r\n  ''size'' => ''100'',\r\n  ''defaultvalue'' => '''',\r\n)', '', '-99', '-99', 0, 1, 0, 1, 1, 1, 1, 0, 7, 0, 0),
(58, 16, 1, 'description', '摘要', '', '', 0, 255, '', '', 'textarea', 'array (\r\n  ''width'' => ''98'',\r\n  ''height'' => ''46'',\r\n  ''defaultvalue'' => '''',\r\n  ''enablehtml'' => ''0'',\r\n)', '', '', '', 0, 1, 0, 1, 0, 1, 1, 1, 10, 0, 0),
(59, 16, 1, 'updatetime', '更新时间', '', '', 0, 0, '', '', 'datetime', 'array (\r\n  ''dateformat'' => ''int'',\r\n  ''format'' => ''Y-m-d H:i:s'',\r\n  ''defaulttype'' => ''1'',\r\n  ''defaultvalue'' => '''',\r\n)', '', '', '', 1, 1, 0, 1, 0, 0, 0, 0, 12, 0, 0),
(60, 16, 1, 'content', '内容', '', '', 1, 999999, '', '内容不能为空', 'editor', 'array (\n  ''toolbar'' => ''full'',\n  ''defaultvalue'' => '''',\n  ''enablekeylink'' => ''1'',\n  ''replacenum'' => ''2'',\n  ''link_mode'' => ''0'',\n  ''enablesaveimage'' => ''1'',\n  ''height'' => '''',\n  ''disabled_page'' => ''0'',\n)', '', '', '', 0, 0, 0, 1, 0, 1, 1, 0, 13, 0, 0),
(61, 16, 1, 'thumb', '缩略图', '', '', 0, 100, '', '', 'image', 'array (\n  ''size'' => ''50'',\n  ''defaultvalue'' => '''',\n  ''show_type'' => ''1'',\n  ''upload_maxsize'' => ''1024'',\n  ''upload_allowext'' => ''jpg|jpeg|gif|png|bmp'',\n  ''watermark'' => ''0'',\n  ''isselectimage'' => ''1'',\n  ''images_width'' => '''',\n  ''images_height'' => '''',\n)', '', '', '', 0, 1, 0, 0, 0, 1, 0, 1, 14, 0, 0),
(62, 16, 1, 'relation', '相关文章', '', '', 0, 0, '', '', 'omnipotent', 'array (\n  ''formtext'' => ''<input type=\\''hidden\\'' name=\\''info[relation][ids]\\'' id=\\''relation\\'' value=\\''{FIELD_VALUE_IDS}\\'' style=\\''50\\'' >\r\n<input type=\\''hidden\\'' name=\\''info[relation][cats]\\'' id=\\''relation_cats\\'' \r\nvalue=\\''{FIELD_VALUE_CATS}\\'' style=\\''50\\'' >\r\n<input type=\\''hidden\\'' name=\\''info[relation][title]\\'' id=\\''relation_title\\'' value=\\''{FIELD_VALUE_TITLE}\\''>\r\n<ul class="list-dot" id="relation_text"></ul>\r\n<div>\r\n<input type=\\''button\\'' value="添加相关" onclick="omnipotent(\\''selectid\\'',\\''{URL_ADD_RELATION}?modelid={MODELID}\\'',\\''添加相关文章\\'',1)" class="button" style="width:66px;">\r\n<span class="edit_content">\r\n<input type=\\''button\\'' value="显示已有" onclick="show_relation(\\''{URL_SHOW_RELATION}\\'',{MODELID},{ID})" class="button" style="width:66px;">\r\n</span>\r\n</div>'',\n  ''fieldtype'' => ''text'',\n  ''minnumber'' => ''1'',\n)', '', '', '', 0, 0, 0, 0, 0, 0, 1, 0, 15, 0, 0),
(64, 16, 1, 'inputtime', '发布时间', '', '', 0, 0, '', '', 'datetime', 'array (\n  ''fieldtype'' => ''int'',\n  ''format'' => ''Y-m-d H:i:s'',\n  ''defaulttype'' => ''0'',\n)', '', '', '', 0, 1, 0, 0, 0, 0, 0, 1, 17, 0, 0),
(65, 16, 1, 'posids', '推荐位', '', '', 0, 0, '', '', 'posid', 'array (\n  ''cols'' => ''4'',\n  ''width'' => ''125'',\n)', '', '', '', 0, 1, 0, 1, 0, 0, 0, 0, 18, 0, 0),
(67, 16, 1, 'url', 'URL', '', '', 0, 100, '', '', 'text', 'array (\n  ''size'' => '''',\n  ''defaultvalue'' => '''',\n  ''ispassword'' => ''0'',\n)', '', '', '', 1, 1, 0, 0, 0, 0, 0, 0, 50, 0, 0),
(68, 16, 1, 'listorder', '排序', '', '', 0, 6, '', '', 'number', '', '', '', '', 1, 1, 0, 1, 0, 0, 0, 0, 51, 0, 0),
(69, 16, 1, 'template', '内容页模板', '', '', 0, 30, '', '', 'template', 'array (\n  ''size'' => '''',\n  ''defaultvalue'' => '''',\n)', '', '-99', '-99', 0, 0, 0, 0, 0, 0, 0, 0, 53, 0, 0),
(70, 16, 1, 'allow_comment', '允许评论', '', '', 0, 0, '', '', 'box', 'array (\n  ''options'' => ''允许评论|1\r\n不允许评论|0'',\n  ''boxtype'' => ''radio'',\n  ''fieldtype'' => ''tinyint'',\n  ''minnumber'' => ''1'',\n  ''width'' => ''88'',\n  ''size'' => ''1'',\n  ''defaultvalue'' => ''1'',\n  ''outputtype'' => ''0'',\n)', '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 54, 0, 0),
(71, 16, 1, 'status', '状态', '', '', 0, 2, '', '', 'box', '', '', '', '', 1, 1, 0, 1, 0, 0, 0, 0, 55, 0, 0),
(72, 16, 1, 'readpoint', '阅读收费', '', '', 0, 5, '', '', 'readpoint', 'array (\n  ''minnumber'' => ''1'',\n  ''maxnumber'' => ''99999'',\n  ''decimaldigits'' => ''0'',\n  ''defaultvalue'' => '''',\n)', '', '-99', '-99', 0, 0, 0, 0, 0, 0, 0, 0, 55, 1, 0),
(73, 16, 1, 'username', '用户名', '', '', 0, 20, '', '', 'text', '', '', '', '', 1, 1, 0, 1, 0, 0, 0, 0, 98, 0, 0),
(74, 16, 1, 'islink', '转向链接', '', '', 0, 0, '', '', 'islink', 'array (\n  ''size'' => '''',\n)', '', '', '', 0, 1, 0, 0, 0, 1, 0, 0, 20, 0, 0),
(76, 16, 1, 'copyfrom', '来源', '', '', 0, 0, '', '', 'copyfrom', 'array (\n  ''defaultvalue'' => '''',\n)', '', '', '', 0, 0, 0, 1, 0, 1, 0, 0, 5, 0, 0),
(103, 17, 1, 'developer', '开发商', '', '', 0, 0, '', '', 'text', 'array (\n  ''size'' => ''50'',\n  ''defaultvalue'' => '''',\n  ''ispassword'' => ''0'',\n)', '', '', '', 0, 1, 0, 1, 0, 1, 1, 0, 0, 0, 0),
(82, 17, 1, 'typeid', '类别', '', '', 0, 0, '', '', 'typeid', 'array (\n  ''minnumber'' => '''',\n  ''defaultvalue'' => '''',\n)', '', '', '', 0, 1, 0, 1, 1, 1, 0, 0, 2, 0, 0),
(83, 17, 1, 'title', '标题', '', 'inputtitle', 1, 80, '', '请输入标题', 'title', '', '', '', '', 0, 1, 0, 1, 1, 1, 1, 1, 4, 0, 0),
(84, 17, 1, 'keywords', '关键词', '多关键词之间用空格或者“,”隔开', '', 0, 40, '', '', 'keyword', 'array (\r\n  ''size'' => ''100'',\r\n  ''defaultvalue'' => '''',\r\n)', '', '-99', '-99', 0, 1, 0, 1, 1, 1, 1, 0, 7, 0, 0),
(85, 17, 1, 'description', '摘要', '', '', 0, 255, '', '', 'textarea', 'array (\r\n  ''width'' => ''98'',\r\n  ''height'' => ''46'',\r\n  ''defaultvalue'' => '''',\r\n  ''enablehtml'' => ''0'',\r\n)', '', '', '', 0, 1, 0, 1, 0, 1, 1, 1, 10, 0, 0),
(86, 17, 1, 'updatetime', '更新时间', '', '', 0, 0, '', '', 'datetime', 'array (\r\n  ''dateformat'' => ''int'',\r\n  ''format'' => ''Y-m-d H:i:s'',\r\n  ''defaulttype'' => ''1'',\r\n  ''defaultvalue'' => '''',\r\n)', '', '', '', 1, 1, 0, 1, 0, 0, 0, 0, 12, 0, 0),
(87, 17, 1, 'content', '内容', '<div class="content_attr"><label><input name="add_introduce" type="checkbox"  value="1" checked>是否截取内容</label><input type="text" name="introcude_length" value="200" size="3">字符至内容摘要\r\n<label><input type=''checkbox'' name=''auto_thumb'' value="1" checked>是否获取内容第</label><input type="text" name="auto_thumb_no" value="1" size="2" class="">张图片作为标题图片\r\n</div>', '', 1, 999999, '', '内容不能为空', 'editor', 'array (\n  ''toolbar'' => ''full'',\n  ''defaultvalue'' => '''',\n  ''enablekeylink'' => ''1'',\n  ''replacenum'' => ''2'',\n  ''link_mode'' => ''0'',\n  ''enablesaveimage'' => ''1'',\n)', '', '', '', 0, 0, 0, 1, 0, 1, 1, 0, 13, 0, 0),
(88, 17, 1, 'thumb', '缩略图', '', '', 0, 100, '', '', 'image', 'array (\n  ''size'' => ''50'',\n  ''defaultvalue'' => '''',\n  ''show_type'' => ''1'',\n  ''upload_maxsize'' => ''1024'',\n  ''upload_allowext'' => ''jpg|jpeg|gif|png|bmp'',\n  ''watermark'' => ''0'',\n  ''isselectimage'' => ''1'',\n  ''images_width'' => '''',\n  ''images_height'' => '''',\n)', '', '', '', 0, 1, 0, 0, 0, 1, 0, 1, 14, 0, 0),
(89, 17, 1, 'relation', '相关文章', '', '', 0, 0, '', '', 'omnipotent', 'array (\n  ''formtext'' => ''<input type=\\''hidden\\'' name=\\''info[relation][ids]\\'' id=\\''relation\\'' value=\\''{FIELD_VALUE_IDS}\\'' style=\\''50\\'' >\r\n<input type=\\''hidden\\'' name=\\''info[relation][cats]\\'' id=\\''relation\\'' value=\\''{FIELD_VALUE_CATS}\\'' style=\\''50\\'' >\r\n<ul class="list-dot" id="relation_text"></ul>\r\n<div>\r\n<input type=\\''button\\'' value="添加相关" onclick="omnipotent(\\''selectid\\'',\\''{URL_ADD_RELATION}?modelid={MODELID}\\'',\\''添加相关文章\\'',1)" class="button" style="width:66px;">\r\n<span class="edit_content">\r\n<input type=\\''button\\'' value="显示已有" onclick="show_relation(\\''{URL_SHOW_RELATION}\\'',{MODELID},{ID})" class="button" style="width:66px;">\r\n</span>\r\n</div>'',\n  ''fieldtype'' => ''varchar'',\n  ''minnumber'' => ''1'',\n)', '', '', '', 0, 0, 0, 0, 0, 0, 1, 0, 15, 0, 0),
(90, 17, 1, 'inputtime', '发布时间', '', '', 0, 0, '', '', 'datetime', 'array (\n  ''fieldtype'' => ''int'',\n  ''format'' => ''Y-m-d H:i:s'',\n  ''defaulttype'' => ''0'',\n)', '', '', '', 0, 1, 0, 0, 0, 0, 0, 1, 17, 0, 0),
(91, 17, 1, 'posids', '推荐位', '', '', 0, 0, '', '', 'posid', 'array (\n  ''cols'' => ''4'',\n  ''width'' => ''125'',\n)', '', '', '', 0, 1, 0, 1, 0, 0, 0, 0, 18, 0, 0),
(92, 17, 1, 'url', 'URL', '', '', 0, 100, '', '', 'text', '', '', '', '', 1, 1, 0, 1, 0, 0, 0, 0, 50, 0, 0),
(93, 17, 1, 'listorder', '排序', '', '', 0, 6, '', '', 'number', '', '', '', '', 1, 1, 0, 1, 0, 0, 0, 0, 51, 0, 0),
(94, 17, 1, 'template', '内容页模板', '', '', 0, 30, '', '', 'template', 'array (\n  ''size'' => '''',\n  ''defaultvalue'' => '''',\n)', '', '-99', '-99', 0, 0, 0, 0, 0, 0, 0, 0, 53, 0, 0),
(95, 17, 1, 'allow_comment', '允许评论', '', '', 0, 0, '', '', 'box', 'array (\n  ''options'' => ''允许评论|1\r\n不允许评论|0'',\n  ''boxtype'' => ''radio'',\n  ''fieldtype'' => ''tinyint'',\n  ''minnumber'' => ''1'',\n  ''width'' => ''88'',\n  ''size'' => ''1'',\n  ''defaultvalue'' => ''1'',\n  ''outputtype'' => ''0'',\n)', '', '', '', 0, 0, 0, 0, 0, 0, 0, 0, 54, 0, 0),
(96, 17, 1, 'status', '状态', '', '', 0, 2, '', '', 'box', '', '', '', '', 1, 1, 0, 1, 0, 0, 0, 0, 55, 0, 0),
(97, 17, 1, 'readpoint', '阅读收费', '', '', 0, 5, '', '', 'readpoint', 'array (\n  ''minnumber'' => ''1'',\n  ''maxnumber'' => ''99999'',\n  ''decimaldigits'' => ''0'',\n  ''defaultvalue'' => '''',\n)', '', '-99', '-99', 0, 0, 0, 0, 0, 0, 0, 0, 55, 0, 0),
(98, 17, 1, 'username', '用户名', '', '', 0, 20, '', '', 'text', '', '', '', '', 1, 1, 0, 1, 0, 0, 0, 0, 98, 0, 0),
(99, 17, 1, 'islink', '转向链接', '', '', 0, 0, '', '', 'islink', '', '', '', '', 0, 1, 0, 1, 0, 1, 0, 0, 20, 0, 0),
(104, 16, 1, 'email', 'email', '', '', 1, 0, '/^[\\w\\-\\.]+@[\\w\\-\\.]+(\\.\\w+)+$/', '邮箱地址不争取', 'text', 'array (\n  ''size'' => ''50'',\n  ''defaultvalue'' => '''',\n  ''ispassword'' => ''0'',\n)', '', '', '', 0, 0, 0, 1, 0, 1, 1, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `xy_model_type`
--

CREATE TABLE IF NOT EXISTS `xy_model_type` (
`id` smallint(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `siteid` smallint(5) NOT NULL,
  `description` varchar(255) NOT NULL,
  `listorder` smallint(5) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `xy_model_type`
--

INSERT INTO `xy_model_type` (`id`, `name`, `module`, `siteid`, `description`, `listorder`) VALUES
(1, '楼盘分类', 'House', 1, '楼盘分类，包括新房，小区，二手房等模型', 2),
(2, '二手房出售', 'Sale', 1, '', 0),
(3, '二手房出租', 'Rent', 1, '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `xy_news`
--

CREATE TABLE IF NOT EXISTS `xy_news` (
`id` mediumint(8) unsigned NOT NULL,
  `siteid` smallint(5) NOT NULL DEFAULT '0',
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `typeid` smallint(5) unsigned NOT NULL,
  `title` char(80) NOT NULL DEFAULT '',
  `style` char(24) NOT NULL DEFAULT '',
  `thumb` char(100) NOT NULL DEFAULT '',
  `keywords` char(40) NOT NULL DEFAULT '',
  `description` char(255) NOT NULL DEFAULT '',
  `posids` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `url` varchar(100) NOT NULL DEFAULT '',
  `listorder` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(2) unsigned NOT NULL DEFAULT '1',
  `sysadd` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `islink` int(10) unsigned NOT NULL DEFAULT '0',
  `username` char(20) NOT NULL,
  `inputtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `xy_news_data`
--

CREATE TABLE IF NOT EXISTS `xy_news_data` (
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  `readpoint` smallint(5) unsigned NOT NULL DEFAULT '0',
  `paginationtype` tinyint(1) NOT NULL,
  `maxcharperpage` mediumint(6) NOT NULL,
  `template` varchar(30) NOT NULL,
  `paytype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `allow_comment` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `relation` text NOT NULL,
  `copyfrom` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `xy_node`
--

CREATE TABLE IF NOT EXISTS `xy_node` (
`id` smallint(6) unsigned NOT NULL,
  `module` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `params` varchar(255) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=254 ;

--
-- 转存表中的数据 `xy_node`
--

INSERT INTO `xy_node` (`id`, `module`, `action`, `title`, `status`, `remark`, `sort`, `pid`, `params`) VALUES
(1, 'Index', 'index', '系统默认', 1, '', 50, 1, ''),
(2, 'Index', 'public_session_life', 'SESSION LIVE', 1, NULL, 50, 1, ''),
(3, 'Menu', 'index', '菜单管理', 1, '', 0, 69, ''),
(5, 'Index', 'main', '默认首页', 1, '', 0, 58, ''),
(11, 'Menu', 'add', '添加', 1, '', 0, 3, ''),
(12, 'Menu', 'edit', '编辑', 1, '', 0, 3, ''),
(14, 'Menu', 'del', '删除', 1, '', 0, 3, ''),
(16, 'Role', 'index', '角色管理', 1, '', 0, 68, ''),
(17, 'Role', 'edit', '编辑', 1, '', 0, 16, ''),
(19, 'User', 'index', '管理员管理', 1, '', 0, 68, ''),
(21, 'User', 'add', '添加', 1, '', 0, 19, ''),
(22, 'User', 'edit', '编辑', 1, '', 0, 19, ''),
(23, 'User', 'del', '删除', 1, '', 0, 19, ''),
(25, 'Log', 'index', '操作日志', 1, '', 0, 69, ''),
(32, 'Changpwd', 'edit', '密码修改', 1, '', 0, 58, ''),
(41, 'Log', 'search', '查询', 1, '', 0, 25, ''),
(43, 'Position', 'index', '推荐位管理', 1, '', 0, 245, ''),
(45, 'Position', 'add', '添加', 1, '', 0, 196, ''),
(46, 'Position', 'edit', '修改', 1, '', 0, 196, ''),
(47, 'Position', 'delete', '删除', 1, '', 0, 196, ''),
(53, 'Index', 'cache_clean', '缓存清理', 1, '', 0, 66, ''),
(58, 'Index', 'left', '个人信息', 1, '', 0, 59, ''),
(59, 'Index', 'index', '我的面板', 1, '', 10, 0, ''),
(62, 'Role', 'index', '设置', 1, '', 9, 0, ''),
(63, 'Menu', 'index', '扩展', 1, '', 5, 0, ''),
(66, 'Convenience', 'index', '快捷操作', 1, '', 0, 59, ''),
(68, 'Manager', 'index', '管理员设置', 1, '', 0, 62, ''),
(69, 'Menu', 'index', '扩展管理', 1, '', 0, 63, ''),
(253, 'Linkage', 'index', '联动菜单', 1, '', 0, 69, ''),
(114, 'Site', 'index', '相关设置', 1, '', 1, 62, ''),
(115, 'Site', 'index', '站点管理', 1, '', 0, 114, ''),
(116, 'Site', 'add', '添加', 1, '', 0, 115, ''),
(117, 'Site', 'edit', '修改', 1, '', 0, 115, ''),
(196, 'Position', 'index', '推荐位', 1, '', 0, 43, ''),
(197, 'Position', 'list_type', '推荐位类别', 1, '', 0, 43, ''),
(245, 'Content', 'index', '内容', 1, '', 8, 0, ''),
(246, 'Content', 'index', '内容发布管理', 1, '', 1, 245, ''),
(247, 'Content', 'index', '内容管理', 1, '', 0, 246, ''),
(248, 'Category', 'index', '栏目管理', 1, '', 0, 246, ''),
(249, 'Model', 'index', '模型管理', 1, '', 0, 246, '');

-- --------------------------------------------------------

--
-- 表的结构 `xy_position`
--

CREATE TABLE IF NOT EXISTS `xy_position` (
`id` smallint(5) unsigned NOT NULL,
  `modelid` smallint(5) DEFAULT '0',
  `catid` smallint(5) DEFAULT '0',
  `name` char(30) NOT NULL DEFAULT '',
  `maxnum` smallint(5) NOT NULL DEFAULT '20',
  `extention` char(100) DEFAULT NULL,
  `listorder` smallint(5) unsigned NOT NULL DEFAULT '0',
  `siteid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `thumb` char(200) NOT NULL,
  `typeid` smallint(5) NOT NULL DEFAULT '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- 转存表中的数据 `xy_position`
--

INSERT INTO `xy_position` (`id`, `modelid`, `catid`, `name`, `maxnum`, `extention`, `listorder`, `siteid`, `thumb`, `typeid`) VALUES
(4, -1, -1, '楼盘首页推荐', 20, NULL, 0, 0, '', 1),
(9, -1, -1, '二手房首页优质出售房源推荐', 20, NULL, 0, 1, '', 2),
(8, -1, -1, '首页出售房源', 20, NULL, 0, 1, '', 2),
(10, -1, -1, '二手房首页优质出租房源推荐', 20, NULL, 0, 1, '', 3),
(11, -1, -1, '边栏推荐房源', 20, NULL, 0, 1, '', 2);

-- --------------------------------------------------------

--
-- 表的结构 `xy_position_data`
--

CREATE TABLE IF NOT EXISTS `xy_position_data` (
  `id` int(8) unsigned NOT NULL DEFAULT '0',
  `catid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `posid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `module` char(20) DEFAULT NULL,
  `modelid` smallint(6) unsigned DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `data` mediumtext,
  `siteid` smallint(5) unsigned NOT NULL DEFAULT '1',
  `listorder` int(8) DEFAULT '0',
  `expiration` int(10) NOT NULL,
  `synedit` tinyint(1) NOT NULL DEFAULT '1',
  `type` char(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xy_position_data`
--

INSERT INTO `xy_position_data` (`id`, `catid`, `posid`, `module`, `modelid`, `thumb`, `data`, `siteid`, `listorder`, `expiration`, `synedit`, `type`) VALUES
(3, 0, 4, 'House', 0, 'http://www.cdfdc.com/wechat/Uploads/2014/01/23/1390468861.jpg', 'array (\n  ''title'' => ''金色世纪'',\n  ''url'' => ''/xiaoyaocms/Wx/Index/project/id/3.html'',\n  ''description'' => '''',\n  ''inputtime'' => 1401410857,\n)', 1, 3, 0, 1, ''),
(949, 0, 9, 'Sale', 0, '', NULL, 1, 0, 0, 1, ''),
(948, 0, 9, 'Sale', 0, '', NULL, 1, 0, 0, 1, ''),
(11, 0, 10, 'Rent', 0, '', NULL, 1, 0, 0, 1, ''),
(10, 0, 10, 'Rent', 0, '', NULL, 1, 0, 0, 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `xy_role`
--

CREATE TABLE IF NOT EXISTS `xy_role` (
`id` smallint(6) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `xy_role`
--

INSERT INTO `xy_role` (`id`, `name`, `pid`, `status`, `remark`) VALUES
(1, '超级管理员', NULL, 1, '超级管理员'),
(2, '管理员', NULL, 1, '管理员');

-- --------------------------------------------------------

--
-- 表的结构 `xy_role_user`
--

CREATE TABLE IF NOT EXISTS `xy_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xy_role_user`
--

INSERT INTO `xy_role_user` (`role_id`, `user_id`) VALUES
(1, '1'),
(2, '2'),
(2, '37'),
(2, '39'),
(2, '42'),
(2, '43'),
(2, '44');

-- --------------------------------------------------------

--
-- 表的结构 `xy_session`
--

CREATE TABLE IF NOT EXISTS `xy_session` (
  `session_id` varchar(255) NOT NULL,
  `session_expire` int(11) NOT NULL,
  `session_data` blob
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `xy_session`
--

INSERT INTO `xy_session` (`session_id`, `session_expire`, `session_data`) VALUES
('040170a11972af96898788db3b250423', 1390961087, '');

-- --------------------------------------------------------

--
-- 表的结构 `xy_site`
--

CREATE TABLE IF NOT EXISTS `xy_site` (
`id` smallint(5) unsigned NOT NULL,
  `name` char(30) DEFAULT '',
  `dirname` char(255) DEFAULT '',
  `domain` char(255) DEFAULT '',
  `site_title` char(255) DEFAULT '',
  `keywords` char(255) DEFAULT '',
  `description` char(255) DEFAULT '',
  `release_point` text,
  `default_style` char(50) DEFAULT NULL,
  `template` text,
  `setting` mediumtext,
  `uuid` char(40) DEFAULT ''
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `xy_site`
--

INSERT INTO `xy_site` (`id`, `name`, `dirname`, `domain`, `site_title`, `keywords`, `description`, `release_point`, `default_style`, `template`, `setting`, `uuid`) VALUES
(1, '红海螺', 'www', 'http://www.hhailuo.com', 'XiaoYaoCMS', 'XiaoYaoCMS 房地产CMS 微信公众平台', 'XiaoYaoCMS 房产门户系统，集成微信公众平台', '', 'default', 'Admin', 'array (\n  ''upload_maxsize'' => ''1024'',\n  ''upload_allowext'' => ''jpg|jpeg|gif|bmp|png|doc|docx|xls|xlsx|ppt|pptx|pdf|txt|rar|zip|swf'',\n  ''watermark_enable'' => ''0'',\n  ''watermark_minwidth'' => ''300'',\n  ''watermark_minheight'' => ''300'',\n  ''watermark_img'' => ''/mark.png'',\n  ''watermark_pct'' => ''85'',\n  ''watermark_quality'' => ''80'',\n)', '3d3fd04a-6433-11e1-b487-1c750850a1c3'),
(2, '红海螺-房产', 'house', 'http://www.house.hhailuo.com', '红海螺-房产', '', '', NULL, NULL, NULL, 'array (\n  ''upload_maxsize'' => ''2048'',\n  ''upload_allowext'' => ''jpg|jpeg|gif|bmp|png|doc|docx|xls|xlsx|ppt|pptx|pdf|txt|rar|zip|swf'',\n  ''watermark_enable'' => ''0'',\n  ''watermark_minwidth'' => ''300'',\n  ''watermark_minheight'' => ''300'',\n  ''watermark_img'' => ''mark.gif'',\n  ''watermark_pct'' => ''100'',\n  ''watermark_quality'' => ''80'',\n)', '');

-- --------------------------------------------------------

--
-- 表的结构 `xy_user`
--

CREATE TABLE IF NOT EXISTS `xy_user` (
`id` smallint(5) unsigned NOT NULL,
  `account` varchar(64) NOT NULL,
  `password` char(32) NOT NULL,
  `realname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `last_login_time` int(11) unsigned DEFAULT '0',
  `last_login_ip` varchar(40) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `role_id` tinyint(2) unsigned DEFAULT '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

--
-- 转存表中的数据 `xy_user`
--

INSERT INTO `xy_user` (`id`, `account`, `password`, `realname`, `email`, `last_login_time`, `last_login_ip`, `status`, `role_id`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '', '', 1425523723, '111.8.131.129', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `xy_access`
--
ALTER TABLE `xy_access`
 ADD KEY `groupId` (`role_id`), ADD KEY `nodeId` (`node_id`);

--
-- Indexes for table `xy_attachment`
--
ALTER TABLE `xy_attachment`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `xy_category`
--
ALTER TABLE `xy_category`
 ADD PRIMARY KEY (`id`), ADD KEY `parentid` (`parentid`), ADD KEY `listorder` (`listorder`);

--
-- Indexes for table `xy_house`
--
ALTER TABLE `xy_house`
 ADD PRIMARY KEY (`id`), ADD KEY `listorder` (`status`,`listorder`,`id`);

--
-- Indexes for table `xy_house_data`
--
ALTER TABLE `xy_house_data`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`);

--
-- Indexes for table `xy_linkage`
--
ALTER TABLE `xy_linkage`
 ADD PRIMARY KEY (`id`,`keyid`), ADD KEY `parentid` (`parentid`,`listorder`);

--
-- Indexes for table `xy_log`
--
ALTER TABLE `xy_log`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `xy_model`
--
ALTER TABLE `xy_model`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `xy_model_field`
--
ALTER TABLE `xy_model_field`
 ADD PRIMARY KEY (`fieldid`), ADD KEY `modelid` (`modelid`,`disabled`), ADD KEY `field` (`field`,`modelid`);

--
-- Indexes for table `xy_model_type`
--
ALTER TABLE `xy_model_type`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `xy_news`
--
ALTER TABLE `xy_news`
 ADD PRIMARY KEY (`id`), ADD KEY `status` (`status`,`listorder`,`id`), ADD KEY `listorder` (`catid`,`status`,`listorder`,`id`), ADD KEY `catid` (`catid`,`status`,`id`);

--
-- Indexes for table `xy_news_data`
--
ALTER TABLE `xy_news_data`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`);

--
-- Indexes for table `xy_node`
--
ALTER TABLE `xy_node`
 ADD PRIMARY KEY (`id`), ADD KEY `pid` (`pid`), ADD KEY `status` (`status`);

--
-- Indexes for table `xy_position`
--
ALTER TABLE `xy_position`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `xy_position_data`
--
ALTER TABLE `xy_position_data`
 ADD PRIMARY KEY (`id`,`posid`), ADD KEY `posid` (`posid`), ADD KEY `listorder` (`listorder`);

--
-- Indexes for table `xy_role`
--
ALTER TABLE `xy_role`
 ADD PRIMARY KEY (`id`), ADD KEY `pid` (`pid`), ADD KEY `status` (`status`);

--
-- Indexes for table `xy_role_user`
--
ALTER TABLE `xy_role_user`
 ADD KEY `group_id` (`role_id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `xy_session`
--
ALTER TABLE `xy_session`
 ADD UNIQUE KEY `session_id` (`session_id`);

--
-- Indexes for table `xy_site`
--
ALTER TABLE `xy_site`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `xy_user`
--
ALTER TABLE `xy_user`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `account` (`account`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `xy_attachment`
--
ALTER TABLE `xy_attachment`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1417594339;
--
-- AUTO_INCREMENT for table `xy_category`
--
ALTER TABLE `xy_category`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `xy_house`
--
ALTER TABLE `xy_house`
MODIFY `id` mediumint(8) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `xy_linkage`
--
ALTER TABLE `xy_linkage`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3783;
--
-- AUTO_INCREMENT for table `xy_log`
--
ALTER TABLE `xy_log`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `xy_model`
--
ALTER TABLE `xy_model`
MODIFY `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `xy_model_field`
--
ALTER TABLE `xy_model_field`
MODIFY `fieldid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=105;
--
-- AUTO_INCREMENT for table `xy_model_type`
--
ALTER TABLE `xy_model_type`
MODIFY `id` smallint(5) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `xy_news`
--
ALTER TABLE `xy_news`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `xy_node`
--
ALTER TABLE `xy_node`
MODIFY `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=254;
--
-- AUTO_INCREMENT for table `xy_position`
--
ALTER TABLE `xy_position`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `xy_role`
--
ALTER TABLE `xy_role`
MODIFY `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `xy_site`
--
ALTER TABLE `xy_site`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `xy_user`
--
ALTER TABLE `xy_user`
MODIFY `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=45;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
