<?php
	session_start();
	$title = "編輯機構";
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
		
		// 更新institution表格
		$ins_name = mysqli_real_escape_string($conn, $_POST['ins_name']);
		$query = "UPDATE institution SET ins_name = '$ins_name' WHERE ins_num = '{$ins_num}'";
		$result = mysqli_query($conn, $query);
		if(!$result){
			$err = "無法更新機構名稱 " . mysqli_error($conn);
		}
		
		// 更新ins_capacity表格
		$caring_num = $_POST['caring_num'];
		$nurse_num = $_POST['nurse_num'];
		$dem_num = $_POST['dem_num'];
		$long_caring_num = $_POST['long_caring_num'];
        $providing_num = $_POST['providing_num'];
		$query = "UPDATE ins_capacity SET caring_num = $caring_num, nurse_num = $nurse_num, dem_num = $dem_num, 
            long_caring_num = $long_caring_num, providing_num = $providing_num,
            housing_num = ($caring_num + $nurse_num + $dem_num + $long_caring_num) WHERE ins_num = '{$ins_num}';";
		$result = mysqli_query($conn, $query);
		if(!$result){
			$err = "無法更新床位數量 " . mysqli_error($conn);
		}
		
		// 更新ins_info表格
		$manager = mysqli_real_escape_string($conn, $_POST['manager']);
		$phone = mysqli_real_escape_string($conn, $_POST['phone']);
		$website = mysqli_real_escape_string($conn, $_POST['website']);
		$query = "UPDATE ins_info SET manager = '$manager', phone = '$phone', website = '$website' WHERE ins_num = '{$ins_num}'";
		$result = mysqli_query($conn, $query);
		if(!$result){
			$err = "無法更新機構資訊 " . mysqli_error($conn);
		}

		if(isset($err)){
			$_SESSION['err_login'] = "機構內容更新失敗：";
			$_SESSION['err_message'] = $err;
		} else {
			$_SESSION['success'] = "機構內容已成功更新";
		}

		header("Location: manage_system.php");
		exit;
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
						<form method="post" action="admin_edit_ins.php?insnum=<?php echo $row['ins_num'];?>" enctype="multipart/form-data">
                            <input type="hidden" name="ins_num" value="<?php echo $row['ins_num']; ?>">
                            <div class="mb-3">
                                <label class="control-label">名稱</label>
                                <input class="form-control rounded-0" type="text" name="ins_name" value="<?php echo $row['ins_name'];?>" >
                            </div>
                            <div class="mb-3">
                                <label class="control-label">地址</label>
                                <input class="form-control rounded-0" type="text" name="addr" value="<?php echo $row['addr'];?>" readonly="true">
                            </div>
                            <div class="mb-3">
                                <label class="control-label">安養型床位</label>
                                <input class="form-control rounded-0" type="number" name="caring_num" value="<?php echo $row['caring_num'];?>" required min="0" step="1">
                            </div>
                            <div class="mb-3">
                                <label class="control-label">養護型床位</label>
                                <input class="form-control rounded-0" type="number" name="nurse_num" value="<?php echo $row['nurse_num'];?>" required min="0" step="1">
                            </div>
                            <div class="mb-3">
                                <label class="control-label">失智照顧型床位</label>
                                <input class="form-control rounded-0" type="number" name="dem_num" value="<?php echo $row['dem_num'];?>" required min="0" step="1">
                            </div>
                            <div class="mb-3">
                                <label class="control-label">長照型床位</label>
                                <input class="form-control rounded-0" type="number" name="long_caring_num" value="<?php echo $row['long_caring_num'];?>" required min="0" step="1">
                            </div>
                            <div class="mb-3">
                                <label class="control-label">總收容人數</label>
                                <input class="form-control rounded-0" type="number" name="providing_num" value="<?php echo $row['providing_num'];?>" required min="0" step="1">
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
							<div class="d-flex justify-content-center">
								<form class="me-2">
									<button type="submit" name="edit"  class="btn btn-primary btn-sm rounded-0">更新</button>
									<button type="reset" class="btn btn-default btn-sm rounded-0 border">取消</button>
								</form>
							</div>
							<div class="d-flex justify-content-center">
								<button id="searchBtn" class="btn btn-primary" style="background-color: #FF5809;">點我搜尋</button>
							</div>
					</div>
				</div>
			</div>
		</div>
	<div style="height: 20px;"></div>

<script>
  document.getElementById('searchBtn').addEventListener('click', function() {
    var addr = '<?= $row['addr'] ?>'; // get institution name
    var form = document.createElement('form'); // create form
    form.method = 'GET'; // or 'POST' if your index.php handle it by post method
    form.action = 'index.php'; // form submission url

    var input = document.createElement('input'); // create input
    input.type = 'hidden'; // input type is hidden
    input.name = 'search'; // the name should match with the name in index.php
    input.value = addr; // set the value to institution name

    form.appendChild(input); // add input to form

    document.body.appendChild(form); // add form to document

    form.submit(); // submit form
  });
</script>