<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $title; ?></title>
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="./bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="./bootstrap/css/styles.css" rel="stylesheet">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script type="text/javascript" src="./bootstrap/js/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="./bootstrap/js/bootstrap.bundle.min.js"></script>
  </head>

  <body>
    <div class="clear-fix pt-5 pb-3"></div>
    <nav class="navbar navbar-expand-lg  navbar-expand-md navbar-light bg-warning bg-gradient fixed-top">
      <div class="container">
        <div class="navbar-header">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav" aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
          <a class="navbar-brand" href="index.php">
            <img src="images/longcare.png" id='titleIcon' alt="長照 Logo" style="max-height: 40px;"> 
            <label for="titleIcon" id='titleWords'>全國長照機構</label>
          </a>
        </div>

        <!--/.navbar-collapse -->
        <div class="collapse navbar-collapse" id="topNav">
          <ul class="nav navbar-nav">
            <?php if(isset($_SESSION['member']['member_type']) && $_SESSION['member']['member_type'] == 2): ?>
              <li class="nav-item"><a class="nav-link" href="admin_customer.php"><span class="fa fa-th-list"></span> 顧客管理</a></li>
              <li class="nav-item"><a class="nav-link" href="manage_system.php"><span class="fa fa-th-list"></span> 機構管理</a></li>
              <li class="nav-item"><a class="nav-link" href="admin_ins_add.php"><span class="fa fa-add"></span> 新增機構</a></li>
              <li class="nav-item"><a class="nav-link" href="admin_add.php"><span class="fa fa-user-plus"></span> 新增管理員</a></li>
			        <li class="nav-item"><a class="nav-link" href="admin_logout.php"><span class="fa fa-sign-out-alt"></span> 管理員登出</a></li>
            <?php elseif(isset($_SESSION['member']) && $_SESSION['member'] == true): ?>
                <li class="nav-item"><a class="nav-link" href="institutes.php"><span class="fa fa-book"></span> 快速搜尋</a></li>
                <li class="nav-item"><a class="nav-link" href="member_info.php"><span class="fa fa-home"></span> 個人資料</a></li>
                <li class="nav-item"><a class="nav-link" href="member_favorite.php"><span class="fa fa-book"></span> 喜愛列表</a></li>
                <li class="nav-item"><a class="nav-link" href="member_logout.php"><span class="fa fa-sign-out"></span> 會員登出</a></li>
            <?php else: ?>
              <li class="nav-item"><a class="nav-link" href="institutes.php"><span class="fa fa-book"></span> 快速搜尋</a></li>
              <li class="nav-item"><a class="nav-link" href="member_login.php"><span class="fa fa-sign-in"></span> 會員登入</a></li>
              <li class="nav-item"><a class="nav-link" href="member_register.php"><span class="fa fa-user-plus"></span> 會員註冊</a></li>
            <?php endif; ?>
            </ul>
        </div>
      </div>
    </nav>

    <style>
      #titleWords:hover{
        cursor: pointer;
      }
    </style>
    <?php
      if(isset($title) && $title == "EMO大書店") {
    ?>
    
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="container">
      <marquee behavior="scroll" direction="left" scrollamount="15">
        <h1>
          歡迎光臨
          <img src="emo.png" alt="emo" style="max-height: 40px;">  
          EMO大書店
          <img src="emo1.png" alt="emo1" style="max-height: 40px;">
          來挑本EMO時看的好書
        </h1>
      </marquee>
      <hr>
    </div>
    <?php } ?>

    <div class="container" id="main">