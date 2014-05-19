/*
MySQL Backup
Source Server Version: 5.6.12
Source Database: info
Date: 5/19/2014 20:50:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
--  Table structure for `article`
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_group_article` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date_create` datetime NOT NULL,
  `views` bigint(20) NOT NULL,
  `is_active` smallint(6) NOT NULL,
  `is_special` smallint(6) NOT NULL,
  `date_start` datetime DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort_description` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_23A0E662B36786B` (`title`),
  UNIQUE KEY `UNIQ_23A0E66F47645AE` (`url`),
  KEY `IDX_23A0E663A128B0F` (`id_group_article`),
  CONSTRAINT `FK_23A0E663A128B0F` FOREIGN KEY (`id_group_article`) REFERENCES `group_article` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
--  Table structure for `article_specialgrouparticle`
-- ----------------------------
DROP TABLE IF EXISTS `article_specialgrouparticle`;
CREATE TABLE `article_specialgrouparticle` (
  `article_id` bigint(20) NOT NULL,
  `specialgrouparticle_id` int(11) NOT NULL,
  PRIMARY KEY (`article_id`,`specialgrouparticle_id`),
  KEY `IDX_4BCDB3AD7294869C` (`article_id`),
  KEY `IDX_4BCDB3ADC36ECB8F` (`specialgrouparticle_id`),
  CONSTRAINT `FK_4BCDB3AD7294869C` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_4BCDB3ADC36ECB8F` FOREIGN KEY (`specialgrouparticle_id`) REFERENCES `special_group_article` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
--  Table structure for `article_tag`
-- ----------------------------
DROP TABLE IF EXISTS `article_tag`;
CREATE TABLE `article_tag` (
  `article_id` bigint(20) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`article_id`,`tag_id`),
  KEY `IDX_919694F97294869C` (`article_id`),
  KEY `IDX_919694F9BAD26311` (`tag_id`),
  CONSTRAINT `FK_919694F9BAD26311` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_919694F97294869C` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
--  Table structure for `cms_page`
-- ----------------------------
DROP TABLE IF EXISTS `cms_page`;
CREATE TABLE `cms_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `sort_desciption` varchar(500) NOT NULL,
  `description` longtext NOT NULL,
  `url` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `date_create` datetime NOT NULL,
  `date_update` datetime DEFAULT NULL,
  `is_active` smallint(6) NOT NULL,
  `options` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D39C1B5DF47645AE` (`url`),
  UNIQUE KEY `UNIQ_D39C1B5D2B36786B` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `group_article`
-- ----------------------------
DROP TABLE IF EXISTS `group_article`;
CREATE TABLE `group_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` smallint(6) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_active` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_special` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_843A5A6A5E237E06` (`name`),
  UNIQUE KEY `UNIQ_843A5A6AF47645AE` (`url`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
--  Table structure for `roles`
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B63E2EC757698A6A` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
--  Table structure for `setting`
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `special_group_article`
-- ----------------------------
DROP TABLE IF EXISTS `special_group_article`;
CREATE TABLE `special_group_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` smallint(6) NOT NULL,
  `position` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_FD3E24605E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
--  Table structure for `tag`
-- ----------------------------
DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_389B7835E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
--  Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1483A5E9F85E0677` (`username`),
  UNIQUE KEY `UNIQ_1483A5E9E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
--  Table structure for `user_role`
-- ----------------------------
DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `IDX_2DE8C6A3A76ED395` (`user_id`),
  KEY `IDX_2DE8C6A3D60322AC` (`role_id`),
  CONSTRAINT `FK_2DE8C6A3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_2DE8C6A3D60322AC` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
--  Records 
-- ----------------------------
INSERT INTO `article` VALUES ('2','3','Ronaldo quân bình kỷ lục giành Chiếc giày vàng của Messi','<b>Với 31 bàn thắng tại La Liga mùa này, Ronaldo \r\nchia sẻ danh hiệu Chiếc giày vàng châu Âu cùng Luis Suarez, đồng thời \r\nsan bằng thành tích của Lionel Messi.\r\n\r\n\r\n\r\n\r\n\r\n</b><span><b>Trong cuộc tiếp đón \r\nEspanyol ở vòng đấu cuối cùng La Liga, Ronaldo có tên trong đội hình \r\nxuất phát. Tuy vậy, trong lúc khởi động để chuẩn bị cho trận đấu, siêu \r\nsao người Bồ Đào Nha bị đau nên không thể tiếp tục thi đấu, qua đó \r\nnhường suất đá chính cho tài năng trẻ Isco. </b><br></span><br><div>Việc Ronaldo bị đau khi trận chung kết Champions League chỉ còn 7 \r\nngày nữa diễn ra khiến BHL và CĐV Real như ngồi trên đống lửa. Bởi lẽ \r\ntại giải năm nay, CR7 chơi vô cùng thuyết phục khi ghi tới 50 bàn sau 46\r\n lần ra sân. Trong đó ở riêng Champions League, anh phá vỡ kỷ lục ghi \r\nbàn của Messi trong một mùa giải với 16 pha lập công (hơn Messi 2 bàn).\r\n    \r\n        <br><br><span><span>\r\n            <img src=\"http://img.v3.news.zdn.vn/w660/Uploaded/SotnTJ/2014_05_18/Ronaldo.jpg\" alt=\"Nhp m t cho nh\">\r\n        </span>\r\n        <span>\r\n            <span><br><i>Ronaldo chia sẻ Giày vàng châu Âu mùa 2013/14 cùng Luis Suarez của Liverpool. Ảnh: Realmadrid.</i></span></span></span></div><br><div>Trong bài phát biểu sau\r\n chiến thắng 3-1 trước Espanyol, HLV Ancelotti công bố về mức độ chấn \r\nthương của cựu sao M.U. Theo chiến lược gia người Ý, Ronaldo chỉ bị đau \r\ncơ nhẹ và chắc chắn siêu sao này sẽ kịp bình phục cho trận chung kết \r\nChampions League với Atletico vào 25/5 tới.\r\n\r\nTuyên bố của Ancelotti chỉ phần nào giảm lo lắng từ các Madridista. Bởi \r\nsau khi Ronaldo dính chấn thương gân khoeo ở trận gặp Valladolid ở trận \r\nđấu bù vòng 34 (rời sân ngay ở phút thứ 9), cựu HLV của Chelsea cũng \r\ntuyên bố như vậy. Ông quả quyết Ronaldo chỉ bị đau nhẹ và sẽ trở lại thi\r\n đấu sau vài ngày dưỡng thương.\r\n\r\nVì không tham dự trận gặp Espanyol đêm qua nên Ronaldo không có cơ hội \r\nnâng tổng số bàn thắng ở mùa giải này. Tuy vậy, với 31 pha lập công, \r\nsiêu sao người Bồ Đào Nha đã cùng Luis Suarez (Liverpool) chia sẻ Chiếc \r\ngiày vàng châu Âu 2014. Đây là lần thứ 3 Ronaldo giành danh hiệu chân \r\nsút hàng đầu châu Âu. Trước đó anh từng 2 lần được vinh danh vào năm \r\n2008 và 2011. Thành tích giúp CR7 quân bình số lần giành danh hiệu Chiếc\r\n giày vàng của Lionel Messi (2010, 2012, 2013).\r\n\r\nNgoài ra với 31 lần xé lưới đối phương tại La Liga, Ronaldo còn giành \r\ndanh hiệu Pichichi (Vua phá lưới La Liga), nhiều hơn người đứng thứ 2 và\r\n 3 là Lionel Messi và Diego Costa lần lượt là 3 và 4 bàn.\r\n\r\n<br><br><b>Tiểu Minh</b><br></div><br><br>','9cebc9f583cf6bfc02a2729d7e106b58e7279d46.jpeg','2014-05-17 16:19:55','1','1','1','2014-05-18 14:20:00','test','Với 31 bàn thắng tại La Liga mùa này, Ronaldo chia sẻ danh hiệu Chiếc giày vàng châu Âu cùng Luis Suarez, đồng thời san bằng thành tích của Lionel Messi.\r\n\r\nTrong cuộc tiếp đón Espanyol ở vòng đấu cuối cùng La Liga, Ronaldo có tên trong đội hình xuất phát. Tuy vậy, trong lúc khởi động để chuẩn bị cho trận đấu, siêu sao người Bồ Đào Nha bị đau nên không thể tiếp tục thi đấu, qua đó nhường suất đá chính cho tài năng trẻ Isco.'), ('5','3','Tàu Việt Nam bị ngăn cản khi tiếp cận giàn khoan 981','<b>Dù bị nhiều loại tàu của Trung Quốc tăng tốc \r\nđộ để ngăn cản quyết liệt, song các tàu chấp pháp của Việt Nam đã cơ \r\nđộng, khéo léo tránh va chạm, kiểm soát được tình hình.\r\n\r\n\r\n\r\n\r\n\r\nĐó là lời khẳng định của \r\nông Nguyễn Văn Trung, Phó cục trưởng Cục Kiểm ngư (Bộ Nông nghiệp và \r\nPhát triển Nông thôn) tại cuộc họp diễn ra lúc 16h30 ngày 18/5 tại Hà \r\nNội.</b><br><br><blockquote><span>&nbsp;<span><span><img src=\"http://img.v3.news.zdn.vn/w660/Uploaded/nphunsa/2014_05_18/TTXVN_chapphap.jpg\" alt=\"u Cnh st bin Vit Nam lm nhim v thc thi php lut ti vng bin Trung Quc h t tri php gin khoan Hi Dng - 981 \">\r\n        </span>\r\n        \r\n            </span></span><br></blockquote>Tàu Cảnh sát biển Việt Nam làm nhiệm vụ thực thi pháp luật tại vùng biển Trung Quốc hạ đặt trái phép giàn khoan Hải Dương 981. \r\n        \r\n    \r\nThông tin về diễn biến hiện trường quanh khu vực giàn khoan \r\nHải Dương 981 (Haiyang Shi You 981), Phó cục trưởng Nguyễn Văn Trung cho\r\n biết, tàu chấp pháp của Việt Nam vẫn tiến hành tiếp cận giàn khoan thực\r\n hiện công tác tuyên truyền yêu cầu phía Trung Quốc rút giàn khoan và \r\ncác tàu về nước.“Tuy nhiên, khi tàu Việt Nam tiếp cận giàn khoan ở\r\n bán kính 5-6 hải lý liền bị tàu Hải cảnh, tàu Hải giám, tàu kéo, tàu cá\r\n vỏ sắt của Trung Quốc tăng tốc độ để ngăn cản quyết liệt, song các tàu \r\ncủa Việt Nam đã cơ động, khéo léo tránh va chạm và kiểm soát tình hình \r\nđảm bảo an toàn về lực lượng cũng như tàu thuyền,” ông Trung nói.Theo\r\n quan sát của lực lượng Kiểm ngư, số tàu của Trung Quốc quanh khu vực \r\ngiàn khoan Hải Dương-981 trong buổi sáng 18/5 là 134 chiếc, số tàu cá đã\r\n tăng thêm 15 chiếc so với ngày 17/5, tàu quân sự và tàu Hải cảnh vẫn \r\nduy trì, song số lượt máy bay đã giảm và bay ở tầm cao hơn.“Đáng \r\nlưu ý là số lượng tàu cá tăng thêm của Trung Quốc là các tàu gỗ song các\r\n tàu này đều được gia cố chắc chắn, được bọc thép phía trong và tăng \r\ncường hoạt động cản trở tàu cá ngư dân ta” ông Trung nhấn mạnh.Trước\r\n tình hình đó, phía Việt Nam vẫn duy trì lực lượng Kiểm ngư, Cảnh sát \r\nbiển và tàu công vụ khác tham gia bảo vệ chủ quyền. Các lực lượng phối \r\nhợp và liên kết chặt chẽ với nhau vừa thực hiện nhiệm vụ tuyên truyền \r\nyêu cầu phía Trung Quốc rút về nước vừa đảm bảo an toàn cho người cũng \r\nnhư phương tiện hoạt động tại khu vực này.Các tàu chấp pháp của \r\nViệt Nam vẫn thường xuyên tăng cường hoạt động hỗ trợ, bám sát để bảo vệ\r\n ngư dân đang hoạt động sản xuất tại ngư trường truyền thống quanh khu \r\nvực giàn khoan Hải Dương 981.Phó cục trưởng Nguyễn Văn Trung nêu \r\nrõ, Bộ Nông nghiệp và Phát triển nông thôn đã có chỉ đạo yêu cầu các địa\r\n phương động viên ngư dân và các đơn vị tăng cường hoạt động của tàu cá \r\nra khơi sản xuất. Lực lượng kiểm ngư được yêu cầu có kế hoạch hỗ trợ đảm\r\n bảo an toàn cho ngư dân hoạt động trên biển không những ở khu vực giàn \r\nkhoan mà kể cả những khu vực biển khác thuộc chủ quyền của đất nước.Cũng\r\n trong ngày 18/5, tại trụ sở Cục Kiểm ngư, đoàn công tác của Văn phòng \r\nChủ tịch nước do ông Đào Việt Trung, Chủ nhiệm Văn phòng Chủ tịch nước \r\nthừa ủy quyền của Chủ tịch nước Trương Tấn Sang đã đến thăm hỏi, động \r\nviên và tặng quà cho cán bộ, công chức, kiểm ngư viên, thuyền viên tàu \r\nKiểm ngư.“Trên tinh thần đó, toàn thể cán bộ và lực lượng kiểm \r\nngư càng thêm phấn khởi và quyết tâm nỗ lực, đồng sức đồng lòng thực \r\nhiện nhiệm vụ bảo vệ ngư dân, bảo vệ chủ quyền của đất nước”, ông Trung \r\nkhẳng định.<br>','39cd79acc7e8c2616d08b606a18f6a445f17831a.jpeg','2014-05-18 15:01:24','0','1','1','2014-05-18 21:47:00','tau-viet-nam-bi-ngan-can-khi-tiep-can-gian-khoan.html','Dù bị nhiều loại tàu của Trung Quốc tăng tốc độ để ngăn cản quyết liệt, song các tàu chấp pháp của Việt Nam đã cơ động, khéo léo tránh va chạm, kiểm soát được tình hình.\r\nĐó là lời khẳng định của ông Nguyễn Văn Trung, Phó cục trưởng Cục Kiểm ngư (Bộ Nông nghiệp và Phát triển Nông thôn) tại cuộc họp diễn ra lúc 16h30 ngày 18/5 tại Hà Nội.'), ('6','8','BlackBerry Z3 sẽ về Việt Nam vào tháng 7','<b><i>Thông tin trên được nguồn tin tại BlackBerry \r\nkhu vực Đông Dương cho biết sáng 18/5, dù BlackBerry Z3 đã được bán tại \r\nIndonesia cách đây 3 ngày. \r\n\r\n\r\n\r\n\r\n\r\n</i></b><br>Mới mức giá chưa tới 200 USD (khoảng 4,2 triệu đồng) tại thị trường \r\nIndonesia và được trang bị cấu hình khá tốt, pin khỏe, màn hình tới 5 \r\ninch. BlackBerry Z3 đang được kỳ vọng đáp ứng được nhu cầu của thị \r\ntrường, nhất là ở khu vực châu Á.<br><br>&nbsp;<span><span><img src=\"http://img.v3.news.zdn.vn/w660/Uploaded/mzdyv/2014_05_18/blackberryz3.jpg\" alt=\"Chic in thoi BlackBerry Z3 mi ra mt nh \">\r\n        </span>\r\n        <span>\r\n            <span><br><i>Chiếc điện thoại BlackBerry Z3 mới ra mắt. Ảnh: CrackBerry</i></span></span></span><br><br><div>Sau đó sẽ có phiên bản \r\ndành Z3 dành cho thị trường châu Âu và Bắc Mỹ. Các thông tin về chiếc \r\nBlackBerry mới từ Indonesia và trên nhiều diễn đàn công nghệ cho thấy sự\r\n kỳ vọng Z3 sẽ có mức giá hợp lý khi về Việt Nam. Máy có điểm số đánh \r\ngiá cao với hệ điều hành Blackberry 10.\r\n\r\nBlackBerry Z10 chính hãng hiện nay ở Việt Nam là 4,5 triệu đồng, với mức\r\n giá này làm cho Z10 khan hiếm nguồn cung. Các nhà phân phối khẳng định,\r\n nhu cầu mua BlackBerry Z10 tại Việt Nam hiện nay là rất lớn.\r\n\r\nSố lượng Z10 nhập về Việt Nam thời gian qua mới chỉ đáp ứng được khoảng \r\n10 đến 20% nhu cầu của khách hàng. Giá hàng xách tay đã được đẩy lên cao\r\n hơn từ vài trăm nghìn tới 1,5 triệu đồng. Trong khi, thông thường hàng \r\nxách tay phải rẻ hơn hàng chính hãng.\r\n\r\nMột lý do khác, Z10 hàng xách tay có chíp 4G LTE, trong khi hàng chính \r\nhãng dùng chip 3G. Tuy nhiên, điều này không tạo ra sự khác biệt bởi nhà\r\n mạng nội chưa cung cấp 4G. Theo một nguồn tin riêng, BlackBerry Z10 \r\ntrong tháng 5 sẽ về 9.000 máy chính hãng.<br></div><br><br>','7395294e5768c50844e84603152558fdf73f10a4.jpeg','2014-05-18 16:28:31','0','1','1','2014-05-18 23:25:00','blackberry-z3-se-ve-viet-nam-vao-thang-7.html','Thông tin trên được nguồn tin tại BlackBerry khu vực Đông Dương cho biết sáng 18/5, dù BlackBerry Z3 đã được bán tại Indonesia cách đây 3 ngày.\r\nZ3 là mẫu smartphone đầu tiên của BlackBerry hợp tác với Foxconn gia công nhằm giảm giá thành, thay vì tự sản xuất như các thế hệ trước.Mới mức giá chưa tới 200 USD (khoảng 4,2 triệu đồng) tại thị trường Indonesia và được trang bị cấu hình khá tốt, pin khỏe, màn hình tới 5 inch. BlackBerry Z3 đang được kỳ vọng đáp ứng được nhu cầu của thị trường, nhất là ở khu vực châu Á.'), ('7','9','Exciter 2014 màu đen, tem mới chưa có mặt ở đại lý','<b><i>Nhiều người dùng ở Hà Nội săn lùng mẫu Exciter 2014 màu đen độc đáo đều ngậm ngùi ra về vì đại lý vẫn chưa có xe để bán.\r\n\r\n\r\n\r\n\r\n\r\nSau khi công bố thông tin\r\n chính thức về mẫu Exciter 2014 với điểm đáng chú ý ở màu sơn đen, tem \r\nmới cho phiên bản RC và màu sơn đỏ cho bản R cách đây vài ngày, nhiều \r\nngười dùng ở Việt Nam tỏ ra thích thú với chiếc Exciter RC 2014 màu sơn \r\nđen.Theo đại diện của hãng, mẫu Exciter 2014 đã bắt đầu được bán \r\ntại các đại lý của Yamaha. Tuy nhiên, ghi nhận tại các đại lý chính hãng\r\n Yamaha ở Hà Nội trong ngày 18/5, phiên bản màu độc này vẫn chưa xuất \r\nhiện. </i></b><br><br>Trong số 3 đại lý lớn được khảo sát bao gồm Việt Nhật (Tôn Đức Thắng), \r\nKường Ngân (phố Huế) và Yamaha Khánh Ngà (Nguyễn Thái Học), chỉ có duy \r\nnhất đại lý Khánh Ngà trưng bày mẫu Exciter 2014 cả bản RC và bản R. Mặc\r\n dù vậy, chiếc Exciter RC 2014 màu đen vẫn chưa có hàng. Hầu hết nhân \r\nviên ở các đại lý cho biết họ chưa nhận được thông tin về mẫu Exciter \r\n2014. Số khác nhận định người dùng phải chờ đến tháng 7, đại lý mới có \r\nxe để bán.\r\n<br><br><img src=\"http://img.v3.news.zdn.vn/w660/Uploaded/mfssa/2014_05_18/ya1.jpg\" alt=\"Nhp m t cho nh\"><br><br><i>Yamaha Exciter 2014 RC màu đen vẫn chưa có mặt ở các đại lý. </i><br><div>Bắt gặp hai khách hàng tên Nam và Hoàng tại đại lý Kường Ngân (phố Huế),\r\n cả hai chia sẻ đã qua nhiều đại lý lớn của Yamaha ở Hà Nội nhưng đều \r\nnhận được câu trả lời chưa có phiên bản Exciter 2014 màu đen. Một trong \r\nhai khách hàng có ý định mua chiếc Exciter 2013 bản màu vàng, sau đó một\r\n thời gian sẽ lên tem mới.<br><br>Trước đó, Yamaha Việt Nam âm thầm ra mắt Exciter RC 2014 với 4 màu, bao \r\ngồm đỏ, trắng, vàng, đen. Bộ tem mới phối màu và bỏ đi logo số 10 như ở \r\nphiên bản năm 2013. Phiên bản Exciter R 2014 thêm màu sơn đỏ, bên cạnh \r\nhai màu cũ là xanh, cam, tem xe không đổi.<br><br>Exciter 2014 vẫn giữ \r\nnguyên thiết kế và động cơ như ở phiên bản 2013. Xe được trang bị 4 thì,\r\n xi-lanh đơn, SOHC, 4 van, làm mát bằng dung dịch, dung tích 135cc, sản \r\nsinh công suất tối đa 12,5 mã lực tại 8.500 vòng/phút, mô-men xoắn cực \r\nđại 12,9 Nm tại 6.000 vòng/phút.&nbsp;Yamaha Exciter 2014 bản R giá 37 triệu còn bản RC giá 40 triệu đồng. \r\n\r\n\r\n\r\n<br><br>Hân Nguyễn<br></div><br><br><br><br><br><br><br><br>','39cac79e888f043aa86b62a67b1d17eea001eb2b.jpeg','2014-05-18 16:32:48','0','1','1','2014-05-18 23:29:00','exciter-2014-mau-den.html','Nhiều người dùng ở Hà Nội săn lùng mẫu Exciter 2014 màu đen độc đáo đều ngậm ngùi ra về vì đại lý vẫn chưa có xe để bán.\r\nSau khi công bố thông tin chính thức về mẫu Exciter 2014 với điểm đáng chú ý ở màu sơn đen, tem mới cho phiên bản RC và màu sơn đỏ cho bản R cách đây vài ngày, nhiều người dùng ở Việt Nam tỏ ra thích thú với chiếc Exciter RC 2014 màu sơn đen.Theo đại diện của hãng, mẫu Exciter 2014 đã bắt đầu được bán tại các đại lý của Yamaha. Tuy nhiên, ghi nhận tại các đại lý chính hãng Yamaha ở Hà Nội trong ngày 18/5, phiên bản màu độc này vẫn chưa xuất hiện.'), ('8','5','Sách dạy làm bánh cho người chưa từng vào bếp','<b><i>“Hai căn bếp ngọt ngào” như một lớp dạy làm \r\nbánh, với đầy đủ các chỉ dẫn từ dụng cụ, nguyên liệu, kỹ thuật cơ bản \r\ncho đến quá trình hoàn thiện.\r\n\r\n\r\n\r\n\r\n\r\nHai căn bếp ngọt ngào\r\n là cuốn sách dạy nấu ăn chuyên về các công thức đồ ngọt và cũng là đứa \r\ncon tinh thần đã được ấp ủ trong 2 năm của hai tác giả Chi Anh, Hoàng \r\nAnh. Được nhà xuất bản Page One tại Singapore ra mắt phiên bản \r\ntiếng Anh toàn cầu, cuốn sách đã nâng tầm tác giả Việt ngang hàng cùng \r\ncác ấn phẩm chất lượng quốc tế.</i></b><br><br><span>\r\n    <span>\r\n        <span>\r\n            <img src=\"http://img.v3.news.zdn.vn/w660/Uploaded/erlx/2014_04_25/sachnauan1.jpg\" alt=\"Nhp m t cho nh\">\r\n        </span>\r\n        <span>\r\n            <span><br><br><i>Hai căn bếp ngọt ngào được viết bởi hai đầu bếp nghiệp dư trẻ tuổi.</i><br><br></span></span></span></span><span>Cuốn sách như một lớp dạy làm bánh, với đầy đủ các chỉ dẫn từ\r\n dụng cụ, nguyên liệu, các kỹ thuật cơ bản cho đến quá trình hoàn thiện.\r\n Chi Anh và Hoàng Anh muốn thực hiện tác phẩm này như một người đồng \r\nhành với những bạn mới đam mê làm bánh.<br><br></span><span>Hai căn bếp ngọt ngào cũng là hành trình các tác giả đã trải \r\nqua khi mới đặt chân vào thế giới nấu nướng. Bắt đầu từ những dụng cụ \r\ncần thiết, kỹ thuật cơ bản để thực hiện mọi loại bánh đến các nguyên \r\nliệu thiết yếu, cuốn sách sẽ giúp bạn khởi động từ kiến thức nền tảng và\r\n dễ dàng thử nghiệm công thức mới trong tương lai.<br><br></span>Với triết lý đơn giản nhưng cá tính, các công thức của hai tác giả 8X\r\n luôn ưu tiên sử dụng nguyên liệu từ thiên nhiên và sáng tạo điểm nhấn \r\ncủa món bánh cùng hoa quả theo mùa.<span>Tất cả công thức đều hạn chế tối đa phụ gia hóa học. Tại Hai căn bếp ngọt ngào, những món ăn chỉ đẹp rực rỡ mà thiếu dinh dương cùng mùi vị thơm ngon đều chỉ là đồ trưng bày không có giá trị.<br><br></span><br><span>\r\n    <span>\r\n        <span>\r\n            <img src=\"http://img.v3.news.zdn.vn/w660/Uploaded/erlx/2014_04_25/sachnauan2.jpg\" alt=\"Nhp m t cho nh\">\r\n<br>        </span>\r\n        <span>\r\n            <i>Món bánh quy chocolate đơn giản, thơm ngon do chính tay hai tác giả làm trong buổi ra mắt sách. </i><br><br></span></span></span>Cuốn sách này còn đặc biệt hơn khi được viết bởi hai tác giả \r\nchưa bao giờ gặp mặt nhau. Chi Anh hiện sống tại Hà Nội, còn Hoàng Anh \r\nluôn có một đại gia đình và công việc trong lĩnh vực tài chính tại Melbourne (Australia). Sống tại hai quốc gia nhưng họ vẫn kết nối khăng \r\nkhít từ những chia sẻ về nấu nướng, ẩm thực và trải nghiệm đời thường. \r\nVì vậy, không chỉ phá vỡ giới hạn về khoảng cách địa lý, cuốn sách còn \r\nxóa nhòa khoảng cách về ngôn ngữ và khác biệt văn hóa.<br><br><br><br><br><br><br>','3d8246091c00e4834fd1de93c517c6ae23988306.jpeg','2014-05-18 16:38:28','0','1','1','2014-05-18 23:34:00','hai-can-bep-ngot-ngao.html','Hai căn bếp ngọt ngào là cuốn sách dạy nấu ăn chuyên về các công thức đồ ngọt và cũng là đứa con tinh thần đã được ấp ủ trong 2 năm của hai tác giả Chi Anh, Hoàng Anh.\r\n\r\nĐược nhà xuất bản Page One tại Singapore ra mắt phiên bản tiếng Anh toàn cầu, cuốn sách đã nâng tầm tác giả Việt ngang hàng cùng các ấn phẩm chất lượng quốc tế.');
INSERT INTO `article_specialgrouparticle` VALUES ('6','1'), ('7','1'), ('8','1'), ('5','2'), ('6','2'), ('7','2'), ('2','3'), ('6','4'), ('7','4'), ('8','4');
INSERT INTO `article_tag` VALUES ('2','1'), ('2','3');
INSERT INTO `cms_page` VALUES ('1','Giới thiệu','Giới thiệu về chúng tôi','<b><i><span class=\"wysiwyg-color-red\">Giới thiệu về chúng tôi</span></i></b><br>','gioi-thieu.html','chung toi','2014-05-16 13:08:11','2014-05-17 17:41:36','1',NULL);
INSERT INTO `group_article` VALUES ('1','Giáo dục','1',NULL,'giao-duc.net','9a99cd49ae44dbe721736da2607d9c378caefb33.png',NULL,'0'), ('2','Sức khỏe','1',NULL,'suc-khoe.html',NULL,NULL,'0'), ('3','Thể thao','1',NULL,'the-thao.html','34487fbf14f363e4cb86b9c2df7134e006dddc13.png','9ed109c7749a205143566e31da4b07d052f1d0e6.png','1'), ('4','Du lịch','1',NULL,'du-lich.html','d0b4ba9da4d11809180d86513b306b72e2f5579e.png','da9061c256369f3c87a8d46b64ffc1824bde2522.png','1'), ('5','Ẩm thực','1',NULL,'am-thuc.html','3ea2edf02db832a1f3ec9c5a747264fbf436fc15.png','fadba79d2d0b447736ac0600cd48f4eb70d79a2a.png','1'), ('6','Thời trang','1',NULL,'thoi-trang.html','1bd32eb27df6b857bdf4ad471bc54954111acda3.png','636c2ae4caa57e8f4ca54565ec10e3eb367772f4.png','1'), ('7','Giải trí','1',NULL,'giai-tri.html','9202adb50555ebfa56ac283bc863e427d257997b.png','e584ca8367cb0f376888e07d7114398f99d9ca2e.png','1'), ('8','Công nghệ','1',NULL,'cong-nghe.html','3b2991171a027a934e44445adc73d53e02253832.png','cc00582213e698f64beb59c045cbfad9807d0f87.png','1'), ('9','Ôtô - Xe máy','1',NULL,'oto-xe-may.html','200911757aed6bebec73c88a61cf17dec9ce91c6.png','8b28709987f9744db7c4a6ca5db76c5fc3ceaca2.png','1'), ('10','Nhà đất','1',NULL,'nha-dat','7f181261c2bfc667f09f173fd307742c8cbbc480.png','94a5c893af401e617c3a1eaaccbeac0cecc63a54.png','1');
INSERT INTO `roles` VALUES ('1','admin','ROLE_ADMIN');
INSERT INTO `special_group_article` VALUES ('1','Khuyến mãi - Giảm giá','1','1'), ('2','Hội nghị - Hội Thảo','1','2'), ('3','Huấn luyện - Đào tạo','1','3'), ('4','Triển lãm - Hội chợ','1','4');
INSERT INTO `tag` VALUES ('1','thể thao','0'), ('3','du lịch','0');
INSERT INTO `users` VALUES ('1','admin','9518664cd261da57a39f4ac03044bb08','wONpRr/UO5dG3MJxUswKzyN+GdXxyDfXH5l+etb55uM9bNer2DU+LLyvNSmsT3IFWNPuZcBRJ4/fgXZ7mUGrbQ==','admin@contact.com','1');
INSERT INTO `user_role` VALUES ('1','1');
