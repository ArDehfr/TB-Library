<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $book->book_name }} Reviews</title>
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function openReviewModal(bookId) {
            const form = document.getElementById('reviewForm');
            form.action = `/books/${bookId}/review`;
            document.getElementById('bookIdInput').value = bookId;
            document.getElementById('reviewModal').style.display = 'block';
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').style.display = 'none';
        }
    </script>
    <style>
        .book-card button {
            background-color: #3085d6;
            color: #fff;
            border: none;
            padding: 1px 7px;
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

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .review {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
        }

        .review .rating {
            font-size: 1.2em;
            color: #ffb400;
        }

        .review p {
            margin: 5px 0;
        }

        .review .user {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php
    $books = App\Models\Book::all();
    $favorites = Auth::user()->favorites->pluck('book_id')->toArray();
    $bookId = $book->book_id;
    $userReview = Auth::user()->reviews()->where('book_id', $bookId)->first();
    $hasReturned = Auth::user()->borrowings()->where('book_id', $bookId)->where('status', 'returned')->exists();
    $otherReviews = App\Models\Review::where('book_id', $bookId)->get();
    ?>

    <div class="wrapper">
        <div class="sidebar">
            <h2>TB Library</h2>
            <ul>
                <li><a style="color: white; font-weight:600" href="{{ route('home') }}"><i class="fas fa-home"></i>Discover</a></li>
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
            <div style="" class="contents">
                <div class="book-cards">
                    <div class="book-card" id="book-card-{{ $book->book_id }}">
                        <div class="card-body">
                            <div>
                                <div style="display: flex; justify-content:flex-start" class="content-lain">
                                    <img src="{{ asset('fotobuku/' . $book->book_cover) }}" alt="{{ $book->book_name }}" style="width:210px; height:250px;">
                                    <div class="inline-compponent">
                                        <h5 class="card-title">{{ $book->book_name }}</h5>
                                        <p class="card-text">{{ $book->writer }}</p>
                                        <button class="favorite-btn" onclick='toggleFavorite({{ $book->book_id }}, event)'>
                                            <i class="{{ in_array($book->book_id, $favorites) ? 'fas' : 'far' }} fa-heart" id="heart-icon-{{ $book->book_id }}"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <p class="card-text">{{ $book->synopsis }}</p>

                        </div>
                    </div>
                </div>
            </div>

            @if($hasReturned && !$userReview)
                <button onclick="openReviewModal({{ $book->book_id }})" class="btn btn-primary">Write a Review</button>
            @endif

            @if($userReview)
                <div class="user-review">
                    <h3>Your Review</h3>
                    <div class="review">
                        <span class="rating">{{ str_repeat('★', $userReview->rating) }}</span>
                        <p class="user">{{ $userReview->user->name }}</p>
                        <p>{{ $userReview->review }}</p>
                    </div>
                </div>
            @endif

            <br>
            <br>
            <br>

            <div class="other-reviews">
                <h3>Other Reviews</h3>
                @foreach ($otherReviews as $review)
                    @if ($review->book_id == $book->book_id)
                        <div class="review">
                            <span class="rating">{{ str_repeat('★', $review->rating) }}</span>
                            <p class="user">{{ $review->user->name }}</p>
                            <p>{{ $review->review }}</p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <div id="reviewModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeReviewModal()">&times;</span>
            <h2>Review Book</h2>
            <form id="reviewForm" action="{{ route('review.submit', ['bookId' => $book->book_id]) }}" method="POST">
                @csrf
                <input type="hidden" id="bookIdInput" name="bookId" value="{{ $book->book_id }}">
                <div class="form-group">
                    <label for="rating">Rating:</label>
                    <input type="range" id="rating" name="rating" min="1" max="5" step="1" oninput="this.nextElementSibling.value = this.value">
                    <output>3</output>
                </div>
                <div class="form-group">
                    <label for="review">Review:</label>
                    <textarea id="review" name="review" class="form-control" rows="4"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </form>
        </div>
    </div>

    <script>
        function openReviewModal(bookId) {
            const form = document.getElementById('reviewForm');
            form.action = `/books/${bookId}/review`;
            document.getElementById('bookIdInput').value = bookId;
            document.getElementById('reviewModal').style.display = 'block';
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').style.display = 'none';
        }
    </script>

</div>

<div id="reviewModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeReviewModal()">&times;</span>
        <h2>Review Book</h2>
        <form id="reviewForm" action="{{ route('review.submit', ['bookId' => $book->book_id]) }}" method="POST">
            @csrf
            <input type="hidden" id="bookIdInput" name="bookId" value="{{ $book->book_id }}">
            <div class="form-group">
                <label for="rating">Rating:</label>
                <input type="range" id="rating" name="rating" min="1" max="5" step="1" oninput="this.nextElementSibling.value = this.value">
                <output>3</output>
            </div>
            <div class="form-group">
                <label for="review">Review:</label>
                <textarea id="review" name="review" class="form-control" rows="4"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Review</button>
        </form>
    </div>
</div>
</body>
</html>
