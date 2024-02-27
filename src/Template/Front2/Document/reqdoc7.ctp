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
						<h1 class="title">Non-face-to-face biometric (KYC authentication) method guide</h1>
						<p><strong>Precautions before change</strong> You must first complete authentication up to LV.2 level 2 to be able to perform 3rd stage KYC authentication.</p>

						<div class="guide_panel">
							<h3 class="sub_title">1. After logging in, click [My Page] > [Authentication Step].</h3>
							<img src="/wb/imgs/reqdoc7/guide1.png" alt="" />
						</div>
						<!--/guide_panel-->

						<div class="guide_panel">
							<h3 class="sub_title"> 2. We will proceed with KYC verification. </h3>
							<img src="/wb/imgs/reqdoc7/guide2.png" alt="" />
						</div>
						<!--/guide_panel-->

						<div class="guide_panel">
							<h3 class="sub_title sub_title3"> 3. We will proceed with KYC verification.</h3>
							<ul>
								<li>Please select a language to register.</li>
							</ul>
							<img src="/wb/imgs/reqdoc7/guide3.png" alt="" />
						</div>
						<!--/guide_panel-->

						<div class="guide_panel">
							<h3 class="sub_title sub_title3"> 4. We will start the notarization process.</h3>
							<p>① Enter your name > ② Select your gender > ③ Enter your date of birth > ④ Select your nationality. </p>
							<div class="notariz">
								<div class="notariz_process">
									<div class="notariz_process_detail">
										<img src="/wb/imgs/reqdoc7/notariz_process1.png" alt="" />
										<div class="notariz_process_text">
											<img src="/wb/imgs/reqdoc7/Vector1.png" alt="" />
											<h6>Enter <span>Name</span>.</h6>
										</div>
									</div>
								</div>
								<!--/notariz_process-->

								<div class="notariz_process">
									<div class="notariz_process_detail">
										<img src="/wb/imgs/reqdoc7/notariz_process2.png" alt="" />
										<div class="notariz_process_text">
											<img src="/wb/imgs/reqdoc7/Vector2.png" alt="" />
											<h6>Select <span>Gender</span>.</h6>
										</div>
									</div>
								</div>
								<!--/notariz_process-->

								<div class="notariz_process">
									<div class="notariz_process_detail">
										<img src="/wb/imgs/reqdoc7/notariz_process3.png" alt="" />
										<div class="notariz_process_text">
											<img src="/wb/imgs/reqdoc7/Vector3.png" alt="" />
											<h6>Enter your <span>date of birth</span>.</h6>
										</div>
									</div>
								</div>
								<!--/notariz_process-->

								<div class="notariz_process">
									<div class="notariz_process_detail">
										<img src="/wb/imgs/reqdoc7/notariz_process4.png" alt="" />
										<div class="notariz_process_text">
											<img src="/wb/imgs/reqdoc7/Vector4.png" alt="" />
											<h6>Select <span>Nationality</span>.</h6>
										</div>
									</div>
								</div>
								<!--/notariz_process-->

							</div>
							<!--/notariz-->
						</div>
						<!--/guide_panel-->

						<div class="guide_panel">
							<h3 class="sub_title sub_title3"> 5. I will start verifying my identity.</h3>
							<p class="clr_blk">① Upload your ID (passport, ID Card) to be authenticated and take a picture using your phone camera.</p>
							<ul>
								<li>Please upload as a jpg or pdf file, and reduce the file size to 3MB or less.</li>
							</ul>
							<img src="/wb/imgs/reqdoc7/guide5.png" alt="" />

							<div class="notariz marg_top64">
								<div class="notariz_process">
									<div class="notariz_process_detail">
										<img src="/wb/imgs/reqdoc7/notariz_process5.png" alt="" />
										<div class="notariz_process_text">
											<img src="/wb/imgs/reqdoc7/Vector1.png" alt="" />
											<h6>Select <span>ID</span> to be authenticated.</h6>
										</div>
									</div>
								</div>
								<!--/notariz_process-->

								<div class="notariz_process">
									<div class="notariz_process_detail">
										<img src="/wb/imgs/reqdoc7/notariz_process1.png" alt="" />
										<div class="notariz_process_text">
											<img src="/wb/imgs/reqdoc7/Vector2.png" alt="" />
											<h6>Select <span>the document submission method</span>.</h6>
										</div>
									</div>
								</div>
								<!--/notariz_process-->

								<div class="notariz_process">
									<div class="notariz_process_detail">
										<img src="/wb/imgs/reqdoc7/notariz_process7.png" alt="" />
										<div class="notariz_process_text">
											<img src="/wb/imgs/reqdoc7/Vector3.png" alt="" />
											<h6>Follow the instructions to <span>take a passport</span>.</h6>
										</div>
									</div>
								</div>
								<!--/notariz_process-->

								<div class="notariz_process">
									<div class="notariz_process_detail">
										<img src="/wb/imgs/reqdoc7/notariz_process8.png" alt="" />
										<div class="notariz_process_text">
											<img src="/wb/imgs/reqdoc7/Vector4.png" alt="" />
											<h6>Upload the <span>passport photo</span> you took.</h6>
										</div>
									</div>
								</div>
								<!--/notariz_process-->

							</div>
							<!--/notariz-->
						</div>
						<!--/guide_panel-->

						<div class="guide_panel">
							<h3 class="sub_title sub_title3"> 6. After uploading your ID, we will start verifying your identity.</h3>
							<p class="clr_blk">① Take a front, left, and right video with the camera, and take a 20-second video by looking straight ahead with the ID card you selected before uploading it.</p>
							<img src="/wb/imgs/reqdoc7/guide6.png" alt="" />
						</div>
						<!--/guide_panel-->

						<div class="guide_panel face_recoganize">
							<div class="face_recoganize_text">
								<h3 class="sub_title">Step 1</h3>
								<p>stare straight into the camera</p>
								<img src="/wb/imgs/reqdoc7/face1.png" alt="" />
								<button type="button" class="next-button">Next</button>
							</div>

							<div class="face_recoganize_text">
								<h3 class="sub_title">Step 2</h3>
								<p>look left</p>
								<img src="/wb/imgs/reqdoc7/face2.png" alt="" />
								<button type="button" class="next-button">Next</button>
							</div>

							<div class="face_recoganize_text">
								<h3 class="sub_title">Step 3</h3>
								<p>look right</p>
								<img src="/wb/imgs/reqdoc7/face3.png" alt="" />
								<button type="button" class="next-button">Next</button>
							</div>

							<div class="face_recoganize_text">
								<h3 class="sub_title">Step 4</h3>
								<p>Look at the front<br/> and show your ID</p>
								<img src="/wb/imgs/reqdoc7/face4.png" alt="" />
								<button type="button" class="next-button">thank you</button>
							</div>
						</div>
						<!--/guide_panel face_recoganize-->


						<div class="guide_panel">
							<h3 class="sub_title sub_title3"> 7. All procedures are now complete. </h3>
							<p class="clr_blk">After successfully uploading, it goes through the KYC verification review period and is completed within 3 days on average.</p>

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
								<li>Your face must not be covered by the ID</li>
								<li>Likewise, the picture should come out bright and clear</li>
								<li>Hat or bandana should not cover any part of the face</li>
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