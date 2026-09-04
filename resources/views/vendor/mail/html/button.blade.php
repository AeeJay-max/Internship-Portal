@props(['url', 'color' => 'primary', 'align' => 'center'])
<table class="action" align="{{ $align }}" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="{{ $align }}">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td align="{{ $align }}">
                        <table border="0" cellpadding="0" cellspacing="0" role="presentation">
                            <tr>
                                <td>
                                    <a href="{{ $url }}"
                                       style="background-color:#011C3E; border:none; border-radius:8px; color:#ffffff !important; display:inline-block; font-family:Helvetica,Arial,sans-serif; font-size:15px; font-weight:bold; line-height:1; padding:16px 36px; text-decoration:none; text-align:center;">
                                        {{ $slot }}
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
