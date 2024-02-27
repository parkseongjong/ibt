// 21.01.27, YMJ
$(function () {
    $("#rwwd_wallet_addr").on('input', function () {
        ctcWalletgetUser();
    });
});

function ctcWalletgetUser() {
    let walletaddr = $("#rwwd_wallet_addr").val();
    if (walletaddr !== '') {
        $("#ajax_coin_tr").show();
        if (getSelectedMethod() === 'trading') {
            $.ajax({
                url: "/front2/assets/ctcwalletgetallusersajax/" + walletaddr,
                dataType: 'JSON',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                success: function (resp) {
                    if (resp.success === "true") {
                        let name = resp.name;
                        $("#rwwd_wallet_name").val(name);
                        $("#rwwd_get_resp").hide();
                    } else {
                        $("#rwwd_wallet_name").val('');
                        $("#rwwd_get_resp").removeClass("alert-success alert-danger").addClass("alert-danger").html(__('Member information not found')).show();
                    }
                },
                error: function (e) {
                    $("#rwwd_wallet_name").val('');
                    $("#ajax_coin_tr").hide();
                }
            });
        } else {
            $.ajax({
                url: "/front2/assets/ctcwalletgetuserajax/" + walletaddr,
                dataType: 'JSON',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                success: function (resp) {
                    if (resp.success === "true") {
                        let name = resp.name;
                        $("#rwwd_wallet_name").val(name);
                        $("#rwwd_get_resp").hide();
                    } else {
                        $("#rwwd_wallet_name").val('');
                        $("#rwwd_get_resp").removeClass("alert-success alert-danger").addClass("alert-danger").html(__('Member information not found')).show();
                    }
                },
                error: function (e) {
                    $("#rwwd_wallet_name").val('');
                    $("#ajax_coin_tr").hide();
                }
            });
        }
    }
}

var getMyCoinId = "";
var withdrawFee = 0;

let datatable_language = '';
if(getlang() === 'kr'){
    datatable_language = 'Korean';
} else {
    datatable_language = 'English';
}

function getInternalTransaction() {
    $("#ajax_coin_tr").show();
    $.ajax({
        url: "/front2/assets/internaltransactionajax/" + getMyCoinId,
        dataType: 'JSON',
        beforeSend: function(xhr){
            xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
        },
        success: function (resp) {
            let respVal = resp.data.coinlist;
            let html = '';
            let rightArrowClick = "transferAmount('" + respVal.coinShortName + "','trading')";
            let leftArrowClick = "transferAmount('" + respVal.coinShortName + "','main')";
            html = html + '<tr>';
            html = html + '<td><strong>' + respVal.coinShortName + '</strong> ' + respVal.coinName + '</td>';
            html = html + '<td>' + respVal.principalBalance + '</td>';
            html = html + '<td><span style="cursor:pointer;" class="fa fa-arrow-right" onClick="' + rightArrowClick +
                '"></span><br/><span style="cursor:pointer;" class="fa fa-arrow-left"  onClick="' + leftArrowClick + '"></span></td>';
            html = html + '<td>' + respVal.tradingBalance + '</td>';
            html = html + '<td>' + respVal.reserveBalance + '</td>';
            html = html + '</tr>';
            $('#internal_withdrawlist').html(html);
        },
        error: function (e) {
            $("#ajax_coin_tr").hide();
        }
    });
}

function tradingBalanceTotal() {
    $("#ajax_coin_tr").show();
    $.ajax({
        url: "/front2/assets/tradingBalanceTotal",
        beforeSend: function(xhr){
            xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
        },
        dataType: 'JSON',
        success: function (resp) {
            let total_val = resp.data.total.total_value;
            let reserveTotalBalance = resp.data.total.reserveTotalBalance;
            let tradingTotalBalance = resp.data.total.tradingTotalBalance;
            $("#total_balance_val").html(total_val);
            $("#resserve_total_balance").html(reserveTotalBalance);
            $("#trading_total_balance").html(tradingTotalBalance);
        },
        error: function (e) {
            $("#ajax_coin_tr").hide();
        }
    });
}
//조정
function mainBalanceTotal() {
    $("#ajax_coin_tr").show();
    $.ajax({
        url: "/front2/assets/mainBalanceTotal/",
        beforeSend: function(xhr){
            xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
        },
        dataType: 'JSON',
        success: function (resp) {
            let total_val = resp.data.total.total_value;
            let reserveTotalBalance = resp.data.total.reserveTotalBalance;
            let mainTotalBalance = resp.data.total.mainTotalBalance;
            $("#total_balance_val").html(total_val);
            $("#resserve_total_balance").html(reserveTotalBalance);
            $("#main_total_balance").html(mainTotalBalance);
        },
        error: function (e) {
            $("#ajax_coin_tr").hide();
        }
    });
}


function currentCoinBalance() {
    $("#ajax_coin_tr").show();
    if (getMyCoinId === '' || getMyCoinId === null) {
        getMyCoinId = 20;
    }
    $.ajax({
        url: "/front2/assets/selectedCoinAmountAjax",// + getMyCoinId,
        type: 'post',
        dataType: 'JSON',
        data: {
            coin_id: getMyCoinId,
            types: getSelectedMethod()
        },
        beforeSend: function(xhr){
            xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
        },
        success: function (resp) {
            let currentCoinTotalVal = resp.data.current_coin.currentCoinTotalVal;
            let principalBalance = resp.data.current_coin.principalBalance;
            let reserveBalance = resp.data.current_coin.reserveBalance;
            let tradingBalance = resp.data.current_coin.tradingBalance;

            if ($('#btn_trading_withdraw').hasClass('selected')) {
                $("#retained_quantity_" + getMyCoinId).html(tradingBalance).closest('tr').addClass("on").parent().parent().addClass("on");
                $("#amount_data").html(tradingBalance);
                $("#krw_amount_data").html(tradingBalance);
                $("#trading_total_balance").html(tradingBalance);
                displayWalletAddress('trading', getCoinsName(getMyCoinId));
            }
            if ($('#btn_main_withdraw').hasClass('selected')) {
                $("#retained_quantity_" + getMyCoinId).html(principalBalance).closest('tr').addClass("on").parent().parent().addClass("on");
                $("#amount_data").html(principalBalance);
                $("#krw_amount_data").html(principalBalance);
                $("#main_total_balance").html(principalBalance);
                displayWalletAddress('main', getCoinsName(getMyCoinId));
            }
            $("#krw_quantity_" + getMyCoinId).html(currentCoinTotalVal);
            $("#resserve_total_balance").html(reserveBalance);
        },
        error: function (e) {
            $("#ajax_coin_tr").hide();
        }
    });
}

function disableElements(){
    $("#amount_deposited").prop('disabled', true).attr('disabled', 'disabled').css({'pointer-events': 'none'});
    $("#bank_deposit_btn").prop('disabled', true).attr('disabled', 'disabled').css({'pointer-events': 'none'});
    $("#req_amount_krw").prop('disabled', true).attr('disabled', 'disabled').css({'pointer-events': 'none'});
    /*$("#req_amount").prop('disabled', true).attr('disabled', 'disabled').css({'pointer-events': 'none'});*/
    $("#password").prop('disabled', true).attr('disabled', 'disabled').css({'pointer-events': 'none'});
    //$("#pass").prop('disabled', true).attr('disabled', 'disabled').css({'pointer-events': 'none'});
    //$("#otp_number").prop('disabled', true).attr('disabled', 'disabled').css({'pointer-events': 'none'});
    //$("#otp_number_krw").prop('disabled', true).attr('disabled', 'disabled').css({'pointer-events': 'none'});
    //$("#withdraw_btn_pw").prop('disabled', true).attr('disabled', 'disabled').css({'pointer-events': 'none'});
    //$("#withdrawrequestdata_pw").prop('disabled', true).attr('disabled', 'disabled').css({'pointer-events': 'none'});
}

function enableElements(){
    $("#req_amount").prop('enabled', true).css({'pointer-events': 'auto', 'cursor': 'pointer'}).removeAttr('disabled');
    //$("#otp_number").prop('enabled', true).css({'pointer-events': 'auto', 'cursor': 'pointer'}).removeAttr('disabled');
    $("#password").prop('enabled', true).css({'pointer-events': 'auto', 'cursor': 'pointer'}).removeAttr('disabled');
    //$("#pass").prop('enabled', true).css({'pointer-events': 'auto', 'cursor': 'pointer'}).removeAttr('disabled');
    //$("#withdrawrequestdata_pw").prop('enabled', true).css({'pointer-events': 'auto', 'cursor': 'pointer'}).removeAttr('disabled');
    //$("#req_amount_krw").prop('enabled', true).css({'pointer-events': 'auto', 'cursor': 'pointer'}).removeAttr('disabled');
    //$("#otp_number_krw").prop('enabled', true).css({'pointer-events': 'auto', 'cursor': 'pointer'}).removeAttr('disabled');
    //$("#withdraw_btn_pw").prop('enabled', true).css({'pointer-events': 'auto', 'cursor': 'pointer'}).removeAttr('disabled');
}

function setTrading() {
    $('#btn_trading_withdraw').addClass('selected');
    $('#btn_main_withdraw').removeClass('selected');
    $('#div_main').css({'display': 'none', 'visibility': 'hidden'});
    $('#div_trading').css({'display': 'inline-block', 'visibility': 'visible'});
    setValues('trading');
    currentCoinBalance();
    displayWalletAddress('trading', getCoinsName(getMyCoinId));
}

function setMain() {
    $('#btn_main_withdraw').addClass('selected');
    $('#btn_trading_withdraw').removeClass('selected');
    $('#div_main').css({'display': 'inline-block', 'visibility': 'visible'});
    $('#div_trading').css({'display': 'none', 'visibility': 'hidden'});
    setValues('main');
    currentCoinBalance();
    displayWalletAddress('main', getCoinsName(getMyCoinId));
}


$(document).ready(function () {
    if (performance.navigation.type === performance.navigation.TYPE_RELOAD || performance.navigation.type === performance.navigation.TYPE_NAVIGATE || performance.navigation.type === performance.navigation.TYPE_BACK_FORWARD) {
        localStorage.removeItem('coinId');
    }
    let pendingVal = $("#pendingVal").val();
    let pendingValw = $("#pendingValw").val();
    let bankAuth = $("#bankAuth").val();
    let emailAuth = $("#emailAuth").val();
    let otpAuth = $("#otpAuth").val();
    let deposit = $("#deposit").val();
    let password = document.getElementById("password");
    let pass = document.getElementById("pass");
    let otpKRW = document.getElementById("otp_number_krw");
    let otp = document.getElementById("otp_number");
    setTrading();

    $('#btn_main_withdraw').click(function () {
        setMain();
    });

    $('#btn_trading_withdraw').click(function () {
        setTrading();
    });

    password.addEventListener("keyup", function (event) {
        // Number 13 is the "Enter" key on the keyboard
        if (event.keyCode === 13) {
            // Cancel the default action, if needed
            event.preventDefault();
            // Trigger the button element with a click
            document.getElementById("withdraw_btn_pw").click();
        }
    });

    otpKRW.addEventListener("keyup", function (event) {
        // Number 13 is the "Enter" key on the keyboard
        if (event.keyCode === 13) {
            // Cancel the default action, if needed
            event.preventDefault();
            // Trigger the button element with a click
            document.getElementById("withdraw_btn").click();
        }
    });

    pass.addEventListener("keyup", function (event) {
        // Number 13 is the "Enter" key on the keyboard
        if (event.keyCode === 13) {
            // Cancel the default action, if needed
            event.preventDefault();
            // Trigger the button element with a click
            document.getElementById("withdrawrequestdata_pw").click();
        }
    });

    otp.addEventListener("keyup", function (event) {
        // Number 13 is the "Enter" key on the keyboard
        if (event.keyCode === 13) {
            // Cancel the default action, if needed
            event.preventDefault();
            // Trigger the button element with a click
            document.getElementById("withdrawrequestdata").click();
        }
    });

    if (deposit === "Y") {
        disableElements()
        $("#ifDeposit").show();
        $("#ifDepositW").show();
        $("#ifLess").hide();
        $("#ifLessW").hide();
    } else {
        $("#ifLess").hide();
        $("#ifLessW").hide();
        $("#ifDeposit").hide();
        $("#ifDepositW").hide();
        if (bankAuth === "Y" && emailAuth === "Y" && otpAuth === "Y" && pendingVal === "N" && pendingValw === "N") {
            enableElements();
        } else {
            disableElements();
            $("#notauthw").show();
            $("#notauth").show();
            $("#ifNotAuth").show();
            $("#ifNotAuthW").show();
            $("#ifpendingD").hide();
            $("#ifpendingW").hide();
            $("#ifLess").hide();
            $("#ifLessW").hide();
            $("#ifDeposit").hide();
            $("#ifDepositW").hide();
        }
    }

    if (bankAuth === "N" || emailAuth === "N" || otpAuth === "N") {
        disableElements();
        $("#notauthw").show();
        $("#notauth").show();
        $("#ifNotAuth").show();
        $("#ifNotAuthW").show();
        $("#ifpendingD").hide();
        $("#ifpendingW").hide();
        $("#ifLess").hide();
        $("#ifLessW").hide();
        $("#ifDeposit").hide();
        $("#ifDepositW").hide();
    } else {
        if (pendingVal === "Y" || pendingValw === "Y") {
            disableElements();
            if (pendingVal === "Y") {
                $("#ifDepositPendingW").show();
                $("#ifDepositPending").show();
                $("#ifpendingD").show();
                $("#ifWithdrawPendingW").hide();
                $("#ifWithdrawPending").hide();
                $("#ifpendingW").hide();
            }
            if (pendingValw === "Y") {
                $("#ifWithdrawPendingW").show();
                $("#ifWithdrawPending").show();
                $("#ifpendingW").show();
                $("#ifDepositPendingW").hide();
                $("#ifDepositPending").hide();
                $("#ifpendingD").hide();
            }
            $("#notauthw").hide();
            $("#notauth").hide();
            $("#ifNotAuth").hide();
            $("#ifNotAuthW").hide();
        } else {
            enableElements();
            if (pendingVal === "N") {
                $("#ifDepositPendingW").hide();
                $("#ifDepositPending").hide();
                $("#ifpendingD").hide();
                $("#ifDeposit").hide();
            }
            if (pendingValw === "N") {
                $("#ifWithdrawPending").hide();
                $("#ifWithdrawPendingW").hide();
                $("#ifpendingW").hide();
                $("#ifDepositW").hide();
            }
            $("#notauthw").hide();
            $("#notauth").hide();
            $("#ifNotAuth").hide();
            $("#ifNotAuthW").hide();
        }
    }

    $("#depositkrw_on").addClass("deposit on");
    localStorage.removeItem("value");
    // mainAndTradingBalanceTotal();
    if(getSelectedMethod() === 'trading') {
        tradingBalanceTotal();
    } else {
        mainBalanceTotal();
    }


    let price = $(".totalkrw").val();
    let principalBalanceTotal = $(".principalBalanceTotal").val();
    let reserveBalance = $(".reserveBalance").val();
    let tradingBalance = $(".tradingBalance").val();
    $(".main_Balance11").html(principalBalanceTotal);
    $(".TradingAccount").html(tradingBalance);
    $(".TradingAccounts").html(reserveBalance);
    $(".pricetotal1223").html(price);
    $("#table_withdrowl2").hide();
    $("#rwwd_modal_form").submit(function () {
        $("#rwwd_model_ajax").show();
        $.ajax({
            url: '/front2/assets/registerWithdrawalWalletAddrAjax',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'JSON',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            success: function (resp) {
                if (resp.success === "false") {
                    $("#rwwd_get_resp").removeClass("alert-success alert-danger").addClass("alert-danger").html(resp.message).show();
                } else if (resp.success === "true") {
                    $("#rwwd_get_resp").removeClass("alert-success alert-danger").addClass("alert-success").html(resp.message).show();
                    $("#rwwd_modal_form").trigger("reset");
                }
                setTimeout(function () {
                    $("#rwwd_get_resp").html("").hide();
                }, 6000);
                $("#rwwd_model_ajax").hide();
            }
        });
    });
    // tab_menu hide
    // if clicked coin list item then tab_menu show, find $(".tab_menu").show();
    $('#tab_menu_coins').hide();
    // add 'on' class to KRW (in mycoinlist)
    const setValue = $('.setvalue');
    for (let i = 0; i < setValue.length; i++) {
        const item = setValue[i];
        if (item.innerText.trim() === "KRW") {
            // const itemParent = $(item).parent().parent();
            // itemParent.addClass('on');

            item.click();
        }
    }

    $("#withdrawal_type_in").click(function () {
        getInternalTransaction();
        $("#table_withdrowl1").hide();
        $("#table_withdrowl2").show();
    });

    $("#withdrawal_type_out").click(function () {
        let amount_data = localStorage.getItem("amount_data");
        let unit_data = localStorage.getItem("unit_data");
        localStorage.setItem("tradingBalanceid", 0);
        localStorage.setItem("amount_dataid", 1);
        $("#table_withdrowl1").show();
        $("#table_withdrowl2").hide();
        $("#amount_data").html(amount_data);
        $("#unit_data").html(unit_data);
        currentCoinBalance();
    });

    $('#req_amount').keyup(function (e) {
        let req_amount = $("#req_amount").val();
        let krwamount = $('#amount_data').text();
        let unit_data = $('#unit_data').text();
        let krw_values = $('#coin_id_' + unit_data).val();
        console.log(krw_values);
        let krw_value = krw_values.replace(/\,/g, '');

        let new_value;
        let fee_charges;
        if(unit_data === "ETH" || unit_data === "BTC"){
            new_value = parseFloat(req_amount).toFixed(6) * parseFloat(krw_value).toFixed(6);
            fee_charges = parseFloat(req_amount).toFixed(6) * withdrawFee / 100;
        } else {
            new_value = parseFloat(req_amount).toFixed(3) * parseFloat(krw_value).toFixed(3);
            fee_charges = parseFloat(req_amount).toFixed(4) * withdrawFee / 100;
        }

        let availAmount = krwamount.replace(/,/g, '');
        if(parseFloat(availAmount) < parseFloat(req_amount)){
            toastr.error(__('Please enter correct amount'));
            e.preventDefault();
            $(this).val('');
            $("#totalValuekrw").html("0");
            $("#amountkrw").html("0");
            $("#withdrawalfee").html("0");
            return false;
        }

        let reamainingBalance = parseFloat(req_amount) - fee_charges;

        if (req_amount === "") {
            $("#amountkrw").html("0");
            $("#withdrawalfee").html("0");
            $("#totalValuekrw").html("0");
        } else {
            if(unit_data === "ETH" || unit_data === "BTC") {
                $("#withdrawalfee").html(thousands_separators(removeZeros(parseFloat(fee_charges).toFixed(6))));
                $("#amountkrw").html(thousands_separators(removeZeros(parseFloat(new_value).toFixed(6))));
                $("#totalValuekrw").html(thousands_separators(removeZeros(reamainingBalance.toFixed(6))));
            } else {
                $("#withdrawalfee").html(thousands_separators(removeZeros(parseFloat(fee_charges).toFixed(4))));
                $("#amountkrw").html(numberWithCommas(removeZeros(parseFloat(new_value).toFixed(4))));
                $("#totalValuekrw").html(thousands_separators(removeZeros(reamainingBalance.toFixed(4))));
            }
        }

    });

    $('#req_amount_krw').keyup(function (e) {
        let req_amount = $("#req_amount_krw").val();
        let total_amount = $("#totalAmountkrw");
        let totalAmount = +req_amount + +1000;
        let totalDeposit_amount = $("#inoutprice").val();
        let totalDeposit = totalDeposit_amount
        let available_amount = document.getElementById("krw_amount_data").innerText;
        total_amount.html(numberWithCommas(totalAmount.toFixed(2)));
        var availAmount = available_amount.replace(/\,/g, '');
        if (totalAmount > totalDeposit) {
            toastr.error(__('Please enter correct amount'));
            e.preventDefault();
            $(this).val('');
            total_amount.html("0");
            return false;
        }
        if (req_amount === "") {
            total_amount.html("0");
        }
    });

    $('#req_amount_krw').change(function (e) {
        let req_amount = $("#req_amount_krw").val();
        let total_amount = $("#totalAmountkrw");
        let reqAmount = parseFloat(req_amount);
        let totalBuy = $("#totalBuy").val();
        let totalSell = $("#totalSell").val();
        let totalBuyAmount = parseFloat(totalBuy);
        let totalSellAmount = parseFloat(totalSell);
        let halfBuy = totalBuyAmount / 2;
        let halfSell = totalSellAmount / 2;
        if ((reqAmount < halfBuy || reqAmount < halfSell) && reqAmount < 50000) {
            toastr.error(__('Withdrawal conditions do not match'));
            e.preventDefault();
            $(this).val('');
            total_amount.html("0");
            return false;
        }
    });

    $("#btnSubmit").click(function () {
        let rwwd_wallet_name = $("#rwwd_wallet_name").val();
        let rwwd_wallet_addr = $("#rwwd_wallet_addr").val();
        if (rwwd_wallet_name === undefined || rwwd_wallet_name === null || rwwd_wallet_name === '') {
            toastr.error(__('Please enter the wallet name'));
            return false;
        }
        if (rwwd_wallet_addr === undefined || rwwd_wallet_addr === null || rwwd_wallet_addr === '') {
            toastr.error(__('Please enter the wallet address'));
            return false;
        }
        let coinName = $("#unit_data").text();
        if (coinName === undefined || coinName === null || coinName === '') {
            toastr.error(__('Please enter the coin'));
            return false;
        }
        if (coinName.toLowerCase() !== "btc" && validateInputAddresses(rwwd_wallet_addr) === false) {
            toastr.error(__('Please enter the valid wallet address'));
            return false;
        }

        let types = getSelectedMethod();
        $("#btnSubmit").prop("disabled", true)
        $.ajax({
            url: '/front2/assets/insertWalletAddress',
            type: 'post',
            data: {
                rwwd_wallet_name: rwwd_wallet_name,
                rwwd_wallet_addr: rwwd_wallet_addr,
                coinName: coinName,
                trans_type: types
            },
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            success: function (resp) {
                var result = JSON.parse(resp);
                if (result.success === "true") {
                    toastr.success(__('Success!'));
                    $("#rwwd_wallet_name").val('');
                    $("#rwwd_wallet_addr").val('');
                    displayWalletAddress(types, coinName);

                    $("#btnSubmit").prop("disabled", false);
                    $('#myModalAddWithdrawalWalletAddr').modal('hide');
                    return false;
                } else {
                    toastr.error(result.message);
                    $("#btnSubmit").prop("disabled", false);
                    //$('#myModalAddWithdrawalWalletAddr').modal('hide');
                    return false;
                }
            }
        });
        return false;
    });

    let mainBal = $("#main").val();
    let mainAccount = parseFloat(mainBal);
    $(".rwwd_modal_form1").submit(function () {
        let id = $(".rwwd_modal_form1").serialize();
        if (id === undefined || id === null || id === '') {
            toastr.error(__('Please select an address to delete'));
        } else {
            $.confirm({
                title: __('Confirm!'),
                content: __('Are you sure that you want to delete it?'),
                buttons: {
                    confirm: function () {
                        $.ajax({
                            url: '/front2/assets/deleteWalletAddress',
                            type: 'post',
                            data: {
                                id: id
                            },
                            beforeSend: function(xhr){
                                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                            },
                            success: function (resp) {
                                var resp = JSON.parse(resp);
                                toastr.success(__('Success!'));
                                $.each(resp.data, function (key, value) {
                                    $("#td_data_" + value).remove();
                                })
                            }
                        })
                    },
                    cancel: function () {
                    },
                }
            });
        }
    });

    $("#withdrawrequestdata").on('click', function () {
        let unitdata = $("#unit_data").text();
        let req_amount = $("#req_amount").val();
        let wallet_address = $("#wallet_address").val();
        let amountCoin = $("#amount_data").text();
        let coinAmount = amountCoin.replace(/\,/g, '');
        let otp_number = $("#otp_number").val();
        let krwAmount = $("#amountkrw").text();
        let krw = parseFloat(krwAmount);
        let totBlnc = $("#total_balance_val").text();
        let totBalance = totBlnc.replace(/\,/g, '');
        let totalBalance = parseFloat(totBalance);
        let totalValue = $('#totalValuekrw').text();
        let amountTotal = parseFloat(totalValue);
        let availableAmountCoin = parseFloat(coinAmount);
        let reqAmount = parseFloat(req_amount);

        if (availableAmountCoin < amountTotal) {
            toastr.error(__('Please enter a valid withdrawal amount'));
            return false;
        }

        if (unitdata === undefined || unitdata === null || unitdata === '') {
            toastr.error(__('Please select a coin'));
            return false;
        }
        if (reqAmount === undefined || reqAmount === null) {
            toastr.error(__('Please enter the amount you want to withdraw'));
            return false;
        }
        if (wallet_address === undefined || wallet_address === null || wallet_address === '') {
            toastr.error(__('Please enter the wallet address'));
            return false;
        }
        if (+reqAmount > +availableAmountCoin) {
            toastr.error(__('Please enter the valid withdrawal amount'));
            return false;
        }
        //OTP 제거 작업 진행
        /*if (otp_number === undefined || otp_number === null || otp_number === '') {
            toastr.error(__('Please enter the OTP'));
            return false;
        }*/
        let tradingBalanceid = localStorage.getItem("tradingBalanceid");
        let amount_dataid = localStorage.getItem("amount_dataid");
        let value = "external";
        if (tradingBalanceid !== undefined && tradingBalanceid !== null && tradingBalanceid !== '') {
            if (tradingBalanceid === "1") {
                value = "internal"
            }
        }
        if (amount_dataid !== undefined && amount_dataid !== null && amount_dataid !== '') {
            if (amount_dataid === "1") {
                if (tradingBalanceid === "1") {
                    value = "external"
                }
            }
        }

        if (getSelectedMethod() === 'main') {
            $.ajax({
                url: '/front2/assets/rquestWithdrawWalletAddress',
                type: 'post',
                data: {
                    total_amount: amountTotal,
                    wallet_address: wallet_address,
                    req_amount: req_amount,
                    coinName: unitdata,
                    otp_number: otp_number,
                    value: value
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                    $('#ajax_loader_for_w').show();
                    $("#withdrawrequestdata").prop('disabled', true).attr('disabled', 'disabled').css({'pointer-events': 'none'});
                    $("#otp_number").prop('disabled', true).attr('disabled', 'disabled').css({'pointer-events': 'none'});
                },
                complete: function () {
                    $("#otp_number").val('');
                    $("#pass").val("");
                },
                success: function (resp) {
                    $('#withdrawrequestdata').show();
                    $('#ajax_loader_for_w').hide();
                    $("#otp_number").val('');
                    $("#pass").val("");

                    var resp = JSON.parse(resp);

                    toastr.success(__('Success!'));
                    let remAmount = resp.data.mylist.mainBalance; //코인 수량 제어 함수 
                    let price = resp.data.mylist.currentPrice;
                    let finalAmount = numberWithCommas(parseFloat(remAmount).toFixed(2));
                    let prices = parseFloat(remAmount) * parseFloat(price);
                    $("#req_amount").val("");
                    $("#wallet_address").val('');
                    $("#otp_number").val("");
                    $("#amountkrw").text('');
                    $("#withdrawalfee").text('');
                    $("#totalValuekrw").text('');
                    $("#pass").val("");
                    $("span#amount_data").text("" + finalAmount);
                    $("span#main_total_balance").text("" + finalAmount);
                    $("#withdrawrequestdata").prop('enabled', true).css({'pointer-events': 'auto', 'cursor': 'pointer'}).removeAttr('disabled');
                    $("#otp_number").prop('enabled', true).css({'pointer-events': 'auto', 'cursor': 'pointer'}).removeAttr('disabled');
                    $("span#retained_quantity_" + getMyCoinId).text("" + finalAmount);
                    $("span#krw_quantity_" + getMyCoinId).text("" + numberWithCommas(prices.toFixed(2)));
                    let totalBal = totalBalance - krw;
                    let totBal = numberWithCommas(totalBal.toFixed(2));
                    $("#total_balance_val").html("" + totBal);
                    $("#myModalOTPW").modal('hide');

                 /*   if (resp.success === "false") {
                        toastr.error(resp.message);
                        $("#withdrawrequestdata").prop('enabled', true).css({'pointer-events': 'auto', 'cursor': 'pointer'}).removeAttr('disabled');
                        $("#otp_number").val('');
                        $("#pass").val("");

                        $('#ajax_loader_for_w').hide();
                        return false;
                    } else {
                        toastr.success(__('Success!'));
                        let remAmount = resp.data.mylist.mainBalance;
                        let price = resp.data.mylist.currentPrice;
                        let finalAmount = numberWithCommas(parseFloat(remAmount).toFixed(2));
                        let prices = parseFloat(remAmount) * parseFloat(price);
                        $("#req_amount").val("");
                        $("#wallet_address").val('');
                        $("#otp_number").val("");
                        $("#amountkrw").text('');
                        $("#withdrawalfee").text('');
                        $("#totalValuekrw").text('');
                        $("#pass").val("");
                        $("span#amount_data").text("" + finalAmount);
                        $("span#main_total_balance").text("" + finalAmount);
                        $("#withdrawrequestdata").prop('enabled', true).css({'pointer-events': 'auto', 'cursor': 'pointer'}).removeAttr('disabled');
                        $("#otp_number").prop('enabled', true).css({'pointer-events': 'auto', 'cursor': 'pointer'}).removeAttr('disabled');
                        $("span#retained_quantity_" + getMyCoinId).text("" + finalAmount);
                        $("span#krw_quantity_" + getMyCoinId).text("" + numberWithCommas(prices.toFixed(2)));
                        let totalBal = totalBalance - krw;
                        let totBal = numberWithCommas(totalBal.toFixed(2));
                        $("#total_balance_val").html("" + totBal);
                        $("#myModalOTPW").modal('hide');
                    }*/
                }
            });
        } else {
            $.ajax({
                url: '/front2/assets/rquestWithdrawWalletAddressTrading',
                type: 'post',
                data: {
                    total_amount: amountTotal,
                    wallet_address: wallet_address,
                    req_amount: req_amount,
                    coinName: unitdata,
                    otp_number: otp_number,
                    value: value
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                    $('#ajax_loader_for_w').show();
                    $("#withdrawrequestdata").prop('disabled', true).attr('disabled', 'disabled').css({'pointer-events': 'none'});
                    $("#otp_number").prop('disabled', true).attr('disabled', 'disabled').css({'pointer-events': 'none'});

                },
                complete: function () {
                    // $("#withdrawrequestdata").prop('disabled',true);
                    $("#otp_number").val('');
                    $("#pass").val("");
                },
                success: function (resp) {
                    var resp = JSON.parse(resp);


                    $('#ajax_loader_for_w').hide();
                    $("#withdrawrequestdata").prop('enabled', true).css({'pointer-events': 'auto', 'cursor': 'pointer'}).removeAttr('disabled');
                    $("#otp_number").prop('enabled', true).css({'pointer-events': 'auto', 'cursor': 'pointer'}).removeAttr('disabled');
                    toastr.success(__('Success!'));
                    let remAmount = resp.data.mylist.mainBalance;
                    let price = resp.data.mylist.currentPrice;
                    let finalAmount = numberWithCommas(parseFloat(remAmount).toFixed(2));
                    let prices = parseFloat(remAmount) * parseFloat(price);
                    $("#req_amount").val("");
                    $("#wallet_address").val('');
                    $("#otp_number").val("");
                    $("#amountkrw").text('');
                    $("#withdrawalfee").text('');
                    $("#totalValuekrw").text('');
                    $("#pass").val("");
                    $("span#amount_data").text("" + finalAmount);
                    $("span#trading_total_balance").text(""+finalAmount);
                    $("span#retained_quantity_" + getMyCoinId).text("" + finalAmount);
                    $("span#krw_quantity_" + getMyCoinId).text("" + numberWithCommas(parseFloat(prices).toFixed(2)));
                    let totalBal = totalBalance - krw;
                    let totBal = numberWithCommas(parseFloat(totalBal).toFixed(2));
                    $("#total_balance_val").html("" + totBal);
                    $("#myModalOTPW").modal('hide');

                    /*if (resp.success === "false") {
                        toastr.error(resp.message);
                        $('#ajax_loader_for_w').hide();
                        $("#otp_number").val('');
                        $("#pass").val("");
                        $("#withdrawrequestdata").prop('enabled', true).css({'pointer-events': 'auto', 'cursor': 'pointer'}).removeAttr('disabled');
                        $("#otp_number").prop('enabled', true).css({'pointer-events': 'auto', 'cursor': 'pointer'}).removeAttr('disabled');
                        return false;
                    } else {
                        $('#ajax_loader_for_w').hide();
                        $("#withdrawrequestdata").prop('enabled', true).css({'pointer-events': 'auto', 'cursor': 'pointer'}).removeAttr('disabled');
                        $("#otp_number").prop('enabled', true).css({'pointer-events': 'auto', 'cursor': 'pointer'}).removeAttr('disabled');
                        toastr.success(__('Success!'));
                        let remAmount = resp.data.mylist.mainBalance;
                        let price = resp.data.mylist.currentPrice;
                        let finalAmount = numberWithCommas(parseFloat(remAmount).toFixed(2));
                        let prices = parseFloat(remAmount) * parseFloat(price);
                        $("#req_amount").val("");
                        $("#wallet_address").val('');
                        $("#otp_number").val("");
                        $("#amountkrw").text('');
                        $("#withdrawalfee").text('');
                        $("#totalValuekrw").text('');
                        $("#pass").val("");
                        $("span#amount_data").text("" + finalAmount);
                        $("span#trading_total_balance").text(""+finalAmount);
                        $("span#retained_quantity_" + getMyCoinId).text("" + finalAmount);
                        $("span#krw_quantity_" + getMyCoinId).text("" + numberWithCommas(parseFloat(prices).toFixed(2)));
                        let totalBal = totalBalance - krw;
                        let totBal = numberWithCommas(parseFloat(totalBal).toFixed(2));
                        $("#total_balance_val").html("" + totBal);
                        $("#myModalOTPW").modal('hide');
                    }*/
                }
            });
        }
    });

    $("#withdraw_btn").on('click', function () {
        let req_amount = $("#req_amount_krw").val();
        let amountkrw = document.getElementById("krw_amount_data").innerText;
        let total_amount = document.getElementById("totalAmountkrw").innerText;
        let otp_number = $("#otp_number_krw").val();
        let account = document.getElementById("bank_account").innerText;
        let bank = document.getElementById("bank_name").innerText;
        let totBlnc = document.getElementById("total_balance_val").innerText;
        let fees = 1000;
        let totBalance = totBlnc.replace(/\,/g, '');
        let totalBalance = parseFloat(totBalance);
        let reqAmount = parseFloat(req_amount);
        let amountTotal = amountkrw.replace(/\,/g, '');
        let totalamount = total_amount.replace(/\,/g, '');
        let availableAmount = parseFloat(amountTotal);
        let totalAmount = parseFloat(totalamount);
        let totalBuy = $("#totalBuy").val();
        let totalSell = $("#totalSell").val();
        let totalBuyAmount = parseFloat(totalBuy);
        let totalSellAmount = parseFloat(totalSell);
        let halfBuy = totalBuyAmount / 2;
        let halfSell = totalSellAmount / 2;

        if ((reqAmount < halfBuy || reqAmount < halfSell) && reqAmount < 50000) {
            toastr.error(__('Withdrawal conditions do not match'));
            return false;
        }

        if (req_amount === undefined || req_amount === null || req_amount === '') {
            toastr.error(__('Please enter the amount you want to withdraw'));
            return false;
        }

        if (availableAmount < 50000) {
            toastr.error(__('Insufficient balance'));
            return false;
        }

        if (reqAmount < 50000) {
            toastr.error(__('You need to withdraw at least 50,000 KRW'));
            return false;
        }

        if (availableAmount > amountTotal || totalAmount > amountTotal) {
            toastr.error(__('Please enter the valid withdrawal amount'));
            return false;
        }
        if (otp_number === undefined || otp_number === null || otp_number === '') {
            toastr.error(__('Please enter the OTP'));
            return false;
        }

        if (account === undefined || account === null || account === '') {
            toastr.error(__('Please verify your bank account'));
            return false;
        }
        if (bank === undefined || bank === null || bank === '') {
            toastr.error(__('Please verify your bank account'));
            return false;
        }
        $.ajax({
            url: '/front2/assets/krwWithdrawalMain',
            type: 'post',
            data: {
                total_amount: totalAmount,
                req_amount: reqAmount,
                fees: fees,
                otp_number: otp_number,
            },
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            success: function (resp) {
                var resp = JSON.parse(resp);
                if (resp.success === "false") {
                    toastr.error(resp.message);
                    return false;
                } else {
                    let remAmount = resp.data;
                    let finalAmount = numberWithCommas(parseFloat(remAmount).toFixed(2));
                    toastr.success(__('Success!'));
                    $("#req_amount_krw").val("");
                    $("#totalAmountkrw").html("");
                    $("#otp_number_krw").val("");
                    $("#password").val("");
                    localStorage.setItem("value", "withdrawListState");
                    $("#krwwithdrawal_tab_content").hide();
                    $("#statement_tab_content").show();
                    $("#withdraw_li_div").show();
                    $("#deposit_li_div").hide();
                    $("#default_content").hide();
                    myWithdrawListAjax();

                    $("#depositListStatement_on").removeClass("on");
                    tabChange("withdrawalListStatement_on");
                    $("#statement_on").addClass("deposit on");
                    $("span#krw_amount_data").text("" + finalAmount);
                    $("span#amount_data").text("" + finalAmount);
                    $("span#main_total_balance").text("" + finalAmount);
                    $("span#retained_quantity_20").text("" + finalAmount);
                    $("span#krw_quantity_20").text("" + finalAmount);
                    let totalBal = totalBalance - totalAmount;
                    let totBal = numberWithCommas(parseFloat(totalBal).toFixed(2));
                    $("#total_balance_val").html("" + totBal);
                    disableElements();
                    $("#ifWithdrawPending").show();
                    $("#ifpendingW").show();
                    $("#myModalOTP").modal('hide');
                }
            }
        });
    });

    $("#deposit_modal_form").submit(function (event) {
        //stop submit the form, we will post it manually.
        event.preventDefault();
        $("#btnSubmit").prop("disabled", true);
        $("#model_qr_code_flat").show();
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "/front2/wallet/transgetToAccountNew",
            data: $("#deposit_modal_form").serialize(),
            dataType: 'JSON',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            success: function (resp) {
                $("#model_qr_code_flat").hide();
                if (resp.status === 'true') {
                    getInternalTransaction();
                    if(getSelectedMethod() === 'trading') {
                        tradingBalanceTotal();
                    } else {
                        mainBalanceTotal();
                    }
                    currentCoinBalance();
                    $("#get_resp").html(resp.message).addClass('alert alert-success').show();
                    setTimeout(function () {
                        $("#get_resp").html("").removeClass('alert alert-success').hide();
                    }, 2500)
                } else if (resp.status === 'false') {
                    $("#get_resp").html(resp.message).addClass('alert alert-danger').show();
                    setTimeout(function () {
                        $("#get_resp").html("").removeClass('alert alert-danger').hide();
                    }, 2500)
                }
                $("#deposit_modal_form")[0].reset();
                $("#btnSubmit").prop("disabled", false);
            },
            error: function (e) {
                $("#model_qr_code_flat").hide();
                $("#btnSubmit").prop("disabled", false);
            }
        });
    });

    $('#search_coin').keyup(function () {
        let coin_name = $("#search_coin").val();
        $.ajax({
            url: '/front2/assets/getusercoinlistajax',
            type: 'post',
            data: {
                coin_name: coin_name
            },
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            dataType: 'JSON',
            success: function (resp) {
                let getHtml = '';
                $("#mycoinlist").html('mycoinlist')
                $.each(resp, function (key, value) {
                    let icon = "";
                    if (value.icon !== undefined && value.icon !== null && value.icon !== '') {
                        icon = '<img src="/uploads/cryptoicon/' + value.icon + '" width="40px" max-height="40px">';
                    }

                    let coinTitle = value.coinName + "(" + value.short_name + ")";
                    let coinId = localStorage.getItem('coinId');
                    let highlighted = localStorage.getItem('highlighted');
                    if (getSelectedMethod() === "trading") {
                        if (value.tradingBalance !== '0') {
                            if(highlighted === 'Yes' && coinId === value.coin_id) {
                                getHtml = getHtml + '<tr class="setvalue on" data-id="coin_name_' + key + '" onclick="callMe(this)" style="cursor:pointer;" data-coin-id="' + value.coin_id + '" data-coin-title="' + coinTitle + '">'
                                getHtml = getHtml + '   <td><span  >' + icon + " " + value.short_name + '</span></td>';
                            } else {
                                getHtml = getHtml + '<tr class="setvalue" data-id="coin_name_' + key + '" onclick="callMe(this)" style="cursor:pointer;" data-coin-id="' + value.coin_id + '" data-coin-title="' + coinTitle + '">'
                                getHtml = getHtml + '   <td><span  >' + icon + " " + value.short_name + '</span></td>';
                            }
                        } else {
                            if(highlighted === 'Yes' && coinId === value.coin_id) {
                                getHtml = getHtml + '<tr class="setvalue on hide_currency" data-id="coin_name_' + key + '" onclick="callMe(this)" style="cursor:pointer;">'
                                getHtml = getHtml + '	<td><span >' + icon + " " + value.short_name + '</span></td>';
                            } else {
                                getHtml = getHtml + '<tr class="setvalue hide_currency" data-id="coin_name_' + key + '" onclick="callMe(this)" style="cursor:pointer;">'
                                getHtml = getHtml + '	<td><span >' + icon + " " + value.short_name + '</span></td>';
                            }

                        }
                        getHtml = getHtml + '	<td><span id="retained_quantity_' + value.coin_id + '">' + value.tradingBalance + '</span></td>';
                        getHtml = getHtml + '	<td><span id="krw_quantity_' + value.coin_id + '">' + value.customPriceTrading + '</span></td>';
                        getHtml = getHtml + '<input type="hidden" value="' + value.tradingBalance + '" id="quantity_' + key + '" name="quantity_' + key + '"/>' +
                            '<input type="hidden" value="'+ value.customPriceTrading +'" id="krw_' + key + '" name="krw_' + key + '"/>' +
                            '<input type="hidden" value="' + value.short_name + '" id="coin_name_'+key+'" name="coin_name_' + key + '"/>' +
                            '<input type="hidden" value="' + value.krwValue + '" id="coin_id_' + value.short_name + '" name="coin_id_' + value.short_name + '"/>' +
                            '<input type="hidden" value="' + value.tradingBalance + '" id="tradingBalance_' + key + '" name="tradingBalance_' + key + '"/>' +
                            '<input type="hidden" value="' + value.coinAddress + '" id="coinAddress_' + key + '" name="coinAddress_' + key + '"/>' +
                            '<input type="hidden" value="' + value.reservedBalance + '" id="reserveBalance_' + key + '" name="reserveBalance_' + key + '"/>';
                    } else {
                        if (value.principalBalance !== '0') {
                            if(highlighted === 'Yes' && coinId === value.coin_id) {
                                getHtml = getHtml + '<tr class="setvalue on" data-id="coin_name_' + key + '" style="cursor:pointer;" onclick="callMe(this)" data-coin-id="'+value.coin_id+'" data-coin-title="'+ coinTitle + '"> '
                                getHtml = getHtml + '   <td><span>' + icon + " " + value.short_name + '</span></td>';
                            } else {
                                getHtml = getHtml + '<tr class="setvalue" data-id="coin_name_' + key + '" style="cursor:pointer;" onclick="callMe(this)" data-coin-id="'+value.coin_id+'" data-coin-title="'+ coinTitle + '"> '
                                getHtml = getHtml + '   <td><span>' + icon + " " + value.short_name + '</span></td>';
                            }

                        } else {
                            if(highlighted === 'Yes' && coinId === value.coin_id) {
                                getHtml = getHtml + '<tr class="setvalue on hide_currency" data-id="coin_name_' + key + '" onclick="callMe(this)" style="cursor:pointer;"> '
                                getHtml = getHtml + '	<td><span >' + icon + " " + value.short_name + '</span></td>';
                            } else {
                                getHtml = getHtml + '<tr class="setvalue hide_currency" data-id="coin_name_' + key + '" onclick="callMe(this)" style="cursor:pointer;"> '
                                getHtml = getHtml + '	<td><span >' + icon + " " + value.short_name + '</span></td>';
                            }
                        }
                        getHtml = getHtml + '	<td><span id="retained_quantity_'+value.coin_id+'">' + value.principalBalance + '</span></td>';
                        getHtml = getHtml + '	<td><span id="krw_quantity_'+value.coin_id+'">' + value.customPriceMain + '</span></td>';
                        getHtml = getHtml + '<input type="hidden" value="' + value.principalBalance + '" id="quantity_' + key + '" name="quantity_' + key + '"/>' +
                            '<input type="hidden" value="'+ value.customPriceMain +'" id="krw_' + key + '" name="krw_' + key + '"/>' +
                            '<input type="hidden" value="' + value.short_name + '" id="coin_name_'+key+'" name="coin_name_' + key + '"/>' +
                            '<input type="hidden" value="' + value.krwValue + '" id="coin_id_' + value.short_name + '" name="coin_id_' + value.short_name + '"/>' +
                            '<input type="hidden" value="' + value.principalBalance + '" id="principalBalance_' + key + '" name="principalBalance_' + key + '"/>' +
                            '<input type="hidden" value="' + value.coinAddress + '" id="coinAddress_' + key + '" name="coinAddress_' + key + '"/>' +
                            '<input type="hidden" value="' + value.reservedBalance + '" id="reserveBalance_' + key + '" name="reserveBalance_' + key + '"/>';
                    }
                    getHtml = getHtml + '</tr>'
                });
                $("#mycoinlist").html(getHtml);
            }
        });
    });

    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    $("#radioclick").click(function () {
        let isChecked = $('#radioclick').is(':checked');
        if (isChecked === true) {
            $(".hide_currency").hide();
        } else {
            $(".hide_currency").show();
        }
    });

});

function callMe(event) {
    $("#mesg").hide();
    $('#tab_menu_coins').show();

    if ($('#depositkrw_on').hasClass('on')) {
        tabClick('deposit');
    }
    if ($('#krwwithdrawal_on').hasClass('on')) {
        tabClick('withdrawal');
    }

    let id = $(event).attr("data-id");
    getMyCoinId = $(event).attr("data-coin-id");
    if (getMyCoinId === '20') {
        if ($('#withdrawal_on').hasClass('on')) {
            tabClick('krwwithdrawal');
        }
        if ($('#deposit_on').hasClass('on')) {
            tabClick('depositkrw');
        }
    }

    let getMyCoinTitle = $(event).attr("data-coin-title");
    $("tbody#mycoinlist tr").removeClass("on");
    $(event).closest('tr').addClass("on");
    $(event).parent().parent().addClass("on");
    localStorage.setItem("coinId", getMyCoinId);
    localStorage.setItem("highlighted", 'Yes');
    let new_value = id.split("_");
    $("#withdrawal_type_out").prop("checked", true);
    $("#withdrawal_type_in").prop('checked', false);
    $("#otp_number").val('');
    $("#req_amount").val('');
    $("#withdrawalfee").text('0');

    if (new_value !== undefined && new_value !== null && new_value !== '') {
        let quantityValue = $("#quantity_" + new_value[2]).val();
        let krwValue = $("#krw_" + new_value[2]).val();
        var coinNameValue = $("#coin_name_" + new_value[2]).val();
        let tradingBalance = $("#tradingBalance_" + new_value[2]).val();
        let coinAddress = $("#coinAddress_" + new_value[2]).val();
        let reserveBalance = $("#reserveBalance_" + new_value[2]).val();
        $("#coin_name_id_data").html(getMyCoinTitle);
        $("#qr_code_image").html('<img src="https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl=' + coinAddress + '" style="width:170px; height:170px;" />');
        $("#wallet_addr_input").val(coinAddress);
        $("#amount_data").html(quantityValue);
        $("#unit_data").html(coinNameValue);
        $(".unit_data").html(coinNameValue);
        localStorage.setItem("amount_data", quantityValue);
        localStorage.setItem("unit_data", coinNameValue);
        localStorage.setItem("tradingBalance", tradingBalance);
        localStorage.setItem("reserveBalance", reserveBalance);
        let value = localStorage.getItem("value");
        if (value == null) {
            $("#depositkrw_on").trigger("click");
            $("#depositkrw_on").addClass("deposit on");
        } else {
            $("#" + value + "_on").trigger("click");
            $("#" + value + "_on").addClass(value + "on");
        }
        $("#amountkrw").html(0);
        $("#totalValuekrw").html(0);
    }
    let transType = getSelectedMethod();
    displayWalletAddress(transType,getCoinsName(coinNameValue));
    currentCoinBalance();
    callUserFeeAjax(getMyCoinId);
}

function openAddWithdrawalWalletAddrModel() {
    let coin_id = $("#selected_coind_id").val();
    $("#rwwd_coin_id").val(coin_id);
    $("#myModalAddWithdrawalWalletAddr").modal('show');
}

function openOTP() {
    let password = $("#password").val();
    let req_amount = $("#req_amount_krw").val();
    let amountkrw = document.getElementById("krw_amount_data").innerText;
    let total_amount = document.getElementById("totalAmountkrw").innerText;
    let account = document.getElementById("bank_account").innerText;
    let bank = document.getElementById("bank_name").innerText;
    let reqAmount = parseFloat(req_amount);
    let amountTotal = amountkrw.replace(/\,/g, '');
    let totalamount = total_amount.replace(/\,/g, '');
    let availableAmount = parseFloat(amountTotal);
    let totalAmount = parseFloat(totalamount);
    let totalBuy = $("#totalBuy").val();
    let totalSell = $("#totalSell").val();
    let totalBuyAmount = parseFloat(totalBuy);
    let totalSellAmount = parseFloat(totalSell);
    let halfBuy = totalBuyAmount / 2;
    let halfSell = totalSellAmount / 2;
    //추가 실제 보유하고있는 금액 체크
    let totalDepositAmount = $("#totalDepositAmount").val();

    if ((reqAmount < halfBuy || reqAmount < halfSell) && reqAmount < 50000) {
        toastr.error(__('Withdrawal conditions do not match'));
        return false;
    }

    if (req_amount === undefined || req_amount === null || req_amount === '') {
        toastr.error(__('Please enter the amount you want to withdraw'));
        return false;
    }

    if (availableAmount < 50000) {
        toastr.error(__('Insufficient balance'));
        return false;
    }

    if (reqAmount < 50000) {
        toastr.error(__('You need to withdraw at least 50,000 KRW'));
        return false;
    }

    if (availableAmount > amountTotal || totalAmount > amountTotal) {
        toastr.error(__('Please enter valid withdrawal amount'));
        return false;
    }

    if (account === undefined || account === null || account === '') {
        toastr.error(__('Please verify your bank account'));
        return false;
    }
    if (bank === undefined || bank === null || bank === '') {
        toastr.error(__('Please verify your bank account'));
        return false;
    }

    if (password !== '' || password !== null) {
        $.ajax({
            url: '/front2/assets/verifyPass',
            type: 'post',
            data: {
                password: password
            },
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            dataType: 'JSON',
            success: function (resp) {
                if (resp.success === "true") {
                    toastr.success(resp.message);
                    $("#myModalOTP").modal('show');
                } else {
                    toastr.error(resp.message);
                }
            }
        });
    } else {
        toastr.error(__('Please enter the password'));
        return false;
    }
}

function openOTPW() {
    let unitdata = $("#unit_data").text();
    let req_amount = $("#req_amount").val();
    let wallet_address = $("#wallet_address").val();
    let amountkrw = $("#amount_data").text();
    let totalValue = $('#totalValuekrw').text();
    let amountTotal = parseFloat(totalValue.replace(/\,/g, ''));
    let availableAmountCoin = parseFloat(amountkrw.replace(/\,/g, ''));
    let reqAmount = parseFloat(req_amount);
    //하루와 한달치 데이터 가져온다
    let day_point = $("#day_coin").val();
    let month_point = $("#month_coin").val();
    //합계값
    let total_day = parseFloat(day_point) + parseFloat(req_amount);
    let total_month = parseFloat(month_point) + parseFloat(req_amount);

    //KRW 출금시
    if(unitdata == 'KRW'){
        //return;
        if(req_amount > 500000) {
            alert("하루 최대 500000KRW 출금가능합니다..");
            return;
        }
        //하루 최대치 20000 초과 입력 방지
        if(total_day > 500000){
            alert("하루 최대 500000KRW 출금가능합니다..");
            return;
        }
        if(total_month > 1000000){
            alert("한달간 최대 100만KRW 출금이 가능합니다.");
            return;
        }
    }

    //console.log(unitdata);

    if (availableAmountCoin < amountTotal) {
        toastr.error(__('Please enter the valid withdrawal amount'));
        return false;
    }

    if (unitdata === undefined || unitdata === null || unitdata === '') {
        toastr.error(__('Please select the coin'));
        return false;
    }
    if (reqAmount === undefined || reqAmount === null) {
        toastr.error(__('Please enter the amount you want to withdraw'));
        return false;
    }
    if (wallet_address === undefined || wallet_address === null || wallet_address === '') {
        toastr.error(__('Please enter the wallet address'));
        return false;
    }
    if (+reqAmount > +availableAmountCoin) {
        toastr.error(__('Please enter the valid withdrawal amount'));
        return false;
    }

    let password = $("#pass").val();
    if (password !== '' || password !== null) {
        $.ajax({
            url: '/front2/assets/verifyPass',
            type: 'post',
            data: {
                password: password
            },
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            dataType: 'JSON',
            success: function (resp) {
                if (resp.success === "true") {
                    toastr.success(resp.message);
                    $("#myModalOTPW").modal('show');
                } else {
                    //비밀번호가 틀려도 일단 모달창을 띄우게 설정
                    //오늘수정
                    toastr.error(resp.message);
                    //$("#myModalOTPW").modal('show');
                }
            }
        });
    } else {
        toastr.error(__('Please enter the password'));
        return false;
    }
}

var coin = "BTC";

function sideBarCoinClick(coin) {
    $(".common_tab").hide();
    $("#mesg").hide();
    $('#tab_menu_coins').show();
    $("#coin_name").html(coin);
    $("#default_content").hide();
    let btcAddr = $("#btcAddr").val();
    let ethAddr = $("#ethAddr").val();
    let walletAddr = (coin === "BTC") ? btcAddr : ethAddr;
    let qrCodeUrl = "https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl=" + walletAddr;
    $("#qr_code_image").attr('src', qrCodeUrl);
    $("#wallet_addr_input").val(walletAddr);
    $("#selected_coind_id").val(coin);
    $("#deposit_tab_content").show();
}
function tabChange(id){
    $("#deposit_on").removeClass("on");
    $("#depositkrw_on").removeClass("on");
    $("#krwwithdrawal_on").removeClass("on");
    $("#statement_on").removeClass("on");
    $("#withdrawal_on").removeClass("on");
    $("#breakdown_on").removeClass("on");
    $("#withdrawal_addr_on").removeClass("on");
    $("#"+id).addClass("deposit on");
}
function tabClick(tab_name) {
    $(".common_tab").hide();
    if (tab_name === "deposit") {
        localStorage.setItem("value", "deposit");
        $("#deposit_tab_content").show();
        $("#btns_transfer").hide();
        tabChange("deposit_on");
    } else if (tab_name === "withdrawal") {
        localStorage.setItem("value", "withdrawal");
        $("#withdrawal_tab_content").show();
        $("#table_withdrowl1").show();
        $("#btns_transfer").show();
        $("#table_withdrowl2").hide();
        $("#withdrawal_type_out").prop("checked", true);
        $("#withdrawal_type_in").prop('checked', false);
        tabChange("withdrawal_on");
    } else if (tab_name === "breakdown") {
        localStorage.setItem("value", "breakdown");
        $("#breakdown_tab_content").show();
        $("#btns_transfer").hide();
        tabChange("breakdown_on");
    } else if (tab_name === "withdrawal_addr") {
        localStorage.setItem("value", "withdrawal_addr");
        $("#withdrawal_addr_tab_content").show();
        $("#btns_transfer").show();
        tabChange("withdrawal_addr_on");
    } else if (tab_name === "depositkrw") {
        localStorage.setItem("value", "depositkrw");
        $("#default_content").show();
        $("#btns_transfer").hide();
        tabChange("depositkrw_on");
    } else if (tab_name === "krwwithdrawal") {
        localStorage.setItem("value", "krwwithdrawal");
        $("#krwwithdrawal_tab_content").show();
        $("#btns_transfer").hide();
        setMain();
        tabChange("krwwithdrawal_on");
    } else if (tab_name === "statement") {
        localStorage.setItem("value", "statement");
        $("#statement_tab_content").show();
        tabChange("depositListStatement_on");
        $("#withdrawalListStatement_on").removeClass("on");
        myDepositListAjax();
        $("#btns_transfer").hide();
        $("#krwwithdrawal_tab_content").hide();
        $("#statement_on").addClass("deposit on");
        $("#withdraw_li_div").hide();
        $("#deposit_li_div").show();
    } else if (tab_name === "depositListState") {
        localStorage.setItem("value", "depositListState");
        $("#statement_tab_content").show();
        tabChange("depositListStatement_on");
        $("#withdraw_li_div").hide();
        $("#btns_transfer").hide();
        $("#deposit_li_div").show();
        myDepositListAjax();
        $("#withdrawalListStatement_on").removeClass("on");
        $("#statement_on").addClass("deposit on");
    } else if (tab_name === "withdrawListState") {
        localStorage.setItem("value", "withdrawListState");
        $("#statement_tab_content").show();
        tabChange("withdrawalListStatement_on");
        $("#withdraw_li_div").show();
        $("#btns_transfer").hide();
        $("#deposit_li_div").hide();
        myWithdrawListAjax();
        $("#depositListStatement_on").removeClass("on");
        $("#statement_on").addClass("deposit on");
    }
}

function createWallet() {
    document.location.href = "/front2/assets/deposit2";
}

function copyToClipboard() {
    if ($("#wallet_addr_input").val() !== '') {
        $("#wallet_addr_input").select();
        document.execCommand("copy");
        $("#copy_msg").html(__('Wallet address copied')).show();
        setTimeout(function () {
            $("#copy_msg").html("").hide();
        }, 5000);
    }
}

function callUserFeeAjaxCallBack(getResp) {
    withdrawFee = getResp;
    $("#withdraw_fee_percent").html("(" + withdrawFee + " %)");
}

function callUserFeeAjax(getCoinId) {
    $.ajax({
        url: '/front2/assets/userFeeSetting/' + getCoinId,
        type: 'GET',
        dataType: 'JSON',
        success: function (resp) {
            callUserFeeAjaxCallBack(resp.data.user_fee);
        }
    })
}

function display_data(id, short_name, principalBalance, krw_value, getMyCustomPrice, reserveBalance, coinAddress, coinName) {
    $("#withdrawal_type_out").prop("checked", true);
    $("#withdrawal_type_in").prop('checked', false);
    let quantityValue = principalBalance;
    let coinNameValue = short_name;
    let tradingBalance = getMyCustomPrice;
    $("#qr_code_image").html('<img src="https://chart.googleapis.com/chart?cht=qr&chs=200x200&chl=' + coinAddress + '" style="width:170px; height:170px;" />');
    $("#wallet_addr_input").val(coinAddress);
    $("#amount_data").html(quantityValue);
    $("#unit_data").html(coinNameValue);
    $(".unit_data").html(coinNameValue);
    localStorage.setItem("amount_data", quantityValue);
    localStorage.setItem("unit_data", coinNameValue);
    localStorage.setItem("tradingBalance", tradingBalance);
    localStorage.setItem("reserveBalance", reserveBalance);
    let value = localStorage.getItem("value");
    if (value == null) {
        $("#deposit_on").trigger("click");
        $("#deposit_on").addClass("deposit on");
    } else {
        $("#" + value + "_on").trigger("click");
        $("#" + value + "_on").addClass(value + "on");
    }
    $("#amountkrw").html(0);
    $("#totalValuekrw").html(0);
    let trans_type = getSelectedMethod();
    displayWalletAddress(trans_type, coinNameValue);
    getMyCoinId = id;
    $("#coin_name_id_data").html(coinName + "(" + coinNameValue + ")")
    currentCoinBalance();
}

function amountSubmitted() {
    let amount = $("#amount_deposited").val();

    if (amount === undefined || amount === null || amount === '' || amount < 50000) {
        toastr.error(__('Please deposit minimum 50,000 KRW'));
        return false;
    }

    $.ajax({
        url: '/front2/assets/krwDeposit',
        type: 'post',
        data: {
            amount_deposited: amount
        },
        beforeSend: function(xhr){
            xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
        },
        success: function (resp) {
            var resp = JSON.parse(resp);
            if (resp.success === "false") {
                toastr.error(resp.message);
                return false;
            } else {
                toastr.success('Success!');
                $("#amount_deposited").val("");
                localStorage.setItem("value", "depositListState");
                $("#default_content").hide();
                $("#statement_tab_content").show();
                myDepositListAjax();
                $("#statement_on").addClass("deposit on");
                $("#withdrawalListStatement_on").removeClass("on");
                tabChange("depositListStatement_on");
                $("#withdraw_li_div").hide();
                $("#deposit_li_div").show();
                disableElements();
            }
        }
    });
}

function transferAmount(coinName, transferTo) {
    $("#modal_coin_name").html(coinName);
    $("#wallet_name").html(transferTo);
    $("#coin_id").val(coinName);
    $("#transfer_to").val(transferTo);
    $('#myModalDeposit').modal('show');
}

function increment() {
    let valuecheck = localStorage.getItem("unit_data");
    let value = "0";
    let value1 = $("#req_amount").val();
    let bPrice = parseFloat(value1).toFixed(2);
    if(!isNaN(parseFloat(bPrice))){
        let numDec = countDecimals(bPrice);
        if(numDec !== 0 && numDec === 1 || numDec === 2){
            $("#req_amount").attr({step:'0.01',min:'0.01'});
            value = '0.01';
        } else if(numDec === 3){
            $("#req_amount").attr({step:'0.1',min:'0.1'});
            value = '0.1';
        } else {
            $("#req_amount").attr({step:'1.0',min:'1.0'});
            value = '1.0';
        }
    }

    if (value1 !== undefined && value1 !== null && value1 !== '') {
        let new_value = parseFloat(value1) + parseFloat(value);
        if (valuecheck === "USDT") {
            $("#req_amount").val(new_value.toFixed(2)).trigger('input');
        } else {
            $("#req_amount").val(new_value.toFixed(2)).trigger('input');
        }
    } else {
        let new_value = value;
        if (valuecheck === "USDT") {
            $("#req_amount").val(parseFloat(new_value).toFixed(2)).trigger('input');
        } else {
            $("#req_amount").val(parseFloat(new_value).toFixed(1)).trigger('input');
        }
    }

    let req_amount = $("#req_amount").val();
    let unit_data = $('#unit_data').text();
    let krw_value = $('#coin_id_' + unit_data).val();
    let new_values = parseFloat(req_amount).toFixed(2) * parseFloat(krw_value).toFixed(2);
    let fee_charges = (parseFloat(req_amount).toFixed(4) * withdrawFee)/100;

    $("span#amountkrw").text("" + new_values);
    $("span#withdrawalfee").text("" + fee_charges.toFixed(4));
    let reamainingBalance = req_amount - fee_charges;
    $("span#totalValuekrw").text("" + reamainingBalance.toFixed(2));
}

function decrement() {
    let valuecheck = localStorage.getItem("unit_data");
    let value1 = $("#req_amount").val();
    let value = "0";
    let bPrice = parseFloat(value1).toFixed(2);
    if(!isNaN(parseFloat(bPrice))){
        let numDec = countDecimals(bPrice);
        if(numDec !==0 && numDec === 1 || numDec === 2){
            $("#req_amount").attr({step:'0.01',min:'0.01'});
            value = "0.01";
        } else if(numDec === 3){
            $("#req_amount").attr({step:'0.1',min:'0.1'});
            value = "0.1";
        } else {
            $("#req_amount").attr({step:'1.0',min:'1.0'});
            value = "1.0";
        }
    }
    if (value1 !== undefined && value1 !== null && value1 !== '') {
        if (value1 > 0) {
            let new_value = parseFloat(value1) - parseFloat(value);
            if (Math.sign(new_value) === -1) {
                new_value = 0;
            }
            if (valuecheck === "USDT") {
                $("#req_amount").val(new_value.toFixed(2)).trigger('input');
            } else {
                $("#req_amount").val(new_value.toFixed(2)).trigger('input');
            }
        }

    } else {
        let new_value = value
        if (valuecheck === "USDT") {
            $("#buy_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
        } else {
            $("#buy_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
        }
    }

    let req_amount = $("#req_amount").val();
    let unit_data = $('#unit_data').text();
    let krw_value = $('#coin_id_' + unit_data).val();

    let new_values = parseFloat(req_amount).toFixed(2) * parseFloat(krw_value).toFixed(2);

    let fee_charges = (parseFloat(req_amount).toFixed(2) * withdrawFee)/100;
    $("span#amountkrw").text("" + new_values.toFixed(2));
    $("span#withdrawalfee").text("" + fee_charges.toFixed(2));
    let reamainingBalance = req_amount - fee_charges;
    $("span#totalValuekrw").text("" + reamainingBalance.toFixed(2));
}

function myDepositListAjax() {
    // ajax for myOrder list
    $.ajax({
        url: '/front2/assets/myDepositListAjax',
        type: 'get',
        dataType: 'json',
        success: function (resp) {
            // my buyOrderList data
            var html = '';
            if ($.isEmptyObject(resp.myDepositList)) {
                html = html + '<tr>';
                html = html + "<td colspan=5>"+__('There is no transaction history')+"</td>";
                html = html + '</tr>';
            } else {
                $.each(resp.myDepositList, function (key, value) {
                    let showAmount = numberWithCommas(parseFloat(value.amount).toFixed(2));
                    var splitDateTime = value.created_at;
                    var splitDateTime = splitDateTime.split("+");
                    var getdateTime = splitDateTime[0];
                    var getdateTime = getdateTime.replace("T", " ");
                    let status = ucfirst(value.status);
                    html = html + '<tr>';
                    html = html + '<td>' + __('Deposit') + '</td>';
                    html = html + '<td>' + showAmount + '</td>';
                    html = html + '<td>' + getdateTime + '</td>';
                    if (status === 'Completed') {
                        html = html + '<td style="color:blue;">' + __('Completed') + '</td>';
                    } else if (status === 'Pending') {
                        html = html + '<td style="color:orange;">' + __('Pending') + '</td>';
                    } else if (status === 'Deleted') {
                        //html = html + '<td style="color:red;">' + '' + '</td>';
                    } else {
                        html = html + '<td>&nbsp;</td>'
                    }
                    html = html + '</tr>';
                });
            }
            $("#depositTable").dataTable().fnDestroy();
            $("#myDepositlist").html(html);
            $('#depositTable').DataTable({
                bSort: false,
                pageLength: 15,
                scrollY: "300px",
                scrollX: false,
                scrollCollapse: true,
                fixedColumns: {
                    leftColumns: 1,
                    rightColumns: 1
                },
                language: {
                    /*"url": "https://www.coinibt.io/datatable_language/"+ datatable_language +".json"*/
                    "url": "https://www.bitsomon.com/datatable_language/"+ datatable_language +".json"
                }
            });
        }
    });
}

function myWithdrawListAjax() {
    $.ajax({
        url: '/front2/assets/myWithdrawListAjax',
        type: 'get',
        dataType: 'json',
        success: function (resp) {
            var html = '';
            if ($.isEmptyObject(resp.myWithdrawList)) {
                html = html + '<tr>';
                html = html + "<td colspan=5>"+__('Transaction history is not available')+"</td>";
                html = html + '</tr>';
            } else {
                $.each(resp.myWithdrawList, function (key, value) {
                    let showAmount = 0.0;
                    if (value.coin_amount !== null && value.coin_amount !== undefined && value.coin_amount !== '') {
                        showAmount = numberWithCommas(parseFloat(value.coin_amount).toFixed(2));
                    } else {
                        showAmount = 0.0;
                    }
                    let created_at = value.created_at.split("+")[0].replace("T", " ");
                    let status = ucfirst(value.status);
                    html = html + '<tr>';
                    html = html + '<td>' + __('Withdrawal') + '</td>';
                    html = html + '<td>' + showAmount + '</td>';
                    html = html + '<td>' + numberWithCommas(parseFloat(value.fees).toFixed(2)) + '</td>';
                    html = html + '<td>' + created_at + '</td>';
                    if (status === 'Completed') {
                        html = html + '<td style="color:blue;">' + __('Completed') + '</td>';
                    } else if (status === 'Pending') {
                        html = html + '<td style="color:orange;">' + __('Pending') + '</td>';
                    } else if (status === 'Deleted') {
                        // html = html + '<td style="color:red;">' + '' + '</td>';
                    } else {
                        html = html + '<td>&nbsp;</td>'
                    }
                    html = html + '</tr>';
                });
            }

            let checkDisplay = $("#withdraw_li_div").css("display");
            if (checkDisplay !== "none") {
                $("#withdrawTable").dataTable().fnDestroy();
                $("#myWithdrawlist").html(html);
                $('#withdrawTable').DataTable({
                    bSort: false,
                    pageLength: 15,
                    scrollY: "300px",
                    scrollX: false,
                    scrollCollapse: true,
                    paging: false,
                    fixedColumns: {
                        leftColumns: 1,
                        rightColumns: 1
                    },
                    language: {
                        /*"url": "https://www.coinibt.io/datatable_language/"+datatable_language+".json"*/
                        "url": "https://www.bitsomon.com/datatable_language/"+datatable_language+".json"
                    }
                });
            } else {
                $("#myWithdrawlist").html(html);
            }
        }
    });
}

function setValues(type) {
    let mainBal = $("#main").val();
    let mainAccount = numberWithCommas(parseFloat(mainBal).toFixed(2));
    if(type === 'main') {
        $('#main_total_balance').text(""+mainAccount).removeAttr('hidden');
        getCoinList('main');
    } else {
        getCoinList('trading');
    }
}

function getCoinList(types) {
    $.ajax({
        url: '/front2/assets/getcoinslist',
        type: 'get',
        dataType: 'json',
        success: function (resp) {
            let getHtml = '';
            $("#mycoinlist").html('mycoinlist')
            $.each(resp, function (key, value) {
                let icon = "";
                if (value.icon !== undefined && value.icon !== null && value.icon !== '') {
                    icon = '<img src="/uploads/cryptoicon/' + value.icon + '" width="40px" max-height="40px">';
                }
                let coinTitle = value.coinName + "(" + value.short_name + ")";
                let coinId = parseInt(localStorage.getItem('coinId'));
                let highlighted = localStorage.getItem('highlighted');
                if (types === "trading") {
                    if (value.tradingBalance !== '0') {
                        if(highlighted === 'Yes' && coinId === value.coin_id) {
                            getHtml = getHtml + '<tr class="setvalue on" data-id="coin_name_' + key + '" onclick="callMe(this)" style="cursor:pointer;" data-coin-id="'+value.coin_id+'" data-coin-title="'+ coinTitle +'">'
                            getHtml = getHtml + '   <td><span  >' + icon + " " + value.short_name + '</span></td>';
                        } else {
                            getHtml = getHtml + '<tr class="setvalue" data-id="coin_name_' + key + '" onclick="callMe(this)" style="cursor:pointer;" data-coin-id="'+value.coin_id+'" data-coin-title="'+ coinTitle +'">'
                            getHtml = getHtml + '   <td><span  >' + icon + " " + value.short_name + '</span></td>';
                        }
                    } else {
                        if(highlighted === 'Yes' && coinId === value.coin_id) {
                            getHtml = getHtml + '<tr class="setvalue on hide_currency" data-id="coin_name_' + key + '" onclick="callMe(this)" style="cursor:pointer;">'
                            getHtml = getHtml + '	<td><span >' + icon + " " + value.short_name + '</span></td>';
                        } else {
                            getHtml = getHtml + '<tr class="setvalue hide_currency" data-id="coin_name_' + key + '" onclick="callMe(this)" style="cursor:pointer;">'
                            getHtml = getHtml + '	<td><span >' + icon + " " + value.short_name + '</span></td>';
                        }
                    }
                    getHtml = getHtml + '	<td><span id="retained_quantity_' + value.coin_id + '">' + value.tradingBalance + '</span></td>';
                    getHtml = getHtml + '	<td><span id="krw_quantity_' + value.coin_id + '">' + value.customPriceTrading + '</span></td>';
                    getHtml = getHtml + '<input type="hidden" value="' + value.tradingBalance + '" id="quantity_' + key + '" name="quantity_' + key + '"/>' +
                        '<input type="hidden" value="'+ value.customPriceTrading +'" id="krw_' + key + '" name="krw_' + key + '"/>' +
                        '<input type="hidden" value="' + value.short_name + '" id="coin_name_'+key+'" name="coin_name_' + key + '"/>' +
                        '<input type="hidden" value="' + value.krwValue + '" id="coin_id_' + value.short_name + '" name="coin_id_' + value.short_name + '"/>' +
                        '<input type="hidden" value="' + value.tradingBalance + '" id="tradingBalance_' + key + '" name="tradingBalance_' + key + '"/>' +
                        '<input type="hidden" value="' + value.coinAddress + '" id="coinAddress_' + key + '" name="coinAddress_' + key + '"/>' +
                        '<input type="hidden" value="' + value.reservedBalance + '" id="reserveBalance_' + key + '" name="reserveBalance_' + key + '"/>';
                } else {
                    if (value.principalBalance !== '0') {
                        if(highlighted === 'Yes' && coinId === value.coin_id) {
                            getHtml = getHtml + '<tr class="setvalue on" data-id="coin_name_' + key + '" style="cursor:pointer;" onclick="callMe(this)" data-coin-id="'+value.coin_id+'" data-coin-title="'+ coinTitle + '"> '
                            getHtml = getHtml + '   <td><span>' + icon + " " + value.short_name + '</span></td>';
                        } else {
                            getHtml = getHtml + '<tr class="setvalue" data-id="coin_name_' + key + '" style="cursor:pointer;" onclick="callMe(this)" data-coin-id="'+value.coin_id+'" data-coin-title="'+ coinTitle + '"> '
                            getHtml = getHtml + '   <td><span>' + icon + " " + value.short_name + '</span></td>';
                        }
                    } else {
                        if(highlighted === 'Yes' && coinId === value.coin_id) {
                            getHtml = getHtml + '<tr class="setvalue on hide_currency" data-id="coin_name_' + key + '" onclick="callMe(this)" style="cursor:pointer;"> '
                            getHtml = getHtml + '	<td><span >' + icon + " " + value.short_name + '</span></td>';
                        } else {
                            getHtml = getHtml + '<tr class="setvalue hide_currency" data-id="coin_name_' + key + '" onclick="callMe(this)" style="cursor:pointer;"> '
                            getHtml = getHtml + '	<td><span >' + icon + " " + value.short_name + '</span></td>';
                        }
                    }
                    getHtml = getHtml + '	<td><span id="retained_quantity_'+value.coin_id+'">' + value.principalBalance + '</span></td>';
                    getHtml = getHtml + '	<td><span id="krw_quantity_'+value.coin_id+'">' + value.customPriceMain + '</span></td>';
                    getHtml = getHtml + '<input type="hidden" value="' + value.principalBalance + '" id="quantity_' + key + '" name="quantity_' + key + '"/>' +
                        '<input type="hidden" value="'+ value.customPriceMain +'" id="krw_' + key + '" name="krw_' + key + '"/>' +
                        '<input type="hidden" value="' + value.short_name + '" id="coin_name_'+key+'" name="coin_name_' + key + '"/>' +
                        '<input type="hidden" value="' + value.krwValue + '" id="coin_id_' + value.short_name + '" name="coin_id_' + value.short_name + '"/>' +
                        '<input type="hidden" value="' + value.principalBalance + '" id="principalBalance_' + key + '" name="principalBalance_' + key + '"/>' +
                        '<input type="hidden" value="' + value.coinAddress + '" id="coinAddress_' + key + '" name="coinAddress_' + key + '"/>' +
                        '<input type="hidden" value="' + value.reservedBalance + '" id="reserveBalance_' + key + '" name="reserveBalance_' + key + '"/>';
                }
                getHtml = getHtml + '</tr>'
            });
            $("#mycoinlist").html(getHtml);
            displayWalletAddress(types, getCoinsName(getMyCoinId));
        }
    });
}

function getSelectedMethod() {
    let types = '';
    if ($('#btn_trading_withdraw').hasClass('selected')) {
        types = 'trading';
        $('#rwwd_wallet_name').attr('readonly',false);
    }
    if ($('#btn_main_withdraw').hasClass('selected')) {
        types = 'main';
        $('#rwwd_wallet_name').attr('readonly',true);
    }


    return types;
}

function displayWalletAddress(transType, coinName){
    $.ajax({
        url: '/front2/assets/displayWalletAddress',
        type: 'post',
        data: {
            'coinName': coinName,
            'trans_type': transType
        },
        beforeSend: function(xhr){
            xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
        },
        success: function (resp) {
            let getHtml1 = '<option value="">'+ __("Please select a wallet address")+'</option>';
            var resp = JSON.parse(resp);
            if (resp.success === "true") {
                var getHtml = "";
                $.each(resp.data, function (key, value) {
                    getHtml = getHtml + '<tr id="td_data_' + value.id + '">';
                    getHtml = getHtml + '	<td><input type="checkbox" name="' + value.id + '" data-id="' + value.id + '" /></td>';
                    getHtml = getHtml + '	<td><span>' + value.wallet_name + '</span></td>';
                    getHtml = getHtml + '	<td><span>' + value.wallet_address + '</span></td>';
                    getHtml = getHtml + '	<td><span>' + moment(value.modified).format('MM-DD-YYYY h:mm A')+'</span></td>';
                    getHtml = getHtml + '</tr>';
                    getHtml1 = getHtml1 + '<option value=' + value.wallet_address + '> ' + value.wallet_name + ' - ' + value.wallet_address + ' </option>';
                });
            } else {
                getHtml = getHtml + '<tr>';
                getHtml = getHtml + '	<td colspan="4">'+__("No registered wallet address")+'</td>';
                getHtml = getHtml + '</tr>';
            }
            $("#withdrawal_addr").html(getHtml);
            $("#wallet_address").html(getHtml1);
        }
    });
}

function getCoinsName(coinId){
    let coinName;
    let coinsId =  coinId.toString();
    switch (coinsId) {
        case '1':
            coinName = 'Bitcoin';
            break;
        case '5':
            coinName = 'Tether';
            break;
        case '17':
            coinName = 'Token Pay';
            break;
        case '18':
            coinName = 'Ethereum';
            break;
        case '19':
            coinName = 'Market Coin';
            break;
        case '20':
            coinName = 'Korean Won';
            break;
        case '21':
            coinName = 'Cybertronchain';
            break;
        case '22':
            coinName = 'Ripple';
            break;
        case '27':
            coinName = 'Binance Coin';
            break;
        default:
            coinName = 'No coin selected';
    }
    return coinName;
}