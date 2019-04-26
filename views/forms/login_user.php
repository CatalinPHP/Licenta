<div class='row'>
    <div class='col-lg-12 parentlogin'>
        <div class='login'>
            <form method='post' action='/loginUser'>
                <p>Welcome</p>
                <div id='messages'><?php echo isset($errors) ? $errors : ''; ?></div>
                <input type='text' name='username' placeholder='Username' class='inputLogin' required/><br>
                <input type='password' name='password' placeholder='Password' class='inputLogin' required/><br>
                <div><input type='submit' value='Login' name='log-in' class='loginBtn '/></div>
                <br>
                <div class='a'><a href='/registerUser'>Not register!</a></div>
            </form>
        </div>
    </div>
</div>

