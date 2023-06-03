<!DOCTYPE html>
<html lang="pt">

<head>
	<title>Bruno Figueira Barros</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" href="https://figueirabarros.com.br/biblioteca/sistema/sistema/plugins/toastr/toastr.min.css">
	<link type="text/css" rel="stylesheet" href="assets/css/controle.css">
	<link type="text/css" rel="stylesheet" href="assets/css/loading.css">
	<script src="assets/js/loading.js"></script>
	<script async src="https://unpkg.com/es-module-shims@1.3.6/dist/es-module-shims.js"></script>
</head>


<body>
	<div id="loading-container">
		<div class="progress">
			<div id="progress-bar" class="progress-bar"></div>
		</div>
	</div>
	<div id="footer">
		<input type="hidden" id="numero_animacao_final" value="0">
		<input type="hidden" id="numero_animacao" value="0">
		<input id="controle_animacao" type="range" value="0" max="5.000" step="0.00001">
	</div>


	<button type="button" class="btn btn-primary madril" onclick="toggleWindow()">Configuração</button>

	<script>
		function toggleWindow() {
			$('#openWindow').toggleClass('open');
		}
	</script>

	<div id="openWindow" class="open-window">
		<div class="open-window-content">
			<div class="open-window-content-container">
				<h5>Configuração</h5>
				<label>Mudar de cor</label>
				<input type="color" id="color3d">



				<label>Começar animação</label>
				<button id="madril" class="btn btn-primary mobile"><b>Animação</b></button>

				<label>Mudar cor de fundo</label>
				<input type="color" id="colorPicker">

				<div class="form-group">
					<label for="resolution-select">Resolução:</label>
					<select id="resolution-select" class="form-control">
						<option value="0.5">Baixa</option>
						<option value="2" selected>Média</option>
						<option value="4">Alta</option>
					</select>
				</div>

				<div class="form-group">
					<label for="resolution-select">Tamanho do Objeto:</label>
					<input type="range" id="size-range" min="0.01" max="2" step="0.00001" value="0.1" />
				</div>

				<div class="form-group">
					<label for="resolution-select">Velocidade animação:</label>
					<input type="range" id="speed-animation" min="0.01" max="10" step="0.00001" value="0.5" />
				</div>
				<div class="form-group">
					<button type="button" class="btn btn-success" id="camera">Zoom</button>
				</div>
				<div class="form-group">
					<button type="button" class="btn btn-warning" id="rotationfull">360</button>
				</div>
				<button type="button" class="btn btn-danger" onclick="toggleWindow()">Fechar</button>
				<button type="button" class="btn btn-secondary" id="salvar">Salvar</button>
			</div>
		</div>
	</div>




	<script type="importmap">
		{
				"imports": {
					"three": "https://figueirabarros.com.br/biblioteca//build/three.module.js",
					"three/addons/": "https://figueirabarros.com.br/biblioteca/three/jsm/"
				}
			}
	</script>

	<script type="module">
		$("#footer").hide();



		$(document).ready(function() {
			$('#salvar').click(function() {
				// Obter os valores dos inputs
				var color3d = $('#color3d').val();
				var backgroundColor = $('#colorPicker').val();
				var resolution = $('#resolution-select').val();
				var objectSize = $('#size-range').val();
				var animationSpeed = $('#speed-animation').val();

				// Criar um objeto com os dados a serem enviados
				var data = {
					color3d: color3d,
					backgroundColor: backgroundColor,
					resolution: resolution,
					objectSize: objectSize,
					animationSpeed: animationSpeed
				};

				// Enviar a requisição AJAX
				$.ajax({
					url: 'model/save.php',
					method: 'POST',
					data: data,
					success: function(response) {

						toastr.success('Operação realizada com sucesso');
						console.log(response);
					},
					error: function(xhr, status, error) {

						console.error('Erro na requisição AJAX:', error);
					}
				});
			});
		});



		$(document).ready(function() {

			function carregarDados() {
				$.ajax({
					url: 'model/fetch.php',
					type: 'GET',
					dataType: 'json',
					success: function(response) {
						if (response.success) {
							var dados = response.data;

							$("#color3d").val(dados.color3d);
							$("#colorPicker").val(dados.backgroundColor);
							$("#resolution-select").val(dados.resolution);
							$("#size-range").val(dados.objectSize);
							$("#speed-animation").val(dados.animationSpeed);


						} else {
							console.log('Erro ao carregar dados do banco de dados.');
						}
					},
					error: function() {
						console.log('Erro na requisição Ajax.');
					}
				});
			}

			carregarDados();
		});



		import * as THREE from 'three';

		import {
			OrbitControls
		} from 'three/addons/controls/OrbitControls.js';
		import {
			GLTFLoader
		} from 'three/addons/loaders/GLTFLoader.js';
		import {
			RGBELoader
		} from 'three/addons/loaders/RGBELoader.js';
		import {
			ShadowMapViewer
		} from 'three/addons/utils/ShadowMapViewer.js';
		import Stats from 'three/addons/libs/stats.module.js';

		let camera, scene, renderer, gltfObject;
		var clock = new THREE.Clock();
		var raycaster = new THREE.Raycaster();
		var mouse = new THREE.Vector2();
		var isDragging = false;
		var previousMousePosition = {
			x: 0,
			y: 0
		};
		var requestID;
		var mixer;
		var mixer_bateria;
		var mixer_animacao_1;
		var mixer_animacao_2;
		var mixer_animacao_3;
		let theta = 0;
		const pointer = new THREE.Vector2();
		const radius = 100;
		var INTERSECTED;
		var bateria_visible = 1;
		var modelagem_visible = 1;
		var modelagem_visible = 0;
		init();
		render();

		$("#colorPicker").on("input", function(event) {
			colorPicker()
		});
		setTimeout(function() {
			colorPicker()
		}, 1000);

		function colorPicker() {
			scene.background = new THREE.Color($("#colorPicker").val());
		}

		function animateCamera() {

			const finalPosition = new THREE.Vector3(5, 0, 5); // Posição final da câmera (exemplo: zoom)
			const initialPosition = new THREE.Vector3(10, 0, 15);
			const duration = 6;
			const startTime = Date.now();

			const gltfCenter = new THREE.Vector3();
			gltfObject.getWorldPosition(gltfCenter);

			function updateCamera() {

				const elapsedTime = (Date.now() - startTime) / 1000; // Calcular o tempo decorrido desde o início da animação


				const progress = Math.min(elapsedTime / duration, 1); // Calcular a porcentagem de conclusão da animação


				const position = new THREE.Vector3();
				position.lerpVectors(initialPosition, finalPosition, progress); // Interpolar a posição da câmera entre a posição inicial e final


				camera.position.copy(position); // Atualizar a posição da câmera


				camera.lookAt(gltfCenter); // Apontar a câmera para o ponto central do objeto GLTF

				// Verificar se a animação ainda está em progresso
				if (progress < 1) {
					// Solicitar o próximo quadro de animação
					requestAnimationFrame(updateCamera);
				}
			}

			// Iniciar a animação da câmera
			updateCamera();
		}


		function rotationfull() {

			const finalPosition = new THREE.Vector3(0, 0, 5); // Posição final da câmera (exemplo: zoom)


			const gltfCenter = new THREE.Vector3();
			gltfObject.getWorldPosition(gltfCenter);


			const initialPosition = gltfCenter.clone();
			initialPosition.z += 20; // Distância inicial da câmera em relação ao objeto GLTF


			const duration = 6;


			const startTime = Date.now();


			function updateCamera2() {
				const elapsedTime = (Date.now() - startTime) / 1000;
				const progress = Math.min(elapsedTime / duration, 1);

				const position = new THREE.Vector3();
				position.lerpVectors(initialPosition, finalPosition, progress);


				const angle = Math.PI * 2 * progress; // Ângulo em radianos
				const rotationMatrix = new THREE.Matrix4().makeRotationY(angle);
				position.applyMatrix4(rotationMatrix);

				camera.position.copy(position);
				camera.lookAt(gltfCenter);

				if (progress < 1) {
					requestAnimationFrame(updateCamera2);
				}
			}


			// Iniciar a animação da câmera
			updateCamera2();
		}


		$('#camera').click(function() {
			animateCamera();
		});
		$('#rotationfull').click(function() {
			rotationfull();
		});

		function init() {
			const container = document.createElement('div');
			document.body.appendChild(container);
			camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.25, 20);
			camera.position.set(10, -0.9, -5);
			scene = new THREE.Scene();
			scene.background = new THREE.Color(0x252126);

			new RGBELoader()
				.setPath('assets/imagem/')
				.load('background.hdr', function(texture) {
					texture.mapping = THREE.EquirectangularReflectionMapping;

					scene.environment = texture;
					render();

				});
			const manager = new THREE.LoadingManager();
			manager.onLoad = () => animate();
			var position_animation_0;
			var postion_old_0;
			const loader = new GLTFLoader(manager).setPath('assets/3d/');


			loader.load('animation.gltf', function(modelagem) {
				$("#cena1").click(function() {
					controls.reset();
					$("#footer").show();
					$(".mobile").show();
					modelagem.scene.visible = true;
				});
				$("#cena2,#cena3,#cena4").click(function() {
					controls.reset();
					$("#footer").hide();
					$(".mobile").hide();

					modelagem.scene.visible = false;
				});

				mixer = new THREE.AnimationMixer(modelagem.scene);
				var action = mixer.clipAction(modelagem.animations[0]);

				action.play();
				var opacity_animation;


				$("#speed-animation").on("input", function(event) {
					speed_animation();
				});
				setTimeout(function() {
					speed_animation();
				}, 1000);

				function speed_animation() {
					const speed = $("#speed-animation").val();
					action.setEffectiveTimeScale(speed);
				}



				$(".mobile").click(function() {
					if (action.time <= 0.19) {
						action.paused = false;
					} else {

						var timeleft = action.time;
						var downloadTimer = setInterval(function() {
							if (timeleft <= 0) {
								clearInterval(downloadTimer);
								setTimeout(function() {
									action.paused = false;
								}, 400);

							} else {
								$("#controle_animacao").val(timeleft);
								action.time = timeleft;
							}
							timeleft -= parseFloat(0.01 * $("#speed-animation").val());
						}, 10);

					}
				});


				function time_action() {

					opacity_animation = parseFloat(1 - action.time);




					var btn1 = scene.getObjectByName("btn1", true);
					var btn2 = scene.getObjectByName("btn2", true);
					var btn3 = scene.getObjectByName("btn3", true);

					var btn1open = scene.getObjectByName("btn1open", true);
					var btn2open = scene.getObjectByName("btn2open", true);
					var btn3open = scene.getObjectByName("btn3open", true);
					if (action.time >= 5.000) {

						action.paused = true;
						if ($("#numero_animacao").val() == 1 && $("#numero_animacao_final").val() == 0) {
							btn1.visible = true;
						}
						if ($("#numero_animacao").val() == 2 && $("#numero_animacao_final").val() == 0) {
							btn2.visible = true;
						}
						if ($("#numero_animacao").val() == 3 && $("#numero_animacao_final").val() == 0) {
							btn3.visible = true;
						}


					} else if (action.time <= 0.99) {
						btn1.visible = false;
						btn2.visible = false;
						btn3.visible = false;

						btn1open.visible = false;
						btn2open.visible = false;
						btn3open.visible = false;
						$(".container").hide();
					}
					$("#controle_animacao").val(action.time);
					setTimeout(function() {
						time_action();
					}, 200);
				}
				time_action();
				modelagem.scene.traverse(function(node) {
					if (node.isMesh) {
						node.castShadow = false;
						node.receiveShadow = false;
						//node.material.metalness = 1;
						//node.material.depthWrite = false;								
						node.material.opacity = 1;
						node.material.transparent = true;


					}
				});



				$("#controle_animacao").on("change touchmove", function() {
					action.paused = true;
					action.time = parseFloat($("#controle_animacao").val());
				});
				$("#controle_animacao").on("change mousemove", function() {
					action.paused = true;
					action.time = parseFloat($("#controle_animacao").val());
				});
				$("#controle_animacao").mouseout(function() {
					action.paused = true;
				});


				scene.add(modelagem.scene);
				gltfObject = modelagem.scene;
				modelagem.scene.scale.x = 0.1; // SCALE
				modelagem.scene.scale.y = 0.1; // SCALE
				modelagem.scene.scale.z = 0.1; // SCALE			
				modelagem.scene.position.x = 0;
				modelagem.scene.position.y = 0.5;
				modelagem.scene.position.z = 0;

				render();
			});




			var map2 = new THREE.TextureLoader().load("assets/icon/Icone_azul 2.png");
			var material2 = new THREE.SpriteMaterial({
				map: map2,
				color: 0xffffff
			});

			var sprite11 = new THREE.Sprite(material2);
			$("#cena2,#cena3,#cena4").click(function() {
				sprite11.visible = false;
			});
			sprite11.position.x = 1.2;
			sprite11.position.y = 0.2;
			sprite11.position.z = -0.5;
			sprite11.name = "btn1open";
			sprite11.scale.set(0.4, 0.4, 1);
			sprite11.visible = false;
			$("#btn_popup").click(function() {
				sprite11.visible = false;
			});

			scene.add(sprite11);

			var sprite22 = new THREE.Sprite(material2);
			$("#cena2,#cena3,#cena4").click(function() {
				sprite22.visible = false;
			});
			sprite22.position.x = -0.5;
			sprite22.position.y = 1.0;
			sprite22.position.z = -0.5;
			sprite22.name = "btn2open";
			sprite22.scale.set(0.4, 0.4, 1);
			sprite22.visible = false;
			$("#btn_popup2").click(function() {
				sprite22.visible = false;
			});



			scene.add(sprite22);

			var sprite33 = new THREE.Sprite(material2);
			$("#cena2,#cena3,#cena4").click(function() {
				sprite33.visible = false;
			});
			sprite33.position.x = -0.5;
			sprite33.position.y = -1.0;
			sprite33.position.z = -0.5;
			sprite33.name = "btn3open";
			sprite33.scale.set(0.4, 0.4, 1);
			sprite33.visible = false;
			$("#btn_popup3").click(function() {
				sprite33.visible = false;
			});



			scene.add(sprite33);


			var map = new THREE.TextureLoader().load("assets/icon/Icone.png");
			var material = new THREE.SpriteMaterial({
				map: map,
				color: 0xffffff
			});


			var sprite = new THREE.Sprite(material);
			$("#cena2,#cena3,#cena4").click(function() {
				$(".container").hide();
				sprite.visible = false;
			});
			sprite.position.x = 1.2;
			sprite.position.y = 0.2;
			sprite.position.z = -0.5;
			sprite.name = "btn1";
			sprite.scale.set(0.4, 0.4, 1);
			sprite.visible = false;

			$(".mobile").click(function() {
				//	sprite.visible = true;
				$("#footer").show();
			});
			scene.add(sprite);




			var sprite2 = new THREE.Sprite(material);
			$("#cena2,#cena3,#cena4").click(function() {
				sprite2.visible = false;
			});
			sprite2.position.x = -0.5;
			sprite2.position.y = 1.0;
			sprite2.position.z = -0.5;
			sprite2.name = "btn2";
			sprite2.scale.set(0.4, 0.4, 1);
			sprite2.visible = false;

			scene.add(sprite2);


			var sprite3 = new THREE.Sprite(material);
			$("#cena2,#cena3,#cena4").click(function() {
				sprite3.visible = false;
			});
			sprite3.position.x = -0.5;
			sprite3.position.y = -1.0;
			sprite3.position.z = -0.5;
			sprite3.name = "btn3";
			sprite3.scale.set(0.4, 0.4, 1);
			sprite3.visible = false;
			scene.add(sprite3);



			const pixelRatio = window.devicePixelRatio;
			let AA = true;
			if (pixelRatio > 1) {
				AA = false;
			}
			renderer = new THREE.WebGLRenderer({
				//antialias: true,
				preserveDrawingBuffer: false,
				antialias: false,
				powerPreference: "high-performance",
				depth: true,
				alpha: false,
				stencil: false


			});



			renderer.shadowMap.type = THREE.PCFSoftShadowMap; // default THREE.PCFShadowMap
			renderer.setClearColor(0x000000, 0);




			const light = new THREE.DirectionalLight(0xffffff, 1);
			light.position.set(0, 1, 1); //default; light shining from top
			light.castShadow = true; // default false
			scene.add(light);

			const light3 = new THREE.DirectionalLight(0xffffff, 1);
			light3.position.set(2, 2, 2); //default; light shining from top
			light3.castShadow = true; // default false
			scene.add(light3);

			const light4 = new THREE.DirectionalLight(0xffffff, 2);
			light4.position.set(10, 10, -20);
			scene.add(light4);


			light.shadow.mapSize.width = 1024; // default
			light.shadow.mapSize.height = 1024; // default
			light.shadow.camera.near = 0.5; // default
			light.shadow.camera.far = 500; // default

			const light2 = new THREE.AmbientLight(0x404040); // soft white light
			scene.add(light2);





			if (window.innerWidth <= 500) {
				renderer.setPixelRatio(window.devicePixelRatio);

			} else {

				renderer.setPixelRatio(window.devicePixelRatio);

			}



			$("#resolution-select").change(function() {
				resolution_select()
			});
			setTimeout(function() {
				resolution_select();
			}, 1000);

			function resolution_select() {
				const resolution = parseFloat($("#resolution-select").val());
				renderer.setPixelRatio(resolution);
			}


			$("#size-range").on('input', function() {
				size_range()
			});
			setTimeout(function() {
				size_range()
			}, 1000);

			function size_range() {
				var Scene_objet = scene.getObjectByName("Scene", true);
				const scale = parseFloat($("#size-range").val());
				if (Scene_objet) {
					Scene_objet.scale.set(scale, scale, scale);
				}
			}


			renderer.setSize(window.innerWidth, window.innerHeight);
			renderer.toneMapping = THREE.ACESFilmicToneMapping;
			renderer.toneMappingExposure = 1;
			renderer.outputEncoding = THREE.LinearEncoding;
			// renderer.outputEncoding = THREE.sRGBEncoding;
			container.appendChild(renderer.domElement);

			const controls = new OrbitControls(camera, renderer.domElement);
			controls.addEventListener('change', render); // use if there is no animation loop
			controls.minDistance = 2;
			controls.maxDistance = 30;
			controls.target.set(0, 0, -0.2);
			controls.update();


			window.addEventListener('resize', onWindowResize);

			renderer.domElement.addEventListener('click', onClick, false);


		}






		$("#color3d").on("input", function(event) {
			color3d();
		});
		setTimeout(function() {
			color3d();
		}, 2000);

		function color3d() {
			const hexColor = $("#color3d").val();
			const color = new THREE.Color(hexColor);
			const trem = scene.getObjectByName("Object649_paintmat_0", true);
			//	console.log(trem);
			trem.material.color = color;
			render();
		}



		function onClick() {

			event.preventDefault();

			mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
			mouse.y = -(event.clientY / window.innerHeight) * 2 + 1;

			raycaster.setFromCamera(mouse, camera);

			var intersects = raycaster.intersectObjects(scene.children, true);

			var btn1open = scene.getObjectByName("btn1open", true);


			var btn1 = scene.getObjectByName("btn1", true);



			function hide_bullet() {
				$(".container").hide();
				btn1open.visible = false;
			}


			if (intersects.length > 0) {

				console.log('NOME DO OBJETO:', intersects[0].object);

				if (intersects[1].object.name == "btn1open" || intersects[0].object.name == "btn1open" && btn1.visible == true) {

					if (intersects[1].object.name == "btn1open") {
						if (intersects[1].object.visible == true) {
							intersects[1].object.visible = false;
							$("#modal1").hide();
						} else {

							hide_bullet();
							$("#modal1").show();
							intersects[1].object.visible = true;
							intersects[0].object.visible = true;
						}
					}

					if (intersects[0].object.name == "btn1open") {
						if (intersects[0].object.visible == true) {
							intersects[0].object.visible = false;
							$("#modal1").hide();
						} else {
							hide_bullet();
							$("#modal1").show();
							intersects[1].object.visible = true;
							intersects[0].object.visible = true;
						}
					}
				}

				if (intersects[0].object.name == "btn1") {
					$("#modal1").show();
				}

			}
			render();
		}

		var modelagem_modelo = 0;
		$(".mobile").click(function() {

			modelagem_modelo = 1;

		});

		function animate() {
			requestAnimationFrame(animate);
			var delta = clock.getDelta();
			if (mixer_animacao_1) {
				mixer_animacao_1.update(delta);
			}

			if (modelagem_modelo == 1) {
				if (mixer) {
					mixer.update(delta);

				}

			}


			renderer.render(scene, camera);


		}


		function onWindowResize() {

			camera.aspect = window.innerWidth / window.innerHeight;
			camera.updateProjectionMatrix();

			renderer.setSize(window.innerWidth, window.innerHeight);

			render();

		}



		function render() {


			renderer.render(scene, camera);




		}
	</script>

</body>

</html>



<div class="container" id="modal1">
	<h1 class="descricao_modal">TITULO</h1>
	<p class="descricao_modal">TESTE</p>
	<button class="design_btn_popup" id="btn_popup"></button>
</div>



<div class="container" id="modal2">
	<h1 class="descricao_modal"><?php echo $titulo_modal_2; ?></h1>
	<p class="descricao_modal"><?php echo $descricao_modal_2; ?></p>
	<button class="design_btn_popup" id="btn_popup2"></button>
</div>

<div class="container" id="modal3">
	<h1 class="descricao_modal"><?php echo $titulo_modal_3; ?>
	</h1 class="descricao_modal">
	<p class="descricao_modal"><?php echo $descricao_modal_3; ?></p>
	<button class="design_btn_popup" id="btn_popup3"></button>
</div>

<script>
	$("#modal1").hide();
	$("#modal2").hide();
	$("#modal3").hide();
	$("#btn_popup").click(function() {

		$("#modal1").hide();
	});
	$("#btn_popup2").click(function() {

		$("#modal2").hide();
	});
	$("#btn_popup3").click(function() {

		$("#modal3").hide();
	});


	$('.mobile').click(function() {
		$(".mobile").css("background", "");
		$(".mobile").css("border-color", "#fff");
		$(this).css('background', '#003b6a');
		$(this).css('border-color', 'transparent');
	});
	$('#madril').click(function() {
		setTimeout(function() {
			$("#numero_animacao").val("1");
		}, 800);
	});

	$('#tamanho').click(function() {
		setTimeout(function() {
			$("#numero_animacao").val("3");
		}, 300);
	});

	$('.btn_animacao').click(function() {
		$(".btn_animacao").css("background", "");
		$(".btn_animacao").css("border-color", "#fff");
		$(this).css('background', '#003b6a');
		$(this).css('border-color', 'transparent');
	});

	$('#cena1').click(function() {
		$("#numero_animacao_final").val("0");
	});
	$('#cena2').click(function() {

		$("#numero_animacao_final").val("1");

	});
	$('#cena3').click(function() {
		$("#numero_animacao_final").val("1");
	});
	$('#cena4').click(function() {
		$("#numero_animacao_final").val("1");
	});
</script>

<script src="https://figueirabarros.com.br/biblioteca/sistema/sistema/plugins/toastr/toastr.min.js"></script>