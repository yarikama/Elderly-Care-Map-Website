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
  $row = mysqli_fetch_assoc($result);

  $query2 = "SELECT * 
  FROM type_func 
  JOIN func_web
  ON type_func.func_name = func_web.func_name
  WHERE ins_num = '$ins_num'";

  $result2 = mysqli_query($conn, $query2);
  if(!$result or !$result2){
    echo "Can't retrieve data " . mysqli_error($conn);
    exit;
  }
  
  if(!$row){
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

  .oval-background {
  width: 70px; /* 調整寬度以適應您的需求 */
  height: 25px; /* 調整高度以適應您的需求 */
  border-radius: 45%; /* 將背景設定為橢圓形 */
  background-color: 	#b0c4de; /* 調整背景顏色 */
  display: flex;
  align-items: center;
  justify-content: center;
  /* 在此添加其他您需要的樣式 */
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
                    
                    <form method="post" action="process.php">
                      <input type="hidden" name="ins_num" value="<?php echo $ins_num;?>">
                    <tr>
                      <th>服務項目</th>
                      <?php 
                      for($i=0;$i<7;$i++){
                        if($row2 = mysqli_fetch_assoc($result2)){
                          echo "<td><a href='".$row2['func_website']."' target='_blank'><div class='oval-background'>".$row2['func_name']."型</div></a></td>";
                        }else{
                          echo "<td></td>";
                        }
                      }
                      ?>
                    </tr>
                    <tr>
                      <th>地址</th>
                      <td  colspan="7"><?php echo $row['addr']; ?></td>
                    </tr>
                    <tr>
                      <th>負責人</th>
                      <td  colspan="7"><?php echo $row['manager']; ?></td>
                    </tr>
                    <tr>
                      <th>電話</th>
                      <td  colspan="7"><?php echo $row['phone']; ?></td>
                    </tr>
                    <tr>
                    <th>網站</th>
                    <td  colspan="7"><a href="<?php echo $row['website']; ?>"><?php echo $row['website']; ?></a></td>
                    </tr>
                    

                    <tr class="no-border">
                      <th>項目</th>
                      <td style="text-align: center;"><b>安養型</b></td>
                      <td style="text-align: center;"><b>養護型</b></td>
                      <td style="text-align: center;"><b>失智照顧型</b></td>
                      <td style="text-align: center;"><b>長照型</b></td>
                      <td style="color: red; text-align: center;"><b>總床位數</b></td>
                      <td style="color: red; text-align: center;"><b>總收容人數</b></td>
                      <td style="color: red; text-align: center;"><b>總剩餘床位</b></td>
                    </tr>
                    <tr>
                      <th>床位數</th>
                      <td style="text-align: center;"><?php echo $row['caring_num']?></td>
                      <td style="text-align: center;"><?php echo $row['nurse_num']?></td>
                      <td style="text-align: center;"><?php echo $row['dem_num']?></td>
                      <td style="text-align: center;"><?php echo $row['long_caring_num']?></td>
                      <td style="color: red; text-align: center;"><?php echo $row['housing_num']?></td>
                      <td style="color: red; text-align: center;"><?php echo $row['providing_num']?></td>
                      <td style="color: red; text-align: center;"><?php echo $row['housing_num']-$row['providing_num']?></td>
                    </tr>
                      

                    <?php
                      if(isset($conn)) {mysqli_close($conn); }
                    ?>
                  </table>
                  
                  <div class="d-flex justify-content-center">
                    <form method="post" action="process.php">
                        <input type="hidden" name="ins_num" value="<?php echo $ins_num;?>">
                        <button type="submit" name="good_list" class="btn btn-primary me-2">加入喜愛列表</button>
                    </form>
                    <button id="searchBtn" class="btn btn-primary" style="background-color: #FF5809;">點我搜尋</button>
                </div>
            </div>
          </div>
       	</div>
      </div>
<?php
  require "./template/footer.php";
?>

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