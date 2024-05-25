<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Side Navigation Bar</title>
    <link rel="stylesheet" href="{{ asset('css/add-crew.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
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

        function handleAction(event, id, action) {
            event.preventDefault();
            fetch(`/borrow/${action}/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(response => response.json()).then(data => {
                if (data.status) {
                    const row = document.querySelector(`#action-${id}`).closest('tr');
                    if (data.status === 'Returned' || data.status === 'Rejected') {
                        row.remove();
                    } else {
                        row.querySelector('.status-cell').innerText = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                        row.querySelectorAll('form').forEach(form => form.remove());
                    }
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
            <li><a href="{{ route('home.crew') }}"><i class="fas fa-home"></i>Discover</a></li>
            <li><a style="color: white; font-weight:600" href="#"><i class="fas fa-list"></i>Book List</a></li>
            <li><a href="{{ route('add.crew') }}"><i class="fas fa-book"></i>Add Book</a></li>
            <li><a href="{{ route('data.crew') }}"><i class="fas fa-database"></i>Customer Data</a></li>
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
                <input style="width: 250px" type="text" class="search-bar" placeholder="Search your favourite books">
            </div>
            <div class="dropdown">
                <button class="dropbtn">
                    {{ Auth::user()->name }}
                    <i style="margin-left: 5px" class="fas fa-caret-down"></i>
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
        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th>Book Name</th>
                        <th>Borrower</th>
                        <th>Day Rent</th>
                        <th>Day Return</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $borrowings = App\Models\Borrowing::all() ?>
                    @foreach ($borrowings as $borrowing)
                    <tr class="{{ $borrowing->status == 'returned' ? 'returned-row' : ($borrowing->status == 'rejected' ? 'returned-row' : '') }}">
                        <td>{{ $borrowing->book->book_name }}</td>
                        <td>{{ $borrowing->user->name }}</td>
                        <td>{{ $borrowing->day_rent }}</td>
                        <td>{{ $borrowing->day_return }}</td>
                        <td class="status-cell" id="action-{{ $borrowing->id }}">
                            @if ($borrowing->status == 'pending')
                                <form style="display: inline;" onsubmit="handleAction(event, {{ $borrowing->id }}, 'approve')">
                                    @csrf
                                    <button type="submit">Approve</button>
                                </form>
                                <form style="display: inline;" onsubmit="handleAction(event, {{ $borrowing->id }}, 'reject')">
                                    @csrf
                                    <button type="submit">Reject</button>
                                </form>
                            @else
                                {{ ucfirst($borrowing->status) }}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
    /* Styling for table */
    table {
        border-collapse: collapse;
        width: 90%;
        margin-top: 20px;
        background-color: white;
    }

    th, td {
        border: 1px solid #A9C2D4;
        text-align: left;
        padding: 8px;
    }

    th {
        background-color: #A9C2D4;
    }

    /* Styling for status cell */
    .status-cell form {
        display: inline;
    }

    /* Styling for returned rows */
    .returned-row {
        display: none;
    }
</style>
</body>
</html>
