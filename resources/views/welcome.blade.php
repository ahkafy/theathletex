<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hello, Bootstrap + Laravel + Vite!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/js/app.js'])

</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Welcome to Laravel with Bootstrap and Vite') }}</div>

                    <div class="card-body">
                        <h1>Hello, World!</h1>
                        <p>This is a simple Laravel application using Bootstrap for styling and Vite for asset management.</p>
                        <p>Enjoy building your application!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module" src="{{ mix('js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Hello, Bootstrap + Laravel + Vite!');
        });
    </script>
    <footer class="text-center mt-5">
        <p>&copy; {{ date('Y') }} Your Company. All rights reserved.</p>
    </footer>
</div>
</body>
</html>
