<div class="container">
  <div class="custom_frame">
    <div class="menu_left" id="coin-list">
			<h1>코인 소개</h1>
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
        <h3 class="section-title">특징</h3>
        <p id="characteristic"></p>
      </section>
      <section class="value">
        <h3 class="section-title">가치</h3>
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
      암호화폐 중 가장 최초로 구현된 코인
      중앙관리 주체가 없으며 P2P1) 네트워크를 이용한 거래 시스템
      블록체인2) 기술을 최초로 화폐라는 개념에 융합시킨 암호화폐
      모든 거래 내역을 사용자들이 공동 관리함으로써 투명도를 최대화 시킨 지불 관리 수단의 혁명
    `,
    value: `
      비트코인은 P2P 탈 중앙화 네트워크이자 전체 시장 규모 측면에서 최고의 디지털 통화입니다. 비트코인은 2008년 10월 사토시 나카모토라는 가명을 쓰는 프로그래머가 개발하여, 2009년 1월 프로그램 소스를 배포되었습니다.
      비트코인 네트워크 자체는 BTC를 사용하여 이루어진 트랜잭션을 독립적으로 확인하는 수천 개의 분산 노드로 구성됩니다. 따라서, 중앙은행이 없이 전 세계적 범위에서 P2P 방식으로 개인들 간에 자유롭게 송금 등의 금융거래를 할 수 있습니다.
      비트코인 채굴자는 BTC 거래 데이터를 그룹화하여 블록에 기록합니다. 각 블록은 이전 블록에 암호화되어 연결돼 블록 체인 이라고하는 체인을 만듭니다. 이 연결은 이전에 기록 된 데이터가 변경되지 않도록 보장합니다.
      출시 이후 몇 년 동안 비트코인은 주류의 관심을 끌었습니다. 오늘날 전 세계적으로 약 1억 명의 비트코인 사용자가 생겨났으며, 수만 명의 판매자가 BTC 결제를 지원하고 있습니다.
    `,
    remark: `
      1) Peer to Peer의 약자로 중앙 서버에 의존하여 통신을 하는 일반적인 방식이 아닌 일반 사용자끼리의 직접적인 연결을 통한 통신 및 정보 교환 방식
      2) 공공거래장부를 기반으로 구성된 네트워크의 방식으로 분산데이터베이스의 한 형태로 지속적으로 성장하는 데이터 기록 리스트로써 분산 노드의 운영자에 의한 임의 조작이 불가능하게끔 설계된 차세대 네트워크 방식
    `,
  },
  eth: {
    characteristic: `
      이더리움은 기본 토큰이 Ether (ETH) 인 퍼블릭 블록 체인 네트워크의 이름이며, 기존 블록체인에 기반해 다양한 탈중앙화 앱(dApp) 개발을 위해 제작된 플랫폼
      기존 비트코인의 블록체인보다 뛰어난 확장성을 보이며 프로그램된 계약을 정확히 실행 할 수 있는 ‘스마트 컨트랙트’ 기능을 포함하고 있음
      비트코인과 더불어 독보적인 위치를 지키고 있으며 BTC, USDT와 더불어 거래소의 기축 암호화폐 역할을 하는 경우가 많음
      비트 코인과 달리 네트워크는 "튜링완전언어” 를 사용하는 분산 형 컴퓨터처럼 작동하여 고급 계산을 처리 할 수 있으며, 이더리움의 계산 기능은 개발자가 네트워크에서 스마트 계약을 작성하고 배포 할 수 있음을 의미함
    `,
    value: `
      이더리움 네트워크는 2015 년에 가동되었습니다.하지만 2017 년이 되어서야 더 많은 관심을 끌기 시작했습니다. 그해, 네트워크는 초기 코인 제공을 시작하는 대표적 플랫폼이 되었으며, 이는 2017 년 암호 화폐 강세장에서 ETH의 가격을 1,400 달러 이상으로 끌어 올리는 데 도움이 되었습니다. 최근 이더리움은 새로운 분산 금융 부문을 지원하는 주요 블록 체인 이었습니다. 즉, 여러 금융 서비스에 대한 무허가 액세스를 허용하는 DApp의 수가 증가하고 있으며, 이는 이더리움 코인 가격에 긍정적인 영향을 미쳤습니다. 이더리움은 현재 작업 증명 알고리즘을 사용하여 분산 된 노드 네트워크 간의 합의에 도달합니다. 그러나 현재 진행중인이더리움 2.0 업그레이드를 통해 네트워크를 지분 증명이라고하는 리소스 집약적 합의 메커니즘으로 전환 할 것입니다. 전력이 부족한 하드웨어에 의존하는 대신 지분 증명은 이더리움 트랜잭션 검증자가 스테이 킹 스마트 계약에서 최소 32 ETH를 잠그도록 요구합니다. 그런 다음 네트워크는 거래 및 계산을 처리하고 확인하기 위해 무작위로 스테이커를 선택합니다. 스테이커는 거래를 올바르게 검증하기 위해 새로 발행 된 ETH를 받는 반면, 네트워크 조작을 시도하면 지분이 "삭감"되거나 손실됩니다. OKEx 에서는 이더리움 스테이킹 노드를 직접 설정하는 번거로움 없이 ETH 스테이킹에 쉽게 액세스 할 수 있도록 지원합니다.
    `,
    remark: `
    `,
  },
  usdt: {
    characteristic: `
      미국 달러화를 기반으로 한 블록체인1) 기반 암호화폐
      실제 달러화 유보금과 1:1정도의 비율을 유지함으로써 가치의 변동성이 거의 없다는 것이 특징
      가치 변동이 심한 다른 암호화폐 거래 시 안정적인 자산 운용을 위한 역할을 수행하고 있음
    `,
    value: `
      암호화폐 거래를 위한 실질적 기축통화와 1:1 비율로 가치를 형성하는 거래 수단 및 극심한 변동성을 가지고 있는 다른 암호화폐를 거래하기 위한 실질적인 화폐의 기능을 수행할 수 있는 목적으로 만들어진 암호화폐 입니다.
      이러한 역할을 수행할 수 있는 화폐는 신뢰성을 바탕으로 운영과 관리가 되어야 하며 이를 운영사인 Tether사에서 은행에 1:1비율로 보유하고 있는 미국 달러를 토대로 투명하게 정기적으로 재무 상태를 공개하며 운영을 하는 정책을 가지고 있지만 실질적으로 2017년 들어 의혹이 생길 만한 일들이 다소 발생하였고 이로 인하여 신뢰도가 어느정도 하락한 상태입니다.
      하지만 아직까지는 USD를 기반으로 한 안정적인 가치의 유지는 지속되고 있으며 여전히 거래 시장 또한 활발하게 움직이고 있는 상황입니다.

      USDT는 안정된 가치 통화 인 미국 달러 (USD), 테더 USD (이하 USDT), 1USDT = 1 달러를 기준으로 테더 사가 발행하는 토큰으로, 사용자는 USDT와 USD를 언제든지 1 : 1 거래에 사용할 수 있습니다. . 테더는 1 : 1 예비 보증을 엄격히 준수하며, 발행하는 모든 USDT 토큰에 대해 은행 계좌에 1 달러를 보장합니다.
    `,
    remark: `
      1) 공공거래장부를 기반으로 구성된 네트워크의 방식으로 분산데이터베이스의 한 형태로 지속적으로 성장하는 데이터 기록 리스트로써 분산 노드의 운영자에 의한 임의 조작이 불가능하게끔 설계된 차세대 네트워크 방식
    `,
  },
  bnb: {
    characteristic: `
      BNB는 2017년에 암호화폐 공개(ICO)를 통해 처음 공개되었음
      이 날은 바이낸스 암호화폐 거래소가 온라인 서비스를 시작하기 11일 전.이때 발행한 코인은 이더리움 네트워크 기반의 ERC-20 토큰(총 발행 한도 2억 개)과 BNB 1억 개였음, 하지만 2019년 4월에 바이낸스 체인 메인넷을 출범하면서 ERC-20 기반 BNB 코인을 BEP2 기반 BNB와 1:1 교환했고, 이제는 이더리움을 사용하지 않고 있음
    `,
    value: `
      BNB는 결제수단으로 사용됩니다. 바이낸스 거래소에서 발생하는 수수료를 지불하거나, 바이낸스 런치패드에서 열리는 토큰세일에 참여할 때 유틸리티 토큰으로 사용하실 수 있습니다.
      또한 BNB는 바이낸스 DEX(탈중앙화 거래소)의 기축통화입니다
    `,
    remark: `
    `,
  },
  tp3: {
    characteristic: `
      TP3는 암호화폐 소비를 효율적으로 사용할 수 있게 하는 실물경제 플랫폼이며, TP3 플랫폼 안에는 다양한 코인이 조회되고 사용 가능한 암호화폐가 조회되고 사용자는 조회된 암호화폐를 자유롭게 교환해서 사용할 수 있다. 사용자 편의성을 고려하여 기존의 모바일 지불결제 서비스와 유사한 형태의 바코드와 QR 코드를 사용하여 손쉽게 결제가 가능함.
    `,
    value: `
      글로벌 최고 수준의 안전하고 투명한 시스템으로 빠르고 편리한 거래를 제공합니다.
      한 지역에 국한되지 않고 세계적인 파트너십을 통해 인프라 구축을 형성할 수 있으며,암호화된 데이터와 키값으로만 거래가 진행되므로 보안성이 매우 높고 해킹이 절대적으로 불가능합니다.
      또한 집중화된 중앙서버와 시스템이 필요 없는 탈중앙화시스템 디파이 서비스로 보안 비용이 감소해 경제적인 면에서도 효율이 높습니다. 그 기술력을 바탕으로 퍼블릭과 프라이빗 블록체인의 경계를 넘나들며 사용자가 원하는 맞춤 플랫폼을 제공합니다.
    `,
    remark: `
    `,
  },
  ctc: {
    characteristic: `
      CTC 토큰은 2019년 6월에 발행되었으며, 이후 2020 4월 완전한 생태계 구축을 위해 발행한 CTC 토큰 99.9%를 소각한 후 , 새로운 CTC를 발행하여 교체하였음
      Cybertronchain.com은 결제 이동, 소비 및 실생활의 사용방식을 재 정의하고 있으며, 경제방식의 수수료와 미래를 지원하고 있음
    `,
    value: `
      사이버 트론 체인 (CTC)의 총 공급량은 100,000,000 (1 억) 토큰으로 시작되었으며, CTC (Cybertronchain)는 시장을 보호하고 미래 토큰 보유자에게 우리를 보여주기 위해 20 % 토큰 장기 전략에만 집중합니다. 나머지 8 천만 개의 토큰은 시스템 수수료, 물리적 결제 및 수수료로 구성됩니다.
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