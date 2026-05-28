<?php
$path_to_root = './';
require_once 'includes/header.php';
?>

<header>
    <h1>Chi Tiết Hóa Đơn #<?php echo $id; ?></h1>
    <a href="index.php?controller=invoice&action=index" class="btn btn-primary" style="background: transparent; border: 1px solid var(--primary-color);">Quay lại</a>
</header>

<div class="card-grid" style="grid-template-columns: 2fr 1fr;">
    <!-- Left: Details -->
    <div class="card">
        <h3>Các khoản thu</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Dịch vụ</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Đơn vị</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($details as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['ten_dich_vu']); ?></td>
                        <td><?php echo formatMoney($row['don_gia']); ?></td>
                        <td><?php echo $row['so_luong']; ?></td>
                        <td><?php echo htmlspecialchars($row['don_vi']); ?></td>
                        <td><?php echo formatMoney($row['thanh_tien']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr style="font-weight: bold; background: rgba(255,255,255,0.05);">
                        <td colspan="4" style="text-align: right;">TỔNG CỘNG:</td>
                        <td colspan="1" style="font-size: 1.2rem; color: var(--success);"><?php echo formatMoney($invoice['tong_tien']); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <?php if ($invoice['trang_thai'] != 'Da thanh toan'): ?>
        <form method="POST" action="index.php?controller=invoice&action=details&id=<?php echo $id; ?>" style="margin-top: 1rem; border-top: 1px solid var(--border-color); padding-top: 1rem;">
             <h4>Thêm dịch vụ</h4>
             <div style="display: flex; gap: 1rem; align-items: flex-end;">
                  <div class="form-group" style="flex: 2; margin-bottom: 0;">
                      <label>Dịch vụ</label>
                      <select name="ma_dich_vu" required>
                          <?php foreach ($services as $s): ?>
                          <option value="<?php echo $s['ma_dich_vu']; ?>">
                              <?php echo htmlspecialchars($s['ten_dich_vu']); ?> (<?php echo formatMoney($s['don_gia']); ?>/<?php echo htmlspecialchars($s['don_vi']); ?>)
                          </option>
                          <?php endforeach; ?>
                      </select>
                  </div>
                  <div class="form-group" style="flex: 1; margin-bottom: 0;">
                      <label>Số lượng</label>
                      <input type="number" name="so_luong" step="0.01" value="1" required>
                  </div>
                  <button type="submit" name="add_service" class="btn btn-primary" style="margin-bottom: 0;">Thêm</button>
             </div>
        </form>
        
        <div style="margin-top: 2rem; text-align: right; display: flex; justify-content: flex-end; gap: 1rem;">
            <!-- Form add room price manually if needed -->
            <?php 
                $has_room_rent = false;
                foreach($details as $d) {
                    if ($d['ten_dich_vu'] === 'Tiền phòng') $has_room_rent = true;
                }
                if (!$has_room_rent):
            ?>
            <form method="POST" action="index.php?controller=invoice&action=details&id=<?php echo $id; ?>">
                <button type="submit" name="add_room_price" class="btn btn-primary" style="background: transparent; border: 1px solid var(--primary-color);">
                     Thêm Tiền Phòng
                </button>
            </form>
            <?php endif; ?>

            <form method="POST" action="index.php?controller=invoice&action=details&id=<?php echo $id; ?>">
                <button type="submit" name="pay" class="btn btn-success" style="background: var(--success); color: white;" onclick="return confirm('Xác nhận thanh toán?')">
                    <i class="fa-solid fa-check"></i> Xác nhận Đã Thanh Toán
                </button>
            </form>
        </div>
        <?php else: ?>
            <div style="margin-top: 1rem; text-align: center; color: var(--success); font-weight: bold; font-size: 1.2rem; border: 2px dashed var(--success); padding: 1rem; border-radius: 8px;">
                ĐÃ THANH TOÁN
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Right: Info -->
    <div class="card">
        <h3>Thông tin</h3>
        <p style="margin-bottom: 0.5rem; color: var(--text-muted);">Hợp đồng:</p>
        <div style="font-size: 1.1rem; margin-bottom: 1.5rem;">#<?php echo $invoice['ma_hop_dong']; ?></div>
        
        <p style="margin-bottom: 0.5rem; color: var(--text-muted);">Phòng:</p>
        <div style="font-size: 1.1rem; margin-bottom: 1.5rem;">Phòng <?php echo $invoice['ma_phong']; ?></div>
        
        <p style="margin-bottom: 0.5rem; color: var(--text-muted);">Giá phòng:</p>
        <div style="font-size: 1.1rem; margin-bottom: 1.5rem;"><?php echo formatMoney($invoice['gia_thue']); ?></div>
        
        <p style="margin-bottom: 0.5rem; color: var(--text-muted);">Tháng/Năm:</p>
        <div style="font-size: 1.1rem; margin-bottom: 1.5rem;"><?php echo $invoice['thang'] . '/' . $invoice['nam']; ?></div>
        
        <div class="badge <?php echo $invoice['trang_thai'] == 'Da thanh toan' ? 'badge-success' : 'badge-warning'; ?>" style="font-size: 1rem; display: inline-block;">
            <?php echo $invoice['trang_thai']; ?>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
