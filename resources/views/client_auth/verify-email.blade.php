<div class="container" style="
    max-width: 600px; 
    margin: 50px auto; 
    font-family: Arial, sans-serif; 
    background: linear-gradient(135deg, #ffffff, #f0f4f8); 
    padding: 40px; 
    border-radius: 10px; 
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    text-align: center;
">
    <h1 style="color: #333; font-size: 26px; font-weight: 700; margin-bottom: 15px;">📧 Verify Your Email</h1>
    <p style="color: #666; font-size: 16px; margin: 20px 0; line-height: 1.6;">
        A verification link has been sent to your email address. Please check your inbox and verify your email to access all features.
    </p>
    <p style="color: #999; font-size: 14px; margin: 15px 0;">
        <strong>Note:</strong> Ensure you are logged into your account in the same browser before clicking the verification link in your email.
    </p>

    @if (session('message'))
        <div class="alert alert-success" style="
            color: #155724; 
            background-color: #d4edda; 
            border: 1px solid #c3e6cb; 
            padding: 10px 15px; 
            border-radius: 5px; 
            font-size: 15px;
            margin: 20px 0;
        ">
            {{ session('message') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" style="margin-top: 25px;">
        @csrf
        <button type="submit" class="btn btn-primary" style="
            background: linear-gradient(135deg, #eb973c, #d4842e); /* Updated button color */
            border: none; 
            color: white; 
            padding: 12px 25px; 
            font-size: 16px; 
            font-weight: 600;
            cursor: pointer; 
            border-radius: 5px; 
            transition: background 0.3s ease, transform 0.2s ease; /* Smooth transitions */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        " 
        onmouseover="this.style.background='linear-gradient(135deg, #d4842e, #c3732a)'; this.style.transform='translateY(-2px)';" 
        onmouseout="this.style.background='linear-gradient(135deg, #eb973c, #d4842e)'; this.style.transform='translateY(0)';">
            Resend Verification Email
        </button>
    </form>
</div>
