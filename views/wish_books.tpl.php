
<div class="col-lg-3 col-sm-9" >

    <a href="/book/<?php echo $book[0]->id ?>" class='bg-success book-box'>
        <img src="<?php echo (!empty($book[0]->image) ? $book[0]->image : '/images/book_default.png'); ?> ">
        <h4><?php echo substr($book[0]->title,0,40); ?></h4>
        <h5><?php echo implode(', ', $book[0]->getAuthorNames()); ?></h5>
        <p class='description'>
            <small><?php echo substr($book[0]->description, 0, 190) . '...'; ?></small>
        </p>
    </a>
</div>
