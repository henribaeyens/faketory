/**
 * @author    Henri Baeyens <henri.baeyens@gmail.com>
 * @copyright 2024
 * @license   MIT License
 */

jQuery(() => {
    const acceptWarning = $('#configuration_form input[name="acceptWarning"]');
    const submitFaketory = $('#configuration_form button[name="submitfaketory"]');

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
        window.location.replace(faketoryController);
    });
});
