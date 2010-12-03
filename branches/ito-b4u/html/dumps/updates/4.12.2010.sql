CREATE TABLE  `category` (
 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `category_name` VARCHAR( 255 ) NOT NULL
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_bin;

INSERT INTO  `b4u`.`category` (
`id` ,
`category_name`
)
VALUES (
'1',  'IT'
), (
'2',  'finance'
);

CREATE TABLE  `subcategory` (
 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `subcategory_name` VARCHAR( 255 ) NOT NULL ,
 `category_id` INT NOT NULL
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_bin;

INSERT INTO  `b4u`.`subcategory` (
`id` ,
`subcategory_name` ,
`category_id`
)
VALUES (
NULL ,  'SEO',  '1'
), (
NULL ,  'web-design',  '1'
);
INSERT INTO  `b4u`.`subcategory` (
`id` ,
`subcategory_name` ,
`category_id`
)
VALUES (
NULL ,  'development',  '1'
), (
NULL ,  'economic',  '2'
);

INSERT INTO  `b4u`.`subcategory` (
`id` ,
`subcategory_name` ,
`category_id`
)
VALUES (
NULL ,  'money',  '2'
);
