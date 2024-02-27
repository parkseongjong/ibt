<div class="container">
  <div class="custom_frame">
    <div class="menu_left" id="coin-list">
			<h1>Coin Info</h1>
			<p class="item">BTC</p>
			<p class="item">ETH</p>
			<p class="item">USDT</p>
			<p class="item">BNB</p>
			<p class="item">TP3</p>
			<p class="item">CTC</p>
			<p class="item">MC</p>
		</div>

    <div class="frame-right contents">
      <h1 id="currentSelectedCoinName">BTC</h1>
      <section class="characteristic">
        <h3 class="section-title">Characteristic</h3>
        <p id="characteristic"></p>
      </section>
      <section class="value">
        <h3 class="section-title">Value</h3>
        <p id="value"></p>
      </section>
      <section class="remark">
        <hr />
        <p id="remark"></p>
      </section>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  console.log('loaded');
  init();
})

function init() {
  addEventListeners();
}

function addEventListeners() {
  const coinInfoList = document.querySelector('#coin-list').querySelectorAll('.item');
  if (!coinInfoList || coinInfoList.length <= 0) {
    console.error('not found coinInfoList');
    return;
  }
  console.log(coinInfoList);
  onClickCoinInfoItem(coinInfoList[0]);
  coinInfoList.forEach(function(item) {
    item.addEventListener('click', function(event) {
      onClickCoinInfoItem(event.target);
    });
  });
}

function onClickCoinInfoItem(targetNode) {
  console.log('targetNode? ', targetNode);
  targetText = targetNode.innerText.toLowerCase();
  console.log('targetText? ', targetText);

  const characteristic = document.querySelector('#characteristic');
  const value = document.querySelector("#value");
  const remark = document.querySelector("#remark");

  const targetData = coinInfoData[targetText];
  if (!targetData) {
    console.error("failed load coinInfoData");
    return;
  }

  characteristic.innerText = targetData.characteristic;
  value.innerText = targetData.value;
  remark.innerText = targetData.remark;

  // set on class
  const coinInfoList = document.querySelector('#coin-list').querySelectorAll('.item');
  coinInfoList.forEach(function(item) {
    item.classList.remove('on');
  })
  targetNode.classList.add('on');

  // set currentSelectedCoinName
  document.querySelector('#currentSelectedCoinName').innerText = targetNode.innerText;
}



const coinInfoData = {
  btc: {
    characteristic: `
      The first coin implemented among cryptocurrencies
      There is no central management entity and transaction system using P2P1) network
      Cryptocurrency that combines blockchain2) technology with the concept of money for the first time
      A revolution in payment management that maximizes transparency by jointly managing all transaction details by users
    `,
    value: `
      Bitcoin is a peer-to-peer decentralized network and the leading digital currency in terms of overall market size. Bitcoin was developed in October 2008 by a programmer under the pseudonym Satoshi Nakamoto, and the program source was distributed in January 2009.
      The Bitcoin network itself consists of thousands of decentralized nodes that independently verify transactions made using BTC. Therefore, financial transactions such as remittance can be freely performed between individuals in a peer-to-peer (P2P) way across the globe without a central bank.
      Bitcoin miners group BTC transaction data and record them in blocks. Each block is cryptographically linked to the previous block to create a chain called a blockchain. This connection ensures that previously written data is not tampered with.
      In the years following its launch, Bitcoin has garnered mainstream attention. Today, there are approximately 100 million Bitcoin users worldwide, and tens of thousands of merchants support BTC payments.
    `,
    remark: `
      1) An abbreviation of Peer to Peer, which is a communication and information exchange method through direct connection between general users, rather than a general method that relies on a central server for communication.
      2) A next-generation network method designed to prevent arbitrary manipulation by the operator of a distributed node as a continuously growing data record list in the form of a distributed database as a network method based on a public transaction ledger
    `,
  },
  eth: {
    characteristic: `
      Ethereum is the name of a public blockchain network whose default token is Ether (ETH), and is a platform created for the development of various decentralized apps (dApps) based on existing blockchains.
      It has greater scalability than the existing Bitcoin blockchain and includes a ‘smart contract’ function that can accurately execute programmed contracts.
      It holds a unique position along with Bitcoin and often serves as the key cryptocurrency of exchanges along with BTC and USDT.
      Unlike Bitcoin, the network acts like a decentralized computer using a “Turing-complete language” and can handle advanced computations, while Ethereum’s computational capabilities mean developers can write and deploy smart contracts on the network.
    `,
    value: `
      The Ethereum network went live in 2015. But it wasn’t until 2017 that it began to attract more attention. That year, the network became a leading platform for launching early coin offerings, which helped push the price of ETH above $1,400 during the cryptocurrency bull market in 2017. Recently, Ethereum has been the main blockchain powering the new decentralized finance sector. In other words, the number of DApps allowing unauthorized access to several financial services is increasing, which has had a positive effect on the Ethereum coin price. Ethereum currently uses a proof-of-work algorithm to reach consensus among a decentralized network of nodes. However, the ongoing Ethereum 2.0 upgrade will transform the network into a resource-intensive consensus mechanism known as proof-of-stake. Instead of relying on power-hungry hardware, Proof of Stake requires Ethereum transaction validators to lock at least 32 ETH in their staking smart contracts. The network then randomly selects stakers to process and confirm transactions and calculations. While stakers receive newly minted ETH to properly validate transactions, their stake is “cut” or lost if they attempt to manipulate the network. OKEx provides easy access to ETH staking without the hassle of setting up Ethereum staking nodes yourself.
    `,
    remark: `
    `,
  },
  usdt: {
    characteristic: `
      Blockchain1) based cryptocurrency based on US dollar
      It is characterized by little volatility in value by maintaining a ratio of about 1:1 with the actual dollar reserve.
      It plays a role for stable asset management when trading other cryptocurrencies with significant fluctuations in value.
    `,
    value: `
      It is a cryptocurrency created for the purpose of performing the function of a real currency for trading other cryptocurrencies with extreme volatility and a transaction method that forms a value in a 1:1 ratio with the actual key currency for cryptocurrency trading.
      A currency that can perform this role must be operated and managed based on reliability, and Tether, the operator, maintains its operation by transparently and regularly disclosing its financial status based on the US dollar held in the bank in a 1:1 ratio. In fact, in 2017, some things that could raise suspicions occurred, and the credibility has decreased to some extent.
      However, the stable value based on USD is still maintained, and the trading market is still actively moving.

      USDT is a token issued by Tether based on the stable value currencies of the United States Dollar (USD), Tether USD (hereafter USDT), and 1USDT = 1 Dollar. Users can use USDT and USD for 1:1 trading at any time. Tether strictly adheres to a 1:1 reserve guarantee, guaranteeing $1 in your bank account for every USDT token you issue.
    `,
    remark: `
      1) A next-generation network method designed to prevent arbitrary manipulation by the operator of a distributed node as a continuously growing data record list in the form of a distributed database as a network method based on a public transaction ledger
    `,
  },
  bnb: {
    characteristic: `
      BNB was first unveiled in 2017 through an initial coin offering (ICO).
      This day was 11 days before the Binance cryptocurrency exchange started its online service. Coins issued at this time were ERC-20 tokens based on the Ethereum network (total issue limit of 200 million) and BNB 100 million, but in 2019 When Binance Chain mainnet was launched in April, ERC-20-based BNB coins were exchanged 1:1 for BEP2-based BNB, and now they are not using Ethereum.
    `,
    value: `
      BNB is used as a payment method. You can use it as a utility token to pay fees incurred on the Binance Exchange, or participate in token sales on Binance Launchpad.
      Also, BNB is the key currency of Binance DEX (Decentralized Exchange).
    `,
    remark: `
    `,
  },
  tp3: {
    characteristic: `
      TP3 is a real economy platform that enables the efficient use of cryptocurrency consumption. In the TP3 platform, various coins are inquired and available cryptocurrency is inquired, and users can freely exchange and use the inquired cryptocurrency. In consideration of user convenience, it is possible to easily pay using barcodes and QR codes that are similar to existing mobile payment services.
    `,
    value: `
      We provide fast and convenient transactions with the world's highest level of safe and transparent system.
      It is not limited to one region and can form an infrastructure construction through global partnerships, and since transactions are conducted only with encrypted data and key values, the security is very high and hacking is absolutely impossible.
      In addition, the decentralized system DeFi service that does not require a centralized central server and system reduces security costs and is economically efficient. Based on that technology, we provide a customized platform that users want, crossing the boundaries of public and private blockchains.
    `,
    remark: `
    `,
  },
  ctc: {
    characteristic: `
      CTC tokens were issued in June 2019, and after that, 99.9% of the CTC tokens issued to build a complete ecosystem in April 2020 were burned, and new CTCs were issued and replaced.
      Cybertronchain.com is redefining how payments are moved, consumed and used in real life, supporting the fees and future of the economy.
    `,
    value: `
      The total supply of Cybertron Chain (CTC) started with 100,000,000 (100 million) tokens, and Cybertronchain (CTC) only focuses on a 20% token long-term strategy to protect the market and show us to future token holders. The remaining 80 million tokens consist of system fees, physical payments and fees.
    `,
    remark: `
    `,
  },
  mc: {
    characteristic: `
    `,
    value: `
    `,
    remark: `
    `,
  },
}
</script>

<style>
.container .custom_frame {
}
.container .custom_frame .menu_left {
  
}
.container .custom_frame .menu_left .item {
  line-height: 1.8;
  font-size: 16px;
  font-weight: 500;
  color: #bebebe;
  cursor: pointer;
}
.container .custom_frame .menu_left .item.on {
  color: #000000;
}

.container .custom_frame .frame-right {
  font-size: 16px;
  max-width: 900px;
  border: 1px solid #eae8e5;
}

.container .custom_frame .frame-right h1#currentSelectedCoinName {
  display: inline-block;
  border: 1px solid #eae8e5;
  padding: 5px 10px;
  color: #fff;
  background-color: rgba(36, 9, 120, 0.8);
}

.container .custom_frame .frame-right h3.section-title {
  border-bottom: 1px solid black;
}

.container .custom_frame .frame-right section h3+p {
  margin-bottom: 50px;
}
</style>