--ins_address 
INSERT INTO ins_address (ins_num, addr, city, dist, longitude, latitude) 
SELECT addr, LEFT(addr, 3) AS city, SUBSTRING_INDEX(SUBSTRING(addr, LOCATE('市', addr)+1), '區', 1) AS dist, longitude, latitude
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

--這邊好像有誤
INSERT INTO type_func (int_num, type, func_name)
SELECT ins_num, type, func_name
FROM tmp1;

SELECT func