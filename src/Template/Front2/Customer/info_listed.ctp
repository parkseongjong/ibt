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
				<li class="title">SMBIT listing screening criteria guide</li>
			</ul>
			<div class="page-subtitle">
				SMBIT is trying to secure the safety of new investments to members through the review of newly registered transaction coins.
				We comply with government and FATF regulations for the healthy development of the virtual asset market. <br />
				In addition, recognizing the role and responsibility of the virtual asset incubator, we will guide you on the standards for listing screening in order to contribute to the sound development of the blockchain industry.
			</div>
			<div class="contents-list">
				<h1 class="contents-list-title">
					listing review
				</h1>
				<ul>
					<li class="emphasis">1. SMBIT listing application submission</li>
					<li>2. Project white paper, technical review report, technical/business related documents, token sale and distribution plan, legal review opinion, regulatory compliance statement, third-party trustee certificate, ethics pledge, etc.</li>
					<li class="emphasis">3. SMBIT Listing Checklist</li>
					<li>4. Internal/external listing review</li>
					<li>5. Listing qualification review and verification by the Listing Deliberation Committee including external experts in technology, finance, and law</li>
					<li class="emphasis">6. Issuer, program manager, and other members self-certification process</li>
					<li class="emphasis">7. Signed SMBIT listing/marketing contract</li>
					<li class="emphasis">8. Project listing</li>
				</ul>
			</div>

			<div class="contents-list">
				<h1 class="contents-list-title">
					Judging criteria
				</h1>
				<ul>
					<li class="emphasis">1. Business model</li>
					<li>2. Growth potential for project goals</li>
					<li>3. Whether the business and technical roadmap is being implemented</li>
					<li>4. Whether the business will be sustainable and create value</li>
					<li>5. Whether infrastructure is established in related industries</li>
					<li class="emphasis">6. Technical competence</li>
					<li>7. Suitability and effectiveness of platform architecture</li>
					<li>8. Whether the blockchain network runs smoothly</li>
					<li>9. Technical competency and implementation capability</li>
					<li class="emphasis">10. Legal Compliance</li>
					<li>11. Compliance with applicable laws and regulations</li>
					<li class="emphasis">12. Project Ecosystem</li>
					<li>13. Rationality of virtual asset operation plan (ecosystem maintenance, distribution, etc.)</li>
					<li>14. Transparency in virtual asset management </li>
					<li class="emphasis">15. Foundation Organization Assessment</li>
					<li>16. Whether members have an understanding of the blockchain ecosystem and related experience</li>
					<li>17. Whether to establish professional manpower and human networks for each field</li>
					<li>18. Whether to activate external activities and communication channels for investors</li>
					<li>19. If the token distribution is over 50%, listing is not possible</li>
					<li>20. Inquiry email</li>
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