<?php
require_once('../Controllers/init.php');
require_once('../Entity/User.php');

$user = new User();
if(!$user->isLoggedIn())
{
    header('location:../index.php?lmsg=true');
    exit;
}

require_once('layout/header.php');
require_once('layout/navbar.php');

$pTrimmginMethod = new TrimmingMethod();
$mTrimmingMethodLists = $pTrimmginMethod->getAllOfInfo();
$mTrimmingMethodLists = $mTrimmingMethodLists->results();

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <button type="button" class="btn btn-primary"id="btn_addTrimmingMethod">
                        Add Trimming Method
                    </button>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">This table includes <?php echo $user->count(); ?> value.</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="example1" class="table table-bordered table-striped" style="width: 100%">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th style="display:none">id</th>
                                <th>Name</th>
                                <th></th>
                            </tr>

                            </thead>
                            <tbody>
                            <?php
                            if($mTrimmingMethodLists){
                                $k = 0;
                                foreach ($mTrimmingMethodLists as $mTrimmingMethod):
                                    $k++;
                                    ?>
                                    <tr>
                                        <td><?php echo $k?></td>
                                        <td style="display:none" class="td_id"><?php echo $mTrimmingMethod->id?></td>
                                        <td class="td_name"><?php echo $mTrimmingMethod->name?></td>
                                        <td style="text-align: center">
                                            <a class="btn bg-gradient-green btn-sm" id="btn_editTrimmingMethod" data-target="#modal-add-trimming-method" href="#modal-add-trimming-method" data-toggle="modal">
                                                <i class="fas fa-pencil-alt"></i>
                                                Edit
                                            </a>
                                            <a class="btn bg-gradient-danger btn-sm" href="../Logic/saveTrimmingMethod.php?act=delete&id=<?php echo $mTrimmingMethod->id?>&cat=users">
                                                <i class="fas fa-trash"></i>
                                                Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach;} ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- User modal (Add/Edit) -->
<div class="modal fade" id="modal-add-trimming-method">
    <div class="modal-dialog">
        <div class="modal-content">
            <!--header-->
            <div class="modal-header">
                <h4 class="modal-title">Add New Trimming Method</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" action="../Logic/saveTrimmingMethod.php" enctype='multipart/form-data' id="editTrimmingMethodFormValidate">
                <!--body-->
                <div class="modal-body">
                    <fieldset>
                        <input name="id" id="id" type="hidden" value="">
                        <input name="act" id="act" type="hidden" value="">

                        <label>Name</label>
                        <div class="form-group input-group mb-3">
                            <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                </div>

                <!--footer-->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <input type="submit" id ="saveTrimmingMethod" class="btn btn-primary" value="Save"></input>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $( document ).ready( function () {

        $("#example1").DataTable();

        $( "#btn_addTrimmingMethod" ).click(function() {
            var act = "add"

            $("#act").val(act);
            $("#modal-add-trimming-method").modal('show');
        });

        //reference url https://jsfiddle.net/1s9u629w/1/
        $('tr #btn_editTrimmingMethod').click(function() {
            var $row = $(this).closest('tr');
            var rowID = $row.attr('class').split('_')[1];
            var id = $row.find('.td_id').text();
            var act = "edit";
            var name =  $row.find('.td_name').text();

            $("#id").val(id);
            $("#act").val(act);
            $("#name").val(name);
        });

        //modal close
        $('#modal-add-trimming-method').on('hidden.bs.modal', function () {
            $("#name").val("");
        })

        //submit
        $('input#saveTrimmingMethod').click(function (event) {
            var name = $('input[name=name]').val();
            var act = $('input[name=act]').val();

            if (act == 'add'){
                if (name){
                    event.preventDefault();
                    $.ajax({
                        method:'POST',
                        url: '../Logic/saveTrimmingMethod.php',
                        data:{act:'validate', name:name},
                        success:function (data) {
                            var obj = JSON.parse(data);
                            if(obj == 'SameName'){
                                alert('Exist Same Name');
                            }else{
                                $('#editTrimmingMethodFormValidate').submit();
                            }
                        }
                    })
                }
            }

            if (act == 'edit'){
                $('#editTrimmingMethodFormValidate').submit();
            }

            return false;
        })

    });

</script>


<?php
require_once('layout/sidebar.php');
require_once('layout/footer.php');

?>
