/******************************************************************/
CREATE OR REPLACE VIEW divoland_orion AS
SELECT 
    p.code_1c, p.product_code, p.name_ru, (p.Price) AS Multitoys,
    Conc__divoland.price_uah AS Divoland, (p.Price/Conc__divoland.price_uah-1)*100 AS Price_diff 
FROM 
    Conc_search__Divoland
INNER JOIN
    Conc__divoland ON Conc__divoland.code=Conc_search__Divoland.code
INNER JOIN
    SC_products AS p ON Conc_search__Divoland.code_1c=p.code_1c
WHERE
    Conc_search__Divoland.code_1c 
IN
    (
      SELECT SC_products.code_1c
      FROM
        SC_products
      WHERE
        SC_products.brand = 'ORION'
    )
ORDER BY Price_diff DESC
;
/******************************************************************/
CREATE OR REPLACE VIEW Dreamtoys_HITS AS
SELECT
    p.code_1c, p.product_code, p.name_ru, (p.Price) AS Multitoys,
    Conc__dreamtoys.price_uah AS Dreamtoys, (p.Price/Conc__dreamtoys.price_uah-1)*100 AS Price_diff
FROM
    Conc_search__Dreamtoys
INNER JOIN
    Conc__dreamtoys ON Conc__dreamtoys.code=Conc_search__Dreamtoys.code
INNER JOIN
    SC_products AS p ON Conc_search__Dreamtoys.code_1c=p.code_1c
WHERE
    Conc_search__Dreamtoys.code_1c
IN
    (
      SELECT SC_products.code_1c
      FROM
        SC_products
      WHERE
        SC_products.items_sold > 0
    )
ORDER BY Price_diff DESC
;
/******************************************************************/
CREATE OR REPLACE VIEW Mixtoys_HITS AS
SELECT 
    p.code_1c, p.product_code, p.name_ru, (p.Price) AS Multitoys,
    Conc__mixtoys.price_uah AS Mixtoys, (p.Price/Conc__mixtoys.price_uah-1)*100 AS Price_diff 
FROM 
    Conc_search__Mixtoys
INNER JOIN 
    SC_products AS p ON Conc_search__Mixtoys.code_1c=p.code_1c
INNER JOIN 
    Conc__mixtoys ON Conc__mixtoys.code=Conc_search__Mixtoys.code
WHERE 
    Conc_search__Mixtoys.code_1c 
IN
    (
      SELECT SC_products.code_1c
      FROM
        SC_products
      WHERE
        SC_products.items_sold > 0
    )
ORDER BY Price_diff DESC
;
/******************************************************************/
CREATE OR REPLACE VIEW Alliance_HITS AS
SELECT 
    p.code_1c, p.product_code, p.name_ru, (p.Price) AS Multitoys,
    Conc__alliance.price_uah AS Alliance, (p.Price/Conc__alliance.price_uah-1)*100 AS Price_diff 
FROM 
    Conc_search__Alliance
INNER JOIN 
    SC_products AS p ON Conc_search__Alliance.code_1c=p.code_1c
INNER JOIN 
    Conc__alliance ON Conc__alliance.code=Conc_search__Alliance.code
WHERE 
    Conc_search__Alliance.code_1c 
IN
    (
      SELECT SC_products.code_1c
      FROM
        SC_products
      WHERE
        SC_products.items_sold > 0
    )
ORDER BY Price_diff DESC
;
/******************************************************************/
CREATE OR REPLACE VIEW Divoland_HITS AS
SELECT 
    p.code_1c, p.product_code, p.name_ru, (p.Price) AS Multitoys,
    Conc__divoland.price_uah AS Divoland, Conc__divoland.code,
    (p.Price/Conc__divoland.price_uah-1)*100 AS Price_diff
FROM 
    Conc_search__divoland
INNER JOIN
    SC_products AS p ON Conc_search__divoland.code_1c=p.code_1c
      INNER JOIN
  Conc__divoland ON Conc__divoland.code=Conc_search__divoland.code
WHERE
    Conc_search__divoland.code_1c 
IN
    (
      SELECT SC_products.code_1c
      FROM
        SC_products
      WHERE
        SC_products.items_sold > 0
    )
ORDER BY Price_diff DESC
;
/******************************************************************/
CREATE OR REPLACE VIEW Divoland_INTEX AS
SELECT
    p.code_1c, p.product_code, p.name_ru, (p.Price/19.7) AS Multitoys,
    Conc__divoland.price_usd AS Divoland, ((p.Price/19.8)/Conc__divoland.price_usd-1)*100 AS Price_diff
FROM
    Conc_search__divoland
INNER JOIN
    SC_products AS p ON Conc_search__divoland.code_1c=p.code_1c
INNER JOIN
    Conc__divoland ON Conc__divoland.code=Conc_search__divoland.code
WHERE
    Conc_search__divoland.code_1c
IN
    (
        SELECT
            SC_products.code_1c
        FROM
            SC_products
        WHERE
            SC_products.categoryID=2064
    )
ORDER BY Price_diff DESC
;
/******************************************************************/
CREATE OR REPLACE VIEW Dreamtoys_INTEX AS
SELECT 
    p.code_1c, p.product_code, p.name_ru, (p.Price/20.6) AS Multitoys, 
    Conc__dreamtoys.price_usd AS Dreamtoys, ((p.Price/20.6)/Conc__dreamtoys.price_usd-1)*100 AS Price_diff 
FROM 
    Conc_search__dreamtoys
INNER JOIN 
    SC_products AS p ON Conc_search__dreamtoys.code_1c=p.code_1c
INNER JOIN 
    Conc__dreamtoys ON Conc__dreamtoys.code=Conc_search__dreamtoys.code
WHERE 
    Conc_search__dreamtoys.code_1c 
IN 
    (
        SELECT 
            SC_products.code_1c 
        FROM 
            SC_products
        WHERE 
            SC_products.categoryID=2064
    )
ORDER BY Price_diff DESC
;
/******************************************************************/
CREATE OR REPLACE VIEW Mixtoys_INTEX AS
SELECT 
    p.code_1c, p.product_code, p.name_ru, (p.Price/20.6) AS Multitoys, 
    Conc__mixtoys.price_usd AS Mixtoys, ((p.Price/20.6)/Conc__mixtoys.price_usd-1)*100 AS Price_diff
FROM 
    Conc_search__mixtoys
INNER JOIN 
    SC_products AS p ON Conc_search__mixtoys.code_1c=p.code_1c
INNER JOIN 
    Conc__mixtoys ON Conc__mixtoys.code=Conc_search__mixtoys.code
WHERE 
    Conc_search__mixtoys.code_1c 
IN 
    (
        SELECT 
            SC_products.code_1c 
        FROM 
            SC_products
        WHERE 
            SC_products.categoryID=2064
    )
ORDER BY Price_diff DESC
;
/******************************************************************/

SELECT 
    p.code_1c, p.product_code AS Artikul, p.name_ru AS Name , (p.Price/20.6) AS Multitoys, 
    Mixtoys_INTEX.Mixtoys AS Mixtoys, Mixtoys_INTEX.Price_diff AS Mixtoys_diff,
    Dreamtoys_INTEX.Dreamtoys AS Dreamtoys, Dreamtoys_INTEX.Price_diff AS Dreamtoys_diff,
    Divoland_INTEX.Divoland AS Divoland, Divoland_INTEX.Price_diff AS Divoland_diff
FROM 
    SC_products AS p
RIGHT JOIN 
    Mixtoys_INTEX ON Mixtoys_INTEX.code_1c=p.code_1c
RIGHT JOIN 
    Dreamtoys_INTEX ON Dreamtoys_INTEX.code_1c=p.code_1c
RIGHT JOIN 
    Divoland_INTEX ON Divoland_INTEX.code_1c=p.code_1c
;
/******************************************************************/