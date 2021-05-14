<tr>
  <td class="header">
    <a href="{{ $url }}" style="display: inline-block;">
      @if (trim($slot) === 'Laravel')
      <img src="https://hashimproperty.com/wp-content/uploads/2020/07/HASHIM_PROPERTY.png" class="logo"
        alt="hashimproperty Logo">
      @else
      {{ $slot }}
      @endif
    </a>
  </td>
</tr>