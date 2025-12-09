<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
/* Chỉnh Select2 */
.select2-container .select2-selection--single {
    height: 38px !important;
    border: 1px solid #ced4da !important;
    display: flex;
    align-items: center;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: normal !important;
    padding-left: 12px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 36px !important;
}

/* Chỉnh Voucher Ticket */
.voucher-ticket {
    border: 2px dashed #ff5722;
    background: #fff4f0;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: 0.3s;
}

.voucher-ticket:hover {
    background: #ffece5;
    cursor: pointer;
}

.voucher-code {
    font-weight: bold;
    color: #ff5722;
    font-size: 16px;
}

.voucher-desc {
    font-size: 12px;
    color: #666;
}
</style>

<div class="container" style="margin-top: 30px; margin-bottom: 50px;">
    <h2 class="text-center mb-4 pb-2 border-bottom">THANH TOÁN & ĐẶT HÀNG</h2>

    <form action="?ctrl=order&act=saveOrder" method="post" style="display: flex; gap: 40px; flex-wrap: wrap;">

        <div style="flex: 1; min-width: 320px;">
            <div class="p-4 bg-white border rounded shadow-sm mb-4">
                <h4 class="mb-3">1. Thông tin giao hàng</h4>

                <div class="mb-3">
                    <label class="fw-bold">Họ tên:</label>
                    <input type="text" name="fullname" value="<?=$user['fullname']?>" required class="form-control">
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Số điện thoại:</label>
                    <input type="text" name="phone" value="<?=$user['phone'] ?? ''?>" required class="form-control">
                </div>

                <div class="mb-3">
                    <label class="fw-bold mb-2">Địa chỉ:</label>
                    <div class="row g-2">
                        <div class="col-12 mb-2">
                            <select id="province" class="form-select" style="width: 100%;">
                                <option value="">Tỉnh/Thành</option>
                            </select>
                        </div>
                        <div class="col-6 mb-2">
                            <select id="district" class="form-select" style="width: 100%;">
                                <option value="">Quận/Huyện</option>
                            </select>
                        </div>
                        <div class="col-6 mb-2">
                            <select id="ward" class="form-select" style="width: 100%;">
                                <option value="">Phường/Xã</option>
                            </select>
                        </div>
                    </div>
                    <input type="text" id="street_name" class="form-control mt-2" placeholder="Số nhà, tên đường..."
                        required>
                    <input type="hidden" name="address" id="full_address_input">
                </div>

                <div class="mb-3">
                    <label>Ghi chú:</label>
                    <textarea name="note" rows="2" class="form-control" placeholder="Lưu ý cho shipper..."></textarea>
                </div>
            </div>

            <div class="p-4 bg-white border rounded shadow-sm">
                <h4 class="mb-3">2. Thanh toán</h4>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="radio" name="payment_method" value="COD" checked>
                    <label class="form-check-label fw-bold">Thanh toán khi nhận hàng (COD)</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="payment_method" value="BANK">
                    <label class="form-check-label fw-bold">Chuyển khoản Ngân hàng (QR Code)</label>
                </div>
                <div id="qr-payment-info" class="alert alert-info mt-3" style="display: none;">
                    <small>Vui lòng quét mã QR ở trang kết quả đặt hàng.</small>
                </div>
            </div>
        </div>

        <div style="width: 35%; min-width: 320px;">
            <div class="p-4 bg-light border rounded sticky-top" style="top: 20px;">
                <h4 class="mb-3 border-bottom pb-2">Đơn hàng (<?=count($_SESSION['cart'])?>)</h4>

                <div class="mb-3" style="max-height: 250px; overflow-y: auto;">
                    <?php foreach ($_SESSION['cart'] as $item): ?>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span><strong><?=$item['name']?></strong> <span
                                class="text-muted">x<?=$item['quantity']?></span></span>
                        <span class="fw-bold"><?=number_format($item['price'] * $item['quantity'])?> đ</span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <label class="fw-bold mb-1">Mã giảm giá:</label>
                <div class="input-group mb-2">
                    <input type="text" id="voucher_code" class="form-control" placeholder="Nhập mã">
                    <button class="btn btn-dark" type="button" id="btn-apply-voucher">Áp dụng</button>
                </div>
                <a href="#" id="open-voucher-modal" class="text-decoration-none small text-primary mb-3 d-inline-block">
                    <i class="fas fa-ticket-alt"></i> Chọn mã giảm giá có sẵn
                </a>
                <div id="voucher-message" class="small mb-2"></div>

                <input type="hidden" name="discount_amount" id="discount_amount_input" value="0">
                <input type="hidden" name="voucher_code_used" id="voucher_code_input" value="">

                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Tạm tính:</span>
                        <span data-original="<?=$totalPrice?>" id="temp-total"><?=number_format($totalPrice)?> đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1 text-success" id="discount-row"
                        style="display:none;">
                        <span>Giảm giá (<span id="discount-percent">0</span>%):</span>
                        <span>- <span id="discount-value">0</span> đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>Vận chuyển:</span>
                        <span class="text-success">Miễn phí</span>
                    </div>
                    <div class="d-flex justify-content-between mt-3 fs-5 fw-bold text-danger">
                        <span>TỔNG CỘNG:</span>
                        <span id="final-total"><?=number_format($totalPrice)?> đ</span>
                    </div>
                </div>

                <button type="submit" class="btn btn-danger w-100 py-3 mt-3 fw-bold text-uppercase">ĐẶT HÀNG
                    NGAY</button>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="voucherModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kho Voucher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="voucher-list-container">
                <p class="text-center text-muted">Đang tải...</p>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // --- 1. XỬ LÝ ĐỊA CHỈ (SELECT2 + API) ---
    $('#province').select2({
        placeholder: "Tỉnh/Thành",
        width: '100%'
    });
    $('#district').select2({
        placeholder: "Quận/Huyện",
        width: '100%'
    });
    $('#ward').select2({
        placeholder: "Phường/Xã",
        width: '100%'
    });

    $.getJSON('https://esgoo.net/api-tinhthanh/1/0.htm', function(data) {
        if (data.error == 0) {
            $.each(data.data, function(k, v) {
                $("#province").append('<option value="' + v.id + '" data-name="' + v.full_name +
                    '">' + v.full_name + '</option>');
            });
        }
    });

    $("#province").change(function() {
        $.getJSON('https://esgoo.net/api-tinhthanh/2/' + $(this).val() + '.htm', function(data) {
            $("#district").html('<option value="">Quận/Huyện</option>');
            $("#ward").html('<option value="">Phường/Xã</option>');
            $.each(data.data, function(k, v) {
                $("#district").append('<option value="' + v.id + '" data-name="' + v
                    .full_name + '">' + v.full_name + '</option>');
            });
        });
        updateAddr();
    });

    $("#district").change(function() {
        $.getJSON('https://esgoo.net/api-tinhthanh/3/' + $(this).val() + '.htm', function(data) {
            $("#ward").html('<option value="">Phường/Xã</option>');
            $.each(data.data, function(k, v) {
                $("#ward").append('<option value="' + v.id + '" data-name="' + v
                    .full_name + '">' + v.full_name + '</option>');
            });
        });
        updateAddr();
    });

    $("#ward, #street_name").change(updateAddr);
    $("#street_name").on('input', updateAddr);

    function updateAddr() {
        var t = $("#province option:selected").data('name') || '';
        var q = $("#district option:selected").data('name') || '';
        var p = $("#ward option:selected").data('name') || '';
        var s = $("#street_name").val();
        $("#full_address_input").val([s, p, q, t].filter(Boolean).join(', '));
    }

    // --- 2. XỬ LÝ VOUCHER ---

    // Mở Modal & Tải danh sách
    $('#open-voucher-modal').click(function(e) {
        e.preventDefault();
        $('#voucherModal').modal('show');

        // Gọi AJAX lấy danh sách
        $.ajax({
            url: '?ctrl=cart&act=listVouchers',
            dataType: 'json',
            success: function(res) {
                var html = '';
                if (res.status == 'success' && res.data.length > 0) {
                    res.data.forEach(function(v) {
                        html += `
                        <div class="voucher-ticket" onclick="selectVoucher('${v.code}')">
                            <div>
                                <div class="voucher-code">${v.code}</div>
                                <div class="voucher-desc">${v.description || 'Giảm giá đơn hàng'}</div>
                                <small class="text-muted">HSD: ${v.end_date}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-danger">-${v.discount_percent}%</span><br>
                                <small class="text-primary">Dùng ngay</small>
                            </div>
                        </div>`;
                    });
                } else {
                    html = '<p class="text-center">Hiện không có mã giảm giá nào.</p>';
                }
                $('#voucher-list-container').html(html);
            }
        });
    });

    // Hàm chọn voucher từ Modal
    window.selectVoucher = function(code) {
        $('#voucher_code').val(code);
        $('#voucherModal').modal('hide');
        $('#btn-apply-voucher').click(); // Tự động bấm nút Áp dụng
    };

    // Nút Áp dụng Voucher
    $('#btn-apply-voucher').click(function() {
        var code = $('#voucher_code').val().trim();
        var total = parseFloat($('#temp-total').data('original'));

        if (code == '') return;

        $.ajax({
            url: '?ctrl=cart&act=checkVoucher',
            type: 'POST',
            data: {
                code: code
            },
            dataType: 'json',
            success: function(res) {
                if (res.status == 'success') {
                    var discount = total * (res.percent / 100);
                    var newTotal = total - discount;

                    $('#discount-percent').text(res.percent);
                    $('#discount-value').text(new Intl.NumberFormat('vi-VN').format(
                        discount));
                    $('#final-total').text(new Intl.NumberFormat('vi-VN').format(newTotal) +
                        ' đ');
                    $('#discount-row').show();

                    $('#discount_amount_input').val(discount);
                    $('#voucher_code_input').val(code);

                    $('#voucher-message').html(
                        '<span class="text-success fw-bold"><i class="fas fa-check-circle"></i> Đã áp dụng mã ' +
                        code + '</span>');
                    $('#voucher_code').prop('disabled', true);
                    $('#btn-apply-voucher').prop('disabled', true).text('Đã dùng');
                } else {
                    $('#voucher-message').html('<span class="text-danger">' + res.message +
                        '</span>');
                    resetVoucher(total);
                }
            }
        });
    });

    function resetVoucher(originalTotal) {
        $('#discount-row').hide();
        $('#final-total').text(new Intl.NumberFormat('vi-VN').format(originalTotal) + ' đ');
        $('#discount_amount_input').val(0);
        $('#voucher_code_input').val('');
    }

    // --- 3. QR CODE INFO ---
    $('input[name="payment_method"]').change(function() {
        $(this).val() == 'BANK' ? $('#qr-payment-info').slideDown() : $('#qr-payment-info').slideUp();
    });
});
</script>