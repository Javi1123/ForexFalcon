<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Forexfalcon - Acceso</title>
    
    <link rel="shortcut icon" href="<?= LINKS_PATH . "/imagenes/favicon.ico" ?>">
    <link rel="forexfalcon" type="image/x-icon" href="<?= LINKS_PATH . "/imagenes/favicon.ico" ?>">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#91DD4E" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#070D25" media="(prefers-color-scheme: dark)" />

    <meta name="title" content="Forexfalcon | Acceso" />
    <meta name="author" content="Forexfalcon" />
    <meta name="description" content="Acceso al panel de administración de Forexfalcon. Inicia sesión con tus credenciales para gestionar alumnos, cursos, recibos y matriculados.">
    <meta name="keywords" content="forexfalcon, login, inicio de sesión, panel admin, acceso administrador, gestión de transporte, credenciales">

    <script src="<?= LINKS_PATH . "/js/all.min.js" ?>"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/css/adminlte.min.css" crossorigin="anonymous"/>

    <style>
        /* ===== VARIABLES DE COLOR ===== */
        :root {
            --color-azul-principal: #070D25;
            --color-azul-principal-translucido: rgba(7, 13, 37, 0.82);
            --color-azul-principal-not-pure: #0b143a;
            --color-azul-principal-not-pure2: #5f6375;
            --color-v-principal: #91DD4E;
            --color-v-hover: #a0e069;
            --color-n-principal: #FB8E37;
            --color-n-hover: #fc9c4f;
            --color-black: #121414;
            --color-black-transparente2: #5c5c5c;
            --color-white-not-pure: rgb(238, 238, 238);
            --color-shadow-principio: #ccd1d1;
            --color-shadow: #e2e6e6;
        }

        /* ===== RESET Y FONDO ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--color-azul-principal);
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        /* Canvas de fondo estilo fluido */
        #fluid {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 0;
            display: block;
            opacity: 0.65;
            /* Opacidad para que se integre bien con el fondo oscuro */
        }

        /* Contenedor del login - centrado y con fondo translúcido */
        .login-box {
            position: relative;
            z-index: 10;
            width: 400px;
            max-width: 92%;
            margin: 5% auto;
            backdrop-filter: blur(12px);
            background: var(--color-azul-principal-translucido);
            border-radius: 24px;
            border: 1px solid rgba(145, 221, 78, 0.25);
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        @media (max-width: 576px) {
            .login-box {
                margin: 15% auto;
            }
        }

        /* Targetas internas */
        .card {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(145, 221, 78, 0.2);
            padding: 1.5rem 1rem 0.5rem;
        }

        .card-body {
            background: transparent;
            padding: 1.8rem;
        }

        /* Estilo de las pestañas */
        .nav-pills .nav-link {
            background: rgba(7, 13, 37, 0.6);
            color: var(--color-white-not-pure);
            font-weight: 600;
            transition: all 0.2s ease;
            border-radius: 40px 40px 0 0;
            margin: 0 2px;
        }

        .nav-pills .nav-link.active {
            background: var(--color-v-principal);
            color: var(--color-azul-principal);
            box-shadow: 0 -2px 8px rgba(145, 221, 78, 0.3);
        }

        .nav-pills .nav-link:not(.active):hover {
            background: rgba(145, 221, 78, 0.25);
            color: white;
        }

        /* Mensaje de bienvenida */
        .login-box-msg {
            color: #eef2ff;
            font-weight: 500;
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 0.95rem;
        }

        /* Input groups con estilo moderno */
        .input-group {
            margin-bottom: 1rem;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(145, 221, 78, 0.3);
            transition: all 0.2s;
        }

        .input-group:focus-within {
            border-color: var(--color-v-principal);
            box-shadow: 0 0 0 2px rgba(145, 221, 78, 0.3);
        }

        .form-floating > .form-control {
            background: transparent;
            border: none;
            color: white;
            padding-top: 1.2rem;
            padding-bottom: 0.4rem;
        }

        .form-floating > .form-control:focus {
            box-shadow: none;
            background: transparent;
            color: white;
        }

        .form-floating > label {
            color: #b0b7d4;
            font-weight: 400;
        }

        .form-control::placeholder {
            color: rgba(200, 200, 220, 0.5);
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: var(--color-v-principal);
            font-size: 1.1rem;
        }

        /* Botones personalizados */
        .btnEnviarFormulario {
            background-color: var(--color-v-principal);
            border: none;
            color: #070D25;
            font-weight: bold;
            padding: 10px 0;
            border-radius: 40px;
            transition: all 0.3s ease;
            font-size: 1rem;
            letter-spacing: 0.5px;
        }

        .btnEnviarFormulario:hover {
            background-color: var(--color-v-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(145, 221, 78, 0.3);
            color: #030617;
        }

        /* Links y extras */
        a {
            color: var(--color-v-principal);
            text-decoration: none;
        }

        a:hover {
            color: var(--color-v-hover);
        }

        /* Alertas adaptadas al tema oscuro */
        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.6);
            color: #ffb3b3;
            border-radius: 20px;
        }

        .invalid-feedback {
            color: #ffa098;
            font-size: 0.75rem;
            margin-left: 0.5rem;
        }

        /* Logo y header */
        .zoom {
            transition: transform 0.2s;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.2));
        }

        .zoom:hover {
            transform: scale(1.02);
        }
    </style>

    <link rel="stylesheet" href="<?= LINKS_PATH . "/css/forexfalcon.css" ?>">
</head>
<body>

    <!-- CANVAS DE FONDO FLUIDO (integración completa) -->
    <canvas id="fluid"></canvas>

    <div class="login-box">
        <div class="card card-outline">
            <div class="card-header text-center">
                <a href="<?= BASE_PATH ?>">
                    <h1 class="mb-0">
                        <img class="zoom" src="<?= LINKS_PATH . "/imagenes/logo.png" ?>" alt="Logo forexfalcon" width="100px">
                    </h1>
                </a>
            </div>

            <!-- PESTAÑAS -->
            <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-pills nav-justified">
                    <li class="nav-item">
                        <a id="tab-login-btn" class="nav-link active rounded-0 rounded-top-start"
                            data-bs-toggle="tab" href="#tab-login">
                            <i class="fa-solid fa-right-to-bracket me-1"></i> Iniciar sesión
                        </a>
                    </li>
                    <li class="nav-item">
                        <a id="tab-registro-btn" class="nav-link rounded-0 rounded-top-end"
                            data-bs-toggle="tab" href="#tab-registro">
                            <i class="fa-solid fa-user-plus me-1"></i> Registrarse
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body login-card-body">
                <div class="tab-content">

                    <!-- ── PESTAÑA LOGIN ───────────────────────────────────── -->
                    <div class="tab-pane fade <?= !isset($mostrarRegistro) || !$mostrarRegistro ? 'show active' : '' ?>" id="tab-login">
                        <p class="login-box-msg">Inicia sesión para comenzar tu sesión.</p>

                        <form action="<?= BASE_PATH . "/login" ?>" method="post">
                            <!-- Usuario -->
                            <div class="input-group mb-1">
                                <div class="form-floating">
                                    <input id="loginEmail" type="text"
                                        class="form-control <?= isset($errores['usuario']) ? 'is-invalid' : '' ?>"
                                        name="usuario" placeholder=""
                                        value="<?= htmlspecialchars($usuario ?? '') ?>"/>
                                    <label for="loginEmail" class="red">Usuario</label>
                                </div>
                                <div class="input-group-text">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            </div>
                            <?php if (isset($errores['usuario'])): ?>
                                <div class="invalid-feedback d-block mb-2">
                                    El campo usuario es obligatorio
                                </div>
                            <?php endif; ?>

                            <!-- Contraseña -->
                            <div class="input-group mb-1">
                                <div class="form-floating">
                                    <input id="loginPassword" type="password"
                                        class="form-control <?= isset($errores['contraseña']) ? 'is-invalid' : '' ?>"
                                        name="contraseña" placeholder=""/>
                                    <label for="loginPassword">Contraseña</label>
                                </div>
                                <div class="input-group-text">
                                    <i class="fa-solid fa-key"></i>
                                </div>
                            </div>
                            <?php if (isset($errores['contraseña'])): ?>
                                <div class="invalid-feedback d-block mb-2">
                                    El campo contraseña es obligatorio
                                </div>
                            <?php endif; ?>

                            <!-- Error de credenciales -->
                            <?php if (isset($errores['login'])): ?>
                                <div class="alert alert-danger mt-2" role="alert">
                                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                                    Usuario o contraseña incorrectos
                                </div>
                            <?php endif; ?>

                            <button type="submit" class="btn btnEnviarFormulario w-100 my-3">
                                Iniciar sesión
                            </button>
                        </form>
                    </div>

                    <!-- ── PESTAÑA REGISTRO ───────────────────────────────── -->
                    <div class="tab-pane fade <?= isset($mostrarRegistro) && $mostrarRegistro ? 'show active' : '' ?>" id="tab-registro">
                        <p class="login-box-msg">Crea tu cuenta y empieza hoy.</p>

                        <form action="<?= BASE_PATH . "/crear_cuenta" ?>" method="post">
                            <!-- Nombre de usuario -->
                            <div class="input-group mb-1">
                                <div class="form-floating">
                                    <input id="regUsuario" type="text"
                                        class="form-control <?= isset($erroresReg['usuario']) ? 'is-invalid' : '' ?>"
                                        name="usuario" placeholder=""
                                        value="<?= htmlspecialchars($regUsuario ?? '') ?>"/>
                                    <label for="regUsuario">Usuario</label>
                                </div>
                                <div class="input-group-text">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            </div>
                            <?php if (isset($erroresReg['usuario'])): ?>
                                <div class="invalid-feedback d-block mb-2">
                                    <?= htmlspecialchars($erroresReg['usuario']) ?>
                                </div>
                            <?php endif; ?>

                            <!-- Email -->
                            <div class="input-group mb-1">
                                <div class="form-floating">
                                    <input id="regEmail" type="email"
                                        class="form-control <?= isset($erroresReg['email']) ? 'is-invalid' : '' ?>"
                                        name="email" placeholder=""
                                        value="<?= htmlspecialchars($regEmail ?? '') ?>"/>
                                    <label for="regEmail">Correo electrónico</label>
                                </div>
                                <div class="input-group-text">
                                    <i class="fa-solid fa-envelope"></i>
                                </div>
                            </div>
                            <?php if (isset($erroresReg['email'])): ?>
                                <div class="invalid-feedback d-block mb-2">
                                    <?= htmlspecialchars($erroresReg['email']) ?>
                                </div>
                            <?php endif; ?>

                            <!-- Contraseña -->
                            <div class="input-group mb-1">
                                <div class="form-floating">
                                    <input id="regPassword" type="password"
                                        class="form-control <?= isset($erroresReg['contraseña']) ? 'is-invalid' : '' ?>"
                                        name="contraseña" placeholder=""/>
                                    <label for="regPassword">Contraseña</label>
                                </div>
                                <div class="input-group-text">
                                    <i class="fa-solid fa-key"></i>
                                </div>
                            </div>
                            <?php if (isset($erroresReg['contraseña'])): ?>
                                <div class="invalid-feedback d-block mb-2">
                                    <?= htmlspecialchars($erroresReg['contraseña']) ?>
                                </div>
                            <?php endif; ?>

                            <!-- Confirmar contraseña -->
                            <div class="input-group mb-1">
                                <div class="form-floating">
                                    <input id="regPasswordConfirm" type="password"
                                        class="form-control <?= isset($erroresReg['confirmar_contraseña']) ? 'is-invalid' : '' ?>"
                                        name="confirmar_contraseña" placeholder=""/>
                                    <label for="regPasswordConfirm">Confirmar contraseña</label>
                                </div>
                                <div class="input-group-text">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                            </div>
                            <?php if (isset($erroresReg['confirmar_contraseña'])): ?>
                                <div class="invalid-feedback d-block mb-2">
                                    <?= htmlspecialchars($erroresReg['confirmar_contraseña']) ?>
                                </div>
                            <?php endif; ?>

                            <!-- Error general de registro -->
                            <?php if (isset($erroresReg['registro'])): ?>
                                <div class="alert alert-danger mt-2" role="alert">
                                    <i class="fa-solid fa-triangle-exclamation me-2"></i>
                                    <?= htmlspecialchars($erroresReg['registro']) ?>
                                </div>
                            <?php endif; ?>

                            <button type="submit" class="btn btnEnviarFormulario w-100 my-3">
                                Crear cuenta
                            </button>
                        </form>
                    </div>

                </div><!-- /tab-content -->
            </div><!-- /card-body -->
        </div><!-- /card -->
    </div><!-- /login-box -->

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    
    <!-- Script completo del fondo fluido (adaptado a los colores de la web) -->
    <script>
        (function() {
            // ---------- FLUID SIMULATION ----------
            // adaptado para fondo de forexfalcon: colores acordes a #070D25, azules y toques verdes/naranja sutiles

            window.addEventListener('load', () => {
                initFluid();
            });

            const initFluid = () => {
                const canvas = document.getElementById('fluid');
                resizeCanvas();

                let config = {
                    SIM_RESOLUTION: 128,
                    DYE_RESOLUTION: 1440,
                    CAPTURE_RESOLUTION: 512,
                    DENSITY_DISSIPATION: 3.2,
                    VELOCITY_DISSIPATION: 2.2,
                    PRESSURE: 0.1,
                    PRESSURE_ITERATIONS: 20,
                    CURL: 3,
                    SPLAT_RADIUS: 0.5,
                    SPLAT_FORCE: 6000,
                    SHADING: true,
                    COLOR_UPDATE_SPEED: 12,
                    PAUSED: false,
                    BACK_COLOR: { r: 0.027, g: 0.05, b: 0.145 }, // #070D25 aproximado
                    TRANSPARENT: true,
                }

                function pointerPrototype() {
                    this.id = -1;
                    this.texcoordX = 0;
                    this.texcoordY = 0;
                    this.prevTexcoordX = 0;
                    this.prevTexcoordY = 0;
                    this.deltaX = 0;
                    this.deltaY = 0;
                    this.down = false;
                    this.moved = false;
                    this.color = [0, 0, 0];
                }

                let pointers = [];
                pointers.push(new pointerPrototype());

                const { gl, ext } = getWebGLContext(canvas);

                if (!ext.supportLinearFiltering) {
                    config.DYE_RESOLUTION = 512;
                    config.SHADING = false;
                }

                function getWebGLContext(canvas) {
                    const params = { alpha: true, depth: false, stencil: false, antialias: false, preserveDrawingBuffer: false };
                    let gl = canvas.getContext('webgl2', params);
                    const isWebGL2 = !!gl;
                    if (!isWebGL2) gl = canvas.getContext('webgl', params) || canvas.getContext('experimental-webgl', params);
                    let halfFloat;
                    let supportLinearFiltering;
                    if (isWebGL2) {
                        gl.getExtension('EXT_color_buffer_float');
                        supportLinearFiltering = gl.getExtension('OES_texture_float_linear');
                    } else {
                        halfFloat = gl.getExtension('OES_texture_half_float');
                        supportLinearFiltering = gl.getExtension('OES_texture_half_float_linear');
                    }
                    gl.clearColor(0.0, 0.0, 0.0, 1.0);
                    const halfFloatTexType = isWebGL2 ? gl.HALF_FLOAT : halfFloat.HALF_FLOAT_OES;
                    let formatRGBA;
                    let formatRG;
                    let formatR;
                    if (isWebGL2) {
                        formatRGBA = getSupportedFormat(gl, gl.RGBA16F, gl.RGBA, halfFloatTexType);
                        formatRG = getSupportedFormat(gl, gl.RG16F, gl.RG, halfFloatTexType);
                        formatR = getSupportedFormat(gl, gl.R16F, gl.RED, halfFloatTexType);
                    } else {
                        formatRGBA = getSupportedFormat(gl, gl.RGBA, gl.RGBA, halfFloatTexType);
                        formatRG = getSupportedFormat(gl, gl.RGBA, gl.RGBA, halfFloatTexType);
                        formatR = getSupportedFormat(gl, gl.RGBA, gl.RGBA, halfFloatTexType);
                    }
                    return { gl, ext: { formatRGBA, formatRG, formatR, halfFloatTexType, supportLinearFiltering } };
                }

                function getSupportedFormat(gl, internalFormat, format, type) {
                    if (!supportRenderTextureFormat(gl, internalFormat, format, type)) {
                        switch (internalFormat) {
                            case gl.R16F: return getSupportedFormat(gl, gl.RG16F, gl.RG, type);
                            case gl.RG16F: return getSupportedFormat(gl, gl.RGBA16F, gl.RGBA, type);
                            default: return null;
                        }
                    }
                    return { internalFormat, format };
                }

                function supportRenderTextureFormat(gl, internalFormat, format, type) {
                    let texture = gl.createTexture();
                    gl.bindTexture(gl.TEXTURE_2D, texture);
                    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.NEAREST);
                    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.NEAREST);
                    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
                    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
                    gl.texImage2D(gl.TEXTURE_2D, 0, internalFormat, 4, 4, 0, format, type, null);
                    let fbo = gl.createFramebuffer();
                    gl.bindFramebuffer(gl.FRAMEBUFFER, fbo);
                    gl.framebufferTexture2D(gl.FRAMEBUFFER, gl.COLOR_ATTACHMENT0, gl.TEXTURE_2D, texture, 0);
                    let status = gl.checkFramebufferStatus(gl.FRAMEBUFFER);
                    return status == gl.FRAMEBUFFER_COMPLETE;
                }

                class Material {
                    constructor(vertexShader, fragmentShaderSource) {
                        this.vertexShader = vertexShader;
                        this.fragmentShaderSource = fragmentShaderSource;
                        this.programs = [];
                        this.activeProgram = null;
                        this.uniforms = [];
                    }
                    setKeywords(keywords) {
                        let hash = 0;
                        for (let i = 0; i < keywords.length; i++) hash += hashCode(keywords[i]);
                        let program = this.programs[hash];
                        if (program == null) {
                            let fragmentShader = compileShader(gl.FRAGMENT_SHADER, this.fragmentShaderSource, keywords);
                            program = createProgram(this.vertexShader, fragmentShader);
                            this.programs[hash] = program;
                        }
                        if (program == this.activeProgram) return;
                        this.uniforms = getUniforms(program);
                        this.activeProgram = program;
                    }
                    bind() { gl.useProgram(this.activeProgram); }
                }

                class Program {
                    constructor(vertexShader, fragmentShader) {
                        this.uniforms = {};
                        this.program = createProgram(vertexShader, fragmentShader);
                        this.uniforms = getUniforms(this.program);
                    }
                    bind() { gl.useProgram(this.program); }
                }

                function createProgram(vertexShader, fragmentShader) {
                    let program = gl.createProgram();
                    gl.attachShader(program, vertexShader);
                    gl.attachShader(program, fragmentShader);
                    gl.linkProgram(program);
                    if (!gl.getProgramParameter(program, gl.LINK_STATUS)) console.trace(gl.getProgramInfoLog(program));
                    return program;
                }

                function getUniforms(program) {
                    let uniforms = [];
                    let uniformCount = gl.getProgramParameter(program, gl.ACTIVE_UNIFORMS);
                    for (let i = 0; i < uniformCount; i++) {
                        let uniformName = gl.getActiveUniform(program, i).name;
                        uniforms[uniformName] = gl.getUniformLocation(program, uniformName);
                    }
                    return uniforms;
                }

                function compileShader(type, source, keywords) {
                    source = addKeywords(source, keywords);
                    const shader = gl.createShader(type);
                    gl.shaderSource(shader, source);
                    gl.compileShader(shader);
                    if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) console.trace(gl.getShaderInfoLog(shader));
                    return shader;
                }

                function addKeywords(source, keywords) {
                    if (keywords == null) return source;
                    let keywordsString = '';
                    keywords.forEach(keyword => { keywordsString += '#define ' + keyword + '\n'; });
                    return keywordsString + source;
                }

                const baseVertexShader = compileShader(gl.VERTEX_SHADER, `
                    precision highp float;
                    attribute vec2 aPosition;
                    varying vec2 vUv;
                    varying vec2 vL;
                    varying vec2 vR;
                    varying vec2 vT;
                    varying vec2 vB;
                    uniform vec2 texelSize;
                    void main () {
                        vUv = aPosition * 0.5 + 0.5;
                        vL = vUv - vec2(texelSize.x, 0.0);
                        vR = vUv + vec2(texelSize.x, 0.0);
                        vT = vUv + vec2(0.0, texelSize.y);
                        vB = vUv - vec2(0.0, texelSize.y);
                        gl_Position = vec4(aPosition, 0.0, 1.0);
                    }
                `);

                const blurVertexShader = compileShader(gl.VERTEX_SHADER, `
                    precision highp float;
                    attribute vec2 aPosition;
                    varying vec2 vUv;
                    varying vec2 vL;
                    varying vec2 vR;
                    uniform vec2 texelSize;
                    void main () {
                        vUv = aPosition * 0.5 + 0.5;
                        float offset = 1.33333333;
                        vL = vUv - texelSize * offset;
                        vR = vUv + texelSize * offset;
                        gl_Position = vec4(aPosition, 0.0, 1.0);
                    }
                `);

                const blurShader = compileShader(gl.FRAGMENT_SHADER, `
                    precision mediump float;
                    precision mediump sampler2D;
                    varying vec2 vUv;
                    varying vec2 vL;
                    varying vec2 vR;
                    uniform sampler2D uTexture;
                    void main () {
                        vec4 sum = texture2D(uTexture, vUv) * 0.29411764;
                        sum += texture2D(uTexture, vL) * 0.35294117;
                        sum += texture2D(uTexture, vR) * 0.35294117;
                        gl_FragColor = sum;
                    }
                `);

                const copyShader = compileShader(gl.FRAGMENT_SHADER, `
                    precision mediump float;
                    precision mediump sampler2D;
                    varying highp vec2 vUv;
                    uniform sampler2D uTexture;
                    void main () { gl_FragColor = texture2D(uTexture, vUv); }
                `);

                const clearShader = compileShader(gl.FRAGMENT_SHADER, `
                    precision mediump float;
                    varying highp vec2 vUv;
                    uniform sampler2D uTexture;
                    uniform float value;
                    void main () { gl_FragColor = value * texture2D(uTexture, vUv); }
                `);

                const colorShader = compileShader(gl.FRAGMENT_SHADER, `
                    precision mediump float;
                    uniform vec4 color;
                    void main () { gl_FragColor = color; }
                `);

                const displayShaderSource = `
                    precision highp float;
                    precision highp sampler2D;
                    varying vec2 vUv;
                    varying vec2 vL;
                    varying vec2 vR;
                    varying vec2 vT;
                    varying vec2 vB;
                    uniform sampler2D uTexture;
                    uniform sampler2D uDithering;
                    uniform vec2 ditherScale;
                    uniform vec2 texelSize;
                    vec3 linearToGamma (vec3 color) {
                        color = max(color, vec3(0));
                        return max(1.055 * pow(color, vec3(0.416666667)) - 0.055, vec3(0));
                    }
                    void main () {
                        vec3 c = texture2D(uTexture, vUv).rgb;
                    #ifdef SHADING
                        vec3 lc = texture2D(uTexture, vL).rgb;
                        vec3 rc = texture2D(uTexture, vR).rgb;
                        vec3 tc = texture2D(uTexture, vT).rgb;
                        vec3 bc = texture2D(uTexture, vB).rgb;
                        float dx = length(rc) - length(lc);
                        float dy = length(tc) - length(bc);
                        vec3 n = normalize(vec3(dx, dy, length(texelSize)));
                        vec3 l = vec3(0.0, 0.0, 1.0);
                        float diffuse = clamp(dot(n, l) + 0.7, 0.7, 1.0);
                        c *= diffuse;
                    #endif
                        float a = max(c.r, max(c.g, c.b));
                        gl_FragColor = vec4(c, a);
                    }
                `;

                const splatShader = compileShader(gl.FRAGMENT_SHADER, `
                    precision highp float;
                    varying vec2 vUv;
                    uniform sampler2D uTarget;
                    uniform float aspectRatio;
                    uniform vec3 color;
                    uniform vec2 point;
                    uniform float radius;
                    void main () {
                        vec2 p = vUv - point.xy;
                        p.x *= aspectRatio;
                        vec3 splat = exp(-dot(p, p) / radius) * color;
                        vec3 base = texture2D(uTarget, vUv).xyz;
                        gl_FragColor = vec4(base + splat, 1.0);
                    }
                `);

                const advectionShader = compileShader(gl.FRAGMENT_SHADER, `
                    precision highp float;
                    varying vec2 vUv;
                    uniform sampler2D uVelocity;
                    uniform sampler2D uSource;
                    uniform vec2 texelSize;
                    uniform vec2 dyeTexelSize;
                    uniform float dt;
                    uniform float dissipation;
                    vec4 bilerp (sampler2D sam, vec2 uv, vec2 tsize) {
                        vec2 st = uv / tsize - 0.5;
                        vec2 iuv = floor(st);
                        vec2 fuv = fract(st);
                        vec4 a = texture2D(sam, (iuv + vec2(0.5, 0.5)) * tsize);
                        vec4 b = texture2D(sam, (iuv + vec2(1.5, 0.5)) * tsize);
                        vec4 c = texture2D(sam, (iuv + vec2(0.5, 1.5)) * tsize);
                        vec4 d = texture2D(sam, (iuv + vec2(1.5, 1.5)) * tsize);
                        return mix(mix(a, b, fuv.x), mix(c, d, fuv.x), fuv.y);
                    }
                    void main () {
                    #ifdef MANUAL_FILTERING
                        vec2 coord = vUv - dt * bilerp(uVelocity, vUv, texelSize).xy * texelSize;
                        vec4 result = bilerp(uSource, coord, dyeTexelSize);
                    #else
                        vec2 coord = vUv - dt * texture2D(uVelocity, vUv).xy * texelSize;
                        vec4 result = texture2D(uSource, coord);
                    #endif
                        float decay = 1.0 + dissipation * dt;
                        gl_FragColor = result / decay;
                    }`, ext.supportLinearFiltering ? null : ['MANUAL_FILTERING']
                );

                const divergenceShader = compileShader(gl.FRAGMENT_SHADER, `
                    precision mediump float;
                    varying highp vec2 vUv;
                    varying highp vec2 vL;
                    varying highp vec2 vR;
                    varying highp vec2 vT;
                    varying highp vec2 vB;
                    uniform sampler2D uVelocity;
                    void main () {
                        float L = texture2D(uVelocity, vL).x;
                        float R = texture2D(uVelocity, vR).x;
                        float T = texture2D(uVelocity, vT).y;
                        float B = texture2D(uVelocity, vB).y;
                        vec2 C = texture2D(uVelocity, vUv).xy;
                        if (vL.x < 0.0) { L = -C.x; }
                        if (vR.x > 1.0) { R = -C.x; }
                        if (vT.y > 1.0) { T = -C.y; }
                        if (vB.y < 0.0) { B = -C.y; }
                        float div = 0.5 * (R - L + T - B);
                        gl_FragColor = vec4(div, 0.0, 0.0, 1.0);
                    }
                `);

                const curlShader = compileShader(gl.FRAGMENT_SHADER, `
                    precision mediump float;
                    varying highp vec2 vUv;
                    varying highp vec2 vL;
                    varying highp vec2 vR;
                    varying highp vec2 vT;
                    varying highp vec2 vB;
                    uniform sampler2D uVelocity;
                    void main () {
                        float L = texture2D(uVelocity, vL).y;
                        float R = texture2D(uVelocity, vR).y;
                        float T = texture2D(uVelocity, vT).x;
                        float B = texture2D(uVelocity, vB).x;
                        float vorticity = R - L - T + B;
                        gl_FragColor = vec4(0.5 * vorticity, 0.0, 0.0, 1.0);
                    }
                `);

                const vorticityShader = compileShader(gl.FRAGMENT_SHADER, `
                    precision highp float;
                    varying vec2 vUv;
                    varying vec2 vL;
                    varying vec2 vR;
                    varying vec2 vT;
                    varying vec2 vB;
                    uniform sampler2D uVelocity;
                    uniform sampler2D uCurl;
                    uniform float curl;
                    uniform float dt;
                    void main () {
                        float L = texture2D(uCurl, vL).x;
                        float R = texture2D(uCurl, vR).x;
                        float T = texture2D(uCurl, vT).x;
                        float B = texture2D(uCurl, vB).x;
                        float C = texture2D(uCurl, vUv).x;
                        vec2 force = 0.5 * vec2(abs(T) - abs(B), abs(R) - abs(L));
                        force /= length(force) + 0.0001;
                        force *= curl * C;
                        force.y *= -1.0;
                        vec2 velocity = texture2D(uVelocity, vUv).xy;
                        velocity += force * dt;
                        velocity = min(max(velocity, -1000.0), 1000.0);
                        gl_FragColor = vec4(velocity, 0.0, 1.0);
                    }
                `);

                const pressureShader = compileShader(gl.FRAGMENT_SHADER, `
                    precision mediump float;
                    varying highp vec2 vUv;
                    varying highp vec2 vL;
                    varying highp vec2 vR;
                    varying highp vec2 vT;
                    varying highp vec2 vB;
                    uniform sampler2D uPressure;
                    uniform sampler2D uDivergence;
                    void main () {
                        float L = texture2D(uPressure, vL).x;
                        float R = texture2D(uPressure, vR).x;
                        float T = texture2D(uPressure, vT).x;
                        float B = texture2D(uPressure, vB).x;
                        float C = texture2D(uPressure, vUv).x;
                        float divergence = texture2D(uDivergence, vUv).x;
                        float pressure = (L + R + B + T - divergence) * 0.25;
                        gl_FragColor = vec4(pressure, 0.0, 0.0, 1.0);
                    }
                `);

                const gradientSubtractShader = compileShader(gl.FRAGMENT_SHADER, `
                    precision mediump float;
                    varying highp vec2 vUv;
                    varying highp vec2 vL;
                    varying highp vec2 vR;
                    varying highp vec2 vT;
                    varying highp vec2 vB;
                    uniform sampler2D uPressure;
                    uniform sampler2D uVelocity;
                    void main () {
                        float L = texture2D(uPressure, vL).x;
                        float R = texture2D(uPressure, vR).x;
                        float T = texture2D(uPressure, vT).x;
                        float B = texture2D(uPressure, vB).x;
                        vec2 velocity = texture2D(uVelocity, vUv).xy;
                        velocity.xy -= vec2(R - L, T - B);
                        gl_FragColor = vec4(velocity, 0.0, 1.0);
                    }
                `);

                const blit = (() => {
                    gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer());
                    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, -1, 1, 1, 1, 1, -1]), gl.STATIC_DRAW);
                    gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, gl.createBuffer());
                    gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array([0, 1, 2, 0, 2, 3]), gl.STATIC_DRAW);
                    gl.vertexAttribPointer(0, 2, gl.FLOAT, false, 0, 0);
                    gl.enableVertexAttribArray(0);
                    return (target, clear = false) => {
                        if (target == null) { gl.viewport(0, 0, gl.drawingBufferWidth, gl.drawingBufferHeight); gl.bindFramebuffer(gl.FRAMEBUFFER, null); }
                        else { gl.viewport(0, 0, target.width, target.height); gl.bindFramebuffer(gl.FRAMEBUFFER, target.fbo); }
                        if (clear) { gl.clearColor(0.0, 0.0, 0.0, 1.0); gl.clear(gl.COLOR_BUFFER_BIT); }
                        gl.drawElements(gl.TRIANGLES, 6, gl.UNSIGNED_SHORT, 0);
                    }
                })();

                let dye, velocity, divergence, curl, pressure;

                const blurProgram = new Program(blurVertexShader, blurShader);
                const copyProgram = new Program(baseVertexShader, copyShader);
                const clearProgram = new Program(baseVertexShader, clearShader);
                const colorProgram = new Program(baseVertexShader, colorShader);
                const splatProgram = new Program(baseVertexShader, splatShader);
                const advectionProgram = new Program(baseVertexShader, advectionShader);
                const divergenceProgram = new Program(baseVertexShader, divergenceShader);
                const curlProgram = new Program(baseVertexShader, curlShader);
                const vorticityProgram = new Program(baseVertexShader, vorticityShader);
                const pressureProgram = new Program(baseVertexShader, pressureShader);
                const gradienSubtractProgram = new Program(baseVertexShader, gradientSubtractShader);
                const displayMaterial = new Material(baseVertexShader, displayShaderSource);

                function initFramebuffers() {
                    let simRes = getResolution(config.SIM_RESOLUTION);
                    let dyeRes = getResolution(config.DYE_RESOLUTION);
                    const texType = ext.halfFloatTexType;
                    const rgba = ext.formatRGBA;
                    const rg = ext.formatRG;
                    const r = ext.formatR;
                    const filtering = ext.supportLinearFiltering ? gl.LINEAR : gl.NEAREST;
                    gl.disable(gl.BLEND);
                    if (dye == null) dye = createDoubleFBO(dyeRes.width, dyeRes.height, rgba.internalFormat, rgba.format, texType, filtering);
                    else dye = resizeDoubleFBO(dye, dyeRes.width, dyeRes.height, rgba.internalFormat, rgba.format, texType, filtering);
                    if (velocity == null) velocity = createDoubleFBO(simRes.width, simRes.height, rg.internalFormat, rg.format, texType, filtering);
                    else velocity = resizeDoubleFBO(velocity, simRes.width, simRes.height, rg.internalFormat, rg.format, texType, filtering);
                    divergence = createFBO(simRes.width, simRes.height, r.internalFormat, r.format, texType, gl.NEAREST);
                    curl = createFBO(simRes.width, simRes.height, r.internalFormat, r.format, texType, gl.NEAREST);
                    pressure = createDoubleFBO(simRes.width, simRes.height, r.internalFormat, r.format, texType, gl.NEAREST);
                }

                function createFBO(w, h, internalFormat, format, type, param) {
                    gl.activeTexture(gl.TEXTURE0);
                    let texture = gl.createTexture();
                    gl.bindTexture(gl.TEXTURE_2D, texture);
                    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, param);
                    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, param);
                    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
                    gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
                    gl.texImage2D(gl.TEXTURE_2D, 0, internalFormat, w, h, 0, format, type, null);
                    let fbo = gl.createFramebuffer();
                    gl.bindFramebuffer(gl.FRAMEBUFFER, fbo);
                    gl.framebufferTexture2D(gl.FRAMEBUFFER, gl.COLOR_ATTACHMENT0, gl.TEXTURE_2D, texture, 0);
                    gl.viewport(0, 0, w, h);
                    gl.clear(gl.COLOR_BUFFER_BIT);
                    let texelSizeX = 1.0 / w;
                    let texelSizeY = 1.0 / h;
                    return { texture, fbo, width: w, height: h, texelSizeX, texelSizeY, attach(id) { gl.activeTexture(gl.TEXTURE0 + id); gl.bindTexture(gl.TEXTURE_2D, texture); return id; } };
                }

                function createDoubleFBO(w, h, internalFormat, format, type, param) {
                    let fbo1 = createFBO(w, h, internalFormat, format, type, param);
                    let fbo2 = createFBO(w, h, internalFormat, format, type, param);
                    return { width: w, height: h, texelSizeX: fbo1.texelSizeX, texelSizeY: fbo1.texelSizeY, get read() { return fbo1; }, set read(value) { fbo1 = value; }, get write() { return fbo2; }, set write(value) { fbo2 = value; }, swap() { let temp = fbo1; fbo1 = fbo2; fbo2 = temp; } };
                }

                function resizeFBO(target, w, h, internalFormat, format, type, param) {
                    let newFBO = createFBO(w, h, internalFormat, format, type, param);
                    copyProgram.bind();
                    gl.uniform1i(copyProgram.uniforms.uTexture, target.attach(0));
                    blit(newFBO);
                    return newFBO;
                }

                function resizeDoubleFBO(target, w, h, internalFormat, format, type, param) {
                    if (target.width == w && target.height == h) return target;
                    target.read = resizeFBO(target.read, w, h, internalFormat, format, type, param);
                    target.write = createFBO(w, h, internalFormat, format, type, param);
                    target.width = w; target.height = h; target.texelSizeX = 1.0 / w; target.texelSizeY = 1.0 / h;
                    return target;
                }

                function updateKeywords() {
                    let displayKeywords = [];
                    if (config.SHADING) displayKeywords.push("SHADING");
                    displayMaterial.setKeywords(displayKeywords);
                }

                updateKeywords();
                initFramebuffers();

                let lastUpdateTime = Date.now();
                let colorUpdateTimer = 0.0;

                function update() {
                    const dt = calcDeltaTime();
                    if (resizeCanvas()) initFramebuffers();
                    updateColors(dt);
                    applyInputs();
                    step(dt);
                    render(null);
                    requestAnimationFrame(update);
                }

                function calcDeltaTime() {
                    let now = Date.now();
                    let dt = (now - lastUpdateTime) / 1000;
                    dt = Math.min(dt, 0.016666);
                    lastUpdateTime = now;
                    return dt;
                }

                function resizeCanvas() {
                    let width = scaleByPixelRatio(canvas.clientWidth);
                    let height = scaleByPixelRatio(canvas.clientHeight);
                    if (canvas.width != width || canvas.height != height) {
                        canvas.width = width;
                        canvas.height = height;
                        return true;
                    }
                    return false;
                }

                function updateColors(dt) {
                    colorUpdateTimer += dt * config.COLOR_UPDATE_SPEED;
                    if (colorUpdateTimer >= 1) {
                        colorUpdateTimer = wrap(colorUpdateTimer, 0, 1);
                        pointers.forEach(p => { p.color = generateColor(); });
                    }
                }

                function applyInputs() {
                    pointers.forEach(p => { if (p.moved) { p.moved = false; splatPointer(p); } });
                }

                function step(dt) {
                    gl.disable(gl.BLEND);
                    curlProgram.bind();
                    gl.uniform2f(curlProgram.uniforms.texelSize, velocity.texelSizeX, velocity.texelSizeY);
                    gl.uniform1i(curlProgram.uniforms.uVelocity, velocity.read.attach(0));
                    blit(curl);
                    vorticityProgram.bind();
                    gl.uniform2f(vorticityProgram.uniforms.texelSize, velocity.texelSizeX, velocity.texelSizeY);
                    gl.uniform1i(vorticityProgram.uniforms.uVelocity, velocity.read.attach(0));
                    gl.uniform1i(vorticityProgram.uniforms.uCurl, curl.attach(1));
                    gl.uniform1f(vorticityProgram.uniforms.curl, config.CURL);
                    gl.uniform1f(vorticityProgram.uniforms.dt, dt);
                    blit(velocity.write); velocity.swap();
                    divergenceProgram.bind();
                    gl.uniform2f(divergenceProgram.uniforms.texelSize, velocity.texelSizeX, velocity.texelSizeY);
                    gl.uniform1i(divergenceProgram.uniforms.uVelocity, velocity.read.attach(0));
                    blit(divergence);
                    clearProgram.bind();
                    gl.uniform1i(clearProgram.uniforms.uTexture, pressure.read.attach(0));
                    gl.uniform1f(clearProgram.uniforms.value, config.PRESSURE);
                    blit(pressure.write); pressure.swap();
                    pressureProgram.bind();
                    gl.uniform2f(pressureProgram.uniforms.texelSize, velocity.texelSizeX, velocity.texelSizeY);
                    gl.uniform1i(pressureProgram.uniforms.uDivergence, divergence.attach(0));
                    for (let i = 0; i < config.PRESSURE_ITERATIONS; i++) {
                        gl.uniform1i(pressureProgram.uniforms.uPressure, pressure.read.attach(1));
                        blit(pressure.write); pressure.swap();
                    }
                    gradienSubtractProgram.bind();
                    gl.uniform2f(gradienSubtractProgram.uniforms.texelSize, velocity.texelSizeX, velocity.texelSizeY);
                    gl.uniform1i(gradienSubtractProgram.uniforms.uPressure, pressure.read.attach(0));
                    gl.uniform1i(gradienSubtractProgram.uniforms.uVelocity, velocity.read.attach(1));
                    blit(velocity.write); velocity.swap();
                    advectionProgram.bind();
                    gl.uniform2f(advectionProgram.uniforms.texelSize, velocity.texelSizeX, velocity.texelSizeY);
                    if (!ext.supportLinearFiltering) gl.uniform2f(advectionProgram.uniforms.dyeTexelSize, velocity.texelSizeX, velocity.texelSizeY);
                    let velocityId = velocity.read.attach(0);
                    gl.uniform1i(advectionProgram.uniforms.uVelocity, velocityId);
                    gl.uniform1i(advectionProgram.uniforms.uSource, velocityId);
                    gl.uniform1f(advectionProgram.uniforms.dt, dt);
                    gl.uniform1f(advectionProgram.uniforms.dissipation, config.VELOCITY_DISSIPATION);
                    blit(velocity.write); velocity.swap();
                    if (!ext.supportLinearFiltering) gl.uniform2f(advectionProgram.uniforms.dyeTexelSize, dye.texelSizeX, dye.texelSizeY);
                    gl.uniform1i(advectionProgram.uniforms.uVelocity, velocity.read.attach(0));
                    gl.uniform1i(advectionProgram.uniforms.uSource, dye.read.attach(1));
                    gl.uniform1f(advectionProgram.uniforms.dissipation, config.DENSITY_DISSIPATION);
                    blit(dye.write); dye.swap();
                }

                function render(target) { gl.blendFunc(gl.ONE, gl.ONE_MINUS_SRC_ALPHA); gl.enable(gl.BLEND); drawDisplay(target); }
                function drawDisplay(target) {
                    let width = target == null ? gl.drawingBufferWidth : target.width;
                    let height = target == null ? gl.drawingBufferHeight : target.height;
                    displayMaterial.bind();
                    if (config.SHADING) gl.uniform2f(displayMaterial.uniforms.texelSize, 1.0 / width, 1.0 / height);
                    gl.uniform1i(displayMaterial.uniforms.uTexture, dye.read.attach(0));
                    blit(target);
                }

                function splatPointer(pointer) { splat(pointer.texcoordX, pointer.texcoordY, pointer.deltaX * config.SPLAT_FORCE, pointer.deltaY * config.SPLAT_FORCE, pointer.color); }
                function splat(x, y, dx, dy, color) {
                    splatProgram.bind();
                    gl.uniform1i(splatProgram.uniforms.uTarget, velocity.read.attach(0));
                    gl.uniform1f(splatProgram.uniforms.aspectRatio, canvas.width / canvas.height);
                    gl.uniform2f(splatProgram.uniforms.point, x, y);
                    gl.uniform3f(splatProgram.uniforms.color, dx, dy, 0.0);
                    gl.uniform1f(splatProgram.uniforms.radius, correctRadius(config.SPLAT_RADIUS / 100.0));
                    blit(velocity.write); velocity.swap();
                    gl.uniform1i(splatProgram.uniforms.uTarget, dye.read.attach(0));
                    gl.uniform3f(splatProgram.uniforms.color, color.r, color.g, color.b);
                    blit(dye.write); dye.swap();
                }

                function correctRadius(radius) { let aspectRatio = canvas.width / canvas.height; if (aspectRatio > 1) radius *= aspectRatio; return radius; }

                // --- Colores personalizados para Forexfalcon (azul/verde/acentos) ---
                function generateColor() {
                    // tonos que combinan: azul profundo, verde neón suave, naranja leve
                    const paleta = [
                        { r: 0.12, g: 0.45, b: 0.25 },   // verde-azulado 
                        { r: 0.09, g: 0.35, b: 0.55 },   // azul medio
                        { r: 0.25, g: 0.65, b: 0.20 },   // verde fresco
                        { r: 0.98, g: 0.55, b: 0.21 },   // naranja tenue
                        { r: 0.05, g: 0.25, b: 0.50 }    // azul profundo
                    ];
                    let c = paleta[Math.floor(Math.random() * paleta.length)];
                    let intensity = 0.08 + Math.random() * 0.12;
                    return { r: c.r * intensity, g: c.g * intensity, b: c.b * intensity };
                }

                function wrap(value, min, max) { let range = max - min; if (range == 0) return min; return (value - min) % range + min; }
                function getResolution(resolution) {
                    let aspectRatio = gl.drawingBufferWidth / gl.drawingBufferHeight;
                    if (aspectRatio < 1) aspectRatio = 1.0 / aspectRatio;
                    let min = Math.round(resolution);
                    let max = Math.round(resolution * aspectRatio);
                    if (gl.drawingBufferWidth > gl.drawingBufferHeight) return { width: max, height: min };
                    else return { width: min, height: max };
                }
                function scaleByPixelRatio(input) { let pixelRatio = window.devicePixelRatio || 1; return Math.floor(input * pixelRatio); }
                function hashCode(s) { if (s.length == 0) return 0; let hash = 0; for (let i = 0; i < s.length; i++) { hash = (hash << 5) - hash + s.charCodeAt(i); hash |= 0; } return hash; }

                // Eventos para mouse/touch (solo efecto decorativo, no interfiere)
                window.addEventListener('mousedown', e => { let pointer = pointers[0]; let posX = scaleByPixelRatio(e.clientX); let posY = scaleByPixelRatio(e.clientY); updatePointerDownData(pointer, -1, posX, posY); clickSplat(pointer); });
                function clickSplat(pointer) { const color = generateColor(); splat(pointer.texcoordX, pointer.texcoordY, (Math.random() - 0.5) * 12, (Math.random() - 0.5) * 12, color); }
                window.addEventListener('mousemove', e => { let pointer = pointers[0]; let posX = scaleByPixelRatio(e.clientX); let posY = scaleByPixelRatio(e.clientY); updatePointerMoveData(pointer, posX, posY, pointer.color); });
                window.addEventListener('touchstart', e => { const touches = e.targetTouches; let pointer = pointers[0]; for (let i = 0; i < touches.length; i++) { let posX = scaleByPixelRatio(touches[i].clientX); let posY = scaleByPixelRatio(touches[i].clientY); updatePointerDownData(pointer, touches[i].identifier, posX, posY); clickSplat(pointer); } });
                window.addEventListener('touchmove', e => { const touches = e.targetTouches; let pointer = pointers[0]; for (let i = 0; i < touches.length; i++) { let posX = scaleByPixelRatio(touches[i].clientX); let posY = scaleByPixelRatio(touches[i].clientY); updatePointerMoveData(pointer, posX, posY, pointer.color); } });
                window.addEventListener('touchend', e => { let pointer = pointers[0]; updatePointerUpData(pointer); });
                function updatePointerDownData(pointer, id, posX, posY) { pointer.id = id; pointer.down = true; pointer.moved = false; pointer.texcoordX = posX / canvas.width; pointer.texcoordY = 1.0 - posY / canvas.height; pointer.prevTexcoordX = pointer.texcoordX; pointer.prevTexcoordY = pointer.texcoordY; pointer.deltaX = 0; pointer.deltaY = 0; pointer.color = generateColor(); }
                function updatePointerMoveData(pointer, posX, posY, color) { pointer.prevTexcoordX = pointer.texcoordX; pointer.prevTexcoordY = pointer.texcoordY; pointer.texcoordX = posX / canvas.width; pointer.texcoordY = 1.0 - posY / canvas.height; pointer.deltaX = correctDeltaX(pointer.texcoordX - pointer.prevTexcoordX); pointer.deltaY = correctDeltaY(pointer.texcoordY - pointer.prevTexcoordY); pointer.moved = Math.abs(pointer.deltaX) > 0 || Math.abs(pointer.deltaY) > 0; pointer.color = color; }
                function updatePointerUpData(pointer) { pointer.down = false; }
                function correctDeltaX(delta) { let aspectRatio = canvas.width / canvas.height; if (aspectRatio < 1) delta *= aspectRatio; return delta; }
                function correctDeltaY(delta) { let aspectRatio = canvas.width / canvas.height; if (aspectRatio > 1) delta /= aspectRatio; return delta; }

                update();
            };
        })();
    </script>
</body>
</html>