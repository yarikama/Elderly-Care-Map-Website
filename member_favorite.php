<?php
	session_start();
	// require_once "./functions/admin.php";
	$title = "Order Information";
	require_once "./template/header.php";
	require_once "./functions/database_functions.php";
    
    $memberid = $_SESSION['member']['member_ID'];

	$conn = db_connect();
	$sql = "SELECT 
	FROM member_favorite, institution
    WHERE member_ID = '$memberid' AND institution.ins_num = member_favorite.ins_num
    ;";
    $result = mysqli_query($conn, $sql);
?>
<div><br></div>
<nav aria-label="breadcrumb" style="display: flex; justify-content: center;">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="member_info.php" class="text-decoration-none text-muted fw-light">會員資訊</a></li>
        <li class="breadcrumb-item"><a href="user_view_order.php" class="text-decoration-none text-muted fw-light">訂單資訊</a></li>
        <li class="breadcrumb-item"><a href="user_view_coupon.php" class="text-decoration-none text-muted fw-light">查看優惠券</a></li>
    </ol>
</nav>
<h4 class="fw-bolder text-center">機構資訊</h4>
<center>
<hr class="bg-warning" style="width:5em;height:3px;opacity:1">
</center>

<div class="card rounded-0">
	<div class="card-body">
		<div class="container-fluid">
			<table class="table table-striped table-bordered text-left" >
			<colgroup>
				<col width="100px">
				<col width="200px">
				<col width="100px">
				<col width="100px">
				<col width="100px">
				<col width="100px">
				<col width="100px">
				<col width="100px">
				<col width="100px">
			</colgroup>
				<thead>
				<tr>
					<th>訂單編號</th>
					<th>訂單日期</th>
					<th>付款方式</th>
					<th>運送方式</th>
					<th>總價</th>
					<th>發票方式</th>
					<th>優惠券</th>
					<th>統編</th>
					<th>取貨地點</th>
				</tr>
				</thead>
				<tbody>
				<?php while($row = mysqli_fetch_assoc($result)){ ?>
				<tr>
					<td class="px-2 py-1 align-middle"><?php echo $row['order_ID']; ?></td>
					<td class="px-2 py-1 align-middle"><?php echo $row['order_date']; ?></td>
					<td class="px-2 py-1 align-middle"><?php echo $row['payment_method']; ?></td>
					<td class="px-2 py-1 align-middle">
						<?php 
						$disttype = $row['dist_type'];
						$conn = db_connect();
						$sql = "SELECT * FROM dist_list where dist_type = '$disttype';";
						$result2 = mysqli_query($conn, $sql);
						$row2 = mysqli_fetch_assoc($result2);
						echo $row2['dist_name']; 
						?>
					</td>

					<td class="px-2 py-1 align-middle"><?php echo $row['total_price']; ?></td>
					<td class="px-2 py-1 align-middle"><?php echo $row['invoice_type']; ?></td>
					<td class="px-2 py-1 align-middle">
						<?php 
						$couponid = $row['coupon_ID'];
						$conn = db_connect();
						$sql = "SELECT * FROM coupon where coupon_ID = '$couponid';";
						$result3 = mysqli_query($conn, $sql);
						$row3 = mysqli_fetch_assoc($result3);
						echo $row3['coupon_name']; 
						?>
					</td>
					<td class="px-2 py-1 align-middle"><?php echo $row['uniform_number']; ?></td>
					<td class="px-2 py-1 align-middle"><?php echo $row['dist_loc']; ?></td>
					<td class="px-2 py-1 align-middle text-center">
						<div class="btn-group btn-group-sm">
							<a href="user_order_detail.php?orderid=<?php echo $row['order_ID']; ?>" class="btn btn-sm rounded-0 btn-primary" title="Edit"><i class="fa fa-align-left"></i></a>
							<a href="user_order_delete.php?orderid=<?php echo $row['order_ID']; ?>" class="btn btn-sm rounded-0 btn-danger" title="Delete" onclick="if(confirm('確定要刪除這本書的訂單嗎?') === false) event.preventDefault()"><i class="fa fa-trash"></i></a>
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

