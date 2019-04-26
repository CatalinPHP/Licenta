<div class="book-details-wrapper">
  <?php if (!empty($image)) : ?>
      <img src="<?php echo $image ?>" class="book-image" alt="No image available"/>
  <?php else : ?>
      <img src='/images/book_default.png' class="book-image"/>
  <?php endif; ?>
    <h3><?php echo $title ?></h3>
    <h5><?php echo $authors ?></h5>
    <div class="price">
        <div><?php echo $price ?></div>
      <?php if (!empty($buyLink)) : ?>
          <a href="<?php echo $buyLink ?>" class="buy" target="_blank"> Buy </a>
      <?php endif; ?>
    </div>
    <div class="rating">
        <div><?php echo $rating ?></div>
      <?php echo $stars ?>
    </div>
    <div>
        <span><strong>Category:</strong> <?php echo $category ?></span>
    </div>
    <div>
        <span><strong>Language:</strong> <?php echo $language ?></span>
    </div>

    <p id="shortDescr">
      <?php echo $description ?>
    </p>
    <p id="longDescr" hidden>
      <?php echo $longDescription ?>
    </p>
    <p class="readMore">
      <?php if (!empty($description) && ($description !== $longDescription)) : ?>
          <a href="#" class="clickable">Read more</a>
      <?php endif; ?>
    </p>
    <form action="/wishBooksAddRemove" method="get" >
        <input name="BookTitle" value="<?php echo $title ?>" style="display: none">
        <input type="submit"  class="btn" id="wishBtn" value="wish">
    </form>
</div>