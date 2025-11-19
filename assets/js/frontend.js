jQuery(document).ready(function ($) {
    var currentStep = 1;
    var totalSteps = $('.sb-step').length;

    function showStep(step) {
        $('.sb-step').hide();
        $('.sb-step[data-step="' + step + '"]').show();
        $('#sb-prev').toggle(step > 1);
        $('#sb-next').toggle(step < totalSteps);
        $('#sb-submit').toggle(step === totalSteps);
    }

    showStep(currentStep);

    $('#sb-next').on('click', function () {
        // basic validation for current step (required fields)
        var valid = true;
        $('.sb-step[data-step="' + currentStep + '"] [required]').each(function () {
            if (!$(this).val()) {
                $(this).addClass('sb-error');
                valid = false;
            } else {
                $(this).removeClass('sb-error');
            }
        });
        if (!valid) {
            alert('Please fill required fields in this step.');
            const firstInvalid = $('.sb-step[data-step="' + currentStep + '"] .sb-error').first();
            $('html, body').animate({ scrollTop: firstInvalid.offset().top - 20 }, 300);
            return;
        }
        currentStep++;
        showStep(currentStep);
        window.scrollTo(0, 0);
    });

    $('#sb-prev').on('click', function () {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    });

    $('#sb-booking-form').on('submit', function (e) {
        e.preventDefault();
        var form = $(this)[0];
        var formData = new FormData(form);

        // append nonce
        formData.append('action', 'sb_submit_booking');
        formData.append('nonce', sb_ajax.nonce);

        $('#sb-message').hide().removeClass('sb-success sb-error');

        $.ajax({
            url: sb_ajax.ajax_url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                if (res.success) {
                    $('#sb-message').addClass('sb-success').text(res.data).show();
                    $('#sb-booking-form')[0].reset();
                    currentStep = 1;
                    showStep(currentStep);
                    window.scrollTo(0, 0);
                } else {
                    $('#sb-message').addClass('sb-error').text(res.data).show();
                    window.scrollTo(0, 0);
                }
            },
            error: function (xhr) {
                $('#sb-message').addClass('sb-error').text('An error occurred. Please try again.').show();
            }
        });
    });
});
