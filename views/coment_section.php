<div class="col-sm-4 col-sm-offset-4">
  <?php if (isset($_SESSION['user'])) : ?>
    <form method="post" action="/setComments" class="commentBox">
        <input type="hidden" name="idBook" value="<?php echo $id ?>">
        <input type="hidden" name="username" value="<?php echo $_SESSION['user']->getUsername() ?>">
        <input type="hidden" name="date" value="<?php echo date('Y-m-d H:i:s') ?>">
        <textarea name="comment"></textarea>
        <button type='submit' name='submit'>Comment</button>
    </form>
  <?php endif; ?>
    <div>
      <?php echo $comment ?>
    </div>
</div>