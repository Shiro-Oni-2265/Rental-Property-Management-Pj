/* ===============================
   1. DATABASE
   =============================== */
DROP DATABASE IF EXISTS quan_ly_phong_tro;
CREATE DATABASE quan_ly_phong_tro
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
USE quan_ly_phong_tro;

/* ===============================
   2. TABLES
   =============================== */

CREATE TABLE PHONG (
    ma_phong INT AUTO_INCREMENT PRIMARY KEY,
    dien_tich FLOAT,
    gia_thue DECIMAL(12,2),
    trang_thai VARCHAR(20) DEFAULT 'Trong'
);

CREATE TABLE NGUOI_THUE (
    ma_nguoi_thue INT AUTO_INCREMENT PRIMARY KEY,
    ho_ten VARCHAR(100),
    so_dien_thoai VARCHAR(15),
    cccd VARCHAR(20)
);

CREATE TABLE HOP_DONG (
    ma_hop_dong INT AUTO_INCREMENT PRIMARY KEY,
    ma_phong INT,
    ngay_bat_dau DATE,
    ngay_ket_thuc DATE,
    tien_coc DECIMAL(12,2),
    trang_thai VARCHAR(20) DEFAULT 'Dang thue',
    FOREIGN KEY (ma_phong) REFERENCES PHONG(ma_phong)
);

CREATE TABLE HOP_DONG_NGUOI_THUE (
    ma_hop_dong INT,
    ma_nguoi_thue INT,
    PRIMARY KEY (ma_hop_dong, ma_nguoi_thue),
    FOREIGN KEY (ma_hop_dong) REFERENCES HOP_DONG(ma_hop_dong),
    FOREIGN KEY (ma_nguoi_thue) REFERENCES NGUOI_THUE(ma_nguoi_thue)
);

CREATE TABLE DICH_VU (
    ma_dich_vu INT AUTO_INCREMENT PRIMARY KEY,
    ten_dich_vu VARCHAR(50),
    don_gia DECIMAL(10,2),
    don_vi VARCHAR(20)
);

CREATE TABLE HOA_DON (
    ma_hoa_don INT AUTO_INCREMENT PRIMARY KEY,
    ma_hop_dong INT,
    thang INT,
    nam INT,
    tong_tien DECIMAL(12,2) DEFAULT 0,
    trang_thai VARCHAR(30) DEFAULT 'Chua thanh toan',
    FOREIGN KEY (ma_hop_dong) REFERENCES HOP_DONG(ma_hop_dong)
);

CREATE TABLE CHI_TIET_HOA_DON (
    ma_ct INT AUTO_INCREMENT PRIMARY KEY,
    ma_hoa_don INT,
    ma_dich_vu INT,
    so_luong FLOAT,
    thanh_tien DECIMAL(12,2),
    FOREIGN KEY (ma_hoa_don) REFERENCES HOA_DON(ma_hoa_don),
    FOREIGN KEY (ma_dich_vu) REFERENCES DICH_VU(ma_dich_vu)
);

CREATE TABLE BAO_TRI (
    ma_bao_tri INT AUTO_INCREMENT PRIMARY KEY,
    ma_phong INT,
    loai_bao_tri VARCHAR(100),
    chi_phi DECIMAL(12,2),
    ngay_bao_tri DATE,
    FOREIGN KEY (ma_phong) REFERENCES PHONG(ma_phong)
);

CREATE TABLE SU_CO_AN_NINH (
    ma_su_co INT AUTO_INCREMENT PRIMARY KEY,
    mo_ta TEXT,
    ngay_xay_ra DATE
);

CREATE TABLE SU_CO_PHONG (
    ma_su_co INT,
    ma_phong INT,
    PRIMARY KEY (ma_su_co, ma_phong),
    FOREIGN KEY (ma_su_co) REFERENCES SU_CO_AN_NINH(ma_su_co),
    FOREIGN KEY (ma_phong) REFERENCES PHONG(ma_phong)
);

CREATE TABLE NOI_QUY (
    ma_noi_quy INT AUTO_INCREMENT PRIMARY KEY,
    noi_dung TEXT
);

CREATE TABLE PHAN_HOI (
    ma_phan_hoi INT AUTO_INCREMENT PRIMARY KEY,
    ma_nguoi_thue INT,
    noi_dung TEXT,
    loai VARCHAR(50),
    trang_thai VARCHAR(30) DEFAULT 'Chua xu ly',
    FOREIGN KEY (ma_nguoi_thue) REFERENCES NGUOI_THUE(ma_nguoi_thue)
);

/* ===============================
   3. STORED PROCEDURE
   =============================== */
DELIMITER $$

CREATE PROCEDURE sp_add_phong(
    IN p_dien_tich FLOAT,
    IN p_gia_thue DECIMAL(12,2)
)
BEGIN
    INSERT INTO PHONG(dien_tich, gia_thue)
    VALUES(p_dien_tich, p_gia_thue);
END $$

CREATE PROCEDURE sp_add_nguoi_thue(
    IN p_ho_ten VARCHAR(100),
    IN p_sdt VARCHAR(15),
    IN p_cccd VARCHAR(20)
)
BEGIN
    INSERT INTO NGUOI_THUE(ho_ten, so_dien_thoai, cccd)
    VALUES(p_ho_ten, p_sdt, p_cccd);
END $$

CREATE PROCEDURE sp_create_hop_dong(
    IN p_ma_phong INT,
    IN p_ngay_bd DATE,
    IN p_ngay_kt DATE,
    IN p_tien_coc DECIMAL(12,2)
)
BEGIN
    INSERT INTO HOP_DONG(ma_phong, ngay_bat_dau, ngay_ket_thuc, tien_coc)
    VALUES(p_ma_phong, p_ngay_bd, p_ngay_kt, p_tien_coc);
END $$

CREATE PROCEDURE sp_create_hoa_don(
    IN p_ma_hop_dong INT,
    IN p_thang INT,
    IN p_nam INT
)
BEGIN
    INSERT INTO HOA_DON(ma_hop_dong, thang, nam)
    VALUES(p_ma_hop_dong, p_thang, p_nam);
END $$

DELIMITER ;

/* ===============================
   4. TRIGGERS
   =============================== */
DELIMITER $$

CREATE TRIGGER trg_hop_dong_insert
AFTER INSERT ON HOP_DONG
FOR EACH ROW
BEGIN
    UPDATE PHONG
    SET trang_thai = 'Da thue'
    WHERE ma_phong = NEW.ma_phong;
END $$

CREATE TRIGGER trg_hop_dong_update
AFTER UPDATE ON HOP_DONG
FOR EACH ROW
BEGIN
    IF NEW.trang_thai IN ('Het han', 'Huy') THEN
        UPDATE PHONG
        SET trang_thai = 'Trong'
        WHERE ma_phong = NEW.ma_phong;
    END IF;
END $$

DELIMITER ;

/* ===============================
   5. TIME ACTION (EVENT)
   =============================== */
SET GLOBAL event_scheduler = ON;
DELIMITER $$

CREATE EVENT ev_check_hop_dong_het_han
ON SCHEDULE EVERY 1 DAY
DO
UPDATE HOP_DONG
SET trang_thai = 'Het han'
WHERE ngay_ket_thuc < CURDATE()
  AND trang_thai = 'Dang thue';
$$

CREATE EVENT ev_auto_create_hoa_don
ON SCHEDULE EVERY 1 MONTH
DO
INSERT INTO HOA_DON(ma_hop_dong, thang, nam)
SELECT ma_hop_dong, MONTH(CURDATE()), YEAR(CURDATE())
FROM HOP_DONG
WHERE trang_thai = 'Dang thue';
$$

DELIMITER ;