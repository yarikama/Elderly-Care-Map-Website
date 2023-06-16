<?php
	session_start();
	$title = "Edit institution";
	require_once "./template/header.php";
	require_once "./function/database_function.php";
	$conn = db_connect();

	if(isset($_GET['insnum'])){
		$ins_num = $_GET['insnum'];
	} else {
		echo "Empty query!";
		exit;
	}

	if(!isset($ins_num)){
		echo "Empty ins_num! check again!";
		exit;
	}

	// get book data
	$query = "SELECT * FROM institution, ins_address, ins_capacity, ins_info
    WHERE institution.ins_num = ins_address.ins_num AND institution.ins_num = ins_capacity.ins_num 
    AND institution.ins_num = ins_info.ins_num AND institution.ins_num = '{$ins_num}'";
	$result = mysqli_query($conn, $query);
	if(!$result){
		echo $err = "Can't retrieve data ";
		exit;
	}else{
		$row = mysqli_fetch_assoc($result);
	}
	if(isset($_POST['edit'])){
		$ins_num = trim($_POST['ins_num']);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, ['edit', 'ins_num'])){
				if(!empty($data)) $data .=", ";
				$data .= "`{$k}` = '".(mysqli_real_escape_string($conn, $v))."'";
			}
		}
		$query = "UPDATE book set $data where ins_num = '{$ins_num}'";
		$result = mysqli_query($conn, $query);
		if($result){
			$_SESSION['success'] = "機構內容已成功更新";
			header("Location: manage_system.php");
		}else{
			$err =  "無法更新機構內容 " . mysqli_error($conn);
		}
	}
	
?>
	<h4 class="fw-bolder text-center">編輯機構內容</h4>
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
								<?= $_SESSION['err_login'] ?>
								<?= $err ?>
							</div>
						<?php 
							endif;
						?>
						<form method="post" action="book_change.php?bookisbn=<?php echo $row['book_ISBN'];?>" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="control-label">名稱</label>
                                <input class="form-control rounded-0" type="text" name="ins_name" value="<?php echo $row['ins_name'];?>" readOnly="true">
                            </div>
                            <div class="mb-3">
                                <label class="control-label">地址</label>
                                <input class="form-control rounded-0" type="text" name="addr" value="<?php echo $row['addr'];?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="control-label">安養床位</label>
                                <input class="form-control rounded-0" type="number" name="caring_num" value="<?php echo $row['caring_num'];?>" required min="0" step="1">
                            </div>
                            <div class="mb-3">
                                <label class="control-label">養護床位</label>
                                <input class="form-control rounded-0" type="number" name="nurse_num" value="<?php echo $row['nurse_num'];?>" required min="0" step="1">
                            </div>
                            <div class="mb-3">
                                <label class="control-label">失智床位</label>
                                <input class="form-control rounded-0" type="number" name="dem_num" value="<?php echo $row['dem_num'];?>" required min="0" step="1">
                            </div>
                            <div class="mb-3">
                                <label class="control-label">長照床位</label>
                                <input class="form-control rounded-0" type="number" name="long_caring_num" value="<?php echo $row['long_caring_num'];?>" required min="0" step="1">
                            </div>
                            <div class="mb-3">
                                <label class="control-label">負責人</label>
                                <input class="form-control rounded-0" type="text" name="manager" value="<?php echo $row['manager'];?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="control-label">電話</label>
                                <input class="form-control rounded-0" type="text" name="phone" value="<?php echo $row['phone'];?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="control-label">網站</label>
                                <input class="form-control rounded-0" type="text" name="website" value="<?php echo $row['website'];?>" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="edit"  class="btn btn-primary btn-sm rounded-0">更新</button>
                                <button type="reset" class="btn btn-default btn-sm rounded-0 border">取消</button>
                            </div>
						</form>
					</div>
				</div>
			</div>
		</div>
	<div style="height: 20px;"></div>