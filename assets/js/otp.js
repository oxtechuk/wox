jQuery(function ($) {
    if (typeof wox_otp === 'undefined') {
        return;
    }

    var $checkout = $('form.checkout, form#order_review');

    if ($checkout.length === 0) {
        return;
    }

    var otpSent = false;
    var cooldown = 60;

    function addOtpFields() {
        var $phone = $('#billing_phone');
        if ($phone.length === 0) {
            return;
        }

        var $row = $('<p class="form-row form-row-wide" id="wox_otp_row">');
        $row.append('<label for="wox_otp_code">' + wox_otp.strings.enter_code + '</label>');
        $row.append('<span style="display:flex;gap:5px;">');
        $row.find('span').append('<input type="text" id="wox_otp_code" name="wox_otp_code" placeholder="000000" style="flex:1;" maxlength="10" />');
        $row.find('span').append('<button type="button" id="wox_send_otp" class="button">' + wox_otp.strings.send + '</button>');
        $row.find('span').append('<button type="button" id="wox_verify_otp" class="button button-primary" style="display:none;">' + wox_otp.strings.verify + '</button>');

        $phone.closest('.form-row').after($row);

        $('#wox_send_otp').on('click', function () {
            var phone = $('#billing_phone').val();
            if (!phone) {
                alert('Please enter your phone number first.');
                return;
            }

            var $btn = $(this);
            $btn.prop('disabled', true).text(wox_otp.strings.sending);

            $.post(wox_otp.ajax_url, {
                action: 'wox_send_otp',
                phone: phone,
                nonce: wox_otp.nonce
            }, function (response) {
                if (response.success) {
                    otpSent = true;
                    $('#wox_verify_otp').show();
                    $btn.text(wox_otp.strings.resend);
                    startCooldown($btn);
                } else {
                    alert(response.data.message);
                    $btn.prop('disabled', false).text(wox_otp.strings.send);
                }
            });
        });

        $('#wox_verify_otp').on('click', function () {
            var phone = $('#billing_phone').val();
            var code = $('#wox_otp_code').val();

            if (!code) {
                alert('Please enter the code sent to your WhatsApp.');
                return;
            }

            var $btn = $(this);
            $btn.prop('disabled', true).text(wox_otp.strings.sending);

            $.post(wox_otp.ajax_url, {
                action: 'wox_verify_otp',
                phone: phone,
                code: code,
                nonce: wox_otp.nonce
            }, function (response) {
                if (response.success) {
                    $('#wox_otp_code').prop('readonly', true).css('border-color', '#46b450');
                    $('#wox_verify_otp').text('Verified').css('background', '#46b450');
                    $('#wox_send_otp').hide();
                } else {
                    alert(response.data.message);
                    $btn.prop('disabled', false).text(wox_otp.strings.verify);
                }
            });
        });
    }

    function startCooldown($btn) {
        var remaining = cooldown;
        var timer = setInterval(function () {
            remaining--;
            if (remaining <= 0) {
                clearInterval(timer);
                $btn.prop('disabled', false).text(wox_otp.strings.send);
            } else {
                $btn.text(wox_otp.strings.resend + ' (' + remaining + 's)');
            }
        }, 1000);
    }

    addOtpFields();
});
