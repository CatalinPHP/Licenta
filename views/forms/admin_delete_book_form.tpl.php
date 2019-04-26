<div>
    <form id="adminDeleteForm" method="post" action="/admin/books/delete">
        <h1>Delete entry with the id: <?php echo $id; ?></h1>
        <input type="hidden" name="bookId" value="<?php echo $id; ?>">
        <label for="del">To confirm deletion please press the delete button.</label>
        <div id="deleteButton" class="form-group">
            <input type="submit" class="btn btn-default" name="delete" value="Confirm deletion">
        </div>
    </form>
</div>
