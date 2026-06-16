CREATE DATABASE IF NOT EXISTS quan_ly_phong_tro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quan_ly_phong_tro;

-- ==========================================
-- 1. DROP CÁC ĐỐI TƯỢNG CŨ (NẾU CÓ)
-- ==========================================
SET FOREIGN_KEY_CHECKS = 0;
DROP VIEW IF EXISTS vw_phong_dang_thue;
DROP VIEW IF EXISTS vw_phong_trong;
DROP VIEW IF EXISTS vw_doanh_thu_thang;
DROP VIEW IF EXISTS vw_cong_no_khach_thue;

DROP EVENT IF EXISTS ev_check_hop_dong_het_han;
DROP EVENT IF EXISTS ev_auto_create_hoa_don;

DROP TABLE IF EXISTS PHAN_HOI;
DROP TABLE IF EXISTS NOI_QUY;
DROP TABLE IF EXISTS SU_CO_PHONG;
DROP TABLE IF EXISTS SU_CO_AN_NINH;
DROP TABLE IF EXISTS BAO_TRI;
DROP TABLE IF EXISTS CHI_TIET_HOA_DON;
DROP TABLE IF EXISTS HOA_DON;
DROP TABLE IF EXISTS HOP_DONG_DICH_VU;
DROP TABLE IF EXISTS HOP_DONG_NGUOI_THUE;
DROP TABLE IF EXISTS HOP_DONG;
DROP TABLE IF EXISTS DICH_VU;
DROP TABLE IF EXISTS NGUOI_THUE;
DROP TABLE IF EXISTS PHONG;
DROP TABLE IF EXISTS AuditLog;
SET FOREIGN_KEY_CHECKS = 1;

-- ==========================================
-- 2. TẠO CÁC BẢNG (TABLES) VÀ RÀNG BUỘC
-- ==========================================
CREATE TABLE PHONG (
    ma_phong INT AUTO_INCREMENT PRIMARY KEY,
    dien_tich FLOAT,
    gia_thue DECIMAL(12,2) NOT NULL,
    trang_thai VARCHAR(20) NOT NULL DEFAULT 'Trong',
    mo_ta TEXT,
    hinh_anh VARCHAR(255),
    CONSTRAINT CK_PHONG_trang_thai CHECK (trang_thai IN ('Trong', 'Da thue', 'Bao tri')),
    CONSTRAINT CK_PHONG_gia_thue CHECK (gia_thue >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE NGUOI_THUE (
    ma_nguoi_thue INT AUTO_INCREMENT PRIMARY KEY,
    ho_ten VARCHAR(100) NOT NULL,
    so_dien_thoai VARCHAR(15),
    cccd VARCHAR(20),
    CONSTRAINT UQ_NGUOI_THUE_cccd UNIQUE (cccd)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE DICH_VU (
    ma_dich_vu INT AUTO_INCREMENT PRIMARY KEY,
    ten_dich_vu VARCHAR(50) NOT NULL,
    don_gia DECIMAL(10,2),
    don_vi VARCHAR(20),
    CONSTRAINT CK_DICH_VU_don_gia CHECK (don_gia >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE HOP_DONG (
    ma_hop_dong INT AUTO_INCREMENT PRIMARY KEY,
    ma_phong INT NOT NULL,
    ngay_bat_dau DATE,
    ngay_ket_thuc DATE,
    tien_coc DECIMAL(12,2),
    trang_thai VARCHAR(20) NOT NULL DEFAULT 'Dang thue',
    CONSTRAINT FK_HOP_DONG_PHONG FOREIGN KEY (ma_phong) REFERENCES PHONG(ma_phong),
    CONSTRAINT CK_HOP_DONG_trang_thai CHECK (trang_thai IN ('Dang thue', 'Het han', 'Huy')),
    CONSTRAINT CK_HOP_DONG_tien_coc CHECK (tien_coc >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE HOP_DONG_NGUOI_THUE (
    ma_hop_dong INT,
    ma_nguoi_thue INT,
    PRIMARY KEY (ma_hop_dong, ma_nguoi_thue),
    CONSTRAINT FK_HDNT_HOP_DONG FOREIGN KEY (ma_hop_dong) REFERENCES HOP_DONG(ma_hop_dong),
    CONSTRAINT FK_HDNT_NGUOI_THUE FOREIGN KEY (ma_nguoi_thue) REFERENCES NGUOI_THUE(ma_nguoi_thue)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE HOP_DONG_DICH_VU (
    ma_hop_dong INT,
    ma_dich_vu INT,
    ngay_dang_ky DATE DEFAULT (CURRENT_DATE),
    PRIMARY KEY (ma_hop_dong, ma_dich_vu),
    CONSTRAINT FK_HDDV_HOP_DONG FOREIGN KEY (ma_hop_dong) REFERENCES HOP_DONG(ma_hop_dong) ON DELETE CASCADE,
    CONSTRAINT FK_HDDV_DICH_VU FOREIGN KEY (ma_dich_vu) REFERENCES DICH_VU(ma_dich_vu) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE HOA_DON (
    ma_hoa_don INT AUTO_INCREMENT PRIMARY KEY,
    ma_hop_dong INT,
    thang INT,
    nam INT,
    tong_tien DECIMAL(12,2) DEFAULT 0,
    trang_thai VARCHAR(30) NOT NULL DEFAULT 'Chua thanh toan',
    CONSTRAINT FK_HOA_DON_HOP_DONG FOREIGN KEY (ma_hop_dong) REFERENCES HOP_DONG(ma_hop_dong),
    CONSTRAINT CK_HOA_DON_trang_thai CHECK (trang_thai IN ('Chua thanh toan', 'Da thanh toan')),
    CONSTRAINT CK_HOA_DON_thang CHECK (thang BETWEEN 1 AND 12),
    CONSTRAINT CK_HOA_DON_tong_tien CHECK (tong_tien >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE CHI_TIET_HOA_DON (
    ma_ct INT AUTO_INCREMENT PRIMARY KEY,
    ma_hoa_don INT,
    ma_dich_vu INT,
    so_luong FLOAT,
    thanh_tien DECIMAL(12,2),
    CONSTRAINT FK_CTHD_HOA_DON FOREIGN KEY (ma_hoa_don) REFERENCES HOA_DON(ma_hoa_don),
    CONSTRAINT FK_CTHD_DICH_VU FOREIGN KEY (ma_dich_vu) REFERENCES DICH_VU(ma_dich_vu),
    CONSTRAINT CK_CTHD_thanh_tien CHECK (thanh_tien >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE BAO_TRI (
    ma_bao_tri INT AUTO_INCREMENT PRIMARY KEY,
    ma_phong INT,
    loai_bao_tri VARCHAR(100),
    chi_phi DECIMAL(12,2),
    ngay_bao_tri DATE,
    CONSTRAINT FK_BAO_TRI_PHONG FOREIGN KEY (ma_phong) REFERENCES PHONG(ma_phong)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE SU_CO_AN_NINH (
    ma_su_co INT AUTO_INCREMENT PRIMARY KEY,
    mo_ta TEXT,
    ngay_xay_ra DATE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE SU_CO_PHONG (
    ma_su_co INT,
    ma_phong INT,
    PRIMARY KEY (ma_su_co, ma_phong),
    CONSTRAINT FK_SCP_SU_CO FOREIGN KEY (ma_su_co) REFERENCES SU_CO_AN_NINH(ma_su_co),
    CONSTRAINT FK_SCP_PHONG FOREIGN KEY (ma_phong) REFERENCES PHONG(ma_phong)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE NOI_QUY (
    ma_noi_quy INT AUTO_INCREMENT PRIMARY KEY,
    noi_dung TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE PHAN_HOI (
    ma_phan_hoi INT AUTO_INCREMENT PRIMARY KEY,
    ma_nguoi_thue INT,
    noi_dung TEXT,
    loai VARCHAR(50),
    trang_thai VARCHAR(30) DEFAULT 'Chua xu ly',
    CONSTRAINT FK_PHAN_HOI_NGUOI_THUE FOREIGN KEY (ma_nguoi_thue) REFERENCES NGUOI_THUE(ma_nguoi_thue)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE AuditLog (
    ma_log INT AUTO_INCREMENT PRIMARY KEY,
    bang_tac_dong VARCHAR(50),
    hanh_dong VARCHAR(20),
    khoa_chinh VARCHAR(50),
    mo_ta TEXT,
    thoi_gian DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ==========================================
-- 3. TẠO CÁC KHUNG NHÌN (VIEWS)
-- ==========================================
CREATE VIEW vw_phong_dang_thue AS
SELECT p.ma_phong, p.dien_tich, p.gia_thue, p.trang_thai,
       hd.ma_hop_dong, hd.ngay_bat_dau, hd.ngay_ket_thuc,
       nt.ma_nguoi_thue, nt.ho_ten, nt.so_dien_thoai
FROM PHONG p
INNER JOIN HOP_DONG hd ON hd.ma_phong = p.ma_phong AND hd.trang_thai = 'Dang thue'
LEFT JOIN HOP_DONG_NGUOI_THUE hdnt ON hdnt.ma_hop_dong = hd.ma_hop_dong
LEFT JOIN NGUOI_THUE nt ON nt.ma_nguoi_thue = hdnt.ma_nguoi_thue;

CREATE VIEW vw_phong_trong AS
SELECT ma_phong, dien_tich, gia_thue, trang_thai, mo_ta
FROM PHONG
WHERE trang_thai = 'Trong';

CREATE VIEW vw_doanh_thu_thang AS
SELECT nam, thang,
       COUNT(*) AS so_hoa_don,
       SUM(tong_tien) AS doanh_thu
FROM HOA_DON
WHERE trang_thai = 'Da thanh toan'
GROUP BY nam, thang;

CREATE VIEW vw_cong_no_khach_thue AS
SELECT nt.ma_nguoi_thue, nt.ho_ten,
       IFNULL(SUM(hd.tong_tien), 0) AS cong_no
FROM NGUOI_THUE nt
INNER JOIN HOP_DONG_NGUOI_THUE hdnt ON hdnt.ma_nguoi_thue = nt.ma_nguoi_thue
INNER JOIN HOP_DONG h ON h.ma_hop_dong = hdnt.ma_hop_dong
INNER JOIN HOA_DON hd ON hd.ma_hop_dong = h.ma_hop_dong AND hd.trang_thai = 'Chua thanh toan'
GROUP BY nt.ma_nguoi_thue, nt.ho_ten;


-- ==========================================
-- 4. TẠO CÁC THỦ TỤC LƯU TRỮ (STORED PROCEDURES)
-- ==========================================
DELIMITER $$

CREATE PROCEDURE sp_add_phong(
    IN p_dien_tich FLOAT,
    IN p_gia_thue DECIMAL(12,2),
    IN p_hinh_anh VARCHAR(255),
    IN p_mo_ta TEXT
)
BEGIN
    INSERT INTO PHONG(dien_tich, gia_thue, hinh_anh, mo_ta)
    VALUES(p_dien_tich, p_gia_thue, p_hinh_anh, p_mo_ta);
    SELECT CONCAT('Them phong thanh cong. Ma phong moi = ', LAST_INSERT_ID()) AS message;
END $$

CREATE PROCEDURE sp_update_phong(
    IN p_ma_phong INT,
    IN p_dien_tich FLOAT,
    IN p_gia_thue DECIMAL(12,2),
    IN p_trang_thai VARCHAR(20),
    IN p_hinh_anh VARCHAR(255),
    IN p_mo_ta TEXT
)
BEGIN
    DECLARE room_exists INT DEFAULT 0;
    SELECT COUNT(*) INTO room_exists FROM PHONG WHERE ma_phong = p_ma_phong;
    IF room_exists = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Khong tim thay phong can cap nhat.';
    ELSE
        UPDATE PHONG
        SET dien_tich = p_dien_tich,
            gia_thue  = p_gia_thue,
            trang_thai= p_trang_thai,
            hinh_anh  = p_hinh_anh,
            mo_ta     = p_mo_ta
        WHERE ma_phong = p_ma_phong;
        SELECT 'Cap nhat phong thanh cong.' AS message;
    END IF;
END $$

CREATE PROCEDURE sp_delete_phong(
    IN p_ma_phong INT
)
BEGIN
    DECLARE has_active_contract INT DEFAULT 0;
    SELECT COUNT(*) INTO has_active_contract FROM HOP_DONG
    WHERE ma_phong = p_ma_phong AND trang_thai = 'Dang thue';
    
    IF has_active_contract > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Khong the xoa: phong dang co hop dong thue hieu luc.';
    ELSE
        DELETE FROM PHONG WHERE ma_phong = p_ma_phong;
        SELECT 'Xoa phong thanh cong.' AS message;
    END IF;
END $$

CREATE PROCEDURE sp_add_nguoi_thue(
    IN p_ho_ten VARCHAR(100),
    IN p_sdt VARCHAR(15),
    IN p_cccd VARCHAR(20)
)
BEGIN
    INSERT INTO NGUOI_THUE(ho_ten, so_dien_thoai, cccd)
    VALUES(p_ho_ten, p_sdt, p_cccd);
    SELECT CONCAT('Them khach thue thanh cong. Ma = ', LAST_INSERT_ID()) AS message;
END $$

CREATE PROCEDURE sp_update_nguoi_thue(
    IN p_ma_nguoi_thue INT,
    IN p_ho_ten VARCHAR(100),
    IN p_sdt VARCHAR(15),
    IN p_cccd VARCHAR(20)
)
BEGIN
    DECLARE tenant_exists INT DEFAULT 0;
    SELECT COUNT(*) INTO tenant_exists FROM NGUOI_THUE WHERE ma_nguoi_thue = p_ma_nguoi_thue;
    IF tenant_exists = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Khong tim thay khach thue can cap nhat.';
    ELSE
        UPDATE NGUOI_THUE
        SET ho_ten = p_ho_ten,
            so_dien_thoai = p_sdt,
            cccd = p_cccd
        WHERE ma_nguoi_thue = p_ma_nguoi_thue;
        SELECT 'Cap nhat khach thue thanh cong.' AS message;
    END IF;
END $$

CREATE PROCEDURE sp_delete_nguoi_thue(
    IN p_ma_nguoi_thue INT
)
BEGIN
    DECLARE has_contract INT DEFAULT 0;
    SELECT COUNT(*) INTO has_contract FROM HOP_DONG_NGUOI_THUE WHERE ma_nguoi_thue = p_ma_nguoi_thue;
    IF has_contract > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Khong the xoa: khach thue dang gan voi hop dong.';
    ELSE
        DELETE FROM PHAN_HOI WHERE ma_nguoi_thue = p_ma_nguoi_thue;
        DELETE FROM NGUOI_THUE WHERE ma_nguoi_thue = p_ma_nguoi_thue;
        SELECT 'Xoa khach thue thanh cong.' AS message;
    END IF;
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
    SELECT CONCAT('Tao hop dong thanh cong. Ma hop dong = ', LAST_INSERT_ID()) AS message;
END $$

CREATE PROCEDURE sp_create_hoa_don(
    IN p_ma_hop_dong INT,
    IN p_thang INT,
    IN p_nam INT
)
BEGIN
    INSERT INTO HOA_DON(ma_hop_dong, thang, nam)
    VALUES(p_ma_hop_dong, p_thang, p_nam);
    SELECT CONCAT('Tao hoa don thanh cong. Ma hoa don = ', LAST_INSERT_ID()) AS message;
END $$

CREATE PROCEDURE sp_thue_phong(
    IN p_ma_phong INT,
    IN p_ma_nguoi_thue INT,
    IN p_ngay_bd DATE,
    IN p_ngay_kt DATE,
    IN p_tien_coc DECIMAL(12,2)
)
BEGIN
    DECLARE v_ma_hop_dong INT;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;
        INSERT INTO HOP_DONG(ma_phong, ngay_bat_dau, ngay_ket_thuc, tien_coc)
        VALUES(p_ma_phong, p_ngay_bd, p_ngay_kt, p_tien_coc);
        SET v_ma_hop_dong = LAST_INSERT_ID();

        INSERT INTO HOP_DONG_NGUOI_THUE(ma_hop_dong, ma_nguoi_thue)
        VALUES(v_ma_hop_dong, p_ma_nguoi_thue);
    COMMIT;
    SELECT CONCAT('Thue phong thanh cong. Ma hop dong = ', v_ma_hop_dong) AS message;
END $$

CREATE PROCEDURE sp_tra_phong(
    IN p_ma_hop_dong INT
)
BEGIN
    DECLARE contract_exists INT DEFAULT 0;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    SELECT COUNT(*) INTO contract_exists FROM HOP_DONG WHERE ma_hop_dong = p_ma_hop_dong;
    IF contract_exists = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Khong tim thay hop dong.';
    ELSE
        START TRANSACTION;
            UPDATE HOP_DONG
            SET trang_thai = 'Het han'
            WHERE ma_hop_dong = p_ma_hop_dong;
        COMMIT;
        SELECT 'Tra phong thanh cong.' AS message;
    END IF;
END $$

CREATE PROCEDURE sp_lap_hoa_don(
    IN p_ma_hop_dong INT,
    IN p_thang INT,
    IN p_nam INT
)
BEGIN
    DECLARE v_ma_hoa_don INT;
    DECLARE contract_exists INT DEFAULT 0;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    SELECT COUNT(*) INTO contract_exists FROM HOP_DONG WHERE ma_hop_dong = p_ma_hop_dong;
    IF contract_exists = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Khong tim thay hop dong de lap hoa don.';
    ELSE
        START TRANSACTION;
            INSERT INTO HOA_DON(ma_hop_dong, thang, nam, tong_tien)
            VALUES(p_ma_hop_dong, p_thang, p_nam, 0);
            SET v_ma_hoa_don = LAST_INSERT_ID();

            INSERT INTO CHI_TIET_HOA_DON(ma_hoa_don, ma_dich_vu, so_luong, thanh_tien)
            SELECT v_ma_hoa_don, dv.ma_dich_vu, 1, dv.don_gia * 1
            FROM HOP_DONG_DICH_VU hddv
            INNER JOIN DICH_VU dv ON dv.ma_dich_vu = hddv.ma_dich_vu
            WHERE hddv.ma_hop_dong = p_ma_hop_dong;

            UPDATE HOA_DON
            SET tong_tien = IFNULL((SELECT SUM(thanh_tien) FROM CHI_TIET_HOA_DON WHERE ma_hoa_don = v_ma_hoa_don), 0)
            WHERE ma_hoa_don = v_ma_hoa_don;
        COMMIT;
        SELECT CONCAT('Lap hoa don thanh cong. Ma hoa don = ', v_ma_hoa_don) AS message;
    END IF;
END $$

CREATE PROCEDURE sp_thanh_toan_hoa_don(
    IN p_ma_hoa_don INT
)
BEGIN
    DECLARE bill_exists INT DEFAULT 0;
    DECLARE is_paid INT DEFAULT 0;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    SELECT COUNT(*) INTO bill_exists FROM HOA_DON WHERE ma_hoa_don = p_ma_hoa_don;
    IF bill_exists = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Khong tim thay hoa don.';
    END IF;

    SELECT COUNT(*) INTO is_paid FROM HOA_DON WHERE ma_hoa_don = p_ma_hoa_don AND trang_thai = 'Da thanh toan';
    IF is_paid > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Hoa don nay da duoc thanh toan truoc do.';
    END IF;

    START TRANSACTION;
        UPDATE HOA_DON
        SET trang_thai = 'Da thanh toan'
        WHERE ma_hoa_don = p_ma_hoa_don;
    COMMIT;
    SELECT 'Thanh toan hoa don thanh cong.' AS message;
END $$

CREATE PROCEDURE sp_thong_ke_doanh_thu_thang(
    IN p_thang INT,
    IN p_nam INT
)
BEGIN
    SELECT p_thang AS thang,
           p_nam AS nam,
           COUNT(*) AS so_hoa_don,
           IFNULL(SUM(tong_tien), 0) AS doanh_thu
    FROM HOA_DON
    WHERE thang = p_thang AND nam = p_nam AND trang_thai = 'Da thanh toan';
END $$

CREATE PROCEDURE sp_check_hop_dong_het_han()
BEGIN
    UPDATE HOP_DONG
    SET trang_thai = 'Het han'
    WHERE ngay_ket_thuc < CURDATE()
      AND trang_thai = 'Dang thue';
    SELECT CONCAT('Da cap nhat cac hop dong het han. So luong: ', ROW_COUNT()) AS message;
END $$

CREATE PROCEDURE sp_auto_create_hoa_don()
BEGIN
    INSERT INTO HOA_DON(ma_hop_dong, thang, nam)
    SELECT ma_hop_dong, MONTH(CURDATE()), YEAR(CURDATE())
    FROM HOP_DONG
    WHERE trang_thai = 'Dang thue';
    SELECT CONCAT('Da tao hoa don tu dong. So luong: ', ROW_COUNT()) AS message;
END $$

CREATE PROCEDURE sp_dashboard()
BEGIN
    SELECT 'Tong so phong' AS chi_tieu, COUNT(*) AS gia_tri FROM PHONG
    UNION ALL
    SELECT 'So phong dang thue', COUNT(*) FROM PHONG WHERE trang_thai = 'Da thue'
    UNION ALL
    SELECT 'So phong trong', COUNT(*) FROM PHONG WHERE trang_thai = 'Trong'
    UNION ALL
    SELECT 'So phong bao tri', COUNT(*) FROM PHONG WHERE trang_thai = 'Bao tri'
    UNION ALL
    SELECT 'Tong doanh thu (da thanh toan)', IFNULL(SUM(tong_tien), 0) FROM HOA_DON WHERE trang_thai = 'Da thanh toan';
END $$

DELIMITER ;


-- ==========================================
-- 5. TẠO CÁC BỘ KÍCH HOẠT (TRIGGERS)
-- ==========================================
DELIMITER $$

-- Triggers đồng bộ trạng thái phòng
CREATE TRIGGER trg_hop_dong_insert
AFTER INSERT ON HOP_DONG
FOR EACH ROW
BEGIN
    UPDATE PHONG SET trang_thai = 'Da thue' WHERE ma_phong = NEW.ma_phong;
END $$

CREATE TRIGGER trg_hop_dong_update
AFTER UPDATE ON HOP_DONG
FOR EACH ROW
BEGIN
    IF NEW.trang_thai IN ('Het han', 'Huy') THEN
        UPDATE PHONG SET trang_thai = 'Trong' WHERE ma_phong = NEW.ma_phong;
    END IF;
END $$

-- Triggers ghi nhật ký cho Hóa đơn
CREATE TRIGGER trg_hoadon_insert_log
AFTER INSERT ON HOA_DON
FOR EACH ROW
BEGIN
    INSERT INTO AuditLog(bang_tac_dong, hanh_dong, khoa_chinh, mo_ta)
    VALUES ('HOA_DON', 'INSERT', CAST(NEW.ma_hoa_don AS CHAR), 
            CONCAT('Lap hoa don thang ', NEW.thang, '/', NEW.nam, ' cho hop dong ', NEW.ma_hop_dong));
END $$

CREATE TRIGGER trg_hoadon_thanhtoan
AFTER UPDATE ON HOA_DON
FOR EACH ROW
BEGIN
    IF NEW.trang_thai = 'Da thanh toan' AND OLD.trang_thai <> 'Da thanh toan' THEN
        INSERT INTO AuditLog(bang_tac_dong, hanh_dong, khoa_chinh, mo_ta)
        VALUES ('HOA_DON', 'UPDATE', CAST(NEW.ma_hoa_don AS CHAR), 
                CONCAT('Thanh toan hoa don, so tien = ', NEW.tong_tien));
    END IF;
END $$

-- Triggers ghi nhật ký cho Phòng
CREATE TRIGGER trg_phong_insert
AFTER INSERT ON PHONG
FOR EACH ROW
BEGIN
    INSERT INTO AuditLog(bang_tac_dong, hanh_dong, khoa_chinh, mo_ta)
    VALUES ('PHONG', 'INSERT', CAST(NEW.ma_phong AS CHAR), 
            CONCAT('Trang thai = ', IFNULL(NEW.trang_thai, '(null)')));
END $$

CREATE TRIGGER trg_phong_update
AFTER UPDATE ON PHONG
FOR EACH ROW
BEGIN
    INSERT INTO AuditLog(bang_tac_dong, hanh_dong, khoa_chinh, mo_ta)
    VALUES ('PHONG', 'UPDATE', CAST(NEW.ma_phong AS CHAR), 
            CONCAT('Trang thai = ', IFNULL(NEW.trang_thai, '(null)')));
END $$

CREATE TRIGGER trg_phong_delete
AFTER DELETE ON PHONG
FOR EACH ROW
BEGIN
    INSERT INTO AuditLog(bang_tac_dong, hanh_dong, khoa_chinh, mo_ta)
    VALUES ('PHONG', 'DELETE', CAST(OLD.ma_phong AS CHAR), 
            'Da xoa phong');
END $$

-- Triggers ghi nhật ký cho Khách thuê
CREATE TRIGGER trg_nguoi_thue_insert
AFTER INSERT ON NGUOI_THUE
FOR EACH ROW
BEGIN
    INSERT INTO AuditLog(bang_tac_dong, hanh_dong, khoa_chinh, mo_ta)
    VALUES ('NGUOI_THUE', 'INSERT', CAST(NEW.ma_nguoi_thue AS CHAR), 
            CONCAT('Khach thue: ', IFNULL(NEW.ho_ten, '(null)')));
END $$

CREATE TRIGGER trg_nguoi_thue_update
AFTER UPDATE ON NGUOI_THUE
FOR EACH ROW
BEGIN
    INSERT INTO AuditLog(bang_tac_dong, hanh_dong, khoa_chinh, mo_ta)
    VALUES ('NGUOI_THUE', 'UPDATE', CAST(NEW.ma_nguoi_thue AS CHAR), 
            CONCAT('Khach thue: ', IFNULL(NEW.ho_ten, '(null)')));
END $$

CREATE TRIGGER trg_nguoi_thue_delete
AFTER DELETE ON NGUOI_THUE
FOR EACH ROW
BEGIN
    INSERT INTO AuditLog(bang_tac_dong, hanh_dong, khoa_chinh, mo_ta)
    VALUES ('NGUOI_THUE', 'DELETE', CAST(OLD.ma_nguoi_thue AS CHAR), 
            CONCAT('Da xoa khach thue: ', IFNULL(OLD.ho_ten, '(null)')));
END $$

DELIMITER ;


-- ==========================================
-- 6. TẠO CÁC SỰ KIỆN ĐỊNH KỲ (EVENTS)
-- ==========================================
SET GLOBAL event_scheduler = ON;

DELIMITER $$

CREATE EVENT ev_check_hop_dong_het_han
ON SCHEDULE EVERY 1 DAY
DO
BEGIN
    CALL sp_check_hop_dong_het_han();
END $$

CREATE EVENT ev_auto_create_hoa_don
ON SCHEDULE EVERY 1 MONTH
DO
BEGIN
    CALL sp_auto_create_hoa_don();
END $$

DELIMITER ;


-- ==========================================
-- 7. DỮ LIỆU MẪU (SEED DATA)
-- ==========================================
SET NAMES utf8mb4;

INSERT INTO PHONG(dien_tich, gia_thue, mo_ta, hinh_anh) VALUES
(20, 2000000, 'Phòng tầng 1, ban công hướng đông', 'p01.jpg'),
(22, 2200000, 'Phòng tầng 1, gần cầu thang',        'p02.jpg'),
(18, 1800000, 'Phòng nhỏ tiết kiệm',                 'p03.jpg'),
(25, 2500000, 'Phòng rộng có gác lửng',              'p04.jpg'),
(20, 2000000, 'Phòng tầng 2, thoáng mát',            'p05.jpg'),
(24, 2400000, 'Phòng góc nhiều cửa sổ',              'p06.jpg'),
(20, 2100000, 'Phòng tầng 3 yên tĩnh',               'p07.jpg'),
(28, 2800000, 'Phòng VIP có điều hòa',               'p08.jpg'),
(19, 1900000, 'Phòng vừa cho sinh viên',             'p09.jpg'),
(21, 2150000, 'Phòng tầng 2 hướng tây',              'p10.jpg'),
(23, 2300000, 'Phòng đang sửa chữa',                 'p11.jpg'),
(26, 2600000, 'Phòng cuối hành lang',                'p12.jpg');

INSERT INTO NGUOI_THUE(ho_ten, so_dien_thoai, cccd) VALUES
('Nguyễn Văn An',   '0901000001', '079200000001'),
('Trần Thị Bình',   '0901000002', '079200000002'),
('Lê Hoàng Cường',  '0901000003', '079200000003'),
('Phạm Thị Dung',   '0901000004', '079200000004'),
('Hoàng Văn Em',    '0901000005', '079200000005'),
('Vũ Thị Phương',   '0901000006', '079200000006'),
('Đặng Văn Giang',  '0901000007', '079200000007'),
('Bùi Thị Hoa',     '0901000008', '079200000008'),
('Đỗ Văn Inh',      '0901000009', '079200000009'),
('Ngô Thị Kim',     '0901000010', '079200000010'),
('Dương Văn Long',  '0901000011', '079200000011'),
('Lý Thị Mai',      '0901000012', '079200000012'),
('Phan Văn Nam',    '0901000013', '079200000013'),
('Trương Thị Oanh', '0901000014', '079200000014'),
('Mai Văn Phú',     '0901000015', '079200000015'),
('Hồ Thị Quỳnh',    '0901000016', '079200000016'),
('Khách Demo',      '0901234567', '079123456789');

INSERT INTO DICH_VU(ten_dich_vu, don_gia, don_vi) VALUES
('Điện',     3500,   'kWh'),
('Nước',     15000,  'm3'),
('Internet', 100000, 'tháng'),
('Gửi xe',   80000,  'xe/tháng'),
('Vệ sinh',  50000,  'tháng');

INSERT INTO HOP_DONG(ma_phong, ngay_bat_dau, ngay_ket_thuc, tien_coc) VALUES
(1, '2024-01-01', '2025-12-31', 2000000),
(2, '2024-03-01', '2025-12-31', 2200000),
(3, '2024-06-15', '2025-12-31', 1800000),
(4, '2023-09-01', '2025-12-31', 2500000),
(5, '2024-02-10', '2025-12-31', 2000000),
(6, '2024-05-20', '2025-12-31', 2400000),
(7, '2024-08-01', '2025-12-31', 2100000),
(8, '2023-01-01', '2025-12-31', 2800000);

UPDATE PHONG SET trang_thai = 'Bao tri' WHERE ma_phong IN (10, 11);

INSERT INTO HOP_DONG_NGUOI_THUE(ma_hop_dong, ma_nguoi_thue) VALUES
(1, 1), (1, 2),
(2, 3),
(3, 4),
(4, 5), (4, 6),
(5, 7),
(6, 8),
(7, 9),
(8, 10), (8, 11);

INSERT INTO HOP_DONG_DICH_VU(ma_hop_dong, ma_dich_vu) VALUES
(1,1),(1,2),(1,3),
(2,1),(2,2),
(3,1),(3,2),(3,4),
(4,1),(4,2),(4,3),(4,4),
(5,1),(5,2),
(6,1),(6,2),(6,3),
(7,1),(7,2),
(8,1),(8,2),(8,3),(8,4),(8,5);

INSERT INTO HOA_DON(ma_hop_dong, thang, nam, tong_tien, trang_thai) VALUES
(1, 1, 2025, 2500000, 'Da thanh toan'),
(1, 2, 2025, 2550000, 'Da thanh toan'),
(1, 3, 2025, 2600000, 'Chua thanh toan'),
(2, 1, 2025, 2300000, 'Da thanh toan'),
(2, 2, 2025, 2350000, 'Chua thanh toan'),
(3, 1, 2025, 2000000, 'Da thanh toan'),
(3, 2, 2025, 2050000, 'Da thanh toan'),
(3, 3, 2025, 2100000, 'Chua thanh toan'),
(4, 1, 2025, 3000000, 'Da thanh toan'),
(4, 2, 2025, 3050000, 'Da thanh toan'),
(4, 3, 2025, 3100000, 'Da thanh toan'),
(5, 1, 2025, 2200000, 'Chua thanh toan'),
(5, 2, 2025, 2250000, 'Da thanh toan'),
(6, 1, 2025, 2600000, 'Da thanh toan'),
(6, 2, 2025, 2650000, 'Chua thanh toan'),
(7, 1, 2025, 2300000, 'Da thanh toan'),
(7, 2, 2025, 2350000, 'Da thanh toan'),
(8, 1, 2025, 3200000, 'Da thanh toan'),
(8, 2, 2025, 3250000, 'Da thanh toan'),
(8, 3, 2025, 3300000, 'Chua thanh toan'),
(8, 4, 2025, 3350000, 'Da thanh toan'),
(2, 3, 2025, 2400000, 'Chua thanh toan');

INSERT INTO CHI_TIET_HOA_DON(ma_hoa_don, ma_dich_vu, so_luong, thanh_tien) VALUES
(1, 1, 100, 350000),
(1, 2, 8,   120000),
(1, 3, 1,   100000),
(2, 1, 110, 385000),
(2, 2, 9,   135000),
(2, 3, 1,   100000);

INSERT INTO BAO_TRI(ma_phong, loai_bao_tri, chi_phi, ngay_bao_tri) VALUES
(10, 'Sửa đường ống nước',  500000, '2025-03-10'),
(11, 'Sơn lại tường',       800000, '2025-03-15'),
(4,  'Thay bóng đèn',       150000, '2025-02-20');

INSERT INTO SU_CO_AN_NINH(mo_ta, ngay_xay_ra) VALUES
('Mất trộm xe máy ở sân để xe', '2025-01-15'),
('Chập điện hành lang tầng 2',  '2025-02-05');

INSERT INTO SU_CO_PHONG(ma_su_co, ma_phong) VALUES
(1, 1), (2, 5), (2, 6);

INSERT INTO NOI_QUY(noi_dung) VALUES
('Giữ gìn vệ sinh chung, đổ rác đúng nơi quy định.'),
('Không gây ồn ào sau 22 giờ.'),
('Đóng tiền phòng trước ngày 05 hàng tháng.');

INSERT INTO PHAN_HOI(ma_nguoi_thue, noi_dung, loai) VALUES
(1, 'Đề nghị sửa vòi nước bị rò rỉ.', 'Bảo trì'),
(3, 'Wifi yếu vào buổi tối.',          'Dịch vụ'),
(5, 'Xin thêm chỗ để xe.',             'Khác');
