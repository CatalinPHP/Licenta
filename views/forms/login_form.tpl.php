<form method="POST" action="/login" class="form-group">
    <div class="header">
        <h2>Log In</h2>
    </div>
    <div class="messages"><?php echo isset($errors) ? $errors : ''; ?></div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="text" name="email" class="form-control" placeholder="Enter email">
    </div>
    <div class="form-group">
        <label for="pwd">Password:</label>
        <input type="password" name="password" class="form-control" placeholder="Enter password">
    </div>
    <div class="form-group">
        <input type="submit" name="log_in" class="btn btn-default" value="Log In">
    </div>
    <div class="form-group">
        Not have an account? <a href="/register"> Register Now</a>
    </div>
</form>
