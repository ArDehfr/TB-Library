<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Side Navigation Bar</title>
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        function logout() {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, log out!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            })
        }

        function confirmContinue(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Confirm Borrowing',
                text: "Are you sure you want to borrow this book?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, continue!'
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchBar = document.getElementById('search-bar');
            searchBar.addEventListener('input', searchBooks);
        });

        function searchBooks() {
            const query = document.getElementById('search-bar').value.toLowerCase();
            const bookCards = document.querySelectorAll('.card-pay');

            bookCards.forEach(card => {
                const title = card.querySelector('.card-body-pay p strong').nextSibling.textContent.toLowerCase();

                if (title.includes(query)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</head>
<body>
<div class="wrapper">
    <div class="sidebar">
        <h2>TB Library</h2>
        <ul>
            <li><a href="{{ route('home') }}"><i class="fas fa-home"></i>Discover</a></li>
            <li><a href="{{ route('home.fav') }}"><i class="fas fa-heart"></i>Favourite</a></li>
            <li><a style="color: white; font-weight:600" href=""><i class="fas fa-money-bill-wave"></i>Payment</a></li>
            <li><a href="{{ route('home.download') }}"><i class="fas fa-bell"></i>Notifications</a></li>
        </ul>
        <hr style="border-color: #27374D">
        <ul style="margin-bottom: 173px">
            <li><a href="{{ route('profile.edit') }}"><i class="fas fa-user"></i>Profile</a></li>
        </ul>

        <ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <li><a href="#" onclick="logout()"><i class="fas fa-sign-out-alt"></i>Log Out</a></li>
        </ul>
    </div>
    <div class="main_content">
        <div class="header">
            <div class="search-container">
                <i class="fas fa-search"></i>
                <input id="search-bar" style="width: 250px" type="text" class="search-bar" placeholder="Search your favourite books">
            </div>
            <div class="dropdown">
                <button class="dropbtn">
                    <div style="display: flex">
                        @if (Auth::user()->picture)
                            <img src="{{ asset(Auth::user()->picture) }}" alt="{{ Auth::user()->name }}" style="width: 2rem; height: 2rem; border-radius: 9999px; object-fit: cover; margin-right: 0.5rem;">
                        @else
                            <img src="{{ asset('assets/user.png') }}" alt="Default Profile Picture" style="width: 2rem; height: 2rem; border-radius: 9999px; object-fit: cover; margin-right: 0.5rem;">
                        @endif
                        <div style="margin-top: 3px; margin-left:5px;">
                            {{ Auth::user()->name }}
                            <i style="margin-left: 5px" class="fas fa-caret-down"></i>
                        </div>
                    </div>
                </button>
                <div class="dropdown-content">
                    <a href="{{ route('profile.edit') }}">Profile</a>
                    <li class="nav-link">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button onclick="logout()" type="submit" class="drpbtn">Log out</button>
                        </form>
                    </li>
                </div>
            </div>
        </div>

        <div class="payments">
            <?php $payments = App\Models\Payment::where('user_id', auth()->id())->get() ?>
            @foreach($payments as $payment)
                <div class="card-pay {{ $payment->payment_status == '1' ? 'none' : '' }}">
                    <div class="card-header-pay">
                        <h3>Payment ID: {{ $payment->id }}</h3>
                    </div>
                    <div class="card-body-pay">
                        <p><strong>Book Title:</strong> {{ $payment->borrowing->book->book_name }}</p>
                        <p><strong>User:</strong> {{ $payment->borrowing->user->name }}</p>
                        <p><strong>Amount:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                    </div>
                    <div class="card-footer-pay {{ $payment->payment_status == '1' ? 'pay-off-button' : '' }}">
                        <button class="" onclick="openPaymentGateway({{ $payment->id }})">{{ $payment->payment_status == '1' ? 'Pay Off' : 'Pay' }}</button>
                    </div>
                </div>
            @endforeach
        </div>

        @include('user.__payment')

        <script>
            function openPaymentGateway(paymentId) {
                const modal = document.getElementById('paymentGatewayModal');
                modal.style.display = 'block';

                const confirmButton = document.getElementById('confirmPaymentButton');
                confirmButton.onclick = function() {
                    confirmPayment(paymentId);
                }
            }

            function closePaymentGateway() {
                const modal = document.getElementById('paymentGatewayModal');
                modal.style.display = 'none';
            }

            function confirmPayment(paymentId) {
                fetch(`/payments/${paymentId}/pay`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ payment_status: true })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Payment successful!');
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Payment failed. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Payment failed. Please try again.');
                });

                closePaymentGateway();
            }

            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('paymentGatewayModal');
                const closeModal = document.getElementsByClassName('close')[0];

                closeModal.onclick = function() {
                    modal.style.display = 'none';
                }

                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = 'none';
                    }
                }
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closePaymentGateway();
                }
            });
        </script>
    </div>
</div>
<style>
    .none {
        display: none;
    }

    .card-pay {
        background-color: #192a3a;
        padding: 20px;
        border-radius: 10px;
        width: 90%;
        margin: 20px auto;
        color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .card-header-pay {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .card-body-pay {
        font-size: 1rem;
        margin-bottom: 10px;
    }

    .card-footer-pay {
        display: flex;
        justify-content: flex-end;
    }

    .card-footer-pay button {
        background-color: #FFB319;
        color: #192a3a;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
    }

    .card-footer-pay button:hover {
        background-color: #e0a00e;
    }

    .pay-off-button button {
        background-color: #4CAF50;
        color: white;
        z-index: 9999;
        cursor: not-allowed;
    }

    .pay-off-button button:hover {
        background-color: #45a049;
    }

    .payments {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
    }

    .content1 {
        background-color: #192a3a;
        padding: 20px;
        border-radius: 10px;
        max-width: 800px;
        right: 50%;
        transform: translateX(20%);
        margin-top: 50px;
    }

    .content1 h2 {
        font-weight: 600;
        margin-bottom: 10px;
        color: white;
    }

    .content1 p {
        margin-bottom: 20px;
        font-size: 14px;
        color: #9a9a9a;
    }

    .content1 form {
        display: flex;
        flex-direction: column;
    }

    .content1 form div {
        margin-bottom: 15px;
        display: flex;
        flex-direction: column;
    }

    .content1 form label {
        font-size: 14px;
        margin-bottom: 5px;
        color: rgba(255, 255, 255, 0.710);
    }

    .content1 form input, .content1 form select {
        padding: 10px;
        border: 1px solid #9a9a9a;
        border-radius: 5px;
        background-color: #27374D;
        color: rgb(255, 255, 255);
    }

    .content1 form button {
        background-color: #FFB319;
        color: #192a3a;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 600;
    }

    .content1 form button:hover {
        background-color: #e0a00e;
    }
</style>
</body>
</html>
