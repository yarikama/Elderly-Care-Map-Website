<?php
  session_start();
  require_once "./function/database_function.php";
  $count = 0;
  // connecto database
  $title = "快速搜尋";
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
    transform: scale(1.5); /* 改变勾选框的大小 */
    margin-right: 10px; /* 调整勾选框与文本之间的间距 */
  }      
</style>
<div>
  <br/>
  <p class="lead text-center text-muted">搜尋長照機構</p>
</div>
<div style="text-align: center;">
  <hr class="bg-warning" style="width: 5em; height: 3px; opacity: 1; margin-left: auto; margin-right: auto;">
</div>
<div class="row justify-content-center">
  <div class="col-8">
    <form class="form-inline mb-4" method="POST" action="institutes_dist.php">
      
    <div class="input-group input-group-vertical">
      <label for="pub_year" class="control-label ">所在縣市&nbsp&nbsp;</label>
      <select class="form-select rounded-0" id="city" name="city" style="width: 50px; height: 40px;" >
        <?php
        $conn = db_connect();
        $sql = "SELECT distinct city FROM ins_address";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
        echo "<option value=\"".$row['city']."\">".$row['city']."</option>";
        }
        ?>            
      </select>
      
      <label for="pub_year" class="control-label ">&nbsp&nbsp所在區域&nbsp&nbsp;</label>
      <input type="text" name="all" class="form-control rounded-0" value="全區域" disabled>
    </div>
    <br/>
    <div class="input-group input-group-vertical">
      <div style="display: block; text-align: center;">
        <button type="submit" name="next" class="btn btn-secondary rounded-0">下一步</button>
      </div>
    </div>
    </form>
  </div>
</div>
<div style="text-align: center;">
  <hr class="bg-warning" style="width: 150ex; height: 1px; opacity: 1; margin-left: auto; margin-right: auto;">
</div>


<?php
$conn = db_connect();

$query = "SELECT ins_num, ins_name FROM institution";
$result = mysqli_query($conn, $query);
if(!$result){
  echo "Can't retrieve data " . mysqli_error($conn);
  exit;
}
?>
<br/>
  <p class="lead text-center text-muted">長照機構總覽</p>
    <?php for($i = 0; $i < mysqli_num_rows($result); $i++){ ?>
      <div class="row">
        <?php while($row = mysqli_fetch_assoc($result)){ ?>
          <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 py-2 mb-2">
      		<a href="institute.php?insnum=<?php echo $row['ins_num']; ?>" class="card rounded-0 shadow book-item text-reset text-decoration-none">
            <div class="card-body">
              <div class="card-title fw-bolder h5 text-center"><?= $row['ins_name'] ?></div>
            </div>
          </a>
      	</div>
        <?php
          $count++;
          if($count >= 4){
              $count = 0;
              break;
            }
          } ?> 
      </div>
<?php
      }
  if(isset($conn)) { mysqli_close($conn); }
  require_once "./template/footer.php";
?>