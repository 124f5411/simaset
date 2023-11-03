<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <img src="{{ asset('logo/draw2.png') }}" alt="" width="50px">
        </div>
        <div class="sidebar-brand-text mx-3">{{ config('app.name') }}</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ ($title == "Dashboard") ? "active" : "" }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Data
    </div>
    <li class="nav-item {{ ($title == "Usulan") ? "active" : "" }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSSH"
            aria-expanded="true" aria-controls="collapseSSH">

            <i class="fas fa-money-check-alt"></i>
            <span>Usulan</span>
        </a>
        <div id="collapseSSH" class="collapse {{ ($title == "Usulan") ? "show" : "" }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ ($page == "SSH") ? "active" : "" }}" href="{{ route('ssh.index') }}">SSH</a>
                <a class="collapse-item {{ ($page == "ASB") ? "active" : "" }}"  href="{{ route('asb.index') }}">ASB</a>
                <a class="collapse-item {{ ($page == "HSPK") ? "active" : "" }}" href="{{ route('hspk.index') }}">HSPK</a>
                <a class="collapse-item {{ ($page == "SBU") ? "active" : "" }}" href="{{ route('sbu.index') }}">SBU</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Pages Collapse Menu -->
    @if (Auth::user()->level != 'admin')
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-folder"></i>
            <span>Barang</span>
        </a>
        <div id="collapseTwo" class="collapse {{ ($title == "Barang") ? "show" : "" }}" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded ">
                <a class="collapse-item {{ ($page == "Tanah") ? "active" : "" }}" href="{{ route('tanah.index') }}">Tanah</a>
                <a class="collapse-item {{ ($page == "Peralatan") ? "active" : "" }}" href="{{ route('peralatan.index') }}">Peralatan & Mesin</a>
                <a class="collapse-item" href="buttons.html">Gedung & Bangunan</a>
                <a class="collapse-item" href="buttons.html">Jalan & Irigasi</a>
                <a class="collapse-item" href="buttons.html">Aset Tetap Lainnya</a>
                <a class="collapse-item text-wrap" {{ ($page == "KDP") ? "active" : "" }} href="{{ route('kdp.index') }}">Kontruksi Dalam Pengerjaan</a>
            </div>
        </div>
    </li>
    <li class="nav-item {{ ($title == "Kontrak") ? "active" : "" }}">
        <a class="nav-link" href="{{ route('kontrak.index') }}">
            <i class="fas fa-file"></i>
            <span>Kontrak</span></a>
    </li>
    <!-- Nav Item - Utilities Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
            aria-expanded="true" aria-controls="collapseUtilities">
            <i class="far fa-file"></i>
            <span>Kartu Inventaris Barang</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="utilities-color.html">KIB A</a>
                <a class="collapse-item" href="utilities-border.html">KIB B</a>
                <a class="collapse-item" href="utilities-animation.html">KIB C</a>
                <a class="collapse-item" href="utilities-animation.html">KIB D</a>
                <a class="collapse-item" href="utilities-animation.html">KIB E</a>
                <a class="collapse-item" href="utilities-animation.html">KIB F</a>
            </div>
        </div>
    </li>
    @endif


    <!-- Nav Item - Pages Collapse Menu -->
    @if (Auth::user()->level == 'admin')
        <li class="nav-item {{ ($title == "Referensi") ? "active" : "" }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                aria-expanded="true" aria-controls="collapsePages">

                <i class="fas fa-asterisk"></i>
                <span>Referensi</span>
            </a>
            <div id="collapsePages" class="collapse {{ ($title == "Referensi") ? "show" : "" }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item {{ ($page == "Hak") ? "active" : "" }}" href="{{ route('hak_tanah.index') }}">Hak Tanah</a>
                    <a class="collapse-item {{ ($page == "Satuan") ? "active" : "" }}" href="{{ route('satuan.index') }}">Satuan</a>
                    <a class="collapse-item {{ ($page == "Status") ? "active" : "" }}" href="{{ route('status_tanah.index') }}">Status Tanah</a>
                    <a class="collapse-item {{ ($page == "Jenis") ? "active" : "" }}" href="{{ route('jenis.index') }}">Jenis Aset</a>
                    <a class="collapse-item {{ ($page == "Kib") ? "active" : "" }}" href="{{ route('kib.index') }}">Master KIB</a>
                    <a class="collapse-item {{ ($page == "Kode") ? "active" : "" }}" href="{{ route('kode_barang.index') }}">Kode Barang</a>
                    <a class="collapse-item {{ ($page == "Rekening") ? "active" : "" }}" href="{{ route('rekening_belanja.index') }}">Rekening Belanja</a>
                    <a class="collapse-item {{ ($page == "Kelompok") ? "active" : "" }}" href="{{ route('kelompok.index') }}">Kelompok SSH</a>
                </div>
            </div>
        </li>
    @endif
    @if (Auth::user()->level == 'admin' || Auth::user()->level == 'bendahara')
        <li class="nav-item {{ ($title == "Pengaturan") ? "active" : "" }}">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCog"
                aria-expanded="true" aria-controls="collapseCog">

                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
            </a>
            <div id="collapseCog" class="collapse {{ ($title == "Pengaturan") ? "show" : "" }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    @if (Auth::user()->level == "admin")
                        <a class="collapse-item {{ ($page == "Admin") ? "active" : "" }}" href="{{ route('admin.index') }}">Admin</a>
                        <a class="collapse-item {{ ($page == "Admin Aset") ? "active" : "" }}" href="{{ route('adminaset.index') }}">Admin Aset</a>
                        <a class="collapse-item {{ ($page == "Bendahara") ? "active" : "" }}" href="{{ route('bendahara.index') }}">Bendahara</a>
                        <a class="collapse-item {{ ($page == "Instansi") ? "active" : "" }}" href="{{ route('instansi.index') }}">Instansi</a>
                    @endif
                    @if (Auth::user()->level == "admin" || Auth::user()->level == "bendahara")
                        <a class="collapse-item {{ ($page == "Operator") ? "active" : "" }}" href="{{ route('operator.index') }}">Operator</a>
                    @endif
                    @if (Auth::user()->level == "bendahara")
                        <a class="collapse-item {{ ($page == "PB") ? "active" : "" }}" href="{{ route('pb.index') }}">Pengguna Barang</a>
                    @endif
                </div>
            </div>
        </li>
    @endif

    @if (Auth::user()->level == "aset")
    <li class="nav-item {{ ($title == "Pantau") ? "active" : "" }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMon"
            aria-expanded="true" aria-controls="collapseMon">

            <i class="fas fa-cog"></i>
            <span>Pantau Usulan</span>
        </a>
        <div id="collapseMon" class="collapse {{ ($title == "Pantau") ? "show" : "" }}" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item {{ ($page == "PANTAUSSH") ? "active" : "" }}" href="{{ route('monitor.index','ssh') }}">SSH</a>
                    <a class="collapse-item {{ ($page == "PANTAUASB") ? "active" : "" }}" href="{{ route('monitor.index','asb') }}">ASB</a>
                    <a class="collapse-item {{ ($page == "PANTAUHSPK") ? "active" : "" }}" href="{{ route('monitor.index','hspk') }}">HSPK</a>
                    <a class="collapse-item {{ ($page == "PANTAUSBU") ? "active" : "" }}" href="{{ route('monitor.index','sbu') }}">SBU</a>
            </div>
        </div>
    </li>
    @endif



    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
