<!-- 
	비대면 생체인증 (KYC 인증) 방법 안내
 -->
 <link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/reqdoc7.css" />
<div class="container">

	<div class="custom_frame">

		<?php echo $this->element('Front2/customer_left'); ?>

		<div class="contents">
			<ul>
				<li class="title"><?= __('Information on submitting certification data') ?></li>
			</ul>

			<?php echo $this->element('Front2/reqdoc_menu'); ?>

			<div class="reqdoc_tab">
				<!-- <h1 class="reqdoc-title">비대면 생체인식(KYC 인증) 방법 안내</h1>
				<h2 class="reqdoc-subtitle">
					<span>변경 전 유의사항</span>
				 	<span>LV.2 레벨2까지 인증을 먼저 완료 하셔야 3단계 KYC 인증이 가능합니다.</span>
				</h2> -->

				<div class="container">
					<div class="frame_content">
						<h1 class="title">비대면 생체인식(KYC 인증) 방법 안내</h1>
						<p><strong>변경 전 유의사항</strong> LV.2 레벨2까지 인증을 먼저 완료 하셔야 3단계 KYC 인증이 가능합니다.</p>

						<div class="guide_panel">
							<h3 class="sub_title">1. 로그인 후 [마이페이지] > [인증단계]를 클릭하세요.</h3>
							<img src="/wb/imgs/reqdoc7/guide1.png" alt="" />
						</div>
						<!--/guide_panel-->

						<div class="guide_panel">
							<h3 class="sub_title"> 2. KYC 인증을 진행하겠습니다. </h3>
							<img src="/wb/imgs/reqdoc7/guide2.png" alt="" />
						</div>
						<!--/guide_panel-->

						<div class="guide_panel">
							<h3 class="sub_title sub_title3"> 3. KYC 인증을 진행하겠습니다.</h3>
							<ul>
								<li>등록할 언어를 선택해 주세요.</li>
							</ul>
							<img src="/wb/imgs/reqdoc7/guide3.png" alt="" />
						</div>
						<!--/guide_panel-->

						<div class="guide_panel">
							<h3 class="sub_title sub_title3"> 4. 공증절차를 시작하겠습니다.</h3>
							<p>① 이름을 입력합니다 > ② 성별을 선택합니다 > ③생년월일을 입력합니다 > ④ 국적을 선택합니다. </p>
							<div class="notariz">
								<div class="notariz_process">
									<div class="notariz_process_detail">
										<img src="/wb/imgs/reqdoc7/notariz_process1.png" alt="" />
										<div class="notariz_process_text">
											<img src="/wb/imgs/reqdoc7/Vector1.png" alt="" />
											<h6><span>이름</span>을 입력합니다.</h6>
										</div>
									</div>
								</div>
								<!--/notariz_process-->

								<div class="notariz_process">
									<div class="notariz_process_detail">
										<img src="/wb/imgs/reqdoc7/notariz_process2.png" alt="" />
										<div class="notariz_process_text">
											<img src="/wb/imgs/reqdoc7/Vector2.png" alt="" />
											<h6><span>성별</span>을 선택합니다.</h6>
										</div>
									</div>
								</div>
								<!--/notariz_process-->

								<div class="notariz_process">
									<div class="notariz_process_detail">
										<img src="/wb/imgs/reqdoc7/notariz_process3.png" alt="" />
										<div class="notariz_process_text">
											<img src="/wb/imgs/reqdoc7/Vector3.png" alt="" />
											<h6><span>생년월일</span>을 입력합니다.</h6>
										</div>
									</div>
								</div>
								<!--/notariz_process-->

								<div class="notariz_process">
									<div class="notariz_process_detail">
										<img src="/wb/imgs/reqdoc7/notariz_process4.png" alt="" />
										<div class="notariz_process_text">
											<img src="/wb/imgs/reqdoc7/Vector4.png" alt="" />
											<h6><span>국적</span>을 선택합니다.</h6>
										</div>
									</div>
								</div>
								<!--/notariz_process-->

							</div>
							<!--/notariz-->
						</div>
						<!--/guide_panel-->

						<div class="guide_panel">
							<h3 class="sub_title sub_title3"> 5. 신원인증을 시작하겠습니다.</h3>
							<p class="clr_blk">① 인증 받을 신분증(여권, 주민등록증,운전면허증)을 업로드 및 폰카메라를 이용하여 촬영해주세요.</p>
							<ul>
								<li>jpg, pdf 파일로 업로드 해주시고, 파일 용량은 3MB 이하로 줄여주시기 바랍니다.</li>
							</ul>
							<img src="/wb/imgs/reqdoc7/guide5.png" alt="" />

							<div class="notariz marg_top64">
								<div class="notariz_process">
									<div class="notariz_process_detail">
										<img src="/wb/imgs/reqdoc7/notariz_process5.png" alt="" />
										<div class="notariz_process_text">
											<img src="/wb/imgs/reqdoc7/Vector1.png" alt="" />
											<h6><span>인증 받</span>을 신분증을 선택합니다.</h6>
										</div>
									</div>
								</div>
								<!--/notariz_process-->

								<div class="notariz_process">
									<div class="notariz_process_detail">
										<img src="/wb/imgs/reqdoc7/notariz_process1.png" alt="" />
										<div class="notariz_process_text">
											<img src="/wb/imgs/reqdoc7/Vector2.png" alt="" />
											<h6><span>서류 제출 방식</span>을 선택합니다.</h6>
										</div>
									</div>
								</div>
								<!--/notariz_process-->

								<div class="notariz_process">
									<div class="notariz_process_detail">
										<img src="/wb/imgs/reqdoc7/notariz_process7.png" alt="" />
										<div class="notariz_process_text">
											<img src="/wb/imgs/reqdoc7/Vector3.png" alt="" />
											<h6>설명에 따라 <span>여권을 촬영</span>합니다.</h6>
										</div>
									</div>
								</div>
								<!--/notariz_process-->

								<div class="notariz_process">
									<div class="notariz_process_detail">
										<img src="/wb/imgs/reqdoc7/notariz_process8.png" alt="" />
										<div class="notariz_process_text">
											<img src="/wb/imgs/reqdoc7/Vector4.png" alt="" />
											<h6>촬영한 <span>여권 사진을 업로드</span>합니다.</h6>
										</div>
									</div>
								</div>
								<!--/notariz_process-->

							</div>
							<!--/notariz-->
						</div>
						<!--/guide_panel-->

						<div class="guide_panel">
							<h3 class="sub_title sub_title3"> 6. 신분증 업로드 후 신원확인을 시작하겠습니다.</h3>
							<p class="clr_blk">① 카메라를 정면,왼쪽,오른쪽 영상 촬영 및 앞에서 선택한 신분증과 정면 응시하여 각 20초 길이로 영상촬영하여 업로드 해주세요.</p>
							<img src="/wb/imgs/reqdoc7/guide6.png" alt="" />
						</div>
						<!--/guide_panel-->

						<div class="guide_panel face_recoganize">
							<div class="face_recoganize_text">
								<h3 class="sub_title">1단계</h3>
								<p>카메라를 정면으로 응시하세요</p>
								<img src="/wb/imgs/reqdoc7/face1.png" alt="" />
								<button type="button" class="next-button">다 음</button>
							</div>

							<div class="face_recoganize_text">
								<h3 class="sub_title">2단계</h3>
								<p>왼쪽을 보세요</p>
								<img src="/wb/imgs/reqdoc7/face2.png" alt="" />
								<button type="button" class="next-button">다 음</button>
							</div>

							<div class="face_recoganize_text">
								<h3 class="sub_title">3단계</h3>
								<p>오른쪽을 보세요</p>
								<img src="/wb/imgs/reqdoc7/face3.png" alt="" />
								<button type="button" class="next-button">다 음</button>
							</div>

							<div class="face_recoganize_text">
								<h3 class="sub_title">4단계</h3>
								<p>정면을 보시고 <br>신분증을 보여주세요</p>
								<img src="/wb/imgs/reqdoc7/face4.png" alt="" />
								<button type="button" class="next-button">감사합니다</button>
							</div>
						</div>
						<!--/guide_panel face_recoganize-->


						<div class="guide_panel">
							<h3 class="sub_title sub_title3"> 7. 이제 모든 절차가 끝났습니다. </h3>
							<p class="clr_blk">성공적으로 업로드 후 KYC 인증 심사 기간을 거치며 평균적으로 3일 내에는 완료 됩니다.</p>

							<div class="notariz marg_top45">
								<div class="notariz_process">
									<div class="notariz_process_detail">
										<img src="/wb/imgs/reqdoc7/notariz_process9.png" alt="" />
									</div>
								</div>

								<div class="notariz_process">
									<div class="notariz_process_detail">
										<img src="/wb/imgs/reqdoc7/notariz_process10.png" alt="" />
									</div>
								</div>
							</div>
							<!--/notariz-->
						</div>
						<!--/guide_panel-->

						<div class="extra_note">
							<ul>
								<li>신분증에 얼굴이 가려지면 안됩니다</li>
								<li>마찬가지로 사진이 밝고 명확하게 나와야합니다</li>
								<li>모자나 두건으로 얼굴의 일부분이 가려져서는 안됩니다</li>
							</ul>
						</div>
						<!--/extra_note-->

					</div>
					<!--/frame_content-->
				</div>
				<!--/container-->
			</div>

		</div>
		<div class="cls"></div>
	</div>

</div>