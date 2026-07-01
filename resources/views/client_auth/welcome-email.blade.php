<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Email</title>
</head>
<body style="font-family: Poppins, sans-serif; background-color: #f6f6f6; color: #333; margin: 0; padding: 30px 20px;">

<div class="container" style="max-width: 600px; margin: auto; background: #fff; border-radius: 0.75rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); overflow: hidden;">
    <div class="header" style="background-color: #eb973c; color: #fff; padding: 20px; text-align: center;"> 
        <h1 style="font-size: 1.8rem; margin: 0;">{!! $headerContent !!}</h1> 
    </div>
    <div class="content" style="padding: 30px 20px;">
        <p style="font-size: 0.875rem; line-height: 1.6; margin: 15px 0;">{!! $bodyText !!}</p>
        <a href="{{ config('app.url') }}" class="button" style="display: inline-block; padding: 12px 25px; background: #eb973c; color: #fff; text-decoration: none; border-radius: 0.75rem; font-weight: 600; text-align: center; margin-top: 20px; transition: background 0.3s;">{{ emailContentSettings('welcome')->button_text }}</a>
    </div>
    <div class="footer" style="background-color: #f3f3f3; padding: 15px 20px; font-size: 0.75rem; text-align: center; color: #666;">
    <img src="{{ config('app.url') . getOrganizationData()->logo }}" alt="Company Logo" class="logo" style="max-width: 200px;">

        <p style="font-size: 0.75rem; color: #666;">{!! $footerText !!}</p>
    </div>
</div>

</body>
</html>