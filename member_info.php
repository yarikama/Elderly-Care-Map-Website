<?php
session_start();
if (!isset($_SESSION['member'])) {
    header('location:member_login.php');
    exit;
}
$title = "個人資料修改";
require_once "./template/header.php";
?>
<div><br></div>
<nav aria-label="breadcrumb" style="display: flex; justify-content: center;">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="member_info.php" class="text-decoration-none text-muted fw-light">個人資料</a></li>
        <li class="breadcrumb-item"><a href="member_favorite.php" class="text-decoration-none text-muted fw-light">喜愛列表</a></li>
    </ol>
</nav>

<div style="text-align: center;">
<div class="row justify-content-center my-5">
    <div class="col-lg-4 col-md-6 col-sm-10 col-xs-12">
        <div class="card rounded-0 shadow">
            <div class="card-header">
                <div class="card-title text-center h4 fw-bolder">編輯會員資訊</div>
            </div>
            <div class="card-body">
                <div class="container-fluid">
                    <form class="form-horizontal" method="post" action="member_update.php">
                        <?php
                        $attributes = [
                            'member_email' => '我的電子郵件',
                            'member_name' => '我的名字',
                            'member_gender' => '我的性別',
                            'member_phone' => '我的電話號碼',
                            'member_address' => '我的地址',
                            'member_birthday' => '我的生日'
                        ];
                        ?>
                        <?php foreach ($attributes as $attribute => $label) : ?>
                            <div class="mb-3">
                                <label for="<?php echo $attribute; ?>" class="control-label"><?php echo $label; ?></label>
                                <div class="input-group">
                                    <?php if($attribute == 'member_gender'){?>
                                        <select name="member_gender" class="form-control rounded-0" disabled>
                                            <option value="<?php echo ($_SESSION['member'][$attribute] == '男') ? '男':'女'; ?>"><?php echo ($_SESSION['member'][$attribute] == '男') ? '男':'女'; ?></option>
                                            <option value="<?php echo ($_SESSION['member'][$attribute] == '男') ? '女':'男'; ?>"><?php echo ($_SESSION['member'][$attribute] == '男') ? '女':'男'; ?></option>
                                        </select>
                                    <?php }elseif($attribute == 'member_birthday'){?>
                                        <input type="date" name="member_birthday" value="<?php echo $_SESSION['member'][$attribute]?>"class="form-control rounded-0" disabled>
                                    <?php }else{?>
                                    <input type="text" name="<?php echo $attribute; ?>" class="form-control rounded-0" value="<?php echo $_SESSION['member'][$attribute]?>" disabled>
                                    <?php }?>
                                    <?php if ($attribute != 'member_email') : ?>
                                        <button type="button" onclick="enableEditing('<?php echo $attribute; ?>')" class="btn btn-secondary">編輯</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div class="mb-3 d-grid">
                            <input type="submit" name="member_submit" class="btn btn-primary rounded-0" value="更新資訊">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function enableEditing(attribute) {
        let input = document.querySelector("input[name='" + attribute + "']") || document.querySelector("select[name='" + attribute + "']");
        input.disabled = !input.disabled;
        if (!input.disabled) {
            input.focus();
        }
    }
</script>


<?php
require_once "./template/footer.php";
?>
