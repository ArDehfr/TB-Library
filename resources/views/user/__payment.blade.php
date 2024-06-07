<!-- Native Modal -->
<div id="paymentGatewayModal" class="modal">
    <div class="modal-content">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <span>Payment Method</span>
                    <div class="card">
                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header p-0" id="headingTwo">
                                    <h2 class="mb-0">
                                        <button style="border-radius:15px;" class="btn btn-light btn-block text-left p-3 rounded-0 border-bottom-custom" type="button" onclick="toggleCollapse('collapseTwo')">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span style="margin-top: 5px">Gopay</span>
                                                <img src="{{ asset('gopay.png') }}" width="30">
                                            </div>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseTwo" class="collapse">
                                    <div class="card-body">
                                        <input type="number" class="form-control" placeholder="Gopay Number">
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header p-0">
                                    <h2 class="mb-0">
                                        <button style="border-radius:15px;" class="btn btn-light btn-block text-left p-3 rounded-0" type="button" onclick="toggleCollapse('collapseOne')">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span style="margin-top: 10px">Credit card</span>
                                                <div class="icons">
                                                    <img src="{{ asset('bca.png') }}" width="30" style="margin-top:5px;">
                                                    <img src="{{ asset('mandiri.png') }}" width="30" style="margin-top:5px; margin-bottom: 4px">
                                                    <img src="{{ asset('bni.png') }}" width="30" style="margin-top:5px; margin-bottom: 4px">
                                                    <img src="{{ asset('visa.png') }}" width="30"style="margin-bottom: 5px">
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseOne" class="collapse show">
                                    <div class="card-body payment-card-body">
                                        <span class="font-weight-normal card-text">Card Number</span>
                                        <div class="input">
                                            <i class="fa fa-credit-card"></i>
                                            <input type="text" class="form-control" placeholder="0000 0000 0000 0000">
                                        </div>
                                        <div class="row mt-3 mb-3">
                                            <div class="col-md-6">
                                                <span class="font-weight-normal card-text">Expiry Date</span>
                                                <div class="input">
                                                    <i class="fa fa-calendar"></i>
                                                    <input type="text" class="form-control" placeholder="MM/YY">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <span class="font-weight-normal card-text">CVC/CVV</span>
                                                <div class="input">
                                                    <i class="fa fa-lock"></i>
                                                    <input type="text" class="form-control" placeholder="000">
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-muted certificate-text"><i class="fa fa-lock"></i> Your transaction is secured with ssl certificate</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <span>Summary</span>
                    <div class="card">
                        <div class="d-flex justify-content-between p-3">
                            <div class="d-flex flex-column">
                                <span><strong>Total</strong></span>
                            </div>
                            <div class="mt-1">
                                <sup class="super-price">Rp {{ number_format($payment->amount, 0, ',', '.') }}</sup>
                            </div>
                        </div>
                        <hr style="width:100%;" class="mt-0 line">
                        <div class="p-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Status</span>
                                <span>{{ $payment->borrowing->status_return }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Rent Price <i class="fa fa-clock-o"></i></span>
                                <span>Rp {{ number_format($payment->borrowing->book->rent_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="p-3">
                            <button class="btn btn-primary btn-block free-button" id="confirmPaymentButton">Confirm Payment</button>
                            <div class="text-center">
                                <a onclick="closePaymentGateway()">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

.modal-content {
    border-radius: 20px;
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    height: 80%; /* Fixed height */
    width: 50%; /* Could be more or less, depending on screen size */
    overflow-y: auto; /* Add vertical scrollbar if content overflows */
}




.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

@import url("https://fonts.googleapis.com/css2?family=Poppins:weight@100;200;300;400;500;600;700;800&display=swap");

.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.card {
    border: none;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    margin: 1rem 0;
    width: 100%;
}

.card-header {
    padding: .5rem 1rem;
    margin-bottom: 0;
    background-color: rgba(0,0,0,.03);
    border-bottom: none;
}

.btn-light {
    display: block;
    width: 100%;
    text-align: left;
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
    padding: 0.5rem 1rem;
    margin-bottom: 1rem;
    cursor: pointer;
}

.btn-light:focus {
    color: #212529;
    background-color: #e2e6ea;
    border-color: #dae0e5;
    box-shadow: 0 0 0 0.2rem rgba(216,217,219,.5);
}

.form-control {
    height: 50px;
    border: 2px solid #eee;
    border-radius: 6px;
    font-size: 14px;
    padding: 0 0.75rem;
    width: calc(100% - 20px);
    margin: 10px;
}

.form-control:focus {
    color: #495057;
    background-color: #fff;
    border-color: #039be5;
    outline: 0;
    box-shadow: none;
}

.input {
    position: relative;
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.input i {
    position: absolute;
    left: 16px;
    color: #989898;
}

.input input {
    text-indent: 25px;
}

.card-text {
    font-size: 13px;
    margin-left: 6px;
}

.certificate-text {
    font-size: 12px;
    color: #6c757d;
}

.billing {
    font-size: 11px;
    color: #007bff;
    text-decoration: none;
}

.super-price {
    font-size: 22px;
    font-weight: bold;
}

.super-month {
    font-size: 11px;
    color: #6c757d;
}

.line {
    border-top: 1px solid #949494;
    margin: 0;
}

.free-button {
    background: #1565c0;
    color: white;
    height: 52px;
    font-size: 15px;
    border-radius: 8px;
    border: none;
    width: 100%;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.free-button:hover {
    background: #0d47a1;
}

.payment-card-body {
    flex: 1 1 auto;
    padding: 24px 1rem !important;
}

.row {
    display: flex;
    flex-wrap: wrap;
    margin: -1rem 0;
}

.col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
    padding: 0 1rem;
}

.d-flex {
    display: flex;
}

.justify-content-between {
    justify-content: space-between;
}

.mt-1 {
    margin-top: 0.25rem;
}

.mt-3 {
    margin-top: 1rem;
}

.mb-2 {
    margin-bottom: 0.5rem;
}

.mb-3 {
    margin-bottom: 1rem;
}

.mb-5 {
    margin-bottom: 3rem;
}

.text-left {
    text-align: left;
}

.text-center {
    text-align: center;
    cursor: pointer;
}

.font-weight-normal {
    font-weight: normal;
}

.text-muted {
    color: #6c757d;
}

.collapse {
    height: 0;
    overflow: hidden;
    transition: height 0.3s ease;
}

.collapse.show {
    height: auto;
}
</style>

<script>
    function toggleCollapse(id) {
        var element = document.getElementById(id);
        var isShown = element.classList.contains('show');
        var allCollapses = document.querySelectorAll('.collapse');

        allCollapses.forEach(function(collapse) {
            collapse.classList.remove('show');
        });

        if (!isShown) {
            element.classList.add('show');
        }
    }

    function closePaymentGateway() {
        document.getElementById('paymentGatewayModal').style.display = 'none';
    }

    document.getElementById('confirmPaymentButton').addEventListener('click', function() {
        // Add your payment confirmation logic here
        alert('Payment Confirmed');
    });
    </script>
