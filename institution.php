<?php
  session_start();
  $book_isbn = $_GET['bookisbn'];
  // connecto database
  require_once "./functions/database_functions.php";
  $conn = db_connect();

  $query = "SELECT * FROM book WHERE book_isbn = '$book_isbn'";
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

  $title = $row['book_title'];
  require "./template/header.php";
?>
      <!-- Example row of columns -->
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="books.php" class="text-decoration-none text-muted fw-light">總覽</a></li>
          <li class="breadcrumb-item active" aria-current="page"><?php echo $row['book_name']; ?></li>
        </ol>
      </nav>
      <div class="row">
        <div class="col-md-3 text-center book-item">
          <div class="img-holder overflow-hidden">
            <img class="img-top" src="./img/<?php echo $row['book_ISBN']; ?>.jpg">
          </div>
        </div>
        <div class="col-md-9">
          <div class="card rounded-0 shadow">
            <div class="card-body">
              <div class="container-fluid">
                <h4><?= $row['book_name'] ?></h4>
                <hr>
                  <h4>書本簡介</h4>
                  <table class="table">
                    
                    <form method="post" action="cart.php">
                      <input type="hidden" name="bookisbn" value="<?php echo $book_isbn;?>">
                    <tr>
                      <th>ISBN</th>
                      <td><?php echo $row['book_ISBN']; ?></td>
                      <td></td>
                    </tr>
                    <tr>
                      <th>作者</th>
                      <td><?php echo $row['book_author']; ?></td>
                      <td></td>
                    </tr>
                    <tr>
                      <th>出版社</th>
                      <td><?php echo $row['book_pub']; ?></td>
                      <td></td>
                    </tr>
                    <tr>
                      <th>出版日期</th>
                      <td><?php echo $row['book_year']; ?></td>
                      <td></td>
                    </tr>
                    <tr>
                      <th>語言</th>
                      <td><?php echo $row['book_language']; ?></td>
                      <td></td>
                    </tr>
                    <!-- 平裝 -->
                    <?php if($row['book_type'] == 0){?>
                      <input type="hidden" name="line_type" value="平裝版">
                      <tr>
                        <th>版本</th>
                        <td>平裝版</td>
                      </tr>
                      <tr>
                        <th>優惠價</th>
                        <td><font color="red"><strong><?php echo $row['book_price']." "; ?></font></strong>元</td>
                      </tr>
                      <tr>
                        <th>庫存</th>
                        <td><?php echo $row['book_softinv']." 本"; ?></td>
                      </tr>
                      <!-- 平裝+精裝 -->
                    <?php }elseif($row['book_type'] == 1){ ?>
                      <tr>
                        <th style="width: 350px;">版本</th>
                        <td style="width: 120px;">平裝版</td>
                        <td>精裝版</td>
                      </tr>
                      <tr>
                        <th>優惠價</th>
                        <td><font color="red"><strong><?php echo $row['book_price']." "; ?></font></strong>元</td>
                        <td><font color="red"><strong><?php echo round($row['book_price']*1.2)." "; ?></font></strong>元</td>
                      </tr>
                      <tr>
                        <th>庫存</th>
                        <td><?php echo $row['book_softinv']." 本"; ?></td>
                        <td><?php echo $row['book_hardinv']." 本"; ?></td>
                      </tr>
                      <tr>
                        <th>可購買版本</th>
                        <td>
                          <select class="form-select rounded-0" name="line_type" style="width: 200px;">
                            <option value="平裝版">平裝版</option>
                            <option value="精裝版">精裝版</option>
                          </select>  
                        </td>         
                      </tr>

                    <?php
                      }
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