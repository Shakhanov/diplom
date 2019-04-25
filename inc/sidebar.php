    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Логотип -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
        <div class="sidebar-brand-icon">
          <div><img src="/img/pictures/icon_doc.png" style="width: 40px; height: 50px;"></div>
        </div>
        <div class="sidebar-brand-text mx-3">Documentor</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Главная -->
      <li class="nav-item active">
        <a class="nav-link" href="index.php">  
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Главная</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
          Документы
        </div>

        <!-- Nav Item - Создать документ -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-folder"></i>
            <span>Создать документ</span>
          </a>
          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <h6 class="collapse-header">Типы документов:</h6>
              <a class="collapse-item" href="main_contract.php">Договор</a>
              <a class="collapse-item" href="main_act.php">Акт</a>
              <a class="collapse-item" href="main_bill.php">Счет</a>
              <a class="collapse-item" href="main_contr_requiz.php">Свой документ</a>
            </div>
          </div>
        </li>

        <!-- Nav Item - Мои документы -->
            <li class="nav-item">
              <a class="nav-link" href="main_mydocs.php">
                <i class="fas fa-fw fa-folder"></i>
                <span>Мои документы</span></a>
              </li>

        <!-- Nav Item - Шаблоны -->
        <li class="nav-item">
          <a class="nav-link" href="main_templates.php">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Шаблоны</span></a>
          </li>

          <!-- Nav Item - Контрагенты -->
          <li class="nav-item">
            <a class="nav-link" href="main_contractors.php">
              <i class="fas fa-fw fa-chart-area"></i>
              <span>Контрагенты</span></a>
            </li>

            <!-- Nav Item - Реквизиты -->
            <li class="nav-item">
              <a class="nav-link" href="main_requiz.php">
                <i class="fas fa-fw fa-chart-area"></i>
                <span>Реквизиты</span></a>
              </li>


              <!-- Divider -->
              <hr class="sidebar-divider">

              <!-- Heading -->
              <div class="sidebar-heading">
                Дополнительно
              </div>

              <!-- Nav Item - Настройки -->
              <li class="nav-item">
                <a class="nav-link" href="main_settings.php">
                  <i class="fas fa-fw fa-cog"></i>
                  <span>Настройки аккаунта</span></a>
                </li>

                <!-- Nav Item - Помощь -->
                <li class="nav-item">
                  <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Помощь</span></a>
                  </li>

                  <!-- Divider -->
                  <hr class="sidebar-divider d-none d-md-block">

                  <!-- Sidebar Toggler (Sidebar) -->
                  <div class="text-center d-none d-md-inline">
                    <button class="rounded-circle border-0" id="sidebarToggle"></button>
                  </div>

                </ul>
    <!-- End of Sidebar -->