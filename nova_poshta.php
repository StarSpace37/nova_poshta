<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--<link rel="stylesheet" href="css/select.css">
	<link rel="stylesheet" href="css/style.css">-->
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
</head>
<body class="bg-white text-dark">
<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-dark text-white border-bottom shadow-sm">
    <h5 class="my-0 mr-md-auto font-weight-normal">Нова Пошта</h5>
    <nav class="my-2 my-md-0 mr-md-3">
        <a class="p-2 text-white" href="/">Главная</a>
        <a class="p-2 text-white" href="#">Про нас</a>
    </nav>
    <a class="btn btn-warning" href="#">Отзывы</a>
</div>
<div class="container mt-5">
    <main role="main">

        <!-- Main jumbotron for a primary marketing message or call to action -->
        <div class="jumbotron bg-warning">
            <div class="container">
                <form action="#" id="form" class="form__body">
                    <h1 class="form__title">Нова Пошта</h1>

                    <!--<form method="post" action="/welcome/check">
                    @csrf
                    @if($errors->any())
                    <div class="alert alert-damger">
                    <ul>
                    @foreach($error->all() as $error)
                    <li>{{$error}}</li>
                    @endforeach
                    </ul>
                    </div>
                    @endif
                    -->
                    <div class="form__item">
                        <input type="text" name="firstname" id="firstname" placeholder="Имя (получателя)" class="form-control"><br>
                        <input type="text" name="lastname" id="lastname" placeholder="Фамилия (получателя)" class="form-control"><br>
                        <input type="email" name="email" id="email" placeholder="Электронная почта (получателя)" class="form-control"><br>
                        <input type="text" name="phone" id="phone" data-mask="+38 (999) 99-99-999" class="form-control" value="" placeholder="Телефон (получателя)" data-reload-payment-form="true" ><br>
                        <input type="text" list="areas" name="region" id="region" placeholder="Область(получателя)" class="form-control"><br>
                        <input type="text" list="cities" name="city"  id="city" placeholder="Город(получателя)" class="form-control"><br>
                        <input type="text" list="warehouses" name="office" id="office"  placeholder="Отделение «Нова Пошта»" class="form-control"><br>
                        <button type="submit" class="btn btn-success">Отправить</button>
                </form>
                <!--<div class="ms-box">
                    <div class="ms-box-t">Адрес доставки «Нова Пошта»</div>
                        <div class="field-for-group">
                            <div class="input-label-group">
                                <label class="label-common required">Область</label>
                                <input class="form-control" data-autocomplete="np_region" type="text" name="region" id="customer_drop_lastname" value="" placeholder="" data-reload-payment-form="true">
                                                        <div class="autocomplete active">
                                        <div class="autocomplete-list"></div>
                                    </div>
                            </div>
                            <div class="input-label-group">
                                <label class="label-common required">Город</label>
                                <input class="form-control" data-autocomplete="np_city" data-min="3" type="text" name="city" id="shipping_address_city" value="" placeholder="" data-onchange="reloadAll" autocomplete="off">
                                                    <div class="autocomplete active">
                                    <div class="autocomplete-list"></div>
                                </div>
                            </div>
                            <div class="input-label-group">
                                <label class="label-common required">Отделение «Нова Пошта»</label>
                                <input class="form-control" type="text" name="office" id="shipping_address_address_1" value="" placeholder="" data-onchange="reloadAll" data-autocomplete="np_office">
                                                <div class="autocomplete active">
                                <div class="autocomplete-list"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->
                </form>
            </div>
        </div>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "nova_poshta";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $areas = '<datalist id="areas">';
        $sql = "SELECT DescriptionRu from Areas";
        $result = mysqli_query($conn, $sql);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Iterate all items in array
        foreach ($rows as $row){
            $areas = $areas . "<option value='{$row['DescriptionRu']}'>\n";
        }
        $areas = $areas . "</datalist>";
        echo $areas;

        // Close mysql connection
        mysqli_close($conn);
        ?>
        <script type="text/javascript">
            $('#region').change(function() {
                $.ajax({
                    type: "GET",
                    url: 'db_get_cities.php',
                    data: {'area_name': $(this).val()},
                    success: function(response)
                    {
                        console.log('response');

                        var jsonData = JSON.parse(response);
                        var responseArr = Object.values(jsonData);

                        $('#cities').html('');
                        $('#warehouses').html('');

                        $('#city').val('');
                        $('#office').val('');

                        $('#office').prop("disabled", true );
                        if (responseArr.length > 0) {
                            $('#city').prop("disabled", false );
                            responseArr.forEach(element =>$('#cities').append('<option value=\'' + element + '\'>\\n'));
                        } else {
                            $('#city').prop("disabled", true );
                        }
                    }
                });
            });

            $('#city').change(function() {
                $.ajax({
                    type: "GET",
                    url: 'db_get_warehouses.php',
                    data: {'city_name': $(this).val()},
                    success: function(response)
                    {
                        var jsonData = JSON.parse(response);
                        var responseArr = Object.values(jsonData);

                        $('#warehouses').html('');

                        $('#office').val('');
                        if (responseArr.length > 0) {
                            $('#office').prop("disabled", false );
                            responseArr.forEach(element =>$('#warehouses').append('<option value=\'' + element + '\'>\\n'));
                        } else {
                            $('#office').prop("disabled", true );
                        }
                    }
                });
            });
        </script>
        <datalist id="cities"></datalist>
        <datalist id="warehouses"></datalist>
    </main>

</div>
</body>
</html>
