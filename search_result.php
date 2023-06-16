<?php
  session_start();
  $count = 0;
  // connecto database
  require_once "./function/database_function.php";
  require_once "./template/header.php";
  $city = $_SESSION['city'];
  if (isset($_POST['dist'])) $dist = $_POST['dist'];
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

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="institutes.php" class="text-decoration-none text-muted fw-light">選擇其他縣市 /</a></li>
    </ol>
</nav>

<div>
  <br/>
  <p class="lead text-center text-muted">搜尋長照機構</p>
</div>
<div style="text-align: center;">
  <hr class="bg-warning" style="width: 5em; height: 3px; opacity: 1; margin-left: auto; margin-right: auto;">
</div>
<div class="row justify-content-center">
  <div class="col-8">
    <form class="form-inline mb-4" method="POST" action="search_result.php">
    <div class="input-group input-group-vertical">
      <label for="pub_year" class="control-label ">所在縣市&nbsp&nbsp;</label>
      <input type="text" name="city" class="form-control rounded-0" value="<?php echo $city?>" disabled>
      <label for="pub_year" class="control-label ">&nbsp&nbsp所在區域&nbsp&nbsp;</label>
      <select class="form-select rounded-0" id="dist" value="$dist" name="dist" style="width: 50px; height: 40px;" >
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

<?php
  $conn = db_connect();
  
  $sql = "SELECT distinct institution.ins_num, ins_name, caring_num, nurse_num, dem_num, long_caring_num, 
  (housing_num-providing_num) as avaliable_num
  FROM type_func, institution, ins_address, ins_capacity
  WHERE institution.ins_num = type_func.ins_num AND institution.ins_num = ins_address.ins_num 
  AND institution.ins_num = ins_capacity.ins_num
  AND city = '$city' ";
  
  $conditions = array();
  $func_name = array();
  $func_num = array();
  if($_POST['dist'] != "全區域"){
    $sql .= " AND dist = '$dist' ";
  }
  if (isset($_POST['長照型'])){
    $conditions[] = "func_name = '長照'";
    $func_name[] = "長照型";
    $func_num[] = "long_caring_num";
  }
  if (isset($_POST['養護型'])){
    $conditions[] = "func_name = '養護'";
    $func_name[] = "養護型";
    $func_num[] = "nurse_num";
  }
  if (isset($_POST['失智照顧型'])){
    $conditions[] = "func_name = '失智'";
    $func_name[] = "失智照顧型";
    $func_num[] = "dem_num";
  }
  if (isset($_POST['安養型'])){
    $conditions[] = "func_name = '安養'";
    $func_name[] = "安養型";
    $func_num[] = "caring_num";
  }
  if (!empty($conditions)) {
    $sql .= " AND (" . implode(" OR ", $conditions) . ") ORDER BY (housing_num-providing_num);";
  }
  $result = mysqli_query($conn, $sql);
  
  
  if(!$result){
    echo "Can't retrieve data " . mysqli_error($conn);
    exit;
  }

  $title = "Search Result";
?>

<br/>
<p class="lead text-center text-muted">位於<u><?php echo " ".$city." "; ?><?php echo $dist." "; ?></u>
&nbsp含有&nbsp<span style="color: red;"><?php echo implode(", ", $func_name); ?></span>
  服務的機構：</p>
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
    <?php } ?>

<br/>
  
    
<?php
  if(isset($conn)) { mysqli_close($conn); }
  require_once "./template/footer.php";
?>

