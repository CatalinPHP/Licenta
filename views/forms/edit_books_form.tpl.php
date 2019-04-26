<h4>Edit <strong><?php echo $title ?></strong></h4>
<form method="post" action="/admin/edit/<?php echo $id ?>" id="editBook">
    <input type="hidden" name="id" <?php echo $id ?> >
    <h5 class="form-group">ISBN-10: <?php echo $ISBN_10 ?></h5>
    <h5 class="form-group">ISBN-13: <?php echo $ISBN_13 ?></h5>
    <div class="form-group">
        <label for="description">Description:</label>
        <textarea name="description" class="form-control" placeholder="Enter a new description" rows="10" cols="10"><?php echo $description ?></textarea>
    <input type="submit" name="edit" class="btn btn-default" value="Save" >
    </div>
    <div id="errors"></div>

</form>
