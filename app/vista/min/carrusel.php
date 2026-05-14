<!-- Carrusel Principal -->
<div id="carruselCertiTransporte" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <!-- Imagen formación mercancías -->
    <div class="carousel-item">
      <a role="button" href="<?= BASE_PATH . "/cursos" ?>">
        <picture>
          <source media="(max-width: 435px)" srcset="<?= LINKS_PATH . "/imagenes/Imagen-de-transportistas-de-mercancia-vertical.png" ?>">
          <source media="(max-width: 990px)" srcset="<?= LINKS_PATH . "/imagenes/Imagen-de-transportistas-de-mercancia.png" ?>">
          <img src="<?= LINKS_PATH . "/imagenes/Imagen-de-transportistas-de-mercancia.png" ?>" class="d-block w-100 carruselImagen" alt="Formación de transportistas de mercancía">
        </picture>
      </a>
      <div class="carousel-caption">
        <span class="fw-bold text-center textoCarrusel h2">
          <span class="red">F</span>ormación de <span class="red">T</span>ransportistas de 
          <span class="orange">M</span>ercancías
        </span>
      </div>
    </div>

    <!-- Imagen formación viajeros -->
    <div class="carousel-item active">
      <a role="button" href="<?= BASE_PATH . "/cursos" ?>">
        <picture>
          <source media="(max-width: 435px)" srcset="<?= LINKS_PATH . "/imagenes/Imagen-de-transportistas-de-viajeros-vertical.png" ?>">
          <!-- <source media="(max-width: 990px)" srcset="<?= LINKS_PATH . "/imagenes/Imagen-de-transportistas-de-viajeros-table-2.png" ?>"> -->
          <img src="<?= LINKS_PATH . "/imagenes/Imagen-de-transportistas-de-viajeros.png" ?>" class="d-block w-100 carruselImagen" alt="Formación de transportistas de viajeros">
        </picture>
      </a>
      <div class="carousel-caption">
        <span class="fw-bold text-center textoCarrusel h2">
          <span class="red">F</span>ormación de <span class="red">T</span>ransportistas de 
          <span class="orange">V</span>iajeros
        </span>
      </div>
    </div>

    <!-- Imagen formación mercancías y viajeros -->
    <div class="carousel-item">
      <a role="button" href="<?= BASE_PATH . "/cursos" ?>">
        <picture>
          <source media="(max-width: 435px)" srcset="<?= LINKS_PATH . "/imagenes/Imagen-de-transportistas-de-mercancia-y-viajeros-vertical.png" ?>">
          <!-- <source media="(max-width: 990px)" srcset="<?= LINKS_PATH . "/imagenes/Imagen-de-transportistas-de-mercancia-y-viajeros.png" ?>"> -->
          <img src="<?= LINKS_PATH . "/imagenes/Imagen-de-transportistas-de-mercancia-y-viajeros.png" ?>" class="d-block w-100 carruselImagen" alt="Formación de transportistas de mercancía y viajeros">
        </picture>
      </a>
      <div class="carousel-caption">
        <span class="fw-bold text-center textoCarrusel h2">
          <span class="red">F</span>ormación de <span class="red">T</span>ransportistas de 
          <span class="orange">M</span>ercancías y <span class="orange">V</span>iajeros
        </span>
      </div>
    </div>
  </div>

  <!-- Controles -->
  <button class="carousel-control-prev" type="button" data-bs-target="#carruselCertiTransporte" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Anterior</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carruselCertiTransporte" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Siguiente</span>
  </button>
</div>