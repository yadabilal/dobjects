<nav class="my-account-navigation">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="{{route('profile')}}" >Anasayfa</a>
        </li>
        <li class="nav-item">
            <a class="nav-link"  href="{{route('profile.edit')}}" >Profil</a>
        </li>
        <li class="nav-item">
            <a class="nav-link"  href="{{route('security')}}" >Şifre Ve Güvenlik</a>
        </li>
        <li class="nav-item">
            <a class="nav-link"  href="{{route('my_order')}}" >Siparişler</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Çıkış Yap</a>
        </li>
    </ul>
</nav>
