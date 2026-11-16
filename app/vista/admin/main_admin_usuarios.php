<?php
require_once __DIR__ . '/helpers.php';

$heroIcono  = 'fa-solid fa-users-gear';
$heroTitulo = 'Administración de usuarios';
$heroTexto  = 'Busca usuarios, consulta su ficha, edita sus datos, cambia su rol o elimina cuentas sin suscripciones.';
$heroDatos  = $resumen ? [[$resumen['usuarios'], 'usuarios'], [$resumen['administradores'], 'administradores'], [$resumen['en_linea'], 'con sesión abierta']] : [];

$hayFiltros = $filtros['q'] !== '' || $filtros['rol'] !== '' || $filtros['estado'] !== '' || $filtros['orden'] !== 'recientes';
$desde = $lista['total'] ? (($pagina - 1) * $porPagina) + 1 : 0;
$hasta = min($lista['total'], $pagina * $porPagina);
?>
<main class="app-main adm">
  <div class="app-content-header">
    <div class="container-fluid">
      <?php include __DIR__ . '/cabecera_seccion.php'; ?>
    </div>
  </div>

  <div class="app-content">
    <div class="container-fluid">

      <?php if ($errorDatos): ?>
        <div class="alert alert-danger admin-alerta" role="alert">
          <i class="fa-solid fa-circle-exclamation me-2"></i> No se pudieron leer los usuarios de la base de datos.
        </div>
      <?php endif; ?>

      <!-- Búsqueda y filtros -->
      <form class="adm-filtros" method="get" action="<?= BASE_PATH ?>/admin/usuarios" data-reveal style="--i: 1">
        <label class="adm-buscar">
          <i class="fa-solid fa-magnifying-glass"></i>
          <input type="search" name="q" value="<?= adm_e($filtros['q']) ?>" maxlength="80" placeholder="Buscar por nombre, correo, teléfono o país" aria-label="Buscar usuarios">
        </label>
        <select name="rol" aria-label="Filtrar por rol" data-autoenviar>
          <option value="">Todos los roles</option>
          <?php foreach (['Usuario', 'Administrador', 'Superusuario'] as $r): ?>
            <option value="<?= $r ?>"<?= $filtros['rol'] === $r ? ' selected' : '' ?>><?= $r ?></option>
          <?php endforeach; ?>
        </select>
        <select name="estado" aria-label="Filtrar por estado" data-autoenviar>
          <option value="">Cualquier estado</option>
          <option value="en_linea"<?= $filtros['estado'] === 'en_linea' ? ' selected' : '' ?>>Con sesión abierta</option>
          <option value="desconectado"<?= $filtros['estado'] === 'desconectado' ? ' selected' : '' ?>>Desconectados</option>
        </select>
        <select name="orden" aria-label="Ordenar" data-autoenviar>
          <option value="recientes"<?= $filtros['orden'] === 'recientes' ? ' selected' : '' ?>>Más recientes</option>
          <option value="antiguos"<?= $filtros['orden'] === 'antiguos' ? ' selected' : '' ?>>Más antiguos</option>
          <option value="nombre"<?= $filtros['orden'] === 'nombre' ? ' selected' : '' ?>>Nombre (A-Z)</option>
          <option value="acceso"<?= $filtros['orden'] === 'acceso' ? ' selected' : '' ?>>Último acceso</option>
        </select>
        <button type="submit" class="btnRegistrarse"><i class="fa-solid fa-filter"></i> Filtrar</button>
        <?php if ($hayFiltros): ?><a class="adm-limpiar" href="<?= BASE_PATH ?>/admin/usuarios"><i class="fa-solid fa-xmark"></i> Limpiar</a><?php endif; ?>
      </form>

      <p class="adm-contador" data-reveal style="--i: 2">
        <?= $lista['total'] ? "Mostrando <strong>{$desde}–{$hasta}</strong> de <strong>{$lista['total']}</strong> usuario" . ($lista['total'] === 1 ? '' : 's') : 'Sin resultados' ?>
      </p>

      <?php if (!$lista['filas']): ?>
        <div class="adm-vacio" data-reveal>
          <i class="fa-regular fa-face-frown-open"></i>
          <h4>No hay usuarios que coincidan</h4>
          <p>Prueba con otra búsqueda o quita algún filtro.</p>
        </div>
      <?php else: ?>
      <div class="adm-tabla-wrap" data-reveal style="--i: 3">
        <table class="adm-tabla">
          <thead>
            <tr>
              <th>Usuario</th><th>País</th><th>Rol</th><th class="num">Servicios</th><th>Registro</th><th>Último acceso</th><th>Estado</th><th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($lista['filas'] as $i => $u):
              $nombreCompleto = trim($u['Nombre'] . ' ' . $u['Apellido']);
              $enLinea = (int) $u['En_linea'] === 1; ?>
              <tr style="--r: <?= $i ?>">
                <td data-label="Usuario">
                  <div class="adm-persona">
                    <span class="adm-avatar" style="background: <?= adm_e(adm_color($u['Nombre'], $u['Apellido'])) ?>"><?= adm_e(adm_inicial($u['Nombre'], $u['Correo'])) ?></span>
                    <span class="adm-persona__texto"><strong><?= adm_e($nombreCompleto) ?></strong><small><?= adm_e($u['Correo']) ?></small></span>
                  </div>
                </td>
                <td data-label="País"><?= adm_e($u['Pais']) ?></td>
                <td data-label="Rol"><span class="adm-rol <?= adm_rol_clase($u['Rol']) ?>"><?= adm_e($u['Rol']) ?></span></td>
                <td data-label="Servicios" class="num"><?= (int) $u['Servicios_vigentes'] ?></td>
                <td data-label="Registro" title="<?= adm_e(adm_fecha($u['Fecha_creacion'])) ?>"><?= adm_e(adm_hace($u['Fecha_creacion'])) ?></td>
                <td data-label="Último acceso" title="<?= adm_e(adm_fecha($u['Ultimo_login'])) ?>"><?= adm_e(adm_hace($u['Ultimo_login'])) ?></td>
                <td data-label="Estado">
                  <span class="adm-estado<?= $enLinea ? ' on' : '' ?>"><i></i> <?= $enLinea ? 'Sesión abierta' : 'Desconectado' ?></span>
                </td>
                <td class="acc">
                  <button type="button" class="adm-btn adm-btn--suave" data-ficha="<?= adm_e($u['Correo']) ?>">
                    <i class="fa-solid fa-id-card"></i> Ver ficha
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <p class="adm-nota"><i class="fa-solid fa-circle-info"></i> «Sesión abierta» se marca al iniciar sesión y se quita al cerrarla: si alguien cierra el navegador sin pulsar «Cerrar sesión», seguirá apareciendo como abierta.</p>

      <?php $ruta = '/admin/usuarios'; include __DIR__ . '/paginacion.php'; ?>
      <?php endif; ?>
    </div>
  </div>
</main>

<!-- Ficha de usuario (un solo modal, lo rellena admin-usuarios.js) -->
<div class="modal fade" id="modalUsuario" tabindex="-1" aria-labelledby="usuTitulo" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <div class="usu-cab">
          <span class="adm-avatar adm-avatar--grande" id="usuAvatar"></span>
          <div class="usu-cab__texto">
            <h5 class="modal-title" id="usuTitulo">Cargando…</h5>
            <div class="usu-cab__correo" id="usuCorreo"></div>
            <div class="usu-cab__chips" id="usuChips"></div>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <div class="usu-error d-none" id="usuError"></div>

        <section class="usu-seccion">
          <h6><i class="fa-solid fa-address-card"></i> Datos</h6>
          <dl class="usu-datos" id="usuDatos"></dl>
        </section>

        <section class="usu-seccion">
          <h6><i class="fa-solid fa-box-open"></i> Servicios contratados</h6>
          <ul class="usu-lista" id="usuServicios"></ul>
        </section>

        <section class="usu-seccion">
          <h6><i class="fa-solid fa-clock-rotate-left"></i> Actividad reciente</h6>
          <ul class="usu-lista" id="usuEventos"></ul>
          <a class="adm-enlace" id="usuVerActividad" href="#">Ver toda su actividad →</a>
        </section>

        <section class="usu-seccion" id="usuSeccionEditar">
          <h6><i class="fa-solid fa-pen-to-square"></i> Editar datos</h6>
          <form id="usuFormEditar" class="usu-form" autocomplete="off">
            <label>Nombre<input type="text" name="nombre" maxlength="50" required></label>
            <label>Apellido<input type="text" name="apellido" maxlength="50"></label>
            <label>Teléfono<input type="text" name="telefono" maxlength="20" required></label>
            <label>País<input type="text" name="pais" maxlength="50" required></label>
            <div class="usu-form__acciones"><button type="submit" class="btnRegistrarse"><i class="fa-solid fa-floppy-disk"></i> Guardar cambios</button></div>
          </form>
        </section>

        <section class="usu-seccion" id="usuSeccionRol">
          <h6><i class="fa-solid fa-user-shield"></i> Rol</h6>
          <div class="usu-rol">
            <select id="usuRolSelect" aria-label="Nuevo rol"></select>
            <button type="button" class="btnRegistrarse" id="usuBtnRol"><i class="fa-solid fa-check"></i> Cambiar rol</button>
          </div>
          <p class="adm-nota" id="usuRolNota"></p>
        </section>

        <section class="usu-seccion usu-peligro" id="usuSeccionEliminar">
          <h6><i class="fa-solid fa-triangle-exclamation"></i> Zona de peligro</h6>
          <p>Eliminar la cuenta borra también su historial de accesos y sus chats. No se puede deshacer.</p>
          <button type="button" class="adm-btn adm-btn--peligro" id="usuBtnEliminar"><i class="fa-solid fa-trash"></i> Eliminar usuario</button>
        </section>

        <p class="adm-nota" id="usuSinPermisos"></p>
      </div>
    </div>
  </div>
</div>
