<!doctype html>
<html>

<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Konfirmasi Akun</title>
</head>

<body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0;padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
	<table role="presentation" border="0" cellpadding="0" cellspacing="0"  style="background-color: #f6f6f6; width: 100%;">
		<tr>
			<td>&nbsp;</td>
			<td  style="display: block; margin: 0 auto !important; max-width: 580px; padding: 10px; width: 580px;">
				<div  style="box-sizing: border-box; display: block; margin: 0 auto; max-width: 580px; padding: 10px;">

					<!-- START CENTERED WHITE CONTAINER -->
					<table role="presentation"  style="background: #ffffff; border-radius: 3px; width: 100%;">

						<!-- START MAIN CONTENT AREA -->
						<tr>
							<td style="box-sizing: border-box; padding: 20px;">
								<table role="presentation" border="0" cellpadding="0" cellspacing="0">
									<tr>
										<td>
											<p>Hi {{ ucwords($account->name) }},</p>
											<p>Klik tombol <b>Verifikasi Akun</b> untuk melanjutkan mengaktifkan akun. Tautan akan aktif dalam waktu 24 Jam</p>
											<table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                            class="btn btn-primary" style="width: auto;">
                                            @php
                                                $link = url('email-verification/check/'.$account->token_verification);
                                            @endphp
											<tbody>
												<tr>
													<td align="left" style="padding-bottom: 15px;  background-color: #ffffff; border-radius: 5px; text-align: center;">
														<table role="presentation" border="0" cellpadding="0"
														cellspacing="0" style="width: auto;">
														<tbody>
															<tr>
																<td style="padding-bottom: 15px;  background-color: #ffffff; border-radius: 5px;text-align: center;">
																	<a href="{{ $link }}" target="_blank" style="background-color: #ffffff; border: solid 1px #21C68A; border-radius: 5px; box-sizing: border-box; color: #21C68A; cursor: pointer; display: inline-block; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-decoration: none; text-transform: capitalize; background-color: #21C68A; border-color: #21C68A; color: #ffffff;">Verifikasi Akun</a> </td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
											</tbody>
										</table>
										<p>Jika tombol diatas tidak dapat bekerja, silahkan klik/copy url dibawah ini ke browser kamu.</p>
										<a href="{{ $link }}">{{ $link }}</a>
										<p>Jika akun sudah diaktifkan, kamu dapat login menggunakan username kamu di <a href="{{ url('login') }}">{{ url('login') }}</a></p>
										<br>
										<p>Salam,</p>
										<p>Tim Mejaseni</p>
										<br>
										<p>Pesan ini di-generate secara otomatis, jangan balas pesan ini.</p>
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<!-- END MAIN CONTENT AREA -->
				</table>
				<!-- END CENTERED WHITE CONTAINER -->

				<!-- START FOOTER -->
				<div  style="color: #999999; font-size: 12px;text-align: center;">
					<table role="presentation" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td style=" color: #999999; font-size: 12px; text-align: center; padding-bottom: 10px; padding-top: 10px;">
								Powered by <a href="http://www.mejaseni.com" style=" color: #999999; font-size: 12px; text-align: center; text-decoration: none;">Mejaseni</a>.
							</td>
						</tr>
					</table>
				</div>
				<!-- END FOOTER -->

			</div>
		</td>
		<td>&nbsp;</td>
	</tr>
</table>
</body>

</html>
