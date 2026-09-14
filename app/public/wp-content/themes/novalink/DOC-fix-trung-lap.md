# Fix: trang "Xem chi tiết" của Sản phẩm và Dịch vụ bị giống nhau

## Nguyên nhân thật sự
Theme `novalink` **đã có 2 template khác nhau** cho 2 loại trang này
(`page-chi-tiet-dich-vu.php` cho Dịch vụ, `single-san_pham.php` cho Sản
phẩm) — bố cục, màu sắc, các khối hiển thị đều khác nhau.

Vấn đề nằm ở **nội dung**, không phải giao diện: công cụ "⚡ Tạo 12 sản
phẩm mẫu" (Sản phẩm → Tạo 12 sản phẩm mẫu) chèn **cùng một đoạn mô tả mẫu**
("Khảo sát và tư vấn theo nhu cầu thực tế doanh nghiệp / Triển khai đúng
tiến độ... / Bàn giao đầy đủ tài liệu...") cho **cả 12 sản phẩm**, không
phân biệt sản phẩm nào với sản phẩm nào — và đoạn đó cũng đọc y như mô tả
chung chung của các mục Dịch vụ. Vì vậy khi mở 2 link:

- `/san-pham/thiet-ke-website-doanh-nghiep-chuan-seo/`
- `/dich-vu/thiet-ke-website/`

...phần nội dung bên dưới đọc gần như giống hệt nhau.

## Đã sửa gì
1. **`functions.php`** — hàm tạo sản phẩm mẫu giờ tạo nội dung **riêng cho
   từng sản phẩm**: hạng mục bàn giao, thời gian thực hiện, đối tượng phù
   hợp đều khác nhau theo từng sản phẩm, và luôn nhấn vào đặc điểm của
   **Sản phẩm** (phạm vi + giá + thời gian cố định, bàn giao 1 lần) để
   phân biệt rõ với **Dịch vụ** (tư vấn/đồng hành dài hạn).
2. **`page-dich-vu.php`** — câu mô tả thẻ "Thiết kế website" chỉnh lại để
   nói rõ đây là hướng tư vấn/đồng hành, không phải gói cố định.
3. **`single-san_pham.php`** — thêm 1 dòng ghi chú nhỏ: "Đây là gói trọn
   gói... nếu cần tư vấn riêng, xem Dịch vụ tương ứng" kèm link.
4. **`page-chi-tiet-dich-vu.php`** — thêm dòng ghi chú ngược lại, dẫn sang
   trang Sản phẩm cho ai muốn giá/phạm vi cố định ngay.

## Việc cần làm thêm (không nằm trong file theme, nằm trong database)
Nếu bạn **đã từng bấm nút** "Tạo 12 sản phẩm mẫu" trên site thật rồi, thì
12 sản phẩm đó đã được tạo với nội dung cũ (bị trùng) và đang nằm trong
database — sửa code không tự cập nhật lại nội dung cũ. Bạn cần:

- Cách nhanh nhất: vào **Sản phẩm → Tất cả sản phẩm**, xoá 12 sản phẩm mẫu
  cũ, rồi vào **Sản phẩm → ⚡ Tạo 12 sản phẩm mẫu** bấm lại — nội dung mới
  (đã khác nhau) sẽ được tạo lại.
- Hoặc: sửa tay nội dung từng sản phẩm trong **Sản phẩm → Tất cả sản phẩm**
  nếu đã tuỳ chỉnh thêm và không muốn xoá.

## Cách áp dụng file đính kèm
Copy 4 file trong thư mục `wp-content/themes/novalink/` của bản đính kèm,
ghi đè vào đúng vị trí trong site Local (`app/public/wp-content/themes/novalink/`).
