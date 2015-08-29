CREATE OR REPLACE VIEW Dreamtoys_analogs AS
SELECT
    p.code_1c, p.product_code, p.name_ru, (p.Price) AS Multitoys,
    Conc__dreamtoys.price_uah AS Dreamtoys, ROUND((p.Price/Conc__dreamtoys.price_uah-1)*100) AS Price_diff
FROM
    Conc_search__Dreamtoys
INNER JOIN
    Conc__dreamtoys ON Conc__dreamtoys.code=Conc_search__Dreamtoys.code
INNER JOIN
    Search_products AS p ON Conc_search__Dreamtoys.code_1c=p.code_1c
WHERE
    Conc_search__Dreamtoys.code_1c
IN
    (
      SELECT Search_products.code_1c
      FROM
        Search_products
      WHERE
        Search_products.enabled > 0
    )
ORDER BY Price_diff DESC
;
/******************************************************************/
CREATE OR REPLACE VIEW Mixtoys_analogs AS
SELECT
    p.code_1c, p.product_code, p.name_ru, (p.Price) AS Multitoys,
    Conc__mixtoys.price_uah AS Mixtoys, ROUND((p.Price/Conc__mixtoys.price_uah-1)*100) AS Price_diff
FROM
    Conc_search__Mixtoys
INNER JOIN
    Search_products AS p ON Conc_search__Mixtoys.code_1c=p.code_1c
INNER JOIN
    Conc__mixtoys ON Conc__mixtoys.code=Conc_search__Mixtoys.code
WHERE
    Conc_search__Mixtoys.code_1c
IN
    (
      SELECT Search_products.code_1c
      FROM
        Search_products
      WHERE
        Search_products.enabled > 0
    )
ORDER BY Price_diff DESC
;
/******************************************************************/
CREATE OR REPLACE VIEW Alliance_analogs AS
SELECT
    p.code_1c, p.product_code, p.name_ru, (p.Price) AS Multitoys,
    Conc__alliance.price_uah AS Alliance, ROUND((p.Price/Conc__alliance.price_uah-1)*100) AS Price_diff
FROM
    Conc_search__Alliance
INNER JOIN
    Search_products AS p ON Conc_search__Alliance.code_1c=p.code_1c
INNER JOIN
    Conc__alliance ON Conc__alliance.code=Conc_search__Alliance.code
WHERE
    Conc_search__Alliance.code_1c
IN
    (
      SELECT Search_products.code_1c
      FROM
        Search_products
      WHERE
        Search_products.enabled > 0
    )
ORDER BY Price_diff DESC
;
/******************************************************************/
CREATE OR REPLACE VIEW Divoland_analogs AS
SELECT
    p.code_1c, p.product_code, p.name_ru, (p.Price) AS Multitoys,
    Conc__divoland.price_uah AS Divoland,
  ROUND((p.Price/Conc__divoland.price_uah-1)*100) AS Price_diff
FROM
    Conc_search__divoland
INNER JOIN
    Search_products AS p ON Conc_search__Divoland.code_1c=p.code_1c
      INNER JOIN
  Conc__divoland ON Conc__divoland.code=Conc_search__divoland.code
WHERE
    Conc_search__divoland.code_1c
IN
    (
      SELECT Search_products.code_1c
      FROM
        Search_products
      WHERE
        Search_products.enabled > 0
    )
ORDER BY Price_diff DESC
;

SELECT *
FROM Conc__analogs
WHERE
  enabled AND
  (Alliance OR Divoland OR Dreamtoys OR Mixtoys)
  ;

# RENAME TABLE  multitoy_multitoys.Conc_search__Alliance TO  multitoy_multitoys.Conc_search__alliance ;
# RENAME TABLE  multitoy_multitoys.Conc_search__Divoland TO  multitoy_multitoys.Conc_search__divoland ;
# RENAME TABLE  multitoy_multitoys.Conc_search__Mixtoys TO  multitoy_multitoys.Conc_search__mixtoys ;
# RENAME TABLE  multitoy_multitoys.Conc_search__Dreamtoys TO  multitoy_multitoys.Conc_search__dreamtoys ;
# RENAME TABLE  multitoy_multitoys.Conc_analogs TO  multitoy_multitoys.Conc__analogs ;