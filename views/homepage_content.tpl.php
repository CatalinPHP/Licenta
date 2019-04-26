<div class='col-lg-3 col-sm-6'>
    <a href="/book/<?php echo $book->id ?>" class='bg-success book-box'>
        <img src="<?php echo (!empty($book->image) ? $book->image : '/images/book_default.png'); ?> ">
        <h4><?php echo $book->title; ?></h4>
        <h5><?php echo implode(', ', $book->getAuthorNames()); ?></h5>
        <p class='description'>
            <small><?php echo substr($book->description, 0, 190) . '...'; ?></small>
        </p>
    </a>
</div>
