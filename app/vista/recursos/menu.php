<nav class="app-header navbar navbar-expand bg-body" style="background: var(--azul) !important; border-bottom: 1px solid rgba(255,255,255,0.05);">
  <div class="container-fluid">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button" style="color: rgba(255,255,255,0.6);">
          <i class="fa-solid fa-bars"></i>
        </a>
      </li>
      <li class="nav-item d-none d-md-block">
        <a href="<?= BASE_PATH ?>" class="nav-link" style="color: var(--verde); font-weight: 600; font-size: 1.1rem;">
          ForexFalcon
        </a>
      </li>
    </ul>

    <ul class="navbar-nav ms-auto">

      <!-- Botón pantalla completa -->
      <li class="nav-item">
        <a class="nav-link" href="#" data-lte-toggle="fullscreen" style="color: rgba(255,255,255,0.5);">
          <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
          <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
        </a>
      </li>

      <!-- Menú desplegable del perfil -->
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown" style="padding: 6px 14px 6px 8px; border-radius: 50px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06); transition: all 0.3s ease; gap: 10px; align-items: center; display: flex; text-decoration: none; line-height: 1;">

          <!-- Avatar (con margen a la izquierda para que no toque el borde) -->
          <span class="user-image rounded-circle shadow avatar-inicial" style="background-color: <?= $colorAvatar ?>; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; color: #fff; font-weight: bold; font-size: 14px; border: none; margin-left: 4px; flex-shrink: 0;">
            <?= $inicial ?>
          </span>

          <!-- Nombre -->
          <span class="d-none d-md-inline" style="color: #fff; font-weight: 500; font-size: 0.9rem; line-height: 1; display: inline-flex; align-items: center; margin-left: 2px;">
            <?= $_SESSION['nombre'] . " " . $_SESSION['apellido']?>
          </span>

          <!-- Chevron -->
          <i class="fa-solid fa-chevron-down" style="font-size: 10px; color: rgba(255,255,255,0.4); flex-shrink: 0; display: inline-flex; align-items: center;"></i>

        </a>
        
        <!-- Dropdown del perfil -->
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end" style="background: var(--azul2) !important; border: 1px solid rgba(255,255,255,0.08) !important; border-radius: 12px !important; padding: 6px !important; box-shadow: 0 16px 48px rgba(0,0,0,0.4) !important; min-width: 220px;">
          
          <!-- Cabecera del perfil -->
          <li class="user-header text-bg-primary" style="background: linear-gradient(135deg, var(--azul), var(--azul2)) !important; padding: 20px 16px 16px !important; border-radius: 10px 10px 0 0 !important; text-align: center !important; margin: -6px -6px 0 !important;">
            <span class="rounded-circle shadow avatar-inicial avatar-inicial-lg" style="width: 80px; height: 80px; font-size: 32px; background-color: <?= $colorAvatar ?>; display: inline-flex; align-items: center; justify-content: center; color: #fff; font-weight: bold; border: 3px solid rgba(255,255,255,0.15);">
              <?= $inicial ?>
            </span>
            <p style="color: #fff; font-weight: 600; font-size: 0.95rem; margin: 12px 0 0 0;">
              <?= $_SESSION['nombre'] . " " . $_SESSION['apellido']?>
            </p>
            <small style="color: rgba(255,255,255,0.4); font-size: 0.75rem;">
              <i class="fa-regular fa-envelope me-1"></i> <?= $_SESSION['email'] ?? 'usuario@forexfalcon.com' ?>
            </small>
          </li>

          <!-- Separador -->
          <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.06) !important; margin: 6px 0 !important;"></li>

          <!-- Botón CONFIGURACIÓN -->
          <li>
            <a href="#" class="dropdown-item" style="display: flex !important; align-items: center !important; gap: 12px !important; padding: 10px 14px !important; border-radius: 8px !important; color: rgba(255,255,255,0.7) !important; transition: all 0.2s ease !important; text-decoration: none !important;">
              <i class="fa-solid fa-gear" style="width: 20px; text-align: center; font-size: 0.95rem; color: rgba(255,255,255,0.4); transition: color 0.2s ease;"></i>
              <span style="font-size: 0.88rem; font-weight: 500;">Configuración</span>
              <span style="margin-left: auto; font-size: 10px; color: rgba(255,255,255,0.2);">
                <i class="fa-solid fa-chevron-right" style="font-size: 10px;"></i>
              </span>
            </a>
          </li>

          <!-- Botón CERRAR SESIÓN -->
          <li>
            <a href="<?= BASE_PATH . "/logout"?>" class="dropdown-item" style="display: flex !important; align-items: center !important; gap: 12px !important; padding: 10px 14px !important; border-radius: 8px !important; color: #ff6b6b !important; transition: all 0.2s ease !important; text-decoration: none !important;">
              <i class="fa-solid fa-right-from-bracket" style="width: 20px; text-align: center; font-size: 0.95rem; color: #ff6b6b; transition: opacity 0.2s ease;"></i>
              <span style="font-size: 0.88rem; font-weight: 500; color: #ff6b6b;">Cerrar sesión</span>
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<!-- Estilos del perfil -->
<style>
  /* ── Avatar ──────────────────────────────────────────── */
  .avatar-inicial {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    color: #fff;
    font-weight: bold;
    font-size: 14px;
    line-height: 1;
    border: 2px solid rgba(255, 255, 255, 0.1);
    transition: border-color 0.3s ease;
  }
  
  .user-menu .dropdown-toggle:hover .avatar-inicial {
    border-color: var(--verde);
  }

  .avatar-inicial-lg {
    width: 80px;
    height: 80px;
    font-size: 32px;
    border-width: 3px;
    border-color: rgba(255, 255, 255, 0.15);
  }

  /* ── Dropdown hover effects ────────────────────────── */
  .dropdown-item:hover {
    background: rgba(145, 221, 78, 0.08) !important;
    color: var(--verde) !important;
  }
  .dropdown-item:hover i {
    color: var(--verde) !important;
    opacity: 1 !important;
  }

  /* ── Cerrar sesión hover ────────────────────────────── */
  .dropdown-item[href*="logout"]:hover {
    background: rgba(255, 107, 107, 0.08) !important;
    color: #ff6b6b !important;
  }
  .dropdown-item[href*="logout"]:hover i {
    color: #ff6b6b !important;
    opacity: 1 !important;
  }

  /* ── Perfil hover ───────────────────────────────────── */
  .user-menu .dropdown-toggle:hover {
    background: rgba(255, 255, 255, 0.08) !important;
    border-color: rgba(145, 221, 78, 0.2) !important;
  }

  /* ── Scrollbar del dropdown ────────────────────────── */
  .dropdown-menu::-webkit-scrollbar {
    width: 3px;
  }
  .dropdown-menu::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.02);
  }
  .dropdown-menu::-webkit-scrollbar-thumb {
    background: rgba(145, 221, 78, 0.3);
    border-radius: 10px;
  }
</style>

<!-- Sidebar con estilo ForexFalcon -->
<aside class="app-sidebar shadow" data-bs-theme="dark" style="background: var(--azul) !important; border-right: 1px solid rgba(255,255,255,0.05);">
  <div class="sidebar-brand" style="padding: 16px 0;">
    <a href="<?= BASE_PATH ?>" class="brand-link" style="display: flex; align-items: center; justify-content: center; text-decoration: none; gap: 10px;">
      <img class="zoom" src="<?= LINKS_PATH . "/imagenes/logo.png" ?>" alt="Logo forexfalcon" width="50px" style="filter: drop-shadow(0 0 12px rgba(145, 221, 78, 0.15));">
    </a>
  </div>
  
  <div class="sidebar-wrapper" style="padding: 8px 12px;">
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation" aria-label="Main navigation" data-accordion="false" id="navigation">
        
        <!-- Tienda -->
        <li class="nav-item">
          <a href="<?= BASE_PATH . "/recursos" ?>" class="nav-link active" style="border-radius: 10px; padding: 10px 16px; color: rgba(255,255,255,0.7); transition: all 0.3s ease; display: flex; align-items: center; gap: 12px;">
            <i class="fa-solid fa-shop" style="color: var(--verde); font-size: 1rem; width: 20px; text-align: center;"></i>
            <p style="margin: 0; font-weight: 500; font-size: 0.9rem; flex: 1;">Tienda</p>
          </a>
        </li>

        <!-- Separador -->
        <li class="nav-item" style="padding: 8px 0;">
          <hr style="border-color: rgba(255,255,255,0.06); margin: 0;">
        </li>

        <!-- Sección: Mis servicios -->
        <li class="nav-item" style="padding-top: 12px;">
          <span class="nav-section-title" style="display: block; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,0.2); padding: 0 16px 8px;">
            Mis Servicios
          </span>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link" style="border-radius: 10px; padding: 10px 16px; color: rgba(255,255,255,0.4); transition: all 0.3s ease; display: flex; align-items: center; gap: 12px;">
            <i class="fa-solid fa-copy" style="color: var(--verde); font-size: 1rem; width: 20px; text-align: center;"></i>
            <p style="margin: 0; font-weight: 400; font-size: 0.9rem; flex: 1;">CopyTrading</p>
            <span class="service-status active" style="font-size: 9px; color: var(--verde); font-weight: 500;">● Activo</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link" style="border-radius: 10px; padding: 10px 16px; color: rgba(255,255,255,0.4); transition: all 0.3s ease; display: flex; align-items: center; gap: 12px;">
            <i class="fa-solid fa-user-graduate" style="color: var(--naranja-dim); font-size: 1rem; width: 20px; text-align: center;"></i>
            <p style="margin: 0; font-weight: 400; font-size: 0.9rem; flex: 1;">Mentorías</p>
            <span class="service-status inactive" style="font-size: 9px; color: var(--naranja); font-weight: 500;">○ Inactivo</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link" style="border-radius: 10px; padding: 10px 16px; color: rgba(255,255,255,0.4); transition: all 0.3s ease; display: flex; align-items: center; gap: 12px;">
            <i class="fa-solid fa-microchip" style="color: var(--naranja-dim); font-size: 1rem; width: 20px; text-align: center;"></i>
            <p style="margin: 0; font-weight: 400; font-size: 0.9rem; flex: 1;">Bots</p>
            <span class="service-status inactive" style="font-size: 9px; color: var(--naranja); font-weight: 500;">○ Inactivo</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link" style="border-radius: 10px; padding: 10px 16px; color: rgba(255,255,255,0.4); transition: all 0.3s ease; display: flex; align-items: center; gap: 12px;">
            <i class="fa-solid fa-gem" style="color: var(--naranja-dim); font-size: 1rem; width: 20px; text-align: center;"></i>
            <p style="margin: 0; font-weight: 400; font-size: 0.9rem; flex: 1;">Análisis Premium</p>
            <span class="service-status inactive" style="font-size: 9px; color: var(--naranja); font-weight: 500;">○ Inactivo</span>
          </a>
        </li>

        <!-- Separador -->
        <li class="nav-item" style="padding: 8px 0;">
          <hr style="border-color: rgba(255,255,255,0.06); margin: 0;">
        </li>

      </ul>
    </nav>

    <footer class="app-footer">
      <strong>© 2026 ForexFalcon</strong>
    </footer>
  </div>
</aside>