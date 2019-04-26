<div class="card">
    <form action="/uploadImage" method="post" enctype="multipart/form-data">
        <label for="file-input" class="img">
            <div class="profile">
                <img <?php if ($_SESSION['user']->getImage() == 0) {
                  echo 'src = images/default_image.png';}
                else{
                  echo 'src = images/'.$_SESSION['user']->getUsername().'.jpg';
                }; ?>>
            </div>
        </label>
        <input class="file_upload" id="file-input" type="file" name="file">
        <div>
            <button name="submit">Upload</button>
        </div>
    </form>
    <h1><?php echo isset($_SESSION['user']) ? $_SESSION['user']->getUsername() : ""; ?></h1>
    <h4><b>About Me:</b></h4>
    <p>I am a book devorater</p>
    <h4><b>Category that I like:</b></h4>
    <p>Fiction</p>

</div>