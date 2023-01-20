<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    <!-- Add jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Add Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <title>TDA | Styleguide</title>
</head>
<body>
    <h1 class="w-100 text-center mt-5">TDA</h1>

    <div class="container">
        <h2>
            Buttons
        </h2>
        <div class="row">
            <div class="col-3">Standard</div>
            <div class="col-3">Hover/Fokus</div>
            <div class="col-3">Active</div>
            <div class="col-3">Disabled</div>
            <div class="col-3">
                <button class="btn">sell</button>
            </div>
            <div class="col-3">
                <button class="btn hover">sell</button>
            </div>
            <div class="col-3">
                <button class="btn active">sell</button>
            </div>
            <div class="col-3">
                <button class="btn disabled">sell</button>
            </div>
        </div>


        <div class="spacer my-5"></div>

        <h2>
            Inventory cards
        </h2>
        <div class="d-block">
            <div class="info-card mb-5">
                <div class="info-card__content">
                    <div class="row">
                        <div class="col-8 d-flex align-items-end">
                            <h3 class="m-0">Item</h3>
                        </div>
                        <div class="col-4">
                            <img class="info-card__image img-fluid" src="https://via.placeholder.com/50x50" alt="Item">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">Quantity: </div>
                        <div class="col-6 text-end">xx / xx</div>
                    </div>
                    <div class="row">
                        <div class="col-6">Price: </div>
                        <div class="col-6 text-end">6$</div>
                    </div>
                    <button class="btn w-100 mb-2">Sell</button>
                    <button class="btn w-100">Delete</button>
                </div>

            </div>

            <div class="info-card">
                <img class="info-card__image mb-3" src="https://via.placeholder.com/140x100" alt="Item">

                <div class="info-card__content">
                    <h3>Item</h3>

                    <div class="row">
                        <div class="col-6">Quantity: </div>
                        <div class="col-6 text-end">xx / xx</div>
                    </div>
                    <div class="row">
                        <div class="col-6">Price: </div>
                        <div class="col-6 text-end">6$</div>
                    </div>
                    <button class="btn w-100 mb-2">Sell</button>
                    <button class="btn w-100">Delete</button>
                </div>
            </div>
        </div>

    </div>    
</body>
</html>