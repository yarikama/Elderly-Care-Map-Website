DROP DATABASE IF EXISTS Final_Project;

CREATE DATABASE Final_Project;

USE Final_Project;

CREATE TABLE
    institution (
        ins_num smallint NOT NULL,
        ins_name varchar(100) NOT NULL,
        PRIMARY KEY (ins_num),
        CONSTRAINT uc_institution_ins_name UNIQUE (ins_name)
    );

CREATE TABLE
    ins_capacity (
        ins_num smallint NOT NULL,
        caring_num smallint NOT NULL,
        nurse_num smallint NOT NULL,
        dem_num smallint NOT NULL,
        -- derived
        emp_num smallint NOT NULL,
        long_caring_num smallint NOT NULL,
        -- derived
        housing_num smallint NOT NULL,
        providing_num smallint NOT NULL,
        PRIMARY KEY (ins_num)
    );

CREATE TABLE
    ins_info (
        ins_num smallint NOT NULL,
        manager varchar(100) NOT NULL,
        phone varchar(50) NOT NULL,
        email varchar(100) NOT NULL,
        website varchar(250) NOT NULL,
        PRIMARY KEY (ins_num)
    );

CREATE TABLE
    ins_address (
        ins_num smallint NOT NULL,
        addr varchar(100) NOT NULL,
        city char(30) NOT NULL,
        dist varchar(50) NOT NULL,
        longitude float NOT NULL,
        latitude float NOT NULL,
        PRIMARY KEY (ins_num)
    );

CREATE TABLE
    type_func (
        int_num smallint NOT NULL,
        type smallint NOT NULL,
        func_name varchar(60) NOT NULL,
        PRIMARY KEY (int_num)
    );

CREATE TABLE
    func_web (
        func_name varchar(60) NOT NULL,
        func_website varchar(250) NOT NULL,
        PRIMARY KEY (func_name)
    );

CREATE TABLE
    user (
        user_id int NOT NULL,
        user_name varchar(50) NOT NULL,
        user_email varchar(100) NOT NULL,
        user_password varchar(50) NOT NULL,
        PRIMARY KEY (user_id),
        CONSTRAINT uc_user_user_name UNIQUE (user_name)
    );

CREATE TABLE
    user_favorite (
        user_id int NOT NULL,
        ins_num smallint NOT NULL,
        PRIMARY KEY (user_id, ins_num)
    );

CREATE TABLE
    tmp1 (
        ins_num smallint NOT NULL AUTO_INCREMENT,
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
        latitude varchar(100),
        longitude varchar(100)
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
    CONSTRAINT fk_type_func_int_num FOREIGN KEY(int_num) REFERENCES institution (ins_num);

ALTER TABLE type_func
ADD
    CONSTRAINT fk_type_func_func_name FOREIGN KEY(func_name) REFERENCES func_web (func_name);

ALTER TABLE user_favorite
ADD
    CONSTRAINT fk_user_favorite_user_id FOREIGN KEY(user_id) REFERENCES institution (ins_num);
-- 用phpmyadmin匯入csv檔案