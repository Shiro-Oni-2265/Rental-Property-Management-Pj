SET NAMES utf8mb4;
USE quan_ly_phong_tro;

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
