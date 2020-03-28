@php 
$id = App\User::where('email',$email)->first();
@endphp

<div style="width:100%; max-width:800px; margin: auto;">
<table width="100%" border="0">
 	<tbody>
    <tr>
    <td bgcolor="#e5e5e5" align="center">
	<img src="http://smartit.ventures/CS/cleaning_service/public/admin/app-assets/images/logo/Cleanerup.png" alt=""  style="width: 230px;margin-top: 15px;margin-bottom: 10px;">
    </td>
    </tr>
	<tr>
	<td height="2"></td>
	</tr>	   
	<tr>
	<td>
		<h3>Hello, {{$id->first_name}}</h3>
		<p style="font-family: arial; line-height: 26px; font-size: 14px">I am very pleased to inform you that Your Registration has been approved by site administration. Now you are authorized to login.</p>
	</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
	 <td bgcolor="#333" style="padding: 10px; color: #fff; text-align: center">&copy; copyright 2018</td>
	</tr>
	</tbody>
</table>
</div>
