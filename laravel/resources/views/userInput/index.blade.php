@extends('layout')

@section('headerInsert')

    <script type="text/javascript">
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

            $('#forwardToStep2').click(function() {

                if (!$('#step1').valid()) {
                    return false;
                }

                $('#step1').slideUp();
                $('#step2').slideDown();
            });

            $('#forwardToStep3').click(function() {

                if (!$('#step2').valid()) {
                    return false;
                }

                $('#unitInfo').html('Unit: ' + $('#unitNumber').val());

                $('#step2').slideUp();
                $('#step3').slideDown();
            });

            $('#startOver').click(function() {
                $('input').val('');
                $('#step2').hide();
                $('#step3').slideUp();
                $('#step1').slideDown();
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
            <button class="btn btn-custom" name="scanQrCode"><img src="{{ asset('images/qr.jpg') }}" id="qrCodeIcon" alt=""></button>
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

        <p id="unitInfo">
            Unit
        </p>

        <div class="row">
            <button class="btn btn-custom">Vehicle Check</button>
        </div>
        <div class="row">
            <button class="btn btn-custom">Recent Checks</button>
        </div>
        <div class="row">
            <button class="btn btn-custom">Damage</button>
        </div>
        <div class="row">
            <button class="btn btn-custom">Defect</button>
        </div>
        <div class="row">
            <button id="startOver" class="btn btn-custom">Start Over</button>
        </div>
    </form>


@stop