{{-- coba dibuat layout --}}
<script>
    document.getElementById('search-bar').addEventListener('input', function() {
        searchBooks();
    });

    function searchBooks() {
        const query = document.getElementById('search-bar').value.toLowerCase();
        const bookCards = document.querySelectorAll('.book-card, .book-card-fav');

        bookCards.forEach(card => {
            const title = card.querySelector('.card-title').textContent.toLowerCase();
            const writer = card.querySelector('.card-text').textContent.toLowerCase();

            if (title.includes(query) || writer.includes(query)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>


