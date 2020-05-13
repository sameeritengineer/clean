@extends('manager.layouts.index')
@section('title','chat')
@section('content')
<div class="app-content content container-fluid wrappermessage">
	<div class="content-wrapper">
		<div class="content-header row">
			<div class="messaging">
				<div class="inbox_msg">
					<div class="inbox_people"></div>
					<div class="mesgs">
						<div class="msg_history">
						@foreach($chats as $chat)
						@if($chat->type == "user")
							<div class="incoming_msg">
								@if($chat->user->image != null)
								<div class="incoming_msg_img"><img src="{{asset('profile/'.$chat->user->image)}}"></div>
								@endif
								<div class="received_msg">
									<div class="received_withd_msg">
										<p>{{$chat->message}}</p>
										<span class="time_date">{{$chat->created_at->format('h:i A')}} | {{$chat->created_at->format('M d')}}</span>
									</div>
								</div>
							</div>
						@endif
						@if($chat->type == "provider")
							<div class="outgoing_msg">
								<div class="sent_msg">
									<p>{{$chat->message}}</p>
									<span class="time_date">{{$chat->created_at->format('h:i A')}} | {{$chat->created_at->format('M d')}}</span>
								</div>
							</div>
						@endif
						@endforeach
						</div>
					</div>
				</div>     
			</div>
		</div>
	</div>
</div>
@endsection