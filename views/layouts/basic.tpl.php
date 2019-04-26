<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?> | Google Books</title>
    <meta http-equiv='Content-Type' content='text/html;charset=utf-8'/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/menu.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <?php echo $css; ?>


</head>
<body class="<?php echo $loggedInAdmin ? 'admin-logged-in' : 'admin-logged-out' ;
                echo $loggedInUser ? ' , user-logged-in' : ' , user-logged-out' ?>"
       >
<?php include SITE_ROOT . '/views/layouts/partials/header.tpl.php' ?>

<content>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
              <div ><?php echo $messages; ?></div>
              <div ><?php echo $content; ?></div>
            </div>
        </div>
    </div>
</content>

<?php include SITE_ROOT . '/views/layouts/partials/footer.tpl.php' ?>
</body>
<?php echo $scriptElements; ?>
</html>