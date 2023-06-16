<?php
  session_start();
  $count = 0;
  // connecto database
  require_once "./function/database_function.php";
  

  $title = "長照機構";
  require_once "./template/header.php";
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
<?php
if(isset($_POST['next'])){
  $city = $_POST['city'];
  $_SESSION['city'] = $city;
}
?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="manage_system.php" class="text-decoration-none text-muted fw-light">選擇其他縣市 /</a></li>
    </ol>
</nav>
<div>
  <p class="lead text-center text-muted">長照機構管理</p>
</div>
<div style="text-align: center;">
  <hr class="bg-warning" style="width: 5em; height: 3px; opacity: 1; margin-left: auto; margin-right: auto;">
</div>
<div class="row justify-content-center">
  <div class="col-8">
    <form class="form-inline mb-4" method="POST" action="admin_search_result.php">
    <div class="input-group input-group-vertical">
      <label for="pub_year" class="control-label ">所在縣市&nbsp&nbsp;</label>
      <input type="text" name="city" class="form-control rounded-0" value="<?php echo $city?>" disabled>
      <label for="pub_year" class="control-label ">&nbsp&nbsp所在區域&nbsp&nbsp;</label>
      <select class="form-select rounded-0" id="dist" name="dist" style="width: 50px; height: 40px;" >
        <option value="全區域">全區域</option>
        <?php
        $conn = db_connect();
        $sql = "SELECT distinct dist FROM ins_address WHERE city = '{$city}'";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
        echo "<option value=\"".$row['dist']."\">".$row['dist']."</option>";
        }
        ?>            
      </select>
    </div>
    <br/>
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
    <br/>
    <div class="input-group input-group-vertical">
      <div style="display: block; text-align: center;">
        <button type="submit" name="next" class="btn btn-secondary rounded-0">搜尋
            <li class="fa fa-search"></li>
        </button>
      </div>
    </div>

    </form>
  </div>
</div>
<div style="text-align: center;">
  <hr class="bg-warning" style="width: 150ex; height: 1px; opacity: 1; margin-left: auto; margin-right: auto;">
</div>
<div style="height: 20px;"></div>
	
	
	<style>
	  .my-custom-table td {
		word-wrap: break-word;
		max-width: 100%;
	  }
	  .my-custom-table {
		table-layout: fixed;
	  }
	</style>
	
	<div style="display: flex; justify-content: center;">
	  <div style="width: 100%;">
		<div class="card rounded-0">
		  <div class="card-body">
			<div class="container-fluid">
						
						<table class="table table-striped table-bordered my-custom-table">
						<colgroup>
							<col width="18%">
							<col width="7%">
							<col width="7%">
							<col width="18%">
							<col width="5%">
						</colgroup>
							<thead>
							<tr>
								<th style="text-align: center; vertical-align: middle;">名稱</th>
								<th style="text-align: center; vertical-align: middle;">電話</th>
								<th style="text-align: center; vertical-align: middle;">總床位</th>
								<th style="text-align: center; vertical-align: middle;">地址</th>
								<th style="text-align: center; vertical-align: middle;">功能</th>
							</tr>
							</thead>
							<tbody>
							<?php
							$counter = 0; // 計數器初始化
                            $conn = db_connect();
                            $sql = "SELECT distinct institution.ins_num, ins_name, phone, housing_num, addr FROM institution, ins_address, ins_capacity, ins_info
                            WHERE institution.ins_num=ins_address.ins_num AND institution.ins_num=ins_capacity.ins_num 
                            AND institution.ins_num=ins_info.ins_num AND city = '{$city}'";
                            $result = mysqli_query($conn, $sql);
                            if(!$result){
                                echo "Can't retrieve data " . mysqli_error($conn);
                                exit;
                            }
							while($row = mysqli_fetch_assoc($result)){
								$counter++; // 每迭代一次增加計數器
								
								if ($counter > 500) {
									break; // 超過1000筆資料時跳出迴圈
								}
								?>
								<tr>
									<td class="px-2 py-1 align-middle"><?php echo $row['ins_name']; ?></td>
									<td class="px-2 py-1 align-middle"><?php echo $row['phone']; ?></td>
									<td class="px-2 py-1 align-middle"><?php echo $row['housing_num']; ?></td>
									<td class="px-2 py-1 align-middle"><?php echo $row['addr']; ?></td>
									<td class="px-2 py-1 align-middle text-center">
										<div class="btn-group btn-group-sm">
											<a href="admin_edit_ins.php?insnum=<?php echo $row['ins_num']; ?>" class="btn btn-sm rounded-0 btn-primary" title="編輯"><i class="fa fa-edit"></i></a>
											<a href="admin_delete_ins.php?insnum=<?php echo $row['ins_num']; ?>" class="btn btn-sm rounded-0 btn-danger" title="刪除" onclick="if(confirm('確定要刪除此長照機構?') === false) event.preventDefault()"><i class="fa fa-trash"></i></a>
										</div>
									</td>
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>	

<div style="height: 20px;"></div>
<?php
  if(isset($conn)) { mysqli_close($conn); }
  require_once "./template/footer.php";
?>