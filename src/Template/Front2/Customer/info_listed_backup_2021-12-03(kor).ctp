<div class="container">
	<div class="custom_frame">
		<?php echo $this->element('Front2/customer_left');
		$keyword = !empty($_REQUEST['keyword']) ? $_REQUEST['keyword'] : '';
		//$keyword = !empty($this->request->data['keyword']) ? $this->request->data['keyword'] : '';
		$page = !empty($_REQUEST['page']) ? $_REQUEST['page'] : 1;
		//$page = !empty($this->request->data['page']) ? $this->request->data['page'] : 1;
        ?>
		<?= $this->Flash->render() ?>
		<div class="contents">
			<ul class="search_box">
				<li class="title">CoinIBT 상장 심사 기준 안내</li>
			</ul>
			<div class="page-subtitle">
				Coinibt는 신규 등록 거래코인의 검토결과를 통해 회원님들께 새로운 투자의 안전성을 확보하고자 노력하고 있으며, 
				가상자산 시장의 건전한 발전을 위한 정부 및 FATF 규제를 준수합니다. <br />
				또한, 가상자산 인큐베이터의 역할과 책임을 인식하며, 블록체인 산업의 건전한 발전을 기여하고자 상장심사 기준을 안내드립니다.
			</div>
			<div class="contents-list">
				<h1 class="contents-list-title">
					상장 심사
				</h1>
				<ul>
					<li class="emphasis">1. Coinibt 상장 지원서 접수</li>
					<li>2. 프로젝트 백서, 기술검토보고서, 기술/비즈니스 관련 문서, 토큰 세일 및 분배 계획서, 법률 검토의견서, 규제 준수 확약서, 제3자 수탁인증서, 윤리서약서 등</li>
					<li class="emphasis">3. Coinibt 상장 체크리스트</li>
					<li>4. 내/외부 상장 검토</li>
					<li>5. 기술, 금융, 법률 관련 외부 전문가들을 포함한 상장심의위원회의 상장 적격성 검토 및 검증</li>
					<li class="emphasis">6. 발행자,프로그램관리자, 그외 구성인 본인인증 절차진행</li>
					<li class="emphasis">7. Coinibt 상장/마케팅 계약 체결</li>
					<li class="emphasis">8. 프로젝트 상장</li>
				</ul>
			</div>

			<div class="contents-list">
				<h1 class="contents-list-title">
					심사 기준
				</h1>
				<ul>
					<li class="emphasis">1. 비즈니스 모델</li>
					<li>2. 프로젝트 목표에 대한 성장 가능성</li>
					<li>3. 비즈니스 및 기술적 로드맵 이행 여부</li>
					<li>4. 비즈니스의 지속성 및 가치 창출 가능성 여부</li>
					<li>5. 연관산업 내 인프라 구축 여부</li>
					<li class="emphasis">6. 기술 역량</li>
					<li>7. 플랫폼 아키텍처의 적합성 및 효율성</li>
					<li>8. 블록체인 네트워크의 원활한 구동 여부</li>
					<li>9. 기술적 역량 및 구현 능력 여부</li>
					<li class="emphasis">10. 법률 준수</li>
					<li>11. 관련 법률 및 규제 준수 여부</li>
					<li class="emphasis">12. 프로젝트 생태계</li>
					<li>13. 가상자산 운영 계획의 합리성 (생태계 유지, 분배 등)</li>
					<li>14. 가상자산 관리의 투명성 </li>
					<li class="emphasis">15. 재단 조직 평가</li>
					<li>16. 구성원들의 블록체인 생태계 이해도 및 관련 경험 보유 여부</li>
					<li>17. 각 분야에 대한 전문 인력 및 인적 네트워크 구축 여부</li>
					<li>18. 투자자 대상 대외활동 및 커뮤니케이션 채널 활성화 여부</li>
					<li>19. 토큰 분배도 50%이상 진행자인 경우 상장불가</li>
					<li>20. 문의메일</li>
				</ul>
			</div>
		</div>
		<div class="cls"></div>
	</div>
</div>

<style>
.contents {
	font-size: 16px;
}
.contents .page-subtitle {
	margin-bottom: 50px;
}
.contents .contents-list {
	margin-bottom: 35px;
}

.contents .contents-list ul > li {
	color: gray;
}
.contents .contents-list ul > li.emphasis {
	color: #000;
}
</style>