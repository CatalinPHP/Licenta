<div class="col-sm-4 col-sm-offset-4">
    <form method="post" action="/setComments">
        <input type="hidden" name="idBook" value="<?php echo $id ?>">
        <input type="hidden" name="username" value="<?php echo $_SESSION['user']->getUsername() ?>">
        <input type="hidden" name="date" value="<?php echo date('Y-m-d H:i:s') ?>">
        <textarea name="comment"></textarea>
        <button type='submit' name='submit'>Comment</button>
    </form>
    <div>
      <?php echo $comment ?>
    </div>
</div>