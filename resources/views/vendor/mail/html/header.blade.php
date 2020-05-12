<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://i.imgur.com/QHqqOyi.gif" class="logo" alt="Nummereins Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
