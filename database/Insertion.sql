update tmp1 set dist="新店區" where dist="新店區 ";

INSERT INTO
    institution (ins_num, ins_name)
SELECT ins_num, ins_name
FROM tmp1;

INSERT INTO
    ins_address (
        ins_num,
        addr,
        city,
        dist,
        longitude,
        latitude
    )
SELECT
    ins_num,
    addr,
    LEFT(addr, 3) as city,
    dist,
    longitude,
    latitude
FROM tmp1;

INSERT INTO
    ins_info (
        ins_num,
        manager,
        phone,
        website
    )
SELECT
    ins_num,
    host_name,
    phone0,
    website
FROM tmp1
WHERE phone0 IS NOT NULL;

INSERT INTO
    ins_capacity (
        ins_num,
        caring_num,
        nurse_num,
        dem_num,
        long_caring_num,
        housing_num,
        providing_num
    )
SELECT
    ins_num,
    caring,
    nursing,
    dementia,
    long_caring, (
        caring + nursing + dementia + long_caring
    ) as housing_num,
    total_toll
FROM tmp1;

INSERT INTO
    func_web (func_name, func_website)
VALUES (
        '失智',
        'http://tada2002.ehosting.com.tw/Support.Tada2002.org.tw/support_resources06.html'
    );

INSERT INTO
    func_web (func_name, func_website)
VALUES (
        '安養',
        'https://www.sfaa.gov.tw/SFAA/Pages/Detail.aspx?nodeid=366&pid=2630'
    );

INSERT INTO
    func_web (func_name, func_website)
VALUES (
        '長照',
        'https://www.gov.tw/News_Content.aspx?n=26&s=505332'
    );

INSERT INTO
    func_web (func_name, func_website)
VALUES (
        '養護',
        'https://www.ilong-termcare.com/Article/Detail/93'
    );

INSERT INTO
    type_func (ins_num, func_name)
SELECT ins_num, orient0
FROM tmp1
WHERE orient0 IS NOT NULL;

INSERT INTO
    type_func (ins_num, func_name)
SELECT ins_num, orient1
FROM tmp1
WHERE orient1 IS NOT NULL;

INSERT INTO
    type_func (ins_num, func_name)
SELECT ins_num, orient2
FROM tmp1
WHERE orient2 IS NOT NULL;

INSERT INTO
    type_func (ins_num, func_name)
SELECT ins_num, orient3
FROM tmp1
WHERE orient3 IS NOT NULL;

INSERT INTO
    Taiwan_city_dist (city, dist, longitude, latitude)
SELECT
    LEFT(addr, 3),
    SUBSTRING(addr, 4),
    longitude,
    latitude
FROM tmp2;