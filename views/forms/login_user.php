<form method="POST" action="/loginUser" class="form-group">
    <div class="header">
        <h2>Log In User</h2>
    </div>
    <div class="messages"><?php echo isset($errors) ? $errors : ''; ?></div>
    <div class="form-group">
        <label for="username">Username:</label>
        <input type='text' name='username' placeholder='Username' class='form-control' required/><br>
    </div>
    <div class="form-group">
        <label for="pwd">Password:</label>
        <input type="password" name="password" class="form-control" placeholder="Enter password">
    </div>
    <div class="form-group">
        <div><input type='submit' value='Login' name='log-in' class='btn btn-default'/></div>
    </div>
    <div class="form-group">
        <div class='a'><a href='/registerUser'>Not register!</a></div>
    </div>
</form>