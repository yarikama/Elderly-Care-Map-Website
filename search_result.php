<?php
  session_start();
  $count = 0;
  // connecto database
  require_once "./function/database_function.php";
  $conn = db_connect();
  
  $city = $_SESSION['city'];
  if (isset($_POST['dist'])) $dist = $_POST['dist'];
  $sql = "SELECT distinct institution.ins_num, ins_name, caring_num, nurse_num, dem_num, long_caring_num
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
  if (isset($_POST['長照'])){
    $conditions[] = "func_name = '長照'";
    $func_name[] = "長照";
    $func_num[] = "long_caring_num";
  }
  if (isset($_POST['養護'])){
    $conditions[] = "func_name = '養護'";
    $func_name[] = "養護";
    $func_num[] = "nurse_num";
  }
  if (isset($_POST['失智'])){
    $conditions[] = "func_name = '失智'";
    $func_name[] = "失智";
    $func_num[] = "dem_num";
  }
  if (isset($_POST['安養'])){
    $conditions[] = "func_name = '安養'";
    $func_name[] = "安養";
    $func_num[] = "caring_num";
  }
  if (!empty($conditions)) {
    $sql .= " AND (" . implode(" OR ", $conditions) . ") ORDER BY ".implode(", ", $func_num).";";
  }
  $result = mysqli_query($conn, $sql);
  
  
  if(!$result){
    echo "Can't retrieve data " . mysqli_error($conn);
    exit;
  }

  $title = "List of Books";
  require_once "./template/header.php";
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="institutes.php" class="text-decoration-none text-muted fw-light">總覽</a></li>
        <li class="breadcrumb-item active">搜尋結果</li>
    </ol>
</nav>

<br/>
<p class="lead text-center text-muted">位於<?php echo " ".$city." "; ?><?php echo $dist." "; ?></p>
  <p class="lead text-center text-muted">含有&nbsp<span style="color: red;"><?php echo implode(", ", $func_name); ?></span>
  服務的機構</p>
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

