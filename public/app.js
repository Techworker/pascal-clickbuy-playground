$(function() {

    var pascal = window.pascalcoin;
    var wallet = new pascal.Wallet('proxy.php');
    var accounts = [];
    var ownedBoxes = {};
    if(window.location.hash !== '') {
        $("#private-key-enc").val(window.location.hash.substr(1));
        $('#auth-modal').modal('show');
        $("#private-key-password").focus();
    }

    $('#private-key-auth').on('click', function(e) {
        wallet.authenticate($("#private-key-enc").val(), $("#private-key-password").val());
        if(!wallet.isAuthenticated()) {
            alert('Unable to authenticate with given credentials');
        } else {
            $("#auth-btn").hide();
            window.location.hash = '#' + $("#private-key-enc").val();
            wallet.getAccountsOfKey().then(function(accountList) {
                accounts = accountList;
                if(accountList.length === 0) {
                    alert('No accounts for this key.');
                } else {
                    $('#auth-modal').modal('hide');
                }

                for(var i = 0; i < accountList.length; i++) {
                    $('#buy-account').append($('<option>', {value:accountList[i].account.toString(), text: accountList[i].account.toString()}));
                }
                owned(true);
            });
        }
    });

    var inited = false;
    function grid() {
        $.get('/api/grid.php').then(function (data) {
            if(!inited) {
                createGrid(data.grid);
            }

            applyToGrid(data.grid);
            updateEvents(data.events);

            setTimeout(function() {
                grid();
            }, 1000);
        });
    }

    function updateEvents(events)
    {
        var html = '';
        for(var i = 0; i < events.length; i++) {
            html += ' - ' + events[i].msg + "\n";
        }
        $("#events").html(html);
    }

    function owned(restart) {
        console.log({accounts: accounts.map(function(a) { return a.account.account;})});
        $.get('/api/owned.php', {accounts: accounts.map(function(a) { return a.account.account;})}).then(function (data) {

            var html = '';
            ownedBoxes = {};
            for(var i = 0; i < data.length; i++) {
                var box = data[i];
                html += '<tr data-box="box-' + box.x + '-' + box.y + '">';
                html += '<td>' + box.x + ':' + box.y + '</td>';
                html += '<td>' + box.account + '</td>';
                html += '<td>' + box.price/10000 + ' PASC</td>';
                html += '</tr>';
                if(ownedBoxes[box.x.toString()] === undefined) {
                    ownedBoxes[box.x.toString()] = {};
                }
                ownedBoxes[box.x.toString()][box.y.toString()] = true;
            }
            $("#owned tbody").html(html);

            if(restart) {
                setTimeout(function () {
                    owned(true);
                }, 5000);
            }
        });
    }

    function createGrid(data) {
        var l = Math.sqrt(data.length);
        var html = '';
        for(x = 1; x <= l; x++) {
            html += '<tr>';
            for(y = 1; y <= l; y++) {
                html += '<td data-toggle="tooltip" data-placement="top" title="Box ' + x + ':' + y + '" data-x="' + x + '" data-y="' + y + '" id="box-' + x + '-' + y + '"></td>';
            }
            html += '</tr>';
        }
        $('#grid')[0].innerHTML = html;

        for(x = 1; x <= l; x++) {
            for(y = 1; y <= l; y++) {
                $("#box-" + x + '-' + y).on('click', function(e) {
                    if(!wallet.isAuthenticated()) {
                        $('#auth-modal').modal('show');
                        return;
                    }

                    var x = $(this).data('x');
                    var y = $(this).data('y');

                    if(ownedBoxes[x] !== undefined && ownedBoxes[x][y] === true) {
                        alert('You already own that box!');
                        return;
                    }
                    var color = $(this).data('color');
                    var owner = $(this).data('owner');
                    var price = $(this).data('price');
                    if(owner == 8) {
                        owner = 'System';
                    }
                    $("#buy-owner").html(owner);
                    $("#buy-color").val('#' + color);
                    $("#buy-x").html(x);
                    $("#buy-y").html(y);
                    $("#buy-price").html(price / 10000);
                    $('#buy-modal').modal('show');
                });
            }
        }

    }

    function applyToGrid(data) {
        for(var i = 0; i < data.length; i++) {
            var box = data[i];
            var $html = $("#box-" + box.x + '-' + box.y);
            $html.css("background-color", '#' + box.color);
            $html.attr('data-color', box.color);
            $html.attr('data-owner', box.account);
            $html.data('owner', box.account);
            $html.attr('data-price', box.price);
        }
    }
    grid();

    $("#buy-button").on('click', function(e) {
        var color = $("#buy-color").val();
        var account = $("#buy-account").val();
        var owner = $("#buy-owner").html();
        var x = $("#buy-x").html();
        var y = $("#buy-y").html();
        var price = $("#buy-price").html();

        var payload = JSON.stringify({
            x: x, y: y, color: color
        });
        let op = wallet.initiateSendTo(account, owner, price);
        op.withPayload(payload);
        wallet.sendTo(op, true).then(function(o) {
            if(o.valid === false) {
                alert('Error: ' + o.errors);
            } else {
                $('#buy-modal').modal('hide');
            }
            owned(false);
        }).catch(function(e) {
            alert('Something went wrong.');
            console.log(e);
        });
        return false;
    })

    $('[data-toggle="tooltip"]').tooltip();
});
