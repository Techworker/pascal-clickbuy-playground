<?php

require_once __DIR__ . '/../bootstrap.php';

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>ClickBuy with PascalCoin.org</title>
    <style>
        table td {
            height: 20px;
            width: 20px;
        }
    </style>
</head>
<body>
<div class="container">
<h1>ClickBuy with PascalCoin</h1>

    <div class="row">
        <div class="col-md-4">
            <table id="grid" border="1">
            </table>
        </div>
        <div class="col-md-8">
            Boxes you own
            <table class="table" id="owned">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">First</th>
                    <th scope="col">Last</th>
                    <th scope="col">Handle</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td>Larry</td>
                    <td>the Bird</td>
                    <td>@twitter</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div id="owned">

</div>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#auth-modal">
    Authenticate
</button>

</div>
<div class="modal fade" id="auth-modal" tabindex="-1" role="dialog" aria-labelledby="auth" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Authenticate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="private-key-enc" class="col-form-label">Encrypted Private key:</label>
                        <textarea class="form-control" id="private-key-enc">53616C7465645F5F0BEE27AC4D30834BFCF09570AD40C3BA9F8DC45310A996E78D7B15566AE7D2BD2EA108681FE6A3C5F6A619364C7BC97FB42BCF0A5D60BC92</textarea>
                    </div>
                    <div class="form-group">
                        <label for="private-key-password" class="col-form-label">Password:</label>
                        <input type="password" class="form-control" id="private-key-password" value="test123">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="private-key-auth">Authenticate</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="buy-modal" tabindex="-1" role="dialog" aria-labelledby="buy" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Buy Box</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <p>Box <code><span id="buy-x"></span>:<span id="buy-y"></span></code> owned by <code><span id="buy-owner"></span></code> for <code id="buy-price"></code> PASC</p>
                    <div class="form-group">
                        <label for="buy-account" class="col-form-label">Account</label>
                        <select id="buy-account">

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="private-key-password" class="col-form-label">Color:</label>
                        <input type="color" class="form-control" id="buy-color">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="buy-button">Buy Box</button>
            </div>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="pascalcoin.js"></script>
<script src="app.js"></script>
</body>
</html>