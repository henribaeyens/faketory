/**
 * @author    Henri Baeyens <henri.baeyens@gmail.com>
 * @copyright 2024
 * @license   MIT License
 */

jQuery(() => {
    const acceptWarning = $('#configuration_form input[name="acceptWarning"]');
    const submitFaketory = $('#configuration_form button[name="submitfaketory"]');
    const submitInner = submitFaketory.html();
    const panelHeading = $('#configuration_form .panel-heading');
    let processingInProgess = false;

    function showAlert(type, msg) {
        $('<div class="alert alert-' + type + '">').text(msg).insertAfter(panelHeading);
    }

    submitFaketory.hide();
    acceptWarning.on('change', () => {
        let checked = acceptWarning.filter(':checked').val();
        if (checked == 1) {
            submitFaketory.show();
        } else {
            submitFaketory.hide();
        }
    });

    submitFaketory.on('click', (e) => {
        e.preventDefault();
        if (!processingInProgess) {
            $.ajax({
                url: faketoryController,
                headers: { "cache-control": "no-cache" },
                cache: false,
                dataType: 'json',
                contentType: 'application/json',
                beforeSend: function() {
                    processingInProgess = true;
                    submitFaketory.html('<i class="process-icon-loading"></i> Processing...');
                },
                }).done((response) => {
                    $.each(response, (key, json) => {
                         showAlert(json.status, json.msg)
                    });
                    submitFaketory.html(submitInner);
                    processingInProgess = false;
                }).fail(() => {
                    showAlert('error', 'Request has failed')
                    processingInProgess = false;
            });
        }
    });
});
