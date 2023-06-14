<?php
  session_start();
  require_once "./function/database_function.php";
  $ins_num = $_GET['insnum'];
  // connecto database
  $conn = db_connect();

  $query = "SELECT institution.ins_num, ins_name, addr, caring_num, nurse_num, dem_num, long_caring_num, 
  housing_num, providing_num, manager, phone, website
  FROM institution, ins_address, ins_capacity, ins_info
  WHERE institution.ins_num = '$ins_num' AND institution.ins_num = ins_address.ins_num
  AND institution.ins_num = ins_capacity.ins_num
  AND institution.ins_num = ins_info.ins_num;";
  $result = mysqli_query($conn, $query);

  $query2 = "SELECT func_name FROM type_func WHERE ins_num = '$ins_num';";
  $result2 = mysqli_query($conn, $query2);
  if(!$result or !$result2){
    echo "Can't retrieve data " . mysqli_error($conn);
    exit;
  }

  $row = mysqli_fetch_assoc($result);
  $row2 = mysqli_fetch_assoc($result2);
  if(!$row or !$row2){
    echo "Empty!";
    exit;
  }

  $title = $row['ins_name'];
  require "./template/header.php";
?>
<style>
  .table {
    table-layout: fixed;
  }

  .table td {
    white-space: normal;
    overflow: visible;
  }

  .no-border td{
    border: none; /* 移除边框线 */
  }
  .no-border th{
    border: none; /* 移除边框线 */
  }
</style>
      <!-- Example row of columns -->
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="institutes.php" class="text-decoration-none text-muted fw-light">總覽</a></li>
          <li class="breadcrumb-item active" aria-current="page"><?php echo $row['ins_name']; ?></li>
        </ol>
      </nav>
      <div class="row">
        <div class="col-md-9">
          <div class="card rounded-0 shadow">
            <div class="card-body">
              <div class="container-fluid">
                <h4><?= $row['ins_name'] ?></h4>
                <hr>
                  <h4>機構資訊</h4>
                  <table class="table">
                    
                    <form method="post" action="cart.php">
                      <input type="hidden" name="ins_num" value="<?php echo $ins_num;?>">
                    <tr>
                      <th style="width: 80px;">地址</th>
                      <td  colspan="6"><?php echo $row['addr']; ?></td>
                    </tr>
                    <tr>
                      <th>負責人</th>
                      <td  colspan="6"><?php echo $row['manager']; ?></td>
                    </tr>
                    <tr>
                      <th>電話</th>
                      <td  colspan="6"><?php echo $row['phone']; ?></td>
                    </tr>
                    <tr>
                    <th>網站</th>
                    <td  colspan="6"><a href="<?php echo $row['website']; ?>"><?php echo $row['website']; ?></a></td>
                    </tr>
                    <tr>
                    <th>服務項目</th>
                    <?php 
                    while($row2 = mysqli_fetch_assoc($result2)){
                      echo "<td colspan=\"6\">".$row2['func_name']."</td>";
                      }
                    ?>
                    </tr>

                    <tr  class="no-border">
                      <th>項目</th>
                      <td><b>安養</b></td>
                      <td><b>養護</b></td>
                      <td><b>失智</b></td>
                      <td><b>長照</b></td>
                      <td style="color: red;"><b>總床位數</b></td>
                      <td style="color: red;"><b>總收容人數</b></td>
                    </tr>
                    <tr>
                      <th>床位數</th>
                      <td><?php echo $row['caring_num']?></td>
                      <td><?php echo $row['nurse_num']?></td>
                      <td><?php echo $row['dem_num']?></td>
                      <td><?php echo $row['long_caring_num']?></td>
                      <td style="color: red;"><?php echo $row['housing_num']?></td>
                      <td style="color: red;"><?php echo $row['providing_num']?></td>
                    </tr>
                      

                    <?php
                      if(isset($conn)) {mysqli_close($conn); }
                    ?>
                  </table>
                  
                    <div class="text-center">
                      <input type="submit" value="加入喜愛列表" name="good_list" class="btn btn-primary rounded-0">
                    </div>
                  </form>
              </div>
            </div>
          </div>
       	</div>
      </div>
<?php
  require "./template/footer.php";
?>