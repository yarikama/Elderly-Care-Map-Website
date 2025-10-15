# 長照地圖網站

> [English](README.md) | [繁體中文](README.zh-TW.md)

## Final Project Proposal

**成員：** 109704001許恒睿、109704038孫于涵、110350028翁華駿、110704008羅緯翔

## 🎥 影片展示

[觀看展示影片](https://drive.google.com/file/d/1vSyCnu_Az3-GZU9DhUnR5SUKbIGK6qCg/view?usp=sharing)

## Application design

### 動機與主旨

在為家人查詢長照相關服務時感到資訊混淆，頁面不清等情況發生。因此我們小組想要為年長者及其家屬設計方便的資料庫，從統整公開的資料著手,設計資料庫架構，最後到網頁前後端設計。

### CRUD

- **Create:** 可供使用者創立自己帳號
- **Read:** 可供使用者進行關於長照服務的地區、功能、規則進行查詢
- **Update:** 可供使用者更新自己的蒐藏機構
- **Delete:** 可供使用者刪除自己的蒐藏機構

### 介面

界面分為兩大類：

#### 搜尋界面
- 功能、服務搜尋
- 地域搜尋
- 床位多寡排序
- 相近距離排序

#### 地圖頁面
- 提供自家地址搜尋
- 在點選地標時標示相關資訊
- 方圓功能
- 相關資訊如服務、網址、聯絡資訊

### 實作功能

- 以**床位數**進行分類，如床位數多為綠燈、床位數中為黃燈、床位數低為紅燈，排序呈現。
- 以**服務項目**進行分類，和搜尋。
- 以**縣市、鄉鎮**進行搜尋。
- 利用**google geography地圖化**項目來讓人直接觀看此地址所需資訊，如聯絡方式、網址、電話
- 設定地址，提供**方圓幾公里內有哪些機構**，床位數狀態等等。
- 設定地址提供**機構的距離**。
- 提供**搜尋機構名字，**提供機構資訊。
- 點選地址可以直接**連結到google導航**中。
- 以功能分類時，提供相關網站介紹規定，如衛服部。

## Data

> ***直接點擊名稱即可到資料來源的所在網址***

### [長照ABC據點](https://data.gov.tw/dataset/88270)

機構名稱、機構代碼、機構種類、縣市、區、地址全址、經度、緯度、O_ABC、特約服務項目、特約縣市、特約區域、機構電話、電子郵件、機構負責人姓名、特約起日、特約迄日、最後異動時間

### [全國機構名冊](https://www.sfaa.gov.tw/sfaa/pages/detail.aspx?nodeid=366&pid=2630)

![機構名冊](Final%20Project%20Proposal%20c06cb86145f54294acefd2c3312d67a0/Untitled.png)

### [衛服部長照專區](https://1966.gov.tw/LTC/mp-207.html)

長照規則、申請說明

## ERModel

> ***使用[QuickDBD](https://app.quickdatabasediagrams.com/#/d/SVcGqV)實現繪製ERM，但此軟體的relationship只有(mandatory)1:M(optional)可選擇，不一定和實際設定情況相同。***

### 資料表說明

#### institution
將此機構的基本資料作為table
- **ins_num：** 代表機構代號，由系統產生不重複且類似index的值作為代號，為PK
- **ins_name：** 代表機構的名稱

#### ins_info
將機構的相關資訊作為table
- **ins_num：** 代表機構代號，由系統產生不重複且類似index的值作為代號，為PK，以及連向institution的FK
- **manager：** 負責人的中文姓名
- **phone：** 機構的聯絡電話
- **website：** 機構的相關網址

#### ins_capacity
將機構的床位數作為table
- **ins_num：** 代表機構代號，由系統產生不重複且類似index的值作為代號，為PK，以及連向institution的FK
- **car_num：** 代表提供之安養型床位數
- **nurs_num：** 代表提供之養護型床位數
- **dem_num：** 代表提供之失智照顧型床位數
- **lcar_num：** 代表提供之長期照護型床位數
- **housing_num：** 代表目前使用中床位數
- **providing_num：** 代表提供之總床位數（derived attribute ⇒ 上述cal, lcar…提供之床位的總和）

#### type_func
代表機構的長照等級和功能作為table
- **ins_num：** 代表機構代號，由系統產生不重複且類似index的值作為代號，為PK，以及連向institution的FK
- **type：** 代表機構類型（如：長照ABC，或是私人機構等等）
- **func_name：** 代表提供之服務，連向func_web的func_name之FK（因通常一機構只會顯示主要提供服務，不會或沒有寫副項）

#### func_web
代表藉由服務名稱所連結到的規則網址作為table
- **func_name：** 代表提供之服務，為PK
- **web：** 代表對應之網址

#### member
代表與使用者的相關資料
- **member_id：** 代表使用者代號，由系統產生不重複且類似index的值作為代號，為PK
- **member_name：** 代表使用者暱稱
- **member_email：** 代表使用者的email（同時作為帳號）
- **member_password：** 代表使用者的密碼

#### member_favorite
代表使用者的蒐藏機構
- **member_id：** 代表使用者代號，由系統產生不重複且類似index的值作為代號，為PK，同時也是連向user.user_id的FK
- **ins_num：** 代表機構代號，由系統產生不重複且類似index的值作為代號，為PK，以及連向institution的FK

#### ins_address
代表機構和地址相關的資訊作為table
- **add_id：** 代表機構地址的代號，由系統產生不重複且類似index的值作為代號，為PK（由string構成add當作PK或FK會造成效率浪費，所以增加此特性為PK達到方便運算）
- **add：** 代表機構所在的地址全名
- **city：** 代表機構所在的縣或市
- **dist：** 代表機構所在的區或鄉
- **longitude：** 代表機構所在的經度
- **latitude：** 代表機構所在的緯度

### ABC分級服務

| ABC分級 | 服務項目 (15) | 內容 | 規定、備註 |
| --- | --- | --- | --- |
| A (1) | 個案管理服務 | 居家服務+日間照顧 | 擬訂計畫、輔導BC單位 |
| B (13) | 小規模多機能服務<br>日間照顧服務<br>交通接送服務<br>到宅沐浴車服務<br>居家失能個案家庭醫師照護服務<br>居家服務<br>家庭托顧服務<br>專業照護服務<br>喘息服務<br>團體家屋服務<br>輔具及居家無障礙環境服務<br>營養餐飲服務 | 居家照顧、社區照顧、機構照顧 其一 | 小規模、多機能長照服務 |
| C (1) | 巷弄長照站 | 共餐、健康促進、延緩失能 | 社區參與(里)、臨時短時數照顧、培育中高齡與學生人力資源 |

### ER 圖表

![ER 圖表](Final%20Project%20Proposal%20c06cb86145f54294acefd2c3312d67a0/Untitled%201.png)

### 資料庫架構圖

```sql
# Modify this code to update the DB schema diagram.
# To reset the sample schema, replace everything with
# two dots ('..' - without quotes).

institution
-
ins_num PK smallint 
ins_add_id FK >- ins_address.add_id smallint
ins_name UNIQUE varchar(50)

ins_capacity
-
ins_num PK smallint FK >- institution.ins_num 
caring_num smallint
nurse_num smallint
dem_num smallint
emp_num smallint #derived
long_caring_num smallint
housing_num smallint #derived
providing_num smallint

ins_info
-
ins_num PK smallint FK >- institution.ins_num
manager varchar(28)
phone varchar(50)
email varchar(100)
website varchar(500)

ins_address
-
add_id PK smallint
add varchar(100)
city char(6)
dist varchar(15)
longitude float
latitude float

type_func
-
int_num PK smallint FK >- institution.ins_num
type smallint
func_name varchar(60) FK >- func_web.func_name

func_web
-
func_name PK varchar(60)
func_website varchar(500)

user
-
user_id int PK
user_name unique varchar(50)
user_email varchar(100)
user_password varchar(50)

user_favorite
-
user_id int PK FK >- user.user_id
ins_num smallint PK FK >- institution.ins_num
```

## Work Plan

### Time Schedule

![時程表 1](Final%20Project%20Proposal%20c06cb86145f54294acefd2c3312d67a0/Untitled%202.png)

![時程表 2](Final%20Project%20Proposal%20c06cb86145f54294acefd2c3312d67a0/Untitled%203.png)

[Untitled Database](https://www.notion.so/268bf9f7e7654a0d9ee38f8cd911e67d?pvs=21)

### Discussion

[團隊討論紀錄](https://www.notion.so/099d5c5a546546ff90322269b20ac268?pvs=21)

### Repository

https://github.com/Weng-john/DBcourse

## 資料庫實作

```sql
DROP DATABASE IF EXISTS Final_Project;

CREATE DATABASE Final_Project; 

USE Final_Project;

CREATE TABLE `institution` (
    `ins_num` smallint  NOT NULL ,
    `ins_name` varchar(100)  NOT NULL ,
    PRIMARY KEY (
        `ins_num`
    ),
    CONSTRAINT `uc_institution_ins_name` UNIQUE (
        `ins_name`
    )
);

CREATE TABLE `ins_capacity` (
    `ins_num` smallint  NOT NULL ,
    `caring_num` smallint  NOT NULL ,
    `nurse_num` smallint  NOT NULL ,
    `dem_num` smallint  NOT NULL ,
    -- derived
    `long_caring_num` smallint  NOT NULL ,
    -- derived
    `housing_num` smallint  NOT NULL ,
    `providing_num` smallint  NOT NULL ,
    PRIMARY KEY (
        `ins_num`
    )
);

CREATE TABLE `ins_info` (
    `ins_num` smallint  NOT NULL ,
    `manager` varchar(28)  NOT NULL ,
    `phone` varchar(50)  NOT NULL ,
    `email` varchar(100)  NOT NULL ,
    `website` varchar(500)  NOT NULL ,
    PRIMARY KEY (
        `ins_num`
    )
);

CREATE TABLE `ins_address` (
    `ins_num` smallint  NOT NULL ,
    `addr` varchar(100)  NOT NULL ,
    `city` char(6)  NOT NULL ,
    `dist` varchar(15)  NOT NULL ,
    `longitude` float  NOT NULL ,
    `latitude` float  NOT NULL ,
    PRIMARY KEY (
        `ins_num`
    )
);

CREATE TABLE `type_func` (
    `int_num` smallint  NOT NULL ,
    `pub_pri_type` smallint  NOT NULL ,
    `serve_type` smallint  NOT NULL ,
    `func_name` varchar(60)  NOT NULL ,
    PRIMARY KEY (
        `int_num`
    )
);

CREATE TABLE `func_web` (
    `func_name` varchar(60)  NOT NULL ,
    `func_website` varchar(500)  NOT NULL ,
    PRIMARY KEY (
        `func_name`
    )
);

CREATE TABLE `user` (
    `user_id` int  NOT NULL ,
    `user_name` varchar(50)  NOT NULL ,
    `user_email` varchar(100)  NOT NULL ,
    `user_password` varchar(50)  NOT NULL ,
    PRIMARY KEY (
        `user_id`
    ),
    CONSTRAINT `uc_user_user_name` UNIQUE (
        `user_name`
    )
);

CREATE TABLE `user_favorite` (
    `user_id` int  NOT NULL ,
    `ins_num` smallint  NOT NULL ,
    PRIMARY KEY (
        `user_id`,`ins_num`
    )
);

CREATE TABLE `temp` (
    `pub_pri_type` varchar(20),
    `ins_name` varchar(100),
    `website` varchar(500),
    `manager` varchar(28),
    `dist` varchar(15),
    `addr` varchar(100),
    `phone0` varchar(50),
    `phone1` varchar(50),
    `serve_type0` varchar(10),
    `serve_type1` varchar(10),
    `serve_type2` varchar(10),
    `serve_type3` varchar(10),
    `Init_Date` varchar(50),
    `caring_num` smallint,
    `nurse_num` smallint,
    `dem_num` smallint,
    -- derived
    `long_caring_num` smallint,
    -- derived
    `housing_num` smallint,
    `providing_num_111` smallint,

    CONSTRAINT `uc_temp_ins_name` UNIQUE (
        `ins_name`
    )
);

ALTER TABLE `ins_capacity` ADD CONSTRAINT `fk_ins_capacity_ins_num` FOREIGN KEY(`ins_num`)
REFERENCES `institution` (`ins_num`);

ALTER TABLE `ins_info` ADD CONSTRAINT `fk_ins_info_ins_num` FOREIGN KEY(`ins_num`)
REFERENCES `institution` (`ins_num`);

ALTER TABLE `ins_address` ADD CONSTRAINT `fk_ins_address_ins_num` FOREIGN KEY(`ins_num`)
REFERENCES `institution` (`ins_num`);

ALTER TABLE `type_func` ADD CONSTRAINT `fk_type_func_int_num` FOREIGN KEY(`int_num`)
REFERENCES `institution` (`ins_num`);

ALTER TABLE `type_func` ADD CONSTRAINT `fk_type_func_func_name` FOREIGN KEY(`func_name`)
REFERENCES `func_web` (`func_name`);

ALTER TABLE `user_favorite` ADD CONSTRAINT `fk_user_favorite_user_id` FOREIGN KEY(`user_id`)
REFERENCES `user` (`user_id`);

ALTER TABLE `user_favorite` ADD CONSTRAINT `fk_user_favorite_ins_num` FOREIGN KEY(`ins_num`)
REFERENCES `institution` (`ins_num`);

LOAD DATA INFILE '/var/lib/mysql-files/final.csv' 
INTO TABLE temp
FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n';
```

---

## License

本專案為資料庫課程期末專題作業。

