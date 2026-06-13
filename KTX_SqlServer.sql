IF DB_ID(N'quan_ly_phong_tro') IS NULL
    CREATE DATABASE quan_ly_phong_tro;
GO

USE quan_ly_phong_tro;
GO

IF OBJECT_ID(N'vw_phong_dang_thue', N'V')   IS NOT NULL DROP VIEW vw_phong_dang_thue;
IF OBJECT_ID(N'vw_phong_trong', N'V')        IS NOT NULL DROP VIEW vw_phong_trong;
IF OBJECT_ID(N'vw_doanh_thu_thang', N'V')    IS NOT NULL DROP VIEW vw_doanh_thu_thang;
IF OBJECT_ID(N'vw_cong_no_khach_thue', N'V') IS NOT NULL DROP VIEW vw_cong_no_khach_thue;
GO

IF OBJECT_ID(N'sp_add_phong', N'P')               IS NOT NULL DROP PROCEDURE sp_add_phong;
IF OBJECT_ID(N'sp_update_phong', N'P')            IS NOT NULL DROP PROCEDURE sp_update_phong;
IF OBJECT_ID(N'sp_delete_phong', N'P')            IS NOT NULL DROP PROCEDURE sp_delete_phong;
IF OBJECT_ID(N'sp_add_nguoi_thue', N'P')          IS NOT NULL DROP PROCEDURE sp_add_nguoi_thue;
IF OBJECT_ID(N'sp_update_nguoi_thue', N'P')       IS NOT NULL DROP PROCEDURE sp_update_nguoi_thue;
IF OBJECT_ID(N'sp_delete_nguoi_thue', N'P')       IS NOT NULL DROP PROCEDURE sp_delete_nguoi_thue;
IF OBJECT_ID(N'sp_create_hop_dong', N'P')         IS NOT NULL DROP PROCEDURE sp_create_hop_dong;
IF OBJECT_ID(N'sp_create_hoa_don', N'P')          IS NOT NULL DROP PROCEDURE sp_create_hoa_don;
IF OBJECT_ID(N'sp_thue_phong', N'P')              IS NOT NULL DROP PROCEDURE sp_thue_phong;
IF OBJECT_ID(N'sp_tra_phong', N'P')               IS NOT NULL DROP PROCEDURE sp_tra_phong;
IF OBJECT_ID(N'sp_lap_hoa_don', N'P')             IS NOT NULL DROP PROCEDURE sp_lap_hoa_don;
IF OBJECT_ID(N'sp_thanh_toan_hoa_don', N'P')      IS NOT NULL DROP PROCEDURE sp_thanh_toan_hoa_don;
IF OBJECT_ID(N'sp_thong_ke_doanh_thu_thang', N'P')IS NOT NULL DROP PROCEDURE sp_thong_ke_doanh_thu_thang;
IF OBJECT_ID(N'sp_check_hop_dong_het_han', N'P')  IS NOT NULL DROP PROCEDURE sp_check_hop_dong_het_han;
IF OBJECT_ID(N'sp_auto_create_hoa_don', N'P')     IS NOT NULL DROP PROCEDURE sp_auto_create_hoa_don;
IF OBJECT_ID(N'sp_dashboard', N'P')               IS NOT NULL DROP PROCEDURE sp_dashboard;
GO

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
GO

CREATE TABLE PHONG (
    ma_phong   INT IDENTITY(1,1) PRIMARY KEY,
    dien_tich  FLOAT,
    gia_thue   DECIMAL(12,2) NOT NULL,
    trang_thai NVARCHAR(20)  NOT NULL DEFAULT N'Trong',
    mo_ta      NVARCHAR(MAX),
    hinh_anh   NVARCHAR(255),
    CONSTRAINT CK_PHONG_trang_thai CHECK (trang_thai IN (N'Trong', N'Da thue', N'Bao tri')),
    CONSTRAINT CK_PHONG_gia_thue   CHECK (gia_thue >= 0)
);
GO

CREATE TABLE NGUOI_THUE (
    ma_nguoi_thue INT IDENTITY(1,1) PRIMARY KEY,
    ho_ten        NVARCHAR(100) NOT NULL,
    so_dien_thoai NVARCHAR(15),
    cccd          NVARCHAR(20),
    CONSTRAINT UQ_NGUOI_THUE_cccd UNIQUE (cccd)
);
GO

CREATE TABLE DICH_VU (
    ma_dich_vu  INT IDENTITY(1,1) PRIMARY KEY,
    ten_dich_vu NVARCHAR(50) NOT NULL,
    don_gia     DECIMAL(10,2),
    don_vi      NVARCHAR(20),
    CONSTRAINT CK_DICH_VU_don_gia CHECK (don_gia >= 0)
);
GO

CREATE TABLE HOP_DONG (
    ma_hop_dong   INT IDENTITY(1,1) PRIMARY KEY,
    ma_phong      INT NOT NULL,
    ngay_bat_dau  DATE,
    ngay_ket_thuc DATE,
    tien_coc      DECIMAL(12,2),
    trang_thai    NVARCHAR(20) NOT NULL DEFAULT N'Dang thue',
    CONSTRAINT FK_HOP_DONG_PHONG FOREIGN KEY (ma_phong) REFERENCES PHONG(ma_phong),
    CONSTRAINT CK_HOP_DONG_trang_thai CHECK (trang_thai IN (N'Dang thue', N'Het han', N'Huy')),
    CONSTRAINT CK_HOP_DONG_tien_coc   CHECK (tien_coc >= 0)
);
GO

CREATE TABLE HOP_DONG_NGUOI_THUE (
    ma_hop_dong   INT,
    ma_nguoi_thue INT,
    PRIMARY KEY (ma_hop_dong, ma_nguoi_thue),
    CONSTRAINT FK_HDNT_HOP_DONG   FOREIGN KEY (ma_hop_dong)   REFERENCES HOP_DONG(ma_hop_dong),
    CONSTRAINT FK_HDNT_NGUOI_THUE FOREIGN KEY (ma_nguoi_thue) REFERENCES NGUOI_THUE(ma_nguoi_thue)
);
GO

CREATE TABLE HOP_DONG_DICH_VU (
    ma_hop_dong INT,
    ma_dich_vu  INT,
    ngay_dang_ky DATE DEFAULT CAST(GETDATE() AS DATE),
    PRIMARY KEY (ma_hop_dong, ma_dich_vu),
    CONSTRAINT FK_HDDV_HOP_DONG FOREIGN KEY (ma_hop_dong) REFERENCES HOP_DONG(ma_hop_dong) ON DELETE CASCADE,
    CONSTRAINT FK_HDDV_DICH_VU  FOREIGN KEY (ma_dich_vu)  REFERENCES DICH_VU(ma_dich_vu)  ON DELETE CASCADE
);
GO

CREATE TABLE HOA_DON (
    ma_hoa_don  INT IDENTITY(1,1) PRIMARY KEY,
    ma_hop_dong INT,
    thang       INT,
    nam         INT,
    tong_tien   DECIMAL(12,2) DEFAULT 0,
    trang_thai  NVARCHAR(30) NOT NULL DEFAULT N'Chua thanh toan',
    CONSTRAINT FK_HOA_DON_HOP_DONG FOREIGN KEY (ma_hop_dong) REFERENCES HOP_DONG(ma_hop_dong),
    CONSTRAINT CK_HOA_DON_trang_thai CHECK (trang_thai IN (N'Chua thanh toan', N'Da thanh toan')),
    CONSTRAINT CK_HOA_DON_thang     CHECK (thang BETWEEN 1 AND 12),
    CONSTRAINT CK_HOA_DON_tong_tien CHECK (tong_tien >= 0)
);
GO

CREATE TABLE CHI_TIET_HOA_DON (
    ma_ct      INT IDENTITY(1,1) PRIMARY KEY,
    ma_hoa_don INT,
    ma_dich_vu INT,
    so_luong   FLOAT,
    thanh_tien DECIMAL(12,2),
    CONSTRAINT FK_CTHD_HOA_DON FOREIGN KEY (ma_hoa_don) REFERENCES HOA_DON(ma_hoa_don),
    CONSTRAINT FK_CTHD_DICH_VU FOREIGN KEY (ma_dich_vu) REFERENCES DICH_VU(ma_dich_vu),
    CONSTRAINT CK_CTHD_thanh_tien CHECK (thanh_tien >= 0)
);
GO

CREATE TABLE BAO_TRI (
    ma_bao_tri   INT IDENTITY(1,1) PRIMARY KEY,
    ma_phong     INT,
    loai_bao_tri NVARCHAR(100),
    chi_phi      DECIMAL(12,2),
    ngay_bao_tri DATE,
    CONSTRAINT FK_BAO_TRI_PHONG FOREIGN KEY (ma_phong) REFERENCES PHONG(ma_phong)
);
GO

CREATE TABLE SU_CO_AN_NINH (
    ma_su_co    INT IDENTITY(1,1) PRIMARY KEY,
    mo_ta       NVARCHAR(MAX),
    ngay_xay_ra DATE
);
GO

CREATE TABLE SU_CO_PHONG (
    ma_su_co INT,
    ma_phong INT,
    PRIMARY KEY (ma_su_co, ma_phong),
    CONSTRAINT FK_SCP_SU_CO FOREIGN KEY (ma_su_co) REFERENCES SU_CO_AN_NINH(ma_su_co),
    CONSTRAINT FK_SCP_PHONG FOREIGN KEY (ma_phong) REFERENCES PHONG(ma_phong)
);
GO

CREATE TABLE NOI_QUY (
    ma_noi_quy INT IDENTITY(1,1) PRIMARY KEY,
    noi_dung   NVARCHAR(MAX)
);
GO

CREATE TABLE PHAN_HOI (
    ma_phan_hoi   INT IDENTITY(1,1) PRIMARY KEY,
    ma_nguoi_thue INT,
    noi_dung      NVARCHAR(MAX),
    loai          NVARCHAR(50),
    trang_thai    NVARCHAR(30) DEFAULT N'Chua xu ly',
    CONSTRAINT FK_PHAN_HOI_NGUOI_THUE FOREIGN KEY (ma_nguoi_thue) REFERENCES NGUOI_THUE(ma_nguoi_thue)
);
GO

CREATE TABLE AuditLog (
    ma_log        INT IDENTITY(1,1) PRIMARY KEY,
    bang_tac_dong NVARCHAR(50),
    hanh_dong     NVARCHAR(20),
    khoa_chinh    NVARCHAR(50),
    mo_ta         NVARCHAR(MAX),
    thoi_gian     DATETIME DEFAULT GETDATE()
);
GO
CREATE PROCEDURE sp_add_phong
    @p_dien_tich FLOAT,
    @p_gia_thue  DECIMAL(12,2),
    @p_hinh_anh  NVARCHAR(255),
    @p_mo_ta     NVARCHAR(MAX)
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        INSERT INTO PHONG(dien_tich, gia_thue, hinh_anh, mo_ta)
        VALUES(@p_dien_tich, @p_gia_thue, @p_hinh_anh, @p_mo_ta);
        PRINT N'Them phong thanh cong. Ma phong moi = ' + CAST(SCOPE_IDENTITY() AS NVARCHAR(20));
    END TRY
    BEGIN CATCH
        PRINT N'Loi sp_add_phong: ' + ERROR_MESSAGE();
        THROW;
    END CATCH
END
GO

CREATE PROCEDURE sp_update_phong
    @p_ma_phong  INT,
    @p_dien_tich FLOAT,
    @p_gia_thue  DECIMAL(12,2),
    @p_trang_thai NVARCHAR(20),
    @p_hinh_anh  NVARCHAR(255),
    @p_mo_ta     NVARCHAR(MAX)
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        IF NOT EXISTS (SELECT 1 FROM PHONG WHERE ma_phong = @p_ma_phong)
            THROW 50001, N'Khong tim thay phong can cap nhat.', 1;

        UPDATE PHONG
        SET dien_tich = @p_dien_tich,
            gia_thue  = @p_gia_thue,
            trang_thai= @p_trang_thai,
            hinh_anh  = @p_hinh_anh,
            mo_ta     = @p_mo_ta
        WHERE ma_phong = @p_ma_phong;
        PRINT N'Cap nhat phong thanh cong.';
    END TRY
    BEGIN CATCH
        PRINT N'Loi sp_update_phong: ' + ERROR_MESSAGE();
        THROW;
    END CATCH
END
GO

CREATE PROCEDURE sp_delete_phong
    @p_ma_phong INT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        IF EXISTS (SELECT 1 FROM HOP_DONG
                   WHERE ma_phong = @p_ma_phong AND trang_thai = N'Dang thue')
            THROW 50002, N'Khong the xoa: phong dang co hop dong thue hieu luc.', 1;

        DELETE FROM PHONG WHERE ma_phong = @p_ma_phong;
        PRINT N'Xoa phong thanh cong.';
    END TRY
    BEGIN CATCH
        PRINT N'Loi sp_delete_phong: ' + ERROR_MESSAGE();
        THROW;
    END CATCH
END
GO

CREATE PROCEDURE sp_add_nguoi_thue
    @p_ho_ten NVARCHAR(100),
    @p_sdt    NVARCHAR(15),
    @p_cccd   NVARCHAR(20)
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        INSERT INTO NGUOI_THUE(ho_ten, so_dien_thoai, cccd)
        VALUES(@p_ho_ten, @p_sdt, @p_cccd);
        PRINT N'Them khach thue thanh cong. Ma = ' + CAST(SCOPE_IDENTITY() AS NVARCHAR(20));
    END TRY
    BEGIN CATCH
        PRINT N'Loi sp_add_nguoi_thue: ' + ERROR_MESSAGE();
        THROW;
    END CATCH
END
GO

CREATE PROCEDURE sp_update_nguoi_thue
    @p_ma_nguoi_thue INT,
    @p_ho_ten NVARCHAR(100),
    @p_sdt    NVARCHAR(15),
    @p_cccd   NVARCHAR(20)
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        IF NOT EXISTS (SELECT 1 FROM NGUOI_THUE WHERE ma_nguoi_thue = @p_ma_nguoi_thue)
            THROW 50003, N'Khong tim thay khach thue can cap nhat.', 1;

        UPDATE NGUOI_THUE
        SET ho_ten = @p_ho_ten,
            so_dien_thoai = @p_sdt,
            cccd = @p_cccd
        WHERE ma_nguoi_thue = @p_ma_nguoi_thue;
        PRINT N'Cap nhat khach thue thanh cong.';
    END TRY
    BEGIN CATCH
        PRINT N'Loi sp_update_nguoi_thue: ' + ERROR_MESSAGE();
        THROW;
    END CATCH
END
GO

CREATE PROCEDURE sp_delete_nguoi_thue
    @p_ma_nguoi_thue INT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        IF EXISTS (SELECT 1 FROM HOP_DONG_NGUOI_THUE WHERE ma_nguoi_thue = @p_ma_nguoi_thue)
            THROW 50004, N'Khong the xoa: khach thue dang gan voi hop dong.', 1;

        DELETE FROM PHAN_HOI WHERE ma_nguoi_thue = @p_ma_nguoi_thue;
        DELETE FROM NGUOI_THUE WHERE ma_nguoi_thue = @p_ma_nguoi_thue;
        PRINT N'Xoa khach thue thanh cong.';
    END TRY
    BEGIN CATCH
        PRINT N'Loi sp_delete_nguoi_thue: ' + ERROR_MESSAGE();
        THROW;
    END CATCH
END
GO

CREATE PROCEDURE sp_create_hop_dong
    @p_ma_phong INT,
    @p_ngay_bd  DATE,
    @p_ngay_kt  DATE,
    @p_tien_coc DECIMAL(12,2)
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        INSERT INTO HOP_DONG(ma_phong, ngay_bat_dau, ngay_ket_thuc, tien_coc)
        VALUES(@p_ma_phong, @p_ngay_bd, @p_ngay_kt, @p_tien_coc);
        PRINT N'Tao hop dong thanh cong. Ma hop dong = ' + CAST(SCOPE_IDENTITY() AS NVARCHAR(20));
    END TRY
    BEGIN CATCH
        PRINT N'Loi sp_create_hop_dong: ' + ERROR_MESSAGE();
        THROW;
    END CATCH
END
GO

CREATE PROCEDURE sp_create_hoa_don
    @p_ma_hop_dong INT,
    @p_thang INT,
    @p_nam   INT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        INSERT INTO HOA_DON(ma_hop_dong, thang, nam)
        VALUES(@p_ma_hop_dong, @p_thang, @p_nam);
        PRINT N'Tao hoa don thanh cong. Ma hoa don = ' + CAST(SCOPE_IDENTITY() AS NVARCHAR(20));
    END TRY
    BEGIN CATCH
        PRINT N'Loi sp_create_hoa_don: ' + ERROR_MESSAGE();
        THROW;
    END CATCH
END
GO

CREATE PROCEDURE sp_thue_phong
    @p_ma_phong      INT,
    @p_ma_nguoi_thue INT,
    @p_ngay_bd       DATE,
    @p_ngay_kt       DATE,
    @p_tien_coc      DECIMAL(12,2)
AS
BEGIN
    SET NOCOUNT ON;
    DECLARE @ma_hop_dong INT;
    BEGIN TRY
        BEGIN TRANSACTION;
            INSERT INTO HOP_DONG(ma_phong, ngay_bat_dau, ngay_ket_thuc, tien_coc)
            VALUES(@p_ma_phong, @p_ngay_bd, @p_ngay_kt, @p_tien_coc);
            SET @ma_hop_dong = SCOPE_IDENTITY();

            INSERT INTO HOP_DONG_NGUOI_THUE(ma_hop_dong, ma_nguoi_thue)
            VALUES(@ma_hop_dong, @p_ma_nguoi_thue);
        COMMIT TRANSACTION;
        PRINT N'Thue phong thanh cong. Ma hop dong = ' + CAST(@ma_hop_dong AS NVARCHAR(20));
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK TRANSACTION;
        PRINT N'Loi sp_thue_phong (da ROLLBACK): ' + ERROR_MESSAGE();
        THROW;
    END CATCH
END
GO

CREATE PROCEDURE sp_tra_phong
    @p_ma_hop_dong INT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
            IF NOT EXISTS (SELECT 1 FROM HOP_DONG WHERE ma_hop_dong = @p_ma_hop_dong)
                THROW 50005, N'Khong tim thay hop dong.', 1;

            UPDATE HOP_DONG
            SET trang_thai = N'Het han'
            WHERE ma_hop_dong = @p_ma_hop_dong;
        COMMIT TRANSACTION;
        PRINT N'Tra phong thanh cong.';
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK TRANSACTION;
        PRINT N'Loi sp_tra_phong (da ROLLBACK): ' + ERROR_MESSAGE();
        THROW;
    END CATCH
END
GO

CREATE PROCEDURE sp_lap_hoa_don
    @p_ma_hop_dong INT,
    @p_thang INT,
    @p_nam   INT
AS
BEGIN
    SET NOCOUNT ON;
    DECLARE @ma_hoa_don INT;
    BEGIN TRY
        BEGIN TRANSACTION;
            IF NOT EXISTS (SELECT 1 FROM HOP_DONG WHERE ma_hop_dong = @p_ma_hop_dong)
                THROW 50006, N'Khong tim thay hop dong de lap hoa don.', 1;

            INSERT INTO HOA_DON(ma_hop_dong, thang, nam, tong_tien)
            VALUES(@p_ma_hop_dong, @p_thang, @p_nam, 0);
            SET @ma_hoa_don = SCOPE_IDENTITY();

            INSERT INTO CHI_TIET_HOA_DON(ma_hoa_don, ma_dich_vu, so_luong, thanh_tien)
            SELECT @ma_hoa_don, dv.ma_dich_vu, 1, dv.don_gia * 1
            FROM HOP_DONG_DICH_VU hddv
            INNER JOIN DICH_VU dv ON dv.ma_dich_vu = hddv.ma_dich_vu
            WHERE hddv.ma_hop_dong = @p_ma_hop_dong;

            UPDATE HOA_DON
            SET tong_tien = ISNULL((SELECT SUM(thanh_tien) FROM CHI_TIET_HOA_DON WHERE ma_hoa_don = @ma_hoa_don), 0)
            WHERE ma_hoa_don = @ma_hoa_don;
        COMMIT TRANSACTION;
        PRINT N'Lap hoa don thanh cong. Ma hoa don = ' + CAST(@ma_hoa_don AS NVARCHAR(20));
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK TRANSACTION;
        PRINT N'Loi sp_lap_hoa_don (da ROLLBACK): ' + ERROR_MESSAGE();
        THROW;
    END CATCH
END
GO

CREATE PROCEDURE sp_thanh_toan_hoa_don
    @p_ma_hoa_don INT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
            IF NOT EXISTS (SELECT 1 FROM HOA_DON WHERE ma_hoa_don = @p_ma_hoa_don)
                THROW 50007, N'Khong tim thay hoa don.', 1;

            IF EXISTS (SELECT 1 FROM HOA_DON WHERE ma_hoa_don = @p_ma_hoa_don AND trang_thai = N'Da thanh toan')
                THROW 50008, N'Hoa don nay da duoc thanh toan truoc do.', 1;

            UPDATE HOA_DON
            SET trang_thai = N'Da thanh toan'
            WHERE ma_hoa_don = @p_ma_hoa_don;
        COMMIT TRANSACTION;
        PRINT N'Thanh toan hoa don thanh cong.';
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK TRANSACTION;
        PRINT N'Loi sp_thanh_toan_hoa_don (da ROLLBACK): ' + ERROR_MESSAGE();
        THROW;
    END CATCH
END
GO

CREATE PROCEDURE sp_thong_ke_doanh_thu_thang
    @thang INT,
    @nam   INT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        SELECT @thang AS thang,
               @nam   AS nam,
               COUNT(*) AS so_hoa_don,
               ISNULL(SUM(tong_tien), 0) AS doanh_thu
        FROM HOA_DON
        WHERE thang = @thang AND nam = @nam AND trang_thai = N'Da thanh toan';
    END TRY
    BEGIN CATCH
        PRINT N'Loi sp_thong_ke_doanh_thu_thang: ' + ERROR_MESSAGE();
        THROW;
    END CATCH
END
GO

CREATE PROCEDURE sp_check_hop_dong_het_han
AS
BEGIN
    SET NOCOUNT ON;
    UPDATE HOP_DONG
    SET trang_thai = N'Het han'
    WHERE ngay_ket_thuc < CAST(GETDATE() AS DATE)
      AND trang_thai = N'Dang thue';
    PRINT N'Da cap nhat cac hop dong het han: ' + CAST(@@ROWCOUNT AS NVARCHAR(20));
END
GO

CREATE PROCEDURE sp_auto_create_hoa_don
AS
BEGIN
    SET NOCOUNT ON;
    INSERT INTO HOA_DON(ma_hop_dong, thang, nam)
    SELECT ma_hop_dong, MONTH(GETDATE()), YEAR(GETDATE())
    FROM HOP_DONG
    WHERE trang_thai = N'Dang thue';
    PRINT N'Da tao hoa don tu dong: ' + CAST(@@ROWCOUNT AS NVARCHAR(20));
END
GO

CREATE PROCEDURE sp_dashboard
AS
BEGIN
    SET NOCOUNT ON;
    SELECT N'Tong so phong' AS chi_tieu, COUNT(*) AS gia_tri FROM PHONG
    UNION ALL
    SELECT N'So phong dang thue', COUNT(*) FROM PHONG WHERE trang_thai = N'Da thue'
    UNION ALL
    SELECT N'So phong trong', COUNT(*) FROM PHONG WHERE trang_thai = N'Trong'
    UNION ALL
    SELECT N'So phong bao tri', COUNT(*) FROM PHONG WHERE trang_thai = N'Bao tri'
    UNION ALL
    SELECT N'Tong doanh thu (da thanh toan)', ISNULL(SUM(tong_tien),0) FROM HOA_DON WHERE trang_thai = N'Da thanh toan';
END
GO
CREATE TRIGGER trg_hopdong_insert
ON HOP_DONG
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;
    UPDATE p
    SET p.trang_thai = N'Da thue'
    FROM PHONG p
    INNER JOIN INSERTED i ON p.ma_phong = i.ma_phong;
END
GO

CREATE TRIGGER trg_hopdong_tra_phong
ON HOP_DONG
AFTER UPDATE
AS
BEGIN
    SET NOCOUNT ON;
    UPDATE p
    SET p.trang_thai = N'Trong'
    FROM PHONG p
    INNER JOIN INSERTED i ON p.ma_phong = i.ma_phong
    WHERE i.trang_thai IN (N'Het han', N'Huy');
END
GO

CREATE TRIGGER trg_hoadon_insert_log
ON HOA_DON
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;
    INSERT INTO AuditLog(bang_tac_dong, hanh_dong, khoa_chinh, mo_ta)
    SELECT N'HOA_DON', N'INSERT',
           CAST(i.ma_hoa_don AS NVARCHAR(50)),
           N'Lap hoa don thang ' + CAST(i.thang AS NVARCHAR(10)) + N'/' + CAST(i.nam AS NVARCHAR(10))
           + N' cho hop dong ' + CAST(i.ma_hop_dong AS NVARCHAR(20))
    FROM INSERTED i;
END
GO

CREATE TRIGGER trg_hoadon_thanhtoan
ON HOA_DON
AFTER UPDATE
AS
BEGIN
    SET NOCOUNT ON;
    INSERT INTO AuditLog(bang_tac_dong, hanh_dong, khoa_chinh, mo_ta)
    SELECT N'HOA_DON', N'UPDATE',
           CAST(i.ma_hoa_don AS NVARCHAR(50)),
           N'Thanh toan hoa don, so tien = ' + CAST(i.tong_tien AS NVARCHAR(30))
    FROM INSERTED i
    INNER JOIN DELETED d ON i.ma_hoa_don = d.ma_hoa_don
    WHERE i.trang_thai = N'Da thanh toan' AND d.trang_thai <> N'Da thanh toan';
END
GO

CREATE TRIGGER trg_phong_audit
ON PHONG
AFTER INSERT, UPDATE, DELETE
AS
BEGIN
    SET NOCOUNT ON;
    DECLARE @hanh_dong NVARCHAR(20);
    IF EXISTS (SELECT 1 FROM INSERTED) AND EXISTS (SELECT 1 FROM DELETED)
        SET @hanh_dong = N'UPDATE';
    ELSE IF EXISTS (SELECT 1 FROM INSERTED)
        SET @hanh_dong = N'INSERT';
    ELSE
        SET @hanh_dong = N'DELETE';

    IF @hanh_dong IN (N'INSERT', N'UPDATE')
        INSERT INTO AuditLog(bang_tac_dong, hanh_dong, khoa_chinh, mo_ta)
        SELECT N'PHONG', @hanh_dong, CAST(i.ma_phong AS NVARCHAR(50)),
               N'Trang thai = ' + ISNULL(i.trang_thai, N'(null)')
        FROM INSERTED i;

    IF @hanh_dong = N'DELETE'
        INSERT INTO AuditLog(bang_tac_dong, hanh_dong, khoa_chinh, mo_ta)
        SELECT N'PHONG', @hanh_dong, CAST(d.ma_phong AS NVARCHAR(50)),
               N'Da xoa phong'
        FROM DELETED d;
END
GO

CREATE TRIGGER trg_nguoithue_audit
ON NGUOI_THUE
AFTER INSERT, UPDATE, DELETE
AS
BEGIN
    SET NOCOUNT ON;
    DECLARE @hanh_dong NVARCHAR(20);
    IF EXISTS (SELECT 1 FROM INSERTED) AND EXISTS (SELECT 1 FROM DELETED)
        SET @hanh_dong = N'UPDATE';
    ELSE IF EXISTS (SELECT 1 FROM INSERTED)
        SET @hanh_dong = N'INSERT';
    ELSE
        SET @hanh_dong = N'DELETE';

    IF @hanh_dong IN (N'INSERT', N'UPDATE')
        INSERT INTO AuditLog(bang_tac_dong, hanh_dong, khoa_chinh, mo_ta)
        SELECT N'NGUOI_THUE', @hanh_dong, CAST(i.ma_nguoi_thue AS NVARCHAR(50)),
               N'Khach thue: ' + ISNULL(i.ho_ten, N'(null)')
        FROM INSERTED i;

    IF @hanh_dong = N'DELETE'
        INSERT INTO AuditLog(bang_tac_dong, hanh_dong, khoa_chinh, mo_ta)
        SELECT N'NGUOI_THUE', @hanh_dong, CAST(d.ma_nguoi_thue AS NVARCHAR(50)),
               N'Da xoa khach thue: ' + ISNULL(d.ho_ten, N'(null)')
        FROM DELETED d;
END
GO

CREATE VIEW vw_phong_dang_thue
AS
SELECT p.ma_phong, p.dien_tich, p.gia_thue, p.trang_thai,
       hd.ma_hop_dong, hd.ngay_bat_dau, hd.ngay_ket_thuc,
       nt.ma_nguoi_thue, nt.ho_ten, nt.so_dien_thoai
FROM PHONG p
INNER JOIN HOP_DONG hd ON hd.ma_phong = p.ma_phong AND hd.trang_thai = N'Dang thue'
LEFT JOIN HOP_DONG_NGUOI_THUE hdnt ON hdnt.ma_hop_dong = hd.ma_hop_dong
LEFT JOIN NGUOI_THUE nt ON nt.ma_nguoi_thue = hdnt.ma_nguoi_thue;
GO

CREATE VIEW vw_phong_trong
AS
SELECT ma_phong, dien_tich, gia_thue, trang_thai, mo_ta
FROM PHONG
WHERE trang_thai = N'Trong';
GO

CREATE VIEW vw_doanh_thu_thang
AS
SELECT nam, thang,
       COUNT(*) AS so_hoa_don,
       SUM(tong_tien) AS doanh_thu
FROM HOA_DON
WHERE trang_thai = N'Da thanh toan'
GROUP BY nam, thang;
GO

CREATE VIEW vw_cong_no_khach_thue
AS
SELECT nt.ma_nguoi_thue, nt.ho_ten,
       ISNULL(SUM(hd.tong_tien), 0) AS cong_no
FROM NGUOI_THUE nt
INNER JOIN HOP_DONG_NGUOI_THUE hdnt ON hdnt.ma_nguoi_thue = nt.ma_nguoi_thue
INNER JOIN HOP_DONG h ON h.ma_hop_dong = hdnt.ma_hop_dong
INNER JOIN HOA_DON hd ON hd.ma_hop_dong = h.ma_hop_dong AND hd.trang_thai = N'Chua thanh toan'
GROUP BY nt.ma_nguoi_thue, nt.ho_ten;
GO
INSERT INTO PHONG(dien_tich, gia_thue, mo_ta, hinh_anh) VALUES
(20, 2000000, N'Phòng tầng 1, ban công hướng đông', N'p01.jpg'),
(22, 2200000, N'Phòng tầng 1, gần cầu thang',        N'p02.jpg'),
(18, 1800000, N'Phòng nhỏ tiết kiệm',                 N'p03.jpg'),
(25, 2500000, N'Phòng rộng có gác lửng',              N'p04.jpg'),
(20, 2000000, N'Phòng tầng 2, thoáng mát',            N'p05.jpg'),
(24, 2400000, N'Phòng góc nhiều cửa sổ',              N'p06.jpg'),
(20, 2100000, N'Phòng tầng 3 yên tĩnh',               N'p07.jpg'),
(28, 2800000, N'Phòng VIP có điều hòa',               N'p08.jpg'),
(19, 1900000, N'Phòng vừa cho sinh viên',             N'p09.jpg'),
(21, 2150000, N'Phòng tầng 2 hướng tây',              N'p10.jpg'),
(23, 2300000, N'Phòng đang sửa chữa',                 N'p11.jpg'),
(26, 2600000, N'Phòng cuối hành lang',                N'p12.jpg');
GO

UPDATE PHONG SET trang_thai = N'Bao tri' WHERE ma_phong IN (10, 11);
GO

INSERT INTO NGUOI_THUE(ho_ten, so_dien_thoai, cccd) VALUES
(N'Nguyễn Văn An',   N'0901000001', N'079200000001'),
(N'Trần Thị Bình',   N'0901000002', N'079200000002'),
(N'Lê Hoàng Cường',  N'0901000003', N'079200000003'),
(N'Phạm Thị Dung',   N'0901000004', N'079200000004'),
(N'Hoàng Văn Em',    N'0901000005', N'079200000005'),
(N'Vũ Thị Phương',   N'0901000006', N'079200000006'),
(N'Đặng Văn Giang',  N'0901000007', N'079200000007'),
(N'Bùi Thị Hoa',     N'0901000008', N'079200000008'),
(N'Đỗ Văn Inh',      N'0901000009', N'079200000009'),
(N'Ngô Thị Kim',     N'0901000010', N'079200000010'),
(N'Dương Văn Long',  N'0901000011', N'079200000011'),
(N'Lý Thị Mai',      N'0901000012', N'079200000012'),
(N'Phan Văn Nam',    N'0901000013', N'079200000013'),
(N'Trương Thị Oanh', N'0901000014', N'079200000014'),
(N'Mai Văn Phú',     N'0901000015', N'079200000015'),
(N'Hồ Thị Quỳnh',    N'0901000016', N'079200000016');
GO

INSERT INTO DICH_VU(ten_dich_vu, don_gia, don_vi) VALUES
(N'Điện',     3500,   N'kWh'),
(N'Nước',     15000,  N'm3'),
(N'Internet', 100000, N'tháng'),
(N'Gửi xe',   80000,  N'xe/tháng'),
(N'Vệ sinh',  50000,  N'tháng');
GO

INSERT INTO HOP_DONG(ma_phong, ngay_bat_dau, ngay_ket_thuc, tien_coc) VALUES
(1, '2024-01-01', '2025-12-31', 2000000),
(2, '2024-03-01', '2025-12-31', 2200000),
(3, '2024-06-15', '2025-12-31', 1800000),
(4, '2023-09-01', '2025-12-31', 2500000),
(5, '2024-02-10', '2025-12-31', 2000000),
(6, '2024-05-20', '2025-12-31', 2400000),
(7, '2024-08-01', '2025-12-31', 2100000),
(8, '2023-01-01', '2025-12-31', 2800000);
GO

INSERT INTO HOP_DONG_NGUOI_THUE(ma_hop_dong, ma_nguoi_thue) VALUES
(1, 1), (1, 2),
(2, 3),
(3, 4),
(4, 5), (4, 6),
(5, 7),
(6, 8),
(7, 9),
(8, 10), (8, 11);
GO

INSERT INTO HOP_DONG_DICH_VU(ma_hop_dong, ma_dich_vu) VALUES
(1,1),(1,2),(1,3),
(2,1),(2,2),
(3,1),(3,2),(3,4),
(4,1),(4,2),(4,3),(4,4),
(5,1),(5,2),
(6,1),(6,2),(6,3),
(7,1),(7,2),
(8,1),(8,2),(8,3),(8,4),(8,5);
GO

INSERT INTO HOA_DON(ma_hop_dong, thang, nam, tong_tien, trang_thai) VALUES
(1, 1, 2025, 2500000, N'Da thanh toan'),
(1, 2, 2025, 2550000, N'Da thanh toan'),
(1, 3, 2025, 2600000, N'Chua thanh toan'),
(2, 1, 2025, 2300000, N'Da thanh toan'),
(2, 2, 2025, 2350000, N'Chua thanh toan'),
(3, 1, 2025, 2000000, N'Da thanh toan'),
(3, 2, 2025, 2050000, N'Da thanh toan'),
(3, 3, 2025, 2100000, N'Chua thanh toan'),
(4, 1, 2025, 3000000, N'Da thanh toan'),
(4, 2, 2025, 3050000, N'Da thanh toan'),
(4, 3, 2025, 3100000, N'Da thanh toan'),
(5, 1, 2025, 2200000, N'Chua thanh toan'),
(5, 2, 2025, 2250000, N'Da thanh toan'),
(6, 1, 2025, 2600000, N'Da thanh toan'),
(6, 2, 2025, 2650000, N'Chua thanh toan'),
(7, 1, 2025, 2300000, N'Da thanh toan'),
(7, 2, 2025, 2350000, N'Da thanh toan'),
(8, 1, 2025, 3200000, N'Da thanh toan'),
(8, 2, 2025, 3250000, N'Da thanh toan'),
(8, 3, 2025, 3300000, N'Chua thanh toan'),
(8, 4, 2025, 3350000, N'Da thanh toan'),
(2, 3, 2025, 2400000, N'Chua thanh toan');
GO

INSERT INTO CHI_TIET_HOA_DON(ma_hoa_don, ma_dich_vu, so_luong, thanh_tien) VALUES
(1, 1, 100, 350000),
(1, 2, 8,   120000),
(1, 3, 1,   100000),
(2, 1, 110, 385000),
(2, 2, 9,   135000),
(2, 3, 1,   100000);
GO

INSERT INTO BAO_TRI(ma_phong, loai_bao_tri, chi_phi, ngay_bao_tri) VALUES
(10, N'Sửa đường ống nước',  500000, '2025-03-10'),
(11, N'Sơn lại tường',       800000, '2025-03-15'),
(4,  N'Thay bóng đèn',       150000, '2025-02-20');
GO

INSERT INTO SU_CO_AN_NINH(mo_ta, ngay_xay_ra) VALUES
(N'Mất trộm xe máy ở sân để xe', '2025-01-15'),
(N'Chập điện hành lang tầng 2',  '2025-02-05');
GO
INSERT INTO SU_CO_PHONG(ma_su_co, ma_phong) VALUES
(1, 1), (2, 5), (2, 6);
GO

INSERT INTO NOI_QUY(noi_dung) VALUES
(N'Giữ gìn vệ sinh chung, đổ rác đúng nơi quy định.'),
(N'Không gây ồn ào sau 22 giờ.'),
(N'Đóng tiền phòng trước ngày 05 hàng tháng.');
GO

INSERT INTO PHAN_HOI(ma_nguoi_thue, noi_dung, loai) VALUES
(1, N'Đề nghị sửa vòi nước bị rò rỉ.', N'Bảo trì'),
(3, N'Wifi yếu vào buổi tối.',          N'Dịch vụ'),
(5, N'Xin thêm chỗ để xe.',             N'Khác');
GO

SELECT N'Tong so phong' AS chi_tieu, COUNT(*) AS gia_tri FROM PHONG;

SELECT N'So phong dang thue' AS chi_tieu, COUNT(*) AS gia_tri FROM PHONG WHERE trang_thai = N'Da thue';

SELECT N'So phong trong' AS chi_tieu, COUNT(*) AS gia_tri FROM PHONG WHERE trang_thai = N'Trong';

SELECT N'Tong doanh thu da thanh toan' AS chi_tieu, ISNULL(SUM(tong_tien),0) AS gia_tri
FROM HOA_DON WHERE trang_thai = N'Da thanh toan';
SELECT * FROM vw_doanh_thu_thang ORDER BY nam, thang;

SELECT TOP 5 nt.ma_nguoi_thue, nt.ho_ten,
       SUM(DATEDIFF(DAY, h.ngay_bat_dau, ISNULL(h.ngay_ket_thuc, CAST(GETDATE() AS DATE)))) AS tong_so_ngay_thue
FROM NGUOI_THUE nt
INNER JOIN HOP_DONG_NGUOI_THUE hdnt ON hdnt.ma_nguoi_thue = nt.ma_nguoi_thue
INNER JOIN HOP_DONG h ON h.ma_hop_dong = hdnt.ma_hop_dong
GROUP BY nt.ma_nguoi_thue, nt.ho_ten
ORDER BY tong_so_ngay_thue DESC;
GO

SELECT ma_phong, trang_thai AS truoc_khi_thue FROM PHONG WHERE ma_phong = 9;
INSERT INTO HOP_DONG(ma_phong, ngay_bat_dau, ngay_ket_thuc, tien_coc)
VALUES (9, '2025-04-01', '2026-03-31', 1900000);
SELECT ma_phong, trang_thai AS sau_khi_thue FROM PHONG WHERE ma_phong = 9;
GO

SELECT ma_phong, trang_thai AS truoc_khi_tra FROM PHONG WHERE ma_phong = 1;
UPDATE HOP_DONG SET trang_thai = N'Het han' WHERE ma_hop_dong = 1;
SELECT ma_phong, trang_thai AS sau_khi_tra FROM PHONG WHERE ma_phong = 1;
GO

DECLARE @truoc INT = (SELECT COUNT(*) FROM HOA_DON);
BEGIN TRY
    EXEC sp_lap_hoa_don @p_ma_hop_dong = 2, @p_thang = 13, @p_nam = 2025;
END TRY
BEGIN CATCH
    PRINT N'Da bat loi o tang goi: ' + ERROR_MESSAGE();
END CATCH
DECLARE @sau INT = (SELECT COUNT(*) FROM HOA_DON);
PRINT N'So hoa don truoc = ' + CAST(@truoc AS NVARCHAR(10)) + N', sau = ' + CAST(@sau AS NVARCHAR(10));
GO

EXEC sp_lap_hoa_don @p_ma_hop_dong = 4, @p_thang = 4, @p_nam = 2025;
GO

SELECT TOP 20 * FROM AuditLog ORDER BY ma_log DESC;
GO

SELECT * FROM vw_phong_dang_thue;
SELECT * FROM vw_phong_trong;
SELECT * FROM vw_cong_no_khach_thue ORDER BY cong_no DESC;
GO