CREATE DATABASE IF NOT EXISTS quan_ly_phong_tro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quan_ly_phong_tro;

SET FOREIGN_KEY_CHECKS = 0;
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
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE PHONG (
    ma_phong INT AUTO_INCREMENT PRIMARY KEY,
    dien_tich FLOAT,
    gia_thue DECIMAL(12,2),
    trang_thai VARCHAR(20) DEFAULT 'Trong',
    mo_ta TEXT,
    hinh_anh VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE NGUOI_THUE (
    ma_nguoi_thue INT AUTO_INCREMENT PRIMARY KEY,
    ho_ten VARCHAR(100),
    so_dien_thoai VARCHAR(15),
    cccd VARCHAR(20)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE DICH_VU (
    ma_dich_vu INT AUTO_INCREMENT PRIMARY KEY,
    ten_dich_vu VARCHAR(50),
    don_gia DECIMAL(10,2),
    don_vi VARCHAR(20)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE HOP_DONG (
    ma_hop_dong INT AUTO_INCREMENT PRIMARY KEY,
    ma_phong INT,
    ngay_bat_dau DATE,
    ngay_ket_thuc DATE,
    tien_coc DECIMAL(12,2),
    trang_thai VARCHAR(20) DEFAULT 'Dang thue',
    FOREIGN KEY (ma_phong) REFERENCES PHONG(ma_phong)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE HOP_DONG_NGUOI_THUE (
    ma_hop_dong INT,
    ma_nguoi_thue INT,
    PRIMARY KEY (ma_hop_dong, ma_nguoi_thue),
    FOREIGN KEY (ma_hop_dong) REFERENCES HOP_DONG(ma_hop_dong),
    FOREIGN KEY (ma_nguoi_thue) REFERENCES NGUOI_THUE(ma_nguoi_thue)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE HOP_DONG_DICH_VU (
    ma_hop_dong INT,
    ma_dich_vu INT,
    ngay_dang_ky DATE DEFAULT (CURRENT_DATE),
    PRIMARY KEY (ma_hop_dong, ma_dich_vu),
    FOREIGN KEY (ma_hop_dong) REFERENCES HOP_DONG(ma_hop_dong) ON DELETE CASCADE,
    FOREIGN KEY (ma_dich_vu) REFERENCES DICH_VU(ma_dich_vu) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE HOA_DON (
    ma_hoa_don INT AUTO_INCREMENT PRIMARY KEY,
    ma_hop_dong INT,
    thang INT,
    nam INT,
    tong_tien DECIMAL(12,2) DEFAULT 0,
    trang_thai VARCHAR(30) DEFAULT 'Chua thanh toan',
    FOREIGN KEY (ma_hop_dong) REFERENCES HOP_DONG(ma_hop_dong)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE CHI_TIET_HOA_DON (
    ma_ct INT AUTO_INCREMENT PRIMARY KEY,
    ma_hoa_don INT,
    ma_dich_vu INT,
    so_luong FLOAT,
    thanh_tien DECIMAL(12,2),
    FOREIGN KEY (ma_hoa_don) REFERENCES HOA_DON(ma_hoa_don),
    FOREIGN KEY (ma_dich_vu) REFERENCES DICH_VU(ma_dich_vu)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE BAO_TRI (
    ma_bao_tri INT AUTO_INCREMENT PRIMARY KEY,
    ma_phong INT,
    loai_bao_tri VARCHAR(100),
    chi_phi DECIMAL(12,2),
    ngay_bao_tri DATE,
    FOREIGN KEY (ma_phong) REFERENCES PHONG(ma_phong)
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
    FOREIGN KEY (ma_su_co) REFERENCES SU_CO_AN_NINH(ma_su_co),
    FOREIGN KEY (ma_phong) REFERENCES PHONG(ma_phong)
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
    FOREIGN KEY (ma_nguoi_thue) REFERENCES NGUOI_THUE(ma_nguoi_thue)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TRIGGER IF EXISTS trg_hop_dong_insert;
DROP TRIGGER IF EXISTS trg_hop_dong_update;

DELIMITER $$
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
DELIMITER ;
