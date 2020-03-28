@php 
$id = App\User::where('email',$email)->first();
$otp = App\Otp::where('user_id',$id->id)->first();
@endphp
Otp is {{$otp->otp}}