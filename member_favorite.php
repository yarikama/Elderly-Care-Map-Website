<?php
	session_start();
	// require_once "./functions/admin.php";
	$title = "我的最愛";
	require_once "./template/header.php";
	require_once "./function/database_function.php";
    
    $memberid = $_SESSION['member']['member_id'];

	$conn = db_connect();
	$sql = "SELECT institution.ins_num, ins_name, phone, housing_num, addr
	FROM member_favorite, institution, ins_address, ins_capacity, ins_info
    WHERE member_id = '$memberid' AND institution.ins_num = member_favorite.ins_num
	AND institution.ins_num = ins_address.ins_num AND institution.ins_num = ins_capacity.ins_num
	AND institution.ins_num = ins_info.ins_num
    ;";
    $result = mysqli_query($conn, $sql);
    if(!$result){
        echo "Can't retrieve data " . mysqli_error($conn);
        exit;
      }
?>
<div><br></div>
<nav aria-label="breadcrumb" style="display: flex; justify-content: center;">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="member_info.php" class="text-decoration-none text-muted fw-light">個人資料</a></li>
        <li class="breadcrumb-item"><a href="member_favorite.php" class="text-decoration-none text-muted fw-light">喜愛列表</a></li>
    </ol>
</nav>
<div><br></div>
<h4 class="fw-bolder text-center">機構喜愛列表</h4>
<center>
<hr class="bg-warning" style="width:5em;height:3px;opacity:1">
</center>

<div class="card rounded-0">
	<div class="card-body">
		<div class="container-fluid">
			<table class="table table-striped table-bordered text-left" >
			<colgroup>
				<col width="200px">
				<col width="100px">
				<col width="100px">
				<col width="200px">
				<col width="80px">
			</colgroup>
				<thead>
				<tr>
					<th>名稱</th>
					<th>電話</th>
					<th>總床位</th>
					<th>地址</th>
					<th>功能</th>
				</tr>
				</thead>
				<tbody>
				<?php while($row = mysqli_fetch_assoc($result)){ ?>
				<tr>
					<td class="px-2 py-1 align-middle"><?php echo $row['ins_name']; ?></td>
					<td class="px-2 py-1 align-middle"><?php echo $row['phone']; ?></td>
					<td class="px-2 py-1 align-middle"><?php echo $row['housing_num']; ?></td>
					<td class="px-2 py-1 align-middle"><?php echo $row['addr']; ?></td>
					<td class="px-2 py-1 align-middle text-center">
						<div class="btn-group btn-group-sm">
							<a href="member_favorite_detail.php?insnum=<?php echo $row['ins_num']; ?>" class="btn btn-sm rounded-0 btn-primary" title="detail"><i class="fa fa-eye"></i></a>
							<a href="member_favorite_delete.php?insnum=<?php echo $row['ins_num']; ?>" class="btn btn-sm rounded-0 btn-danger" title="Delete" onclick="if(confirm('確定要將此機構移出喜愛列表嗎?') === false) event.preventDefault()"><i class="fa fa-trash"></i></a>
						</div>
					</td>
				</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div style="height: 20px;"></div>

