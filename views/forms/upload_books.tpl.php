<div class="uploadBooks">
    <div>
        <h2>Upload books</h2>
    </div>
    <form method="post" action="/importBooks">
        <div class="form-group">
            <label>Titlu</label>
            <input type="text" class="form-control" name="title">
        </div>

        <div class="form-group">
            <label>Autor</label>
            <input type="text" class="form-control" name="author">
        </div>


        <div class="form-group">
            <label for="uri">Categorie</label>
            <input type="text" class="form-control" name="category"> <br>
        </div>


        <div class="form-group">
            <input type="submit" class="btn btn-default" name="action" value="Upload">
        </div>
    </form>
</div>