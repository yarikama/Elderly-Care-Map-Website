<?php
	session_start();
	$title = "Add new institution";
	require_once "./template/header.php";
	require_once "./function/database_function.php";
	$conn = db_connect();

	if(isset($_POST['add'])){
		$ins_name = trim($_POST['ins_name']);
		$ins_name = mysqli_real_escape_string($conn, $ins_name);
		
        $public_private = '公立';

		$addr = trim($_POST['addr']);
		$addr = mysqli_real_escape_string($conn, $addr);

        $dist = substr($addr, 9, 9);

		$longitude = trim($_POST['longitude']);
		$longitude = mysqli_real_escape_string($conn, $longitude);
		
		$latitute = trim($_POST['latitute']);
		$latitute = mysqli_real_escape_string($conn, $latitute);
		
        if (isset($_POST['長照型'])){
            $orient0 = "長照";
        }else{
            $orient0 = "NULL";
        }
        if (isset($_POST['養護型'])){
            $orient1 = "養護";
        }else{
            $orient1 = "NULL";
        }
        if (isset($_POST['失智照顧型'])){
            $orient2 = "失智";
        }else{
            $orient2 = "NULL";
        }
        if (isset($_POST['安養型'])){
            $orient3 = "安養";
        }else{
            $orient3 = "NULL";
        }

		$caring_num = trim($_POST['caring_num']);
		$caring_num = mysqli_real_escape_string($conn, $caring_num);
		
		$nurse_num = trim($_POST['nurse_num']);
		$nurse_num = mysqli_real_escape_string($conn, $nurse_num);
		
		$dem_num = trim($_POST['dem_num']);
		$dem_num = mysqli_real_escape_string($conn, $dem_num);
		
		$long_caring_num = intval(trim($_POST['long_caring_num']));
		$long_caring_num = mysqli_real_escape_string($conn, $long_caring_num);
		
        $total_bed_num = $caring_num + $nurse_num + $dem_num + $long_caring_num;

		$providing_num = intval(trim($_POST['providing_num']));
		$providing_num = mysqli_real_escape_string($conn, $providing_num);
		
		$manager = trim($_POST['manager']);
		$manager = mysqli_real_escape_string($conn, $manager);
        
        $phone = trim($_POST['phone']);
		$phone = mysqli_real_escape_string($conn, $phone);
		
		$website = trim($_POST['website']);
		$website = mysqli_real_escape_string($conn, $website);

		if ($type == 0) {
			$hinv = 0;
		}
		$query = "INSERT INTO tmp1(`public_private`, `ins_name`, `website`, `host_name`, `dist`, `addr`, 
        `phone0`, `total_bed_num`, `long_caring`, `nursing`, `dementia`, 
        `caring`, `total_toll`, `longitude`, `latitude`, 
        `orient0`, `orient1`, `orient2`, `orient3`)
        VALUES ('" . $public_private . "', '" . $ins_name . "', '" . $website . "', '" . $manager . "', '" . $dist . "', '" . $addr . "',
         '" . $phone . "', '" . $total_bed_num . "', '" . $long_caring_num . "', '" . $nurse_num . "', '" . $dem_num . "', 
         '" . $caring_num . "', '" . $providing_num . "', '" . $longitude . "', '" . $latitute . "',
         '" . $orient0 . "','" . $orient1 . "','" . $orient2 . "','" . $orient3 . "');";
		
        $result = mysqli_query($conn, $query);

        $sql = "SELECT ins_num FROM tmp1 ORDER BY ins_num DESC LIMIT 1;";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $ins_num = $row['ins_num'];

        if(!$result) $err =  "無法新增機構 " . mysqli_error($conn);

		$query_insert = "INSERT INTO institution (ins_num, ins_name) SELECT ins_num, ins_name FROM tmp1 WHERE ins_num = '{$ins_num}';";
        $query_insert .= "INSERT INTO ins_address (ins_num, addr, city, dist, longitude, latitude) 
                        SELECT ins_num, addr, LEFT(addr, 3) as city, dist, longitude, latitude FROM tmp1 WHERE ins_num = '{$ins_num}';";
		$query_insert .= "INSERT INTO ins_info (ins_num, manager, phone, website)
                        SELECT ins_num, host_name, phone0, website FROM tmp1 WHERE ins_num = '{$ins_num}';";
        $query_insert .= "INSERT INTO ins_capacity (ins_num, caring_num, nurse_num, dem_num, long_caring_num, housing_num, providing_num)
                        SELECT ins_num, caring, nursing, dementia, long_caring, (caring + nursing + dementia + long_caring) as housing_num, total_toll 
                        FROM tmp1 WHERE ins_num = '{$ins_num}';";
        $query_insert .= "INSERT INTO type_func (ins_num, func_name) SELECT ins_num, orient0 FROM tmp1 WHERE ins_num = '{$ins_num}' AND orient0 IS NOT NULL;";
        $query_insert .= "INSERT INTO type_func (ins_num, func_name) SELECT ins_num, orient1 FROM tmp1 WHERE ins_num = '{$ins_num}' AND orient1 IS NOT NULL;";
        $query_insert .= "INSERT INTO type_func (ins_num, func_name) SELECT ins_num, orient2 FROM tmp1 WHERE ins_num = '{$ins_num}' AND orient2 IS NOT NULL;";
        $query_insert .= "INSERT INTO type_func (ins_num, func_name) SELECT ins_num, orient3 FROM tmp1 WHERE ins_num = '{$ins_num}' AND orient3 IS NOT NULL;";
        
        $result_insert = mysqli_multi_query($conn, $query_insert);
        if(!$result_insert) $err =  "無法插入機構 " . mysqli_error($conn);

        if($result AND $result_insert){
			$_SESSION['success'] = "新的機構已成功新增";
			header("Location: manage_system.php");
		}
	}
?>
<style>
  .input-group-vertical {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .custom-checkbox {
    margin-bottom: 10px;
    padding-left: 20px; /* 左侧空白距离 */
    padding-right: 20px; /* 右侧空白距离 */
  }

  .custom-checkbox input[type="checkbox"] {
    transform: scale(2); /* 改变勾选框的大小 */
    margin-right: 10px; /* 调整勾选框与文本之间的间距 */
  }
  .custom-checkbox label {
    font-size: 20px; /* 调整标签的字体大小 */
  }
</style>
	<h4 class="fw-bolder text-center">新增機構</h4>
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
						<form method="post" action="admin_ins_add.php" enctype="multipart/form-data">
								<div class="mb-3">
									<label class="control-label">名稱</label>
									<input class="form-control rounded-0" type="text" name="ins_name" required>
								</div>
								<div class="mb-3">
									<label class="control-label">地址</label>
									<input class="form-control rounded-0" type="text" name="addr" required>
								</div>
                                <div class="mb-3">
									<label class="control-label">經度</label>
									<input class="form-control rounded-0" type="float" name="longitude" required>
								</div>
                                <div class="mb-3">
									<label class="control-label">緯度</label>
									<input class="form-control rounded-0" type="float" name="latitute" required>
								</div>
                                <div class="input-group input-group-vertical">
                                    <?php
                                    $checkboxes = array(
                                    '安養型' => '安養型',
                                    '養護型' => '養護型',
                                    '失智照顧型' => '失智照顧型',
                                    '長照型' => '長照型'
                                    );

                                    foreach ($checkboxes as $key => $label) {
                                    echo '<div class="custom-checkbox">';
                                    echo '<input type="checkbox" name="' . $key . '" id="' . $key . '">';
                                    echo '<label for="' . $key . '">' . $label . '</label>';
                                    echo '</div>';
                                    }
                                    ?>
                                </div>
								<div class="mb-3">
									<label class="control-label">安養型床位</label>
									<input class="form-control rounded-0" type="number" name="caring_num" required min="0" step="1">
								</div>
								<div class="mb-3">
									<label class="control-label">養護型床位</label>
									<input class="form-control rounded-0" type="number" name="nurse_num" required min="0" step="1">
								</div>
								<div class="mb-3">
									<label class="control-label">失智照顧型床位</label>
									<input class="form-control rounded-0" type="number" name="dem_num" required min="0" step="1">
								</div>
								<div class="mb-3">
									<label class="control-label">長照型床位</label>
									<input class="form-control rounded-0" type="number" name="long_caring_num" required min="0" step="1">
								</div>
                                <div class="mb-3">
                                <label class="control-label">總收容人數</label>
                                    <input class="form-control rounded-0" type="number" name="providing_num" required min="0" step="1">
                                </div>
								<div class="mb-3">
                                <label class="control-label">負責人</label>
                                    <input class="form-control rounded-0" type="text" name="manager" required>
                                </div>
                                <div class="mb-3">
                                    <label class="control-label">電話</label>
                                    <input class="form-control rounded-0" type="text" name="phone" required>
                                </div>
                                <div class="mb-3">
                                    <label class="control-label">網站</label>
                                    <input class="form-control rounded-0" type="text" name="website" required>
                                </div>
                                
                                <div class="text-center">
									<button type="submit" name="add"  class="btn btn-primary btn-sm rounded-0">新增</button>
									<button type="reset" class="btn btn-default btn-sm rounded-0 border">取消</button>
								</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div style="height: 20px;"></div>