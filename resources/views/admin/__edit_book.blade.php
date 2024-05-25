<div class="modal fade" id="editBookModal_{{ $book->book_id }}" tabindex="-1" role="dialog" aria-labelledby="editBookModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content card" style="color: #fff;">
            <form id="editBookForm" action="{{ route('admin.book.update', $book->book_id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT') <!-- Add this hidden field to spoof the PUT method -->

                <div class="modal-header">
                    <h5 class="modal-title" id="editBookModalLabel">Edit Book</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="book_id" value="{{ $book->book_id }}">

                    <div class="form-group">
                        <label for="editBookName">Book Name</label>
                        <input type="text" value="{{ $book->book_name }}" name="book_name" class="form-control"
                            id="editBookName" required>
                    </div>
                    <div class="form-group">
                        <label for="editCategories">Categories</label>
                        <select name="categories" class="form-control" id="categories" required>
                            <option style="background-color: #495057;" value="">Select a category</option>
                            <option style="background-color: #495057;" value="Fantasy" @if ($book->categories == 'Fantasy') selected @endif>Fantasy</option>
                            <option style="background-color: #495057;" value="Horror" @if ($book->categories == 'Horror') selected @endif>Horror</option>
                            <option style="background-color: #495057;" value="Education" @if ($book->categories == 'Education') selected @endif>Education
                            </option>
                            <option style="background-color: #495057;" value="Sci-fi" @if ($book->categories == 'Sci-fi') selected @endif>Sci-fi</option>
                            <option style="background-color: #495057;" value="Geography" @if ($book->categories == 'Geography') selected @endif>Geography
                            </option>
                            <option style="background-color: #495057;" value="Drama" @if ($book->categories == 'Drama') selected @endif>Drama</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editWriter">Writer</label>
                        <input type="text" value="{{ $book->writer }}" name="writer" class="form-control"
                            id="editWriter" required>
                    </div>
                    <div class="form-group">
                        <label for="editPublisher">Publisher</label>
                        <input type="text" name="publisher" value="{{ $book->publisher }}" class="form-control"
                            id="editPublisher" required>
                    </div>
                    <div class="form-group">
                        <label for="editYear">Year</label>
                        <input type="number" name="year" value="{{ $book->year }}" class="form-control"
                            id="editYear" required>
                    </div>
                    <div class="form-group">
                        <label for="editSynopsis">Synopsis</label>
                        <textarea name="synopsis" class="form-control" id="editSynopsis" rows="5" required>{{ $book->synopsis }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="editBookCover">Book Cover</label>
                        <input type="file" name="book_cover" class="form-control-file" id="editBookCover">
                        <img id="currentBookCover" src="" alt="Book Cover" width="100" height="150">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Book</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    #editSynopsis {
        resize: none;
    }
</style>
