<?php
session_start();
if(isset($_SESSION['member']['member_type']) && $_SESSION['member']['member_type'] == 2){
    header("Location: manage_system.php");
}   
	$title = "員工登入";
	require_once "./template/header.php";
?>
<div class="row justify-content-center my-5">
	<div class="col-lg-4 col-md-6 col-sm-10 col-xs-12">
		<div class="card rounded-0 shadow">
			<div class="card-header">
				<div class="card-title text-center h4 fw-bolder">會員登入</div>
			</div>
			<div class="card-body">
				<div class="container-fluid">
					<?php if(isset($_SESSION['err_login'])): ?>
						<div class="alert alert-danger rounded-0">
							<?= $_SESSION['err_login'] ?>
						</div>
					<?php 
						unset($_SESSION['err_login']);
						endif;
					?>
					<form class="form-horizontal" method="post" action="member_verify.php">
						<div class="mb-3">
							<label for="member_email" class="control-label ">會員帳號</label>
							<input type="email" name="member_email" class="form-control rounded-0">
						</div>
						<div class="mb-3">
							<label for="member_password" class="control-label ">會員密碼</label>
							<input type="password" name="member_password" class="form-control rounded-0">
						</div>
						<div class="mb-3 d-grid">
							<input type="submit" name="member_submit" class="btn btn-primary rounded-0">
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