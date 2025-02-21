<header id="header" class="header fixed-top d-flex align-items-center header_background">
    <div class="d-flex align-items-center justify-content-between">
        <a href="index.php" class="logo d-flex align-items-center">
            <img src="assets/img/<?php echo "$logo"; ?>" alt="">
            <span class="d-none d-lg-block text-white"><?php echo "$title_page"; ?></span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn text-white"></i>
    </div>
    <div class="search-bar">
        <form action="index.php" class="search-form d-flex align-items-center" method="GET">
            <input type="hidden" name="Page" value="Help">
            <input type="hidden" name="Sub" value="HelpHome">
            <input type="text" name="keyword" placeholder="Cari Bantuan" title="Cari Bantuan">
            <button type="submit" title="Search"><i class="bi bi-search"></i></button>
        </form>
    </div>
    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item d-block d-lg-none">
                <a class="nav-link nav-icon search-bar-toggle " href="javascript:void(0);">
                    <i class="bi bi-search text-white"></i>
                </a>
            </li>
            <!-- <li class="nav-item dropdown">
                <a class="nav-link nav-icon" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class="bi bi-bell text-white"></i>
                    <?php
                        if(!empty($JumlahNotifikasi)){
                            echo '<span class="badge bg-danger rounded-pill badge-number">'.$JumlahNotifikasi.'</span>';
                        }
                    ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                    <li class="dropdown-header">
                        <?php
                            if(!empty($JumlahNotifikasi)){
                                echo 'Anda Memiliki '.$JumlahNotifikasi.' Pemberitahuan';
                            }else{
                                echo 'Tidak Ada Pemberitahuan<br>';
                                echo '<small><a href="index.php?Page=Notifikasi">Lihat Semua Pemberitahuan</a></small>';
                            }
                        ?>
                    </li>
                    <?php
                        if(!empty($JumlahNotifikasi)){
                            //Looping Notifikasi
                            $QryNotifikasi = mysqli_query($Conn, "SELECT*FROM notifikasi WHERE id_akses='$SessionIdAkses' AND status_notifikasi='Pending' ORDER BY id_notifikasi DESC LIMIT 10");
                            while ($DataNotifikasi = mysqli_fetch_array($QryNotifikasi)) {
                                $id_notifikasi= $DataNotifikasi['id_notifikasi'];
                                $datetime_notifikasi= $DataNotifikasi['datetime_notifikasi'];
                                $kategori_notifikasi= $DataNotifikasi['kategori_notifikasi'];
                                $notifikasi= $DataNotifikasi['notifikasi'];
                                $status_notifikasi= $DataNotifikasi['status_notifikasi'];
                                echo '<li><hr class="dropdown-divider"></li>';
                                echo '<li class="notification-item">';
                                echo '  <i class="bi bi-check-circle text-success"></i>';
                                echo '  <div>';
                                echo '      <h4><a href="index.php?Page=Notifikasi">'.$kategori_notifikasi.'</a></h4>';
                                echo '      <p>'.$notifikasi.'</p>';
                                echo '  </div>';
                                echo '</li>';
                            }
                        }
                    ?>
                </ul>
            </li> -->
            
            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <?php
                        if($SessionModeAkses=="Anggota"){
                            echo '<img src="assets/img/Anggota/'.$SessionGambar.'" alt="Profile" class="rounded-circle">';
                            echo '<span class="d-none d-md-block dropdown-toggle ps-2 text-white">'.$SessionNama.'</span>';
                        }else{
                            echo '<img src="assets/img/User/'.$SessionGambar.'" alt="Profile" class="rounded-circle">';
                            echo '<span class="d-none d-md-block dropdown-toggle ps-2 text-white">'.$SessionNama.'</span>';
                        }
                    ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6><?php echo $SessionNama;?></h6>
                        <span><?php echo $SessionAkses;?></span>
                    </li>
                    <?php
                        if($SessionModeAkses=="Anggota"){
                            echo '<li>';
                            echo '  <hr class="dropdown-divider">';
                            echo '</li>';
                            echo '<li>';
                            echo '  <a class="dropdown-item d-flex align-items-center" href="index.php?Page=MyProfile">';
                            echo '      <i class="bi bi-person"></i>';
                            echo '      <span>Profil Saya</span>';
                            echo '  </a>';
                            echo '</li>';
                            echo '<li>';
                            echo '  <hr class="dropdown-divider">';
                            echo '</li>';
                            echo '<li>';
                            echo '  <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalLogout">';
                            echo '      <i class="bi bi-box-arrow-right"></i>';
                            echo '      <span>Keluar</span>';
                            echo '  </a>';
                            echo '</li>';
                        }else{
                            echo '<li>';
                            echo '  <hr class="dropdown-divider">';
                            echo '</li>';
                            echo '<li>';
                            echo '  <a class="dropdown-item d-flex align-items-center" href="index.php?Page=MyProfile">';
                            echo '      <i class="bi bi-person"></i>';
                            echo '      <span>Profil Saya</span>';
                            echo '  </a>';
                            echo '</li>';
                            echo '<li>';
                            echo '  <hr class="dropdown-divider">';
                            echo '</li>';
                            echo '<li>';
                            echo '  <a class="dropdown-item d-flex align-items-center" href="index.php?Page=Help&Sub=HelpHome">';
                            echo '      <i class="bi bi-question-circle"></i>';
                            echo '      <span>Bantuan</span>';
                            echo '  </a>';
                            echo '</li>';
                            echo '<li>';
                            echo '  <hr class="dropdown-divider">';
                            echo '</li>';
                            echo '<li>';
                            echo '  <a class="dropdown-item d-flex align-items-center" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#ModalLogout">';
                            echo '      <i class="bi bi-box-arrow-right"></i>';
                            echo '      <span>Keluar</span>';
                            echo '  </a>';
                            echo '</li>';
                        }
                    ?>
                </ul>
            </li>
        </ul>
    </nav>
</header>