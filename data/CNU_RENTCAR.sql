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

-- 관리자는 본인 이메일
INSERT INTO Customer VALUES ('root', '관리자', 'root!', 'haijun79@naver.com');
INSERT INTO Customer VALUES ('202200001', '이짱구', '0001!', 'leejg12@gmail.com');
INSERT INTO Customer VALUES ('202200002', '박철수', '0002!', 'parkcs23@gmail.com');
INSERT INTO Customer VALUES ('202200003', '김맹구', '0003!', 'kimmg34@gmail.com');
INSERT INTO Customer VALUES ('202300001', '신유리', '0001!', 'shinyr45@gmail.com');
INSERT INTO Customer VALUES ('202300002', '백형만', '0002!', 'paekhm56@gmail.com');
INSERT INTO Customer VALUES ('202300003', '최송아', '0003!', 'choish67@gmail.com');
SELECT * FROM Customer;

INSERT INTO CarModel VALUES ('스파크', '소형', 40000, '디젤', 5);
INSERT INTO CarModel VALUES ('쏘나타', '전기차', 45000, '전기', 5);
INSERT INTO CarModel VALUES ('투싼', 'SUV', 55000, '가솔린', 5);
INSERT INTO CarModel VALUES ('쏘렌토', 'SUV', 60000, '가솔린', 6);
INSERT INTO CarModel VALUES ('카니발', '대형', 70000, '디젤', 9);
SELECT * FROM CarModel;

-- 2023년 5월 2일 00시 기준
INSERT INTO RentCar VALUES ('180001', '스파크', TO_DATE('04/30/23', 'MM/DD/YY'), TO_DATE('05/03/23', 'MM/DD/YY'), '202300003');
INSERT INTO RentCar VALUES ('190001', '스파크', NULL, NULL, NULL);
INSERT INTO RentCar VALUES ('180002', '쏘나타', NULL, NULL, NULL);
INSERT INTO RentCar VALUES ('190002', '쏘나타', TO_DATE('04/25/23', 'MM/DD/YY'), TO_DATE('05/05/23', 'MM/DD/YY'), '202200002');
INSERT INTO RentCar VALUES ('190003', '투싼', NULL, NULL, NULL);
INSERT INTO RentCar VALUES ('200001', '투싼', TO_DATE('05/01/23', 'MM/DD/YY'), TO_DATE('05/03/23', 'MM/DD/YY'), '202200003');
INSERT INTO RentCar VALUES ('190004', '쏘렌토', TO_DATE('05/01/23', 'MM/DD/YY'), TO_DATE('05/02/23', 'MM/DD/YY'), '202300001');
INSERT INTO RentCar VALUES ('190005', '쏘렌토', NULL, NULL, NULL);
INSERT INTO RentCar VALUES ('210001', '쏘렌토', TO_DATE('04/20/23', 'MM/DD/YY'), TO_DATE('05/05/23', 'MM/DD/YY'), '202200001');
INSERT INTO RentCar VALUES ('200002', '카니발', NULL, NULL, NULL);
SELECT * FROM RentCar;

INSERT INTO Reservation VALUES ('180001', TO_DATE('04/29/23', 'MM/DD/YY'), TO_DATE('05/04/23', 'MM/DD/YY'), TO_DATE('05/05/23', 'MM/DD/YY'), '202300002');
INSERT INTO Reservation VALUES ('180001', TO_DATE('04/21/23', 'MM/DD/YY'), TO_DATE('05/06/23', 'MM/DD/YY'), TO_DATE('05/10/23', 'MM/DD/YY'), '202300003');
INSERT INTO Reservation VALUES ('180001', TO_DATE('04/22/23', 'MM/DD/YY'), TO_DATE('05/12/23', 'MM/DD/YY'), TO_DATE('05/13/23', 'MM/DD/YY'), '202200001');
INSERT INTO Reservation VALUES ('210001', TO_DATE('04/20/23', 'MM/DD/YY'), TO_DATE('05/07/23', 'MM/DD/YY'), TO_DATE('05/09/23', 'MM/DD/YY'), '202200002');
INSERT INTO Reservation VALUES ('210001', TO_DATE('05/01/23', 'MM/DD/YY'), TO_DATE('05/15/23', 'MM/DD/YY'), TO_DATE('05/20/23', 'MM/DD/YY'), '202200001');
INSERT INTO Reservation VALUES ('210001', TO_DATE('05/01/23', 'MM/DD/YY'), TO_DATE('05/22/23', 'MM/DD/YY'), TO_DATE('05/24/23', 'MM/DD/YY'), '202300003');
SELECT * FROM Reservation;

INSERT INTO Options VALUES ('180001', '블랙박스');
INSERT INTO Options VALUES ('180001', '후방카메라');
INSERT INTO Options VALUES ('190001', '블랙박스');
INSERT INTO Options VALUES ('180002', '블랙박스');
INSERT INTO Options VALUES ('180002', '후방카메라');
INSERT INTO Options VALUES ('190002', '블랙박스');
INSERT INTO Options VALUES ('190002', '내비게이션');
INSERT INTO Options VALUES ('190002', '후방카메라');
INSERT INTO Options VALUES ('190003', '블랙박스');
INSERT INTO Options VALUES ('190003', '내비게이션');
INSERT INTO Options VALUES ('200001', '블랙박스');
INSERT INTO Options VALUES ('200001', '내비게이션');
INSERT INTO Options VALUES ('200001', '후방카메라');
INSERT INTO Options VALUES ('200001', '가죽시트');
INSERT INTO Options VALUES ('190004', '블랙박스');
INSERT INTO Options VALUES ('190004', '내비게이션');
INSERT INTO Options VALUES ('190005', '블랙박스');
INSERT INTO Options VALUES ('190005', '내비게이션');
INSERT INTO Options VALUES ('190005', '후방카메라');
INSERT INTO Options VALUES ('210001', '블랙박스');
INSERT INTO Options VALUES ('210001', '내비게이션');
INSERT INTO Options VALUES ('210001', '후방카메라');
INSERT INTO Options VALUES ('210001', '가죽시트');
INSERT INTO Options VALUES ('210001', '열선시트');
INSERT INTO Options VALUES ('210001', '크루즈 컨트롤');
INSERT INTO Options VALUES ('200002', '블랙박스');
INSERT INTO Options VALUES ('200002', '내비게이션');
INSERT INTO Options VALUES ('200002', '후방카메라');
SELECT * FROM Options;

INSERT INTO PreviousRental VALUES ('190005', TO_DATE('04/01/23', 'MM/DD/YY'), TO_DATE('04/03/23', 'MM/DD/YY'), 180000, '202200001');
INSERT INTO PreviousRental VALUES ('210001', TO_DATE('04/01/23', 'MM/DD/YY'), TO_DATE('04/10/23', 'MM/DD/YY'), 600000, '202200002');
INSERT INTO PreviousRental VALUES ('190001', TO_DATE('04/10/23', 'MM/DD/YY'), TO_DATE('04/15/23', 'MM/DD/YY'), 240000, '202300003');
INSERT INTO PreviousRental VALUES ('190003', TO_DATE('04/10/23', 'MM/DD/YY'), TO_DATE('04/20/23', 'MM/DD/YY'), 605000, '202300001');
INSERT INTO PreviousRental VALUES ('200002', TO_DATE('04/20/23', 'MM/DD/YY'), TO_DATE('04/22/23', 'MM/DD/YY'), 210000, '202300002');

INSERT INTO PreviousRental VALUES ('180001', TO_DATE('03/01/23', 'MM/DD/YY'), TO_DATE('03/03/23', 'MM/DD/YY'), 120000, '202200003');
INSERT INTO PreviousRental VALUES ('180002', TO_DATE('03/02/23', 'MM/DD/YY'), TO_DATE('03/08/23', 'MM/DD/YY'), 315000, '202200001');
INSERT INTO PreviousRental VALUES ('190004', TO_DATE('03/20/23', 'MM/DD/YY'), TO_DATE('03/21/23', 'MM/DD/YY'), 120000, '202300002');
INSERT INTO PreviousRental VALUES ('190002', TO_DATE('03/24/23', 'MM/DD/YY'), TO_DATE('03/28/23', 'MM/DD/YY'), 225000, '202300001');
INSERT INTO PreviousRental VALUES ('200001', TO_DATE('02/02/23', 'MM/DD/YY'), TO_DATE('02/04/23', 'MM/DD/YY'), 165000, '202300001');
INSERT INTO PreviousRental VALUES ('200002', TO_DATE('02/03/23', 'MM/DD/YY'), TO_DATE('02/06/23', 'MM/DD/YY'), 280000, '202200001');
INSERT INTO PreviousRental VALUES ('190004', TO_DATE('02/09/23', 'MM/DD/YY'), TO_DATE('02/12/23', 'MM/DD/YY'), 240000, '202200003');
INSERT INTO PreviousRental VALUES ('180002', TO_DATE('02/09/23', 'MM/DD/YY'), TO_DATE('02/15/23', 'MM/DD/YY'), 315000, '202300003');
INSERT INTO PreviousRental VALUES ('200002', TO_DATE('02/13/23', 'MM/DD/YY'), TO_DATE('02/19/23', 'MM/DD/YY'), 490000, '202200001');
INSERT INTO PreviousRental VALUES ('210001', TO_DATE('02/21/23', 'MM/DD/YY'), TO_DATE('02/24/23', 'MM/DD/YY'), 240000, '202300002');
INSERT INTO PreviousRental VALUES ('210001', TO_DATE('01/02/23', 'MM/DD/YY'), TO_DATE('01/10/23', 'MM/DD/YY'), 540000, '202300003');
INSERT INTO PreviousRental VALUES ('190003', TO_DATE('01/09/23', 'MM/DD/YY'), TO_DATE('01/11/23', 'MM/DD/YY'), 165000, '202200001');
INSERT INTO PreviousRental VALUES ('200002', TO_DATE('01/12/23', 'MM/DD/YY'), TO_DATE('01/15/23', 'MM/DD/YY'), 280000, '202300001');
INSERT INTO PreviousRental VALUES ('180001', TO_DATE('01/20/23', 'MM/DD/YY'), TO_DATE('01/22/23', 'MM/DD/YY'), 120000, '202300002');
INSERT INTO PreviousRental VALUES ('210001', TO_DATE('01/21/23', 'MM/DD/YY'), TO_DATE('01/24/23', 'MM/DD/YY'), 240000, '202200001');
SELECT * FROM PreviousRental;
