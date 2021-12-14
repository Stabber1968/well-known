<?php
require_once('../Controllers/init.php');
require_once('../Entity/User.php');

$user = new User();
if(!$user->isLoggedIn())
{
    header('location:../index.php?lmsg=true');
    exit;
}

$backupInfo = $p_general->getValueOfAnyTable('backup','id','=','1');
$backupInfo = $backupInfo->results();

require_once('layout/header.php');
require_once('layout/navbar.php');
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Backup & SMTP</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item active">Backup & SMTP</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <form method="post" action="../Logic/saveBackup.php" enctype='multipart/form-data' id="editClientFormValidate">
                <div class="row">
                    <div class="col-md-5">
                        <div class="card card-default color-palette-box">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-tag"></i>
                                    Send to E-Mail
                                </h3>
                            </div>
                            <div class="card-body">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="<?=$backupInfo[0]->send_email?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card card-default color-palette-box">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-tag"></i>
                                    SMTP Configuration
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>SMTP Host (ex: smtp.gmail.com)</label>
                                    <input type="text" class="form-control" id="smtp_host" name="smtp_host" placeholder="SMTP Host"  value="<?=$backupInfo[0]->smtp_host?>">
                                </div>
                                <div class="form-group">
                                    <label>Encryption (ex: SSL)</label>
                                    <select class="form-control select2bs4" name="smtp_encryption" id="smtp_encryption" style="width: 100%;">
                                        <option value="none" <?=($backupInfo[0]->smtp_encryption == "none")?  "selected" : "";?> >none</option>
                                        <option value="ssl" <?=($backupInfo[0]->smtp_encryption == "ssl") ? "selected" : ""?> >SSL</option>
                                        <option value="tls" <?=($backupInfo[0]->smtp_encryption == "tls") ? "selected" : ""?>  >TLS</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>SMTP Port (ex: 465)</label>
                                    <input type="text" class="form-control" id="smtp_port" name="smtp_port" placeholder="SMTP Port" value="<?=$backupInfo[0]->smtp_port?>">
                                </div>
                                <div class="form-group">
                                    <label>SMTP Username (ex: email@gmail.com)</label>
                                    <input type="text" class="form-control" id="smtp_username" name="smtp_username" placeholder="SMTP Username" value="<?=$backupInfo[0]->smtp_username?>">
                                </div>
                                <div class="form-group">
                                    <label>SMTP Password</label>
                                    <input type="password" class="form-control" id="smtp_password" name="smtp_password" placeholder="SMTP Password">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <input type="submit" value="Save Changes" class="btn btn-success float-right">
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<?php
require_once('layout/sidebar.php');
require_once('layout/footer.php');
echo "
    <script type=\"text/javascript\">
    var e = document.getElementById('smtp_password'); e.value='".$backupInfo[0]->smtp_password."';
    </script>
";
?>
