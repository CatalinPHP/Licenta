<div>
    <form method="post" action="/admin/search">
        <div class="form-group">
            <label for="search-title">Title</label>
            <input type="text" class="form-control" placeholder="Title" name="search-title" id="search-title"/>
        </div>
        <div class="form-group">
            <label for="search-author">Author</label>
            <select data-placeholder="Choose an author..." class="chosen-select" name="search-author"
                    id="search-author">
                <option value="">All authors</option>
              <?php echo $optionsAuthor ?>
            </select>
        </div>
        <div class="form-group">
            <label for="search-category">Category</label>
            <select data-placeholder="Choose a category" class="chosen-select" name="search-category"
                    id="search-category">
                <option value="">All categories</option>
              <?php echo $optionsCategory ?>
            </select>
        </div>
        <div class="form-group">
            <label for="search-priceFrom">Price</label>
            <input type="text" class="form-control" placeholder="From" name="search-priceFrom" id="search-priceFrom"/>
            <label for="search-priceTo"></label>
            <input type="text" class="form-control" placeholder="To" name="search-priceTo" id="search-priceTo"/>
        </div>
        <input type="submit" class="btn btn-default" value="Search" id="searchEntries">
        <div id="errors"></div>
    </form>
</div>

