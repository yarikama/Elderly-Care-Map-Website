<?php
session_start();
	$title = "員工新增";
	require_once "./template/header.php";
?>

<div class="row justify-content-center my-5">
	<div class="col-lg-8 col-md-6 col-sm-10 col-xs-12">
		<div class="card rounded-0 shadow">
			<div class="card-header">
				<div class="card-title text-center h4 fw-bolder">員工新增</div>
			</div>
			<div class="card-body">
				<div class="container-fluid">
                    <?php 
                        if (isset($_SESSION['error_message'])) {
                            echo "<div class='alert alert-danger'>" . $_SESSION['error_message'] . "</div>";
                            unset($_SESSION['error_message']);
                        }
                    ?>
					<?php if(isset($_SESSION['err_login'])): ?>
						<div class="alert alert-danger rounded-0">
							<?= $_SESSION['err_login'] ?>
						</div>
					<?php 
						unset($_SESSION['err_login']);
						endif;
					?>
					<form class="form-horizontal" method="post" action="admin_add_confirm.php">
                        <div class="mb-3">
                            <label for="member_email" class="control-label ">員工Email</label>
                            <input type="email" name="member_email" class="form-control rounded-0" required>
                        </div>
						<div class="mb-3">
							<label for="member_password" class="control-label ">員工密碼</label>
							<input type="password" name="member_password" class="form-control rounded-0" required>
						</div>
                        <div class="mb-3">
                            <label for="member_pass_confirm" class="control-label ">確認員工密碼</label>
                            <input type="password" name="member_pass_confirm" class="form-control rounded-0" required>
                        </div>
                        <div class="mb-3">
							<label for="member_name" class="control-label ">名字</label>
							<input type="text" name="member_name" class="form-control rounded-0" required>
						</div>
                        <div class="mb-3">
							<label for="member_gender" class="control-label ">性別</label>
							<select name="member_gender" class="form-control rounded-0" required>
								<option value="男">男</option>
								<option value="女">女</option>
							</select>
						</div>
                        <div class="mb-3">
							<label for="member_phone" class="control-label ">電話</label>
							<input type="tel" name="member_phone" class="form-control rounded-0" required>
						</div>
                        <div class="mb-3">
							<label for="member_address" class="control-label ">地址</label>
							<input type="text" name="member_address" class="form-control rounded-0" required>
						</div>
                        <div class="mb-3">
							<label for="member_birthday" class="control-label ">生日</label>
							<input type="date" name="member_birthday" class="form-control rounded-0" required>
						</div>
						<div class="mb-3 d-grid">
							<input type="submit" name="member_submit" class="btn btn-primary rounded-0">
						</div>
						<div class="mb-3">
							<p>已經有員工長照帳號了？ <a href="member_login.php">從這登入</a></p>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
	require_once "./template/footer.php";
?>