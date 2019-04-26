<form method="post" action="/register" class="form-group">
    <div class="header">
        <h2>Sign Up</h2>
    </div>
    <div class="messages"><?php echo isset($errors) ? $errors : ''; ?></div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="text" class="form-control" name="email" placeholder="Enter email">
    </div>
    <div class="form-group">
        <label for="pwd">Password:</label>
        <input type="password" class="form-control" name="password" placeholder="Enter password">
    </div>
    <div class="form-group">
        <label for="conf_pwd">Confirm Password:</label><br>
        <input type="password" class="form-control" name="confirm_password" placeholder="Enter confirm password">
    </div>
    <div class="form-group">
        <input type="submit" name="register" class='btn btn-default' value="Sign Up">
    </div>
    <div>
        Already have an account? <a href="/login">Log In</a>
    </div>
</form>