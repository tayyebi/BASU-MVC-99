<?php

?>
<!--PAYLOAD_CONTENT_END-->
<link href="<?php echo _Root ?>static/css/login.css" rel="stylesheet">



<?php
// Message Modal
if (isset($Data['Message'])) {
?>
<div class="modal show" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h5 class="modal-title" id="exampleModalLongTitle">پیغامی دارید!</h5>
      </div>
      <div class="modal-body">
      <?php echo $Data['Message'] ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">بستن</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    $(window).on('load',function(){
        $('.modal').modal('show');
    });
</script>
<?php
}
?>

<div class="text-center">
  <form class="form-signin" method="post" action="<?php echo _Root . 'Authentication/Login' ?>">
    <img class="mb-4" src="<?php echo _Root . 'static/Logo.svg' ?>" alt="" height="100">
    <h1 class="h3 mb-3 font-weight-normal text-center">ورود به سیستم</h1>
    <label for="EmailInput" class="sr-only">نام کاربری</label>
    <input type="email" name="EmailInput" id="EmailInput" class="form-control" placeholder="نشانی پست الکترونیک" required autofocus>
    <label for="PasswordInput" class="sr-only">کلمه‌ی عبور</label>
    <input type="password" name="PasswordInput" id="PasswordInput" class="form-control" placeholder="کلمه‌ی عبور">
    <button class="btn btn-lg btn-primary btn-block" type="submit">ورود</button>
  </form>
</div>