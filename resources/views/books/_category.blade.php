<div class="accordion" id="accordionCategory">
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <strong>Categories</strong>
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionCategory">
            <div class="accordion-body">
                <div class="card-group">

                    <div class="card">
                        <div class="card-body">
                            <form id="category_form">
                                @csrf
                                <div class="form-group">
                                    <label for="title">New category</label>
                                    <input type="text" class="form-control" name="title" id="titlecat" spellcheck="false" required />
                                </div>
                                <div align="center">
                                    <button type="submit" class="btn btn-primary btnmin" id="add_category" value="save">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">

                            <label for="type" class="form-label">List of categories, select to change or delete</label>
                            <select size="4" class="form-select form-select-lg mb-3" id="category_idcat" name="category_idcat[]">
                                @foreach($categories as $cat)
                                <option value="{{$cat->id}}">{{$cat->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <form id="category_edit_form">
                                @csrf

                                <div class="form-group">
                                    <label for="title">Change category</label>
                                    <input type="text" class="form-control" name="title" id="titledit" spellcheck="false"/>
                                </div>
                                <div align="center">
                                    <input type="hidden" id="hidden_idcat" name="hidden_idcat" value="hidden_idcat" />
                                    <button type="submit" class="btn btn-primary btnmin" id="update_category" value="save">Update</button>
                                </div>
                                <div align="center">
                                    <br>
                                    <button type="submit" class="btn btn-danger btnmin" id="delete_category" value="save">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>