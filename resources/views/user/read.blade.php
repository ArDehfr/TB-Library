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

        function showBorrowModal(bookId, bookName, bookQuantities) {
            const modal = document.getElementById('borrow-modal');
            modal.style.display = 'block';
            document.getElementById('modal-book-id').value = bookId;
            document.getElementById('modal-book-name').textContent = bookName;
            document.getElementById('modal-book-quantities').textContent = `Quantity Available: ${bookQuantities}`;

            const outOfStock = document.getElementById('out-of-stock');
            const borrowButton = document.getElementById('borrow-button');
            const dayRent = document.getElementById('day_rent_input');

            if (bookQuantities > 0) {
                outOfStock.style.display = 'none';
                borrowButton.style.display = 'block';
                dayRent.style.display = 'block';
            } else {
                outOfStock.style.display = 'block';
                borrowButton.style.display = 'none';
                dayRent.style.display = 'none';
            }
        }

        function closeBorrowModal() {
            document.getElementById('borrow-modal').style.display = 'none';
        }
    </script>
    <style>
        .book-card button {
            background-color: #27374D;
            color: #fff;
            border: none;
            padding: 1px 7px;
            border-radius: 5px;
            cursor: pointer;
        }

        .book-card button:hover {
            background-color: #374c6b;
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

        .content-lain{
            color: #000000;
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
                <li><a href="#" onclick="logout(event)"><i class="fas fa-sign-out-alt"></i>Log Out</a></li>
            </ul>
        </div>
        <div class="main_content">
            <style>
                 .headers{
                    padding: 20px;
                    display: flex;
                    align-items: center;
                    justify-content:end;
                    gap: 300px;
                    background: #526D82;
                    color: #ffffff;
                    height: 85px;
                    }
            </style>
            <div class="headers" style="">

                <div class="dropdown">
                    <button class="dropbtn">
                        <div style="display: flex;">
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
                            <div class="content-atas">
                                <div style="display: flex; justify-content:flex-start" class="content-lain">
                                    <div class="div-buku" style="border-radius:20px; width:250px; height:310px;">
                                        <img src="{{ asset('fotobuku/' . $book->book_cover) }}" alt="{{ $book->book_name }}" style="border-radius:10px; width:210px; height:270px;">
                                    </div>
                                    <div class="inline-compponent">
                                        <h5 class="card-title">{{ $book->book_name }}</h5>
                                        <p style="margin-bottom: 10px" class="card-text">{{ $book->writer }}</p>

                                        <div style="display: flex;">
                                            <button class="favorite-btn" onclick='toggleFavorite({{ $book->book_id }}, event)'>
                                                <i class="{{ in_array($book->book_id, $favorites) ? 'fas' : 'far' }} fa-heart" id="heart-icon-{{ $book->book_id }}"></i>
                                            </button>
                                            <div style="display: flex; margin-top:5px; margin-left:15px;" class="rating-btn" data-book-id="{{ $book->book_id }}">
                                                <i style="color: #ffb400; font-size:24px; margin-right:5px" class="fas fa-star"></i>
                                                <p style="color: black; font-size:18px"><strong>{{ $book->average_rating }}/5</strong></p>
                                            </div>
                                        </div>

                                        <button style="width: 230px; height:40px; font-size:20px; margin-top:90px; margin-bottom:10px;" class="btn-aside" onclick="showBorrowModal({{ $book->book_id }}, '{{ $book->book_name }}', {{ $book->book_quantities }})">
                                            Borrow Book <i style="margin-left: 10px;" class="fas fa-book"></i>
                                        </button>
                                        <p style="margin-left: 60px" class="card-text">Book Stock: {{ $book->book_quantities }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-synopsis">
                                <h1>Synopsis</h1>
                                <div class="scroll-layer">
                                    <div class="scroll">
                                    <p style="font-size: 20px" class="card-text">{{ $book->synopsis }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <style>

                .content-atas{
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .content-lain{
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 35px;
                    width: 85%;
                    height: 50dvh;
                    background-color: #A9C2D4;
                    border-radius: 20px;
                    margin-bottom: 20px;
                    margin-top: 20px;
                }

                .div-buku{
                    margin-left: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background-color: white;
                }

                .card-synopsis{
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-direction: column;
                    width: 85%;
                    background-color: #A9C2D4;
                    right: 50%;
                    transform: translateX(9%);
                    padding: 35px;
                    border-radius: 15px;
                }

                .scroll-layer{
                    background-color: #001524;
                    border-radius: 15px;
                    margin-top: 20px;
                    margin-bottom: 10px;
                }

                .scroll{
                    right: 50%;
                    transform: translateX(5.5%);
                    margin-top: 20px;
                    margin-bottom: 20px;
                    background-color: white;
                    padding: 15px;
                    overflow: auto;
                    height: 200px;
                    border-radius: 10px;
                    width: 90%;
                }

                .inline-compponent{
                    display: flex;
                    flex-direction: column;
                    margin-left: 70px;
                    margin-top: 110px
                }
                .inline-compponent h5{
                    font-size: 25px;
                    font-weight: 900;
                    margin-top: -100px
                }

                .your-review{
                    background-color: #526D82;
                    width: 60%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 35px;
                    border-radius:20px;
                    margin-bottom: 20px;
                    margin-top: 20px;
                }

                .wrapper-your-review{
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .your-review button {
                    background-color: #27374D;
                    color: #fff;
                    border: none;
                    padding: 1px 7px;
                    border-radius: 5px;
                    width: 300px;
                    height: 50px;
                    font-size: 24px;
                    font-weight: 800;
                    cursor: pointer;
                }

                .your-review button:hover {
                    background-color: #374c6b;
                }

                .review-wrapper{
                    background-color: #001524;
                    width: 500px;
                    padding: 15px;
                    border-radius:15px;
                }
                .reviews-wrapper{
                    background-color: #001524;
                    width: 800px;
                    padding: 15px;
                    border-radius:15px;
                    margin-bottom: 10px;
                }
                .review{
                    background-color: white;
                    padding: 10px;
                    border-radius:10px;
                }

                .wrapper-other-review{
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .other-reviews{
                    background-color: #526D82;
                    width: 85%;
                    padding: 35px;
                    border-radius:20px;
                    margin-bottom: 20px;
                    margin-top: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-direction: column;
                }
            </style>

            <div class="wrapper-your-review">
                <div class="your-review">
                    @if($hasReturned && !$userReview)
                    <button onclick="openReviewModal({{ $book->book_id }})" class="btn btn-primary">Write a Review</button>
                    @endif

                    @if($userReview)
                    <div class="user-review">
                        <h3 style="color: white; margin-bottom:10px; font-size:20px">Your Review</h3>
                        <div class="review-wrapper">
                           <div class="review">
                            <span class="rating">{{ str_repeat('★', $userReview->rating) }}</span>
                            <p class="user">{{ $userReview->user->name }}</p>
                            <p>{{ $userReview->review }}</p>
                        </div>
                        </div>

                    </div>
                    @endif
                </div>
            </div>

            <div class="wrapper-other-review">
                <div class="other-reviews">
                    <h3 style="color: white; margin-bottom:20px; font-size:20px">Reviews</h3>
                    @foreach ($otherReviews as $review)
                    @if ($review->book_id == $book->book_id)
                    <div class="reviews-wrapper">
                    <div class="review">
                        <span class="rating">{{ str_repeat('★', $review->rating) }}</span>
                        <p class="user">{{ $review->user->name }}</p>
                        <p>{{ $review->review }}</p>
                    </div>
                    </div>
                    @endif
                    @endforeach
                </div>
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

<!-- Borrow Modal -->
<div id="borrow-modal">
    <div class="modal-content">
        <span class="close" onclick="closeBorrowModal()">&times;</span>
        <h2>Borrow a Book</h2>
        <form action="{{ route('borrow.book') }}" method="POST">
            @csrf
            <div>
                <p id="modal-book-quantities"></p>
                <p id="out-of-stock" style="display: none; color: red;">Out of Stock</p>
            </div>
            <div id="day_rent_input">
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
            <button id="borrow-button" type="submit">Continue</button>
        </form>
    </div>
</div>
<style>
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
</body>
</html>
