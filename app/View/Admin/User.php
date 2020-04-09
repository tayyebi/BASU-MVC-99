
<div class="card">
  <div class="card-header">
    تغییر کلمه‌ی عبور
  </div>
  <div class="card-body">
    <h5 class="card-title">/<?php echo $Data['Model']['Email'] ?>/</h5>
    <form class="form" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <!-- Because of this input, chrome browser does not ask for stored password update;
            maybe it's a bug from us that we know it. -->
            <label for="FormerPassInput">ایمیل</label>
            <input type="email" name="EmailInput" id="EmailInput"
            readonly
            class="form-control" value="<?php echo $Data['Model']['Email'] ?>" />
        </div>
        <div class="form-group">
            <label for="FormerPassInput">کلمه‌ی عبور پیشین</label>
            <input type="password" name="FormerPassInput" id="FormerPassInput"
            class="form-control" placeholder="کلمه‌ی عبور پیشین" />
        </div>
        <div class="form-group">
            <label for="NewPassInput">کلمه‌ی عبور پسین</label>
            <input type="password" name="NewPassInput" id="NewPassInput"
            required
            class="form-control" placeholder="کلمه‌ی عبور جدید خود را وارد کنید" />
        </div>
        <div class="form-group">
            <label for="ConfirmPassInput">تکرار مکررات</label>
            <input type="password" name="ConfirmPassInput" id="ConfirmPassInput"
            class="form-control" placeholder="تکرار کلمه‌ی عبور جدید خود را وارد کنید" />
        </div>
        
        <button type="submit" class="btn btn-primary" name="ChangePassword">تغییر گذرواژه</button>
    </form>
  </div>
</div>

