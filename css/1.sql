INSERT  INTO shop_product
                   (`id`, `type_id`, `name`, `summary`, 
                   `category_id`, `description`, `url`, 
                   `create_datetime`, `currency`, `status`, 
                   `sku_id`, `contact_id`) 
                   VALUES (400761, 3, 'Рыбалка музыкальная (планшет) 2231В р 36,5*19 см /120/', '', 7763, '', 'rybalka-muzykal-naya-planshet-2231v-r-36-5-19-sm-120-41952', '2014-07-21 02:54:05', 'UAH', 0, -1, 1);
                   
		  INSERT  INTO shop_product_skus
                   (`name`, `sku`, `price`, `available`, `compare_price`, `sort`, `primary_price`, `product_id`) VALUES ('', '2231B', 56.42, 0, 0, 1, 56.42, 400761);
                   
		  SELECT count FROM shop_product_skus WHERE (id=7511);
		  
		  INSERT  INTO shop_product_stocks_log
                   (`product_id`, `sku_id`, `before_count`, `after_count`, `diff_count`, `datetime`, `description`, `type`) VALUES (400761, 7511, NULL, 100, 100, '2016-03-31 13:01:18', '', 'import');
                   
		  UPDATE shop_product_skus
                   SET `count` = 100
                   WHERE `id` = 7511;
                   
		  UPDATE shop_product
                   SET `sku_id` = 7511
                   WHERE `id` = 400761;
                   
		  SELECT * FROM shop_product WHERE `id` = 400761 LIMIT 1;
		  
		  SELECT * FROM shop_stock ORDER BY sort;
		  
		  SELECT * FROM shop_product_stocks WHERE `product_id` = 400761;
		  
		  SELECT * FROM shop_product_skus WHERE `product_id` = 400761;
		  
		  UPDATE shop_product
                   SET `sku_count` = 1, `min_price` = 56.42, `max_price` = 56.42, `price` = 56.42, `compare_price` = 0, `count` = 0
                   WHERE `id` = 400761;
                   
		  SELECT * FROM shop_product WHERE `id` = 400761 LIMIT 1;
		  
		  SELECT * FROM shop_category_products WHERE `product_id` = 400761;
		  
		  SELECT category_id, product_id FROM shop_category_products
                WHERE `product_id` = 400761;
                
		  SELECT category_id, MAX(sort) AS sort FROM shop_category_products
                        WHERE `category_id` IN ('7763')
                        GROUP BY category_id;
                        
		  INSERT INTO shop_category_products (`category_id`,`product_id`,`sort`) VALUES (7763,400761,7);
		  
		  UPDATE `shop_category` SET count = count + 1 WHERE id = 7763;
		  
		  UPDATE `shop_product` p
            JOIN `shop_category_products` cp ON p.id = cp.product_id
            SET p.category_id = cp.category_id
            WHERE p.category_id IS NULL
         AND p.id IN (400761);
         
		  UPDATE `shop_product` p
                JOIN (
            SELECT p.id FROM `shop_product` p
            LEFT JOIN `shop_category_products` cp ON p.id = cp.product_id AND p.category_id = cp.category_id
            WHERE p.category_id IS NOT NULL AND cp.category_id IS NULL
         AND p.id IN (400761) ) r ON p.id = r.id
                LEFT JOIN `shop_category_products` cp ON p.id = cp.product_id
                SET p.category_id = cp.category_id;
                
		  UPDATE shop_product
                   SET `category_id` = 7763
                   WHERE `id` = 400761;
                   
		  SELECT * FROM shop_product WHERE `id` = 400761 LIMIT 1;
		  
		  SELECT * FROM shop_search_word WHERE name LIKE 'планшет';
		  
		  SELECT * FROM shop_search_word WHERE name LIKE '2231в';
		  
		  INSERT IGNORE  INTO shop_search_word
                   (`name`) VALUES ('2231в');
                   
		  SELECT * FROM shop_search_word WHERE name LIKE 'р';
		  
		  SELECT * FROM shop_search_word WHERE name LIKE '36';
		  
		  SELECT * FROM shop_search_word WHERE name LIKE '5*19';
		  
		  SELECT * FROM shop_search_word WHERE name LIKE '120';
		  
		  SELECT * FROM shop_stock ORDER BY sort;
		  
		  SELECT * FROM shop_product_stocks WHERE `product_id` = 400761;
		  
		  SELECT * FROM shop_product_skus WHERE `product_id` = 400761;
		  
		  SELECT * FROM shop_search_word WHERE name LIKE '2231b';
		  
		  INSERT IGNORE  INTO shop_search_word
                   (`name`) VALUES ('2231b');
                   
		  SELECT * FROM shop_type WHERE `id` = 3 LIMIT 1;
		  
		  SELECT t.id, t.name FROM shop_product_tags pt
                JOIN shop_tag t ON pt.tag_id = t.id
                WHERE pt.product_id = 400761;
                
		  SELECT f.id AS feature_id, f.code, f.type, f.multiple, tf.sort
                    FROM shop_feature AS f
                        JOIN shop_type_features AS tf
                            ON tf.feature_id = IFNULL(f.parent_id,f.id)
                    WHERE tf.type_id=3
                    ORDER BY tf.sort;
                    
		  SELECT pf.*, f.code, f.type, f.multiple , tf.sort
                FROM shop_product_features pf
                    JOIN shop_feature f
                        ON pf.feature_id = f.id
                     LEFT JOIN shop_type_features tf ON ((tf.feature_id = IFNULL(f.parent_id,f.id)) AND (tf.type_id=3))
                WHERE pf.product_id = 400761
                    AND pf.sku_id IS NULL
                ORDER BY tf.sort;
                
		  INSERT INTO shop_search_index (`product_id`,`word_id`,`weight`) VALUES (400761,5331,90), (400761,917,90), (400761,4334,90), (400761,21094,90), (400761,1185,90), (400761,1379,90), (400761,18021,90), (400761,1850,90), (400761,520,90), (400761,21095,40), (400761,7,10);
		  
		  UPDATE `shop_type` SET `count`=`count`+1 WHERE `id` = 3;
		  
		  INSERT  INTO shop_product
                   (`id`, `type_id`, `name`, `summary`, `category_id`, `description`, `url`, `create_datetime`, `currency`, `status`, `sku_id`, `contact_id`) VALUES (400762, 3, 'Рыбалка на планш. /36/', '', 7763, '', 'rybalka-na-plansh-36-40608', '2014-07-21 02:54:05', 'UAH', 0, -1, 1);
                   
		  INSERT  INTO shop_product_skus
                   (`name`, `sku`, `price`, `available`, `compare_price`, `sort`, `primary_price`, `product_id`) VALUES ('', '901003C', 195.05, 0, 0, 1, 195.05, 400762);
                   
		  SELECT count FROM shop_product_skus WHERE (id=7512);
		  