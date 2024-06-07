<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Favorite Books</title>
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
                  if (!isFavorited) {
                      document.getElementById(`book-card-${bookId}`).remove();
                  }
              });
        }
    </script>
    <style>
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
            font-size: 10px;
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

        .favorite-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.5rem;
            color: #e74c3c;
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
    </style>
</head>
<body>
<div class="wrapper">
    <div class="sidebar">
        <h2>TB Library</h2>
        <ul>
            <li><a href="{{ route('home') }}"><i class="fas fa-home"></i>Discover</a></li>
            <li><a style="color: white; font-weight:600" href="#"><i class="fas fa-heart"></i>Favourite</a></li>
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
            <h1 style="font-size: 20px; margin-top: 20px; margin-left: 20px;">My Favorite Books</h1>
            <div class="book-cards">
                <?php $favorites = Auth::user()->favorites()->get(); ?>
                @foreach($favorites as $book)
                    <?php
                        $maxWords = 2;
                        $bookNameArray = explode(' ', $book->book_name);
                        $bookNameShortened = implode(' ', array_slice($bookNameArray, 0, $maxWords));
                        if (count($bookNameArray) > $maxWords) {
                            $bookNameShortened .= '...';
                        }
                    ?>
                    <div class="book-card" id="book-card-{{ $book->book_id }}">
                        <img src="{{ asset('fotobuku/' . $book->book_cover) }}" alt="{{ $book->book_name }}" style="width:110px; height:150px;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $bookNameShortened }}</h5>
                            <p class="card-text">{{ $book->writer }}</p>
                            <button class="favorite-btn" onclick='toggleFavorite({{ $book->book_id }}, event)'>
                                <i class="fas fa-heart" id="heart-icon-{{ $book->book_id }}"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
</body>
</html>
