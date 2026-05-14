<!-- Pie de pagina -->
<footer class="container-fluid black mt-5 pt-3 pb-2">
  <!-- Boton para ir hacia arriba -->
  <div class="position-relative">
    <a href="#navegacion" class="position-absolute top-0 end-0" title="Ir al inicio de esta página">
      <i class="fas fa-chevron-up pe-2 fa-2x text-white"></i>
    </a>
  </div>
  <!-- Posicion de la empresa y imagen de logo -->
  <div class="row text-white">
    <div class="col-12 col-sm-12 col-md-3 col-lg-2 mb-2 text-center"> 
      <img src="<?= LINKS_PATH . "/imagenes/Logo-CertiTransporte-blanco.png" ?>" width="250px" class="img-fluid" alt="Certitransporte - Servicios Tecnológicos de Formación"/>
    </div>
    <div class="flex-row col-sm-12 col-md-9 col-lg-10">
      <div class="row p-3">
        <div class="col-6 col-sm-6 col-xl-3">
          <p class="h4 negrita">¿Donde estamos?</p>
          <ul>
            <li>Avda. San Francisco Javier, 24</li>
            <li>Edif. HERMES 41018 - Sevilla</li>
            <li>Tfno: (+34) 671 355 000</li>
            <li>info@forexfalcon.com</li>
          </ul>
        </div>
        <div class="col-6 col-sm-6 col-xl-4">
          <p class="h4 negrita">Redes</p>
          <span class="d-block"><a class="orange" href="https://www.instagram.com/certitransporte/">Instagram</a></span>
          <span class="d-block"><a class="orange" href="https://www.linkedin.com/in/certitransporte-certitransporte-376bb2312?miniProfileUrn=urn%3Ali%3Afs_miniProfile%3AACoAAE-OLooB6ECAgJ11phOU4zppv8N89JnSPFY&lipi=urn%3Ali%3Apage%3Ad_flagship3_search_srp_all%3BNBrrijlARQKiTx4EzAfLMQ%3D%3D">Linkeding</a></span>
        </div>
      </div>

    </div>
    <!-- Privacidad, Aviso legal y Mision, Vision y Valores -->
    <div class="col-sm-12 col-md-12 col-lg-12 mt-1">
      <a class="pe-3 float-end text-decoration-none orange" href="<?= BASE_PATH . "/privacidad" ?>">Privacidad</a>
      <a class="pe-3 float-end text-decoration-none orange" href="<?= BASE_PATH . "/aviso_legal"?>">Aviso Legal</a>
      <a class="pe-3 float-end text-decoration-none orange" href="<?= BASE_PATH . "/mision_visionyvalores"?>">Misi&oacute;n, Visi&oacute;n y Valores</a>
    </div>
  </div>
</footer>

