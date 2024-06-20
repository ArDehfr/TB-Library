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



        window.onclick = function(event) {
            var modals = document.getElementsByClassName('modal');
            for (var i = 0; i < modals.length; i++) {
                if (event.target == modals[i]) {
                    modals[i].style.display = "none";
                }
            }
        }
    </script>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #151f29;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            color: white;
            border-radius: 8px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #fff;
            text-decoration: none;
            cursor: pointer;
        }

        .form-group label {
            color: #ddd;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            background-color: #495057;
            color: #ddd;
            border: none;
            padding: 8px;
            width: 100%;
            border-radius: 4px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            color: white;
            cursor: pointer;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            padding: 10px 20px;
            color: white;
            cursor: pointer;
        }

        .btn:hover {
            opacity: 0.8;
        }

        .content {
            padding: 20px;
            background: #151f29;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -15px;
        }

        .col-12 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #151f29;
            color: #ddd;
            background-clip: border-box;
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 0.25rem;
            margin-bottom: 1rem;
            width: 100%;
        }

        .card-header {
            padding: 0.75rem 1.25rem;
            margin-bottom: 0;
            background-color: rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }

        .card-body {
            display: flex;
            flex: 1 1 auto;
            padding: 1.25rem;
            flex-wrap: wrap;
        }

        .text-left {
            text-align: left;
        }

        .card-category,
        .card-title {
            margin: 0;
        }

        .mb-3 {
            margin-bottom: 1rem !important;
        }

        .btn {
            display: inline-block;
            font-weight: 400;
            color: #212529;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-danger {
            color: #fff;
            background-color: #ff0000;
            border-color: #ff0000;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }

        .book-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 16px;
            max-width: 250px;
        }

        .book-card-header {
            padding: 8px;
            text-align: center;
            background-color: #f8f9fa;
        }

        .book-card-body {
            padding: 16px;
        }

        .book-card-title {
            font-size: 1.25rem;
            margin-bottom: 8px;
        }

        .book-card-text {
            margin-bottom: 8px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="sidebar">
            <h2>TB Library</h2>
            <ul>
                <li><a href="{{ route('home.crew') }}"><i class="fas fa-home"></i>Discover</a></li>
                <li><a href="{{ route('list.crew') }}"><i class="fas fa-list"></i>Waiting List</a></li>
                <li><a style="color: white; font-weight:600" href="#"><i class="fas fa-book"></i>Book Data</a></li>
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
                    <input style="width: 250px" type="text" class="search-bar"
                        placeholder="Search your favourite books">
                </div>
                <div class="dropdown">
                    <button class="dropbtn">
                        {{ Auth::user()->name }}
                        <i style="margin-left: 5px" class="fas fa-caret-down"></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="{{ route('profile.edit') }}">Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button onclick="logout()" type="submit" class="drpbtn">Log out</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-chart">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-6 text-left">
                                        <h5 class="card-category">Book Database</h5>
                                        <h2 class="card-title">All Book Data</h2>
                                    </div>
                                </div>
                                <div class="card-header mb-3" style="margin-left: -16px">
                                    <button type="button" class="btn btn-primary"
                                        onclick="location.href='{{ route('crew.add-book') }}'">Add Book</button>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-wrap">
                                <?php
                                $books = App\Models\Book::all();
                                ?>
                                @foreach ($books as $book)
                                    <div class="card mb-3 mx-2 items-center d-flex justify-center"
                                        style="max-width: 250px;">
                                        <div class="card-header">
                                            <img src="{{ asset('fotobuku/' . $book->book_cover) }}"
                                                alt="{{ $book->book_name }}" width="150" height="200">
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $book->book_name }}</h5>
                                            <p class="card-text"><strong>Categories:</strong> {{ $book->categories }}
                                            </p>
                                            <p class="card-text"><strong>Writer:</strong> {{ $book->writer }}</p>
                                            <p class="card-text"><strong>Publisher:</strong> {{ $book->publisher }}</p>
                                            <p class="card-text"><strong>Year:</strong> {{ $book->year }}</p>
                                            <p class="card-text" id="synopsis-{{ $book->id }}"
                                                style="max-height: 100px; overflow: hidden;">
                                                <strong>Synopsis:</strong> {{ $book->synopsis }}
                                            </p>
                                            <p>Rent Price: Rp {{ number_format($book->rent_price, 0, ',', '.') }}</p>
                                            <button type="button" class="btn btn-primary btn-sm"
                                                onclick="openModal({{ $book->book_id }})">
                                                Edit Book
                                            </button>

                                            {{-- <div id="editBookModal_{{ $book->book_id }}" class="modal">
                                                <div class="modal-content">
                                                    <span class="close"
                                                        onclick="closeModal({{ $book->book_id }})">&times;</span>
                                                    </div>
                                                </div> --}}
                                            @include('crew.___edit_book')

                                            <form action="{{ route('books.destroy', ['book' => $book->book_id]) }}"
                                                method="POST" style="display:inline;"
                                                onsubmit="return confirm('Are you sure you want to delete this book?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function openModal(bookId) {
            var modal = document.getElementById('editBookModal_' + bookId);
            modal.style.display = "block";
        }

        function closeModal(bookId) {
            var modal = document.getElementById('editBookModal_' + bookId);
            modal.style.display = "none";
        }
    </script>
</body>

</html>
