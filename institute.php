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
  if(!$result){
    echo "Can't retrieve data " . mysqli_error($conn);
    exit;
  }

  $row = mysqli_fetch_assoc($result);
  if(!$row){
    echo "Empty book";
    exit;
  }

  $title = $row['ins_name'];
  require "./template/header.php";
?>
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
                      <td><?php echo $row['addr']; ?></td>
                      <td></td>
                    </tr>
                    <tr>
                      <th>負責人</th>
                      <td><?php echo $row['manager']; ?></td>
                      <td></td>
                    </tr>
                    <tr>
                      <th>電話</th>
                      <td><?php echo $row['phone']; ?></td>
                      <td></td>
                    </tr>
                    <tr>
                    <th>網站</th>
                    <td><a href="<?php echo $row['website']; ?>"><?php echo $row['website']; ?></a></td>
                    <td></td>
                    </tr>
                      <!-- 平裝+精裝 -->
                      <tr>
                        <th>項目</th>
                        <td>安養</td>
                        <td>養護</td>
                        <td>失智</td>
                        <td>長照</td>
                        <td>總床位數</td>
                        <td>總收容人數</td>
                      </tr>
                      <tr>
                        <th>床位數</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      

                    <?php
                      if(isset($conn)) {mysqli_close($conn); }
                    ?>
                  </table>
                  
                    <div class="text-center">
                      <input type="submit" value="加入購物車" name="cart" class="btn btn-primary rounded-0">
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