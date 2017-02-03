@extends('layout')

@section('headerInsert')

    <script type="text/javascript">
        var unitNumber;
        var firstName;
        var lastName;

        $(document).ready(function() {

            // Prevent form submission
            $('form').submit(function(e){
                return false;
            });

            // Step validation rules
            $('#step1').validate({
                rules: {
                    unitNumber: {
                        required: true,
                        minlength: 6
                    }
                },
                messages: {
                    unitNumber: {
                        required: "Please the vehicle unit number.",
                        minlength: "The unit number must be at least 6 characters in length."
                    }
                }
            });

            $('#step2').validate({
                rules: {
                    firstName: {
                        required: true,
                        minlength: 2
                    },
                    lastName: {
                        required: true,
                        minlength: 2
                    }
                },
                messages: {
                    firstName: {
                        required: "Please the driver's first name.",
                        minlength: "The driver's first name must be at least 2 characters in length."
                    },
                    lastName: {
                        required: "Please the driver's last name.",
                        minlength: "The driver's last name must be at least 2 characters in length."
                    }
                }
            });

            $('#defectReportView').validate({
                rules: {
                    defectCategory: {
                        required: true
                    },
                    defectDescription: {
                        required: true,
                        minlength: 3
                    }
                },
                messages: {
                    defectCategory: {
                        required: "Please a category."
                    },
                    defectDescription: {
                        required: "Please enter a description.",
                        minlength: "The description must be at least 3 characters in length."
                    }
                }
            });



            $('#forwardToStep2').click(function() {

                if (!$('#step1').valid()) {
                    return false;
                }

                unitNumber = $('#unitNumber').val();

                $('#step1').slideUp();
                $('#step2').slideDown();
            });

            $('#forwardToStep3').click(function() {

                if (!$('#step2').valid()) {
                    return false;
                }

                firstName = $('#firstName').val();
                lastName = $('#lastName').val();


                $('.unitInfo').html('Unit: ' + unitNumber);

                $('#step2').slideUp();
                $('#step3').slideDown();
            });

            $('.startOver').click(function() {
                $('input').val('');
                unitNumber = '';
                firstName = '';
                lastName = '';
                $('form:not(#step1)').slideUp();
                $('#step1').slideDown();
            });

            $('.mainMenu').click(function() {
                $('form').slideUp();
                $('#step3').slideDown();
            });

            $('#vehicleCheck').click(function() {
                $('form:not(#step3)').hide();
                $('#step3').slideUp();
                $('#vehicleCheckView').slideDown();
            });

            $('#vehicleCheckSubmit').click(function() {

                $('.vehicleUnitNumber').html(unitNumber);
                $('.driverFullName').html(firstName + ' ' + lastName);

                $('form:not(#vehicleCheckView)').hide();
                $('#vehicleCheckView').slideUp(400, function() {
                    $('input[type=checkbox]').bootstrapToggle('on');
                });
                $('#vehicleCheckSubmittedView').slideDown();
            });

            $('#recentChecks').click(function() {
                $('form:not(#step3)').hide();
                $('#step3').slideUp();
                $('#recentChecksView').slideDown();
            });

            $('#defectReport').click(function() {
                $('form:not(#step3)').hide();
                $('#step3').slideUp();
                $('#defectReportView').slideDown();
            });

            $('#defectReportSubmit').click(function() {

                if (!$('#defectReportView').valid()) {
                    return false;
                }

                $('.vehicleUnitNumber').html(unitNumber);
                $('.driverFullName').html(firstName + ' ' + lastName);

                $('form:not(#defectReportView)').hide();
                $('#defectReportView').slideUp(400, function() {
                    $('#defectReportView input, #defectReportView select, #defectReportView textarea').val('');
                });
                $('#defectReportSubmittedView').slideDown();
            });



            $('#recentDefects').click(function() {
                $('form:not(#step3)').hide();
                $('#step3').slideUp();
                $('#recentDefectsView').slideDown();
            });

            $('#scanQrCode').click(function() {
                $('form:not(#step1)').hide();
                $('#step1').slideUp();
                $('#qrCode').slideDown();
            });


        });
    </script>

@endsection

@section('content')

    <div class="row">
        <img src="{{ asset('images/ryder.png') }}" alt="Ryder" id="logo">
    </div>

    <form id="step1">

        <h1>Enter Vehicle Unit Number</h1>

        <div class="row">
            <label for="unitNumber">Unit Number</label>
            <input class="form-control" id="unitNumber" name="unitNumber" placeholder="Example: AB12CDE">
            <br>
            <button class="btn btn-custom" id="forwardToStep2">Next</button>
        </div>

        <div class="row">or</div>

        <div class="row">
            <label for="scanQrCode">Scan QR Code</label>
            <button id="scanQrCode" class="btn btn-custom"><img src="{{ asset('images/qr.jpg') }}" id="qrCodeIcon" alt=""></button>
        </div>
    </form>

    <form id="step2">
        <h1>Confirm Driver Details</h1>

        <div class="row">
            <label for="lastName">Surname</label>
            <input class="form-control" id="lastName" name="lastName" placeholder="Example: Smith">
        </div>
        <div class="row">
            <label for="firstName">First Name</label>
            <input class="form-control" id="firstName" name="firstName" placeholder="Example: John">
        </div>

        <div class="row">
            <button class="btn btn-custom" id="forwardToStep3">Next</button>
        </div>
    </form>

    <form id="step3">
        <h1>Main Menu</h1>

        <p class="unitInfo">
            Unit
        </p>

        <div class="row">
            <button id="vehicleCheck" class="btn btn-custom">Vehicle Check</button>
        </div>
        <div class="row">
            <button id="recentChecks" class="btn btn-custom">Recent Checks</button>
        </div>
        <!--
        <div class="row">
            <button id="damageReport" class="btn btn-custom">Damage</button>
        </div>
        -->
        <div class="row">
            <button id="defectReport" class="btn btn-custom">Report Defect</button>
        </div>
        <div class="row">
            <button id="recentDefects" class="btn btn-custom">Recent Defects</button>
        </div>
        <div class="row">
            <button class="startOver btn btn-default">Start Over</button>
        </div>
    </form>

    <form id="vehicleCheckView">

        <h1>Vehicle Check</h1>

        <p class="unitInfo">
            Unit
        </p>

        <div class="row">
            <label for="oil">Oil</label>
            <input type="checkbox" name="oil" data-style="wide" checked data-on="Ok" data-off="Issue" data-toggle="toggle" data-onstyle="success" data-offstyle="danger">
        </div>
        <div class="row">
            <label for="water">Water</label>
            <input type="checkbox" name="water" data-style="wide" checked data-on="Ok" data-off="Issue" data-toggle="toggle" data-onstyle="success" data-offstyle="danger">
        </div>
        <div class="row">
            <label for="lights">Lights</label>
            <input type="checkbox" name="lights" data-style="wide" checked data-on="Ok" data-off="Issue" data-toggle="toggle" data-onstyle="success" data-offstyle="danger">
        </div>
        <div class="row">
            <label for="tyres">Tyres</label>
            <input type="checkbox" name="tyres" data-style="wide" checked data-on="Ok" data-off="Issue" data-toggle="toggle" data-onstyle="success" data-offstyle="danger">
        </div>
        <div class="row">
            <label for="brakes">Brakes</label>
            <input type="checkbox" name="brakes" data-style="wide" checked data-on="Ok" data-off="Issue" data-toggle="toggle" data-onstyle="success" data-offstyle="danger">
        </div>

        <div class="row">
            <button id="vehicleCheckSubmit" class="btn btn-custom">Submit Vehicle Check</button>
        </div>

        <div class="row">
            <button class="mainMenu btn btn-default">Main Menu</button>
        </div>

    </form>

    <form id="vehicleCheckSubmittedView">

        <p>
            Thank you for submitting your vehicle check to Ryder.
        </p>

        <ul>
            <li>Vehicle: <span class="vehicleUnitNumber"></span></li>
            <li>Date: <span class="vehicleCheckSubmittedDate">01/01/2017</span></li>
            <li>Time: <span class="vehicleCheckSubmittedTime">01/01/2017</span></li>
            <li>Driver: <span class="driverFullName"></span></li>
        </ul>

        <p>
            Your check will be uploaded to Ryder when your mobile device has a sufficient network connection.
        </p>

        <div class="row">
            <button class="mainMenu btn btn-custom">Main Menu</button>
        </div>

        <div class="row">
            <button class="startOver btn btn-default">Start Over</button>
        </div>

    </form>


    <form id="recentChecksView">
        <h1>Recent Checks</h1>

        <p class="unitInfo">
            Unit
        </p>

        <table class="table table-responsive table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Driver</th>
                    <th>Start</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>01/01/17</td>
                    <td>Example</td>
                    <td>12:00</td>
                </tr>
                <tr>
                    <td>05/01/17</td>
                    <td>Example</td>
                    <td>13:12</td>
                </tr>
                <tr>
                    <td>09/01/17</td>
                    <td>Example</td>
                    <td>16:02</td>
                </tr>
            </tbody>
        </table>

        <div class="row">
            <button class="mainMenu btn btn-custom">Main Menu</button>
        </div>
    </form>

    <form id="defectReportView">

        <h1>Defect Report</h1>

        <p class="unitInfo">
            Unit
        </p>

        <div class="row">
            <label for="defectCategory">Category</label>
            <select name="defectCategory" class="form-control" size="1">
                <option value="" disabled selected></option>
                <option>Engine</option>
                <option>Lights</option>
                <option>Tyres</option>
            </select>
        </div>

        <div class="row">
            <label for="defectDescription">Defect Description</label>
            <textarea name="defectDescription" class="form-control" placeholder="Enter description"></textarea>
        </div>

        <div class="row">
            <label for="driverSignature">Driver Signature</label>
            <textarea name="driverSignature" class="form-control" placeholder="Enter signature"></textarea>
        </div>

        <div class="row">
            <button id="defectReportSubmit" class="btn btn-custom">Submit Defect Report</button>
        </div>

        <div class="row">
            <button class="mainMenu btn btn-default">Main Menu</button>
        </div>

    </form>

    <form id="defectReportSubmittedView">

        <p>
            Thank you for submitting your defect report to Ryder.
        </p>

        <ul>
            <li>Vehicle: <span class="vehicleUnitNumber"></span></li>
            <li>Category: <span class="vehicleCheckSubmittedDate">01/01/2017</span></li>
            <li>Time: <span class="vehicleCheckSubmittedTime">01/01/2017</span></li>
            <li>Driver: <span class="driverFullName"></span></li>
        </ul>

        <p>
            Your defect report will be uploaded to Ryder when your mobile device has a sufficient network connection.
        </p>

        <div class="row">
            <button class="mainMenu btn btn-custom">Main Menu</button>
        </div>

        <div class="row">
            <button class="startOver btn btn-default">Start Over</button>
        </div>

    </form>

    <form id="recentDefectsView">
        <h1>Recent Defects</h1>

        <p class="unitInfo">
            Unit
        </p>

        <table class="table table-responsive table-bordered">
            <thead>
            <tr>
                <th>Date</th>
                <th>Driver</th>
                <th>Start</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>01/02/17</td>
                <td>Example</td>
                <td>18:55</td>
            </tr>
            <tr>
                <td>05/02/17</td>
                <td>Example</td>
                <td>16:09</td>
            </tr>
            <tr>
                <td>09/02/17</td>
                <td>Example</td>
                <td>11:14</td>
            </tr>
            </tbody>
        </table>

        <div class="row">
            <button class="mainMenu btn btn-custom">Main Menu</button>
        </div>
    </form>

    <form id="qrCode">
        <h1>Scan QR Code</h1>

        <p>
            This function will, when developed, allow the user to take a photo of a QR code with their mobile device,
            which will translate to the unit number of a vehicle.
        </p>

        <div class="row">
            <button class="startOver btn btn-custom">Start Over</button>
        </div>

    </form>

@stop