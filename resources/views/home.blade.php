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

        function showBookDetails(book) {
            const aside = document.querySelector('aside');
            let description = book.synopsis.split(' ').slice(0, 15).join(' ');
            if (book.synopsis.split(' ').length > 15) {
                description += '...';
            }

            const bookDetailsHTML = `
                <div class="aside-content">
                    <img src="{{ asset('fotobuku/') }}/${book.book_cover}" alt="${book.book_name}" style="width:160px; height:220px;">
                    <br>
                    <p><strong>${book.book_name}</strong></p>
                    <br>
                    <p><strong>Author:</strong> ${book.writer}</p>
                    <br>
                    <p><strong>Description:</strong> ${description}</p>
                    <button onclick='readBook(${book.book_id})'>Read Book</button>
                    <button onclick='showBorrowModal(${JSON.stringify(book.book_id)}, "${book.book_name.replace(/"/g, '&quot;')}")'>Borrow Book</button>
                </div>
            `;
            aside.innerHTML = bookDetailsHTML;
        }

        function readBook(bookId) {
            window.location.href = `/read/${bookId}`;
        }

        function showBorrowModal(bookId, bookName) {
            console.log('showBorrowModal called with:', bookId, bookName); // Debugging
            const modal = document.getElementById('borrow-modal');
            modal.style.display = 'block';
            document.getElementById('modal-book-id').value = bookId;
            document.getElementById('modal-book-name').textContent = bookName;
        }

        function closeBorrowModal() {
            console.log('closeBorrowModal called'); // Debugging
            document.getElementById('borrow-modal').style.display = 'none';
        }
    </script>
    <style>
        .aside-content {
            margin-top: 30px;
            margin-bottom: 30px;
            display: flex;
            flex-direction: column;
            color: white;
            align-items: center;
            padding-left: 30px;
            padding-right: 30px
        }

        .book-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 15px;
            margin-left: 18px
        }

        .book-card {
            background-color: #fff;
            color: #000;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 130px;
            height: 220px;
            text-align: center;
            cursor: pointer;
        }

        .book-card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 5px;
        }

        .book-card h5 {
            font-size: 14px;
        }

        .book-card p {
            color: #666;
            margin-bottom: 2px;
            font-size: 10px
        }

        .book-card button {
            background-color: #3085d6;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .book-card button:hover {
            background-color: #2874a6;
        }

        /* Modal styles */
        #borrow-modal {
            display: none;
            position: fixed;
            z-index: 1000;
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
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
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
    </style>
</head>
<body>

<div class="wrapper">
    <div class="sidebar">
        <h2>TB Library</h2>
        <ul>
            <li><a style="color: white; font-weight:600" href="#"><i class="fas fa-home"></i>Discover</a></li>
            <li><a href="{{ route('home.fav') }}"><i class="fas fa-heart"></i>Favourite</a></li>
            <li><a href="{{ route('home.lib') }}"><i class="fas fa-book"></i>My Library</a></li>
            <li><a href="{{ route('home.download') }}"><i class="fas fa-bell"></i>Approval</a></li>
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
        <div class="content">
            <div class="content-main">
                <div style="display: flex; flex-direction:column;" class="content2">
                    <div style="display: flex; align-items:center; justify-content:space-around; gap:460px; margin-top:15px" class="text-recommended">
                        <h1 style="font-size: 20px">Recommended</h1>
                        <a href="">see all</a>
                    </div>
                    <div class="book-cards">
                        <?php $books = App\Models\Book::all(); ?>
                        @foreach($books as $book)
                            @if($book->book_id <= 6)
                                <?php
                                    $maxWords = 2;
                                    $bookNameArray = explode(' ', $book->book_name);
                                    $bookNameShortened = implode(' ', array_slice($bookNameArray, 0, $maxWords));
                                    if (count($bookNameArray) > $maxWords) {
                                        $bookNameShortened .= '...';
                                    }
                                ?>
                                <div class="book-card" style="z-index: 999" onclick="showBookDetails({{ json_encode($book) }})">
                                    <img src="{{ asset('fotobuku/' . $book->book_cover) }}" alt="{{ $book->book_name }}" style=" width:110px; height:150px;">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $bookNameShortened }}</h5>
                                        <p class="card-text">{{ $book->writer }}</p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                </div>
                <div class="content3">
                    <h1></h1>
                </div>
            </div>
            <aside>
                <div class="aside-content">
                    <h2>Book Details</h2>
                    <p style="text-align: center">Click on a book to see the details here.</p>
                </div>
            </aside>
        </div>
    </div>
</div>

<!-- Borrow Modal -->
<div id="borrow-modal">
    <div class="modal-content">
        <span class="close" onclick="closeBorrowModal()">&times;</span>
        <h2>Borrow a Book</h2>
        <form action="{{ route('borrow.book') }}" method="POST">
            @csrf
            <div>
                <label for="day_rent">Day Rent:</label>
                <input type="date" id="day_rent" name="day_rent" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" readonly>
            </div>
            <div>
                <label for="book">Select Book:</label>
                <input type="hidden" id="modal-book-id" name="book">
                <p id="modal-book-name"></p>
            </div>
            <button type="submit">Continue</button>
        </form>
    </div>
</div>

</body>
</html>
