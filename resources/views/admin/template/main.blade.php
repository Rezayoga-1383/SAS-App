<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
	<meta name="author" content="AdminKit">
	<meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

	<link rel="icon" type="image/png" href="{{ asset('assets/image/logo.png') }}">
	<title>@yield('title')</title>
	@include('admin.template.style')
</head>

<body>
	<div class="wrapper">
		@include('admin.template.sidebar')
		<div class="main">
			@include('admin.template.navbar')
            
            @yield('content')

			<footer class="footer">
				<div class="container-fluid">
					<div class="row text-muted">
						<div class="col-6 text-start">
							<p class="mb-0">
								<a class="text-muted" href="https://saranaagungsejahtera.co.id/" target="_blank"><strong>P.T Sarana Agung Sejahtera</strong></a> - <a class="text-muted" href="https://saranaagungsejahtera.co.id/" target="_blank"><strong>2025</strong></a>								&copy;
							</p>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>

	@include('admin.template.script')

</body>
</html>