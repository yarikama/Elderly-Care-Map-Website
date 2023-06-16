<?php
	session_start();
	$title = "會員資訊";
	require_once "./template/header.php";
	require_once "./function/database_function.php";
	$conn = db_connect();
	$sql = "SELECT distinct `member_id`, `member_name`, `member_email`, `member_address`, `member_phone`, 
            `member_birthday`, `member_gender`, `member_type`
            FROM `member` WHERE member_type = 1;";	
    $result = mysqli_query($conn, $sql);
?>
    <br/>
	<h4 class="fw-bolder text-center">顧客資訊管理</h4>
	<center>
	<hr class="bg-warning" style="width:5em;height:3px;opacity:1">
	</center>
	<?php if(isset($_SESSION['username'])): ?>
		<div class="alert alert-success rounded-0">
			<?= $_SESSION['username'] ?>
		</div>
	<?php 
		unset($_SESSION['username']);
		endif;
	?>
	<div class="card rounded-0">
		<div class="card-body">
			<div class="container-fluid">
				<table class="table table-striped table-bordered text-left" >
				<colgroup>
                    <col width="150px">
					<col width="100px">
					<col width="50px">
					<col width="150px">
					<col width="250px">
					<col width="150px">
					<col width="100px">
                    <col width="120px">
				</colgroup>
					<thead>
					<tr>
						<th>帳號</th>
						<th>姓名</th>
						<th>Email</th>
						<th>地址</th>
						<th>電話</th>
						<th>生日</th>
						<th>性別</th>
                        <th>編輯</th>
					</tr>
					</thead>
					<tbody>
					<?php while($row = mysqli_fetch_assoc($result)){ ?>
					<tr>
						<td class="px-2 py-1 align-middle"><?php echo $row['member_id']; ?></td>
                        <td class="px-2 py-1 align-middle"><?php echo $row['member_name']; ?></td>
						<td class="px-2 py-1 align-middle"><?php echo $row['member_email']; ?></td>
						<td class="px-2 py-1 align-middle"><?php echo $row['member_address']; ?></td>
						<td class="px-2 py-1 align-middle"><?php echo $row['member_phone']; ?></td>
						<td class="px-2 py-1 align-middle"><?php echo $row['member_birthday']; ?></td>
						<td class="px-2 py-1 align-middle"><?php echo $row['member_gender']; ?></td>
						<td class="px-2 py-1 align-middle text-center">
							<div class="btn-group btn-group-sm">
								<a href="admin_customer_edit.php?memberid=<?php echo $row['member_id']; ?>" class="btn btn-sm rounded-0 btn-primary" title="Edit"><i class="fa fa-edit"></i></a>
								<a href="admin_customer_delete.php?memberid=<?php echo $row['member_id']; ?>" class="btn btn-sm rounded-0 btn-danger" title="Delete" onclick="if(confirm('確定要刪除此位顧客?') === false) event.preventDefault()"><i class="fa fa-trash"></i></a>
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

