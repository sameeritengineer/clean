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
		<p style="font-family: arial; line-height: 26px; font-size: 14px">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
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
