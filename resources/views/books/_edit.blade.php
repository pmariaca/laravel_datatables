<div align="right">
    <button class="btn btn-success" name="create_book_btn" id="create_book_btn" data-bs-toggle="modal" data-bs-target="#formModal">Add</button>
    <button name="edit_book_btn" id="edit_book_btn" data-bs-toggle="modal" data-bs-target="#formModal" style="display:none">edit_record</button>
    <button type="button" name="delete_book_dml" id="delete_book_dml" data-bs-toggle="modal" data-bs-target="#deleteModal" style="display:none"></button>
</div>

<div class="modal fade" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="edit_form" enctype="multipart/form-data">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="formModalLabel">Books</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span id="form_result"></span>
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" spellcheck="false" required />
                    </div>
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="2" spellcheck="false" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-5">
                            <label for="published_at" class="col-form-label">Published</label>
                            <input id="published_at" name="published_at" class="form-control" type="date" required />
                        </div>
                        <div class="col-7">
                            <label for="type" class="form-label">Categories</label>
                            <select size="2" class="form-select form-select-lg mb-3" id="category_id" name="category_id[]" multiple required>
                                @foreach($categories as $cat)
                                <option value="{{$cat->id}}">{{$cat->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="image_url" class="form-label">Image</label>
                        <input class="form-control" type="file" id="image_url" name="image_url" onchange="previewFile();">
                        <span class="text-danger" id="image-input-error"></span>
                        <img src="" height="100" alt="Image preview..." id="imgpreview" />
                    </div>

                    <input type="hidden" name="action" id="action" value="Add" />
                    <input type="hidden" name="hidden_id" id="hidden_id" value="hidden_id" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btnmin" data-bs-dismiss="modal" id="close_action_button">Close</button>
                    <button type="submit" class="btn btn-primary btnmin" name="action_button" id="action_button" value="save">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="deleteModalLabel">Delete book</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            Surely you want to delete book?
                <input type="hidden" name="hiddenBook_id" id="hiddenBook_id" value="hiddenBook_id" />
            </div>
            <div class="modal-footer">
                <button type="button" name="delete_record_close" id="delete_record_close" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" name="delete_book" id="delete_book" class="btn btn-primary">Delete</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    function htmlDecode(data) {
        var txt = document.createElement('textarea');
        txt.innerHTML = data;
        return txt.value
    }
</script>