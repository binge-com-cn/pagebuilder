'use strict';
(function ($) {
    'use strict';
    $(document).on('submit', '.xtpb-global-form-content form', function (e) {
        e.preventDefault();

        var $form = $(this);
        if ($form.data('xtpb-submitting')) {
            return;
        }

        var $submitButton = $form.find('.xtpb-btn-forms').first();
        var submitButtonHtml = $submitButton.data('xtpb-original-html') || $submitButton.html();
        var submitSuccessText = $submitButton.data('success-text') || '提交成功';
        $submitButton.data('xtpb-original-html', submitButtonHtml);

        var $formMeta = $form.find('input[name="xtpb_form_data"]').first();
        if ($formMeta.length) {
            try {
                var formMeta = JSON.parse($formMeta.val());
                formMeta.source_url = window.location.href;
                $formMeta.val(JSON.stringify(formMeta));
            } catch (error) {}
        }

        var $formData = $form.serialize() + '&action=xtpb_form_process';
        var $formErrors = { msg: '', requiredFields: [] };

        const targetForm = e.target;

        $(targetForm.querySelectorAll('[data-required="true"]')).each(function (index, element) {
            var $field = $(this);
            var field_type = $field[0].type;
            var $closestDiv = $field.closest('.xtpb-form-field-wrap');

            if (field_type === 'switch' || field_type === 'radio') {
                $closestDiv.addClass('has-multi-input');
            } else {
                if (!$field.val()) {
                    $field.addClass('xtpb-form-field-has-error');
                    $formErrors.requiredFields.push($field.data('label'));
                } else {
                    if (field_type === 'email' && !xtpbFormValidateEmail($field.val())) {
                        $field.addClass('xtpb-form-field-has-error');
                        $formErrors.requiredFields.push($field.data('label'));
                    } else {
                        $field.removeClass('xtpb-form-field-has-error');
                    }
                }
            }
        });

        if ($form.find('.has-multi-input').length) {
            $form.find('.has-multi-input').each(function (index, element) {
                var $that = $(this);
                var $firstField = $that.find('input').first();

                if (!$that.find('input:checked').length) {
                    $formErrors.requiredFields.push($firstField.data('label'));
                    $that.addClass('xtpb-form-field-has-error');
                } else {
                    var newIndex = $formErrors.requiredFields.indexOf($firstField.data('label'));
                    if (newIndex > -1) {
                        $formErrors.requiredFields.splice(newIndex, 1);
                    }
                    $that.removeClass('xtpb-form-field-has-error');
                }
            });
        }
        var ErrorMsg = '<div class="xtpb_alert_warning">';

        if ($formErrors.requiredFields.length) {
            ErrorMsg += 'Please, fill in the following fields:';
            ErrorMsg += '<ul>';

            $.each($formErrors.requiredFields, function (errorIndex, error) {
                ErrorMsg += '<li>' + error + '</li>';
            });

            ErrorMsg += '</ul></div>';
            $form.find('.xtpb-form-msg').html(ErrorMsg);
            return;
        }

        $.ajax({
            type: 'POST',
            url: xtpb_form.ajax_url,
            data: $formData,
            beforeSend: function (jqXHR, settings) {
                $form.data('xtpb-submitting', true);
                $form.find('.xtpb-form-msg').html('');
                $submitButton.prop('disabled', true).addClass('xtpb-form-submitting').html('<i class="ri-loader-4-line ri-spin"></i> <span>提交中...</span>');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                var errorMessage = xtpbGetFormErrorMessage(jqXHR, textStatus + '(' + jqXHR.status + ')' + ' : ' + errorThrown);
                xtpbShowFormError($form, errorMessage);
            },
            success: function (data, textStatus, jqXHR) {
                var responseData = data && data.data ? data.data : {};

                if (data && data.success) {
                    $form.find('.xtpb_form_response').html('');
                    if (responseData.msg) {
                        jQuery('<p class="xtpb_alert_success"></p>').text(responseData.msg).appendTo($form.find('.xtpb_form_response'));
                    }
                    $form[0].reset();
                    $form.find('.xtpb-form-field-has-error').removeClass('xtpb-form-field-has-error');
                    $form.find('.has-multi-input').removeClass('has-multi-input');
                    $submitButton.removeClass('xtpb-form-submitting').addClass('xtpb-form-success').html('<i class="ri-checkbox-circle-line"></i> <span></span>');
                    $submitButton.find('span').text(submitSuccessText);

                    setTimeout(function () {
                        $form.find('.xtpb_form_response').html('');
                        $submitButton.removeClass('xtpb-form-success').prop('disabled', false).html(submitButtonHtml);
                        $form.data('xtpb-submitting', false);
                    }, 2000);

                    if (responseData.enable_redirect_url) {
                        location.href = responseData.redirect_url;
                    }

                    return;
                }

                xtpbShowFormError($form, xtpbGetFormErrorMessage(data, ''));
            },
            complete: function (jqXHR, textStatus) {
                if (!$submitButton.hasClass('xtpb-form-success')) {
                    $submitButton.removeClass('xtpb-form-submitting').prop('disabled', false).html(submitButtonHtml);
                    $form.data('xtpb-submitting', false);
                }

                if (typeof grecaptcha !== 'undefined') {
                    grecaptcha.reset();
                }
            },
        });
    });
})(jQuery);

function xtpbFormValidateEmail(email) {
    var emailValue = String(email).trim().toLowerCase();
    var atIndex = emailValue.indexOf('@');
    var dotIndex = emailValue.lastIndexOf('.');

    return atIndex > 0 && dotIndex > atIndex + 1 && dotIndex < emailValue.length - 1 && emailValue.indexOf(' ') === -1;
}

function xtpbGetFormErrorMessage(response, fallbackMessage) {
    var data = response && response.responseJSON ? response.responseJSON : response;

    if (data && data.data) {
        data = data.data;
    }

    if (typeof data === 'string') {
        return data;
    }

    if (data && data.errmsg) {
        return data.errmsg;
    }

    if (data && data.msg) {
        return data.msg;
    }

    if (data && data.message && data.message.indexOf('<') === -1) {
        return data.message;
    }

    if (data && data.messages && data.messages.length) {
        return data.messages.join('，');
    }

    return fallbackMessage || '表单提交失败，请稍后重试。';
}

function xtpbShowFormError($form, message) {
    var $message = jQuery('<div class="xtpb_alert_warning"></div>');
    $message.text(message);
    $form.find('.xtpb-form-msg').html($message);
}
