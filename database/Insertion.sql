--ins_address 
INSERT INTO ins_address (ins_num, addr, city, dist, longitude, latitude) 
SELECT ins_num, addr, LEFT(addr, 3) AS city, SUBSTRING_INDEX(SUBSTRING(addr, LOCATE('市', addr)+1), '區', 1) AS dist, longitude, latitude
FROM tmp1;

INSERT INTO institution (ins_num, ins_name)
SELECT ins_num, ins_name
FROM tmp1;

INSERT INTO ins_info (ins_num, manager, phone, email, website)
SELECT ins_num, manager, phone, email, website
FROM tmp1;

INSERT INTO ins_capacity (ins_num, caring_num, nurse_num, dem_num, emp_num, long_caring_num, housing_num, providing_num)
SELECT ins_num, caring_num, nurse_num, dem_num, emp_num, long_caring_num, housing_num, providing_num
FROM tmp1;

INSERT INTO func_web (func_name, func_website)
VALUES ('失智', 'http://tada2002.ehosting.com.tw/Support.Tada2002.org.tw/support_resources06.html');

INSERT INTO func_web (func_name, func_website)
VALUES ('安養', 'https://www.sfaa.gov.tw/SFAA/Pages/Detail.aspx?nodeid=366&pid=2630');

INSERT INTO func_web (func_name, func_website)
VALUES ('長照', 'https://www.gov.tw/News_Content.aspx?n=26&s=505332');

INSERT INTO func_web (func_name, func_website)
VALUES ('養護', 'https://www.ilong-termcare.com/Article/Detail/93');

--這邊好像有誤
INSERT INTO type_func (int_num, func_name)
SELECT ins_num, orient0
FROM tmp1
WHERE orient0 IS NOT NULL;

INSERT INTO type_func (int_num, func_name)
SELECT ins_num, orient1
FROM tmp1
WHERE orient1 IS NOT NULL;

INSERT INTO type_func (int_num, func_name)
SELECT ins_num, orient2
FROM tmp1
WHERE orient2 IS NOT NULL;

INSERT INTO type_func (int_num, func_name)
SELECT ins_num, orient3
FROM tmp1
WHERE orient3 IS NOT NULL;

