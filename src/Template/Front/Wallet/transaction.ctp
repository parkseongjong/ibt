<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1> BTC Transaction <small>Detail</small> </h1>
        <ol class="breadcrumb">
            <li><a href="index.html"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">BTC Transaction</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="content" class="table-layout">
        <div class="tray tray-center" style="height: 726px;">
            <style>
                .export_border{
                    border: 1px dotted #b5b5b5;
                    padding: 1px;
                }
            </style>
            <div class="">
                <div class="col-lg-12 col-xl-10 center-block ">
                    <h4>BTC Transaction Detail</h4>
                    <div class="tab-block mb25" style="position: relative">
                        <ul class="nav nav-tabs tabs-bg">
                            <li class="active"> <a href="#main_wallet_panel" data-toggle="tab" id="main_wallet_transaction_anchor" aria-expanded="true"> Bitconnect <span class="hidden-xs-custom">wallet</span></a> </li>
                            <li class=""> <a href="#bitcoin_wallet_penel" data-toggle="tab" id="bitcoin_wallet_transaction_anchor" aria-expanded="false"> Bitcoin <span class="hidden-xs-custom">wallet</span></a> </li>
                            <li class=""> <a href="#lending_wallet_penel" data-toggle="tab" id="lending_wallet_transaction_anchor" aria-expanded="false"> Lending <span class="hidden-xs-custom">wallet</span></a> </li>
                            <li class=""> <a href="#bitcoin_cash_wallet_penel" data-toggle="tab" id="bitcoin_cash_wallet_transaction_anchor" aria-expanded="false"> Bitcoin Cash <span class="hidden-xs-custom">wallet</span></a> </li>
                        </ul>
                        <div class="tab-content pn-h-small">
                            <div id="main_wallet_panel" class="tab-pane active">
                                <div style="display: flow-root;">
                                    <select id="main_wallet_per_page">
                                        <option value="10">10</option>
                                        <option value="25" selected="">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="250">250</option>
                                        <option value="500">500</option>
                                        <option value="1000">1000</option>
                                    </select>
                                    <select id="main_wallet_display">
                                        <option value="all" selected="">All</option>
                                        <option value="RFTP">Received from third party</option>
                                        <option value="STTP">Sent to third party</option>
                                        <option value="PFLB">Paid for lend</option>
                                        <option value="RFLW">Received from lending wallet</option>
                                        <option value="STLW">Sent to lending wallet</option>
                                        <option value="BCC_BUY">BCC Buy</option>
                                        <option value="BCC_SELL">BCC Sell</option>
                                    </select>
                                    <div class="fr export_border">
                                        <form action="#" method="post" accept-charset="utf-8" id="export_bitconnect_wallet_data_form" class="">
                                            <div style="display:none">
                                                <input name="ci_csrf_token" value="26397bc206dffb797e8076d2f533b81d" type="hidden">
                                            </div>
                                            <div style="display: inline-table;">
                                                <select id="export_year_id" class="export_filter" name="export_year" onchange="if($(this).val().length!=0){$('#bitconnect_wallet_export_error').hide();}">
                                                    <option value="">Year</option>
                                                    <option value="2017" selected="">2017</option>
                                                    <option value="2016">2016</option>
                                                </select>
                                                <select id="export_month_id" class="export_filter" name="export_month" onchange="if($(this).val().length!=0){$('#bitconnect_wallet_export_error').hide();}">
                                                    <option value="">Month</option>
                                                    <option value="12">12</option>
                                                    <option value="11" selected="">11</option>
                                                    <option value="10">10</option>
                                                    <option value="9">09</option>
                                                    <option value="8">08</option>
                                                    <option value="7">07</option>
                                                    <option value="6">06</option>
                                                    <option value="5">05</option>
                                                    <option value="4">04</option>
                                                    <option value="3">03</option>
                                                    <option value="2">02</option>
                                                    <option value="1">01</option>
                                                </select>
                                                <select id="bitconnect_wallet_option_export" class="export_filter" name="bitconnect_wallet_option_export" onchange="if($(this).val().length!=0){$('#bitconnect_wallet_export_error').hide();}">
                                                    <option value="" selected="">Select</option>
                                                    <option value="RFTP">Received from third party</option>
                                                    <option value="STTP">Sent to third party</option>
                                                    <option value="PFLB">Paid for lend</option>
                                                    <option value="RFLW">Received from lending wallet</option>
                                                    <option value="STLW">Sent to lending wallet</option>
                                                    <option value="BCC_BUY">BCC Buy</option>
                                                    <option value="BCC_SELL">BCC Sell</option>
                                                </select>
                                                <br>
                                                <span class="text-red dn" id="bitconnect_wallet_export_error">Please select an option.</span> </div>
                                            <button type="submit" name="export_bitconnect_wallet_data" class="btn btn-success btn-xs" style="margin-top: -4px" onclick=""><i class="fa fa-download"></i> Export</button>
                                        </form>
                                    </div>
                                </div>
                                <h6 class="w100p text-center text-black" id="bcc_deposit_withdrawal_total_div">
                                    <button type="button" class="btn btn-sm btn-default" id="bcc_deposit_withdrawal_total_btn"> <i class="fa progress-icon"></i> Get BCC Deposit/Withdrawal total </button>
                                </h6>
                                <div id="main_wallet_transaction_div" class="mt10">
                                    <div class="table-responsive">
                                        <table table="" class="table table-striped table-hover" style="color: black" width="100%" cellspacing="0">
                                            <thead>
                                            <tr role="row">
                                                <th class="va-m cp"></th>
                                                <th title="sort" onclick="sort_order_main_wallet('created_on','asc')" class="va-m cp"> Date
                                                    <div class="dib ml10"><i class="fa fa-caret-up fa-lg text-primary sort-trans-first"></i><i class="fa fa-lg fa-caret-down text-off-grey"></i></div>
                                                    <br>
                                                    <span class="fs10">(local time)</span></th>
                                                <th title="sort" onclick="sort_order_main_wallet('received_bitcoin+0','desc')" class="va-m cp"> Received
                                                    <div class="dib ml10"><i class="fa fa-caret-up fa-lg text-off-grey sort-trans-first"></i><i class="fa fa-lg fa-caret-down text-off-grey"></i></div></th>
                                                <th title="sort" onclick="sort_order_main_wallet('sent_bitcoin+0','desc')" class="va-m cp"> Sent
                                                    <div class="dib ml10"><i class="fa fa-caret-up fa-lg text-off-grey sort-trans-first"></i><i class="fa fa-lg fa-caret-down text-off-grey"></i></div></th>
                                                <th title="sort" onclick="sort_order_main_wallet('description','desc')" class="va-m cp"> Description
                                                    <div class="dib ml10"><i class="fa fa-caret-up fa-lg text-off-grey sort-trans-first"></i><i class="fa fa-lg fa-caret-down text-off-grey"></i></div></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td colspan="6"><div class="w100p text-center p20">No records found.</div></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="bitcoin_wallet_penel" class="tab-pane">
                                <div style="display: flow-root;">
                                    <select id="bitcoin_wallet_per_page">
                                        <option value="10">10</option>
                                        <option value="25" selected="">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="250">250</option>
                                        <option value="500">500</option>
                                        <option value="1000">1000</option>
                                    </select>
                                    <select id="bitcoin_wallet_display">
                                        <option value="all" selected="">All</option>
                                        <option value="RFTP">Received from third party</option>
                                        <option value="STTP">Sent to third party</option>
                                        <option value="BCC_BUY">BCC Buy</option>
                                        <option value="BCC_SELL">BCC Sell</option>
                                    </select>
                                    <div class="fr export_border">
                                        <form action="https://bitconnect.co/user/transaction/export_bitcoin_wallet_data" method="post" accept-charset="utf-8" id="export_bitcoin_wallet_data_form" class="">
                                            <div style="display:none">
                                                <input name="ci_csrf_token" value="26397bc206dffb797e8076d2f533b81d" type="hidden">
                                            </div>
                                            <div style="display: inline-table;">
                                                <select id="export_year_id" class="export_filter" name="export_year" onchange="if($(this).val().length!=0){$('#bitcoin_wallet_export_error').hide();}">
                                                    <option value="">Year</option>
                                                    <option value="2017" selected="">2017</option>
                                                    <option value="2016">2016</option>
                                                </select>
                                                <select id="export_month_id" class="export_filter" name="export_month" onchange="if($(this).val().length!=0){$('#bitcoin_wallet_export_error').hide();}">
                                                    <option value="">Month</option>
                                                    <option value="12">12</option>
                                                    <option value="11" selected="">11</option>
                                                    <option value="10">10</option>
                                                    <option value="9">09</option>
                                                    <option value="8">08</option>
                                                    <option value="7">07</option>
                                                    <option value="6">06</option>
                                                    <option value="5">05</option>
                                                    <option value="4">04</option>
                                                    <option value="3">03</option>
                                                    <option value="2">02</option>
                                                    <option value="1">01</option>
                                                </select>
                                                <select id="bitcoin_wallet_option_export" class="export_filter" name="bitcoin_wallet_option_export" onchange="if($(this).val().length!=0){$('#bitcoin_wallet_export_error').hide();}">
                                                    <option value="" selected="">Select</option>
                                                    <option value="RFTP">Received from third party</option>
                                                    <option value="STTP">Sent to third party</option>
                                                    <option value="BCC_BUY">BCC Buy</option>
                                                    <option value="BCC_SELL">BCC Sell</option>
                                                </select>
                                                <br>
                                                <span class="text-red dn" id="bitcoin_wallet_export_error">Please select an option.</span> </div>
                                            <button type="submit" name="export_bitcoin_wallet_data" class="btn btn-success btn-xs" style="margin-top: -4px" onclick=""><i class="fa fa-download"></i> Export</button>
                                        </form>
                                    </div>
                                </div>
                                <h6 class="w100p text-center text-black" id="btc_deposit_withdrawal_total_div">
                                    <button type="button" class="btn btn-sm btn-default" id="btc_deposit_withdrawal_total_btn"> <i class="fa  progress-icon"></i> Get BTC Deposit/Withdrawal total </button>
                                </h6>
                                <div id="bitcoin_wallet_transaction_div" class="mt10">
                                    <div class="table-responsive">
                                        <table table="" class="table table-striped table-hover" style="color: black" width="100%" cellspacing="0">
                                            <thead>
                                            <tr role="row">
                                                <th class="va-m cp"></th>
                                                <th title="sort" onclick="sort_order_bitcoin_wallet('created_on','asc')" class="va-m cp"> Date
                                                    <div class="dib ml10"><i class="fa fa-caret-up fa-lg text-primary sort-trans-first"></i><i class="fa fa-lg fa-caret-down text-off-grey"></i></div>
                                                    <br>
                                                    <span class="fs10">(local time)</span></th>
                                                <th title="sort" onclick="sort_order_bitcoin_wallet('received_bitcoin+0','desc')" class="va-m cp"> Received
                                                    <div class="dib ml10"><i class="fa fa-caret-up fa-lg text-off-grey sort-trans-first"></i><i class="fa fa-lg fa-caret-down text-off-grey"></i></div></th>
                                                <th title="sort" onclick="sort_order_bitcoin_wallet('sent_bitcoin+0','desc')" class="va-m cp"> Sent
                                                    <div class="dib ml10"><i class="fa fa-caret-up fa-lg text-off-grey sort-trans-first"></i><i class="fa fa-lg fa-caret-down text-off-grey"></i></div></th>
                                                <th title="sort" onclick="sort_order_bitcoin_wallet('description','desc')" class="va-m cp"> Description
                                                    <div class="dib ml10"><i class="fa fa-caret-up fa-lg text-off-grey sort-trans-first"></i><i class="fa fa-lg fa-caret-down text-off-grey"></i></div></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td colspan="5"><div class="w100p text-center p20">No records found.</div></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="lending_wallet_penel" class="tab-pane">
                                <div style="display: flow-root;">
                                    <select id="lending_wallet_per_page">
                                        <option value="10">10</option>
                                        <option value="25" selected="">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="250">250</option>
                                        <option value="500">500</option>
                                        <option value="1000">1000</option>
                                    </select>
                                    <select id="lending_wallet_display">
                                        <option value="all" selected="">All</option>
                                        <option value="lent">Lent</option>
                                        <option value="profit">Profit</option>
                                        <option value="sent">Sent</option>
                                        <option value="received">Received</option>
                                        <option value="capital_released">Capital Released</option>
                                    </select>
                                    <div class="fr export_border">
                                        <form action="https://bitconnect.co/user/transaction/export_lending_wallet_data" method="post" accept-charset="utf-8" id="export_lending_wallet_data_form" class="">
                                            <div style="display:none">
                                                <input name="ci_csrf_token" value="26397bc206dffb797e8076d2f533b81d" type="hidden">
                                            </div>
                                            <div style="display: inline-table;">
                                                <select id="export_year_id" class="export_filter" name="export_year" onchange="if($(this).val().length!=0){$('#lending_wallet_export_error').hide();}">
                                                    <option value="">Year</option>
                                                    <option value="2017" selected="">2017</option>
                                                    <option value="2016">2016</option>
                                                </select>
                                                <select id="export_month_id" class="export_filter" name="export_month" onchange="if($(this).val().length!=0){$('#lending_wallet_export_error').hide();}">
                                                    <option value="">Month</option>
                                                    <option value="12">12</option>
                                                    <option value="11" selected="">11</option>
                                                    <option value="10">10</option>
                                                    <option value="9">09</option>
                                                    <option value="8">08</option>
                                                    <option value="7">07</option>
                                                    <option value="6">06</option>
                                                    <option value="5">05</option>
                                                    <option value="4">04</option>
                                                    <option value="3">03</option>
                                                    <option value="2">02</option>
                                                    <option value="1">01</option>
                                                </select>
                                                <select id="lending_wallet_option_export" class="export_filter" name="lending_wallet_option_export" onchange="if($(this).val().length!=0){$('#lending_wallet_export_error').hide();}">
                                                    <option value="" selected="">Select</option>
                                                    <option value="lent">Lent</option>
                                                    <option value="profit">Profit</option>
                                                    <option value="sent">Sent</option>
                                                    <option value="capital_released">Capital Released</option>
                                                </select>
                                                <br>
                                                <span class="text-red dn" id="lending_wallet_export_error">Please select an option.</span> </div>
                                            <button type="submit" name="export_lending_wallet_data" class="btn btn-success btn-xs" style="margin-top: -4px" onclick=""><i class="fa fa-download"></i> Export</button>
                                        </form>
                                    </div>
                                </div>
                                <div id="lending_wallet_transaction_div" class="mt10">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover" style="color: black" width="100%" cellspacing="0">
                                            <thead>
                                            <tr>
                                                <th class="va-m"></th>
                                                <th title="sort" onclick="sort_order_lending_wallet('created_on','asc')" class="va-m cp"> Date
                                                    <div class="dib ml10"><i class="fa fa-caret-up fa-lg text-primary sort-trans-first"></i><i class="fa fa-lg fa-caret-down text-off-grey"></i></div>
                                                    <br>
                                                    <span class="fs10">(local time)</span></th>
                                                <th title="sort" onclick="sort_order_lending_wallet('received_dollar+0','desc')" class="va-m cp"> Received Dollar
                                                    <div class="dib ml10"><i class="fa fa-caret-up fa-lg text-off-grey sort-trans-first"></i><i class="fa fa-lg fa-caret-down text-off-grey"></i></div></th>
                                                <th title="sort" onclick="sort_order_lending_wallet('sent_dollar+0','desc')" class="va-m cp"> Sent Dollar
                                                    <div class="dib ml10"><i class="fa fa-caret-up fa-lg text-off-grey sort-trans-first"></i><i class="fa fa-lg fa-caret-down text-off-grey"></i></div></th>
                                                <th title="sort" onclick="sort_order_lending_wallet('description','desc')" class="va-m cp"> Description
                                                    <div class="dib ml10"><i class="fa fa-caret-up fa-lg text-off-grey sort-trans-first"></i><i class="fa fa-lg fa-caret-down text-off-grey"></i></div></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td colspan="5"><div class="w100p text-center p20">No records found.</div></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div id="bitcoin_cash_wallet_penel" class="tab-pane">
                                <div style="display: flow-root;">
                                    <select id="bitcoin_cash_wallet_per_page">
                                        <option value="10">10</option>
                                        <option value="25" selected="">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="250">250</option>
                                        <option value="500">500</option>
                                        <option value="1000">1000</option>
                                    </select>
                                    <select id="bitcoin_cash_wallet_display">
                                        <option value="all" selected="">All</option>
                                        <option value="RFTP">Received from third party</option>
                                        <option value="STTP">Sent to third party</option>
                                    </select>
                                    <div class="fr export_border">
                                        <form action="https://bitconnect.co/user/transaction/export_bitcoin_cash_wallet_data" method="post" accept-charset="utf-8" id="export_bitcoin_cash_wallet_data_form" class="">
                                            <div style="display:none">
                                                <input name="ci_csrf_token" value="26397bc206dffb797e8076d2f533b81d" type="hidden">
                                            </div>
                                            <div style="display: inline-table;">
                                                <select id="export_year_id" class="export_filter" name="export_year" onchange="if($(this).val().length!=0){$('#bitcoin_cash_wallet_export_error').hide();}">
                                                    <option value="">Year</option>
                                                    <option value="2017" selected="">2017</option>
                                                    <option value="2016">2016</option>
                                                </select>
                                                <select id="export_month_id" class="export_filter" name="export_month" onchange="if($(this).val().length!=0){$('#bitcoin_cash_wallet_export_error').hide();}">
                                                    <option value="">Month</option>
                                                    <option value="12">12</option>
                                                    <option value="11" selected="">11</option>
                                                    <option value="10">10</option>
                                                    <option value="9">09</option>
                                                    <option value="8">08</option>
                                                    <option value="7">07</option>
                                                    <option value="6">06</option>
                                                    <option value="5">05</option>
                                                    <option value="4">04</option>
                                                    <option value="3">03</option>
                                                    <option value="2">02</option>
                                                    <option value="1">01</option>
                                                </select>
                                                <select id="bitcoin_cash_wallet_option_export" class="export_filter" name="bitcoin_cash_wallet_option_export" onchange="if($(this).val().length!=0){$('#bitcoin_cash_wallet_export_error').hide();}">
                                                    <option value="" selected="">Select</option>
                                                    <option value="RFTP">Received from third party</option>
                                                    <option value="STTP">Sent to third party</option>
                                                </select>
                                                <br>
                                                <span class="text-red dn" id="bitcoin_cash_wallet_export_error">Please select an option.</span> </div>
                                            <button type="submit" name="export_bitcoin_cash_wallet_data" class="btn btn-success btn-xs" style="margin-top: -4px" onclick=""><i class="fa fa-download"></i> Export</button>
                                        </form>
                                    </div>
                                </div>
                                <h6 class="w100p text-center text-black" id="bch_deposit_withdrawal_total_div">
                                    <button type="button" class="btn btn-sm btn-default" id="bch_deposit_withdrawal_total_btn"> <i class="fa  progress-icon"></i> Get BCH Deposit/Withdrawal total </button>
                                </h6>
                                <div id="bitcoin_cash_wallet_transaction_div" class="mt10">
                                    <div class="table-responsive">
                                        <table table="" class="table table-striped table-hover" style="color: black" width="100%" cellspacing="0">
                                            <thead>
                                            <tr role="row">
                                                <th class="va-m cp"></th>
                                                <th title="sort" onclick="sort_order_bitcoin_cash_wallet('created_on','asc')" class="va-m cp"> Date
                                                    <div class="dib ml10"><i class="fa fa-caret-up fa-lg text-primary sort-trans-first"></i><i class="fa fa-lg fa-caret-down text-off-grey"></i></div>
                                                    <br>
                                                    <span class="fs10">(local time)</span></th>
                                                <th title="sort" onclick="sort_order_bitcoin_cash_wallet('received+0','desc')" class="va-m cp"> Received
                                                    <div class="dib ml10"><i class="fa fa-caret-up fa-lg text-off-grey sort-trans-first"></i><i class="fa fa-lg fa-caret-down text-off-grey"></i></div></th>
                                                <th title="sort" onclick="sort_order_bitcoin_cash_wallet('sent+0','desc')" class="va-m cp"> Sent
                                                    <div class="dib ml10"><i class="fa fa-caret-up fa-lg text-off-grey sort-trans-first"></i><i class="fa fa-lg fa-caret-down text-off-grey"></i></div></th>
                                                <th title="sort" onclick="sort_order_bitcoin_cash_wallet('description','desc')" class="va-m cp"> Description
                                                    <div class="dib ml10"><i class="fa fa-caret-up fa-lg text-off-grey sort-trans-first"></i><i class="fa fa-lg fa-caret-down text-off-grey"></i></div></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td colspan="5"><div class="w100p text-center p20">No records found.</div></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bs-component mt10">
                        <div class="panel-group accordion" id="accordion">
                            <div class="panel" style="border: 1px solid #c2c8d8;">
                                <div class="panel-heading" style="height: inherit"> <a class="accordion-toggle accordion-icon link-unstyled collapsed" data-toggle="collapse" data-parent="#accordion" href="#accord1" style="border-radius: 0px;background-color:transparent;color:black">
                                        <table class="table ">
                                            <thead>
                                            <tr>
                                                <td><strong class="text-black "><span class="dib">Total volume : </span><span class="dib"><i class="fa fa-dollar"></i> 0</span></strong></td>
                                                <td><strong class="text-black">Level Bonus</strong></td>
                                                <td><strong class="text-black"> <span class="dib"><i class="fa fa-dollar"></i> 0.00</span> <span class="dib">( <i class="fa fa-bitcoin"></i> 0.00000000)</span></strong></td>
                                            </tr>
                                            </thead>
                                        </table>
                                    </a> </div>
                                <div id="accord1" class="panel-collapse collapse ">
                                    <div class="panel-body" style="margin-top: 0">
                                        <div class="table-responsive">
                                            <div class="w100p text-center p20">No Level Bonus Found.</div>
                                            <table class="table " id="address_data">
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                .pagination{
                    display: initial;
                }
                .bg-deleted{
                    background-color: #CCCCCC;
                }
                .cp{
                    cursor: pointer;
                }
                #content .panel-group.accordion .panel .panel-heading a:not(.collapsed) {
                    background-color: #ffc42a;
                }
                .sort-trans-first{
                    display: block;
                    margin-bottom: -6px
                }
                .text-off-grey {
                    color: #cacaca!important;
                }
            </style>
        </div>
    </section>