var pairCurrentPrice = 0;
var fee = 0.50;
var datatable_language = '';
const authUserType = $('#authUserType').val();
const firstCoinId = $("#firstCoinId").val();
const secondCoinId = $("#secondCoinId").val();
const authUserId = $("#authUserId").val();
const firstCoin = $("#firstCoin").val();
const secondCoin = $("#secondCoin").val();
const binancePrice = $("#binancePrice").val();

if(getlang() === 'kr'){
    datatable_language = 'Korean';
} else {
    datatable_language = 'English';
}

$(document).ready(function(){
    $('input[type=radio]').click(function(){
        let getVal = $(this).val();
        let currentPrice =  "";
        if(getVal === "buy_limit_tab"){
            $("#buy_per_price").val(0).change();
            $("#buy_per_price_div").show();
        }
        else if(getVal === "buy_market_tab"){
            currentPrice =  $("#current_price").html();
            $("#buy_per_price").val(currentPrice).change();
            $("#buy_per_price_div").hide();
        }
        else if(getVal === "sell_limit_tab"){
            $("#sell_per_price").val(0).change();
            $("#sell_per_price_div").show();
        }
        else if(getVal === "sell_market_tab"){
            currentPrice =  $("#current_price").html();
            $("#sell_per_price").val(currentPrice).change();
            $("#sell_per_price_div").hide();
        }
    });
    getUserCurrentBalance();
    getPairCurrentPrice();
    setGraph();
    setInterval(function(){ checkExchange(); }, 5000);
    $('#ajaxdata123').DataTable({
        bSort: false,
        pageLength: 15,
        scrollY:        "300px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   {
            leftColumns: 1,
            rightColumns: 1
        },
        language: {
            "url": "http://testexchange.hansbiotech.kr/datatable_language/" + datatable_language + ".json"
        }
    });

    $('#mySellOrderlist_table').DataTable({
        bSort: false,
        pageLength: 15,
        scrollY:        "300px",
        scrollX:        false,
        scrollCollapse: true,
        paging:         true,
        fixedColumns:   {
            leftColumns: 1,
            rightColumns: 1
        },
        language: {
            "url": "http://testexchange.hansbiotech.kr/datatable_language/" + datatable_language + ".json"
        }
    });

    $("#sale_li").click(function(){
        setTimeout(function(){
            $('#mySellOrderlist_table').dataTable().fnDestroy();
            $('#mySellOrderlist_table').DataTable({
                bSort: false,
                pageLength: 15,
                scrollY:        "300px",
                scrollX:        false,
                scrollCollapse: true,
                paging:         true,
                fixedColumns:   {
                    leftColumns: 1,
                    rightColumns: 1
                },
                language: {
                    "url": "http://testexchange.hansbiotech.kr/datatable_language/"+ datatable_language + ".json"
                }
            });
        },0);
    });

    // Set the datepicker's date format
    $.datepicker.setDefaults({
        dateFormat: 'dd.mm.yy',
        onSelect: function(dateText) {
            this.onchange();
            this.onblur();
        }
    });
    let currentMyUrl = window.location.href;
    let breakUrl = currentMyUrl.split("?");
    let win = $(this); //this = window
    if (win.width() >= 1024) { //This will check windows resolution means width

    } else {
        if (typeof breakUrl[1] === 'undefined' && typeof window.orientation !== 'undefined') {
            $('html, body').animate({
                scrollTop: $(".tranx").offset().top
            }, 1000);
        }
    }

    $("#nav_bar").click(function(){
        $("#coin_show").toggle()
    })
    //exchange js

    $("#span_buy_volume").click(function(){
        let getVal = $("#span_buy_volume_all").val();
        $("#buy_total_amount").val(getVal);
    });

    $("#span_sell_volume").click(function(){
        let getVal = $("#span_sell_volume_all").val();
        $("#sell_volume").val(getVal).change();
    });

    // my order tab fuction
    $(".myorder_li").click(function(){
        $(".myorder_li").removeClass("on");
        $(this).addClass("on");
        let getId = $(this).attr('id');
        let showDivId = getId+"_div";
        $(".order_tab_system").hide();
        $("#"+showDivId).show();
    });

    if(authUserId !== '') {
        let daysRemaining = $("#daysRemaining").val();
        popup_show(daysRemaining);
    }

    callAllFunctions();
    let chatHistory = document.getElementById("sell_order_show_div");
    if(chatHistory!= null){
        chatHistory.scrollTop = chatHistory.scrollHeight;
    }

    myRealGraph = Highcharts.stockChart('container', {
        chart: {
            renderTo: 'container',
            backgroundColor: '#fff',
            borderColor: 'transparent',
            borderWidth: 0
        },
        credits: {
            enabled: false
        },
        navigator: {
            enabled: false
        },
        yAxis: [{
            labels: {
                align: 'right',
                x: -3
            },
            title: {
                text: 'OHLC'
            },
            height: '70%',
            lineWidth: 2,
            resize: {
                enabled: true
            }
        }, {
            labels: {
                align: 'right',
                x: -3
            },
            title: {
                text: __('Volume')
            },
            top: '72%',
            height: '20%',
            offset: 0,
            lineWidth: 2
        }],
        rangeSelector: {
            selected: 0,
            inputDateFormat: '%d.%m.%y',
            inputEditDateFormat: '%d.%m.%y',
            inputDateParser: function (value) {
                value = value.split(/[\.]/);
                return Date.UTC(
                    parseInt(value[2]),
                    parseInt(value[1]) - 1,
                    parseInt(value[0])
                );
            },
            buttons: [
                {
                    type: 'day',
                    count: 15,
                    text: '15d',
                },
                {
                    type: 'all',
                    text: __('All')
                }
            ]
        },
        title: {
            text: secondCoin + __(' Price')
        },
        plotOptions: {
            series: {
                turboThreshold: 0
            }
        },
        series: [{
            type: 'candlestick',
            dataGrouping: {
                units: [
                    [
                        'minute', // unit name
                        [1, 5, 30] // allowed multiples
                    ],
                    [
                        'day', // unit name
                        [1] // allowed multiples
                    ],
                    [
                        'week', // unit name
                        [1] // allowed multiples
                    ], [
                        'month',
                        [1, 2, 3, 4, 6]
                    ]
                ]
            }
        }, {
            type: 'column',
            yAxis: 1,
            dataGrouping: {
                units: [
                    [
                        'minute', // unit name
                        [1, 5, 30] // allowed multiples
                    ],
                    [
                        'day', // unit name
                        [1] // allowed multiples
                    ],
                    [
                        'week', // unit name
                        [1] // allowed multiples
                    ], [
                        'month',
                        [1, 2, 3, 4, 6]
                    ]
                ]
            }
        }],
        lang: {
            months: [
                '1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'
            ],
            weekdays: [
                '월요일', '화요일', '수요일', '목요일', '금요일', '토요일', '일요일'
            ]
        }
    }, function (chart) {
        // apply the date pickers
        setTimeout(function () {
            $('input.highcharts-range-selector', $(chart.container).parent())
                .datepicker();
        }, 0);
    });

    $( "#radioclick" ).click(function() {
        let isChecked = $('#radioclick').is(':checked');
        if(isChecked === true){
            $(".hide_currency").hide()
        }else{
            $(".hide_currency").show()
        }
    });

    updateCurrentPrice();

});

function callAllFunctions() {
    notCompletedOrderList();
    myOrderListAjax();
    marketHistory();
    getUserBalance();
    //getCurrenPrice();
    getLastTwentyFourHourTicker();
    getPairCurrentPrice();
    getUserCurrentBalance();
}

var myRealGraph;

function modalDialog(type,btn_text) {


    let perPrice = $("#"+type+"_per_price").val();
    let qty = $("#"+type+"_volume").val();
    let amount = $("#"+type+"_total_amount").val();

    $("span#buy_span_price").text(""+perPrice);
    $("span#buy_span_qty").text(""+qty);
    $("span#buy_span_amount").text(""+amount);

    $("#submit_type").val(type);
    $("#submit_per_price").val(perPrice);
    $("#submit_volume").val(qty);
    $('#submit_btn').attr('onclick',"formSubmit('"+type+"')");
    $('#submit_btn').removeClass();
    if(type == 'buy'){
        $('#submit_btn').addClass('btn_buy');
    } else {
        $('#submit_btn').addClass('btn_sell');
    }
    $('#submit_btn').text(btn_text);
}
function formSubmit(type){
    let formData = new FormData($('#buy_form')[0]);
    if(authUserId !== '') {
        let max_buysell_per = $("#max_buysell_per").val();
        let min_buysell_per = $("#min_buysell_per").val();
        let max_market_per = $("#max_market_per").val();
        let min_market_per = $("#min_market_per").val();
        let mid_night_price = $("#mid_night_price").val();
        let perPrice = $("#"+type+"_per_price").val();
        let perPrices = parseFloat(perPrice);
        //max_buysell_per
        let getTenPercentHighOfCurrentPrice = pairCurrentPrice * parseFloat(max_buysell_per) / 100;
        let maxPerPrice = parseFloat(pairCurrentPrice) + parseFloat(getTenPercentHighOfCurrentPrice);
        if (perPrices > maxPerPrice) {
            modal_alert('',"The daily upper limit price has been exceeded.");
            return false;
        }
        //min_buysell_per
        let getTenPercentLowOfCurrentPrice = pairCurrentPrice * parseFloat(min_buysell_per) / 100;
        let minPerPrice = parseFloat(pairCurrentPrice) - parseFloat(getTenPercentLowOfCurrentPrice);
        if (perPrices < minPerPrice) {
            modal_alert('',"The daily lower price has been exceeded");
            return false;
        }

        //max_market_per
        getTenPercentHighOfCurrentPrice = parseFloat(mid_night_price) * parseFloat(max_market_per) / 100;
        maxPerPrice = parseFloat(pairCurrentPrice) + parseFloat(getTenPercentHighOfCurrentPrice);
        if (perPrices > maxPerPrice) {
            modal_alert('',"The daily upper limit price has been exceeded.");
            return false;
        }

        //min_market_per
        getTenPercentLowOfCurrentPrice = parseFloat(mid_night_price) * parseFloat(min_market_per) / 100;
        minPerPrice = parseFloat(pairCurrentPrice) - parseFloat(getTenPercentLowOfCurrentPrice);
        if (perPrices < minPerPrice) {
            modal_alert('',"The daily lower price has been exceeded");
            return false;
        }

        $('#submit_btn').hide();
        // ajax for market History list
        $.ajax({
            beforeSend: function () {
                $('#show_buy_resp').html("<img src='/webroot/ajax-loader.gif' />");
            },
            url: '/front2/exchange/index/' + secondCoin + '/' + firstCoin,
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            success: function (resp) {
                $('#show_buy_resp').html(resp.message);
                let seconds = 100;
                //call to exchange
                if (resp.error === 0) {
                    callAjaxExchange(formData, type);
                    callAllFunctions();
                } else {
                    seconds = 3000;
                }
                $('#submit_btn').show();
                setTimeout(function () {
                    $('#show_buy_resp').html('');
                    $(".modal-header button").click();
                }, seconds);
                return;
            }
        });
    }
}

function toDecimals(id){
    let num = parseFloat($("#" + id).val());
    if(!isNaN(num)) {
        let cleanNum = num.toFixed(2);
        $("#" + id).val(cleanNum);
    }
}

function calAdminFee(totalAmt){
    let calFee = (totalAmt*fee)/100;
    calFee = parseFloat(calFee);
    if(!isNaN(calFee)){
        return calFee;
    }
    return '';
}

function calculateForm(thisId,exType) {
    let volume = $("#"+exType+"_volume").val();
    volume = parseFloat(volume);

    let totalAmt = $("#"+exType+"_total_amount").val();
    totalAmt = parseFloat(totalAmt);

    let perPrice = $("#"+exType+"_per_price").val();
    perPrice = parseFloat(perPrice);

    if(thisId === exType+"_volume" && !isNaN(perPrice)){
        // calculate total
        totalAmt = volume * perPrice;
        totalAmt = parseFloat(totalAmt);
        totalAmt = removeZeros(totalAmt);
        if(!isNaN(totalAmt)){
            $("#"+exType+"_total_amount").val(totalAmt);
            // calculate fee
            let calFee = calAdminFee(totalAmt);
            $("#"+exType+"_admin_fee").val(calFee);
        }
    }

    if(thisId === exType+"_per_price"){
        if(!isNaN(volume)){
            totalAmt = volume * perPrice;
            totalAmt = parseFloat(totalAmt);
            totalAmt = removeZeros(totalAmt);
            if(!isNaN(totalAmt)){
                $("#"+exType+"_total_amount").val(totalAmt);
                // calculate fee
                let calFee = calAdminFee(totalAmt);
                $("#"+exType+"_admin_fee").val(calFee);
            }
        }
        else {
            totalAmt = $("#"+exType+"_total_amount").val();
            volume = totalAmt/perPrice;
            volume = parseFloat(volume);
            if(!isNaN(volume)){
                if(volume !== 0){
                    $("#"+exType+"_volume").val(volume);
                }
                // calculate fee
                let calFee = calAdminFee(totalAmt);
                $("#"+exType+"_admin_fee").val(calFee);
            }
        }
    }

    if(thisId === exType+"_total_amount" && !isNaN(perPrice)){
        let totalAmt = $("#"+thisId).val();
        let volume = totalAmt/perPrice;
        volume = parseFloat(volume);
        if(!isNaN(volume)){
            if(volume !== 0){
                $("#"+exType+"_volume").val(volume);
            }
            // calculate fee
            let calFee = calAdminFee(totalAmt);
            $("#"+exType+"_admin_fee").val(calFee);
        }
    }
}

function getPairCurrentPrice(){
    $.ajax({
        url : "/front2/exchange/getPairCurrentPrice/"+firstCoinId,
        type : 'GET',
        dataType : 'json',
        success : function(resp){
            $.each(resp,function(getKey,getValData){
                let getVal = getValData.price;
                let getPricePercent = getValData.price_percent;
                getPricePercent = parseFloat(getPricePercent).toFixed(2);
                let isBinancePrice = getValData.binance;
                getVal = parseFloat(getVal).toFixed(2);
                let getValInThousands = numberWithCommas(getVal);
                $("#"+getKey).html(getValInThousands);
                let setPriceClass = (getPricePercent<0) ? "red" : "blue";
                let setPriceSign = (getPricePercent<0) ? "-" : "+";
                getPricePercent = parseFloat(getPricePercent).toFixed(2);
                $("#percent_"+getKey).html(setPriceSign+""+Math.abs(getPricePercent)+"%").removeClass("red").removeClass("blue").addClass(setPriceClass);
                if(getKey === "current_price_"+secondCoin+"_"+firstCoin){
                    $("#middle_only_price").html(getValInThousands);
                    $("#current_price").html(getValInThousands);
                    let buyCheckBox = $('input[value="buy_market_tab"]:checked').length;
                    if(buyCheckBox > 0){
                        $("#buy_per_price").val(getVal);
                    }
                    let sellCheckBox = $('input[value="sell_market_tab"]:checked').length;
                    if(sellCheckBox > 0){
                        $("#sell_per_price").val(getVal);
                    }
                }
            });
        }
    });
}

function setAmount(percent,type){
    let getBalanceAmt = parseFloat($("#span_"+type+"_volume_all").text().replaceAll(",",""));
    let volume = 0;
    let perPrice = 0;
    let totalAmount = 0;
    if(type == 'sell'){
        volume = getBalanceAmt * (parseFloat(percent)/100);
        perPrice = parseFloat($("#"+type+"_per_price").val());
        totalAmount = volume * perPrice;
    } else if (type == 'buy'){
        totalAmount = getBalanceAmt * (parseFloat(percent)/100);
        perPrice = parseFloat($("#"+type+"_per_price").val());
        volume = totalAmount / perPrice;
    }
    $("#"+type+"_volume").val(volume.toFixed(2));
    $("#"+type+"_total_amount").val(totalAmount.toFixed(2));
}

function fill_data(getTable,getTableType){
    let fillPerPrice = $(getTable).find("td.fill_per_price div").html();
    let fillAmount = $(getTable).find("td.fill_amount").html();
    fillPerPrice = fillPerPrice.replace(/,/g,'');
    let fillPerPrices = parseFloat(fillPerPrice);
    fillAmount = fillAmount.replace(/,/g,'');
    let max_buysell_per = $("#max_buysell_per").val();
    let min_buysell_per = $("#min_buysell_per").val();
    let max_market_per = $("#max_market_per").val();
    let min_market_per = $("#min_market_per").val();
    let mid_night_price = $("#mid_night_price").val();
    if(getTableType === "sell"){
        let getTenPercentHighOfCurrentPrice = pairCurrentPrice * parseFloat(max_buysell_per)/100;
        let maxPerPrice = parseFloat(pairCurrentPrice) + parseFloat(getTenPercentHighOfCurrentPrice);

        if(fillPerPrices > maxPerPrice){
            modal_alert('',"The daily upper limit price has been exceeded.");
            return false;
        }
        //min_buysell_per
        let getTenPercentLowOfCurrentPrice = pairCurrentPrice * parseFloat(min_buysell_per)/100;
        let minPerPrice = parseFloat(pairCurrentPrice) - parseFloat(getTenPercentLowOfCurrentPrice);
        if(fillPerPrices < minPerPrice){
            modal_alert('',"The daily lower price has been exceeded.");
            return false;
        }

        //max_market_per
        getTenPercentHighOfCurrentPrice = parseFloat(mid_night_price) * parseFloat(max_market_per)/100;
        maxPerPrice = parseFloat(pairCurrentPrice) + parseFloat(getTenPercentHighOfCurrentPrice);
        if(fillPerPrices > maxPerPrice){
            modal_alert('',"The daily upper limit price has been exceeded.");
            return false;
        }
        //min_market_per
        getTenPercentLowOfCurrentPrice = parseFloat(mid_night_price) * parseFloat(min_market_per)/100;
        minPerPrice = parseFloat(pairCurrentPrice) - parseFloat(getTenPercentLowOfCurrentPrice);
        if(fillPerPrices < minPerPrice){
            modal_alert('',"The daily lower price has been exceeded.");
            return false;
        }
        $("#sell_per_price").val(fillPerPrice).trigger("input");
        $("#buy_per_price").val(fillPerPrice).trigger("input");
        $("#profile-tab").click();
    }

    if(getTableType === "buy"){
        //max_buysell_per
        let getTenPercentHighOfCurrentPrice = pairCurrentPrice * max_buysell_per/100;
        let maxPerPrice = parseFloat(pairCurrentPrice) + parseFloat(getTenPercentHighOfCurrentPrice);
        if(fillPerPrices > maxPerPrice){
            modal_alert('',"The daily upper limit price has been exceeded.");
            return false;
        }
        //min_buysell_per
        let getTenPercentLowOfCurrentPrice = pairCurrentPrice * min_buysell_per/100;
        let minPerPrice = parseFloat(pairCurrentPrice) - parseFloat(getTenPercentLowOfCurrentPrice);
        if(fillPerPrices < minPerPrice){
            modal_alert('',"The daily lower price has been exceeded.");
            return false;
        }
        //max_market_per
        getTenPercentHighOfCurrentPrice = mid_night_price * max_market_per/100;
        maxPerPrice = parseFloat(pairCurrentPrice) + parseFloat(getTenPercentHighOfCurrentPrice);
        if(fillPerPrices > maxPerPrice){
            modal_alert('',"The daily upper limit price has been exceeded.");
            return false;
        }
        //min_market_per
        getTenPercentLowOfCurrentPrice = mid_night_price * min_market_per/100;
        minPerPrice = parseFloat(pairCurrentPrice) - parseFloat(getTenPercentLowOfCurrentPrice);
        if(fillPerPrices < minPerPrice){
            modal_alert('',"The daily lower price has been exceeded.");
            return false;
        }
        $("#sell_per_price").val(fillPerPrice).trigger("input");
        $("#buy_per_price").val(fillPerPrice).trigger("input");
        $("#home-tab").click();
    }
}

function fill_data_middle(){
    let fillPerPrice = $("#middle_only_price").html();
    fillPerPrice = fillPerPrice.replace(/,/g,'');
    $("#sell_per_price").val(fillPerPrice).trigger("input");
    $("#buy_per_price").val(fillPerPrice).trigger("input");
    $("#sell_volume").val(0);
    $("#buy_volume").val(0);
    $("#profile-tab").click();
}

function operation(opt,type) {
    let value1= $("#"+type+"_per_price").val();
    let value = "0";
    let bPrice = parseFloat(value1).toFixed(2);
    if(bPrice !== undefined && bPrice !== null && bPrice !== ''){
        let numDec = countDecimals(bPrice);
        if(numDec !== 0 && numDec === 1 || numDec === 2){
            $("#"+type+"_per_price").attr({step:'0.01',min:'0.01'});
            value = "0.01";
        } else if(numDec === 3){
            $("#"+type+"_per_price").attr({step:'0.1',min:'0.1'});
            value = "0.1";
        } else {
            $("#"+type+"_per_price").attr({step:'1.0',min:'1.0'});
            value = "1.0";
        }
    }
    if(value1 !== undefined && value1 !== null && value1 !== ''){
        if (opt === 'increment') {
            let new_value = parseFloat(value1)+parseFloat(value);
            $("#"+type+"_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
        }
        if (opt === 'decrement') {
            if(value1>0){
                let new_value=parseFloat(value1)-parseFloat(value);
                if(Math.sign(new_value) === -1){
                    new_value=0;
                }
                $("#"+type+"_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
            }
        }
    }else{
        let new_value = value;
        $("#"+type+"_per_price").val(parseFloat(new_value).toFixed(2)).trigger('input');
    }
}

function operationVol(opt,type,value) {
    let value1 = $("#"+type+"_volume").val();
    if(value1 !== undefined && value1 !== null && value1 !== ''){
        if (opt === 'increment'){
            let new_value = parseFloat(value1) + parseFloat(value);
            $("#"+type+"_volume").val(parseFloat(new_value).toFixed(1)).trigger('input');
        }
        if (opt === 'decrement'){
            if(value1>0){
                let new_value = parseFloat(value1) - parseFloat(value);
                if(Math.sign(new_value) === -1){
                    new_value = 0;
                }
                $("#"+type+"_volume").val(parseFloat(new_value).toFixed(1)).trigger('input');
            }
        }
    }else{
        let new_value = value;
        $("#"+type+"_volume").val(parseFloat(new_value).toFixed(1)).trigger('input');
    }
}

function clearBuyForm(){
    $("#buy_volume").val('');
    $("#buy_per_price").val('');
    $("#buy_total_amount").val('');
    $("#buy_admin_fee").val('');
    getUserCurrentBalance();
}

function clearSellForm(){
    $("#sell_volume").val('');
    $("#sell_per_price").val('');
    $("#sell_total_amount").val('');
    $("#sell_admin_fee").val('');
    getUserCurrentBalance();
}

function popup_hide(state){
    let user_id = $('#authUserId').val();
    $('#days_remaining_dialog').dialog('close');
    $('#days_remaining_dialog').hide();
    if(state === 1){
        if(getCookie('daysRemainingCookie_'+user_id) !== 'Y'){
            setCookie('daysRemainingCookie_'+user_id,'Y',1);
        }
    }
}

function popup_show(type){
    let user_id = $('#authUserId').val();
    if(type !== 'non_annual_membership' && type !== 'lot_remain'){
        if(getCookie('daysRemainingCookie_'+user_id) !== 'Y'){
            $('#days_remaining_dialog').dialog();
            $('#days_remaining_dialog').show();
        }
    }
}

function setGraph(){
    if(binancePrice === "N") {
        // ajax for market History list from DB
        $.ajax({
            url : '/front2/exchange/getGraphData/'+firstCoinId+'/'+secondCoinId,
            type : 'GET',
            dataType:'json',
            success : function(resp){
                let jsonData = [];
                let jsonDataVolume = [];
                if(resp.success === "true"){
                    $.each(resp.data,function(key,valE){
                        let ddt = [
                            parseFloat(valE.datecol+"000"),
                            parseFloat(valE.open_price),
                            parseFloat(valE.max_price),
                            parseFloat(valE.min_price),
                            parseFloat(valE.close_price)
                        ];
                        jsonData.push(ddt);
                        let setColorColumn = (parseFloat(valE.close_price) > parseFloat(valE.open_price)) ? '#0c45d5' : '#d80000';
                        jsonDataVolume.push({x:parseFloat(valE.datecol+"000"),y:parseFloat(valE.open_price),color: setColorColumn});
                    });
                    myRealGraph.series[0].setData(jsonData);
                    myRealGraph.series[1].setData(jsonDataVolume);
                }
            }
        });
    } else {
        var firstCoinForUrl = (firstCoin=="KRW") ? "B"+firstCoin : firstCoin;
        // ajax for market History list From Binance
        $.ajax({
            url: 'https://api.binance.com/api/v3/klines?symbol=' + secondCoin + firstCoinForUrl + '&interval=1m&limit=10000',
            type: 'GET',
            dataType: 'json',
            success: function (resp) {
                let jsonData = [];
                let jsonDataVolume = [];
                $.each(resp, function (key, valE) {
                    let ddt = [
                        parseFloat(valE[0]),
                        parseFloat(valE[1]),
                        parseFloat(valE[2]),
                        parseFloat(valE[3]),
                        parseFloat(valE[4])
                    ];
                    jsonData.push(ddt);
                    let setColorColumn = (parseFloat(valE[4]) > parseFloat(valE[1])) ? '#0c45d5' : '#d80000';
                    jsonDataVolume.push({x: parseFloat(valE[0]), y: parseFloat(valE[5]), color: setColorColumn});
                });
                myRealGraph.series[0].setData(jsonData);
                myRealGraph.series[1].setData(jsonDataVolume);
            }
        });
    }
}
// ajax for user balance
function checkExchange() {
    $.ajax({
        url : '/front2/exchange/checkExchange/'+firstCoinId+'/'+secondCoinId,
        type : 'get',
        //dataType : 'json',
        success : function(resp){
            if(resp === 1){
                callAllFunctions();
            }
        }
    });
}
function updateCurrentPrice() {
    $.ajax({
        url : '/front2/exchange/updateMyPrice/'+firstCoinId+'/'+secondCoinId,
        type : 'get',
        dataType : 'json',
        success : function(resp){

        }
    });
}

// ajax for user balance
function getUserBalance() {
    if(authUserId !== ''){
        $.ajax({
            url : '/front2/exchange/getUserBalance/'+firstCoinId+'/'+secondCoinId,
            type : 'get',
            dataType : 'json',
            success : function(resp){
                let firstCoinBalance = parseFloat(resp.firstCoinBalance).toFixed(2);
                let secondCoinBalance = parseFloat(resp.secondCoinBalance).toFixed(2);
                $("#span_buy_volume_all").val(firstCoinBalance);
                $("#span_sell_volume_all").val(secondCoinBalance);
            }
        });
    }
}

function getUserCurrentBalance() {
    if(authUserId !== ''){
        $.ajax({
            url : '/front2/exchange/getUserCurrentBalance/' + firstCoinId +'/'+secondCoinId,
            type : 'get',
            dataType : 'json',
            success : function(resp){
                let firstCoinBalance = numberWithCommas(parseFloat(resp.firstCoinsSum).toFixed(2));
                let secondCoinBalance = numberWithCommas(parseFloat(resp.secondCoinsSum).toFixed(2));
                $("span#span_buy_volume_all").text(""+firstCoinBalance);
                $("span#span_sell_volume_all").text(""+secondCoinBalance);
            }
        });
    } else {
        $("span#span_buy_volume_all").text("0");
        $("span#span_sell_volume_all").text("0");
    }
}

function callAjaxExchange(formData,requestType){
    if(authUserId !== '') {
        $.ajax({
            url : '/front2/exchange/exchange/'+ firstCoin + '/' + secondCoin,
            type : 'post',
            data : formData,
            contentType: false,
            //cache: false,
            processData:false,
            beforeSend: function(xhr){
                xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
            },
            success : function(resp){
                if(requestType ==='buy'){
                    clearBuyForm();
                }
                else {
                    clearSellForm();
                }
                callAllFunctions();
                $('#submit_btn').show();
            }
        });
    }
}

// ajax for market History list
function marketHistory() {
    if(binancePrice === "N") {
        $.ajax({
            url : '/front2/exchange/marketHistory/'+ firstCoinId + '/' +secondCoinId,
            type : 'get',
            dataType : 'json',
            success : function(resp){
                // my buyOrderList data
                var html = '';
                if($.isEmptyObject(resp)){
                    html = html + '<tr>';
                    html = html + "<td colspan=5>"+__('Order not available')+"</td>";
                    html = html + '</tr>';
                } else {
                    $.each(resp,function(key,value){
                        let sellPurchaseType = "";
                        let perPrice = "";
                        let sellPurchaseAmt = '';

                        if(value.get_cryptocoin_id === secondCoinId){
                            perPrice = (value.get_per_price);
                        } else {
                            perPrice = (value.spend_per_price);
                        }

                        if(value.get_cryptocoin_id === secondCoinId){
                            sellPurchaseAmt = (value.get_amount);
                        } else {
                            sellPurchaseAmt = (value.spend_amount);
                        }

                        let totalPrice = (sellPurchaseAmt*perPrice);
                        var splitDateTime = value.created_at;
                        var splitDateTime = splitDateTime.split("+");
                        var getdateTime = splitDateTime[0];
                        var newSplitTime = getdateTime.split("T");
                        var getdateTime = getdateTime.replace("T"," ");
                        let setColor = (value.extype === "buy") ? "blue " : "red";
                        html = html + '<tr>';
                        html = html + '<td class="left"><div class="bold">'+newSplitTime[0]+'</div>'+newSplitTime[1]+'</td>';
                        html = html + '<td class="left"><div class="'+setColor+'">'+numberWithCommas(parseFloat(perPrice).toFixed(2))+'</div></td>';
                        html = html + '<td class="right">'+numberWithCommas(parseFloat(totalPrice).toFixed(2))+'</td>';
                        html = html + '</tr>';
                    });
                }
                $(".market_history").html(html);
            }
        });
    } else {
        var firstCoinForUrl = (firstCoin=="KRW") ? "B"+firstCoin : firstCoin;
        $.ajax({
            url : 'https://api.binance.com/api/v3/trades?symbol=' + secondCoin + firstCoinForUrl,
            type : 'get',
            dataType : 'json',
            success : function(resp){
                resp = resp.reverse();
                // my buyOrderList data
                let html = '';
                if($.isEmptyObject(resp)){
                    html = html + '<tr>';
                    html = html + "<td colspan=5>" + __('Order not found') + "</td>";
                    html = html + '</tr>';
                }
                else {
                    $.each(resp,function(key,value){
                        let perPrice = value.price;
                        let setColor = (value.isBuyerMaker === false) ? "blue " : "red";
                        let totalPrice = value.quoteQty;
                        let myDate = new Date(value.time);
                        let showOnlyDate = myDate.getFullYear()  + "-" + (myDate.getMonth()+1) + "-" + myDate.getDate();
                        let showOnlyTime = myDate.getHours() + ":" + myDate.getMinutes()+ ":" + myDate.getSeconds();
                        html = html + '<tr>';
                        html = html + '<td class="left"><div class="bold">'+showOnlyDate+'</div>'+showOnlyTime+'</td>';
                        html = html + '<td class="left"><div class="'+setColor+'">'+numberWithCommas(parseFloat(perPrice).toFixed(2))+'</div></td>';
                        html = html + '<td class="right">'+numberWithCommas(parseFloat(totalPrice).toFixed(2))+'</td>';
                        html = html + '</tr>';
                    });
                }
                $(".market_history").html(html);
            }
        });
    }
}

// ajax for user balance
function getCurrenPrice() {
    $.ajax({
        url : '/front2/exchange/getCurrenPrice/'+ firstCoinId +'/' + secondCoinId,
        type : 'get',
        dataType : 'json',
        success : function(resp){
            if ($.isEmptyObject(resp.current_price)) {

            } else {
                let returnPrice = resp.current_price[0].get_per_price;
                returnPrice = parseFloat(returnPrice).toFixed(2);
                let returnPriceInThousands = numberWithCommas(returnPrice);
                let baseCoinPriceInUsd = $('#baseCoinPriceInUsd').val();
                let currentPriceInUsd = returnPrice * parseFloat(baseCoinPriceInUsd);
                currentPriceInUsd = parseFloat(currentPriceInUsd).toFixed(2);
                $("#current_price").html(returnPriceInThousands);
                pairCurrentPrice = returnPrice;
                let setHtml = (resp.goto === "down") ? "&#9660;" :"&#9650;";
                $("#middle_current_price").html('<tr onClick="fill_data_middle()"><td colspan="3"><div class="updown_rate"><span id="middle_only_price">'+returnPriceInThousands+'</span><span class="updown_arrow" style="font-size:16px; line-height: 1;">'+setHtml+'</span></div></td></tr>');
                $("#current_price_"+ secondCoin + "_" +firstCoin).html(returnPriceInThousands);
                $("#current_price_usd").html(currentPriceInUsd);
            }

            if(binancePrice === "N") {
                let newMyClass = (resp.change_in_one_day < 0) ? "red" : "blue";
                let newSignPrcNew = (resp.change_in_one_day < 0) ? "-" : "+";
                $("#change_in_one_day").html(newSignPrcNew+""+Math.abs(parseFloat(resp.change_in_one_day).toFixed(2))+"%").removeClass("blue").removeClass("red").addClass(newMyClass);
                // for current volume
                if($.isEmptyObject(resp.current_volume)){
                    $("#current_volume").html(0);
                }
                else {
                    let returnVolume = numberWithCommas(parseFloat(resp.current_volume).toFixed(2));
                    $("#current_volume").html(returnVolume);
                }

                if(resp.min_price==""){
                    $("#min_price").html('');
                }
                else {
                    let minPrice = numberWithCommas(parseFloat(resp.min_price).toFixed(2));
                    $("#min_price").html(minPrice);
                }
                //if($.isEmptyObject(resp.max_price)){
                if(resp.max_price==""){
                    $("#max_price").html('');
                }
                else {
                    let maxPrice = numberWithCommas(parseFloat(resp.max_price).toFixed(2));
                    $("#max_price").html(maxPrice);
                }
            }
        }
    });
}

function getLastTwentyFourHourTicker(){
    if(binancePrice === "Y") {
        var firstCoinForUrl = (firstCoin=="KRW") ? "B"+firstCoin : firstCoin;
        $.ajax({
            url : 'https://api.binance.com/api/v3/ticker/24hr?symbol='+ secondCoin + firstCoinForUrl,
            type : 'get',
            dataType : 'json',
            success : function(resp){
                let highPrice = numberWithCommas(parseFloat(resp.highPrice).toFixed(2));
                let lowPrice = numberWithCommas(parseFloat(resp.lowPrice).toFixed(2));
                let getMyVolume = numberWithCommas(parseFloat(resp.quoteVolume).toFixed(2));
                let getMyPercent = parseFloat(resp.priceChangePercent).toFixed(2);
                let newClass = (getMyPercent<0) ? "red" : "blue";
                let newSignPrc = (getMyPercent<0) ? "-" : "+";
                $("#current_volume").html(getMyVolume);
                $("#max_price").html(highPrice);
                $("#min_price").html(lowPrice);
                $("#change_in_one_day").html(newSignPrc+""+Math.abs(getMyPercent)+"%").removeClass("red").removeClass("blue").addClass(newClass);
            }
        });
    } else {
        getCurrenPrice();
    }
}

function notCompletedOrderList(){
    // ajax for get not completed order list of buy orders
    $.ajax({
        url : '/front2/exchange/notCompletedOrderListAjax/' + firstCoinId + '/' + secondCoinId,
        type : 'get',
        dataType : 'json',
        beforeSend : function(xhr){
            $("#buyAjaxData").html('<img src="/ajax-loader.gif" />');
            $("#sellAjaxData").html('<img src="/ajax-loader.gif" />');
        },
        success : function(resp){
            let html = '';
            let list_count = 0;
            let total_list_count = 10;
            if($.isEmptyObject(resp.buyOrderList)){
                html += '<tr>';
                html += "<td colspan=3>" + __('Order not found') + "</td>";
                html += '</tr>';
            }
            else {
                let buyOrderBalance = 0;
                list_count = 0;
                if(authUserType == 'A'){
                    total_list_count = resp.buyOrderList.length + 1;
                }
                $.each(resp.buyOrderList,function(key,value){
                    if(list_count < total_list_count){
                        html += '<tr onClick="fill_data(this,\'sell\')" style="cursor:pointer;">';
                        html += '<td class="fill_per_price"><div class="bold red">'+numberWithCommas(parseFloat(value.per_price).toFixed(2))+'</div></td>';
                        html += '<td class="fill_amount">'+numberWithCommas(parseFloat(value.sum).toFixed(2))+'</td>';
                        html += '<td class="right">'+numberWithCommas(parseFloat((parseFloat(value.per_price).toFixed(2))*(parseFloat(value.sum).toFixed(2))).toFixed(2))+'</td>';
                        html += '</tr>';
                        buyOrderBalance += parseFloat(value.per_price).toFixed(2)*parseFloat(value.sum).toFixed(2);
                    }
                    list_count++;
                });
                buyOrderBalance  = numberWithCommas(parseFloat(buyOrderBalance).toFixed(2));
                $("#buy_order_balance").html(buyOrderBalance + " KRW");
            }
            $("#buyAjaxData").html(html);
            // add data to sell table
            html = '';
            if($.isEmptyObject(resp.sellOrderList)){
                html += '<tr>';
                html += "<td colspan=3>" + __('Order not found') + "</td>";
                html += '</tr>';
            }
            else {
                let sellOrderBalance = 0;
                list_count = resp.sellOrderList.length - 10;
                if(authUserType == 'A'){
                    list_count = 0;
                }
                $.each(resp.sellOrderList,function(key,value){
                    if(list_count <= key){
                        html += '<tr onClick="fill_data(this,\'buy\')" style="cursor:pointer;">';
                        html += '<td class="fill_per_price"><div class="bold blue">'+numberWithCommas(parseFloat(value.per_price).toFixed(2))+'</div></td>';
                        html += '<td class="fill_amount">'+numberWithCommas(parseFloat(value.sum).toFixed(2))+'</td>';
                        html += '<td class="right">'+numberWithCommas(parseFloat((parseFloat(value.per_price).toFixed(2))*(parseFloat(value.sum).toFixed(2))).toFixed(2))+'</td>';
                        html += '</tr>';
                        sellOrderBalance += parseFloat(value.per_price).toFixed(2)*parseFloat(value.sum).toFixed(2);
                    }
                });
                sellOrderBalance  = numberWithCommas(parseFloat(sellOrderBalance).toFixed(2));
                $("#sell_order_balance").html(sellOrderBalance + " KRW");
            }
            $("#sellAjaxData").html(html);
            let objDiv = document.getElementById("sell_order_show_div");
            if(objDiv!= null){
                objDiv.scrollTop = objDiv.scrollHeight;
            }
        }
    });
}

function myOrderListAjax(){
    if(authUserId !== '') {
        // ajax for myOrder list
        $.ajax({
            url: '/front2/exchange/myOrderListAjax/'+ firstCoinId + '/' + secondCoinId,
            type: 'get',
            dataType: 'json',
            success: function (resp) {
                // my buyOrderList data
                var html = '';
                if ($.isEmptyObject(resp.myBuyOrderList)) {
                    html += '<tr>';
                    html += "<td colspan=6>" + __('There is no transaction history.') + "</td>";
                    html += '</tr>';
                } else {
                    $.each(resp.myBuyOrderList, function (key, value) {
                        let action = '&nbsp;';
                        let showAmount = numberWithCommas(parseFloat(value.total_buy_get_amount).toFixed(2));
                        if (value.status === 'pending') {
                            action = "<a class='button sell sell_ntr' href='javascript:void(0)' id='buy_" + value.id + "' onClick='deleteOrder(this.id)'>" + __('Cancel') + "</a>";
                            showAmount = numberWithCommas(parseFloat(value.buy_get_amount).toFixed(2));
                        }
                        if (value.status === 'deleted') {

                        }
                        let created_at = value.created_at.split("+")[0].replace("T"," ");
                        let status = ucfirst(value.status);
                        html += '<tr>';
                        html += '<td>' + created_at + '</td>';
                        html += '<td>' + numberWithCommas(parseFloat(value.per_price).toFixed(2)) + '</td>';
                        html += '<td>' + showAmount + '</td>';
                        if (parseFloat(value.buy_get_amount) === 0.0) {
                            html += '<td>' + numberWithCommas(parseFloat((parseFloat(value.per_price).toFixed(2)) * (parseFloat(value.total_buy_get_amount).toFixed(2))).toFixed(2)) + '</td>';
                        } else {
                            html += '<td>' + numberWithCommas(parseFloat((parseFloat(value.per_price).toFixed(2)) * (parseFloat(value.buy_get_amount).toFixed(2))).toFixed(2)) + '</td>';
                        }

                        if (status === 'Completed') {
                            html += '<td>' + __('Completed') + '</td>';
                        } else if (status === 'Pending') {
                            html += '<td>' + __('Pending') + '</td>';
                        } else if (status === 'Deleted') {

                        } else {
                            html += '<td>&nbsp;</td>'
                        }
                        html += '<td>' + action + '</td>';
                        html += '</tr>';
                    });
                }
                //$.fn.dataTable.ext.errMode = 'none';
                $("#ajaxdata123").dataTable().fnDestroy();
                $("#myBuyOrderlist").html(html);
                $('#ajaxdata123').DataTable({
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
                        "url": "http://testexchange.hansbiotech.kr/datatable_language/" + datatable_language + ".json"
                    }
                });

                // my seller order list data
                html = '';
                if ($.isEmptyObject(resp.mySellOrderList)) {
                    html += '<tr>';
                    html += "<td colspan=6>" + __('Transaction history is not available') + "</td>";
                    html += '</tr>';
                } else {
                    $.each(resp.mySellOrderList, function (key, value) {
                        let action = '&nbsp;';
                        let showAmount = numberWithCommas(parseFloat(value.total_sell_get_amount).toFixed(2));
                        if (value.status === 'pending') {
                            action = "<a class='button sell sell_ntr' style='background-color: #0c45d5;' href='javascript:void(0)' id='sell_" + value.id + "' onClick='deleteOrder(this.id)'>" + __('Cancel') + "</a>";
                            showAmount = numberWithCommas(parseFloat(value.sell_get_amount).toFixed(2));
                        }
                        if (value.status === 'deleted') {

                        }
                        var created_at = value.created_at.split("+")[0].replace("T"," ");
                        var status = ucfirst(value.status);
                        html += '<tr>';
                        html += '<td>' + created_at + '</td>';
                        html += '<td>' + numberWithCommas(parseFloat(value.per_price).toFixed(2)) + '</td>';
                        html += '<td>' + numberWithCommas(parseFloat(parseFloat(value.sell_get_amount).toFixed(2) / parseFloat(value.per_price).toFixed(2)).toFixed(2)) + '</td>';
                        html += '<td>' + numberWithCommas(parseFloat(value.sell_get_amount).toFixed(2)) + '</td>';
                        if (status === 'Completed') {
                            html += '<td>' + __('Completed') + '</td>';
                        } else if (status === 'Pending') {
                            html += '<td>' + __('Pending') + '</td>';
                        } else if (status === 'Deleted') {

                        } else {
                            html += '<td>&nbsp;</td>'
                        }
                        html += '<td>' + action + '</td>';
                        html += '</tr>';
                    });
                }
                //let checkDisplay = $("#sale_li_div").css("display");
                //if (checkDisplay !== "none") {
                $("#mySellOrderlist_table").dataTable().fnDestroy();
                $("#mySellOrderlist").html(html);
                $('#mySellOrderlist_table').DataTable({
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
                        "url": "http://testexchange.hansbiotech.kr/datatable_language/" + datatable_language + ".json"
                    }
                });
                //} else {
                //$("#mySellOrderlist").html(html);
                //}

            }
        });
    }
}

function deleteOrder(getId){
    if(authUserId !== '') {
        if (confirm("Are you really want to delete this ?")) {
            //	$("#"+getId).remove();
            $("#" + getId).closest('tr').remove();
            let splitId = getId.split("_");
            let tableType = splitId[0];
            let tableId = splitId[1];

            $.ajax({
                url: "/front2/exchange/deleteMyOrder/" + tableId + "/" + tableType,
                type: 'post',
                dataType: 'json',
                beforeSend: function(xhr){
                    xhr.setRequestHeader('X-CSRF-Token', $("input[name='_csrfToken']").val());
                },
                success: function (resp) {
                }
            });
        }
    }
}