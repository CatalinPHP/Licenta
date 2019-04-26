<div class='row'>
    <div class='col-lg-12 parentlogin'>
        <div class='login'>
            <form method='post' action='/registerUser'>
                <p>Register</p>
                <div id='messages'><?php echo isset($errors) ? $errors : ''; ?></div>
                <input type='text' name='username' placeholder='Username' class='inputLogin' required/><br>
                <input type='password' name='password' placeholder='Password' class='inputLogin' required/><br>
                <input type='password' name='confirm-password' placeholder='Confirm Password' class='inputLogin'
                       required/><br>
                <div><input type='submit' value='Login' name='log-in' class='loginBtn '/></div>
                <div class='a'><a href='/'>Home</a></div>
            </form>
        </div>
    </div>
</div>
