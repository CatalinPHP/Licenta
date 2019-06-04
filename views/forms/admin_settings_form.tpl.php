<div class="settings_content" id="setting_content">
    <div class="form adminApiSettings">
        <form id="adminSettingsForm" method="post" action="/admin/settings">
            <div id="adminSettingsh1"><h1>Admin settings</h1></div>
            <div class="messages"><?php echo isset($errors) ? $errors : ''; ?></div>
            <div class="form-group">
                <label for="url">Google Api Endpoint</label>
                <input type="text" class="form-control" placeholder="Google Api Endpoint" name="google_api_endpoint"
                       id="endPoint" value="<?php echo $apiUrl; ?>">
            </div>
            <div class="form-group">
                <label for="maxResults">Max books results per page</label>
                <input type="text" class="form-control" placeholder="Max books results per page"
                       name="customer_default_max_books_results_per_page" id="maxResults"
                       value="<?php echo $maxBooks; ?>">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-default" name="action" value="Save">
            </div>
        </form>
    </div>

    <div class="form adminUserEdit">
        <?php include_once "edit_users.tpl.php" ?>
    </div>

    <div class="form adminUploadBook">
      <?php include_once "upload_books.tpl.php" ?>

    </div>

    <div class="form adminLatestBooks">
        <p>soon will be update</p>
    </div>
</div>