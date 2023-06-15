DROP DATABASE IF EXISTS Final_Project;

CREATE DATABASE Final_Project;

USE Final_Project;

CREATE TABLE
    institution (
        ins_num int NOT NULL,
        ins_name varchar(100) NOT NULL,
        PRIMARY KEY (ins_num),
        CONSTRAINT uc_institution_ins_name UNIQUE (ins_name)
    );

CREATE TABLE
    ins_capacity (
        ins_num int NOT NULL,
        caring_num int NOT NULL,
        nurse_num int NOT NULL,
        dem_num int NOT NULL,
        -- derived
        long_caring_num int NOT NULL,
        -- derived
        housing_num int NOT NULL,
        providing_num int NOT NULL,
        PRIMARY KEY (ins_num)
    );

CREATE TABLE
    ins_info (
        ins_num int NOT NULL,
        manager varchar(100) NOT NULL,
        phone varchar(50) NOT NULL,
        website varchar(250) NOT NULL,
        PRIMARY KEY (ins_num)
    );

CREATE TABLE
    ins_address (
        ins_num int NOT NULL,
        addr varchar(100) NOT NULL,
        city char(30) NOT NULL,
        dist varchar(50) NOT NULL,
        longitude float NOT NULL,
        latitude float NOT NULL,
        PRIMARY KEY (ins_num)
    );

CREATE TABLE
    type_func (
        ins_num int NOT NULL,
        func_name varchar(60) NOT NULL,
        PRIMARY KEY (ins_num, func_name)
    );

CREATE TABLE
    func_web (
        func_name varchar(60) NOT NULL,
        func_website varchar(250) NOT NULL,
        PRIMARY KEY (func_name)
    );

CREATE TABLE
    `member` (
        member_id int NOT NULL AUTO_INCREMENT,
        member_name varchar(50) NOT NULL,
        member_email varchar(100) NOT NULL UNIQUE,
        member_password varchar(50) NOT NULL,
        member_address varchar(200),
        member_phone VARCHAR(10),
        member_birthday DATE,
        member_gender char(5),
        member_type SMALLINT,
        PRIMARY KEY (member_id),
        CONSTRAINT uc_member_member_name UNIQUE (member_name)
    );

CREATE TABLE
    member_favorite (
        member_id int NOT NULL,
        ins_num int NOT NULL,
        PRIMARY KEY (member_id, ins_num)
    );

CREATE TABLE
    Taiwan_city_dist (
        dist_num int AUTO_INCREMENT,
        city varchar(14) NOT NULL,
        dist VARCHAR(14) NOT NULL,
        longitude FLOAT NOT NULL,
        latitude FLOAT NOT NULL,
        PRIMARY KEY (dist_num)
    );

CREATE TABLE
    tmp1 (
        ins_num int NOT NULL AUTO_INCREMENT PRIMARY KEY,
        public_private varchar(50) NOT NULL,
        ins_name varchar(200),
        website varchar(250),
        host_name varchar(50),
        dist varchar(50),
        addr varchar(200),
        phone0 varchar(20),
        phone1 varchar(20),
        orient0 varchar(50),
        orient1 varchar(50),
        orient2 varchar(50),
        orient3 varchar(50),
        init_date varchar(50),
        total_bed_num int,
        long_caring int,
        nursing int,
        dementia int,
        caring int,
        total_toll int,
        latitude float,
        longitude float
    );

CREATE TABLE
    tmp2 (
        addr varchar(20),
        ma3 char(3),
        longitude FLOAT,
        latitude FLOAT,
        TGOS_URL VARCHAR(250)
    );

ALTER TABLE ins_address
ADD
    CONSTRAINT fk_institution_ins_add_id FOREIGN KEY(ins_num) REFERENCES institution (ins_num);

ALTER TABLE ins_capacity
ADD
    CONSTRAINT fk_ins_capacity_ins_num FOREIGN KEY(ins_num) REFERENCES institution (ins_num);

ALTER TABLE ins_info
ADD
    CONSTRAINT fk_ins_info_ins_num FOREIGN KEY(ins_num) REFERENCES institution (ins_num);

ALTER TABLE type_func
ADD
    CONSTRAINT fk_type_func_ins_num FOREIGN KEY(ins_num) REFERENCES institution (ins_num);

ALTER TABLE type_func
ADD
    CONSTRAINT fk_type_func_func_name FOREIGN KEY(func_name) REFERENCES func_web (func_name);

ALTER TABLE member_favorite
ADD
    CONSTRAINT fk_member_favorite_member_id FOREIGN KEY(member_id) REFERENCES `member` (member_id);

ALTER TABLE member_favorite
ADD
    CONSTRAINT fk_member_favorite_ins_num FOREIGN KEY(ins_num) REFERENCES institution (ins_num);

-- 用phpmyadmin匯入csv檔案
-- public_private,ins_name,website,host_name,dist,addr,phone0,phone1,orient0,orient1,orient2,orient3,init_date,total_bed_num,long_caring,nursing,dementia,caring,total_toll,latitude,longitude
