<?php
  session_start();
  $count = 0;
  // connecto database
  require_once "./function/database_function.php";
  

  $title = "全國長照機構";
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