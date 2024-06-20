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
    <script>
        function logout(event) {
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

        function showRejectionReason(reason) {
            Swal.fire({
                title: 'Rejection Reason',
                text: reason,
                icon: 'info',
                confirmButtonText: 'OK'
            });
        }
    </script>

<script>
    function searchTable() {
            const query = document.getElementById('search-bar').value.toLowerCase();
            const rows = document.querySelectorAll('table tbody tr');

            rows.forEach(row => {
                const cells = row.getElementsByTagName('td');
                let match = false;
                for (let i = 0; i < cells.length; i++) {
                    if (cells[i].textContent.toLowerCase().includes(query)) {
                        match = true;
                        break;
                    }
                }
                row.style.display = match ? '' : 'none';
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('search-bar').addEventListener('input', searchTable);
        });
</script>

</head>
<body>
<div class="wrapper">
    <div class="sidebar">
        <h2>TB Library</h2>
        <ul>
            <li><a href="{{ route('home') }}"><i class="fas fa-home"></i>Discover</a></li>
            <li><a href="{{ route('home.fav') }}"><i class="fas fa-heart"></i>Favourite</a></li>
            <li><a href="{{ route('home.lib') }}"><i class="fas fa-money-bill-wave"></i>Payment</a></li>
            <li><a style="color: white; font-weight:600" href="#"><i class="fas fa-bell"></i>Notifications</a></li>
        </ul>
        <hr style="border-color: #27374D">
        <ul style="margin-bottom: 173px">
            <li><a href="{{ route('profile.edit') }}"><i class="fas fa-user"></i>Profile</a></li>
        </ul>

        <ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <li><a href="#" onclick="logout(event)"><i class="fas fa-sign-out-alt"></i>Log Out</a></li>
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
                            <button onclick="logout(event)" type="submit" class="drpbtn">Log out</button>
                        </form>
                    </li>
                </div>
            </div>
        </div>
        <div style="display: flex; flex-direction:column;" class="content">
            <h2 style="color: white; margin-top:20px;">Book History</h2>
            <?php $borrowings = App\Models\Borrowing::where('user_id', auth()->id())->get(); ?>
            <table>
                <thead>
                    <tr>
                        <th>Book Name</th>
                        <th>User Name</th>
                        <th>Day Rent</th>
                        <th>Day Return</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($borrowings as $borrowing)
                        <tr>
                            <td>{{ optional($borrowing->book)->book_name ?? 'Book not found' }}</td>
                            <td>{{ optional($borrowing->user)->name ?? 'User not found' }}</td>
                            <td>{{ $borrowing->day_rent }}</td>
                            <td>{{ $borrowing->day_return }}</td>
                            <td class="{{ $borrowing->status == 'returned' ? 'returned-row' : ($borrowing->status == 'rejected' ? 'rejected-row' : 'status') }}" onclick="if ('{{ $borrowing->status }}' === 'rejected') { showRejectionReason('{{ $borrowing->rejection_reason }}') }">
                                {{ $borrowing->status }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<style>
    table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    color: white;
}

table, th, td {
    border: 1px solid #3b4958;
}

th, td {
    padding: 15px;
    text-align: left;
}

th {
    background-color: #27374D;
}

tr:nth-child(even) {
    background-color: #3b4958;
}

tr:nth-child(odd) {
    background-color: #2e3b4e;
}

.status, .returned-row, .rejected-row {
    cursor: pointer;
    color: #ffffff;
}

.returned-row {
    background-color: #28a745;
}

.rejected-row {
    background-color: #dc3545;
}
.status {
    background-color: #dcd935;
}
</style>
</body>
</html>
