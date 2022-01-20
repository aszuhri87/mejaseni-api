<!doctype html>
<html>

<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Mejaseni Reminder</title>
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
                                            <p>Hi {{ ucwords($schedule->student_name) }},</p>
											<table role="presentation" border="0" cellpadding="0" cellspacing="0"
                                            class="btn btn-primary" style="width: auto;">
											<tbody>
												<tr>
													<p><b>{{$schedule->message}}</b></p>
												</tr>
                                                <tr>
													<p>Link {{$schedule->platform}}: {{$schedule->platform_link}}</p>
												</tr>
											</tbody>
										</table>
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
