<?php
	session_start();
	$title = "編輯會員資訊";
		require_once "./template/header.php";
	require_once "./function/database_function.php";
	$conn = db_connect();

	if(isset($_GET['memberid'])){
		$member_id = $_GET['memberid'];
	} else {
		echo "Empty query!";
		exit;
	}

	if(!isset($member_id)){
		echo "Empty member_id! Please check again!";
		exit;
	}

	// get member data
	$sql = "SELECT distinct `member_id`, `member_name`, `member_email`, `member_address`, `member_phone`, 
    `member_birthday`, `member_gender`, `member_type`
    FROM `member` WHERE member_id = '$member_id';";	
	$result = mysqli_query($conn, $sql);
	if(!$result){
		echo $err = "Can't retrieve data ";
		exit;
	}else{
		$row = mysqli_fetch_assoc($result);
	}
	if(isset($_POST['edit'])){
		$oid = trim($_POST['memberid']);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, ['edit', 'memberid'])){
				if(!empty($data)) $data .= ", ";
				$data .= "`{$k}` = '".mysqli_real_escape_string($conn, $v)."'";
			}
		}

		$query = "UPDATE `member` SET $data WHERE member_ID = '$member_id'";
		$result = mysqli_query($conn, $query);
		if($result){
			$_SESSION['order_success'] = "Member details have been updated successfully";
			header("Location: admin_customer.php");
			exit;
		} else {
			$err = "Can't update data: " . mysqli_error($conn);
		}
	}
?>
<h4 class="fw-bolder text-center">編輯顧客資訊</h4>
<center>
	<hr class="bg-warning" style="width:5em;height:3px;opacity:1">
</center>
<div class="row justify-content-center">
	<div class="col-lg-6 col-md-8 col-sm-10 col-xs-12">
		<div class="card rounded-0 shadow">
			<div class="card-body">
				<div class="container-fluid">
					<?php if(isset($err)): ?>
						<div class="alert alert-danger rounded-0">
							<?= $err ?>
						</div>
					<?php endif; ?>
					<form method="post" action="admin_customer_edit.php?memberid=<?php echo $row['member_id'];?>" enctype="multipart/form-data">
                        <input type="hidden" name="memberid" value="<?php echo $row['member_id']; ?>">
                        <div class="mb-3">
							<label class="control-label">帳號</label>
							<input class="form-control rounded-0" type="text" name="member_email" value="<?php echo $row['member_email'];?>" readonly>
						</div>
						<div class="mb-3">
							<label class="control-label">姓名</label>
							<input class="form-control rounded-0" type="text" name="member_name" value="<?php echo $row['member_name'];?>" required>
						</div>
                        <div class="mb-3">
							<label class="control-label">Email</label>
							<input class="form-control rounded-0" type="text" name="member_email" value="<?php echo $row['member_email'];?>" required>
						</div>
                        <div class="mb-3">
							<label class="control-label">地址</label>
							<input class="form-control rounded-0" type="text" name="member_address" value="<?php echo $row['member_address'];?>" required>
						</div>
                        <div class="mb-3">
							<label class="control-label">電話</label>
							<input class="form-control rounded-0" type="text" name="member_phone" value="<?php echo $row['member_phone'];?>" required>
						</div>
                        <div class="mb-3">
							<label class="control-label">生日</label>
							<input class="form-control rounded-0" type="date" name="member_birthday" value="<?php echo $row['member_birthday'];?>" required>
						</div>
                        <div class="mb-3">
							<label for="name" class="control-label">性別</label>
							<select class="form-select rounded-0" name="member_gender" style="width: 200px;">
								<option value="男" <?php if ($row['member_gender'] == '男') echo 'selected'; ?>>男</option>
								<option value="女" <?php if ($row['member_gender'] == '女') echo 'selected'; ?>>女</option>
							</select>
						</div>
						<div class="text-center">
							<button type="submit" name="edit" class="btn btn-primary btn-sm rounded-0">更新</button>
							<button type="reset" class="btn btn-default btn-sm rounded-0 border">取消</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div style="height: 20px;"></div>
