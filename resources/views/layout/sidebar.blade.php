<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <a class="nav-link" href="dashboard">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>
            <a class="nav-link" href="diagnosa">
                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                Diagnosa
            </a>
            @auth
                @if (Auth::user()->level === 'superadmin')
            <a class="nav-link" href="datauser">
                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                Data User
            </a>
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                Data Pakar
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="datagangguan">Data Gangguan</a>
                    <a class="nav-link" href="datapenyebab">Data Penyebab</a>
                    <a class="nav-link" href="datasolusi">Data Solusi </a>
                    <a class="nav-link" href="datakategorisolusi">Data Kategori Solusi</a>
                    <a class="nav-link" href="dataaturan">Data Aturan</a>
                </nav>
            </div>
                    </a>
                @endif
            @endauth
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small">Logged in as:</div>
        Iconnet
    </div>
</nav>