function handleDamagePhoto(input)
{
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#damagePhotoPreview').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}


// Handle offline mode
setInterval(function() {
    if (navigator.onLine) {
        $('#offlineMode').slideUp('slow');
    } else {
        $('#offlineMode').slideDown('slow');
    }

    console.log(navigator.onLine);

}, 2000);
