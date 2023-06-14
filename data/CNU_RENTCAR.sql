DROP TABLE Customer CASCADE CONSTRAINTS;
DROP TABLE CarModel CASCADE CONSTRAINTS;
DROP TABLE RentCar CASCADE CONSTRAINTS;
DROP TABLE Options CASCADE CONSTRAINTS;
DROP TABLE Reservation CASCADE CONSTRAINTS;
DROP TABLE PreviousRental CASCADE CONSTRAINTS;

CREATE TABLE Customer (
    cno VARCHAR(10) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    passwd VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL
);

CREATE TABLE CarModel (
    modelName VARCHAR(100),
    vehicleType VARCHAR(100) NOT NULL,
    rentRatePerDay NUMBER NOT NULL,
    fuel VARCHAR(100) NOT NULL,
    numberOfSeats NUMBER NOT NULL,
    CONSTRAINT pk_carmodel PRIMARY KEY(modelName)
);

CREATE TABLE RentCar (
    licensePlateNo VARCHAR(20),
    modelName VARCHAR(100) NOT NULL,
    dateRented DATE,
    returnDate DATE,
    cno VARCHAR(10),
    CONSTRAINT pk_rentcar PRIMARY KEY(licensePlateNo),
    CONSTRAINT fk_rentcar_modelname FOREIGN KEY(modelName) REFERENCES CarModel(modelName),
    CONSTRAINT fk_rentcar_cno FOREIGN KEY(cno) REFERENCES Customer(cno)
);

CREATE TABLE Options (
    licensePlateNo VARCHAR(20),
    optionName VARCHAR(100) NOT NULL,
    CONSTRAINT pk_options PRIMARY KEY (licensePlateNo, optionName),
    CONSTRAINT fk_options FOREIGN KEY(licensePlateNo) REFERENCES RentCar(licensePlateNo)
);

CREATE TABLE Reservation (
    licensePlateNo VARCHAR(20),
    reserveDate DATE NOT NULL,
    startDate DATE NOT NULL,
    endDate DATE NOT NULL,
    cno VARCHAR(10) NOT NULL,
    CONSTRAINT pk_reserve PRIMARY KEY(licensePlateNo, startDate),
    CONSTRAINT fk_reserve_licenseplateno FOREIGN KEY(licensePlateNo) REFERENCES RentCar(licensePlateNo),
    CONSTRAINT fk_reserve_cno FOREIGN KEY(cno) REFERENCES Customer(cno) 
);

CREATE TABLE PreviousRental (
    licensePlateNo VARCHAR(20),
    dateRented DATE NOT NULL,
    dateReturned DATE NOT NULL,
    payment NUMBER(10) NOT NULL,
    cno VARCHAR(10) NOT NULL,
    CONSTRAINT pk_previousrental PRIMARY KEY (licensePlateNo, dateRented),
    CONSTRAINT fk_previsourental_licenseplateno FOREIGN KEY (licensePlateNo) REFERENCES RentCar(licensePlateNo),
    CONSTRAINT fk_previsourental_cno FOREIGN KEY(cno) REFERENCES Customer(cno) 
);

-- 관리자 이메일은 공용 이메일
-- 이짱구 이메일만 확인용 실제 이메일로
INSERT INTO Customer VALUES ('root', '관리자', 'root!', 'cnu.rentcar10@gmail.com');
INSERT INTO Customer VALUES ('202200001', '이짱구', '0001!', 'haijun79@naver.com');
INSERT INTO Customer VALUES ('202200002', '박철수', '0002!', 'parkcs23@gmail.co');
INSERT INTO Customer VALUES ('202200003', '김맹구', '0003!', 'kimmg34@gmail.co');
INSERT INTO Customer VALUES ('202300001', '신유리', '0001!', 'shinyr45@gmail.co');
INSERT INTO Customer VALUES ('202300002', '백형만', '0002!', 'paekhm56@gmail.co');
INSERT INTO Customer VALUES ('202300003', '최송아', '0003!', 'choish67@gmail.co');
SELECT * FROM Customer;

insert into carmodel
values('아반떼', '소형', 100000, '디젤', 5);
insert into carmodel
values('쏘나타', '대형', 130000, '디젤', 5);
insert into carmodel
values('투싼', 'SUV', 140000, '가솔린', 5);
insert into carmodel
values('스타렉스', '승합', 170000, '가솔린', 8);
insert into carmodel
values('G80', '전기', 120000, '전기', 5);
SELECT * FROM CarModel;

-- 2023년 6월 21일 00시 기준으로
-- 이짱구로 로그인한다는 가정
-- 기간 정하고, 빌릴 사람 정하기
insert into rentcar
values('50 허 4313', '아반떼', NULL, NULL, NULL);
insert into rentcar
values('50 허 2314', '아반떼', '2023-06-01', '2023-06-23', '202200002');
insert into rentcar
values('50 허 9450', '쏘나타', '2023-06-19', '2023-06-25', '202200003');
insert into rentcar
values('50 허 6745', '쏘나타', NULL, NULL, NULL);
insert into rentcar
values('50 허 2261', '쏘나타', NULL, NULL, NULL);

-- 여기에 날짜가 바뀌면
-- 예약에서 대여 상태로 넘어와 데이터에 넣도록 한다.
-- 고객은 이짱구로
insert into rentcar
values('50 허 6589', '쏘나타', NULL, NULL, NULL);

insert into rentcar
values('50 허 3158', '투싼', '2023-06-20', '2023-06-23', '202300001');
insert into rentcar
values('50 허 1234', '스타렉스', '2023-06-15', '2023-06-30', '202300002');
insert into rentcar
values('50 허 6894', 'G80', '2023-06-19', '2023-06-24', '202300003');
insert into rentcar
values('50 허 6703', 'G80', NULL, NULL, NULL);
SELECT * FROM RentCar;

-- 예약 정하기 렌트 고려해서 넣기 
-- 차번호 / 예약한날 / 렌트시작날 / 반납예정날 / cno
insert into reservation
values ('50 허 6703', '2023-06-12', '2023-06-27', '2023-07-01', '202200003');
insert into reservation
values ('50 허 6703', '2023-06-19', '2023-07-02', '2023-07-03', '202200001');
insert into reservation
values ('50 허 4313', '2023-06-19', '2023-06-25', '2023-06-30', '202200002');
insert into reservation
values ('50 허 4313', '2023-06-15', '2023-07-01', '2023-07-03', '202300001');
insert into reservation
values ('50 허 6745', '2023-06-11', '2023-07-13', '2023-07-16', '202300002');
insert into reservation
values ('50 허 6745', '2023-06-06', '2023-07-16', '2023-07-18', '202300003');
SELECT * FROM Reservation;
--주석 풀지 마세요
--데모를 위해 아래와 같은 형식의 데이터를 넣을 예정
--insert into reservation
--values ('50 허 6589', '2023-06-21', '2023-06-22', '2023-06-25', '202200001');

insert into options
values ('50 허 4313', '후방카메라');
insert into options
values ('50 허 4313', '썬루프');
insert into options
values ('50 허 2314', '후방카메라');
insert into options
values ('50 허 9450', '썬루프');
insert into options
values ('50 허 9450', '주행보조');
insert into options
values ('50 허 9450', '후방카메라');
insert into options
values ('50 허 6745', '후방카메라');
insert into options
values ('50 허 6745', '썬루프');
insert into options
values ('50 허 2261', '후방카메라');
insert into options
values ('50 허 6589', '후방카메라');
insert into options
values ('50 허 3158', '후방카메라');
insert into options
values ('50 허 3158', '주행보조');
insert into options
values ('50 허 3158', '썬루프');
insert into options
values ('50 허 1234', '후방카메라');
insert into options
values ('50 허 1234', '썬루프');
insert into options
values ('50 허 1234', '주행보조');
insert into options
values ('50 허 6894', '썬루프');
insert into options
values ('50 허 6894', '주행보조');
insert into options
values ('50 허 6894', '후방카메라');
insert into options
values ('50 허 6894', '컴포트 시트');
insert into options
values ('50 허 6703', '후방카메라');
insert into options
values ('50 허 6703', '주행보조');
SELECT * FROM Options;

-- 차번호와 비용계산해서 넣어주기
-- 사람은 상관없을 듯
INSERT INTO PreviousRental VALUES ('50 허 4313', TO_DATE('05/02/23', 'MM/DD/YY'), TO_DATE('05/10/23', 'MM/DD/YY'), 900000, '202300003');
INSERT INTO PreviousRental VALUES ('50 허 2314', TO_DATE('05/09/23', 'MM/DD/YY'), TO_DATE('05/11/23', 'MM/DD/YY'), 300000, '202200001');
INSERT INTO PreviousRental VALUES ('50 허 6745', TO_DATE('05/12/23', 'MM/DD/YY'), TO_DATE('05/15/23', 'MM/DD/YY'), 520000, '202300001');
INSERT INTO PreviousRental VALUES ('50 허 6589', TO_DATE('05/20/23', 'MM/DD/YY'), TO_DATE('05/22/23', 'MM/DD/YY'), 390000, '202300002');
INSERT INTO PreviousRental VALUES ('50 허 1234', TO_DATE('05/21/23', 'MM/DD/YY'), TO_DATE('05/24/23', 'MM/DD/YY'), 680000, '202200001');
INSERT INTO PreviousRental VALUES ('50 허 6894', TO_DATE('04/01/23', 'MM/DD/YY'), TO_DATE('04/03/23', 'MM/DD/YY'), 360000, '202200001');
INSERT INTO PreviousRental VALUES ('50 허 6703', TO_DATE('04/01/23', 'MM/DD/YY'), TO_DATE('04/10/23', 'MM/DD/YY'), 1200000, '202200002');
INSERT INTO PreviousRental VALUES ('50 허 2261', TO_DATE('04/10/23', 'MM/DD/YY'), TO_DATE('04/15/23', 'MM/DD/YY'), 780000, '202300003');
INSERT INTO PreviousRental VALUES ('50 허 2314', TO_DATE('04/10/23', 'MM/DD/YY'), TO_DATE('04/20/23', 'MM/DD/YY'), 1100000, '202300001');
INSERT INTO PreviousRental VALUES ('50 허 4313', TO_DATE('04/20/23', 'MM/DD/YY'), TO_DATE('04/22/23', 'MM/DD/YY'), 300000, '202300002');
INSERT INTO PreviousRental VALUES ('50 허 6703', TO_DATE('03/01/23', 'MM/DD/YY'), TO_DATE('03/03/23', 'MM/DD/YY'), 360000, '202200003');
INSERT INTO PreviousRental VALUES ('50 허 6894', TO_DATE('03/02/23', 'MM/DD/YY'), TO_DATE('03/08/23', 'MM/DD/YY'), 840000, '202200001');
INSERT INTO PreviousRental VALUES ('50 허 4313', TO_DATE('03/20/23', 'MM/DD/YY'), TO_DATE('03/21/23', 'MM/DD/YY'), 200000, '202300002');
INSERT INTO PreviousRental VALUES ('50 허 9450', TO_DATE('03/24/23', 'MM/DD/YY'), TO_DATE('03/28/23', 'MM/DD/YY'), 650000, '202300001');
INSERT INTO PreviousRental VALUES ('50 허 6589', TO_DATE('02/02/23', 'MM/DD/YY'), TO_DATE('02/04/23', 'MM/DD/YY'), 390000, '202300001');
INSERT INTO PreviousRental VALUES ('50 허 9450', TO_DATE('02/03/23', 'MM/DD/YY'), TO_DATE('02/06/23', 'MM/DD/YY'), 520000, '202200001');
INSERT INTO PreviousRental VALUES ('50 허 2314', TO_DATE('02/09/23', 'MM/DD/YY'), TO_DATE('02/12/23', 'MM/DD/YY'), 400000, '202200003');
INSERT INTO PreviousRental VALUES ('50 허 3158', TO_DATE('02/09/23', 'MM/DD/YY'), TO_DATE('02/15/23', 'MM/DD/YY'), 980000, '202300003');
INSERT INTO PreviousRental VALUES ('50 허 6894', TO_DATE('02/13/23', 'MM/DD/YY'), TO_DATE('02/19/23', 'MM/DD/YY'), 840000, '202200001');
INSERT INTO PreviousRental VALUES ('50 허 6703', TO_DATE('02/21/23', 'MM/DD/YY'), TO_DATE('02/24/23', 'MM/DD/YY'), 480000, '202300002');

SELECT * FROM PreviousRental;

COMMIT;

