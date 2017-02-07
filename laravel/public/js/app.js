var communicator = {
    queue: [],
    addToQueue: function(communication)
    {
        this.queue.push(communication);
    },
    processQueue: function()
    {
        if (!navigator.onLine) {
            return false;
        }

        var i;
        var indexesProcessed = [];

        for (i = 0; i < this.queue.length; i++) {

            if (this.queue[i].pending) {
                continue;
            }

            this.queue[i].pending = true;

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                method: "POST",
                url: "api",

                // Synchronous calls are deprecated deprecated, however we lose the value of i when doing async.
                // i is needed so we can splice processed communications our of array. Maybe find another approach.
                async: false,

                contentType: false,
                processData: false,
                cache : false,
                data: this.queue[i].formData,
                success: function()
                {
                    indexesProcessed.push(i);
                }
            });

        }

        var j;
        for (j = 0; j < indexesProcessed.length; j++) {
            this.queue.splice(indexesProcessed[j], 1);
        }

    }
};

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

function handleReportCompletion(type)
{

    var form = $('form#' + type + 'View')[0];
    var formData = new FormData(form);
    var communication = {
        type: type,
        guid: generateGuid(),
        formData: formData
    };
    communicator.addToQueue(communication);
}

function generateGuid()
{
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
}


// Handle offline mode
setInterval(function() {
    if (navigator.onLine) {
        $('#offlineMode').slideUp('slow');
    } else {
        $('#offlineMode').slideDown('slow');
    }

}, 2000);

// Process communications
setInterval(function() {
    communicator.processQueue();
}, 10000);

