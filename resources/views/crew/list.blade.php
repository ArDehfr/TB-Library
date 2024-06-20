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
        // Function to handle approval of borrowing requests
        function approveBorrowing(borrowingId) {
        Swal.fire({
            title: 'Approve Borrow Request',
            html: '<label for="day_return">Return Date:</label>' +
                  '<input type="date" id="day_return" class="swal2-input">',
            preConfirm: () => {
                const dayReturn = Swal.getPopup().querySelector('#day_return').value;
                if (!dayReturn) {
                    Swal.showValidationMessage('You need to provide a return date');
                }
                return { dayReturn: dayReturn };
            },
            showCancelButton: true,
            confirmButtonText: 'Approve',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/borrow/approve/${borrowingId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ day_return: result.value.dayReturn }),
                })
                .then(response => handleResponse(response, 'Approved!', 'The borrowing request has been approved.'))
                .then(() => {
                    location.reload(); // Refresh the page
                });
            }
        });
    }

        // Function to handle returning borrowed books
        function returnBorrowing(borrowingId) {
            fetch(`/borrow/return/${borrowingId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
            .then(response => handleResponse(response, 'Returned!', 'The book has been returned.'))
            .then(() => {
                location.reload(); // Refresh the page
            });
        }

        function handleAction(event, id, action) {
            event.preventDefault();
            if (action === 'reject') {
                handleReject(event, id);
            } else {
                fetch(`/borrow/${action}/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => response.json()).then(data => {
                    if (data.status) {
                        location.reload();
                    }
                });
            }
        }

        // Function to handle reject borrowing requests
        function handleReject(event, id) {
            Swal.fire({
                title: 'Reject Borrow Request',
                input: 'text',
                inputLabel: 'Reason for rejection',
                inputPlaceholder: 'Enter your reason here...',
                showCancelButton: true,
                confirmButtonText: 'Reject',
                cancelButtonText: 'Cancel',
                preConfirm: (reason) => {
                    if (!reason) {
                        Swal.showValidationMessage('You need to provide a reason for rejection');
                    } else {
                        return reason;
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/borrow/reject/${id}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ rejection_reason: result.value })
                    }).then(response => response.json()).then(data => {
                        if (data.status) {
                            location.reload();
                        }
                    });
                }
            });
        }

        // Function to handle logout
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

        // Function to handle response from fetch request
        function handleResponse(response, title, message) {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json()
                .then(data => {
                    if (data.status === 'borrowed' || data.status === 'returned') {
                        // Show success message if borrowing request is approved or book is returned successfully
                        Swal.fire({
                            title: title,
                            text: message,
                            icon: 'success',
                            confirmButtonText: 'OK',
                        }).then(() => {
                            location.reload(); // Refresh the page
                        });
                    } else {
                        throw new Error('Unexpected response');
                    }
                })
                // .catch(error => {
                //     console.error('Error:', error);
                //     // Show error message if there's a problem with the request or response
                //     Swal.fire({
                //         title: 'Error!',
                //         text: 'There was a problem processing the request.',
                //         icon: 'error',
                //         confirmButtonText: 'OK'
                //     });
                // });
        }

        // Function to open the modal
        function openModal(borrowingId) {
            const modal = document.getElementById("reportModal");
            if (modal) {
                modal.style.display = "block";
                document.getElementById("borrowingId").value = borrowingId;
            }
        }

        function initModal() {
            const modal = document.getElementById("reportModal");
            const closeModal = document.getElementsByClassName("close")[0];
            const reportForm = document.getElementById("reportForm");

            if (closeModal) {
                closeModal.onclick = function() {
                    if (modal) {
                        modal.style.display = "none";
                    }
                }
            }

            if (reportForm) {
                reportForm.addEventListener("submit", function(event) {
                    event.preventDefault();

                    const borrowingId = document.getElementById("borrowingId").value;
                    const report = document.getElementById("report").value;
                    const returnStatus = document.getElementById("returnStatus").value;

                    fetch(`/borrow/return/${borrowingId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({ report: report, returnStatus: returnStatus }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'returned') {
                            Swal.fire({
                                title: 'Returned!',
                                text: 'The book has been returned.',
                                icon: 'success',
                                confirmButtonText: 'OK',
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'There was a problem processing the request.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'There was a problem processing the request.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });

                    modal.style.display = "none";
                });
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }

        document.addEventListener("DOMContentLoaded", initModal);

    </script>


</head>
<body>
<div class="wrapper">
    <div class="sidebar">
        <h2>TB Library</h2>
        <ul>
            <li><a href="{{ route('home.crew') }}"><i class="fas fa-home"></i>Discover</a></li>
            <li><a style="color: white; font-weight:600" href="#"><i class="fas fa-list"></i>Waiting List</a></li>
            <li><a href="{{ route('add.crew') }}"><i class="fas fa-book"></i>Book Data</a></li>
            <li><a href="{{ route('data.crew') }}"><i class="fas fa-database"></i>History</a></li>
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
                <input id="search-bar" style="width: 250px" type="text" class="search-bar" placeholder="Search borrowings">
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const searchBar = document.getElementById('search-bar');
                    searchBar.addEventListener('input', searchBooks);
                });

                function searchBooks() {
                    const query = document.getElementById('search-bar').value.toLowerCase();
                    const bookCards = document.querySelectorAll('.card1');

                    bookCards.forEach(card => {
                        const title = card.querySelector('.card1 p').nextSibling.textContent.toLowerCase();

                        if (title.includes(query)) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                }
            </script>
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
                        <form method="POST="{{ route('logout') }}">
                            @csrf
                            <button onclick="logout()" type="submit" class="drpbtn">Log out</button>
                        </form>
                    </li>
                </div>
            </div>
        </div>
        <div style="display: flex; flex-direction:column;" class="content">
            <h2 style="color: white; margin-top:20px;">Borrowings List</h2>
            <div class="borrowings">
                @foreach ($borrowings as $borrowing)
                    <div class="card1">
                        <div class="card {{ $borrowing->status == 'returned' || $borrowing->status == 'rejected' ? 'returned-card' : '' }}">
                            <div class="image-card">
                                @if ($borrowing->user->picture)
                                    <img src="{{ asset($borrowing->user->picture) }}" alt="Profile Picture" style="width: 70px; height: 70px; border-radius: 50%;">
                                @else
                                    <img src="{{ asset('assets/user.png') }}" alt="Default Profile Picture" style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover;">
                                @endif
                            </div>
                            <div style="display: flex; justify-content: space-between; width: 950px; margin-left: 15px;">
                                <div style="margin-left: 20px;" class="card-body">
                                    <p>{{ $borrowing->book->book_name ?? 'Book not found' }}</p>
                                    <p>User: {{ $borrowing->user->name ?? 'User not found' }}</p>
                                    <p>Day Rent: {{ $borrowing->day_rent }}</p>
                                    <p>Status: {{ $borrowing->status }}</p>
                                </div>
                                <div class="card-actions">
                                    @if ($borrowing->status == 'pending')
                                        <button style="display: inline;" class="btn-approved" onclick="approveBorrowing({{ $borrowing->id }})"><i class="fas fa-check fa-2x"></i></button>
                                        <form style="display: inline;" onsubmit="handleAction(event, {{ $borrowing->id }}, 'reject')">
                                            @csrf
                                            <button class="btn-rejected" type="submit"><i class="fas fa-times fa-2x"></i></button>
                                        </form>
                                    @elseif ($borrowing->status == 'borrowed')
                                        <button class="btn-return" onclick="openModal({{ $borrowing->id }})">Return</button>
                                    @else
                                        <button disabled>N/A</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            </div>
        </div>
    </div>
    <!-- Report Modal -->
    <div id="reportModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Submit Report</h2>
            <form id="reportForm">
                @csrf
                <input type="hidden" name="borrowing_id" id="borrowingId">
                <div>
                    <label for="report">Report:</label>
                    <textarea name="report" id="report" required></textarea>
                </div>
                <label for="returnStatus">Return Status:</label>
                    <select name="returnStatus" id="returnStatus">
                        <option value="good">Good</option>
                        <option value="late">Late</option>
                        <option value="damaged">Damaged</option>
                        <option value="lost">Lost</option>
                    </select>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
    <style>
        /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 30%;
        position: relative;
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
        .content {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
        }

        .card1 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 950px;
            max-width: 850px;
            margin-left: 35px;
        }

        .card {
            background-color: #618096;
            border-radius: 20px;
            padding: 20px 50px 20px 20px;
            width: 950px;
            max-width: 850px;
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        .image-card {
            margin-left: 15px;
            margin-top: 5px;
        }

        .returned-card {
            display: none;
        }

        .card-header {
            font-size: 1.1em;
            color: #e1e9ee;
        }

        .card-body {
            color: #e1e9ee;
        }

        .card-body p {
            margin: 0;
        }

        .card-body p:not(:last-child) {
            margin-bottom: 10px;
        }

        .card-actions {
            margin-left: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-approved, .btn-rejected, .btn-return {
            border: none;
            width: 70px;
            height: 70px;
            border-radius: 15px;
            cursor: pointer;
            color: #e1e9ee;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-approved {
            background-color: #0ea500;
        }

        .btn-approved:hover {
            background-color: #0b8500;
        }

        .btn-return {
            background-color: #c7c400;
            color: black;
            font-weight: 600;
            font-size: 18px;
            height: 50px;
            width: 140px;
        }

        .btn-return:hover {
            background-color: #999600;
        }

        .btn-rejected {
            background-color: #c40000;
        }

        .btn-rejected:hover {
            background-color: #a00000;
        }
    </style>

    </body>
    </html>


