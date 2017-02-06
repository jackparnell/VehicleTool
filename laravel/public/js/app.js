function handleDamagePhoto(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#damagePhotoPreview').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}