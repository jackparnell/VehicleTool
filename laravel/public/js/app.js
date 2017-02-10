var unitNumber;
var driverFirstName;
var driverLastName;
var vehicleData = {};

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
                url: "api/" + this.queue[i].type + "/create",

                // Synchronous calls are deprecated, however we lose the value of i when doing async.
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
    formData.append('unitNumber', unitNumber);
    formData.append('driverFirstName', driverFirstName);
    formData.append('driverLastName', driverLastName);
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

function downloadVehicleData()
{

    if (!unitNumber || !navigator.onLine) {
        return false;
    }

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        method: "GET",
        url: "api/vehicle/" + unitNumber,
        async: true,
        dataType: 'json',
        contentType: false,
        processData: false,
        cache : false,
        success: function(response)
        {
            vehicleData[unitNumber] = response;
        }
    });

    return true;

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

// Download vehicle data
setInterval(function() {
    downloadVehicleData(unitNumber);
}, 120000);