
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- SEARCH FORM -->
        <div class="form-inline ml-3">
            <div class="input-group input-group-md">
                <input class="form-control form-control-navbar" type="search"  placeholder="Search" aria-label="Search" autocomplete="off">
                <!--id="searchInput" onchange="myFunction()"-->
                <div class="input-group-append">
                    <button class="btn btn-navbar" type="products">
                        <i class="fas fa-qrcode"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
                    <i class="fas fa-th-large"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->



    <script>
        $("input[type='search']").wrap("<form>");
        $("input[type='search']").closest("form").attr("autocomplete","off");
        $('input[type=search]').on('input', function(){
            clearTimeout(this.delay);
            this.delay = setTimeout(function(){
                console.log(this.value);
                var searchText = this.value;
                $.ajax({
                    method:'POST',
                    url: '../Logic/saveUser.php',
                    data: {act:'search', text:searchText},
                    success:function(data){
                        var obj = JSON.parse(data);

                        console.log(obj);
                        if(obj == false || obj == 'noExist'){

                            alert(obj);

                        }else {

                            var kind = obj[0];
                            var redirect_url = obj[1];
                            var room_id = obj[2];
                            var plant_id = obj[3];

                            if(kind == 'qr_code'){
                                $.redirect(redirect_url,
                                    {
                                        room:room_id,
                                        p: plant_id
                                    },
                                    'GET');
                            }
                            if(kind == 'UID'){
                                $.redirect(redirect_url,
                                    {
                                        room:room_id,
                                        p: plant_id
                                    },
                                    'GET');
                            }
                            if(kind == 'history'){
                                $.redirect(redirect_url,
                                    {
                                        kind:room_id,
                                        p: plant_id
                                    },
                                    'GET');
                            }
                        }

                    }

                })
            }.bind(this), 800);
        });

        //unneed
        function myFunction() {
            var searchText = $('#searchInput').val();

            $.ajax({
                method:'POST',
                url: '../Logic/saveUser.php',
                data: {act:'search', text:searchText},
                success:function(data){
                    var obj = JSON.parse(data);

                    console.log(obj);
                    if(obj == false || obj == 'noExist'){

                        alert(obj);

                    }else {

                        var kind = obj[0];
                        var redirect_url = obj[1];
                        var room_id = obj[2];
                        var plant_id = obj[3];

                        if(kind == 'qr_code'){
                            $.redirect(redirect_url,
                                {
                                    room:room_id,
                                    p: plant_id
                                },
                                'GET');
                        }
                        if(kind == 'UID'){
                            $.redirect(redirect_url,
                                {
                                    room:room_id,
                                    p: plant_id
                                },
                                'GET');
                        }
                    }

                }

            })


        }


        $(document).ready(function () {

        });



    </script>