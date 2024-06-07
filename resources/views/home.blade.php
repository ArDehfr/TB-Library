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

        function showBookDetails(book) {
            const aside = document.querySelector('aside');
            let description = book.synopsis.split(' ').slice(0, 25).join(' ');
            if (book.synopsis.split(' ').length > 25) {
                description += '...';
            }

            const bookDetailsHTML = `
                <div class="aside-content">
                    <img src="{{ asset('fotobuku/') }}/${book.book_cover}" alt="${book.book_name}" style="width:160px; height:220px; border-radius:5px;">
                    <p style="text-align:center; margin-top:10px; margin-bottom:5px"><strong>${book.book_name}</strong></p>
                    <p style="margin-bottom:5px"><strong></strong> ${book.writer}</p>
                    <p style="text-align:center; margin-bottom:15px"><strong></strong> ${description}</p>
                    <button onclick='readBook(${book.book_id})'>Read Book</button>
                    <button onclick='showBorrowModal(${book.book_id}, "${book.book_name.replace(/"/g, '&quot;')}")'>Borrow Book</button>
                </div>
            `;
            aside.innerHTML = bookDetailsHTML;
        }

        function readBook(bookId) {
            window.location.href = `/read/${bookId}`;
        }

        function showBorrowModal(bookId, bookName) {
            const modal = document.getElementById('borrow-modal');
            modal.style.display = 'block';
            document.getElementById('modal-book-id').value = bookId;
            document.getElementById('modal-book-name').textContent = bookName;
        }

        function closeBorrowModal() {
            document.getElementById('borrow-modal').style.display = 'none';
        }

        function toggleFavorite(bookId, event) {
            event.stopPropagation();
            const heartIcon = document.getElementById(`heart-icon-${bookId}`);
            const isFavorited = heartIcon.classList.contains('fas');

            fetch(`/favorite/${bookId}`, {
                method: isFavorited ? 'DELETE' : 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            }).then(response => response.json())
              .then(data => {
                  console.log(data.message);
                  heartIcon.classList.toggle('fas');
                  heartIcon.classList.toggle('far');
              });
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
            padding-right: 30px;
        }

        .book-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 15px;
            margin-left: 18px;
        }

        .book-card {
            background-color: #fff;
            color: #000;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 130px;
            height: 220px;
            text-align: center;
            cursor: pointer;
            position: relative;
            transition: transform 0.3s;
        }

        .book-card:hover {
            transform: scale(1.1);
        }

        .book-card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: transform 0.3s;
        }

        .book-card:hover img {
            transform: scale(1.1);
        }

        .book-card h5 {
            font-size: 14px;
        }

        .book-card p {
            color: #666;
            margin-bottom: 2px;
            font-size: 10px;
        }

        .book-card button {
            background-color: #00000096;
            color: #fff;
            border: none;
            padding: 0px 5px;
            border-radius: 5px;
            cursor: pointer;
        }

        .book-card button:hover {
            background-color: #000000c2;
        }

        .favorite-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.5rem;
            color: #e74c3c;
            z-index: 200;
            position: absolute;
            top: 8px;
            right: 8px;
            display: none;
        }

        .favorite-btn .fa-heart {
            transition: color 0.3s;
        }

        .favorite-btn .fa-heart.fas {
            color: #e74c3c;
        }

        .favorite-btn .fa-heart.far {
            color: #ccc;
        }

        .book-card:hover .favorite-btn {
            display: block;
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
            <li><a href="{{ route('home.lib') }}"><i class="fas fa-money-bill-wave"></i>Payment</a></li>
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
            <li><a href="#" onclick="logout(event)"><i class="fas fa-sign-out-alt"></i>Log Out</a></li>
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
                        <?php $favorites = Auth::user()->favorites->pluck('book_id')->toArray(); ?>
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
                                    <button class="favorite-btn" onclick='toggleFavorite({{ $book->book_id }}, event)'>
                                        <i class="{{ in_array($book->book_id, $favorites) ? 'fas' : 'far' }} fa-heart" id="heart-icon-{{ $book->book_id }}"></i>
                                    </button>
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
