 /*************************************************************************************************************************************************************/
SELECT p.*, p.productID,SUM(si.weight_part) AS weight_part,
  COUNT(*) AS weight_count FROM SC_products p JOIN shop_search_index si ON p.productID = si.product_id WHERE p.enabled = 1 AND si.word_id IN () GROUP BY p.productID ORDER BY weight_part DESC LIMIT 1

    /* получаем ids слов-индексов для поискового запроса*/
    Query	SELECT id FROM shop_search_word WHERE name LIKE '%%';
    
    /* количество ids товаров из search_index == ids слов-индексов*/
    Query	SELECT COUNT(DISTINCT p.productID) 
            FROM SC_products p 
            JOIN shop_search_index si ON p.productID = si.product_id 
            WHERE p.enabled = 1 AND si.word_id IN (5058,18345,50517,52766,53023,52767,52771,52772,32721,35966,32780,8521,38578,36447,2852,14397,8832,22549,32217);
            
            
    Query	SELECT p.*, p.productID, SUM(si.weight_part) AS weight_part,
            COUNT(*) AS weight_count 
            FROM SC_products p 
            JOIN shop_search_index si ON p.productID = si.product_id 
            WHERE p.enabled = 1 AND si.word_id IN (5058,18345,50517,52766,53023,52767,52771,52772,32721,35966,32780,8521,38578,36447,2852,14397,8832,22549,32217) 
            GROUP BY p.productID 
            ORDER BY weight_part DESC LIMIT 1;
            
    Query	SELECT * FROM SHOP_CURRENCY ORDER BY sort;
    
    Query	SELECT p.*, p.productID, SUM(si.weight_part) AS weight_part,
            COUNT(*) AS weight_count 
            FROM SC_products p 
            JOIN shop_search_index si ON p.productID = si.product_id 
            WHERE p.enabled = 1 AND si.word_id IN (5058,18345,50517,52766,53023,52767,52771,52772,32721,35966,32780,8521,38578,36447,2852,14397,8832,22549,32217)
            GROUP BY p.productID HAVING SUM(si.weight_part) >= 21
            ORDER BY weight_part DESC LIMIT 30;
            
    Query	SELECT COUNT(*) FROM (
                SELECT p.product_code, p.productId, p.name_ru FROM SC_products p 
                JOIN shop_search_index si ON p.productID = si.product_id 
                WHERE si.word_id IN (5058,18345,50517,52766,53023,52767,52771,52772,32721,35966,32780,8521,38578,36447,2852,14397,8832,22549,32217) 
                GROUP BY p.brand HAVING SUM(si.weight_part) >= 21
            ) AS t;
            