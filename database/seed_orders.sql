-- =====================================================================
-- Seed: Sample order history for meaningful chart data
-- Generates ~90 days of order history with mixed statuses & payments
-- =====================================================================
SET NAMES utf8mb4;
USE hasaki_db;

-- Clear existing test data (keep schema)
DELETE FROM ChiTietHoaDonBan;
DELETE FROM HoaDonBan;
ALTER TABLE HoaDonBan AUTO_INCREMENT = 1;
ALTER TABLE ChiTietHoaDonBan AUTO_INCREMENT = 1;

-- Stored procedure to seed deterministic orders across last 90 days
DROP PROCEDURE IF EXISTS seed_orders;

DELIMITER //
CREATE PROCEDURE seed_orders()
BEGIN
  DECLARE i INT DEFAULT 0;
  DECLARE total_orders INT DEFAULT 90;
  DECLARE order_id INT;
  DECLARE order_date DATETIME;
  DECLARE order_status INT;
  DECLARE payment_type VARCHAR(20);
  DECLARE customer_id INT;
  DECLARE order_total DECIMAL(12,2);
  DECLARE day_offset INT;
  DECLARE n_items INT;
  DECLARE j INT;
  DECLARE pid INT;
  DECLARE pqty INT;
  DECLARE pprice DECIMAL(12,2);
  DECLARE pname VARCHAR(255);
  DECLARE pcolor VARCHAR(100);
  DECLARE psize VARCHAR(100);
  DECLARE customer_count INT;

  SELECT COUNT(*) INTO customer_count FROM KhachHang;

  WHILE i < total_orders DO
    -- Distribute orders across last 90 days, weighted toward recent
    SET day_offset = FLOOR(POW(RAND(), 2) * 90);
    SET order_date = DATE_SUB(NOW(), INTERVAL day_offset DAY) + INTERVAL FLOOR(RAND() * 86400) SECOND;

    -- Status distribution: ~60% completed, 15% shipping, 15% pending, 10% cancelled
    SET order_status = CASE
      WHEN RAND() < 0.60 THEN 2
      WHEN RAND() < 0.40 THEN 1
      WHEN RAND() < 0.50 THEN 0
      ELSE 3
    END;

    -- Payment: ~55% COD, 25% VNPAY, 15% MOMO, 5% other
    SET payment_type = CASE
      WHEN RAND() < 0.55 THEN 'COD'
      WHEN RAND() < 0.55 THEN 'VNPAY'
      WHEN RAND() < 0.50 THEN 'MOMO'
      ELSE 'BANK'
    END;

    -- Pick random customer (1..N)
    SET customer_id = 1 + FLOOR(RAND() * customer_count);

    -- Insert empty order (we'll update total after items)
    INSERT INTO HoaDonBan (NgayBan, ThanhTien, MaKhachHang, SDT, Email, DiaChiGH, HinhThucTT, TrangThai)
    SELECT order_date, 0, customer_id, kh.SDT, kh.Email, kh.DiaChi, payment_type, order_status
      FROM KhachHang kh WHERE kh.MaKhachHang = customer_id;
    SET order_id = LAST_INSERT_ID();

    -- Add 1-4 items per order
    SET n_items = 1 + FLOOR(RAND() * 4);
    SET order_total = 0;
    SET j = 0;
    WHILE j < n_items DO
      SELECT MaSanPham, TenSanPham, GiaBan, IFNULL(MauSac,''), IFNULL(KichCo,'')
        INTO pid, pname, pprice, pcolor, psize
        FROM SanPham ORDER BY RAND() LIMIT 1;
      SET pqty = 1 + FLOOR(RAND() * 3);

      INSERT INTO ChiTietHoaDonBan (MaHoaDon, MaSanPham, TenSanPham, SoLuong, GiaBan, KichCo, MauSac)
      VALUES (order_id, pid, pname, pqty, pprice, NULLIF(psize,''), NULLIF(pcolor,''));

      SET order_total = order_total + (pprice * pqty);
      SET j = j + 1;
    END WHILE;

    UPDATE HoaDonBan SET ThanhTien = order_total WHERE MaHoaDon = order_id;
    SET i = i + 1;
  END WHILE;
END//
DELIMITER ;

CALL seed_orders();
DROP PROCEDURE seed_orders;

-- Also seed customer registration dates spread over last 6 months
UPDATE KhachHang SET NgayDangKy = DATE_SUB(NOW(), INTERVAL FLOOR(RAND() * 180) DAY)
WHERE MaKhachHang > 1;

SELECT COUNT(*) AS total_orders FROM HoaDonBan;
SELECT TrangThai, COUNT(*) AS so_don FROM HoaDonBan GROUP BY TrangThai;
SELECT HinhThucTT, COUNT(*) AS so_don FROM HoaDonBan GROUP BY HinhThucTT;
SELECT SUM(ThanhTien) AS doanh_thu_tong FROM HoaDonBan WHERE TrangThai = 2;
