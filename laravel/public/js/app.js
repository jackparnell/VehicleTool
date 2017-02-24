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

        downloadVehicleData();

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

function populateRecentChecks()
{

    $('#recentChecksView table tbody').html('');

    if (jQuery.isEmptyObject(vehicleData[unitNumber].vehicleChecks)) {
        $('#recentChecksView table tbody').append('<tr><td colspan="3">No recent checks found.</td></tr>');
        return;
    }

    for (var i in vehicleData[unitNumber].vehicleChecks) {

        var rowHtml = '<tr id="' + vehicleData[unitNumber].vehicleChecks[i].guid + '">'
            + '<td>' + vehicleData[unitNumber].vehicleChecks[i].date + '</td>'
            + '<td>' + vehicleData[unitNumber].vehicleChecks[i].driverFirstName + ' ' + vehicleData[unitNumber].vehicleChecks[i].driverLastName + '</td>'
            + '<td>' + vehicleData[unitNumber].vehicleChecks[i].time + '</td>'
            + '</tr>';

        $('#recentChecksView table tbody').append(rowHtml);

    }

    generateRecentChecksViewListeners();

}

function populateRecentDefects()
{

    $('#recentDefectsView table tbody').html('');

    if (jQuery.isEmptyObject(vehicleData[unitNumber].defectReports)) {
        $('#recentDefectsView table tbody').append('<tr><td colspan="3">No recent defects found.</td></tr>');
        return;
    }

    for (var i in vehicleData[unitNumber].defectReports) {

        var rowHtml = '<tr id="' + vehicleData[unitNumber].defectReports[i].guid + '">'
                    + '<td>' + vehicleData[unitNumber].defectReports[i].date + '</td>'
                    + '<td>' + vehicleData[unitNumber].defectReports[i].driverFirstName + ' ' + vehicleData[unitNumber].defectReports[i].driverLastName + '</td>'
                    + '<td>' + vehicleData[unitNumber].defectReports[i].time + '</td>'
                    + '</tr>';

        $('#recentDefectsView table tbody').append(rowHtml);

    }

    generateRecentDefectsViewListeners();

}

function populateRecentDamageReports()
{

    $('#recentDamageReportsView table tbody').html('');

    if (jQuery.isEmptyObject(vehicleData[unitNumber].damageReports)) {
        $('#recentDamageReportsView table tbody').append('<tr><td colspan="3">No recent damage reports found.</td></tr>');
        return;
    }

    for (var i in vehicleData[unitNumber].damageReports) {

        var rowHtml = '<tr id="' + vehicleData[unitNumber].damageReports[i].guid + '">'
            + '<td>' + vehicleData[unitNumber].damageReports[i].date + '</td>'
            + '<td>' + vehicleData[unitNumber].damageReports[i].driverFirstName + ' ' + vehicleData[unitNumber].damageReports[i].driverLastName + '</td>'
            + '<td>' + vehicleData[unitNumber].damageReports[i].time + '</td>'
            + '</tr>';


        $('#recentDamageReportsView table tbody').append(rowHtml);

    }

    generateRecentDamageReportsViewListeners();

}

function generateRecentDamageReportsViewListeners()
{
    $('#recentDamageReportsView td').click(function(e) {

        console.log(e);

        $('form:not(#recentDamageReportsView)').hide();
        $('#recentDamageReportsView').slideUp();

        var guid = this.closest('tr').id;

        populateRecentDamageRow(guid);

        $('#recentDamageRow').slideDown(
            400,
            function() {
                window.location.hash = '#recentDamageRow_' + guid;
            }
        );

    });

}

function generateRecentDefectsViewListeners()
{
    $('#recentDefectsView td').click(function(e) {

        $('form:not(#recentDefectsView)').hide();
        $('#recentDefectsView').slideUp();

        var guid = this.closest('tr').id;

        populateRecentDefectRow(guid);

        $('#recentDefectRow').slideDown(
            400,
            function() {
                window.location.hash = '#recentDefectRow_' + guid;
            }
        );

    });

}

function generateRecentChecksViewListeners()
{
    $('#recentChecksView td').click(function(e) {

        $('form:not(#recentChecksView)').hide();
        $('#recentChecksView').slideUp();

        var guid = this.closest('tr').id;

        populateVehicleCheckRow(guid);

        $('#vehicleCheckRow').slideDown(
            400,
            function() {
                window.location.hash = '#vehicleCheckRow_' + guid;
            }
        );

    });

}

function populateRecentDamageRow(guid)
{
    var row = getRowByGuid(guid);

    var simpleFields = [
        'driverFirstName',
        'driverLastName',
        'damageDriverSignature',
        'damageDescription',
        'damageLocation',
        'date',
        'time'
    ];

    simpleFields.forEach(function(element) {
        ($('#recentDamageRow .' + element)).html(row[element]);
    });

}

function populateRecentDefectRow(guid)
{
    var row = getRowByGuid(guid);

    var simpleFields = [
        'driverFirstName',
        'driverLastName',
        'driverSignature',
        'defectDescription',
        'defectCategory',
        'date',
        'time'
    ];

    simpleFields.forEach(function(element) {
        ($('#recentDefectRow .' + element)).html(row[element]);
    });

}

function populateVehicleCheckRow(guid)
{
    var row = getRowByGuid(guid);

    var simpleFields = [
        'driverFirstName',
        'driverLastName',
        'oil',
        'water',
        'lights',
        'tyres',
        'brakes',
        'date',
        'time'
    ];

    simpleFields.forEach(function(element) {
        ($('#vehicleCheckRow .' + element)).html(row[element]);
    });

}

function getRowByGuid(guid)
{
    var row = {};

    for (var property in vehicleData[unitNumber].damageReports) {
        if (vehicleData[unitNumber].damageReports.hasOwnProperty(property)) {
            if (vehicleData[unitNumber].damageReports[property].guid == guid) {
                row = vehicleData[unitNumber].damageReports[property];
                break;
            }
        }
    }

    for (var property in vehicleData[unitNumber].defectReports) {
        if (vehicleData[unitNumber].defectReports.hasOwnProperty(property)) {
            if (vehicleData[unitNumber].defectReports[property].guid == guid) {
                row = vehicleData[unitNumber].defectReports[property];
                break;
            }
        }
    }

    for (var property in vehicleData[unitNumber].vehicleChecks) {
        if (vehicleData[unitNumber].vehicleChecks.hasOwnProperty(property)) {
            if (vehicleData[unitNumber].vehicleChecks[property].guid == guid) {
                row = vehicleData[unitNumber].vehicleChecks[property];
                break;
            }
        }
    }

    return row;
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