</td>

<td width="150px" class="sidebar">
    <div class="sidebarHeader">NAV BAR</div>
    <ul>
        <?php if (isset($user) && $user !== null && $user->isAdmin()):?>
            <li><a href="/admin">Admin panel</a></li>
        <?php endif;?>
        <li><a href="/">Home</a></li>
        <li><a href="/about">About</a></li>
    </ul>
</td>
</tr>
<tr>
    <td class="footer" colspan="<?=$colspan ?? '2'?>">&copy; 2020<?php echo date('Y') !== '2020' ? '-'.date('Y') : ''; ?> Evgen blog</td>
</tr>
</table>

</body>
</html>