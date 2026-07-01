<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width" />
	<title>PicPax</title>
	<style type="text/css">
		@import url('https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&family=Open+Sans:wght@400;500;600;700&display=swap');

		body {
			height: 100% !important;
			margin: 0;
			padding: 0;
			width: 100% !important;
		}

		table {
			border-collapse: separate;
		}

		img,
		a img {
			border: 0;
			outline: none;
			text-decoration: none;
		}

		strong {
			font-weight: 500;
		}

		h1,
		h2,
		h3,
		h4,
		h5,
		h6 {
			margin: 0;
			padding: 0;
		}

		p {
			margin: 1em 0;
		}

		table,
		td {
			mso-table-lspace: 0pt;
			mso-table-rspace: 0pt;
		}

		#outlook a {
			padding: 0;
		}

		img {
			-ms-interpolation-mode: bicubic;
		}

		body,
		table,
		td,
		p,
		a,
		li,
		blockquote {
			-ms-text-size-adjust: 100%;
			-webkit-text-size-adjust: 100%;
		}

		img {
			max-width: 100%;
			height: auto;
		}

		.main-table {
			width: 600px !important;
		}

		@media only screen and (max-width: 620px) {
			#foxeslab-email .table1 {
				width: 90% !important;
			}

			.main-table {
				width: 100% !important
			}
		}

		@media only screen and (max-width: 480px) {

			table[class="flexibleContainer"] {
				width: 100% !important;
			}

			#foxeslab-email .table1 {
				width: 100% !important;
			}
		}
        .editable-text a{
            color: white!important;
        }
	</style>
</head>

<body style="padding: 0; margin: 0;" id="foxeslab-email">

	<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color:#F8f8f8;">

		<tbody>
			<tr>
				<td height="20px"></td>
			</tr>
			<tr>
				<td>

					<table class="table1 main-table" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto; background-color: #fff;
				border-radius: 18px;">
						<tbody>
							<tr>
								<td height="15"></td>
							</tr>

							<tr>
								<td style="padding-left:20px; padding-right:20px;">
									<table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto;">
										<tbody>
											<tr>
												<td>
													<img src="{{asset('images/logo-full.png')}}" alt="" width="110">
												</td>

												<td mc:edit="text1203" width="50%" align="right" style="color: #000; font-size:14px;font-weight:normal; font-family:'Open Sans', sans-serif; mso-line-height-rule: exactly;">
													Date: {{date('d/m/Y')}}
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>



							<tr>
								<td height="10"></td>
							</tr>

							<tr>
								<td style="padding-left: 20px; padding-right: 20px;">
									<table class="table1 main-table" border="0" align="center" cellpadding="0" cellspacing="0" style="background-color: #f4f9f7; border-radius: 18px;">
										<tbody>
											<tr>
												<td height="20"></td>
											</tr>
											<tr>
												<td style="font-family:'Lato', sans-serif; font-weight: 600;  font-size:22px; color:#000;padding-left: 20px; padding-right: 20px;" align="center" valign="top" class="editable">
													<multiline>Thank Yor For Choosing</multiline>
												</td>
											</tr>
											<tr>
												<td height="10"></td>
											</tr>
											<tr>
												<td style="font-family:'Open Sans', sans-serif;font-size:14px; color:#000; padding-left: 20px; padding-right: 20px;" align="center" valign="top" class="editable">
													<multiline>Hi <strong>{{ $first_name }} {{ $last_name }}</strong>.</multiline>
												</td>
											</tr>
											<tr>
												<td height="20"></td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>

							<tr>
								<td>
									<table class="two-left" align="left" width="100%" cellspacing="0" cellpadding="0" border="0">
										<tbody>
											<tr>
												<td align="left" valign="top" height="20"></td>
											</tr>
                                            <tr>
												<td align="left" mc:edit="text202" class="text_color_2E2E3B" style="color: #565656; font-size: 14px; font-family: 'Open Sans', sans-serif; mso-line-height-rule: exactly; padding-left: 20px; padding-right:20px;">
                                                    <strong>Message Here:</strong>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>

							<tr>
								<td height="30"></td>
							</tr>

							<tr>
								<td style="padding-left:20px; padding-right: 20px;">
									<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 0 auto; background-color:#244d80; border-radius: 18px;">
										<tbody>
											<tr>
												<td height="15px"></td>
											</tr>

											<tr>
												<td mc:edit="text1201" align="left" style="color: #fff;  font-size: 13px; font-family:'Open Sans', sans-serif; mso-line-height-rule: exactly;
										padding-left:20px; padding-right: 20px;">
													<div class="editable-text" align="center" style="color:white">Copyright © {{date('Y')}}
														wwt.com all rights reserved.</div>
												</td>
											</tr>
											<tr>
												<td height="15px"></td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
							<tr>
								<td height="20"></td>
							</tr>
						</tbody>
					</table>

				</td>
			</tr>
			<tr>
				<td height="20px"></td>
			</tr>
		</tbody>
	</table>
</body>

</html>