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
            aside.innerHTML = `
                <h2>${book.book_name}</h2>
                <img src="{{ asset('fotobuku/') }}/${book.book_cover}" alt="${book.book_name}" style="width:100px; height:150px;">
                <p><strong>Author:</strong> ${book.writer}</p>
                <p><strong>Description:</strong> ${book.synopsis}</p>
            `;
        }
    </script>
</head>
<body>

<div class="wrapper">
    <div class="sidebar">
        <h2>TB Library</h2>
        <ul>
            <li><a style="color: white; font-weight:600" href="#"><i class="fas fa-home"></i>Discover</a></li>
            <li><a href="{{ route('list.crew') }}"><i class="fas fa-list"></i>Book List</a></li>
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
                    {{-- @if (Auth::user()->picture)
                       <img src="{{ asset(Auth::user()->picture) }}" alt="" class="w-8 h-8 rounded-full object-cover mr-2">
                    @endif --}}
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
            <div class="content-main">
                <div style="display: flex; flex-direction:column;" class="content2">
                    <div style="display: flex; align-items:center; justify-content:space-around; gap:440px; margin-top:10px" class="text-recommended">
                        <h1 style="font-size: 18px">Recommended</h1>
                        <a href="">see all</a>
                    </div>
                    <div class="book-cards">
                        <?php $books = App\Models\Book::all(); ?>
                        @foreach($books as $book)
                            <?php
                                $maxWords = 2;
                                $bookNameArray = explode(' ', $book->book_name);
                                $bookNameShortened = implode(' ', array_slice($bookNameArray, 0, $maxWords));
                                if (count($bookNameArray) > $maxWords) {
                                    $bookNameShortened .= '...';
                                }
                            ?>
                            <div class="book-card" style="z-index: 999" onclick="showBookDetails({{ json_encode($book) }})">
                                <img src="{{ asset('fotobuku/' . $book->book_cover) }}" alt="{{ $book->book_name }}" style=" width:90px; height:130px;">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $bookNameShortened }}</h5>
                                    <p class="card-text">{{ $book->writer }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="content3">
                    <h1></h1>
                </div>
            </div>
        <aside>
            <h2>Book Details</h2>
            <p>Click on a book to see the details here.</p>
        </aside>
    </div>
    </div>
</div>

<style>

    .book-cards {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 10px;
        margin-left: 75px
    }

    .book-card {
        background-color: #fff;
        color: #000;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        width: 130px;
        height: 200px;
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


</style>

</body>
</html>
