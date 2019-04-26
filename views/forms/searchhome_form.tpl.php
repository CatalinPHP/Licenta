<div>
  <form method="get" action="/search">
    <div class="form-group">
      <label for="search-title">Title:</label>
      <input type="text" class="form-control" placeholder="Title" name="search-title" id="search-title" value ="<?php echo isset($title) ? $title : "" ?>"/>
    </div>
    <div class="form-group">
      <label for="search-author">Author (select one):</label><br>
      <select data-placeholder="Choose an author..." class="chosen-select" name="search-author" id="search-author">
          <option value="">All authors</option>
	  <?php echo $author_options ?>
      </select>
    </div>
    <div class="form-group">
      <label for="search-priceFrom">Price:</label>
      <input type="text" class="form-control" placeholder="From" name="search-priceFrom" id="search-priceFrom" value ="<?php echo isset($priceFrom) ? $priceFrom : "" ?>"/>
      <label for="search-priceTo"></label>
      <input type="text" class="form-control" placeholder="To" name="search-priceTo" id="search-priceTo" value ="<?php echo isset($priceTo) ? $priceTo : "" ?>"/>
    </div>
    <input type="submit" class="btn btn-default" value="Search" id="searchEntries">
    <div class="messages"><?php echo isset($errors) ? $errors : ''; ?></div>
  </form>
</div>

